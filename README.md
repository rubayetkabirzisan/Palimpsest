# Palimpsest Sentinel 🛡️

**An Enterprise-Grade AI Data Loss Prevention (DLP) Platform**

Palimpsest Sentinel is a security application designed to detect and redact Personally Identifiable Information (PII) from text documents in real-time. By leveraging Google's Gemini AI, Palimpsest acts as a smart firewall, ensuring that sensitive data (credit cards, API keys, emails) is automatically redacted before unauthorized personnel can view it.

![Build Status](https://img.shields.io/github/actions/workflow/status/rubayetkabirzisan/Palimpsest-Sentinel/ci.yml?branch=main&label=CI/CD%20Pipeline&style=flat-square)
![Stack](https://img.shields.io/badge/Stack-Laravel%20%7C%20Supabase%20%7C%20Tailwind-blue?style=flat-square)
![Testing](https://img.shields.io/badge/Testing-Playwright%20E2E-green?style=flat-square)

## 🚀 Key Features

- **AI-Powered Redaction:** Offloads document scanning to background queue workers that interface directly with the Gemini 1.5 Flash API to intelligently identify and redact sensitive data.
- **Role-Based Access Control (RBAC):**
  - **Admin:** Full access to raw data and custom detection rules.
  - **Compliance Officer:** Access to raw data and company-wide audit logs.
  - **Regular User:** Strict isolation. Can only view their own documents, with all sensitive PII automatically replaced with `[REDACTED]` tags.
- **Audit Logging:** Immutable tracking of all user actions (logins, document uploads, views).
- **Automated E2E Testing:** A complete Playwright test suite running against an isolated SQLite testing database.
- **DevOps Ready:** Fully containerized with Docker and integrated with a GitHub Actions CI/CD pipeline.

## 🏗️ Tech Stack

- **Backend:** Laravel 11 (PHP 8.4)
- **Database:** Supabase (PostgreSQL) + SQLite (for testing)
- **Frontend:** Blade Templates, Tailwind CSS, Vanilla JS
- **AI Integration:** Google Gemini
- **Testing:** Playwright
- **DevOps:** Docker, GitHub Actions

---

## 💻 Running Locally (with Docker)

The easiest way to run the application is using the provided Docker configuration. You do not need PHP or Node installed on your local machine.

1. **Clone the repository:**
   ```bash
   git clone https://github.com/rubayetkabirzisan/Palimpsest-Sentinel.git
   cd Palimpsest-Sentinel
   ```

2. **Configure your environment:**
   Copy the example environment file:
   ```bash
   cp .env.example .env
   ```
   Open `.env` and configure your Database and Gemini credentials:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=your-supabase-host
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres
   DB_PASSWORD=your-supabase-password

   GEMINI_API_KEY=your-gemini-api-key
   ```

3. **Boot the application:**
   This will build the containers, install dependencies, and start both the web server and the AI queue worker.
   ```bash
   docker compose up -d
   ```

4. **Access the application:**
   Open your browser and navigate to `http://localhost:8000`.

*(Note: Ensure you have run migrations and seeded the database using `php artisan migrate --seed` inside the container before logging in).*

---

## 🧪 Testing

The application includes an End-to-End test suite written in Playwright. The tests are designed to run against an isolated SQLite database (`.env.testing`) to prevent destructive actions against the production Supabase database.

To run the tests locally, first create your testing environment file:
```bash
cp .env.example .env.testing
php artisan key:generate --env=testing
```

Then execute the test suite:
```bash
npm ci
npm run test:e2e
```
To run the tests with the visual UI dashboard:
```bash
npm run test:e2e:ui
```