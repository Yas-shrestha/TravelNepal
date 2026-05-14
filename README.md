# TravelNepal

A full-stack travel booking website for a Nepali adventure travel company. TravelNepal helps travellers discover, plan, and book trips across Nepal — from high-altitude Himalayan treks to cultural heritage tours — with full itinerary details, package comparisons, and direct booking from every trip page.

Built as part of a spec-driven development workflow with deliberate AI tool integration throughout — from writing specifications to UI prototyping and code generation.

---

## Tech Stack

- **Backend** — Laravel 12, PHP 8.2
- **Admin Panel** — FilamentPHP 5
- **Frontend** — Blade, Livewire, Flux
- **Database** — MySQL

---

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- MySQL
- Node.js & npm

### Setup

```bash
git clone https://github.com/Yas-shrestha/TravelNepal.git
cd TravelNepal

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate
```

Set up your database in `.env`:

```env
DB_DATABASE=travelnepal
DB_USERNAME=root
DB_PASSWORD=
```

Then run migrations and seed:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

---

note:please add mail crediential for mailer to work


## Admin Panel

Access the admin panel at `/admin`

| Field    | Value             |
|----------|-------------------|
| Email    | test@example.com  |
| Password | password          |

The seeded super admin account has full access to manage trips, packages, bookings, enquiries, testimonials, and users.

---

## What's Inside

**Frontend**
- Home page with hero, featured trips, category highlights, and testimonials
- Trip listing page with filters by category, difficulty, and duration
- Trip detail page with day-by-day itinerary accordion, facts, package comparison table, and booking CTA
- About and Contact pages

**Admin Panel**
- Full trip management — itinerary days, attractions, gallery images, FAQs, packages, bike rentals — all manageable without touching code
- Booking management with motorbike license verification for foreign riders
- Enquiry inbox with read/replied status tracking
- Testimonial approval workflow
- Role-based access — Super Admin and Content Manager roles
- Dashboard with live stats, pending bookings, and latest enquiries

---

## Project Structure

```
Specs/          — Spec-driven development files (trips, packages, admin, pages)
Designs/        — AI-generated UI prototypes and design system references
AI_USAGE.md     — Full documentation of AI tools and prompts used throughout
```

---

## Known Limitations

Role-based access control is partially implemented — the database includes a role column and basic permission checks are in place. A dedicated user management interface for assigning roles exists in the admin panel but has limited enforcement across all resources within the task timeline.

---

## Development Approach

This project followed a spec-first workflow — all four spec files were written and reviewed before any code was written. AI tools were used deliberately at each phase: Claude for spec writing and code review, Google AI Studio for UI prototyping, and Cursor for development assistance. See `AI_USAGE.md` for full details including verbatim prompts and reflections on what was accepted, changed, or overridden.