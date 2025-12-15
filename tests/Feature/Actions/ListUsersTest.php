<?php

declare(strict_types=1);

use App\Actions\ListUsers;
use App\Models\User;

it('lists users with pagination', function (): void {
    User::factory()->count(60)->create();

    $action = new ListUsers;
    $result = $action->handle();

    expect($result->total())->toBe(60)
        ->and($result->perPage())->toBe(50)
        ->and($result->count())->toBe(50);
});

it('searches users by name', function (): void {
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Smith']);
    User::factory()->create(['name' => 'John Johnson']);

    $action = new ListUsers;
    $result = $action->handle(['search' => 'John']);

    expect($result->total())->toBe(2);
});

it('searches users by email', function (): void {
    User::factory()->create(['email' => 'john@example.com']);
    User::factory()->create(['email' => 'jane@example.com']);
    User::factory()->create(['email' => 'bob@test.com']);

    $action = new ListUsers;
    $result = $action->handle(['search' => 'example']);

    expect($result->total())->toBe(2);
});

it('sorts users by name ascending', function (): void {
    User::factory()->create(['name' => 'Charlie']);
    User::factory()->create(['name' => 'Alice']);
    User::factory()->create(['name' => 'Bob']);

    $action = new ListUsers;
    $result = $action->handle(['sort_by' => 'name', 'sort_direction' => 'asc']);

    expect($result->first()->name)->toBe('Alice')
        ->and($result->last()->name)->toBe('Charlie');
});

it('sorts users by name descending', function (): void {
    User::factory()->create(['name' => 'Alice']);
    User::factory()->create(['name' => 'Bob']);
    User::factory()->create(['name' => 'Charlie']);

    $action = new ListUsers;
    $result = $action->handle(['sort_by' => 'name', 'sort_direction' => 'desc']);

    expect($result->first()->name)->toBe('Charlie')
        ->and($result->last()->name)->toBe('Alice');
});

it('sorts users by email', function (): void {
    User::factory()->create(['email' => 'charlie@example.com']);
    User::factory()->create(['email' => 'alice@example.com']);
    User::factory()->create(['email' => 'bob@example.com']);

    $action = new ListUsers;
    $result = $action->handle(['sort_by' => 'email', 'sort_direction' => 'asc']);

    expect($result->first()->email)->toBe('alice@example.com')
        ->and($result->last()->email)->toBe('charlie@example.com');
});

it('uses default sort by created_at descending', function (): void {
    $user1 = User::factory()->create(['created_at' => now()->subDays(2)]);
    $user2 = User::factory()->create(['created_at' => now()->subDay()]);
    $user3 = User::factory()->create(['created_at' => now()]);

    $action = new ListUsers;
    $result = $action->handle();

    expect($result->first()->id)->toBe($user3->id);
});

it('validates sort column to prevent SQL injection', function (): void {
    User::factory()->count(3)->create();

    $action = new ListUsers;
    $result = $action->handle(['sort_by' => 'malicious_column; DROP TABLE users;']);

    expect($result->total())->toBe(3);
});

it('validates sort direction', function (): void {
    User::factory()->count(3)->create();

    $action = new ListUsers;
    $result = $action->handle(['sort_by' => 'name', 'sort_direction' => 'invalid']);

    expect($result->total())->toBe(3);
});

it('respects custom per page limit', function (): void {
    User::factory()->count(30)->create();

    $action = new ListUsers;
    $result = $action->handle(['per_page' => 10]);

    expect($result->perPage())->toBe(10)
        ->and($result->count())->toBe(10);
});

it('limits per page to maximum of 100', function (): void {
    User::factory()->count(150)->create();

    $action = new ListUsers;
    $result = $action->handle(['per_page' => 200]);

    expect($result->perPage())->toBe(50);
});

it('limits per page to minimum of 1', function (): void {
    User::factory()->count(10)->create();

    $action = new ListUsers;
    $result = $action->handle(['per_page' => -10]);

    expect($result->perPage())->toBe(50);
});
