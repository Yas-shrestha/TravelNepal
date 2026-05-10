# METHOD.md — UI Prototyping Method with AI Tools

## Purpose

Document the prompts, tools, and deliberate design decisions made during Phase 2 UI prototyping for the TravelNepal project. All three pages were generated as visual starting points using Google AI Studio, then critically reviewed and refined before implementation.

> **Note:** The AI-generated prototypes were used strictly as layout references. Every component in the final implementation was rebuilt from scratch in Blade + Livewire — no prototype code was copy-pasted into the project.

---

## Tool Used

**Google AI Studio** (aistudio.google.com) — Used for all three page prototypes due to its ability to generate full-page HTML layouts with responsive behaviour from detailed natural language prompts.

---

## Page 1 — Homepage (`/`)

### Prompt Used

```
Build a homepage for a Nepali adventure travel company called TravelNepal.
Theme: earthy adventure — deep forest green (#1a3a2a), warm stone beige (#f5f0e8),
ochre/amber accents (#c8860a). No purple gradients. No generic Tailwind cards.
Sections:
1. Full-width hero with a dramatic background, bold headline "Ride It. Trek It. Live It. Nepal.",
   one-line subheading, two CTA buttons: "Explore Trips" and "Plan My Trip"
2. Category highlights row — 5 cards: Motorbike Tours, Trekking, Cycling, Cultural Tours,
   Hikes — each with icon and trip count
3. Featured trips grid — 6 cards each showing cover image, category badge, trip title,
   duration, difficulty badge (colour coded), starting price in USD,
   "View Trip" and "Book Now" buttons
4. Why Choose Us — 4 icon + text blocks: Licensed Guides, Small Groups,
   Motorbikes & Gear Provided, 24/7 Support
5. Testimonials — 3 cards with avatar, name, location, star rating, quote
6. Full-width CTA banner: "Ready to Start Your Adventure?" with Browse Trips button
Fully responsive. Mobile hamburger nav.
Nav order: Logo, Home, About Us, Categories, All Trips, FAQ, Contact, Plan My Trip button.
IMPORTANT: Navbar must be fixed/sticky with background so it never overlaps content.
Hero section must be min 100vh with at least 80–100px top padding.
Both hero CTA buttons must be fully visible and stack on mobile.
```

### What the AI Generated

- A full homepage layout with all 6 sections present
- Solid green navbar from the start
- Hero height was correct but navbar overlapped the headline text
- Both CTA buttons were clipped on mobile view
- Testimonials were static 3-column grid — no interaction
- Footer social icons were broken (used icon font classes that did not load)
- A yellowish clay/beige background on the initial navbar state

### What I Changed and Why

**Navbar — Glassmorphism effect:**

> The AI generated a solid coloured navbar which felt heavy and covered the hero image. I changed it to a frosted glass effect (`rgba(255,255,255,0.08)` + `backdrop-filter: blur(16px)`) so the dramatic mountain background remains visible through the navbar on load. This is a deliberate design decision — premium travel websites use this pattern to keep the hero cinematic. The navbar transitions to solid `#1a3a2a` only after scrolling 80px.

**Hero layout — Full 100vh with image behind navbar:**

> The AI placed the hero below the navbar (100vh minus nav height). I changed this so the hero image fills the full 100vh and the glass navbar floats on top of it. This creates a more immersive first impression — the mountain fills the entire screen before any scrolling.

**Hero gradient overlay:**

> Added `linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.15) 40%, rgba(0,0,0,0.55) 100%)` — darkens the top and bottom while keeping the landscape visible in the middle. The AI's overlay was too dark and obscured the background image entirely.

**Mini About Us section:**

> The AI did not include this. I added a short two-column section just below the hero — a brief company introduction with stat badges (500+ Trips, 10+ Years, 98% Happy Travellers). This builds trust immediately after the hero before pushing trips.

**Testimonials — Carousel:**

> The AI generated a static 3-column grid. I changed this to a carousel that auto-advances every 4 seconds and shows navigation dots. On mobile it shows 1 card at a time. Static grids on mobile compress testimonial cards to an unreadable size — the carousel keeps them full-width and legible.

