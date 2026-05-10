# admin.md — Admin Panel Specification

## Purpose
Define the FilamentPHP admin panel structure, resources, roles, and capabilities. The admin panel must allow non-technical staff to manage all website content — trips, packages, bookings, enquiries, testimonials — without touching the database, codebase, or server.

---

## User Stories

### Super Admin
- As a **super admin**, I want to create and manage other admin accounts with assigned roles.
- As a **super admin**, I want to see a dashboard with key site stats so I can monitor activity at a glance.
- As a **super admin**, I want to manage testimonials and approve or reject visitor-submitted reviews.
- As a **super admin**, I want to view all bookings and confirm or cancel them.
- As a **super admin**, I want to view motorcycle license details and uploaded license images for motorbike bookings so I can verify before confirming.

### Content Manager
- As a **content manager**, I want to create and edit trips with full itinerary, attractions, and gallery without developer help.
- As a **content manager**, I want to manage trip packages and their pricing independently.
- As a **content manager**, I want to manage trip categories and their icons and descriptions.
- As a **content manager**, I want to manage bike rental options per motorbike trip.
- As a **content manager**, I want to view and manage contact form enquiries from the dashboard.

---

## Roles & Permissions

| Role            | Permissions                                                                                          |
| --------------- | ---------------------------------------------------------------------------------------------------- |
| Super Admin     | Full access: all resources, user management, booking confirmation, testimonial approval              |
| Content Manager | Manage trips, packages, categories, bike rentals, images. View enquiries. Cannot manage users or confirm bookings. |

---

## Filament Resources

### 1. TripResource
**Model:** Trip

**Form fields:**
- `TextInput` — title (required)
- `TextInput` — slug (auto-generated from title, editable)
- `Select` — category_id (relation to Category, required)
- `Select` — difficulty (enum: easy, moderate, challenging, extreme)
- `TextInput` — duration_days (numeric, required)
- `TextInput` — max_altitude_m (numeric, nullable — shown for trekking/hikes)
- `TextInput` — route_distance_km (numeric, nullable — shown for motorbike/cycling)
- `TextInput` — min_group_size / max_group_size (numeric)
- `TextInput` — best_season
- `RichEditor` — overview (required)
- `TagsInput` — highlights (JSON array)
- `FileUpload` — cover_image (single image, required)
- `Toggle` — is_featured
- `Toggle` — is_active
- `Toggle` — requires_bike (shows/hides bike rental section)
- `Toggle` — bike_rental_available (visible only when requires_bike = true)

**Relation Managers:**
- `ItineraryDaysRelationManager` — add/edit/reorder/delete day entries (ordered by day_number)
- `TripAttractionsRelationManager` — add/edit/reorder/delete attractions with image upload (ordered by sort_order)
- `TripImagesRelationManager` — gallery image upload with drag-to-reorder (ordered by sort_order)
- `TripFaqsRelationManager` — add/edit/reorder/delete trip-specific FAQs (ordered by sort_order)
- `PackagesRelationManager` — manage standard/premium/luxury tiers per trip
- `BikeRentalsRelationManager` — visible only when requires_bike = true; manage rental bike models, engine CC, price per day, availability

**Table columns:** title, category, difficulty badge, duration, is_featured, is_active, requires_bike, created_at

**Filters:** by category, by difficulty, by is_featured, by requires_bike

---

### 2. CategoryResource
**Model:** Category

**Form fields:**
- `TextInput` — name (required)
- `TextInput` — slug (auto-generated, editable)
- `TextInput` — icon (icon name or SVG class)
- `Textarea` — description

**Table columns:** name, slug, trip count, created_at

---

### 3. PackageResource
**Model:** Package

**Form fields:**
- `Select` — trip_id (searchable relation, required)
- `Select` — tier (standard, premium, luxury)
- `TextInput` — title (required)
- `TextInput` — price_usd (numeric, required — USD only)
- `Toggle` — is_popular
- `Toggle` — is_active
- `Textarea` — description

**Relation Manager:**
- `PackageInclusionsRelationManager` — manage inclusions and exclusions with type toggle (inclusion / exclusion) and sort_order

**Table columns:** trip title, tier badge, price (USD), is_popular, is_active

**Filters:** by trip, by tier, by is_active

