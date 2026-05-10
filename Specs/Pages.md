# pages.md — Frontend Pages Specification

## Purpose

Define content requirements, layout structure, and functional behaviour for every public-facing page of the TravelNepal website. All pages must be fully responsive (mobile and desktop) and follow the defined visual design system.

---

## Global Requirements

### Navigation Bar (all pages)

- Order: Logo → Home → About Us → Categories (dropdown) → All Trips → FAQ → Contact → "Plan My Trip" (CTA button)
- Categories dropdown dynamically loads all active categories from the database
- Active nav link is visually highlighted on every page
- On mobile: collapses into a hamburger menu with a slide-in drawer
- "Plan My Trip" CTA button opens the global booking popup (see below)

### Footer (all pages)

- Logo + short tagline
- Quick links: Home, All Trips, About Us, FAQ, Contact
- Social icons: Facebook, Instagram, TikTok, YouTube
- Contact info: address, phone, email
- Copyright line

### Global Booking Popup

A reusable Livewire modal component available on every page and every trip card.

- Triggered by: "Book Now" button on trip cards, "Plan My Trip" in navbar, sticky button on trip detail page
- Trip is pre-identified via hidden `trip_id` and `trip_slug` — visitor never fills these manually
- If opened from a trip card or trip detail: trip name shown at top — e.g. **"Booking: Manaslu Circuit Trek"**
- If opened from navbar "Plan My Trip": shows a trip selector dropdown instead

**Standard fields (all trips):**

- Name (required)
- Email (required)
- Phone
- Preferred Date (required)
- Package (Standard / Premium / Luxury)
- Group Size
- Message

**Motorbike-only fields (shown only when trip `requires_bike = true`):**

- Do you have your own bike? (Yes / No radio)
- If Yes → Bike Model (text input)
- If No + `bike_rental_available = true` → Rental Bike (dropdown of available models with engine size and daily price)
- If No + `bike_rental_available = false` → Info message: "Please contact us to discuss bike arrangements"

- On submit: stored to `bookings` table with status `pending`, confirmation email sent to visitor
- Success message inside popup: "Your booking request has been sent! We'll contact you within 24 hours."
- Livewire — no page reload, popup stays open showing success state

### Design System

- Earthy adventure theme: deep forest green, warm stone, ochre accents
- No purple gradients, no generic default Tailwind card patterns
- Consistent typefaces and spacing scale across all pages

---

## Page 1 — Home (`/`)

### Purpose

First impression of the brand. Must communicate adventure, trust, and Nepal's unique identity immediately.

### Sections

#### 1.1 Hero

- Full-width background image (motorbike on mountain pass, trekker on ridge, or dramatic Nepal landscape)
- Headline: bold, short, emotional — e.g. "Ride It. Trek It. Live It. Nepal."
- Subheading: one-line company value proposition — e.g. "Motorbike tours, treks, cycles and cultural adventures across Nepal"
- Two CTAs: "Explore Trips" (primary → /trips) and "Plan My Trip" (secondary → opens booking popup)
- Subtle scroll-down indicator

#### 1.2 Trip Categories

- Section heading: "Explore by Category"
- Horizontal scrollable row or grid of category cards
- Each card: icon/image, category name, trip count
- Clicking navigates to `/trips?category={slug}`

#### 1.3 Featured Trips

- Section heading: "Popular Trips"
- Grid of up to 6 trips where `is_featured = true`
- Each card: cover image, category badge, title, duration, difficulty badge, starting price
- Two actions per card: "View Trip" (→ trip detail) and "Book Now" (opens popup with trip pre-filled)
- "View All Trips" link below the grid

#### 1.4 Why Choose Us

- 3–4 icon + heading + short text blocks
- e.g. "Licensed Adventure Guides", "Small Groups", "Motorbikes & Gear Provided", "24/7 On-Road Support"

#### 1.5 Testimonials

- Section heading: "What Our Travellers Say"
- 3 approved testimonials (`is_approved = true`)
- Each: avatar or initials fallback, name, location, star rating, quote excerpt, trip name (linked)
- Carousel on mobile, 3-column grid on desktop

#### 1.6 Call to Action Banner

- Full-width banner with mountain background
- Headline: "Ready to Start Your Adventure?"
- CTA: "Browse All Trips" (→ /trips)

### Data Requirements

```php
Trip::where('is_featured', true)->where('is_active', true)
    ->with(['category', 'packages' => fn($q) => $q->where('is_active', true)])
    ->take(6)->get()

Category::withCount(['trips' => fn($q) => $q->where('is_active', true)])->get()

Testimonial::where('is_approved', true)->latest()->take(3)->get()
```

---

## Page 2 — Trip Listing (`/trips`)

### Purpose

Allow visitors to discover and filter the full catalogue of available trips.

### Sections

#### 2.1 Page Header

- Heading: "All Trips" — or "{Category Name} Trips" if filtered by category
- Breadcrumb: Home > Trips
- Active filter tags shown as dismissible pills below the heading

