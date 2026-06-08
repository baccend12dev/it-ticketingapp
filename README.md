# OTTO IT - IT Support Ticketing Portal

OTTO IT is a modern, responsive, and secure IT Support Ticketing Application designed to streamline issue reporting and ticketing operations for organizations.

## Tech Stack

- **Framework**: Laravel 12
- **Language**: PHP 8.2
- **Database**: PostgreSQL (pgsql)
- **Frontend**: Bootstrap 5, Bootstrap Icons, Vanilla CSS Design System, Vite (Asset Bundler)

---

## Key Features

1. **Role-Based Operational Dashboard**:
   - Dynamic ticketing stats cards (Total, Active, Pending, Resolved) with micro-animations on hover.
   - Clickable statistics cards that redirect and auto-apply filters on the Ticket Directory.
   - Interactive Support Queue table showing the 5 most recent tickets with detail modals accessible directly from the dashboard.
   - Security-enforced role data isolation: regular users (`user`) only see their own tickets, while `admin` and `it` staff see system-wide statistics.

2. **Ticket Management**:
   - **Ticket Directory**: Searchable list with quick client-side filtering by status (All, Active, Pending, Resolved, Canceled).
   - **Detailed Modals**: Pop-up window containing detailed fields (priority, status, categories, sub-categories, department, location, reporter, descriptions, and file attachments).
   - **Public Submission**: Form allowing unauthenticated users to submit issues. The system auto-links the ticket to their email (or registers a new user account if they don't exist).
   - **File Attachments**: Upload and download options for attachments (supporting automatic inline image previews).
   - **Status Actions**: Direct status updates (Active, Pending, Resolved, Canceled) available to authorized staff via the directory dropdown.

3. **User & Identity Directory**:
   - Admin and IT staff can view, search, and register users.
   - Admins can delete users or toggle active/inactive status (protecting self-account modification).

4. **Master Data Administration (Admin Only)**:
   - Complete CRUD interfaces for managing **Departments**, **Locations**, **Categories**, and **Sub-Categories**.

---

## Installation & Setup

Follow these steps to set up the project locally:

### 1. Prerequisites
Ensure you have PHP 8.2, Composer, Node.js (with NPM), and a PostgreSQL database server installed.

### 2. Clone and Configure
Clone the project repository to your working directory. Then create your `.env` configuration file:
```bash
cp .env.example .env
```

Open `.env` and specify your PostgreSQL database credentials:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1      # Update with your PostgreSQL Host
DB_PORT=5432           # Default is 5432 (or 5434 in custom environments)
DB_DATABASE=it-ticket
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Install Dependencies
Install PHP dependencies via Composer and frontend packages via NPM:
```bash
composer install
npm install
```

### 4. Database Setup
Generate your application key, run database migrations, and seed initial master data and test accounts:
```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Start the Application
Compile the assets and run the local development server:
```bash
# Compile assets with Vite
npm run dev

# Start Laravel development server
php artisan serve
```

Access the application in your browser at `http://localhost:8000`.

---

## Default User Accounts

Use the following credentials to log in and test different system roles (Password for all accounts is `password`):

| Role | Email | Capabilities |
| :--- | :--- | :--- |
| **Administrator** | `admin@example.com` | Full access, Master Data CRUD, User Directory Management, All Tickets |
| **IT Support** | `it@example.com` | User Directory Management, Ticket Status Updating, All Tickets |
| **Regular User** | `user@example.com` | Submit Tickets, View and filter own tickets/stats only |

---

## Development & Maintenance

### Running Tests
To run the PHPUnit feature and unit test suites:
```bash
php artisan test
```

### Running Seeder Again
If you want to clear and re-populate the database with dummy tickets:
```bash
php artisan db:seed
```
