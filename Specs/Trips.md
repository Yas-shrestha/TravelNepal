# trips.md — Trip Specification

## Purpose

Define the structure, categorisation, and content model for all adventure travel trips offered by TravelNepal. The platform covers a broad range of Nepal adventure experiences — motorbike tours, trekking, cycling, cultural tours, and hikes. Trips must support rich itinerary detail, difficulty classification, and category-based navigation.

---

## User Stories

### Visitor

- As a **visitor**, I want to browse trips by category (motorbike tours, trekking, cycling, cultural, hikes, etc.) so I can find trips relevant to my interests.
- As a **visitor**, I want to see a trip's difficulty level so I can assess whether it suits my fitness.
- As a **visitor**, I want to read a day-by-day itinerary so I understand exactly what the trip involves.
- As a **visitor**, I want to see key facts (duration, max altitude / max-distance, group size, best season) at a glance.
- As a **visitor**, I want to be able to rent bike for ride.
- As a **visitor**, I want to know the estimated cost and major attraction points.
- As a **visitor**, I want to read testimonials from past travellers so I can trust the company.
- As a **visitor**, I want to submit my own review after a trip so I can share my experience.
- As a **visitor**, I want to book a trip directly from the trip page so I don't have to navigate elsewhere.
- As a **visitor**, I want to receive a confirmation after booking so I know my request was received.

### Admin

- As an **admin**, I want to create and edit trips from the admin panel without touching the database.
- As an **admin**, I want to mark a trip as featured so it appears on the homepage.
- As an **admin**, I want to upload a cover image and gallery images for each trip.
- As an **admin**, I want to approve or reject visitor-submitted reviews before they go live.
- As an **admin**, I want to view the license details and uploaded license image for motorbike bookings so I can verify before confirming.

---

## Data Model

### `trips` table

| Column                  | Type            | Notes                                    |
| ----------------------- | --------------- | ---------------------------------------- |
| id                      | bigint (PK)     |                                          |
| title                   | string          | e.g. "Annapurna Base Camp Trek"          |
| slug                    | string (unique) | URL-friendly identifier                  |
| category_id             | FK → categories |                                          |
| difficulty              | enum            | easy, moderate, challenging, extreme     |
| duration_days           | integer         | e.g. 14                                  |
| max_altitude_m          | integer         | e.g. 4130                                |
| min_group_size          | integer         |                                          |
| max_group_size          | integer         |                                          |
| best_season             | string          | e.g. "March–May, Sept–Nov"               |
| overview                | text            | Short paragraph summary                  |
| highlights              | json            | Array of highlight strings               |
| cover_image             | string          | Path to main image                       |
| is_featured             | boolean         | default false                            |
| is_active               | boolean         | default true                             |
| requires_bike           | boolean         | default false — true for motorbike trips |
| bike_rental_available   | boolean         | default false — true if rental offered   |
| created_at / updated_at | timestamps      |                                          |

### `categories` table

| Column      | Type            | Notes                                                              |
| ----------- | --------------- | ------------------------------------------------------------------ |
| id          | bigint (PK)     |                                                                    |
| name        | string          | e.g. "Motorbike Tours", "Trekking", "Cycling", "Cultural", "Hikes" |
| slug        | string (unique) |                                                                    |
| icon        | string          | Icon name or SVG path                                              |
| description | text            |                                                                    |

### `itinerary_days` table

| Column           | Type               | Notes                      |
| ---------------- | ------------------ | -------------------------- |
| id               | bigint (PK)        |                            |
| trip_id          | FK → trips         |                            |
| day_number       | integer            |                            |
| title            | string             | e.g. "Kathmandu to Lukla"  |
| description      | text               | Full day description       |
| accommodation    | string             | e.g. "Tea house in Namche" |
| meals_included   | string             | e.g. "Breakfast, Dinner"   |
| distance_km      | decimal (nullable) |                            |
| elevation_gain_m | integer (nullable) |                            |

### `trip_attractions` table

| Column      | Type        | Notes                               |
| ----------- | ----------- | ----------------------------------- |
| id          | bigint (PK) |                                     |
| trip_id     | FK → trips  |                                     |
| name        | string      | e.g. "Kala Patthar Viewpoint"       |
| description | text        | Short description of the attraction |
| image_path  | string      | Photo of the attraction             |
| sort_order  | integer     | Display order on the page           |

### `trip_images` table

