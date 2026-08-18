# Feature Specification: Public Product Detail Experience

**Feature ID:** `SPEC-08-PUBLIC-PRODUCT-DETAIL`  
**Feature Name:** Public Product Detail Experience  
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
10. [docs/references/Company Profile ANS 2026 Rev.02.pdf](file:///c:/laragon/www/avenasa/docs/references/Company%20Profile%20ANS%202026%20Rev.02.pdf)  
**Status Dokumen:** Ready for Final Review

---

## 1. Feature Overview & Scope

Fitur **Public Product Detail Experience** mendefinisikan seluruh pengalaman interaksi publik saat pengunjung meninjau lembar informasi produk individual secara mendalam pada website publik PT Abhipraya Nawasena Sejahtera.

Fokus utama fitur ini adalah memberikan informasi teknis dan komersial alat kesehatan/laboratorium secara lengkap, elegan, terstruktur, aman, ramah seluler (*mobile-first*), serta memfasilitasi konversi prospek B2B menuju permintaan penawaran harga (*Quotation Inquiry*).

### 1.1. Cakupan Fitur (In Scope):
- Penyajian halaman detail produk berbasis rute terlokalisasi ketat (`/{locale}/products/{slug}`).
- Resolusi slug dwibahasa presisi (`slug_id` vs `slug_en`) dengan validasi visibilitas aktif (`is_active = true`).
- Breadcrumb navigasi hierarkis dwibahasa.
- Identitas produk: Foto utama (*Primary Image*), nama produk terlokalisasi, ringkasan singkat (`summary_id` / `summary_en`), identitas Brand/Principal resmi, dan Kategori.
- Galeri foto produk interaktif (*Product Image Gallery*) berbasis Alpine.js dengan dukungan aksesibilitas keyboard dan navigasi sentuh seluler.
- Deskripsi lengkap produk dan ringkasan spesifikasi teknis dinamis berbasis JSON terstruktur (aman dari XSS).
- Unduhan dokumen brosur resmi (*PDF Brochure Download*) berbasis resolusi file server-side yang aman.
- Tombol aksi utama Permintaan Penawaran (*Quotation CTA*) in-page dan bilah aksi lengket (*Sticky Action Bar*) pada layar seluler yang membawa *product context* ke halaman kontak.
- Penanganan navigasi kembali ke katalog (*Back to Catalog*).
- Kesiapan integrasi metadata SEO (SPEC-10) dan titik pemicu analitik GA4 (SPEC-09).

> [!NOTE]
> Spesifikasi ini murni mengatur **pengalaman pengunjung publik (Public-Facing Experience)**. Pengelolaan data master katalog di CMS telah didefinisikan pada **SPEC-01**, arsitektur dwibahasa pada **SPEC-05**, alur formulir quotation pada **SPEC-04**, dan katalog publik pada **SPEC-06**. Detail arsitektur analitik dan SEO final masing-masing akan dikunci pada **SPEC-09** dan **SPEC-10**.

---

## 2. Public URL Architecture & Strict Locale Slug Resolution

Mengikuti keputusan arsitektur **SPEC-01** dan **SPEC-05 Localization**:

### 2.1. Format URL Kanonikal
- **Bahasa Indonesia (`id`):** `/id/products/{slug_id}` (Contoh: `/id/products/alat-pcr-real-time`)
- **Bahasa Inggris (`en`):** `/en/products/{slug_en}` (Contoh: `/en/products/real-time-pcr-system`)

### 2.2. Aturan Resolusi Slug Ketat (*Strict Resolution*)
1. **Rute Bahasa Indonesia (`/id/products/{slug}`):**
   - Server **hanya mencocokkan kolom `slug_id`** pada record produk yang berstatus aktif (`is_active = true`).
2. **Rute Bahasa Inggris (`/en/products/{slug}`):**
   - Server **hanya mencocokkan kolom `slug_en`** pada record produk yang berstatus aktif (`is_active = true`).
3. **Larangan Kueri Ambigu (`OR` Query):**
   - Dilarang keras menggabungkan pencarian `slug_id` dan `slug_en` dalam satu kueri ambigu `OR`.
   - Mengakses slug bahasa Inggris pada rute Indonesia (misal: `/id/products/real-time-pcr-system`) atau sebaliknya wajib mengembalikan **`HTTP 404 Not Found`**.
4. **Visibilitas Produk Aktif:**
   - Produk berstatus `is_active = false` otomatis ditolak dan mengembalikan respon **`HTTP 404 Not Found`**. Produk nonaktif tidak memiliki URL publik yang dapat diakses.

---

## 3. Product Detail Page Structure

Tata letak halaman detail produk disusun secara hierarkis:

```
+-------------------------------------------------------------------+
| GLOBAL HEADER (Logo, Navigasi, Language Switcher ID|EN)           |
+-------------------------------------------------------------------+
| BREADCRUMB: Home > Produk > [Kategori] > [Nama Produk]            |
+---------------------------------+---------------------------------+
| SISI KIRI: GALERI FOTO          | SISI KANAN: IDENTITAS & CTA     |
| - Foto Utama (Primary Image)    | - Badge Brand & Kategori        |
| - Thumbnail Carousel (Gallery)  | - Judul Produk (H1 Terlokalisasi|
| - Tombol Prev/Next & Counter    | - Ringkasan / Summary           |
|                                 | - Tombol CTA "Minta Penawaran"  |
|                                 | - Tombol "Download Brosur (PDF)"|
+---------------------------------+---------------------------------+
| SPESIFIKASI TEKNIS & DESKRIPSI LENGKAP                            |
| - Tab / Section Deskripsi Lengkap Produk                          |
| - Tabel Spesifikasi Teknis Dinamis (JSON Key-Value Terstruktur)   |
+-------------------------------------------------------------------+
| NAVIGASI FOOTER: "Kembali ke Katalog Produk"                      |
+-------------------------------------------------------------------+
| GLOBAL FOOTER                                                     |
+-------------------------------------------------------------------+
| [MOBILE < 768px ONLY] STICKY BOTTOM ACTION BAR: CTA Penawaran     |
+-------------------------------------------------------------------+
```

---

## 4. Breadcrumb & Product Identity

### 4.1. Breadcrumb Navigasi Hierarkis
- Menyajikan jejak navigasi semantik:
  - Locale ID: `Beranda` (`/id`) → `Produk` (`/id/products`) → `[Nama Kategori]` (`/id/products?category={category_slug_id}`) → `[Nama Produk]`
  - Locale EN: `Home` (`/en`) → `Products` (`/en/products`) → `[Category Name]` (`/en/products?category={category_slug_en}`) → `[Product Name]`
- Menggunakan elemen semantik `<nav aria-label="Breadcrumb">` dan tag terstruktur.

### 4.2. Identitas Produk & Hirarki Tipografi
- **Tag `<h1>` Utama:** Menampilkan nama produk terlokalisasi (`name_id` pada locale ID, `name_en` pada locale EN).
- **Brand / Principal Showcase (Graceful Omission):** Jika relasi Brand tersedia, sistem menampilkan nama Brand resmi dan logo Brand. Jika relasi Brand tidak tersedia (kondisi anomali defensif), section/badge Brand disembunyikan secara bersih (*graceful omission*) tanpa menampilkan teks placeholder fiktif atau merusak tampilan.
- **Kategori Produk (Graceful Omission):** Jika relasi Kategori tersedia, sistem menampilkan nama Kategori resmi (`name_id` / `name_en`). Jika relasi Kategori tidak tersedia, badge Kategori disembunyikan secara bersih (*graceful omission*).
- **Ringkasan Singkat:** Menampilkan `summary_id` / `summary_en` (tersedia pada skema `products` SPEC-01) sebagai paragraf pembuka yang tajam.
- **Deskripsi Lengkap:** Menampilkan `description_id` / `description_en` dalam tipografi yang mudah dibaca (*prose styling*). Jika deskripsi kosong, section disembunyikan secara anggun tanpa menyisakan heading kosong.

---

## 5. Product Image Gallery Specification

Galeri produk dirancang untuk memberikan visualisasi alat yang jelas dan responsif:

### 5.1. Struktur Data Visual & Fallback Behavior
1. **Foto Utama (`primary_image_path`):**
   - Wajib ditampilkan sebagai gambar pembuka galeri.
   - Diprioritaskan sebagai kandidat LCP (*Largest Contentful Paint*) menggunakan `loading="eager"` dan `fetchpriority="high"`.
   - **Missing Primary Image Fallback:** Jika file foto utama secara fisik tidak ditemukan di penyimpanan disk storage, sistem merender placeholder visual netral/korporat sederhana via UI/CSS tanpa menampilkan gambar rusak (*broken image tag*) dan tanpa memberikan impresi keliru sebagai foto produk aktual.
2. **Foto Galeri Pendukung (`ProductImage`):**
   - Menampilkan koleksi foto pendukung dari relasi `Product -> productImages`, diurutkan berdasarkan `sort_order ASC`.

### 5.2. Interaktivitas Galeri & Breakpoint Responsif
Sistem menerapkan perilaku galeri yang konsisten terhadap breakpoint standar proyek:
- **Mobile (`< 768px`):**
  - Galeri geser (*swipeable carousel*) dengan navigasi sentuh jari (*touch swipe*) satu kolom tanpa menyebabkan horizontal page overflow.
  - Tombol navigasi manual *Previous / Next* dan indikator counter foto (misal: `"1 / 4"`).
- **Tablet (`768px - < 1024px`):**
  - Layout dua kolom responsif dengan gambar utama yang proporsional dan deretan thumbnail interaktif yang mudah disentuh.
- **Desktop (`>= 1024px`):**
  - Area gambar utama besar dengan rasio aspek konsisten (1:1 atau 4:3) menggunakan CSS `object-contain` berlatar belakang kontainer netral/putih bersih di sisi kiri, dengan deretan thumbnail foto di bawahnya.
- **Aksesibilitas Galeri (WCAG 2.1 AA):**
  - Navigasi penuh via keyboard (tombol panah kiri/kanan dan tombol `Tab`).
  - Seluruh tombol kontrol memiliki atribut aksesibel: `aria-label="Foto Sebelumnya"`, `aria-label="Foto Berikutnya"`, dan `aria-label="Pilih Foto 2"`.
  - Tag `<img>` menyertakan atribut `alt` bermakna berbasis nama produk.

---

## 6. Product Specifications Rendering (Structured JSON)

Spesifikasi teknis produk dikonsumsi dari kolom database `specifications` (format JSON Key-Value bilingual terstruktur dari SPEC-01):

### 6.1. Format Data JSON & Rendering Publik
- **Representasi Data:** Array of Key-Value Object:
  ```json
  [
    {"key_id": "Rentang Pengukuran", "key_en": "Measurement Range", "value_id": "0.1 - 100 mg/L", "value_en": "0.1 - 100 mg/L"},
    {"key_id": "Waktu Deteksi", "key_en": "Detection Time", "value_id": "15 Menit", "value_en": "15 Minutes"}
  ]
  ```
- **Tampilan Publik:**
  - Dirender dalam bentuk tabel spesifikasi dua kolom yang bersih dan elegan (Kolom Kiri: Parameter/Fitur Teknis, Kolom Kanan: Nilai Spesifikasi).
  - Teks parameter dan nilai otomatis menyesuaikan locale aktif (`key_id`/`value_id` pada locale ID, `key_en`/`value_en` pada locale EN).
- **Keamanan XSS & Sanitasi:**
  - Seluruh output teks spesifikasi di-escape secara default oleh Blade (`{{ ... }}`) untuk mencegah serangan Cross-Site Scripting (XSS).
- **Empty State Penanganan:**
  - Jika kolom `specifications` bernilai `null` atau berupa array kosong (`[]`), section spesifikasi disembunyikan secara bersih tanpa merender tabel kosong atau broken layout.

---

## 7. Product Brochure Download Feature

Fitur unduhan brosur resmi memfasilitasi calon klien untuk mendapatkan lembar spesifikasi teknis lengkap:

### 7.1. Ketentuan File Brosur
- **Format:** Dokumen PDF resmi (`.pdf`).
- **Ukuran Maksimum:** 10 MB (sesuai limitasi SPEC-01).
- **Path Penyimpanan:** Disimpan pada local public storage (`storage/app/public/brochures/...`).

### 7.2. Tampilan & Perilaku Unduhan (Download Behavior)
- **Kondisi Brosur Tersedia (`brochure_path` valid & file fisik ada):**
  - Menampilkan tombol aksi terkemuka: *"Unduh Brosur Produk (PDF)"* / *"Download Product Brochure (PDF)"* dengan ikon dokumen PDF dan informasi format.
- **Kondisi Brosur Tidak Tersedia (`brochure_path = null`):**
  - Tombol unduh brosur secara anggun disembunyikan (*hidden*) agar tidak menampilkan tautan rusak.
- **Keamanan Jalur Unduhan (Server-Side Resolution Security):**
  - Unduhan brosur dilayani melalui route terlokalisasi berbasis resolusi database record `Product` aktif (misal: `GET /{locale}/products/{slug}/brochure`).
  - Dilarang keras menerima arbitrary query parameter seperti `?file=path/to/file.pdf` dari client guna mencegah serangan *Path Traversal* atau eksposisi file sistem internal server.
  - Server memeriksa keberadaan fisik file sebelum merespon unduhan stream/download.
- **Penanganan File Fisik Hilang:**
  - Jika path tercatat di database namun file fisik hilang di disk, server secara anggun mengembalikan respon HTTP 404 informatif tanpa melempar HTTP 500 fatal exception.

---

## 8. Quotation CTA & Responsive Action Behavior

### 8.1. Tombol CTA Permintaan Penawaran (In-Page)
- **Teks Tombol:** *"Minta Penawaran Harga"* / *"Request a Quotation"*.
- **Tautan Navigasi:** Mengarahkan langsung ke halaman kontak dengan parameter ID produk:
  `/{locale}/contact?product_id={$product->id}`
- **Larangan Client String:** Dilarang membawa string nama produk di URL (`?product_name=...`); identitas produk murni di-resolve server-side oleh halaman kontak (sesuai SPEC-04 & SPEC-07).

### 8.2. Mobile Sticky Bottom Action Bar (`< 768px`)
- Pada perangkat smartphone (`< 768px`), sistem menyajikan bilah aksi lengket di bagian bawah layar (*Sticky Bottom Action Bar*):
  - Berisi tombol utama *"Minta Penawaran"* yang mudah dijangkau satu tangan.
  - Tetap terlihat (*fixed bottom*) saat pengguna membaca spesifikasi teknis panjang.
  - Memiliki tinggi area sentuh minimal `44px x 44px` dan padding bawah aman (*safe-area-inset-bottom*).
  - Tidak menghalangi teks konten utama (diberikan margin bottom kompensasi pada body layout).
- Pada perangkat Tablet (`768px - < 1024px`) dan Desktop (`>= 1024px`), CTA penawaran disajikan secara in-page normal di area informasi produk tanpa sticky bottom bar.

### 8.3. Navigasi Kembali ke Katalog (*Back to Catalog*)
- Di bagian bawah konten produk, disediakan tombol navigasi kontekstual:
  - `← Kembali ke Katalog Produk` (`/{locale}/products`)
  - Jika kategori produk tersedia: `← Lihat Produk Lain di Kategori [Nama Kategori]` (`/{locale}/products?category={$category->slug_id}`)

---

## 9. Language Switcher on Product Detail

Mengikuti aturan ketat **SPEC-05 Localization**:

```
[Pengunjung berada di: /id/products/cawan-petri-steril]
                       ↓ (Klik "EN" pada Switcher)
[Sistem memetakan ke: $product->slug_en padanannya]
                       ↓
[Browser diarahkan ke: /en/products/sterile-petri-dish]
```

### 9.1. Aturan Pemetaan Switcher
1. **Kondisi Normal (Slug ID & EN Tersedia):** Switcher mengarahkan langsung ke slug padanannya:
   `/id/products/{slug_id}` ↔ `/en/products/{slug_en}`.
2. **Defensive Fallback (Anomali Ketiadaan `slug_en`):** Jika karena anomali data produk aktif tidak memiliki `slug_en`, link switcher EN secara aman mengarahkan pengunjung ke halaman katalog induk `/en/products` (mencegah broken URL 404).

---

## 10. Progressive Enhancement & JavaScript Degradation

Halaman Product Detail dirancang dengan prinsip **Progressive Enhancement**:
- **Fungsionalitas Tanpa JavaScript (No-JS Core):**
  - Jika JavaScript dinonaktifkan di browser: Foto utama produk tetap tampil, seluruh teks narasi, ringkasan, dan tabel spesifikasi teknis tetap terbaca sempurna secara server-rendered, tombol download brosur tetap berfungsi normal, dan tombol CTA Minta Penawaran tetap mengarahkan ke form kontak.
- **Penyempurnaan Berbasis JavaScript (Alpine.js):**
  - JavaScript murni digunakan untuk interaktivitas visual tambahan: peralihan thumbnail galeri foto, gestur sentuh swipe di ponsel, dan sticky bottom bar transition.

---

## 11. Performance & Shared Hosting Optimization

- **LCP Optimization:** Tag `<img>` foto utama produk diberi atribut `loading="eager"` dan `fetchpriority="high"` dengan rasio aspek eksplisit (`aspect-square` atau `width`/`height`) untuk mencegah *Cumulative Layout Shift* (CLS).
- **Lazy Loading Asset Non-Kritis:** Foto galeri thumbnail dan aset pendukung lainnya diberi atribut `loading="lazy"`.
- **Eager Loading Anti N+1:** Product Detail harus memuat record Product beserta seluruh relasi yang dibutuhkan (`category`, `brand`, `productImages`) dalam jumlah kueri yang efisien guna mencegah masalah N+1 query.
- **Shared Hosting Constraint Compliance:** *Production environment* **tidak membutuhkan Node.js runtime process yang berjalan terus-menerus**, Redis, queue worker, Elasticsearch, atau background daemon.

---

## 12. Security Architecture on Product Detail

1. **Strict Route Model Binding & Active Validation:** Server hanya menyajikan produk yang lolos validasi `is_active = true`.
2. **Path Traversal Protection pada Brosur:** File brosur diunduh via controller berbasis nama file yang tersimpan di database internal, bukan dari input path bebas dari URL client.
3. **Output Escaping Mutlak:** Seluruh teks nama produk, deskripsi, ringkasan, dan spesifikasi JSON di-escape secara aman guna mencegah serangan XSS.
4. **No PII Transmission:** Tidak ada data pribadi pengunjung yang diekspos atau dikirim ke pihak ketiga pada halaman detail produk.

---

## 13. Accessibility Standards (WCAG 2.1 AA)

- **Hierarki Heading Semantik:** Tepat satu tag `<h1>` utama (Nama Produk), diikuti tag `<h2>` untuk section galeri, deskripsi, spesifikasi teknis, dan unduhan brosur.
- **Kontrol Galeri Aksesibel:** Seluruh tombol thumbnail, previous, dan next dapat diakses via navigasi keyboard (`Tab`, `Enter`, `Space`) dengan outline fokus yang jelas (`focus:ring-2`).
- **Atribut Alt Bergambar:** Tag `<img>` menyertakan deskripsi visual produk yang jelas.
- **Dukungan Prefers-Reduced-Motion:** Transisi peralihan foto galeri dinonaktifkan secara otomatis jika preferensi *reduced motion* aktif di sistem operasi pengguna.
- **Target Sentuh Seluler:** Seluruh tombol CTA dan thumbnail memiliki dimensi area sentuh minimal `44px x 44px`.

---

## 14. SEO & Analytics Integration Boundaries

### 14.1. Kesiapan Data untuk SEO (Candidate Data untuk SPEC-10)
Product Detail menyediakan data kandidat metadata yang bersumber murni dari atribut Product yang telah ada:
- Candidate Title: Bersumber dari nama produk dan nama brand.
- Candidate Description: Bersumber dari `summary_id` / `summary_en` (atau cuplikan `description_id` / `description_en`).
- Candidate Canonical & Hreflang: Menggunakan rute terlokalisasi `/id/products/{slug_id}` dan `/en/products/{slug_en}`.
- Candidate OpenGraph Image: Menggunakan `primary_image_path`.
- *(Catatan: Definisi arsitektur SEO, tag kanonikal final, hreflang tags, dan Structured Data Schema.org secara eksklusif menjadi tanggung jawab **SPEC-10 SEO & Discoverability**).*

### 14.2. Titik Pemicu Analitik GA4 (Trigger Points untuk SPEC-09)
Product Detail menyediakan titik pemicu (*trigger points*) behavioral bagi modul analitik:
1. **`view_product`:** Pemicu aktif ketika halaman detail produk berhasil selesai dirender di browser.
2. **`download_brochure`:** Pemicu aktif ketika pengunjung berhasil mengklik dan memulai unduhan file brosur PDF produk yang valid.
3. **`start_quotation`:** Pemicu aktif ketika pengunjung mengklik tombol CTA *"Minta Penawaran"*.
- Identitas produk, kategori, brand, locale, dan metadata kontekstual non-PII lainnya tersedia sebagai *candidate event context*; spesifikasi parameter detail, format dataLayer, dan kepatuhan privasi final akan ditetapkan secara eksklusif pada **SPEC-09 Analytics**.

---

## 15. Error & Edge Cases Handling Matrix

| Skenario Edge Case | Perilaku Sistem yang Ditetapkan |
|---|---|
| **Slug produk tidak ditemukan di database** | Server mengembalikan respon `HTTP 404 Not Found` yang graceful. |
| **Produk berstatus nonaktif (`is_active = false`)** | Server menolak akses dan mengembalikan respon `HTTP 404 Not Found`. |
| **Slug bahasa Inggris diakses pada prefix ID (`/id/products/english-slug`)** | Server mengembalikan respon `HTTP 404 Not Found` (mencegah kueri ambigu). |
| **Foto utama produk rusak/hilang di storage** | Merender placeholder visual netral/branded via UI/CSS tanpa broken image tag. |
| **Galeri pendukung kosong (`productImages` 0 item)** | Galeri hanya menampilkan foto utama tanpa merender thumbnail carousel kosong. |
| **Salah satu file foto galeri hilang di disk storage** | Sistem secara anggun melewatkan (*skip*) thumbnail rusak tersebut. |
| **Relasi Brand atau Kategori bernilai null** | Graceful omission: Menyembunyikan badge terkait tanpa merusak layout atau menampilkan data fiktif. |
| **Deskripsi atau Spesifikasi JSON bernilai null/kosong** | Section terkait disembunyikan secara bersih tanpa menyisakan heading kosong. |
| **Field `brochure_path` bernilai null di database** | Tombol unduh brosur disembunyikan secara otomatis. |
| **Field `brochure_path` ada tetapi file fisik PDF hilang di disk** | Server mengembalikan respon 404 informatif tanpa melempar fatal HTTP 500 exception. |
| **Percobaan akses brosur dengan parameter path manipulasi** | Ditolak; file brosur hanya dilayani berbasis record produk yang terverifikasi di server. |
| **JavaScript dinonaktifkan oleh pengguna browser** | Halaman tetap menyajikan foto utama, spesifikasi, dan tombol aksi secara server-side. |

---

## 16. Acceptance Criteria (Format Given / When / Then)

### AC-01: Akses Halaman Detail Produk Bahasa Indonesia
- **Given:** Produk aktif dengan `slug_id = 'alat-pcr-real-time'`.
- **When:** Pengunjung membuka `/id/products/alat-pcr-real-time`.
- **Then:** Halaman merender detail produk dalam Bahasa Indonesia, tag `<html lang="id">` aktif, dan rute canonical ID disajikan.

### AC-02: Akses Halaman Detail Produk Bahasa Inggris
- **Given:** Produk aktif dengan `slug_en = 'real-time-pcr-system'`.
- **When:** Pengunjung membuka `/en/products/real-time-pcr-system`.
- **Then:** Halaman merender detail produk dalam Bahasa Inggris, tag `<html lang="en">` aktif, dan rute canonical EN disajikan.

### AC-03: Penolakan Resolusi Slug Bahasa Silang (Cross-Language Slug Rejection)
- **Given:** Produk memiliki `slug_en = 'real-time-pcr-system'`.
- **When:** Pengunjung mencoba membuka `/id/products/real-time-pcr-system`.
- **Then:** Sistem mengembalikan respon `HTTP 404 Not Found`.

### AC-04: Penolakan Akses Produk Nonaktif
- **Given:** Produk berstatus `is_active = false` dengan `slug_id = 'reagen-lama'`.
- **When:** Pengunjung membuka `/id/products/reagen-lama`.
- **Then:** Sistem mengembalikan respon `HTTP 404 Not Found`.

### AC-05: Penampilan Identitas Produk Lengkap
- **Given:** Produk terhubung dengan Brand "Merck" dan Kategori "Mikrobiologi", serta memiliki ringkasan `summary_id`.
- **When:** Halaman detail produk dimuat.
- **Then:** Sistem menampilkan nama produk pada tag `<h1>`, badge brand Merck, badge kategori Mikrobiologi, paragraf ringkasan, dan foto utama produk.

### AC-06: Interaktivitas Galeri Foto Produk Desktop & Mobile
- **Given:** Produk memiliki 3 foto galeri pendukung.
- **When:** Pengunjung mengklik thumbnail foto kedua di desktop atau melakukan swipe di seluler.
- **Then:** Tampilan gambar utama berganti menampilkan foto kedua dengan mulus dan indikator counter terbarui.

### AC-07: Aksesibilitas Keyboard pada Galeri Foto
- **Given:** Pengunjung menavigasi halaman menggunakan keyboard.
- **When:** Pengunjung memfokuskan tombol thumbnail galeri dan menekan tombol `Enter` atau tombol panah.
- **Then:** Fokus visual terlihat jelas (`focus:ring-2`) dan foto aktif berganti sesuai pilihan.

### AC-08: Responsivitas Mobile Gallery (< 768px) Tanpa Horizontal Overflow
- **Given:** Pengunjung membuka galeri produk pada perangkat ponsel (< 768px).
- **When:** Pengunjung menggeser foto galeri.
- **Then:** Galeri bergeser mulus di dalam kontainernya tanpa menyebabkan body halaman bergeser secara horizontal (*no horizontal page scroll*).

### AC-09: Rendering Tabel Spesifikasi Teknis JSON
- **Given:** Kolom `specifications` memuat 3 parameter teknis Key-Value bilingual.
- **When:** Halaman detail produk dimuat pada locale ID.
- **Then:** Sistem merender tabel 2 kolom yang menampilkan teks `key_id` dan `value_id` yang bersih dan ter-escape dari XSS.

### AC-10: Penanganan Spesifikasi Kosong Tanpa Broken Layout
- **Given:** Kolom `specifications` bernilai `null` atau `[]`.
- **When:** Halaman detail produk dimuat.
- **Then:** Section spesifikasi disembunyikan secara bersih tanpa menyisakan judul kosong atau tabel rusak.

### AC-11: Ketersediaan Tombol Unduh Brosur PDF
- **Given:** Produk memiliki `brochure_path` valid dan file PDF fisik tersedia di disk storage.
- **When:** Halaman detail produk dimuat.
- **Then:** Tombol *"Download Brochure (PDF)"* ditampilkan secara terkemuka.

### AC-12: Penyembunyian Tombol Brosur yang Tidak Tersedia
- **Given:** Kolom `brochure_path` bernilai `null`.
- **When:** Halaman detail produk dimuat.
- **Then:** Tombol unduh brosur disembunyikan secara otomatis.

### AC-13: Penanganan File Fisik Brosur Hilang Tanpa Error 500
- **Given:** Kolom `brochure_path` terisi di DB namun file fisik terhapus di storage.
- **When:** Pengunjung mencoba mengklik tautan unduhan brosur.
- **Then:** Server merespon dengan `HTTP 404` yang anggun tanpa melempar fatal exception HTTP 500.

### AC-14: Keamanan Jalur Unduhan Brosur (Anti-Path Traversal)
- **Given:** Pengunjung mencoba mengirimkan query `?file=../../etc/passwd` pada endpoint unduhan.
- **When:** Request diproses oleh server.
- **Then:** Server mengabaikan parameter bebas client dan hanya memproses file berdasarkan path database internal record produk terkait.

### AC-15: Navigasi CTA Minta Penawaran Membawa Product Context
- **Given:** Pengunjung berada di halaman produk dengan ID = 15.
- **When:** Pengunjung mengklik tombol *"Minta Penawaran"*.
- **Then:** Browser diarahkan ke `/{locale}/contact?product_id=15` (tanpa query string nama produk).

### AC-16: Validasi Konteks Produk Server-Side pada Halaman Kontak
- **Given:** Pengunjung tiba di halaman kontak dengan `product_id = 15`.
- **When:** Halaman kontak dimuat.
- **Then:** Server me-resolve produk ID 15 dari database dan menampilkan badge konteks resmi; hidden input bukan single source of truth.

### AC-17: Penolakan Konteks Produk Tidak Valid
- **Given:** Pengunjung diarahkan ke `/contact?product_id=99999` (ID salah/nonaktif).
- **When:** Halaman kontak dimuat.
- **Then:** Server secara aman memproses request sebagai inquiry umum (`product_id = null`) tanpa error sistem.

### AC-18: Peralihan Bahasa (Language Switcher) pada Detail Produk
- **Given:** Produk memiliki `slug_id = 'alat-pcr'` dan `slug_en = 'pcr-system'`.
- **When:** Pengunjung berada di `/id/products/alat-pcr` dan mengklik tombol "EN" pada switcher.
- **Then:** Browser diarahkan langsung ke `/en/products/pcr-system` dengan HTTP 200.

### AC-19: Keterjangkauan Mobile Sticky Bottom Action Bar (< 768px)
- **Given:** Pengunjung membuka halaman detail produk pada layar ponsel (< 768px).
- **When:** Pengunjung melakukan scroll ke bawah membaca spesifikasi.
- **Then:** Bilah aksi lengket (*Sticky Bottom Bar*) tetap terlihat di bagian bawah dengan tombol CTA *"Minta Penawaran"* berukuran sentuh minimal `44px x 44px`.

### AC-20: Responsivitas Tata Letak Mobile / Tablet / Desktop
- **Given:** Halaman detail produk dibuka pada resolusi Mobile (< 768px), Tablet (768px - < 1024px), dan Desktop (>= 1024px).
- **When:** Halaman dirender.
- **Then:** Seluruh elemen (galeri, tabel spesifikasi, narasi deskripsi, tombol aksi) tampil simetris dan adaptif sesuai breakpoint tanpa menyebabkan horizontal scroll pada body.

### AC-21: Prioritas Performa Gambar Utama (LCP Optimization)
- **Given:** Halaman detail produk dimuat oleh browser.
- **When:** HTML diuraikan.
- **Then:** Tag `<img>` foto utama memiliki atribut `loading="eager"` dan `fetchpriority="high"`, sedangkan thumbnail galeri memiliki `loading="lazy"`.

### AC-22: Kemandirian Fungsi Inti Tanpa JavaScript (No-JS Core)
- **Given:** Pengguna menonaktifkan JavaScript di browser.
- **When:** Pengunjung membuka halaman detail produk.
- **Then:** Informasi produk, foto utama, tabel spesifikasi, tombol download brosur, dan tombol CTA penawaran tetap tampil dan berfungsi normal.

### AC-23: Kepatuhan Aksesibilitas WCAG 2.1 AA
- **Given:** Halaman diuji menggunakan audit aksesibilitas.
- **When:** Seluruh elemen dievaluasi.
- **Then:** Terdapat tepat satu tag `<h1>`, kontras warna teks memenuhi rasio 4.5:1, kontrol interaktif memiliki outline fokus, dan gambar memiliki alt text deskriptif.

### AC-24: Penanganan Respon HTTP 404 untuk Slug Tidak Ditemukan
- **Given:** Pengunjung membuka URL `/id/products/produk-fiktif-tidak-ada`.
- **When:** Request diproses oleh controller.
- **Then:** Server mengembalikan respon `HTTP 404 Not Found` yang ramah pengguna.

### AC-25: Ketersediaan Titik Pemicu Analitik (Analytics Integration Points)
- **Given:** Pengunjung membuka detail produk, mengklik download brosur, atau mengklik CTA penawaran.
- **When:** Tindakan dilakukan.
- **Then:** Sistem menyediakan event trigger points untuk `view_product`, `download_brochure`, dan `start_quotation` dengan ketersediaan metadata kandidat non-PII.

### AC-26: Ketersediaan Data Kandidat Metadata untuk SEO (SEO Dependency)
- **Given:** Halaman detail produk siap dirender.
- **When:** Modul SEO membaca data produk.
- **Then:** Nama produk terlokalisasi, deskripsi ringkas (`summary`), slug dwibahasa, nama brand, nama kategori, dan foto utama tersedia dari arsitektur model Product tanpa memerlukan field SEO spekulatif baru.

---

## 17. Implementation Dependencies & Conceptual Order

### 17.1. Dependensi Arsitektur
- **Laravel 12 & Blade Engine:** Routing dan rendering template publik pada PHP 8.3.x.
- **Tailwind CSS 4.x & Alpine.js:** Untuk styling responsif, thumbnail gallery switcher, dan mobile sticky action bar.
- **Eloquent Models Terkait:** `Product`, `Category`, `Brand`, `ProductImage` (dari SPEC-01).
- **Filesystem & Storage Symlink:** Local public disk (`storage/app/public/`) untuk foto produk dan file PDF brosur.
- **Localization Middleware:** `SetLocaleMiddleware` (dari SPEC-05).
- **Quotation Flow:** Alur permintaan penawaran berbasis `product_id` (dari SPEC-04).

### 17.2. Urutan Konseptual Implementasi (Tanggung Jawab Perilaku)
1. Konfigurasi resolusi rute produk aktif menggunakan strict localized slug matching (`slug_id` / `slug_en`).
2. Implementasi query produk aktif beserta eager loading seluruh relasi yang dibutuhkan (`category`, `brand`, `productImages`).
3. Penyusunan struktur view Blade halaman detail produk.
4. Integrasi foto utama dengan atribut prioritas LCP.
5. Integrasi galeri thumbnail desktop dan galeri geser sentuh seluler berbasis Alpine.js.
6. Integrasi breadcrumb hierarkis dan identitas produk (H1, Brand, Kategori, Ringkasan, Deskripsi).
7. Integrasi rendering tabel spesifikasi teknis dinamis dari kolom JSON.
8. Integrasi endpoint dan tombol unduhan aman brosur PDF.
9. Integrasi tombol CTA in-page dan Mobile Sticky Bottom Action Bar (< 768px).
10. Integrasi Language Switcher dwibahasa dan defensive fallback anomali slug.
11. Pengujian menyeluruh responsivitas mobile, aksesibilitas keyboard, dan integritas data.

---

## 18. Out of Scope

Hal-hal berikut secara tegas dinyatakan **DI LUAR CAKUPAN (OUT OF SCOPE)** untuk Public Product Detail Experience V1:
- Antarmuka upload/edit produk, gambar, atau brosur di CMS (telah dicakup di SPEC-01).
- Fitur Ulasan / Review bintang dan komentar produk oleh pengunjung.
- Fitur Perbandingan produk multi-kolom (*Product Comparison Matrix*).
- Fitur Wishlist / Favorit produk dan keranjang belanja e-commerce.
- Sistem checkout transaksi dan pembayaran online (*Payment Gateway*).
- Manajemen stok dan pelacakan inventaris real-time (*Inventory Tracking*).
- Algoritma rekomendasi produk otomatis berbasis AI / Machine Learning.
- Akun pengguna / login pengunjung publik (*Customer Portal*).
- Definisi final arsitektur analitik (dicakup di SPEC-09) dan SEO final (dicakup di SPEC-10).

---

## 19. Architecture Consistency Notes

- **Architecture Consistency Audit: PASS**
- **Konsistensi Skema Produk (SPEC-01):** Kolom `summary_id`, `summary_en`, `name_id`, `name_en`, `slug_id`, `slug_en`, `description_id`, `description_en`, `specifications` (JSON), `primary_image_path`, dan `brochure_path` terverifikasi 100% konsisten dengan skema `products` SPEC-01 Section 7.1.
- **Konsistensi dengan SPEC-04 (Quotation & Inquiry):** CTA penawaran murni membawa `product_id` numerik ke halaman kontak; server-side database menjadi satu-satunya sumber kebenaran.
- **Konsistensi dengan SPEC-05 (Localization):** Strict localized slug routing (`slug_id` pada ID, `slug_en` pada EN) diterapkan secara ketat tanpa kueri `OR` ambigu.
- **Konsistensi dengan SPEC-06 & SPEC-07:** Navigasi katalog, breadcrumb kategori (`?category={category_slug}`), layout global header/footer, dan integrasi mobile breakpoint konsisten 100%.
- **Konsistensi dengan Shared Hosting Target:** Berjalan ringan secara server-side tanpa runtime process Node.js di server produksi, tanpa Redis, Elasticsearch, atau background worker daemons.

---

*(Feature Specification Public Product Detail Experience telah selesai direvisi dan berstatus Ready for Final Review sebelum dikunci untuk implementasi.)*
