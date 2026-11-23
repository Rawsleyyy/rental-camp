# Project Specification: Camping & Glamping Rental System
**Role:** Fullstack Web Developer
**Tech Stack:** Laravel 11, PHP 8.2+, MySQL, Laravel Breeze (Blade + Alpine.js + Tailwind CSS), Chart.js.

## 1. Project Context & Constraints
This is a university final project (UAS). The focus is on clean business logic, stability, and good UI/UX. Do NOT over-engineer (no React/Vue/SPA). Use standard Laravel MVC.
- Payments are dummy/sandbox. Refunds are logical only (status changes and notes).
- Must be responsive (mobile + desktop).
- Standard security: Password hashing (bcrypt), input validation, route protection via middleware.

## 2. Database Schema (Migration Guide)

Please generate Laravel Migrations and Models with the following relationships:

1.  **users**: `id`, `name`, `email`, `password`, `role` (enum: admin, staff, user), `phone`, `address`, timestamps.
2.  **categories**: `id`, `name`, `type` (enum: equipment, glamping), timestamps.
3.  **items**: `id`, `category_id` (FK), `name`, `description`, `total_stock`, `price_per_day`, `image_path`, timestamps.
4.  **articles**: `id`, `title`, `slug`, `content`, `image_path`, `author_id` (FK to users), timestamps.
5.  **bookings**: `id`, `user_id` (FK), `booking_code` (unique string), `start_date`, `end_date`, `total_price`, `status` (enum: pending, confirmed, active, completed, cancelled), `is_insured` (boolean, default false), timestamps.
6.  **booking_items**: `id`, `booking_id` (FK), `item_id` (FK), `quantity`, `price_at_booking`, timestamps.
7.  **cancellations**: `id`, `booking_id` (FK), `reason`, `status` (enum: pending, approved, rejected), `refund_amount`, `admin_notes`, timestamps.
8.  **damage_reports**: `id`, `booking_id` (FK), `item_id` (FK), `description`, `penalty_fee`, `is_paid` (boolean), timestamps.
9.  **activity_logs**: `id`, `user_id` (FK - staff/admin), `action`, `description`, timestamps.

## 3. Seeders Requirement (Mandatory)
Generate seeders for the database. IT IS CRITICAL to include these exact demo accounts:
- **Admin**: email: `admin@demo.test`, password: `Admin#2026`, role: `admin`
- **Staff**: email: `staff@demo.test`, password: `Staff#2026`, role: `staff`
- **User**: email: `user@demo.test`, password: `User#2026`, role: `user`

## 4. Role-Based Access Control (Middleware)
- `Admin`: Full access. Manages users, views all staff activity logs, plus all Staff capabilities.
- `Staff`: Dashboard, booking management, cancellation processing, CRUD items, CRUD articles.
- `User`: Public pages, booking process, user dashboard (history, cancellation request, profile edit).

## 5. Specific Business Logic Instructions for AI

When generating controllers, adhere to these rules:

**A. Booking & Stock Validation:**
- Users select an item/package, `start_date`, `end_date`, and `quantity`.
- **Validation Formula:** Available Stock = `total_stock` - (Sum of `quantity` in `booking_items` where `booking status` is 'confirmed' or 'active' AND dates overlap with requested dates).
- Cannot book if available stock is insufficient.

**B. Booking Tracking (Guest/User):**
- Create a route where users can input `booking_code` + `email`.
- If found & confirmed, display a UI card (which acts as an equipment pickup card or room handover checklist) with a dummy QR code (use `simplesoftwareio/simple-qrcode`).

**C. Cancellation & Refund Logic (Staff/Admin):**
- User submits cancellation form -> Cancellation status `pending`.
- Staff approves -> Booking status becomes `cancelled`. Stock is implicitly released (because date overlap check ignores 'cancelled' bookings).
- **Glamping Insurance Logic:** If `is_insured` is true AND cancellation date is > 3 days before `start_date`, refund is 100%. If <= 3 days, refund is 50%. Record this in `cancellations.refund_amount`.

**D. Damage Penalty Logic (Staff/Admin):**
- Upon equipment return, Staff can create a `damage_report`.
- Links to `booking_id` and `item_id`. Admin sets a `penalty_fee` which the user is required to pay (dummy payment status `is_paid`).

**E. Staff Activity Logging:**
- Every time a Staff member approves/rejects a cancellation, or adds/edits an item, create a record in `activity_logs`.

## 6. UI/UX & Views Requirements
Use Tailwind CSS utility classes. 
- **Public:** Hero section, Article list (min 3 dummy articles), Item catalog, Booking form.
- **User Dashboard:** Profile edit, Booking history table, Status badges (Tailwind colors).
- **Staff/Admin Dashboard:** Sidebar layout.
- **Analytics (Admin/Staff):** Use Chart.js in the blade view to show:
  1. Bar chart: Rentals per period (e.g., this month).
  2. Pie chart: Status of bookings (Completed vs Cancelled).
  3. Line chart: Item stock usage rate.

## Suggested Vibecoding Steps for the Dev (Tell AI to execute these sequentially):
1. "Set up the models, migrations, and relationships based on the schema above."
2. "Create the Database Seeder including the 3 mandatory accounts and dummy items/articles."
3. "Create the Auth scaffolding using Breeze and implement the Role Middleware."
4. "Create the Item and Article CRUD interfaces for Staff/Admin."
5. "Implement the public frontend: Homepage, Item Catalog, Article reading, and the Booking form with date-overlap stock validation."
6. "Implement the User Dashboard: View history, Request Cancellation, and the Search Booking Code feature (with simple-qrcode)."
7. "Implement the Staff/Admin processing logic: Approve cancellations (with the insurance refund logic) and create damage reports."
8. "Create the Admin Dashboard charts using Chart.js pulling data from Eloquent."