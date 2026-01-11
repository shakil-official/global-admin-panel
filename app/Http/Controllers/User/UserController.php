<?php

namespace App\Http\Controllers\User;

use App\Engine\User\Services\Contracts\UserServiceInterface;
use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use App\Models\About;
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

class UserController extends Controller
{
    private UserServiceInterface $service;

    public function __construct(UserServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.users.index')->with([
            'title' => 'Abouts',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('user.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'users_table',
            'columns' => [
                "Name",
                "Status",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('user.store'))
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Name',
                    'col' => 'col-md-12 mb-3',
                    'value' => '',
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'radio',
                    'name' => 'status',
                    'label' => 'Select status',
                    'options' => [
                        ['value' => 'active', 'label' => 'Active', 'checked' => 'active'],
                        ['value' => 'inactive', 'label' => 'Inactive', 'checked' => ''],
                    ],
                    'col' => 'col-6 mb-3',
                    'required' => true,
                    'invalid_feedback' => 'More example invalid feedback text',
                ],
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Submit form',
                'col' => 'col-12 mb-3',
                'class' => 'btn btn-sm btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.users.add')->with([
            'title' => 'About Add',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('user.index'),
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
            'name' => 'required|unique:abouts,name',
            'status' => 'required',
        ]);

        $response = $this->service->create($request->all());

        if ($response) {
            return redirect()->route('user.index')->with('success', 'About added successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function edit(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->service->find(1);

        $formConfig = (new FormMaking())
            ->action(route('user.update', ['id' => 1]))
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
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'custom',
                    'name' => 'description',
                    'value' => $data->description,
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
            ->addInput([
                'type' => 'submit',
                'value' => 'Update user',
                'col' => 'col-6 mb-3',
                'class' => 'btn btn-primary',
            ])
            ->endRow()
            ->build();

        return view('backend.users.edit')->with([
            'title' => 'User Edit',
            'buttons' => [],
            'formConfig' => $formConfig,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'title' => [
                'required',
                Rule::unique((new About)->getTable())->ignore($id),
            ],
            'description' => 'required',
        ]);

        $response = $this->service->update($id, $request->all());

        if ($response) {
            return redirect()->route('user.edit')->with('success', 'About updated successfully.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }

    public function delete(Request $request): JsonResponse
    {
        $data = $this->service->delete($request->input('id'));

        if ($data) {
            return response()->json([
                'message' => 'About deleted successfully',
                'status_code' => ResponseAlias::HTTP_OK,
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'About delete failed',
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
        $data = $this->service->dataTableData()->orderBy('id', 'desc');

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
            ->addColumn('action', function ($data) {
                $editRoute = route('user.edit', $data->id);
                $viewRoute = route('user.edit', $data->id); // Optional, replace with actual route if needed
                $deleteRoute = null; // Replace with delete route if needed
                return actionDropdown($data->id, $editRoute, $viewRoute, $deleteRoute);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
