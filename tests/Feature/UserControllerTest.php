<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_display_users_list()
    {
        $user = User::factory()->create();
        Permission::create(['name' => 'view users', 'guard_name' => 'web']);
        $user->givePermissionTo('view users');

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function test_it_can_show_create_user_form()
    {
        $user = User::factory()->create();
        Permission::create(['name' => 'create users', 'guard_name' => 'web']);
        $user->givePermissionTo('create users');
        $response = $this->actingAs($user)->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    public function test_it_can_store_a_user()
    {
        $role = Role::create(['name' => 'admin']);
        Permission::create(['name' => 'create users', 'guard_name' => 'web']);
        $role->givePermissionTo('create users');

        $user = User::factory()->create();
        $user->assignRole($role);

        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role' => $role->name
        ];

        $response = $this->actingAs($user)->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
        ->post(route('users.store'), $data);

        $response->assertRedirect(route('users.create'));
        $response->assertSessionHas('success', 'User created successfully.');

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email']
        ]);
    }

    public function test_it_can_show_edit_user_form()
    {
        $role = Role::create(['name' => 'admin']);
        Permission::create(['name' => 'edit users', 'guard_name' => 'web']);
        $role->givePermissionTo('edit users');

        $user = User::factory()->create();
        $user->assignRole($role);

        $userToEdit = User::factory()->create();

        $response = $this->actingAs($user)->get(route('users.edit', $userToEdit->id));

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
        $response->assertViewHas('user', $userToEdit);
        $response->assertViewHas('roles');

        $response->assertSee('Edit User'); 
    }

    public function test_it_can_update_a_user()
    {
       $adminRole = Role::create(['name' => 'admin']);
       Permission::create(['name' => 'edit users', 'guard_name' => 'web']);
       $adminRole->givePermissionTo('edit users');

       $userRole = Role::create(['name' => 'user']);

       $user = User::factory()->create();
       $user->assignRole($adminRole);

       $userToUpdate = User::factory()->create();
       $userToUpdate->assignRole($userRole);

       $response = $this->actingAs($user)->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)->put(route('users.update', $userToUpdate->id), [
           'name' => 'Updated User',
           'email' => 'updateduser@example.com',
           'role' => $userRole->name, 
           'password' => 'newpassword',
           'password_confirmation' => 'newpassword'
       ]);

       $response->assertRedirect(route('users.index'));

       $this->assertDatabaseHas('users', [
           'id' => $user->id,
           'name' => $user->name,
           'email' => $user->email,
       ]);
    }

    public function test_it_can_delete_a_user()
    {
        $user = User::factory()->create();
        Permission::create(['name' => 'delete users', 'guard_name' => 'web']);
        $user->givePermissionTo('delete users');
        $userToDelete = User::factory()->create();

        $user->givePermissionTo('delete users');
    
        $response = $this->actingAs($user)
                        ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
                        ->delete(route('users.destroy', $userToDelete->id));
    
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }
}
