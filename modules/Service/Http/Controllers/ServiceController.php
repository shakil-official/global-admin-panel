<?php

namespace Modules\Service\Http\Controllers;


use Modules\Service\Services\Contracts\ServiceServiceInterface;

use Modules\Service\Models\Service;
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

class ServiceController extends Controller
{
    private ServiceServiceInterface $service;

    public function __construct(ServiceServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Service::service.index')->with([
            'title' => 'Services',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('service.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'service_table',
            'columns' => [
                'title',
                'position',
                'description',
                'section',
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('service.store'))
            ->method('POST')

            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'placeholder' => 'Please enter the title',
                    'label' => 'Service Title',
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                    'value' => old('title')
                ],
            ])
            ->endRow()


            // Position
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'number',
                    'name' => 'position',
                    'placeholder' => 'Please enter the position number',
                    'label' => 'Position',
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                    'value' => old('position')
                ],
            ])
            ->addFormFields([
                [
                    'type' => 'select',
                    'name' => 'icon',
                    'label' => 'Icon',
                    'col' => 'col-md-6 mb-3',
                    'options' => [
                        'home' => 'Home',
                        'building' => 'Building',
                        'gamepad' => 'Gamepad',
                        'camera' => 'Camera',
                        'network' => 'Network',
                        'phone' => 'Phone',
                        'server' => 'Server',
                        'database' => 'Database',
                        'zap' => 'Zap',
                        'mic' => 'Mic',

                    ],
                    'required' => true,
                    'invalid_feedback' => 'Please select a valid icon.',
                    'value' => old('icon')
                ],
            ])
            ->endRow()

            // Short Description
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                    'col' => 'col-md-12 mb-3',
                    'required' => false,
                    'value' => old('description')
                ],
            ])
            ->endRow()

//            // Description
//            ->startRow()
//            ->addFormFields([
//                [
//                    'type' => 'custom',
//                    'name' => 'description',
//                    'value' => old('description'),
//                    'col' => 'col-md-12 mb-3',
//                    'callback' => function ($input) {
//                        $value = htmlspecialchars($input['value'], ENT_QUOTES, 'UTF-8'); // Escape value for safety
//
//                        return "<div class=\"form-group\">
//                                <label for=\"{$input['name']}\">Description</label>
//                                <div class=\"snow-editor\"  data-name=\"{$input['name']}\" style=\"height: 300px;\">$value</div>
//                                <input type=\"hidden\" name=\"{$input['name']}\" id=\"{$input['name']}\" value=\"$value\">
//                                   </div>
//                            ";
//                    }
//
//                ]
//            ])
//            ->endRow()

            ->startRow()
            ->repeatable([
                'name' => 'service',
                'label' => 'Services',
                'col' => 'col-md-6',
                'placeholder' => 'Enter service',
                'value' => old('service')
            ])
            ->endRow()

            // Section
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'select',
                    'name' => 'section',
                    'label' => 'Section',
                    'col' => 'col-md-6 mb-3',
                    'options' => [
                        'premium' => 'Premium Services',
                        'specialized' => 'Specialized Business Solutions',
                        'Value_added' => 'Value Added Services',
                    ],
                    'required' => true,
                    'invalid_feedback' => 'Please select a valid section.',
                ],
            ])
            ->endRow()

            // Button Text
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'button_text',
                    'label' => 'Service Button Text',
                    'placeholder' => 'Please enter the button text',
                    'col' => 'col-md-12 mb-3',
                    'required' => false,
                    'value' => old('button_text')
                ],
            ])
            ->endRow()

            // Button Url
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'button_url',
                    'label' => 'Service Button Url',
                    'placeholder' => 'Please enter the button url',
                    'col' => 'col-md-12 mb-3',
                    'required' => false,
                    'value' => old('button_url')
                ],
            ])
            ->endRow()


            // Submit
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Create Service',
                'icon' => 'ri-add-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-md-6 mb-3',
            ])
            ->endRow()
            ->build();


        return view('Service::service.add')
            ->with([
                'title' => 'Add',
                'buttons' => [
                    [
                        'label' => 'Back',
                        'url' => route('service.index'),
                        'icon' => 'ri-corner-down-right-fill',
                        'classes' => 'btn-sm btn-outline-danger',
                    ],
                ],
                'formConfig' => $formConfig
            ]);

    }

    public function store(Request $request): RedirectResponse
    {
        if ('Value_added' == $request->get('section')) {
            $this->validate($request, [
                'title' => 'required|unique:services,title',
                'position' => 'required|numeric',
                'icon' => 'required|max:20',
                'description' => 'nullable',
                'section' => 'required_if:section,premium,specialized|max:30',
            ]);
        } else {
            $this->validate($request, [
                'title' => 'required|unique:services,title',
                'position' => 'required|numeric',
                'icon' => 'required|max:20',
                'description' => 'nullable',
                'service.*' => 'required',
                'section' => 'required_if:section,premium,specialized|max:30',
            ]);
        }

        $request->merge([
            'service' => json_encode($request->service),
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->service->create($request->all());

        if ($response) {
            return redirect()->route('service.index')
                ->with('success', 'Service added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('service.update', ['id' => $id]))
            ->method('POST')
            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Service Title',
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                    'value' => old('title', $data->title)
                ],
            ])
            ->endRow()


            // Position
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'number',
                    'name' => 'position',
                    'label' => 'Position',
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                    'value' => old('position', $data->position)
                ],
            ])
            ->addFormFields([
                [
                    'type' => 'select',
                    'name' => 'icon',
                    'label' => 'Icon',
                    'col' => 'col-md-6 mb-3',
                    'options' => [
                        'home' => 'Home',
                        'building' => 'Building',
                        'gamepad' => 'Gamepad',
                        'camera' => 'Camera',
                        'network' => 'Network',
                        'phone' => 'Phone',
                        'server' => 'Server',
                        'database' => 'Database',
                        'zap' => 'Zap',
                        'mic' => 'Mic',

                    ],
                    'required' => true,
                    'invalid_feedback' => 'Please select a valid icon.',
                    'value' => old('icon', $data->icon)
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                    'col' => 'col-md-12 mb-3',
                    'required' => false,
                    'value' => old('description', $data->description)
                ],
            ])
            ->endRow()
            ->startRow()
            ->repeatable([
                'name' => 'service',
                'label' => 'Services',
                'col' => 'col-md-12',
                'placeholder' => 'Enter service',
                'value' => old('service', json_decode($data->service))
            ])
            ->endRow()

