# AI_USAGE.md — AI Tool Usage Documentation

## Overview

This document tracks all AI tool usage throughout the TravelNepal project, covering spec writing, UI prototyping, and code generation. This is a required deliverable as per the project brief.

> **Note:** This document is updated progressively as the project develops. It reflects honest usage — what was accepted, what was changed, and why.

---

## Phase 1 — Spec Writing

**Tool Used:** Claude (claude.ai)

### What Was Done

All four spec files were drafted collaboratively with Claude. The process was iterative — Claude generated an initial structure, which was then reviewed, questioned, and refined through multiple back-and-forth exchanges before being finalised.

---

### Prompts Used

**Prompt 1 — Initial trips spec:**

> "I am building a full-stack travel booking website for a Nepali adventure company using Laravel, FilamentPHP, and Blade/Livewire. Help me write a detailed spec file for the trips feature. It should cover: purpose, user stories, data model with column types, relationships, and acceptance criteria. Include realistic Nepal trip examples in the seed data section."

**What I accepted:** Overall structure, data model columns, acceptance criteria checklist format.

**What I changed:**

- Reviewed and expanded visitor user stories — added estimated cost, major attractions with images, testimonials, direct booking, and confirmation flow
- Added motorbike tours as a category after studying the reference sites (motorbikenepal.com, vintagerides.com) — both are motorbike companies, not trekking-only
- Added `requires_bike` flag and full motorcycle license verification flow — visitors need to provide license details before booking a motorbike trip
- Added bike rental as a separate cost (`bike_rentals` table) with `price_per_day_usd × duration_days` calculation
- Added `route_distance_km` (nullable) for motorbike/cycling trips alongside `max_altitude_m` (nullable) for trekking/hikes — both hidden on frontend if null
- Added `trip_attractions` table with images and `sort_order`
- Added `trip_faqs` and global `faqs` tables
- Added `enquiries` table for contact form (separate from bookings)
- Decided USD only pricing — NPR noted as future iteration
- Decided itinerary stored as separate rows (`itinerary_days` table) not JSON — proper normalisation
- Dropped visitor gallery upload — too complex, only admins manage gallery

---

**Prompt 2 — Packages spec:**

> "Write a spec for the packages feature. Each trip should have up to 3 tiers: standard, premium, luxury. Include inclusions and exclusions per tier for a Nepal adventure company covering motorbike tours, trekking, cycling, cultural tours and hikes."

**What I accepted:** Tier structure, inclusion/exclusion pattern, comparison table layout.

**What I changed:**

- Removed `price_nrs` — decided USD only after discussion
- Removed unrealistic inclusions: oxygen cylinder, satellite communication, GPS per rider, 4-5 star hotels throughout
- Replaced with realistic Nepal alternatives: walkie talkies, altitude sickness medication, shared GPS for group leader, "best available hotel" with honest note that 5-star is Kathmandu only
- Fixed Luxury motorbike package — originally said "bike included free" which contradicted our `bike_rentals` table. Changed to "priority access to best rental bike, cost still applies"
- Added `sort_order` to `package_inclusions` table

---

**Prompt 3 — Admin panel spec:**

> "Write a FilamentPHP admin panel spec. List all Filament resources, form fields, relation managers, roles, and dashboard widgets for a Nepal adventure travel company with motorbike and trekking trips."

**What I accepted:** Resource structure, Filament component names, dashboard widget ideas.

**What I changed:**

- Added `BookingResource` — was missing entirely, admin needs to view and verify license images before confirming motorbike bookings
- Fixed `TestimonialResource` — had `is_active` but should be `is_approved`
- Removed `by is_active` filter from TripResource — admin sees all trips regardless of active status
- Added `BikeRentalsRelationManager` to TripResource — only visible when `requires_bike = true`
- Added `TripFaqsRelationManager` and `FaqResource` for global FAQs
- Added `TripAttractionsRelationManager`
- Noted Filament v5 uses `Filament\Schemas\Components\Section` not `Filament\Forms\Components\Section`

---

**Prompt 4 — Pages spec:**

> "Write a pages spec for all public pages. Include a global booking popup that pre-fills trip id and slug automatically. The popup should show motorbike-specific fields only when requires_bike is true."

**What I accepted:** Page section breakdowns, Eloquent query examples, responsive requirements.

**What I changed:**

- Added global booking popup as a reusable Livewire component — pre-fills trip and package automatically
- Added motorbike-specific booking fields: license check (hard block if no license), own bike or rental dropdown
- Added sticky "Book Now" button fixed to bottom-right on trip detail page
- Added FAQ page (`/faq`) as Page 5, Contact moved to Page 6
- Added FAQ accordion section to trip detail page
- Added major attractions section to trip detail page
- Fixed facts sidebar — shows altitude OR distance depending on which is set, hidden if null
- Added `enquiries` table reference to contact form

---

## Phase 2 — UI Prototyping

**Tool Used:** Google AI Studio (aistudio.google.com)

### Pages Prototyped

- Homepage — hero, category highlights, featured trips, testimonials
- Trip Listing — filter bar, card grid with price preview
- Trip Detail — itinerary accordion, facts sidebar, package comparison, booking popup

### What Was Generated

