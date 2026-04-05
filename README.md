# Kargo Laravel

Kargo Laravel is a cargo service request management platform built with Laravel. It provides a complete workflow for customers to submit cargo service requests, employees to process and track shipments, and managers to oversee operations with approval workflows.

This project demonstrates backend architecture including role-based access control, multi-stage workflow processing, shipment tracking, notification systems, activity logging, and a Dockerized development environment.

---

## Features

### Authentication & Authorization
- Laravel Breeze authentication (login, register, logout, email verification, password reset)
- Role-based access control with three roles: **Customer**, **Employee**, **Manager**
- Role middleware protecting route groups (`role:customer`, `role:employee`, `role:manager`)
- First-time manager setup flow (only available when no manager exists)
- Employee account activation/deactivation by manager
- Active-user middleware that auto-logs-out deactivated accounts
- Request ownership enforcement via authorization policies (customers only see their own requests)

### Service Request Workflow
- Customers submit service requests for Import, Export, Courier, or Customs Clearance
- Auto-generated tracking IDs in format `KRG-YYYY-XXXXX`
- Multi-stage request status workflow: `request` → `pending` → `completed` → `approved` (or `revision_required`)
- Employee processing with cargo details (quantity, product detail, weight, dimension)
- Manager approval and revision-required flow
- Soft-delete (trash/restore) system for inactive requests

### Shipment Tracking
- Directional tracking flows based on service type:
  - **In-flow** (Import, Clearance): Warehouse → Customs → Office → Destination
  - **Out-flow** (Export, Courier): Office → Customs → Route → Destination
- Tracking events with notes and updater attribution
- Public tracking lookup page (no login required)

### Notifications
- In-app notification system triggered by workflow events:
  - Request submitted, approved, completed, re-completed, revision required, tracking updated
- Notifications sent to relevant roles (customers, employees, managers)
- Read/unread status with mark-as-read functionality

### Activity Logging
- Full audit trail on every service request
- Logs status changes, tracking updates, approvals, revisions, and trash actions
- Records old value → new value transitions with descriptions

### Dashboards
- **Customer Dashboard**: Total requests, in-progress count, recent requests
- **Employee Dashboard**: Assigned requests, pending action, processed count, work queue
- **Manager Dashboard**: Total requests, pending review, active staff, inactive requests, recent activity

### Staff Management (Manager)
- Create employee accounts
- View staff directory
- Activate/deactivate employee accounts
- Staff detail view with account status toggle

### Filtering & Search
- Manager and employee request lists with filters: status, tracking status, service type, tracking ID, customer name, date range
- Paginated results with query string persistence

---

## Tech Stack

- PHP 8.2
- Laravel 12
- MySQL 8.0
- Laravel Breeze
- Blade + Tailwind CSS
- Docker + Docker Compose
- Mailpit (email testing)
- phpMyAdmin (database GUI)
- PHPUnit (testing)

---

## Project Structure

### Models
- `User` — Roles: customer, employee, manager. Supports is_active flag.
- `ServiceRequest` — Core model with status, tracking, cargo details, soft-delete.
- `TrackingEvent` — Shipment tracking history entries.
- `ActivityLog` — Audit trail for all request changes.
- `Notification` — In-app notifications with read/unread state.

### Controllers
- `ServiceRequestController` — Customer CRUD for requests
- `Employee\RequestController` — Employee processing, status updates, tracking updates
- `Manager\RequestController` — Manager approval, revision, trash/restore
- `Manager\StaffController` — Employee account management
- `Customer\DashboardController` — Customer dashboard
- `NotificationController` — Notification listing and mark-as-read
- `PublicTrackingController` — Public tracking lookup
- `FirstManagerSetupController` — Initial manager account creation
- `ProfileController` — User profile management
- Auth controllers (Laravel Breeze)

### Middleware
- `RoleMiddleware` — Restricts routes by user role
- `EnsureUserIsActive` — Logs out deactivated users
- `EnsureNoManagerExists` — Guards the first-manager setup route
- `EnsureUserIsManager` — Legacy manager-only guard

### Policies
- `ServiceRequestPolicy` — Enforces customer ownership of their own requests

---

## Database Schema

