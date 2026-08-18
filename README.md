# PT Abhipraya Nawasena Sejahtera (ANS)
## Website Company Profile & Bilingual Product Catalog

Technical architecture documentation and developer reference for the **PT Abhipraya Nawasena Sejahtera (ANS)** company profile and bilingual B2B product catalog platform.

---

## 1. Overview

This repository contains the web application platform for **PT Abhipraya Nawasena Sejahtera (ANS)**, an Indonesian B2B distributor specializing in clinical diagnostics, industrial microbiology, food safety testing, life science reagents, and laboratory consumables.

The platform architecture comprises two integrated subsystems:
1. **Public Company Website & Bilingual Catalog:** A high-performance, mobile-first, server-rendered public interface presenting corporate credentials, official brand/principal portfolios (including Merck, Neogen, and Era Biology), an organized product catalog with dynamic technical specifications and downloadable PDF datasheets, and structured quotation/inquiry workflows.
2. **Administrative Content Management System (CMS):** A dedicated administrative panel powered by **Filament 5.x** designed for structured management of catalog entities (categories, brands, products, gallery images), corporate content (hero banners, company profile, core values, leadership, clients), and quotation lifecycle management.

---

## 2. Core Objectives

The system architecture is engineered around five foundational objectives:

1. **Shared-Hosting Compatibility:**  
   The application is designed to operate independently and reliably in standard cPanel / shared-hosting environments (PHP 8.3.x, Apache/LiteSpeed, MySQL 8.0+, local filesystem storage, and direct synchronous SMTP) without requiring background worker daemons (such as Redis, Horizon, or Supervisor) or container runtimes.
2. **Structured CMS, Not Page Builder:**  
   The administration interface is purpose-built for managing concrete relational business entities and structured data models rather than generic drag-and-drop page builders, preserving UI consistency, accessibility, and relational data integrity.
3. **SEO-First Multilingual Experience:**  
   Public routing and metadata architecture are optimized for dual-language discoverability across Indonesian (`/id/...`) and English (`/en/...`) locales, employing strict localized slugs, self-canonical tags, bidirectional hreflang references, Open Graph tags, JSON-LD schemas, and a consolidated XML sitemap (`/sitemap.xml`).
4. **Mobile-First Responsiveness:**  
   All public interfaces and key interaction patterns—including catalog filter drawers, hero banner carousels, specification tables, and quotation call-to-action bars—follow a mobile-first implementation using Tailwind CSS 4.x.
5. **High-Integrity Lead Generation:**  
   The relational database serves as the absolute **Single Source of Truth**. All quotation requests are persisted in the database before email notifications are dispatched, ensuring zero lead loss during potential SMTP transport or mail server interruptions.

---

## 3. Technology Stack

The baseline technology stack is formally specified as follows:

| Layer / Component | Technology | Target Version | Architectural Role |
|---|---|---|---|
| **Runtime / Language** | PHP | `^8.3` (Baseline: 8.3.x) | Core server execution runtime. |
| **Backend Framework** | Laravel | `^12.0` (Baseline: 12.x) | MVC architecture, routing, Eloquent ORM, middleware, and security layer. |
| **Admin CMS Panel** | Filament | `^5.0` (**Filament 5.x ONLY**) | Declarative administrative panel built on the modern TALL stack. |
| **Reactive UI Engine** | Livewire | `^4.0` (Baseline: 4.x) | Reactive component engine powering Filament 5. |
| **Frontend View Layer** | Laravel Blade | Native Laravel 12 | Server-rendered templates ensuring performance and search crawler discoverability. |
| **Frontend Styling** | Tailwind CSS | `^4.0` (Baseline: 4.x) | Utility-first styling framework compiled via Vite. |
| **Micro-interactivity** | Alpine.js | Minimal | Lightweight client-side UI interactivity (filter drawers, mobile menus, modals). |
| **Database Engine** | MySQL | `8.0+` (InnoDB, UTF8mb4) | Relational persistence with native JSON column support for dynamic specifications. |
| **Media Storage** | Local Public Disk | `storage/app/public` | Local filesystem storage linked via `public/storage` symlink. |
| **Mail Protocol** | Laravel Mailer | Direct SMTP | Direct synchronous email delivery with fail-safe database persistence. |
| **AI Tooling (Dev)** | Laravel Boost | `^2.5` (`require-dev`) | Local development Model Context Protocol (MCP) server; excluded from production. |
| **Testing Suite** | PHPUnit | Standard Laravel 12 | Automated unit and feature testing suite. |

