<?php

namespace App\Http\Controllers\Feedback;

use App\Engine\Feedback\Services\Contracts\FeedbackServiceInterface;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
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

class FeedbackController extends Controller
{
    private FeedbackServiceInterface $service;

    public function __construct(FeedbackServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.feedback.index')->with([
            'title' => 'Feedbacks',
            'buttons' => [
                [
                    'label' => 'Add New Item',
                    'url' => route('feedback.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'feedback_table',
            'columns' => [
                "Title",
                "Name",
                "Rating",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('feedback.store'))
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Title',
                    'col' => 'col-md-12 mb-3',
                    'value' => '',
                    'required' => true,
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Name',
                    'col' => 'col-md-12 mb-3',
                    'value' => '',
                    'required' => true,
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'number',
                    'name' => 'rating',
                    'label' => 'Rating',
                    'col' => 'col-md-12 mb-3',
                    'value' => '',
                    'required' => true,
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'message',
                    'label' => 'Message',
                    'col' => 'col-md-12 mb-3',
                    'value' => '',
                    'required' => true,
                ]
            ])
            ->endRow()

            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Add Feedback',
                'col' => 'col-12 mb-3',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.feedback.add')->with([
            'title' => 'Feedback Add',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('feedback.index'),
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
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:feedbacks,name',
            'rating' => 'required|numeric|min:1|max:5',
            'message' => 'required|string',

        ]);

        $response = $this->service->create($request->all());

        if ($response) {
            return redirect()->route('feedback.index')->with('success', 'Feedback added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(Request $request, $id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find($id);

        $formConfig = (new FormMaking())
            ->action(route('feedback.update', ['id' => $id]))
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Title',
                    'col' => 'col-md-12 mb-3',
                    'value' => $data->title,
                    'required' => true,
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Name',
                    'col' => 'col-md-12 mb-3',
                    'value' => $data->name,
                    'required' => true,
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'number',
                    'name' => 'rating',
                    'label' => 'Rating',
                    'col' => 'col-md-12 mb-3',
                    'value' => $data->rating,
                    'required' => true,
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'message',
                    'label' => 'Message',
                    'col' => 'col-md-12 mb-3',
                    'value' => $data->message,
                    'required' => true,
                ]
            ])
            ->endRow()

            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update Feedback',
                'col' => 'col-6 mb-3',
                'class' => 'btn btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.feedback.edit')->with([
            'title' => 'Feedback Edit',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('feedback.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
                [
                    'label' => 'Back to List',
                    'url' => route('feedback.index'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-danger',
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
            'title' => 'required|string|max:255',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique((new Feedback)->getTable())->ignore($id),
            ],
            'rating' => 'required|numeric|min:1|max:5',
            'message' => 'required|string',

        ]);

        $response = $this->service->update($id, $request->all());

        if ($response) {
            return redirect()->route('feedback.index')->with('success', 'Feedback updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function delete(Request $request): JsonResponse
    {
        $data = $this->service->delete($request->input('id'));

        if ($data) {
            return response()->json([
                'message' => 'Feedback deleted successfully',
                'status_code' => ResponseAlias::HTTP_OK,
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'Feedback delete failed',
            'status_code' => ResponseAlias::HTTP_BAD_REQUEST,
            'data' => []
        ], ResponseAlias::HTTP_BAD_REQUEST);
    }

    public function dataTableList(Request $request): JsonResponse
    {
        $data = $this->service->dataTableData()->orderBy('id', 'desc');

        return DataTables::of($data)
            ->filter(function ($query) {
                if (request()->has('name') && !is_null(request('name'))) {
                    $query->where('name', 'like', '%' . request('name') . '%');
                }
            }, true)
            ->addColumn('title', fn($data) => $data->title)
            ->addColumn('name', fn($data) => $data->name)
            ->addColumn('rating', fn($data) => $data->rating)
            ->editColumn('status', fn($data) => ucfirst($data->status))
            ->addColumn('action', function ($data) {
                $editRoute = route('feedback.edit', $data->id);
                $viewRoute = null; // optional
                $deleteRoute = null;
                return actionDropdown($data->id, $editRoute, $viewRoute, $deleteRoute);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function list()
    {
        return $this->service->dataTableData()->orderBy('updated_at', 'desc')->get();
    }
}
