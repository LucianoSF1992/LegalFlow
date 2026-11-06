# LegalFlow

A demonstrative legal-office management system built as a portfolio
project. LegalFlow combines a Laravel REST API with a Vue 3 single-page
application to showcase common workflows for managing clients, legal
cases, tasks, and documents.

**Live demo:** https://legalflow.lucianoferreiradev.com
**Repository:** https://github.com/LucianoSF1992/LegalFlow

> **Demo project:** use fictional data only. This application must not
> store real personal information, legal documents, or confidential
> data. Real-world use requires additional security, authorization,
> privacy, data protection, and backup controls to be reviewed and
> implemented.

## Overview

This project demonstrates: - REST API development with Laravel and
PHP. - Token-based authentication with Laravel Sanctum. - A Vue 3 and
TypeScript single-page application. - Relational data persistence with
MySQL. - CRUD operations, validation, pagination, and frontend/backend
integration. - Automated tests with PHPUnit. - Containerization and
deployment of a full-stack application to a Linux VPS.

## Tech stack

  Layer               Technologies
  ------------------- -------------------------------
  Backend             PHP 8.4, Laravel 12
  Authentication      Laravel Sanctum
  Database            MySQL 8.4
  Frontend            Vue 3, TypeScript, Vite
  State and routing   Pinia, Vue Router
  HTTP client         Axios
  Testing             PHPUnit
  Containers          Docker, Docker Compose
  Infrastructure      Ubuntu Server, Nginx, PHP-FPM
  HTTPS               Let's Encrypt / Certbot

## Features

-   User registration and token-based authentication.
-   Login and logout.
-   Dashboard metrics.
-   Client creation, viewing, updating, and deletion.
-   Legal case management linked to clients.
-   Task management with priority, status, and due date.
-   Document upload, listing, download, and deletion.
-   Private document storage.
-   Pagination, basic search, and filters.
-   Input validation and authenticated API routes.
-   Responsive Portuguese-language interface.

## Live demo

Access **https://legalflow.lucianoferreiradev.com**

Users can create an account directly in the application and explore its
features. Use fictional information only; do not upload real documents
or sensitive personal data.

## Architecture

The project is split into two applications:

-   `backend/`: Laravel REST API, responsible for authentication,
    business logic, validation, and persistence.
-   `frontend/`: Vue 3 SPA, responsible for the user interface and HTTP
    communication with the API.

### Production environment

The application is deployed on an Ubuntu VPS with the following
architecture:

-   Host Nginx serves the frontend static files and forwards API
    requests.
-   Laravel backend runs in a Docker container with PHP-FPM.
-   MySQL 8.4 runs in a Docker container.
-   An internal Docker network connects the backend and database.
-   A persistent Docker volume stores MySQL data.
-   A persistent Docker volume stores uploaded documents.
-   PHP-FPM is bound to `127.0.0.1:9000` and is not directly exposed to
    the public internet.
-   HTTPS is configured with a Let's Encrypt certificate.

The deployed environment uses a dedicated Nginx virtual host for the
LegalFlow domain without replacing the configuration of other
applications hosted on the VPS.

## Local prerequisites

-   PHP 8.2 or later, with Laravel's required extensions and
    `pdo_mysql`.
-   Composer 2.
-   Node.js 20 or later and npm.
-   Docker with Docker Compose, or a MySQL 8+ installation.
-   The PHP `pdo_sqlite` extension enabled to run automated tests.

## Run locally

Run the following commands from the project root unless stated
otherwise.

### 1. Start the database

Using Docker Compose:

``` bash
docker compose up -d db
```

The local example configuration uses: - Database: `legalflow` -
Username: `legalflow` - Password: `legalflow_local_only`

These credentials are for local development only. **Never reuse them in
production.**

If you use your own MySQL installation, create the database and user,
then update the `DB_*` variables in `backend/.env`.

### 2. Configure and run the backend

On Windows PowerShell:

``` powershell
cd backend
Copy-Item .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

On Linux/macOS:

``` bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The API will be available at `http://127.0.0.1:8000/api`.

