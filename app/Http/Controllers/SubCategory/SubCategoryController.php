<?php

namespace App\Http\Controllers\SubCategory;

use App\Engine\SubCategory\Services\Contracts\SubCategoryServiceInterface;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
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
        return view('backend.subcategory.index')->with([
            'title' => 'Sub Category',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('subcategory.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'subcategory_table',
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
            ->action(route('subcategory.store'))
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

        return view('backend.subcategory.add')->with([
            'title' => 'SubCategory Add',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('subcategory.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'formConfig' => $formConfig
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'position' => 'required|numeric|min:1',
            'category_id' => 'required',
        ]);

        $response = $this->service->create($request->all());

        if ($response) {
            return redirect()->route('subcategory.index')->with('success', 'Sub Category added successfully.');
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
            ->action(route('subcategory.update', ['id' => $id]))
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
                'class' => 'btn btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.subcategory.edit')->with([
            'title' => 'SubCategory Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('subcategory.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back to List',
                    'url' => route('subcategory.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-danger',
                ],
            ],
            'formConfig' => $formConfig,
        ]);
    }

    /**
     * @throws ValidationException
     */
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
            return redirect()->route('subcategory.index')->with('success', 'SubCategory updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function delete(Request $request): JsonResponse
    {
        $data = $this->service->delete($request->input('id'));

        if ($data) {
            return response()->json([
                'message' => 'SubCategory deleted successfully',
                'status_code' => ResponseAlias::HTTP_OK,
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'SubCategory delete failed',
            'status_code' => ResponseAlias::HTTP_BAD_REQUEST,
            'data' => []
        ], ResponseAlias::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
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
            ->editColumn('status', function ($data) {
                return $data->status;
            })
            ->addColumn('action', function ($data) {
                $editRoute = route('subcategory.edit', $data->id);
                $viewRoute = route('subcategory.edit', $data->id); // Optional, replace with actual route if needed
                $deleteRoute = null;
                return actionDropdown($data->id, $editRoute, $viewRoute, $deleteRoute);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
