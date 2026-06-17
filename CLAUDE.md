# FADO Jewellery — Claude Code Project Brief

## What we are building

A Laravel 13 e-commerce website for **FADÓ Jewellery**, a fine Irish jewellery brand. Full migration away from their old OpenCart site. B2C only — B2B is explicitly deferred, do not build any B2B features.

**Agency:** Heaventree Digital  
**Dev lead:** Sean O'Byrne  
**Project manager:** Abitha  
**Client:** John Condron (FADÓ Jewellery)

---

## Stack

| Item | Detail |
|------|--------|
| Framework | Laravel 13 |
| PHP | 8.1+ |
| Database | MySQL 8 |
| Hosting | 20i — heaventree15.com (staging) |
| Web root | `/public` |
| GitHub repo | github.com/heaventree/fado-jewelry |
| Admin theme | **Larkon v1.2.0** — Laravel 13 Ecommerce Admin Dashboard (ThemeForest) |
| Front-end theme | **Ochaka** — Multipurpose eCommerce HTML5 Template (ThemeForest) |

---

## Two themes — how they work together

### Larkon (admin — already a Laravel app)
Larkon is a **full Laravel application skeleton**, not just HTML. It ships with:
- Blade views for: products (list, grid, create, edit, detail), orders (list, detail, cart, checkout), categories, customers, invoices, coupons, attributes, inventory, roles, settings
- Auth views: login, register, reset password, lock screen
- Layout partials: topbar, main-nav, footer, right-sidebar, head-css, footer-scripts
- A catch-all `RoutingController` that maps URL segments directly to Blade view paths
- Migrations for: users, cache, jobs

**The Larkon codebase is the starting point for the entire project.** Do NOT start from a blank Laravel install. Extend and build on top of Larkon. All admin routes must be prefixed `/admin` and protected by an `admin` middleware.

### Ochaka (front-end — HTML5 theme)
Ochaka is a static HTML/CSS/JS ecommerce theme. Convert its HTML pages into Laravel Blade templates stored in `resources/views/shop/`. Customer-facing routes live separately from admin routes. Style Ochaka to the FADO colour palette (see below).

---

## Colour palette

| Name | Hex | Usage |
|------|-----|-------|
| Light mint | #DCF6D5 | Highlights, hover states |
| Pale mint | #EBFCEF | Light backgrounds |
| Near white | #FBFBFB | Page background option |
| Off white | #F8F9F5 | Page background option |
| Soft white | #F5F7F1 | Section backgrounds |
| Cream | #F0F1E7 | Product tile backgrounds |
| Warm grey | #BCB3AB | Borders, subtle dividers |
| Brand green light | #81CC60 | Buttons, accents |
| Brand green mid | #0AAC45 | Primary CTA, links |
| Deep green | #044705 | Headers, logo text, nav |
| Gold accent | #766D42 | Premium touches, logo foil effect |

Background: white or off-white. Product tiles: cream or off-white. Accents: mint/mid-green. Gold used sparingly.

---

## Brand assets

