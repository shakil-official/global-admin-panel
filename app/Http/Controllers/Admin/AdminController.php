<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.dashboard');
    }

    public function userView(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.users.index')->with([
            'title' => 'Users',
            'title_main' => 'Users List',
            'title_sub' => 'List',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('super-admin.user.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'users_table',
            'table_url' => route('super-admin.user.list'),
            'delete_url' => route('super-admin.user.delete'),
            'columns' => [
                "Name",
                "Email",
                "Status",
                "Action"
            ],
        ]);
    }


    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $formConfig = (new FormMaking())
            ->action(route('super-admin.user.store'))
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => 'Name',
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
                    'type' => 'email',
                    'name' => 'email',
                    'label' => 'Email',
                    'col' => 'col-md-12 mb-3',
                    'value' => old('email', ''),
                    'required' => true,
                    'validation_feedback' => 'Looks good!',
                ]
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                [
                    'type' => 'password',
                    'name' => 'password',
                    'label' => 'Password',
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
                    'col' => 'col-6 mb-3 ',
                    'required' => true,
                    'invalid_feedback' => 'More example invalid feedback text',
                ],
            ])
            ->endRow()
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'User Add',
                'col' => 'col-12 mb-3',
                'class' => 'btn btn-sm btn-primary',
                'icon' => 'ri-add-line',
            ])
            ->endRow()
            ->build();

        return view('backend.users.add')->with([
            'title' => 'Users Add',
            'title_main' => 'Users Add',
            'title_sub' => 'Add',
            'title_card_header' => '',
            'buttons' => [
                [
                    'label' => 'Back',
                    'url' => route('super.user.view'),
                    'icon' => 'ri-arrow-left-line',
                    'classes' => 'btn-sm btn-outline-danger',
                ],
            ],
            'formConfig' => $formConfig
        ]);
    }


    public function userStore(Request $request): RedirectResponse
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8', // Optional: add 'confirmed' to require password confirmation
        ]);

        // Create the new user
        $user = User::query()->create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        if ($user) {
            $this->roleAssign($user->id);
        }

        // Redirect to a success page or back to the form with a success message
        return redirect()->route('super.user.view')->with('success', 'User created successfully!');
    }


    public function delete(Request $request): JsonResponse
    {
        $data = User::query()->where(['id' => $request->input('id')])->delete();

        if ($data) {
            return response()->json([
                'message' => 'User deleted successfully',
                'status_code' => ResponseAlias::HTTP_OK,
                'data' => []
            ], ResponseAlias::HTTP_OK);
        }

        return response()->json([
            'message' => 'User delete failed',
            'status_code' => ResponseAlias::HTTP_BAD_REQUEST,
            'data' => []
        ], ResponseAlias::HTTP_BAD_REQUEST);
    }

    public function userList(Request $request): JsonResponse
    {
        $data = User::query()->select(['id', 'email', 'name', 'status', 'parent_id'])
            ->whereNull([
                'parent_id'
            ])
            ->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('status', function ($row) {
                return actionDropdownStatus($row->status);
            })
            ->addColumn('action', function ($row) {
                $deleteRoute = route('super-admin.user.delete', $row->id);
                return actionDropdownWithOutEdit($row->id, $deleteRoute);
            })
            ->rawColumns(['name', 'status', 'action']) // Allow HTML in the 'action' column
            ->toJson();

    }

    private function roleAssign($userIds): void
    {
        $permissions = [
            'manage_super' => 'manage_super',
        ];

        // Get selected users from request
        if (empty($userIds)) {
            redirect()->back()->with('error', 'No users selected.');
            return;
        }

        // Retrieve users
        $users = User::query()->where('id', $userIds)->get();

        if ($users->isEmpty()) {
            redirect()->back()->with('error', 'No valid users found.');
            return;
        }

        // Retrieve the "manage_super" role
        $role = Role::where('name', 'manage_super')->first();

        if (!$role) {
            redirect()->back()->with('error', 'Role manage_super not found.');
            return;
        }

        // Assign the role to all selected users (if not already assigned)
        foreach ($users as $user) {
            if (!$user->hasRole('manage_super')) {
                $user->assignRole($role);
            }
        }

        // Extract permission names
        $permissionNames = array_values($permissions);

        // Get existing permissions from the database
        $existingPermissions = Permission::whereIn('name', $permissionNames)
            ->where('guard_name', 'web')
            ->pluck('id', 'name')
            ->toArray();

        // Find missing permissions
        $missingPermissions = array_diff($permissionNames, array_keys($existingPermissions));

        // Insert missing permissions
        if (!empty($missingPermissions)) {
            $permissionsData = [];
            foreach ($missingPermissions as $permission) {
                $permissionsData[] = [
                    'name' => $permission,
                    'group_name' => 'manage_super',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Permission::insert($permissionsData);

            // Fetch newly inserted permissions
            $newPermissions = Permission::whereIn('name', $missingPermissions)
                ->where('guard_name', 'web')
                ->pluck('id', 'name')
                ->toArray();

            // Merge new permissions with existing ones
            $existingPermissions = array_merge($existingPermissions, $newPermissions);
        }

        // Sync permissions using their IDs
        $role->permissions()->sync(array_values($existingPermissions));

        // Assign permissions to each user without detaching existing ones
        foreach ($users as $user) {
            $user->permissions()->syncWithoutDetaching(array_values($existingPermissions));
        }
    }

    public function adminAssign(Request $request): RedirectResponse
    {
        // Get selected users from request
        $userIds = $request->input('id', []); // Expecting an array of user IDs

        if (empty($userIds)) {
            return redirect()->back()->with('error', 'No users selected.');
        }

        // Retrieve users
        $users = User::query()->where('id', $userIds)->get();

        if ($users->isEmpty()) {
            return redirect()->back()->with('error', 'No valid users found.');
        }

        $this->roleAssign($userIds);

        return redirect()->back()->with('success', 'Users assigned to manage_super role successfully with permissions.');
    }

}
