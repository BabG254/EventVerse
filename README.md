🚀 Multi-Tenant Event Management System

A simplified multi-tenant event management system built with Laravel 11 (API) and Nuxt 3 (Frontend) using MySQL, Tailwind CSS, and TypeScript.

📦 Tech Stack

Laravel 11+ (REST API)

Nuxt 3 (Frontend)

MySQL (XAMPP)

TypeScript

Tailwind CSS


🏗️ Features

Admin Dashboard (Laravel)

Admin registration/login (email/password).

Create and manage organizations.

Organization can manage:

Events (title, description, venue, date, price, max attendees)

Attendees (name, email, phone)

Organization-level data isolation (multi-tenancy):

Path-based routing: /api/{organization_slug}/events

Soft deletes (SoftDeletes trait).

Activity logs for event changes.

Public Frontend (Nuxt 3)

View upcoming events for a specific organization (via slug).

Register for events (name/email/phone).

Success/Failure message after registration.

Calls Laravel API using useFetch.

🛠️ Setup Instructions

✅ Laravel API

Clone repo & install dependencies:

cd mtems-api
composer install
cp .env.example .env
php artisan key:generate

Set DB credentials in .env

Run migrations:

php artisan migrate

Start server:

php artisan serve

✅ Nuxt 3 Frontend

Install dependencies:

cd mtems-frontend
npm install

Add API base URL to .env:

NUXT_PUBLIC_API_BASE=http://localhost:8000/api

Start dev server:
npm run dev
