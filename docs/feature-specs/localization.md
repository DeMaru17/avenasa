# Feature Specification: Localization & Bilingual Website

**Feature ID:** `SPEC-05-LOCALIZATION`  
**Feature Name:** Localization & Bilingual Website  
**Proyek:** Website Company Profile & Katalog Produk  
**Klien:** PT Abhipraya Nawasena Sejahtera (ANS)  
**Dokumen Referensi & Sumber Kebenaran:**
1. [docs/architecture/technology-baseline.md](file:///c:/laragon/www/avenasa/docs/architecture/technology-baseline.md)
2. [docs/architecture/system-design.md](file:///c:/laragon/www/avenasa/docs/architecture/system-design.md)
3. [docs/urs/ans-website-urs.md](file:///c:/laragon/www/avenasa/docs/urs/ans-website-urs.md)
4. [docs/feature-specs/catalog-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/catalog-management.md)
5. [docs/feature-specs/company-content-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/company-content-management.md)
6. [docs/feature-specs/hero-banner-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/hero-banner-management.md)
7. [docs/feature-specs/quotation-inquiry-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/quotation-inquiry-management.md)
8. [docs/references/Company Profile ANS 2026 Rev.02.pdf](file:///c:/laragon/www/avenasa/docs/references/Company%20Profile%20ANS%202026%20Rev.02.pdf)  
**Status Dokumen:** Draft Feature Specification — Under Review

---

## 1. Feature Overview

Fitur **Localization & Bilingual Website** adalah aturan dan fondasi arsitektur terpusat yang mengatur perilaku dwibahasa (**Bahasa Indonesia - `id`** dan **Bahasa Inggris - `en`**) pada seluruh ekosistem website publik PT Abhipraya Nawasena Sejahtera.

Fitur ini menjamin pengalaman bernavigasi yang konsisten, deterministik, dan ramah SEO (*SEO-friendly*) melalui:
- **Prefix URL Kanonikal:** Struktur URL berbasis prefix bahasa eksplisit (`/id/...` dan `/en/...`).
- **Resolusi Slug Terlokalisasi Ketat (*Strict Localized Slug Routing*):** Pemisahan pencarian slug produk (`slug_id` vs `slug_en`) tanpa kueri ambigu `OR`.
- **Language Switcher Kontekstual:** Pengalih bahasa pintar di navbar/header yang mempertahankan konteks halaman aktif, slug produk padanannya, serta parameter filter katalog.
- **Pemisahan Tuntas UI Strings vs CMS Database Content:** Pemetaan string statis antarmuka melalui file terjemahan bawaan Laravel (`lang/id.json` & `lang/en.json`) dan konten dinamis melalui kolom database bilingual terpisah (`_id` & `_en`).
- **Integrasi Penuh SEO & Analitik GA4:** Penyediaan tag `<html lang="">`, tag kanonikal, pasangan `hreflang`, sitemap XML dwibahasa, serta pelacakan event `language_switch` tanpa PII.

---

## 2. Supported Locales & Hierarchy

Sistem menetapkan batasan bahasa resmi secara tegas pada V1:
1. **Daftar Bahasa Resmi yang Didukung:**
   - **`id` (Bahasa Indonesia):** Bahasa default (*Default Locale*) dan bahasa acuan utama (*Primary Reference*).
   - **`en` (Bahasa Inggris):** Bahasa internasional resmi (*Secondary Official Locale*).
2. **Bahasa Tidak Terbatas Dilarang:** Sistem **TIDAK MENDUKUNG** locale di luar `id` dan `en` pada V1.
3. **Fallback Locale:** Jika terdapat teks antarmuka atau konten dinamis yang belum diterjemahkan ke Bahasa Inggris, sistem secara aman menggunakan versi Bahasa Indonesia (`id`) sebagai *fallback*.

---

## 3. URL Architecture & Routing Patterns

Website publik mengadopsi struktur URL prefix kanonikal. Seluruh halaman publik wajib memiliki rute ber-prefix:

```
[Format URL Kanonikal Publik]
/{locale}/{route_path}
```

### 3.1. Pemetaan URL Publik Dwibahasa
| Halaman Publik | URL Versi Bahasa Indonesia (`id`) | URL Versi Bahasa Inggris (`en`) |
|---|---|---|
| **Beranda (Home)** | `/id` atau `/id/` | `/en` atau `/en/` |
| **Tentang Kami (About)** | `/id/about` | `/en/about` |
| **Katalog Produk (Catalog)** | `/id/products` | `/en/products` |
| **Katalog Terfilter (Filtered)** | `/id/products?category=mikrobiologi&brand=merck` | `/en/products?category=microbiology&brand=merck` |
| **Detail Produk (Product Detail)** | `/id/products/{slug_id}` | `/en/products/{slug_en}` |
| **Mitra & Klien (Partners/Clients)**| `/id/partners-clients` | `/en/partners-clients` |
| **Kontak & Quotation (General)** | `/id/contact` | `/en/contact` |
| **Kontak dengan Konteks Produk** | `/id/contact?product_id={id}` | `/en/contact?product_id={id}` |

### 3.2. Larangan Format URL Non-Kanonikal
- **DILARANG** menggunakan query string sebagai penentu bahasa utama (misal: `/?lang=id` atau `/?lang=en`).
- **DILARANG** membuat URL campuran tanpa prefix untuk halaman publik internal.

---

## 4. Locale Resolution & Root URL Behavior

Sistem menerapkan resolusi bahasa yang deterministik dan stabil untuk crawler mesin pencari maupun pengunjung manusia:

1. **URL Sebagai Sumber Kebenaran Mutlak:**
   - Locale yang tercantum pada prefix URL (`/id/...` atau `/en/...`) adalah penentu bahasa aplikasi 100%.
2. **Akses Root URL (`/`):**
   - Saat pengunjung mengakses domain root (`https://avenasa.co.id/`), sistem melakukan *Permanent Redirect* (HTTP 301) ke default locale: `/id`.
3. **Abaikan Otomasi Header `Accept-Language` Browser:**
   - Sistem **TIDAK MELAKUKAN** pengalihan otomatis secara dinamis berdasarkan header `Accept-Language` browser. Hal ini mencegah URL kanonikal berfluktuasi saat diindeks oleh bot mesin pencari internasional (Googlebot).
4. **Penanganan Prefix Locale Ilegal / Tidak Didukung:**
   - Permintaan ke prefix di luar `id` dan `en` (misal: `/fr/about`, `/es/products`) langsung mengembalikan respon resmi **`HTTP 404 Not Found`**.
5. **Penanganan Halaman Tidak Ditemukan (404 Error):**
   - Jika route tidak ditemukan pada locale yang valid (misal: `/id/halaman-palsu`), sistem merender halaman error 404 dalam bahasa sesuai prefix URL (`/id` -> pesan 404 Bahasa Indonesia; `/en` -> pesan 404 Bahasa Inggris).

---

## 5. Conceptual Middleware: `SetLocaleMiddleware`

Modul ini mendefinisikan tanggung jawab arsitektural middleware `SetLocaleMiddleware` yang berjalan pada setiap request grup rute publik:

### 5.1. Tanggung Jawab Middleware
1. **Ekstraksi Parameter Locale:** Membaca segmen pertama dari URI rute (`{locale}`).
2. **Validasi Whitelist:** Memeriksa apakah nilai locale berada dalam whitelist: `in_array($locale, ['id', 'en'])`.
   - Jika lolos: Menyetel locale aplikasi Laravel via `App::setLocale($locale)` dan menyetel default URL parameter `URL::defaults(['locale' => $locale])`.
   - Jika gagal: Melempar `abort(404)`.
3. **Penyediaan Variabel Global ke Blade:** Membagikan locale aktif ke seluruh view Blade melalui *View Composer* / variabel global (`$currentLocale`).
4. **Isolasi Logika:** Middleware ini murni mengelola konteks bahasa dan tidak mencampurkan logika bisnis entitas produk, company, atau quotation.

---

## 6. Language Switcher Behavior & Contextual Mapping

Language Switcher (`ID | EN`) disematkan pada Header/Navbar publik dan Footer global dengan perilaku cerdas:

```
[Pengunjung berada di: /id/products/media-kultur-agar]
                       ↓ (Klik "EN")
[Sistem mencari slug_en padanan dari record Product aktif]
                       ↓
[Browser diarahkan ke: /en/products/culture-media-agar]
```

### 6.1. Matriks Pemetaan Kontekstual Language Switcher
| Konteks Halaman Aktif | Saat Berada di `ID`, Tautan `EN` Menuju: | Saat Berada di `EN`, Tautan `ID` Menuju: |
|---|---|---|
| **Beranda** | `/en` | `/id` |
| **Tentang Kami** | `/en/about` | `/id/about` |
| **Katalog Produk** | `/en/products` | `/id/products` |
| **Katalog Terfilter** | `/en/products?category={category_slug_en}&brand={brand_slug}` | `/id/products?category={category_slug_id}&brand={brand_slug}` |
| **Detail Produk** | `/en/products/{$product->slug_en}` | `/id/products/{$product->slug_id}` |
| **Kontak Umum** | `/en/contact` | `/id/contact` |
| **Kontak Konteks Produk** | `/en/contact?product_id={id}` | `/id/contact?product_id={id}` |

### 6.2. Aturan Slug Requirement & Defensive Fallback
- **Aturan Bisnis Utama (CMS Requirement):** Sesuai SPEC-01, setiap record `Product` baru/aktif **wajib memiliki `slug_id` dan `slug_en` (`required`)** agar seluruh produk memiliki URL kanonikal dwibahasa yang valid.
- **Defensive Fallback (Penanganan Anomali / Legacy):** Jika karena anomali data tak terduga sebuah produk aktif tidak memiliki `slug_en`, link switch bahasa Inggris secara aman mengarahkan pengunjung ke halaman katalog induk: `/en/products`. Ini murni perlindungan teknis (*defensive fallback*) agar tidak terjadi broken URL dan bukan alur bisnis normal.

---

## 7. Strict Localized Slug Routing (Catalog & Products)

Mengikuti keputusan arsitektur yang telah dikunci pada SPEC-01:

1. **Rute Bahasa Indonesia (`/id/products/{slug}`):**
   - Query pencarian database **hanya mencocokkan kolom `slug_id`**:
     `Product::where('slug_id', $slug)->firstOrFail()`
2. **Rute Bahasa Inggris (`/en/products/{slug}`):**
   - Query pencarian database **hanya mencocokkan kolom `slug_en`**:
     `Product::where('slug_en', $slug)->firstOrFail()`
3. **Larangan Kueri Ambigu (`OR` Query):**
   - **DILARANG KERAS** menggunakan kueri `where('slug_id', $slug)->orWhere('slug_en', $slug)`.
   - Jika pengunjung mencoba mengakses `/id/products/culture-media-agar` (slug bahasa Inggris pada rute Indonesia), sistem wajib mengembalikan **`HTTP 404 Not Found`**. Hal ini mencegah duplikasi konten (*duplicate content penalty*) di mata mesin pencari.

---

## 8. Localized Content Model & Separation of Concerns

Sistem memisahkan terjemahan menjadi dua domain independen:

### 8.1. Tipe A: Static UI Strings (File JSON Lokal)
- Dikelola melalui file terjemahan bawaan Laravel:
  - `lang/id.json`: Berisi kamus terjemahan antarmuka Bahasa Indonesia.
  - `lang/en.json`: Berisi kamus terjemahan antarmuka Bahasa Inggris.
- Contoh elemen UI: Label tombol ("Minta Penawaran" / "Request Quotation"), teks menu navbar, placeholder form, pagination string, teks hak cipta footer.
- Pemanggilan di Blade menggunakan helper standar: `__('Request Quotation')`.

### 8.2. Tipe B: Dynamic Database Content (Kolom Terpisah)
- Dikelola oleh admin melalui form Filament 5 CMS menggunakan kolom terpisah pada masing-masing tabel:
  - `Category`: `name_id`, `name_en`, `slug_id`, `slug_en` (Category menggunakan localized slug).
  - `Brand`: `name`, `slug`, `description_id`, `description_en` (Brand menggunakan universal slug).
  - `Product`: `name_id`, `name_en`, `slug_id`, `slug_en`, `summary_id`, `summary_en`, `description_id`, `description_en`, `specifications` (JSON array of key-value bilingual).
  - `ProductImage`: `caption_id`, `caption_en`.
  - `HeroBanner`: `title_id`, `title_en`, `subtitle_id`, `subtitle_en`, `button_text_id`, `button_text_en`.
  - `CompanyProfile`: `tagline_id`, `tagline_en`, `about_id`, `about_en`, `vision_id`, `vision_en`, `mission_id`, `mission_en`.
  - `CoreValue`: `title_id`, `title_en`, `description_id`, `description_en`.
  - `Management`: `position_id`, `position_en`, `bio_id`, `bio_en`.
- Dilarang membuat tabel translasi terpisah (*no generic translations table*) atau package eksternal.

---

## 9. Content Fallback Policy

| Kategori Konten | Skenario Kondisi | Perilaku Sistem yang Ditetapkan |
|---|---|---|
| **Static UI Strings** | Frasa tidak ditemukan di `lang/en.json` | Laravel otomatis merender teks default dari pemanggilan helper `__()`. |
| **CMS Dynamic Content (Product, Profile, Banner, CoreValue)** | Nilai kolom `*_en` kosong / null | Accessor Model mengembalikan nilai dari kolom `*_id` padanannya. |
| **Product Specifications (JSON)** | Nilai `key_en` atau `value_en` kosong | Merender `key_id` dan `value_id` padanannya. |
| **Product Slug EN** | `slug_en` saat input produk di CMS | Wajib diisi (*validation required*). |

### 9.1. Prinsip Kunci Graceful Degradation:
1. **Fallback Sebagai Safety Mechanism:** Fallback ID ke EN murni berfungsi sebagai *availability / safety fallback* agar halaman publik tidak kosong atau menghasilkan layout rusak jika admin belum melengkapi terjemahan.
2. **Bukan Pengganti Terjemahan:** Penerapan fallback bukan berarti konten Bahasa Inggris dianggap sudah selesai diterjemahkan. Admin tetap bertanggung jawab menyediakan konten Bahasa Inggris resmi di CMS.
3. **Intentional Graceful Degradation:** Tampilan teks Bahasa Indonesia pada sebagian halaman `/en/...` akibat konten EN yang belum diisi adalah perilaku yang disengaja (*intentional graceful degradation*) pada V1.
4. **Tanpa Mesin Penerjemah Otomatis:** Sistem tidak menjalankan *machine translation* otomatis (AI atau external API) saat runtime.

---

## 10. Filament 5 CMS Localization Management

1. **Antarmuka Form Terstruktur:**
   - Setiap form resource di Filament 5 menyediakan field dwibahasa berdampingan atau dalam section bertingkat (misal: *Nama Produk (ID)* & *Product Name (EN)*).
   - Admin mengisi terjemahan secara manual dan terukur tanpa komponen page builder.
2. **Bahasa Panel Admin:**
   - Panel admin Filament 5 menggunakan antarmuka Bahasa Indonesia resmi untuk staf operasional internal ANS.

---

## 11. Quotation & Email Localization Integration

1. **Persistensi Locale Quotation (Sesuai SPEC-04):**
   - Kolom `locale` pada tabel `quotations` merekam bahasa antarmuka saat pengunjung mengirim formulir (`id` atau `en`).
   - Nilai ini merepresentasikan preferensi calon klien dan tidak dapat diubah oleh admin.
2. **Bahasa Template Email Konfirmasi (`QuotationConfirmationMail`):**
   - Jika `locale = 'id'`: Mengirim email konfirmasi berbahasa Indonesia resmi.
   - Jika `locale = 'en'`: Mengirim email konfirmasi berbahasa Inggris resmi korporat.
3. **Bahasa Email Notifikasi Admin (`QuotationAdminNotificationMail`):**
   - Selalu dikirim dalam format Bahasa Indonesia standar kepada `admin@avenasa.co.id`.

---

## 12. SEO, Meta Tags & Sitemap Localization

### 12.1. Tag Dokumen HTML & Meta Tags
Setiap halaman publik Blade menyajikan tag header HTML sesuai locale aktif:
```html
<!-- Pada Halaman Bahasa Indonesia (/id/about) -->
<html lang="id">
<head>
    <title>Tentang Kami - PT Abhipraya Nawasena Sejahtera</title>
    <meta name="description" content="...">
    <link rel="canonical" href="https://avenasa.co.id/id/about">
    <link rel="alternate" hreflang="id" href="https://avenasa.co.id/id/about">
    <link rel="alternate" hreflang="en" href="https://avenasa.co.id/en/about">
    <link rel="alternate" hreflang="x-default" href="https://avenasa.co.id/id/about">
    <meta property="og:locale" content="id_ID">
    <meta property="og:url" content="https://avenasa.co.id/id/about">
</head>
```

```html
<!-- Pada Halaman Bahasa Inggris (/en/about) -->
<html lang="en">
<head>
    <title>About Us - PT Abhipraya Nawasena Sejahtera</title>
    <meta name="description" content="...">
    <link rel="canonical" href="https://avenasa.co.id/en/about">
    <link rel="alternate" hreflang="id" href="https://avenasa.co.id/id/about">
    <link rel="alternate" hreflang="en" href="https://avenasa.co.id/en/about">
    <link rel="alternate" hreflang="x-default" href="https://avenasa.co.id/id/about">
    <meta property="og:locale" content="en_US">
    <meta property="og:url" content="https://avenasa.co.id/en/about">
</head>
```

### 12.2. Single Dynamic Sitemap (`/sitemap.xml`)
- Menghasilkan daftar seluruh URL kanonikal publik aktif dalam kedua versi bahasa (`/id/...` dan `/en/...`).
- Setiap entri produk dalam sitemap menyertakan tag relasi `<xhtml:link rel="alternate" hreflang="..." href="...">` untuk menghubungkan versi ID dan EN secara eksplisit.

---

## 13. GA4 Analytics Integration: `language_switch` Event

Sesuai Analytics Architecture yang telah dikunci pada System Design:
- Setiap interaksi pengunjung saat mengklik Language Switcher memicu event analitik GA4:

1. **`language_switch` Event:**
   - **Pemicu:** Pengunjung mengklik tombol pengalih bahasa (ID -> EN atau EN -> ID).
   - **Payload Parameter:**
     - `source_locale`: Bahasa sebelum beralih (`id` atau `en`).
     - `target_locale`: Bahasa tujuan (`en` atau `id`).
     - `current_path`: **Path URL murni saja tanpa query string** (misal: `'/id/products'`, `'/id/about'`, `'/id/products/cawan-petri'`).
2. **Kepatuhan Privasi (No PII):** Dilarang keras mengirim data pribadi pada event ini.

---

## 14. Mobile UX & Accessibility Standards

- **Language Switcher Usability:** Tombol `ID | EN` disajikan secara bersih pada header mobile (di samping tombol hamburger menu atau di dalam mobile drawer).
- **Indikator Visual Aktif:** Bahasa yang sedang aktif diberikan penanda visual yang kontras (*bold / badge highlight* / garis bawah aktif).
- **Aksesibilitas (WCAG 2.1 AA):** Tombol switch memiliki atribut aksesibel eksplisit: `aria-label="Switch language to English"` atau `aria-label="Ganti bahasa ke Bahasa Indonesia"`, serta ukuran area sentuh minimal `44px x 44px`.

---

## 15. Error & Edge Cases Handling Matrix

| Skenario Edge Case | Perilaku Sistem yang Ditetapkan |
|---|---|
| **Akses prefix bahasa tidak didukung (misal: `/fr/products`)** | Server langsung mengembalikan respon `HTTP 404 Not Found`. |
| **Akses domain root tanpa prefix (`/`)** | Server melakukan redirect permanen `HTTP 301` ke default locale `/id`. |
| **Slug bahasa Inggris diakses pada prefix ID (`/id/products/culture-media`)** | Server mengembalikan `HTTP 404 Not Found` (mencegah kueri ambigu). |
| **Anomali data produk tanpa `slug_en` saat di-switch** | Defensive fallback: Language switcher mengarahkan ke halaman katalog `/en/products`. |
| **Field terjemahan EN dinamis kosong di database** | Accessor Model menyajikan teks Bahasa Indonesia (`_id`) sebagai availability fallback. |
| **Pengalihan bahasa pada katalog yang sedang difilter** | Language switcher memetakan parameter `category` (localized slug) dan `brand` (slug) yang setara. |
| **Pengalihan bahasa pada form kontak dengan konteks produk** | Query `?product_id={id}` dipertahankan secara utuh pada URL tujuan. |

---

## 16. Acceptance Criteria (Format Given / When / Then)

### AC-01: Akses Default Root Mengarahkan ke `/id`
- **Given:** Pengunjung membuka root domain `https://avenasa.co.id/`.
- **When:** Request diterima server.
- **Then:** Server mengembalikan HTTP 301 Redirect ke `https://avenasa.co.id/id`.

### AC-02: Akses Prefix Bahasa Inggris `/en`
- **Given:** Pengunjung membuka `https://avenasa.co.id/en/about`.
- **When:** Halaman berhasil dimuat.
- **Then:** Server menyajikan konten dalam Bahasa Inggris, tag `<html lang="en">` terpasang, dan canonical URL mengarah ke `/en/about`.

### AC-03: Penolakan Locale Tidak Didukung
- **Given:** Pengunjung mencoba membuka `https://avenasa.co.id/de/about`.
- **When:** Request diproses oleh `SetLocaleMiddleware`.
- **Then:** Sistem mengembalikan respon `HTTP 404 Not Found`.

### AC-04: Language Switch pada Detail Produk dengan Strict Localized Slug
- **Given:** Produk memiliki `slug_id = 'cawan-petri-steril'` dan `slug_en = 'sterile-petri-dish'`.
- **When:** Pengunjung berada di `/id/products/cawan-petri-steril` dan mengklik tombol "EN" pada switcher.
- **Then:** Browser diarahkan ke `/en/products/sterile-petri-dish` dengan HTTP 200.

### AC-05: Penolakan Slug Bahasa Silang (Cross-Language Slug Rejection)
- **Given:** Produk memiliki `slug_en = 'sterile-petri-dish'`.
- **When:** Pengunjung mencoba membuka `/id/products/sterile-petri-dish`.
- **Then:** Sistem mengembalikan respon `HTTP 404 Not Found`.

### AC-06: Preservasi Filter Katalog Saat Switch Bahasa (Category Localized Slug & Brand Universal Slug)
- **Given:** Pengunjung berada di `/id/products?category=mikrobiologi&brand=merck` (Kategori Mikrobiologi memiliki `slug_id = 'mikrobiologi'` dan `slug_en = 'microbiology'`).
- **When:** Pengunjung mengklik tombol switcher "EN".
- **Then:** Browser diarahkan ke `/en/products?category=microbiology&brand=merck`.

### AC-07: Fallback Konten Dinamis Bahasa Inggris yang Kosong (Graceful Degradation)
- **Given:** Kolom `summary_en` pada suatu produk bernilai `null`, sedangkan `summary_id` terisi.
- **When:** Pengunjung membuka halaman produk tersebut pada versi bahasa Inggris (`/en/products/...`).
- **Then:** Deskripsi ringkas produk tetap tampil menggunakan teks `summary_id` sebagai availability fallback tanpa layout kosong atau error.

### AC-08: Persistensi Locale pada Quotation & Email Konfirmasi
- **Given:** Pengunjung mengirim quotation dari form di URL `/en/contact`.
- **When:** Form berhasil disubmit dan tersimpan di database.
- **Then:** Kolom `locale` pada tabel `quotations` tersimpan sebagai `'en'`, dan email konfirmasi yang dikirim ke pengunjung menggunakan template Bahasa Inggris resmi.

### AC-09: Validasi SEO Tag Hreflang & Canonical
- **Given:** Pengunjung membuka `/id/contact`.
- **When:** Halaman dirender.
- **Then:** Source HTML memuat tag `<link rel="canonical" href=".../id/contact">`, `<link rel="alternate" hreflang="id" href=".../id/contact">`, dan `<link rel="alternate" hreflang="en" href=".../en/contact">`.

### AC-10: Pelacakan Analitik GA4 `language_switch` (current_path Tanpa Query String)
- **Given:** Pengunjung berada di URL `/id/products?category=mikrobiologi&brand=merck` dan mengklik switcher ke EN.
- **When:** Aksi klik dieksekusi.
- **Then:** Event GA4 `language_switch` terpicu membawa parameter `source_locale: 'id'`, `target_locale: 'en'`, dan `current_path: '/id/products'` (murni path tanpa query parameters), serta tanpa data PII.

---

## 17. Implementation Dependencies

Fitur Localization membutuhkan dependensi internal berikut:
1. **Laravel 12 Framework & Routing Engine:** Berjalan pada PHP 8.3.x dengan route grouping prefix `{locale}`.
2. **`SetLocaleMiddleware`:** Middleware untuk validasi whitelist locale dan konfigurasi application locale.
3. **Model Accessors & Localized Fields:** Seluruh model Eloquent (SPEC-01 s.d. SPEC-04) yang mengimplementasikan accessor terjemahan dinamis.
4. **File Kamus Terjemahan:** File `lang/id.json` dan `lang/en.json` untuk string antarmuka statis.
5. **Blade Layout & Language Switcher Component:** Komponen navbar/header Blade yang merender tombol pengalih dwibahasa responsif.
6. **Sitemap Generator Engine:** Modul pembuat `/sitemap.xml` yang merender seluruh pasangan URL terlokalisasi.

---

## 18. Out of Scope

Hal-hal berikut secara tegas dinyatakan **DI LUAR CAKUPAN (OUT OF SCOPE)** untuk fitur Localization V1:
- Dukungan bahasa ketiga selain Bahasa Indonesia dan Bahasa Inggris (misal: Mandarin, Jepang, Arab).
- Layanan integrasi mesin penerjemah otomatis saat runtime (Google Cloud Translation API, DeepL, atau ChatGPT API).
- Sistem *Translation Memory* atau *Crowdsourced Translation*.
- Alur kerja persetujuan terjemahan bertingkat (*Multi-level Translation Approval Workflow*).
- Deteksi IP geolocation dinamis yang mengubah canonical URL secara sepihak.

---

## 19. Architecture Consistency Notes

- **Konsistensi dengan SPEC-01 (Catalog Management):** Localized slug matching (`slug_id` vs `slug_en`) diimplementasikan secara ketat sesuai SPEC-01 tanpa kueri ambigu.
- **Konsistensi dengan SPEC-04 (Quotation Management):** Rekaman `locale` pada tabel `quotations` menjadi basis penentuan bahasa email konfirmasi calon klien.
- **Konsistensi dengan System Design:** Menggunakan format URL prefix kanonikal (`/id` dan `/en`) dan Direct SMTP synchronous delivery yang kompatibel penuh dengan shared hosting.

---

*(Feature Specification Localization & Bilingual Website telah selesai disusun dan menunggu final review sebelum dikunci untuk implementasi.)*
