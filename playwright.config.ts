import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './tests/e2e',
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 0,
  workers: process.env.CI ? 1 : undefined,
  reporter: 'html',
  
  use: {
    baseURL: 'http://127.0.0.1:8001',
    trace: 'on-first-retry',
  },

  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
  ],

  // Automatically start Laravel PHP server before tests
  webServer: {
    command: 'php artisan serve --host=127.0.0.1 --port=8001 --env=testing',
    url: 'http://127.0.0.1:8001',
    reuseExistingServer: !process.env.CI,
    timeout: 30000,
  },
});
