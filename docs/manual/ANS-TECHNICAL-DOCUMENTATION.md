# DOKUMENTASI TEKNIS SISTEM
# WEBSITE PT ABHIPRAYA NAWASENA SEJAHTERA
### Dokumen Serah Terima Teknis, Arsitektur, & Panduan Pemeliharaan

---

## DAFTAR ISI

1. [System Overview & Ruang Lingkup](#1-system-overview--ruang-lingkup)
2. [Arsitektur Sistem & Struktur Direktori](#2-arsitektur-sistem--struktur-direktori)
3. [Spesifikasi Tech Stack & Dependensi](#3-spesifikasi-tech-stack--dependensi)
4. [Routing & Sistem Lokalisasi Dwibahasa (ID / EN)](#4-routing--sistem-lokalisasi-dwibahasa-id--en)
5. [Arsitektur Database & Skema Relasi Entitas](#5-arsitektur-database--skema-relasi-entitas)
6. [Arsitektur Filament CMS 5.x](#6-arsitektur-filament-cms-5x)
7. [Arsitektur Public Website & Frontend](#7-arsitektur-public-website--frontend)
8. [Manajemen Berkas & Public Storage (Hostinger Compatible)](#8-manajemen-berkas--public-storage-hostinger-compatible)
9. [Alur Transaksi Permintaan Penawaran (Quotation / Inquiry Workflow)](#9-alur-transaksi-permintaan-penawaran-quotation--inquiry-workflow)
10. [Infrastruktur Email Notifikasi & Konfirmasi](#10-infrastruktur-email-notifikasi--konfirmasi)
11. [Search Engine Optimization (SEO) & Structured Data](#11-search-engine-optimization-seo--structured-data)
12. [Google Analytics 4 (GA4) & Telemetri dataLayer](#12-google-analytics-4-ga4--telemetri-datalayer)
13. [Mekanisme Keamanan Sistem (Security Implementations)](#13-mekanisme-keamanan-sistem-security-implementations)
14. [Testing & Quality Assurance (QA)](#14-testing--quality-assurance-qa)
15. [Arsitektur Production Deployment & Hosting Setup](#15-arsitektur-production-deployment--hosting-setup)
16. [Konfigurasi Environment (.env Reference)](#16-konfigurasi-environment-env-reference)
17. [Prosedur Pencadangan Data & Disaster Recovery](#17-prosedur-pencadangan-data--disaster-recovery)
18. [Troubleshooting & Diagnostik Sistem](#18-troubleshooting--diagnostik-sistem)
19. [Catatan Pemeliharaan & Konvensi Kode](#19-catatan-pemeliharaan--konvensi-kode)

---

## 1. System Overview & Ruang Lingkup

Sistem Website PT Abhipraya Nawasena Sejahtera (ANS) adalah platform digital korporat dan katalog ilmiah yang dirancang khusus untuk memfasilitasi publikasi produk alat kesehatan, diagnostik in-vitro, dan instrumen laboratorium kelas dunia di Indonesia.

### Fitur Utama Sistem:
1. **Public B2B Corporate & Catalog Experience:** Antarmuka publik yang responsif, modern, dan dwibahasa (Bahasa Indonesia dan English) dengan fitur filter katalog terintegrasi (Kategori & Principal/Brand), pencarian, galeri foto, dan unduhan berkas brosur PDF.
2. **Contextual Quotation & Lead Capture:** Modul penangkapan prospek penawaran harga (*quotation*) yang mengikat konteks produk aktif, dilengkapi validasi ketat, sanitasi injeksi header email, anti-spam honeypot, dan proteksi throttling.
3. **Filament 5.x Headless CMS:** Panel administratif modern untuk mengelola produk, kategori, prinsipal, klien korporat, hero banner beranda, profil perusahaan, nilai inti (*core values*), dewan pimpinan, serta tiket *inquiry* dengan indikator status dan lencana notifikasi *real-time*.
4. **Automated Technical SEO & JSON-LD:** Peta situs dinamis (`sitemap.xml`), tag canonical, tag alternate hreflang timbal balik, meta OpenGraph, dan schema graph JSON-LD (Organization, WebSite, Product, BreadcrumbList).
5. **No-PII Google Analytics 4 Telemetry:** Mesin analitik terpusat (`ANSAnalytics`) yang mengirimkan 8 event kunci ke Google Analytics 4 dan `dataLayer` tanpa mengekspos data identitas pribadi (PII).
6. **Zero-Symlink Storage Architecture:** Desain sistem berkas publik yang kompatibel dengan lingkungan shared hosting (Hostinger) tanpa ketergantungan pada symbolic link Unix.

---

## 2. Arsitektur Sistem & Struktur Direktori

Sistem mengadopsi pola arsitektur **Clean MVC (Model-View-Controller)** yang diperluas dengan Service Layer dan Filament 5 Resource Schemas.

```
avenasa/
├── app/
│   ├── Filament/
│   │   └── Resources/
│   │       ├── Brands/ (Schemas, Tables, Pages)
│   │       ├── Categories/ (Schemas, Tables, Pages)
│   │       ├── Clients/ (Schemas, Tables, Pages)
│   │       ├── CompanyProfiles/ (Schemas, Tables, Pages)
│   │       ├── CoreValues/ (Schemas, Tables, Pages)
│   │       ├── HeroBanners/ (Schemas, Tables, Pages)
│   │       ├── Management/ (Schemas, Tables, Pages)
│   │       ├── Products/ (Schemas, Tables, Pages, RelationManagers)
│   │       ├── Quotations/ (Schemas, Tables, Pages)
│   │       └── Users/ (Schemas, Tables, Pages)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ContactController.php
│   │   │   ├── HomeController.php
│   │   │   ├── PageController.php
│   │   │   ├── ProductController.php
│   │   │   └── SitemapController.php
│   │   ├── Middleware/
│   │   │   └── SetLocaleMiddleware.php
│   │   └── Requests/
│   │       └── StoreQuotationRequest.php
│   ├── Mail/
│   │   ├── QuotationAdminNotificationMail.php
│   │   └── QuotationConfirmationMail.php
│   ├── Models/
│   │   ├── Brand.php, Category.php, Client.php, CompanyProfile.php,
│   │   ├── CoreValue.php, HeroBanner.php, Management.php,
│   │   ├── Product.php, ProductImage.php, Quotation.php, User.php
│   │   └── ...
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── Filament/AdminPanelProvider.php
│   └── Services/
│       └── LocalizationService.php
├── config/
│   ├── app.php, database.php, filesystems.php, mail.php, services.php, ...
├── database/
│   ├── migrations/ (13 skema migrasi tabel)
│   └── seeders/ (DatabaseSeeder & 8 seeders entitas)
├── lang/
│   ├── id.json, id/
│   └── en.json, en/
├── public/
│   ├── robots.txt, favicon assets, images/
├── resources/
│   ├── css/ (app.css dengan Tailwind v4)
│   ├── js/ (app.js, analytics.js, bootstrap.js)
│   └── views/
│       ├── components/ (seo/, contact/, layout headers/footers)
│       ├── emails/quotation/ (admin.blade.php, confirmation.blade.php)
│       ├── layouts/public.blade.php
│       ├── pages/ (home, about, partners-clients, contact, products/index, products/show)
│       └── sitemap.blade.php
├── routes/
│   └── web.php
└── tests/
    ├── Feature/ (Filament, Localization, Seeder, DomainFoundation)
    └── Unit/
```

---

## 3. Spesifikasi Tech Stack & Dependensi

Berdasarkan manifest aktual `composer.json` dan `package.json`:

### 3.1 Backend Stack
* **PHP Runtime:** PHP `^8.2` / `8.3`
* **Core Framework:** Laravel `^12.0` (Streamlined modern directory structure)
* **Admin Panel Engine:** Filament `^5.0` (Filament 5 Resource / Schemas architecture)
* **Reactive Components:** Livewire `^3.x` (Internal Filament Engine)
* **Database Driver:** MySQL (Production) / SQLite (Local/Testing)
* **Code Formatter:** Laravel Pint `^1.13`
* **Testing Framework:** PHPUnit `^11.5.3`

### 3.2 Frontend & Tooling Stack
* **Build Tool:** Vite `^6.0.11` + `@tailwindcss/vite` `^4.0.0`
* **CSS Framework:** Tailwind CSS `^4.0.0`
* **Interactivity Engine:** Alpine.js `^3.16.2`
* **HTTP Client:** Axios `^1.7.4`
* **Icon Set:** Heroicons (Blade Heroicons v2)

---

## 4. Routing & Sistem Lokalisasi Dwibahasa (ID / EN)

Seluruh rute publik diisolasi dalam *Route Group* berpola prefix locale yang divalidasi ketat oleh `SetLocaleMiddleware`.

```php
// routes/web.php
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/', function () {
    return redirect('/id', 301); // Permanent redirect ke default locale
});

Route::prefix('{locale}')
    ->where(['locale' => 'id|en'])
    ->middleware([SetLocaleMiddleware::class])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/about', [PageController::class, 'about'])->name('about');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{slug}/brochure', [ProductController::class, 'brochure'])->name('products.brochure');
        Route::get('/partners-clients', [PageController::class, 'partnersClients'])->name('partners-clients');
        Route::get('/contact', [ContactController::class, 'index'])->name('contact');
        Route::post('/contact', [ContactController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('contact.store');
    });
```

### 4.1 Mekanisme Service Lokalisasi (`LocalizationService`)
1. **Penerjemahan Slug Otomatis:** Saat beralih bahasa di halaman produk (`products.show`), service memetakan `slug_id` ke `slug_en` (atau sebaliknya).
2. **Defensive Slug Fallback:** Jika entitas produk belum memiliki terjemahan `slug_en`, sistem secara aman mengarahkan ke katalog umum (`/en/products`) tanpa memicu eror 404.
3. **Preservasi Parameter URL:** Filter kategori dan parameter query string dipertahankan saat pergantian bahasa.
4. **Canonical & Alternate Hreflang:** Menghasilkan tag `<link rel="canonical">`, `<link rel="alternate" hreflang="id">`, `<link rel="alternate" hreflang="en">`, dan `<link rel="alternate" hreflang="x-default">` (merujuk ke versi default ID).

---

## 5. Arsitektur Database & Skema Relasi Entitas

Skema database terdiri atas 13 berkas migrasi yang membangun 11 tabel entitas bisnis dan tabel infrastruktur sistem:

```
[Brand] ──1:N── [Product] ──1:N── [ProductImage]
                   │
                   ├──N:1── [Category]
                   │
                   └──1:N── [Quotation]

[CompanyProfile] (Singleton)
[CoreValue]
[Management]
[Client]
[HeroBanner]
[User]
```

### 5.1 Rincian Tabel Utama

#### 1. `products`
* `id` (BIGINT, PK, Auto Increment)
* `category_id` (FK ke `categories.id`, onDelete RESTRICT)
* `brand_id` (FK ke `brands.id`, onDelete RESTRICT)
* `name_id`, `name_en` (VARCHAR 255)
* `slug_id`, `slug_en` (VARCHAR 255, Unique Indexed)
* `summary_id`, `summary_en` (TEXT, Nullable)
* `description_id`, `description_en` (LONGTEXT, Nullable)
* `specifications` (JSON, Key-Value dwibahasa: `key_id`, `key_en`, `value_id`, `value_en`)
* `primary_image_path` (VARCHAR 255)
* `brochure_path` (VARCHAR 255, Nullable)
* `is_featured` (BOOLEAN, default false, Indexed)
* `is_active` (BOOLEAN, default true, Indexed)
* `sort_order` (INT, default 0, Indexed)
* `timestamps`

#### 2. `product_images` (Galeri Produk)
* `id` (BIGINT, PK)
* `product_id` (FK ke `products.id`, onDelete CASCADE)
* `image_path` (VARCHAR 255)
* `caption_id`, `caption_en` (VARCHAR 255, Nullable)
* `sort_order` (INT, default 0)
* `timestamps`

#### 3. `categories` & `brands`
* Kategori menyimpan nama dan slug dwibahasa (`name_id`, `name_en`, `slug_id`, `slug_en`).
* Brand menyimpan `name`, `slug`, `logo_path`, `website_url`, `is_new_principal` (boolean), `description_id`, `description_en`.

#### 4. `quotations` (Arsip Penawaran / Inquiries)
* `id` (BIGINT, PK)
* `product_id` (FK ke `products.id`, Nullable, onDelete SET NULL)
* `name` (VARCHAR 255)
* `email` (VARCHAR 255)
* `phone` (VARCHAR 50, Nullable)
* `company` (VARCHAR 255, Nullable)
* `subject` (VARCHAR 255)
* `message` (TEXT)
* `status` (VARCHAR 50, Indexed: `'New'`, `'Contacted'`, `'Quoted'`, `'Closed'`)
* `locale` (VARCHAR 10, default `'id'`)
* `admin_notes` (TEXT, Nullable — Khusus Internal Admin)
* `timestamps`

#### 5. Konten Korporat & Banner
* `company_profiles`: Data singleton profil perusahaan, visi, misi, alamat, koordinat maps, kontak.
* `core_values`: 6 nilai komitmen perusahaan dengan atribut `icon_name` terkurasi.
* `managements`: Data jajaran direksi & komisaris (`name`, `photo_path`, `position_id`, `position_en`, `bio_id`, `bio_en`, `is_active`).
* `clients`: Data mitra institusi/klien (`name`, `logo_path`, `is_active`, `sort_order`).
* `hero_banners`: Data slider beranda (`title_id`, `title_en`, `subtitle_id`, `subtitle_en`, `image_path`, `mobile_image_path`, `button_text_id`, `button_text_en`, `button_url`, `is_active`, `sort_order`).

---

## 6. Arsitektur Filament CMS 5.x

Admin panel dikonfigurasi melalui `AdminPanelProvider` pada path `/admin` dengan tema warna `Amber` dan ikon heroicons.

### 6.1 Daftar 10 Filament Resources
1. **CategoryResource (`app/Filament/Resources/Categories`)**
   * Grup: *Catalog Management* (Sort: 1)
   * Aksi Hapus: Dibatalkan jika terdapat relasi produk aktif.
2. **BrandResource (`app/Filament/Resources/Brands`)**
   * Grup: *Catalog Management* (Sort: 2)
   * Aksi Hapus: Dibatalkan jika terdapat relasi produk aktif.
3. **ProductResource (`app/Filament/Resources/Products`)**
   * Grup: *Catalog Management* (Sort: 3)
   * Relation Manager: `ImagesRelationManager` (Manajemen galeri foto produk).
   * Aksi Hapus: Ditolak jika produk memiliki riwayat `quotations`.
4. **HeroBannerResource (`app/Filament/Resources/HeroBanners`)**
   * Grup: *Homepage* (Sort: 1)
   * Validasi Regex: `button_url` wajib diawali tanda `/` (misal `/products`).
5. **CompanyProfileResource (`app/Filament/Resources/CompanyProfiles`)**
   * Grup: *Company Content* (Sort: 1)
   * Sifat: Singleton (non-deletable).
6. **CoreValueResource (`app/Filament/Resources/CoreValues`)**
   * Grup: *Company Content* (Sort: 2)
   * Komponen Khusus: Select icon dengan preview SVG Heroicon langsung pada antarmuka admin.
7. **ManagementResource (`app/Filament/Resources/Management`)**
   * Grup: *Company Content* (Sort: 3)
8. **ClientResource (`app/Filament/Resources/Clients`)**
   * Grup: *Company Content* (Sort: 4)
9. **QuotationResource (`app/Filament/Resources/Quotations`)**
   * Grup: *Quotation / Inquiry Management* (Sort: 1)
   * **Badge Counter:** Menampilkan lencana merah berisi jumlah tiket berstatus `New`.
   * **Retention Security:** Tidak menyediakan aksi hapus (non-deletable).
10. **UserResource (`app/Filament/Resources/Users`)**
    * Grup: *Settings* (Sort: 10)
    * Logika Password: Hashing otomatis BCRYPT, diabaikan saat edit jika input kosong.

---

## 7. Arsitektur Public Website & Frontend

Frontend dibangun dengan pendekatan *Component-Driven Architecture* menggunakan Laravel Blade + Tailwind CSS v4 + Alpine.js:

* **Layout Dasar (`resources/views/layouts/public.blade.php`):**
  * Memuat `x-seo.meta-head` untuk injeksi SEO terpusat.
  * Memuat inisialisasi aman Google Analytics 4 dan `dataLayer`.
  * Memuat navigasi aksesibilitas keyboard (WCAG 2.2 AA skip link).
* **Komponen Bersarang (`resources/views/components/`):**
  * `header.blade.php`: Navigasi desktop, drawer menu mobile, dan language switcher.
  * `footer.blade.php`: Informasi legal korporat, kontak cepat, dan floating CTA WhatsApp.
  * `contact/form-shell.blade.php`: Formulir penawaran harga dengan validasi realtime, honeypot, dan event tracker.

---

## 8. Manajemen Berkas & Public Storage (Hostinger Compatible)

Pada lingkungan shared hosting standar (Hostinger), eksekusi perintah symlink `php artisan storage:link` kerap dibatasi atau terisolasi. Oleh karena itu, arsitektur penyimpanan berkas dikonfigurasi melalui disk `public` di `config/filesystems.php`:

```php
'public' => [
    'driver' => 'local',
    'root' => env('FILESYSTEM_PUBLIC_ROOT', storage_path('app/public')),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
    'throw' => false,
    'report' => false,
],
```

### 8.1 Direktori Penyimpanan Logis:
* **Foto Utama Produk:** `products/primary/` (Maks 2 MB, format JPG/PNG/WebP).
* **Galeri Tambahan Produk:** `products/gallery/` (Maks 2 MB).
* **Brosur Resmi Produk:** `brochures/` (Maks 10 MB, format PDF).
* **Logo Brand / Principal:** `brands/` (Maks 2 MB, format PNG/WebP transparan).
* **Logo Klien Korporat:** `clients/` (Maks 2 MB, format PNG/WebP).
* **Foto Profil Pimpinan:** `management/` (Maks 2 MB, format JPG/PNG/WebP).
* **Hero Banner Beranda:** `hero-banners/` (Maks 2 MB, format JPG/PNG/WebP).

---

## 9. Alur Transaksi Permintaan Penawaran (Quotation / Inquiry Workflow)

```
[ Pengunjung Website Mengisi Form ]
                │
                ▼
  [ POST /{locale}/contact ] ── (Rate Limit: 5 req/min via Throttle Middleware)
                │
                ▼
  [ StoreQuotationRequest ]
     ├─ Sanitasi Mail Header Injection (\r, \n)
     ├─ Validasi Format RFC Email & Batas Panjang String
     └─ Pengecekan Honeypot (website_url_hp)
                │
         (Honeypot Terisi?) ─── YES ───► [ Drop Diam-diam (Silent 302 Redirect) ]
                │ NO
                ▼
  [ 1. Database Persistence ] ── (Single Source of Truth -> Status 'New')
                │
                ├─────────────────────────────┬─────────────────────────────┐
                ▼                             ▼                             ▼
  [ 2. Isolated Admin Mail ]    [ 3. Isolated User Mail ]    [ 4. Telemetri GA4 ]
  (Try-Catch: Gagal kirim       (Try-Catch: Gagal kirim       (Flash session non-PII
   tidak membatalkan DB)         tidak membatalkan DB)         payload ke browser)
```

---

## 10. Infrastruktur Email Notifikasi & Konfirmasi

### 10.1 Notifikasi Admin (`QuotationAdminNotificationMail`)
* **Tujuan:** Mengirimkan data lengkap inquiry calon klien ke alamat admin (`MAIL_ADMIN_ADDRESS`).
* **Subjek:** `[New Inquiry] - {Subject} - {Customer Name}`
* **Fitur Reply-To:** Header `Reply-To` diatur secara otomatis ke email dan nama calon klien, sehingga tim sales ANS dapat langsung membalas (*Reply*) dari aplikasi email client tanpa menyalin ulang alamat email.

### 10.2 Konfirmasi Calon Klien (`QuotationConfirmationMail`)
* **Tujuan:** Memberikan bukti tanda terima profesional kepada calon klien.
* **Subjek Dwibahasa:** Menyesuaikan bahasa formulir yang digunakan (`ID` atau `EN`).

---

## 11. Search Engine Optimization (SEO) & Structured Data

Sistem menerapkan arsitektur Technical SEO otomatis:
1. **Dynamic XML Sitemap (`/sitemap.xml`):** Menghasilkan indeks seluruh halaman publik statis dan seluruh entitas produk yang aktif (`is_active = true`). Produk nonaktif dan rute admin otomatis dikecualikan.
2. **Robots Exclusion Standard (`robots.txt`):** Mengizinkan perayapan halaman publik, melarang perayapan `/admin` dan `/filament`, serta mendeklarasikan lokasi sitemap.
3. **Penanganan Parameter Noindex:** Rute filter katalog (`/products?category=...`) dan rute kontak kontekstual (`/contact?product_id=...`) secara otomatis menyuntikkan `<meta name="robots" content="noindex, follow">` dan mengarahkan canonical ke URL dasar guna mencegah duplikasi konten (*canonical index consolidation*).
4. **JSON-LD Structured Data Graph:**
   * `@type: Organization` (Identitas resmi PT Abhipraya Nawasena Sejahtera, logo, kontak, alamat terstruktur).
   * `@type: WebSite` (URL kanonikal dan bahasa).
   * `@type: Product` (Injeksi data produk resmi pada halaman detail tanpa atribut harga/ulasan fiktif).
   * `@type: BreadcrumbList` (Navigasi breadcrumb halaman).

---

## 12. Google Analytics 4 (GA4) & Telemetri dataLayer

Implementasi analitik dikendalikan melalui `resources/js/analytics.js` (`ANSAnalytics`) dengan standar kepatuhan privasi ketat (**Strict No-PII Policy**).

### 8 Event Kunci yang Diimplementasikan:
1. `view_product`: Ditembakkan saat pengunjung membuka halaman detail produk. Parameter: `product_id`, `product_name`, `locale`, `category_name`, `brand_name`.
2. `product_filter`: Ditembakkan saat pengunjung menyaring katalog. Parameter: `filter_type`, `locale`, `category_slug`, `brand_slug`.
3. `download_brochure`: Ditembakkan saat tombol unduh brosur PDF diklik. Parameter: `product_id`, `product_name`, `locale`, `file_format`.
4. `click_whatsapp`: Ditembakkan saat tautan WhatsApp resmi diklik. Parameter: `source_page`, `locale`, `product_id`. (Bebas dari nomor telepon/pesan teks).
5. `start_quotation`: Ditembakkan saat pengunjung mulai berinteraksi dengan formulir penawaran harga. Dilengkapi deduplikasi perjalanan sesi (`sessionStorage`).
6. `submit_quotation` *(Primary Key Event / Conversion)*: Ditembakkan setelah data quotation berhasil tersimpan ke database. Parameter: `product_id`, `has_company`, `source`, `locale`.
7. `language_switch`: Ditembakkan saat pengunjung menukar bahasa antarmuka. Parameter: `source_locale`, `target_locale`, `current_path`.
8. `hero_cta_click`: Ditembakkan saat tombol aksi slider banner diklik. Parameter: `banner_id`, `locale`, `cta_type`, `destination_type`.

---

## 13. Mekanisme Keamanan Sistem (Security Implementations)

1. **CSRF Protection:** Seluruh formulir POST dilindungi token validasi CSRF Laravel.
2. **Mail Header Injection Defense:** Seluruh input formulir disaring dari karakter *carriage return* (`\r`) dan *newline* (`\n`) sebelum diolah mailable.
3. **Anti-Spam Honeypot:** Kolom tersembunyi `website_url_hp` yang tidak terlihat oleh manusia digunakan untuk menjebak bot otomatis dan membuang submission secara senyap (*silent drop*).
4. **Rate Limiting (Throttling):** Pengiriman formulir quotation dibatasi maksimal 5 permintaan per menit per alamat IP (`throttle:5,1`).
5. **Database Integrity & Restrict Deletion:** Relasi kategori-produk dan brand-produk dilindungi aturan *foreign key* dan interceptor Filament untuk mencegah *orphan records*.
6. **XSS Escaping:** Seluruh output teks pada antarmuka publik dan meta tag dibungkus escape helper `e()` / `{{ }}`.
7. **Credential Protection:** Kredensial database, mail server, dan app key terisolasi penuh pada file konfigurasi environment `.env` di luar document root publik.

---

## 14. Testing & Quality Assurance (QA)

Sistem memiliki cakupan pengujian komprehensif menggunakan PHPUnit dan Feature Test:

### Perintah Uji Standar Developer:
```bash
# Menjalankan seluruh rangkaian test
php artisan test --compact

# Menjalankan test spesifik Feature
php artisan test tests/Feature/Localization/QuotationInquiryTest.php
php artisan test tests/Feature/Localization/SeoAndAnalyticsTest.php
php artisan test tests/Feature/Filament/FilamentResourceTest.php

# Memformat kode sesuai standar PSR-12 / Laravel Pint
vendor/bin/pint --format agent

# Melakukan kompilasi bundle aset frontend
npm run build
```

---

## 15. Arsitektur Production Deployment & Hosting Setup

Pada lingkungan production Hostinger, sistem menggunakan struktur direktori terpisah untuk keamanan maksimal:

```
/home/u123456789/
├── application/             <-- Seluruh source code Laravel (Private, Di luar web root)
│   ├── app/
│   ├── config/
│   ├── vendor/
│   ├── .env
│   └── ...
└── public_html/             <-- Web Document Root Publik
    ├── index.php            <-- Memuat bootstrap/app.php dari ../application/
    ├── .htaccess
    ├── build/               <-- Hasil kompilasi Vite (assets CSS & JS)
    ├── storage/             <-- Berkas unggahan publik (produk, brosur, logo)
    ├── robots.txt
    └── images/
```

### 15.1 Konfigurasi `public_html/index.php`:
```php
require __DIR__.'/../application/vendor/autoload.php';
$app = require_once __DIR__.'/../application/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();
$kernel->terminate($request, $response);
```

---

## 16. Konfigurasi Environment (.env Reference)

Berikut adalah struktur variabel konfigurasi `.env` production (tanpa memuat kredensial sensitif):

```ini
APP_NAME="ANS Company Profile"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://avenasa.co.id

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_avenasa
DB_USERNAME=user_avenasa
DB_PASSWORD=YOUR_STRONG_DB_PASSWORD

SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=database

FILESYSTEM_DISK=public
FILESYSTEM_PUBLIC_ROOT=/home/u123456789/public_html/storage

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=admin@avenasa.co.id
MAIL_PASSWORD=YOUR_SMTP_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="admin@avenasa.co.id"
MAIL_FROM_NAME="PT Abhipraya Nawasena Sejahtera"
MAIL_ADMIN_ADDRESS=admin@avenasa.co.id

GA_MEASUREMENT_ID=G-606RT4D574
```

---

## 17. Prosedur Pencadangan Data & Disaster Recovery

Untuk menjamin ketersediaan sistem (*high availability*), lakukan pencadangan rutin:
1. **Backup Database:** Lakukan export dump database SQL secara berkala (minimal mingguan atau sebelum melakukan perubahan katalog massal).
2. **Backup Media Publik:** Unduh arsip folder `public_html/storage/` yang berisi seluruh foto produk, galeri, brosur PDF, dan logo klien.
3. **Backup Konfigurasi:** Simpan salinan file `.env` production di tempat penyimpanan terenkripsi yang aman.
4. **Kebijakan Retensi Quotation:** Jangan pernah memanipulasi atau mengosongkan tabel `quotations` secara langsung di database demi menjaga validitas laporan audit prospek masuk.

---

## 18. Troubleshooting & Diagnostik Sistem

| Gejala Masalah | Kemungkinan Penyebab | Tindakan Pemeriksaan & Solusi |
| :--- | :--- | :--- |
| **Website menampilkan HTTP 500** | Konfigurasi `.env` keliru, izin folder `storage/logs` terblokir, atau database down. | Periksa log eror di `application/storage/logs/laravel.log`. Pastikan izin folder penyimpanan `chmod 755` atau `chmod 775`. |
| **Berkas upload tidak muncul di web** | Konfigurasi `FILESYSTEM_PUBLIC_ROOT` di `.env` tidak mengarah ke `public_html/storage`. | Sesuaikan path absolut `FILESYSTEM_PUBLIC_ROOT` pada `.env` agar tepat mengarah ke folder publik server. |
| **Email Quotation gagal terkirim** | Port SMTP, password email, atau kuota kirim email Hostinger terblokir. | Periksa log SMTP di `storage/logs/laravel.log`. Verifikasi kredensial email di cPanel/hPanel Hostinger. Record database quotation tetap aman tersimpan. |
| **Perubahan layout CSS tidak muncul** | Cache Vite atau Cloudflare/Browser cache aktif. | Jalankan `npm run build` di server, bersihkan cache peramban dengan `Ctrl + F5`. |
| **Admin Panel gagal login** | Sesi browser usang atau CSRF expired. | Bersihkan cookie sesi browser dan coba login kembali di `/admin/login`. |

---

## 19. Catatan Pemeliharaan & Konvensi Kode

1. **Filament 5.x Compliance:** Seluruh penambahan form atau kolom tabel wajib mengikuti arsitektur Filament 5 Schemas & Tables. Jangan menggunakan komponen usang Filament 3 atau Filament 4.
2. **Formatting Standard:** Selalu jalankan `vendor/bin/pint --format agent` sebelum melakukan commit kode PHP baru untuk menjaga konsistensi format PSR-12.
3. **Penambahan Produk Baru:** Pastikan setiap penambahan produk baru menyertakan `name_id` dan `name_en` agar kedua versi bahasa website dapat menampilkannya secara optimal.

---
*Dokumentasi Teknis Sistem PT Abhipraya Nawasena Sejahtera — Disusun untuk Tim IT & System Administrator (Agustus 2026)*
