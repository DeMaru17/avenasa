# Feature Specification: Public Company Website

**Feature ID:** `SPEC-07-PUBLIC-COMPANY-WEBSITE`  
**Feature Name:** Public Company Website (Home, About, Partners & Clients, Contact)  
**Proyek:** Website Company Profile & Katalog Produk  
**Klien:** PT Abhipraya Nawasena Sejahtera (ANS)  
**Dokumen Referensi & Sumber Kebenaran:**
1. [docs/architecture/technology-baseline.md](file:///c:/laragon/www/avenasa/docs/architecture/technology-baseline.md)
2. [docs/architecture/system-design.md](file:///c:/laragon/www/avenasa/docs/architecture/system-design.md)
3. [docs/urs/ans-website-urs.md](file:///c:/laragon/www/avenasa/docs/urs/ans-website-urs.md)
4. [docs/feature-specs/company-content-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/company-content-management.md)
5. [docs/feature-specs/hero-banner-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/hero-banner-management.md)
6. [docs/feature-specs/localization.md](file:///c:/laragon/www/avenasa/docs/feature-specs/localization.md)
7. [docs/feature-specs/catalog-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/catalog-management.md)
8. [docs/feature-specs/public-catalog-experience.md](file:///c:/laragon/www/avenasa/docs/feature-specs/public-catalog-experience.md)
9. [docs/feature-specs/quotation-inquiry-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/quotation-inquiry-management.md)
10. [docs/references/Company Profile ANS 2026 Rev.02.pdf](file:///c:/laragon/www/avenasa/docs/references/Company%20Profile%20ANS%202026%20Rev.02.pdf)  
**Status Dokumen:** Draft Feature Specification — Under Review

---

## 1. Feature Overview & Scope

Fitur **Public Company Website** mendefinisikan seluruh pengalaman publik (*Public-Facing Experience*) untuk halaman-halaman profil perusahaan PT Abhipraya Nawasena Sejahtera di luar katalog produk:
1. **Beranda (Home):** Pintu gerbang utama yang menyajikan carousel hero banner terkurasi, profil pengantar ANS, highlight pilar bisnis/produk unggulan, dan CTA strategis.
2. **Tentang Kami (About Us):** Halaman komprehensif yang memuat identitas perusahaan, Visi & Misi resmi, 6 Core Values (Spiral Nilai ANS), serta jajaran Manajemen/Founder (hanya yang berstatus aktif).
3. **Mitra & Klien (Partners & Clients):** Halaman etalase kredibilitas yang menampilkan logo prinsipal global resmi (termasuk Era Biology, Merck, Neogen) dan institusi klien terkemuka.
4. **Kontak (Contact Page):** Pusat informasi kontak resmi ANS (alamat Mensana Tower, telepon, email, WhatsApp, embed peta responsif) dan antarmuka formulir permintaan penawaran/inquiry.

> [!NOTE]
> - **Katalog Produk Publik:** Telah didefinisikan secara khusus pada **SPEC-06 Public Catalog Experience**.
> - **Detail Produk Publik:** Didefinisikan pada feature spec terpisah pasca SPEC-07.
> - **CMS Management CRUD:** Telah didefinisikan pada **SPEC-02 Company Content** dan **SPEC-03 Hero Banner**.

---

## 2. Public URL Architecture & Routing

Mengikuti ketentuan **SPEC-05 Localization**, seluruh URL publik ber-prefix bahasa kanonikal (`/id/...` dan `/en/...`):

| Halaman Publik | URL Bahasa Indonesia (`id`) | URL Bahasa Inggris (`en`) |
|---|---|---|
| **Beranda (Home)** | `/id` atau `/id/` | `/en` atau `/en/` |
| **Tentang Kami (About Us)** | `/id/about` | `/en/about` |
| **Mitra & Klien (Partners & Clients)** | `/id/partners-clients` | `/en/partners-clients` |
| **Kontak & Inquiry Umum** | `/id/contact` | `/en/contact` |
| **Kontak dengan Konteks Produk** | `/id/contact?product_id={id}` | `/en/contact?product_id={id}` |

- **Root Access (`/`):** Mengembalikan HTTP 301 Permanent Redirect ke `/id`.
- **Dilarang Format Non-Kanonikal:** Dilarang menggunakan parameter `?lang=id` sebagai URL utama.

---

## 3. Global Public Layout & Navigation

Seluruh halaman publik menggunakan layout Blade terpusat: `Header`, `Main Content`, dan `Footer`.

### 3.1. Global Header & Navigation Bar
- **Identitas Visual:** Logo resmi PT Abhipraya Nawasena Sejahtera (`<img>` dengan tag `alt="PT Abhipraya Nawasena Sejahtera"` dan tautan kembali ke `/{locale}`).
- **Menu Navigasi Utama:**
  - *Beranda / Home* (`/{locale}`)
  - *Tentang Kami / About Us* (`/{locale}/about`)
  - *Produk / Products* (`/{locale}/products`)
  - *Mitra & Klien / Partners & Clients* (`/{locale}/partners-clients`)
  - *Kontak / Contact* (`/{locale}/contact`)
- **Indikator Status Aktif:** Item navigasi yang sesuai dengan halaman saat ini diberikan penanda visual aktif (*active state border/bold/highlight*).
- **Language Switcher Terintegrasi:** Tombol switch `ID | EN` yang mempertahankan path konteks halaman aktif sesuai ketentuan SPEC-05.
- **Mobile Navigation Drawer:** Tombol *hamburger menu* pada mobile (`< 1024px`) yang membuka panel navigasi vertikal responsif berbasis Alpine.js dengan touch target minimal `44px x 44px`.

### 3.2. Global Footer
- **Kolom 1 - Identitas Perusahaan:** Logo ANS dan ringkasan profil pengantar resmi PT Abhipraya Nawasena Sejahtera. Jika informasi legalitas perusahaan (misal: NIB/izin edar) tersedia pada record `CompanyProfile` atau sumber resmi, informasi tersebut dapat ditampilkan; jika belum tersedia, sistem tidak membuat entity baru atau mengarang nomor legalitas fiktif.
- **Kolom 2 - Tautan Cepat (Quick Links):** Tautan navigasi internal (`Home`, `About`, `Products`, `Partners & Clients`, `Contact`).
- **Kolom 3 - Informasi Kontak Resmi (Berdasarkan Company Profile ANS):**
  - Alamat: *Mensana Tower Lt. 15, Jl. Raya Kranggan Kav. 1, Cibubur, Bekasi, Jawa Barat 17433*
  - Telepon: `(021) 39722772`
  - WhatsApp: `0822-614-614-00`
  - Email: `admin@avenasa.co.id`
- **Kolom 4 - Hak Cipta & Legalitas:** *© 2026 PT Abhipraya Nawasena Sejahtera. All rights reserved.*

---

## 4. Home Page Specification (`/{locale}`)

Beranda dirancang sebagai *executive gateway* yang elegan dan cepat dimuat:

```
+-------------------------------------------------------------------+
| GLOBAL HEADER (Logo, Navigasi, Language Switcher ID|EN, CTA)     |
+-------------------------------------------------------------------+
| 1. HERO BANNER CAROUSEL (SPEC-03 data: Banner Gambar, Judul, CTA) |
+-------------------------------------------------------------------+
| 2. PROFIL PENGANTAR ANS (Tagline, Ringkasan Perusahaan, CTA About)|
+-------------------------------------------------------------------+
| 3. PILAR BISNIS & PRODUK HIGHLIGHT (Highlight Produk, CTA Catalog)|
+-------------------------------------------------------------------+
| 4. PRINSIPAL & KLIEN HIGHLIGHT (Logo Marquee / Grid Ringkas)      |
+-------------------------------------------------------------------+
| 5. CTA STRATEGIS KONSULTASI (Minta Penawaran / Hubungi Kami)       |
+-------------------------------------------------------------------+
| GLOBAL FOOTER                                                     |
+-------------------------------------------------------------------+
```

### 4.1. Section 1: Hero Banner Carousel (Integrasi SPEC-03)
- **Konsumsi Data CMS:** Menampilkan hanya record `HeroBanner` yang berstatus `is_active = true`, diurutkan berdasarkan `sort_order ASC`.
- **Rasio & Fallback Gambar:**
  - Desktop: Menggunakan `image_path` (wajib).
  - Mobile: Menggunakan `mobile_image_path` (jika diisi); jika null, sistem secara anggun menggunakan `image_path` desktop (*Graceful Fallback*).
  - **LCP Optimization:** Gambar hero slide pertama dimuat secara eager (`loading="eager"`, `fetchpriority="high"`), slide berikutnya dimuat secara lazy.
- **Interaktivitas Carousel (Alpine.js):**
  - Autoplay dengan interval 5-6 detik.
  - Otomatis *pause on hover* (desktop) dan *pause on touch* (mobile).
  - Tombol navigasi manual *Previous / Next* dan indikator dot.
  - **Aksesibilitas Reduced Motion:** Jika browser mendeteksi `@media (prefers-reduced-motion: reduce)`, autoplay dinonaktifkan secara otomatis.
- **CTA Hero:** Tombol aksi dengan teks dwibahasa (`button_text_id` / `button_text_en`) mengarah ke URL tujuan yang otomatis disesuaikan dengan locale aktif.
- **Graceful Fallback Hero (Tanpa Active Banner):** Jika tidak ada banner aktif di CMS, sistem menyajikan section hero fallback statis berlogo ANS dan teks struktural netral dengan tombol CTA ke katalog produk tanpa mengarang campaign atau slogan fiktif.

### 4.2. Section 2: Company Introduction
- **Sumber Data:** Model singleton `CompanyProfile` (SPEC-02).
- **Tampilan:** Tagline perusahaan dwibahasa (`tagline_id` / `tagline_en`), ringkasan profil pengantar ANS, dan tombol CTA *"Pelajari Profil Kami"* (`/{locale}/about`).
- **Fallback:** Mengikuti aturan SPEC-05 (jika field `_en` kosong, fallback aman menyajikan teks `_id`).

### 4.3. Section 3: Product Highlights on Home
- **Mekanisme Pemilihan Produk:** Menggunakan produk aktif teratas berdasarkan aturan ordering yang telah ada (`is_active = true`, diurutkan berdasarkan `sort_order ASC`, limit: 3-4 produk). V1 **tidak menambahkan field baru seperti `is_featured`** pada skema database.
- **Tampilan Kartu:** Foto utama (`primary_image_path`), nama produk dwibahasa, nama kategori, dan tautan langsung ke detail produk.
- **CTA:** Tombol utama *"Jelajahi Seluruh Katalog"* mengarah ke `/{locale}/products`.

### 4.4. Section 4: Principals & Clients Showcase
- **Tampilan:** Grid logo prinsipal terkemuka (Era Biology, Merck, Neogen) dan institusi klien untuk membangun kredibilitas instan di beranda, dengan tautan menuju `/{locale}/partners-clients`.

---

## 5. About Us Page Specification (`/{locale}/about`)

Halaman Tentang Kami menyajikan profil korporat lengkap bersumber dari data CMS SPEC-02:

### 5.1. Section 1: Company Profile Narrative
- **Konten:** Judul dan narasi lengkap sejarah, komitmen, dan kapabilitas PT Abhipraya Nawasena Sejahtera sebagai distributor alat kesehatan dan laboratorium terpercaya.

### 5.2. Section 2: Visi & Misi Perusahaan
- **Visi (`vision_id` / `vision_en`):** Disajikan dalam tipografi terkemuka dan elegan.
- **Misi (`mission_id` / `mission_en`):** Disajikan dalam bentuk daftar butir poin terstruktur dwibahasa.

### 5.3. Section 3: 6 Core Values (Spiral Nilai ANS)
- **Sumber Data:** Model `CoreValue` (SPEC-02) yang memuat 6 nilai inti resmi ANS:
  1. *Customer Focus*
  2. *Innovative*
  3. *Integrity*
  4. *Collaborative*
  5. *Commitment*
  6. *Agility*
- **Tata Letak:**
  - Mobile (`< 640px`): 1 kolom lapang.
  - Tablet (`640px - 1023px`): 2 kolom simetris.
  - Desktop (`>= 1024px`): 3 kolom simetris (2 baris x 3 kartu) dengan kartu berdesain modern, judul dwibahasa, dan deskripsi nilai.

### 5.4. Section 4: Management & Founders
- **Ketentuan Bisnis (Client Decision):**
  - Sistem **HANYA MENAMPILKAN** record `Management` yang berstatus **`is_active = true`**.
  - Sesuai keputusan klien pada tahap awal peluncuran (dimana 3 Founder disiapkan dengan status awal `is_active = false` menunggu foto HD), section ini **tidak akan merender kartu manajemen yang nonaktif**.
  - Jika ada record aktif: Menampilkan foto (atau placeholder profesional jika null), nama lengkap, jabatan dwibahasa (`position_id` / `position_en`), dan biografi singkat.

---

## 6. Partners & Clients Page Specification (`/{locale}/partners-clients`)

Halaman Mitra & Klien dibagi menjadi 2 entitas visual terpisah:

### 6.1. Section 1: Official Principals & Partners
- **Sumber Data:** Model `Brand` (SPEC-01) yang berstatus `is_active = true`.
- **Tampilan:** Grid logo prinsipal resmi (termasuk *Era Biology, Merck, Neogen*) di dalam kontainer putih seragam (*aspect ratio preserved*, tidak terdistorsi, `max-h-16`, `object-contain`).
- **Deskripsi Prinsipal:** Menampilkan deskripsi profil singkat prinsipal dwibahasa (`description_id` / `description_en`) jika tersedia.

### 6.2. Section 2: Corporate & Institution Clients
- **Sumber Data:** Model `Client` (SPEC-02) yang berstatus `is_active = true`, diurutkan berdasarkan `sort_order ASC`.
- **Tampilan:** Grid logo klien korporat, universitas, rumah sakit, dan laboratorium riset.
- **Aksesibilitas:** Seluruh logo menyertakan tag `alt="Logo [Nama Klien]"` deskriptif.

---

## 7. Contact Page Specification (`/{locale}/contact`)

Halaman Kontak adalah pusat interaksi langsung dan formulir penawaran harga:

```
+-------------------------------------------------------------------+
| JUDUL HALAMAN: Hubungi Kami / Contact Us                          |
+---------------------------------+---------------------------------+
| SISI KIRI: INFORMASI & PETA     | SISI KANAN: FORMULIR INQUIRY    |
| - Alamat Kantor Mensana Tower   | - Nama Lengkap (*)              |
| - Telepon & WhatsApp            | - Alamat Email (*)              |
| - Alamat Email Resmi            | - Telepon / WA (Opsional)       |
| - Embed Peta Interaktif (Peta)  | - Nama Perusahaan (Opsional)    |
|   (Mensana Tower Cibubur)       | - Subjek Permintaan (*)         |
|                                 | - Pesan / Rincian Kebutuhan (*) |
|                                 | - [Context: Produk Badge]       |
|                                 | - [Tombol Submit Quotation]     |
+---------------------------------+---------------------------------+
```

### 7.1. Informasi Kontak Resmi & Tautan Cepat
- **Sumber Data Terverifikasi:** Hanya menampilkan informasi resmi yang tersedia dari Company Profile ANS (Alamat Mensana Tower Cibubur, Telepon `021 39722772`, WhatsApp `0822-614-614-00`, dan Email `admin@avenasa.co.id`). Informasi tambahan yang belum ada data resminya (seperti Jam Operasional) tidak di-hardcode.
- **Tautan Cepat:** Tautan `tel:02139722772`, tautan WhatsApp `https://wa.me/6282261461400`, dan tautan `mailto:admin@avenasa.co.id`.

### 7.2. Embed Peta Lokasi Responsif (Configuration Dependency)
- Menampilkan iframe sematan peta lokasi Mensana Tower Cibubur (`aspect-video` atau `h-64 md:h-80`, `loading="lazy"`, `referrerpolicy="no-referrer-when-downgrade"`).
- **Aturan Sumber URL Peta:** URL sematan iframe dan URL tombol *"Buka di Google Maps"* harus bersumber dari konfigurasi aplikasi (`config/app.php` atau `.env`) yang ditentukan pada tahap implementasi tanpa mengarang koordinat fiktif atau membuat migration CMS baru.

### 7.3. Integrasi Formulir Permintaan Penawaran & Validasi Keamanan Konteks Produk
- **Product Context & Hidden Input Security (Sesuai SPEC-04):**
  1. Parameter `product_id` dari query string `/{locale}/contact?product_id={id}` **hanya digunakan sebagai konteks awal render halaman**.
  2. Server wajib melakukan lookup dan validasi ulang keberadaan dan status aktif record `Product` ke database MySQL (Database as Single Source of Truth).
  3. **Hidden input pada form BUKAN source of truth.** Data nama produk dari client/browser dilarang dipercaya.
  4. Saat request POST dikirimkan, `product_id` divalidasi ulang di server (`nullable|exists:products,id`). Jika `product_id` tidak ditemukan atau produk nonaktif, sistem secara aman **tidak mengasosiasikan quotation dengan produk tersebut** (`product_id = null`) dan memprosesnya sebagai inquiry umum tanpa error sistem.
- **Mekanisme Anti-Spam & Isolasi SMTP:** Mengikuti SPEC-04 (Honeypot `website_url_hp`, rate limiting 5 req/menit/IP, dan ketahanan data saat SMTP failure).

---

## 8. Responsive & Mobile-First Breakpoint Matrix

| Komponen Halaman | Mobile (`< 640px`) | Tablet (`640px - 1023px`) | Desktop (`>= 1024px`) |
|---|---|---|---|
| **Header Navigasi** | Hamburger Menu Drawer | Hamburger Menu Drawer | Horizontal Menu Bar + Lang Switcher |
| **Hero Banner** | Full width, fallback image jika mobile image null | Full width, rasio proporsional | Full width desktop container, aspect proporsional |
| **Core Values (About)** | 1 Kolom Vertikal | 2 Kolom Grid | 3 Kolom Grid (2 baris x 3 kartu) |
| **Prinsipal & Klien** | 2 Kolom Grid Logo | 3 s.d. 4 Kolom Grid Logo | 4 s.d. 6 Kolom Grid Logo |
| **Kontak & Form** | 1 Kolom Vertikal (Info di atas, Form di bawah) | 1 Kolom Vertikal Lapang | 2 Kolom Berdampingan (Info & Peta Kiri, Form Kanan) |

---

## 9. SEO, Canonical & Sitemap Integration

Sesuai arsitektur SEO System Design:
1. **Meta Title & Meta Description Terlokalisasi:**
   - Home ID: `PT Abhipraya Nawasena Sejahtera - Distributor Alat Kesehatan & Laboratorium`
   - Home EN: `PT Abhipraya Nawasena Sejahtera - Medical & Laboratory Equipment Distributor`
2. **Canonical & Hreflang Tag:**
   - Setiap halaman publik menyajikan tag kanonikal mandiri (*self-canonical*) dan tag `hreflang="id"`, `hreflang="en"`, serta `hreflang="x-default"`.
   - Canonical Home ID: `https://avenasa.co.id/id`
   - Canonical Home EN: `https://avenasa.co.id/en`
3. **Sitemap (`/sitemap.xml`):**
   - Mendaftarkan seluruh URL publik (`/id`, `/en`, `/id/about`, `/en/about`, `/id/partners-clients`, `/en/partners-clients`, `/id/contact`, `/en/contact`).

---

## 10. GA4 Analytics Integration Points (Strict No-PII)

Sesuai Analytics Architecture System Design:

1. **`language_switch` Event:**
   - **Pemicu:** Pengunjung mengklik tombol pengalih bahasa `ID | EN`.
   - **Parameter:** `source_locale`, `target_locale`, `current_path` (murni path URL tanpa query string). Bebas PII.
2. **`hero_cta_click` Event:**
   - **Pemicu:** Pengunjung mengklik tombol CTA pada Hero Banner aktif di beranda.
   - **Parameter:** `banner_id`, `locale`, `cta_type`, `destination_type` (tanpa query string/data sensitif). Bebas PII.
3. **`click_whatsapp` Event:**
   - **Pemicu:** Pengunjung mengklik tautan chat WhatsApp resmi ANS di footer atau halaman kontak.
   - **Parameter:** `locale`, `source_page` (misal: `'contact_page'` atau `'footer'`). Bebas PII.
4. **`start_quotation` & `submit_quotation` Events:**
   - Mengikuti ketentuan ketat **SPEC-04** (hanya dipicu sesuai alur form quotation dan hanya membawa `product_id`, `has_company`, `source`, `locale`).
5. **Audit Kepatuhan Privasi Mutlak (Strict No-PII):**
   - Seluruh event GA4 di atas **DILARANG KERAS** mengirim parameter identitas pribadi: nama, email, nomor telepon, isi pesan quotation, alamat fisik, IP address, atau data sensitif lainnya.

---

## 11. Accessibility Standards (WCAG 2.1 AA)

- **Semantic HTML5:** Penggunaan elemen `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`, dan `<aside>`.
- **Heading Hierarchy:** Tepat satu tag `<h1>` per halaman dengan urutan sub-heading `<h2>` dan `<h3>` yang logis.
- **Keyboard Navigation & Visible Focus:** Seluruh tautan, tombol hero carousel, hamburger menu, dan input form dapat diakses dengan tombol `Tab` dengan outline fokus terlihat jelas (`focus:ring-2`).
- **Atribut Alt Bermakna:** Logo prinsipal/klien menyertakan nama institusi pada tag `alt`. Gambar hero dekoratif menyertakan deskripsi visual yang sesuai.
- **Prefers-Reduced-Motion:** Transisi CSS dan autoplay carousel dinonaktifkan jika preferensi reduced motion diaktifkan oleh pengguna OS/browser.

---

## 12. Performance & Shared Hosting Optimization

- **Server-Side Rendered:** 100% menggunakan Blade View tanpa kompilasi SPA client-side yang lambat.
- **Critical Asset Prioritization:** Gambar hero banner slide pertama dimuat secara eager (`fetchpriority="high"`), sedangkan seluruh gambar di bawah fold (Core Values, Logos, Map) dimuat secara lazy (`loading="lazy"`).
- **Efisiensi Database:** Memuat record profil perusahaan, banner, dan core values secara efisien dengan kueri terindeks tanpa loop kueri N+1.
- **Shared Hosting Constraint Compliance:** *Production environment* **tidak membutuhkan Node.js server/runtime process yang berjalan terus-menerus**, Redis, queue worker, Elasticsearch, atau background daemon. (Node/npm hanya digunakan sebagai *build-time tooling* saat pengembangan/deployment aset frontend).

---

## 13. Error & Edge Cases Handling Matrix

| Skenario Edge Case | Perilaku Sistem yang Ditetapkan |
|---|---|
| **Tidak ada Hero Banner aktif (`is_active = true` 0 record)** | Sistem menyajikan *Graceful Fallback Hero Section* statis berlogo ANS dan CTA katalog tanpa broken layout. |
| **Banner aktif tidak memiliki `mobile_image_path`** | Sistem otomatis menggunakan `image_path` desktop sebagai gambar fallback di mobile. |
| **Data Visi/Misi/Tagline EN bernilai null di database** | Accessor Model menyajikan teks Bahasa Indonesia (`_id`) sebagai availability fallback. |
| **Foto Management tidak tersedia / belum diunggah** | Merender placeholder siluet profesional berlogo ANS tanpa broken image tag. |
| **Semua record Management berstatus `is_active = false`** | Section Manajemen di halaman About secara anggun tidak dirender ke layar publik. |
| **Iframe sematan peta gagal dimuat (offline/blocked)** | Menampilkan teks alamat fisik dan tombol tautan eksternal *"Buka di Google Maps"*. |
| **Koneksi Direct SMTP error saat kirim form kontak** | Quotation tetap tersimpan di MySQL; sistem mencatat log error dan menampilkan pesan sukses ke pengguna. |
| **Pengunjung membuka `/contact?product_id=999` (ID salah/nonaktif)** | Server menolak konteks produk dan memproses sebagai inquiry umum (`product_id = null`) tanpa error 500. |

---

## 14. Acceptance Criteria (Format Given / When / Then)

### AC-01: Akses Beranda Bahasa Indonesia (`/id`)
- **Given:** Pengunjung membuka `https://avenasa.co.id/id`.
- **When:** Halaman berhasil dimuat.
- **Then:** Server merender beranda dalam Bahasa Indonesia, tag `<html lang="id">` aktif, hero banner aktif pertama tampil dengan gambar eager, dan canonical URL menunjuk ke `/id`.

### AC-02: Autoplay & Interaktivitas Hero Banner Carousel
- **Given:** Terdapat 3 hero banner aktif di CMS.
- **When:** Pengunjung berada di beranda tanpa mengarahkan kursor ke banner.
- **Then:** Slide berpindah otomatis setiap 5-6 detik; saat kursor diarahkan ke banner (*hover*), autoplay berhenti sementara (*pause*).

### AC-03: Fallback Mobile Image pada Hero Banner
- **Given:** Suatu hero banner aktif memiliki `image_path` tetapi `mobile_image_path` bernilai `null`.
- **When:** Pengunjung membuka beranda pada layar smartphone (< 640px).
- **Then:** Banner tetap tampil menggunakan gambar `image_path` desktop tanpa broken image tag.

### AC-04: Akses Halaman Tentang Kami (`/id/about`)
- **Given:** Pengunjung membuka `/id/about`.
- **When:** Halaman dirender.
- **Then:** Sistem menyajikan profil narasi ANS, Visi, Misi, serta 6 kartu Core Values dalam format grid responsif.

### AC-05: Isolasi Record Management Nonaktif di Halaman About
- **Given:** Di database terdapat 3 record Management dengan status `is_active = false`.
- **When:** Pengunjung membuka `/id/about`.
- **Then:** Ketiga record tersebut **TIDAK TAMPIL** pada halaman publik.

### AC-06: Akses Halaman Mitra & Klien (`/id/partners-clients`)
- **Given:** Pengunjung membuka `/id/partners-clients`.
- **When:** Halaman dimuat.
- **Then:** Sistem menyajikan logo prinsipal resmi (termasuk Era Biology) dan logo klien aktif dalam kontainer rasio proporsional.

### AC-07: Validasi Keamanan Konteks Produk pada Halaman Kontak
- **Given:** Pengunjung membuka `/id/contact?product_id=5` (Produk ID 5 valid).
- **When:** Halaman kontak dimuat.
- **Then:** Server me-resolve produk dari database, menampilkan badge konteks resmi, dan menyematkan `product_id = 5` pada hidden input; saat POST dikirimkan, `product_id` divalidasi ulang di server.

### AC-08: Penanganan Invalid `product_id` pada Halaman Kontak
- **Given:** Pengunjung membuka `/id/contact?product_id=99999` (ID tidak ada di DB atau nonaktif).
- **When:** Halaman dimuat.
- **Then:** Server menyajikan form kontak dalam mode inquiry umum (`product_id = null`) tanpa mengasosiasikan produk dan tanpa error HTTP 500.

### AC-09: Pengiriman Form Kontak & Ketahanan Kegagalan SMTP
- **Given:** Pengunjung mengisi formulir kontak dengan data valid saat server SMTP eksternal sedang mengalami gangguan.
- **When:** Pengunjung menekan tombol Submit.
- **Then:** Record tersimpan sukses di tabel `quotations` MySQL dengan status `'New'`, error SMTP dicatat di log, browser **TIDAK** menampilkan HTTP 500, dan pengguna menerima notifikasi sukses.

### AC-10: Language Switcher pada Seluruh Halaman Publik
- **Given:** Pengunjung berada di `/id/about`.
- **When:** Pengunjung mengklik tombol switcher "EN".
- **Then:** Browser diarahkan ke `/en/about`, tag `<html lang="en">` terpasang, dan konten beralih ke Bahasa Inggris resmi.

### AC-11: Pelacakan Analitik GA4 `hero_cta_click` & `click_whatsapp` Bebas PII
- **Given:** Pengunjung mengklik tombol CTA Hero atau tautan chat WhatsApp resmi.
- **When:** Aksi klik dieksekusi.
- **Then:** Event GA4 terkait (`hero_cta_click` atau `click_whatsapp`) terpicu dengan parameter non-PII (`locale`, `source_page`, `banner_id`, `cta_type`, `destination_type`) tanpa data identitas pribadi pengguna.

---

## 15. Implementation Dependencies & Conceptual Order

### 15.1. Dependensi Arsitektur
- **Laravel 12 & Blade Engine:** Routing dan rendering template publik pada PHP 8.3.x.
- **Tailwind CSS 4.x & Alpine.js:** Untuk styling responsif, mobile navigation drawer, dan carousel hero banner.
- **Eloquent Models Terkait:** `CompanyProfile`, `CoreValue`, `Management`, `Client` (dari SPEC-02), `HeroBanner` (dari SPEC-03), `Product` & `Brand` (dari SPEC-01), dan `Quotation` (dari SPEC-04).
- **Localization Middleware:** `SetLocaleMiddleware` (dari SPEC-05).

### 15.2. Urutan Konseptual Implementasi
1. Pembuatan Global Master Layout Blade (`app.blade.php`, `header.blade.php`, `footer.blade.php`) dengan mobile navigation drawer & language switcher.
2. Implementasi Beranda (`Home`): Hero banner carousel Alpine.js, profil pengantar, pilar bisnis, dan CTA.
3. Implementasi Halaman Tentang Kami (`About`): Narasi profil, Visi & Misi, 6 Core Values grid, dan Management filter `is_active`.
4. Implementasi Halaman Mitra & Klien (`Partners & Clients`): Grid logo prinsipal (Brand) dan klien korporat (Client).
5. Implementasi Halaman Kontak (`Contact`): Informasi Mensana Tower, embed map responsif, dan integrasi form quotation (SPEC-04).
6. Integrasi SEO Meta Tags (canonical, hreflang, OpenGraph) dan GA4 dataLayer events.
7. Pengujian menyeluruh responsivitas mobile, aksesibilitas keyboard, dan ketahanan data fallback.

---

## 16. Out of Scope

Hal-hal berikut secara tegas dinyatakan **DI LUAR CAKUPAN (OUT OF SCOPE)** untuk Public Company Website V1:
- Fitur Blog / Berita / Artikel CMS.
- Live chat widget berbasis pihak ketiga berbayar (Intercom, Zendesk, Tawk.to).
- Integrasi Google Maps JavaScript API berbayar dengan custom map styling kompleks.
- Formulir pendaftaran karir / lowongan kerja (*Career Application Portal*).
- Sistem pemesanan / booking jadwal pertemuan online (*Appointment Booking System*).
- Animasi 3D WebGL / Canvas berat yang membebani performa browser seluler.

---

## 17. Architecture Consistency Notes

- **Architecture Consistency Audit: PASS**
- **Konsistensi dengan SPEC-02 & SPEC-03:** Seluruh data profil perusahaan, visi-misi, 6 core values, dan hero banner dikonsumsi murni dari schema database yang telah dikunci.
- **Konsistensi dengan SPEC-04:** Penanganan form kontak, anti-spam honeypot, resolusi server-side `product_id` (hidden input bukan single source of truth), dan isolasi SMTP failure selaras 100%.
- **Konsistensi dengan SPEC-05:** Struktur prefix URL dwibahasa (`/id/...` dan `/en/...`), language switcher mapping, dan content fallback selaras 100%.
- **Konsistensi dengan Shared Hosting:** Seluruh halaman dirender secara server-side tanpa runtime process Node.js di server production, tanpa background daemon, Redis, atau Elasticsearch.

---

*(Feature Specification Public Company Website telah selesai direvisi dan menunggu final review sebelum dikunci untuk implementasi.)*
