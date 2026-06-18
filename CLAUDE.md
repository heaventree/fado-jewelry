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

### Larkon (admin)
Larkon is a full Laravel application skeleton. The Larkon codebase is the starting point. Do NOT start from a blank Laravel install. All admin routes prefixed `/admin`, protected by `admin` middleware.

### Ochaka (front-end)
Static HTML/CSS/JS ecommerce theme. Converted to Laravel Blade templates in `resources/views/shop/`. Styled to FADO colour palette.

---

## Colour palette

| Name | Hex | Usage |
|------|-----|-------|
| Light mint | #DCF6D5 | Highlights, hover states |
| Pale mint | #EBFCEF | Light backgrounds |
| Near white | #FBFBFB | Page background |
| Off white | #F8F9F5 | Page background option |
| Soft white | #F5F7F1 | Section backgrounds |
| Cream | #F0F1E7 | Product tile backgrounds |
| Warm grey | #BCB3AB | Borders, dividers |
| Brand green light | #81CC60 | Buttons, accents |
| Brand green mid | #0AAC45 | Primary CTA, links |
| Deep green | #044705 | Headers, logo, nav |
| Gold accent | #766D42 | Premium touches, logo foil |

All 11 CSS variables defined in `public/css/fado.css` as `--fado-*` custom properties.

---

## Brand assets