| Column     | Type              | Notes |
| ---------- | ----------------- | ----- |
| id         | bigint (PK)       |       |
| trip_id    | FK → trips        |       |
| image_path | string            |       |
| caption    | string (nullable) |       |
| sort_order | integer           |       |

### `bike_rentals` table

| Column            | Type          | Notes                              |
| ----------------- | ------------- | ---------------------------------- |
| id                | bigint (PK)   |                                    |
| trip_id           | FK → trips    |                                    |
| model             | string        | e.g. "Royal Enfield Himalayan 411" |
| engine_cc         | integer       | e.g. 411                           |
| price_per_day_npr | decimal(10,2) | Rental cost per day                |
| is_available      | boolean       | default true                       |
| sort_order        | integer       | Display order in rental selector   |

### `testimonials` table

| Column                  | Type                  | Notes                                                |
| ----------------------- | --------------------- | ---------------------------------------------------- |
| id                      | bigint (PK)           |                                                      |
| trip_id                 | FK → trips (nullable) | Which trip the review is about                       |
| name                    | string                | Reviewer's name                                      |
| location                | string                | e.g. "Sydney, Australia"                             |
| avatar                  | string (nullable)     | Optional profile photo                               |
| quote                   | text                  | The review content                                   |
| rating                  | tinyint               | 1–5                                                  |
| is_approved             | boolean               | default false — admin must approve before going live |
| created_at / updated_at | timestamps            |                                                      |

### `bookings` table

| Column                  | Type                         | Notes                                                                                         |
| ----------------------- | ---------------------------- | --------------------------------------------------------------------------------------------- |
| id                      | bigint (PK)                  |                                                                                               |
| trip_id                 | FK → trips                   |                                                                                               |
| package_id              | FK → packages                |                                                                                               |
| name                    | string                       |                                                                                               |
| email                   | string                       |                                                                                               |
| phone                   | string (nullable)            |                                                                                               |
| preferred_date          | date                         |                                                                                               |
| group_size              | integer                      |                                                                                               |
| message                 | text (nullable)              |                                                                                               |
| has_own_bike            | boolean (nullable)           | Only collected when `requires_bike = true`                                                    |
| own_bike_model          | string (nullable)            | Filled if has_own_bike = true                                                                 |
| rental_bike_id          | FK → bike_rentals (nullable) | Filled if has_own_bike = false and rental selected — NULL if has_own_bike = true              |
| rental_cost_usd         | decimal(10,2) (nullable)     | Calculated at booking time: `price_per_day_usd × duration_days` — NULL if has_own_bike = true |
| has_license             | boolean (nullable)           | Only collected when `requires_bike = true`                                                    |
| license_number          | string (nullable)            | Visitor's motorcycle license number — required if has_license = true                          |
| license_country         | string (nullable)            | Country that issued the license                                                               |
| license_type            | enum (nullable)              | `local` or `international` — only when `requires_bike = true`                                 |
| license_image           | string (nullable)            | Uploaded photo/scan of the license — required if has_license = true                           |
| status                  | enum                         | pending, confirmed, cancelled                                                                 |
| created_at / updated_at | timestamps                   |                                                                                               |

---

## Booking Form Logic (Motorbike Trips)

The following conditional flow applies **only** when a trip has `requires_bike = true`:

```
1. Do you have a valid motorcycle license?
   ├── NO  → Show blocking message:
   │         "A valid motorcycle license is required to join this trip.
   │          You cannot proceed without one."
   │         Form submission is disabled.
   │
   └── YES → Ask:
             - License Number (required)
             - Issuing Country (required)
             - License Type: Local / International Driving Permit (required)
             - Upload License Photo (required)

             Then ask:
             2. Do you have your own bike?
                ├── YES → Ask: Bike Model (required)
                │         No rental charge applied.
                │         rental_bike_id and rental_cost_usd remain NULL.
                │
                └── NO  → If bike_rental_available = true:
                           Show rental dropdown (only is_available = true bikes).
                           Each option shows: model, engine CC, price per day, and
                           calculated total (price_per_day_usd × duration_days).
                           Selected bike stored in rental_bike_id.
                           rental_cost_usd = price_per_day_usd × trip duration_days.

                           If bike_rental_available = false:
                           Show message: "Please contact us to discuss bike arrangements."
```

> **Note:** License details are always required for motorbike trips regardless of whether the visitor brings their own bike or rents one — they must be able to legally ride.

### Rental Cost Calculation

