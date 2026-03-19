<?php

namespace Modules\Faq\Http\Controllers;


use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Faq\Models\Faq;
use Modules\Faq\Services\Contracts\FaqServiceInterface;
use Modules\FaqCategory\Models\FaqCategory;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    private FaqServiceInterface $service;

    public function __construct(FaqServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Faq::faq.index')->with([
            'title' => 'Faqs',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('faq.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'faq_table',
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
            ->action(route('faq.store'))
            ->enctype('multipart/form-data')
            ->method('POST')
            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Faq Title',
                    'value' => old('title'),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()

            // Category
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'select',
                    'name' => 'faq_category_id',
                    'label' => 'Select Category',
                    'options' => FaqCategory::query()->pluck('title', 'id')->toArray(),
                    'value' => old('faq_category_id'),
                    'col' => 'col-md-6 mb-3',
                    'placeholder' => 'Select Category',
                    'required' => true,
                ]
            ])
            ->endRow()



            // Description
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'custom',
                    'name' => 'description',
                    'value' => old('description'),
                    'col' => 'col-md-12 mb-3',
                    'callback' => function ($input) {
                        $value = htmlspecialchars($input['value'], ENT_QUOTES, 'UTF-8'); // Escape value for safety

                        return "<div class=\"form-group\">
                                            <label for=\"{$input['name']}\">Description</label>
                                            <div class=\"snow-editor\"  data-name=\"{$input['name']}\" style=\"height: 300px;\">$value</div>
                                            <input type=\"hidden\" name=\"{$input['name']}\" id=\"{$input['name']}\" value=\"$value\">
                                               </div>
                                        ";
                    }

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
                'value' => 'Add Faq',
                'icon' => 'ri-save-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-12 mb-3',
            ])
            ->endRow()
            ->build();

        return view('Faq::faq.add')
            ->with([
                'title' => 'Faq Add',
                'buttons' => [
                    [
                        'label' => 'Back',
                        'url' => route('faq.index'),
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
            'title' => 'required|unique:faqs,title',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
            'faq_category_id' => 'required|exists:faq_categories,id',
        ]);

        $data = $request->only([
            'title',
            'description',
            'status',
            'faq_category_id'
        ]);


        $data['user_id'] = auth()->user()->id;

        $response = $this->service->create($data);

        if ($response) {
            return redirect()->route('faq.index')
                ->with('success', 'Faq added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('faq.update', ['id' => $id]))
            ->enctype('multipart/form-data')
            ->method('POST')

            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Faq Title',
                    'value' => old('title', $data->title),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()

            // Category
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'select',
                    'name' => 'faq_category_id',
                    'label' => 'Select Category',
                    'options' => FaqCategory::query()->pluck('title', 'id')->toArray(),
                    'value' => old('faq_category_id', $data->faq_category_id),
                    'col' => 'col-md-6 mb-3',
                    'placeholder' => 'Select Category',
                    'required' => true,
                ]
            ])
            ->endRow()


            // Description
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'custom',
                    'name' => 'description',
                    'value' => old('description', $data->description),
                    'col' => 'col-md-12 mb-3',
                    'callback' => function ($input) {
                        $value = htmlspecialchars($input['value'], ENT_QUOTES, 'UTF-8'); // Escape value for safety

                        return "<div class=\"form-group\">
                                         <label for=\"{$input['name']}\">Description</label>
                                         <div class=\"snow-editor\"  data-name=\"{$input['name']}\" style=\"height: 300px;\">$value</div>
                                         <input type=\"hidden\" name=\"{$input['name']}\" id=\"{$input['name']}\" value=\"$value\">
                                            </div>
                                     ";
                    }

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

        return view('Faq::faq.edit')->with([
            'title' => 'Faq Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('faq.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back',
                    'url' => route('faq.index'),
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
                Rule::unique((new Faq)->getTable())->ignore($id),
            ],
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $this->service->find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data not found.');
        }

        $data = $request->only([
            'title',
            'description',
            'status',
            'faq_category_id'
        ]);


        $data['user_id'] = auth()->user()->id;

        $response = $this->service->update($id, $data);


        if ($response) {
            return redirect()->route('faq.index')
                ->with('success', 'Faq updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));

        return response()->json([
            'message' => 'Faq deleted successfully',
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
                $editRoute = route('faq.edit', $data->id);
                $viewRoute = null; // optional
                return actionDropdown($data->id, $editRoute, $viewRoute);
            })
            ->rawColumns(['action', 'image'])
            ->toJson();
    }
}
