<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;
    protected $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        $this->authorize('view roles');

        $search = $request->input('search', '');
        $roles = $this->roleService->getRolesPaginated(5, $search);
        return view('roles.index', compact('roles', 'search'));
    }

    public function create()
    {
        $this->authorize('create roles');

        $permissions = $this->permissionService->getPermissions();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create roles');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);
        
        $roles = $this->roleService->createRole($validatedData);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $this->authorize('edit roles');

        $role = $this->roleService->getRoleById($id);
        $permissions = $this->permissionService->getPermissions();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.create', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit roles');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $updated = $this->roleService->updateRole($id, $validatedData);

        if ($updated) {
            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update role.');
    }

    public function destroy($id)
    {
        $this->authorize('delete roles');

        $success = $this->roleService->deleteRole($id);

        if ($success) {
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
        } else {
            return redirect()->route('roles.index')->with('error', 'Failed to delete role.');
        }
    }
}
