<?php

namespace Modules\Package\Http\Controllers;

use App\Models\FormStore;
use Modules\Package\Services\Contracts\PackageServiceInterface;
use Modules\Package\Models\Package;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    private PackageServiceInterface $service;

    public function __construct(PackageServiceInterface $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Package::package.index')->with([
            'title' => 'Packages',
            'buttons' => [
                [
                    'label' => 'Add New Package',
                    'url' => route('package.add'),
                    'icon' => 'ri-add-line', // ✅ icon fixed
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'package_table',
            'columns' => [

                "Title",
                "Category",
                "Price",
                "Status",
                "Action"
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADD
    |--------------------------------------------------------------------------
    */
    public function add()
    {
        $formConfig = (new FormMaking())
            ->action(route('package.store'))
            ->enctype('multipart/form-data')
            ->method('POST')

            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Title',
                    'value' => old('title'),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()

            // Category & Speed
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'select',
                    'name' => 'category',
                    'label' => 'Category',
                    'options' => [
                        'Home Internet' => 'Home Internet',
                        'Corporate' => 'Corporate',
                        'SME' => 'SME',
                        'Freelancer' => 'Freelancer',
                        'GameX' => 'GameX'
                    ],
                    'value' => old('category'),
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'name' => 'speed',
                    'label' => 'Speed',
                    'value' => old('speed'),
                    'col' => 'col-md-6 mb-3',
                    'placeholder' => 'e.g., 100 Mbps',
                ]
            ])
            ->endRow()

            // Base Price & VAT Percentage
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'number',
                    'name' => 'base_price',
                    'label' => 'Base Price',
                    'value' => old('base_price'),
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                    'placeholder' => '0.00',
                    'step' => '0.01',
                ],
                [
                    'type' => 'number',
                    'name' => 'vat_percentage',
                    'label' => 'VAT %',
                    'value' => old('vat_percentage', 5),
                    'col' => 'col-md-6 mb-3',
                    'placeholder' => '5',
                    'step' => '0.01',
                ]
            ])
            ->endRow()

            // VAT Enabled & Popular Package
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'checkbox',
                    'name' => 'vat_enabled',
                    'label' => 'Enable VAT',
                    'checked' => old('vat_enabled', true),
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'checkbox',
                    'name' => 'is_popular',
                    'label' => 'Popular Package',
                    'checked' => old('is_popular'),
                    'col' => 'col-md-6 mb-3',
                ]
            ])
            ->endRow()

            // Features
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'custom',
                    'name' => 'features',
                    'label' => 'Features',
                    'col' => 'col-md-12 mb-3',
                    'callback' => fn() => '
                        <div class="form-group">
                            <label>Features</label>
                            <div id="feature-wrapper">
                                <div class="d-flex mb-2">
                                    <input type="text" name="features[]" class="form-control me-2" placeholder="Enter feature">
                                    <button type="button" class="btn btn-success add-feature"><i class="ri-add-line"></i></button>
                                </div>
                            </div>
                        </div>
                    '
                ]
            ])
            ->endRow()

            // Image
