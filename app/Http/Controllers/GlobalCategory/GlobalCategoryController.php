<?php

namespace App\Http\Controllers\GlobalCategory;

use App\Engine\GlobalCategory\Services\Contracts\GlobalCategoryServiceInterface;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use App\Models\GlobalCategory;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class GlobalCategoryController extends Controller
{
    private GlobalCategoryServiceInterface $service;

    public function __construct(GlobalCategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.global_category.index')->with([
            'title' => 'Service Type',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('global_category.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'global_category_table',
            'columns' => [
                "Name",
                "Position",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('global_category.store'))
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
                'type' => 'submit',
                'value' => 'Add new category',
                'col' => 'col-12 mb-3',
                'icon' => 'ri-add-line',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.global_category.add')->with([
            'title' => 'Add',
            'buttons' => [
                [
                    'label' => 'Back',
                    'url' => route('global_category.index'),
                    'icon' => 'ri-corner-down-right-fill',
                    'classes' => 'btn-sm btn-outline-danger',
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
        ]);

        // Adjust positions of existing slider images
        $providedPosition = $request->get('position');



        $response = $this->service->create([
            'name' => $request->get('name'),
            'position' => $providedPosition,
        ]);

        if ($response) {
            return redirect()->route('global_category.index')->with('success', 'Category added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);


        $formConfig = (new FormMaking())
            ->action(route('global_category.update', ['id' => $id]))
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
                'col' => 'col-6 mb-3',
                'icon' => 'ri-edit-line',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.global_category.edit')->with([
            'title' => 'Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('global_category.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back to List',
                    'url' => route('global_category.index'),
                    'icon' => 'ri-corner-down-right-fill',
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
        ]);

        // Adjust positions if the position is changing
        $sliderImage = GlobalCategory::query()->findOrFail($id);// Get the current and new positions


        $sliderImage->update([
            'name' => $request->get('name'),
            'position' => $request->get('position'),
        ]);

        return redirect()->route('global_category.index')->with('success', 'GlobalCategory updated successfully.');
    }

    public function delete(Request $request): JsonResponse
    {
        $data = $this->service->delete($request->input('id'));

        if ($data) {
            return response()->json([
                'message' => 'GlobalCategory deleted successfully',
                'status_code' => ResponseAlias::HTTP_OK,
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'GlobalCategory delete failed',
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
                if ($search = request('search.value')) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                }
            })
            ->addColumn('id', fn($data) => $data->id)
            ->addColumn('name', fn($data) => $data->name)
            ->addColumn('position', fn($data) => $data->position)

            ->addColumn('action', function ($data) {
                $editRoute = route('global_category.edit', $data->id);
                $deleteRoute = null; // Replace with delete route if needed
                return actionDropdown($data->id, $editRoute, null, $deleteRoute);
            })
            ->rawColumns(['action', 'reason'])
            ->toJson();
    }

    public function list(Request $request)
    {
        $id = $request->get('id');

        $query = GlobalCategory::with('subCategories');

        if ($id) {
            $query->where('id', $id);
        }

        $categories = $query->orderBy('position')->get();

        // map category -> React style object
        $mapped = $categories->map(function ($category, $index) {
            return [
                "id" => $category->id,
                "icon" => $this->mapIcon($category->id), // dynamic icon mapping
                "letter" => strtoupper(substr($category->name, 0, 1)), // প্রথম অক্ষর
                "title" => $category->name,
                "description" => $category->description,
                "examples" => $category->subCategories->pluck('name')->toArray(), // sub_categories
                "color" => $this->mapColor($index)["color"],
                "bgColor" => $this->mapColor($index)["bgColor"],
                "sub_category" => $category->subCategories,
            ];
        });

        return response()->json([
            "success" => true,
            "data" => $mapped
        ]);
    }

    private function mapColor($index): array
    {
        $colors = [
            ["color" => "from-red-500 to-red-600", "bgColor" => "bg-red-50"],
            ["color" => "from-orange-500 to-orange-600", "bgColor" => "bg-orange-50"],
            ["color" => "from-yellow-500 to-yellow-600", "bgColor" => "bg-yellow-50"],
            ["color" => "from-green-500 to-green-600", "bgColor" => "bg-green-50"],
            ["color" => "from-blue-500 to-blue-600", "bgColor" => "bg-blue-50"],
            ["color" => "from-indigo-500 to-indigo-600", "bgColor" => "bg-indigo-50"],
            ["color" => "from-teal-500 to-teal-600", "bgColor" => "bg-teal-50"],
            ["color" => "from-purple-500 to-purple-600", "bgColor" => "bg-purple-50"],
            ["color" => "from-gray-500 to-gray-600", "bgColor" => "bg-gray-50"],
        ];

        return $colors[$index % count($colors)];
    }

    private function mapIcon($id): string
    {
        // এখানে আপনি চাইলে DB field অনুযায়ী বা ID অনুযায়ী icon return করতে পারেন
        // আপাতত placeholder
        return "Shield";
    }


}
