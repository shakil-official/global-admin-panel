<?php

namespace Modules\Coverage\Http\Controllers;


use Modules\Coverage\Services\Contracts\CoverageServiceInterface;

use Modules\Coverage\Models\Coverage;
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

class CoverageController extends Controller
{
    private CoverageServiceInterface $service;

    public function __construct(CoverageServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Coverage::coverage.index')->with([
            'title' => 'Coverages',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('coverage.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'coverage_table',
            'columns' => [
                "Name",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
                    ->action(route('coverage.store'))
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
                    ->addInput([
                        'type' => 'submit',
                        'value' => 'Submit form',
                        'icon' => 'ri-add-line',
                        'class' => 'btn btn-sm btn-primary',
                        'col' => 'col-12 mb-3',
                    ])
                    ->endRow()
                    ->build();

        return view('Coverage::coverage.add')
        ->with([
                'title' => 'Coverage Add',
                'buttons' => [
                    [
                       'label' => 'Back',
                        'url' => route('coverage.index'),
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
            'name' => 'required|unique:coverages,name',
        ]);

        $request->merge([
           'user_id' => auth()->user()->id,
        ]);

        $response = $this->service->create($request->all());

        if ($response) {
            return redirect()->route('coverage.index')
                ->with('success', 'Coverage added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
       $data = $this->service->find($id);

              $formConfig = (new FormMaking())
                  ->action(route('coverage.update', ['id' => $id]))
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
                  ->addInput([
                      'type' => 'submit',
                      'value' => 'Submit form',
                      'col' => 'col-6 mb-3',
                      'class' => 'btn btn-sm btn-primary',
                      'icon' => 'ri-edit-line',
                  ])
                  ->endRow()
                  ->build();

        return view('Coverage::coverage.edit')->with([
                      'title' => 'Coverage Edit',
                    'buttons' => [
                        [
                            'label' => 'Add New',
                            'url' => route('coverage.index'),
                            'icon' => 'ri-add-line',
                            'classes' => 'btn-sm btn-outline-primary',
                        ],
                        [
                            'label' => 'Back',
                            'url' => route('coverage.index'),
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
                Rule::unique((new Coverage)->getTable())->ignore($id),
            ],

        ]);

        $request->merge([
            'user_id' => auth()->user()->id,
        ]);


        $response = $this->service->update($id, $request->all());

        if ($response) {
            return redirect()->route('coverage.index')
                ->with('success', 'Coverage updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));

        return response()->json([
            'message' => 'Coverage deleted successfully',
            'status_code' => ResponseAlias::HTTP_OK,
        ]);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $editRoute = route('coverage.edit', $data->id);
                $viewRoute = null; // optional
                return actionDropdown($data->id, $editRoute, $viewRoute);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
