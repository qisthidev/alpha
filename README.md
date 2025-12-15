# Alpha - Laravel + React + Inertia Playground

**Alpha** is my personal playground for exploring Laravel with React and Inertia.js. Built on top of the ultra-strict, type-safe [Laravel Starter Kit](https://github.com/nunomaduro/laravel-starter-kit-inertia-react), this project serves as my primary starter project for experimenting with Laravel features and gradually building all [Laravolt](https://laravolt.dev) features based on my experience maintaining the Laravolt ecosystem.

## About This Project

This is a customized version of the Laravel Starter Kit (Inertia & React) that maintains all the rigorous development standards while serving as a personal laboratory for:

- Exploring Laravel 12 features and best practices
- Building modern UI components with React and Inertia.js
- Implementing Laravolt-inspired features incrementally
- Testing architectural patterns and development workflows
- Creating reusable components and actions

### Based On

- **Laravel Starter Kit (Inertia & React)**: [github.com/nunomaduro/laravel-starter-kit-inertia-react](https://github.com/nunomaduro/laravel-starter-kit-inertia-react)
- Inspired by **Laravolt**: A Laravel package ecosystem for rapid application development

## Core Principles

This project maintains the strict standards of the original starter kit:

- ✅ **Fully Actions-Oriented Architecture**: Every operation is encapsulated in a single-action class
- ✅ **Cruddy by Design**: Standardized CRUD operations for all controllers, actions, and Inertia & React pages
- ✅ **100% Type Coverage**: Every method, property, and parameter is explicitly typed
- ✅ **Zero Tolerance for Code Smells**: Rector, PHPStan, ESLint, and Prettier at maximum strictness
- ✅ **Immutable-First Architecture**: Data structures favor immutability to prevent unexpected mutations
- ✅ **Fail-Fast Philosophy**: Errors are caught at compile-time, not runtime
- ✅ **Automated Code Quality**: Pre-configured tools ensure consistent, pristine code
- ✅ **Just Better Laravel Defaults**: Thanks to **[Essentials](https://github.com/nunomaduro/essentials)**
- ✅ **AI Guidelines**: Integrated AI Guidelines to assist in maintaining code quality and consistency
- ✅ **Full Testing Suite**: Comprehensive tests with 100% code coverage using Pest

## Planned Laravolt Features

As I build out this playground, I'll be implementing features inspired by my experience with Laravolt:

- [x] Advanced authentication flows (email verification, two-factor, social login)
- [x] User management and profile system (scalable CRUD for 100k+ users)
- [ ] Role-based access control (RBAC)
- [ ] Form builder components
- [ ] Media library and file management
- [ ] Activity logging and audit trails
- [ ] Notification system
- [ ] Settings management
- [ ] Advanced search and filtering
- [ ] Workflow and approval systems

Each feature will be built with modern best practices, full type safety, and comprehensive testing.

## Getting Started

> **Requires [PHP 8.4+](https://php.net/releases/) and a code coverage driver like [xdebug](https://xdebug.org/docs/install)**.

Create your type-safe Laravel application using [Composer](https://getcomposer.org):

```bash
composer create-project nunomaduro/laravel-starter-kit-inertia-react --prefer-dist example-app
```

### Initial Setup

Navigate to your project and complete the setup:

```bash
cd example-app

# Setup the project
composer setup

# Start the development server
composer dev
```

### Optional: Browser Testing Setup

If you plan to use Pest's browser testing capabilities:

```bash
npm install playwright
npx playwright install
```

### Verify Installation

Run the test suite to ensure everything is configured correctly:

```bash
composer test
```

You should see 100% test coverage and all quality checks passing.

## Available Tooling

### Development
- `composer dev` - Starts Laravel server, queue worker, log monitoring, and Vite dev server concurrently

### Code Quality
- `composer lint` - Runs Rector (refactoring), Pint (PHP formatting), and Prettier (JS/TS formatting)
- `composer test:lint` - Dry-run mode for CI/CD pipelines

### Testing
- `composer test:type-coverage` - Ensures 100% type coverage with Pest
- `composer test:types` - Runs PHPStan at level 9 (maximum strictness)
- `composer test:unit` - Runs Pest tests with 100% code coverage requirement
- `composer test` - Runs the complete test suite (type coverage, unit tests, linting, static analysis)

### Maintenance
- `composer update:requirements` - Updates all PHP and NPM dependencies to latest versions

## License

**Laravel Starter Kit Inertia React** was created by **[Nuno Maduro](https://x.com/enunomaduro)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
