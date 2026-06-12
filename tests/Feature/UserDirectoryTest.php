<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Department;
use App\Models\Location;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $it;
    protected Department $department;

    protected function setUp(): void
    {
        parent::setUp();

        $this->department = Department::create([
            'name' => 'Finance',
            'description' => 'Finance Dept',
            'is_active' => true
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->it = User::create([
            'name' => 'IT Support',
            'email' => 'it-test@example.com',
            'password' => bcrypt('password'),
            'role' => 'it',
            'is_active' => true,
        ]);
    }

    /**
     * Test admin can create user with department and extension.
     */
    public function test_admin_can_create_user_with_dept_and_ext(): void
    {
        $response = $this->actingAs($this->admin)->post('/users', [
            'name' => 'Jane Enduser',
            'email' => 'jane@example.com',
            'role' => 'user',
            'call_ext' => 'Ext 101',
            'department_id' => $this->department->id,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'name' => 'Jane Enduser',
            'email' => 'jane@example.com',
            'role' => 'user',
            'call_ext' => 'Ext 101',
            'department_id' => $this->department->id,
        ]);
    }

    /**
     * Test department and extension are ignored/set to null for non-user roles.
     */
    public function test_dept_and_ext_ignored_for_admin_and_it_roles(): void
    {
        $response = $this->actingAs($this->admin)->post('/users', [
            'name' => 'New Admin',
            'email' => 'new-admin@example.com',
            'role' => 'admin',
            'call_ext' => 'Ext 999',
            'department_id' => $this->department->id,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'name' => 'New Admin',
            'email' => 'new-admin@example.com',
            'role' => 'admin',
            'call_ext' => null,
            'department_id' => null,
        ]);
    }

    /**
     * Test User Directory search feature.
     */
    public function test_user_directory_search(): void
    {
        $user1 = User::create([
            'name' => 'Searchable User',
            'email' => 'searchable@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'call_ext' => 'Ext 555',
            'is_active' => true,
        ]);

        $user2 = User::create([
            'name' => 'Another User',
            'email' => 'another@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'call_ext' => 'Ext 666',
            'is_active' => true,
        ]);

        // Search by name
        $response = $this->actingAs($this->admin)->get('/users?search=Searchable');
        $response->assertStatus(200);
        $response->assertSee('Searchable User');
        $response->assertDontSee('Another User');

        // Search by email
        $response = $this->actingAs($this->admin)->get('/users?search=another@example.com');
        $response->assertStatus(200);
        $response->assertSee('Another User');
        $response->assertDontSee('Searchable User');

        // Search by call extension
        $response = $this->actingAs($this->admin)->get('/users?search=555');
        $response->assertStatus(200);
        $response->assertSee('Searchable User');
        $response->assertDontSee('Another User');
    }

    /**
     * Test public ticket submission updates the user profile's name, call_ext, and department.
     */
    public function test_public_ticket_submission_updates_user_profile(): void
    {
        $location = Location::create(['name' => 'Floor 1', 'is_active' => true]);
        $category = Category::create(['name' => 'Software', 'is_active' => true]);
        $subCategory = SubCategory::create([
            'name' => 'Outlook',
            'category_id' => $category->id,
            'is_active' => true
        ]);

        // Create an existing user with initial info
        $existingUser = User::create([
            'name' => 'Old Name',
            'email' => 'test-reporter@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'call_ext' => 'Ext 111',
            'department_id' => null,
            'is_active' => true,
        ]);

        // Submit ticket using same email but new name, ext, and department
        $response = $this->post('/submit-ticket', [
            'name' => 'New Updated Name',
            'email' => 'test-reporter@example.com',
            'title' => 'Broken Outlook',
            'department_id' => $this->department->id,
            'category_id' => $category->id,
            'sub_category_id' => $subCategory->id,
            'priority' => 'medium',
            'call_ext' => 'Ext 222',
            'description' => 'I cannot send emails anymore.',
        ]);

        $response->assertStatus(302);
        
        // Verify user profile got updated with name, call_ext, and department_id
        $this->assertDatabaseHas('users', [
            'email' => 'test-reporter@example.com',
            'name' => 'New Updated Name',
            'call_ext' => 'Ext 222',
            'department_id' => $this->department->id,
        ]);
    }

    /**
     * Test authenticated user can submit a ticket with an attachment.
     */
    public function test_authenticated_user_can_submit_ticket_with_attachment(): void
    {
        $category = Category::create(['name' => 'Software', 'is_active' => true]);
        $subCategory = SubCategory::create([
            'name' => 'Outlook',
            'category_id' => $category->id,
            'is_active' => true
        ]);

        \Illuminate\Support\Facades\Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->image('screenshot.png');

        $response = $this->actingAs($this->it)->post('/tickets', [
            'title' => 'Outlook crash',
            'department_id' => $this->department->id,
            'category_id' => $category->id,
            'sub_category_id' => $subCategory->id,
            'priority' => 'high',
            'call_ext' => 'Ext 333',
            'attachment' => $file,
            'description' => 'The software keeps crashing on startup.',
        ]);

        $response->assertStatus(302);
        
        $ticket = Ticket::where('title', 'Outlook crash')->first();
        $this->assertNotNull($ticket);
        $this->assertNotNull($ticket->attachment);
        
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($ticket->attachment);
    }

    /**
     * Test authenticated user cannot submit ticket with missing sub category.
     */
    public function test_authenticated_user_cannot_submit_ticket_with_missing_sub_category(): void
    {
        $category = Category::create(['name' => 'Software', 'is_active' => true]);

        $response = $this->actingAs($this->it)->post('/tickets', [
            'title' => 'Outlook crash',
            'department_id' => $this->department->id,
            'category_id' => $category->id,
            'sub_category_id' => '', // missing subcategory
            'priority' => 'high',
            'call_ext' => 'Ext 333',
            'description' => 'The software keeps crashing on startup.',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('sub_category_id');
    }

    /**
     * Test desktop API OPTIONS preflight request.
     */
    public function test_desktop_api_preflight_options(): void
    {
        $response = $this->json('OPTIONS', '/api/tickets/pending-active');
        $response->assertStatus(204);
        $response->assertHeader('Access-Control-Allow-Origin', '*');
        $response->assertHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
    }

    /**
     * Test desktop API unauthorized calls.
     */
    public function test_desktop_api_unauthorized(): void
    {
        // Without header or query parameter
        $response = $this->getJson('/api/tickets/pending-active');
        $response->assertStatus(401);

        // With invalid token
        $response = $this->withHeaders(['X-API-Key' => 'wrong-token'])
            ->getJson('/api/tickets/pending-active');
        $response->assertStatus(401);
    }

    /**
     * Test desktop API retrieves pending and active tickets.
     */
    public function test_desktop_api_retrieves_pending_and_active_tickets(): void
    {
        $category = Category::create(['name' => 'Software', 'is_active' => true]);
        $subCategory = SubCategory::create([
            'name' => 'Outlook',
            'category_id' => $category->id,
            'is_active' => true
        ]);

        // Create pending ticket
        $ticketPending = Ticket::create([
            'title' => 'Pending Ticket',
            'description' => 'Pending description',
            'priority' => 'low',
            'status' => 'pending',
            'user_id' => $this->admin->id,
            'department_id' => $this->department->id,
            'category_id' => $category->id,
            'sub_category_id' => $subCategory->id,
        ]);

        // Create active ticket
        $ticketActive = Ticket::create([
            'title' => 'Active Ticket',
            'description' => 'Active description',
            'priority' => 'medium',
            'status' => 'active',
            'user_id' => $this->admin->id,
            'department_id' => $this->department->id,
            'category_id' => $category->id,
            'sub_category_id' => $subCategory->id,
        ]);

        // Create resolved ticket (should not be returned)
        $ticketResolved = Ticket::create([
            'title' => 'Resolved Ticket',
            'description' => 'Resolved description',
            'priority' => 'high',
            'status' => 'resolved',
            'user_id' => $this->admin->id,
            'department_id' => $this->department->id,
            'category_id' => $category->id,
            'sub_category_id' => $subCategory->id,
        ]);

        // Retrieve tickets using query parameter
        $response = $this->getJson('/api/tickets/pending-active?api_key=default_it_desktop_key_2026');
        $response->assertStatus(200);
        $response->assertHeader('Access-Control-Allow-Origin', '*');
        $response->assertJsonPath('count', 2);
        $response->assertJsonFragment(['title' => 'Pending Ticket']);
        $response->assertJsonFragment(['title' => 'Active Ticket']);
        $response->assertJsonMissing(['title' => 'Resolved Ticket']);

        // Retrieve tickets using X-API-Key header
        $response = $this->withHeaders(['X-API-Key' => 'default_it_desktop_key_2026'])
            ->getJson('/api/tickets/pending-active');
        $response->assertStatus(200);
        $response->assertJsonPath('count', 2);
    }
}