---

### 4. BookingResource
**Model:** Booking

Read-only from the list view. Super Admin can change status. Content Manager can view only.

**Table columns:** name, email, trip title, package tier, preferred date, group size, status badge, has_own_bike, created_at

**Detail view shows:**
- All standard booking fields
- If motorbike booking: has_license, license_number, license_country, license_type, license_image (viewable inline)
- Rental bike selected (if applicable) with daily rate and calculated total
- Current status

**Actions (Super Admin only):**
- Confirm Booking (pending → confirmed)
- Cancel Booking (pending/confirmed → cancelled)
- View License Image (opens uploaded image in modal)

**Filters:** by status (pending, confirmed, cancelled), by trip, by has_own_bike, by date range

**Note:** Admin must verify license image manually before confirming a motorbike booking.

---

### 5. EnquiryResource
**Model:** Enquiry (from contact form)

Read-only resource. No create form needed from admin side.

**Table columns:** name, email, subject, trip of interest, submitted_at, status badge

**Actions:**
- Mark as Read
- Mark as Replied
- Delete

**Filters:** by status (unread, read, replied)

---

### 6. FaqResource
**Model:** Faq (global company FAQs)

**Form fields:**
- `TextInput` — question (required)
- `Textarea` — answer (required)
- `TextInput` — sort_order (numeric)

**Table columns:** question (truncated), sort_order, created_at

**Note:** Trip-specific FAQs are managed via `TripFaqsRelationManager` inside TripResource — not here. This resource is for global FAQs only.

---

### 7. TestimonialResource
**Model:** Testimonial

**Form fields:**
- `TextInput` — name (required)
- `TextInput` — location (e.g. "Sydney, Australia")
- `Select` — trip_id (optional — which trip this is about)
- `Textarea` — quote (required)
- `TextInput` — rating (1–5)
- `FileUpload` — avatar (optional)
- `Toggle` — is_approved

**Table columns:** name, location, trip (linked), rating, is_approved, created_at

**Actions:**
- Approve (sets is_approved = true)
- Reject / Unapprove (sets is_approved = false)
- Delete

**Filters:** by is_approved, by trip, by rating

**Note:** Newly submitted visitor reviews arrive with is_approved = false and are highlighted in the table until actioned.

---

## Dashboard Widgets

| Widget                  | Data Shown                                                                         |
| ----------------------- | ---------------------------------------------------------------------------------- |
| StatsOverviewWidget     | Total active trips, total bookings this month, pending bookings, total enquiries   |
| PendingBookingsWidget   | Last 5 pending bookings — name, trip, date, status                                 |
| LatestEnquiriesWidget   | Last 5 contact form submissions                                                    |
| PendingReviewsWidget    | Count of unapproved testimonials with quick approve/reject actions                 |
| FeaturedTripsWidget     | Count of currently featured trips — warning if approaching limit of 6              |

---

## Acceptance Criteria

- [ ] A non-technical user can create a complete trip (itinerary, attractions, images, packages, bike rentals) entirely through the admin panel
- [ ] Cover image upload works and previews before saving
- [ ] Gallery images support drag-to-reorder via sort_order
- [ ] Attraction images can be uploaded and reordered inline
- [ ] Itinerary days can be added, edited, reordered, and deleted inline
- [ ] Packages show inline inclusion/exclusion management with sort_order
- [ ] BikeRentalsRelationManager only appears on trips where requires_bike = true
- [ ] Bookings are viewable with full detail including license image for motorbike bookings
- [ ] License image opens in a modal preview — admin can verify without downloading
- [ ] Only Super Admin can confirm or cancel bookings
- [ ] Enquiries are visible with unread highlighted
- [ ] Visitor-submitted testimonials arrive as is_approved = false and are clearly flagged
- [ ] Testimonials can be approved or rejected with a single action
- [ ] TripFaqsRelationManager allows adding, reordering, and deleting FAQs per trip
- [ ] FaqResource allows managing global company FAQs with sort_order
- [ ] Role-based access prevents Content Managers from confirming bookings or managing users
- [ ] Dashboard loads without N+1 queries (eager load or direct widget queries)
- [ ] All admin routes protected behind Filament authentication
- [ ] Admin panel accessible at `/admin`