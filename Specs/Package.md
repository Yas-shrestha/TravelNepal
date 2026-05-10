# packages.md — Package Specification

## Purpose

Define the pricing tiers, inclusions, exclusions, and structure for trip packages. Each trip offers up to three tiers — Standard, Premium, and Luxury — allowing customers to choose their level of service and comfort. Packages apply to all trip types: motorbike tours, trekking, cycling, cultural tours, and hikes.

> **Note:** All prices are in USD. NPR conversion can be added in a future iteration.

---

## User Stories

### Visitor

- As a **visitor**, I want to compare what's included in each package tier side-by-side so I can make an informed choice.
- As a **visitor**, I want to see the price for each package tier clearly so I know what I'm committing to.
- As a **visitor**, I want to understand exactly what is and isn't included so there are no surprises.
- As a **visitor**, I want to see which package is most popular so I have a recommended starting point.

### Admin

- As an **admin**, I want to create package tiers per trip with their own pricing and inclusion lists.
- As an **admin**, I want to mark one package as "most popular" to highlight it in the comparison table.
- As an **admin**, I want to enable or disable individual package tiers without deleting them.
- As an **admin**, I want to manage inclusions and exclusions per package independently.

---

## Data Model

### `packages` table

| Column                  | Type          | Notes                                              |
| ----------------------- | ------------- | -------------------------------------------------- |
| id                      | bigint (PK)   |                                                    |
| trip_id                 | FK → trips    |                                                    |
| tier                    | enum          | standard, premium, luxury                          |
| title                   | string        | e.g. "Standard Motorbike Package"                  |
| price_usd               | decimal(10,2) | Per person price in USD                            |
| is_popular              | boolean       | Highlights this tier in the UI — only one per trip |
| is_active               | boolean       | default true                                       |
| description             | text          | Short paragraph about this tier                    |
| created_at / updated_at | timestamps    |                                                    |

### `package_inclusions` table

| Column      | Type          | Notes                            |
| ----------- | ------------- | -------------------------------- |
| id          | bigint (PK)   |                                  |
| package_id  | FK → packages |                                  |
| description | string        | e.g. "All meals during the trek" |
| type        | enum          | inclusion, exclusion             |
| sort_order  | integer       | Controls display order           |

---

## Relationships

- A **Package** belongs to one **Trip**
- A **Package** has many **PackageInclusions** (typed as inclusion or exclusion, ordered by sort_order)
- A **Booking** belongs to one **Package**

---

## Package Tier Definitions

Inclusions vary by trip type. Below are typical inclusions per tier for each major category. All inclusions are based on what is realistically available in Nepal.

### Standard

Entry-level. Core necessities covered — guided, safe, and no-frills.

**Trekking / Hikes:**

- Airport transfers
- Government-licensed English-speaking guide
- Tea house / basic lodge accommodation
- Breakfast and dinner daily
- All required permits and TIMS card
- First aid kit
- Walkie talkie communication between guide and support

**Motorbike Tours:**

- Airport transfers
- Experienced motorbike tour leader
- Fuel for the route
- Basic guesthouse accommodation
- Breakfast daily
- Shared mechanic support
- Required road permits

**Cycling:**

- Airport transfers
- Cycling guide
- Basic guesthouse accommodation
- Breakfast daily
- Shared support vehicle
- Puncture repair kit

**Cultural Tours:**

- Airport transfers
- Licensed cultural guide
- Basic hotel accommodation (2-star)
- Breakfast daily
- Entry fees to monuments and heritage sites

**Typical exclusions (all categories):**

- International / domestic flights
- Lunch and personal snacks
- Travel insurance
- Personal gear and equipment
- Tips for guide and support staff

---

### Premium

Mid-tier. Comfort upgrades and additional support included.

**Everything in Standard, plus:**

**Trekking / Hikes:**

- Porter service (1 porter per 2 guests)
- Welcome hotel night in Kathmandu (3-star)
- Farewell dinner in Kathmandu
- Walkie talkie per group (guide + assistant)
- Basic medical kit with altitude sickness medication

**Motorbike Tours:**

- Dedicated mechanic per group
- 3-star hotel accommodation throughout
- Breakfast and dinner daily
- Spare parts kit per bike
- Shared GPS device for the group (tour leader)
- Welcome and farewell dinner in Kathmandu

**Cycling:**