The seeder creates a demo account only in the `local` environment: -
Email: `demo@legalflow.local` - Password: `Demo@12345`

This account and password are for local development only. Do not use
these credentials in production or on external services.

### 3. Configure and run the frontend

Open another terminal from the project root.

On Windows PowerShell:

``` powershell
cd frontend
Copy-Item .env.example .env
npm install
npm run dev
```

On Linux/macOS:

``` bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

The application will be available at `http://localhost:5173`.

### 4. Run automated tests

From the `backend` directory:

Windows PowerShell:

``` powershell
.\vendor\bin\phpunit
```

Linux/macOS:

``` bash
./vendor/bin/phpunit
```

Tests use an in-memory SQLite database as configured by PHPUnit. The PHP
`pdo_sqlite` extension must be enabled.

### 5. Build the frontend for production

From the `frontend` directory:

``` bash
npm run build
```

Production assets are generated in `frontend/dist`.

## Main API endpoints

  --------------------------------------------------------------------------------
  Method                  Endpoint                         Description
  ----------------------- -------------------------------- -----------------------
  POST                    `/api/auth/register`             User registration

  POST                    `/api/auth/login`                Authentication

  POST                    `/api/auth/logout`               Revoke current token

  GET                     `/api/auth/me`                   Authenticated user
                                                           details

  GET                     `/api/dashboard`                 Dashboard metrics

  GET/POST                `/api/clients`                   List and create clients

  GET/PUT/DELETE          `/api/clients/{id}`              Read, update, and
                                                           delete a client

  GET/POST                `/api/cases`                     List and create legal
                                                           cases

  GET/PUT/DELETE          `/api/cases/{id}`                Read, update, and
                                                           delete a legal case

  GET/POST                `/api/tasks`                     List and create tasks

  GET/PUT/DELETE          `/api/tasks/{id}`                Read, update, and
                                                           delete a task

  GET/POST                `/api/documents`                 List and upload
                                                           documents

  GET                     `/api/documents/{id}/download`   Download a document

  DELETE                  `/api/documents/{id}`            Delete a document
  --------------------------------------------------------------------------------

Registration and login routes are public. The other business endpoints
require Bearer-token authentication.

## Project structure

``` text
LegalFlow/
├── backend/                    # Laravel API
│   ├── app/
│   ├── database/
│   ├── routes/
│   ├── tests/
│   └── Dockerfile
├── frontend/                   # Vue 3 application
│   ├── src/
│   ├── public/
│   └── package.json
├── deploy/                     # Deployment files
│   ├── docker-compose.production.yml
│   ├── .env.production.example
│   ├── nginx-legalflow.conf.example
│   └── DEPLOY-PRODUCTION.md
├── docker-compose.yml           # Local environment
├── DEPLOY.md
├── README.md
└── README.en.md
```

## Configuration and security

-   `.env` files contain environment-specific settings and must not be
    committed.
-   Generate a separate `APP_KEY` for each environment.
-   Use strong, unique production credentials.
-   Keep `APP_DEBUG=false` in production.
-   Do not run the demo seeder in production.
-   Store uploaded files privately and configure upload limits.
-   Configure and regularly verify backups.
-   Use HTTPS and monitor application logs.
-   Review authorization rules, public registration, and abuse
    prevention before making the application available beyond
    demonstration purposes.
-   Do not use real client or legal case data.

## Deployment

Deployment instructions are documented in: -
[`DEPLOY.md`](./DEPLOY.md) -
[`deploy/DEPLOY-PRODUCTION.md`](./deploy/DEPLOY-PRODUCTION.md)

Nginx and Docker Compose examples are available in the `deploy/`
directory. The `deploy/.env.production.example` file is a template only:
real credentials and the application key must remain in an untracked
environment file.

------------------------------------------------------------------------

Built as a portfolio project to demonstrate Full Stack development, REST
APIs, authentication, data persistence, Docker, and Linux server
deployment.