**Footer social icons:**

> The AI used generic icon class names that did not render. I replaced them with Font Awesome 6 CDN (`fab fa-facebook-f`, `fab fa-instagram`, `fab fa-tiktok`, `fab fa-youtube`) with explicit sizing and ochre hover states.

**Section heading for Categories:**

> The AI skipped a heading for the category row. I added "Explore by Category" with a subtitle for visual hierarchy consistency across all sections.

---

## Page 2 — Trip Listing (`/trips`)

### Prompt Used

```
Design System: Deep forest green (#1a3a2a), warm stone beige (#f5f0e8), ochre (#c8860a).
Must match homepage visual style.

Navbar: Same as homepage — fixed, frosted glass (rgba(255,255,255,0.08) +
backdrop-filter: blur(16px)) on load, transitions to solid #1a3a2a on scroll.
White text. Mobile hamburger with slide-in drawer.

Page Header: Heading "All Trips", subheading, breadcrumb: Home > Trips.

Filter Bar (sticky below header):
Category pills: All, Motorbike Tours, Trekking, Cycling, Cultural Tours, Hikes.
Difficulty dropdown: All, Easy, Moderate, Challenging, Extreme.
Duration dropdown: All, Under 7 days, 7–14 days, 14+ days.
Active filters shown as dismissible tags. "Reset Filters" link on the right.
Clean, not bulky — inline on desktop, scrollable on mobile.

Trip Card Grid:
3 columns desktop, 2 tablet, 1 mobile. 6 sample cards with realistic Nepal trip data.
Each card: cover image, category badge, difficulty badge (colour coded),
title, duration, best season, starting price in USD, "View Trip" and "Book Now" buttons.
Cards must NOT look generic — earthy styling, no plain white box shadows.

Empty state, pagination. Fully responsive. No purple gradients.
```

### What the AI Generated

- A clean listing layout with filter bar and card grid
- Page header was a plain text block — no visual weight
- Navbar was slightly inconsistent with the homepage (different blur value)
- No background image in the page header area
- Cards were mostly correct but used plain white backgrounds

### What I Changed and Why

**Page Banner with background image:**

> The AI gave a plain text header with just a heading and breadcrumb on a beige background. I changed this to a full-width banner with a mountain background image, dark gradient overlay, and the breadcrumb + title anchored to the bottom of the banner. This makes the listing page feel as premium as the homepage rather than a plain internal page. The banner goes behind the glass navbar — same pattern as the homepage — so there is visual continuity across pages.

**Navbar consistency:**

> The AI generated a slightly different blur value and transition timing. I corrected this to exactly match the homepage: `rgba(255,255,255,0.08)`, `blur(16px)`, transition at 80px scroll, `0.4s ease`.

**Banner stats pills:**

> Added "42 Adventures", "5 Categories", "All Difficulties" as small white pills inside the banner. This gives visitors immediate context about the catalogue size before they start filtering.

**Card styling:**

> Cards used plain white with generic drop shadows. I added earthy styling — warm stone backgrounds, subtle top border accent in the category colour, and an ochre price highlight — to keep the visual language consistent with the design system.

---

## Page 3 — Trip Detail (`/trips/{slug}`)

### Prompt Used

```
Build a complete trip detail page for TravelNepal.
Design System: Deep forest green (#1a3a2a), warm stone beige (#f5f0e8), ochre (#c8860a).
Playfair Display headings, Inter body. No purple gradients.

Navbar: Fixed, transparent on load with backdrop-filter: blur(16px), transitions to
solid #1a3a2a on scroll past 80px.

Page Banner: Height 420px, goes behind navbar. Full-width Nepal mountain image.
Gradient overlay rgba(0,0,0,0.2) to rgba(0,0,0,0.75). Breadcrumb inside banner.
Trip title in Playfair Display. Quick badges row: 14 Days | Challenging | Max 5,500m etc.

Two column layout: left 2/3 (overview, itinerary accordion, attractions, package table,
gallery, reviews carousel, FAQs, submit review), right 1/3 sticky facts card.

Package table: overflow-x auto, never clips vertically. Most Popular badge on Premium.
Book Now per column opens booking popup with package pre-selected.

Testimonials: carousel — 3 cards desktop, 1 card mobile, auto-advance 4s, dots + arrows.

FAQ: word-break: break-word, 48px min tap height, mobile font 0.9rem.

Submit Review: expandable inline form, clickable star rating, success message, no reload.

Booking Popup: triggered by all Book Now buttons, dark overlay, trip + package pre-filled,
success state inside popup, no page reload.

Sticky Book Now button: visible only on tablet and mobile (hidden on desktop — facts card
covers that use case). Full width at bottom on mobile under 480px.

Footer: same as other pages, 4 columns, Font Awesome social icons, ochre hover.
```

