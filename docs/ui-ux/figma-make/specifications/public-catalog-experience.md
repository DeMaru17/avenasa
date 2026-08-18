# Feature Specification: Public Catalog Experience

**Feature ID:** `SPEC-06-PUBLIC-CATALOG`  
**Feature Name:** Public Catalog Experience  
**Proyek:** Website Company Profile & Katalog Produk  
**Klien:** PT Abhipraya Nawasena Sejahtera (ANS)  
**Dokumen Referensi & Sumber Kebenaran:**
1. [docs/architecture/technology-baseline.md](file:///c:/laragon/www/avenasa/docs/architecture/technology-baseline.md)
2. [docs/architecture/system-design.md](file:///c:/laragon/www/avenasa/docs/architecture/system-design.md)
3. [docs/urs/ans-website-urs.md](file:///c:/laragon/www/avenasa/docs/urs/ans-website-urs.md)
4. [docs/feature-specs/catalog-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/catalog-management.md)
5. [docs/feature-specs/localization.md](file:///c:/laragon/www/avenasa/docs/feature-specs/localization.md)
6. [docs/references/Company Profile ANS 2026 Rev.02.pdf](file:///c:/laragon/www/avenasa/docs/references/Company%20Profile%20ANS%202026%20Rev.02.pdf)  
**Status Dokumen:** Draft Feature Specification — Under Review

---

## 1. Feature Overview

Fitur **Public Catalog Experience** mendefinisikan seluruh pengalaman interaksi pengunjung publik (*B2B leads & researchers*) saat menjelajahi katalog produk PT Abhipraya Nawasena Sejahtera pada website publik (`/{locale}/products`).

Fokus utama fitur ini adalah memberikan pengalaman pencarian dan penemuan produk yang cepat, intuitif, ramah seluler (*mobile-first*), ramah SEO (*SEO-friendly*), dan ringan bagi infrastruktur *shared hosting* melalui:
- **Server-Side Rendering (Laravel Blade):** Render halaman murni server-side tanpa arsitektur SPA yang berat.
- **Dual-Filtering (Kategori & Brand) dengan Logika AND:** Pemfilteran presisi menggunakan parameter HTTP GET standar.
- **Penyimpanan State Berbasis URL:** URL adalah *Single Source of Truth* untuk filter aktif, sehingga tautan dapat dibagikan (*shareable*), di-bookmark, dan tetap bertahan saat halaman di-refresh.
- **Desain Responsif Adaptif:** Sidebar filter permanen pada desktop dan *Slide-over Drawer* interaktif berbasis Alpine.js pada mobile.
- **Paginasi Efisien:** Paginasi server-side yang mempertahankan query parameter filter aktif.
- **Integrasi Penuh Dwibahasa & GA4:** Pemetaan slug dwibahasa presisi dan pelacakan event analitik `product_filter` & `view_product` tanpa PII.

> [!NOTE]
> Spesifikasi ini murni mengatur **pengalaman pengunjung publik (Public-Facing Experience)**. Pengelolaan data master katalog di CMS telah didefinisikan secara lengkap pada **SPEC-01 Catalog Management**.

---

## 2. Public User Flow

```
[Pengunjung Mengakses: /{locale}/products]
                   ↓
[Melihat Daftar Produk Lengkap (Paginated)]
                   ↓
[Memilih Kategori dan/atau Brand di Filter]
  ├── Desktop: Klik Filter di Sidebar
  └── Mobile: Buka Drawer Filter -> Pilih Opsi -> Terapkan
                   ↓
[URL Diperbarui: /{locale}/products?category=...&brand=...]
                   ↓
[Sistem Memuat Produk yang Cocok (Logika AND)]
  ├── Ada Hasil: Menampilkan Grid Produk Terfilter
  └── Hasil = 0: Menampilkan Graceful Empty State + Tombol Reset
                   ↓
[Pengunjung Mengklik Kartu Produk]
                   ↓
[Halaman Detail Produk Terbuka: /{locale}/products/{slug}]
```

---

## 3. URL Architecture & Filter Parameters

Mengikuti ketentuan **SPEC-01** dan **SPEC-05**, URL katalog publik tersusun secara kanonikal:

### 3.1. Struktur URL
- **Katalog Utama (Tanpa Filter):**
  - Bahasa Indonesia: `/id/products`
  - Bahasa Inggris: `/en/products`
- **Katalog Terfilter (Single Filter):**
  - Kategori saja: `/id/products?category=mikrobiologi` atau `/en/products?category=microbiology`
  - Brand saja: `/id/products?brand=merck` atau `/en/products?brand=merck`
- **Katalog Terfilter Ganda (Combined Dual-Filter):**
  - Bahasa Indonesia: `/id/products?category=mikrobiologi&brand=merck`
  - Bahasa Inggris: `/en/products?category=microbiology&brand=merck`

### 3.2. Ketentuan Format Identifier Filter
- **Parameter `category`:** Menggunakan **Localized Slug** (`slug_id` pada locale `id`, dan `slug_en` pada locale `en`).
- **Parameter `brand`:** Menggunakan **Universal Slug** (`slug` universal, misal: `merck`, `neogen`, `era-biology`).
- **State URL:** Dilarang menyimpan state filter utama di dalam PHP Session; URL parameter adalah satu-satunya sumber kebenaran (*Source of Truth*).

---

## 4. Filter Logic & Behavior

Pemfilteran katalog pada V1 bersifat **Single-Select per Dimensi** dengan **Logika Persilangan AND**:

| Kondisi Filter | Kueri Database yang Diterapkan | Perilaku Tampilan Sistem |
|---|---|---|
| **Tanpa Filter** (`/id/products`) | Seluruh produk aktif (`Product::where('is_active', true)`) | Menampilkan seluruh produk aktif, diurutkan berdasarkan `sort_order ASC`, lalu `created_at DESC`. |
| **Kategori Saja** (`?category=mikrobiologi`) | `whereHas('category', fn($q) => $q->where('slug_id', 'mikrobiologi'))` | Menampilkan hanya produk di bawah kategori Mikrobiologi. |
| **Brand Saja** (`?brand=merck`) | `whereHas('brand', fn($q) => $q->where('slug', 'merck'))` | Menampilkan hanya produk dari principal Merck. |
| **Kategori + Brand (AND Logic)** (`?category=mikrobiologi&brand=merck`) | `whereHas('category', ...)->whereHas('brand', ...)` | Menampilkan hanya produk yang memenuhi **KEDUA** syarat (Kategori Mikrobiologi **DAN** Brand Merck). |
| **Filter Tidak Menghasilkan Produk** | Query menghasilkan 0 record | Menampilkan *Graceful Zero-Result Empty State* dengan tombol "Reset Semua Filter". |
| **Slug Filter Tidak Valid / Typo** | Query slug kategori/brand tidak ditemukan | Sistem memperlakukan kueri sebagai zero-result tanpa melempar error sistem atau SQL exception. |

---

## 5. Desktop User Experience (Sidebar + Product Grid)

Tata letak desktop (`>= 1024px` / `lg:`) menggunakan pembagian 2 kolom:

```
+-------------------+---------------------------------------------------+
| SIDEBAR FILTER    | KATALOG PRODUK                                    |
|                   | Total: 24 Produk Ditemukan                        |
| [Kategori Produk] | [ Active Filter Chips: Mikrobiologi (x), Merck (x)]|
| - Semua Kategori  +---------------------------------------------------+
| • Mikrobiologi (v)| [Card 1]     [Card 2]     [Card 3]     [Card 4]   |
| - Biologi Molekuler|                                                  |
|                   | [Card 5]     [Card 6]     [Card 7]     [Card 8]   |
| [Brand / Principal|                                                  |
| - Semua Brand     | [Card 9]     [Card 10]    [Card 11]    [Card 12]  |
| • Merck (v)       +---------------------------------------------------+
| - Neogen          | [ << Previous ]    [ 1 ]  [ 2 ]    [ Next >> ]    |
|                   +---------------------------------------------------+
| [Reset Filter]    |                                                   |
+-------------------+---------------------------------------------------+
```

### 5.1. Komponen Sidebar Desktop
- **Section Kategori:** Daftar seluruh `Category::where('is_active', true)` dengan teks localized (`name_id` / `name_en`). Item aktif diberikan *highlight* visual (warna latar/teks kontras dan ikon centang).
- **Section Brand:** Daftar seluruh `Brand::where('is_active', true)`. Item aktif diberikan penanda visual.
- **Tombol Reset:** Tautan cepat *"Reset Semua Filter"* yang mengarah## 6. Mobile & Tablet User Experience (< 1024px: Drawer + Responsive Grid)

Pengalaman seluler dan tablet (`< 1024px`) dirancang khusus dengan pendekatan **Mobile-First**:

1. **Tombol Pemicu Filter (Sticky / Prominent Filter Bar):**
   - Tombol *"Filter Produk"* yang mudah dijangkau di bagian atas daftar produk dengan badge counter jika filter aktif (misal: *"Filter (2)"*).
2. **Slide-over Drawer (Alpine.js):**
   - Mengklik tombol filter membuka panel laci dari sisi samping/bawah layar dengan animasi transisi halus.
   - Background di belakang drawer dilapisi backdrop gelap semi-transparan (`bg-black/50`).
   - Drawer memuat opsi Kategori, Brand, dan tombol aksi: *"Terapkan Filter"* (Apply) dan *"Reset"*.
3. **Penerapan Filter & Aksesibilitas Drawer:**
   - Saat pengguna menekan *"Terapkan Filter"*, drawer tertutup dan browser melakukan navigasi HTTP GET memuat URL terfilter baru.
   - Memilih opsi di dalam drawer **tidak langsung memicu kueri atau event analitik** sebelum tombol *"Terapkan Filter"* ditekan.
   - Drawer dapat ditutup dengan: menekan tombol silang (X), menekan area backdrop luar, atau menekan tombol `ESC` pada keyboard.
   - Body scroll otomatis dinonaktifkan (`overflow-hidden`) saat drawer terbuka.

---

## 7. Product Card & Responsive Grid Layout

Kartu produk (*Product Card*) dirancang informatif dan konsisten:

### 7.1. Struktur & Konten Kartu
1. **Foto Utama Produk (`primary_image_path`):**
   - Menggunakan tag `<img>` dengan rasio aspek konsisten (1:1 atau 4:3), CSS `object-cover`, serta atribut `loading="lazy"`.
   - **Fallback Gambar:** Jika gambar utama rusak atau bernilai null karena anomali data, kartu menyajikan visual placeholder korporat berlogo ANS tanpa broken image.
2. **Badge Kategori & Brand:**
   - Menampilkan nama kategori dan brand resmi produk dalam badge visual yang rapi.
3. **Nama Produk (`name_id` / `name_en`):**
   - Judul produk ditampilkan sesuai bahasa aktif, dibatasi maksimal 2 baris (*line-clamp-2*) agar tinggi kartu tetap simetris.
4. **Ringkasan Singkat (`summary_id` / `summary_en`):**
   - Menampilkan cuplikan ringkas produk jika tersedia (*line-clamp-2*).
5. **Tombol CTA Aksi:**
   - Tombol *"Lihat Detail"* / *"View Details"* yang mengarah ke `/{locale}/products/{localized_slug}`. Seluruh area kartu memiliki efek hover interaktif.

### 7.2. Ketentuan Pasti Breakpoint Responsivitas Grid & Filter
Sistem menetapkan perilaku responsif tata letak secara deterministik:
- **Smartphone (`< 640px`):**
  - Grid Produk: **Tepat 1 Kolom** (`grid-cols-1`).
  - Mekanisme Filter: **Slide-over Drawer**.
- **Tablet (`640px - 1023px`):**
  - Grid Produk: **Tepat 2 Kolom** (`grid-cols-2`).
  - Mekanisme Filter: **Slide-over Drawer**.
- **Desktop (`>= 1024px`):**
  - Grid Produk: **3 s.d. 4 Kolom** (`lg:grid-cols-3 xl:grid-cols-4`) di samping sidebar.
  - Mekanisme Filter: **Permanent Desktop Sidebar**.

---

## 8. Pagination Specification

- **Jumlah Item:** **12 produk per halaman** (`paginate(12)`).
- **Preservasi Parameter URL:** Seluruh tautan paginasi wajib mempertahankan filter aktif dan locale menggunakan method bawaan Laravel:
  `$products->withQueryString()->links()`
- **Contoh URL Paginasi:**
  - Halaman 2 katalog utama: `/id/products?page=2`
  - Halaman 2 kategori mikrobiologi: `/id/products?category=mikrobiologi&page=2`
  - Halaman 2 filter ganda: `/id/products?category=mikrobiologi&brand=merck&page=2`
- **Aksesibilitas Paginasi:** Menggunakan komponen semantik nav dengan label aria *"Halaman Sebelumnya"* dan *"Halaman Berikutnya"*.

---

## 9. Empty State & Active Filter Chips

### 9.1. Active Filter Chips Display
- Saat ada filter yang aktif, sistem menampilkan deretan *Filter Chips* di atas grid produk:
  - Chip Kategori: `[ Kategori: Mikrobiologi (x) ]` -> Mengklik silang menghapus filter kategori dan mempertahankan filter brand.
  - Chip Brand: `[ Brand: Merck (x) ]` -> Mengklik silang menghapus filter brand.
  - Tautan Teks: *"Hapus Semua"* -> Mengarahkan ke `/{locale}/products`.

### 9.2. Graceful Zero-Result Empty State
- Jika kombinasi filter tidak menghasilkan produk:
  - Sistem menyajikan kartu kosong berdesain profesional dengan ilustrasi netral/ikon pencarian.
  - Judul: *"Tidak Ada Produk yang Sesuai"* / *"No Products Found"*.
  - Pesan Penjelas: *"Kami tidak menemukan produk yang cocok dengan kombinasi filter yang Anda pilih. Coba sesuaikan atau hapus filter Anda."*
  - Tombol CTA: *"Reset Semua Filter"* (mengarahkan kembali ke katalog lengkap).
  - Halaman **TIDAK MENAMPILKAN** error 404 karena halaman katalog berstatus valid.

---

## 10. Language Switcher Integration on Catalog

Mengikuti ketentuan **SPEC-05**:
- Saat pengunjung beralih bahasa di halaman katalog, sistem memetakan filter aktif ke bahasa tujuan secara presisi:
  - Dari ID: `/id/products?category=mikrobiologi&brand=merck`
  - Beralih ke EN -> Menuju: `/en/products?category=microbiology&brand=merck`
  - Dari EN: `/en/products?category=microbiology`
  - Beralih ke ID -> Menuju: `/id/products?category=mikrobiologi`
- Filter kategori menggunakan **localized slug** padanannya, sedangkan brand menggunakan **universal slug**.

---

## 11. SEO & Indexing Strategy (Locked Decisions)

Sistem menetapkan keputusan arsitektur SEO katalog secara definitif:

### 11.1. Halaman Katalog Utama & Paginasi Tanpa Filter
1. **Katalog Utama (`/id/products` & `/en/products`):**
   - Robots: `index, follow`.
   - Canonical URL: Self-canonical (`https://avenasa.co.id/id/products`).
   - Tag `hreflang`: Pasangan lengkap `hreflang="id"`, `hreflang="en"`, dan `hreflang="x-default"`.
2. **Paginasi Katalog Tanpa Filter (`/id/products?page={n}`):**
   - Robots: `index, follow`.
   - Canonical URL: **Self-canonical** mengarah ke URL halaman paginasi aktifnya masing-masing (misal: `https://avenasa.co.id/id/products?page=2`). Dilarang memaksakan canonical ke halaman 1 agar crawler dapat mengindeks tautan produk di halaman dalam secara wajar.

### 11.2. Halaman Katalog Terfilter (Filtered Navigation URLs)
1. **Seluruh URL Terfilter (`?category=...`, `?brand=...`, `?category=...&brand=...`):**
   - Robots: **`noindex, follow`**.
   - Canonical URL: Self-canonical atau Canonical URL default halaman tersebut.
   - **Rasional Keputusan:** URL terfilter murni berfungsi sebagai navigasi discovery pengunjung. Kebijakan `noindex, follow` mencegah pemborosan *crawl budget* dan penalti konten duplikat (*low-value faceted navigation index*) akibat ribuan permutasi filter kategori × brand, sembari tetap mengizinkan bot mesin pencari menelusuri (*follow*) link kartu produk individual yang bernilai SEO tinggi.
2. **Paginasi Katalog Terfilter (`/id/products?category=mikrobiologi&page=2`):**
   - Robots: **`noindex, follow`**.

---

## 12. Analytics Integration Points (GA4 Events)

Pelacakan analitik katalog diatur secara presisi murni *client-side* tanpa PII:

### 12.1. `product_filter` Event
- **Pemicu:** Dipicu **tepat satu kali** setelah halaman katalog terfilter berhasil selesai dirender di browser berdasarkan query parameter aktif.
- **Larangan Pemicu Prematur:** Membuka drawer filter, mengklik tombol filter, atau mencentang opsi filter sebelum menekan *"Terapkan"* **DILARANG** memicu event GA4.
- **Payload Parameter:**
  - `category`: Slug/nama kategori aktif (atau `'all'`).
  - `brand`: Slug/nama brand aktif (atau `'all'`).
  - `locale`: Bahasa aktif (`'id'` atau `'en'`).

### 12.2. `view_product` Event
- **Pemicu:** Dipicu **tepat satu kali** ketika halaman Product Detail (`/{locale}/products/{slug}`) berhasil selesai dirender di browser pengunjung (bukan saat melakukan hover atau melihat kartu pada grid katalog).
- **Payload Parameter:**
  - `product_id`: ID produk (angka).
  - `product_name`: Nama produk.
  - `category`: Nama kategori produk.
  - `brand`: Nama brand produk.
  - `locale`: Bahasa aktif (`'id'` atau `'en'`).

### 12.3. Kepatuhan Privasi Mutlak (No PII)
- **DILARANG KERAS** mengirimkan: nama visitor, email, nomor telepon, IP address, teks pesan quotation, atau identifier pribadi pengguna lainnya ke Google Analytics 4.

---

## 13. Accessibility Standards (WCAG 2.1 AA)

- **Hierarki Heading:** Menggunakan satu tag `<h1>` utama (*"Katalog Produk"* / *"Product Catalog"*), dan `<h2>` / `<h3>` untuk kartu produk.
- **Kontras Warna & Indikator Non-Warna:** Item filter yang aktif ditandai tidak hanya dengan perubahan warna, melainkan juga dengan ikon centang dan status `aria-current="true"`.
- **Navigasi Keyboard Penuh:** Seluruh filter, tombol drawer, kartu produk, dan tautan paginasi dapat diakses menggunakan tombol `Tab`, `Enter`, dan `Space`.
- **Atribut Alt Bergambar:** Tag `<img>` kartu produk menyertakan atribut `alt` deskriptif yang diambil dari nama produk.

---

## 14. Performance & Shared Hosting Query Optimization

Untuk menjamin kecepatan maksimal pada shared hosting (cPanel):
1. **Eager Loading Relasi Mutlak:** Kueri katalog wajib memuat relasi sekaligus guna mencegah masalah *N+1 Query*:
   `Product::with(['category', 'brand'])`
2. **Seleksi Kolom Efisien:** Kueri hanya mengambil kolom yang dibutuhkan untuk tampilan kartu katalog (mengecualikan kolom teks besar seperti `description_id`, `description_en`, dan dokumen brosur).
3. **Pemanfaatan Database Indexing:** Kueri pemfilteran bersandar pada index foreign key (`category_id`, `brand_id`), status boolean (`is_active`), dan index unique slug (`slug_id`, `slug_en`) yang telah didefinisikan pada SPEC-01.
4. **Tanpa Dependensi Berat:** Tidak memerlukan Redis, Elasticsearch, Meilisearch, atau daemon antrean (*zero daemon dependency*).

---

## 15. Out of Scope

Hal-hal berikut secara tegas dinyatakan **DI LUAR CAKUPAN (OUT OF SCOPE)** untuk fitur Public Catalog Experience V1:
- Pencarian teks bebas kompleks (*Full-text search engine / Elasticsearch / Algolia*).
- Fitur komparasi produk multi-kolom (*Product Comparison Matrix*).
- Fitur Wishlist / Favorit produk.
- Keranjang belanja (*Shopping Cart*) dan sistem transaksi online.
- Review / Ulasan bintang dan komentar produk.
- Pengurutan dinamis kustom (Sorting by price, popularity, etc. - katalog V1 menggunakan default sorting `sort_order ASC`).
- Rekomendasi produk berbasis AI / Machine Learning.
- Infinite scrolling (katalog V1 menggunakan paginasi nomor standar).

---

## 16. Error & Edge Cases Handling Matrix

| Skenario Edge Case | Perilaku Sistem yang Ditetapkan |
|---|---|
| **Database gagal memuat daftar produk** | Server menangani error secara aman dan menampilkan halaman error ramah pengguna tanpa membocorkan database exception. |
| **Slug kategori pada query tidak ditemukan di database** | Kueri menghasilkan 0 produk dan menyajikan Graceful Empty State dengan tombol Reset. |
| **Slug brand pada query tidak ditemukan di database** | Kueri menghasilkan 0 produk dan menyajikan Graceful Empty State dengan tombol Reset. |
| **Kombinasi kategori dan brand tidak memiliki produk aktif** | Menyajikan Graceful Empty State informatif (*"Tidak Ada Produk yang Sesuai"*). |
| **Produk tidak memiliki foto utama (`primary_image_path = null`)** | Kartu produk merender placeholder visual resmi korporat ANS tanpa broken image. |
| **Produk dinonaktifkan (`is_active = false`) oleh admin** | Produk otomatis hilang dari katalog dan kueri direct slug mengembalikan HTTP 404. |
| **Nomor halaman paginasi tidak valid (misal: `?page=999`)** | Menampilkan halaman kosong dengan pagination controller yang valid tanpa fatal error. |
| **Pengguna menghapus salah satu filter chip** | Halaman me-reload URL dengan mempertahankan filter yang tersisa secara mulus. |

---

## 17. Acceptance Criteria (Format Given / When / Then)

### AC-01: Membuka Katalog Utama Tanpa Filter
- **Given:** Pengunjung membuka URL `/id/products`.
- **When:** Halaman berhasil dimuat.
- **Then:** Sistem menyajikan 12 produk aktif pertama secara server-rendered, diurutkan berdasarkan `sort_order ASC`, dengan tag `<meta name="robots" content="index, follow">` dan self-canonical.

### AC-02: Pemfilteran Berdasarkan Kategori Saja
- **Given:** Pengunjung berada di `/id/products`.
- **When:** Pengunjung memilih kategori "Mikrobiologi".
- **Then:** URL berubah menjadi `/id/products?category=mikrobiologi`, grid hanya menampilkan produk dalam kategori tersebut, dan opsi Mikrobiologi disorot aktif di sidebar.

### AC-03: Pemfilteran Berdasarkan Brand Saja
- **Given:** Pengunjung berada di `/id/products`.
- **When:** Pengunjung memilih brand "Merck".
- **Then:** URL berubah menjadi `/id/products?brand=merck`, grid hanya menampilkan produk dari brand Merck, dan opsi Merck disorot aktif.

### AC-04: Pemfilteran Ganda Kategori dan Brand (Logika AND)
- **Given:** Pengunjung memilih kategori "Mikrobiologi" dan brand "Merck".
- **When:** Filter diterapkan.
- **Then:** URL menjadi `/id/products?category=mikrobiologi&brand=merck`, dan sistem hanya menampilkan produk yang berstatus Kategori Mikrobiologi **DAN** Brand Merck.

### AC-05: Reset Semua Filter
- **Given:** Pengunjung berada di halaman terfilter `/id/products?category=mikrobiologi&brand=merck`.
- **When:** Pengunjung mengklik tombol *"Reset Semua Filter"*.
- **Then:** Browser diarahkan kembali ke `/id/products` tanpa query parameter, dan seluruh produk aktif kembali ditampilkan.

### AC-06: Preservasi State Filter Saat Refresh Halaman
- **Given:** Pengunjung berada di `/id/products?category=mikrobiologi`.
- **When:** Pengunjung menekan tombol refresh browser (F5).
- **Then:** Halaman memuat ulang dengan tetap berada pada URL `/id/products?category=mikrobiologi` dan filter Mikrobiologi tetap aktif.

### AC-07: Preservasi Filter pada Paginasi Halaman
- **Given:** Terdapat 25 produk pada kategori "Mikrobiologi".
- **When:** Pengunjung mengklik tombol halaman 2 paginasi.
- **Then:** Browser diarahkan ke `/id/products?category=mikrobiologi&page=2` dengan filter kategori yang tetap terjaga utuh.

### AC-08: Tampilan Graceful Zero-Result Empty State
- **Given:** Kombinasi filter kategori dan brand tidak memiliki produk sama sekali di database.
- **When:** Halaman dimuat.
- **Then:** Sistem menampilkan pesan *"Tidak Ada Produk yang Sesuai"*, tidak menampilkan broken grid, dan menyediakan tombol *"Reset Semua Filter"*.

### AC-09: Transisi ke Halaman Detail Produk
- **Given:** Pengunjung melihat kartu produk "Media Kultur Agar" (`slug_id = 'media-kultur-agar'`).
- **When:** Pengunjung mengklik kartu produk tersebut.
- **Then:** Browser membuka halaman detail server-rendered di `/id/products/media-kultur-agar` dengan HTTP 200.

### AC-10: Language Switcher Memetakan Filter yang Setara
- **Given:** Pengunjung berada di `/id/products?category=mikrobiologi&brand=merck`.
- **When:** Pengunjung mengklik tombol pengalih bahasa "EN".
- **Then:** Browser diarahkan ke `/en/products?category=microbiology&brand=merck` dengan filter yang tetap aktif dalam versi bahasa Inggris.

### AC-11: Responsivitas Mobile (< 640px) — 1 Kolom & Drawer
- **Given:** Pengunjung membuka katalog pada perangkat smartphone (< 640px).
- **When:** Halaman dimuat.
- **Then:** Grid produk merender tepat 1 kolom (`grid-cols-1`) dan filter disajikan melalui Slide-over Drawer.

### AC-12: Responsivitas Tablet (640px - 1023px) — 2 Kolom & Drawer
- **Given:** Pengunjung membuka katalog pada perangkat tablet (640px - 1023px).
- **When:** Halaman dimuat.
- **Then:** Grid produk merender tepat 2 kolom (`grid-cols-2`) dan filter disajikan melalui Slide-over Drawer.

### AC-13: Responsivitas Desktop (>= 1024px) — 3 s.d. 4 Kolom & Sidebar
- **Given:** Pengunjung membuka katalog pada layar desktop (>= 1024px).
- **When:** Halaman dimuat.
- **Then:** Grid produk merender 3 s.d. 4 kolom di samping permanent desktop sidebar filter.

### AC-14: SEO Tag Robot pada Filtered Catalog (`noindex, follow`)
- **Given:** Pengunjung atau crawler membuka URL `/id/products?category=mikrobiologi`.
- **When:** Halaman dirender.
- **Then:** Tag meta robots menyajikan `<meta name="robots" content="noindex, follow">`.

### AC-15: SEO Self-Canonical pada Paginasi Tanpa Filter
- **Given:** Pengunjung membuka halaman 2 katalog utama `/id/products?page=2`.
- **When:** Halaman dirender.
- **Then:** Tag `<link rel="canonical" href="https://avenasa.co.id/id/products?page=2">` tersaji dengan tag robots `index, follow`.

### AC-16: SEO Tag Robot pada Paginasi Terfilter (`noindex, follow`)
- **Given:** Pengunjung membuka halaman 2 katalog terfilter `/id/products?category=mikrobiologi&page=2`.
- **When:** Halaman dirender.
- **Then:** Tag meta robots menyajikan `<meta name="robots" content="noindex, follow">`.

### AC-17: Pemicu GA4 `product_filter` Pasca Render Halaman Terfilter
- **Given:** Pengunjung berada di URL `/id/products?category=mikrobiologi&brand=merck`.
- **When:** Halaman terfilter selesai dimuat di browser.
- **Then:** Event GA4 `product_filter` terpicu tepat 1 kali dengan parameter `category: 'mikrobiologi'`, `brand: 'merck'`, dan `locale: 'id'` tanpa PII.

### AC-18: Isolasi Interaksi Drawer Tanpa Memicu GA4 `product_filter` Prematur
- **Given:** Pengunjung membuka filter drawer dan mencentang opsi kategori.
- **When:** Pengunjung belum menekan tombol *"Terapkan Filter"*.
- **Then:** Event GA4 `product_filter` **TIDAK** dipicu.

### AC-19: Pemicu GA4 `view_product` Pasca Render Detail Produk
- **Given:** Pengunjung membuka halaman `/id/products/media-agar`.
- **When:** Halaman detail produk selesai dimuat.
- **Then:** Event GA4 `view_product` terpicu tepat 1 kali membawa `product_id`, `product_name`, `category`, `brand`, dan `locale`.

### AC-20: Pencegahan N+1 Query & Efisiensi Database
- **Given:** Halaman katalog memuat 12 produk dengan relasi kategori dan brand.
- **When:** Controller mengeksekusi kueri database dengan eager loading (`with(['category', 'brand'])`).
- **Then:** Jumlah kueri database tetap konstan (maksimal 3 kueri) dan tidak bertambah seiring bertambahnya jumlah produk per halaman.

---

## 18. Implementation Dependencies & Order

### 18.1. Dependensi Arsitektur
- **Laravel 12 Framework:** Routing & Controller engine pada PHP 8.3.x.
- **Database & Eloquent Models:** Tabel `products`, `categories`, `brands` dari SPEC-01.
- **Localization Engine:** `SetLocaleMiddleware` dan localized routing dari SPEC-05.
- **Tailwind CSS 4.x & Alpine.js:** Untuk styling responsif dan interaktivitas mobile drawer.
- **Google Tag Base Setup:** Untuk penampung event GA4 `product_filter` dan `view_product`.

### 18.2. Urutan Konseptual Implementasi (CMS-First Compliance)
1. Konfigurasi rute katalog publik `/{locale}/products` dan `/{locale}/products/{slug}`.
2. Implementasi query controller katalog dengan filter validation & eager loading (`with(['category', 'brand'])`).
3. Implementasi server-side pagination dengan `withQueryString()`.
4. Pembuatan komponen Blade kartu produk (*Product Card*) dengan fallback gambar.
5. Pembuatan komponen Blade sidebar filter desktop.
6. Pembuatan komponen Blade slide-over drawer filter mobile berbasis Alpine.js.
7. Pembuatan komponen Empty State & Active Filter Chips.
8. Integrasi meta tag SEO (index/self-canonical untuk katalog utama & paginasinya; `noindex, follow` untuk seluruh URL terfilter) dan GA4 dataLayer events.
9. Pengujian menyeluruh (*Automated & Manual QA*).

---

## 19. Architecture Consistency Notes

- **Architecture Consistency Audit: PASS**
- **Konsistensi dengan SPEC-01:** Penggunaan localized category slug dan universal brand slug selaras 100% dengan skema data SPEC-01.
- **Konsistensi dengan SPEC-05:** Struktur URL prefix (`/id/...` dan `/en/...`) dan integrasi language switcher terhubung secara deterministik.
- **Konsistensi SEO & Indexing:** Keputusan `noindex, follow` untuk URL terfilter query dan self-canonical untuk paginasi tanpa filter menjamin perlindungan maksimal terhadap crawl budget dan duplicate content indexing pada Google Search.
- **Konsistensi Shared Hosting:** Seluruh kueri beroperasi murni secara server-side tanpa background daemon worker atau memory cache eksternal.

---

*(Feature Specification Public Catalog Experience telah selesai direvisi dan menunggu final review sebelum dikunci untuk implementasi.)*
