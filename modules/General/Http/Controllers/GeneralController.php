<?php

namespace Modules\General\Http\Controllers;

use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Modules\General\Models\General;
use Modules\General\Services\Contracts\GeneralServiceInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class GeneralController extends Controller
{
    private GeneralServiceInterface $service;

    public function __construct(GeneralServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('General::general.index')->with([
            'title' => 'Generals',
            'buttons' => [],
            'table' => 'general_table',
            'columns' => [
                "Fav Icon",
                "Icon",
                "Title",
                "WhatsApp",
                "Facebook",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('general.store'))
            ->enctype('multipart/form-data')
            ->method('POST')
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
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'whats_app_contact',
                    'label' => 'WhatsApp Contact',
                    'value' => old('whats_app_contact'),
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'name' => 'facebook_contact',
                    'label' => 'Facebook Contact',
                    'value' => old('facebook_contact'),
                    'col' => 'col-md-6 mb-3',
                    'required' => false,
                ],
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'fav',
                    'label' => 'Fav Icon',
                    'col' => 'col-md-4 mb-3',
                    'required' => true,
                ],
                [
                    'type' => 'file',
                    'name' => 'icon',
                    'label' => 'Icon',
                    'col' => 'col-md-4 mb-3',
                    'required' => true,
                ],
                [
                    'type' => 'file',
                    'name' => 'btrc_document_file',
                    'label' => 'BTRC Document',
                    'col' => 'col-md-4 mb-3',
                    'required' => true,
                ],
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'url',
                    'name' => 'social_facebook_link',
                    'label' => 'Facebook Link',
                    'value' => old('social_facebook_link'),
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'url',
                    'name' => 'social_youtube_link',
                    'label' => 'YouTube Link',
                    'value' => old('social_youtube_link'),
                    'col' => 'col-md-6 mb-3',
                ],
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'url',
                    'name' => 'social_linkdin_link',
                    'label' => 'LinkedIn Link',
                    'value' => old('social_linkdin_link'),
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'url',
                    'name' => 'social_instragram_link',
                    'label' => 'Instagram Link',
                    'value' => old('social_instragram_link'),
                    'col' => 'col-md-6 mb-3',
                ],
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Add General',
                'icon' => 'ri-save-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-12 mb-3',
            ])
            ->endRow()
            ->build();

        return view('General::general.add')->with([
            'title' => 'General Add',
            'buttons' => [
                [
                    'label' => 'Back',
                    'url' => route('general.index'),
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
            'title' => 'required|unique:generals,title',
            'whats_app_contact' => 'required|max:30',
            'facebook_contact' => 'nullable|max:255',

            'fav' => 'required|file|mimes:jpg,jpeg,png,webp,ico,svg|max:2048',
            'icon' => 'required|file|mimes:jpg,jpeg,png,webp,ico,svg|max:2048',
            'btrc_document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5048',

            'social_facebook_link' => 'nullable|url',
            'social_youtube_link' => 'nullable|url',
            'social_linkdin_link' => 'nullable|url',
            'social_instragram_link' => 'nullable|url',
        ]);

        $data = $request->only([
            'title',
            'whats_app_contact',
            'facebook_contact',
            'social_facebook_link',
            'social_youtube_link',
            'social_linkdin_link',
            'social_instragram_link',
            'status',
        ]);

        $uploadPath = public_path('uploads/general');

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $fileFields = ['fav', 'icon', 'btrc_document_file'];

        foreach ($fileFields as $field) {

            if ($request->hasFile($field)) {

                $file = $request->file($field);

                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move($uploadPath, $fileName);

                $data[$field] = 'uploads/general/' . $fileName;
            }
        }

        $data['user_id'] = auth()->id();

        $response = $this->service->create($data);

        if ($response) {

            return redirect()
                ->route('general.view')
                ->with('success', 'General added successfully.');
        }

        return redirect()
            ->back()
            ->with('error', 'Something went wrong.');
    }

    public function edit(Request $request, $id)
    {
        $data = $this->service->find($id);

        if (!$data) {
            return redirect()
                ->route('general.view')
                ->with('error', 'General data not found.');
        }

        $formConfig = (new FormMaking())
            ->action(route('general.update', ['id' => $id]))
            ->enctype('multipart/form-data')
            ->method('POST')
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
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'whats_app_contact',
                    'label' => 'WhatsApp Contact',
                    'value' => old('whats_app_contact', $data->whats_app_contact),
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'name' => 'facebook_contact',
                    'label' => 'Facebook Contact',
                    'value' => old('facebook_contact', $data->facebook_contact),
                    'col' => 'col-md-6 mb-3',
                ],
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'fav',
                    'label' => 'Fav Icon',
                    'col' => 'col-md-4 mb-3',
                    'help' => 'Leave empty to keep existing file',
                ],
                [
                    'type' => 'file',
                    'name' => 'icon',
                    'label' => 'Icon',
                    'col' => 'col-md-4 mb-3',
                    'help' => 'Leave empty to keep existing file',
                ],
                [
                    'type' => 'file',
                    'name' => 'btrc_document_file',
                    'label' => 'BTRC Document',
                    'col' => 'col-md-4 mb-3',
                    'help' => 'Leave empty to keep existing file',
                ],
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'url',
                    'name' => 'social_facebook_link',
                    'label' => 'Facebook Link',
                    'value' => old('social_facebook_link', $data->social_facebook_link),
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'url',
                    'name' => 'social_youtube_link',
                    'label' => 'YouTube Link',
                    'value' => old('social_youtube_link', $data->social_youtube_link),
                    'col' => 'col-md-6 mb-3',
                ],
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'url',
                    'name' => 'social_linkdin_link',
                    'label' => 'LinkedIn Link',
                    'value' => old('social_linkdin_link', $data->social_linkdin_link),
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'url',
                    'name' => 'social_instragram_link',
                    'label' => 'Instagram Link',
                    'value' => old('social_instragram_link', $data->social_instragram_link),
                    'col' => 'col-md-6 mb-3',
                ],
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update General',
                'col' => 'col-6 mb-3',
                'class' => 'btn btn-sm btn-primary',
                'icon' => 'ri-edit-line',
            ])
            ->endRow()
            ->build();

        return view('General::general.edit')->with([
            'title' => 'General',
            'buttons' => [
                [
                    'label' => 'Back',
                    'url' => route('general.view'),
                    'icon' => 'ri-corner-down-right-fill',
                    'classes' => 'btn-sm btn-outline-danger',
                ],
            ],
            'formConfig' => $formConfig,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $general = $this->service->find($id);

        if (!$general) {
            return redirect()->back()->with('error', 'General not found.');
        }

        $this->validate($request, [
            'title' => [
                'required',
                Rule::unique((new General())->getTable())->ignore($id),
            ],

            'whats_app_contact' => 'required|max:30',
            'facebook_contact' => 'nullable|max:255',

            'fav' => 'nullable|file|mimes:jpg,jpeg,png,webp,ico,svg|max:2048',
            'icon' => 'nullable|file|mimes:jpg,jpeg,png,webp,ico,svg|max:2048',
            'btrc_document_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5048',

            'social_facebook_link' => 'nullable|url',
            'social_youtube_link' => 'nullable|url',
            'social_linkdin_link' => 'nullable|url',
            'social_instragram_link' => 'nullable|url',
        ]);

        $data = $request->only([
            'title',
            'whats_app_contact',
            'facebook_contact',
            'social_facebook_link',
            'social_youtube_link',
            'social_linkdin_link',
            'social_instragram_link',
            'status',
        ]);

        $uploadPath = public_path('uploads/general');

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $fileFields = ['fav', 'icon', 'btrc_document_file'];

        foreach ($fileFields as $field) {

            if ($request->hasFile($field)) {

                // delete old file
                if ($general->$field && File::exists(public_path($general->$field))) {
                    File::delete(public_path($general->$field));
                }

                $file = $request->file($field);

                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move($uploadPath, $fileName);

                $data[$field] = 'uploads/general/' . $fileName;
            }
        }

        $data['user_id'] = auth()->id();

        $response = $this->service->update($id, $data);

        if ($response) {

            return redirect()
                ->route('general.view')
                ->with('success', 'General updated successfully.');
        }

        return redirect()
            ->back()
            ->with('error', 'Something went wrong.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));

        return response()->json([
            'message' => 'General deleted successfully',
            'status_code' => ResponseAlias::HTTP_OK,
        ]);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData();

        return DataTables::of($data)
            ->addColumn('icon', function ($data) {

                return $data->icon
                    ? '<img src="' . asset($data->icon) . '" style="width:50px;height:50px;object-fit:cover;">'
                    : 'No Image';
            })
            ->addColumn('fav icon', function ($data) {

                return $data->fav
                    ? '<img src="' . asset($data->fav) . '" style="width:50px;height:50px;object-fit:cover;">'
                    : 'No Image';
            })
            ->addColumn('whatsapp', function ($data) {

                return $data->whats_app_contact;
            })
            ->addColumn('facebook', function ($data) {

                return $data->facebook_contact;
            })
            ->addColumn('action', function ($data) {

                $editRoute = route('general.edit', $data->id);

                return actionDropdownWithOutDelete($data->id, $editRoute);
            })
            ->rawColumns(['fav icon', 'icon', 'action'])
            ->toJson();
    }
}
