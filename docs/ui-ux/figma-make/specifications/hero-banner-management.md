# Feature Specification: Hero Banner Management

**Feature ID:** `SPEC-03-HERO-BANNER`  
**Feature Name:** Hero Banner Management  
**Proyek:** Website Company Profile & Katalog Produk  
**Klien:** PT Abhipraya Nawasena Sejahtera (ANS)  
**Dokumen Referensi & Sumber Kebenaran:**
1. [docs/architecture/technology-baseline.md](file:///c:/laragon/www/avenasa/docs/architecture/technology-baseline.md)
2. [docs/architecture/system-design.md](file:///c:/laragon/www/avenasa/docs/architecture/system-design.md)
3. [docs/urs/ans-website-urs.md](file:///c:/laragon/www/avenasa/docs/urs/ans-website-urs.md)
4. [docs/feature-specs/catalog-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/catalog-management.md)
5. [docs/feature-specs/company-content-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/company-content-management.md)
6. [docs/references/Company Profile ANS 2026 Rev.02.pdf](file:///c:/laragon/www/avenasa/docs/references/Company%20Profile%20ANS%202026%20Rev.02.pdf)  
**Status Dokumen:** Draft Feature Specification — Under Review

---

## 1. Feature Overview

Fitur **Hero Banner Management** adalah modul CMS terstruktur untuk mengelola visual bergerak utama (*hero carousel/slideshow*) yang disajikan pada bagian paling atas halaman beranda publik (*Homepage* `/{locale}`).

Fitur ini bertujuan untuk:
- Menyampaikan pesan promosi/kampanye utama, positioning bisnis (*Empowering Science for a Prosperous Future*), serta sorotan kategori produk atau principal resmi (seperti kemitraan Era Biology).
- Menyediakan judul utama (*headline*), sub-judul penjelas (*supporting text*), serta tombol aksi (*Call to Action / CTA*) dwibahasa (ID/EN) yang mengarahkan pengunjung ke halaman produk, kontak, atau tautan eksternal.
- Menjamin pengalaman visual optimal di seluruh perangkat (desktop, tablet, dan ponsel) dengan arsitektur **Mobile-First** dan dukungan *art-direction* gambar mobile opsional.
- Menerapkan prinsip **Structured CMS, Not Page Builder**, di mana seluruh konten terikat pada skema data yang terdefinisi secara ketat tanpa komponen *drag-and-drop layout builder*.

---

## 2. Scope

Scope fitur ini mencakup 1 entitas database utama yang dikelola di Filament 5:
1. **`HeroBanner` Entity:** Pengelolaan data slide banner beranda (gambar utama/desktop, gambar mobile opsional, teks dwibahasa, tombol CTA internal/eksternal, status aktif, dan urutan penataan).

Pemisahan tanggung jawab sistem:
- **Tanggung Jawab CMS (Filament 5):** Menyediakan form terstruktur bagi admin untuk membuat (*create*), melihat (*read*), memperbarui (*update*), menghapus (*delete* dengan pembersihan berkas fisik), mengaktifkan/menonaktifkan (*activation toggle*), serta mengatur urutan tampilan (*sort order*).
- **Tanggung Jawab Public Website (Blade + Alpine.js):** Mengonsumsi data banner berstatus aktif dari database, merender slider responsif (atau visual statis jika hanya ada 1 banner aktif), menangani fallback gambar mobile ke desktop, merender teks sesuai *locale* aktif, serta memicu event analitik GA4 saat tombol CTA diklik.

---

## 3. Actors & Permissions

- **Staf Administrator ANS:** Memiliki akses penuh pada Filament 5 Admin Panel untuk mengelola banner, mengunggah aset visual, mengatur link CTA, serta mengontrol status publikasi dan urutan slide.
- **Pengunjung Publik (B2B Leads & Stakeholders):** Memiliki akses *read-only* pada halaman beranda untuk melihat tayangan visual banner, membaca pesan utama, dan berinteraksi dengan tombol CTA.

---

## 4. Business Rules

1. **Prinsip Structured CMS, Bukan Page Builder:**
   - Hero banner memiliki struktur atribut yang kaku dan konsisten. Admin mengisi kolom teks dan mengunggah gambar sesuai form, tanpa menyusun layout blok HTML bebas.
2. **Aturan Wajib Gambar Desktop vs Gambar Mobile Opsional (Key Project Rule):**
   - **`image_path` (Gambar Utama / Desktop):** **Wajib diunggah (`REQUIRED`)**. Gambar ini berfungsi sebagai gambar visual utama dan sekaligus menjadi gambar *fallback* untuk perangkat mobile.
   - **`mobile_image_path` (Gambar Mobile Khusus):** **Opsional / Boleh Kosong (`NULLABLE`)**.
   - **Aturan Konsumsi Tampilan:**
     - *Skenario A (Single Responsive Image):* Jika `mobile_image_path` bernilai `NULL`, public frontend menggunakan satu berkas `image_path` untuk seluruh ukuran layar secara responsif dengan CSS object-fit/cover.
     - *Skenario B (Art-Directed Mobile Image):* Jika `mobile_image_path` diisi, public frontend menggunakan `image_path` untuk layar desktop/tablet (breakpoint `md:` ke atas) dan `mobile_image_path` untuk layar mobile (di bawah breakpoint `md:`).
3. **Art Direction Purpose:**
   - Penggunaan gambar mobile khusus hanya ditujukan untuk kebutuhan *art-direction* (misalnya: komposisi desktop terlalu lebar/horizontal sehingga objek utama terpotong, atau titik fokus visual/focal point perlu digeser vertikal pada layar sempit).
   - Admin **TIDAK DIWAJIBKAN** membuat dua file gambar untuk setiap banner.
4. **Aturan Visibilitas Publik (Activation Rule):**
   - Public homepage hanya menyajikan banner yang berstatus aktif (`is_active = true`).
   - Banner berstatus `is_active = false` tetap tersimpan di database/CMS namun disembunyikan sepenuhnya dari public website tanpa proses publikasi otomatis berbasis tanggal pada V1.
5. **Aturan Pengurutan Deterministic:**
   - Tampilan urutan slide pada beranda diurutkan berdasarkan `sort_order ASC`, kemudian `id ASC`.
6. **Integritas Penghapusan & Media Lifecycle:**
   - Saat record `HeroBanner` dihapus, sistem wajib membersihkan file fisik `image_path` dan `mobile_image_path` dari disk storage lokal untuk mencegah penumpukan berkas yatim (*orphaned files*).
   - Jika admin mengganti atau menghapus gambar mobile pada form CMS, file fisik lama wajib dihapus dari storage.

---

## 5. Core Data Model: `HeroBanner`

- **Tabel Database:** `hero_banners`
- **Model Eloquent:** `App\Models\HeroBanner`

### 5.1. Skema Kolom & Validasi Server-Side
| Nama Kolom | Tipe Data | Constraint / Rule | Deskripsi & Perilaku |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Identifikator unik banner. |
| `title_id` | `varchar(255)` | Required, Max: 255 | Judul utama banner dalam Bahasa Indonesia. |
| `title_en` | `varchar(255)` | Required, Max: 255 | Judul utama banner dalam Bahasa Inggris. |
| `subtitle_id` | `text` | Nullable | Teks penjelas/sub-judul dalam Bahasa Indonesia. |
| `subtitle_en` | `text` | Nullable | Teks penjelas/sub-judul dalam Bahasa Inggris. |
| `image_path` | `varchar(255)` | Required, Image (Max 2MB) | Path gambar utama/desktop di `storage/app/public/hero-banners/`. |
| `mobile_image_path` | `varchar(255)` | Nullable, Image (Max 2MB) | Path gambar mobile khusus (*art-directed*) di `storage/app/public/hero-banners/`. |
| `button_text_id` | `varchar(255)` | Nullable, Max: 255 | Teks label tombol CTA dalam Bahasa Indonesia (misal: "Lihat Produk"). |
| `button_text_en` | `varchar(255)` | Nullable, Max: 255 | Teks label tombol CTA dalam Bahasa Inggris (misal: "Explore Products"). |
| `button_url` | `varchar(255)` | Nullable, Max: 255 | Tautan tujuan tombol CTA (path internal atau URL eksternal). |
| `sort_order` | `integer` | Default: 0, Min: 0 | Urutan urutan tampil slide banner (1, 2, 3, dst.). |
| `is_active` | `boolean` | Default: `true` | Status publikasi slide banner. |
| `created_at` | `timestamp` | Nullable | Waktu pembuatan record. |
| `updated_at` | `timestamp` | Nullable | Waktu pembaruan record. |

---

## 6. Bilingual Content Rules & Fallback

1. **Atribut Terjemahan Eksplisit (Model Accessors):**
   Model `HeroBanner` menyediakan accessor dinamis untuk menyederhanakan pemanggilan di Blade view:
   - `$banner->title`: Mengembalikan `title_en` jika locale aktif `en` (fallback ke `title_id` jika kosong), dan `title_id` jika locale `id`.
   - `$banner->subtitle`: Mengembalikan `subtitle_en` jika locale aktif `en` (fallback ke `subtitle_id`), dan `subtitle_id` jika locale `id`.
   - `$banner->button_text`: Mengembalikan `button_text_en` jika locale aktif `en` (fallback ke `button_text_id`), dan `button_text_id` jika locale `id`.
2. **Kondisional Tampilan Tombol CTA:**
   - Tombol CTA hanya dirender pada public frontend jika **KEDUA** atribut (`button_text` dan `button_url`) memiliki nilai yang tidak kosong.
   - Jika salah satu bernilai null/kosong, tombol CTA disembunyikan tanpa merusak tata letak teks judul dan sub-judul.

---

## 7. CTA URL Routing & Handling Rules

Kolom `button_url` mendukung dua jenis target navigasi:
1. **Internal Path / Route:**
   - Format: Diawali dengan tanda garis miring (misal: `/products`, `/products?category=microbiology`, `/contact`, `/partners-clients`).
   - **Perilaku Localized URL:** Public Blade view secara otomatis memetakan path internal ke locale aktif menggunakan mekanisme lokalisasi/routing yang telah ditetapkan pada System Design:
     - Jika pengguna berada di `/id` dan CTA berisi `/products` -> tautan mengarah ke `/id/products`.
     - Jika pengguna berada di `/en` dan CTA berisi `/products` -> tautan mengarah ke `/en/products`.
     - **Preservasi Query Parameter:** Path yang memiliki query string (contoh: `/products?category=microbiology`) wajib mempertahankan query parameter tersebut secara utuh setelah lokalisasi (menjadi `/id/products?category=microbiology` atau `/en/products?category=microbiology`).
     - **Pencegahan Error Routing:** Resolusi URL wajib menggunakan existing localization mechanism (bukan string concatenation manual yang rawan menghasilkan *double slash* seperti `//products` atau penggandaan locale prefix seperti `/id/id/products`).
     - Admin tidak perlu meng-hardcode prefix `/id/` atau `/en/` pada form CMS.
2. **External URL:**
   - Format: Diawali dengan skema protokol resmi (`http://` atau `https://`, misal: `https://www.merckmillipore.com`).
   - Tautan eksternal tidak dimodifikasi oleh sistem lokalisasi dan dirender langsung dengan atribut pengaman HTML: `target="_blank" rel="noopener noreferrer"`.

---

## 8. Media Architecture & Lifecycle Rules

- **Direktori Penyimpanan:** `storage/app/public/hero-banners/`
- **Format Berkas Valid:** `jpg`, `jpeg`, `png`, `webp` (wajib lolos validasi MIME server-side).
- **Batas Ukuran Berkas:** Maksimal **2 MB** per gambar (`max:2048`).
- **Penyimpanan Lokal:** Menggunakan filesystem publik lokal Laravel (`public` disk) yang diakses via `asset('storage/' . $path)` melalui symlink `public/storage`. Dilarang menggunakan AWS S3, Cloudinary, atau CDN eksternal.
- **Siklus Hidup Media (Pencegahan Berkas Yatim / Orphaned Files):**
  - **Saat Mengganti Gambar Utama:** Sistem otomatis menghapus berkas fisik lama dari direktori `hero-banners/`.
  - **Saat Mengganti/Menghapus Gambar Mobile:** Sistem otomatis menghapus berkas fisik mobile lama dan mengembalikan `mobile_image_path` menjadi `NULL`.
  - **Saat Menghapus Record Banner:** Sistem menghapus kedua berkas fisik (`image_path` dan `mobile_image_path` jika ada) dari disk storage sebelum menghapus record dari database.

---

## 9. Mobile-First Presentation & Art Direction Rules

Hero banner wajib mengadopsi pendekatan **Mobile-First CSS** (Tailwind CSS 4.x) dengan ketentuan:

1. **Struktur Render Gambar Responsif (HTML `<picture>` Tag):**
   ```html
   <picture>
       @if($banner->mobile_image_path)
           <!-- Art-directed mobile image untuk konteks layar kecil -->
           <source media="(max-width: 767px)" srcset="{{ asset('storage/' . $banner->mobile_image_path) }}">
       @endif
       <!-- Main image untuk desktop atau fallback mobile jika mobile_image_path null -->
       <img src="{{ asset('storage/' . $banner->image_path) }}" 
            alt="{{ $banner->title }}" 
            class="w-full h-full object-cover object-center"
            loading="eager" 
            fetchpriority="high">
   </picture>
   ```
2. **Pedoman Implementasi Responsif (Implementation Guidance):**
   - **Prinsip Mobile-First:** Ketinggian container (*container height*), skala tipografi (*typography scale*), rasio aspek gambar (*image aspect ratio*), dan penyesuaian breakpoint harus mengikuti desain antarmuka responsif pada tahap UI implementation.
   - **Karakteristik Layar Mobile:** Teks wajib melakukan wrapping secara aman (*break-words*), target sentuh tombol CTA minimal `44px x 44px` untuk kemudahan akses jari, dan layout wajib mencegah terjadinya scroll horizontal (*no horizontal overflow*).
   - **Karakteristik Layar Desktop:** Layout desktop menyajikan komposisi visual yang luas dengan keterbacaan teks yang terjamin menggunakan lapisan overlay kontras semi-transparan.
   - **Rekomendasi Dimensi Aset (Guideline Admin, Bukan Database Constraint):**
     - Resolusi rekomendasi gambar desktop: ~`1920x800 px` (atau rasio landscape lebar).
     - Resolusi rekomendasi gambar mobile (*art-direction*): ~`800x1000 px` (atau rasio portrait/vertikal).
     - Angka-angka tersebut berfungsi sebagai panduan pembuatan aset grafis bagi staf admin dan bukan batasan validasi kaku sistem.

---

## 10. Filament 5 CMS Behavior: `HeroBannerResource`

Seluruh pengelolaan banner dikelompokkan di bawah grup navigasi **"Company Content"**:

### 10.1. Konfigurasi Navigasi
- **Group Navigasi:** *"Company Content"* (konsisten dengan System Design).
- **Label Navigasi:** *"Hero Banners"*.
- **Icon:** `heroicon-o-photo`.
- **Sort Order Navigasi:** 0 (ditampilkan paling atas pada grup Company Content).

### 10.2. Tampilan Tabel Index (`HeroBannerResource::table`)
- **Kolom Tabel:**
  - `image_path`: Image Column (thumbnail persegi/panjang 80px).
  - `title_id`: Text Column, searchable, sortable.
  - `title_en`: Text Column, searchable, toggleable (hidden by default).
  - `button_url`: Text Column (badge/link icon jika ada URL).
  - `sort_order`: Text Column / TextInput Column, sortable.
  - `is_active`: Toggle Column (dapat diaktifkan/dinonaktifkan langsung dari tabel).
- **Searchable Fields:** `title_id`, `title_en`, `subtitle_id`, `subtitle_en`.
- **Sortable Fields:** `sort_order`, `title_id`, `created_at`.
- **Filter Tabel:** Select Filter / Ternary Filter untuk status aktif (`is_active`).
- **Aksi Tabel:** Edit Action, Delete Action (dengan konfirmasi dialog).

### 10.3. Form Schema (`HeroBannerResource::form`)
Disusun dalam section terstruktur yang ramah bagi admin non-teknis:
- **Section 1: "Konten Teks Dwibahasa" (Bilingual Content):**
  - `title_id`: TextInput, required, label: *"Judul Utama (ID)"*, placeholder: *"Solusi Terbaik Laboratorium & Industri..."*.
  - `title_en`: TextInput, required, label: *"Main Title (EN)"*, placeholder: *"Empowering Science for a Prosperous Future..."*.
  - `subtitle_id`: Textarea, nullable, rows: 2, label: *"Sub-judul / Penjelas (ID)"*.
  - `subtitle_en`: Textarea, nullable, rows: 2, label: *"Subtitle / Description (EN)"*.
- **Section 2: "Aset Visual & Gambar" (Media Assets):**
  - `image_path`: FileUpload, required, image, max 2MB, directory: `hero-banners`, label: *"Gambar Utama / Desktop (Wajib)"*, helper text: *"Format JPG/PNG/WebP, maks 2MB. Resolusi rekomendasi: 1920x800 px. Berfungsi sebagai gambar utama desktop dan fallback mobile."*
  - `mobile_image_path`: FileUpload, nullable, image, max 2MB, directory: `hero-banners`, label: *"Gambar Khusus Mobile (Opsional / Art-Direction)"*, helper text: *"Kosongkan jika ingin menggunakan gambar desktop secara responsif. Isi hanya jika memerlukan komposisi artwork vertikal khusus mobile (rekomendasi: 800x1000 px)."*
- **Section 3: "Aksi Tombol CTA" (Call to Action - Opsional):**
  - Grid 2 kolom:
    - `button_text_id`: TextInput, nullable, label: *"Teks Tombol (ID)"* (misal: *"Jelajahi Produk"*).
    - `button_text_en`: TextInput, nullable, label: *"Button Text (EN)"* (misal: *"Explore Products"*).
  - `button_url`: TextInput, nullable, label: *"Tautan Tujuan (URL / Path)"*, helper text: *"Gunakan path internal seperti '/products' atau URL lengkap seperti 'https://...'."*
- **Section 4: "Pengaturan Visibilitas & Urutan":**
  - `sort_order`: TextInput numeric, default 0, min 0, label: *"Urutan Tampil (Sort Order)"*.
  - `is_active`: Toggle, default true, label: *"Status Aktif (Tampilkan di Beranda)"*.

---

## 11. Public Website Consumption & Fallback Behavior

### 11.1. Kasus Jumlah Banner Aktif
Public website merender banner beranda (`/{locale}`) dengan aturan kondisional berikut:

1. **Skenario 1: Tidak Ada Banner Aktif (`count = 0`):**
   - Homepage **TIDAK BOLEH** menghasilkan broken layout, error null/exception, atau meninggalkan kontainer carousel kosong yang rusak.
   - Sistem wajib menyediakan **Graceful Fallback Hero Section** yang mengonsumsi data identitas korporat yang telah tersedia dalam arsitektur atau desain UI (misal: Slogan dan Profil Perusahaan dari `CompanyProfile`).
   - Implementasi visual final dari fallback section ditentukan pada tahap implementasi UI frontend tanpa membuat entity atau tabel database baru.
2. **Skenario 2: Hanya Terdapat Tepat 1 Banner Aktif (`count = 1`):**
   - Public homepage merender banner tersebut sebagai **Hero Statis** tunggal.
   - Tombol navigasi slide (panah next/prev dan dots pagination) disembunyikan secara otomatis, dan fungsi rotasi otomatis dinonaktifkan.
3. **Skenario 3: Terdapat 2 atau Lebih Banner Aktif (`count >= 2`):**
   - Public homepage merender komponen **Hero Carousel/Slideshow** interaktif berbasis Alpine.js (tanpa menambahkan library eksternal).
   - **Karakteristik & Kontrol Aksesibel:**
     - Mendukung navigasi panah kiri/kanan dan indikator titik (*bullet dots*) yang dapat diklik serta ramah layar sentuh (*touch friendly*).
     - Memiliki perilaku pause/control yang aksesibel (rotasi otomatis berhenti saat pengguna berinteraksi, fokus tombol keyboard, atau sentuhan mobile, dan tidak hanya mengandalkan event mouse hover).
     - **Interval Rotasi (Rekomendasi Implementasi):** Default interval rotasi ~5–6 detik sebagai pedoman awal implementasi yang dapat disesuaikan pada konfigurasi frontend Blade/Alpine.js.

---

## 12. Accessibility & SEO Guidelines

1. **Aksesibilitas (WCAG 2.1 AA Compliance):**
   - **Alt Text Gambar:** Tag `<img>` wajib memiliki atribut `alt` yang bermakna, diisi dari atribut judul banner aktif (`$banner->title`).
   - **Kontras Teks (Color Contrast):** Seluruh teks judul dan sub-judul yang berada di atas gambar wajib dilapisi background overlay gelap semi-transparan (`bg-black/50` atau CSS gradient) untuk menjamin keterbacaan teks (*sufficient contrast ratio* >= 4.5:1).
   - **Navigasi Keyboard & Touch Target:** Tombol navigasi carousel dan tombol CTA memiliki outline fokus yang jelas (`focus:ring-2`) dan area sentuh minimal `44px x 44px`.
2. **SEO & Metadata:**
   - Judul Hero Banner **BUKAN** pengganti tag `<title>` dokumen HTML atau `<meta name="description">`. Tag SEO canonical tetap diatur oleh layout global dan page SEO architecture.
   - Tag heading judul banner pada beranda menggunakan `<h1>` untuk slide aktif pertama dan `<h2>`/`<div>` untuk slide berikutnya guna menjaga hierarki dokumen semantik yang benar.
   - Gambar banner pertama memiliki atribut `loading="eager"` dan `fetchpriority="high"` untuk mengoptimalkan metrik LCP (*Largest Contentful Paint*).

---

## 13. Analytics Integration Points (GA4 Events)

Integrasi analitik mengikuti arsitektur GA4 murni *client-side* tanpa PII yang telah dikunci pada System Design:

1. **`hero_cta_click` Event:**
   - **Pemicu:** Pengunjung mengklik tombol CTA pada salah satu slide hero banner di beranda.
   - **Payload Parameter:**
     - `banner_id`: ID unik banner (angka) sebagai **identifier utama**.
     - `destination_url`: Nilai `button_url` tujuan.
     - `sort_order`: Urutan posisi slide banner.
     - `locale`: Bahasa aktif (`id` atau `en`).
     - `banner_title`: (Opsional) Judul banner dalam bahasa yang sedang aktif sebagai konteks deskriptif pelengkap.
2. **Kepatuhan Privasi (No PII):** Dilarang keras mengirim data pribadi pengguna ke event analitik ini.

---

## 14. Error & Edge Cases Handling Matrix

| Skenario Edge Case | Perilaku Sistem yang Ditetapkan |
|---|---|
| **Tidak ada banner berstatus aktif di database** | Homepage merender graceful fallback hero section berbasis data korporat yang tersedia dengan rapi tanpa broken layout. |
| **Hanya ada 1 banner aktif** | Homepage merender banner tunggal secara statis tanpa tombol carousel atau autoplay. |
| **Banner aktif tanpa gambar mobile (`mobile_image_path = null`)** | Frontend otomatis menggunakan `image_path` (desktop) secara responsif dengan CSS `object-cover` untuk seluruh ukuran layar. |
| **Banner aktif dengan gambar mobile terisi** | Frontend merender tag `<picture>` yang menyajikan gambar mobile khusus pada layar kecil dan gambar desktop pada layar lebih besar. |
| **Teks Bahasa Inggris (EN) kosong** | Sistem secara aman menggunakan fallback teks Bahasa Indonesia (ID) padanannya. |
| **Tombol CTA hanya diisi teks tanpa URL (atau URL tanpa teks)** | Tombol CTA disembunyikan secara bersih dari tampilan banner publik. |
| **CTA menggunakan path internal (misal: `/products?category=microbiology`)** | Frontend otomatis memetakan localized URL (`/id/products?category=microbiology` atau `/en/products?category=microbiology`) dengan query string yang tetap utuh. |
| **CTA menggunakan URL eksternal (misal: `https://...`)** | Frontend merender link dengan target tab baru `_blank` dan pengaman `rel="noopener noreferrer"`. |
| **Admin mengganti file gambar banner di CMS** | File fisik gambar lama otomatis dihapus dari disk `storage/app/public/hero-banners/`. |
| **Admin menghapus record banner di CMS** | Kedua file gambar fisik (`image_path` dan `mobile_image_path`) dihapus dari disk lokal sebelum record database dihapus. |
| **Teks judul banner sangat panjang pada layar ponsel** | CSS layout menerapkan text wrapping rapi (*break-words* / *hyphens-auto*) tanpa menyebabkan horizontal overflow. |
| **Unggahan berkas gambar melebihi 2 MB** | Form Filament menolak berkas dengan pesan error validasi: *"Ukuran gambar tidak boleh melebihi 2 MB."* |

---

## 15. Acceptance Criteria (Format Given / When / Then)

### Kriteria 1: Pembuatan Hero Banner Standar (Single Image Responsive)
- **Given:** Admin membuka form Create Hero Banner di Filament 5.
- **When:** Admin mengisi `title_id` ("Solusi Laboratorium Terpadu"), `title_en` ("Integrated Laboratory Solutions"), mengunggah `image_path` (1.5MB WebP), membiarkan `mobile_image_path` kosong, mengisi teks CTA dan URL `/products`, lalu klik Save.
- **Then:** Record tersimpan di database MySQL, file gambar tersimpan di `storage/app/public/hero-banners/`, dan banner tampil aktif di beranda.

### Kriteria 2: Pembuatan Hero Banner dengan Art-Directed Mobile Image
- **Given:** Admin membuat banner baru dengan mengisi `image_path` desktop dan `mobile_image_path` vertikal khusus mobile.
- **When:** Pengunjung membuka beranda pada layar desktop (lebar 1280px).
- **Then:** Halaman memuat file gambar dari `image_path`.
- **When:** Pengunjung membuka beranda pada ponsel (lebar 390px).
- **Then:** Halaman memuat file gambar khusus dari `mobile_image_path`.

### Kriteria 3: Perilaku Fallback Gambar Mobile
- **Given:** Banner aktif memiliki `mobile_image_path = null`.
- **When:** Pengunjung membuka beranda pada perangkat ponsel.
- **Then:** Halaman tetap merender gambar utama `image_path` secara responsif tanpa broken image atau error visual.

### Kriteria 4: Pengurutan Deterministic Slide Banner
- **Given:** Terdapat Banner A (`sort_order = 2`) dan Banner B (`sort_order = 1`), keduanya aktif.
- **When:** Pengunjung membuka halaman beranda.
- **Then:** Banner B ditampilkan sebagai slide pertama, diikuti oleh Banner A sebagai slide kedua.

### Kriteria 5: Banner Inactive Tidak Muncul di Publik
- **Given:** Admin mengubah status Banner A menjadi `is_active = false` via Filament CMS.
- **When:** Pengunjung membuka halaman beranda.
- **Then:** Banner A tidak muncul dalam tayangan slider beranda; hanya banner berstatus aktif yang ditampilkan.

### Kriteria 6: Tautan CTA Internal Berbasis Locale
- **Given:** Banner memiliki `button_url = "/products"`.
- **When:** Pengunjung mengklik tombol CTA di halaman `/id`.
- **Then:** Browser diarahkan ke `/id/products`.
- **When:** Pengunjung mengklik tombol CTA di halaman `/en`.
- **Then:** Browser diarahkan ke `/en/products`.

### Kriteria 7: Pelacakan Analitik GA4 pada Klik CTA (No PII)
- **Given:** Pengunjung melihat banner aktif dengan tombol CTA.
- **When:** Pengunjung mengklik tombol CTA banner tersebut.
- **Then:** Event `hero_cta_click` terkirim ke GA4 membawa parameter `banner_id`, `banner_title`, `destination_url`, `sort_order`, dan `locale` tanpa memuat data identitas pribadi pengunjung.

### Kriteria 8: Pembersihan Berkas Fisik Media Saat Dihapus
- **Given:** Banner memiliki berkas gambar fisik di disk storage.
- **When:** Admin menghapus record banner tersebut dari Filament CMS.
- **Then:** Berkas fisik gambar desktop dan mobile terhapus dari storage lokal, dan record terhapus dari database.

---

## 16. Implementation Dependencies

Fitur Hero Banner Management membutuhkan dependensi berikut sebelum implementasi kode dimulai:
1. **Laravel 12 Framework Foundation:** Berjalan pada PHP 8.3.x.
2. **Database Migration MySQL 8.0+:** Tabel `hero_banners`.
3. **Storage Symlink:** Symlink `public/storage` terhubung ke `storage/app/public/`.
4. **Filament 5 Admin Panel:** Terpasang untuk merender `HeroBannerResource` di bawah grup navigasi *"Company Content"*.
5. **Tailwind CSS 4.x & Alpine.js:** Komponen frontend terpasang untuk merender slider interaktif responsif pada layout beranda Blade.
6. **SetLocaleMiddleware (Localization Engine):** Untuk menangani resolusi link CTA internal dwibahasa (`/id` dan `/en`).

---

## 17. Out of Scope

Hal-hal berikut secara tegas dinyatakan **DI LUAR CAKUPAN (OUT OF SCOPE)** untuk fitur Hero Banner Management V1:
- Sistem *Drag-and-Drop Visual Page Builder*.
- Video Background pada Hero Banner (hanya mendukung format gambar statis).
- Penyimpanan media di cloud storage eksternal (AWS S3, Google Cloud Storage, Cloudinary, atau external DAM).
- Penjadwalan otomatis publikasi banner berdasarkan tanggal dan waktu (*Campaign Scheduling*).
- Fitur A/B Testing variasi banner.
- Personalisasi banner per segmen pengguna (*User Banner Targeting*).
- Integrasi otomatis dengan tool marketing eksternal atau CRM.

---

*(Feature Specification Hero Banner Management telah selesai disusun dan menunggu final review sebelum dikunci untuk implementasi.)*
