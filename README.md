# AK Tech SOL — Digital Services Agency Website

Official website of **AK Tech SOL**, a full-service digital agency delivering web development, mobile apps, UI/UX design and cloud solutions to clients **worldwide**.

🌐 **Live:** [aktechsol.pk](https://aktechsol.pk)

---

## About AK Tech SOL

AK Tech SOL is a digital-services agency helping startups and businesses across the globe design, develop, and launch exceptional digital products. From idea to launch — and beyond — the team works across all timezones with transparent pricing and on-time delivery.

### Services
- Custom Web Development
- Mobile App Development
- UI/UX Design
- Cloud Solutions
- Digital Transformation

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13 (PHP 8.4) |
| Frontend | Blade + Tailwind CSS 3.4 |
| Database | MySQL |
| Media | Spatie Media Library |
| Auth | Laravel Fortify (2FA supported) |
| Charts | ApexCharts |
| Editor | Quill (self-hosted) |

---

## Features

### Public Website
- Modern responsive homepage (hero, services, portfolio, testimonials, blog)
- Services listing with live AJAX search, category filters & load more
- Portfolio with gallery lightbox, project details & related projects
- Blog with search, categories, share buttons & related articles
- About, Contact (AJAX form + Google Maps embed), FAQs pages
- Newsletter subscription (AJAX)
- Full SEO: meta tags, Open Graph, JSON-LD schemas, sitemap

### Admin Panel (`/admin`)
- Dashboard with stats, charts & "Needs Attention" widget
- Services & Categories, Projects, Blog & Categories, FAQs, Testimonials
- Contact message inbox, Newsletter subscribers, Media Library
- Users & Roles (Spatie permissions, user-wise assignment)
- Notifications center with topbar bell
- Activity log (auto-logged CRUD)
- 8-tab Settings: General, Homepage Stats, Email/SMTP, SEO, Legal pages & more
- Quill rich-text editing, AJAX drawers everywhere, dark mode

---

## Getting Started

```bash
# Clone (dev branch for development)
git clone -b dev https://github.com/mhamzase/aktechsol.git
cd aktechsol

# Install dependencies
composer install

# Environment
cp .env.example .env
php artisan key:generate

# Database (fresh)
php artisan migrate:fresh --seed

# Frontend assets (Tailwind)
npx tailwindcss -c ./tailwind.config.js \
  -i ./resources/css/app.css \
  -o ./public/assets/css/app.css --minify

# Serve
php artisan serve
```

### Default Admin Login
Seeded via `PlatformSeeder` — check `database/seeders/PlatformSeeder.php` for credentials.

---

## Git Workflow

| Branch | Purpose |
|--------|---------|
| `dev` | All development work happens here |
| `master` | Production — merged from `dev` after review |

> ⚠️ Never commit directly to `master`. All changes go to `dev` first.

### Rebuilding Tailwind
After **every** Blade change that adds new utility classes:

```bash
npx tailwindcss -c ./tailwind.config.js \
  -i ./resources/css/app.css \
  -o ./public/assets/css/app.css --minify
```

---

## Deployment (cPanel)

- Deploy key based Git pull on the server (`~/public_html/aktechsol.pk`)
- `vendor/` uploaded manually — re-upload after every `composer install/update`
- PHP 8.4 via cPanel MultiPHP Manager

---

## License

Proprietary — © AK Tech SOL. All rights reserved.
