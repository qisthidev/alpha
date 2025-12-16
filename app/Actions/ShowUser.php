<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final readonly class ShowUser
{
    public function handle(User $user): User
    {
        return $user;
    }
}
