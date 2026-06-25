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

**⚠️ Two-step session. Verify first, build second.**

---

**STEP 1 — Send this first:**

```
Read FRONTEND_RULES.md and WORKFLOW.md first. Do not make any changes yet.

Check each of the following and report YES/NO with file + line number:

1. Is the tagline in partials/header.blade.php already using Setting::get() — or is it hardcoded text?
2. Is the newsletter intro in partials/footer.blade.php already using Setting::get() — or hardcoded?
3. Are <title> tags in auth/login.blade.php and auth/register.blade.php hardcoded "FADÓ Jewellery" or already dynamic?
4. Is the "New Customer" copy on login.blade.php hardcoding the store name?
5. Are the <title> tags in dashboard, orders, order-detail, addresses, profile Blade files hardcoded?

Report format: item number — YES already dynamic / NO still hardcoded (file, line X)

Wait for confirmation before doing anything.
```

---

**STEP 2 — After report, send this for anything still hardcoded:**

```
Thank you. Now fix only the items still hardcoded (as identified above). Skip anything already dynamic.

RULES:
- Read FRONTEND_RULES.md and WORKFLOW.md — do not deviate
- Pure string substitutions only — do NOT restructure, restyle, or refactor anything else

FIXES (only for hardcoded items):

1. partials/header.blade.php — tagline
   Replace hardcoded tagline with: {{ Setting::get('store_tagline', 'Fine Irish Jewellery') }}

2. partials/footer.blade.php — newsletter intro
   Replace hardcoded intro with: {{ Setting::get('newsletter_intro', 'Subscribe for exclusive offers and new arrivals.') }}

3. auth/login.blade.php and auth/register.blade.php
   Replace every hardcoded "FADÓ Jewellery" in <title> tags and body copy with:
   {{ Setting::get('store_name', 'FADÓ Jewellery') }}
   On login page: also fix the "New Customer" paragraph where store name is hardcoded

4. Account pages: dashboard, orders, order-detail, addresses, profile
   Replace every hardcoded "FADÓ Jewellery" in <title> tags with:
   {{ Setting::get('store_name', 'FADÓ Jewellery') }}

Use the fallback value in every Setting::get() so the page still renders if the key is missing.
Only touch the specific hardcoded strings — do not alter surrounding HTML, classes, or structure.

After all changes: git add -A && git commit -m "FE-1: Replace hardcoded store name/tagline/newsletter intro with Setting::get()"
Then: git push origin main
```

---

## FE-2 · Product Page Tabs + Checkout T&C + Country List

**Files touched:** `product.blade.php`, `checkout.blade.php`

**Why grouped:** Both are single-page fixes with no shared dependencies. The product tab text and the checkout country/T&C are independent of each other but both are quick in-blade substitutions — cleaner to knock out in one session than two.

**⚠️ Two-step session. Verify first, build second.**

---

**STEP 1 — Send this first:**

```
Read FRONTEND_RULES.md and WORKFLOW.md first. Do not make any changes yet.

Check and report YES/NO with file + line number:

1. Is the delivery tab text in product.blade.php already using Setting::get('delivery_info') — or hardcoded?
2. Is the returns tab text in product.blade.php already using Setting::get('returns_policy') — or hardcoded?
3. How many countries are in the checkout.blade.php country dropdown? List them.
4. Does the T&C link in checkout.blade.php have a real href (route('terms') or similar) — or is it missing/empty?

Wait for confirmation before doing anything.
```

---

**STEP 2 — After report, send this for anything still hardcoded:**

