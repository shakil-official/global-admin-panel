<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderImageResource;
use App\Models\SliderImage;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SliderImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return SliderImageResource::collection(SliderImage::query()->orderBy('position')->orderBy('updated_at', 'desc')->get());
    }
}
