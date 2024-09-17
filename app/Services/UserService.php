<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function getUsersPaginated(int $perPage = 10, ?string $searchTerm = ''): LengthAwarePaginator
    {
        return User::where('name', 'like', "%{$searchTerm}%")
            ->orWhere('email', 'like', "%{$searchTerm}%")
            ->paginate($perPage);
    }
    
    public function createUser(array $data): User
    {
        if (! isset($data['password'])) {
            $data['password'] = bcrypt('Password123');
        }
        $user = User::create($data);

        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
        }
    
        return $user;
    }

    public function getUserById($id): ?User
    {
        return User::find($id);
    }
    
    public function updateUser($id, array $data): bool
    {
        $user = $this->getUserById($id);

        if ($user) {
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']); 
            }
    
            $user->update($data);
    
            if (isset($data['role'])) {
                $user->syncRoles([$data['role']]);
            }
    
            return true;
        }
    
        return false;
    }

    public function deleteUser($data): bool
    {
        $user = $this->getUserById($data);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