```
Thank you. Fix only the items still hardcoded. Do NOT touch anything else on these pages — no layout changes, no JS, no CSS.

FIXES (only for hardcoded items):

1. product.blade.php — Delivery tab (if still hardcoded)
   Replace text content with:
   {!! nl2br(e(Setting::get('delivery_info', 'Orders dispatched within 2–3 working days. Free standard delivery on orders over €50.'))) !!}

2. product.blade.php — Returns tab (if still hardcoded)
   Replace text content with:
   {!! nl2br(e(Setting::get('returns_policy', '30-day return policy on all unworn items. See our full returns policy for details.'))) !!}

3. checkout.blade.php — Country dropdown (if fewer than 30 countries)
   Replace the hardcoded <option> list with a full list. Include at minimum:
   Ireland (first, selected by default), United Kingdom, United States, Canada, Australia, New Zealand, Germany, France, Spain, Italy, Netherlands, Belgium, Sweden, Norway, Denmark, Finland, Switzerland, Austria, Portugal, Poland, Czech Republic, Hungary, Romania, Bulgaria, Croatia, Slovakia, Slovenia, Greece, Cyprus, Malta, Luxembourg, Estonia, Latvia, Lithuania, Japan, Singapore, UAE, South Africa
   Keep Ireland as first/default, then alphabetical.

4. checkout.blade.php — T&C link (if href missing)
   Change anchor to: <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a>
   Note: terms route created in BE-2. For now just set the href — it will 404 until BE-2 is done.

After all changes: git add -A && git commit -m "FE-2: Dynamic delivery/returns tabs, full country list, T&C link"
Then: git push origin main
```

---

## FE-3 · Homepage Hardcoded Sections (Settings-based fixes only)

**Files touched:** `home.blade.php`

**Why grouped:** All homepage fixes that only require `Setting::get()` — no new tables, no new Blade loops. The slider, testimonials, blog, and Instagram sections are NOT touched here (they need new DB tables — those are FE-5). This group is strictly the subtitle text, trust strip, sale banner, and removing the hardcoded star rating.

**⚠️ Two-step session. Verify first, build second.**

---

**STEP 1 — Send this first:**

```
Read FRONTEND_RULES.md and WORKFLOW.md first. Do not make any changes yet.

Check home.blade.php only and report YES/NO with line numbers:

1. Are the 3 section subtitles still hardcoded lorem ipsum text — or already using Setting::get()?
2. Are the trust strip labels ("30 days return" etc.) still hardcoded — or already Setting::get()?
3. Is the sale banner image src still a hardcoded path — or already Setting::get()?
4. Is the featured product star rating still hardcoded HTML stars — or already removed/dynamic?

Do NOT touch anything. Wait for confirmation.
```

---

**STEP 2 — After report, send this for anything still hardcoded:**

```
Thank you. Fix only the items still hardcoded in home.blade.php. Do NOT touch the hero slider, testimonials, blog, or Instagram sections — those are separate tasks.

FIXES (only for hardcoded items):

1. Section subtitles — 3 instances of lorem ipsum subtitle text
   Replace each with:
   {{ Setting::get('homepage_subtitle_1', '') }}
   {{ Setting::get('homepage_subtitle_2', '') }}
   {{ Setting::get('homepage_subtitle_3', '') }}
   Use empty string fallback so nothing renders if not set.

2. Trust strip labels
   Replace each hardcoded label with:
   {{ Setting::get('trust_label_1', 'Free Delivery') }}
   {{ Setting::get('trust_label_2', '30 Day Returns') }}
   {{ Setting::get('trust_label_3', 'Secure Payment') }}
   {{ Setting::get('trust_label_4', 'Irish Crafted') }}
   If there are sublabels/descriptions under each, use trust_sub_1 through trust_sub_4.

3. Sale banner image
   Replace hardcoded src with:
   {{ Setting::get('sale_banner_image', asset('assets/images/banners/sale-banner.jpg')) }}

4. Featured product star rating
   Find the hardcoded static star HTML (★★★★★ or SVG stars) on the featured product block.
   Delete the star markup entirely. Do not replace with anything. Keep surrounding layout intact.

After all changes: git add -A && git commit -m "FE-3: Homepage Settings substitutions — subtitles, trust strip, sale banner, remove fake stars"
Then: git push origin main
```

---

## FE-4 · About, Contact, Privacy, FAQ Sidebar

**Files touched:** `about.blade.php`, `contact.blade.php`, `coming-soon.blade.php` (privacy), `faq.blade.php`