| Field               | Source                                                                      |
| ------------------- | --------------------------------------------------------------------------- |
| `price_per_day_usd` | From the selected `bike_rentals` record                                     |
| `duration_days`     | From the `trips` record (`duration_days` column)                            |
| `rental_cost_usd`   | `price_per_day_usd × duration_days` — calculated and stored at booking time |

> The rental cost is calculated at the moment of booking and stored as `rental_cost_usd` so the agreed price is preserved even if the daily rate is updated later. This amount is shown to the visitor in the booking summary and in their confirmation email as an add-on to the trip package price.

---

## Relationships

- A **Trip** belongs to one **Category**
- A **Trip** has many **ItineraryDays** (ordered by day_number)
- A **Trip** has many **TripAttractions** (ordered by sort_order)
- A **Trip** has many **TripImages**
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
- [ ] Max altitude and group size display in the "facts" sidebar on trip detail
- [ ] Each attraction displays its name, description, and image on the trip detail page
- [ ] Attractions are displayed in sort_order sequence
- [ ] Attraction image is required — an attraction cannot be saved without one
- [ ] Admin can add, reorder, and delete attractions per trip from the admin panel
- [ ] Booking form is accessible directly on the trip detail page

**Motorbike-specific booking criteria:**

- [ ] License question, own bike question, and rental dropdown are shown **only** when `requires_bike = true`
- [ ] If visitor answers "No" to license question, form submission is hard-blocked with an explanatory message
- [ ] If visitor answers "Yes" to license question, the following fields become required: license number, issuing country, license type (local / international), license image upload
- [ ] License image must be uploaded before the booking form can be submitted on a motorbike trip
- [ ] After license fields, visitor is asked: "Do you have your own bike?"
- [ ] If visitor selects "Yes" for own bike, bike model field is required
- [ ] If visitor selects "No" for own bike and `bike_rental_available = true`, rental dropdown appears
- [ ] Rental dropdown lists only `is_available = true` bike rentals for that trip
- [ ] If visitor selects "No" for own bike and `bike_rental_available = false`, a "contact us" message is shown
- [ ] Rental bike selection stored in `rental_bike_id` on the booking
- [ ] All license fields (`has_license`, `license_number`, `license_country`, `license_type`, `license_image`) remain NULL for non-motorbike bookings
- [ ] Admin can view license details and the uploaded license image on the booking detail page
- [ ] Admin must manually verify the license before changing booking status from `pending` to `confirmed`

**General booking criteria:**

- [ ] A confirmation email is sent to the visitor after a successful booking
- [ ] Booking is saved to the database with status `pending` by default
- [ ] Testimonials with `is_approved = false` never appear on the frontend
- [ ] Admin can approve, reject, or delete submitted testimonials

---

## Categories (Seed Data)

| Category        | Slug            | Description                                                                                  |
| --------------- | --------------- | -------------------------------------------------------------------------------------------- |
| Motorbike Tours | motorbike-tours | Royal Enfield and off-road motorcycle adventures across Nepal's highways and mountain passes |
| Trekking        | trekking        | Multi-day Himalayan treks to base camps, circuits, and remote valleys                        |
| Cycling         | cycling         | Road and mountain biking routes through Nepal's terrain                                      |
| Cultural Tours  | cultural-tours  | Heritage, temple, and village experiences exploring Nepal's history and traditions           |
| Hikes           | hikes           | Shorter day and multi-day hikes accessible to all fitness levels                             |

---

## Example Trip Names (Seed Data)

**Motorbike Tours** _(requires_bike = true)_

- Kathmandu to Mustang Motorbike Expedition (12 days, Challenging)
- Everest Highway Royal Enfield Ride (10 days, Moderate)
- Nepal Himalaya Circuit by Motorbike (18 days, Extreme)

**Trekking** _(requires_bike = false)_

- Everest Base Camp Trek (14 days, Challenging)
- Annapurna Circuit Trek (18 days, Challenging)
- Langtang Valley Trek (10 days, Moderate)
- Manaslu Circuit Trek (16 days, Extreme)

**Cycling** _(requires_bike = false)_

- Kathmandu Valley Cycling Tour (5 days, Easy)
- Pokhara to Chitwan Cycling Adventure (7 days, Moderate)

**Cultural Tours** _(requires_bike = false)_

- Upper Mustang Cultural Tour (12 days, Moderate)
- Kathmandu Heritage Valley Tour (4 days, Easy)

**Hikes** _(requires_bike = false)_

- Nagarkot Sunrise Hike (1 day, Easy)
- Poon Hill Hike (4 days, Easy)
