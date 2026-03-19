<?php

namespace Modules\Paybill\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Paybill\Services\Contracts\PaybillServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class PaybillApiController extends Controller
{
    protected PaybillServiceInterface $service;

    public function __construct(PaybillServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = $this->service->all();

        // Group by 'method'
        $grouped = [];
        foreach ($data as $item) {
            $method = $item->method;
            if (!isset($grouped[$method])) {
                $grouped[$method] = [];
            }

            $grouped[$method][] = [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'step_no' => $item->step_no,
                'meta' => $item->meta ?? [
                        'qr' => ['qr_file' => null],
                        'bank' => ['account_no' => null, 'branch' => null, 'ifsc' => null],
                        'bkash' => ['merchant_no' => null],
                        'nagad' => ['merchant_no' => null],
                    ],
                'user_id' => $item->user_id,
                'status' => $item->status,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $grouped,
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
            'message' => 'Paybill created successfully',
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
            'message' => 'Paybill updated successfully',
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
            'message' => 'Paybill deleted successfully',
        ], Response::HTTP_OK);
    }
}