### Main FADO brand
- Logo: FADÓ wordmark with macron on the O, ship icon mark above
- Tagline: Fine Irish Jewellery
- Packaging: deep green boxes (#044705), gold foil ship logo, cream/white interior, gold FADÓ wordmark

### The Jewellery Garden sub-brand (Garden Collection only)
- Separate logo: watercolour wildflower illustration (bluebells, red flowers, grasses, butterflies) with "The Jewellery Garden — FADŌ" typeset below in deep green
- This logo is used **only** on Garden Collection pages (Flora/Fauna subcategories)
- Do not use it as the main site logo

---

## Agreed scope — B2C only

### In scope
- Standard retail e-commerce: browse → product → cart → checkout
- Customer accounts + guest checkout
- Wishlist / favourites
- Book a consultation form (email/call request)
- EUR base currency, manual USD rate override (no live API), GeoIP region detection, future currency expansion
- Mega menu navigation (wide dropdowns)
- Collection/category pages with Sheila Fleet-style banners (title + text left, product image right, evocative background)
- Filters: product category, collection, metal, second metal/finish, gemstone, price range slider, colour
- 3–4 column product grid, clean spacing
- Product detail page: vertical thumbnail strip left, hero image carousel, up/down nav for overflow thumbnails
- Metal + gemstone + size selectable via dropdowns on ONE product page (variant selector, not separate URLs)
- US ring sizing dropdown
- About Us page: image-led, gallery layout
- Contact page with consultation booking form

### Out of scope (do not build)
- B2B trade login, tiered pricing, custom payment terms, multi-quantity per size ordering

### Critical SEO rule
**One page per product, variants on the same page.** The client originally wanted separate pages per metal type and gemstone. This was overruled — duplicate pages destroy SEO. Selecting a metal or gemstone variant must update the page (images, description, price) via JavaScript without navigating to a new URL. Never generate separate routes or pages per variant.

---

## Navigation structure

```
Jewellery
  └── Rings | Crosses | Pendants | Earrings | Bracelets/Bangles | Cufflinks | Brooches | Tie-tacks

Collections
  └── Claddagh | Corrib Claddagh | Trinity | An Rí | Livia | Sheelin
      High Crosses | Newgrange | Irish Folklore | Shamrock
      The Garden Collection by FADÓ

Wedding / Engagement
  └── The Garden Collection by FADÓ
      ├── Flora → Daisy | Wild Daisy | Bluebell | Forget Me Not | All
      └── Fauna → Butterfly | Bee | All

About Us
```

All top-level items with children use **wide mega menu dropdowns** (horizontal, not narrow vertical lists).  
Header layout: logo left-aligned, mega menu below it, cart/wishlist/login/search top right. Left of logo: Contact Us, Book an appointment.

---

## Database schema

Build these migrations from scratch (Larkon only ships users/cache/jobs):

```
products
  id, name, slug, description, short_description, is_active, created_at, updated_at

product_images
  id, product_id, path, sort_order, is_primary

product_variants
  id, product_id, metal_id, gemstone_id (nullable), sku, price_eur, stock, is_active

product_sizes (for rings)
  id, product_id, us_size, stock

metals
  id, name, slug (e.g. "10k-yellow-gold", "sterling-silver")

gemstones
  id, name, slug (e.g. "diamond", "emerald", "ruby")

categories
  id, name, slug, parent_id (nullable), sort_order, banner_image, banner_title, banner_description

collections
  id, name, slug, banner_image, banner_title, banner_description

category_product (pivot)
collection_product (pivot)

currencies
  id, code, name, rate, is_default, region_codes (JSON)

orders
  id, user_id (nullable — guest), status, subtotal, total, currency_code, shipping_address (JSON)

order_items
  id, order_id, product_id, variant_id, size_id (nullable), quantity, unit_price

wishlists
  id, user_id, product_id, variant_id

consultations
  id, name, email, phone, message, preferred_contact, created_at
```

---

## Seeder data to include

Write seeders for these so the DB is testable immediately:

**Metals:** Sterling Silver, 9ct Yellow Gold, 9ct White Gold, 9ct Rose Gold, 10ct Yellow Gold, 14ct Yellow Gold, 14ct White Gold, 18ct Yellow Gold, 18ct White Gold, Platinum, Two-tone (Yellow/White Gold)

**Gemstones:** Diamond, Emerald, Ruby, Sapphire, Cubic Zirconia, Amethyst, Aquamarine, Garnet, Opal, Pearl, Topaz

**Ring sizes (US):** 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5, 11, 12, 13

**Currencies:** EUR (default, rate 1.0, EU regions), USD (rate manually set, all other regions)

---

## Larkon views that already exist — extend, don't rebuild

These Blade views ship with Larkon. Adapt them for FADO rather than building from scratch:

| Existing view | FADO use |
|---------------|----------|
| `general/products/list` | Admin product list |
| `general/products/create` | Admin add product (extend for metals/gemstones/variants) |
| `general/products/edit` | Admin edit product |
| `general/products/detail` | Admin product detail |
| `general/products/grid` | Admin product grid view |
| `general/orders/list` | Admin order list |
| `general/orders/details` | Admin order detail |
| `general/orders/cart` | Admin cart view |
| `general/orders/checkout` | Admin checkout |
| `general/category/list` | Admin category list |
| `general/category/create` | Admin add category |
| `general/category/edit` | Admin edit category |
| `general/attributes/list` | Repurpose for metals/gemstones |
| `other/coupons-list` | Discount codes |
| `other/coupons-add` | Add discount code |
| `users/customer/list` | Customer list |
| `users/customer/details` | Customer detail |
| `general/settings` | Site settings, currency rates |
| `dashboards/index` | Main admin dashboard |

---

## Build order (phases)

### Phase 0 — OpenCart data export (before any dev work)
- Export from phpMyAdmin: `oc_product`, `oc_product_description`, `oc_product_image`, `oc_category`, `oc_category_description`, `oc_product_to_category`, `oc_url_alias`, `oc_information`, `oc_information_description`
- Save dump to `database/opencart-export.sql` (add to .gitignore — do not commit)
- Download all product images from old server to `_opencart-images/` (also gitignored)

### Phase 1 — Foundation
1. Database migrations (all tables above, including `opencart_id` on products)
2. Laravel models + relationships
3. Seeders (metals, gemstones, ring sizes, currencies)
4. Currency service (GeoIP region detection, manual rate lookup)
5. Artisan import command: `php artisan fado:import-opencart`

### Phase 2 — Admin panel (extend Larkon)
5. Extend product create/edit views to handle variants (metal + gemstone combinations)
6. Category management with banner image upload
7. Collection management with banner image upload
8. Currency admin (manual rate updates per currency)
9. Order management and status updates
10. Consultation enquiry inbox

### Phase 3 — Customer front end (Ochaka → Blade)
11. Global layout: header, mega menu, footer (`resources/views/shop/layouts/`)
12. Homepage: hero banner, featured collections
13. Collection/category listing pages with Sheila Fleet-style banner + filters
14. Product detail page: thumbnail strip, carousel, variant selectors
15. Cart and checkout (guest + account)
16. Wishlist

### Phase 4 — Supporting pages
17. About Us
18. Contact / Book a consultation
19. Customer account area (orders, wishlist, profile)

### Phase 5 — Polish + SEO
20. Per-collection hero imagery
21. Clean slugged URLs, meta titles/descriptions per product and category
22. XML sitemap

### Phase 6 — QA + launch
23. Cross-device testing
24. Client UAT
25. DNS, SSL, production deploy on 20i

---

## OpenCart data migration

### Background
The client ran an OpenCart site for approximately 20 years. It went offline 2–3 months ago due to PHP version incompatibility (OpenCart required an upgrade that wasn't worth the cost, so the decision was made to rebuild in Laravel instead).

### What to migrate
- **Products** — names, descriptions, prices, images, categories ✅
- **Page content** — any static pages (About Us, etc.) ✅
- **Orders** — do NOT migrate, too old and site has been offline ❌
- **Customer accounts** — do NOT migrate ❌

### How to access the data
We have **phpMyAdmin access** to the old OpenCart database. Export the relevant tables as a MySQL dump before beginning migration work.

### Key OpenCart tables to export
```
oc_product                 — core product data (product_id, model, price, status)
oc_product_description     — product names and descriptions (per language)
oc_product_image           — additional product images
oc_category                — category structure
oc_category_description    — category names and descriptions
oc_product_to_category     — product↔category relationships
oc_url_alias               — existing SEO URLs (useful for redirects)
```

### Migration approach
Write a Laravel Artisan command `php artisan fado:import-opencart` that:
1. Reads from the exported OpenCart MySQL dump (placed at `database/opencart-export.sql`)
2. Or connects directly to the old DB via a second DB connection defined in `config/database.php` as `opencart`
3. Maps OpenCart product fields to the new FADO schema
4. Creates Product, ProductVariant (defaulting to Sterling Silver if no metal data exists), and ProductImage records
5. Maps OpenCart categories to the new categories table
6. Downloads/copies product images to `storage/app/public/products/`
7. Logs skipped or failed records to `storage/logs/opencart-import.log`

### OpenCart → FADO field mapping
```
oc_product.product_id        → products.opencart_id (add this column for reference)
oc_product_description.name  → products.name
oc_product_description.description → products.description
oc_product.price             → product_variants.price_eur
oc_product.model             → product_variants.sku
oc_product.status (1=active) → products.is_active
oc_product_image.image       → product_images.path
oc_url_alias.keyword         → products.slug (reuse old slugs where possible for SEO)
```

### Add to products table migration
```
opencart_id unsignedInteger nullable -- for traceability post-migration
```

### SEO redirects
Export `oc_url_alias` and create a `redirects` table or use Laravel's redirect middleware to map old OpenCart URLs (`/index.php?route=product/product&product_id=X` and `/old-slug`) to new Laravel URLs (`/products/new-slug`). This preserves any existing Google rankings.

### Page content migration
Check OpenCart's `oc_information` and `oc_information_description` tables for static page content (About Us etc.) and manually port that text into the new Laravel pages — don't automate this, it needs a human review.

---

## Laravel conventions to follow

- Resourceful controllers (`php artisan make:controller --resource`)
- Eloquent relationships — no raw queries
- Named routes throughout
- Blade templates extend a master layout
- Laravel Vite for asset compilation
- Product images: `storage/app/public/products`, symlinked to `public/storage`
- Admin routes: prefixed `/admin`, protected by `admin` middleware
- Shop routes: prefixed `/` (root), no auth required except account/wishlist
- Form validation via Request classes
- Use Laravel's built-in auth scaffolding for customer accounts
