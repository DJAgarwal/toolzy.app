<?php

namespace App\Services\SqlOptimizer;

use PHPSQLParser\PHPSQLParser;

class SqlAnalysisService
{
    protected PHPSQLParser $parser;

    public function __construct()
    {
        $this->parser = new PHPSQLParser();
    }

    public function analyze(string $sql): array
    {
        try {
            $parsed = $this->parser->parse($sql);
            if (!$parsed) {
                return ['error' => 'Could not parse SQL query. Please ensure it is a valid SQL statement.'];
            }

            return [
                'tables' => $this->extractTables($parsed),
                'columns' => $this->extractColumns($parsed),
                'where' => $this->extractWhere($parsed),
                'orderBy' => $this->extractOrderBy($parsed),
                'groupBy' => $this->extractGroupBy($parsed),
                'joins' => $this->extractJoins($parsed),
                'limit' => $this->extractLimit($parsed),
                'rawParsed' => $parsed
            ];
        } catch (\Exception $e) {
            return ['error' => 'Parser Error: ' . $e->getMessage()];
        }
    }

    protected function extractTables(array $parsed): array
    {
        $tables = [];
        if (isset($parsed['FROM'])) {
            foreach ($parsed['FROM'] as $table) {
                if (!isset($table['expr_type'])) {
                    continue;
                }
                if ($table['expr_type'] === 'table') {
                    $tables[] = $table['table'];
                } elseif ($table['expr_type'] === 'subquery') {
                    $tables = array_merge($tables, $this->extractTables($table['sub_tree']));
                }
            }
        }
        return array_unique($tables);
    }

    protected function extractColumns(array $parsed): array
    {
        $columns = [];
        if (isset($parsed['SELECT'])) {
            foreach ($parsed['SELECT'] as $col) {
                $this->findColumnRefs([$col], $columns);
            }
        }
        return array_unique($columns);
    }

    protected function extractWhere(array $parsed): array
    {
        $whereColumns = [];
        if (isset($parsed['WHERE'])) {
            $this->findColumnRefs($parsed['WHERE'], $whereColumns);
        }
        return array_unique($whereColumns);
    }

    protected function extractOrderBy(array $parsed): array
    {
        $orderByColumns = [];
        if (isset($parsed['ORDER'])) {
            foreach ($parsed['ORDER'] as $order) {
                if (isset($order['expr_type']) && $order['expr_type'] === 'colref') {
                    $orderByColumns[] = $order['base_expr'];
                }
            }
        }
        return array_unique($orderByColumns);
    }

    protected function extractGroupBy(array $parsed): array
    {
        $groupByColumns = [];
        if (isset($parsed['GROUP'])) {
            foreach ($parsed['GROUP'] as $group) {
                if (isset($group['expr_type']) && $group['expr_type'] === 'colref') {
                    $groupByColumns[] = $group['base_expr'];
                }
            }
        }
        return array_unique($groupByColumns);
    }

    protected function extractJoins(array $parsed): array
    {
        $joins = [];
        if (isset($parsed['FROM'])) {
            foreach ($parsed['FROM'] as $table) {
                if (isset($table['join_type']) && $table['join_type'] !== 'JOIN') {
                    $joins[] = [
                        'type' => $table['join_type'],
                        'table' => $table['table'],
                        'on' => $table['ref_clause'] ?? null
                    ];
                }
            }
        }
        return $joins;
    }

    protected function extractLimit(array $parsed): ?array
    {
        if (isset($parsed['LIMIT'])) {
            return $parsed['LIMIT'];
        }
        return null;
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
}
