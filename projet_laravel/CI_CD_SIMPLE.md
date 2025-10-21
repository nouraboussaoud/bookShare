# Simple CI/CD Pipeline

## Overview

This project uses a simple GitHub Actions workflow to run unit tests on every push and pull request.

## Workflow File

**File:** `.github/workflows/tests.yml`

## What It Does

1. **Checkout code** - Gets the latest code from the branch
2. **Setup PHP** - Configures PHP 8.2 with required extensions
3. **Cache dependencies** - Caches Composer packages for faster builds
4. **Install dependencies** - Runs `composer install`
5. **Prepare environment** - Copies `.env.example` to `.env.testing` and generates app key
6. **Run migrations** - Sets up test database with fresh migrations
7. **Run tests** - Executes `php artisan test`

## When It Runs

- On push to `main` or `develop` branches
- On pull requests to `main` or `develop` branches

## Test Database

- Uses temporary MySQL 8.0 instance
- Database: `bookshare_test`
- Automatically cleaned up after tests

## View Results

1. Go to GitHub repository
2. Click "Actions" tab
3. View workflow runs and test results
4. Click on a run to see detailed logs

## Local Testing

To run tests locally:

```bash
# Copy environment file
cp .env.example .env.testing

# Generate app key
php artisan key:generate --env=testing

# Run migrations
php artisan migrate:fresh --env=testing

# Run tests
php artisan test
```

## Status Badge

Add this to your README.md:

```markdown
![Tests](https://github.com/nouraboussaoud/bookShare/workflows/Tests/badge.svg)
```
