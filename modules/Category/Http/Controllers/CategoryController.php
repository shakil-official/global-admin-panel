<?php

namespace Modules\Category\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Modules\Category\Services\Contracts\CategoryServiceInterface;

use Modules\Category\Models\Category;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    private CategoryServiceInterface $service;

    public function __construct(CategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Category::category.index')->with([
            'title' => 'Category',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('category.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'category_table',
            'columns' => [
                "Name",
                "Status",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('category.store')) // Replace with your form action URL
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Name',
                    'col' => 'col-md-12 mb-3',
                    'value' => old('name', ''),
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'number',
                    'name' => 'position',
                    'label' => 'Position',
                    'col' => 'col-md-12 mb-3',
                    'value' => old('position', ''),
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Add Category',
                'col' => 'col-12 mb-3',
                'icon' => 'ri-add-line',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('Category::category.add')
            ->with([
                'title' => 'Add',
                'buttons' => [
                    [
                        'label' => 'Back',
                        'url' => route('category.index'),
                        'icon' => 'ri-corner-down-right-fill',
                        'classes' => 'btn-sm btn-outline-danger',
                    ],
                ],
                'formConfig' => $formConfig
            ]);

    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:categories,name',
            'position' => 'required|numeric',
        ]);

        // Retrieve the provided position
        $providedPosition = $request->input('position');

        // Adjust positions of existing categories
        DB::table('categories')
            ->where('position', '>=', $providedPosition)
            ->increment('position');

        // Add the new category with the provided position
        $data = $request->all();
        $data['position'] = $providedPosition;

        $response = $this->service->create($data);

        if ($response) {
            return redirect()->route('category.index')
                ->with('success', 'Category added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('category.update', ['id' => $id])) // Replace with your form action URL
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Name',
                    'col' => 'col-md-12 mb-3',
                    'value' => $data->name,
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'number',
                    'name' => 'position',
                    'label' => 'Position',
                    'col' => 'col-md-12 mb-3',
                    'value' => old('position', $data->position),
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update Category',
                'icon' => 'ri-edit-line',
                'col' => 'col-6 mb-3',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('Category::category.edit')->with([
            'title' => 'Category',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('category.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back',
                    'url' => route('category.index'),
                    'icon' => 'ri-corner-down-right-fill',
                    'classes' => 'btn-sm btn-outline-danger',
                ],
            ],
            'formConfig' => $formConfig,
        ]);

    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique((new Category)->getTable())->ignore($id),
            ],
            'position' => 'required|numeric',
        ]);

        try {
            $category = Category::query()->findOrFail($id);// Get the current and new positions
            $currentPosition = $category->position;
            $newPosition = $request->input('position');// If the position remains unchanged, update only the name or other fields
            if ($currentPosition == $newPosition) {
                $category->update($request->all());
                return redirect()->route('category.index')->with('success', 'Category updated successfully.');
            }// Adjust positions of other categories


            if ($newPosition < $currentPosition) {
                // Shift categories down (increment position) between the new and current positions
                DB::table('categories')
                    ->where('id', '!=', $id)
                    ->whereBetween('position', [$newPosition, $currentPosition - 1])
                    ->increment('position');
            } elseif ($newPosition > $currentPosition) {
                // Shift categories up (decrement position) between the current and new positions
                DB::table('categories')
                    ->where('id', '!=', $id)
                    ->whereBetween('position', [$currentPosition + 1, $newPosition])
                    ->decrement('position');
            }// Update the category's position and other fields
            $category->update(array_merge($request->all(), ['position' => $newPosition]));


            return redirect()->route('category.index')
                ->with('success', 'Category updated successfully.');


        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function delete(Request $request): JsonResponse
    {
        $data = $this->service->delete($request->input('id'));

        if ($data) {
            return response()->json([
                'message' => 'Category delete successfully',
                'status_code' => ResponseAlias::HTTP_OK,
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'Category delete failed',
            'status_code' => ResponseAlias::HTTP_BAD_REQUEST,
            'data' => []
        ], ResponseAlias::HTTP_BAD_REQUEST);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->orderBy('id', 'desc');


        return DataTables::of($data)
            ->filter(function ($query) {
                if (request()->has('name') && !is_null(request('name'))) {
                    $query->where('name', 'like', '%' . request('name') . '%');
                }
            }, true)
            ->addColumn('name', function ($data) {
                return $data->name;
            })
            ->addColumn('position', function ($data) {
                return $data->position;
            })
            ->editColumn('status', function ($data) {
                return $data->status;
            })
            ->addColumn('action', function ($data) {
                $editRoute = route('category.edit', $data->id);

                return actionDropdown($data->id, $editRoute);
            })
            ->rawColumns(['action']) // Allow HTML in the 'action' column
            ->toJson();
    }
}
