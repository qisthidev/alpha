<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\SearchGitHubForClaudeApiKey;
use Inertia\Inertia;
use Inertia\Response;

final readonly class GitHubSearchController
{
    public function __invoke(SearchGitHubForClaudeApiKey $action): Response
    {
        $results = $action->handle();

        return Inertia::render('github-search/index', [
            'totalCount' => $results['total_count'] ?? 0,
            'incompleteResults' => $results['incomplete_results'] ?? false,
            'items' => $results['items'] ?? [],
        ]);
    }
}
