import { test, expect } from '@playwright/test';

test.describe('Authentication Flow', () => {
  test('user can log in and log out', async ({ page }) => {
    // Navigate to login
    await page.goto('/login');
    
    // Fill in credentials for the seeded Admin user
    await page.fill('input[name="email"]', 'admin@palimpsest.dev');
    await page.fill('input[name="password"]', 'password');
    
    // Click submit
    await page.click('button[type="submit"]');
    
    // Verify redirection to dashboard and presence of dashboard header
    await expect(page).toHaveURL('/dashboard');
    await expect(page.getByRole('heading', { name: 'Dashboard' })).toBeVisible();

    // Verify Admin specific elements are visible (using .first() for mobile/desktop dupes)
    await expect(page.getByText('Admin User').first()).toBeVisible();
    
    // Log out
    await page.getByText('Admin User').first().click(); // Open dropdown
    await page.getByText('Log Out').first().click();    // Click logout
    
    // Verify redirected back to home or login page by checking for the login button
    await expect(page.getByRole('button', { name: 'Log in' })).toBeVisible();
  });

  test('invalid login shows error', async ({ page }) => {
    await page.goto('/login');
    
    await page.fill('input[name="email"]', 'wrong@example.com');
    await page.fill('input[name="password"]', 'wrongpassword');
    await page.click('button[type="submit"]');
    
    // Verify validation error
    await expect(page.locator('text=These credentials do not match our records.')).toBeVisible();
  });
});
