# Feature Specification: SEO & Discoverability

**Feature ID:** `SPEC-10-SEO`  
**Feature Name:** SEO & Discoverability  
**Proyek:** Website Company Profile & Katalog Produk  
**Klien:** PT Abhipraya Nawasena Sejahtera (ANS)  
**Dokumen Referensi & Sumber Kebenaran:**
1. [docs/architecture/technology-baseline.md](file:///c:/laragon/www/avenasa/docs/architecture/technology-baseline.md)
2. [docs/architecture/system-design.md](file:///c:/laragon/www/avenasa/docs/architecture/system-design.md)
3. [docs/urs/ans-website-urs.md](file:///c:/laragon/www/avenasa/docs/urs/ans-website-urs.md)
4. [docs/feature-specs/catalog-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/catalog-management.md)
5. [docs/feature-specs/quotation-inquiry-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/quotation-inquiry-management.md)
6. [docs/feature-specs/localization.md](file:///c:/laragon/www/avenasa/docs/feature-specs/localization.md)
7. [docs/feature-specs/public-catalog-experience.md](file:///c:/laragon/www/avenasa/docs/feature-specs/public-catalog-experience.md)
8. [docs/feature-specs/public-company-website.md](file:///c:/laragon/www/avenasa/docs/feature-specs/public-company-website.md)
9. [docs/feature-specs/hero-banner-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/hero-banner-management.md)
10. [docs/feature-specs/public-product-detail-experience.md](file:///c:/laragon/www/avenasa/docs/feature-specs/public-product-detail-experience.md)
11. [docs/feature-specs/analytics.md](file:///c:/laragon/www/avenasa/docs/feature-specs/analytics.md)  
**Status Dokumen:** Ready for Final Review

---

## 1. Executive Summary & SEO Philosophy

Fitur **SEO & Discoverability** mendefinisikan arsitektur penemuan (*discoverability*), pengindeksan mesin pencari (*search engine indexing*), pencegahan duplikasi konten (*duplicate content prevention*), metadata media sosial (*Open Graph*), penataan data terstruktur (*Structured Data / JSON-LD*), peta situs (*sitemap.xml*), dan kebijakan perayapan (*robots.txt*) untuk seluruh halaman publik website PT Abhipraya Nawasena Sejahtera (ANS).

### 1.1. Prinsip Utama Arsitektur SEO
1. **Server-Rendered HTML sebagai Pondasi Utama:** Seluruh konten penting, metadata, dan tautan di-render langsung oleh Laravel Blade di server sehingga dapat di-crawl secara instan tanpa bergantung pada eksekusi JavaScript sisi klien.
2. **URL Bersih, Stabil, dan Terlokalisasi Ketat:** Setiap halaman memiliki canonical URL dwibahasa berbasis prefix rute (`/id/...` dan `/en/...`) dan slug terlokalisasi tanpa query parameter bahasa.
3. **Pemisahan Bahasa yang Presisi (Hreflang Reciprocal):** Versi Bahasa Indonesia dan Bahasa Inggris diperlakukan sebagai padanan halaman yang saling mereferensikan melalui tag `hreflang` dan `x-default`.
4. **Data Driven dari Model CMS yang Ada (*No Speculative Database Fields*):** Seluruh title, description, dan image diturunkan secara cerdas dari data model CMS yang sudah ada (`CompanyProfile`, `Product`, `Category`, `Brand`) tanpa menambahkan tabel SEO baru atau plugin CMS yang rumit.
5. **Shared Hosting Compliance:** Seluruh mekanisme SEO, pembuatan sitemap dinamis, dan robots.txt berjalan murni via native routing Laravel tanpa membutuhkan background daemon, Redis, Elasticsearch, Node.js production server, atau external paid SEO service.

---

## 2. Public SEO Page Inventory & Indexing Strategy

Berikut adalah inventaris seluruh halaman publik beserta strategi pengindeksan, canonical, dan ketercakupannya dalam sitemap:

| Modul Halaman | Rute ID | Rute EN | Status Indeks | Strategi Kanonikal | Masuk Sitemap V1? |
|---|---|---|---|---|---|
| **Beranda (Home)** | `/id` | `/en` | `index, follow` | Self-canonical (`/id` / `/en`) | Ya |
| **Tentang Kami (About)** | `/id/about` | `/en/about` | `index, follow` | Self-canonical (`/id/about` / `/en/about`) | Ya |
| **Katalog Utama (Tanpa Filter)** | `/id/products` | `/en/products` | `index, follow` | Self-canonical (`/id/products` / `/en/products`) | Ya |
| **Katalog Paginasi Halaman (`?page=N`)** | `/id/products?page=N` | `/en/products?page=N` | `index, follow` | Self-canonical ke URL berpaginasi | **Tidak (Hanya Base Catalog)** |
| **Katalog Terfilter (`?category=...&brand=...`)** | `/id/products?...` | `/en/products?...` | `noindex, follow` | Canonical menunjuk ke Base Catalog (`/id/products`) | **Tidak** |
| **Detail Produk (Product Detail)** | `/id/products/{slug_id}` | `/en/products/{slug_en}` | `index, follow` | Self-canonical (`/id/products/{slug_id}`) | **Ya (Hanya Produk Aktif)** |
| **Mitra & Klien (Partners & Clients)** | `/id/partners-clients` | `/en/partners-clients` | `index, follow` | Self-canonical (`/id/partners-clients`) | Ya |
| **Kontak & Form Penawaran Umum** | `/id/contact` | `/en/contact` | `index, follow` | Self-canonical (`/id/contact`) | Ya |
| **Kontak dengan Konteks Produk (`?product_id=N`)**| `/id/contact?product_id=N` | `/en/contact?product_id=N`| `noindex, follow` | Canonical menunjuk ke Base Contact (`/id/contact`)| **Tidak** |

---

## 3. Canonical URL & Hreflang Architecture

### 3.1. Canonical URL Strategy & Environment URL Resolution
- Setiap halaman publik yang dapat diindeks menyajikan tag `<link rel="canonical" href="...">` absolut yang dibangun secara dinamis berbasis konfigurasi aplikasi yang otoritatif (misal: `config('app.url')`).
- **Larangan Hardcoded Domain:** Domain produksi `https://avenasa.co.id` dalam dokumentasi ini murni merupakan contoh ilustratif. Kode implementasi **DILARANG KERAS MENGEKOR/HARDCODE DOMAIN STRING** pada Blade, controller, atau helper. Lingkungan lokal pengujian harus menghasilkan URL lokal (misal: `http://localhost/...`) secara tepat tanpa tercampur URL produksi.
- Versi `/id/...` menunjuk kanonikal ke dirinya sendiri (`{APP_URL}/id/...`).
- Versi `/en/...` menunjuk kanonikal ke dirinya sendiri (`{APP_URL}/en/...`).
- Halaman kontak berkonteks produk (`/contact?product_id=N`) dan katalog hasil filter menyajikan canonical yang menunjuk kembali ke URL dasar masing-masing guna mengonsolidasikan *link equity* dan mencegah *crawl bloat*.

### 3.2. Hreflang Tags & Cross-Language Linking
Setiap halaman publik dwibahasa menyajikan tag relasi bahasa timbal balik (*reciprocal hreflang tags*):

```html
<!-- Contoh pada Halaman Beranda ID (/id) dan EN (/en) -->
<link rel="alternate" hreflang="id" href="{APP_URL}/id" />
<link rel="alternate" hreflang="en" href="{APP_URL}/en" />
<link rel="alternate" hreflang="x-default" href="{APP_URL}/id" />

<!-- Contoh pada Halaman Detail Produk ID (/id/products/alat-pcr) -->
<link rel="alternate" hreflang="id" href="{APP_URL}/id/products/alat-pcr" />
<link rel="alternate" hreflang="en" href="{APP_URL}/en/products/real-time-pcr" />
<link rel="alternate" hreflang="x-default" href="{APP_URL}/id/products/alat-pcr" />
```

- **Kebijakan `x-default`:** Dialokasikan ke versi Bahasa Indonesia (`/id/...`) sebagai bahasa bisnis utama PT Abhipraya Nawasena Sejahtera.
- **Aturan Hreflang Produk & Ketiadaan Slug Counterpart:**
  - Jika produk memiliki `slug_id` dan `slug_en` yang valid: Sistem merender tag `hreflang="id"` dan `hreflang="en"` secara timbal balik (*reciprocal*).
  - Jika produk hanya memiliki `slug_id` dan belum memiliki `slug_en` yang valid: Sistem **HANYA MERENDER tag `hreflang="id"`** dan `x-default` ke rute ID tersebut.
  - **DILARANG KERAS** membuat tag hreflang EN fiktif atau mengarahkan hreflang EN ke halaman katalog induk `/en/products` karena keduanya bukan halaman dengan konten yang sama.

---

## 4. Localized Meta Title & Meta Description Generation

Metadata SEO dihasilkan secara terpusat oleh helper/layout Blade tanpa membuat tabel database khusus:

### 4.1. Meta Title Generation Rules
- **Format Umum:** `[Judul Halaman / Nama Produk] | PT Abhipraya Nawasena Sejahtera` (atau ringkasan `ANS`).
- **Home (ID):** `PT Abhipraya Nawasena Sejahtera - Distributor Alat Kesehatan & Laboratorium`
- **Home (EN):** `PT Abhipraya Nawasena Sejahtera - Medical & Laboratory Equipment Distributor`
- **About (ID / EN):** `Tentang Kami | PT Abhipraya Nawasena Sejahtera` / `About Us | PT Abhipraya Nawasena Sejahtera`
- **Catalog (ID / EN):** `Katalog Produk Medis & Laboratorium | PT Abhipraya Nawasena Sejahtera` / `Medical & Laboratory Product Catalog | PT Abhipraya Nawasena Sejahtera`
- **Product Detail (ID):** `[name_id] - [brand_name] | PT Abhipraya Nawasena Sejahtera`
- **Product Detail (EN):** `[name_en] - [brand_name] | PT Abhipraya Nawasena Sejahtera`
- **Partners & Clients (ID / EN):** `Mitra Prinsipal & Klien | PT Abhipraya Nawasena Sejahtera` / `Principals & Clients | PT Abhipraya Nawasena Sejahtera`
- **Contact (ID / EN):** `Hubungi Kami & Permintaan Penawaran | PT Abhipraya Nawasena Sejahtera` / `Contact Us & Quotation Request | PT Abhipraya Nawasena Sejahtera`

### 4.2. Meta Description Generation Rules (Menggunakan Field Otoritatif & Editorial)
- **Home:** Menggunakan ringkasan profil pengantar dan tagline resmi dari model singleton `CompanyProfile` dwibahasa (`tagline_id` / `tagline_en`), dengan fallback salinan editorial resmi jika field bernilai kosong.
- **About:** Menggunakan narasi visi dan komitmen korporat yang bersumber langsung dari `CompanyProfile` (`vision_id` / `vision_en`).
- **Catalog:** Menggunakan deskripsi editorial terstandarisasi yang ditetapkan untuk lembar katalog produk pada tahap implementasi (tanpa membuat CMS page builder).
- **Product Detail:** Menggunakan `summary_id` / `summary_en` produk (tersedia pada skema `products` SPEC-01). Jika summary kosong, fallback menggunakan pemotongan bersih teks deskripsi `description_id` / `description_en` tanpa tag HTML (*plain text snippet* max 160 karakter).
- **Contact:** Menggunakan informasi kontak otoritatif resmi perusahaan (alamat Mensana Tower Cibubur, nomor telepon, dan email resmi ANS).

> [!NOTE]
> Seluruh metadata deskripsi diturunkan langsung dari *single source of truth* data perusahaan (`CompanyProfile`) dan model katalog (`Product`) atau copy editorial terdefinisi tanpa membuat tabel database baru, kolom SEO spekulatif, atau menduplikasi data bisnis mentah di berbagai controller/helper.

---

## 5. Open Graph & Social Sharing Metadata

Setiap halaman menyajikan tag Open Graph standar untuk menghasilkan tampilan cuplikan (*social snippet card*) yang profesional saat tautan dibagikan di media komunikasi:

```html
<meta property="og:site_name" content="PT Abhipraya Nawasena Sejahtera" />
<meta property="og:type" content="website" />
<meta property="og:title" content="[Localized Page Title]" />
<meta property="og:description" content="[Localized Meta Description]" />
<meta property="og:url" content="[Absolute Canonical URL]" />
<meta property="og:locale" content="id_ID" /> <!-- 'en_US' pada locale EN -->
<meta property="og:locale:alternate" content="en_US" /> <!-- 'id_ID' pada locale EN -->
<meta property="og:image" content="[Absolute Public Image URL]" />
```

### 5.1. Aturan Penentuan Gambar Open Graph (`og:image`)
- **Product Detail:** Menggunakan URL publik absolut dari foto utama produk (`asset('storage/' . $product->primary_image_path)`).
- **Halaman Lain (Home, About, Catalog, Contact):** Menggunakan asset korporat publik resmi yang ditetapkan sebagai *default social sharing image* pada tahap implementasi (misal: logo/banner resmi di public directory).
- **Integritas URL Gambar:** Tag `og:image` selalu disajikan dalam bentuk absolute URL yang valid berbasis konfigurasi aplikasi dan tidak pernah mengekspos local filesystem path internal server.

---

## 6. Structured Data (JSON-LD Schema.org)

Sistem menyematkan skrip data terstruktur berbasis format JSON-LD (`<script type="application/ld+json">`) secara dinamis:

### 6.1. Schema `Organization` & `WebSite` (Bersumber Murni dari Data Otoritatif)
Seluruh data entitas bisnis perusahaan pada JSON-LD **wajib diturunkan secara dinamis** dari model singleton `CompanyProfile` dan konfigurasi aplikasi resmi (`config('app.url')`), tanpa membuat data statis hardcoded, tabel database baru, atau field spekulatif:

```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "{APP_URL}/#organization",
      "name": "[CompanyProfile.company_name]",
      "url": "{APP_URL}",
      "logo": "{LOGO_URL}",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "[CompanyProfile.phone]",
        "contactType": "customer service",
        "areaServed": "ID",
        "availableLanguage": ["Indonesian", "English"]
      },
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "[CompanyProfile.address_street]",
        "addressLocality": "[CompanyProfile.address_city]",
        "addressRegion": "[CompanyProfile.address_province]",
        "postalCode": "[CompanyProfile.address_postal_code]",
        "addressCountry": "ID"
      }
    },
    {
      "@type": "WebSite",
      "@id": "{APP_URL}/#website",
      "url": "{APP_URL}",
      "name": "[CompanyProfile.company_name]",
      "publisher": { "@id": "{APP_URL}/#organization" },
      "inLanguage": ["id-ID", "en-US"]
    }
  ]
}
```
*(Catatan: Jika property tertentu pada CompanyProfile bernilai null, property tersebut dihilangkan secara anggun (*graceful omission*) dari output JSON-LD tanpa mengarang data fiktif).*

### 6.2. Schema `Product` (Khusus Halaman Detail Produk)
Disematkan hanya jika produk memiliki data yang valid.
> [!IMPORTANT]
> **Larangan Data Fiktif:** Website ANS adalah distributor B2B alat laboratorium/medis yang menggunakan alur penawaran harga (*Inquiry by Request*). Sistem **DILARANG MENGARANG** harga palsu (`price`), stok fiktif, SKU karangan, bintang review palsu (`aggregateRating`), atau barcode palsu (`GTIN`). Schema `Product` disajikan secara jujur merepresentasikan data yang terlihat di layar:
```json
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "[Product.name_localized]",
  "image": ["[Product.primary_image_absolute_url]"],
  "description": "[Product.summary_or_description_localized]",
  "brand": {
    "@type": "Brand",
    "name": "[Brand.name]"
  },
  "category": "[Category.name_localized]"
}
```

### 6.3. Schema `BreadcrumbList` (Halaman Katalog & Detail Produk)
Disematkan untuk mendukung *rich snippet breadcrumb* di hasil pencarian Google:
```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Beranda",
      "item": "{APP_URL}/id"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Produk",
      "item": "{APP_URL}/id/products"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "[Product.name_localized]",
      "item": "{APP_URL}/id/products/[Product.slug_localized]"
    }
  ]
}
```

---

## 7. Dynamic Sitemap (`/sitemap.xml`) Architecture

Peta situs XML dilayani secara dinamis oleh route controller Laravel tanpa menyimpan file statis atau membuat tabel database sitemap khusus:

### 7.1. Struktur Sitemap XML Bilingual V1
Sitemap V1 berfokus murni pada canonical resources publik yang esensial:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
  <!-- 10 Static Base Pages (Home, About, Base Products, Partners, Contact - ID & EN) -->
  <url>
    <loc>{APP_URL}/id</loc>
    <xhtml:link rel="alternate" hreflang="id" href="{APP_URL}/id"/>
    <xhtml:link rel="alternate" hreflang="en" href="{APP_URL}/en"/>
  </url>
  <url>
    <loc>{APP_URL}/en</loc>
    <xhtml:link rel="alternate" hreflang="en" href="{APP_URL}/en"/>
    <xhtml:link rel="alternate" hreflang="id" href="{APP_URL}/id"/>
  </url>

  <!-- Dynamic Active Products (Hanya Produk Aktif dengan Localized Slug Valid) -->
  <url>
    <loc>{APP_URL}/id/products/alat-pcr-real-time</loc>
    <xhtml:link rel="alternate" hreflang="id" href="{APP_URL}/id/products/alat-pcr-real-time"/>
    <xhtml:link rel="alternate" hreflang="en" href="{APP_URL}/en/products/real-time-pcr-system"/>
    <lastmod>2026-08-16</lastmod>
  </url>
</urlset>
```
*(Catatan: Tag `changefreq` dan `priority` bersifat opsional dan bukan sinyal utama ranking Google; sitemap V1 dijaga tetap ramping dan efisien).*

### 7.2. Aturan Penyaringan URL pada Sitemap V1 (*Inclusion Rules*)
1. **Static Sitemap Entries (Tepat 10 Localized URLs):**
   - Beranda: `/id` & `/en`
   - Tentang Kami: `/id/about` & `/en/about`
   - Katalog Utama: `/id/products` & `/en/products`
   - Mitra & Klien: `/id/partners-clients` & `/en/partners-clients`
   - Kontak & Permintaan Penawaran: `/id/contact` & `/en/contact`
2. **Dynamic Sitemap Entries (Localized Active Product Details):**
   - URL `/id/products/{slug_id}` untuk setiap record produk aktif (`is_active = true`) yang memiliki `slug_id` valid.
   - URL `/en/products/{slug_en}` untuk setiap record produk aktif (`is_active = true`) yang memiliki `slug_en` valid.
   - *Catatan:* Jumlah total entri dinamis bergantung murni pada jumlah record produk aktif di database MySQL.
3. **Pengecualian Mutlak (*Exclusions*):**
   - **DILARANG KERAS MEMASUKKAN:** Produk nonaktif (`is_active = false`), endpoint admin Filament (`/admin`), rute submit form, rute utilitas internal, halaman sukses submit form, URL filter katalog (`?category=...`), URL paginasi katalog (`?page=N`), dan URL kontak berkonteks produk (`?product_id=N`).

---

## 8. Robots Policy (`/robots.txt`)

File `/robots.txt` dilayani secara dinamis atau statis pada root domain publik untuk memandu bot perayap (*search engine crawlers*):

```txt
User-agent: *
Allow: /
Disallow: /admin
Disallow: /filament
Disallow: /*?*product_id=*

Sitemap: {APP_URL}/sitemap.xml
```

### 8.1. Ketentuan Kebijakan Robots
1. **Akses Publik Diizinkan Penuh:** Bot mesin pencari diizinkan mengakses seluruh aset publik CSS, JS, font, dan gambar produk.
2. **Ketiadaan Pembatasan pada Brosur PDF Publik:** Sesuai arsitektur V1, file brosur PDF adalah aset publik resmi yang dapat diakses calon klien, sehingga tidak diblokir di `robots.txt`. (Hak akses/keamanan tetap dilindungi melalui middleware/authorization aplikasi, bukan robots.txt).
3. **Proteksi Jalur Administratif:** Direktori `/admin` dan `/filament` dilarang dirayap.
4. **Penyebutan Sitemap Resmi:** Mengarahkan crawler secara eksplisit ke `{APP_URL}/sitemap.xml`.

---

## 9. Image SEO Standards

Pengoptimalan aset visual untuk pencarian gambar (*Google Images*) dan aksesibilitas:
1. **Product Image:** Atribut `alt` otomatis diisi dengan nama produk dan brand terkait secara deskriptif (misal: `alt="Alat PCR Real Time - Era Biology"`).
2. **Hero Banner:** Jika banner menyampaikan informasi visual penting, gunakan alt text deskriptif. Jika banner murni dekoratif dan informasinya telah tersaji dalam teks HTML di atasnya, gunakan `alt=""` sesuai semantik aksesibilitas WCAG.
3. **Logo Perusahaan:** Menggunakan nama entitas resmi `alt="PT Abhipraya Nawasena Sejahtera"` jika logo berfungsi sebagai tautan beranda.
4. **Bebas Nama File Mentah:** Dilarang keras menampilkan nama file acak seperti `IMG_00912.JPG` sebagai alt text.

---

## 10. Performance, Security & Shared Hosting Compliance

1. **Efisiensi Server-Side Tanpa Overhead:** Peta situs XML di-generate dalam kueri ringan terindeks `Product::where('is_active', true)->select('slug_id', 'slug_en', 'updated_at')->get()`.
2. **Ketiadaan Database Tambahan:** Tidak ada tabel database lokal untuk SEO.
3. **Proteksi Informasi Pribadi (Zero PII in Metadata):** Seluruh tag `<meta>`, OpenGraph, dan JSON-LD terbebas 100% dari data pribadi pengunjung atau pengirim quotation.
4. **Shared Hosting Ready:** Berjalan mulus di cPanel tanpa Redis, queue worker daemon, Elasticsearch, atau Node.js server.

---

## 11. Testing & Verification Strategy

Pengujian fungsionalitas SEO dilakukan tanpa tools berbayar melalui prosedur verifikasi terstruktur:

1. **Inspeksi HTML Source Code & Dynamic URL Resolution:**
   - Memeriksa bahwa canonical dan hreflang dibangun dari konfigurasi `app.url` dan tidak menghasilkan domain produksi saat diuji di local development.
2. **Validasi Hreflang Produk Tanpa Counterpart:**
   - Menguji produk yang hanya memiliki `slug_id` untuk memastikan hreflang EN tidak muncul dan tidak mengarah ke katalog.
3. **Validasi Structured Data (Schema.org Validator):**
   - Menguji payload JSON-LD Organization (memastikan bersumber dari CompanyProfile) dan Product (memastikan tidak ada harga/rating fiktif).
4. **Inspeksi Sitemap XML (`/sitemap.xml`):**
   - Memastikan sitemap hanya memuat 10 static base pages + active products, dan tidak memuat URL paginasi (`?page=N`) atau URL terfilter.
5. **Inspeksi Robots Policy (`/robots.txt`):**
   - Memastikan direktori admin di-disallow, sitemap tercantum, dan tidak ada disallow untuk file brosur PDF publik.

---

## 12. Acceptance Criteria (Format Given / When / Then)

### AC-01: Meta Title Terlokalisasi Dwibahasa
- **Given:** Pengunjung membuka halaman beranda `/id` dan `/en`.
- **When:** Dokumen HTML dimuat.
- **Then:** Pada `/id` title memuat Bahasa Indonesia resmi dan pada `/en` title memuat padanan Bahasa Inggris resmi.

### AC-02: Meta Description Bersumber dari Model CMS
- **Given:** Pengunjung membuka halaman detail produk `/id/products/alat-pcr`.
- **When:** Tag `<head>` diinspeksi.
- **Then:** Tag `<meta name="description">` memuat ringkasan produk `summary_id` (atau potongan bersih `description_id`) tanpa tag HTML mentah.

### AC-03: Penerapan Canonical URL Mandiri Berbasis Konfigurasi Aplikasi
- **Given:** Pengunjung membuka `/id/about` pada lingkungan pengujian lokal (`APP_URL=http://localhost:8000`).
- **When:** Dokumen HTML dirender.
- **Then:** Tag `<link rel="canonical" href="http://localhost:8000/id/about">` tersaji tanpa menghasilkan domain produksi secara tidak sengaja.

### AC-04: Relasi Hreflang Timbal Balik untuk Produk Lengkap
- **Given:** Pengunjung membuka halaman detail produk `/id/products/alat-pcr` (memiliki padanan `slug_en = 'real-time-pcr'`).
- **When:** Tag `<head>` diperiksa.
- **Then:** Tersedia tag `hreflang="id"` menunjuk ke rute ID dan `hreflang="en"` menunjuk ke rute EN yang valid.

### AC-05: Penanganan Hreflang Produk Tanpa Localized Counterpart
- **Given:** Produk aktif hanya memiliki `slug_id = 'reagen-khusus'` dan belum memiliki `slug_en`.
- **When:** Halaman detail produk `/id/products/reagen-khusus` dirender.
- **Then:** Sistem merender `hreflang="id"` dan `x-default` ke rute ID tersebut, **TIDAK MERENDER tag `hreflang="en"`**, dan **TIDAK MENGARAHKAN** hreflang ke `/en/products`.

### AC-06: Penetapan `x-default` Hreflang
- **Given:** Seluruh halaman publik dwibahasa dievaluasi.
- **When:** Tag hreflang dirender.
- **Then:** Tag `hreflang="x-default"` menunjuk ke URL versi Bahasa Indonesia (`/id/...`).

### AC-07: Penyajian Open Graph Metadata Lengkap
- **Given:** Tautan detail produk dibagikan ke media sosial.
- **When:** Parser Open Graph membaca halaman.
- **Then:** Tersedia tag `og:title`, `og:description`, `og:url`, `og:locale`, dan `og:image` (menunjuk ke foto utama produk dengan absolute URL).

### AC-08: Structured Data `Organization` Bersumber Dinamis dari CMS
- **Given:** Data alamat dan telepon pada `CompanyProfile` diperbarui di CMS.
- **When:** Skrip JSON-LD Organization diuraikan.
- **Then:** Schema Organization menampilkan data terbaru yang konsisten dengan CMS tanpa ada data hardcoded yang tertinggal.

### AC-09: Structured Data `Product` Jujur Tanpa Data Fiktif
- **Given:** Pengunjung membuka detail produk aktif.
- **When:** Skrip JSON-LD Product diuraikan.
- **Then:** Schema memuat nama produk, gambar, deskripsi, brand, dan kategori yang ada tanpa menyertakan harga palsu, review fiktif, atau rating karangan.

### AC-10: Peta Situs Dinamis `/sitemap.xml` Tanpa Paginasi
- **Given:** Mesin pencari mengakses endpoint `{APP_URL}/sitemap.xml`.
- **When:** Server merespon.
- **Then:** Menghasilkan dokumen XML valid berisi 10 static base pages dan rute produk aktif, serta **TIDAK MEMUAT** URL paginasi (`?page=N`) atau URL terfilter.

### AC-11: Eksklusi Produk Nonaktif dari Sitemap
- **Given:** Di database terdapat record produk dengan `is_active = false`.
- **When:** Dokumen `/sitemap.xml` di-generate.
- **Then:** URL produk nonaktif tersebut **TIDAK TERCANTUM** pada sitemap XML.

### AC-12: Penyajian Dokumen `/robots.txt` Tanpa Blokir Brosur PDF
- **Given:** Bot perayap mengakses `{APP_URL}/robots.txt`.
- **When:** File dibaca.
- **Then:** Menampilkan direktori `/admin` di-disallow, sitemap tercantum, dan **TIDAK MEMBLOKIR** path brosur PDF publik.

### AC-13: Proteksi Indeksasi Area Administratif
- **Given:** URL backend admin Filament `/admin/login`.
- **When:** Halaman diakses.
- **Then:** Halaman tidak terdaftar pada sitemap dan dibatasi oleh autentikasi server-side.

### AC-14: Strategi `noindex, follow` pada Katalog Terfilter
- **Given:** Pengunjung membuka halaman katalog dengan parameter filter `/id/products?category=mikrobiologi`.
- **When:** Dokumen HTML dirender.
- **Then:** Tag `<meta name="robots" content="noindex, follow">` tersaji, dan tag canonical menunjuk kembali ke base catalog `/id/products`.

### AC-15: Ketersediaan Alt Text Deskriptif Gambar
- **Given:** Gambar produk atau banner ditampilkan pada halaman publik.
- **When:** Tag `<img>` diuraikan.
- **Then:** Atribut `alt` terisi teks deskriptif bermakna atau `alt=""` untuk elemen murni dekoratif tanpa nama file mentah.

### AC-16: Jaminan Ketiadaan PII pada Metadata SEO
- **Given:** Seluruh metadata title, description, OpenGraph, dan JSON-LD diinspeksi.
- **When:** Evaluasi privasi dilakukan.
- **Then:** Tidak ada satu pun data pribadi pengunjung/pengirim penawaran yang muncul pada metadata publik.

### AC-17: Kemandirian Pengindeksan Tanpa JavaScript (No-JS Crawling)
- **Given:** Bot mesin pencari merayap halaman tanpa mengeksekusi JavaScript.
- **When:** HTML dasar diuraikan.
- **Then:** Seluruh teks profil perusahaan, nama produk, spesifikasi, dan metadata SEO dapat dibaca secara lengkap (Server-Side Rendered).

### AC-18: Kompatibilitas Penuh Shared Hosting cPanel
- **Given:** Sistem SEO dioperasikan di server produksi shared hosting.
- **When:** Seluruh request sitemap, robots, dan rendering metadata diproses.
- **Then:** Tidak ada dependensi background daemon, Redis, atau Node.js server yang dibutuhkan.

### AC-19: Ketiadaan Tabel Database Khusus SEO
- **Given:** Struktur skema database MySQL dievaluasi.
- **When:** Tabel diperiksa.
- **Then:** Tidak terdapat tabel database khusus SEO (seluruh data diturunkan langsung dari model katalog dan profil yang ada).

---

## 13. Out of Scope

Hal-hal berikut secara tegas dinyatakan **DI LUAR CAKUPAN (OUT OF SCOPE)** untuk modul SEO V1:
- Integrasi software SEO berbayar pihak ketiga (Ahrefs, Semrush API).
- Otomasi publikasi artikel / blog post SEO marketing.
- Otomasi Google Search Console API atau Google Ads API.
- Dashboard analitik kata kunci (*Keyword Rank Tracker*) di dalam panel admin Filament.
- Pembuatan konten artikel otomatis berbasis AI.
- Dukungan bahasa tambahan di luar Bahasa Indonesia dan Bahasa Inggris.

---

## 14. Architecture Consistency Notes

- **Architecture Consistency Audit: PASS**
- **Konsistensi dengan Technology Baseline:** Menggunakan server-side Blade rendering pada PHP 8.3.x / Laravel 12 tanpa background daemon atau Redis.
- **Konsistensi dengan System Design & URS:** Mengimplementasikan bab 13 SEO, Discoverability & Metadata Strategy secara presisi.
- **Konsistensi Hreflang Produk:** Penanganan ketiadaan localized counterpart produk tanpa fallback salah ke katalog selaras 100% dengan SPEC-05 & SPEC-08.
- **Konsistensi Resolusi Domain Aplikasi:** Membangun seluruh URL absolut secara dinamis berbasis `config('app.url')` tanpa hardcoded string domain.
- **Konsistensi Sitemap V1:** Membatasi sitemap pada 10 base pages + active products tanpa mencemari query parameter pagination.
- **Konsistensi dengan SPEC-01 (Catalog Management):** Metadata produk diturunkan murni dari skema kolom `products` (`name_id`, `name_en`, `slug_id`, `slug_en`, `summary_id`, `summary_en`, `primary_image_path`) tanpa field spekulatif.
- **Konsistensi dengan SPEC-06 (Public Catalog):** Kebijakan `noindex, follow` dan canonical base catalog untuk query filter terfilter selaras 100%.
- **Konsistensi dengan SPEC-07 & SPEC-08:** Struktur metadata OpenGraph, breadcrumb, dan image alt text terhubung harmonis.
- **Konsistensi dengan SPEC-09 (Analytics):** Batasan modul jelas dan terpisah (SPEC-10 untuk metadata discoverability, SPEC-09 untuk behavioral telemetry).

---

*(Seluruh rangkaian 10 Feature Specifications telah selesai disusun dan diselaraskan secara lengkap, terstruktur, dan konsisten. Proyek siap untuk tahap Spec Review & Consistency Check sebelum penguncian arsitektur.)*