#### 2.2 Filter Bar

Livewire-powered, updates results without page reload:

- Category (pill buttons — dynamically loaded)
- Difficulty (easy / moderate / challenging / extreme)
- Duration (Under 7 days / 7–14 days / 14+ days)
- Reset Filters link

#### 2.3 Trip Card Grid

- Responsive grid: 3 columns desktop, 2 tablet, 1 mobile
- Each card:
  - Cover image
  - Category badge
  - Title
  - Duration + difficulty badge
  - Best season
  - Starting price (lowest active package price)
  - "View Trip" link
  - "Book Now" button — opens global booking popup with trip pre-filled
- Empty state message if no trips match filters

### Data Requirements

```php
Trip::where('is_active', true)
    ->with(['category', 'packages' => fn($q) => $q->where('is_active', true)])
    ->when($category, fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', $category)))
    ->when($difficulty, fn($q) => $q->where('difficulty', $difficulty))
    ->when($duration, fn($q) => $q->whereBetween('duration_days', $durationRange))
    ->paginate(12)
```

---

## Page 3 — Trip Detail (`/trips/{slug}`)

### Purpose

The primary conversion page. Visitor reads everything about a trip and books directly without leaving the page.

### Booking UX

- A **sticky "Book Now" button** is fixed to the bottom-right corner on all screen sizes
- Clicking it opens the global booking popup with this trip's `id`, `slug`, and name pre-filled
- Visitor never has to navigate away to book

### Sections

#### 3.1 Hero

- Full-width cover image with trip title overlaid
- Breadcrumb: Home > Trips > {Category} > {Trip Name}

#### 3.2 Overview + Quick Facts

Two-column layout:

- **Left (2/3):** Overview paragraph, highlights list
- **Right (1/3) — Sticky Facts Card:**
  - Duration
  - Difficulty (colour-coded badge)
  - Max Altitude (shown only if `max_altitude_m` is set — trekking/hikes)
  - Route Distance (shown only if `route_distance_km` is set — motorbike/cycling)
  - Group Size (min–max)
  - Best Season
  - Starting from $X (lowest active package price)
  - "Book Now" button — opens global booking popup with trip pre-filled

#### 3.3 Itinerary Accordion

- Section heading: "Day-by-Day Itinerary"
- Each day = one accordion item (first day open by default, rest collapsed)
- Header: "Day {N} — {title}"
- Body: description, accommodation, meals included, distance/elevation if available
- Data from `itinerary_days` ordered by `day_number`

#### 3.4 Major Attractions

- Section heading: "Major Attractions"
- Grid of attraction cards from `trip_attractions` ordered by `sort_order`
- Each card: attraction image, name, short description

#### 3.5 Package Comparison Table

- Section heading: "Choose Your Package"
- 3-column table: Standard / Premium / Luxury (only active tiers shown)
- "Most Popular" badge on the relevant column
- Rows: price, accommodation, guide, porter, meals, highlights
- Inclusions with ✓ / ✗ icons
- Each column: "Book Now" button — opens popup with trip + that specific package pre-filled
- Horizontally scrollable on mobile

#### 3.6 Photo Gallery

- Section heading: "Trip Gallery"
- Grid of gallery images ordered by `sort_order` (up to 10)
- Lightbox on click, swipeable on mobile

#### 3.7 Traveller Reviews

- Section heading: "What Travellers Say"
- Up to 3 approved testimonials for this trip
- Each: avatar or initials fallback, name, location, star rating, quote
- "Share Your Experience" button — opens review submission form below

#### 3.8 Trip FAQs

- Section heading: "Frequently Asked Questions"
- Accordion — each question is one item, collapsed by default
- Data from `trip_faqs` ordered by `sort_order`
- Empty section hidden if no FAQs exist for this trip

#### 3.9 Submit a Review

- Inline expandable form (Livewire)
- Fields: Name (required), Email (required), Rating 1–5 stars (required), Trip Date, Review (required)
- On submit: stored to `testimonials` table with `is_approved = false`
- Success message: "Thank you! Your review will appear after approval."
- No page reload

### Data Requirements

```php
// All relations eager loaded — no lazy loading allowed on this page
Trip::where('slug', $slug)
    ->with([
        'category',
        'itineraryDays'  => fn($q) => $q->orderBy('day_number'),
        'attractions'    => fn($q) => $q->orderBy('sort_order'),
        'images'         => fn($q) => $q->orderBy('sort_order'),
        'faqs'           => fn($q) => $q->orderBy('sort_order'),
        'packages'       => fn($q) => $q->where('is_active', true)->with('inclusions'),
        'testimonials'   => fn($q) => $q->where('is_approved', true)->latest()->take(3),
    ])
    ->firstOrFail()
```

---

## Page 4 — About Us (`/about`)

### Purpose

Build trust and communicate the company's identity, story, values, and team.

### Sections

#### 4.1 Hero Banner

