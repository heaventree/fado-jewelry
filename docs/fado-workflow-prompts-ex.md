# FADÓ Jewellery — Additional Workflow Prompts
**Continuation of fado-workflow-prompts.md**  
**Date:** June 2026

Same rules apply: verify first, build second. Read CLAUDE.md before every session.

---

## EX-1 · Mega Menu Image — Confirm banner_image is used correctly

**Files touched:** `partials/header.blade.php` (mega menu), `CLAUDE.md`

**Decision made:** Use existing `banner_image` column on categories. No new `menu_image` column needed.

**⚠️ Two-step session.**

---

**STEP 1 — Send this first:**

```
Read CLAUDE.md first. Do not make any changes yet.

Check and report:

1. In partials/header.blade.php, does the mega menu already use $category->banner_image for category images?
   Report the exact line and what it currently renders.
2. If it uses a different field or a hardcoded image, report that.
3. Does CLAUDE.md already document that banner_image is used for the mega menu?

Do NOT make changes. Wait for confirmation.
```

---

**STEP 2 — After report:**

```
Thank you.

1. If the mega menu is NOT already using $category->banner_image:
   - In partials/header.blade.php, find the mega menu category image src
   - Replace any hardcoded path or wrong field with: {{ $category->banner_image ? asset('storage/' . $category->banner_image) : asset('assets/images/placeholder.jpg') }}
   - Do not alter any other part of the mega menu

2. Update CLAUDE.md — add this note under the Categories section:
   "Mega menu category images use the banner_image column on the categories table.
    No separate menu_image field exists — this is intentional."

After changes (if any): git add -A && git commit -m "EX-1: Confirm mega menu uses banner_image; document in CLAUDE.md"
Then: git push origin main
```

---

## EX-2 · Menu Management — WordPress-style Menu Builder

**Files touched:** new migrations, new models (`Menu`, `MenuItem`), new `Admin\MenuController`, new admin Blade views, `partials/header.blade.php`, `partials/footer.blade.php`, `routes/web.php`, admin sidebar

**Scope:** Full menu management system. Admin creates named menus, adds/removes/reorders items (custom links, pages, categories, collections), assigns menus to locations (header, footer). Blade partials render from DB.

**⚠️ Two-step session.**

---

**STEP 1 — Send this first:**

```
Read CLAUDE.md first. Do not make any changes yet.

Check and report:

1. Does a menus table/migration already exist?
2. Does a menu_items table/migration already exist?
3. Does a Menu model exist?
4. Does a MenuItem model exist?
5. Does Admin\MenuController exist?
6. In partials/header.blade.php — list every hardcoded static nav link (not category/collection loops).
   For each: label, href, line number.
7. In partials/footer.blade.php — list every hardcoded static link in footer columns.
   For each: label, href, line number.
8. Does the admin sidebar already have a Menus link?

Do NOT make changes. Wait for confirmation.
```

---

**STEP 2 — After report, build only what is missing:**

