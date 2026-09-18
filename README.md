# Invoice & Financial Management App

Hey there! Welcome to the source code for my Invoice Management project. I built this application to handle everyday business financial tasks—like tracking sales, logging purchases, keeping an eye on expenses, and making sure payments actually line up.

I wanted the user experience to be extremely fast and fluid, so almost all of the heavy lifting (like adding, editing, and deleting records) happens in clean pop-up modals without ever needing to reload the page. 

## What This App Does
- **Dashboard**: Gives a quick overview of net profit, outstanding payments, and recent invoice activity.
- **Invoices**: Handles both Sales and Purchase invoices. It calculates line-item totals and taxes automatically.
- **Payments**: Keeps track of who paid what and when. The payments are strictly tied to specific invoices so nothing gets lost in the system.
- **Expenses & Budgets**: Lets you set budgets and track business expenses against them.
- **Thermal Printing**: Includes a custom view format specifically designed for printing receipts directly to a thermal printer.

## Tech Stack
I built this using modern tools that I genuinely enjoy working with:
- **Laravel 13** and **PHP 8.3+** for the backend logic.
- **Tailwind CSS** and **Alpine.js** for the frontend UI. I specifically avoided heavy frontend frameworks (like React/Vue) to keep the app lightweight. It relies on clean, server-rendered Blade templates with Alpine handling the interactivity.
- **MySQL** for the database.

## Standout Architecture Details
If you're looking at the code, here are a few structural decisions I made to keep the project scalable:
- **Polymorphic Payments**: Instead of creating separate payment tables for Sales and Purchases, I used Laravel's polymorphic relationships. A single `Payment` model dynamically links to whatever type of invoice it needs to.
- **Service Classes**: I extracted heavy third-party logic (like the Frankfurter Exchange Rate API) out of the controllers and into dedicated Service classes (like `ExchangeRateService`) to keep the controllers thin and focused.
- **Database Optimization**: I was very careful about database performance. If you look at the controllers, I heavily utilize Eloquent's eager loading (`with()`, `whereHasMorph()`) to make sure there are no N+1 query issues on the reporting pages.

## How to run it locally

1. Clone the repo and `cd` into the folder.
2. Install the backend and frontend dependencies:
   ```bash
   composer install
   npm install
   ```
3. Copy `.env.example` to `.env` and set up your local MySQL database credentials.
4. Generate your app key: 
   ```bash
   php artisan key:generate
   ```
5. Run the migrations and seeders to populate the database with some initial data: 
   ```bash
   php artisan migrate --seed
   ```
6. Compile the CSS/JS assets: 
   ```bash
   npm run build
   ```
7. Spin up the local development server: 
   ```bash
   php artisan serve
   ```

That's it! You should be able to access the app at `http://localhost:8000`. Let me know if you run into any issues.
