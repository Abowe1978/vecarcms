# Plans and Payment Plans Data

This directory contains exported data from the database for seeding Plans and Payment Plans.

## Files

- `plans_data.php` - Exported data containing all Plans and Payment Plans from the current database

## Usage

To seed the database with this data:

```bash
php artisan db:seed --class=PlansAndPaymentPlansSeeder
```

## Regenerating Data

If you need to regenerate `plans_data.php` from the current database state, simply run:

```bash
php artisan plans:export-data
```

You can also specify a custom output path:

```bash
php artisan plans:export-data --output=path/to/custom/file.php
```

## Seeder Features

The `PlansAndPaymentPlansSeeder`:
- Uses `updateOrCreate()` to avoid duplicates (matches by `name`)
- Maintains relationships between Plans and Payment Plans
- Runs in a database transaction for data integrity
- Provides detailed output during seeding
- Can be run multiple times safely (idempotent)

## Data Structure

### Payment Plans
- 5 payment plans total
- Fields: name, button_label, payment_processor, period, scope, description, etc.

### Plans
- 23 membership plans total
- Categories: UK, EU, ROW (Rest of World)
- Types: Single, Joint, Junior, Works, e-Membership, Life
- Each plan can be associated with one or more payment plans

## Notes

- The seeder uses `updateOrCreate()` instead of `create()`, so running it multiple times won't create duplicates
- Payment Plans are seeded first, then Plans (to maintain referential integrity)
- The `plan_payment_plan` pivot table is populated automatically via `sync()`