> [!IMPORTANT]
> **Filament Version Policy:** This project strictly uses **Filament 5.x**. Deprecated APIs, form schemas, or components from Filament 3.x or 4.x are prohibited.

---

## 4. System Architecture

The application follows the classic layered **Model-View-Controller (MVC)** architectural pattern:

```
[Web Browser / Client (Desktop & Mobile)]
       │
       ├── /admin ─────────────► [Filament 5 Auth Middleware] ──► [Filament 5 Admin Panel]
       ├── /sitemap.xml ───────► [Sitemap Route / Controller] ──► [Consolidated XML Engine]
       ├── /robots.txt ────────► [Robots Route / Static] ───────► [Robots Policy Handler]
       └── /{locale}/... ──────► [SetLocaleMiddleware] ────────► [Public Blade Controllers]
                                                                        │
                                   ┌────────────────────────────────────┴────────────────────────────────────┐
                                   ▼                                                                         ▼
                         [Eloquent Domain Models]                                                     [Blade Views]
                                   │                                                                         │
                  ┌────────────────┴────────────────┐                                                        ▼
                  ▼                                 ▼                                            [Tailwind CSS 4.x]
       [MySQL 8.0+ Database]           [Local Public Storage]                                            [Alpine.js]
     (Single Source of Truth)             (public/storage)
```

### Architectural Layers:
1. **Client Layer:** Public visitors access server-rendered HTML pages with minimal client-side JavaScript execution.
2. **Routing & Middleware Layer:**
   - **Public Routes:** Prefixed by valid locale segments (`/id/...` and `/en/...`) and processed through `SetLocaleMiddleware`.
   - **Root Handling:** Accessing `/` automatically redirects (HTTP 302) to `/id`.
   - **Administrative Routes:** The `/admin` endpoint is managed directly by the Filament 5 Panel Provider with session-based authentication.
   - **Search Engine Directives:** `/sitemap.xml` and `/robots.txt` reside at the root domain level.
3. **Controller & Presentation Layer:**
   - Public controllers handle request lifecycle, execute Eloquent scopes with eager loading, and return Blade views styled with Tailwind CSS 4.x.
4. **Administration Layer (Filament 5):**
   - Provides administrative resources, relation managers, custom specification repeaters, and media upload interfaces.
5. **Data & Persistence Layer:**
   - Eloquent models represent MySQL tables, manage entity relationships, provide bilingual accessors with safe fallback, and cast JSON specifications.
6. **Infrastructure & Storage:**
   - Media assets reside in `storage/app/public` and are exposed via `public/storage`.
   - Email dispatch operates synchronously via SMTP with isolated exception handling.

---

## 5. Domain Model

The business domain consists of **11 primary entities** categorized into four operational areas:

```
[Category] 1 ──── N [Product] 1 ──── N [ProductImage] (Gallery Images)
                       │
[Brand]    1 ──── N ───┤
                       │
                       └── 1 ──── N [Quotation] (Leads & Inquiries)
```

### 5.1. Catalog Management
1. **Category (`categories`):**  
   Flat (single-level) product categorization (e.g., Microbiology, Food Safety & Hygiene, Diagnostic & Life Science).  
   *Relationship:* `hasMany(Product::class)`.
2. **Brand (`brands`):**  
   Official principals and manufacturers (e.g., Merck, Neogen, Era Biology). Includes `is_new_principal` flag for promotional highlighting.  
   *Relationship:* `hasMany(Product::class)`.
3. **Product (`products`):**  
   Primary catalog entity. Stores bilingual names, localized slugs, summary text, full descriptions, primary image path, nullable PDF brochure path, featured flag, active status, sort order, and a native JSON `specifications` payload.  
   *Relationships:* `belongsTo(Category::class)`, `belongsTo(Brand::class)`, `hasMany(ProductImage::class)`, `hasMany(Quotation::class)`.
4. **ProductImage (`product_images`):**  
   Separate relational table managing supplementary gallery photos per product.  
   *Relationship:* `belongsTo(Product::class)`.

### 5.2. Corporate Content
5. **HeroBanner (`hero_banners`):**  
   Homepage slider banners containing bilingual headlines, subheadings, CTA button parameters, mandatory desktop artwork (`image_path`), and optional mobile artwork (`mobile_image_path`).
6. **CompanyProfile (`company_profiles`):**  
   Singleton corporate record storing tagline, about narrative, vision, mission, Mensana Tower physical address, phone, official WhatsApp, email, and Google Maps embed URL.
7. **CoreValue (`core_values`):**  
   The 6 official ANS spiral core values (*Integrity, Innovation, Collaboration, Sustainability, Professionalism, Well-Being*).
