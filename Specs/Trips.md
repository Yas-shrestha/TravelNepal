# trips.md — Trip Specification

## Purpose

Define the structure, categorisation, and content model for all travel trips offered by TravelNepal. Trips are the core product of the platform and must support rich itinerary detail, difficulty classification, and category-based navigation.

---

## User Stories

### Visitor

- As a **visitor**, I want to browse trips by category (trekking, cycling, cultural, hikes, etc.) so I can find trips relevant to my interests.
- As a **visitor**, I want to see a trip's difficulty level so I can assess whether it suits my fitness.
- As a **visitor**, I want to read a day-by-day itinerary so I understand exactly what the trip involves.
- As a **visitor**, I want to see key facts (duration, max altitude, group size, best season) at a glance.
- As a **visitor**, I want to know the estimated cost and major attraction points.
- As a **visitor**, I want to read testimonials from past travellers so I can trust the company.
- As a **visitor**, I want to submit my own review after a trip so I can share my experience.
- As a **visitor**, I want to book a trip directly from the trip page so I don't have to navigate elsewhere.
- As a **visitor**, I want to receive a confirmation after booking so I know my request was received.
- As a **visitor**, I want to view a photo gallery of the trek
  so I can visualise the experience.

### Admin

- As an **admin**, I want to create and edit trips from the admin panel without touching the database.
- As an **admin**, I want to mark a trip as featured so it appears on the homepage.
- As an **admin**, I want to upload a cover image and gallery images for each trip.
- As an **admin**, I want to approve or reject visitor-submitted reviews before they go live.

---

## Data Model

### `trips` table

| Column                  | Type            | Notes                                |
| ----------------------- | --------------- | ------------------------------------ |
| id                      | bigint (PK)     |                                      |
| title                   | string          | e.g. "Annapurna Base Camp Trek"      |
| slug                    | string (unique) | URL-friendly identifier              |
| category_id             | FK → categories |                                      |
| difficulty              | enum            | easy, moderate, challenging, extreme |
| duration_days           | integer         | e.g. 14                              |
| max_altitude_m          | integer         | e.g. 4130                            |
| min_group_size          | integer         |                                      |
| max_group_size          | integer         |                                      |
| best_season             | string          | e.g. "March–May, Sept–Nov"           |
| overview                | text            | Short paragraph summary              |
| highlights              | json            | Array of highlight strings           |
| cover_image             | string          | Path to main image                   |
| is_featured             | boolean         | default false                        |
| is_active               | boolean         | default true                         |
| created_at / updated_at | timestamps      |                                      |

### `categories` table

| Column      | Type            | Notes                                  |
| ----------- | --------------- | -------------------------------------- |
| id          | bigint (PK)     |                                        |
| name        | string          | e.g. "Trekking", "Cycling", "Cultural" |
| slug        | string (unique) |                                        |
| icon        | string          | Icon name or SVG path                  |
| description | text            |                                        |

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

| Column                  | Type              | Notes                         |
| ----------------------- | ----------------- | ----------------------------- |
| id                      | bigint (PK)       |                               |
| trip_id                 | FK → trips        |                               |
| package_id              | FK → packages     |                               |
| name                    | string            |                               |
| email                   | string            |                               |
| phone                   | string (nullable) |                               |
| preferred_date          | date              |                               |
| group_size              | integer           |                               |
| message                 | text (nullable)   |                               |
| status                  | enum              | pending, confirmed, cancelled |
| created_at / updated_at | timestamps        |                               |

---

## Relationships

- A **Trip** belongs to one **Category**
- A **Trip** has many **ItineraryDays** (ordered by day_number)
- A **Trip** has many **TripAttractions** (ordered by sort_order)
- A **Trip** has many **TripImages**
- A **Trip** has many **Packages** (see packages.md)
- A **Trip** has many **Testimonials**
- A **Trip** has many **Bookings**

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
- [ ] A confirmation email is sent to the visitor after a successful booking
- [ ] Booking is saved to the database with status `pending` by default
- [ ] Testimonials with `is_approved = false` never appear on the frontend
- [ ] Admin can approve, reject, or delete submitted testimonials

---

## Example Trip Names (Seed Data)

- Everest Base Camp Trek (14 days, Challenging)
- Annapurna Circuit Trek (18 days, Challenging)
- Langtang Valley Trek (10 days, Moderate)
- Upper Mustang Cultural Tour (12 days, Moderate)
- Kathmandu Valley Cycling Tour (5 days, Easy)
- Manaslu Circuit Trek (16 days, Extreme)
- Pokhara to Chitwan Heritage Drive (7 days, Easy)
