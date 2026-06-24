# FADÓ Jewellery — Full Project Audit
**Project:** heaventree15.com | Laravel 12 + Ochaka Theme  
**Date:** June 2026  
**Scope:** All Blade templates (frontend) + Admin panel (backend)

---

## Summary

### Frontend
| Status | Count |
|--------|-------|
| ✅ Fully dynamic | 14 templates / sections |
| ⚠️ Partially dynamic | 8 templates with mixed content |
| ❌ Issues requiring fix | 27 individual items |

### Backend / Admin
| Status | Count |
|--------|-------|
| ✅ Working | Products, Orders, Customers, Settings, Coupons, Currencies, Inventory, Invoices, Consultations |
| ❌ Issues requiring fix | 23 individual items |

---

## Page-by-Page Audit

### 🏠 Homepage (`home.blade.php`)

**Dynamic ✅**
- Categories carousel — from DB
- Featured collections — from DB
- New arrivals / best sellers / on sale — from DB
- Coupon banner — from DB (real code / value / expiry)
- Featured product — `Setting::get('featured_product_id')`
- Free shipping notice — `Setting::get()`

**Hardcoded ❌**

| # | Item | Fix |
|---|------|-----|
| 1 | Hero slider images — `slider-22/23/24.jpg` | Admin-managed slider table |
| 2 | Hero slider text — "Irish Claddagh Rings", etc. | DB / Settings |
| 3 | Testimonials — fake names, fake products, lorem text | `testimonials` DB table |
| 4 | Blog section — Ochaka demo content | `posts` DB table or remove section |
| 5 | Instagram gallery — demo images, dead links | Live integration or remove section |
| 6 | 3 section subtitles — "Up to 50% off Lorem ipsum…" | `Setting::get()` |
| 7 | Trust strip labels — "30 days return", etc. | `Setting::get()` |
| 8 | Featured product star rating — hardcoded 5 stars | Real review system or remove |
| 9 | Sale banner image — hardcoded path | `Setting::get()` or media upload |

---

### 🔝 Header (`partials/header.blade.php`)

**Dynamic ✅**
- Logo, nav categories/collections, currency, cart/wishlist counts, auth links

**Hardcoded ❌**

| # | Item | Fix |
|---|------|-----|
| 10 | Tagline "Fine Irish Jewellery" | `Setting::get('store_tagline')` |

---

### 🔻 Footer (`partials/footer.blade.php`)

**Dynamic ✅**
- Store name/address/phone/email, social links, copyright year, newsletter form

**Hardcoded ❌**

| # | Item | Fix |
|---|------|-----|
| 11 | Newsletter intro text | `Setting::get('newsletter_intro')` |

---

### 🧭 Nav / Mobile Menu / Cart Drawer

**Dynamic ✅** — categories, collections, products, currency, cart items, routes. No issues.

---

### 📋 Listing (`listing.blade.php`)

**Dynamic ✅** — title, banner, filters, products, pagination, breadcrumbs. No issues.

---

### 💎 Product (`product.blade.php`)

**Dynamic ✅**
- All product data, variants, images, prices, stock, related products, viewing count, recent buyers

**Hardcoded ❌**

| # | Item | Fix |
|---|------|-----|
| 12 | Delivery tab text — "Orders dispatched within 2-3 working days…" | `Setting::get('delivery_info')` |
| 13 | Returns tab text — "30-day return policy…" | `Setting::get('returns_policy')` |

---

### 🛒 Cart (`cart.blade.php`)

**Dynamic ✅** — items, prices, free shipping threshold, CSRF. No issues.

---

### 💳 Checkout (`checkout.blade.php`)

**Dynamic ✅**
- Shipping rates, payment methods, saved addresses, cart items

**Hardcoded ❌**

| # | Item | Fix |
|---|------|-----|
| 14 | Country list — only 12 countries | Full ISO list or `countries` DB table |
| 15 | T&C link — no href, placeholder text only | `Setting::get('terms_url')` + real link |

---

### ❤️ Wishlist (`wishlist.blade.php`)

**Dynamic ✅** — No issues.

---

### 🗂️ Collections (`collections.blade.php`)

**Dynamic ✅** — No issues.

---

### ℹ️ About (`about.blade.php`)

**Dynamic ✅**
- Store name/phone/email/address — `Setting::get()`

**Hardcoded ❌**

| # | Item | Fix |
|---|------|-----|
| 16 | Hero image | `Setting::get('about_hero_image')` |
| 17 | Hero heading | `Setting::get('about_heading')` |
| 18 | Brand story text — all body paragraphs | `Setting::get('about_story')` or CMS field |
| 19 | Gallery images — Ochaka demo paths | `Setting::get()` or media manager |
| 20 | Craft values strip | `Setting::get()` fields per value |

---

### 📬 Contact (`contact.blade.php`)

