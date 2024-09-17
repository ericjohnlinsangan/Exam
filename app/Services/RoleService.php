<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function getRolesPaginated(int $perPage = 10, ?string $searchTerm = ''): LengthAwarePaginator
    {
        return Role::where('name', 'like', "%{$searchTerm}%")
            ->paginate($perPage);
    }

    public function getRoles()
    {
        return Role::get();
    }

    public function createRole(array $data): Role
    {
        $role = Role::create(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    public function getRoleById($id): ?Role 
    {
        return Role::findById($id);
    }

    public function updateRole($id, array $data): bool
    {
        $role = Role::findById($id);
        if ($role) {
            // Update role name
            $role->name = $data['name'];
            $role->save();

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            return true;
        }

        return false;
    }

    public function deleteRole($id): bool
    {
        $role = Role::findById($id);

        if ($role) {
            return $role->delete();
        }

        return false;
    }
}