8. **Management (`managements`):**  
   Leadership and founder profiles (name, bilingual title, biographical summary, and portrait photo).
9. **Client (`clients`):**  
   Corporate client logos (e.g., Kalbe, Biofarma, Unilever, Prodia) for client showcase sections.

### 5.3. Leads & Inquiry
10. **Quotation (`quotations`):**  
    Inquiry and quotation requests received via public forms. Stores optional `product_id` relation, sender contact details, subject, message, lifecycle status (`New`, `Contacted`, `Quoted`, `Closed`), internal admin notes, and audit metadata (`ip_address`, `user_agent`).  
    *Relationship:* `belongsTo(Product::class)`.

### 5.4. Administration & Authentication
11. **User (`users`):**  
    Administrative user accounts authorized to access the Filament 5 CMS panel.

---

## 6. Functional & Architectural Capabilities

### 6.1. Public Corporate Website
- **Home:** Dynamic hero slider, corporate overview highlighting >15 years of industry experience, featured products showcase, and brand/client logo strips.
- **About Us:** Corporate narrative, vision, mission, interactive presentation of the 6 Core Values, and founder/executive profiles.
- **Partners & Clients:** Official principal showcase highlighting flagship partners (e.g., Era Biology) and client credentials.
- **Contact:** Comprehensive contact details, direct WhatsApp links (`0822-614-614-00`), interactive map embed, and a unified inquiry submission form.

### 6.2. Bilingual Product Catalog
- **Multilingual Presentation:** Full catalog browsing in both Indonesian (`/id/products`) and English (`/en/products`).
- **Compound Filtering:** Multi-parameter filtering by Category and Brand via HTTP GET query parameters (`?category=...&brand=...`) evaluated using **AND** logic.
- **Server-Side Pagination:** Standard 12 items per page with eager loaded relations to prevent N+1 queries.
- **Adaptive Filter UI:** Desktop persistent sidebar filter and mobile slide-over modal drawer.

### 6.3. Product Detail Experience
- **Strict Localized Slugs:** Exact locale slug resolution (`/id/products/{slug_id}` and `/en/products/{slug_en}`).
- **Visual Presentation:** High-resolution primary image display alongside supplementary gallery photos from `product_images`.
- **Dynamic Key-Value Specifications:** Responsive table rendering from structured JSON storage (`specifications` attribute).
- **PDF Brochure Downloads:** Conditional datasheet download action displayed when `brochure_path` is populated.
- **Conversion Actions:** Product-contextual quotation CTA, direct WhatsApp link, and a mobile sticky bottom action bar.

### 6.4. Quotation & Inquiry Workflow
- **Submission Contexts:** Supports general business inquiries and pre-filled product-specific quotation requests.
- **Form Hardening:** CSRF protection, request rate throttling (`throttle:5,1`), and hidden honeypot spam traps (`website_url_hp`).
- **Fail-Safe Pipeline:**
  ```
  User Submits Form
         ↓
  Server-Side Validation & Anti-Spam (Honeypot + Throttle)
         ↓
  Persist Quotation in MySQL (Status: 'New')  <── Single Source of Truth
         ↓
  Attempt Synchronous SMTP Email Notification
         ├── Admin Notification Email
         └── User Acknowledgment Confirmation Email
         ↓
  Catch SMTP Exceptions (Log to storage/logs/laravel.log without failing HTTP response)
         ↓
  Return Success Flash Response to User
  ```

---

## 7. Localization Architecture

The application implements a URL-prefix routing architecture supporting two official locales:

```text
Supported Locales:
  - id (Bahasa Indonesia — Default & x-default)
  - en (English)

Public Route Mapping:
  /id                                 <── Home (ID)
  /en                                 <── Home (EN)

  /id/about                           <── About Us (ID)
  /en/about                           <── About Us (EN)

  /id/partners-clients                <── Partners & Clients (ID)
  /en/partners-clients                <── Partners & Clients (EN)

  /id/products                        <── Product Catalog (ID)
  /en/products                        <── Product Catalog (EN)

  /id/products/{slug_id}              <── Product Detail (ID)
  /en/products/{slug_en}              <── Product Detail (EN)

  /id/contact                         <── Contact & Quotation (ID)
  /en/contact                         <── Contact & Quotation (EN)

Root & System Endpoints:
  /                                   <── Redirects (302) to /id
  /sitemap.xml                        <── Consolidated XML Sitemap
  /robots.txt                         <── Crawler Policy Directive
  /admin                              <── Filament 5 Administrative Panel
```

