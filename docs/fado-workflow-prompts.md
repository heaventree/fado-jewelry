# FADÓ Jewellery — Claude Code Workflow Prompts
**Project:** heaventree15.com | Laravel 12  
**Repo:** `heaventree/fado-jewelry`  
**Rules:** Always follow FRONTEND_RULES.md and WORKFLOW.md before touching any file.

Each block below is one Claude Code session. Copy-paste the whole block. Do them in order within each section — later groups may depend on earlier ones.

---

# ═══════════════════════════════════
# FRONTEND
# ═══════════════════════════════════

---

## FE-1 · Global Titles, Header Tagline, Footer Intro, Auth Copy

**Files touched:** `partials/header.blade.php`, `partials/footer.blade.php`, `auth/login.blade.php`, `auth/register.blade.php`, all account Blade files (dashboard, orders, order-detail, addresses, profile), `layouts/app.blade.php` (if titles are set there)

**Why grouped:** All are single `Setting::get()` substitutions with zero logic — pure string swaps across shared partials and auth pages. Safest batch to start with; none of these files contain dynamic logic that could break.

---

```
Read FRONTEND_RULES.md and WORKFLOW.md first. Do not deviate from them.

You are fixing hardcoded strings across partials and auth/account pages. These are pure string substitutions — do NOT restructure, restyle, or refactor anything else in these files.

TASKS:

1. partials/header.blade.php
   - Find the hardcoded tagline text "Fine Irish Jewellery"
   - Replace with: {{ Setting::get('store_tagline', 'Fine Irish Jewellery') }}

2. partials/footer.blade.php
   - Find the hardcoded newsletter intro/description text
   - Replace with: {{ Setting::get('newsletter_intro', 'Subscribe for exclusive offers and new arrivals.') }}

3. auth/login.blade.php and auth/register.blade.php
   - Find every hardcoded "FADÓ Jewellery" in <title> tags and body copy
   - Replace with: {{ Setting::get('store_name', 'FADÓ Jewellery') }}
   - On login page: find the "New Customer" section where the store name is hardcoded in the paragraph text — replace that instance too

4. All account pages: dashboard.blade.php, orders.blade.php, order-detail.blade.php, addresses.blade.php, profile.blade.php
   - Find every hardcoded "FADÓ Jewellery" in <title> tags
   - Replace with: {{ Setting::get('store_name', 'FADÓ Jewellery') }}

RULES:
- Use the fallback value (second argument to Setting::get) so the page still renders if the setting is missing
- Only touch the specific hardcoded strings listed — do not alter surrounding HTML, classes, or structure
- After all changes: git add -A && git commit -m "FE-1: Replace hardcoded store name/tagline/newsletter intro with Setting::get()"
- Then run: git push origin main
```

---

## FE-2 · Product Page Tabs + Checkout T&C + Country List

**Files touched:** `product.blade.php`, `checkout.blade.php`

**Why grouped:** Both are single-page fixes with no shared dependencies. The product tab text and the checkout country/T&C are independent of each other but both are quick in-blade substitutions — cleaner to knock out in one session than two.

---

```
Read FRONTEND_RULES.md and WORKFLOW.md first.

You are fixing three hardcoded blocks across two pages. Do NOT touch anything else on these pages — no layout changes, no JS, no CSS.

TASKS:

1. product.blade.php — Delivery tab
   - Find the hardcoded delivery information text (something like "Orders dispatched within 2-3 working days…")
   - Replace the entire text content with: {!! nl2br(e(Setting::get('delivery_info', 'Orders dispatched within 2–3 working days. Free standard delivery on orders over €50.'))) !!}

2. product.blade.php — Returns tab
   - Find the hardcoded returns policy text ("30-day return policy…")
   - Replace with: {!! nl2br(e(Setting::get('returns_policy', '30-day return policy on all unworn items. See our full returns policy for details.'))) !!}

3. checkout.blade.php — Country dropdown
   - Find the hardcoded <option> list (currently only ~12 countries)
   - Replace with a full list of countries commonly used for e-commerce. Include at minimum: Ireland, United Kingdom, United States, Canada, Australia, New Zealand, Germany, France, Spain, Italy, Netherlands, Belgium, Sweden, Norway, Denmark, Finland, Switzerland, Austria, Portugal, Poland, Czech Republic, Hungary, Romania, Bulgaria, Croatia, Slovakia, Slovenia, Greece, Cyprus, Malta, Luxembourg, Estonia, Latvia, Lithuania, Japan, Singapore, UAE, South Africa
   - Keep Ireland as the first/default option (selected), then alphabetical after

4. checkout.blade.php — T&C link
   - Find the Terms & Conditions text that currently has no href
   - Change the anchor to: <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a>
   - Note: the terms route will be created in a later backend task. For now just set the href correctly — it will 404 until BE-3 is done.

After all changes: git add -A && git commit -m "FE-2: Dynamic delivery/returns tabs, full country list, T&C link"
Then: git push origin main
```

