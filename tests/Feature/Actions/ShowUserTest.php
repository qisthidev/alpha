<?php

declare(strict_types=1);

use App\Actions\ShowUser;
use App\Models\User;

it('returns the user', function (): void {
    $user = User::factory()->create(['name' => 'Test User']);

    $action = new ShowUser;
    $result = $action->handle($user);

    expect($result->id)->toBe($user->id)
        ->and($result->name)->toBe('Test User');
});
