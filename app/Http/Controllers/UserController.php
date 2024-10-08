<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        $this->authorize('view users');

        $search = $request->input('search', '');
        $users = $this->userService->getUsersPaginated(5, $search);

        return view('users.index', compact('users', 'search'));
    }

    public function create(Request $request)
    {
        $this->authorize('create users');

        $search = $request->input('search', '');
        $roles = $this->roleService->getRoles();
        return view('users.create', compact('search', 'roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create users');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'role' => 'required|exists:roles,name',
        ]);
        $user = $this->userService->createUser($validatedData);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $this->authorize('edit users');

        $user = $this->userService->getUserById($id);
        $roles = $this->roleService->getRoles();

        return view('users.create', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit users');
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required|exists:roles,name',
            'password' => 'confirmed',
        ]);

        $updated = $this->userService->updateUser($id, $validatedData);

        if ($updated) {
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update user.');
        }
    }

    public function destroy($id)
    {
        $this->authorize('delete users');
        $success = $this->userService->deleteUser($id);
        if ($success) {
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->route('users.index')->with('error', 'Failed to delete user.');
        }
    }
}
