<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('administration.categories.category', []);
    }

    public function indexSub(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('administration.categories.sub-category', []);
    }

    public function list(Request $request): JsonResponse
    {
        $categories = Category::with('parent')->where(['owner_id' => Helpers::owner_id()])->orderBy('id', 'desc');

        if (1 == $request->input('type')){
            $categories->whereNull('parent_id');
        }

        if (2 == $request->input('type')){
            $categories->whereNotNull('parent_id');
        }


        return DataTables::of($categories)
            ->addColumn('parent', function ($category) {
                return $category->parent ? $category->parent->name : '-';
            })
            ->addColumn('action', function ($category) {
                return '<button class="btn btn-sm btn-warning editCategoryBtn" data-id="' . $category->id . '">Edit</button> ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::query()->create([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
            'owner_id' => Helpers::owner_id(),
        ]);

        return response()->json($category);
    }


    public function edit($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // **Check if id and parent_id are the same**
        if ($request->input('parent_id') == $id) {
            return response()->json([
                'message' => 'A category cannot be its own parent.'
            ], 422);
        }

        $category->update([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
        ]);

        return response()->json($category);
    }


    public function getCategories(): JsonResponse
    {
        $categories = Category::query()
            ->select('id', 'name')
            ->where(['owner_id' => Helpers::owner_id()])
            ->whereNull('parent_id')
            ->get();
        return response()->json($categories);
    }

}
