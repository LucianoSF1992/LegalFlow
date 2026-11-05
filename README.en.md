# LegalFlow

A demonstrative legal-office management system built as a portfolio project. LegalFlow combines a Laravel REST API with a Vue 3 single-page application to showcase common workflows for managing clients, legal cases, tasks, and documents.

> **Demo project:** use fictional data only. This application must not store real personal information, legal documents, or confidential data. Real-world use requires security, authorization, privacy, backup, and additional operational controls to be reviewed and implemented.

## Overview

This project demonstrates:
- REST API development with Laravel and PHP.
- Token-based authentication with Laravel Sanctum.
- A Vue 3 and TypeScript single-page application.
- Relational data persistence with MySQL.
- CRUD operations, validation, pagination, and frontend/backend integration.
- Automated tests with PHPUnit.

## Tech stack

| Layer | Technologies |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| Authentication | Laravel Sanctum |
| Database | MySQL 8+ |
| Frontend | Vue 3, TypeScript, Vite |
| State and routing | Pinia, Vue Router |
| HTTP client | Axios |
| Testing | PHPUnit |
| Local database environment | Docker Compose |

## Features

- User registration and token-based authentication.
- Dashboard metrics.
- Client creation, viewing, updating, and deletion.
- Legal case management linked to clients.
- Task management with priority, status, and due date.
- Document upload, listing, download, and deletion.
- Pagination, basic search, and filters.
- Input validation and authenticated API routes.
- Responsive Portuguese-language interface.

## Architecture

The project is split into two applications:

- `backend/`: Laravel REST API, responsible for authentication, business logic, validation, and persistence.
- `frontend/`: Vue 3 SPA that communicates with the API through HTTP requests.

MySQL can be started locally with Docker Compose. Vite builds the frontend into static production assets.

## Local prerequisites

- PHP 8.2 or later, with Laravel's required extensions and `pdo_mysql`.
- Composer 2.
- Node.js 20 or later and npm.
- Docker with Docker Compose, or a MySQL 8+ installation.

## Run locally

Run the following commands from the project root unless stated otherwise.

### 1. Start the database

Using Docker Compose:

```bash
docker compose up -d db
```

The local example configuration uses:
- Database: `legalflow`
- Username: `legalflow`
- Password: `legalflow_local_only`

These credentials are for local development only. **Never reuse them in production.**

If you use your own MySQL installation, create the database and user, then update the `DB_*` variables in `backend/.env`.

### 2. Configure and run the backend

On Windows PowerShell:

```powershell
cd backend
Copy-Item .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

On Linux/macOS, replace the copy command with:

```bash
cp .env.example .env
```

The API will be available at:

`http://127.0.0.1:8000/api`

The local seeder creates a demo account:

- Email: `demo@legalflow.local`
- Password: `Demo@12345`

This account and password are for local development only. Do not publish production credentials or reuse this password on external services.

### 3. Configure and run the frontend

Open another terminal from the project root.

On Windows PowerShell:

```powershell
cd frontend
Copy-Item .env.example .env
npm install
npm run dev
```

On Linux/macOS, use `cp .env.example .env` to copy the file.

The application will be available at:

`http://localhost:5173`

### 4. Run automated tests

From the `backend` directory:

Windows PowerShell:

```powershell
.\vendor\bin\phpunit
```

Linux/macOS:

```bash
./vendor/bin/phpunit
```

Tests use an in-memory SQLite database as configured by PHPUnit. The PHP `pdo_sqlite` extension must be enabled.

### 5. Build the frontend for production

From the `frontend` directory:

```bash
npm run build
```

Production assets are generated in `frontend/dist`.

## Main API endpoints

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/auth/register` | User registration |
| POST | `/api/auth/login` | Authentication |
| POST | `/api/auth/logout` | Revoke current token |
| GET | `/api/auth/me` | Authenticated user details |
| GET | `/api/dashboard` | Dashboard metrics |
| GET/POST | `/api/clients` | List and create clients |
| GET/PUT/DELETE | `/api/clients/{id}` | Read, update, and delete a client |
| GET/POST | `/api/cases` | List and create legal cases |
| GET/PUT/DELETE | `/api/cases/{id}` | Read, update, and delete a legal case |
| GET/POST | `/api/tasks` | List and create tasks |
| GET/PUT/DELETE | `/api/tasks/{id}` | Read, update, and delete a task |
| GET/POST | `/api/documents` | List and upload documents |
| GET | `/api/documents/{id}/download` | Download a document |
| DELETE | `/api/documents/{id}` | Delete a document |

Registration and login routes are public. The other endpoints listed above require Bearer-token authentication.

## Configuration and security

- `.env` files contain environment-specific settings and must not be committed.
- Generate a separate `APP_KEY` for each environment.
- Use strong, unique production credentials.
- Keep `APP_DEBUG=false` in production.
- Do not use the local demo account as a production account.
- Store uploaded files privately and configure upload limits.
- Configure HTTPS, backups, and log monitoring.
- Do not use real client or legal case data in this demo project.

## Deployment

LegalFlow is being prepared for deployment on a VPS using:

- Nginx as the web server and reverse proxy.
- Vue 3 frontend served as static files.
- Laravel backend running in a Docker container with PHP-FPM.
- MySQL 8.4 running in a Docker container on an internal network.
- HTTPS with an SSL certificate.

Planned URL:

https://legalflow.lucianoferreiradev.com

Deployment instructions are documented in [`DEPLOY.md`](./DEPLOY.md). Deployment is still in preparation and will be validated before being considered complete.

Built as a portfolio project.