### Localization Mechanics:
- **SetLocaleMiddleware:** Validates the `{locale}` parameter (`id` or `en`), configures `app()->setLocale($locale)`, and sets route URL defaults.
- **Model Attribute Fallback:** Eloquent accessors dynamically resolve localized attributes (e.g., `$product->name`), falling back to Indonesian content if English translations are absent.
- **Strict Locale Slug Resolution:** Detail routes strictly query matching slug columns (`slug_id` for ID locale, `slug_en` for EN locale), throwing `404 Not Found` if a slug does not match the active route locale.

---

## 8. SEO & Discoverability Architecture

The platform embeds native, server-rendered SEO capabilities without third-party plugin overhead:

- **Dynamic Meta Tags:** Automated generation of page titles (`[Page/Product] | PT Abhipraya Nawasena Sejahtera`) and meta descriptions derived directly from corporate profile and product summaries.
- **Canonical URLs:** Environment-aware canonical tags (`<link rel="canonical" href="...">`). Filtered catalog URLs (`?category=...`) use `noindex, follow` directives and point canonical references back to the base catalog URL.
- **Bidirectional Hreflang Tags:** Reciprocal `hreflang="id"` and `hreflang="en"` tags across all dual-language pages, with `hreflang="x-default"` pointing to the Indonesian version. Products without an English slug omit the English hreflang tag rather than generating invalid fallbacks.
- **Open Graph Metadata:** Complete Open Graph tags (`og:title`, `og:description`, `og:url`, `og:image`, `og:locale`) referencing absolute public asset URLs.
- **Structured Data (JSON-LD):** Native Schema.org JSON-LD scripts for `Organization`, `WebSite`, `Product`, and `BreadcrumbList`. Product schema strictly reflects verified catalog data without speculative pricing or fake ratings.
- **Consolidated Dynamic Sitemap (`/sitemap.xml`):** Serves all 10 static localized URLs and active product detail URLs with `lastmod` timestamps and `xhtml:link` hreflang alternates.
- **Robots Directive (`/robots.txt`):** Permits crawling of public assets and PDF brochures while disallowing `/admin` and `/filament` endpoints.

---

## 9. Analytics & Telemetry (Google Analytics 4)

Behavioral measurement is architecturally decoupled from business data:

- **Strict No Direct PII Policy:** Personal identifiable information (visitor names, email addresses, phone numbers, free-text inquiry messages) is **never transmitted** to Google Analytics 4.
- **Single Source of Truth:** Business and lead data reside exclusively in MySQL; GA4 is used solely for aggregate behavioral telemetry.
- **Client-Side Google Tag:** Implemented via standard `gtag.js` in the main layout, activated in production when `GA_MEASUREMENT_ID` is configured.
- **Standard & Custom Event Schema:**
  - Standard / Enhanced: `page_view`, `first_visit`, `session_start`, `user_engagement`, `scroll`, `click`, `file_download`.
  - Custom Business Events: `view_product`, `product_filter`, `download_brochure`, `click_whatsapp`, `start_quotation`, `submit_quotation` (Primary Conversion Key Event), `language_switch`, `hero_cta_click`.
- **Non-Blocking Resilience:** Ad-blockers or analytics network timeouts do not interfere with core application functions, form submissions, or brochure downloads.

---

## 10. Security & Data Integrity

1. **CSRF & Rate Throttling:** All state-changing POST requests require valid `@csrf` tokens and are rate-limited via route throttling middleware.
2. **Spam Mitigation:** Hidden honeypot fields silently discard automated bot submissions.
3. **Server-Side File Validation:** File uploads in Filament enforce strict MIME-type rules (images: `jpg, jpeg, png, webp` max 2 MB; brochures: `application/pdf` max 10 MB).
4. **Injection Defense:** Native PDO prepared statements through Eloquent ORM eliminate SQL injection vulnerabilities, and Blade template auto-escaping prevents Cross-Site Scripting (XSS).
5. **Administrative Isolation:** Administrative access is restricted to authenticated users with secure password hashing (Bcrypt).

---

## 11. Environment Configuration

Copy `.env.example` to `.env` and configure appropriate environment variables:

```dotenv
APP_NAME="ANS Company Profile"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_URL=http://localhost

# Database Configuration (MySQL 8.0+)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=avenasa
DB_USERNAME=root
DB_PASSWORD=

# Session, Cache & Queue
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync

# Filesystem Storage
FILESYSTEM_DISK=public

# Mail Configuration (Direct SMTP)
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="no-reply@avenasa.co.id"
MAIL_FROM_NAME="PT Abhipraya Nawasena Sejahtera"
MAIL_ADMIN_ADDRESS="admin@avenasa.co.id"

# Google Analytics 4 (Production Web Stream Measurement ID)
GA_MEASUREMENT_ID=
```

