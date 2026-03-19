<?php

namespace Modules\Package\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Package\Services\Contracts\PackageServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class PackageApiController extends Controller
{
    protected PackageServiceInterface $service;

    public function __construct(PackageServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $packages = $this->service->all();
        $groupedData = [];

        foreach ($packages as $package) {
            $basePrice = (float)$package->base_price;
            $vatPercentage = (float)($package->vat_percentage ?? 5);
            $vatAmount = ($basePrice * $vatPercentage) / 100;
            $totalPrice = $basePrice + $vatAmount;

            $features = [];
            if (!empty($package->features)) {
                $features = is_array($package->features) ? $package->features : json_decode($package->features, true);
            }

            $packageData = [
                'id' => $package->id,
                'title' => $package->title,
                'slug' => $package->slug,
                'image' => $package->image,
                'user_id' => $package->user_id,
                'category' => $package->category,
                'speed' => $package->speed,
                'base_price' => $basePrice,
                'vat_enabled' => $package->vat_enabled,
                'vat_percentage' => $vatPercentage,
                'vat_amount' => $vatAmount,
                'total_price' => $totalPrice,
                'is_popular' => $package->is_popular,
                'features' => $features,
                'short_description' => $package->short_description,
                'description' => $package->description,
                'type' => $package->type,
                'status' => $package->status,
                // Additional formatted fields for API convenience
                'name' => $package->title,
                'basePrice' => $basePrice,
                'vat' => $vatAmount,
                'total' => $totalPrice,
            ];

            if ($package->is_popular) {
                $packageData['popular'] = true;
            }

            $groupedData[$package->category][] = $packageData;
        }

        return response()->json($groupedData, Response::HTTP_OK);
    }
}
