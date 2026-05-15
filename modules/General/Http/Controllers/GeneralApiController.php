<?php

namespace Modules\General\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\General\Services\Contracts\GeneralServiceInterface;

class GeneralApiController extends Controller
{
    protected GeneralServiceInterface $service;

    public function __construct(GeneralServiceInterface $service)
    {
        $this->service = $service;
    }

    public function show(): JsonResponse
    {
        $data = Cache::remember('general_single_data', now()->addDays(24), function () {

            return $this->service->find(1);
        });

        if (!$data) {

            return response()->json([
                'status' => false,
                'message' => 'General data not found',
            ], 404);
        }

        $data->fav_url = $data->fav
            ? asset($data->fav)
            : null;

        $data->icon_url = $data->icon
            ? asset($data->icon)
            : null;

        $data->btrc_document_file_url = $data->btrc_document_file
            ? asset($data->btrc_document_file)
            : null;

        return response()->json([
            'status' => true,
            'message' => 'General data fetched successfully',
            'data' => $data,
        ]);
    }

}
