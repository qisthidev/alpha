<?php

declare(strict_types=1);

use App\Actions\SearchGitHubForClaudeApiKey;
use App\Services\GitHubService;
use Illuminate\Support\Facades\Http;

it('searches github for CLAUDE_API_KEY', function (): void {
    Http::fake([
        'https://api.github.com/search/code*' => Http::response([
            'total_count' => 2,
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
                [
                    'name' => 'config.py',
                    'path' => 'config.py',
                    'html_url' => 'https://github.com/another/project/blob/main/config.py',
                    'repository' => [
                        'full_name' => 'another/project',
                        'html_url' => 'https://github.com/another/project',
                        'private' => false,
                    ],
                    'score' => 0.8,
                ],
            ],
        ]),
    ]);

    $action = resolve(SearchGitHubForClaudeApiKey::class);

    $results = $action->handle();

    expect($results['total_count'])->toBe(2)
        ->and($results['incomplete_results'])->toBeFalse()
        ->and($results['items'])->toHaveCount(2)
        ->and($results['items'][0]['name'])->toBe('.env')
        ->and($results['items'][1]['name'])->toBe('config.py');
});

it('returns empty results when no matches found', function (): void {
    Http::fake([
        'https://api.github.com/search/code*' => Http::response([
            'total_count' => 0,
            'incomplete_results' => false,
            'items' => [],
        ]),
    ]);

    $action = resolve(SearchGitHubForClaudeApiKey::class);

    $results = $action->handle();

    expect($results['total_count'])->toBe(0)
        ->and($results['items'])->toBeEmpty();
});
