# Laravel E-commerce Portfolio Project

A first full-stack project built to learn how a shop works across customer pages, admin tools, database relationships, and interactive forms. The goal is to improve the existing application step by step and make its behavior understandable and reliable.

The application includes a storefront, product management, carts, wishlists, order management, and four kinds of offers. The current `/checkout` page is a cart and discount summary; completing a purchase from that page and online payment processing are not implemented.

## Contents

- [Run locally](#run-locally)
- [Customer pages](#customer-pages)
- [Admin pages](#admin-pages)
- [Code structure and design choices](#code-structure-and-design-choices)
- [Offers and pricing](#offers-and-pricing)
- [Searchable dropdowns](#searchable-dropdowns-select2)
- [Tests](#tests)
- [Current limitations](#current-limitations)
- [Cleanup](#cleanup)

## Technology

| Technology | Purpose |
| --- | --- |
| PHP 8.3+, Laravel 13 | Routing, validation, authentication, database access, and services. |
| Livewire 4 | Search, filters, pagination, and cart interactions without a separate frontend API. |
| Blade | Page templates and reusable markup such as inputs and navigation. |
| Alpine.js | Small browser interactions, including offer field visibility and deletion modals. |
| Bootstrap | Layout, styling, and admin confirmation modals. |
| jQuery / Select2 | Searchable single and multiple dropdowns with remote results. |
| Vite / npm | Building frontend entry points; many template assets also load directly from `public/assets`. |
| Pest | Automated feature and unit checks. |

The storefront and admin dashboard reuse existing template assets. This project focuses on implementing shop behavior rather than creating a design system from scratch.

## Run locally

Install PHP 8.3 or later with Laravel's required extensions and the extension for your database, Composer, and a Node.js version supported by the locked Vite dependency. SQLite is the default in `.env.example`; MySQL can be configured instead.

From the project directory:

```sh
composer install
npm ci
```

Copy `.env.example` to `.env` only if `.env` does not already exist. On PowerShell:

```powershell
Copy-Item .env.example .env
```

On macOS/Linux use `cp .env.example .env`. Set `APP_URL` to your local address. For SQLite, create an empty `database/database.sqlite` file if missing. For MySQL, create a database and configure the `DB_*` values.

```sh
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build
php artisan serve
```

Open `http://127.0.0.1:8000`. Uploaded images use the public storage disk; `storage:link` makes them accessible in the browser. For frontend development run `npm run dev` in a second terminal. Alternatively, `composer run dev` starts the Laravel server, queue listener, and Vite together.

### Demo accounts and data

On a fresh local database:

```sh
php artisan db:seed
```

The current [DatabaseSeeder](database/seeders/DatabaseSeeder.php) creates one admin:

| Email | Password | Role |
| --- | --- | --- |
| `admin@gmail.com` | `admin` | Admin |

These are local demo credentials; change them before public deployment. The seeder does not create a complete catalog and is not designed to run repeatedly on the same database.

Register a separate customer at `/register`. Use admin pages to create categories, tags, products, companies, and offers. Give products stock and mark some featured to populate the home page.

### Background tasks and mail

```sh
php artisan schedule:work
```

[routes/console.php](routes/console.php) schedules offer status updates daily and deletion of carts older than one day every second. Review the latter frequency before deployment. To run these tasks individually:

```sh
php artisan app:offers:activate
php artisan app:cart:delete-old
```

Storefront pricing also checks offer dates when fetching offers. The default log mailer writes password-reset emails to `storage/logs/laravel.log`; configure a mail service to deliver real emails.

## Customer pages

[routes/web.php](routes/web.php) is the source of truth for addresses and route names; [routes/auth.php](routes/auth.php) defines account routes.

| Page / address | Purpose | Access |
| --- | --- | --- |
| Home: `/`, `/home` | Introduce the shop and display featured products and product slides. | Public |
| Catalog: `/products` | Browse products using search, category/company filters, sorting, and pagination. | Public |
| Product: `/home/product/{product}` | Inspect a product, its image, and its details. | Public |
| Offers: `/home/offers` | Browse active public promotions; coupons are excluded. | Public |
| Wishlist: `/home.wishlist` | Browse saved products and remove favorites. | Signed-in user |
| Cart summary: `/checkout` | Change quantities, remove items, enter a coupon, and inspect totals. An empty valid cart redirects home. | Signed-in user |
| Orders: `/order` | List, create, view, edit, and delete the customer's orders. | Signed-in user; ownership is checked |
| Contacts: `/contact` | Submit and manage contact messages. | Signed-in user; ownership is checked |
| Profile: `/profile` | Update account information/password or delete the account. | Signed-in user |
| Authentication pages | Login, registration, password recovery, confirmation, and verification. | Depends on action |
| Language: `/locale/{lang}` | Store a locale choice in the session; translations live in `lang/en` and `lang/ar`. | Public |

`/home/catgory` redirects to `/products`; it is a compatibility address rather than a separate browser.

## Admin pages

Admin-only routes require authentication and `role = admin`. The dashboard also has `verified` middleware. Orders and contacts share route families with customers, with different layouts and record visibility.

| Page / address | Why it exists |
| --- | --- |
| Dashboard: `/dashboard` | Summarize products, orders, messages, companies, tags, and active offers. |
| Products: `/product` | Manage names, images, descriptions, prices, stock, featured status, and relationships. |
| Categories: `/catagory` | Organize products into groups and inspect each category's products. |
| Companies: `/company` | Manage company information and associated products. |
| Tags: `/tag` | Manage labels attached to products. |
| Orders: `/order` | Manage customer orders and corresponding stock changes. |
| Contacts: `/contact` | Review and manage customer messages. |
| Global offers: `/offer` | Manage promotions applying to every product. |
| Product offers: `/productsOffer` | Manage promotions assigned to selected products. |
| Category offers: `/catagoryOffers` | Manage promotions assigned to selected categories. |
| Coupons: `/offerCoupons` | Manage promotions applied by entering a code. |

The four offer lists help admins find each promotion type. They share an offer model, controller, create/edit form, and deletion endpoint. Admin products, categories, companies, tags, orders, and all offer lists use Bootstrap delete modals showing the selected record's name and ID. Confirming submits a CSRF-protected DELETE form.

## Code structure and design choices

| Location | Responsibility |
| --- | --- |
| `routes/` | Addresses, controller actions, middleware, and scheduled commands. |
| `app/Http/Controllers/` | Render pages and handle ordinary form submissions. |
| `app/Http/Requests/` | Validate and authorize product, company, order, and profile input. Some controllers validate directly. |
| `app/Models/` | Database records and relationships. |
| `app/Livewire/` | Class-based `Catalog`, `PublicOffers`, and `RecordTable` components. |
| `resources/views/livewire/` | Templates for class-based Livewire components. |
| `resources/views/components/⚡*.blade.php` | Livewire single-file components, with PHP behavior and markup together. The lightning symbol is intentional. |
| `resources/views/components/` | Reusable Blade markup alongside single-file Livewire components. |
| `resources/views/partials/` | Shared order/offer forms and asset includes. |
| `app/Services/` | Offer calculations, cart totals, and shared storefront data. |
| `resources/views/admin.blade.php` | Shared admin layout. |
| `resources/views/layouts/storefront.blade.php` | Shared customer layout, including authentication and profile pages. |
| `public/assets/` | Template assets and custom scripts/styles. |
| `database/migrations/` | Schema history, including decimal precision updates. |
| `tests/` | PHP feature/unit tests and JavaScript dropdown checks. |

### Why use both Blade and Livewire?

Blade components reuse markup. Livewire manages changing server state. Ordinary create/edit forms submit to controllers, while listings use Livewire for filters and pagination without a full page reload. Alpine handles small browser-only changes. This keeps the existing Laravel application while adding interaction where needed.

`RecordTable` shares listing behavior across products, companies, tags, offers, orders, and contacts. Categories use their own single-file table component. `Catalog` handles storefront browsing and wishlist mode; `PublicOffers` handles public promotions.

An admin edit follows: route -> controller -> record loaded -> Blade form -> validated submission -> database save -> redirect. A filter change follows: Livewire property updated -> matching records queried -> table markup updated.

### Main database relationships

- A product belongs to a category and has many tags and companies through pivot tables.
- Product and category offer targets use `products_offer` and `categories_offer`.
- A user has a cart; `cart_product` stores products and quantities.
- Favorite records link users to products.
- Orders link to users/products through `user_order` and `product_order`.
- Contact messages belong to a user.

Existing spellings such as `Catagory`, `Favoriate`, and `catagory_id` match the current schema and routes. Renaming them requires coordinated changes rather than deleting or renaming individual files.

## Offers and pricing

[OfferService](app/Services/OfferService.php) calculates discounts. [CartService](app/Services/CartService.php) calculates totals using quantities. [StorefrontData](app/Services/StorefrontData.php) shares loaded carts, offers, and coupon lookups within a request. It is a scoped service so components can avoid duplicate queries without sharing customer data across requests.

Current rules:

1. Storefront offer queries require an active flag and valid start/end dates.
2. Global offers apply to every product; product/category offers apply to their targets.
3. Each offer is calculated against regular price. The lowest price wins; discounts do not stack.
4. A valid coupon competes with automatic offers and wins when its price is equal or lower.
5. A percentage entered as `15.5` is stored as `0.155`. Fixed discounts subtract from each unit price and are clamped at zero.
6. A $5 fixed coupon on three units can save $15: this is a per-unit discount, not a single cart deduction.
7. Offer saves validate targets and use transactions. Changing the type removes old target relationships; the end date includes the end of that day.

These calculations drive storefront/cart displays. The separate order create/edit flow still saves regular product price multiplied by quantity. Unifying those paths is a planned improvement.

## Searchable dropdowns (Select2)

[remote-select.blade.php](resources/views/components/form/remote-select.blade.php) and [user-selects.js](public/assets/js/user-selects.js) provide reusable product/category/company/tag dropdowns.

- Opening or searching requests `/select-options/{resource}`. Typing uses a 300 ms delay.
- Laravel searches names and returns 20 options at a time; scrolling fetches more.
- Only IDs, names, and product prices where needed are returned.
- Edit forms include saved IDs and fetch names in batches of 20, even outside the first result page.
- Ordinary forms submit IDs; Livewire filters explicitly update component properties.
- `wire:ignore` protects Select2's filter markup. Hooks synchronize cleared filters and initialize new selects.
- Selecting a product emits an event for the order-total preview; the server calculates the saved total independently.
- Custom positioning keeps the dropdown inside the viewport on small screens.

This avoids loading the whole catalog into every form. Category/company lookups are public, product lookups require authentication, and tags require an admin. Saved selections are validated separately by Laravel.

## Tests

```sh
php artisan test
node --test tests/Unit/UserSelects.test.cjs
```

PHP feature tests use an in-memory SQLite database from `phpunit.xml`. Ensure compiled-view/log directories and Pest test-cache storage are writable. Tests cover authentication, ownership, listings, filters, offer validation/relationships, decimal order totals, stock changes, shared cart updates, and dropdown search/pagination.

Focused checks:

```sh
php artisan test tests/Feature/DashboardImprovementsTest.php tests/Feature/CheckoutUpdatesTest.php tests/Feature/CatagoryPagesTest.php tests/Feature/RemoteSelectTest.php
php artisan view:cache
```

`tests/RemoteSelectBrowser.cjs` is an additional local browser check. It assumes Windows, a specific Microsoft Edge executable, and a server at `http://127.0.0.1:8007`; it is not a portable test command. Passing tests confirm covered behavior, not every shop scenario.

## Current limitations

This portfolio application is under active development. Priorities are:

- Connect the cart summary to order placement. The Checkout button has no purchase action; no payment gateway is integrated.
- Use consistent pricing for cart displays and saved orders, with explicit money rounding.
- Explain invalid, expired, or less beneficial coupons to customers.
- Add a manual offer active/inactive control; saving currently sets an offer active.
- Strengthen stock checks across every cart interaction and prevent duplicate submissions.
- Decide whether coupons need usage limits, minimum spend, or customer restrictions; these are not implemented.
- Add complete demo data, screenshots, and deployment instructions as the purchase flow becomes complete.

## Cleanup

Removed 14 files with no active references: empty controller/service/seed stubs, an unused email-based admin middleware, an unused welcome view, and superseded cart/product/select prototype components. Obsolete imports and a commented route were removed with them.

The active cart uses `items`, `receipt`, `increment_decrement`, and shared storefront cart components. The active catalog uses `Catalog`; dropdowns use `form.remote-select`.

Template assets, framework views, migrations, and registered providers remain. CSS and vendor scripts can reference assets indirectly, so a missing Blade reference is not sufficient evidence to delete an asset. Before removing another file, check routes, providers, component references, tests, and indirect dependencies, then run relevant checks.
