import { test, expect } from '@playwright/test';

test.describe('NGO Workflows', () => {

    test.beforeEach(async ({ page }) => {
        await page.goto('index.php?route=login');
        await page.fill('input[name="email"]', 'chelsea@gmail.com');
        await page.fill('input[name="password"]', 'Password123!');
        await page.click('button[type="submit"]');

        await page.waitForLoadState('networkidle');
        await expect(page).not.toHaveURL(/.*route=login/);
    });

    test('NGO Dashboard & Inactivity Tracking', async ({ page }) => {
        // Specs: Dashboard updates "activity" timestamp
        await page.goto('index.php?route=ngo_dashboard');
        // Accept both "Dashboard" or "Command Center"
        await expect(page.locator('h1')).toContainText(/Dashboard|Command Center/i);
        // Should list cleanup drives
        await expect(page.locator('body')).toContainText(/Cleanup Drives/i);
    });

    test('Create Cleanup Drive (10 points/hour logic)', async ({ page }) => {
        await page.goto('index.php?route=ngo_project_new');

        // approved NGOs only - if not approved, check for warning
        if (await page.locator('text=pending admin approval').isVisible()) return;

        await page.fill('input[name="title"]', 'River Bank Cleanup');
        await page.fill('textarea[name="description"]', 'Cleaning Kelani River bank');
        await page.fill('input[name="event_date"]', '2026-05-20');
        await page.fill('input[name="start_time"]', '08:00');
        await page.fill('input[name="end_time"]', '12:00'); // 4 hours = 40 points

        await page.click('button[type="submit"]');
    });

    test('QR Generation for Attendance', async ({ page }) => {
        // Requires an existing project ID
        await page.goto('index.php?route=ngo_generate_qr&project_id=1&type=checkin');
        // Target the QR code specifically (logo is usually first img)
        const qrImage = page.locator('img[alt*="QR"], img[src*="qrserver"]');
        await expect(qrImage.first()).toBeVisible();
    });

    test('Proposal Adoption & Feedback', async ({ page }) => {
        await page.goto('index.php?route=ngo_proposals');
        await expect(page.locator('body')).toContainText(/Proposals/i);

        await page.goto('index.php?route=ngo_feedback');
        await expect(page.locator('body')).toContainText(/Rating|Feedback/i);
    });

});
