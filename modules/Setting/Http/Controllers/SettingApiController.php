<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Setting\Services\Contracts\SettingServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class SettingApiController extends Controller
{
    protected SettingServiceInterface $service;

    public function __construct(SettingServiceInterface $service)
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
     * Store a newly created resource.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $this->service->create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Setting created successfully',
            'data'    => $data,
        ], Response::HTTP_CREATED);
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

    /**
     * Update the specified resource.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $updated = $this->service->update($id, $validated);

        return response()->json([
            'status'  => true,
            'message' => 'Setting updated successfully',
            'data'    => $updated,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json([
            'status'  => true,
            'message' => 'Setting deleted successfully',
        ], Response::HTTP_OK);
    }
}
