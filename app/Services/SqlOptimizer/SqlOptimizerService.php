<?php

namespace App\Services\SqlOptimizer;

class SqlOptimizerService
{
    protected SqlAnalysisService $analysisService;
    protected IndexRecommendationService $recommendationService;
    protected AntiPatternDetectionService $antiPatternService;
    protected PerformanceScoringService $scoringService;

    public function __construct(
        SqlAnalysisService $analysisService,
        IndexRecommendationService $recommendationService,
        AntiPatternDetectionService $antiPatternService,
        PerformanceScoringService $scoringService
    ) {
        $this->analysisService = $analysisService;
        $this->recommendationService = $recommendationService;
        $this->antiPatternService = $antiPatternService;
        $this->scoringService = $scoringService;
    }

    public function optimize(string $sql): array
    {
        $analysis = $this->analysisService->analyze($sql);
        
        if (isset($analysis['error'])) {
            return ['error' => $analysis['error']];
        }

        $recommendations = $this->recommendationService->getRecommendations($analysis);
        $antiPatterns = $this->antiPatternService->detect($sql, $analysis);
        $scoring = $this->scoringService->calculate($analysis, $antiPatterns);

        return [
            'querySummary' => [
                'tables' => $analysis['tables'],
                'whereColumns' => $analysis['where'],
                'orderByColumns' => $analysis['orderBy'],
                'groupByColumns' => $analysis['groupBy'],
                'joins' => array_column($analysis['joins'], 'table'),
                'hasLimit' => !empty($analysis['limit'])
            ],
            'recommendedIndexes' => $recommendations,
            'antiPatterns' => $antiPatterns,
            'scoring' => $scoring,
            'optimizationSuggestions' => $this->generateSuggestions($recommendations, $antiPatterns)
        ];
    }

    protected function generateSuggestions(array $recommendations, array $antiPatterns): array
    {
        $suggestions = [];
        
        foreach ($recommendations as $rec) {
            $suggestions[] = "Add {$rec['type']} on table '{$rec['table']}' (" . implode(", ", $rec['columns']) . ").";
        }

        foreach ($antiPatterns as $ap) {
            $suggestions[] = $ap['recommendation'];
        }

        return array_unique($suggestions);
    }
}
