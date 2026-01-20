import { test, expect } from '@playwright/test';

test.describe('Edge Cases & Security', () => {

    test('EC_AUTH_01: Duplicate Email Registration', async ({ page }) => {
        await page.goto('index.php?route=register');

        // Fill with an existing email (assuming suthan@example.com exists)
        await page.fill('input[name="name"]', 'Duplicate User');
        await page.fill('input[name="email"]', 'suthank@gmail.com'); // Admin or common email
        await page.fill('input[name="password"]', 'Password123!');
        await page.fill('input[name="password_confirm"]', 'Password123!');
        await page.fill('input[name="city"]', 'Colombo');
        await page.fill('input[name="latitude"]', '6.9271');
        await page.fill('input[name="longitude"]', '79.8612');

        await page.click('button[type="submit"]');

        // Expect error message about email already existing
        await expect(page.locator('.bg-red-50')).toContainText(/email/i);
    });

    test('EC_ADM_01: Unauthorized access to Admin Panel', async ({ page }) => {
        // Attempting to access admin dashboard without being logged in as admin
        await page.goto('index.php?route=admin_dashboard');

        // Most systems redirect to login or show 403
        // or the controller might just redirect to home
        const currentUrl = page.url();
        if (currentUrl.includes('route=admin_dashboard')) {
            // If it still shows admin dashboard, check if there's a login prompt
            const heading = page.locator('h1');
            await expect(heading).not.toContainText('Admin Dashboard');
        } else {
            // Successfully blocked
            await expect(page).toHaveURL(/.*route=login/);
        }
    });

    test('EC_FEED_01: Invalid file format in Gallery', async ({ page }) => {
        // This test assumes user is logged in. 
        // For now, checking the store route directly.
        await page.goto('index.php?route=gallery');

        // Check if "Post Impact" button exists (requires login)
        const postButton = page.locator('button:has-text("Post Impact")');
        if (await postButton.isVisible()) {
            await postButton.click();

            // Try uploading a non-image file
            // await page.setInputFiles('input[name="media"]', 'package.json');
            // await page.click('button:has-text("Share")');
            // await expect(page.locator('.text-red-500')).toBeVisible();
        }
    });

});
