<?php

namespace Modules\NetworkPartner\Http\Controllers;


use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Modules\NetworkPartner\Models\NetworkPartner;
use Modules\NetworkPartner\Services\Contracts\NetworkPartnerServiceInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;


class NetworkPartnerController extends Controller
{
    private NetworkPartnerServiceInterface $service;

    public function __construct(NetworkPartnerServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('NetworkPartner::network-partner.index')->with([
            'title' => 'Network Partners',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('networkpartner.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'network-partner_table',
            'columns' => [
                "Image",
                "Name",
                "Status",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('networkpartner.store'))
            ->enctype('multipart/form-data')
            ->method('POST')
            // Name
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Partner Name',
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->addFormFields([
                [
                    'type' => 'select_advance',
                    'name' => 'type',
                    'label' => 'Partner Type',
                    'options' => [
                        ['value' => 'upstream', 'label' => 'Upstream'],
                        ['value' => 'peering', 'label' => 'Peering'],
                        ['value' => 'technology', 'label' => 'Technology'],
                        ['value' => 'nttn', 'label' => 'NTTN'],
                    ],
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ],
            ])
            ->endRow()

            // Image Upload
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'image',
                    'label' => 'Partner Image / Logo',
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
                            'checked' => true,
                        ],
                        [
                            'value' => 'inactive',
                            'label' => 'Inactive',
                            'checked' => false,
                        ],
                    ],
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ],
            ])
            ->endRow()

            // Submit Button
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Create Network Partner',
                'icon' => 'ri-add-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-12 mb-3',
            ])
            ->endRow()
            ->build();


        return view('NetworkPartner::network-partner.add')
            ->with([
                'title' => 'Add',
                'buttons' => [
                    [
                        'label' => 'Back',
                        'url' => route('network-partner.index'),
                        'icon' => 'ri-corner-down-right-fill',
                        'classes' => 'btn-sm btn-outline-danger',
                    ],
                ],
                'formConfig' => $formConfig
            ]);

    }


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'type' => 'required|in:upstream,peering,technology,nttn',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $uploadPath = public_path('uploads/network-partners');

            // Ensure directory exists
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Move file to public/uploads
            $file->move($uploadPath, $fileName);

            // Store relative path in DB
            $imagePath = 'uploads/network-partners/' . $fileName;
        }

        $response = $this->service->create([
            'name' => $request->name,
            'image' => $imagePath,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        if ($response) {
            return redirect()
                ->route('network-partner.index')
                ->with('success', 'Network Partner added successfully.');
        }

        return redirect()
            ->back()
            ->with('error', 'Something went wrong. Please try again.');
    }


    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $networkPartner = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('networkpartner.update', ['id' => $id]))
            ->enctype('multipart/form-data')
            ->method('POST')
            // Name
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Partner Name',
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                    'value' => $networkPartner->name
                ]
            ])
            ->addFormFields([
                [
                    'type' => 'select_advance',
                    'name' => 'type',
                    'label' => 'Partner Type',
                    'options' => [
                        ['value' => 'upstream', 'label' => 'Upstream'],
                        ['value' => 'peering', 'label' => 'Peering'],
                        ['value' => 'technology', 'label' => 'Technology'],
                        ['value' => 'nttn', 'label' => 'NTTN'],
                    ],
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                    'value' => $networkPartner->type
                ],
            ])
            ->endRow()

            // Image Upload
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'image',
                    'label' => 'Partner Image / Logo',
                    'col' => 'col-md-12 mb-3',
                    'required' => false,
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
                            'checked' => $networkPartner->status === 'active',
                        ],
                        [
                            'value' => 'inactive',
                            'label' => 'Inactive',
                            'checked' => $networkPartner->status === 'inactive',
                        ],
                    ],
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ],
            ])
            ->endRow()

            // Submit
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update Network Partner',
                'icon' => 'ri-edit-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-12 mb-3',
            ])
            ->endRow()
            ->build();

        return view('NetworkPartner::network-partner.edit')->with([
            'title' => 'Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('networkpartner.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back',
                    'url' => route('network-partner.index'),
                    'icon' => 'ri-corner-down-right-fill',
                    'classes' => 'btn-sm btn-outline-danger',
                ],
            ],
            'formConfig' => $formConfig,
        ]);

    }


    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'type' => 'required|in:upstream,peering,technology,nttn',
            'status' => 'required|in:active,inactive',
        ]);

        $networkPartner = NetworkPartner::query()->findOrFail($id);

        $data = $request->only(['name', 'type', 'status']);

        // Handle image upload to /public/uploads/network-partners
        if ($request->hasFile('image')) {

            $uploadPath = public_path('uploads/network-partners');

            // Ensure directory exists
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Optional: delete old image
            if ($networkPartner->image && File::exists(public_path($networkPartner->image))) {
                File::delete(public_path($networkPartner->image));
            }

            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($uploadPath, $fileName);

            // Store relative path in DB
            $data['image'] = 'uploads/network-partners/' . $fileName;
        }


        $data['user_id'] =  auth()->user()->id;

        $response = $this->service->update($id, $data);

        if ($response) {
            return redirect()
                ->route('network-partner.index')
                ->with('success', 'Network Partner updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }


    public function delete(Request $request): JsonResponse
    {
        $id = $request->input('id');

        $networkPartner = $this->service->find($id);

        if (!$networkPartner) {
            return response()->json([
                'message' => 'NetworkPartner not found',
                'status_code' => ResponseAlias::HTTP_NOT_FOUND,
            ]);
        }

        // Delete image from public folder
        if ($networkPartner->image && File::exists(public_path($networkPartner->image))) {
            File::delete(public_path($networkPartner->image));
        }

        $this->service->delete($id);

        return response()->json([
            'message' => 'Network Partner deleted successfully',
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
                $editRoute = route('networkpartner.edit', $data->id);
                $viewRoute = null; // optional
                return actionDropdown($data->id, $editRoute, $viewRoute);
            })
            ->rawColumns(['action', 'image'])
            ->toJson();
    }
}