---

## 12. Local Development Setup

### System Prerequisites
- **PHP:** `^8.3` with extensions: `pdo_mysql`, `mbstring`, `openssl`, `intl`, `fileinfo`, `gd`, `zip`, `dom`, `xml`.
- **Composer:** `^2.2`
- **Node.js & NPM:** Node.js `^20.x` or `^24.x`, NPM `^10.x` or `^11.x`
- **Database:** MySQL `^8.0`

### Installation Steps
1. **Clone the Repository:**
   ```bash
   git clone https://github.com/DeMaru17/avenasa.git
   cd avenasa
   ```

2. **Install PHP Dependencies:**
   ```bash
   composer install
   ```

3. **Configure Environment:**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. **Run Database Migrations:**
   Ensure the target MySQL database is created, then execute:
   ```bash
   php artisan migrate
   ```

5. **Create Storage Symlink:**
   ```bash
   php artisan storage:link
   ```

6. **Install Frontend Dependencies & Compile Assets:**
   ```bash
   npm install
   npm run build
   ```

7. **Start Development Server:**
   ```bash
   # Option A: Via local web server (e.g., Laragon at http://avenasa.test)
   # Option B: Via PHP built-in server
   php artisan serve
   ```

---

## 13. Asset Compilation (Vite & Tailwind CSS 4)

The frontend asset pipeline is powered by **Vite** and **Tailwind CSS 4.x**:

- **Development Mode (Vite Dev Server / HMR):**
  ```bash
  npm run dev
  ```
- **Production Compilation:**
  ```bash
  npm run build
  ```
  Compiled production bundles are output to `public/build/`.

---

## 14. Testing & Code Quality

The repository includes a **PHPUnit** test suite covering unit and feature specifications:

```bash
# Execute the full test suite
php artisan test

# Execute a specific test class or filter
php artisan test --filter=ExampleTest

# Format PHP codebase using Laravel Pint
vendor/bin/pint --dirty
```

---

## 15. Media Storage & Mail System

### Media Storage Organization
Media files are stored on the local public disk (`storage/app/public/`) and exposed publicly via `public/storage/`:
- `branding/` : Corporate logos and favicon.
- `banners/` : Desktop and mobile hero banner artwork.
- `products/primary/` : Primary product images.
- `products/gallery/` : Supplementary product gallery photos (`product_images`).
- `brochures/` : PDF datasheets and product brochures.
- `brands/` : Official principal and brand partner logos.
- `clients/` : Corporate client logos.
- `management/` : Executive leadership portraits.

### Mail Delivery (Direct SMTP)
- Email notifications utilize standard Laravel Mailable classes dispatched with `QUEUE_CONNECTION=sync` for direct execution on shared hosting without worker daemons.
- Mail operations are enclosed in exception-handling blocks, ensuring that SMTP timeouts or connection failures are logged without disrupting user-facing HTTP responses.

---

## 16. Shared Hosting Deployment Model (cPanel)

The application architecture is structured for standard cPanel directory segregation:

```text
Target cPanel Directory Structure:
│
├── /home/username/avenasa_core/        <── Application root (outside public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   └── .env                            <── Production config (APP_ENV=production, APP_DEBUG=false)
│
└── /home/username/public_html/         <── Web server document root
    ├── index.php                       <── Configured to load ../avenasa_core/bootstrap/app.php
    ├── .htaccess                       <── URL rewriting & HTTPS enforcement
    ├── build/                          <── Vite production assets
    ├── robots.txt                      <── Search crawler policy
    └── storage/                        <── Symlink to /home/username/avenasa_core/storage/app/public
```

### Deployment Guidelines:
1. Deploy application files (excluding the `public` directory) to `/home/username/avenasa_core/`.
2. Deploy the contents of the `public/` directory to `/home/username/public_html/`.
3. Update paths in `/home/username/public_html/index.php` to reference `../avenasa_core/vendor/autoload.php` and `../avenasa_core/bootstrap/app.php`.
4. Create the symbolic link from `/home/username/public_html/storage` to `/home/username/avenasa_core/storage/app/public`.
5. Ensure production `.env` settings: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://avenasa.co.id`.
6. Set the PHP runtime to **PHP 8.3** via cPanel MultiPHP Manager.

---

## 17. License

Intellectual property and all rights reserved by **PT Abhipraya Nawasena Sejahtera (ANS)**.