Detailed prompts with full design system specifications — deep forest green (#1a3a2a), warm stone beige (#f5f0e8), ochre (#c8860a), Playfair Display headings, Inter body font. Prompts and full details documented in `/Design/Method/method.md`.

### What I accepted

Overall layout structure, colour palette application, card component patterns, accordion behaviour.

### What I changed

- Hero font pairing refined — AI choice was too generic
- Card hover states increased in contrast
- Mobile hamburger nav behaviour adjusted
- Package comparison table made horizontally scrollable on mobile
- Booking popup designed for trekking trip — motorbike-specific fields (license check, rental dropdown) will be implemented in Livewire during Phase 3 since they require conditional logic

> Screenshots saved in `/Design/Prototypes/`. Partial screenshots showing hero and upper sections — full pages generated in Google AI Studio.

---

## Phase 3 — Development (In Progress)

**Tools Used:** Claude (claude.ai), Cursor Agent

### What Has Been Built So Far

**Database layer:**

- 14 migrations created and run successfully: categories, trips, itinerary_days, trip_attractions, trip_images, trip_faqs, bike_rentals, faqs, packages, package_inclusions, testimonials, enquiries, bookings, settings
- 14 Eloquent models with fillable fields, casts, scopes, and relationships

**Filament Admin Panel:**

- Filament v5 installed and configured
- CategoryResource — with auto-slug generation from name, trip count column
- TripResource — with form sections, auto-slug, reactive `bike_rental_available` toggle (only shows when `requires_bike` is on), colour-coded difficulty badges, filters
- All 5 relation managers generated: ItineraryDaysRelationManager, TripAttractionsRelationManager, TripImagesRelationManager, TripFaqsRelationManager, BikeRentalsRelationManager

### Prompts Used

**Prompt 5 — Trip model:**

> "Write a Laravel Eloquent model for Trip with fillable fields, casts for JSON highlights, active and featured local scopes, all relationships, and a getStartingPriceAttribute accessor."

**What I accepted:** Model structure, accessor logic, scope patterns.
**What I changed:** Added `Builder` type hint to scopes to fix Intelephense warnings. Added `orderBy` to relevant relationships matching sort_order and day_number from spec.

---

**Prompt 6 — Filament relation managers:**

> "I am using Filament v5 with Laravel. Create relation managers for TripResource using Filament\Schemas\Components\Section. Include ItineraryDaysRelationManager, TripAttractionsRelationManager, TripImagesRelationManager, TripFaqsRelationManager, BikeRentalsRelationManager."

**What I accepted:** Generated file structure, form fields, table columns.
**What I changed:** Verified `$relationship` names match exactly with Trip model method names (camelCase). Fixed deprecated `imageResizeMode()` → `automaticallyResizeImagesMode()` for Filament v5.

---

### Where AI Had To Be Corrected (Phase 3)

- **Filament v5 namespaces:** AI initially suggested `Filament\Forms\Components\Section` — correct namespace in v5 is `Filament\Schemas\Components\Section`
- **Deprecated methods:** `imageResizeMode()` and `imageCropAspectRatio()` are deprecated in Filament v5 — replaced with `automaticallyResizeImagesMode()`
- **navigationGroup type:** AI suggested `?string` type — v5 requires `string|\UnitEnum|null` or no type hint
- **column-size:** AI suggested `column(2)` which make it look like form take less than half space . Change it to `columnSpanFull()` so it looks bigger.

---

**Prompt 7 — PackageResource:**

> "I am using Filament v5 with Laravel. Create a PackageResource inside app/Filament/Resources/Packages/ directory with PackageInclusionsRelationManager. Include form fields for trip_id, tier, title, price_usd, is_popular, is_active, description. Table with tier badges (standard=gray, premium=warning, luxury=success) and inclusion/exclusion badges (inclusion=success, exclusion=danger)."

**What I accepted:** Overall structure, badge colours, relation manager pattern, filter setup.

**What I changed:**

- Added `->columnSpanFull()` to Section in PackageInclusionsRelationManager — form was rendering half width
- Added `->defaultSort('created_at', 'desc')` to table — newest packages appear first
- Fixed navigationIcon to use `Heroicon::OutlinedCube` enum instead of string `'heroicon-o-cube'` — consistent with Filament v5 pattern used in other resources

---

## Reflection (Updated as project progresses)

### Where AI helped most

- **Spec writing:** Dramatically accelerated creation of detailed specs. Iterative refinement through conversation produced better results than a single prompt.
- **Data model design:** AI caught relationships and edge cases (nullable columns, cascade deletes, enum types) that would have taken longer to think through alone.
- **Boilerplate generation:** Migrations, models, and Filament resources were largely accurate on first attempt — saving hours of repetitive typing.
- **Domain knowledge:** AI knew Nepal geography, realistic trek names, altitude figures, and adventure travel terminology without needing to be taught.

### Where I had to override or correct AI

- **Filament version differences:** AI wasn't always aware of v5 namespace changes — required cross-referencing docs
- **Unrealistic inclusions:** AI suggested oxygen cylinders and satellite devices for standard packages — corrected to realistic Nepal standards (walkie talkies, basic medical kit)
- **Nepal hotel reality:** AI suggested 4-5 star hotels throughout — corrected to reflect actual Nepal accommodation availability outside Kathmandu
- **Business logic:** AI didn't anticipate the motorbike license verification requirement or the rental cost calculation at booking time — these came from thinking through the actual user flow
- **Currency:** AI defaulted to USD only which was actually the right call for this project — NPR conversion deferred to future iteration

---

_This document is updated progressively and reflects honest usage throughout all phases of development._
