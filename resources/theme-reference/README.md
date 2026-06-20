# Ochaka Theme — HTML Reference

This folder contains the **original, unmodified** static HTML files from the
purchased Ochaka ThemeForest template (themeforest-eEPan0Fi-ochaka-multipurpose-ecommerce-html5-template).

## Purpose

These files are **reference only** — they are never loaded or served by the
Laravel application. They exist so that when converting a page to Blade, we
can copy the exact, byte-for-byte original markup and only insert Blade
dynamic syntax where real data is needed, rather than hand-reconstructing
HTML from memory (which is how most of the original frontend drift bugs
happened).

## Workflow for converting a page

1. Find the matching `.html` file in this folder for the page being built.
2. Copy the relevant section(s) into the new Blade file unchanged.
3. Replace only the specific dummy content (text, image src, prices, loops)
   with Blade syntax (`{{ }}`, `@foreach`, `@if`, etc.) — do not change
   classes, structure, nesting, or remove/add wrapper elements.
4. Cross-reference `public/css/styles.css` to confirm any class names used
   are real theme classes (search the class name in this HTML reference set
   to confirm it's actually used in the theme, not invented).

## Key page mappings

| FADÓ page | Ochaka reference file |
|---|---|
| Homepage | `home-jewelry.html` |
| Collection / category listing | `shop-left-sidebar.html` |
| Product detail | `product-detail.html` |
| Cart | `view-cart.html` |
| Checkout | `checkout.html` |
| Wishlist | `wishlist.html` |
| Account dashboard | `account-page.html` |
| Account orders | `account-orders.html` |
| Account order detail | `account-orders-detail.html` |
| Account settings | `account-setting.html` |
| About | `about-us.html` |
| Contact | `contact-us.html` |
| 404 | `404.html` |

Header and footer markup is embedded inline in every page (this theme has
no separate header/footer partial files) — any page's `<header>`/`<footer>`
section can be used as the source for `header.blade.php` / `footer.blade.php`.
