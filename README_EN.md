# AL HASM Atelier Luxury v2

Updated with a redesigned animated front page, 3-scene hero slideshow, reveal-on-scroll, refined responsive listing cards, more editorial sections, and a newly styled admin command center with real record-based metrics. See `WHATS_NEW_ATELIER_v2_AR.md` for quick-start.

# AL HASM Premier Properties

## Instant interactive preview (no installation)

Recommended: double-click `START_INSTANT_PREVIEW.bat` to start a tiny Windows PowerShell localhost server. Website and dashboard share the same browser storage origin. No PHP, Composer, Node, or Python needed. Alternatively double-click `OPEN_PREVIEW.bat` or open `preview/index.html` in your browser. Open `OPEN_DASHBOARD.bat` or `preview/dashboard.html` for the command center. Preview interactions (listing CRUD, leads, filters, compare, favorites, settings, sample enquiries) persist in the local browser via localStorage. The previews are static test environments, not a production database.

## Run the real Laravel app on Windows

Install PHP 8.2+ (with pdo_sqlite) and Composer. Double-click `SETUP_AND_RUN_LARAVEL.bat`. It installs framework dependencies if needed, creates a SQLite database, runs migrations, seeds **demo** content on first installation, generates a random local admin password, starts Laravel, and opens `http://127.0.0.1:8000`. Administrator login is `http://127.0.0.1:8000/admin/login`; the generated local credentials are stored privately in `LOCAL_ACCESS_NOT_FOR_UPLOAD.txt`.

For manual install: `composer install`, copy `.env.example` to `.env`, create `database/database.sqlite`, set a secure ADMIN_EMAIL and ADMIN_PASSWORD (min 12 chars), `php artisan key:generate`, `php artisan migrate --seed`, `php artisan storage:link`, `php artisan serve`.

The Laravel source uses Laravel ^12 and SQLite by default, with an optional MySQL configuration. Browser assets require no Node/npm build. All demo property details, prices, client names and editorial content are fictional examples. Images use public Unsplash URLs with bundled SVG fallbacks. Replace contact placeholders, imagery, listings and branding details prior to launch. `vendor/` is intentionally excluded, so Composer and an internet connection are required for the first full Laravel install. Use `public/` as your production Document Root and never upload local credentials or `.env` to public paths.
