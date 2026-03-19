<?php

namespace Modules\Offer\Http\Controllers;


use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Modules\Offer\Services\Contracts\OfferServiceInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class OfferController extends Controller
{
    private OfferServiceInterface $service;

    public function __construct(OfferServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Offer::offer.index')->with([
            'title' => 'Offers',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('offer.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'offer_table',
            'columns' => [
                "Image",
                "Title",
                "Slug",
                "Status",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('offer.store'))
            ->enctype('multipart/form-data')
            ->method('post')

            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Offer Title',
                    'value' => old('title'),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()

            // Description
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                    'value' => old('description'),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'image',
                    'label' => 'Offer Image',
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                    'help' => 'Leave empty to keep existing image',
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
                'value' => 'Add Offer',
                'icon' => 'ri-save-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-12 mb-3',
            ])
            ->endRow()
            ->build();


        return view('Offer::offer.add')
            ->with([
                'title' => 'Offer Add',
                'buttons' => [
                    [
                        'label' => 'Back',
                        'url' => route('offer.index'),
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
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);


        $data = $request->only(['title', 'slug', 'description', 'status']);

        if ($request->hasFile('image')) {

            $uploadPath = public_path('uploads/offers');

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($uploadPath, $fileName);

            // Save relative path
            $data['image'] = 'uploads/offers/' . $fileName;
        }

        $data['user_id'] = auth()->user()->id;

        $response = $this->service->create($data);

        if ($response) {
            return redirect()
                ->route('offer.index')
                ->with('success', 'Offer added successfully.');
        }

        return redirect()
            ->back()
            ->with('error', 'Something went wrong. Please try again.');

    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $offer = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('offer.update', $offer->id))
            ->enctype('multipart/form-data')
            ->method('post')

            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Offer Title',
                    'value' => old('title', $offer->title),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()



            // Description
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                    'value' => old('description', $offer->description),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()

            // Image (optional on edit)
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'image',
                    'label' => 'Offer Image',
                    'col' => 'col-md-12 mb-3',
                    'required' => false,
                    'help' => 'Leave empty to keep existing image',
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
                            'checked' => $offer->status === 'active',
                        ],
                        [
                            'value' => 'inactive',
                            'label' => 'Inactive',
                            'checked' => $offer->status === 'inactive',
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
                'value' => 'Update Offer',
                'icon' => 'ri-save-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-12 mb-3',
            ])
            ->endRow()
            ->build();

        return view('Offer::offer.edit')->with([
            'title' => 'Offer Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('offer.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back',
                    'url' => route('offer.index'),
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
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $offer = $this->service->find($id);

        if (!$offer) {
            return redirect()->back()->with('error', 'Offer not found.');
        }

        $data = $request->only(['title', 'slug', 'description', 'status']);

        if ($request->hasFile('image')) {

            $uploadPath = public_path('uploads/offers');

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Remove old image
            if ($offer->image && File::exists(public_path($offer->image))) {
                File::delete(public_path($offer->image));
            }

            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);

            $data['image'] = 'uploads/offers/' . $fileName;
        }

        $data['user_id'] = auth()->user()->id;

        $response = $this->service->update($id, $data);

        if ($response) {
            return redirect()
                ->route('offer.index')
                ->with('success', 'Offer updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }


    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));

        return response()->json([
            'message' => 'Offer deleted successfully',
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
                $editRoute = route('offer.edit', $data->id);
                $viewRoute = null; // optional
                return actionDropdown($data->id, $editRoute, $viewRoute);
            })
            ->rawColumns(['action', 'image'])
            ->toJson();
    }
}
