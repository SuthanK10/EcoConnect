import { test, expect } from '@playwright/test';

test.describe('Admin Master Workflows', () => {

    test.beforeEach(async ({ page }) => {
        // 1. Login as Admin first
        await page.goto('index.php?route=login');
        await page.fill('input[name="email"]', 'admin@gmail.com');
        await page.fill('input[name="password"]', 'Password123!');
        await page.click('button[type="submit"]');

        // Wait for server processing
        await page.waitForLoadState('networkidle');

        // Ensure we are redirected away from login
        await expect(page).not.toHaveURL(/.*route=login/);
    });

    test('Admin Dashboard & Statistics', async ({ page }) => {
        await page.goto('index.php?route=admin_dashboard');
        await expect(page.locator('h1')).toContainText(/Central Command/i);

        // Exact line match to avoid descriptions
        await expect(page.locator('p').filter({ hasText: /^Cleanup Missions$/ })).toBeVisible();
        await expect(page.locator('p').filter({ hasText: /^Total Volunteers$/ })).toBeVisible();
        await expect(page.locator('p').filter({ hasText: /^Partner NGOs$/ })).toBeVisible();
    });

    test('Manage NGOs & Projects', async ({ page }) => {
        await page.goto('index.php?route=admin_ngos');
        await expect(page.locator('h2')).toContainText(/Manage NGOs/i);

        await page.goto('index.php?route=admin_projects');
        await expect(page.locator('h2')).toContainText(/Drive Management/i);
    });

    test('Moderation: Posts & Proposals', async ({ page }) => {
        // Post Moderation
        await page.goto('index.php?route=admin_post_moderation');
        await expect(page.locator('body')).toContainText(/Pending Posts|Moderation/i);

        // Community Proposals
        await page.goto('index.php?route=admin_proposals');
        await expect(page.locator('body')).toContainText(/Moderation|Proposals/i);
    });

    test('Maintenance Tools: Re-engagement & NGO Inactivity', async ({ page }) => {
        await page.goto('index.php?route=admin_dashboard');
        await expect(page.locator('button:has-text("Run Inactivity Cleanup")')).toBeVisible();
        await expect(page.locator('button:has-text("Reach Out to Inactive Users")')).toBeVisible();
    });

    test('Appeals & Reactivations', async ({ page }) => {
        await page.goto('index.php?route=admin_appeals');
        // Admin appeals uses h1 "Reactivation Appeals"
        await expect(page.locator('h1')).toContainText(/Appeals/i);
    });

    test('Leaderboard Finalization (POST)', async ({ page }) => {
        await page.goto('index.php?route=admin_dashboard');
        const finalizeBtn = page.locator('button:has-text("Finalize Rankings Now")');
        await expect(finalizeBtn).toBeVisible();
    });
});