### What the AI Generated

- A detailed trip detail layout with most sections present
- Package comparison table was overflowing and top portion was clipped
- "What Travellers Say" was a static grid with no carousel
- Sticky Book Now button was showing on all screen sizes including desktop
- FAQ items were not optimised for small screens — text overflowed
- No review submission form
- Book Now buttons were plain links — no popup modal

### What I Changed and Why

**Package table overflow fix:**

> The AI did not wrap the table in an `overflow-x: auto` container. The top of the table was being clipped on smaller viewports. I added the wrapper, sufficient top padding on the section, and a "← Scroll to compare →" hint visible only on mobile.

**Traveller Reviews — Carousel:**

> The AI generated a static 3-column grid identical to the homepage, which does not work well in the context of a detail page where the left column is already narrow. Changed to a carousel matching the homepage testimonials pattern for visual consistency.

**Sticky Book Now button — tablet and mobile only:**

> The AI showed this button on all screen sizes. On desktop the sticky right facts card already has a prominent Book Now button — having two visible simultaneously is redundant and clutters the UI. I hid the floating button on screens 1024px and above, keeping it only for tablet and mobile where the facts card is not persistently visible.

**Booking Popup Modal:**

> The AI generated Book Now buttons as anchor links. I changed all of them to trigger a proper popup modal with trip name pre-filled, package pre-selected when opened from the comparison table, and a success state that stays inside the popup without reloading the page. This is the correct UX pattern for a booking flow — the visitor should never be taken away from the trip detail page.

**Submit a Review section:**

> The AI did not include this. I added an expandable inline form below the FAQ section with clickable star rating (stars fill ochre on selection), all required fields, and a thank-you success message. Reviews are stored with `is_approved = false` and appear only after admin approval — this prevents spam.

**FAQ mobile optimisation:**

> FAQ accordion items had fixed widths that caused text overflow on small screens. I added `word-break: break-word`, minimum 48px tap targets, and slightly reduced font size on mobile for comfortable reading without sacrificing information density.

---

## Summary of Deliberate Design Decisions

| Decision       | AI Output                  | My Refinement                                 | Reason                                       |
| -------------- | -------------------------- | --------------------------------------------- | -------------------------------------------- |
| Navbar on load | Solid green or clay colour | Frosted glass (`backdrop-filter: blur`)       | Keeps hero cinematic, premium feel           |
| Hero layout    | Below navbar               | Behind navbar, full 100vh                     | More immersive, standard travel site pattern |
| Page banners   | Plain text headers         | Image banner with gradient, breadcrumb inside | Visual continuity across all pages           |
| Testimonials   | Static grid                | Auto-advancing carousel                       | Better mobile UX, consistent across pages    |
| Social icons   | Broken / not loading       | Font Awesome 6 CDN with hover states          | Functional and styled correctly              |
| Book Now       | Anchor links               | Popup modal with pre-filled fields            | Correct booking UX, no page navigation loss  |
| Sticky button  | All screen sizes           | Tablet + mobile only                          | Avoids redundancy with desktop facts card    |
| Review form    | Not included               | Inline expandable with star rating            | Required feature per spec                    |
| Package table  | Clips on overflow          | `overflow-x: auto` wrapper                    | Prevents content loss on small screens       |
| Mini About Us  | Not included               | Added below hero on homepage                  | Trust signal before trip listings            |
