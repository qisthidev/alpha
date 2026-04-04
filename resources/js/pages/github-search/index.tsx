import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { ExternalLink } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'GitHub Search',
        href: '/github-search',
    },
];

interface Repository {
    full_name: string;
    html_url: string;
    private: boolean;
}

interface SearchItem {
    name: string;
    path: string;
    html_url: string;
    repository: Repository;
    score: number;
}

interface Props {
    totalCount: number;
    incompleteResults: boolean;
    items: SearchItem[];
}

export default function GitHubSearchIndex({ totalCount, incompleteResults, items }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="GitHub Search – CLAUDE_API_KEY" />
            <div className="flex flex-col gap-6 p-4">
                <div>
                    <h1 className="text-xl font-semibold">GitHub Code Search: CLAUDE_API_KEY</h1>
                    <p className="text-muted-foreground mt-1 text-sm">
                        {totalCount.toLocaleString()} result{totalCount !== 1 ? 's' : ''} found
                        {incompleteResults && ' (incomplete results)'}
                    </p>
                </div>

                {items.length === 0 ? (
                    <p className="text-muted-foreground text-sm">No results found.</p>
                ) : (
                    <ul className="flex flex-col gap-3">
                        {items.map((item) => (
                            <li
                                key={item.html_url}
                                className="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                            >
                                <div className="flex items-start justify-between gap-4">
                                    <div className="min-w-0">
                                        <a
                                            href={item.html_url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="flex items-center gap-1 truncate font-medium hover:underline"
                                        >
                                            {item.repository.full_name}/{item.path}
                                            <ExternalLink className="inline h-3.5 w-3.5 shrink-0" />
                                        </a>
                                        <a
                                            href={item.repository.html_url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="text-muted-foreground mt-0.5 flex items-center gap-1 text-sm hover:underline"
                                        >
                                            {item.repository.full_name}
                                            <ExternalLink className="inline h-3 w-3 shrink-0" />
                                        </a>
                                    </div>
                                </div>
                            </li>
                        ))}
                    </ul>
                )}
            </div>
        </AppLayout>
    );
}
