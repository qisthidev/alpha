<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Http;

it('renders the github search page', function (): void {
    Http::fake([
        'https://api.github.com/search/code*' => Http::response([
            'total_count' => 1,
            'incomplete_results' => false,
            'items' => [
                [
                    'name' => '.env',
                    'path' => '.env',
                    'html_url' => 'https://github.com/owner/repo/blob/main/.env',
                    'repository' => [
                        'full_name' => 'owner/repo',
                        'html_url' => 'https://github.com/owner/repo',
                        'private' => false,
                    ],
                    'score' => 1.0,
                ],
            ],
        ]),
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('github-search'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('github-search/index')
            ->where('totalCount', 1)
            ->where('incompleteResults', false)
            ->has('items', 1)
        );
});

it('passes empty results when no matches found', function (): void {
    Http::fake([
        'https://api.github.com/search/code*' => Http::response([
            'total_count' => 0,
            'incomplete_results' => false,
            'items' => [],
        ]),
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('github-search'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('github-search/index')
            ->where('totalCount', 0)
            ->has('items', 0)
        );
});

it('requires authentication', function (): void {
    $response = $this->get(route('github-search'));

    $response->assertRedirectToRoute('login');
});

it('requires email verification', function (): void {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)
        ->get(route('github-search'));

    $response->assertRedirectToRoute('verification.notice');
});