- Private support vehicle
- 3-star hotel accommodation
- Breakfast and dinner daily
- Bike servicing at midpoint

**Cultural Tours:**

- 3-star hotel accommodation
- Breakfast and dinner daily
- Private transport between sites
- Cultural performance evening (e.g. Newari dinner show)

**Typical exclusions (all categories):**

- International flights
- Personal travel insurance
- Alcoholic beverages
- Personal shopping

---

### Luxury

Top-tier. Full-service, premium experience with maximum comfort available in Nepal.

**Everything in Premium, plus:**

**Trekking / Hikes:**

- Private porter per guest
- 4-star hotel in Kathmandu (pre/post trip — limited 5-star availability in Kathmandu only)
- Helicopter rescue insurance
- Dedicated trip coordinator (on-call)
- Professional trip photographer (select days)
- Custom meal planning (dietary needs catered where possible)
- Premium gear rental (sleeping bag, trekking poles, etc.)

**Motorbike Tours:**

- Priority access to best available Royal Enfield rental bike (rental cost still applies per day)
- Premium helmet and riding gear included
- Best available hotel accommodation throughout (3-star outside Kathmandu, up to 4-star in Kathmandu)
- All meals included (breakfast, lunch, dinner)
- Private mechanic per 2 riders
- Drone footage of select route highlights
- Helicopter evacuation insurance

**Cycling:**

- Premium rental bike (cost included in package)
- Best available hotel accommodation
- All meals included
- Professional photographer on key days

**Cultural Tours:**

- Best available hotel accommodation (up to 4-star in Kathmandu, 3-star elsewhere)
- All meals included
- Private vehicle throughout
- Private after-hours access to key heritage sites (where available)
- Senior licensed cultural guide

**Typical exclusions (all categories):**

- International flights
- Personal luxury items
- Bike rental daily cost for motorbike tours (quoted separately)

---

## UI Comparison Table Structure

The trip detail page renders a 3-column comparison table. Rows shown depend on what is relevant for that trip's category.

**Example — Trekking Trip:**

| Feature              | Standard   | Premium      | Luxury             |
| -------------------- | ---------- | ------------ | ------------------ |
| Price                | $X         | $X           | $X                 |
| Accommodation        | Tea house  | 3-star lodge | 4-star (Kathmandu) |
| Guide                | ✓          | ✓            | ✓                  |
| Porter               | ✗          | Shared       | Private            |
| Meals                | B+D        | B+D          | B+L+D              |
| Hotel (Kathmandu)    | ✗          | 1 night      | 2 nights           |
| Walkie Talkie        | Guide only | Guide + Asst | Guide + Asst       |
| Photographer         | ✗          | ✗            | ✓                  |
| Helicopter Insurance | ✗          | ✗            | ✓                  |

**Example — Motorbike Tour:**

| Feature              | Standard     | Premium      | Luxury              |
| -------------------- | ------------ | ------------ | ------------------- |
| Price                | $X           | $X           | $X                  |
| Accommodation        | Guesthouse   | 3-star hotel | Best available      |
| Bike Rental          | Extra charge | Extra charge | Extra charge        |
| Riding Gear          | ✗            | ✗            | ✓ (helmet + jacket) |
| Mechanic             | Shared       | Dedicated    | Private (per 2)     |
| Meals                | B            | B+D          | B+L+D               |
| GPS (group)          | ✗            | ✓ (shared)   | ✓ (shared)          |
| Drone Footage        | ✗            | ✗            | ✓ (select days)     |
| Helicopter Insurance | ✗            | ✗            | ✓                   |

---

## Acceptance Criteria

- [ ] Each trip must have at least one active package to be publishable
- [ ] Maximum of one package per tier per trip (no duplicate tiers)
- [ ] Prices must be positive decimals greater than 0 (USD only)
- [ ] Only one package per trip can have `is_popular = true`
- [ ] Inclusions and exclusions displayed in separate lists with ✓ / ✗ indicators
- [ ] Package comparison table renders all active tiers side-by-side on trip detail page
- [ ] If a tier is inactive it is hidden from the frontend comparison table
- [ ] The "most popular" badge is visually prominent in the comparison UI
- [ ] Each package "Book Now" button opens the booking popup with that package pre-selected
- [ ] Inclusions and exclusions respect sort_order in display
- [ ] package_inclusions can be added, reordered, and deleted from the admin panel
