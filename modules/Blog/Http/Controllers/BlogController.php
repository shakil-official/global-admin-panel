<?php

namespace Modules\Blog\Http\Controllers;


use Modules\Blog\Services\Contracts\BlogServiceInterface;

use Modules\Blog\Models\Blog;
use Modules\Category\Models\Category;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    private BlogServiceInterface $service;

    public function __construct(BlogServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Blog::blog.index')->with([
            'title' => 'Blogs',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('blog.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'blog_table',
            'columns' => [
                "Image",
                "Title",
                "Slug",
                "Category",
                "Type",
                "Status",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('blog.store'))
            ->enctype('multipart/form-data')
            ->method('POST')
            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Blog Title',
                    'value' => old('title'),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()


            // Short Description
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'short_description',
                    'label' => 'Short Description',
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                    'maxlength' => 255,
                    'value' => old('short_description')
                ],
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
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'select_advance',
                    'name' => 'category_id',
                    'label' => 'Category',
                    'options' => Category::query()->where('status', 'active')->pluck('name', 'id')->map(function ($name, $id) {
                        return ['value' => $id, 'label' => $name];
                    })->values()->toArray(),
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ],
                [
                    'type' => 'select_advance',
                    'name' => 'type',
                    'label' => 'Type',
                    'options' => [
                        [
                            'value' => 'blog',
                            'label' => 'Blog'
                        ],
                        [
                            'value' => 'coverage',
                            'label' => 'Coverage'
                        ],
                        [
                            'value' => 'internet',
                            'label' => 'Internet'
                        ],
                    ],
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                ],
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'image',
                    'label' => 'Blog Image',
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
                'value' => 'Add Blog',
                'icon' => 'ri-save-line',
                'class' => 'btn btn-sm btn-primary',
                'col' => 'col-12 mb-3',
            ])
            ->endRow()
            ->build();

        return view('Blog::blog.add')
            ->with([
                'title' => 'Blog Add',
                'buttons' => [
                    [
                        'label' => 'Back',
                        'url' => route('blog.index'),
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
            'title' => 'required|unique:blogs,title',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'required|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:coverage,blog,internet',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = $request->only(['title', 'slug', 'description', 'status', 'short_description', 'type', 'category_id']);

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
            return redirect()->route('blog.index')
                ->with('success', 'Blog added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('blog.update', ['id' => $id]))
            ->enctype('multipart/form-data')
            ->method('POST')

            // Title
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Blog Title',
                    'value' => old('title', $data->title),
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                ]
            ])
            ->endRow()

            // Short Description
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'short_description',
                    'label' => 'Short Description',
                    'col' => 'col-md-12 mb-3',
                    'required' => true,
                    'value' => old('short_description', $data->short_description)
                ],
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
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'select_advance',
                    'name' => 'category_id',
                    'label' => 'Category',
                    'options' => Category::where('status', 'active')->pluck('name', 'id')->map(function ($name, $id) {
                        return ['value' => $id, 'label' => $name];
                    })->values()->toArray(),
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                    'value' => $data->category_id
                ],
                [
                    'type' => 'select_advance',
                    'name' => 'type',
                    'label' => 'Type',
                    'options' => [
                        [
                            'value' => 'blog',
                            'label' => 'Blog'
                        ],
                        [
                            'value' => 'coverage',
                            'label' => 'Coverage'
                        ],
                        [
                            'value' => 'internet',
                            'label' => 'Internet'
                        ],
                    ],
                    'col' => 'col-md-6 mb-3',
                    'required' => true,
                    'value' => $data->type
                ],
            ])
            ->endRow()


            // Image (optional on edit)
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'image',
                    'label' => 'Blog Image',
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

        return view('Blog::blog.edit')->with([
            'title' => 'Blog Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('blog.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back',
                    'url' => route('blog.index'),
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
                Rule::unique((new Blog)->getTable())->ignore($id),
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'required|max:300',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:coverage,blog,internet',
            'category_id' => 'required|exists:categories,id',
        ]);

        $blog = $this->service->find($id);

        if (!$blog) {
            return redirect()->back()->with('error', 'Offer not found.');
        }

        $data = $request->only(['title', 'slug', 'description', 'status', 'short_description', 'type', 'category_id']);

        if ($request->hasFile('image')) {

            $uploadPath = public_path('uploads/offers');

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Remove old image
            if ($blog->image && File::exists(public_path($blog->image))) {
                File::delete(public_path($blog->image));
            }

            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $fileName);

            $data['image'] = 'uploads/offers/' . $fileName;
        }

        $data['user_id'] = auth()->user()->id;

        $response = $this->service->update($id, $data);


        if ($response) {
            return redirect()->route('blog.index')
                ->with('success', 'Blog updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong.');
    }

    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->input('id'));

        return response()->json([
            'message' => 'Blog deleted successfully',
            'status_code' => ResponseAlias::HTTP_OK,
        ]);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->with([
            'category'
        ])->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('image', function ($data) {
                $imagePath = isset($data->image) ? asset($data->image) : null; // Generate the URL or leave it null
                return $imagePath
                    ? '<img src="' . $imagePath . '" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;">'
                    : 'No Image'; // Return image tag or fallback text
            })
            ->addColumn('category', function ($data) {
                return $data->category ? badge($data->category->name, 'primary') : badge('No Category', 'secondary');
            })
            ->addColumn('type', function ($data) {
                $typeConfig = [
                    'blog' => ['label' => 'Blog', 'color' => 'info'],
                    'upstream' => ['label' => 'Upstream', 'color' => 'warning'],
                ];

                $config = $typeConfig[$data->type] ?? ['label' => ucfirst($data->type), 'color' => 'secondary'];
                return badge($config['label'], $config['color']);
            })
            ->addColumn('status', function ($data) {
                return badge(strtoupper($data->status), $data->status);
            })
            ->addColumn('action', function ($data) {
                $editRoute = route('blog.edit', $data->id);
                $viewRoute = null; // optional
                return actionDropdown($data->id, $editRoute, $viewRoute);
            })
            ->rawColumns(['action', 'image', 'category', 'type', 'status'])
            ->toJson();
    }
}
