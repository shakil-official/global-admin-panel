<?php

namespace Modules\SubCategory\Http\Controllers;


use Modules\Category\Models\Category;
use Modules\SubCategory\Services\Contracts\SubCategoryServiceInterface;

use Modules\SubCategory\Models\SubCategory;
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

class SubCategoryController extends Controller
{
    private SubCategoryServiceInterface $service;

    public function __construct(SubCategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('SubCategory::sub-category.index')->with([
            'title' => 'Sub Category',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('sub-category.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'sub-category_table',
            'columns' => [
                "Name",
                "Status",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $categories = Category::query()
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => $item->name
                ];
            })
            ->toArray();

        $formConfig = (new FormMaking())
            ->action(route('sub-category.store'))
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Name',
                    'col' => 'col-md-12 mb-3',
                    'value' => '',
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
                'type' => 'select',
                'name' => 'category_id',
                'label' => 'Category',
                'col' => 'col-md-12 mb-3',
                'options' => $categories,
                'value' => old('category_id', ''),
                'required' => true,
                'invalid_feedback' => 'Please select a valid category.',
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Add new sub category',
                'col' => 'col-12 mb-3',
                'icon' => 'ri-add-line',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('SubCategory::sub-category.add')
            ->with([
                'title' => 'Add',
                'buttons' => [
                    [
                        'label' => 'Back',
                        'url' => route('sub-category.index'),
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
            'name' => 'required',
            'position' => 'required|numeric|min:1',
            'category_id' => 'required',
        ]);

        $response = $this->service->create($request->all());

        if ($response) {
            return redirect()->route('sub-category.index')
                ->with('success', 'Sub Category added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $categories = Category::query()
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => $item->name
                ];
            })
            ->toArray();

        $formConfig = (new FormMaking())
            ->action(route('sub-category.update', ['id' => $id]))
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Name',
                    'col' => 'col-md-12 mb-3',
                    'value' => old('name', $data->name),
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
                'type' => 'select',
                'name' => 'category_id',
                'label' => 'Category',
                'col' => 'col-md-12 mb-3',
                'options' => $categories,
                'value' => old('category_id', $data->category_id),
                'required' => true,
                'invalid_feedback' => 'Please select a valid category.',
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update Sub Category',
                'col' => 'col-6 mb-3',
                'icon' => 'ri-edit-line',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('SubCategory::sub-category.edit')->with([
            'title' => 'Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('sub-category.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back',
                    'url' => route('sub-category.index'),
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
                'required', Rule::unique((new SubCategory)->getTable())->ignore($id),
            ],
            'position' => 'required|numeric|min:1',
            'category_id' => 'required',
        ]);

        $response = $this->service->update($id, $request->all());

        if ($response) {
            return redirect()->route('sub-category.index')
                ->with('success', 'SubCategory updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));

        return response()->json([
            'message' => 'SubCategory deleted successfully',
            'status_code' => ResponseAlias::HTTP_OK,
        ]);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $editRoute = route('sub-category.edit', $data->id);
                $viewRoute = null; // optional
                return actionDropdown($data->id, $editRoute, $viewRoute);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
