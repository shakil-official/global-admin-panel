<?php

namespace App\Http\Controllers\SliderImage;

use App\Engine\SliderImage\Services\Contracts\SliderImageServiceInterface;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use App\Models\SliderImage;
use Exception;
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
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class SliderImageController extends Controller
{
    private SliderImageServiceInterface $service;

    public function __construct(SliderImageServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.slider_image.index')->with([
            'title' => 'Slider',
            'buttons' => [
                [
                    'label' => 'Add',
                    'url' => route('slider_image.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'slider_image_table',
            'columns' => [
                "image",
                "position",
                "name",
                "action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('slider_image.store'))
            ->method('POST')
            ->enctype('multipart/form-data')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Name',
                    'placeholder' => 'Enter name',
                    'col' => 'col-md-12 mb-3',
                    'value' => old('name', ''),
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                [
                    'type' => 'number',
                    'name' => 'position',
                    'label' => 'Position',
                    'col' => 'col-md-12 mb-3',
                    'value' => old('position', ''),
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()


            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'image',
                    'label' => 'Upload Image',

                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                    'invalid_feedback' => 'Please upload a valid image.',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Add Image',
                'col' => 'col-12 mb-3',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.slider_image.add')->with([
            'title' => 'Brand Image Add',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('slider_image.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'formConfig' => $formConfig
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:slider_images,name',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'position' => 'required|numeric|min:1',
        ]);

        if (!$request->hasFile('image')) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }

        $path = null;

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');

            // Define the destination path inside the public/uploads/slider folder
            $destinationPath = public_path('uploads/slider');

            // Ensure the directory exists
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true); // Create the directory if it doesn't exist
            }

            $date = now()->format('Y-m-d'); // Get the current date in 'YYYY-MM-DD' format

            // Get the original file name
            $fileName = $date . '_'. uniqid() . '_' .  $uploadedFile->getClientOriginalName();

            // Move the uploaded file to the public/uploads/slider directory
            $uploadedFile->move($destinationPath, $fileName);

            // Define the file path relative to the public directory
            $path = "uploads/slider/" . $fileName;
        }


        // Adjust positions of existing slider images
        $providedPosition = $request->get('position');

        DB::table('slider_images')
            ->where('position', '>=', $providedPosition)
            ->increment('position');


        $response = $this->service->create([
            'name' => $request->get('name'),
            'position' => $providedPosition,
            'path' => $path,
            'user_id' => Auth::id(),
        ]);

        if ($response) {
            return redirect()->route('slider_image.index')->with('success', 'Slider Image added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('slider_image.update', ['id' => $id]))
            ->method('POST')
            ->enctype('multipart/form-data')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Name',
                    'col' => 'col-md-12 mb-3',
                    'value' => old('name', $data->name),
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                [
                    'type' => 'number',
                    'name' => 'position',
                    'label' => 'Position',
                    'col' => 'col-md-12 mb-3',
                    'value' => old('position', $data->position),
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'image',
                    'label' => 'Upload Image',
                    'col' => 'col-md-12 mb-3',
                    'required' => false,
                    'invalid_feedback' => 'Please upload a valid image.',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'hidden',
                'name' => 'old_image',
                'label' => '',
                'col' => 'col-md-12',
                'value' => old('old_image', $data->path),
                'id' => 'old_image',
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update Slider',
                'col' => 'col-6 mb-3',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.slider_image.edit')->with([
            'title' => 'Edit Slider',
            'buttons' => [
                [
                    'label' => 'Back to List',
                    'url' => route('slider_image.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-danger',
                ],
            ],
            'formConfig' => $formConfig,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique((new SliderImage)->getTable())->ignore($id),
            ],
            'position' => 'required|numeric|min:1',
        ]);

        // Adjust positions if the position is changing
        $sliderImage = SliderImage::query()->findOrFail($id);// Get the current and new positions
        $currentPosition = $sliderImage->position;
        $newPosition = $request->input('position');// If the position remains unchanged, update only the name or other fields


        // image work here
        $filePath = $request->input("old_image");


        if ($request->file('image')) {

            $uploadedFile = $request->file('image');

            // Define the destination path for the new file
            $destinationPath = public_path('uploads/slider');

            // Ensure the directory exists
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true); // Create the directory if it doesn't exist
            }

            $date = now()->format('Y-m-d'); // Get the current date in 'YYYY-MM-DD' format

            // Get the original file name
            $fileName = $date .'_'. uniqid() . '_' .  $uploadedFile->getClientOriginalName();

            // Move the uploaded file to the public/uploads/slider directory
            $uploadedFile->move($destinationPath, $fileName);

            // Define the file path for the new image
            $filePath = "uploads/slider/" . $fileName;

            // Get the path of the old image
            $oldImagePath = $request->input('old_image');

            // Delete the old image if it exists
            if ($oldImagePath && File::exists(public_path($oldImagePath))) {
                File::delete(public_path($oldImagePath));
            }
        }


        if ($currentPosition == $newPosition) {
            $sliderImage->update([
                'name' => $request->get('name'),
                'path' => $filePath,
                'user_id' => Auth::id(),
                'position' => $newPosition,
            ]);

            return redirect()->route('slider_image.index')->with('success', 'Brand updated successfully.');
        }


        // Adjust positions of other slider_images
        if ($newPosition < $currentPosition) {
            // Shift slider_images down (increment position) between the new and current positions
            DB::table('slider_images')
                ->where('id', '!=', $id)
                ->whereBetween('position', [$newPosition, $currentPosition - 1])
                ->increment('position');
        } elseif ($newPosition > $currentPosition) {
            // Shift slider_images up (decrement position) between the current and new positions
            DB::table('slider_images')
                ->where('id', '!=', $id)
                ->whereBetween('position', [$currentPosition + 1, $newPosition])
                ->decrement('position');
        }

        // Update the category's position and other fields

        $response = $sliderImage->update(array_merge([
            'id' => $id,
            'name' => $request->get('name'),
            'path' => $filePath,
            'user_id' => Auth::id(),
        ], ['position' => $newPosition]));


        if ($response) {
            return redirect()->route('slider_image.index')->with('success', 'SliderImage updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function delete(Request $request): JsonResponse
    {
        $data = $this->service->delete($request->input('id'));

        if ($data) {
            return response()->json([
                'message' => 'SliderImage deleted successfully',
                'status_code' => ResponseAlias::HTTP_OK,
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'SliderImage delete failed',
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
        $data = $this->service->dataTableData()->orderBy('position')->orderBy('updated_at', 'desc');

        return DataTables::of($data)
            ->filter(function ($query) {
                if (request()->has('name') && !is_null(request('name'))) {
                    $query->where('name', 'like', '%' . request('name') . '%');
                }
            }, true)
            ->addColumn('name', function ($data) {
                return $data->name;
            })
            ->editColumn('status', function ($data) {
                return $data->status;
            })
            ->addColumn('image', function ($data) {

                $imagePath = isset($data->path) ? asset( $data->path) : null; // Generate the URL or leave it null
                return $imagePath
                    ? '<img src="' . $imagePath . '" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;">'
                    : 'No Image'; // Return image tag or fallback text
            })
            ->addColumn('action', function ($data) {
                $editRoute = route('slider_image.edit', $data->id);
                return actionDropdown($data->id, $editRoute);
            })
            ->rawColumns(['action', 'image'])
            ->toJson();
    }
}
