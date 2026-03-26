# ISP Inventory Tracker

Simple Laravel-based inventory and employee issue tracking app for ISP office/admin operations.

## Features

- Admin authentication (Breeze)
- Product and category management
- Vendor and employee management
- Purchase entries (`IN` stock)
- Issue vouchers to employees (`OUT` stock)
- Return entries (`RETURN` stock)
- Stock ledger (`stock_transactions`)
- Reports:
  - Stock balance report
  - Employee issue/pending report

## Stack

- Laravel 13
- Blade + Tailwind
- SQLite/MySQL supported by Laravel config

## Modules and Routes

- Dashboard: `/dashboard`
- Categories: `/categories`
- Products: `/products`
- Vendors: `/vendors`
- Employees: `/employees`
- Purchases: `/purchases`
- Issues: `/issues`
- Returns: `/returns`
- Reports:
  - `/reports/stock`
  - `/reports/employee-issues`

## Setup

1. Install dependencies:

```bash
composer install
npm install
npm run build
```

2. Configure `.env` database.

### Option A: MySQL

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory
DB_USERNAME=root
DB_PASSWORD=
```

### Option B: SQLite

Make sure PHP has `pdo_sqlite` enabled, then:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

Create the SQLite file if needed:

```bash
type nul > database/database.sqlite
```

3. Run migration and seed:

```bash
php artisan migrate --seed
```

4. Start app:

```bash
php artisan serve
```

## Default Admin Login

- Email: `admin@inventory.local`
- Password: `admin12345`

Change this password immediately after first login.

## Stock Logic

Stock is calculated from `stock_transactions`:

- `IN` increases stock
- `OUT` decreases stock
- `RETURN` increases stock
- `ADJUSTMENT` is reserved for future manual corrections

Current balance is computed as:

`IN + RETURN + ADJUSTMENT - OUT`

## Notes

- Issue records are treated as immutable in this MVP.
- Use return entries to track partial/full returns.
- If issue fails with insufficient stock, adjust purchase/return entries first.
