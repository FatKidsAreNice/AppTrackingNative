# AppTrackingNative

Laravel 12 + NativePHP Mobile application for the Sauels coldstore dashboard, jobs view, BEV track overview and mobile barcode/scanner workflows.

## Overview

The project currently provides three main screens:

- `/` coldstore dashboard with BEV track overview, track list, track details and jobs panel
- `/scanner` mobile-friendly barcode and camera test workflow
- `/settings` integration and runtime configuration overview

The dashboard combines two separate data flows:

- overview / BEV / track data
- jobs data for prepared orders per production line

Those flows are intentionally separated. Jobs can run locally or fetch from a remote backend API on mobile, while overview and barcode handling keep their existing behavior.

## Current Jobs Architecture

Jobs are built around:

- `Line -> Arbeitsplatz_Nr`
- next open production order
- normalized `required_pe_text1`
- matching against current coldstore inventory

Important boundaries in the current codebase:

- `JobMatchingService` matches only against `ColdstoreInventoryRepository`
- `EtikInterfaceLookupRepository` exists only as a future seam and is not used for job matching
- `ColdstoreInventoryRepository` remains mock-backed in the current implementation
- `ProductionOrderRepository` can run in `mock` or prepared `sqlsrv` mode

## Main Routes

- `GET /` coldstore dashboard
- `GET /scanner` barcode scanner page
- `GET /settings` settings page
- `GET /api/coldstore/overview` overview API
- `GET /api/coldstore/jobs` jobs API
- `POST /api/coldstore/barcodes` barcode forwarding API

## Requirements

- PHP 8.4
- Composer
- Node.js + npm
- Laravel Herd for the desktop `.test` host
- NativePHP Mobile toolchain for iOS builds

Optional for live production-order reads:

- SQL Server access to `TP0030TS1`
- correct `COLDSTORE_SQLSERVER_*` env values

## Local Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

If `.env.example` is not present in your local checkout, create `.env` from your existing local standard and do not commit real credentials.

## Desktop Development

Default desktop access is through Herd:

```text
http://apptrackingnative.test
```

Useful commands:

```bash
composer run dev
npm run dev
npm run build
php artisan test
vendor/bin/pint --dirty
```

Notes:

- `composer run dev` starts Laravel, queue listener and Vite together
- if frontend changes are not visible, run `npm run dev` or `npm run build`

## NativePHP iOS Development

Build commands are run manually from your terminal:

```bash
npm run build -- --mode=ios
php artisan native:run ios
```

For hot-reload style work:

```bash
php artisan native:watch
```

The mobile app must be rebuilt when you change frontend assets or mobile-relevant `.env` values.

## Coldstore Configuration

Relevant application config lives mainly in:

- `config/coldstore.php`
- `config/database.php`

### Overview / Barcode Remote Integration

These env values control the existing remote overview and barcode integration:

```dotenv
COLDSTORE_REMOTE_BASE_URL=http://10.10.121.30:8000
COLDSTORE_REMOTE_OVERVIEW_PATH=/overview
COLDSTORE_REMOTE_BARCODE_PATH=/barcode-scan
COLDSTORE_REMOTE_TIMEOUT_SECONDS=4
```

### Jobs Data Source

Jobs can run in two modes:

```dotenv
COLDSTORE_JOBS_DATA_SOURCE=local
```

or

```dotenv
COLDSTORE_JOBS_DATA_SOURCE=remote_api
COLDSTORE_REMOTE_API_BASE_URL=http://10.10.123.66:8000
```

Behavior:

- `local`: dashboard computes jobs through the local Laravel app
- `remote_api`: mobile fetches jobs JSON from another backend over LAN

The jobs endpoint path is configurable and defaults to:

```dotenv
COLDSTORE_JOBS_PATH=/api/coldstore/jobs
```

### Production Orders Source

Prepared sources:

```dotenv
COLDSTORE_PRODUCTION_ORDER_SOURCE=mock
```

or later:

```dotenv
COLDSTORE_PRODUCTION_ORDER_SOURCE=sqlsrv
```

Current intent:

- `mock` remains the default
- `sqlsrv` enables the prepared SQL Server `ProductionOrderRepository`
- this does not change inventory matching rules

### SQL Server Connection

The dedicated Laravel connection is `coldstore_sqlsrv`.

Supported env keys:

```dotenv
COLDSTORE_SQLSERVER_HOST=
COLDSTORE_SQLSERVER_PORT=
COLDSTORE_SQLSERVER_DATABASE=TP0030TS1
COLDSTORE_SQLSERVER_USERNAME=
COLDSTORE_SQLSERVER_PASSWORD=
COLDSTORE_SQLSERVER_ENCRYPT=true
COLDSTORE_SQLSERVER_TRUST_SERVER_CERTIFICATE=true
```

## Mobile Jobs via LAN Backend

For iPhone testing with remote jobs data:

1. Keep the mobile env on:

```dotenv
COLDSTORE_JOBS_DATA_SOURCE=remote_api
COLDSTORE_REMOTE_API_BASE_URL=http://10.10.123.66:8000
```

2. Start a LAN-reachable PHP server on the PC:

```bash
php -S 0.0.0.0:8000 -t public
```

3. Verify the endpoint from the phone:

```text
http://10.10.123.66:8000/api/coldstore/jobs?line=6
```

4. Rebuild and run the iOS app:

```bash
npm run build -- --mode=ios
php artisan native:run ios
```

Important:

- `apptrackingnative.test` is for desktop/Herd, not for direct phone access
- if the phone still shows old mock data, the most common cause is an outdated mobile build
- the LAN PHP server must keep running while the phone fetches jobs

## Testing

Run the focused Coldstore suite:

```bash
php artisan test --compact tests/Feature/ColdstoreDashboardTest.php
php artisan test --compact tests/Feature/ColdstoreJobsApiTest.php
php artisan test --compact tests/Feature/ColdstoreOverviewApiTest.php
```

Run the broader default suite:

```bash
php artisan test --compact
```

Formatting:

```bash
vendor/bin/pint --dirty
```

## Composer Scripts

Available project scripts from `composer.json`:

```bash
composer run setup
composer run dev
composer run test
```

What they do:

- `setup` installs PHP and Node dependencies, prepares `.env`, generates app key, migrates and builds assets
- `dev` starts Laravel, queue listener and Vite concurrently
- `test` clears config cache and runs the test suite

## Frontend Scripts

Available npm scripts from `package.json`:

```bash
npm run dev
npm run build
```

For NativePHP platform builds:

```bash
npm run build -- --mode=ios
npm run build -- --mode=android
```

