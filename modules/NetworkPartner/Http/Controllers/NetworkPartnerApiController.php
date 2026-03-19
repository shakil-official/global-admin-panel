<?php

namespace Modules\NetworkPartner\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\NetworkPartner\Services\Contracts\NetworkPartnerServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class NetworkPartnerApiController extends Controller
{
    protected NetworkPartnerServiceInterface $service;

    public function __construct(NetworkPartnerServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = $this->service->all();

        return response()->json([
            'status' => true,
            'data' => $data,
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $data = $this->service->find($id);

        if (! $data) {
            return response()->json([
                'status'  => false,
                'message' => 'Resource not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data'   => $data,
        ], Response::HTTP_OK);
    }

}
