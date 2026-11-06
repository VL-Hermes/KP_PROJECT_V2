<?php

namespace App\Http\Controllers;

use App\Services\LaptopAiAdvisorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiChatController extends Controller
{
    /**
     * Handle incoming AI recommendation requests.
     */
    public function recommend(Request $request, LaptopAiAdvisorService $advisorService): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:2|max:1000',
            'history' => 'nullable|array|max:20',
            'history.*.role' => 'nullable|string|in:user,model,assistant',
            'history.*.text' => 'nullable|string|max:2000',
        ]);

        $message = trim($validated['message']);
        $history = $validated['history'] ?? [];

        $response = $advisorService->getRecommendation($message, $history);

        return response()->json([
            'status' => 'success',
            'reply' => $response['reply'],
            'source' => $response['source'] ?? 'unknown',
        ]);
    }
}

