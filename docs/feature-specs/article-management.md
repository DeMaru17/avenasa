# Feature Specification: Article Management (Revised)

**Feature ID:** `SPEC-11-ARTICLE-MANAGEMENT`  
**Feature Name:** Article Management & Corporate News/Knowledge Base  
**Proyek:** Website Company Profile & Katalog Produk  
**Klien:** PT Abhipraya Nawasena Sejahtera (ANS)  
**Dokumen Referensi & Sumber Kebenaran:**
1. [docs/architecture/technology-baseline.md](file:///c:/laragon/www/avenasa/docs/architecture/technology-baseline.md)
2. [docs/architecture/system-design.md](file:///c:/laragon/www/avenasa/docs/architecture/system-design.md)
3. [docs/architecture/architecture-lock.md](file:///c:/laragon/www/avenasa/docs/architecture/architecture-lock.md)
4. [docs/manual/ANS-TECHNICAL-DOCUMENTATION.md](file:///c:/laragon/www/avenasa/docs/manual/ANS-TECHNICAL-DOCUMENTATION.md)
5. [docs/manual/ANS-CMS-USER-MANUAL.md](file:///c:/laragon/www/avenasa/docs/manual/ANS-CMS-USER-MANUAL.md)  
**Status Dokumen:** Revised Specification — Ready for Implementation (Review Cycle 2)

---

## 1. Feature Overview

Fitur **Article Management** adalah modul Content Management System (CMS) dan publikasi konten editorial korporat dwibahasa (Bahasa Indonesia & English) yang dirancang khusus untuk mempublikasikan artikel edukatif, berita perusahaan (*News*), agenda/kegiatan (*Event*), pembaruan produk (*Product Update*), dan pembaruan korporat (*Company Update*).

Fitur ini secara strategis menjembatani konten edukasi ilmiah dan korporat menuju penemuan produk (*Content-to-Product Discovery*) melalui relasi **Many-to-Many** antara Artikel dan Produk ANS (*Related Products*).

---

## 2. Scope & Principles

### 2.1 Kebijakan Pembuatan & Pengelolaan Slug (Automatic Slug — No Manual Input)
1. **Admin Tidak Mengisi Slug:** Admin TIDAK PERLU dan TIDAK BOLEH diminta mengisi field `slug_id` atau `slug_en`. Field slug disembunyikan sepenuhnya dari form Filament CMS.
2. **Generasi Otomatis Terpisah:**
   - `slug_id` dibuat otomatis dari `title_id` menggunakan `Str::slug()`.
   - `slug_en` dibuat otomatis dari `title_en` menggunakan `Str::slug()`.
   - Perubahan pada `title_id` hanya memengaruhi `slug_id`, dan perubahan pada `title_en` hanya memengaruhi `slug_en`.
3. **Collision Handling Deterministic:** Jika slug sudah digunakan oleh artikel lain, sistem menambahkan suffix bertahap (`-2`, `-3`, dst.).
4. **Kebijakan Stabilitas URL (URL Stability & SEO Protection):**
   - Saat pembuatan artikel (*create*): `slug_id` dan `slug_en` digenerate otomatis dari masing-masing judul.
   - Saat pengeditan artikel (*edit*): Untuk menjaga stabilitas tautan eksternal, backlink, indexing search engine, dan bookmark pengunjung, slug yang sudah ada **TIDAK** diubah otomatis hanya karena judul diedit setelah dipublikasikan. Regenerasi otomatis hanya terjadi jika nilai slug pada database masih kosong/null.

### 2.2 Isolasi Ketat Slug per Locale (Strict Locale Slug Isolation)
1. **Pencarian Tanpa Fallback Lintas Bahasa:**
   - URL `/id/articles/{slug_id}` HANYA mencari artikel berdasarkan kolom `slug_id`.
   - URL `/en/articles/{slug_en}` HANYA mencari artikel berdasarkan kolom `slug_en`.
2. **Anti-Pattern Terlarang:**
   - DILARANG melakukan fallback lintas bahasa (contoh: mencoba mencari `slug_id` pada rute `/en/`, atau sebaliknya).
   - Jika pengunjung mengakses `/en/articles/{slug_id}` atau `/id/articles/{slug_en}`, sistem WAJIB mengembalikan respon **HTTP 404 Not Found**.
3. **Language Switcher Berbasis Record:**
   - Language switcher menavigasi ke slug bahasa pasangan dari record artikel yang sama:
     - Dari English (`/en/articles/{slug_en}`) beralih ke ID: mengambil `slug_id` dari record yang sama -> `/id/articles/{slug_id}`.
     - Dari Indonesia (`/id/articles/{slug_id}`) beralih ke EN: mengambil `slug_en` dari record yang sama -> `/en/articles/{slug_en}`.
     - Jika slug pasangan kosong/null karena anomali data, diarahkan secara aman ke listing artikel locale tujuan (`/{targetLocale}/articles`).

### 2.3 Relasi Many-to-Many & Pengurutan Pivot (Related Products Pivot Ordering)
1. **Integritas Kolom `sort_order`:**
   - Kolom `sort_order` pada tabel pivot `article_product` murni milik relasi artikel bersangkutan (*scoped per article*).
   - Pengurutan Related Products **HANYA** mengubah `article_product.sort_order` dan **TIDAK PERNAH** memodifikasi `products.sort_order`.
2. **Filament 5 Implementation:**
   - Dikelola melalui `RelatedProductsRelationManager` pada halaman Edit Article.
   - Implementasi reorder secara eksplisit memperbarui tabel pivot `article_product` berdasarkan `article_id` dan `product_id`.
   - Pada form `ArticleForm`, pemilihan produk awal dapat dilakukan via `Select` yang tersinkronisasi tanpa merusak nilai urutan pivot yang sudah diatur di Relation Manager.

### 2.4 Aturan Visibilitas Publik & Default Publishing
1. **Default Publishing State:**
   - Kolom `published_at` bersifat `NULLABLE` dengan default `NULL`.
   - Form CMS `ArticleForm` tidak mengisi otomatis `published_at` dengan `now()`. Admin harus secara sadar memilih tanggal dan jam rilis.
2. **Kriteria Akses Publik (`Article::published()`):**
   Artikel HANYA dapat diakses publik (pada listing, detail, beranda, sitemap) jika memenuhi ketiga syarat berikut:
   ```sql
   is_active = TRUE AND published_at IS NOT NULL AND published_at <= NOW()
   ```
3. **Pemisahan Scope Eloquent:**
   - `Article::published()`: Memeriksa `is_active = true` AND `published_at <= now()`.
   - `Article::active()`: Hanya memeriksa `is_active = true`.

### 2.5 Zero-Symlink Storage Architecture & Keamanan RichEditor
1. **Path Storage Hostinger Production:**
   - `FILESYSTEM_PUBLIC_ROOT=/home/u290252867/domains/avenasa.co.id/public_html/storage`
   - Foto Sampul: `public_html/storage/articles/covers/` (URL: `/storage/articles/covers/...`)
   - Gambar Inline RichEditor: `public_html/storage/articles/editor/` (URL: `/storage/articles/editor/...`)
2. **Keamanan Attachment RichEditor:**
   - Membatasi tipe berkas hanya gambar aman: `image/jpeg`, `image/png`, `image/webp`, `image/gif`.
   - Batas ukuran berkas: maksimal 5 MB per gambar inline.
   - Menggunakan native Filament 5 RichEditor (Tiptap) yang menghasilkan nama berkas acak unik (*cryptographically secure randomized hashes*) untuk mencegah penimpaan (*collision*) dan directory traversal.

---

## 3. Data Model & Database Architecture

### 3.1 Skema Tabel `articles`

| Kolom | Tipe Data | Nullable | Default | Keterangan & Index |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Tidak | Auto Increment | Primary Key |
| `title_id` | `VARCHAR(255)` | Tidak | - | Judul Artikel Bahasa Indonesia |
| `title_en` | `VARCHAR(255)` | Tidak | - | Judul Artikel English |
| `slug_id` | `VARCHAR(255)` | Tidak | - | URL Slug Bahasa Indonesia (`UNIQUE INDEX`) |
| `slug_en` | `VARCHAR(255)` | Tidak | - | URL Slug English (`UNIQUE INDEX`) |
| `excerpt_id` | `TEXT` | Ya | `NULL` | Ringkasan/Cuplikan Bahasa Indonesia |
| `excerpt_en` | `TEXT` | Ya | `NULL` | Ringkasan/Cuplikan English |
| `content_id` | `LONGTEXT` | Ya | `NULL` | Konten HTML RichEditor Bahasa Indonesia |
| `content_en` | `LONGTEXT` | Ya | `NULL` | Konten HTML RichEditor English |
| `cover_image_path` | `VARCHAR(255)` | Ya | `NULL` | Path Berkas Foto Sampul (`articles/covers/...`) |
| `type` | `VARCHAR(50)` | Tidak | `'News'` | Tipe Konten (`INDEX`): News, Event, Product Update, Company Update |
| `published_at` | `DATETIME` | Ya | `NULL` | Tanggal & Waktu Rilis Konten (`INDEX`, default NULL) |
| `is_featured` | `BOOLEAN` | Tidak | `FALSE` | Penanda Artikel Unggulan (`INDEX`) |
| `is_active` | `BOOLEAN` | Tidak | `TRUE` | Status Publikasi Master (`INDEX`) |
| `sort_order` | `INT` | Tidak | `0` | Urutan Tampilan Opsional (`INDEX`) |
| `created_at` | `TIMESTAMP` | Ya | `NULL` | Waktu Pembuatan |
| `updated_at` | `TIMESTAMP` | Ya | `NULL` | Waktu Pembaruan Terakhir |

### 3.2 Skema Tabel Pivot `article_product`

| Kolom | Tipe Data | Nullable | Default | Keterangan & Index |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Tidak | Auto Increment | Primary Key |
| `article_id` | `BIGINT UNSIGNED` | Tidak | - | FK ke `articles.id` (`ON DELETE CASCADE`) |
| `product_id` | `BIGINT UNSIGNED` | Tidak | - | FK ke `products.id` (`ON DELETE CASCADE`) |
| `sort_order` | `INT` | Tidak | `0` | Urutan Prioritas Tampilan Produk Terkait |
| `created_at` | `TIMESTAMP` | Ya | `NULL` | Waktu Pembuatan |
| `updated_at` | `TIMESTAMP` | Ya | `NULL` | Waktu Pembaruan Terakhir |

**Constraints & Indexes pada `article_product`:**
- `UNIQUE KEY uq_article_product (article_id, product_id)` (Mencegah duplikasi produk pada artikel yang sama).
- `KEY idx_article_sort (article_id, sort_order)` (Optimalisasi pengurutan produk per artikel).
- `KEY idx_product (product_id)` (Optimalisasi reverse lookup).

---

## 4. Filament 5 CMS Architecture

### 4.1 Form Schemas (`ArticleForm`)
- **Section 1 — Basic Information:**
  - `title_id` (TextInput, required, maxLength 255)
  - `title_en` (TextInput, required, maxLength 255)
  - *(CATATAN: Field slug_id dan slug_en TIDAK DITAMPILKAN di form)*
  - `excerpt_id` (Textarea, rows 3)
  - `excerpt_en` (Textarea, rows 3)
  - `type` (Select, options: News, Event, Product Update, Company Update, default 'News', required)
  - `cover_image_path` (FileUpload, disk 'public', directory 'articles/covers', image, maxSize 5120)
- **Section 2 — Content:**
  - `content_id` (RichEditor, label: Konten ID, disk: public, directory: articles/editor, maxSize: 5120, acceptedTypes: jpeg, png, webp, gif)
  - `content_en` (RichEditor, label: Konten EN, disk: public, directory: articles/editor, maxSize: 5120, acceptedTypes: jpeg, png, webp, gif)
- **Section 3 — Related Products (Initial Selection on Create):**
  - `Select::make('relatedProducts')`
    - Digunakan untuk memilih produk awal saat pembuatan artikel baru.
    - Pada halaman Edit, admin diarahkan ke tabel Relation Manager di bawah form untuk mengatur pengurutan presisi secara visual.
- **Section 4 — Publishing Settings:**
  - `published_at` (DateTimePicker, nullable, default NULL)
  - `is_active` (Toggle, default true)
  - `is_featured` (Toggle, default false)
  - `sort_order` (TextInput, numeric, default 0)

### 4.2 Related Products Relation Manager (`RelatedProductsRelationManager`)
- Mengelola relasi Many-to-Many pada halaman Edit Article.
- Tabel menampilkan foto produk, nama produk, kategori, dan nomor urut pivot.
- Aksi reorder drag-and-drop mengeksekusi update eksplisit ke tabel pivot `article_product.sort_order` terikat pada `article_id` aktif.
- Aksi `AttachAction` dengan pencarian searchable.
- Aksi `DetachAction` untuk mencabut relasi produk.

---

## 5. Public Web Experience & Routing

### 5.1 Rute Publik & Isolasi Locale
- `GET /{locale}/articles` -> `ArticleController@index` (Named: `articles.index`)
- `GET /{locale}/articles/{slug}` -> `ArticleController@show` (Named: `articles.show`)
- Validasi parameter `{locale}`: `id|en`.
- Resolusi slug:
  - Locale `'id'`: Query `Article::published()->where('slug_id', $slug)->firstOrFail()`.
  - Locale `'en'`: Query `Article::published()->where('slug_en', $slug)->firstOrFail()`.

### 5.2 Halaman Indeks (`/articles`)
- Query: `Article::published()->orderByDesc('published_at')->orderByDesc('id')->paginate(12)`.
- Kartu artikel menampilkan foto sampul, badge tipe, tanggal terformat, judul, excerpt, dan tombol baca.
- Pagination bawaan Tailwind. Empty state jika belum ada konten.

### 5.3 Halaman Detail (`/articles/{slug}`)
- Konten artikel RichEditor disajikan dengan tipografi responsif (`prose`). Gambar inline dibatasi `max-w-full h-auto` agar tidak menyebabkan overflow pada layar mobile.
- Seksi Related Products merender kartu produk `<x-products.card :product="$product" />` hanya untuk produk yang `is_active = TRUE`.

### 5.4 Beranda (*Homepage Latest Articles*)
- Menampilkan 3 artikel terpublikasi terbaru: `Article::published()->orderByDesc('published_at')->orderByDesc('id')->take(3)->get()`.
- Disembunyikan sepenuhnya jika hasil query kosong.

---

## 6. SEO, Sitemap, & Language Switcher

- Canonical URL dan Hreflang menggunakan slug masing-masing bahasa tanpa fallback silang:
  - ID: `https://avenasa.co.id/id/articles/{slug_id}`
  - EN: `https://avenasa.co.id/en/articles/{slug_en}`
  - x-default: Mengarah ke versi EN jika ada, atau ID.
- Skema JSON-LD: `Article` dan `BreadcrumbList`.
- Dynamic XML Sitemap: Menambahkan artikel yang lolos kriteria `Article::published()`.

---

## 7. Production Database Deployment (Zero SSH)

- File SQL: `deployment/migrations/create_articles_feature.sql`
- Menggunakan `CREATE TABLE IF NOT EXISTS` untuk `articles` dan `article_product`.
- Pendaftaran histori migrasi pada tabel `migrations` menggunakan query dinamis:
  ```sql
  INSERT INTO `migrations` (`migration`, `batch`)
  SELECT '2026_09_07_100001_create_articles_table', COALESCE(MAX(batch), 0) + 1 FROM `migrations`
  WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_09_07_100001_create_articles_table');

  INSERT INTO `migrations` (`migration`, `batch`)
  SELECT '2026_09_07_100002_create_article_product_table', COALESCE(MAX(batch), 0) + 1 FROM `migrations`
  WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_09_07_100002_create_article_product_table');
  ```
