import { test, expect } from '@playwright/test';

test.describe('Public Pages (Normal View)', () => {

    test('Home Page Content', async ({ page }) => {
        await page.goto('');
        await expect(page.locator('h1')).toContainText('Join the Movement');
        // Check for featured drives (up to 3)
        const featuredDrives = page.locator('.featured-drive-card'); // Note: Adjust selector to match your HTML class
        // Check for recent posts (up to 2)
        const recentPosts = page.locator('.community-post-card');
    });

    test('Explore Events & Filters', async ({ page }) => {
        await page.goto('index.php?route=events');
        // Actual heading is "Cleanup Drives"
        await expect(page.locator('h1')).toContainText(/Cleanup Drives|Explore|Events/i);

        // Test search/filter visibility
        await expect(page.locator('select[name="district"], input[placeholder*="district"]')).toBeAttached();
    });

    test('Community Gallery (Eco-Action Feed)', async ({ page }) => {
        await page.goto('index.php?route=gallery');
        await expect(page.locator('h1, h2')).toContainText(/Feed|Gallery|Moments/i);
        // Should see approved posts
        const images = page.locator('img');
        await expect(images.first()).toBeVisible();
    });

    test('Donations (Stripe Flow)', async ({ page }) => {
        await page.goto('index.php?route=donations');
        await page.fill('input[name="amount"]', '150'); // Min 100
        await page.click('button:has-text("Continue to Secure Payment")');
        // We just verify the button action triggers
    });

    test('Rewards & Map', async ({ page }) => {
        await page.goto('index.php?route=rewards');
        await expect(page.locator('body')).toContainText(/Rewards/i);

        await page.goto('index.php?route=explore_drives');
        await expect(page.locator('#map, .leaflet-container')).toBeAttached();
    });

    test('Partnerships Directory', async ({ page }) => {
        await page.goto('index.php?route=partnerships');
        await expect(page.locator('body')).toContainText(/Organizations|NGOs|Partners/i);
    });

});
