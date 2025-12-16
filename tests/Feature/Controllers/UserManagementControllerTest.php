<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('renders user management index page', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)
        ->get(route('user-management.index'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page->component('user-management/index'));
});

it('may list users with pagination', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    User::factory()->count(55)->create();

    $response = $this->actingAs($admin)
        ->get(route('user-management.index'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user-management/index')
            ->has('users.data', 50)
            ->has('users.links'));
});

it('may search users by name', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Smith']);
    User::factory()->create(['name' => 'Bob Johnson']);

    $response = $this->actingAs($admin)
        ->get(route('user-management.index', ['search' => 'John']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user-management/index')
            ->where('users.total', 2));
});

it('may search users by email', function (): void {
    $admin = User::factory()->create(['is_admin' => true, 'email' => 'admin@test.com']);
    User::factory()->create(['email' => 'john@example.com']);
    User::factory()->create(['email' => 'jane@example.com']);
    User::factory()->create(['email' => 'bob@test.com']);

    $response = $this->actingAs($admin)
        ->get(route('user-management.index', ['search' => 'example']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user-management/index')
            ->where('users.total', 2));
});

it('may sort users by name', function (): void {
    $admin = User::factory()->create(['is_admin' => true, 'name' => 'Zoe Admin']);
    $userA = User::factory()->create(['name' => 'Alice']);
    $userB = User::factory()->create(['name' => 'Bob']);
    $userC = User::factory()->create(['name' => 'Charlie']);

    $response = $this->actingAs($admin)
        ->get(route('user-management.index', ['sort_by' => 'name', 'sort_direction' => 'asc']));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user-management/index')
            ->where('users.data.0.name', 'Alice')
            ->where('users.data.1.name', 'Bob'));
});

it('renders user show page', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create(['name' => 'Test User']);

    $response = $this->actingAs($admin)
        ->get(route('user-management.show', $user));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user-management/show')
            ->where('user.id', $user->id)
            ->where('user.name', 'Test User'));
});

it('renders user create page', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)
        ->get(route('user-management.create'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page->component('user-management/create'));
});

it('may create a new user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)
        ->post(route('user-management.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ]);

    $response->assertRedirectToRoute('user-management.index');

    $user = User::query()->where('email', 'newuser@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('New User')
        ->and($user->email)->toBe('newuser@example.com')
        ->and(Hash::check('password1234', $user->password))->toBeTrue();
});

it('requires name when creating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)
        ->post(route('user-management.store'), [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

    $response->assertSessionHasErrors('name');
});

it('requires email when creating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)
        ->post(route('user-management.store'), [
            'name' => 'Test User',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

    $response->assertSessionHasErrors('email');
});

it('requires valid email when creating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)
        ->post(route('user-management.store'), [
            'name' => 'Test User',
            'email' => 'not-an-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

    $response->assertSessionHasErrors('email');
});

it('requires unique email when creating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->actingAs($admin)
        ->post(route('user-management.store'), [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

    $response->assertSessionHasErrors('email');
});

it('requires password when creating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)
        ->post(route('user-management.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response->assertSessionHasErrors('password');
});

it('requires password confirmation when creating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)
        ->post(route('user-management.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

    $response->assertSessionHasErrors('password');
});

it('renders user edit page', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create(['name' => 'Test User']);

    $response = $this->actingAs($admin)
        ->get(route('user-management.edit', $user));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user-management/edit')
            ->where('user.id', $user->id)
            ->where('user.name', 'Test User'));
});

it('may update a user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $response = $this->actingAs($admin)
        ->patch(route('user-management.update', $user), [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

    $response->assertRedirectToRoute('user-management.index');

    $user->refresh();

    expect($user->name)->toBe('New Name')
        ->and($user->email)->toBe('new@example.com');
});

it('requires name when updating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();

    $response = $this->actingAs($admin)
        ->patch(route('user-management.update', $user), [
            'email' => 'test@example.com',
        ]);

    $response->assertSessionHasErrors('name');
});

it('requires email when updating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();

    $response = $this->actingAs($admin)
        ->patch(route('user-management.update', $user), [
            'name' => 'Test User',
        ]);

    $response->assertSessionHasErrors('email');
});

it('requires valid email when updating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();

    $response = $this->actingAs($admin)
        ->patch(route('user-management.update', $user), [
            'name' => 'Test User',
            'email' => 'not-an-email',
        ]);

    $response->assertSessionHasErrors('email');
});

it('requires unique email when updating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    User::factory()->create(['email' => 'existing@example.com']);
    $user = User::factory()->create(['email' => 'test@example.com']);

    $response = $this->actingAs($admin)
        ->patch(route('user-management.update', $user), [
            'name' => 'Test User',
            'email' => 'existing@example.com',
        ]);

    $response->assertSessionHasErrors('email');
});

it('allows same email when updating user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $response = $this->actingAs($admin)
        ->patch(route('user-management.update', $user), [
            'name' => 'Updated Name',
            'email' => 'test@example.com',
        ]);

    $response->assertRedirectToRoute('user-management.index');

    $user->refresh();

    expect($user->name)->toBe('Updated Name')
        ->and($user->email)->toBe('test@example.com');
});

it('may delete a user', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();

    $response = $this->actingAs($admin)
        ->delete(route('user-management.destroy', $user));

    $response->assertRedirectToRoute('user-management.index');

    expect($user->fresh())->toBeNull();
});

it('requires authentication to access user management', function (): void {
    $response = $this->get(route('user-management.index'));

    $response->assertRedirect(route('login'));
});

it('requires authentication to create user', function (): void {
    $response = $this->get(route('user-management.create'));

    $response->assertRedirect(route('login'));
});

it('requires authentication to store user', function (): void {
    $response = $this->post(route('user-management.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('login'));
});

it('requires authentication to show user', function (): void {
    $user = User::factory()->create();

    $response = $this->get(route('user-management.show', $user));

    $response->assertRedirect(route('login'));
});

it('requires authentication to edit user', function (): void {
    $user = User::factory()->create();

    $response = $this->get(route('user-management.edit', $user));

    $response->assertRedirect(route('login'));
});

it('requires authentication to update user', function (): void {
    $user = User::factory()->create();

    $response = $this->patch(route('user-management.update', $user), [
        'name' => 'New Name',
        'email' => 'new@example.com',
    ]);

    $response->assertRedirect(route('login'));
});

it('requires authentication to delete user', function (): void {
    $user = User::factory()->create();

    $response = $this->delete(route('user-management.destroy', $user));

    $response->assertRedirect(route('login'));
});

it('prevents admin from deleting themselves', function (): void {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)
        ->delete(route('user-management.destroy', $admin));

    $response->assertForbidden();

    expect($admin->fresh())->not->toBeNull();
});

it('prevents non-admin users from accessing user management', function (): void {
    $regularUser = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($regularUser)
        ->get(route('user-management.index'));

    $response->assertForbidden();
});

it('prevents non-admin users from creating users', function (): void {
    $regularUser = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($regularUser)
        ->post(route('user-management.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ]);

    $response->assertForbidden();
});
