<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CookieConsent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CookieConsentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'accepted' => 'required|boolean',
            ]);

            CookieConsent::query()->create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'ip' => $request->ip(),
                'accepted' => $request->accepted,
            ]);

            return response()->json(['message' => 'Consent recorded successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to record consent: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to record consent'], 500);
        }
    }

}
