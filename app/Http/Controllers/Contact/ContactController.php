<?php

namespace App\Http\Controllers\Contact;

use App\Engine\Contact\Services\Contracts\ContactServiceInterface;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    private ContactServiceInterface $service;

    public function __construct(ContactServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.contact.index')->with([
            'title' => 'Contact',
            'buttons' => [],
            'table' => 'contact_table',
            'columns' => [
                "Name",
                'Email',
                'Area',
                'Phone',
                'Message',
                'Status',
                'Action'
            ],
        ]);
    }



    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('contact.update', ['id' => $id]))
            ->method('POST')
            ->startRow()
            ->addFormFields([[
                'type' => 'select',
                'name' => 'status_',
                'label' => 'Status',
                'col' => 'col-md-3 mb-3',
                'options' => [
                    '0' => 'Pending',
                    '1' => 'Reply',
                    '2' => 'Waiting',
                    '3' => 'Contacted',
                    '4' => 'Not Contacted',
                    '5' => 'Rejected',
                    '6' => 'Cancelled',
                ],
                'value' => old('status_', $data->status),
                'required' => true,
                'invalid_feedback' => 'Please select a valid status.',
            ]])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Change Status',
                'col' => 'col-6 mb-3',
                'icon' => 'ri-check-line',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.contact.edit')->with([
            'title' => 'Reach out Edit',
            'buttons' => [
                [
                    'label' => 'Back to List',
                    'url' => route('contact.view'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-danger',
                ],
            ],
            'formConfig' => $formConfig,
            'data' => $data,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'status_' => 'required',
        ]);

        $request['status'] = $request['status_'];

        $response = $this->service->update($id, $request->all());

        if ($response) {
            return redirect()->route('contact.view')->with('success', 'Contact updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function delete(Request $request): JsonResponse
    {
        $data = $this->service->delete($request->input('id'));

        if ($data) {
            return response()->json([
                'message' => 'Contact deleted successfully',
                'status_code' => ResponseAlias::HTTP_OK,
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'Contact delete failed',
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
                return $data->name . ' ' . $data->last_name;
            })
            ->addColumn('message', function ($data) {
                return $data->description;
            })
            ->editColumn('status', function ($data) {

                $statusMessages = [
                    '0' => 'Pending',
                    '1' => 'Reply',
                    '2' => 'Waiting',
                    '3' => 'Contacted',
                    '4' => 'Not Contacted',
                    '5' => 'Rejected',
                    '6' => 'Cancelled',
                ];

                // Check if the provided status code exists in the array
                if (array_key_exists($data->status, $statusMessages)) {
                    // Return the corresponding status message
                    return $statusMessages[$data->status];
                } else {
                    // Return a default message for unknown status codes
                    return 'Unknown Status';
                }
            })
            ->addColumn('action', function ($data) {
                $editRoute = route('contact.edit', $data->id);

                return actionDropdown($data->id, $editRoute);
            })
            ->toJson();
    }
}
