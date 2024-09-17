<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function it_can_display_users_list()
    {
        $user = User::factory()->create(); // Create a user using a factory

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function it_can_show_create_user_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    public function it_can_store_a_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('users.store'), [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
        ]);
    }

    public function it_can_show_edit_user_form()
    {
        $user = User::factory()->create();
        $editUser = User::factory()->create();

        $response = $this->actingAs($user)->get(route('users.edit', $editUser->id));

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
        $response->assertViewHas('user', $editUser);
    }

    public function it_can_update_a_user()
    {
        $user = User::factory()->create();
        $editUser = User::factory()->create();

        $response = $this->actingAs($user)->put(route('users.update', $editUser->id), [
            'name' => 'Updated User',
            'email' => 'updateduser@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Updated User',
            'email' => 'updateduser@example.com',
        ]);
    }

    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();
        $deleteUser = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('users.destroy', $deleteUser->id));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $deleteUser->id,
        ]);
    }
}
