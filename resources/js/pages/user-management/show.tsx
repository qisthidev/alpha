import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Calendar, Mail, User as UserIcon } from 'lucide-react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Management',
        href: '/user-management',
    },
    {
        title: 'User Details',
        href: '#',
    },
];

interface User {
    id: string; // ULID primary key
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

interface Props {
    user: User;
}

export default function Show({ user }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`User: ${user.name}`} />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-4">
                        <Link href="/user-management">
                            <Button variant="ghost" size="sm">
                                <ArrowLeft className="size-4" />
                            </Button>
                        </Link>
                        <div>
                            <h1 className="text-3xl font-bold tracking-tight">
                                User Details
                            </h1>
                            <p className="text-muted-foreground">
                                View user information
                            </p>
                        </div>
                    </div>
                    <Link href={`/user-management/${user.id}/edit`}>
                        <Button>Edit User</Button>
                    </Link>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>User Information</CardTitle>
                        <CardDescription>
                            Details for user #{user.id}
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="space-y-2">
                                <div className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                    <UserIcon className="size-4" />
                                    Name
                                </div>
                                <div className="text-lg font-semibold">
                                    {user.name}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <div className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                    <Mail className="size-4" />
                                    Email
                                </div>
                                <div className="text-lg font-semibold">
                                    {user.email}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <div className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                    Status
                                </div>
                                <div>
                                    {user.email_verified_at ? (
                                        <Badge className="bg-green-600">
                                            Verified
                                        </Badge>
                                    ) : (
                                        <Badge variant="secondary">
                                            Unverified
                                        </Badge>
                                    )}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <div className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                    User ID
                                </div>
                                <div className="font-mono text-lg font-semibold">
                                    #{user.id}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <div className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                    <Calendar className="size-4" />
                                    Created At
                                </div>
                                <div className="text-lg font-semibold">
                                    {new Date(user.created_at).toLocaleString()}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <div className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                    <Calendar className="size-4" />
                                    Updated At
                                </div>
                                <div className="text-lg font-semibold">
                                    {new Date(user.updated_at).toLocaleString()}
                                </div>
                            </div>

                            {user.email_verified_at && (
                                <div className="space-y-2">
                                    <div className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                        Email Verified At
                                    </div>
                                    <div className="text-lg font-semibold">
                                        {new Date(
                                            user.email_verified_at,
                                        ).toLocaleString()}
                                    </div>
                                </div>
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
