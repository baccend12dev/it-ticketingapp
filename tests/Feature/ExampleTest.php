<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated guest redirects to login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');

        $response = $this->get('/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Login screen displays successfully.
     */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign In');
    }

    /**
     * Users can authenticate using the login screen.
     */
    public function test_users_can_authenticate(): void
    {
        $user = User::create([
            'name' => 'Test Admin',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin-test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    /**
     * Inactive users cannot authenticate.
     */
    public function test_inactive_users_cannot_authenticate(): void
    {
        $user = User::create([
            'name' => 'Deactivated User',
            'email' => 'deactivated@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'deactivated@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test role-based authorization: user cannot view admin panel.
     */
    public function test_regular_user_cannot_access_user_directory(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/users');
        $response->assertStatus(403);
    }

    /**
     * Test role-based authorization: admin can view admin panel.
     */
    public function test_admin_can_access_user_directory(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/users');
        $response->assertStatus(200);
        $response->assertSee('User Directory');
    }

    /**
     * Test admin toggling active status of other users.
     */
    public function test_admin_can_toggle_user_active_status(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post("/users/{$targetUser->id}/toggle-status");
        $response->assertStatus(302);
        
        $this->assertFalse($targetUser->fresh()->is_active);

        // Toggle back to active
        $response = $this->actingAs($admin)->post("/users/{$targetUser->id}/toggle-status");
        $response->assertStatus(302);
        
        $this->assertTrue($targetUser->fresh()->is_active);
    }

    /**
     * Test admin cannot deactivate themselves.
     */
    public function test_admin_cannot_toggle_self_active_status(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post("/users/{$admin->id}/toggle-status");
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertTrue($admin->fresh()->is_active);
    }

    /**
     * Test admin can delete other users.
     */
    public function test_admin_can_delete_user(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete("/users/{$targetUser->id}");
        $response->assertStatus(302);
        
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }

    /**
     * Test admin cannot delete themselves.
     */
    public function test_admin_cannot_delete_self(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete("/users/{$admin->id}");
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /**
     * Test non-admin cannot toggle status or delete user.
     */
    public function test_non_admin_cannot_toggle_status_or_delete_user(): void
    {
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $targetUser = User::create([
            'name' => 'Target User',
            'email' => 'target-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        // Non-admin attempting to toggle status should get 403
        $response = $this->actingAs($regularUser)->post("/users/{$targetUser->id}/toggle-status");
        $response->assertStatus(403);

        // Non-admin attempting to delete should get 403
        $response = $this->actingAs($regularUser)->delete("/users/{$targetUser->id}");
        $response->assertStatus(403);
    }

    /**
     * Test admin can create a new user.
     */
    public function test_admin_can_create_user(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post('/users', [
            'name' => 'New Staff',
            'email' => 'newstaff@example.com',
            'password' => 'secure123',
            'role' => 'it',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'name' => 'New Staff',
            'email' => 'newstaff@example.com',
            'role' => 'it',
            'is_active' => true,
        ]);
    }

    /**
     * Test admin cannot create user with duplicate email.
     */
    public function test_admin_cannot_create_user_with_duplicate_email(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post('/users', [
            'name' => 'Duplicate User',
            'email' => 'existing@example.com',
            'password' => 'secure123',
            'role' => 'it',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test non-admin cannot create a user.
     */
    public function test_non_admin_cannot_create_user(): void
    {
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $response = $this->actingAs($regularUser)->post('/users', [
            'name' => 'New Staff',
            'email' => 'newstaff@example.com',
            'password' => 'secure123',
            'role' => 'it',
        ]);

        $response->assertStatus(403);
    }
}
