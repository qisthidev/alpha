# Alpha Playground Documentation

## Purpose

**Alpha** is my personal laboratory for exploring Laravel, React, and Inertia.js. This project serves as a testing ground for building modern web applications with strict type safety and code quality standards.

## Goals

### Primary Goals

1. **Learn by Doing**: Explore Laravel 12 features, React 19, and Inertia.js v2 through hands-on development
2. **Build Laravolt Features**: Gradually implement all Laravolt package features based on my experience maintaining the ecosystem
3. **Maintain Quality**: Keep 100% test coverage and strict type safety throughout development
4. **Document Learnings**: Record insights, patterns, and best practices discovered during development

### Secondary Goals

- Experiment with different architectural patterns
- Build reusable components and actions
- Create a reference implementation for future projects
- Develop a comprehensive component library

## Development Approach

### Actions-First

All business logic is encapsulated in Action classes:

```php
// app/Actions/User/CreateUser.php
final readonly class CreateUser
{
    public function handle(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Additional user setup logic...

            return $user;
        });
    }
}
```

### Component-Based UI

React components follow a consistent structure:

```tsx
// resources/js/Pages/Users/Index.tsx
export default function UsersIndex({ users }: Props) {
  return (
    <Layout>
      <h1>Users</h1>
      {/* Component implementation */}
    </Layout>
  );
}
```

### Test-Driven Development

Every feature is backed by comprehensive tests:

```php
// tests/Feature/User/CreateUserTest.php
it('creates a user with valid data', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
    ];

    $action = new CreateUser();
    $user = $action->handle($data);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->email)->toBe('john@example.com');
});
```

## Laravolt Features Roadmap

### Phase 1: Foundation (Current)

- [x] Project setup and customization
- [ ] Enhanced authentication system
- [ ] User profile management
- [ ] Basic RBAC implementation

### Phase 2: Core Features

- [ ] Form builder components
- [ ] Media library and file upload
- [ ] Activity logging system
- [ ] Settings management

### Phase 3: Advanced Features

- [ ] Notification system (email, database, push)
- [ ] Advanced search and filtering
- [ ] Workflow engine
- [ ] Approval systems
- [ ] Report generation

### Phase 4: Extensions

- [ ] API documentation
- [ ] Admin dashboard
- [ ] Multi-tenancy support
- [ ] Localization system

## Technology Stack

### Backend

- **PHP 8.4**: Latest PHP version with modern features
- **Laravel 12**: Latest Laravel framework
- **Inertia.js**: Server-side routing with SPA feel
- **SQLite**: Development database (easily swappable)

### Frontend

- **React 19**: Latest React with concurrent features
- **TypeScript**: Full type safety on the frontend
- **Tailwind CSS 4**: Utility-first CSS framework
- **Radix UI**: Accessible component primitives
- **Vite**: Fast development build tool

### Testing & Quality

- **Pest 4**: Modern PHP testing framework
- **PHPStan Level 9**: Maximum static analysis
- **ESLint**: JavaScript linting
- **Prettier**: Code formatting
- **Rector**: Automated refactoring

## Code Quality Standards

This project maintains strict quality standards:

- ✅ 100% type coverage (PHP and TypeScript)
- ✅ 100% test coverage
- ✅ Zero PHPStan errors at level 9
- ✅ Zero ESLint warnings
- ✅ Consistent code formatting
- ✅ Immutable data structures where possible

## Development Workflow

### Daily Development

1. Start dev server: `composer dev`
2. Make changes following existing patterns
3. Write tests first (TDD approach)
4. Run tests: `php artisan test --filter=YourTest`
5. Lint and format: `composer lint`
6. Commit with clear messages

### Before Committing

```bash
# Run full test suite
composer test

# Ensure code quality
composer lint

# Verify type coverage
composer test:type-coverage
```

## Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Inertia.js Documentation](https://inertiajs.com)
- [React Documentation](https://react.dev)
- [Laravolt Documentation](https://laravolt.dev)
- [Pest Documentation](https://pestphp.com)

## Notes and Insights

### Key Learnings

(This section will be updated as I learn and discover patterns)

- Action classes provide excellent separation of concerns
- Inertia.js makes SPAs feel like traditional server-side apps
- Type safety catches bugs early and improves IDE support
- 100% test coverage is achievable and maintainable

### Common Patterns

(To be documented as patterns emerge)

### Gotchas and Solutions

(To be documented as issues are encountered and solved)

## Contributing

This is a personal playground project, but ideas and suggestions are welcome! Feel free to open issues or discussions.

## License

This project is open-source software licensed under the MIT license, based on the [Laravel Starter Kit](https://github.com/nunomaduro/laravel-starter-kit-inertia-react) by Nuno Maduro.
