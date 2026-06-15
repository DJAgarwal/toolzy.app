<?php

namespace App\Services\SqlOptimizer;

class PerformanceScoringService
{
    public function calculate(array $analysis, array $antiPatterns): array
    {
        $complexityScore = 0;
        $riskScore = 0;

        // Complexity factors
        $complexityScore += count($analysis['tables'] ?? []) * 10;
        $complexityScore += count($analysis['joins'] ?? []) * 15;
        $complexityScore += count($analysis['where'] ?? []) * 5;
        $complexityScore += (!empty($analysis['groupBy'] ?? [])) ? 20 : 0;
        $complexityScore += (!empty($analysis['orderBy'] ?? [])) ? 10 : 0;

        // Risk factors
        foreach ($antiPatterns as $ap) {
            if ($ap['severity'] === 'High') $riskScore += 40;
            if ($ap['severity'] === 'Medium') $riskScore += 20;
            if ($ap['severity'] === 'Low') $riskScore += 5;
        }

        if (empty($analysis['where'] ?? [])) {
            $riskScore += 30; // High risk if no where clause (full table scan)
        }

        $complexityScore = min(100, $complexityScore);
        $riskScore = min(100, $riskScore);

        return [
            'complexity' => [
                'score' => $complexityScore,
                'level' => $this->getLevel($complexityScore)
            ],
            'performanceRisk' => [
                'score' => $riskScore,
                'level' => $this->getRiskLevel($riskScore)
            ]
        ];
    }

    protected function getLevel(int $score): string
    {
        if ($score < 30) return 'Simple';
        if ($score < 60) return 'Moderate';
        if ($score < 85) return 'Complex';
        return 'Very Complex';
    }

    protected function getRiskLevel(int $score): string
    {
        if ($score < 25) return 'Low';
        if ($score < 55) return 'Medium';
        if ($score < 85) return 'High';
        return 'Critical';
    }
}
