<?php

namespace Modules\Slider\Http\Controllers;

use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Modules\Slider\Models\Slider;
use Modules\Slider\Services\Contracts\SliderServiceInterface;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{
    private SliderServiceInterface $service;

    public function __construct(SliderServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Slider::slider.index')
            ->with([
                'title' => 'Sliders',
                'buttons' => [
                    [
                        'label' => 'Add New Item',
                        'url' => route('slider.add'),
                        'icon' => 'ri-add-line',
                        'classes' => 'btn-sm btn-outline-primary',
                    ],
                ],
                'table' => 'slider_table',
                'columns' => [
                    "image",
                    "position",
                    "name",
                    "type",
                    "action"
                ],
            ]);
    }

    // ---------------- Add Slider ----------------
    public function add(): Factory|Application|View
    {
        $formConfig = (new FormMaking())
            ->action(route('slider.store'))
            ->method('POST')
            ->enctype('multipart/form-data')
            ->startRow()->addFormFields([
                ['type' => 'text', 'name' => 'name', 'label' => 'Name', 'col' => 'col-md-12 mb-3', 'value' => old('name'), 'required' => true],
                ['type' => 'text', 'name' => 'badge', 'label' => 'Badge', 'col' => 'col-md-12 mb-3', 'value' => old('badge')],
                ['type' => 'text', 'name' => 'headline', 'label' => 'Headline', 'col' => 'col-md-12 mb-3', 'value' => old('headline')],
                ['type' => 'textarea', 'name' => 'description', 'label' => 'Description', 'col' => 'col-md-12 mb-3', 'value' => old('description')],
            ])->endRow()
            ->startRow()
            ->repeatable([
                'name' => 'feature_pills',
                'label' => 'Feature Pills',
                'col' => 'col-md-12 mb-3',
                'placeholder' => 'Enter feature pill',
                'value' => old('feature_pills')
            ])
            ->endRow()
            ->startRow()->addFormFields([
                ['type' => 'number', 'name' => 'position', 'label' => 'Position', 'col' => 'col-md-6 mb-3', 'value' => old('position'), 'required' => true],
                ['type' => 'file', 'name' => 'image', 'label' => 'Upload Image', 'col' => 'col-md-6 mb-3', 'required' => true],
                ['type' => 'select_advance', 'name' => 'type', 'label' => 'Type', 'options' => [['value' => 'web', 'label' => 'Web'], ['value' => 'mobile', 'label' => 'Mobile']], 'col' => 'col-md-12 mb-3', 'required' => true],
                ['type' => 'text', 'name' => 'button_text', 'label' => 'Button Text', 'col' => 'col-md-6 mb-3', 'value' => old('button_text')],
                ['type' => 'text', 'name' => 'button_url', 'label' => 'Button URL', 'col' => 'col-md-6 mb-3', 'value' => old('button_url')],
                ['type' => 'text', 'name' => 'button_icon', 'label' => 'Button Icon', 'col' => 'col-md-6 mb-3', 'value' => old('button_icon')],
                ['type' => 'text', 'name' => 'button_classes', 'label' => 'Button Classes', 'col' => 'col-md-6 mb-3', 'value' => old('button_classes')],
                ['type' => 'checkbox', 'name' => 'overlay_enabled', 'label' => 'Enable Overlay', 'col' => 'col-md-12 mb-3', 'checked' => old('overlay_enabled', true)],
            ])->endRow()
            ->startRow()->addInput(['type' => 'submit', 'value' => 'Add Slider', 'col' => 'col-12 mb-3', 'icon' => 'ri-add-line', 'class' => 'btn btn-sm btn-primary'])->endRow()
            ->build();

        return view('Slider::slider.add')->with([
            'title' => 'Add Slider',
            'buttons' => [['label' => 'Back', 'url' => route('slider.index'), 'icon' => 'ri-corner-down-right-fill', 'classes' => 'btn-sm btn-outline-danger']],
            'formConfig' => $formConfig
        ]);
    }

    // ---------------- Edit Slider ----------------
    public function edit(Request $request, $id): Factory|View|Application
    {
        $data = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('slider.update', ['id' => $id]))
            ->method('POST')
            ->enctype('multipart/form-data')
            ->startRow()->addFormFields([
                ['type' => 'text', 'name' => 'name', 'label' => 'Name', 'col' => 'col-md-12 mb-3', 'value' => old('name', $data->name), 'required' => true],
                ['type' => 'text', 'name' => 'badge', 'label' => 'Badge', 'col' => 'col-md-12 mb-3', 'value' => old('badge', $data->badge)],
                ['type' => 'text', 'name' => 'headline', 'label' => 'Headline', 'col' => 'col-md-12 mb-3', 'value' => old('headline', $data->headline)],
                ['type' => 'textarea', 'name' => 'description', 'label' => 'Description', 'col' => 'col-md-12 mb-3', 'value' => old('description', $data->description)],
            ])->endRow()
            ->startRow()
            ->repeatable([
                'name' => 'feature_pills',
                'label' => 'Feature Pills',
                'col' => 'col-md-12 mb-3',
                'placeholder' => 'Enter feature pill',
                'value' => old('feature_pills', collect($data->feature_pills ?? [])->map(function($item) {
                    return is_object($item) ? ($item->label ?? '') : (is_array($item) ? ($item['label'] ?? '') : $item);
                })->filter()->toArray())
            ])
            ->endRow()
            ->startRow()->addFormFields([
                ['type' => 'number', 'name' => 'position', 'label' => 'Position', 'col' => 'col-md-6 mb-3', 'value' => old('position', $data->position), 'required' => true],
                ['type' => 'file', 'name' => 'image_file', 'label' => 'Upload Image', 'col' => 'col-md-6 mb-3', 'required' => false],
                ['type' => 'hidden', 'name' => 'old_image', 'value' => $data->image],
                ['type' => 'select_advance', 'name' => 'type', 'label' => 'Type', 'options' => [['value' => 'web', 'label' => 'Web'], ['value' => 'mobile', 'label' => 'Mobile']], 'col' => 'col-md-12 mb-3', 'value' => $data->type],
                ['type' => 'text', 'name' => 'button_text', 'label' => 'Button Text', 'col' => 'col-md-6 mb-3', 'value' => old('button_text', $data->button_text)],
                ['type' => 'text', 'name' => 'button_url', 'label' => 'Button URL', 'col' => 'col-md-6 mb-3', 'value' => old('button_url', $data->button_url)],
                ['type' => 'text', 'name' => 'button_icon', 'label' => 'Button Icon', 'col' => 'col-md-6 mb-3', 'value' => old('button_icon', $data->button_icon)],
                ['type' => 'text', 'name' => 'button_classes', 'label' => 'Button Classes', 'col' => 'col-md-6 mb-3', 'value' => old('button_classes', $data->button_classes)],
                ['type' => 'checkbox', 'name' => 'overlay_enabled', 'label' => 'Enable Overlay', 'col' => 'col-md-12 mb-3', 'checked' => old('overlay_enabled', $data->overlay_enabled)],
            ])->endRow()
            ->startRow()->addInput(['type' => 'submit', 'value' => 'Update Slider', 'col' => 'col-12 mb-3', 'icon' => 'ri-edit-line', 'class' => 'btn btn-sm btn-primary'])->endRow()
            ->build();

        return view('Slider::slider.edit')->with([
            'title' => 'Edit Slider',
            'buttons' => [['label' => 'Back', 'url' => route('slider.index'), 'icon' => 'ri-corner-down-right-fill', 'classes' => 'btn-sm btn-outline-danger']],
            'formConfig' => $formConfig
        ]);
    }

    // ---------------- Store Slider ----------------
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:sliders,name',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'position' => 'required|numeric|min:1',
            'type' => 'required|in:web,mobile',
            'feature_pills.*' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:255',
            'headline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'button_icon' => 'nullable|string|max:255',
            'button_classes' => 'nullable|string|max:255',
            'overlay_enabled' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $destinationPath = public_path('uploads/slider');
            if (!File::exists($destinationPath)) File::makeDirectory($destinationPath, 0775, true);
            $fileName = now()->format('Y-m-d') . '_' . uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->move($destinationPath, $fileName);
            $path = 'uploads/slider/' . $fileName;
        }

        DB::table('sliders')->where('position', '>=', $request->position)->increment('position');

        $overlayEnabled = $request->input('overlay_enabled', '0') == '1';

        // Convert feature_pills array to object format
        $featurePills = [];
        if ($request->feature_pills && is_array($request->feature_pills)) {
            foreach ($request->feature_pills as $pill) {
                if (!empty(trim($pill))) {
                    $featurePills[] = [
                        'icon' => 'check-circle',
                        'label' => trim($pill)
                    ];
                }
            }
        }

        $request->merge([
            'feature_pills' => $featurePills,
            'overlay_enabled' => $overlayEnabled,
            'user_id' => Auth::id(),
            'image' => $path
        ]);

        $slider = $this->service->create($request->only([
            'name', 'badge', 'headline', 'description', 'feature_pills', 'position', 'type',
            'button_text', 'button_url', 'button_icon', 'button_classes', 'overlay_enabled', 'image', 'user_id'
        ]));

        return $slider
            ? redirect()->route('slider.index')->with('success', 'Slider added successfully.')
            : redirect()->back()->with('error', 'Something went wrong.');
    }

    // ---------------- Update Slider ----------------
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => ['required', Rule::unique((new Slider)->getTable())->ignore($id)],
            'position' => 'required|numeric|min:1',
            'type' => 'required|in:web,mobile',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'feature_pills.*' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:255',
            'headline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'button_icon' => 'nullable|string|max:255',
            'button_classes' => 'nullable|string|max:255',
            'overlay_enabled' => 'nullable|string',
        ]);

        $slider = Slider::query()->findOrFail($id);


        $filePath = $request->input('old_image');

        if ($request->file('image_file')) {
            \Log::info('Image file detected: ' . $request->file('image_file')->getClientOriginalName());

            $uploadedFile = $request->file('image_file');
            $destinationPath = public_path('uploads/slider');
            if (!File::exists($destinationPath)) File::makeDirectory($destinationPath, 0775, true);
            $fileName = now()->format('Y-m-d') . '_' . uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->move($destinationPath, $fileName);
            $filePath = 'uploads/slider/' . $fileName;



            if ($request->input('old_image') && File::exists(public_path($request->input('old_image')))) {
                File::delete(public_path($request->input('old_image')));
                \Log::info('Old image deleted: ' . $request->input('old_image'));
            }
        } else {
            \Log::info('No image file uploaded, keeping old path: ' . $filePath);
        }

        $overlayEnabled = $request->input('overlay_enabled', '0') == '1';

        // Convert feature_pills array to object format
        $featurePills = [];
        if ($request->feature_pills && is_array($request->feature_pills)) {
            foreach ($request->feature_pills as $pill) {
                if (!empty(trim($pill))) {
                    $featurePills[] = [
                        'icon' => 'check-circle',
                        'label' => trim($pill)
                    ];
                }
            }
        }

        $request->merge([
            'image' => $filePath,
            'feature_pills' => $featurePills,
            'overlay_enabled' => $overlayEnabled,
            'user_id' => Auth::id(),

        ]);

        $updateData = $request->only([
            'name', 'badge', 'headline', 'description', 'feature_pills', 'position', 'type',
            'button_text', 'button_url', 'button_icon', 'button_classes', 'overlay_enabled', 'image', 'user_id'
        ]);



        $slider->update($updateData);


        return redirect()->route('slider.index')->with('success', 'Slider updated successfully.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->id);
        return response()->json(['message' => 'Slider deleted successfully', 'status_code' => ResponseAlias::HTTP_OK]);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('name', fn($d) => $d->name)->editColumn('status', fn($d) => $d->status)
            ->addColumn('image', function ($d) {
                return $d->image ? '<img src="' . asset($d->image) . '" style="width:50px;height:50px;object-fit:cover">' : 'No Image';
            })->addColumn('action', fn($d) => actionDropdown($d->id, route('slider.edit', $d->id), null))
            ->rawColumns(['image', 'action'])->toJson();
    }

}