---

## FE-3 · Homepage Hardcoded Sections (Settings-based fixes only)

**Files touched:** `home.blade.php`

**Why grouped:** All homepage fixes that only require `Setting::get()` — no new tables, no new Blade loops. The slider, testimonials, blog, and Instagram sections are NOT touched here (they need new DB tables — those are FE-5). This group is strictly the subtitle text, trust strip, sale banner, and removing the hardcoded star rating.

---

```
Read FRONTEND_RULES.md and WORKFLOW.md first.

You are making targeted fixes to home.blade.php only. Do NOT touch the hero slider section, testimonials section, blog section, or Instagram gallery section — those are handled separately. Only fix the items listed below.

TASKS:

1. Section subtitles (there are 3 — find the ones with lorem ipsum text like "Up to 50% off Lorem ipsum dolor…")
   - Replace each subtitle string with Setting::get() calls:
     {{ Setting::get('homepage_subtitle_1', '') }}
     {{ Setting::get('homepage_subtitle_2', '') }}
     {{ Setting::get('homepage_subtitle_3', '') }}
   - Use empty string as fallback so nothing renders if not set (cleaner than showing placeholder text)

2. Trust strip labels (the horizontal strip with icons — "30 days return", "Free shipping", etc.)
   - These are currently hardcoded label strings inside the strip
   - Replace each label text with the appropriate Setting::get():
     {{ Setting::get('trust_label_1', 'Free Delivery') }}
     {{ Setting::get('trust_label_2', '30 Day Returns') }}
     {{ Setting::get('trust_label_3', 'Secure Payment') }}
     {{ Setting::get('trust_label_4', 'Irish Crafted') }}
   - If there are subtitle/description lines under each label, do the same pattern with _sub suffix keys

3. Sale banner image (the mid-page promotional banner with a hardcoded image path)
   - Replace the hardcoded src with: {{ Setting::get('sale_banner_image', asset('assets/images/banners/sale-banner.jpg')) }}

4. Featured product star rating
   - Find the hardcoded 5-star HTML (the static ★★★★★ or SVG stars on the featured product block)
   - Remove the stars entirely — do not replace with dynamic logic (reviews system doesn't exist yet)
   - Keep surrounding layout intact — just delete the star markup

RULES:
- Do not alter any other section on the homepage
- Do not change any class names, IDs, or layout structure
- After all changes: git add -A && git commit -m "FE-3: Homepage Settings substitutions — subtitles, trust strip, sale banner, remove fake stars"
- Then: git push origin main
```

---

## FE-4 · About, Contact, Privacy, FAQ Sidebar

**Files touched:** `about.blade.php`, `contact.blade.php`, `coming-soon.blade.php` (privacy), `faq.blade.php`

