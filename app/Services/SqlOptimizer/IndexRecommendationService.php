<?php

namespace App\Services\SqlOptimizer;

class IndexRecommendationService
{
    public function getRecommendations(array $analysis): array
    {
        $recommendations = [];
        $tables = $analysis['tables'] ?? [];

        foreach ($tables as $table) {
            $whereCols = $this->filterByTable($analysis['where'] ?? [], $table);
            $orderByCols = $this->filterByTable($analysis['orderBy'] ?? [], $table);
            $groupByCols = $this->filterByTable($analysis['groupBy'] ?? [], $table);

            // Composite Index Recommendation
            if (!empty($whereCols)) {
                $compositeCols = array_merge($whereCols, $orderByCols);
                $compositeCols = array_unique($compositeCols);
                
                if (count($compositeCols) > 1) {
                    $idxName = "idx_" . strtolower($table) . "_" . implode("_", array_map(fn($c) => $this->cleanCol($c), $compositeCols));
                    $recommendations[] = [
                        'type' => 'Composite Index',
                        'table' => $table,
                        'name' => $idxName,
                        'columns' => $compositeCols,
                        'sql' => "CREATE INDEX {$idxName} ON {$table} (" . implode(", ", $compositeCols) . ");",
                        'why' => "Combines " . implode(", ", $whereCols) . " for filtering and " . implode(", ", $orderByCols) . " for sorting.",
                        'impact' => 'High'
                    ];
                } else {
                    $col = $whereCols[0];
                    $idxName = "idx_" . strtolower($table) . "_" . $this->cleanCol($col);
                    $recommendations[] = [
                        'type' => 'Single Column Index',
                        'table' => $table,
                        'name' => $idxName,
                        'columns' => [$col],
                        'sql' => "CREATE INDEX {$idxName} ON {$table} ({$col});",
                        'why' => "Indexed column '{$col}' is used in the WHERE clause.",
                        'impact' => 'Medium'
                    ];
                }
            }

            // Covering Index recommendation
            $selectCols = $this->filterByTable($analysis['columns'] ?? [], $table);
            if (count($selectCols) > 0 && count($selectCols) < 10 && !empty($whereCols)) {
                $coveringCols = array_unique(array_merge($whereCols, $selectCols));
                $idxName = "idx_cov_" . strtolower($table) . "_" . $this->cleanCol($whereCols[0]);
                $recommendations[] = [
                    'type' => 'Covering Index',
                    'table' => $table,
                    'name' => $idxName,
                    'columns' => $coveringCols,
                    'sql' => "CREATE INDEX {$idxName} ON {$table} (" . implode(", ", $coveringCols) . ");",
                    'why' => "This index contains all columns requested in the SELECT and WHERE clauses, allowing the database to skip reading the actual table data.",
                    'impact' => 'Very High'
                ];
            }
            // JOIN columns recommendation
            foreach (($analysis['joins'] ?? []) as $join) {
                if ($join['table'] === $table && isset($join['on']) && is_array($join['on'])) {
                    $joinCols = [];
                    $this->findColumnRefs($join['on'], $joinCols);
                    $joinCols = $this->filterByTable($joinCols, $table);
                    
                    foreach ($joinCols as $col) {
                        $idxName = "idx_fk_" . strtolower($table) . "_" . $this->cleanCol($col);
                        $recommendations[] = [
                            'type' => 'Foreign Key Index',
                            'table' => $table,
                            'name' => $idxName,
                            'columns' => [$col],
                            'sql' => "CREATE INDEX {$idxName} ON {$table} ({$col});",
                            'why' => "Indexed column '{$col}' is used in a JOIN condition. This significantly speeds up lookups between tables.",
                            'impact' => 'High'
                        ];
                    }
                }
            }
        }

        return $this->deduplicate($recommendations);
    }

    protected function findColumnRefs(array $items, array &$columns): void
    {
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            if (!isset($item['expr_type'])) {
                // If it's a numeric array, it might be a list of expressions
                if (array_is_list($item)) {
                    $this->findColumnRefs($item, $columns);
                }
                continue;
            }

            if ($item['expr_type'] === 'colref') {
                $columns[] = $item['base_expr'];
            }
            if (isset($item['sub_tree']) && is_array($item['sub_tree'])) {
                $this->findColumnRefs($item['sub_tree'], $columns);
            }
        }
    }

    protected function deduplicate(array $recommendations): array
    {
        $unique = [];
        foreach ($recommendations as $rec) {
            $key = $rec['table'] . '|' . implode(',', $rec['columns']);
            if (!isset($unique[$key])) {
                $unique[$key] = $rec;
            }
        }
        return array_values($unique);
    }

    protected function filterByTable(array $columns, string $table): array
    {
        $filtered = array_filter($columns, function($col) use ($table) {
            if (str_contains($col, '.')) {
                return str_starts_with($col, $table . '.') || str_starts_with($col, "`$table`.");
            }
            return true; // Assume it belongs to the table if no table prefix
        });

        return array_values($filtered);
    }

    protected function cleanCol(string $col): string
    {
        $col = str_replace(['`', '"', '[', ']'], '', $col);
        if (str_contains($col, '.')) {
            $parts = explode('.', $col);
            $col = end($parts);
        }
        return strtolower($col);
    }
}
