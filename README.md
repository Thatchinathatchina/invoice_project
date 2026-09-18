# Invoice Management System

A robust, full-stack Laravel application designed to streamline billing, track expenses, and monitor financial health. Built with modern web development practices in mind, this project focuses on providing a clean, responsive, and highly interactive user experience.

## Features

- **Dashboard Analytics**: Real-time overview of net profit, outstanding receivables, sales revenue, and recent invoice activities.
- **Sales & Purchase Invoices**: Comprehensive invoice management including line-item calculations, tax handling, and status tracking (Draft, Pending, Paid, Overdue).
- **Payment Tracking**: Centralized payment reporting seamlessly linked to respective sales and purchase invoices.
- **Expense & Budget Management**: Track organizational expenses against predefined budgets.
- **Modern UI/UX**: Fully modal-driven CRUD operations using Alpine.js, ensuring users rarely have to leave the page they are on.
- **Thermal Receipts**: Custom view layouts specifically designed for thermal receipt printers.

## Tech Stack

- **Backend**: Laravel 13, PHP 8.3+
- **Frontend**: Blade Templating, Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Architecture**: MVC pattern with strict adherence to Laravel conventions (Form Requests, Enums, Eloquent Relationships, Morphable polymorphic relationships for payments).

## Prerequisites

Make sure you have the following installed on your local machine:
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL

## Installation & Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd invoice_project
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install and compile frontend assets**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup**
   Copy the example environment file and configure your database credentials:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run Migrations & Seeders**
   Set up the database tables and populate them with initial data:
   ```bash
   php artisan migrate --seed
   ```

6. **Serve the Application**
   ```bash
   php artisan serve
   ```
   You can now access the application at `http://localhost:8000`.

## Architecture Highlights

- **Polymorphic Payments**: The `Payment` model utilizes polymorphic relationships (`payable_id`, `payable_type`) to elegantly link a single payment table to both `SalesInvoice` and `PurchaseInvoice` models.
- **Eager Loading**: Heavy emphasis on query optimization. Controllers actively use eager loading (e.g., `whereHasMorph`, `with`) to eliminate N+1 query problems, especially on complex reporting pages.
- **Reusable Components**: The UI is built using highly reusable Blade components (`x-table`, `x-actions`, `x-modal`, `x-filters`), keeping the views DRY and maintainable.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
