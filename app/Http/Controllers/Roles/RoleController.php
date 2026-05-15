<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ModuleScannerService;
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
        return view('backend.roles.index')->with([
            'title' => 'Roles',
            'title_main' => 'Roles Management',
            'title_sub' => 'List',
            'buttons' => [
                [
                    'label' => 'Add New',
                    'url' => route('roles.add'),
                    'icon' => 'ri-add-line',
                    'classes' => 'btn-sm btn-outline-primary',
                ],
            ],
            'table' => 'rolesTable',
            'table_url' => route('role.role-list'),
            'delete_url' => route('role.role-delete'),
            'columns' => [
                "ID",
                "Name",
                "Created At",
                "Action"
            ],
        ]);
    }

    public function add(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.roles.add')->with([
            'title' => 'Add Role',
            'title_main' => 'Roles Management',
            'title_sub' => 'Create'
        ]);
    }

    public function permissionSettings(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.roles.settings')->with([
            'title' => 'Permission Settings',
            'title_main' => 'Permission Management',
            'title_sub' => 'Settings'
        ]);
    }

    public function permissionAudit(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.roles.audit')->with([
            'title' => 'Permission Audit',
            'title_main' => 'Permission Management',
            'title_sub' => 'Audit Log'
        ]);
    }

    public function getTableData(): JsonResponse
    {
        $data = User::query()->select(['id', 'email', 'name']);

        // Add parent_id filter only if column exists
        try {
            if (\Schema::hasColumn('users', 'parent_id')) {
                $data->where(['parent_id' => owner_id()]);
            }
        } catch (\Exception $e) {
            // Column doesn't exist or schema check failed, continue without filter
        }

        $data->with('roles')->orderBy('id', 'desc');

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
        $data = Role::query()->orderBy('id', 'desc');

        // Add owner_id filter only if column exists
        try {
            if (\Schema::hasColumn('roles', 'owner_id')) {
                $data->where(['owner_id' => owner_id()]);
            }
        } catch (\Exception $e) {
            // Column doesn't exist or schema check failed, continue without filter
        }

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                return '<form action="' . route('role.role-delete', ['id' => $row->id]) . '" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(this);">
            ' . csrf_field() . '
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
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
            $roleData = [
                'name' => $request->input('role-name'),
            ];

            // Add owner_id only if column exists
            try {
                if (\Schema::hasColumn('roles', 'owner_id')) {
                    $roleData['owner_id'] = owner_id();
                }
            } catch (\Exception $e) {
                // Column doesn't exist, continue without owner_id
            }

            // Add guard_name if column exists
            try {
                if (\Schema::hasColumn('roles', 'guard_name')) {
                    $roleData['guard_name'] = 'web';
                }
            } catch (\Exception $e) {
                // Column doesn't exist, continue without it
            }

            $role = Role::create($roleData);

            // Check permission settings and auto-assign basic permissions if enabled
            $permissionSettings = $this->getPermissionSettings();

            if ($permissionSettings['autoAssignPermissions']) {
                $this->assignBasicPermissions($role);
            }

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
        try {
            $user = User::query()->findOrFail($userId);

            $rolesQuery = Role::query();

            // Add owner_id filter only if column exists
            try {
                if (\Schema::hasColumn('roles', 'owner_id')) {
                    $rolesQuery->where(['owner_id' => owner_id()]);
                }
            } catch (\Exception $e) {
                // Column doesn't exist, continue without filter
            }

            $roles = $rolesQuery->get();

            // Get assigned roles using the HasRoles trait
            $assignedRoles = $user->roles()->pluck('name')->toArray();

            return view('backend.users.assign-role')->with([
                'data' => $user,
                'roles' => $roles,
                'assignedRoles' => $assignedRoles,
                'title' => 'Assign Role',
                'title_main' => 'User Role Assignment',
                'title_sub' => 'Edit'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('super.user.view')
                ->with('error', 'User not found.');
        } catch (\Exception $e) {
            return redirect()->route('super.user.view')
                ->with('error', 'Error loading user data: ' . $e->getMessage());
        }
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
        $permissionGroupsQuery = Role::query();

        // Add owner_id filter only if column exists
        try {
            if (\Schema::hasColumn('roles', 'owner_id')) {
                $permissionGroupsQuery->where(['owner_id' => owner_id()]);
            }
        } catch (\Exception $e) {
            // Column doesn't exist, continue without filter
        }

        $permissionGroups = $permissionGroupsQuery->get()
            ->pluck('name', 'id')
            ->toArray();

        return view('backend.roles.permissions')->with([
            'permissionGroups' => $permissionGroups,
            'title' => 'Permissions',
            'title_main' => 'Permission Management',
            'title_sub' => 'Assign'
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
        \Log::info('Permission assignment started', ['request' => $request->all()]);

        // Get role
        $role = Role::find($request->input('group_name'));
        \Log::info('Role found', ['role_id' => $request->input('group_name'), 'role' => $role ? $role->name : 'NOT FOUND']);

        if (!$role) {
            return redirect()->back()->with('error', 'Role not found.');
        }

        // Get permissions from request
        $permissions = $request->input('permissions', []);
        \Log::info('Permissions from request', ['permissions' => $permissions, 'count' => count($permissions)]);

        // Handle custom permissions
        if ($request->has('custom_permissions')) {
            $customPermissions = explode(',', $request->input('custom_permissions'));
            $customPermissions = array_map('trim', $customPermissions);
            $customPermissions = array_filter($customPermissions);
            $permissions = array_merge($permissions, $customPermissions);
        }

        // Filter out empty permissions
        $permissions = array_filter($permissions, function ($permission) {
            return !empty(trim($permission));
        });

        // Remove duplicates
        $permissions = array_unique($permissions);
        \Log::info('Processed permissions', ['permissions' => $permissions, 'count' => count($permissions)]);

        // Find or create all permissions
        $permissionIds = [];
        foreach ($permissions as $permission) {
            $permissionModel = Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
            \Log::info('Permission processed', ['name' => $permission, 'id' => $permissionModel->id]);
            $permissionIds[] = $permissionModel->id;
        }

        \Log::info('Permission IDs to sync', ['ids' => $permissionIds, 'role_id' => $role->id]);

        // Sync permissions with role
        try {
            $role->syncPermissions($permissionIds);
            \Log::info('Permissions synced successfully');

            // Verify the sync worked
            $assignedPerms = $role->permissions()->pluck('name')->toArray();
            \Log::info('Verified assigned permissions', ['count' => count($assignedPerms), 'permissions' => $assignedPerms]);
        } catch (\Exception $e) {
            \Log::error('Error syncing permissions', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error assigning permissions: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Permissions assigned successfully');
    }

    /**
     * Get permission settings (simulated from localStorage or defaults)
     */
    private function getPermissionSettings()
    {
        // In a real application, these would come from database or config
        // For now, we'll use default values that match the frontend defaults
        return [
            'autoAssignPermissions' => true,
            'inheritParentPermissions' => true,
            'strictMode' => false
        ];
    }

    /**
     * Assign basic permissions to a new role
     */
    private function assignBasicPermissions($role)
    {
        $basicPermissions = [
            'dashboard.view',
            'profile.view',
            'profile.update'
        ];

        // Ensure permissions exist before assigning
        $existingPermissions = Permission::whereIn('name', $basicPermissions)
            ->where('guard_name', 'web')
            ->get();

        if ($existingPermissions->isNotEmpty()) {
            $role->syncPermissions($existingPermissions);
        }
    }

    /**
     * Get all modules with their permissions
     */
    public function getModulesPermissions(Request $request): JsonResponse
    {
        try {
            $moduleScanner = new ModuleScannerService();
            $modulesPermissions = $moduleScanner->getPermissionsByModule();

            // Static default permissions for specific modules
            $staticPermissions = [
                'contact' => [
                    'contact.view',
                    'contact.edit',
                    'contact.update',
                    'contact.create',
                    'contact.delete',
                ],
                'extra' => [
                    'package.request',
                    'package.list-request',
                    'section_term.edit',
                    'about.edit',
                    'about.update',
                    'section.term.update',
                ]
            ];

            // Merge static permissions with dynamic module permissions
            foreach ($staticPermissions as $module => $permissions) {
                if (!isset($modulesPermissions[$module])) {
                    $modulesPermissions[$module] = $permissions;
                } else {
                    // Merge and remove duplicates
                    $modulesPermissions[$module] = array_unique(array_merge($modulesPermissions[$module], $permissions));
                }
            }

            return response()->json($modulesPermissions);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load modules: ' . $e->getMessage()], 500);
        }
    }

}
