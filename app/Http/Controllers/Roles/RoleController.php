<?php

namespace App\Http\Controllers\Roles;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function roleView(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('template.index');
    }

    public function getTableData(): JsonResponse
    {
        $data = User::query()->select(['id', 'email', 'name'])
            ->where(['parent_id' => Helpers::owner_id()])
            ->with('roles')
            ->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('role_name', function ($row) {

                return $row->roles->pluck('name')->join(', ');
            })
            ->addColumn('action', function ($row) {
                return '<a href="' . route('user.assign-role-edit', ['userId' => $row->id]) . '" class="btn btn-sm btn-primary">Assign</a>';
            })
            ->make(true);
    }

    public function roleList(): JsonResponse
    {
        $data = Role::query()->orderBy('id', 'desc')->where(['owner_id' => Helpers::owner_id()]);
//        <a href="' . route('user.assign-role-edit', ['userId' => $row->id]) . '" class="btn btn-sm btn-primary">Edit</a>

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                return '<form action="' . route('role.role-delete', ['id' => $row->id]) . '" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(this);">
            ' . csrf_field() . '
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>';

            })
            ->make(true);
    }

    public function roleCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role-name' => 'required|unique:roles,name|max:50',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Role::create([
                'name' => $request->input('role-name'),
                'owner_id' => Helpers::owner_id(),
            ]);

            return redirect()->back()->with('success', 'Role created successfully.');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', 'something went wrong');

        }
    }

    public function roleDelete(Request $request): RedirectResponse
    {
        try {
            Role::query()->where('id', $request->input('id'))->delete();

            return redirect()->back()->with('success', 'Role deleted successfully.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'something went wrong');
        }
    }


    public function userAssignRoleEdit($userId): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $user = User::query()->findOrFail($userId);
        $roles = Role::query()->where([
            'owner_id' => Helpers::owner_id(), // Ensure the 'owner_id' matches
        ])->get();
        $assignedRoles = $user->roles->pluck('name')->toArray(); // Get assigned role names


        return view('roles.user-assign-role-edit')->with([
            'data' => $user,
            'roles' => $roles,
            'assignedRoles' => $assignedRoles // Pass assigned roles
        ]);
    }

    public function userAssignRoleUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'role' => 'required|exists:roles,name',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Try to decrypt the user ID
        try {
            $userId = decrypt($request->get('user_id'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid user ID');
        }

        // Find the user
        $user = User::query()
            ->select(['id'])
            ->where([
                'id' => $userId,
            ])->first();

        if ($user) {

            $user->syncRoles([$request->get('role')]);

            return redirect()->back()->with('success', 'Role assign successfully');
        }

        return redirect()->back()->with('error', 'User not found');
    }


    public function permissionList(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        $permissions = [
            'support_createId' => 'support.view.supportCreateId',
            'dashboard' => 'dashboard.dashboard.view',
            'mikrotik' => 'administration.mikrotik.view',
            'area' => 'administration.area.view',
            'port' => 'administration.port.view',
            'ip_pool' => 'administration.ip-pool.view',
            'queue_type' => 'administration.view.queue.type',
            'simpleQueue' => 'administration.view.simple.queue',
            'add_vlan' => 'administration.view.add.vlan',
            'target_ip' => 'administration.view.target.ip',
            'package' => 'administration.view.package',
            'support_details' => 'support.view.supportDetails',
            'users' => 'user.user.view',
            'finance_assets' => 'finance.assets.view',
            'finance_credit_entry' => 'finance.credit.entry.view',
            'finance_debit_entry' => 'finance.debit.entry.view',
            'queues' => 'administration.queues.view',
            'package_view' => 'administration.package.view',
            'interfaces' => 'administration.interfaces.view',
            'ppp' => 'administration.ppp.view',
            'categories' => 'admin.categories.index',
            'sub_categories' => 'admin.sub-categories.index',
            'others' => 'admin.view.expire.edit',
        ];

        $permissionGroups = Role::query()
            ->where([
                'owner_id' => Helpers::owner_id(), // Ensure the 'owner_id' matches
            ])
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        return view('roles.permission-list')->with([
            'permissions' => $permissions,
            'permissionGroups' => $permissionGroups
        ]);
    }

    public function getGroupPermissions(Request $request): JsonResponse
    {
        $groupId = $request->group_id;

        // অনুমতি লোড করুন
        $permissions = Role::where('id', $groupId)->with('permissions')->first();

        if (!$permissions) {
            return response()->json(['permissions' => []]);
        }

        // শুধুমাত্র অনুমতির নাম নিয়ে পাঠানো
        return response()->json([
            'permissions' => $permissions->permissions->pluck('name')->toArray()
        ]);
    }


    public function create(Request $request): RedirectResponse
    {
        // Retrieve the role by ID and owner_id
        $role = Role::query()
            ->where([
                'id' => $request->input('group_name'),
                'owner_id' => Helpers::owner_id(), // Ensure the 'owner_id' matches
            ])
            ->first();

        if (!$role) {
            return redirect()->back()->with('error', 'Role not found or owner mismatch.');
        }

        $permissions = $request->input('permissions');

        // Get existing permissions to skip duplicates
        $existingPermissions = Permission::query()
            ->whereIn('name', $permissions)
            ->where('guard_name', 'web')
            ->pluck('name')
            ->toArray();

        // Prepare the data for batch insert, skipping existing permissions
        $permissionsData = [];
        foreach ($permissions as $permission) {
            if (!in_array($permission, $existingPermissions)) {
                $permissionsData[] = [
                    'name' => $permission,
                    'group_name' => $role->name,
                    'guard_name' => 'web',
                    'owner_id' => Helpers::owner_id(), // Ensure this is correct
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Batch insert into the permissions table
        if (!empty($permissionsData)) {
            Permission::insert($permissionsData);
        }

        // Sync the permissions with the role
        if (!empty($permissions)) {
            // Ensure permissions exist in the database before syncing
            $permissions = Permission::whereIn('name', $permissions)
                ->where('guard_name', 'web')
                ->pluck('id')
                ->toArray();

            // Sync the permissions with the role using the permission IDs
            $role->syncPermissions($permissions);
        }

        return redirect()->back()->with('success', 'Group assigned successfully');
    }

}
