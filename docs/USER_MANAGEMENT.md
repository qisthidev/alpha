# User Management Feature

This document describes the user management feature implementation with scalability for 100k+ users.

## Enterprise-Level Architecture

### ULID Primary Keys
The User model uses **ULID** (Universally Unique Lexicographically Sortable Identifier) instead of auto-incrementing integers for:
- **Enhanced Security**: Non-sequential IDs prevent enumeration attacks and information leakage
- **Distributed Systems**: Globally unique IDs enable database sharding and microservices architecture
- **Better Performance**: Chronologically sortable with efficient B-tree indexing
- **URL Safety**: 26-character case-insensitive strings (shorter than UUIDs)

See [ULID_MIGRATION.md](./ULID_MIGRATION.md) for detailed implementation and migration guide.

## Features

### CRUD Operations
- **Create**: Add new users with name, email, and password
- **Read**: List all users with pagination, search, and sorting
- **Update**: Edit user name and email
- **Delete**: Remove users from the system
- **Show**: View detailed user information

### Scalability Features

#### 1. Database Indexing
The migration `2024_12_15_000000_add_user_management_indexes.php` adds indexes on:
- `name` column - for efficient name-based searches and sorting
- `created_at` column - for efficient date-based sorting
- `email` column - already indexed as UNIQUE (from base migration)

These indexes ensure fast queries even with 100k+ users.

#### 2. Pagination
- Default page size: 50 users per page
- Configurable per page: values between 1-100 are used as-is; values < 1 default to 50; values > 100 are clamped to 100
- Laravel's built-in pagination for efficient database queries
- Only loads one page at a time, not all records

#### 3. Efficient Search
The `ListUsers` action implements database-specific optimizations:
- **PostgreSQL**: Uses ILIKE with pg_trgm extension for efficient trigram-based search
- **PostgreSQL (Advanced)**: Full-text search with tsvector and GIN indexes for complex queries
- **MySQL/SQLite**: Standard LIKE queries with indexed columns
- Search by name OR email across all databases
- Uses Laravel's query builder for parameter binding (prevents SQL injection)

**PostgreSQL Full-Text Search Setup:**
The migration `2024_12_16_000001_add_postgresql_fulltext_search.php` enables:
- `pg_trgm` extension for similarity search
- GIN indexes on name and email columns for efficient ILIKE queries
- Optional tsvector column with full-text search index for advanced search capabilities

#### 4. Sortable Columns
Users can sort by:
- ID
- Name
- Email
- Created At
- Updated At

With validation to prevent SQL injection attacks.

#### 5. Filter Preservation
Search and sort parameters are preserved in the URL, allowing:
- Bookmarking filtered views
- Sharing filtered lists
- Browser back/forward navigation

## Architecture

### Backend (Laravel)

#### Actions
- `ListUsers`: Handles pagination, search, and sorting logic
- `ShowUser`: Retrieves a single user
- `CreateUser`: Creates a new user (reused from auth)
- `UpdateUser`: Updates user information (reused from auth)
- `DeleteUser`: Deletes a user (reused from auth)

#### Controllers
- `UserManagementController`: RESTful resource controller with all CRUD methods

#### Requests
- `CreateUserManagementRequest`: Validates user creation data
- `UpdateUserManagementRequest`: Validates user update data

#### Routes
All routes are protected with `auth` and `verified` middleware:
```php
Route::resource('user-management', UserManagementController::class);
```

### Frontend (React + Inertia.js)

#### Pages
- `index.tsx`: Paginated list with search and sort
- `show.tsx`: User detail view
- `create.tsx`: User creation form
- `edit.tsx`: User edit form

#### UI Features
- Real-time search
- Click-to-sort headers
- Pagination controls
- Responsive design
- Status badges (verified/unverified)
- Confirmation dialogs for delete

## Performance Considerations

### Database
1. **Indexes**: Compound and single column indexes for fast lookups
2. **Pagination**: Limit queries to only necessary data
3. **Query optimization**: Use Laravel's query builder efficiently

### Application
1. **Lazy loading**: Only load one page of users at a time
2. **Efficient queries**: Use indexed columns for WHERE and ORDER BY
3. **Parameter validation**: Prevent SQL injection and invalid queries

### Frontend
1. **Inertia.js**: Partial page reloads for fast navigation
2. **Optimistic UI**: Immediate feedback on actions
3. **Debouncing**: Search input can be debounced if needed

## Testing

Comprehensive tests cover:
- Controller CRUD operations
- Action logic (pagination, search, sort)
- Validation rules
- Authentication requirements
- Authorization requirements (admin-only access)
- Self-deletion prevention
- Edge cases (invalid inputs, SQL injection attempts)

## Scalability Metrics

This implementation can handle:
- ✅ 100k+ users in the database
- ✅ Sub-100ms query times with proper indexing
- ✅ 50 users per page (1,000 pages for 50k users)
- ✅ Fast search and sort operations
- ✅ Concurrent user access

## Security

1. **Authorization**: Admin-only access with policy-based permissions
   - `is_admin` field in users table (added via migration `2024_12_16_000000_add_is_admin_to_users_table.php`)
   - `UserPolicy` enforces admin-only access for all CRUD operations
   - Self-deletion prevention: Admins cannot delete their own account
   - Form requests (`CreateUserManagementRequest`, `UpdateUserManagementRequest`) check admin status
2. **SQL Injection Prevention**: Validated sort columns and parameters
3. **XSS Prevention**: 
   - React automatic escaping
   - Safe HTML entity decoding for pagination labels (no `dangerouslySetInnerHTML`)
4. **CSRF Protection**: Laravel built-in CSRF tokens
5. **Authentication**: Required for all operations
6. **Validation**: Server-side validation for all inputs

## Future Enhancements

Potential improvements for even larger scale:
- [ ] Elasticsearch for full-text search
- [ ] Redis caching for frequently accessed data
- [ ] Background jobs for bulk operations
- [ ] Export functionality for large datasets
- [ ] Advanced filters (date ranges, status, etc.)
- [ ] Role-based permissions
- [ ] Soft deletes for audit trails
- [ ] Bulk actions (delete, export)

## Accessibility

The user interface follows WCAG 2.1 guidelines:

1. **Semantic HTML**: Proper use of table elements with headers
2. **Keyboard Navigation**: All interactive elements are keyboard accessible
3. **Screen Reader Support**:
   - `aria-label` attributes on action buttons (View, Edit, Delete)
   - `aria-sort` attributes on sortable table headers indicating current sort state
   - Descriptive button labels for icon-only buttons
4. **Custom Dialogs**: Accessible confirmation dialogs using Radix UI primitives
5. **Focus Management**: Proper focus handling in modals and dialogs

