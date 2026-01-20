import { test, expect } from '@playwright/test';

test.describe('Volunteer (User) Workflows', () => {

    test.beforeEach(async ({ page }) => {
        await page.goto('index.php?route=login');
        await page.fill('input[name="email"]', 'test@gmail.com');
        await page.fill('input[name="password"]', 'test123');
        await page.click('button[type="submit"]');

        await page.waitForLoadState('networkidle');
        await expect(page).not.toHaveURL(/.*route=login/);
    });

    // Note: These tests assume a logged-in state.
    // In a real environment, you'd use a saved storage state.

    test('Volunteer Dashboard - Points & Rankings', async ({ page }) => {
        await page.goto('index.php?route=user_dashboard');
        // Use exact match to avoid matching "+20 PTS" or description text
        await expect(page.getByText('PTS', { exact: true }).first()).toBeVisible();
        await expect(page.locator('body')).toContainText(/Rank|Level/i);
    });

    test('Project Proposal Submission', async ({ page }) => {
        await page.goto('index.php?route=user_propose_cleanup');
        await page.fill('input[name="title"]', 'New Beach Cleanup Idea');
        await page.fill('textarea[name="description"]', 'Lots of plastic at Mount Lavinia');
        await page.fill('input[name="location"]', 'Mount Lavinia');
        await page.click('button[type="submit"]');

        // Verify redirection or success message
        await expect(page.locator('body')).toContainText(/Proposal|Success|Submitted/i);
    });

    test('Attendance QR Scan Flow (Simulated)', async ({ page }) => {
        // Attendance route requires a token.
        await page.goto('index.php?route=attendance_checkin&token=invalid_token');
        // Accept any error/invalid/expired style message
        await expect(page.locator('body')).toContainText(/Error|Invalid|Expired/i);
    });

    test('Notifications & Messaging', async ({ page }) => {
        await page.goto('index.php?route=check_notifications');
        const content = await page.innerText('body');
        // Messaging Inbox
        await page.goto('index.php?route=messages');
        await expect(page.locator('body')).toContainText(/Messages|Inbox/i);
    });

    test('Leaderboard (Top 10 / Monthly History)', async ({ page }) => {
        await page.goto('index.php?route=leaderboard');
        await expect(page.locator('body')).toContainText(/Leaderboard|Standings/i);
    });

});