```
Thank you. Build a WordPress-style menu management system. Study existing admin resources (Categories, FAQs) and match patterns exactly.

═══ MIGRATION 1: create_menus_table ═══

Columns:
- id
- name (string) — e.g. "Header Menu", "Footer Menu"
- location (string, nullable) — e.g. "header", "footer_col_1", "footer_col_2"
- timestamps

═══ MIGRATION 2: create_menu_items_table ═══

Columns:
- id
- menu_id (foreignId, cascadeOnDelete)
- parent_id (unsignedBigInteger, nullable, default null) — for dropdown children
- label (string) — display text
- url (string, nullable) — for custom links
- type (string, default 'custom') — values: 'custom', 'page', 'category', 'collection'
- reference_id (unsignedBigInteger, nullable) — ID of category/collection if type is not custom
- target (string, default '_self') — '_self' or '_blank'
- sort_order (integer, default 0)
- timestamps

═══ MODEL: Menu ═══

- fillable: name, location
- hasMany: MenuItem (ordered by sort_order)
- static method: getByLocation(string $location) — returns Menu with items for a given location
- scope: withItems() — with(['items' => fn($q) => $q->whereNull('parent_id')->orderBy('sort_order')->with(['children' => fn($q) => $q->orderBy('sort_order')])])

═══ MODEL: MenuItem ═══

- fillable: menu_id, parent_id, label, url, type, reference_id, target, sort_order
- belongsTo: Menu
- hasMany: children (self-referential, MenuItem where parent_id = this->id, orderBy sort_order)
- getResolvedUrl(): method that returns the correct URL:
  - if type = 'custom': return $this->url
  - if type = 'category': return route('shop.category', Category::find($this->reference_id)?->slug ?? '#')
  - if type = 'collection': return route('shop.collection', Collection::find($this->reference_id)?->slug ?? '#')
  - if type = 'page': return $this->url

═══ ADMIN: Admin\MenuController ═══

- index(): list all menus with item count
- create/store(): create a new menu (name + location)
- edit(Menu $menu): show menu with all its items — this is the main management page
- update(Menu $menu): save menu name/location
- destroy(Menu $menu): delete menu and all items (cascade)

Admin\MenuItemController (separate controller for item CRUD within a menu):
- store(): add new item to a menu
- update(): edit an existing item (label, url, type, reference_id, target, sort_order)
- destroy(): remove item from menu
- reorder(): POST endpoint to save new sort_order after drag-drop (accepts array of ids + positions)

Routes in admin group:
  Route::resource('menus', Admin\MenuController::class);
  Route::post('menus/{menu}/items', [Admin\MenuItemController::class, 'store'])->name('admin.menu-items.store');
  Route::put('menus/{menu}/items/{item}', [Admin\MenuItemController::class, 'update'])->name('admin.menu-items.update');
  Route::delete('menus/{menu}/items/{item}', [Admin\MenuItemController::class, 'destroy'])->name('admin.menu-items.destroy');
  Route::post('menus/{menu}/items/reorder', [Admin\MenuItemController::class, 'reorder'])->name('admin.menu-items.reorder');

═══ ADMIN BLADE VIEWS: resources/views/admin/menus/ ═══

index.blade.php:
- Table: menu name, location badge, item count, Edit/Delete buttons
- "Create Menu" button

create.blade.php:
- Fields: name (text), location (select: header, footer_col_1, footer_col_2, none)

edit.blade.php — this is the main menu builder page:
- Top: menu name + location (editable inline)
- Left panel — "Add Items" form with tabs:
  Tab 1: Custom Link — fields: Label, URL, Target (self/blank) → "Add to Menu" button
  Tab 2: Pages — checkboxes for: Home, About, Contact, FAQ, Blog, Terms, Privacy
  Tab 3: Categories — checkboxes listing all Category records from DB
  Tab 4: Collections — checkboxes listing all Collection records from DB
- Right panel — current menu items list:
  - Each item shows: drag handle (☰), label, type badge, url, Edit (inline) and Remove buttons
  - Items are sortable via drag-and-drop (use a simple HTML5 drag or Sortable.js if already in the project — check first)
  - Child items are indented under parent (for dropdown support)
  - "Save Order" button that POSTs to the reorder endpoint
- Match the visual style of existing admin pages

═══ SEEDER: MenuSeeder ═══

Create 3 default menus seeded with the current hardcoded links found in Step 1:

Menu 1: name="Header Menu", location="header"
  Items: seed with the exact hardcoded links currently in header.blade.php (extracted from Step 1 report)

Menu 2: name="Footer Links", location="footer_col_1"
  Items: seed with footer column 1 links from Step 1 report

Menu 3 (if footer has 2 columns): name="Footer Info", location="footer_col_2"
  Items: seed with footer column 2 links from Step 1 report

Add MenuSeeder to DatabaseSeeder.

═══ BLADE HELPER: Menu rendering ═══

Create a Blade component or helper at app/View/Components/NavMenu.php (or a simple include):

resources/views/components/nav-menu.blade.php:
@props(['location'])
@php
  $menu = Menu::getByLocation($location);
@endphp
@if($menu)
  @foreach($menu->items->whereNull('parent_id') as $item)
    {{-- render item with children if any --}}
    {{-- Copy the exact HTML of one existing nav link as the template --}}
    {{-- For items with children: render as dropdown using existing dropdown HTML --}}
  @endforeach
@endif

═══ PARTIALS: Wire header + footer ═══

partials/header.blade.php:
- Find the hardcoded static nav links
- Replace with: <x-nav-menu location="header" />
- IMPORTANT: Do not touch category/collection loops — they are already dynamic and working

partials/footer.blade.php:
- Find the hardcoded footer link columns
- Replace column 1 with: <x-nav-menu location="footer_col_1" />
- Replace column 2 with: <x-nav-menu location="footer_col_2" /> (if applicable)
- Do not touch any other footer content

Add "Menus" link in admin sidebar under a Navigation group.

After all changes: git add -A && git commit -m "EX-2: WordPress-style menu management — menus + menu_items tables, admin builder, header/footer wired"
Then: git push origin main

After pushing, remind me to run on the server:
php artisan migrate
php artisan db:seed --class=MenuSeeder
```

---

## EX-3 · Blog/Posts System — Full Build

**Files touched:** new migration, new model, new admin controller + Blade views, new frontend controller + Blade views, `routes/web.php`, `home.blade.php`, settings seeder, admin sidebar

**Scope:** Full blog system — posts table, admin CRUD, frontend listing page, frontend post detail page, homepage section wired.

**⚠️ Two-step session. BE-3 must already be done (it is).**

---

**STEP 1 — Send this first:**

```
Read CLAUDE.md first. Do not make any changes yet.

Check and report:

1. Does a posts table/migration already exist?
2. Does a Post model exist at app/Models/Post.php?
3. Does an admin PostController exist?
4. Does a frontend blog listing route/view exist?
5. Does a frontend blog post detail route/view exist?
6. In home.blade.php — is the blog section currently hidden with @if(false)?
   Report the exact lines it spans.
7. Does the admin sidebar already have a Blog/Posts link?

Do NOT make changes. Wait for confirmation.
```

---

**STEP 2 — After report, build only what is missing:**

