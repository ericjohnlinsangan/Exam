<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    public function test_get_users_paginated()
    {
        User::factory()->count(15)->create();

        $perPage = 5;
        $searchTerm = '';
        $paginatedUsers = $this->userService->getUsersPaginated($perPage, $searchTerm);

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginatedUsers);
        $this->assertEquals($perPage, $paginatedUsers->perPage());
        $this->assertEquals(15, $paginatedUsers->total());
    }

    public function test_create_user()
    {
        Role::create(['name' => 'admin']);
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ];

        $user = $this->userService->createUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_get_user_by_id()
    {
        $user = User::factory()->create();

        $foundUser = $this->userService->getUserById($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_update_user()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);
        Permission::create(['name' => 'create users', 'guard_name' => 'web']);
        $role->givePermissionTo('create users');

        $user = User::factory()->create();
        $user->assignRole($role);
        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'password' => 'newpassword123',
            'role' => 'admin',
        ];

        $updated = $this->userService->updateUser($user->id, $data);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        $this->assertTrue($user->fresh()->hasRole('admin'));
    }

    public function test_delete_user()
    {
        $user = User::factory()->create();

        $deleted = $this->userService->deleteUser($user->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
