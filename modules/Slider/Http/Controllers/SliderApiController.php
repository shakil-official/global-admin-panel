<?php

namespace Modules\Slider\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Slider\Services\Contracts\SliderServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class SliderApiController extends Controller
{
    protected SliderServiceInterface $service;

    public function __construct(SliderServiceInterface $service)
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

}
