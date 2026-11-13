# Star News — Laravel News Portal

A small news-portal application built with **Laravel 13**, **Blade** and **Bootstrap 5**.
Articles are organised into categories, rendered through server-side Blade views,
and browsable via a list page with a real-time title search and per-article
detail pages.

## Requirements

- **PHP 8.3+** (with the `pdo_sqlite` extension, which is bundled by default)
- **Composer 2.x**
- Node.js / npm *(optional — pages load Bootstrap from a CDN, so no asset build is required)*

## Setup

From the project root:

```bash
# 1. Install PHP dependencies
composer install

# 2. Create your environment file
cp .env.example .env

# 3. Generate the application key
php artisan key:generate

# 4. Create the SQLite database file (it is git-ignored, so it does not exist yet)
touch database/database.sqlite

# 5. Run the migrations (users, sessions/cache/queue, categories, articles)
php artisan migrate

# 6. Seed demo content: 5 categories and 20 articles (12 published, 5 drafts, 3 archived)
php artisan db:seed

# 7. Start the development server
php artisan serve
```

Then open **http://127.0.0.1:8000**.

## What you get

| Page | Route | Description |
|---|---|---|
| Article list | `/` | All articles, newest first, with category/status badges and author. Includes a **real-time search filter by title** (pure client-side JavaScript — no requests while typing) and a live "Showing X of Y" counter. |
| Article detail | `/articles/{permalink}` | Full title, category, author, published date and body. Draft articles show **when they were drafted** (`Drafted <date> · not yet published`). Seeded URLs look like `/articles/aut-fugit-et-autem-5`. |

## Implementation notes

- **Models:** `Category` has many `Article`s; `Article` belongs to a `Category`
  (`app/Models`).
- **No N+1 queries:** the list page eager loads categories via
  `Article::with('category')` — 2 queries total regardless of article count.
- **Article workflow:** a `status` field (`draft` / `published` / `archived`)
  plus `published_at` (nullable) and `drafted_at` (nullable) so drafts keep
  track of when they were written. URLs use the unique `permalink` column,
  seeded as `<title-slug>-<id>` so they stay unique and readable even when
  titles repeat.
- **Seeding:** `ArticleFactory` generates Faker-driven content with `draft()`
  and `archived()` states; `CategoryFactory` picks from real news category
  names. `DatabaseSeeder` creates 5 categories + 20 articles distributed
  across them via `recycle()`.

## Testing

```bash
php artisan test
```

## Handy commands

| Command | Purpose |
|---|---|
| `php artisan serve` | Start the development server |
| `php artisan migrate` | Apply pending migrations |
| `php artisan db:seed` | Seed the demo data |
| `php artisan migrate:fresh --seed` | Drop all tables, re-migrate and reseed |
| `php artisan test` | Run the test suite |

## Project structure

```
app/Http/Controllers/ArticleController.php   # index() list page, show() detail page
app/Models/                                  # Category, Article
database/factories/                          # ArticleFactory, CategoryFactory
database/seeders/DatabaseSeeder.php          # 5 categories + 20 articles
database/migrations/                         # schema (categories, articles, drafted_at)
resources/views/
├── layouts/app.blade.php                    # Bootstrap 5 layout (CDN)
└── articles/                                # index (list + search), show (detail)
routes/web.php                               # / and /articles/{permalink}
```

## Notes

- `.env` and `database/database.sqlite` are git-ignored — every clone sets up
  its own local database.
- Seeding is not idempotent — the demo user's email is unique, so running
  `db:seed` a second time on the same database fails. To reset and reseed
  from scratch, use `php artisan migrate:fresh --seed`.
