import { test, expect } from '@playwright/test';

test.describe('Role-Based Access Control', () => {
  test('Regular User cannot access rules', async ({ page }) => {
    // Log in as Regular User
    await page.goto('/login');
    await page.fill('input[name="email"]', 'user@palimpsest.dev');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    await expect(page).toHaveURL('/dashboard');
    
    // Check that Rules tab is hidden from navigation
    const rulesNav = page.getByRole('link', { name: 'Rules' });
    await expect(rulesNav).toHaveCount(0); // Should not exist
    
    // Try to access it directly via URL
    const response = await page.goto('/custom-rules');
    expect(response?.status()).toBe(403);
  });

  test('Admin can access rules', async ({ page }) => {
    // Log in as Admin
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@palimpsest.dev');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    
    await expect(page).toHaveURL('/dashboard');
    
    // Rules tab should be visible
    const rulesNav = page.getByRole('link', { name: 'Rules' }).first();
    await expect(rulesNav).toBeVisible();
    
    // Can access directly
    const response = await page.goto('/custom-rules');
    expect(response?.status()).toBe(200);
    await expect(page.locator('text=Custom Detection Rules')).toBeVisible();
  });
});