//            // Description
//            ->startRow()
//            ->addFormFields([
//                [
//                    'type' => 'custom',
//                    'name' => 'description',
//                    'value' => old('description', $data->description),
//                    'col' => 'col-md-12 mb-3',
//                    'callback' => function ($input) {
//                        $value = htmlspecialchars($input['value'], ENT_QUOTES, 'UTF-8'); // Escape value for safety
//
//                        return "<div class=\"form-group\">
//                                <label for=\"{$input['name']}\">Description</label>
//                                <div class=\"snow-editor\"  data-name=\"{$input['name']}\" style=\"height: 300px;\">$value</div>
//                                <input type=\"hidden\" name=\"{$input['name']}\" id=\"{$input['name']}\" value=\"$value\">
//                                   </div>
//                            ";
//                    }
//
//                ]
//            ])
//            ->endRow()

            // Section
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'select',
                    'name' => 'section',
                    'label' => 'Section',
                    'col' => 'col-md-6 mb-3',
                    'options' => [
                        'premium' => 'Premium Services',
                        'specialized' => 'Specialized Business Solutions',
                        'Value_added' => 'Value Added Services',
                    ],
                    'required' => true,
                    'invalid_feedback' => 'Please select a valid section.',
                    'value' => old('section', $data->section)
                ],
            ])
            ->endRow()

            // Button Text
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'button_text',
                    'label' => 'Service Button Text',
                    'col' => 'col-md-12 mb-3',
                    'required' => false,
                    'value' => old('button_text', $data->button_text)
                ],
            ])
            ->endRow()

            // Button Url
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'button_url',
                    'label' => 'Service Button Url',
                    'col' => 'col-md-12 mb-3',
                    'required' => false,
                    'value' => old('button_url', $data->button_url)
                ],
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update Service',
                'col' => 'col-6 mb-3',
                'class' => 'btn btn-sm btn-primary',
                'icon' => 'ri-edit-line',
            ])
            ->endRow()
            ->build();

        return view('Service::service.edit')->with([
            'title' => 'Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('service.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back',
                    'url' => route('service.index'),
                    'icon' => 'ri-corner-down-right-fill',
                    'classes' => 'btn-sm btn-outline-danger',
                ],
            ],
            'formConfig' => $formConfig,
        ]);

    }

    public function update(Request $request, $id): RedirectResponse
    {

        if ('Value_added' == $request->get('section')) {
            $this->validate($request, [
                'title' => [
                    'required',
                    Rule::unique((new Service)->getTable())->ignore($id),
                ],
                'position' => 'required|numeric',
                'icon' => 'required|max:20',
                'description' => 'nullable',
                'section' => 'required_if:section,premium,specialized|max:30',
            ]);
        } else {
            $this->validate($request, [
                'title' => [
                    'required',
                    Rule::unique((new Service)->getTable())->ignore($id),
                ],
                'position' => 'required|numeric',
                'icon' => 'required|max:20',
                'description' => 'nullable',
                'service.*' => 'required',
                'section' => 'required_if:section,premium,specialized|max:30',
            ]);
        }

        $request->merge([
            'service' => json_encode($request->service),
            'user_id' => auth()->user()->id,
        ]);

        $response = $this->service->update($id, $request->all());

        if ($response) {
            return redirect()->route('service.index')
                ->with('success', 'Service updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));

        return response()->json([
            'message' => 'Service deleted successfully',
            'status_code' => ResponseAlias::HTTP_OK,
        ]);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('section', function ($data) {
                $sectionName = ucwords(str_replace('_', ' ', $data->section));
                return badge($sectionName, $data->section);
            })
            ->addColumn('description', function ($data) {
                return is_null($data->description) ? 'N/A' : $data->description;
            })
            ->addColumn('action', function ($data) {
                $editRoute = route('service.edit', $data->id);
                $viewRoute = null; // optional
                return actionDropdown($data->id, $editRoute, $viewRoute);
            })
            ->rawColumns(['action', 'section'])
            ->toJson();
    }
}