**Why grouped:** All four pages have hardcoded content that maps directly to `Setting::get()` keys — no new tables needed. The FAQ Q&As themselves are NOT fixed here (needs DB — that's BE-3). Only the FAQ sidebar banner is fixed here.

**⚠️ Two-step session. Verify first, build second.**

---

**STEP 1 — Send this first:**

```
Read FRONTEND_RULES.md and WORKFLOW.md first. Do not make any changes yet.

Check and report YES/NO with file + line numbers:

1. about.blade.php — is the hero image src hardcoded or already Setting::get()?
2. about.blade.php — is the hero heading hardcoded or already Setting::get()?
3. about.blade.php — are the brand story paragraphs hardcoded or already Setting::get()?
4. about.blade.php — are the gallery image srcs hardcoded Ochaka demo paths or already Setting::get()?
5. about.blade.php — are the craft values strip labels hardcoded or already Setting::get()?
6. contact.blade.php — are the Google Maps iframe coordinates hardcoded or already Setting::get()?
7. coming-soon.blade.php — is the privacy policy text hardcoded or already Setting::get()?
8. faq.blade.php — is the sidebar banner image hardcoded or already Setting::get()?

Do NOT make any changes. Wait for confirmation.
```

---

**STEP 2 — After report, send this for anything still hardcoded:**

```
Thank you. Fix only the items still hardcoded. Do NOT restructure layouts or alter any dynamic sections already working.

FIXES (only for hardcoded items):

1. about.blade.php
   a. Hero image src — replace with: {{ Setting::get('about_hero_image', asset('assets/images/about/hero.jpg')) }}
   b. Hero heading — replace with: {{ Setting::get('about_heading', 'Our Story') }}
   c. Brand story body paragraphs — keep the wrapper div, replace only the inner content with:
      {!! nl2br(e(Setting::get('about_story', ''))) !!}
   d. Gallery image srcs — replace each hardcoded src with:
      {{ Setting::get('about_gallery_1', asset('assets/images/about/gallery-1.jpg')) }}
      {{ Setting::get('about_gallery_2', asset('assets/images/about/gallery-2.jpg')) }}
      {{ Setting::get('about_gallery_3', asset('assets/images/about/gallery-3.jpg')) }}
   e. Craft values strip — replace each label/text with:
      {{ Setting::get('craft_value_1_title', 'Handcrafted') }}
      {{ Setting::get('craft_value_1_text', '') }}
      Repeat for _2, _3, _4

2. contact.blade.php — Google Maps iframe
   Replace hardcoded coordinates in iframe src with:
   src="https://www.google.com/maps?q={{ Setting::get('store_lat', '53.3498') }},{{ Setting::get('store_lng', '-6.2603') }}&output=embed"
   Do not touch anything else on the contact page.

3. coming-soon.blade.php (Privacy Policy)
   Replace the hardcoded privacy policy text block with:
   {!! nl2br(e(Setting::get('privacy_policy', 'Our privacy policy is currently being updated. Please contact us for details.'))) !!}
   Keep surrounding layout and heading intact.

4. faq.blade.php — sidebar banner only
   Replace hardcoded sidebar banner image src with:
   {{ Setting::get('faq_banner_image', asset('assets/images/banners/faq-sidebar.jpg')) }}
   Do NOT touch the Q&A list items.

After all changes: git add -A && git commit -m "FE-4: About/Contact/Privacy/FAQ sidebar — replace hardcoded content with Setting::get()"
Then: git push origin main
```

---

## FE-5 · Homepage Dynamic Sections — Slider + Testimonials (requires BE-3 done first)

**Files touched:** `home.blade.php`, `HomeController.php`

**Why grouped:** These homepage sections need the DB tables from BE-3. Do this AFTER BE-3 is complete and confirmed.

**Dependency:** BE-3 must be fully done and pushed before starting this.

**⚠️ Two-step session. Verify first, build second.**

---

**STEP 1 — Send this first:**

```
Read FRONTEND_RULES.md and WORKFLOW.md first. Do not make any changes yet.

BE-3 should already be done. Verify the following and report:

1. Do the sliders, testimonials, faqs tables exist in the database? (run: php artisan migrate:status)
2. Does HomeController already pass $sliders to home.blade.php?
3. Does HomeController already pass $testimonials to home.blade.php?
4. Is the hero slider section in home.blade.php still hardcoded (slider-22/23/24.jpg etc.) or already a @foreach loop?
5. Is the testimonials section still hardcoded fake names/quotes or already a @foreach loop?
6. Is the blog section already hidden/commented out?
7. Is the Instagram gallery section already hidden/commented out?

Do NOT make any changes. Wait for confirmation.
```

---

**STEP 2 — After report, send this:**

```
Thank you. Now wire home.blade.php to the DB for any sections still hardcoded.

IMPORTANT RULES:
- Read FRONTEND_RULES.md — copy the exact HTML of ONE existing hardcoded block as the loop template
- Do NOT invent new HTML structure
- Do NOT touch any other sections on the homepage

FIXES:

1. Hero Slider (if still hardcoded)
   - Update HomeController to pass: $sliders = Slider::where('active', true)->orderBy('sort_order')->get();
   - In home.blade.php, find the 3 hardcoded slide blocks
   - Take ONE slide block's exact HTML as the loop template
   - Replace all 3 hardcoded blocks with a single @foreach($sliders as $slide) loop:
     Map: image → $slide->image, heading → $slide->heading, subheading → $slide->subheading,
     button text → $slide->button_text, button url → $slide->button_url
   - Wrap with @if($sliders->isNotEmpty()) so slider hides if no slides exist

2. Testimonials (if still hardcoded)
   - Update HomeController to pass: $testimonials = Testimonial::where('active', true)->orderBy('sort_order')->get();
   - In home.blade.php, find the hardcoded testimonial cards
   - Take ONE card's exact HTML as the loop template
   - Replace all hardcoded cards with @foreach($testimonials as $testimonial)
     Map: name → $testimonial->name, location → $testimonial->location, body → $testimonial->body,
     rating → $testimonial->rating, product_name → $testimonial->product_name
   - Wrap entire section with @if($testimonials->isNotEmpty())

3. Blog Section (if not already hidden)
   - Wrap the entire blog section with @if(false) ... @endif
   - Add comment inside: {{-- Blog section hidden: no posts system yet --}}

4. Instagram Gallery (if not already hidden)
   - Wrap the entire instagram section with @if(false) ... @endif
   - Add comment inside: {{-- Instagram section hidden: no live integration --}}

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

**⚠️ IMPORTANT: This is a two-step session. Verify first, build second.**

---

```
Read WORKFLOW.md first.

STEP 1 — VERIFY ONLY. Do not make any changes yet.

Check each of the following and report back with YES/NO and the exact file + line number:

1. Does the product create/edit admin Blade view already have an is_bestseller checkbox?
2. Does the variant form already have a sale_price_eur input field?
3. Does the variant form already have a second_metal_id select field?
4. Does the variant form already have a colour input field?
5. Does Admin/ProductController.php store() already save is_bestseller?
6. Does Admin/ProductController.php update() already save is_bestseller?
7. Does Admin/ProductController.php already save sale_price_eur, second_metal_id, colour in the variant save loop?
8. Does Admin/CustomerController.php show() already eager-load addresses (User::with(['orders', 'addresses']))?
9. Does the customer detail admin Blade view already show an addresses section?

Report format:
  1. is_bestseller checkbox in form — YES (file, line X) / NO
  2. sale_price_eur in variant form — YES / NO
  ... etc.

Wait for my confirmation before doing anything.
```

---

**After Claude Code reports back — if any items are missing, send this second prompt:**

---

```
Thank you for the audit. Now implement only the items that are missing (as identified above). Skip anything already in place.

TASKS (implement only missing ones):

1. Product edit form — is_bestseller toggle (if missing)
   - In the product create/edit admin Blade view, find the main product fields section
   - Add a checkbox field for is_bestseller:
     Label: "Mark as Bestseller"
     Input: <input type="checkbox" name="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller ?? false) ? 'checked' : '' }}>
   - In Admin/ProductController.php store() and update() methods:
     Add 'is_bestseller' to the validated/fillable fields
     Save as: $product->is_bestseller = $request->boolean('is_bestseller');

2. ProductVariant form — sale_price_eur, second_metal_id, colour (add only missing fields)
   - In the variant rows section of the product admin Blade (the repeating variant block):
     a. sale_price_eur (if missing):
        Label: "Sale Price (€)"
        Input: <input type="number" step="0.01" name="variants[{index}][sale_price_eur]" value="{{ old(..., $variant->sale_price_eur ?? '') }}">
     b. second_metal_id (if missing):
        Label: "Second Metal"
        Populate with all Metal records: Metal::all()
        Allow blank/null option ("None")
        name="variants[{index}][second_metal_id]"
     c. colour (if missing):
        Label: "Colour"
        Input: <input type="text" name="variants[{index}][colour]" value="{{ old(..., $variant->colour ?? '') }}">
   - In Admin/ProductController.php, in the variant save loop:
     Add saving of any missing fields (sale_price_eur, second_metal_id, colour)
     sale_price_eur: store null if blank
     second_metal_id: store null if blank

3. Customer detail — address history (if missing)
   - In Admin/CustomerController.php show() method:
     Change the existing query to eager-load addresses:
     $customer = User::with(['orders', 'addresses'])->findOrFail($id);
   - In the customer detail admin Blade view:
     Find the section after order history
     Add an Addresses section that loops $customer->addresses and displays:
     address_line_1, address_line_2 (if set), city, county, postcode, country, is_default badge
     Match the visual style of the existing order history table already on the page

RULES:
- Do NOT touch anything already working
- Do NOT alter any frontend Blade files
- Only touch admin views and controllers

After all changes: git add -A && git commit -m "BE-1: Product is_bestseller + variant fields wired; customer address history in admin"
Then: git push origin main
```

---

## BE-2 · Settings Admin Expansion + Terms/Privacy Pages

**Files touched:** Settings admin Blade, settings seeder, `routes/web.php`, new `terms.blade.php`

**Why grouped:** All the new `Setting::get()` keys introduced in FE-1 through FE-4 need corresponding admin inputs so the client can edit them. Plus the Terms page is a quick new route + view.

**Dependency:** Can run alongside FE-1–FE-4. Frontend fallback defaults work even if settings DB keys don't exist yet.

**⚠️ Two-step session. Verify first, build second.**

---

**STEP 1 — Send this first:**

```
Read WORKFLOW.md first. Do not make any changes yet.

Check the settings admin Blade view and seeder and report:

1. Does the settings admin already have a store_tagline field?
2. Does it already have homepage_subtitle_1/2/3 fields?
3. Does it already have trust_label_1 through trust_label_4 fields?
4. Does it already have sale_banner_image, newsletter_intro fields?
5. Does it already have delivery_info and returns_policy fields?
6. Does it already have about_hero_image, about_heading, about_story, about_gallery_1/2/3 fields?
7. Does it already have craft_value_1/2/3/4 title and text fields?
8. Does it already have store_lat and store_lng fields?
9. Does it already have privacy_policy and terms_conditions fields?
10. Does it already have faq_banner_image field?
11. Does a named route 'terms' exist in routes/web.php?
12. Does resources/views/terms.blade.php exist?

Report YES/NO for each. Do NOT make changes. Wait for confirmation.
```

---

**STEP 2 — After report, send this for anything missing:**

```
Thank you. Add only the missing items. Do not duplicate anything already in place.

TASKS (missing items only):

1. Settings admin Blade — add missing input fields in logical sections:

   STORE section: store_tagline (text input)

   HOMEPAGE section (create if not exists):
   homepage_subtitle_1, homepage_subtitle_2, homepage_subtitle_3 (text inputs)
   trust_label_1 through trust_label_4 (text inputs) — "Trust Strip Label 1–4"
   trust_sub_1 through trust_sub_4 (text inputs) — "Trust Strip Sublabel 1–4"
   sale_banner_image (text input), newsletter_intro (text input)

   PRODUCT section (create if not exists):
   delivery_info (textarea), returns_policy (textarea)

   ABOUT section (create if not exists):
   about_hero_image (text), about_heading (text), about_story (textarea tall)
   about_gallery_1, about_gallery_2, about_gallery_3 (text inputs)
   craft_value_1_title, craft_value_1_text — repeat for _2, _3, _4

   CONTACT section (create if not exists):
   store_lat (text), store_lng (text)

   LEGAL section (create if not exists):
   privacy_policy (textarea very tall), terms_conditions (textarea tall), faq_banner_image (text)

2. Settings seeder — add missing default values using updateOrInsert or firstOrCreate:
   store_tagline: "Fine Irish Jewellery"
   newsletter_intro: "Subscribe for exclusive offers and new arrivals."
   delivery_info: "Orders are dispatched within 2–3 working days. Free standard delivery on orders over €50 within Ireland."
   returns_policy: "We accept returns within 30 days of delivery on all unworn, undamaged items in original packaging."
   trust_label_1: "Free Delivery" | trust_label_2: "30 Day Returns" | trust_label_3: "Secure Payment" | trust_label_4: "Irish Crafted"
   about_heading: "Our Story"
   store_lat: "53.3498" | store_lng: "-6.2603"
   All other new keys: "" (empty — client will fill)

3. Terms & Conditions page (if route/view missing):
   - Add route: Route::get('/terms', fn() => view('terms'))->name('terms');
   - Create resources/views/terms.blade.php modelled identically on coming-soon.blade.php structure
   - Content: {!! nl2br(e(Setting::get('terms_conditions', 'Our terms and conditions are currently being updated.'))) !!}

After all changes: git add -A && git commit -m "BE-2: Settings admin expanded with all new keys; defaults seeded; Terms page created"
Then: git push origin main
```

---

## BE-3 · New DB Tables — Sliders, Testimonials, FAQs + Admin CRUD

**Files touched:** new migrations, new models, new admin controllers, new admin Blade views, `routes/web.php`, `HomeController.php`, faq controller/view

**Why grouped:** Three content tables, all the same CRUD pattern. Build together efficiently. FE-5 depends on this.

**⚠️ Two-step session. Verify first, build second.**

---

**STEP 1 — Send this first:**

```
Read WORKFLOW.md first. Do not make any changes yet.

Check and report YES/NO:

1. Does a sliders table exist? (php artisan migrate:status or check migrations folder)
2. Does a Slider model exist at app/Models/Slider.php?
3. Does an admin SliderController exist?
4. Does a testimonials table exist?
5. Does a Testimonial model exist?
6. Does an admin TestimonialController exist?
7. Does a faqs table exist?
8. Does a Faq model exist?
9. Does an admin FaqController exist?
10. Are Sliders/Testimonials/FAQs linked in the admin sidebar?
11. Does the faq.blade.php Q&A section already use a @foreach loop — or still hardcoded?

Do NOT make any changes. Wait for confirmation.
```

---

**STEP 2 — After report, build only what is missing:**

```
Thank you. Build only the missing tables/models/controllers. For anything already in place, skip it.

IMPORTANT: Study an existing simple admin resource first (look at Categories or Coupons — how the controller, routes, and Blade views are structured). Match that pattern exactly — same layout, same form style. Do not invent new UI patterns.

═══ TABLE 1: sliders (if missing) ═══

Migration columns: id, heading (string), subheading (string nullable), button_text (string nullable), button_url (string nullable), image (string), sort_order (int default 0), active (boolean default true), timestamps

Model Slider: fillable all above, scopeActive() = where active true orderBy sort_order

Admin SliderController: full resource (index, create, store, edit, update, destroy)
- image upload to storage/public/sliders/
Admin Blade views at resources/views/admin/sliders/:
- index: table with thumbnail, heading, sort_order, active, edit/delete buttons
- create + edit: all fields with image upload

Route in admin group: Route::resource('sliders', Admin\SliderController::class);

Seeder SliderSeeder — 3 sample slides:
  1: heading="Irish Claddagh Rings", subheading="Handcrafted in Ireland", button_text="Shop Now", button_url="/shop", image="slider-1.jpg", sort_order=1
  2: heading="Gold & Silver Jewellery", subheading="For every occasion", button_text="Explore", button_url="/collections", image="slider-2.jpg", sort_order=2
  3: heading="Free Delivery on Orders Over €50", subheading="", button_text="Shop Now", button_url="/shop", image="slider-3.jpg", sort_order=3

═══ TABLE 2: testimonials (if missing) ═══

Migration columns: id, name (string), location (string nullable), body (text), rating (tinyInteger default 5), product_name (string nullable), avatar (string nullable), sort_order (int default 0), active (boolean default true), timestamps

Model Testimonial: fillable all above, scopeActive()

Admin TestimonialController: full resource
Admin Blade views at resources/views/admin/testimonials/:
- index: name, rating, body preview, active, edit/delete
- create + edit: all fields, rating as select 1–5

Route: Route::resource('testimonials', Admin\TestimonialController::class);

Seeder TestimonialSeeder — 3 sample testimonials with Irish names and realistic jewellery reviews

═══ TABLE 3: faqs (if missing) ═══

Migration columns: id, question (string), answer (text), sort_order (int default 0), active (boolean default true), timestamps

Model Faq: fillable all above, scopeActive()

Admin FaqController: full resource
Admin Blade views at resources/views/admin/faqs/:
- index: question (truncated), sort_order, active, edit/delete
- create + edit: question (text input) + answer (textarea)

Route: Route::resource('faqs', Admin\FaqController::class);

Frontend wiring (faq.blade.php Q&A section — if still hardcoded):
- In the faq route controller, pass: $faqs = Faq::active()->orderBy('sort_order')->get();
- In faq.blade.php: copy exact HTML of ONE existing hardcoded Q&A accordion block as loop template
- Replace all hardcoded Q&As with @foreach($faqs as $faq) loop
- Map: $faq->question and $faq->answer

Seeder FaqSeeder — extract the 15 existing hardcoded Q&As from faq.blade.php and seed them into DB. Do not invent new ones.

Add all three seeders to DatabaseSeeder.
Add sidebar nav links for Sliders, Testimonials, FAQs under a "Content" group in admin nav.

After all changes: git add -A && git commit -m "BE-3: Sliders, Testimonials, FAQs — migrations, models, admin CRUD, seeders, frontend wired"
Then: git push origin main
```

---

## BE-4 · Emails + Attributes CRUD + Roles Fix

**Files touched:** new Mailable classes, `OrderController.php`, consultation handler, new attribute controllers, new migration (colours), admin sidebar

**Why grouped:** Three independent backend systems, each small. Emails = 2 Mailables. Attributes = 4 simple CRUDs. Roles = remove one broken link.

**⚠️ Two-step session. Verify first, build second.**

---

**STEP 1 — Send this first:**

```
Read WORKFLOW.md first. Do not make any changes yet.

Check and report YES/NO with file references:

1. Does app/Mail/OrderConfirmed.php exist?
2. Does OrderController.php store() already dispatch an order confirmation email?
3. Does app/Mail/ConsultationReceived.php exist?
4. Does the consultation controller already dispatch a notification email?
5. Does Admin\MetalController exist and have full CRUD routes wired?
6. Does Admin\GemstoneController exist and have full CRUD routes wired?
7. Does Admin\RingSizeController exist and have full CRUD routes wired?
8. Does a colours table/migration exist?
9. Does Admin\ColourController exist?
10. Does the admin sidebar still have a "Roles & Permissions" link pointing to a dead/static page?

Do NOT make any changes. Wait for confirmation.
```

---

**STEP 2 — After report, build only what is missing:**

```
Thank you. Implement only the missing items below.

═══ TASK 1: Order Confirmation Email (if missing) ═══

Create app/Mail/OrderConfirmed.php:
- Extends Mailable
- Constructor: public Order $order (eager-load items, user, shippingAddress)
- envelope(): subject = "Order Confirmed — #{$this->order->order_number}"
- content(): view 'emails.order-confirmed'

Create resources/views/emails/order-confirmed.blade.php:
- Clean plain HTML email, inline CSS only
- Content: thank customer, order number, items (name/qty/price), total, delivery address
- Sign off: {{ Setting::get('store_name') }} and {{ Setting::get('store_email') }}

In OrderController.php store(), after order saved, BEFORE redirect:
  try {
    Mail::to($order->user->email)->send(new OrderConfirmed($order));
  } catch (\Exception $e) {
    \Log::error('Order email failed: ' . $e->getMessage());
  }

═══ TASK 2: Consultation Notification Email (if missing) ═══

Find the consultation POST controller (check routes/web.php).

Create app/Mail/ConsultationReceived.php:
- Constructor receives consultation data
- envelope(): subject = "New Consultation Request — {{ Setting::get('store_name') }}"
- content(): view 'emails.consultation-received'

Create resources/views/emails/consultation-received.blade.php:
- Internal notification to store (not customer)
- Show: name, email, phone, message, timestamp

In consultation controller store(), after saving:
  try {
    Mail::to(Setting::get('store_email'))->send(new ConsultationReceived($data));
  } catch (\Exception $e) {
    \Log::error('Consultation email failed: ' . $e->getMessage());
  }

═══ TASK 3: Attributes Admin CRUD (build only missing ones) ═══

For Metal, Gemstone, RingSize — create any missing resource controllers:
- index: list all records
- create/store: add new
- edit/update: edit existing
- destroy: check for ProductVariant usage before deleting, show error if in use

For Colours (if table/controller missing):
  Migration: create_colours_table (id, name, hex_code nullable, timestamps)
  Model: Colour
  Admin\ColourController: full resource
  Admin Blade views at resources/views/admin/colours/: index, create, edit

Routes in admin group (add only missing):
  Route::resource('metals', Admin\MetalController::class);
  Route::resource('gemstones', Admin\GemstoneController::class);
  Route::resource('ring-sizes', Admin\RingSizeController::class);
  Route::resource('colours', Admin\ColourController::class);

For any admin Blade views that are empty stubs: flesh them out following the Categories admin as reference pattern.

═══ TASK 4: Fix Roles & Permissions sidebar link (if still present) ═══

In the admin sidebar Blade partial:
- Find the "Roles & Permissions" link
- Comment it out: {{-- Roles & Permissions: not yet implemented --}}
- Do NOT add any Spatie logic

After all changes: git add -A && git commit -m "BE-4: Order/consultation emails, attributes CRUD, remove broken roles link"
Then: git push origin main
```

---

## Notes on Sequencing

```
Each session is two steps: STEP 1 = verify (Claude reports, no changes), STEP 2 = build (only missing items).
Never skip Step 1 — it prevents Claude from redoing work that already exists.

Recommended order:

Day 1:  BE-1  (quick wins, no deps)
        FE-1  (quick wins, no deps)
        FE-2  (no deps)

Day 2:  BE-2  (settings expansion)
        FE-3  (homepage settings fixes)
        FE-4  (about/contact/privacy/faq sidebar)

Day 3:  BE-3  (new tables — sliders, testimonials, faqs)
        FE-5  (homepage dynamic wiring — MUST be after BE-3)

Day 4:  BE-4  (emails, attributes, roles fix — fully independent)

Total: 9 Claude sessions across 4 days.
Pattern per session: verify → confirm → build → commit → push.
```

---

*FADÓ Jewellery / Heaventree Design — June 2026*
