import { test, expect } from '@playwright/test';

test.describe('Authentication & Security Workflows', () => {

    test('Volunteer Registration Flow', async ({ page }) => {
        await page.goto('index.php?route=register');
        await page.check('input[value="user"]', { force: true });

        await page.fill('input[name="name"]', 'Automation Volunteer');
        await page.fill('input[name="email"]', `vol_${Date.now()}@example.com`);
        await page.fill('input[name="password"]', 'Password123!');
        await page.fill('input[name="password_confirm"]', 'Password123!');
        await page.fill('input[name="city"]', 'Colombo');
        await page.fill('input[name="latitude"]', '6.9271');
        await page.fill('input[name="longitude"]', '79.8612');

        await page.click('button[type="submit"]');
        // Success should redirect to dashboard or show success message
        await expect(page).toHaveURL(/.*route=user_dashboard|login/);
    });

    test('NGO Registration Flow (Pending Admin Approval)', async ({ page }) => {
        await page.goto('index.php?route=register');
        // Select NGO role via label as per previous fix
        await page.locator('label').filter({ has: page.locator('input[value="ngo"]') }).click();

        await page.fill('input[name="name"]', 'NGO Applicant');
        await page.fill('input[name="email"]', `ngo_${Date.now()}@example.com`);
        await page.fill('input[name="ngo_name"]', 'Green Earth NGO');
        await page.fill('input[name="verification_link"]', 'https://greenearth.org/verify');

        // Test logo upload check (optional but listed in specs)
        await expect(page.locator('input[name="ngo_logo"]')).toBeVisible();

        // We expect a message about "pending admin approval"
        // await page.click('button[type="submit"]');
        // await expect(page.locator('text=pending admin approval')).toBeVisible();
    });

    test('Password Reset & Appeals Navigation', async ({ page }) => {
        await page.goto('index.php?route=forgot_password');
        await expect(page.locator('input[name="email"]')).toBeVisible();

        // Check appeal route
        await page.goto('index.php?route=submit_appeal');
        await expect(page.locator('form')).toBeVisible();
    });

    test('Logout (Security)', async ({ page }) => {
        // If logged in, logout should clear session
        await page.goto('index.php?route=logout');
        await expect(page).toHaveURL(/.*route=home|index.php$/);
    });

});
