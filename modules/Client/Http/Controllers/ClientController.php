<?php

namespace Modules\Client\Http\Controllers;


use Modules\Client\Services\Contracts\ClientServiceInterface;

use Modules\Client\Models\Client;
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

class ClientController extends Controller
{
    private ClientServiceInterface $service;

    public function __construct(ClientServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Client::client.index')->with([
            'title' => 'Clients',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('client.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'client_table',
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
                    ->action(route('client.store'))
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
                        'icon' => 'ri-add-line',
                         'class' => 'btn btn-sm btn-primary',
                    ])
                    ->endRow()
                    ->build();

        return view('Client::client.add')
        ->with([
                'title' => 'Client Add',
                'buttons' => [
                    [
                       'label' => 'Back',
                        'url' => route('client.index'),
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
            'name' => 'required|unique:clients,name',
            'status' => 'required',
        ]);

        $response = $this->service->create($request->all());

        if ($response) {
            return redirect()->route('client.index')
                ->with('success', 'Client added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
       $data = $this->service->find($id);

              $formConfig = (new FormMaking())
                  ->action(route('client.update', ['id' => $id]))
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
                      'icon' => 'ri-edit-line',
                  ])
                  ->endRow()
                  ->build();

        return view('Client::client.edit')->with([
                      'title' => 'Client Edit',
                    'buttons' => [
                        [
                            'label' => 'Add New',
                            'url' => route('client.index'),
                            'icon' => 'ri-add-line',
                            'classes' => 'btn-sm btn-outline-primary',
                        ],
                        [
                            'label' => 'Back',
                            'url' => route('client.index'),
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
                Rule::unique((new Client)->getTable())->ignore($id),
            ],
            'status' => 'required|in:active,inactive',
        ]);

        $response = $this->service->update($id, $request->all());

        if ($response) {
            return redirect()->route('client.index')
                ->with('success', 'Client updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));

        return response()->json([
            'message' => 'Client deleted successfully',
            'status_code' => ResponseAlias::HTTP_OK,
        ]);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $editRoute = route('client.edit', $data->id);
                $viewRoute = null; // optional
                return actionDropdown($data->id, $editRoute, $viewRoute);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
