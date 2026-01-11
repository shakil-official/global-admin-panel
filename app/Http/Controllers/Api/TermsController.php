<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TermsAndCondition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    /**
     * Fetch terms content.
     *
     * @return JsonResponse
     */
    public function getTerms(): JsonResponse
    {
        // Example terms content (you can load this from a database or file)
        $term = TermsAndCondition::query()->latest()->first();
        return response()->json([
            'htmlContent' => $term->description ?? 'No terms available.',
        ], 200);

    }

    public function store()
    {
        dd("ss");

    }

}
