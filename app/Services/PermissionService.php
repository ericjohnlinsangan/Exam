<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function getPermissionsPaginated(int $perPage = 10, ?string $searchTerm = ''): LengthAwarePaginator
    {
        return Permission::where('name', 'like', "%{$searchTerm}%")
            ->paginate($perPage);
    }

    public function getPermissions()
    {
        return Permission::get();
    }
}