### Main FADO brand
- Logo: FADÓ wordmark with macron on the O, ship icon mark above
- Tagline: Fine Irish Jewellery
- Packaging: deep green boxes (#044705), gold foil ship logo, cream interior, gold FADÓ wordmark

### The Jewellery Garden sub-brand (Garden Collection only)
- Watercolour wildflower illustration logo — used ONLY on Garden Collection pages
- Do not use as the main site logo

---

## Agreed scope — B2C only

### In scope (all built)
- Standard retail e-commerce: browse → product → cart → checkout
- Customer accounts + guest checkout
- Wishlist / favourites
- Book a consultation form
- EUR/USD currency with GeoIP detection and manual rate override
- Mega menu navigation (wide dropdowns)
- Collection/category pages with Sheila Fleet-style banners
- Filters: product category, collection, metal, second metal/finish, gemstone, price range dual slider, colour
- 3–4 column product grid
- Product detail page: vertical thumbnail strip, hero carousel, variant selectors
- Metal + gemstone + size on ONE page (no separate URLs per variant — SEO rule)
- US ring sizing dropdown
- About Us page (image-led)
- Contact page with consultation booking

### Out of scope
- B2B — explicitly deferred, do not build

### Critical SEO rule
One page per product. Variant selection (metal/gemstone) updates the page via JavaScript — never navigates to a new URL.

---

## Navigation structure

```
Jewellery → Rings | Crosses | Pendants | Earrings | Bracelets/Bangles | Cufflinks | Brooches | Tie-tacks
Collections → Claddagh | Corrib Claddagh | Trinity | An Rí | Livia | Sheelin | High Crosses | Newgrange | Irish Folklore | Shamrock | The Garden Collection
Wedding/Engagement → The Garden Collection → Flora (Daisy, Wild Daisy, Bluebell, Forget Me Not) | Fauna (Butterfly, Bee)
About Us
```

Wide mega menu dropdowns. Logo left, nav right, cart/wishlist/login/search top right.

---

## Database schema (all migrations complete — 16+ tables)

```
products, product_images, product_variants (includes second_metal_id, colour),
product_sizes, metals, gemstones, categories (self-referential),
collections, category_product (pivot), collection_product (pivot),
currencies, orders (nullable user_id for guest), order_items,
wishlists, consultations, redirects, coupons, settings
```

---

## Build status — complete summary

### Phase 1 — Foundation ✅ COMPLETE
- ✅ 16 database migrations
- ✅ 12 Eloquent models with full relationships
- ✅ Seeders: metals (11), gemstones (11), ring sizes (US), currencies (EUR/USD)
- ✅ CurrencyService — GeoIP detection (ip-api.com), session override, manual rate
- ✅ `php artisan fado:import-opencart` — full OpenCart migration command with dry-run, image download, SEO redirects

### Phase 2 — Admin panel ✅ COMPLETE
- ✅ Step 1: Product management — full CRUD with variants (metals/gemstones), images, sizes
- ✅ Step 2: Category management — CRUD with banner image upload and live preview
- ✅ Step 3: Collection management — CRUD with banner image upload
- ✅ Step 4: Currency admin — manual rate updates, region toggle, conversion preview
- ✅ Step 5: Order management — list, detail, status timeline, inline status update
- ✅ Step 6: Consultation inbox — list, detail, read/unread tracking, nav badge
- ✅ Step 7: Dashboard — real data (products, orders, revenue, consultations, top products)
- ✅ Step 8: Customers — list with lifetime value, detail with order history
- ✅ Step 9: Inventory — stock editing with low-stock alerts and badges
- ✅ Step 10: Invoices — list, printable A4 invoice with @media print
- ✅ Step 11: Coupons — full CRUD, fixed/percent types, expiry, usage limits
- ✅ Step 12: Seller/vendor sections removed — nav cleaned up
- ✅ Step 13: Roles/permissions — Spatie setup (in progress)
- ✅ Step 14: Settings page — full settings admin (see settings spec below)

### Phase 3 — Customer front end ✅ COMPLETE
- ✅ Step 1: Global layout — header, mega menu, footer, mobile menu, FADO CSS variables
- ✅ Step 2: Homepage — hero slider, trust strip, category carousel, featured collections, new arrivals, Garden Collection spotlight, consultation CTA
- ✅ Step 3: Collection/category listing pages — Sheila Fleet banners, all 7 filters, 3-4 col grid, mobile offcanvas filters, sort toolbar, filter pills
- ✅ Step 3 fix: Added missing filters — collection, second metal/finish, colour
- ✅ Step 4: Product detail page — vertical thumbnail strip, hero carousel, up/down nav, metal/gemstone/size dropdowns, JS variant switcher (no URL change), related products
- ✅ Step 5: Cart + checkout — CartService (session), guest + account checkout, COD payment, order confirmation page, currency switcher
- ✅ Step 6: Wishlist — WishlistService (guest session + DB), toggle on product/listing pages, wishlist page, guest→DB merge on login, header badge

### Phase 4 — Supporting pages ✅ COMPLETE
- ✅ Step 1: About Us — hero, story, values strip, collection gallery, craft process, consultation CTA
- ✅ Step 2: Contact/Book consultation — contact info from settings, general enquiry + consultation forms, consultation_enabled gate
- ✅ Step 3: Customer account area — dashboard, order history, order detail, profile edit, password change
- ✅ Step 4: Search — full-text search (name/description), ranked results, product grid, pagination

### Phase 5 — SEO + polish ✅ COMPLETE
- ✅ Meta tags on every page (title, description, OG, Twitter Card, canonical)
- ✅ noindex on cart, checkout, account, confirmation pages
- ✅ Google Analytics injection (production only, from settings)
- ✅ Redirect middleware — catches OpenCart URLs (/index.php?route=...), checks redirects table
- ✅ XML sitemap at /sitemap.xml (products, categories, collections, static pages)
- ✅ robots.txt blocking admin/cart/checkout/account

### Phase 6 — QA + launch prep ✅ COMPLETE
- ✅ Seller view files deleted
- ✅ robots.txt
- ✅ 404 and 500 error pages (FADO-branded, standalone — no layout dependency)
- ✅ CSRF on all forms (verified)
- ✅ Rate limiting on contact/consultation form (5/min per IP)
- ⏳ User roles/permissions (Spatie — in progress)

---

## Pending / outstanding items

| Item | Notes |
|------|-------|
| User roles/permissions | Spatie setup — currently running in Claude Code |
| OpenCart DB export | Get from cPanel/phpMyAdmin — needed for product migration |
| OpenCart product images | Download from old server via cPanel |
| Run migrations on 20i | `php artisan migrate --seed` — once deployed to staging |
| Run storage:link on 20i | `php artisan storage:link` |
| Stripe integration | Waiting for client to provide API keys |
| Payment method decision | Stripe vs PayPal — confirm with John |
| Product reviews | Keep or remove — decision pending with client |
| Cross-device testing | Phase 6 QA — after staging deploy |
| Client UAT | John signs off |
| DNS switch + go live | Final step |

---

## Server deployment checklist (20i — heaventree15.com)

When deploying to staging, run in this order:
```
1. git pull
2. composer install --no-dev
3. php artisan migrate --seed
4. php artisan db:seed --class=SettingsSeeder
5. php artisan db:seed --class=RolesAndPermissionsSeeder
6. php artisan db:seed --class=AdminUserSeeder
7. php artisan storage:link
8. php artisan config:cache
9. php artisan route:cache
```

Set these in `.env` on the server:
```
APP_URL=https://heaventree15.com
APP_ENV=production
APP_DEBUG=false
ADMIN_NAME=
ADMIN_EMAIL=
ADMIN_PASSWORD=
OC_DB_HOST= (OpenCart DB for migration)
OC_DB_DATABASE=
OC_DB_USERNAME=
OC_DB_PASSWORD=
```

---

## User roles and permissions (Spatie Laravel Permission)

| Role | Access |
|------|--------|
| `super_admin` | Everything |
| `store_admin` | Full admin except roles management |
| `staff` | Orders, consultations, inventory, invoices only |
| `customer` | Shop front end and account area only |

- EnsureAdmin middleware checks `hasRole(['super_admin', 'store_admin', 'staff'])`
- Staff sees restricted nav — no products/categories/collections/currencies/settings/coupons
- Admin credentials from `.env`: `ADMIN_EMAIL`, `ADMIN_PASSWORD`, `ADMIN_NAME` — never hardcoded

---

## Admin settings — all complete ✅

All use `Setting::get('key')` — never hardcoded.

| Group | Keys |
|-------|------|
| Store identity | store_name, store_tagline, store_email, store_phone, store_address, orders_email, consultation_email, meta_title, meta_description, google_analytics_id, facebook_url, instagram_url, twitter_url, maintenance_mode |
| Payments | cod_enabled, payment_method_label, stripe_publishable_key, stripe_secret_key |
| Shipping | free_shipping_threshold, shipping_rate_ireland, shipping_rate_international, shipping_notice |
| Display | products_per_page, new_arrivals_count, wishlist_enabled, reviews_enabled |
| Orders | order_email_from_name, order_email_from_address, low_stock_threshold |
| Consultation | consultation_enabled, consultation_intro_text |

---

## OpenCart migration

### What to migrate
- Products, descriptions, images, categories ✅
- Orders — do NOT migrate ❌
- Customer accounts — do NOT migrate ❌

### How to run
```bash
# Dry run first
php artisan fado:import-opencart --dry-run

# Full import
php artisan fado:import-opencart --image-base-url=https://old.fadojewellery.com/image/
```

Set OC_DB_* variables in .env to connect to the old OpenCart database.

---

## Core principles

### Nothing gets skipped
Both admin and front end must be fully functional. Every client requirement must be built. Every Larkon section must be wired to real data.

### Strictly no hardcoding
Every configurable value must come from `Setting::get()`, the database, or `.env`. Never hardcode shipping rates, thresholds, counts, labels, emails, or any value the store admin might want to change.

### If in doubt — make it a setting.

---

## Laravel conventions

- Resourceful controllers, Eloquent relationships, named routes
- Blade templates extend master layout
- Product images: `storage/app/public/products`, symlinked to `public/storage`
- Admin routes: `/admin` prefix, `admin` middleware
- Shop routes: `/` prefix, no auth except account/wishlist
- Form validation via Request classes
- No raw queries
