# CI instructions

Continuous Integration (CI) for this project is set up to ensure code quality and functionality on every commit.

GitHub Actions

This project uses GitHub Actions for CI. The workflow lives at `.github/workflows/ci.yml` and runs on pushes and pull requests to `main`.

What the workflow does

- Checks out the code
- Sets up PHP 8.4 and Node 18
- Installs Composer and NPM dependencies
- Prepares a local SQLite database
- Runs Laravel Pint for code style
- Runs the test suite using `php artisan test`

Local reproduction

1. Install PHP 8.4 and Node 18 locally, and Composer/NPM.
2. Install PHP dependencies:

```bash
composer install
```

3. Install Node dependencies (if you need to build assets locally):

```bash
npm ci
```

4. Copy the example env and create the SQLite DB:

```bash
cp .env.example .env
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan key:generate
```

5. Run the test subset locally (fast):

```bash
php artisan test --filter=Peaks\
```

6. To run the full test suite:

```bash
php artisan test
```

Notes

- The GitHub Action uses SQLite and configures common environment variables for a fast, isolated test run.
- If you want browser E2E tests (Pest/Playwright or Laravel Dusk), add a separate job with the browser runner and a compatible driver.

Running Playwright E2E locally

1. Install Playwright and browsers:

```bash
npm ci
npx playwright install --with-deps
```

2. Start a local server (testing environment):

```bash
php artisan serve --env=testing --port=8080
```

3. Run Playwright tests:

```bash
npx playwright test --reporter=list
```

Enabling E2E in GitHub Actions

- You can trigger the E2E job by using the workflow_dispatch input `run_e2e` set to `true` when running the workflow manually, or by setting the repository secret `RUN_BROWSER_E2E=true` and modifying the workflow env accordingly.