//            ->startRow()
//            ->addFormFields([
//                [
//                    'type' => 'file',
//                    'name' => 'image',
//                    'label' => 'Image',
//                    'col' => 'col-md-12 mb-3',
//                    'required' => true,
//                ]
//            ])
//            ->endRow()

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
                            'checked' => true
                        ],
                        [
                            'value' => 'inactive',
                            'label' => 'Inactive',
                            'checked' => false
                        ]
                    ],
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()

            // Submit Button
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Save Package',
                'icon' => 'ri-save-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-12 mb-3',
            ])
            ->endRow()
            ->build();

        return view('Package::package.add')->with([
            'title' => 'Add Package',
            'buttons' => [
                [
                    'label' => 'Back',
                    'url' => route('package.index'),
                    'icon' => 'ri-arrow-left-line',
                    'classes' => 'btn-sm btn-outline-danger'
                ]
            ],
            'formConfig' => $formConfig
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $data = $this->service->find($id);
        $features = is_array($data->features) ? $data->features : json_decode($data->features ?? '[]', true);

        $formConfig = (new FormMaking())
            ->action(route('package.update', $id))
            ->enctype('multipart/form-data')
            ->method('POST')

            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Title',
                    'value' => old('title', $data->title),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()

            // Category & Speed
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'select',
                    'name' => 'category',
                    'label' => 'Category',
                    'options' => [
                        'Home Internet' => 'Home Internet',
                        'Corporate' => 'Corporate',
                        'SME' => 'SME',
                        'Freelancer' => 'Freelancer',
                        'GameX' => 'GameX'
                    ],
                    'value' => old('category', $data->category),
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'name' => 'speed',
                    'label' => 'Speed',
                    'value' => old('speed', $data->speed),
                    'col' => 'col-md-6 mb-3',
                    'placeholder' => 'e.g., 100 Mbps',
                ]
            ])
            ->endRow()

            // Base Price & VAT Percentage
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'number',
                    'name' => 'base_price',
                    'label' => 'Base Price',
                    'value' => old('base_price', $data->base_price),
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                    'placeholder' => '0.00',
                    'step' => '0.01',
                ],
                [
                    'type' => 'number',
                    'name' => 'vat_percentage',
                    'label' => 'VAT %',
                    'value' => old('vat_percentage', $data->vat_percentage),
                    'col' => 'col-md-6 mb-3',
                    'placeholder' => '5',
                    'step' => '0.01',
                ]
            ])
            ->endRow()

            // VAT Enabled & Popular Package
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'checkbox',
                    'name' => 'vat_enabled',
                    'label' => 'Enable VAT',
                    'checked' => old('vat_enabled', $data->vat_enabled),
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'checkbox',
                    'name' => 'is_popular',
                    'label' => 'Popular Package',
                    'checked' => old('is_popular', $data->is_popular),
                    'col' => 'col-md-6 mb-3',
                ]
            ])
            ->endRow()

            // Features
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'custom',
                    'name' => 'features',
                    'label' => 'Features',
                    'col' => 'col-md-12 mb-3',
                    'callback' => function () use ($features) {
                        $html = '<div class="form-group">
                            <label>Features</label>
                            <div id="feature-wrapper">';

                        if (!empty($features) && is_array($features)) {
                            foreach ($features as $feature) {
                                $html .= '
                                    <div class="d-flex mb-2">
                                        <input type="text" name="features[]" value="' . htmlspecialchars($feature, ENT_QUOTES, 'UTF-8') . '" class="form-control me-2" placeholder="Enter feature">
                                        <button type="button" class="btn btn-success add-feature me-1"><i class="ri-add-line"></i></button>
                                        <button type="button" class="btn btn-danger remove-feature"><i class="ri-delete-bin-line"></i></button>
                                    </div>';
                            }
                        } else {
                            $html .= '
                                <div class="d-flex mb-2">
                                    <input type="text" name="features[]" class="form-control me-2" placeholder="Enter feature">
                                    <button type="button" class="btn btn-success add-feature me-1"><i class="ri-add-line"></i></button>
                                    <button type="button" class="btn btn-danger remove-feature"><i class="ri-delete-bin-line"></i></button>
                                </div>';
                        }

                        $html .= '
                            </div>
                        </div>';

                        return $html;
                    }
                ]
            ])
            ->endRow()

