// tests/all_functionalities.master.test.ts
import { test, expect, Page } from "@playwright/test";

/**
 * MASTER FUNCTIONALITY SMOKE SUITE
 */

// Use the same BASE_URL as your other tests for consistency
const BASE_URL = "http://localhost/Eco-Connect/public/index.php";

const CREDS = {
    user: { email: "test@gmail.com", pass: "test123" },
    ngo: { email: "chelsea@gmail.com", pass: "Password123!" },
    admin: { email: "admin@gmail.com", pass: "Password123!" },
};

function routeUrl(route: string, params: Record<string, string | number> = {}) {
    const u = new URL(BASE_URL);
    u.searchParams.set("route", route);
    for (const [k, v] of Object.entries(params)) u.searchParams.set(k, String(v));
    return u.toString();
}

async function gotoAndAssertBasic(page: Page, url: string, note?: string) {
    const resp = await page.goto(url, { waitUntil: "networkidle" });

    // Basic fatal error check
    const body = await page.locator('body').innerText().catch(() => "");
    expect(body, `${note || ""} should not have PHP errors`).not.toMatch(/Fatal error|Parse error|Uncaught Error|Warning:\s/i);

    if (resp && resp.status() >= 400) {
        // Some redirects are expected (e.g. event_show without ID)
        if (resp.status() === 302 || resp.status() === 301) return;
        throw new Error(`${note || "Page"} returned status ${resp.status()} at ${url}`);
    }
}

async function tryLogin(page: Page, role: "user" | "ngo" | "admin") {
    const { email, pass } = CREDS[role];
    await page.goto(routeUrl("login"), { waitUntil: "networkidle" });

    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', pass);
    await page.click('button[type="submit"]');

    await page.waitForLoadState("networkidle");
    const body = await page.locator('body').innerText().catch(() => "");
    expect(body).not.toMatch(/invalid|incorrect|failed|lockout/i);
}

async function logout(page: Page) {
    await page.goto(routeUrl("logout"), { waitUntil: "networkidle" });
}

const PUBLIC_ROUTES = [
    "home", "events", "gallery", "donations", "rewards", "map",
    "explore_drives", "partnerships", "login", "register", "forgot_password"
];

const USER_ROUTES = [
    "user_dashboard", "user_edit_profile", "user_my_cleanups",
    "user_propose_cleanup", "user_my_proposals", "leaderboard",
    "messages", "contact_ngo", "contact_admin"
];

const NGO_ROUTES = [
    "ngo_dashboard", "ngo_profile_edit", "ngo_project_new",
    "ngo_proposals", "ngo_feedback"
];

const ADMIN_ROUTES = [
    "admin_dashboard", "admin_ngos", "admin_projects",
    "admin_users", "admin_appeals", "admin_proposals", "admin_post_moderation"
];

test.describe("MASTER: Public Pages", () => {
    for (const r of PUBLIC_ROUTES) {
        test(`Route: ${r}`, async ({ page }) => {
            await gotoAndAssertBasic(page, routeUrl(r), r);
        });
    }
});

test.describe("MASTER: Access Control", () => {
    test("Blocked Routes for Guest", async ({ page }) => {
        const protectedRoutes = [...USER_ROUTES, ...NGO_ROUTES, ...ADMIN_ROUTES];
        for (const r of protectedRoutes) {
            await page.goto(routeUrl(r));
            await page.waitForLoadState("networkidle");
            const url = page.url();
            const body = await page.locator('body').innerText().catch(() => "");

            const isBlocked = url.includes("route=login") ||
                url.includes("route=home") ||
                url.endsWith("/public/") ||
                /login|unauthorized|access denied|permission/i.test(body);

            expect(isBlocked, `Guest should be blocked from ${r}`).toBeTruthy();
        }
    });
});

test.describe("MASTER: Role Workflows", () => {
    test("Volunteer Master Flow", async ({ page }) => {
        await tryLogin(page, "user");
        for (const r of USER_ROUTES) {
            // Skip contact/message routes that might need IDs for now
            if (['contact_ngo', 'contact_admin', 'messages'].includes(r)) continue;
            await gotoAndAssertBasic(page, routeUrl(r), `User ${r}`);
        }

        // Check forbidden access
        for (const r of ADMIN_ROUTES) {
            await page.goto(routeUrl(r));
            await page.waitForLoadState("networkidle");
            const url = page.url();
            const body = await page.locator('body').innerText().catch(() => "");

            const isBlocked = url.includes("route=login") ||
                url.includes("route=home") ||
                url.includes("route=user_dashboard") ||
                url.endsWith("/public/") ||
                /login|unauthorized|access denied|permission|restricted/i.test(body);

            if (!isBlocked) {
                console.log(`FAIL: User accessed forbidden route ${r}. URL: ${url}`);
            }
            expect(isBlocked, `User should be blocked from ${r}. Current URL: ${url}`).toBeTruthy();
        }
        await logout(page);
    });

    test("NGO Master Flow", async ({ page }) => {
        await tryLogin(page, "ngo");
        for (const r of NGO_ROUTES) {
            await gotoAndAssertBasic(page, routeUrl(r), `NGO ${r}`);
        }
        await logout(page);
    });

    test("Admin Master Flow", async ({ page }) => {
        await tryLogin(page, "admin");
        for (const r of ADMIN_ROUTES) {
            await gotoAndAssertBasic(page, routeUrl(r), `Admin ${r}`);
        }
        await logout(page);
    });
});