**Dynamic ✅**
- Store info, social links, consultation text, form

**Hardcoded ❌**

| # | Item | Fix |
|---|------|-----|
| 21 | Google Maps iframe — generic Dublin coordinates | `Setting::get('store_lat')` / `store_lng` |

---

### ❓ FAQ (`faq.blade.php`)

**Hardcoded ❌** (entirely hardcoded)

| # | Item | Fix |
|---|------|-----|
| 22 | All 15 Q&As | Create `faqs` DB table + admin CRUD |
| 23 | Sidebar banner image | `Setting::get('faq_banner_image')` |

---

### 🔒 Privacy (`coming-soon.blade.php`)

**Dynamic ✅**
- Store name and email — `Setting::get()`

**Hardcoded ❌**

| # | Item | Fix |
|---|------|-----|
| 24 | Entire privacy policy text | `Setting::get('privacy_policy')` or CMS field |

---

### ✅ Order Confirmation

**Dynamic ✅** — all from `$order` model. No issues.

---

### 👤 Account Pages (dashboard, orders, order-detail, addresses, profile)

**Dynamic ✅**
- Orders, addresses, profile, avatar, CRUD forms — all dynamic

**Hardcoded ❌**

| # | Item | Fix |
|---|------|-----|
| 25 | All page `<title>` tags — hardcoded "FADÓ Jewellery" | `Setting::get('store_name')` |

---

### 🔑 Auth Pages (login, register)

**Dynamic ✅**
- Forms — CSRF, correct routes, validation

**Hardcoded ❌**

| # | Item | Fix |
|---|------|-----|
| 26 | Page `<title>` tags — hardcoded "FADÓ Jewellery" | `Setting::get('store_name')` |
| 27 | Login "New Customer" copy — hardcoded store name | `Setting::get('store_name')` |

---

---

## Backend / Admin Audit

### 🛍️ Products Admin

**Working ✅**
- Create/edit with name, slug, description, short_description, categories, collections, images
- Variants (metal, gemstone, sku, price, stock)
- Category banner image upload
- Collection banner image upload

**Missing ❌**

| # | Item | Fix |
|---|------|-----|
| B1 | `is_bestseller` toggle — field in model/migration, not in form, not saved by controller | Add checkbox to product edit form + save in controller |
| B2 | `sale_price_eur` — field on `ProductVariant`, not in form, not saved | Add input per variant row + save in controller |
| B3 | `second_metal_id` — field on `ProductVariant`, not in form, not saved | Add select per variant row + save in controller |
| B4 | `colour` — field on `ProductVariant`, not in form, not saved | Add input per variant row + save in controller |

---

### 📦 Orders Admin

**Working ✅** — list with search/filter/stat cards, detail view, status update dropdown. No issues.

---

### 👥 Customers Admin

**Working ✅**
- List with search and lifetime value
- Detail with order history

**Missing ❌**

| # | Item | Fix |
|---|------|-----|
| B5 | Address history — not loaded or displayed in customer detail | Eager-load `addresses` in `CustomerController::show()`, render in view |

---

### ⚙️ Settings Admin

**Working ✅** — all `Setting::get()` keys have matching admin inputs. No orphaned keys.

---

### 🗂️ Admin Sidebar

**Working ✅** — Dashboard, Products, Categories, Collections, Orders, Customers, Consultations, Coupons, Currencies, Settings, Inventory, Invoices — all linked and wired.

**Missing ❌**

| # | Item | Fix |
|---|------|-----|
| B6 | Roles & Permissions — links to static Larkon template, no real controller or Spatie logic | Wire `spatie/laravel-permission` or remove link until implemented |

---

### 🏷️ Attributes Management

**Missing ❌** (stub views exist, no routes/controllers wired)

| # | Item | Fix |
|---|------|-----|
| B7 | Metals — no admin CRUD, only seeded | Create `MetalController` with index/create/edit/delete + wire routes |
| B8 | Gemstones — no admin CRUD, only seeded | Create `GemstoneController` with full CRUD + wire routes |
| B9 | Ring Sizes — no admin CRUD, hardcoded in seeder | Create `RingSizeController` with full CRUD + wire routes |
| B10 | Colours — no management at all, no seeder | Create `colours` table + model + `ColourController` |

---

### ⭐ Reviews

**Missing ❌** (entirely absent)

| # | Item | Fix |
|---|------|-----|
| B11 | No `reviews` table or model | Create migration + `Review` model (`product_id`, `user_id`, `rating`, `body`, `approved`) |
| B12 | No customer review submission on product pages | Add review form to `product.blade.php` + `ReviewController::store()` |
| B13 | No admin review management | Create admin reviews index with approve/delete actions |
| B14 | `reviews_enabled` setting exists but unused anywhere | Wire setting to show/hide review form and display on product page |

---

### 📄 CMS Pages

