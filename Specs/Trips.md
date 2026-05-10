# trips.md — Trip Specification

## Purpose
Define the structure, categorisation, and content model for all adventure travel trips offered by TravelNepal. The platform covers a broad range of Nepal adventure experiences — motorbike tours, trekking, cycling, cultural tours, and hikes. Trips must support rich itinerary detail, difficulty classification, and category-based navigation.

---

## User Stories

### Visitor
- As a **visitor**, I want to browse trips by category (motorbike tours, trekking, cycling, cultural, hikes, etc.) so I can find trips relevant to my interests.
- As a **visitor**, I want to see a trip's difficulty level so I can assess whether it suits my fitness.
- As a **visitor**, I want to read a day-by-day itinerary so I understand exactly what the trip involves.
- As a **visitor**, I want to see key facts (duration, max altitude / route distance, group size, best season) at a glance so I can quickly assess the trip.
- As a **visitor**, I want to know the estimated cost and major attraction points.
- As a **visitor**, I want to read testimonials from past travellers so I can trust the company.
- As a **visitor**, I want to submit my own review after a trip so I can share my experience.
- As a **visitor**, I want to read trip-specific FAQs so I can get answers to common questions about that trip.
- As a **visitor**, I want to read general company FAQs so I can understand policies and logistics before booking.
- As a **visitor**, I want to book a trip directly from the trip page so I don't have to navigate elsewhere.
- As a **visitor**, I want to receive a confirmation after booking so I know my request was received.

### Admin
- As an **admin**, I want to create and edit trips from the admin panel without touching the database.
- As an **admin**, I want to mark a trip as featured so it appears on the homepage.
- As an **admin**, I want to upload a cover image and gallery images for each trip.
- As an **admin**, I want to approve or reject visitor-submitted reviews before they go live.
- As an **admin**, I want to manage trip-specific FAQs per trip from the admin panel.
- As an **admin**, I want to manage global company FAQs from the admin panel.

---

## Data Model

### `trips` table

| Column | Type | Notes |
|---|---|---|
| id | bigint (PK) | |
| title | string | e.g. "Annapurna Base Camp Trek" |
| slug | string (unique) | URL-friendly identifier |
| category_id | FK → categories | |
| difficulty | enum | easy, moderate, challenging, extreme |
| duration_days | integer | e.g. 14 |
| max_altitude_m | integer (nullable) | Trekking & hikes only. Hidden on frontend if null |
| route_distance_km | decimal (nullable) | Motorbike & cycling only. Hidden on frontend if null |
| min_group_size | integer | |
| max_group_size | integer | |
| best_season | string | e.g. "March–May, Sept–Nov" |
| overview | text | Short paragraph summary |
| highlights | json | Array of highlight strings |
| cover_image | string | Path to main image |
| is_featured | boolean | default false |
| is_active | boolean | default true |
| requires_bike | boolean | default false — true for motorbike trips |
| bike_rental_available | boolean | default false — true if rental offered |
| created_at / updated_at | timestamps | |

### `categories` table

| Column | Type | Notes |
|---|---|---|
| id | bigint (PK) | |
| name | string | e.g. "Motorbike Tours", "Trekking", "Cycling", "Cultural", "Hikes" |
| slug | string (unique) | |
| icon | string | Icon name or SVG path |
| description | text | |

### `itinerary_days` table

| Column | Type | Notes |
|---|---|---|
| id | bigint (PK) | |
| trip_id | FK → trips | |
| day_number | integer | |
| title | string | e.g. "Kathmandu to Lukla" |
| description | text | Full day description |
| accommodation | string | e.g. "Tea house in Namche" |
| meals_included | string | e.g. "Breakfast, Dinner" |
| distance_km | decimal (nullable) | |
| elevation_gain_m | integer (nullable) | |

### `trip_attractions` table

| Column | Type | Notes |
|---|---|---|
| id | bigint (PK) | |
| trip_id | FK → trips | |
| name | string | e.g. "Kala Patthar Viewpoint" |
| description | text | Short description of the attraction |
| image_path | string | Photo of the attraction |
| sort_order | integer | Display order on the page |

### `trip_images` table

