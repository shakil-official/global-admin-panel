<?php

namespace Modules\Faq\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Faq\Services\Contracts\FaqServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class FaqApiController extends Controller
{
    protected FaqServiceInterface $service;

    public function __construct(FaqServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = $this->service->all();
        $groupedData = [];

        foreach ($data as $faq) {
            $category = $faq->category->title ?? 'General Queries';
            $categoryId = strtolower(str_replace(' ', '_', $category));
            
            // Generate custom ID based on category and position
            $categoryPrefix = match($categoryId) {
                'general_queries' => 'gen',
                'payment_method' => 'pay', 
                'technical_queries' => 'tech',
                'troubleshooting' => 'trouble',
                default => substr($categoryId, 0, 3)
            };
            
            // Get the position of this FAQ within its category
            if (!isset($groupedData[$categoryId])) {
                $groupedData[$categoryId] = [
                    'id' => $categoryId === 'general_queries' ? 'general' : 
                           ($categoryId === 'payment_method' ? 'payment' : 
                           ($categoryId === 'technical_queries' ? 'technical' : $categoryId)),
                    'label' => $category,
                    'items' => []
                ];
            }
            
            $position = count($groupedData[$categoryId]['items']) + 1;
            
            $faqItem = [
                'id' => $categoryPrefix . '-' . $position,
                'question' => $faq->title,
                'answer' => strip_tags($faq->description),
            ];

            $groupedData[$categoryId]['items'][] = $faqItem;
        }

        // Reorder categories to match desired sequence
        $orderedCategories = ['general_queries', 'payment_method', 'technical_queries', 'troubleshooting'];
        $orderedData = [];

        foreach ($orderedCategories as $categoryKey) {
            if (isset($groupedData[$categoryKey])) {
                $orderedData[] = $groupedData[$categoryKey];
            }
        }

        // Add any remaining categories that weren't in the ordered list
        foreach ($groupedData as $categoryKey => $categoryData) {
            if (!in_array($categoryKey, $orderedCategories)) {
                $orderedData[] = $categoryData;
            }
        }

        return response()->json($orderedData, Response::HTTP_OK);
    }




}
