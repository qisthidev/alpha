import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Eye, Pencil, Plus, Search, Trash2 } from 'lucide-react';
import { useState } from 'react';

import DeleteConfirmDialog from '@/components/delete-confirm-dialog';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Management',
        href: '/user-management',
    },
];

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedUsers {
    data: User[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    links: PaginationLink[];
}

interface Filters {
    search?: string | null;
    sort_by?: string;
    sort_direction?: string;
    per_page?: number;
}

interface Props {
    users: PaginatedUsers;
    filters: Filters;
}

export default function Index({ users, filters }: Props) {
    const [search, setSearch] = useState(filters.search || '');
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
    const [userToDelete, setUserToDelete] = useState<{
        id: number;
        name: string;
    } | null>(null);

    const handleSearch = () => {
        router.get(
            '/user-management',
            {
                search,
                sort_by: filters.sort_by,
                sort_direction: filters.sort_direction,
                per_page: filters.per_page,
            },
            { preserveState: true },
        );
    };

    const handlePerPageChange = (value: string) => {
        router.get(
            '/user-management',
            {
                search,
                sort_by: filters.sort_by,
                sort_direction: filters.sort_direction,
                per_page: value,
            },
            { preserveState: true },
        );
    };

    const handleSort = (column: string) => {
        const direction =
            filters.sort_by === column && filters.sort_direction === 'asc'
                ? 'desc'
                : 'asc';
        router.get(
            '/user-management',
            {
                search,
                sort_by: column,
                sort_direction: direction,
                per_page: filters.per_page,
            },
            { preserveState: true },
        );
    };

    const handleDeleteClick = (userId: number, userName: string) => {
        setUserToDelete({ id: userId, name: userName });
        setDeleteDialogOpen(true);
    };

    const handleDeleteConfirm = () => {
        if (userToDelete) {
            router.delete(`/user-management/${userToDelete.id}`);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="User Management" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">
                            User Management
                        </h1>
                        <p className="text-muted-foreground">
                            Manage users with scalable CRUD operations
                        </p>
                    </div>
                    <Link href="/user-management/create">
                        <Button>
                            <Plus className="mr-2 size-4" />
                            Add User
                        </Button>
                    </Link>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Users</CardTitle>
                        <CardDescription>
                            Total: {users.total} users (showing {users.from} to{' '}
                            {users.to})
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="mb-4 flex gap-2">
                            <Select
                                value={String(filters.per_page || 15)}
                                onValueChange={handlePerPageChange}
                            >
                                <SelectTrigger className="w-[100px]">
                                    <SelectValue placeholder="Per page" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="10">10</SelectItem>
                                    <SelectItem value="15">15</SelectItem>
                                    <SelectItem value="25">25</SelectItem>
                                    <SelectItem value="50">50</SelectItem>
                                    <SelectItem value="100">100</SelectItem>
                                </SelectContent>
                            </Select>
                            <div className="relative flex-1">
                                <Search className="absolute top-2.5 left-2 size-4 text-muted-foreground" />
                                <Input
                                    placeholder="Search by name or email..."
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                    onKeyDown={(e) => {
                                        if (e.key === 'Enter') {
                                            handleSearch();
                                        }
                                    }}
                                    className="pl-8"
                                />
                            </div>
                            <Button onClick={handleSearch}>Search</Button>
                        </div>

                        <div className="rounded-md border">
                            <div className="overflow-x-auto">
                                <table className="w-full">
                                    <thead className="border-b bg-muted/50">
                                        <tr>
                                            <th
                                                className="cursor-pointer px-4 py-3 text-left text-sm font-medium hover:bg-muted"
                                                onClick={() => handleSort('id')}
                                                aria-label="Sort by ID"
                                                aria-sort={
                                                    filters.sort_by === 'id'
                                                        ? filters.sort_direction ===
                                                          'asc'
                                                            ? 'ascending'
                                                            : 'descending'
                                                        : undefined
                                                }
                                            >
                                                ID{' '}
                                                {filters.sort_by === 'id' &&
                                                    (filters.sort_direction ===
                                                    'asc'
                                                        ? '↑'
                                                        : '↓')}
                                            </th>
                                            <th
                                                className="cursor-pointer px-4 py-3 text-left text-sm font-medium hover:bg-muted"
                                                onClick={() =>
                                                    handleSort('name')
                                                }
                                                aria-label="Sort by Name"
                                                aria-sort={
                                                    filters.sort_by === 'name'
                                                        ? filters.sort_direction ===
                                                          'asc'
                                                            ? 'ascending'
                                                            : 'descending'
                                                        : undefined
                                                }
                                            >
                                                Name{' '}
                                                {filters.sort_by === 'name' &&
                                                    (filters.sort_direction ===
                                                    'asc'
                                                        ? '↑'
                                                        : '↓')}
                                            </th>
                                            <th
                                                className="cursor-pointer px-4 py-3 text-left text-sm font-medium hover:bg-muted"
                                                onClick={() =>
                                                    handleSort('email')
                                                }
                                                aria-label="Sort by Email"
                                                aria-sort={
                                                    filters.sort_by === 'email'
                                                        ? filters.sort_direction ===
                                                          'asc'
                                                            ? 'ascending'
                                                            : 'descending'
                                                        : undefined
                                                }
                                            >
                                                Email{' '}
                                                {filters.sort_by === 'email' &&
                                                    (filters.sort_direction ===
                                                    'asc'
                                                        ? '↑'
                                                        : '↓')}
                                            </th>
                                            <th className="px-4 py-3 text-left text-sm font-medium">
                                                Status
                                            </th>
                                            <th
                                                className="cursor-pointer px-4 py-3 text-left text-sm font-medium hover:bg-muted"
                                                onClick={() =>
                                                    handleSort('created_at')
                                                }
                                                aria-label="Sort by Created Date"
                                                aria-sort={
                                                    filters.sort_by ===
                                                    'created_at'
                                                        ? filters.sort_direction ===
                                                          'asc'
                                                            ? 'ascending'
                                                            : 'descending'
                                                        : undefined
                                                }
                                            >
                                                Created{' '}
                                                {filters.sort_by ===
                                                    'created_at' &&
                                                    (filters.sort_direction ===
                                                    'asc'
                                                        ? '↑'
                                                        : '↓')}
                                            </th>
                                            <th className="px-4 py-3 text-right text-sm font-medium">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y">
                                        {users.data.map((user) => (
                                            <tr
                                                key={user.id}
                                                className="hover:bg-muted/50"
                                            >
                                                <td className="px-4 py-3 text-sm">
                                                    {user.id}
                                                </td>
                                                <td className="px-4 py-3 text-sm font-medium">
                                                    {user.name}
                                                </td>
                                                <td className="px-4 py-3 text-sm text-muted-foreground">
                                                    {user.email}
                                                </td>
                                                <td className="px-4 py-3 text-sm">
                                                    {user.email_verified_at ? (
                                                        <span className="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-800 dark:text-green-100">
                                                            Verified
                                                        </span>
                                                    ) : (
                                                        <span className="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                                            Unverified
                                                        </span>
                                                    )}
                                                </td>
                                                <td className="px-4 py-3 text-sm text-muted-foreground">
                                                    {new Date(
                                                        user.created_at,
                                                    ).toLocaleDateString()}
                                                </td>
                                                <td className="px-4 py-3 text-right">
                                                    <div className="flex justify-end gap-2">
                                                        <Link
                                                            href={`/user-management/${user.id}`}
                                                        >
                                                            <Button
                                                                variant="ghost"
                                                                size="sm"
                                                                aria-label={`View details for ${user.name}`}
                                                            >
                                                                <Eye className="size-4" />
                                                            </Button>
                                                        </Link>
                                                        <Link
                                                            href={`/user-management/${user.id}/edit`}
                                                        >
                                                            <Button
                                                                variant="ghost"
                                                                size="sm"
                                                                aria-label={`Edit ${user.name}`}
                                                            >
                                                                <Pencil className="size-4" />
                                                            </Button>
                                                        </Link>
                                                        <Button
                                                            variant="ghost"
                                                            size="sm"
                                                            aria-label={`Delete ${user.name}`}
                                                            onClick={() =>
                                                                handleDeleteClick(
                                                                    user.id,
                                                                    user.name,
                                                                )
                                                            }
                                                        >
                                                            <Trash2 className="size-4 text-destructive" />
                                                        </Button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {users.last_page > 1 && (
                            <div className="mt-4 flex items-center justify-between">
                                <div className="text-sm text-muted-foreground">
                                    Page {users.current_page} of{' '}
                                    {users.last_page}
                                </div>
                                <div className="flex gap-2">
                                    {users.links.map((link, index) => {
                                        // Decode HTML entities safely - process &amp; last to avoid double-decoding
                                        const decodedLabel = link.label
                                            .replace(/&laquo;/g, '«')
                                            .replace(/&raquo;/g, '»')
                                            .replace(/&lt;/g, '<')
                                            .replace(/&gt;/g, '>')
                                            .replace(/&quot;/g, '"')
                                            .replace(/&amp;/g, '&');

                                        return (
                                            <Button
                                                key={index}
                                                variant={
                                                    link.active
                                                        ? 'default'
                                                        : 'outline'
                                                }
                                                size="sm"
                                                disabled={!link.url}
                                                onClick={() => {
                                                    if (link.url) {
                                                        const url = new URL(
                                                            link.url,
                                                        );
                                                        if (filters.per_page) {
                                                            url.searchParams.set(
                                                                'per_page',
                                                                String(
                                                                    filters.per_page,
                                                                ),
                                                            );
                                                        }
                                                        router.get(
                                                            url.toString(),
                                                        );
                                                    }
                                                }}
                                            >
                                                {decodedLabel}
                                            </Button>
                                        );
                                    })}
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>

            <DeleteConfirmDialog
                open={deleteDialogOpen}
                onOpenChange={setDeleteDialogOpen}
                onConfirm={handleDeleteConfirm}
                userName={userToDelete?.name}
            />
        </AppLayout>
    );
}