**Why grouped:** All four pages have hardcoded content that maps directly to `Setting::get()` keys — no new tables needed. The FAQ Q&As themselves are NOT fixed here (needs DB — that's BE-3/FE-5). Only the FAQ sidebar banner is fixed here.

---

```
Read FRONTEND_RULES.md and WORKFLOW.md first.

You are replacing hardcoded content on four static pages with Setting::get() calls. Do NOT restructure layouts or alter dynamic sections that are already working.

TASKS:

1. about.blade.php
   a. Hero image src — replace hardcoded path with:
      {{ Setting::get('about_hero_image', asset('assets/images/about/hero.jpg')) }}
   b. Hero heading text — replace with:
      {{ Setting::get('about_heading', 'Our Story') }}
   c. Brand story body paragraphs — there will be multiple hardcoded <p> blocks
      Replace the entire text content area with:
      {!! nl2br(e(Setting::get('about_story', ''))) !!}
      Wrap in the same <div> that currently holds the paragraphs — keep the wrapper, replace only the inner content
   d. Gallery images (Ochaka demo paths) — replace each hardcoded src with:
      {{ Setting::get('about_gallery_1', asset('assets/images/about/gallery-1.jpg')) }}
      {{ Setting::get('about_gallery_2', asset('assets/images/about/gallery-2.jpg')) }}
      {{ Setting::get('about_gallery_3', asset('assets/images/about/gallery-3.jpg')) }}
      (use as many keys as there are hardcoded images)
   e. Craft values strip — find the hardcoded label/icon/description items
      Replace label text with:
      {{ Setting::get('craft_value_1_title', 'Handcrafted') }}
      {{ Setting::get('craft_value_1_text', '') }}
      Repeat pattern for _2, _3, _4 as needed

2. contact.blade.php — Google Maps iframe
   - Find the hardcoded lat/lng coordinates in the iframe src
   - Replace with dynamic values:
     src="https://www.google.com/maps?q={{ Setting::get('store_lat', '53.3498') }},{{ Setting::get('store_lng', '-6.2603') }}&output=embed"
   - Do not touch anything else on the contact page

3. coming-soon.blade.php (Privacy Policy page)
   - Find the large hardcoded privacy policy text block
   - Replace the entire text content with:
     {!! nl2br(e(Setting::get('privacy_policy', 'Our privacy policy is currently being updated. Please contact us for details.'))) !!}
   - Keep the surrounding page layout and heading intact

4. faq.blade.php — Sidebar banner only
   - Find the hardcoded sidebar banner image path
   - Replace with: {{ Setting::get('faq_banner_image', asset('assets/images/banners/faq-sidebar.jpg')) }}
   - Do NOT touch the Q&A list items — those are handled in a later task

After all changes: git add -A && git commit -m "FE-4: About/Contact/Privacy/FAQ sidebar — replace hardcoded content with Setting::get()"
Then: git push origin main
```

---

## FE-5 · Homepage Dynamic Sections — Slider + Testimonials (requires BE-2 and BE-3 done first)

**Files touched:** `home.blade.php`

**Why grouped:** These are the homepage sections that need new DB tables created in the backend tasks. Do this group AFTER BE-2 (Settings admin expanded) and BE-3 (sliders + testimonials + faqs migrations) are complete.

**Dependency:** BE-2 and BE-3 must be done before this.

---

```
Read FRONTEND_RULES.md and WORKFLOW.md first.

BE-2 and BE-3 have already been completed — the sliders, testimonials, and faqs DB tables exist and are seeded. You are now wiring home.blade.php to use them.

The controller that serves home.blade.php must already be passing data. First check HomeController (or whichever controller renders the homepage) and confirm what variables are passed. If $sliders, $testimonials are not passed, update the controller to query and pass them before editing the Blade file.

TASKS:

1. Hero Slider
   - The controller should pass: $sliders = Slider::where('active', true)->orderBy('sort_order')->get();
   - In home.blade.php, find the hardcoded slider section (currently 3 hardcoded slide blocks referencing slider-22/23/24.jpg)
   - Replace with a @foreach loop over $sliders:
     @foreach($sliders as $slide)
       {{-- Copy the existing single slide HTML structure exactly --}}
       {{-- Replace hardcoded image src with $slide->image --}}
       {{-- Replace hardcoded heading with $slide->heading --}}
       {{-- Replace hardcoded subheading/text with $slide->subheading --}}
       {{-- Replace hardcoded button text with $slide->button_text --}}
       {{-- Replace hardcoded button link with $slide->button_url --}}
     @endforeach
   - IMPORTANT: Copy the exact HTML of ONE existing slide block as the loop template. Do not invent new HTML.

2. Testimonials Section
   - The controller should pass: $testimonials = Testimonial::where('active', true)->orderBy('sort_order')->get();
   - In home.blade.php, find the testimonials section (currently hardcoded fake names/quotes)
   - Replace the hardcoded testimonial cards with a @foreach loop over $testimonials
   - Copy the exact HTML of one existing testimonial card as the loop template
   - Map fields: $testimonial->name, $testimonial->location, $testimonial->body, $testimonial->rating, $testimonial->product_name, $testimonial->avatar (if present)
   - If $testimonials is empty, hide the entire section with @if($testimonials->isNotEmpty())

3. Blog Section
   - This section has no posts table — do not attempt to wire it
   - Simply hide the entire blog section with: @if(false) ... @endif
   - Add a comment: {{-- Blog section hidden: no posts system yet --}}

4. Instagram Gallery
   - This section has no live integration — hide it entirely
   - Wrap with: @if(false) ... @endif
   - Add a comment: {{-- Instagram section hidden: no live integration --}}

After all changes: git add -A && git commit -m "FE-5: Homepage slider and testimonials wired to DB; blog/instagram sections hidden"
Then: git push origin main
```

---

# ═══════════════════════════════════
# BACKEND
# ═══════════════════════════════════

---

## BE-1 · Product Admin — Missing Variant Fields + Customer Addresses

**Files touched:** `Admin/ProductController.php`, product create/edit Blade views (admin), `Admin/CustomerController.php`, customer detail Blade view (admin)

**Why grouped:** Both are "field exists in DB, just not wired to the form" fixes. No migrations. No new models. Pure controller + Blade changes on the admin side only. Fastest backend win.

---

```
Read WORKFLOW.md first.

You are adding missing form fields to existing admin forms. No migrations needed — the DB columns already exist. Do NOT alter any frontend Blade files. Only touch admin views and controllers.

TASKS:

1. Product edit form — is_bestseller toggle
   - In the product create/edit admin Blade view, find the main product fields section
   - Add a checkbox field for is_bestseller:
     Label: "Mark as Bestseller"
     Input: <input type="checkbox" name="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller ?? false) ? 'checked' : '' }}>
   - In Admin/ProductController.php store() and update() methods:
     Add 'is_bestseller' to the validated/fillable fields
     Save as: $product->is_bestseller = $request->boolean('is_bestseller');

2. ProductVariant form — sale_price_eur, second_metal_id, colour
   - In the variant rows section of the product admin Blade (the repeating variant block):
     a. Add a number input for sale_price_eur:
        Label: "Sale Price (€)"
        Input: <input type="number" step="0.01" name="variants[{index}][sale_price_eur]" value="{{ old(..., $variant->sale_price_eur ?? '') }}">
     b. Add a select for second_metal_id:
        Label: "Second Metal"
        Populate with all Metal records: Metal::all()
        Allow blank/null option ("None")
        name="variants[{index}][second_metal_id]"
     c. Add a text input for colour:
        Label: "Colour"
        Input: <input type="text" name="variants[{index}][colour]" value="{{ old(..., $variant->colour ?? '') }}">
   - In Admin/ProductController.php, in the variant save loop:
     Add saving of sale_price_eur, second_metal_id, colour for each variant
     sale_price_eur: store null if blank
     second_metal_id: store null if blank

3. Customer detail — address history
   - In Admin/CustomerController.php show() method:
     Change the existing query to eager-load addresses:
     $customer = User::with(['orders', 'addresses'])->findOrFail($id);
   - In the customer detail admin Blade view:
     Find the section after order history
     Add an Addresses section that loops $customer->addresses and displays:
     address_line_1, address_line_2 (if set), city, county, postcode, country, is_default badge
     Match the visual style of the existing order history table already on the page

After all changes: git add -A && git commit -m "BE-1: Product is_bestseller + variant fields wired; customer address history in admin"
Then: git push origin main
```

---

## BE-2 · Settings Admin Expansion + Terms/Privacy Pages

**Files touched:** `Admin/SettingController.php` (or settings admin Blade), `settings` seeder/migration, `routes/web.php`, new `terms.blade.php`

**Why grouped:** All the new `Setting::get()` keys introduced in FE-1 through FE-4 need corresponding admin inputs so the client can actually edit them. Plus the Terms page (a new route + view) is trivial and belongs here alongside the Privacy/T&C settings fields.

**Dependency:** Run this before or alongside FE-1 to FE-4 — the frontend substitutions will work with fallback defaults even if the settings don't exist yet in DB.

---

```
Read WORKFLOW.md first.

You are expanding the Settings admin panel to include all new keys introduced in the frontend audit fixes. You are also seeding default values and creating the Terms page.

TASKS:

1. Settings admin Blade — add new input fields
   Open the admin settings view. Add new fields in appropriate sections (create new sections/tabs if needed to keep it organised). Fields to add:

   STORE section:
   - store_tagline (text input) — "Store Tagline"

   HOMEPAGE section (create if not exists):
   - homepage_subtitle_1 (text input) — "Section Subtitle 1"
   - homepage_subtitle_2 (text input) — "Section Subtitle 2"
   - homepage_subtitle_3 (text input) — "Section Subtitle 3"
   - trust_label_1 through trust_label_4 (text inputs) — "Trust Strip Label 1–4"
   - trust_sub_1 through trust_sub_4 (text inputs) — "Trust Strip Sublabel 1–4"
   - sale_banner_image (file/text input) — "Sale Banner Image Path"
   - newsletter_intro (text input) — "Newsletter Intro Text"
   - featured_product_id (number input) — already exists, confirm it's there

   PRODUCT section (create if not exists):
   - delivery_info (textarea) — "Delivery Information Tab"
   - returns_policy (textarea) — "Returns Policy Tab"

   ABOUT section (create if not exists):
   - about_hero_image (text input) — "About Hero Image Path"
   - about_heading (text input) — "About Page Heading"
   - about_story (textarea, tall) — "Brand Story Text"
   - about_gallery_1, about_gallery_2, about_gallery_3 (text inputs) — "About Gallery Image 1–3"
   - craft_value_1_title, craft_value_1_text (text inputs) — repeat for _2, _3, _4

   CONTACT section (create if not exists):
   - store_lat (text input) — "Store Latitude"
   - store_lng (text input) — "Store Longitude"

   LEGAL section (create if not exists):
   - terms_url (text input) — "Terms & Conditions URL" (used if linking to external URL)
   - privacy_policy (textarea, very tall) — "Privacy Policy Text"
   - faq_banner_image (text input) — "FAQ Sidebar Banner Image Path"

2. Settings seeder — add default values
   In the settings seeder (or DatabaseSeeder), add default values for all new keys so the pages render correctly on first load:
   - store_tagline: "Fine Irish Jewellery"
   - newsletter_intro: "Subscribe for exclusive offers and new arrivals."
   - delivery_info: "Orders are dispatched within 2–3 working days. Free standard delivery on orders over €50 within Ireland."
   - returns_policy: "We accept returns within 30 days of delivery on all unworn, undamaged items in original packaging. Custom or engraved pieces are non-refundable."
   - homepage_subtitle_1/2/3: "" (empty)
   - trust_label_1: "Free Delivery" | trust_label_2: "30 Day Returns" | trust_label_3: "Secure Payment" | trust_label_4: "Irish Crafted"
   - about_heading: "Our Story"
   - about_story: "" (empty — client will fill)
   - store_lat: "53.3498" | store_lng: "-6.2603"
   - privacy_policy: "" (empty — client will fill)
   - Use updateOrInsert or firstOrCreate so running the seeder again doesn't duplicate rows

3. Terms & Conditions page
   - Add a route in web.php: Route::get('/terms', ...)->name('terms');
   - Create resources/views/terms.blade.php
   - Model it identically on the privacy page structure (coming-soon.blade.php)
   - Content: {!! nl2br(e(Setting::get('terms_conditions', 'Our terms and conditions are currently being updated.'))) !!}
   - Add terms_conditions to the settings admin LEGAL section and seeder with empty default

After all changes: git add -A && git commit -m "BE-2: Settings admin expanded with all new keys; defaults seeded; Terms page created"
Then: git push origin main
```

---

## BE-3 · New DB Tables — Sliders, Testimonials, FAQs + Admin CRUD

**Files touched:** new migrations, new models, new admin controllers, new admin Blade views, `routes/web.php` (admin routes), `HomeController.php`, `FaqController.php`

**Why grouped:** These three tables are all "simple content tables with admin CRUD" — same pattern repeated three times. Building them together is more efficient than three separate sessions. After this task, FE-5 can be done.

---

```
Read WORKFLOW.md first.

You are creating three new DB tables with models, migrations, seeders, and admin CRUD. Follow the exact same patterns already used in this project for other admin resources (look at how Categories or Coupons admin is structured and match it exactly — same layout, same form style, same controller pattern).

IMPORTANT: Do not invent new UI patterns. Study an existing simple admin resource first, then replicate.

═══ TABLE 1: sliders ═══

Migration — create_sliders_table:
- id
- heading (string)
- subheading (string, nullable)
- button_text (string, nullable)
- button_url (string, nullable)
- image (string) — stores path/filename
- sort_order (integer, default 0)
- active (boolean, default true)
- timestamps

Model: Slider
- fillable: all above except id/timestamps
- scope: scopeActive() — where active = true, orderBy sort_order

Admin CRUD: Admin\SliderController
- index: list all sliders in sort_order
- create/store: form to add new slider (image upload to storage/public/sliders/)
- edit/update: edit existing
- destroy: delete
- No soft deletes needed

Admin Blade views (resources/views/admin/sliders/):
- index.blade.php — table with: image thumbnail, heading, sort_order, active toggle, edit/delete buttons
- create.blade.php and edit.blade.php — form with all fields, image upload input

Routes (inside existing admin middleware group):
  Route::resource('sliders', Admin\SliderController::class);

Seeder: SliderSeeder — seed 3 sample sliders:
  Slider 1: heading="Irish Claddagh Rings", subheading="Handcrafted in Ireland", button_text="Shop Now", button_url="/shop", image="slider-1.jpg", sort_order=1
  Slider 2: heading="Gold & Silver Jewellery", subheading="For every occasion", button_text="Explore", button_url="/collections", image="slider-2.jpg", sort_order=2
  Slider 3: heading="Free Delivery on Orders Over €50", subheading="", button_text="Shop Now", button_url="/shop", image="slider-3.jpg", sort_order=3

Add SliderSeeder to DatabaseSeeder.

═══ TABLE 2: testimonials ═══

Migration — create_testimonials_table:
- id
- name (string)
- location (string, nullable)
- body (text)
- rating (tinyInteger, default 5)
- product_name (string, nullable)
- avatar (string, nullable)
- sort_order (integer, default 0)
- active (boolean, default true)
- timestamps

Model: Testimonial
- fillable: all above
- scope: scopeActive()

Admin CRUD: Admin\TestimonialController
- Full resource controller (index, create, store, edit, update, destroy)

Admin Blade views (resources/views/admin/testimonials/):
- index.blade.php — table with name, rating stars, short body preview, active, edit/delete
- create.blade.php / edit.blade.php — all fields, rating as select 1–5

Routes: Route::resource('testimonials', Admin\TestimonialController::class); in admin group

Seeder: TestimonialSeeder — seed 3 sample testimonials with Irish names, realistic jewellery reviews

═══ TABLE 3: faqs ═══

Migration — create_faqs_table:
- id
- question (string)
- answer (text)
- sort_order (integer, default 0)
- active (boolean, default true)
- timestamps

Model: Faq
- fillable: all above
- scope: scopeActive()

Admin CRUD: Admin\FaqController
- Full resource controller

Admin Blade views (resources/views/admin/faqs/):
- index.blade.php — table with question (truncated), sort_order, active toggle, edit/delete
- create.blade.php / edit.blade.php — question (text input) + answer (textarea)

Routes: Route::resource('faqs', Admin\FaqController::class); in admin group

Frontend: FaqController (or update existing faq route controller)
- Pass $faqs = Faq::active()->orderBy('sort_order')->get(); to faq.blade.php
- In faq.blade.php: replace the 15 hardcoded Q&A blocks with @foreach($faqs as $faq) loop
  Copy the exact HTML of one existing Q&A accordion block as the loop template
  Map: $faq->question and $faq->answer

Seeder: FaqSeeder — seed the 15 existing hardcoded Q&As from the current faq.blade.php into the DB (extract them first, don't invent new ones)

Add all three seeders to DatabaseSeeder.

Add sidebar links in admin navigation for Sliders, Testimonials, FAQs (under a "Content" group if one exists, or create it).

After all changes: git add -A && git commit -m "BE-3: Sliders, Testimonials, FAQs — migrations, models, admin CRUD, seeders, frontend wired"
Then: git push origin main
```

---

## BE-4 · Emails + Attributes CRUD + Roles Fix

**Files touched:** new Mailable classes, `OrderController.php`, consultation handler, new attribute controllers (Metal, Gemstone, RingSize, Colour), new migration (colours), admin sidebar

**Why grouped:** These are three independent backend systems, but each is small. Emails are 2 Mailables + dispatch calls. Attributes are 4 simple CRUDs (same pattern). Roles fix is one line (remove the broken link). Grouping avoids 3 tiny sessions.

---

```
Read WORKFLOW.md first.

You are implementing transactional emails, wiring existing attribute stubs to real controllers, and fixing the broken Roles link. These are three independent tasks — complete them in order.

═══ TASK 1: Order Confirmation Email ═══

Create app/Mail/OrderConfirmed.php:
- Extends Mailable
- Constructor: public Order $order (eager-load with items, user, shippingAddress)
- envelope(): subject = "Order Confirmed — #{$this->order->order_number}"
- content(): returns view 'emails.order-confirmed'

Create resources/views/emails/order-confirmed.blade.php:
- Plain, clean HTML email (no heavy styling — inline CSS only, keep it simple)
- Content: thank the customer, show order number, list ordered items (name, qty, price), show total, delivery address, note that they'll be contacted when dispatched
- Sign off with store name: {{ Setting::get('store_name') }} and store email

In OrderController.php, in the store() method, after the order is saved and BEFORE the redirect:
  Mail::to($order->user->email)->send(new OrderConfirmed($order));
  (import Mail and OrderConfirmed at top of file)
Wrap in try/catch so a mail failure does NOT break the order:
  try { Mail::to(...)->send(...); } catch (\Exception $e) { \Log::error('Order email failed: ' . $e->getMessage()); }

═══ TASK 2: Consultation Notification Email ═══

Find the controller/method that handles the consultation form submission (check routes/web.php for the consultation POST route).

Create app/Mail/ConsultationReceived.php:
- Constructor: receives the consultation data array or model
- envelope(): subject = "New Consultation Request — FADÓ Jewellery"
- content(): view 'emails.consultation-received'

Create resources/views/emails/consultation-received.blade.php:
- Internal notification email (sent to the store, not the customer)
- Show: customer name, email, phone, message, date/time submitted
- Send TO: Setting::get('store_email')

In the consultation controller store() method, after saving:
  Mail::to(Setting::get('store_email'))->send(new ConsultationReceived($data));
  Wrap in same try/catch pattern as above.

═══ TASK 3: Attributes Admin CRUD ═══

The stub views already exist. Wire them up.

For each of Metal, Gemstone, RingSize — create a full resource controller:
  app/Http/Controllers/Admin/MetalController.php
  app/Http/Controllers/Admin/GemstoneController.php
  app/Http/Controllers/Admin/RingSizeController.php

Each controller:
- index(): list all records
- create/store(): add new (name field minimum; Metal also has colour_hex if it exists on the model)
- edit/update(): edit existing
- destroy(): delete (check: if any ProductVariants use this record, show error instead of deleting)

For Colours (no table exists yet):
  Create migration: create_colours_table (id, name, hex_code nullable, timestamps)
  Create Colour model
  Create Admin\ColourController — full resource
  Create admin Blade views: resources/views/admin/colours/ (index, create, edit)

Routes in admin group:
  Route::resource('metals', Admin\MetalController::class);
  Route::resource('gemstones', Admin\GemstoneController::class);
  Route::resource('ring-sizes', Admin\RingSizeController::class);
  Route::resource('colours', Admin\ColourController::class);

For the admin Blade views — if stubs already exist, flesh them out. If they are blank/empty, create them following the exact same pattern as another simple admin resource (Categories is a good reference).

═══ TASK 4: Fix Roles & Permissions sidebar link ═══

In the admin sidebar Blade partial:
- Find the "Roles & Permissions" navigation link
- Remove it entirely (comment it out with {{-- --}} and a note: {{-- Roles & Permissions: not yet implemented --}})
- Do NOT add Spatie logic — just remove the broken link so admins don't see a dead page

After all changes: git add -A && git commit -m "BE-4: Order/consultation emails, attributes CRUD, remove broken roles link"
Then: git push origin main
```

---

## Notes on Sequencing

```
Recommended order:

Day 1:  BE-1  (quick wins, no deps)
        FE-1  (quick wins, no deps)
        FE-2  (no deps)

Day 2:  BE-2  (settings expansion — do before FE-3/FE-4 ideally, but FE fallbacks work anyway)
        FE-3  (homepage settings fixes)
        FE-4  (about/contact/privacy/faq sidebar)

Day 3:  BE-3  (new tables — sliders, testimonials, faqs)
        FE-5  (homepage dynamic wiring — MUST be after BE-3)

Day 4:  BE-4  (emails, attributes, roles fix — fully independent)

Total: 9 Claude sessions across 4 days.
Each session is self-contained: read → fix → commit → push.
```

---

*FADÓ Jewellery / Heaventree Design — June 2026*
