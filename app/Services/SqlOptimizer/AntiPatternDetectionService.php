<?php

namespace App\Services\SqlOptimizer;

class AntiPatternDetectionService
{
    public function detect(string $sql, array $analysis): array
    {
        $antiPatterns = [];

        // 1. SELECT *
        if (preg_match('/SELECT\s+\*/i', $sql)) {
            $antiPatterns[] = [
                'title' => 'SELECT * Usage',
                'severity' => 'Medium',
                'description' => 'Retrieving all columns increases I/O load and network traffic. It also prevents the database from using covering indexes.',
                'recommendation' => 'Specify only the columns you actually need.'
            ];
        }

        // 2. Leading Wildcards
        if (preg_match("/LIKE\s+['\"]%/i", $sql)) {
            $antiPatterns[] = [
                'title' => 'Leading Wildcard Search',
                'severity' => 'High',
                'description' => 'A leading wildcard (e.g., LIKE "%term") prevents the database from using B-Tree indexes, forcing a full table scan.',
                'recommendation' => 'Use trailing wildcards if possible (e.g., "term%") or implement Full-Text Search.'
            ];
        }

        // 3. Functions on columns in WHERE
        if (isset($analysis['rawParsed']['WHERE'])) {
            if ($this->hasFunctionsOnColumns($analysis['rawParsed']['WHERE'])) {
                $antiPatterns[] = [
                    'title' => 'Functions on Indexed Columns',
                    'severity' => 'High',
                    'description' => 'Applying functions to columns in the WHERE clause (e.g., YEAR(created_at) = 2025) usually prevents index usage.',
                    'recommendation' => 'Rewrite the condition to use a range (e.g., created_at BETWEEN "2025-01-01" AND "2025-12-31").'
                ];
            }
        }

        // 4. Large Offset
        if (isset($analysis['limit']) && isset($analysis['limit']['offset']) && $analysis['limit']['offset'] > 1000) {
            $antiPatterns[] = [
                'title' => 'Deep Pagination (Large OFFSET)',
                'severity' => 'Medium',
                'description' => 'Large OFFSET values cause the database to read and discard thousands of rows, which becomes progressively slower.',
                'recommendation' => 'Consider using Keyset Pagination (Seek Method) instead of OFFSET.'
            ];
        }

        return $antiPatterns;
    }

    protected function hasFunctionsOnColumns(array $where): bool
    {
        foreach ($where as $item) {
            if (!is_array($item)) {
                continue;
            }

            if (isset($item['expr_type']) && $item['expr_type'] === 'function') {
                // Check if any argument is a column
                if (isset($item['sub_tree']) && is_array($item['sub_tree'])) {
                    foreach ($item['sub_tree'] as $arg) {
                        if (isset($arg['expr_type']) && $arg['expr_type'] === 'colref') {
                            return true;
                        }
                    }
                }
            }
            if (isset($item['sub_tree']) && is_array($item['sub_tree'])) {
                if ($this->hasFunctionsOnColumns($item['sub_tree'])) {
                    return true;
                }
            }
        }
        return false;
    }
}