- Full-width banner (team on motorbikes, group trekking, or Nepal landscape)
- Page title: "About TravelNepal"
- Tagline: e.g. "Nepal's Home for Adventure — On Two Wheels or Two Feet."

#### 4.2 Our Story

- 2–3 paragraphs: how the company started, mission, what makes it different
- Side image of team or Nepal landscape

#### 4.3 Our Values

- 3–4 icon + heading + short description blocks
- e.g. Sustainability, Safety First, Authentic Experiences, Community Support

#### 4.4 Meet the Team

- Grid of team member cards: photo, name, role, short bio (2–3 lines)
- 3–4 team members (seeded with realistic data)

#### 4.5 Certifications & Trust Badges

- Row of trust logos: Government of Nepal Licensed, TAAN Member, etc.
- Short label beneath each

#### 4.6 CTA

- Banner: "Ready to Explore Nepal?"
- Button: "View All Trips" (→ /trips)

---

## Page 5 — FAQ (`/faq`)

### Purpose

Answer common company-wide questions about booking, safety, gear, policies, and logistics — reducing friction before a visitor decides to book.

### Sections

#### 5.1 Page Header

- Heading: "Frequently Asked Questions"
- Subheading: "Everything you need to know before your Nepal adventure."

#### 5.2 FAQ Accordion

- All global FAQs from `faqs` table ordered by `sort_order`
- Each question is one accordion item, collapsed by default
- First item open by default

#### 5.3 CTA

- "Still have questions?" banner
- Button: "Get In Touch" (→ /contact)

### Example Global FAQs (Seed Data)

- What fitness level is required for a motorbike tour?
- Do I need a motorcycle license to join a motorbike trip?
- What travel insurance do you recommend?
- What is your cancellation policy?
- What currency should I bring to Nepal?
- Are your guides government licensed?
- What is the best time of year to visit Nepal?
- Can I customise an itinerary?

### Data Requirements

```php
Faq::orderBy('sort_order')->get()
```

---

## Page 6 — Contact (`/contact`)

### Purpose

For general enquiries, trip questions, and custom itinerary requests. Separate from the booking popup which handles direct bookings.

### Sections

#### 6.1 Page Header

- Heading: "Get In Touch"
- Subheading: "Have questions? We'd love to hear from you."

#### 6.2 Two-Column Layout

**Left — Contact Info:**

- Address: Thamel, Kathmandu, Nepal
- Phone number
- Email address
- Office hours
- Embedded Google Map (iframe)

**Right — Contact Form (Livewire):**

- Full Name (required)
- Email (required)
- Phone (optional)
- Subject: General Enquiry / Trip Question / Custom Itinerary / Other
- Trip of Interest: optional select from active trips
- Message (required)

On submit:

- Validate all fields server-side
- Store to `enquiries` table
- Send email notification to admin
- Show inline success message — no page reload
- Clear the form

---

## Acceptance Criteria

- [ ] All pages render correctly on mobile (375px), tablet (768px), and desktop (1280px+)
- [ ] Navigation highlights the active page on every route
- [ ] Categories dropdown in navbar loads dynamically from database
- [ ] Mobile hamburger menu opens and closes correctly
- [ ] Global booking popup opens from navbar, trip cards, and trip detail sticky button
- [ ] Booking popup pre-fills trip name, id, and slug automatically
- [ ] Booking popup opened from navbar shows trip selector dropdown
- [ ] Each package "Book Now" pre-fills both trip and package in the popup
- [ ] Bike ownership question appears only on motorbike trips (`requires_bike = true`)
- [ ] Rental bike dropdown appears only when visitor selects "No" and `bike_rental_available = true`
- [ ] Rental dropdown shows model, engine size, and daily price
- [ ] Info message shown when no rental available and visitor has no bike
- [ ] Booking stores to `bookings` table with status `pending`
- [ ] Confirmation email sent to visitor after booking
- [ ] Trip listing filters update without full page reload
- [ ] Active filters shown as dismissible tags above the grid
- [ ] Trip detail sticky "Book Now" button visible at all scroll positions on all screen sizes
- [ ] Itinerary accordion works on mobile and desktop
- [ ] Attractions display in `sort_order` sequence
- [ ] Package comparison table scrolls horizontally on mobile
- [ ] Gallery lightbox is swipeable on mobile
- [ ] Only approved testimonials appear on the frontend
- [ ] Review submission stores with `is_approved = false` and shows thank you message
- [ ] Contact form stores to `enquiries` table and shows success message
- [ ] All images have descriptive alt text
- [ ] Trip FAQ accordion displays in sort_order sequence on trip detail page
- [ ] Trip FAQ section hidden entirely if no FAQs exist for that trip
- [ ] Global FAQ page loads all faqs ordered by sort_order
- [ ] Global FAQ accordion first item open by default
- [ ] No broken links across any page
- [ ] Fallback content shown when no featured trips, testimonials, or gallery images exist
