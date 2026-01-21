<?php

namespace App\Http\Controllers\GlobalSubCategory;

use App\Engine\GlobalSubCategory\Services\Contracts\GlobalSubCategoryServiceInterface;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use App\Models\GlobalCategory;
use App\Models\GlobalSubCategory;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class GlobalSubCategoryController extends Controller
{
    private GlobalSubCategoryServiceInterface $service;

    public function __construct(GlobalSubCategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.global_sub_category.index')->with([
            'title' => 'Service',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('global_sub_category.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'global_sub_category_table',
            'columns' => [
                "Name",
                "Category",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $globalCategories = GlobalCategory::query()
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id => $item->name
                ];
            })
            ->toArray();


        $formConfig = (new FormMaking())
            ->action(route('global_sub_category.store'))
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
                'options' => $globalCategories,
                'value' => old('category_id', ''),
                'required' => true,
                'invalid_feedback' => 'Please select a valid category.',
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'custom',
                    'name' => 'description',
                    'value' => '',
                    'col' => 'col-md-12 mb-3',
                    'callback' => function ($input) {
                        $value = htmlspecialchars($input['value'], ENT_QUOTES, 'UTF-8'); // Escape value for safety

                        return "<div class=\"form-group\">
                                <label for=\"{$input['name']}\">Description</label>
                                <div class=\"snow-editor\"  data-name=\"{$input['name']}\" style=\"height: 300px;\">$value</div>
                                <input type=\"hidden\" name=\"{$input['name']}\" id=\"{$input['name']}\" value=\"$value\">
                                   </div>
                            ";
                    }

                ]
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


        return view('backend.global_sub_category.add')->with([
            'title' => 'Service',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('global_sub_category.index'),
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
            'description' => 'required',
        ]);

        // Adjust positions of existing slider images
        $providedPosition = $request->get('position');

        $response = $this->service->create([
            'name' => $request->get('name'),
            'position' => $providedPosition,
            'global_category_id' => $request->get('category_id'),
            'description' => $request->get('description'),
        ]);

        if ($response) {
            return redirect()->route('global_sub_category.index')->with('success', 'Global Sub Category added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $globalCategories = GlobalCategory::query()
            ->get()
            ->mapWithKeys(function ($item) {

                return [
                    $item->id => $item->name
                ];
            })
            ->toArray();


        $formConfig = (new FormMaking())
            ->action(route('global_sub_category.update', ['id' => $id]))
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
                'options' => $globalCategories,
                'value' => old('category_id', $data->global_category_id),
                'required' => true,
                'invalid_feedback' => 'Please select a valid category.',
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'custom',
                    'name' => 'description',
                    'value' => $data->description,
                    'col' => 'col-md-12 mb-3',
                    'callback' => function ($input) {
                        $value = htmlspecialchars($input['value'], ENT_QUOTES, 'UTF-8'); // Escape value for safety

                        return "<div class=\"form-group\">
                                <label for=\"{$input['name']}\">Description</label>
                                <div class=\"snow-editor\"  data-name=\"{$input['name']}\" style=\"height: 300px;\">$value</div>
                                <input type=\"hidden\" name=\"{$input['name']}\" id=\"{$input['name']}\" value=\"$value\">
                                   </div>
                            ";
                    }

                ]
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update Global Sub Category',
                'col' => 'col-6 mb-3',
                'icon' => 'ri-edit-line',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();


        return view('backend.global_sub_category.edit')->with([
            'title' => 'GlobalSubCategory Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('global_sub_category.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back to List',
                    'url' => route('global_sub_category.index'),
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
            'name' => 'required',
            'position' => 'required|numeric|min:1',
            'category_id' => 'required',
            'description' => 'required',
        ]);

        // Adjust positions if the position is changing
        $globalSubCategory = GlobalSubCategory::query()->findOrFail($id);// Get the current and new positions
        $newPosition = $request->input('position');// If the position remains unchanged, update only the name or other fields


        $globalSubCategory->update([
            'name' => $request->get('name'),
            'global_category_id' => $request->get('category_id'),
            'description' => $request->get('description'),
            'position' => $newPosition,
        ]);

        return redirect()->route('global_sub_category.index')->with('success', 'Sub Category updated successfully.');

    }

    public function delete(Request $request): JsonResponse
    {
        $data = $this->service->delete($request->input('id'));

        if ($data) {
            return response()->json([
                'message' => 'GlobalSubCategory deleted successfully',
                'status_code' => ResponseAlias::HTTP_OK,
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'GlobalSubCategory delete failed',
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
        $data = $this->service->dataTableData()->with([
            'category'
        ])->orderBy('id', 'desc');


        return DataTables::of($data)
            ->filter(function ($query) {
                if ($search = request('search.value')) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('reason', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                }
            })
            ->addColumn('name', function ($data) {
                return $data->name;
            })
            ->editColumn('category', function ($data) {
                return $data->category->name;
            })
            ->addColumn('action', function ($data) {
                $editRoute = route('global_sub_category.edit', $data->id);
                $viewRoute = route('global_sub_category.edit', $data->id); // Optional, replace with actual route if needed
                $deleteRoute = null; // Replace with delete route if needed
                return actionDropdown($data->id, $editRoute, null, $deleteRoute);
            })
            ->rawColumns(['action', 'category'])
            ->toJson();
    }
}
