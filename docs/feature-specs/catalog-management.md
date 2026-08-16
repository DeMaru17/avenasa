# Feature Specification: Catalog Management

**Feature ID:** `SPEC-01-CATALOG`  
**Feature Name:** Catalog Management  
**Proyek:** Website Company Profile & Katalog Produk  
**Klien:** PT Abhipraya Nawasena Sejahtera (ANS)  
**Dokumen Referensi & Sumber Kebenaran:**
1. [docs/architecture/technology-baseline.md](file:///c:/laragon/www/avenasa/docs/architecture/technology-baseline.md)
2. [docs/architecture/system-design.md](file:///c:/laragon/www/avenasa/docs/architecture/system-design.md)
3. [docs/urs/ans-website-urs.md](file:///c:/laragon/www/avenasa/docs/urs/ans-website-urs.md)
4. [docs/references/Company Profile ANS 2026 Rev.02.pdf](file:///c:/laragon/www/avenasa/docs/references/Company%20Profile%20ANS%202026%20Rev.02.pdf)  
**Status Dokumen:** Draft Feature Specification — Under Review

---

## 1. Feature Overview

Fitur **Catalog Management** adalah pusat pengelolaan data produk, kategori, principal resmi/brand, galeri foto, dokumen brosur teknis, dan spesifikasi produk dinamis PT Abhipraya Nawasena Sejahtera.

Sistem membagi tanggung jawab secara tegas antara area administrasi (CMS) dan area publik:
- **Tanggung Jawab CMS (Filament 5):** Menyediakan antarmuka terstruktur bagi staf admin untuk membuat (*create*), membaca (*read*), memperbarui (*update*), menghapus (*delete* dengan proteksi integritas), mengaktifkan/menonaktifkan (*activation toggle*), mengurutkan (*sort order*), mengelola spesifikasi dinamis (JSON Repeater), serta mengunggah media (foto utama, galeri foto, dan brosur PDF).
- **Tanggung Jawab Public Website (Blade):** Menyajikan data katalog secara server-rendered, menangani pemfilteran persilangan dua dimensi (*dual-filter* Kategori dan Brand berbasis parameter HTTP GET dengan logika AND), menyajikan halaman detail produk berbasis localized slug, menyediakan unduhan brosur PDF kondisional, serta menyediakan pemicu CTA permintaan penawaran harga (*quotation*).

---

## 2. Scope

Scope fitur ini mencakup 4 entitas database dan 2 sub-komponen terintegrasi:
1. **Category Management:** Pengelolaan kategori master 1 tingkat (*flat*).
2. **Brand / Principal Management:** Pengelolaan manufaktur/principal resmi produk (termasuk penandaan New Principal Era Biology).
3. **Product Management:** Pengelolaan item produk inti (data umum, foto utama, relasi kategori, dan brand).
4. **Product Gallery Management:** Pengelolaan multi-foto pendukung produk melalui tabel relasional terpisah (`ProductImage`).
5. **Product Brochure Management:** Pengelolaan dokumen teknis/datasheet PDF tunggal per produk.
6. **Product Specifications Management:** Pengelolaan spesifikasi teknis dinamis terstruktur (Key-Value bilingual via JSON).

---

## 3. Actors & Permissions

- **Staf Administrator ANS:** Memiliki akses penuh pada Filament 5 Admin Panel untuk seluruh operasi CRUD katalog, manajemen visibilitas, pengurutan, dan pengelolaan media.
- **Pengunjung Publik (B2B Leads):** Memiliki akses *read-only* pada website publik untuk menelusuri katalog, memfilter produk, melihat spesifikasi detail, mengunduh brosur PDF, dan memulai permintaan penawaran.

---

## 4. Business Rules

1. **Struktur Relasi 1:N (Bukan Hierarki Kompleks):**
   - Setiap `Product` wajib terhubung ke tepat satu `Category` utama (`belongsTo`).
   - Setiap `Product` wajib terhubung ke tepat satu `Brand` utama (`belongsTo`).
   - `Category` dan `Brand` bersifat independen (tidak memiliki relasi langsung satu sama lain) dan masing-masing memiliki relasi `hasMany` ke `Product`.
   - Kategori bersifat *flat* (1 level, tanpa parent-child hierarchy pada V1).
2. **Aturan Visibilitas Publik (Activation Rule):**
   - Produk hanya ditampilkan di public website jika: `Product.is_active = true` **DAN** `Category.is_active = true` **DAN** `Brand.is_active = true`.
   - Jika Category atau Brand dinonaktifkan (`is_active = false`), seluruh produk di bawahnya otomatis tersembunyi dari publik tanpa mengubah status `is_active` milik masing-masing produk.
3. **Aturan Filter Katalog (AND Logic):**
   - Pemfilteran katalog bersifat *single-select* per dimensi (1 kategori dan/atau 1 brand).
   - Menggunakan parameter HTTP GET: `?category={category_slug}&brand={brand_slug}`.
   - Jika kedua filter dipilih bersamaan, produk yang tampil harus memenuhi KEDUA kriteria tersebut (logika AND).
4. **Integritas Penghapusan (Deletion Constraints):**
   - Kategori atau Brand yang masih memiliki relasi ke satu atau lebih Produk **DILARANG** dihapus (*Restricted Deletion*). Admin wajib memindahkan atau menghapus produk terkait terlebih dahulu.
   - Produk yang telah memiliki relasi ke riwayat `Quotation` **DILARANG** di-hard delete agar tidak merusak audit trail prospek penawaran harga. Sebagai gantinya, admin dapat menonaktifkan produk (`is_active = false`).
5. **Dokumen Brosur Tunggal:**
   - Setiap produk hanya dapat memiliki maksimal 1 file brosur PDF aktif. Jika admin mengunggah brosur baru, file lama digantikan.
   - Tombol unduh brosur pada halaman detail produk bersifat kondisional (hanya muncul jika `brochure_path` tidak kosong).

---

## 5. Category Specification

- **Tabel:** `categories`
- **Model:** `App\Models\Category`

### 5.1. Skema Kolom & Validasi
| Nama Kolom | Tipe Data | Constraint / Rule | Deskripsi & Perilaku |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Identifikator unik kategori. |
| `name_id` | `varchar(255)` | Required, Max: 255 | Nama kategori dalam Bahasa Indonesia. |
| `name_en` | `varchar(255)` | Required, Max: 255 | Nama kategori dalam Bahasa Inggris. |
| `slug_id` | `varchar(255)` | Required, Unique, Max: 255 | Slug URL unik untuk rute Bahasa Indonesia. |
| `slug_en` | `varchar(255)` | Required, Unique, Max: 255 | Slug URL unik untuk rute Bahasa Inggris. |
| `sort_order` | `integer` | Default: 0, Min: 0 | Urutan penataan pada sidebar filter dan menu. |
| `is_active` | `boolean` | Default: `true` | Status publikasi kategori. |
| `created_at` | `timestamp` | Nullable | Waktu pembuatan record. |
| `updated_at` | `timestamp` | Nullable | Waktu pembaruan record. |

### 5.2. Relasi Eloquent
- `products()`: `hasMany(Product::class, 'category_id')`

---

## 6. Brand / Principal Specification

- **Tabel:** `brands`
- **Model:** `App\Models\Brand`

### 6.1. Skema Kolom & Validasi
| Nama Kolom | Tipe Data | Constraint / Rule | Deskripsi & Perilaku |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Identifikator unik brand/principal. |
| `name` | `varchar(255)` | Required, Max: 255 | Nama resmi brand/principal (misal: Merck, Neogen, Era Biology). |
| `slug` | `varchar(255)` | Required, Unique, Max: 255 | Slug URL unik universal untuk filter katalog. |
| `logo_path` | `varchar(255)` | Required, Image (Max 2MB) | Path logo resmi principal di `storage/app/public/brands/`. |
| `website_url` | `varchar(255)` | Nullable, URL, Max: 255 | Tautan ke website resmi principal global. |
| `description_id` | `text` | Nullable | Deskripsi singkat principal dalam Bahasa Indonesia. |
| `description_en` | `text` | Nullable | Deskripsi singkat principal dalam Bahasa Inggris. |
| `is_new_principal` | `boolean` | Default: `false` | Flag penanda kemitraan principal baru (khusus Era Biology). |
| `sort_order` | `integer` | Default: 0, Min: 0 | Urutan penataan pada showcase dan sidebar filter. |
| `is_active` | `boolean` | Default: `true` | Status aktif publikasi brand. |
| `created_at` | `timestamp` | Nullable | Waktu pembuatan record. |
| `updated_at` | `timestamp` | Nullable | Waktu pembaruan record. |

### 6.2. Relasi Eloquent
- `products()`: `hasMany(Product::class, 'brand_id')`

---

## 7. Product Specification

- **Tabel:** `products`
- **Model:** `App\Models\Product`

### 7.1. Skema Kolom & Validasi
| Nama Kolom | Tipe Data | Constraint / Rule | Deskripsi & Perilaku |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Identifikator unik produk. |
| `category_id` | `bigint unsigned` | Required, Foreign Key (`categories.id`) | Kategori produk terkait. |
| `brand_id` | `bigint unsigned` | Required, Foreign Key (`brands.id`) | Brand/Principal pembuat produk. |
| `name_id` | `varchar(255)` | Required, Max: 255 | Nama produk dalam Bahasa Indonesia. |
| `name_en` | `varchar(255)` | Required, Max: 255 | Nama produk dalam Bahasa Inggris. |
| `slug_id` | `varchar(255)` | Required, Unique, Max: 255 | Slug unik untuk URL rute `/id/products/{slug_id}`. |
| `slug_en` | `varchar(255)` | Required, Unique, Max: 255 | Slug unik untuk URL rute `/en/products/{slug_en}`. |
| `summary_id` | `text` | Nullable | Ringkasan singkat produk dalam Bahasa Indonesia. |
| `summary_en` | `text` | Nullable | Ringkasan singkat produk dalam Bahasa Inggris. |
| `description_id` | `longtext` | Nullable | Deskripsi lengkap produk (Rich Text/HTML) dalam ID. |
| `description_en` | `longtext` | Nullable | Deskripsi lengkap produk (Rich Text/HTML) dalam EN. |
| `specifications` | `json` | Nullable, Array of Key-Value | Atribut spesifikasi teknis dinamis terstruktur bilingual. |
| `primary_image_path` | `varchar(255)` | Required, Image (Max 2MB) | Foto utama produk di `storage/app/public/products/primary/`. |
| `brochure_path` | `varchar(255)` | Nullable, PDF (Max 10MB) | File brosur PDF di `storage/app/public/brochures/`. |
| `is_featured` | `boolean` | Default: `false` | Penanda produk unggulan untuk homepage showcase. |
| `is_active` | `boolean` | Default: `true` | Status publikasi produk. |
| `sort_order` | `integer` | Default: 0, Min: 0 | Urutan penataan produk dalam katalog. |
| `created_at` | `timestamp` | Nullable | Waktu pembuatan record. |
| `updated_at` | `timestamp` | Nullable | Waktu pembaruan record. |

### 7.2. Relasi Eloquent
- `category()`: `belongsTo(Category::class, 'category_id')`
- `brand()`: `belongsTo(Brand::class, 'brand_id')`
- `images()`: `hasMany(ProductImage::class, 'product_id')->orderBy('sort_order', 'asc')`
- `quotations()`: `hasMany(Quotation::class, 'product_id')`

---

## 8. Product Gallery Specification (`ProductImage`)

- **Tabel:** `product_images`
- **Model:** `App\Models\ProductImage`

### 8.1. Skema Kolom & Validasi
| Nama Kolom | Tipe Data | Constraint / Rule | Deskripsi & Perilaku |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Identifikator unik gambar galeri. |
| `product_id` | `bigint unsigned` | Required, Foreign Key (`products.id`), Cascade Delete | Produk pemilik gambar galeri. |
| `image_path` | `varchar(255)` | Required, Image (Max 2MB) | Path gambar di `storage/app/public/products/gallery/`. |
| `caption_id` | `varchar(255)` | Nullable, Max: 255 | Keterangan gambar dalam Bahasa Indonesia. |
| `caption_en` | `varchar(255)` | Nullable, Max: 255 | Keterangan gambar dalam Bahasa Inggris. |
| `sort_order` | `integer` | Default: 0, Min: 0 | Urutan tampilan foto pada galeri detail produk. |
| `created_at` | `timestamp` | Nullable | Waktu pembuatan record. |
| `updated_at` | `timestamp` | Nullable | Waktu pembaruan record. |

### 8.2. Pengelolaan di CMS
- Dikelola secara mandiri melalui `ImagesRelationManager` yang terpasang pada halaman edit `ProductResource` di Filament 5.

---

## 9. Product Brochure Specification

- **Lokasi Penyimpanan:** `storage/app/public/brochures/`
- **Format File:** Hanya `application/pdf` (ekstensi `.pdf`).
- **Batas Ukuran:** Maksimal 10 MB per file.
- **Karakteristik:** Bersifat opsional (`nullable`).
- **Siklus Hidup Media:** Jika admin mengunggah berkas brosur baru atau menghapus brosur pada form produk di Filament, berkas fisik PDF lama di storage wajib dihapus secara otomatis untuk mencegah penumpukan berkas yatim (*orphaned files*) pada shared hosting.
- **Tampilan Publik:**
  - Jika `brochure_path` terisi: Tampilkan tombol *"Unduh Brosur (PDF)"* / *"Download Brochure (PDF)"* pada halaman detail produk.
  - Jika `brochure_path` null: Sembunyikan elemen tombol unduh brosur tanpa meninggalkan ruang kosong atau layout rusak.

---

## 10. Dynamic Specifications Specification (JSON Repeater)

### 10.1. Struktur Data JSON
Spesifikasi disimpan dalam format array JSON berisi objek key-value bilingual:

```json
[
  {
    "key_id": "Bentuk Media",
    "key_en": "Media Form",
    "value_id": "Bubuk Dehidrasi",
    "value_en": "Dehydrated Powder"
  },
  {
    "key_id": "Kemasan",
    "key_en": "Packaging",
    "value_id": "Botol 500 gram",
    "value_en": "500g Bottle"
  },
  {
    "key_id": "Suhu Penyimpanan",
    "key_en": "Storage Temperature",
    "value_id": "2 - 8 °C",
    "value_en": "2 - 8 °C"
  }
]
```

### 10.2. Aturan Validasi Form & Fallback
- Setiap baris repeater wajib mengisi `key_id`, `key_en`, `value_id`, dan `value_en`.
- Jika `specifications` kosong (*empty array* atau *null*), tab/bagian tabel spesifikasi pada halaman detail produk disembunyikan secara bersih.
- Pada tampilan publik, sistem membaca item sesuai bahasa aktif:
  - Bahasa Indonesia: Menampilkan `key_id` dan `value_id`.
  - Bahasa Inggris: Menampilkan `key_en` (fallback ke `key_id` jika kosong) dan `value_en` (fallback ke `value_id` jika kosong).

---

## 11. Filament 5 CMS Behavior

Seluruh pengelolaan katalog dikelompokkan di bawah grup navigasi **"Catalog Management"**:

### 11.1. `CategoryResource`
- **Navigasi:** Group: *"Catalog Management"*, Icon: `heroicon-o-tag`, Sort: 1.
- **Tabel:** Kolom `name_id`, `name_en`, `slug_id`, `products_count` (Badge counter), `sort_order`, `is_active` (Toggle Column).
- **Searchable:** `name_id`, `name_en`.
- **Sortable:** `sort_order`, `name_id`, `created_at`.
- **Filter Tabel:** Filter status aktif (`is_active`).
- **Form:**
  - Section *"Informasi Kategori"*: `name_id` (auto-generate `slug_id`), `name_en` (auto-generate `slug_en`).
  - Section *"Pengaturan"*: `sort_order`, `is_active`.
- **Aksi Hapus:** Proteksi dengan notifikasi error jika `products_count > 0`.

### 11.2. `BrandResource`
- **Navigasi:** Group: *"Catalog Management"*, Icon: `heroicon-o-building-office-2`, Sort: 2.
- **Tabel:** Kolom `logo_path` (Image Column), `name`, `website_url`, `is_new_principal` (Icon/Badge Column), `products_count`, `sort_order`, `is_active` (Toggle Column).
- **Searchable:** `name`.
- **Sortable:** `sort_order`, `name`, `created_at`.
- **Filter Tabel:** Filter `is_active`, filter `is_new_principal`.
- **Form:**
  - Section *"Identitas Brand"*: `name` (auto-generate `slug`), `logo_path` (FileUpload `image()`, max 2MB), `website_url` (URL input).
  - Section *"Deskripsi"*: `description_id`, `description_en`.
  - Section *"Status & Pengurutan"*: `is_new_principal` (Checkbox/Toggle), `sort_order`, `is_active`.
- **Aksi Hapus:** Proteksi dengan notifikasi error jika `products_count > 0`.

### 11.3. `ProductResource`
- **Navigasi:** Group: *"Catalog Management"*, Icon: `heroicon-o-cube`, Sort: 3.
- **Tabel:** Kolom `primary_image_path` (Image), `name_id`, `category.name_id`, `brand.name`, `is_featured` (Toggle), `is_active` (Toggle), `sort_order`.
- **Searchable:** `name_id`, `name_en`.
- **Sortable:** `sort_order`, `name_id`, `created_at`.
- **Filter Tabel:** Select Filter `category_id`, Select Filter `brand_id`, Toggle Filter `is_featured`, Toggle Filter `is_active`.
- **Form (Multi-Section / Tabs):**
  - Section *"Data Utama"*: `category_id` (Select searchable), `brand_id` (Select searchable), `name_id` (auto-generate `slug_id`), `name_en` (auto-generate `slug_en`), `summary_id`, `summary_en`.
  - Section *"Deskripsi Lengkap"*: `description_id` (RichEditor), `description_en` (RichEditor).
  - Section *"Media & Dokumen"*: `primary_image_path` (FileUpload image max 2MB), `brochure_path` (FileUpload PDF max 10MB).
  - Section *"Spesifikasi Teknis"*: `specifications` (Repeater component 4 kolom: `key_id`, `key_en`, `value_id`, `value_en`).
  - Section *"Status & Pengurutan"*: `is_featured` (Toggle), `is_active` (Toggle), `sort_order` (Numeric input).
- **Relation Manager:** `ImagesRelationManager` (CRUD galeri foto produk `product_images`).
- **Aksi Hapus:** Proteksi dengan notifikasi error jika produk memiliki relasi ke tabel `quotations`.

---

## 12. Validation Rules Matrix

| Operasi | Entitas | Aturan Validasi Server-Side (Laravel FormRequest / Filament Schema) |
|---|---|---|
| **Create / Update** | `Category` | `name_id`: `required\|string\|max:255`<br>`name_en`: `required\|string\|max:255`<br>`slug_id`: `required\|string\|max:255\|unique:categories,slug_id,{id}`<br>`slug_en`: `required\|string\|max:255\|unique:categories,slug_en,{id}`<br>`sort_order`: `required\|integer\|min:0`<br>`is_active`: `boolean` |
| **Create / Update** | `Brand` | `name`: `required\|string\|max:255`<br>`slug`: `required\|string\|max:255\|unique:brands,slug,{id}`<br>`logo_path`: `required\|image\|mimes:jpg,jpeg,png,webp\|max:2048`<br>`website_url`: `nullable\|url\|max:255`<br>`description_id`: `nullable\|string`<br>`description_en`: `nullable\|string`<br>`is_new_principal`: `boolean`<br>`sort_order`: `required\|integer\|min:0`<br>`is_active`: `boolean` |
| **Create / Update** | `Product` | `category_id`: `required\|exists:categories,id`<br>`brand_id`: `required\|exists:brands,id`<br>`name_id`: `required\|string\|max:255`<br>`name_en`: `required\|string\|max:255`<br>`slug_id`: `required\|string\|max:255\|unique:products,slug_id,{id}`<br>`slug_en`: `required\|string\|max:255\|unique:products,slug_en,{id}`<br>`summary_id`: `nullable\|string`<br>`summary_en`: `nullable\|string`<br>`description_id`: `nullable\|string`<br>`description_en`: `nullable\|string`<br>`primary_image_path`: `required\|image\|mimes:jpg,jpeg,png,webp\|max:2048`<br>`brochure_path`: `nullable\|file\|mimes:pdf\|max:10240`<br>`specifications`: `nullable\|array`<br>`specifications.*.key_id`: `required\|string`<br>`specifications.*.key_en`: `required\|string`<br>`specifications.*.value_id`: `required\|string`<br>`specifications.*.value_en`: `required\|string`<br>`is_featured`: `boolean`<br>`is_active`: `boolean`<br>`sort_order`: `required\|integer\|min:0` |
| **Create / Update** | `ProductImage` | `product_id`: `required\|exists:products,id`<br>`image_path`: `required\|image\|mimes:jpg,jpeg,png,webp\|max:2048`<br>`caption_id`: `nullable\|string\|max:255`<br>`caption_en`: `nullable\|string\|max:255`<br>`sort_order`: `required\|integer\|min:0` |

---

## 13. CRUD & Deletion Behavior (Integritas Data)

1. **Delete Category:**
   - Sistem memeriksa apakah Category masih memiliki satu atau lebih Product yang mereferensikannya.
   - Jika Category memiliki relasi dengan Product: Sistem wajib menolak operasi penghapusan dan menampilkan notifikasi peringatan di Filament CMS: *"Kategori tidak dapat dihapus karena masih memiliki produk terkait. Silakan pindahkan atau hapus produk terlebih dahulu."*
   - Jika Category tidak memiliki relasi dengan Product: Sistem mengizinkan penghapusan record Category.
2. **Delete Brand:**
   - Sistem memeriksa apakah Brand masih memiliki satu atau lebih Product yang mereferensikannya.
   - Jika Brand memiliki relasi dengan Product: Sistem wajib menolak operasi penghapusan dan menampilkan notifikasi peringatan di Filament CMS: *"Brand tidak dapat dihapus karena masih memiliki produk terkait."*
   - Jika Brand tidak memiliki relasi dengan Product: Sistem menghapus file logo fisik dari disk storage lokal, lalu menghapus record Brand.
3. **Delete Product:**
   - Sistem memeriksa apakah Product memiliki riwayat relasi pada tabel permintaan penawaran (`Quotation`).
   - Jika Product memiliki riwayat Quotation: Sistem wajib menolak operasi penghapusan guna melindungi integritas audit trail, dan menampilkan notifikasi di Filament CMS: *"Produk ini memiliki riwayat permintaan penawaran (quotation) dan tidak dapat dihapus. Nonaktifkan status produk jika tidak lagi ditampilkan."*
   - Jika Product tidak memiliki riwayat Quotation:
     1. Sistem menghapus seluruh file gambar galeri fisik dari storage dan menghapus seluruh record `ProductImage` terkait.
     2. Sistem menghapus file foto utama (`primary_image_path`) fisik dari storage lokal.
     3. Sistem menghapus file brosur (`brochure_path`) fisik dari storage lokal jika ada.
     4. Sistem menghapus record `Product`.

---

## 14. Activation & Visibility Rules

1. **Aturan Visibilitas Publik (Public Catalog & Detail Scope):**
   - Halaman katalog publik dan halaman detail produk hanya boleh menampilkan Product yang berstatus aktif (`is_active = true`), serta memiliki Category yang berstatus aktif (`is_active = true`), dan Brand yang juga berstatus aktif (`is_active = true`).
   - Jika sebuah Category atau Brand dinonaktifkan oleh admin, seluruh produk di bawahnya otomatis tidak dapat diakses atau dilihat oleh publik, dan permintaan URL langsung menuju produk tersebut harus menghasilkan respon `HTTP 404 Not Found`.
2. **Aturan Produk Unggulan Beranda (Featured Scope):**
   - Bagian produk unggulan di halaman beranda hanya boleh menampilkan Product yang berstatus featured (`is_featured = true`) dan aktif (`is_active = true`), serta memiliki Category dan Brand yang berstatus aktif.
   - Daftar produk unggulan dibatasi maksimal 8 produk dan diurutkan berdasarkan nilai urutan terkecil (`sort_order` menaik).

---

## 15. Ordering & Sort Rules

- **Kategori pada Sidebar Filter:** Diurutkan berdasarkan `sort_order ASC`, lalu `name_id ASC`.
- **Brand pada Sidebar Filter / Showcase:** Diurutkan berdasarkan `sort_order ASC`, lalu `name ASC`.
- **Produk pada Listing Katalog:** Diurutkan berdasarkan `sort_order ASC`, lalu `created_at DESC`.
- **Galeri Foto pada Detail Produk:** Diurutkan berdasarkan `sort_order ASC`, lalu `id ASC`.

---

## 16. Slug Rules

1. **Auto-Generation:** Pada form Filament, input `name_id` otomatis mengisi `slug_id`, dan `name_en` otomatis mengisi `slug_en` menggunakan `Str::slug()`.
2. **Uniqueness:** Slug divalidasi unik di tingkat tabel database menggunakan unique index.
3. **Perutean Ketat Berdasarkan Bahasa:**
   - Prefix `/id/products/{slug}` membaca record: `Product::where('slug_id', $slug)->firstOrFail()`.
   - Prefix `/en/products/{slug}` membaca record: `Product::where('slug_en', $slug)->firstOrFail()`.
4. **Kategori Filter Query:** Parameter `?category={slug}` mencocokkan `slug_id` saat locale `id` atau `slug_en` saat locale `en`.

---

## 17. Bilingual Content Rules

1. **Atribut Terjemahan Eksplisit:**
   Model `Product` dan `Category` menyediakan accessor dinamis:
   - `$product->name`: Mengembalikan `name_en` jika locale aktif `en` (fallback ke `name_id` jika kosong), dan `name_id` jika locale `id`.
   - `$product->summary`: Mengembalikan `summary_en` jika locale `en` (fallback ke `summary_id`), dan `summary_id` jika locale `id`.
   - `$product->description`: Mengembalikan `description_en` jika locale `en` (fallback ke `description_id`), dan `description_id` jika locale `id`.
2. **Switching Bahasa Presisi:**
   Tombol pengalih bahasa (ID <-> EN) pada halaman detail produk menghasilkan URL localized yang valid menggunakan atribut slug padanannya:
   - Dari ID ke EN: `route('products.show', ['locale' => 'en', 'slug' => $product->slug_en])`.
   - Dari EN ke ID: `route('products.show', ['locale' => 'id', 'slug' => $product->slug_id])`.

---

## 18. Media Rules (Storage & Limits)

| Jenis Media | Direktori Penyimpanan | Ekstensi Valid | Max Size | Penanganan Lifecycle |
|---|---|---|---|---|
| **Logo Brand** | `storage/app/public/brands/` | `jpg, jpeg, png, webp` | 2 MB | File lama dihapus jika diganti/dihapus record. |
| **Foto Utama Produk** | `storage/app/public/products/primary/` | `jpg, jpeg, png, webp` | 2 MB | File lama dihapus jika diganti/dihapus produk. |
| **Galeri Foto Produk** | `storage/app/public/products/gallery/` | `jpg, jpeg, png, webp` | 2 MB | File fisik dihapus saat item galeri dihapus. |
| **Brosur PDF Produk** | `storage/app/public/brochures/` | `pdf` | 10 MB | File fisik dihapus jika brosur diganti/dikosongkan. |

*Seluruh URL media diakses secara publik via helper `asset('storage/' . $path)` melalui symlink `public/storage`.*

---

## 19. Public Website Consumption

### 19.1. Halaman Beranda (`/{locale}`)
- Mengonsumsi `Product::where('is_featured', true)` maksimal 8 item.
- Menampilkan logo `Brand::where('is_active', true)` dalam logo showcase slider.

### 19.2. Halaman Katalog Produk (`/{locale}/products`)
- **Panel Filter Sisi (Desktop Sidebar / Mobile Drawer):**
  - Menampilkan seluruh `Category::where('is_active', true)` dan `Brand::where('is_active', true)`.
  - Memberikan indikator sorotan (*active highlight*) pada item yang dipilih.
  - Tautan "Reset Semua Filter" mengarahkan ke `/{locale}/products` murni tanpa query string.
- **Logika Pemfilteran:**
  - Request `?category=microbiology` -> Filter produk dengan kategori tersebut.
  - Request `?brand=merck` -> Filter produk dengan brand tersebut.
  - Request `?category=microbiology&brand=merck` -> Filter persilangan AND.
  - Paginasi: 12 produk per halaman dengan query parameters yang dipertahankan (`withQueryString()`).

### 19.3. Halaman Detail Produk (`/{locale}/products/{slug}`)
- Menampilkan foto utama resolusi penuh + galeri thumbnail foto `product_images`.
- Menampilkan badge nama Kategori dan Brand.
- Menampilkan deskripsi lengkap (Rich Text).
- Menampilkan tabel spesifikasi dinamis (dari JSON Key-Value).
- Menampilkan tombol *"Unduh Brosur (PDF)"* (jika `brochure_path` ada).
- Menampilkan tombol CTA *"Minta Penawaran / Quotation"* (mengalihkan ke `/{locale}/contact?product_id={id}&product_name={name}`).
- Menampilkan tombol CTA *"Chat via WhatsApp"* dengan prefilled message: *"Halo PT ANS, saya tertarik meminta penawaran untuk produk [Nama Produk]..."*.

---

## 20. Analytics Integration Points (GA4 Events)

Event analitik dipicu secara *client-side* murni tanpa merekam data identitas pribadi (No PII):

1. **`view_product` Event:**
   - **Pemicu:** Pengunjung berhasil memuat halaman detail produk.
   - **Payload Parameter:** `product_id`, `product_name`, `category`, `brand`, `locale`.
2. **`product_filter` Event:**
   - **Pemicu:** Pengunjung menerapkan filter kategori dan/atau brand di katalog.
   - **Payload Parameter:** `category` (slug/nama), `brand` (slug/nama), `locale`.
3. **`download_brochure` Event:**
   - **Pemicu:** Pengunjung mengklik tombol unduh brosur PDF pada halaman detail produk.
   - **Payload Parameter:** `product_id`, `product_name`, `brand`, `file_type` (`pdf`), `locale`.

---

## 21. Error & Edge Cases Handling

| Skenario Edge Case | Perilaku Sistem yang Ditetapkan |
|---|---|
| **Produk tidak memiliki galeri foto** | Halaman detail produk hanya menampilkan foto utama (*primary image*) tanpa memunculkan thumbnail galeri kosong. |
| **Produk tidak memiliki brosur PDF** | Tombol unduh brosur disembunyikan secara kondisional; layout halaman menyesuaikan secara rapi. |
| **Produk berstatus Featured tetapi Inactive** | Produk **TIDAK AKAN** muncul di homepage maupun katalog (aturan `is_active = true` menjadi syarat mutlak). |
| **Kategori induk dinonaktifkan** | Seluruh produk di bawah kategori tersebut otomatis tersembunyi dari katalog publik dan query direct slug menghasilkan HTTP 404. |
| **Brand induk dinonaktifkan** | Seluruh produk di bawah brand tersebut otomatis tersembunyi dari katalog publik dan query direct slug menghasilkan HTTP 404. |
| **Upaya menghapus Kategori/Brand berelasi** | Ditolak oleh sistem di Filament CMS dengan pesan error validasi yang jelas. |
| **Upaya menghapus Produk berelasi Quotation** | Ditolak oleh sistem di Filament CMS; admin diarahkan untuk menonaktifkan status produk. |
| **Slug tidak ditemukan pada locale aktif** | Sistem mengembalikan respon resmi `HTTP 404 Not Found`. |
| **Spesifikasi JSON kosong / null** | Tab/bagian tabel spesifikasi teknis disembunyikan dari halaman detail produk. |
| **Gagal upload media (melebihi limit)** | Validasi Filament/Laravel menolak request dan memunculkan error inline *"Ukuran berkas melebihi batas maksimal (2MB untuk gambar, 10MB untuk PDF)"*. |

---

## 22. Acceptance Criteria (Format Given / When / Then)

### Kriteria 1: Pembuatan Kategori di CMS
- **Given:** Staf admin membuka form Create Category di Filament 5.
- **When:** Admin mengisi `name_id` ("Mikrobiologi"), `name_en` ("Microbiology"), `sort_order` (1), dan status aktif, lalu klik Save.
- **Then:** Data kategori tersimpan di database MySQL, slug `slug_id` ("mikrobiologi") dan `slug_en` ("microbiology") ter-generate unik, dan kategori muncul di daftar master.

### Kriteria 2: Proteksi Hapus Kategori yang Memiliki Produk
- **Given:** Kategori "Microbiology" memiliki 5 produk aktif di database.
- **When:** Admin mencoba menghapus Kategori "Microbiology" dari tabel CMS.
- **Then:** Sistem menolak penghapusan, data tidak terhapus, dan muncul notifikasi peringatan integritas data.

### Kriteria 3: Pembuatan Produk dengan Spesifikasi Dinamis & Brosur PDF
- **Given:** Admin membuat produk baru dan memilih Category "Microbiology" serta Brand "Merck".
- **When:** Admin mengunggah primary image (PNG 1.5MB), mengunggah brosur (PDF 4MB), menambahkan 3 baris spesifikasi Key-Value pada Repeater, dan klik Save.
- **Then:** Seluruh berkas tersimpan di storage lokal publik, spesifikasi tersimpan dalam format JSON valid di kolom `specifications`, dan produk siap dipublikasikan.

### Kriteria 4: Filter Ganda Katalog Produk (AND Logic via HTTP GET)
- **Given:** Terdapat produk A (Kategori: Microbiology, Brand: Merck) dan produk B (Kategori: Microbiology, Brand: Neogen).
- **When:** Pengunjung membuka URL `/id/products?category=mikrobiologi&brand=merck`.
- **Then:** Halaman hanya menampilkan produk A; produk B tidak ditampilkan; sidebar filter menyorot kategori Mikrobiologi dan brand Merck sebagai status aktif.

### Kriteria 5: Akses Detail Produk dengan Strict Localized Slug
- **Given:** Produk memiliki `slug_id` = "media-kultur-agar" dan `slug_en` = "culture-media-agar".
- **When:** Pengunjung mengakses `/id/products/media-kultur-agar`.
- **Then:** Sistem menyajikan halaman detail dalam Bahasa Indonesia dengan HTTP 200. Tombol pengalih bahasa EN mengarahkan ke `/en/products/culture-media-agar`.
- **When:** Pengunjung mencoba mengakses `/id/products/culture-media-agar` (slug bahasa Inggris pada prefix ID).
- **Then:** Sistem mengembalikan respon `HTTP 404 Not Found`.

### Kriteria 6: Unduh Brosur Kondisional
- **Given:** Produk X memiliki `brochure_path` terisi, sedangkan Produk Y memiliki `brochure_path` bernilai null.
- **When:** Pengunjung membuka halaman detail Produk X.
- **Then:** Tombol *"Unduh Brosur (PDF)"* ditampilkan dan mengunduh berkas PDF yang valid.
- **When:** Pengunjung membuka halaman detail Produk Y.
- **Then:** Tombol unduh brosur tidak muncul sama sekali di layar.

---

## 23. Implementation Dependencies

Fitur Catalog Management membutuhkan dependensi arsitektur berikut sebelum implementasi kode dimulai:
1. **Laravel 12 Framework Foundation:** Environment terkonfigurasi pada PHP 8.3.x.
2. **Database Migrations:** Tabel `categories`, `brands`, `products`, dan `product_images` dengan engine InnoDB MySQL 8.0+.
3. **Storage Symlink:** Eksekusi `php artisan storage:link` untuk direktori `public/storage`.
4. **Filament 5 Admin Panel:** Terpasang dengan autentikasi admin default.
5. **Tailwind CSS 4.x & Blade Layout:** Layout publik terpasang untuk merender kartu dan halaman detail produk.

---

## 24. Out of Scope

Hal-hal berikut secara tegas dinyatakan **DI LUAR CAKUPAN (OUT OF SCOPE)** untuk fitur Catalog Management V1:
- Keranjang belanja (*Shopping Cart*) dan sistem transaksi/pembayaran online (*Payment Gateway*).
- Manajemen stok dan inventaris gudang (*Inventory / Stock Management*).
- Integrasi sistem ERP eksternal atau sinkronisasi PIM pihak ketiga.
- Mesin pencari teks kompleks berbasis server (Elasticsearch/Meilisearch).
- Fitur komparasi produk multi-kolom (*Product Comparison Matrix*).
- Rekomendasi produk berbasis AI / Machine Learning.
- Kategori hierarkis bersarang (*Multi-level Parent-Child Categories*).
- Penyimpanan berkas di AWS S3 atau cloud media service lainnya.

---
 
 *(Feature Specification telah selesai disusun dan menunggu final review sebelum dikunci untuk implementasi.)*
