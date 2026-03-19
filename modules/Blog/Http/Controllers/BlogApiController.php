<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Blog\Services\Contracts\BlogServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class BlogApiController extends Controller
{
    protected BlogServiceInterface $service;

    public function __construct(BlogServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = $this->service->dataTableData()->with([
            'category'
        ])->where('type', 'blog')->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $data,
        ], Response::HTTP_OK);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $slug): JsonResponse
    {
        $data = $this->service->findBySlug($slug);

        if (! $data) {
            return response()->json([
                'status'  => false,
                'message' => 'Blog not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data'   => $data,
        ], Response::HTTP_OK);
    }


}
