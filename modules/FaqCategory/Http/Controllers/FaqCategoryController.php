<?php

namespace Modules\FaqCategory\Http\Controllers;


use Modules\FaqCategory\Services\Contracts\FaqCategoryServiceInterface;

use Modules\FaqCategory\Models\FaqCategory;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class FaqCategoryController extends Controller
{
    private FaqCategoryServiceInterface $service;

    public function __construct(FaqCategoryServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('FaqCategory::faq-category.index')->with([
            'title' => 'Faq Category',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('faq-category.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'faq-category_table',
            'columns' => [
                "Title",
                "Status",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('faq-category.store'))
            ->enctype('multipart/form-data')
            ->method('POST')
            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Faq Category Title',
                    'value' => old('title'),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()


            // Status
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'radio',
                    'name' => 'status',
                    'label' => 'Select status',
                    'options' => [
                        [
                            'value' => 'active',
                            'label' => 'Active',
                            'checked' => 'active'
                        ],
                        [
                            'value' => 'inactive',
                            'label' => 'Inactive',
                            'checked' => ''
                        ],
                    ],
                    'col' => 'col-6 mb-3',
                    'required' => true,
                    'invalid_feedback' => 'More example invalid feedback text'
                ]
            ])
            ->endRow()

            // Submit
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Add FaqCategory',
                'icon' => 'ri-save-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-12 mb-3',
            ])
            ->endRow()
            ->build();

        return view('FaqCategory::faq-category.add')
            ->with([
                'title' => 'Faq Category Add',
                'buttons' => [
                    [
                        'label' => 'Back',
                        'url' => route('faq-category.index'),
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
            'title' => 'required|unique:faq_categories,title',
            'status' => 'required|in:active,inactive',

        ]);

        $data = $request->only(['title', 'status',]);

        $data['user_id'] = auth()->user()->id;

        $response = $this->service->create($data);

        if ($response) {
            return redirect()->route('faq-category.index')
                ->with('success', 'Faq Category added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('faq-category.update', ['id' => $id]))
            ->enctype('multipart/form-data')
            ->method('POST')

            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Faq Category Title',
                    'value' => old('title', $data->title),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()

            // Status
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'radio',
                    'name' => 'status',
                    'label' => 'Status',
                    'options' => [
                        [
                            'value' => 'active',
                            'label' => 'Active',
                            'checked' => $data->status === 'active',
                        ],
                        [
                            'value' => 'inactive',
                            'label' => 'Inactive',
                            'checked' => $data->status === 'inactive',
                        ],
                    ],
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ],
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Submit form',
                'col' => 'col-6 mb-3',
                'class' => 'btn btn-sm btn-primary',
                'icon' => 'ri-edit-line',
            ])
            ->endRow()
            ->build();

        return view('FaqCategory::faq-category.edit')->with([
            'title' => 'Faq Category Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('faq-category.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back',
                    'url' => route('faq-category.index'),
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
            'title' => [
                'required',
                Rule::unique((new FaqCategory)->getTable())->ignore($id),
            ],

            'status' => 'required|in:active,inactive',
        ]);

        $data = $this->service->find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data not found.');
        }

        $data = $request->only(['title',  'status',]);



        $data['user_id'] = auth()->user()->id;

        $response = $this->service->update($id, $data);


        if ($response) {
            return redirect()->route('faq-category.index')
                ->with('success', 'FaqCategory updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));

        return response()->json([
            'message' => 'Faq Category deleted successfully',
            'status_code' => ResponseAlias::HTTP_OK,
        ]);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('image', function ($data) {
                $imagePath = isset($data->image) ? asset($data->image) : null; // Generate the URL or leave it null
                return $imagePath
                    ? '<img src="' . $imagePath . '" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;">'
                    : 'No Image'; // Return image tag or fallback text
            })
            ->addColumn('action', function ($data) {
                $editRoute = route('faq-category.edit', $data->id);
                $viewRoute = null; // optional
                return actionDropdown($data->id, $editRoute, $viewRoute);
            })
            ->rawColumns(['action', 'image'])
            ->toJson();
    }
}