| Column | Type | Notes |
|---|---|---|
| id | bigint (PK) | |
| trip_id | FK → trips | |
| image_path | string | |
| caption | string (nullable) | |
| sort_order | integer | |

### `bike_rentals` table

| Column | Type | Notes |
|---|---|---|
| id | bigint (PK) | |
| trip_id | FK → trips | |
| model | string | e.g. "Royal Enfield Himalayan 411" |
| engine_cc | integer | e.g. 411 |
| price_per_day_usd | decimal(10,2) | Rental cost per day |
| is_available | boolean | default true |
| sort_order | integer | Display order in rental selector |

### `trip_faqs` table

| Column      | Type        | Notes                                              |
| ----------- | ----------- | -------------------------------------------------- |
| id          | bigint (PK) |                                                    |
| trip_id     | FK → trips  |                                                    |
| question    | string      | e.g. "What permits are needed for Manaslu?"        |
| answer      | text        | Full answer                                        |
| sort_order  | integer     | Controls display order                             |

### `faqs` table

| Column      | Type        | Notes                                              |
| ----------- | ----------- | -------------------------------------------------- |
| id          | bigint (PK) |                                                    |
| question    | string      | e.g. "Do you provide travel insurance?"            |
| answer      | text        | Full answer                                        |
| sort_order  | integer     | Controls display order                             |

### `enquiries` table

| Column                  | Type                  | Notes                                          |
| ----------------------- | --------------------- | ---------------------------------------------- |
| id                      | bigint (PK)           |                                                |
| name                    | string                |                                                |
| email                   | string                |                                                |
| phone                   | string (nullable)     |                                                |
| subject                 | string                | e.g. "Custom Itinerary"                        |
| trip_id                 | FK → trips (nullable) | Trip of interest if specified                  |
| message                 | text                  |                                                |
| status                  | enum                  | unread, read, replied                          |
| created_at / updated_at | timestamps            |                                                |

### `testimonials` table

| Column | Type | Notes |
|---|---|---|
| id | bigint (PK) | |
| trip_id | FK → trips (nullable) | Which trip the review is about |
| name | string | Reviewer's name |
| location | string | e.g. "Sydney, Australia" |
| avatar | string (nullable) | Optional profile photo |
| quote | text | The review content |
| rating | tinyint | 1–5 |
| is_approved | boolean | default false — admin must approve before going live |
| created_at / updated_at | timestamps | |

### `bookings` table

| Column | Type | Notes |
|---|---|---|
| id | bigint (PK) | |
| trip_id | FK → trips | |
| package_id | FK → packages | |
| name | string | |
| email | string | |
| phone | string (nullable) | |
| preferred_date | date | |
| group_size | integer | |
| message | text (nullable) | |
| has_own_bike | boolean (nullable) | Only for motorbike trips |
| own_bike_model | string (nullable) | Filled if has_own_bike = true |
| rental_bike_id | FK → bike_rentals (nullable) | Filled if has_own_bike = false and rental selected |
| rental_cost_usd | decimal(10,2) (nullable) | Calculated at booking: `price_per_day_usd × duration_days` |
| has_license | boolean (nullable) | Only collected when `requires_bike = true` |
| license_number | string (nullable) | Required if has_license = true |
| license_country | string (nullable) | Country that issued the license |
| license_type | enum (nullable) | `local` or `international` |
| license_image | string (nullable) | Uploaded photo/scan — required if has_license = true |
| status | enum | pending, confirmed, cancelled |
| created_at / updated_at | timestamps | |

> **Note:** All prices are in USD. NPR conversion can be added in a future iteration.

---

## Relationships

- A **Trip** belongs to one **Category**
- A **Trip** has many **ItineraryDays** (ordered by day_number)
- A **Trip** has many **TripAttractions** (ordered by sort_order)
- A **Trip** has many **TripImages**
- A **Trip** has many **TripFaqs** (ordered by sort_order)
- A **Trip** has many **BikeRentals** (only populated when requires_bike = true)
- A **Trip** has many **Packages** (see packages.md)
- A **Trip** has many **Testimonials**
- A **Trip** has many **Bookings**
- A **Booking** optionally belongs to one **BikeRental**

---

## Acceptance Criteria