//            // Image
//            ->startRow()
//            ->addFormFields([
//                [
//                    'type' => 'file',
//                    'name' => 'image',
//                    'label' => 'Image',
//                    'col' => 'col-md-12 mb-3',
//                ]
//            ])
//            ->endRow()

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
                        ]
                    ],
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()

            // Submit Button
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update Package',
                'icon' => 'ri-edit-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-12 mb-3',
            ])
            ->endRow()
            ->build();

        return view('Package::package.edit')->with([
            'title' => 'Edit Package',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('package.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary'
                ],
                [
                    'label' => 'Back',
                    'url' => route('package.index'),
                    'icon' => 'ri-arrow-left-line',
                    'classes' => 'btn-sm btn-outline-danger'
                ]
            ],
            'formConfig' => $formConfig
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE / UPDATE LOGIC (same as previous)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|unique:packages,title',
            'category' => 'required',
            'base_price' => 'required|numeric',
        ]);

        $data = $request->only([
            'title', 'category', 'speed', 'base_price', 'vat_percentage'
        ]);

        $data['slug'] = Str::slug($request->title);
        $data['vat_enabled'] = $request->has('vat_enabled');
        $data['is_popular'] = $request->has('is_popular');

        if ($data['vat_enabled']) {
            $vat = ($data['base_price'] * ($data['vat_percentage'] ?? 5)) / 100;
            $data['vat_amount'] = $vat;
            $data['total_price'] = $data['base_price'] + $vat;
        } else {
            $data['vat_amount'] = 0;
            $data['total_price'] = $data['base_price'];
        }

        $data['features'] = json_encode(array_values(array_filter($request->features ?? [])));

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/packages'), $name);
            $data['image'] = 'uploads/packages/' . $name;
        }

        $data['user_id'] = auth()->id();

        $this->service->create($data);

        return redirect()->route('package.index')->with('success', 'Created');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $package = $this->service->find($id);

        $request->validate([
            'title' => ['required', Rule::unique('packages')->ignore($id)],
        ]);

        $data = $request->only([
            'title', 'category', 'speed', 'base_price', 'vat_percentage'
        ]);

        $data['slug'] = Str::slug($request->title);
        $data['vat_enabled'] = $request->has('vat_enabled');
        $data['is_popular'] = $request->has('is_popular');

        if ($data['vat_enabled']) {
            $vat = ($data['base_price'] * ($data['vat_percentage'] ?? 5)) / 100;
            $data['total_price'] = $data['base_price'] + $vat;
        } else {
            $data['total_price'] = $data['base_price'];
        }

        $data['features'] = json_encode(array_values(array_filter($request->features ?? [])));

        if ($request->hasFile('image')) {
            if ($package->image && File::exists(public_path($package->image))) {
                File::delete(public_path($package->image));
            }
            $file = $request->file('image');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/packages'), $name);
            $data['image'] = 'uploads/packages/' . $name;
        }

        $this->service->update($id, $data);

        return redirect()->route('package.index')->with('success', 'Updated');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->id);
        return response()->json(['message' => 'Deleted', 'status_code' => ResponseAlias::HTTP_OK]);
    }

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */
    public function dataTableList(): JsonResponse
    {
        $data = $this->service->dataTableData();
        return DataTables::of($data)
            ->addColumn('image', fn($d) => '<img src="' . asset($d->image) . '" width="50">')
            ->addColumn('category', fn($d) => $d->category)
            ->addColumn('price', fn($d) => number_format($d->total_price, 2) . ' ৳')
            ->addColumn('action', fn($d) => actionDropdown($d->id, route('package.edit', $d->id)))
            ->rawColumns(['image', 'action'])
            ->toJson();
    }


    public function packageRequest(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Package::package.list')->with([
            'title' => 'Request',
            'buttons' => [],
            'table' => 'package_table_request',
            'columns' => [
                'Name',
                'Mobile',
                'Address',
                'Type',
                'Preferred Date',
                'Map Link',
                'message',
                'package',
                'source',
                'Date',
            ],
        ]);
    }

    public function packageRequestDataTableList(): JsonResponse
    {
        $data = FormStore::query()->where([
            'source' => FormStore::SOURCE_PACKAGE
        ])->orderByDesc('id');

        return DataTables::of($data)
            ->filter(function ($query) {
                if (request()->has('name') && !is_null(request('name'))) {
                    $query->where('name', 'like', '%' . request('name') . '%');
                }
            }, true)
            ->addColumn('name', function ($data) {
                return $data->name;
            })
            ->addColumn('message', function ($data) {
                return $data->description;
            })
            ->addColumn('preferred date', function ($data) {
                $data = json_decode($data->extra_data, true);
                return is_null($data['preferredDate']) ? 'N/A' : $data['preferredDate'];
            })
            ->addColumn('map link', function ($data) {
                $data = json_decode($data->extra_data, true);
                return is_null($data['mapLink']) ? 'N/A' : $data['mapLink'];
            })
            ->addColumn('date', function ($data) {
                return $data->created_at;
            })
            ->editColumn('status', function ($data) {

                $statusMessages = [
                    'pending' => 'Pending',
                    'reply' => 'Reply',
                    'waiting' => 'Waiting',
                    'contacted' => 'Contacted',
                    'not contacted' => 'Not Contacted',
                    'rejected' => 'Rejected',
                    'cancelled' => 'Cancelled',
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
