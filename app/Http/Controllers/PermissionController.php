<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        $this->authorize('view permissions');

        $search = $request->input('search', '');
        $permissions = $this->permissionService->getPermissionsPaginated(5, $search);
        return view('permissions.index', compact('permissions', 'search'));
    }
}