```
Thank you. Build the blog/posts system. Study an existing admin resource (Categories or FAQs) and match its patterns exactly — same controller structure, same Blade layout style.

═══ MIGRATION: create_posts_table ═══

Columns:
- id
- title (string)
- slug (string, unique)
- excerpt (text, nullable) — short summary for listing page
- body (longText) — full post content
- featured_image (string, nullable)
- author (string, nullable) — simple text field, no user relation needed
- published_at (timestamp, nullable) — null = draft
- sort_order (integer, default 0)
- timestamps

═══ MODEL: Post ═══

- fillable: all above
- scopePublished(): where published_at is not null and <= now(), orderBy published_at desc
- getRouteKeyName(): return 'slug'
- Auto-generate slug from title on create if slug is empty

═══ ADMIN: Admin\PostController ═══

Full resource controller:
- index(): list all posts, show title, published_at (or "Draft"), edit/delete buttons
- create/store(): form with all fields. featured_image uploads to storage/public/blog/
- edit/update(): edit existing
- destroy(): delete

Admin Blade views at resources/views/admin/posts/:
- index.blade.php — table: title, excerpt preview, published_at or "Draft" badge, edit/delete
- create.blade.php + edit.blade.php — all fields. Body field: use a tall textarea (no rich editor needed — plain text/HTML is fine for now). published_at: datetime input (leave blank for draft).

Route in admin group: Route::resource('posts', Admin\PostController::class);

Add "Blog Posts" link in admin sidebar under the Content group (alongside Sliders, Testimonials, FAQs).

═══ FRONTEND: Blog listing + post detail ═══

Controller: app/Http/Controllers/BlogController.php
- index(): $posts = Post::published()->paginate(9); → view 'blog.index'
- show(Post $post): → view 'blog.show' with $post

Routes (public, outside admin group):
  Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
  Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Frontend Blade views at resources/views/blog/:
- index.blade.php:
  - Extend the main layout (@extends('layouts.app'))
  - Page title: "Blog" — {{ Setting::get('store_name') }}
  - Grid of post cards: featured_image, title, excerpt, published_at, "Read More" link
  - Pagination links
  - If no posts: show a simple "No posts yet" message
  - Match the visual style of the listing.blade.php page for consistency

- show.blade.php:
  - Extend main layout
  - Page title: $post->title — {{ Setting::get('store_name') }}
  - Show: featured_image (full width), title, author, published_at, body ({!! nl2br(e($post->body)) !!})
  - "Back to Blog" link
  - Match visual style of existing content pages

═══ SEEDER: PostSeeder ═══

Seed 2 sample blog posts so the section is not empty:
  Post 1: title="The Art of Irish Jewellery Making", excerpt="Discover the centuries-old craft...", body="[short placeholder paragraph]", published_at=now(), author="FADÓ Team"
  Post 2: title="How to Choose the Perfect Claddagh Ring", excerpt="The Claddagh ring is one of Ireland's...", body="[short placeholder paragraph]", published_at=now()-1week, author="FADÓ Team"

Add PostSeeder to DatabaseSeeder.

═══ HOMEPAGE: Wire blog section ═══

In HomeController (or whatever controller renders home.blade.php):
- Add: $posts = Post::published()->take(3)->get();
- Pass $posts to the view

In home.blade.php:
- Find the blog section currently wrapped in @if(false)
- Remove the @if(false) wrapper
- Replace the hardcoded blog post cards with @foreach($posts as $post) loop
- Copy the exact HTML of ONE existing hardcoded blog card as the loop template
- Map: $post->featured_image, $post->title, $post->excerpt, $post->published_at, route('blog.show', $post)
- Wrap entire section with @if($posts->isNotEmpty()) so it hides if no published posts exist
- Add a "View All Posts" link pointing to route('blog.index')

After all changes: git add -A && git commit -m "EX-3: Blog/Posts system — migration, model, admin CRUD, frontend listing+detail, homepage wired"
Then: git push origin main

After pushing, remind me to run on the server:
php artisan migrate
php artisan db:seed --class=PostSeeder
```

---

## Deployment Checklist (after all EX tasks pushed)

Run on 20i server via SSH:

```bash
cd /path/to/fado-jewelry
git pull
php /usr/php84/usr/bin/php artisan migrate
php /usr/php84/usr/bin/php artisan db:seed --class=MenuSeeder
php /usr/php84/usr/bin/php artisan db:seed --class=PostSeeder
php /usr/php84/usr/bin/php artisan config:cache
```

Then purge CDN via 20i panel.

**Visual checks:**
- Header nav — static links rendering from DB (Home, About, Contact etc.)
- Footer links — rendering from DB
- Admin → Menus — can create/edit/delete menus and items
- Admin → Menus → Header Menu — drag/drop reorder works
- Homepage blog section — 2 sample posts showing
- /blog — listing page renders with pagination
- /blog/[slug] — post detail page renders
- Admin sidebar — Blog Posts + Menus links visible
- Admin → Blog Posts — create/edit/delete works
- Mega menu — category images showing from banner_image

---

*FADÓ Jewellery / Heaventree Design — June 2026*