### Tables
- `users` — name, email, password, role, is_active, email_verified_at
- `service_requests` — user_id, service_type, sender/receiver details, cargo details, notes, tracking_id, status, tracking_status, processed_by, processed_at, manager_note, employee_note, is_trashed, trashed_at, trashed_by, trash_reason
- `tracking_events` — service_request_id, updated_by, tracking_status, note
- `activity_logs` — service_request_id, user_id, action, field_changed, old_value, new_value, description
- `notifications` — user_id, service_request_id, type, title, message, read_at
- `cache`, `sessions`, `jobs`, `job_batches`, `failed_jobs`

---

## Setup

Clone the repository:

```bash
git clone https://github.com/khadkarewant/kargo-laravel
cd kargo-laravel
```

Copy the environment file:

```bash
cp .env.example .env
```

Start the Docker containers:

```bash
docker compose up -d --build
```

Generate the application key:

```bash
docker compose exec app php artisan key:generate
```

Run migrations:

```bash
docker compose exec app php artisan migrate
```

(Optional) Seed demo data:

```bash
docker compose exec app php artisan db:seed
```

Install frontend dependencies and build assets:

```bash
docker compose exec app npm install
docker compose exec app npm run build
```

Open in browser:

```
http://localhost:8000
```

---

## Docker Services

| Service    | Container          | Port  | Description                          |
|------------|--------------------|-------|--------------------------------------|
| app        | kargo_app          | 8000  | Laravel application (PHP 8.2 + Apache) |
| db         | kargo_db           | 3307  | MySQL 8.0 database                   |
| mailpit    | kargo_mailpit      | 8025  | Email testing UI                     |
| phpmyadmin | kargo_phpmyadmin   | 8080  | Database management GUI              |

---

## Useful Commands

Start containers:

```bash
docker compose up -d
```

Stop containers:

```bash
docker compose down
```

Rebuild containers:

```bash
docker compose up -d --build
```

Run Artisan commands:

```bash
docker compose exec app php artisan <command>
```

Run tests:

```bash
docker compose exec app php artisan test
```

Clear config cache:

```bash
docker compose exec app php artisan config:clear
```

---

## Demo Accounts

After running `php artisan db:seed`, the following accounts are available (password: `password`):

| Role     | Email                         | Name             |
|----------|-------------------------------|------------------|
| Manager  | manager@kargo.test            | Kargo Manager    |
| Employee | aarav.employee@kargo.test     | Aarav Shrestha   |
| Employee | sanjana.employee@kargo.test   | Sanjana Karki    |
| Employee | ritesh.employee@kargo.test    | Ritesh Lama      |
| Customer | nabin.customer@kargo.test     | Nabin Traders    |
| Customer | everest.customer@kargo.test   | Everest Imports  |
| Customer | himal.customer@kargo.test     | Himal Suppliers  |
| Customer | city.customer@kargo.test      | City Cargo Client|

---

## Request Workflow

```
Customer submits request
        │
        ▼
   [request] ──── Employee picks up ───► [pending]
                                              │
                                    Employee completes
                                              │
                                              ▼
                                        [completed]
                                         ╱         ╲
                            Manager approves    Manager requests revision
                                 ╱                     ╲
                                ▼                       ▼
                          [approved]           [revision_required]
                               │                       │
                   Employee updates            Employee re-processes
                   tracking status             and re-completes
                               │                       │
                               ▼                       ▼
                   Tracking flow begins         Back to [completed]
```

---

## Tracking Flow

**In-flow services** (Import, Clearance):

```
Warehouse → Customs → Office → Destination
```

**Out-flow services** (Export, Courier):

```
Office → Customs → Route → Destination
```

---

## Testing

The project includes comprehensive PHPUnit tests covering:

- Authentication flow (login, register, password reset, email verification)
- Service request CRUD and validation
- Request ownership and authorization (403 enforcement)
- Role-based access control across all route groups
- Workflow status transitions and edge cases
- Tracking status updates and sequencing
- Notification creation on workflow events
- Notification read behavior
- Public tracking lookup
- Trash/restore functionality
- First manager setup and validation
- Profile update and account deletion
- Password confirmation and update

Run all tests:

```bash
docker compose exec app php artisan test
```

---

## Git Workflow

This project uses a professional Git workflow:

- `main` → stable production-ready branch
- `dev` → integration branch
- `feature/*` → feature-specific branches

Examples: `feature/docker-setup`, `feature/auth`, `feature/service-request-crud`, `feature/workflow`, `feature/tracking`, `feature/notifications`, `feature/staff-management`

---

## Author

**Rewant Khadka**
Backend development project built as part of Laravel backend training.
