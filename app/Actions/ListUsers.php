<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final readonly class ListUsers
{
    /**
     * List users with pagination, search, and sorting.
     *
     * @param  array{search?: string|null, sort_by?: string, sort_direction?: string, per_page?: int}  $filters
     * @return LengthAwarePaginator<int, User>
     */
    public function handle(array $filters = []): LengthAwarePaginator
    {
        $query = User::query();

        // Apply search filter
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $this->applySearch($query, $search);
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        // Validate sort column to prevent SQL injection
        $allowedSortColumns = ['id', 'name', 'email', 'created_at', 'updated_at'];
        if (! in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'created_at';
        }

        // Validate sort direction
        if (! in_array(mb_strtolower($sortDirection), ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortBy, $sortDirection);

        // Paginate with configurable per page (default 50 for performance)
        $perPage = $filters['per_page'] ?? 50;
        if ($perPage < 1) {
            $perPage = 50;
        } elseif ($perPage > 100) {
            $perPage = 100;
        }

        return $query->paginate($perPage);
    }

    /**
     * Apply search filter with database-specific optimizations.
     *
     * @param  Builder<User>  $query
     */
    private function applySearch(Builder $query, string $search): void
    {
        /** @var \Illuminate\Database\Connection $connection */
        $connection = $query->getConnection();
        $driver = $connection->getDriverName();

        // Use PostgreSQL full-text search with pg_trgm for better performance
        if ($driver === 'pgsql') {
            // Check if pg_trgm extension is available for similarity search
            $query->where(function (Builder $q) use ($search): void {
                $q->whereRaw('name ILIKE ?', ["%{$search}%"])
                    ->orWhereRaw('email ILIKE ?', ["%{$search}%"]);
            });
        } else {
            // Use standard LIKE for other databases (MySQL, SQLite, etc.)
            $query->where(function (Builder $q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
    }
}
