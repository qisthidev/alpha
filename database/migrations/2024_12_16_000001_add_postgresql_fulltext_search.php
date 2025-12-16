<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Only run for PostgreSQL
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        // Enable pg_trgm extension for similarity search (if not already enabled)
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');

        // Create GIN indexes for efficient ILIKE queries with pg_trgm
        DB::statement('CREATE INDEX CONCURRENTLY IF NOT EXISTS users_name_trgm_idx ON users USING gin (name gin_trgm_ops)');
        DB::statement('CREATE INDEX CONCURRENTLY IF NOT EXISTS users_email_trgm_idx ON users USING gin (email gin_trgm_ops)');

        // Optional: Create a full-text search index using tsvector for more advanced search
        // This creates a generated column for full-text search
        DB::statement("ALTER TABLE users ADD COLUMN IF NOT EXISTS search_vector tsvector GENERATED ALWAYS AS (to_tsvector('english', coalesce(name, '') || ' ' || coalesce(email, ''))) STORED");
        DB::statement('CREATE INDEX CONCURRENTLY IF NOT EXISTS users_search_vector_idx ON users USING gin (search_vector)');
    }

    public function down(): void
    {
        // Only run for PostgreSQL
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('DROP INDEX CONCURRENTLY IF EXISTS users_search_vector_idx');
        DB::statement('ALTER TABLE users DROP COLUMN IF EXISTS search_vector');
        DB::statement('DROP INDEX CONCURRENTLY IF EXISTS users_email_trgm_idx');
        DB::statement('DROP INDEX CONCURRENTLY IF EXISTS users_name_trgm_idx');
        // Note: We don't drop the pg_trgm extension as it might be used by other tables
    }
};
