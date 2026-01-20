# Eco-Connect Automation Tests

This directory contains automated End-to-End (E2E) tests for the Eco-Connect platform using [Playwright](https://playwright.dev/).

## Prerequisites

- [Node.js](https://nodejs.org/) (v16 or higher)
- Local server running (e.g., XAMPP) with the website accessible at `http://localhost/Eco-Connect/public`

## Setup

1. Open your terminal in this directory (`tests/automation`).
2. Install dependencies:
   ```bash
   npm install
   ```
3. Install browser binaries:
   ```bash
   npx playwright install
   ```

## Running Tests

### Run all tests (Headless)
```bash
npm test
```

### Run tests with UI Mode
This allows you to see the tests running in a browser window and debug easily.
```bash
npm run test:ui
```

### Run a specific test file
```bash
npx playwright test tests/smoke.test.ts
```

## Test Structure

- `tests/smoke.test.ts`: Basic connectivity and page load checks.
- `tests/volunteer.test.ts`: Tests for volunteer registration, login, and joining projects.
- `tests/ngo.test.ts`: Tests for NGO registration, creating projects, and managing volunteers.
- `tests/admin.test.ts`: Tests for administrative dashboards and moderation.

## Edge Cases Covered

The test plan includes edge cases such as:
- Duplicate registration attempts.
- Invalid login credentials.
- Joining expired projects.
- Unauthorized access to admin routes.
- Large file uploads for project banners.
