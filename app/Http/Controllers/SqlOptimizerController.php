<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SqlOptimizer\SqlOptimizerService;
use Illuminate\Http\JsonResponse;

class SqlOptimizerController extends Controller
{
    protected SqlOptimizerService $optimizerService;

    public function __construct(SqlOptimizerService $optimizerService)
    {
        $this->optimizerService = $optimizerService;
    }

    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'sql' => 'required|string|max:10000',
        ]);

        $result = $this->optimizerService->optimize($request->input('sql'));

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 422);
        }

        return response()->json($result);
    }
}
