<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

final readonly class GitHubService
{
    /**
     * @return array{total_count: int, incomplete_results: bool, items: array<int, array<string, mixed>>}
     */
    public function searchCode(string $query): array
    {
        $response = Http::withToken((string) config('services.github.token'))
            ->accept('application/vnd.github+json')
            ->withHeader('X-GitHub-Api-Version', '2022-11-28')
            ->get('https://api.github.com/search/code', [
                'q' => $query,
            ])
            ->throw();

        /** @var array{total_count: int, incomplete_results: bool, items: array<int, array<string, mixed>>} */
        return $response->json();
    }
}