**Missing ❌** (no CMS system exists)

| # | Item | Fix |
|---|------|-----|
| B15 | FAQ — hardcoded in Blade, no admin editor | Create `faqs` table + admin CRUD (also fixes frontend #22) |
| B16 | Privacy Policy — hardcoded, no admin editor | Add `privacy_policy` to Settings admin (long textarea) |
| B17 | Terms & Conditions — no route, no view, no admin editor | Add `terms_conditions` to Settings admin + create `terms.blade.php` |
| B18 | About Us — hardcoded, no admin editor | Add about fields to Settings admin (also fixes frontend #16–20) |
| B19 | No CMS/Page model or admin page editor | Consider a lightweight `pages` table (slug, title, body) for future expandability |

---

### 🧭 Menu Management

**Missing ❌**

| # | Item | Fix |
|---|------|-----|
| B20 | No admin menu editor — header/footer nav hardcoded in Blade partials | Low priority; wire from categories/collections (already done) or add `nav_links` Setting as JSON |

---

### 📧 Emails / Notifications

**Missing ❌** (no Mail or Notification classes exist)

| # | Item | Fix |
|---|------|-----|
| B21 | No order confirmation email | Create `OrderConfirmed` Mailable, dispatch from `OrderController::store()` |
| B22 | No consultation notification email | Create `ConsultationReceived` Mailable, dispatch from consultation form handler |

---

### 📋 CLAUDE.md Gaps

Items confirmed in DB but not documented in CLAUDE.md:

| # | Item | Action |
|---|------|--------|
| B23 | `is_bestseller` — exists in DB, not admin-editable | Fix admin (B1) then update CLAUDE.md |
| B24 | `sale_price_eur` — exists in DB, not admin-editable | Fix admin (B2) then update CLAUDE.md |
| B25 | Mega menu category images — no separate `menu_image` field, only `banner_image` | Decide: reuse `banner_image` or add `menu_image` column; document the decision |

---

## Grouped Fix Plan

### Priority 1 — Quick wins: admin form fields (no new tables)
Backend-only changes. Add missing fields to existing product/variant form and save in controller.

- `is_bestseller` checkbox on product edit (B1)
- `sale_price_eur`, `second_metal_id`, `colour` per variant row (B2–B4)
- Customer address history in detail view (B5)

### Priority 2 — Quick wins: Settings substitutions (no new DB tables)
Single `Setting::get()` swaps, batch in one frontend pass.

- Header tagline (F10)
- Footer newsletter intro (F11)
- Product delivery + returns tabs (F12–13)
- Homepage section subtitles × 3 (F6)
- Homepage trust strip labels (F7)
- Homepage sale banner image (F9)
- About page — hero image, heading, story, gallery, craft values (F16–20)
- Contact map coordinates (F21)
- FAQ sidebar banner (F23)
- Privacy policy text (F24) — add long textarea to Settings admin (B16)
- Terms & Conditions — add to Settings admin + create view (B17)
- About fields — add to Settings admin (B18)
- All account + auth page titles and copy (F25–27)
- T&C link URL (F15)

### Priority 3 — New DB tables required

| Table | Feeds | Ref |
|-------|-------|-----|
| `sliders` | Homepage hero slider images + text | F1–2 |
| `testimonials` | Homepage testimonials section | F3 |
| `faqs` | FAQ page Q&As + admin CRUD | F22, B15 |
| `reviews` | Product star ratings, admin moderation | F8, B11–14 |
| `posts` | Blog section | F4 — or remove section |

### Priority 4 — Backend systems (new controllers/routes)

- Attributes CRUD — Metals, Gemstones, Ring Sizes, Colours (B7–10)
- Order confirmation email — `OrderConfirmed` Mailable (B21)
- Consultation notification email — `ConsultationReceived` Mailable (B22)
- Roles & Permissions — wire Spatie or remove sidebar link (B6)

### Priority 5 — External integrations or deferred

- Instagram gallery (F5) — live API or remove section
- Country list (F14) — full ISO list in Blade
- Menu management (B20) — low priority, nav already driven by categories/collections
- `menu_image` vs `banner_image` for mega menu (B25) — decision required before implementing

---

## Settings Keys Reference

Proposed keys for `settings` table (where not already defined):

```
store_tagline
newsletter_intro
delivery_info
returns_policy
about_hero_image
about_heading
about_story
about_gallery_images   (JSON array)
about_craft_values     (JSON array)
faq_banner_image
privacy_policy         (long text)
terms_url
store_lat
store_lng
homepage_subtitle_1
homepage_subtitle_2
homepage_subtitle_3
trust_strip            (JSON array of labels)
sale_banner_image
```

---

*Last updated: June 2026 — FADÓ Jewellery / Heaventree Design*  
*Frontend issues prefixed F, backend issues prefixed B*
