<?php

declare(strict_types=1);

namespace App\Actions;

use App\Services\GitHubService;

final readonly class SearchGitHubForClaudeApiKey
{
    public function __construct(private GitHubService $github) { }

    /**
     * @return array{total_count: int, incomplete_results: bool, items: array<int, array<string, mixed>>}
     */
    public function handle(): array
    {
        return $this->github->searchCode('CLAUDE_API_KEY');
    }
}
