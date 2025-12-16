<?php

declare(strict_types=1);

use App\Models\User;

beforeEach(function (): void {
    // Create an admin user for testing
    $this->admin = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
        'is_admin' => true,
        'email_verified_at' => now(),
    ]);

    // Create some regular users for testing
    User::factory()->count(5)->create();
});

it('shows user management page for admin users', function (): void {
    $page = actingAs($this->admin)->visit('/user-management');

    $page->assertSee('User Management')
        ->assertSee('Add User')
        ->assertSee('Total');
});

it('denies access to non-admin users', function (): void {
    $regularUser = User::factory()->create(['is_admin' => false, 'email_verified_at' => now()]);

    $page = actingAs($regularUser)->visit('/user-management');

    // Should redirect or show 403 error
    $page->assertSee('403');
});

it('can search for users by name', function (): void {
    User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

    $page = actingAs($this->admin)->visit('/user-management');

    $page->type('[placeholder*="Search"]', 'John')
        ->press('Search')
        ->waitForText('John Doe')
        ->assertSee('John Doe')
        ->assertDontSee('Jane Smith');
});

it('can sort users by name', function (): void {
    User::factory()->create(['name' => 'Zoe']);
    User::factory()->create(['name' => 'Alice']);
    User::factory()->create(['name' => 'Bob']);

    $page = actingAs($this->admin)->visit('/user-management');

    // Click on Name header to sort
    $page->click('th[aria-label*="Sort by Name"]')
        ->waitForReload();

    // Check that sorting happened (first visible name should be Alice when sorted ascending)
    $page->assertSee('Alice');
});

it('can view user details', function (): void {
    $user = User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);

    $page = actingAs($this->admin)->visit('/user-management');

    // Click on the view button for the user
    $page->click('[aria-label*="View details for Test User"]')
        ->waitForText('User Details')
        ->assertSee('Test User')
        ->assertSee('test@example.com')
        ->assertSee('User Information');
});

it('can create a new user', function (): void {
    $page = actingAs($this->admin)->visit('/user-management');

    $page->click('text=Add User')
        ->waitForText('Create User')
        ->type('[name="name"]', 'New Test User')
        ->type('[name="email"]', 'newuser@example.com')
        ->type('[name="password"]', 'password123456')
        ->type('[name="password_confirmation"]', 'password123456')
        ->press('Create User')
        ->waitForText('User created successfully')
        ->assertSee('New Test User');

    // Verify user was created in database
    expect(User::where('email', 'newuser@example.com')->exists())->toBeTrue();
});

it('validates required fields when creating user', function (): void {
    $page = actingAs($this->admin)->visit('/user-management/create');

    $page->press('Create User')
        ->waitForText('required');

    // Should show validation errors
    $page->assertSee('required');
});

it('can edit a user', function (): void {
    $user = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);

    $page = actingAs($this->admin)->visit('/user-management');

    $page->click('[aria-label*="Edit Old Name"]')
        ->waitForText('Edit User')
        ->clear('[name="name"]')
        ->type('[name="name"]', 'Updated Name')
        ->clear('[name="email"]')
        ->type('[name="email"]', 'updated@example.com')
        ->press('Update User')
        ->waitForText('User updated successfully')
        ->assertSee('Updated Name');

    // Verify user was updated in database
    $user->refresh();
    expect($user->name)->toBe('Updated Name')
        ->and($user->email)->toBe('updated@example.com');
});

it('can delete a user with confirmation', function (): void {
    $user = User::factory()->create(['name' => 'User To Delete', 'email' => 'delete@example.com']);

    $page = actingAs($this->admin)->visit('/user-management');

    // Click delete button
    $page->click('[aria-label*="Delete User To Delete"]')
        ->waitForText('Are you sure');

    // Should show confirmation dialog
    $page->assertSee('Are you sure')
        ->assertSee('User To Delete')
        ->press('Delete')
        ->waitForText('User deleted successfully');

    // Verify user was deleted from database
    expect(User::where('email', 'delete@example.com')->exists())->toBeFalse();
});

it('can cancel user deletion', function (): void {
    $user = User::factory()->create(['name' => 'User To Keep', 'email' => 'keep@example.com']);

    $page = actingAs($this->admin)->visit('/user-management');

    // Click delete button
    $page->click('[aria-label*="Delete User To Keep"]')
        ->waitForText('Are you sure')
        ->press('Cancel');

    // User should still exist
    expect(User::where('email', 'keep@example.com')->exists())->toBeTrue();
});

it('shows pagination controls when there are many users', function (): void {
    // Create more than 50 users to trigger pagination
    User::factory()->count(60)->create();

    $page = actingAs($this->admin)->visit('/user-management');

    $page->assertSee('Page')
        ->assertSee('of');

    // Should show pagination buttons
    $page->assertElementExists('button:has-text("»")');
});

it('can navigate to next page', function (): void {
    // Create more than 50 users to trigger pagination
    User::factory()->count(60)->create();

    $page = actingAs($this->admin)->visit('/user-management');

    $currentPage = $page->text();

    // Click next page button
    $page->click('button:has-text("»")')
        ->waitForReload();

    // Page content should change
    $newPage = $page->text();
    expect($currentPage)->not->toBe($newPage);
});

it('displays verified status badge', function (): void {
    User::factory()->create(['name' => 'Verified User', 'email_verified_at' => now()]);
    User::factory()->create(['name' => 'Unverified User', 'email_verified_at' => null]);

    $page = actingAs($this->admin)->visit('/user-management');

    $page->assertSee('Verified')
        ->assertSee('Unverified');
});

it('prevents admin from deleting themselves', function (): void {
    $page = actingAs($this->admin)->visit('/user-management');

    // Try to delete the admin user (self)
    $page->click('[aria-label*="Delete ' . $this->admin->name . '"]')
        ->waitForText('Are you sure')
        ->press('Delete');

    // Should show error or prevent deletion
    // The admin should still exist
    expect(User::where('email', 'admin@example.com')->exists())->toBeTrue();
});

it('shows user count information', function (): void {
    $page = actingAs($this->admin)->visit('/user-management');

    // Should show total count and range
    $page->assertSee('Total:')
        ->assertSee('showing');
});

it('can clear search', function (): void {
    User::factory()->create(['name' => 'Searchable User']);

    $page = actingAs($this->admin)->visit('/user-management');

    // Perform search
    $page->type('[placeholder*="Search"]', 'Searchable')
        ->press('Search')
        ->waitForText('Searchable User');

    // Clear search
    $page->clear('[placeholder*="Search"]')
        ->press('Search')
        ->waitForReload();

    // Should show all users again
    $page->assertSee($this->admin->name);
});

it('has accessible action buttons', function (): void {
    $user = User::factory()->create(['name' => 'Accessible User']);

    $page = actingAs($this->admin)->visit('/user-management');

    // Check that buttons have aria-labels
    $page->assertElementExists('[aria-label*="View details for Accessible User"]')
        ->assertElementExists('[aria-label*="Edit Accessible User"]')
        ->assertElementExists('[aria-label*="Delete Accessible User"]');
});

it('has accessible sortable headers', function (): void {
    $page = actingAs($this->admin)->visit('/user-management');

    // Check that headers have aria-sort and aria-label attributes
    $page->assertElementExists('th[aria-label*="Sort by ID"]')
        ->assertElementExists('th[aria-label*="Sort by Name"]')
        ->assertElementExists('th[aria-label*="Sort by Email"]')
        ->assertElementExists('th[aria-label*="Sort by Created Date"]');
});
