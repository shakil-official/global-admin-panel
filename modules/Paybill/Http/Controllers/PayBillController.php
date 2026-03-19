<?php

namespace Modules\Paybill\Http\Controllers;

use Modules\Paybill\Services\Contracts\PaybillServiceInterface;
use Modules\Paybill\Models\PayBill;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class PayBillController extends Controller
{
    private PaybillServiceInterface $service;

    public function __construct(PaybillServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $buttons = [
            [
                'label' => 'Add New',
                'url' => route('paybill.add'),
                'icon' => 'ri-add-line',
                'classes' => 'btn-sm btn-outline-primary',
            ],
        ];

        return view('Paybill::paybill.index')->with([
            'title' => 'Paybills',
            'buttons' => $buttons,
            'table' => 'paybill_table',
            'columns' => ["Method", "Title", "step_no", "Status", "Action"],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $buttons = [
            [
                'label' => 'Back',
                'url' => route('paybill.index'),
                'icon' => 'ri-arrow-left-line',
                'classes' => 'btn-sm btn-outline-danger',
            ],
        ];

        $metaFields = $this->getMetaFields();

        $formBuilder = $this->buildForm(route('paybill.store'), null, $metaFields, 'Add Paybill');

        return view('Paybill::paybill.add')->with([
            'title' => 'Add Paybill',
            'buttons' => $buttons,
            'formConfig' => $formBuilder,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'method' => 'required|in:bkash,nagad,rocket,bank,qr',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'step_no' => 'nullable|integer',
            'meta' => 'nullable|array',
            'meta.qr.qr_file' => 'nullable|file|mimes:png,jpg,jpeg',
            'status' => 'required|in:active,inactive',
        ]);

        $meta = $request->meta ?? [];

        // Handle QR file upload
        if ($request->hasFile('meta.qr.qr_file')) {
            $file = $request->file('meta.qr.qr_file');
            $path = $file->store('paybill_qr', 'public');
            $meta['qr']['qr_file'] = $path;
        }

        $data = [
            'method' => $request->input('method'),
            'title' => $request->title,
            'description' => $request->description,
            'step_no' => $request->step_no,
            'meta' => $meta,
            'status' => $request->status,
            'user_id' => auth()->id(),
        ];

        $this->service->create($data);

        return redirect()->route('paybill.index')->with('success', 'Paybill added successfully.');
    }

    public function edit($id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $buttons = [
            [
                'label' => 'Back',
                'url' => route('paybill.index'),
                'icon' => 'ri-arrow-left-line',
                'classes' => 'btn-sm btn-outline-danger',
            ],
        ];

        $metaFields = $this->getMetaFields();

        $formBuilder = $this->buildForm(route('paybill.update', $id), $data, $metaFields, 'Update Paybill');

        return view('Paybill::paybill.edit')->with([
            'title' => 'Edit Paybill',
            'buttons' => $buttons,
            'formConfig' => $formBuilder,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'method' => 'required|in:bkash,nagad,rocket,bank,qr',
            'title' => ['required', Rule::unique((new PayBill)->getTable())->ignore($id)],
            'description' => 'nullable|string',
            'step_no' => 'nullable|integer',
            'meta' => 'nullable|array',
            'meta.qr.qr_file' => 'nullable|file|mimes:png,jpg,jpeg',
            'status' => 'required|in:active,inactive',
        ]);

        $meta = $request->meta ?? [];

        // Handle QR file upload
        if ($request->hasFile('meta.qr.qr_file')) {
            $file = $request->file('meta.qr.qr_file');
            $path = $file->store('paybill_qr', 'public');
            $meta['qr']['qr_file'] = $path;
        }

        $data = [
            'method' => $request->input('method'),
            'title' => $request->title,
            'description' => $request->description,
            'step_no' => $request->step_no,
            'meta' => $meta,
            'status' => $request->status,
            'user_id' => auth()->id(),
        ];

        $this->service->update($id, $data);

        return redirect()->route('paybill.index')->with('success', 'Pay bill updated successfully.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));
        return response()->json([
            'message' => 'Pay bill deleted successfully',
            'status_code' => ResponseAlias::HTTP_OK,
        ]);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('method', fn($data) => strtoupper($data->method))
            ->addColumn('step_no', fn($data) => $data->step_no ?? '-')
            ->addColumn('action', fn($data) => actionDropdown($data->id, route('paybill.edit', $data->id)))
            ->rawColumns(['action'])
            ->toJson();
    }

    // ---------------- Helper Methods ---------------- //

    private function getMetaFields(): array
    {
        return [
            'bkash' => [['name' => 'merchant_no', 'label' => 'Merchant Number']],
            'nagad' => [['name' => 'merchant_no', 'label' => 'Merchant Number']],
            'bank'  => [
                ['name' => 'account_no', 'label' => 'Account Number'],
                ['name' => 'branch', 'label' => 'Branch Name'],
                ['name' => 'ifsc', 'label' => 'IFSC Code'],
            ],
            'qr' => [['name' => 'qr_file', 'label' => 'QR Code', 'type' => 'file']],
        ];
    }

    private function buildForm($action, $data = null, $metaFields = [], $submitLabel = 'Submit')
    {
        $formBuilder = (new FormMaking())
            ->action($action)
            ->method('POST')
            ->enctype('multipart/form-data')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'select_advance',
                    'name' => 'method',
                    'label' => 'Payment Method',
                    'options' => [
                        ['value' => 'bkash', 'label' => 'bKash'],
                        ['value' => 'nagad', 'label' => 'Nagad'],
                        ['value' => 'rocket', 'label' => 'Rocket'],
                        ['value' => 'bank', 'label' => 'Bank'],
                        ['value' => 'qr', 'label' => 'QR'],
                    ],
                    'value' => $data->method ?? null,
                    'required' => true,
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Title',
                    'value' => $data->title ?? null,
                    'required' => true,
                    'col' => 'col-md-6 mb-3',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                ['type' => 'textarea', 'name' => 'description', 'label' => 'Description', 'value' => $data->description ?? null, 'col' => 'col-md-12 mb-3']
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                ['type' => 'number', 'name' => 'step_no', 'label' => 'Step Number', 'value' => $data->step_no ?? null, 'col' => 'col-md-6 mb-3']
            ])
            ->endRow();

        foreach ($metaFields as $method => $fields) {
            $inputs = [];
            foreach ($fields as $field) {
                $inputs[] = [
                    'type' => $field['type'] ?? 'text',
                    'name' => "meta[{$method}][{$field['name']}]",
                    'label' => $field['label'],
                    'placeholder' => $field['label'],
                    'value' => $data->meta[$method][$field['name']] ?? null,
                    'col' => 'col-md-6 mb-3',
                ];
            }
            $formBuilder->startRow()->addFormFields($inputs)->endRow();
        }

        $formBuilder
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'radio',
                    'name' => 'status',
                    'label' => 'Status',
                    'options' => [
                        ['value' => 'active', 'label' => 'Active', 'checked' => ($data->status ?? 'active') === 'active'],
                        ['value' => 'inactive', 'label' => 'Inactive', 'checked' => ($data->status ?? 'active') === 'inactive'],
                    ],
                    'col' => 'col-md-6 mb-3',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addInput(['type' => 'submit', 'value' => $submitLabel, 'class' => 'btn btn-sm btn-primary', 'icon' => 'ri-save-line', 'col' => 'col-12'])
            ->endRow();

        return $formBuilder->build();
    }
}
