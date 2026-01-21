<?php

namespace Modules\Product\Http\Controllers;


use Modules\Product\Services\Contracts\ProductServiceInterface;

use Modules\Product\Models\Product;
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

class ProductController extends Controller
{
    private ProductServiceInterface $service;

    public function __construct(ProductServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Product::product.index')->with([
            'title' => 'Products',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('product.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'product_table',
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
                    ->action(route('product.store'))
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
                            'type' => 'radio',
                            'name' => 'status',
                            'label' => 'Select status',
                            'options' => [
                                ['value' => 'active', 'label' => 'Active', 'checked' => 'active'],
                                ['value' => 'inactive', 'label' => 'Inactive', 'checked' => ''],
                            ],
                            'col' => 'col-6 mb-3',
                            'required' => true,
                            'invalid_feedback' => 'More example invalid feedback text',
                        ],
                    ])
                    ->endRow()
                    ->startRow()
                    ->addInput([
                        'type' => 'submit',
                        'value' => 'Submit form',
                        'col' => 'col-12 mb-3',
                        'class' => 'btn btn-sm btn-primary',
                    ])
                    ->endRow()
                    ->build();

        return view('Product::product.add')
        ->with([
                'title' => 'Product Add',
                'buttons' => [
                    [
                       'label' => 'Back',
                        'url' => route('product.index'),
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
            'name' => 'required|unique:products,name',
            'status' => 'required',
        ]);

        $response = $this->service->create($request->all());

        if ($response) {
            return redirect()->route('product.index')
                ->with('success', 'Product added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
       $data = $this->service->find($id);

              $formConfig = (new FormMaking())
                  ->action(route('product.update', ['id' => $id]))
                  ->method('POST')
                  ->startRow()
                  ->addFormFields([
                      [
                          'type' => 'text',
                          'name' => 'name',
                          'label' => 'Name',
                          'col' => 'col-md-6 mb-3',
                          'value' => $data->name,
                          'required' => true,
                          'validation_feedback' => 'Looks good!',
                      ]
                  ])
                  ->endRow()
                  ->startRow()
                  ->addFormFields([
                      [
                          'type' => 'radio',
                          'name' => 'status',
                          'label' => 'Select status',
                          'options' => [
                              ['value' => 'active', 'label' => 'Active', 'checked' => $data->status === 'active'],
                              ['value' => 'inactive', 'label' => 'Inactive', 'checked' => $data->status === 'inactive'],
                          ],
                          'col' => 'col-6 mb-3',
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
                  ])
                  ->endRow()
                  ->build();

        return view('Product::product.edit')->with([
                      'title' => 'Product Edit',
                    'buttons' => [
                        [
                            'label' => 'Add New',
                            'url' => route('product.index'),
                            'icon' => 'ri-add-line',
                            'classes' => 'btn-sm btn-outline-primary',
                        ],
                        [
                            'label' => 'Back',
                            'url' => route('product.index'),
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
                Rule::unique((new Product)->getTable())->ignore($id),
            ],
            'status' => 'required|in:active,inactive',
        ]);

        $response = $this->service->update($id, $request->all());

        if ($response) {
            return redirect()->route('product.index')
                ->with('success', 'Product updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));

        return response()->json([
            'message' => 'Product deleted successfully',
            'status_code' => ResponseAlias::HTTP_OK,
        ]);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $editRoute = route('product.edit', $data->id);
                $viewRoute = null; // optional
                return actionDropdown($data->id, $editRoute, $viewRoute);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
