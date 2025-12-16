<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final readonly class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, User $model): bool
    {
        // Prevent self-deletion
        if ($user->id === $model->id) {
            return false;
        }

        return $user->is_admin;
    }
}
