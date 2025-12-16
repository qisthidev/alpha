<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateUser;
use App\Actions\DeleteUser;
use App\Actions\ListUsers;
use App\Actions\ShowUser;
use App\Actions\UpdateUser;
use App\Http\Requests\CreateUserManagementRequest;
use App\Http\Requests\UpdateUserManagementRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final readonly class UserManagementController
{
    public function index(Request $request, ListUsers $action): Response
    {
        Gate::authorize('viewAny', User::class);
        /** @var array{search?: string|null, sort_by?: string, sort_direction?: string, per_page?: int} $filters */
        $filters = [
            'search' => $request->query('search'),
            'sort_by' => $request->query('sort_by', 'created_at'),
            'sort_direction' => $request->query('sort_direction', 'desc'),
            'per_page' => (int) $request->query('per_page', '50'),
        ];

        $users = $action->handle($filters);

        return Inertia::render('user-management/index', [
            'users' => $users,
            'filters' => $filters,
        ]);
    }

    public function show(User $user, ShowUser $action): Response
    {
        Gate::authorize('view', User::class);

        return Inertia::render('user-management/show', [
            'user' => $action->handle($user),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', User::class);

        return Inertia::render('user-management/create');
    }

    public function store(CreateUserManagementRequest $request, CreateUser $action): RedirectResponse
    {
        Gate::authorize('create', User::class);

        /** @var array<string, mixed> $attributes */
        $attributes = $request->safe()->except('password');

        $action->handle(
            $attributes,
            $request->string('password')->value(),
        );

        return to_route('user-management.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): Response
    {
        Gate::authorize('update', User::class);

        return Inertia::render('user-management/edit', [
            'user' => $user,
        ]);
    }

    public function update(UpdateUserManagementRequest $request, User $user, UpdateUser $action): RedirectResponse
    {
        Gate::authorize('update', User::class);

        /** @var array<string, mixed> $attributes */
        $attributes = $request->safe()->all();

        $action->handle($user, $attributes);

        return to_route('user-management.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user, DeleteUser $action): RedirectResponse
    {
        Gate::authorize('delete', [User::class, $user]);

        $action->handle($user);

        return to_route('user-management.index')
            ->with('success', 'User deleted successfully.');
    }
}