- [ ] Each trip has at least one category assigned
- [ ] Slug is auto-generated from title and must be unique
- [ ] Itinerary days are always displayed in ascending day_number order
- [ ] A trip with `is_active = false` must not appear on any public-facing page
- [ ] Featured trips (up to 6) appear on the homepage featured section
- [ ] Difficulty levels render with a visual badge (colour-coded)
- [ ] Cover image is required before a trip can be published
- [ ] Gallery supports a minimum of 1 and maximum of 10 images
- [ ] Filtering on the listing page works for: category, difficulty, duration range
- [ ] Facts sidebar shows `max_altitude_m` for trekking/hikes and `route_distance_km` for motorbike/cycling — hidden if null
- [ ] Each attraction displays its name, description, and image on the trip detail page
- [ ] Attractions are displayed in sort_order sequence
- [ ] Attraction image is required — an attraction cannot be saved without one
- [ ] Admin can add, reorder, and delete attractions per trip from the admin panel
- [ ] Booking form is accessible directly on the trip detail page
- [ ] Trip FAQs display in sort_order sequence as an accordion on the trip detail page
- [ ] Global FAQs display in sort_order sequence on the FAQ page
- [ ] Admin can add, reorder, and delete trip FAQs per trip from the admin panel
- [ ] Admin can add, reorder, and delete global FAQs from the admin panel

**Motorbike-specific booking criteria:**
- [ ] License and bike fields shown only when `requires_bike = true`
- [ ] If visitor answers No to license question, form submission is hard-blocked with explanatory message
- [ ] If visitor answers Yes, the following become required: license number, issuing country, license type, license image upload
- [ ] After license fields, visitor is asked: "Do you have your own bike?"
- [ ] If Yes to own bike, bike model field is required
- [ ] If No to own bike and `bike_rental_available = true`, rental dropdown appears showing model, engine CC, daily price, and calculated total
- [ ] If No to own bike and `bike_rental_available = false`, contact us message shown
- [ ] Rental cost calculated as `price_per_day_usd × duration_days` and stored in `rental_cost_usd` at booking time
- [ ] Rental cost shown in booking summary and confirmation email
- [ ] All license fields remain NULL for non-motorbike bookings
- [ ] Admin can view license details and uploaded license image on booking detail
- [ ] Admin must manually verify license before confirming booking

**General booking criteria:**
- [ ] Confirmation email sent to visitor after successful booking
- [ ] Booking saved with status `pending` by default
- [ ] Testimonials with `is_approved = false` never appear on the frontend
- [ ] Admin can approve, reject, or delete submitted testimonials

---

## Categories (Seed Data)

| Category | Slug | Description |
|---|---|---|
| Motorbike Tours | motorbike-tours | Royal Enfield and off-road motorcycle adventures across Nepal's highways and mountain passes |
| Trekking | trekking | Multi-day Himalayan treks to base camps, circuits, and remote valleys |
| Cycling | cycling | Road and mountain biking routes through Nepal's terrain |
| Cultural Tours | cultural-tours | Heritage, temple, and village experiences exploring Nepal's history and traditions |
| Hikes | hikes | Shorter day and multi-day hikes accessible to all fitness levels |

---

## Example Trip Names (Seed Data)

**Motorbike Tours**
- Kathmandu to Mustang Motorbike Expedition (12 days, Challenging)
- Everest Highway Royal Enfield Ride (10 days, Moderate)
- Nepal Himalaya Circuit by Motorbike (18 days, Extreme)

**Trekking**
- Everest Base Camp Trek (14 days, Challenging)
- Annapurna Circuit Trek (18 days, Challenging)
- Langtang Valley Trek (10 days, Moderate)
- Manaslu Circuit Trek (16 days, Extreme)

**Cycling**
- Kathmandu Valley Cycling Tour (5 days, Easy)
- Pokhara to Chitwan Cycling Adventure (7 days, Moderate)

**Cultural Tours**
- Upper Mustang Cultural Tour (12 days, Moderate)
- Kathmandu Heritage Valley Tour (4 days, Easy)

**Hikes**
- Nagarkot Sunrise Hike (1 day, Easy)
- Poon Hill Hike (4 days, Easy)