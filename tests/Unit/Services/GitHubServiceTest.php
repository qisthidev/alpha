<?php

declare(strict_types=1);

use App\Services\GitHubService;
use Illuminate\Support\Facades\Http;

it('searches code on github using the correct endpoint and headers', function (): void {
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

    $service = resolve(GitHubService::class);

    $results = $service->searchCode('CLAUDE_API_KEY');

    expect($results['total_count'])->toBe(1)
        ->and($results['items'])->toHaveCount(1);

    Http::assertSent(function ($request): bool {
        return str_contains($request->url(), 'api.github.com/search/code')
            && $request['q'] === 'CLAUDE_API_KEY'
            && $request->header('Accept')[0] === 'application/vnd.github+json'
            && $request->header('X-GitHub-Api-Version')[0] === '2022-11-28';
    });
});
