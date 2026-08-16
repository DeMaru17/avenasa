# Feature Specification: Company Content Management

**Feature ID:** `SPEC-02-COMPANY-CONTENT`  
**Feature Name:** Company Content Management  
**Proyek:** Website Company Profile & Katalog Produk  
**Klien:** PT Abhipraya Nawasena Sejahtera (ANS)  
**Dokumen Referensi & Sumber Kebenaran:**
1. [docs/architecture/technology-baseline.md](file:///c:/laragon/www/avenasa/docs/architecture/technology-baseline.md)
2. [docs/architecture/system-design.md](file:///c:/laragon/www/avenasa/docs/architecture/system-design.md)
3. [docs/urs/ans-website-urs.md](file:///c:/laragon/www/avenasa/docs/urs/ans-website-urs.md)
4. [docs/feature-specs/catalog-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/catalog-management.md)
5. [docs/references/Company Profile ANS 2026 Rev.02.pdf](file:///c:/laragon/www/avenasa/docs/references/Company%20Profile%20ANS%202026%20Rev.02.pdf)  
**Status Dokumen:** Draft Feature Specification — Under Review

---

## 1. Feature Overview

Fitur **Company Content Management** adalah modul CMS terstruktur untuk mengelola seluruh konten korporat resmi PT Abhipraya Nawasena Sejahtera yang disajikan pada website publik. Modul ini berfokus pada penyajian informasi profil, visi-misi, nilai-nilai inti perusahaan, jajaran pimpinan, serta logo mitra klien korporat.

Pemisahan tanggung jawab sistem:
- **Tanggung Jawab CMS (Filament 5):** Menyediakan antarmuka manajemen terstruktur untuk memperbarui data profil tunggal (*singleton*), mengelola CRUD 6 Core Values, mengelola profil dan foto jajaran founder/manajemen, mengelola kumpulan logo klien korporat, mengatur status aktif/visibilitas publik, serta mengatur urutan penataan (*sort order*).
- **Tanggung Jawab Public Website (Blade):** Mengonsumsi data profil dan konten korporat aktif dari database, menyajikan konten secara dwibahasa (ID/EN) sesuai *locale* aktif, menampilkan tata letak responsif (mobile-first), dan menyediakan *graceful empty state* jika sebagian data belum diisi.

---

## 2. Scope

Scope fitur ini mencakup 4 entitas database utama yang dikelola di Filament 5:
1. **`CompanyProfile` (Singleton Content):** Data profil inti tunggal perusahaan (Sejarah/About, Visi, Misi, Slogan/Tagline, Alamat Fisik Mensana Tower, Nomor Telepon, Nomor WhatsApp, Email Resmi, dan Embed Peta Google Maps).
2. **`CoreValue` (Master 6 Nilai Inti):** Pengelolaan 6 poin nilai inti resmi ANS (Integrity, Innovation, Collaboration, Sustainability, Professionalism, Well-Being) secara terstruktur.
3. **`Management` (Jajaran Founder & Pimpinan):** Pengelolaan profil biografi, jabatan dwibahasa, dan foto 3 Founder/Owner ANS (Erik Haryanto, Fernanda Ramadhan F, Hazin Yusuf).
4. **`Client` (Showcase Klien Korporat):** Pengelolaan nama dan logo klien/pelanggan korporat (Farmasi, FnB, Lab Uji, Universitas, RS).

> [!NOTE]
> **Pemisahan Entitas Partner vs Klien:**
> - Entitas **`Client`** murni digunakan untuk logo klien/pelanggan korporat (*customer showcase*).
> - Entitas **`Brand`** (Principal/Pabrikan Resmi) telah didefinisikan secara lengkap pada **SPEC-01 Catalog Management**. Halaman publik *Partners & Clients* mengonsumsi data `Brand` dari SPEC-01 dan data `Client` dari SPEC-02 tanpa menduplikasi data.

---

## 3. Actors & Permissions

- **Staf Administrator ANS:** Memiliki akses penuh pada Filament 5 Admin Panel untuk mengedit Profil Perusahaan, mengelola 6 Core Values, mengelola entri Management, serta mengunggah/mengatur logo Client.
- **Pengunjung Publik (B2B Leads & Stakeholders):** Memiliki akses *read-only* pada halaman publik (Beranda, Tentang Kami, Mitra & Klien, dan Kontak) untuk membaca profil dan melihat showcase kredibilitas perusahaan.

---

## 4. Business Rules

1. **Prinsip Structured CMS, Not Page Builder:**
   - Seluruh konten perusahaan terikat pada skema data yang terdefinisi secara ketat. Tidak ada komponen *drag-and-drop page builder* atau blok HTML bebas yang tidak terstruktur.
2. **Arsitektur Singleton `CompanyProfile`:**
   - Tabel `company_profiles` dirancang khusus sebagai *singleton record* (hanya ada 1 record data aktif pada sistem).
   - Admin tidak dapat membuat record profil baru (*disable create*) atau menghapus record profil (*disable delete*). Admin hanya dapat mengedit record profil yang ada.
3. **Struktur Terstruktur 6 Core Values:**
   - Mengakomodasi 6 poin nilai inti spiral resmi ANS dari Company Profile 2026.
   - Setiap nilai inti memiliki judul, deskripsi dwibahasa, ikon visual, dan urutan penataan (`sort_order`).
   - Konten nilai inti tidak di-hardcode di file Blade, melainkan dibaca secara dinamis dari tabel `core_values`.
4. **Aturan Visibilitas Publik (Activation Rule):**
   - Public website hanya menyajikan record `CoreValue`, `Management`, dan `Client` yang memiliki `is_active = true`.
   - Record berstatus `is_active = false` disembunyikan dari tampilan publik tanpa menghapus data fisiknya dari database.
5. **Aturan Status Awal Data Founder / Manajemen (Management Initial Activation Rule):**
   - Modul `Management` tetap tersedia penuh di CMS Filament 5 untuk mengelola profil pimpinan.
   - Sesuai keputusan bisnis klien, seluruh record Founder/Management yang disiapkan pada initial deployment **wajib disetel dalam kondisi nonaktif (`is_active = false`)** (*"Disiapkan tetapi sementara nonaktif"* / *"Prepared but inactive"*).
   - Selama berstatus `is_active = false`, Founder/Management **TIDAK BOLEH** muncul di public website.
   - Ketiadaan foto resolusi tinggi (HD) bukan blocker untuk pembuatan record di CMS; kolom `photo_path` bersifat opsional (`nullable`).
   - Tidak ada aktivasi otomatis (*no auto-activation*). Admin dapat mengaktifkan record Founder secara manual di kemudian hari setelah materi teks dan foto profil disetujui klien.
   - Public website dilarang menampilkan kartu placeholder Founder hanya karena record sudah ada di database jika statusnya masih nonaktif.
6. **Integritas Penghapusan (Deletion Constraint):**
   - `CompanyProfile`: **Dilarang keras dihapus (*No Delete*)**.
   - `CoreValue`, `Management`, `Client`: Penghapusan data diizinkan jika record tidak lagi relevan, dan file gambar fisik terkait wajib dihapus dari storage lokal untuk mencegah berkas yatim (*orphaned files*).

---

## 5. CompanyProfile Specification (Singleton)

- **Tabel:** `company_profiles`
- **Model:** `App\Models\CompanyProfile`

### 5.1. Skema Kolom & Validasi
| Nama Kolom | Tipe Data | Constraint / Rule | Deskripsi & Perilaku |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Identifikator unik record profil tunggal. |
| `tagline_id` | `varchar(255)` | Required, Max: 255 | Slogan resmi dalam Bahasa Indonesia. |
| `tagline_en` | `varchar(255)` | Required, Max: 255 | Slogan resmi dalam Bahasa Inggris (*"Empowering Science for a Prosperous Future"*). |
| `about_id` | `text` | Required | Deskripsi narasi sejarah & profil operasional dalam ID. |
| `about_en` | `text` | Required | Deskripsi narasi sejarah & profil operasional dalam EN. |
| `vision_id` | `text` | Required | Pernyataan Visi perusahaan dalam Bahasa Indonesia. |
| `vision_en` | `text` | Required | Pernyataan Visi resmi dalam Bahasa Inggris. |
| `mission_id` | `text` | Required | Pernyataan 4 poin Misi perusahaan dalam ID (RichText/HTML). |
| `mission_en` | `text` | Required | Pernyataan 4 poin Misi perusahaan dalam EN (RichText/HTML). |
| `address` | `text` | Required | Alamat fisik kantor resmi (Mensana Tower, Kranggan, Bekasi). |
| `phone` | `varchar(50)` | Required, Max: 50 | Nomor telepon kantor resmi (`021 39722772`). |
| `whatsapp` | `varchar(50)` | Required, Max: 50 | Nomor WhatsApp resmi untuk direct CTA (`0822-614-614-00`). |
| `email` | `varchar(255)` | Required, Email, Max: 255 | Email resmi perusahaan (`admin@avenasa.co.id`). |
| `maps_embed_url` | `text` | Nullable | URL embed Google Maps untuk halaman Kontak. |
| `created_at` | `timestamp` | Nullable | Waktu inisialisasi record. |
| `updated_at` | `timestamp` | Nullable | Waktu terakhir pembaruan konten. |

---

## 6. Vision & Mission Specification

- **Penyimpanan:** Disimpan sebagai bagian integral dari entitas `CompanyProfile` pada kolom `vision_id`, `vision_en`, `mission_id`, dan `mission_en`.
- **Format Konten:**
  - `vision_id` & `vision_en`: Format teks paragraf murni (*plain text* / *textarea*).
  - `mission_id` & `mission_en`: Format teks berpoin / terstruktur (*Rich Editor* atau *ordered list*).
- **Perilaku Tampilan:**
  - Disajikan pada Halaman Tentang Kami (`/{locale}/about`) di bawah section *Visi & Misi*.
  - Menampilkan teks sesuai locale aktif (`id` atau `en`) dengan fallback yang aman.

---

## 7. CoreValue Specification

- **Tabel:** `core_values`
- **Model:** `App\Models\CoreValue`

### 7.1. Skema Kolom & Validasi
| Nama Kolom | Tipe Data | Constraint / Rule | Deskripsi & Perilaku |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Identifikator unik nilai inti. |
| `title_id` | `varchar(255)` | Required, Max: 255 | Judul nilai inti dalam ID (misal: Integritas, Inovasi). |
| `title_en` | `varchar(255)` | Required, Max: 255 | Judul nilai inti dalam EN (Integrity, Innovation, Collaboration, Sustainability, Professionalism, Well-Being). |
| `description_id` | `text` | Required | Uraian penjelasan nilai inti dalam Bahasa Indonesia. |
| `description_en` | `text` | Required | Uraian penjelasan nilai inti dalam Bahasa Inggris. |
| `icon_name` | `varchar(100)` | Nullable, Max: 100 | Nama identifier ikon SVG/Heroicon (misal: `shield-check`, `light-bulb`, `user-group`, `globe-alt`, `briefcase`, `heart`). |
| `sort_order` | `integer` | Default: 0, Min: 0 | Urutan urutan tampil (1 s.d. 6). |
| `is_active` | `boolean` | Default: `true` | Status publikasi nilai inti. |
| `created_at` | `timestamp` | Nullable | Waktu pembuatan record. |
| `updated_at` | `timestamp` | Nullable | Waktu pembaruan record. |

---

## 8. Management Specification

- **Tabel:** `managements`
- **Model:** `App\Models\Management`

### 8.1. Skema Kolom & Validasi
| Nama Kolom | Tipe Data | Constraint / Rule | Deskripsi & Perilaku |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Identifikator unik data pimpinan. |
| `name` | `varchar(255)` | Required, Max: 255 | Nama lengkap dan gelar founder/manajemen (misal: Erik Haryanto, Fernanda Ramadhan F, Hazin Yusuf). |
| `position_id` | `varchar(255)` | Required, Max: 255 | Jabatan resmi dalam Bahasa Indonesia (misal: Komisaris, Direktur). |
| `position_en` | `varchar(255)` | Required, Max: 255 | Jabatan resmi dalam Bahasa Inggris (misal: Commissioner, Sales & Director, Director). |
| `bio_id` | `text` | Nullable | Ringkasan riwayat pengalaman profesional dalam ID. |
| `bio_en` | `text` | Nullable | Ringkasan riwayat pengalaman profesional dalam EN. |
| `photo_path` | `varchar(255)` | Nullable, Image (Max 2MB) | Path foto profil di `storage/app/public/management/`. Bersifat opsional (dapat diisi nanti oleh admin). |
| `sort_order` | `integer` | Default: 0, Min: 0 | Urutan penataan kartu pimpinan pada halaman Tentang Kami. |
| `is_active` | `boolean` | Default: `true` | Status publikasi profil pimpinan. (Catatan: Data awal Founder di-seed dengan `is_active = false`). |
| `created_at` | `timestamp` | Nullable | Waktu pembuatan record. |
| `updated_at` | `timestamp` | Nullable | Waktu pembaruan record. |

### 8.2. Penjelasan Perilaku Data & Foto
- **Foto Tidak Menjadi Blocker:** Ketiadaan foto profil berkualitas HD tidak menghambat pembuatan atau penyimpanan data Founder di CMS. Kolom `photo_path` tetap `nullable`.
- **Status Awal Nonaktif:** Record Founder disiapkan di database/CMS dengan status `is_active = false` sampai materi dan foto resmi disetujui klien.
- **Aktivasi Manual:** Admin dapat mengunggah foto kapan saja dan mengubah status menjadi aktif secara manual melalui Filament CMS. Tidak ada proses aktivasi otomatis saat foto diunggah.

---

## 9. Client Specification (Corporate Customers)

- **Tabel:** `clients`
- **Model:** `App\Models\Client`

### 9.1. Skema Kolom & Validasi
| Nama Kolom | Tipe Data | Constraint / Rule | Deskripsi & Perilaku |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Identifikator unik klien korporat. |
| `name` | `varchar(255)` | Required, Max: 255 | Nama institusi/perusahaan klien (misal: Kalbe Farma, Bio Farma, Unilever, Prodia). |
| `logo_path` | `varchar(255)` | Required, Image (Max 2MB) | Path logo resmi klien di `storage/app/public/clients/`. |
| `sort_order` | `integer` | Default: 0, Min: 0 | Urutan penataan logo pada grid/showcase. |
| `is_active` | `boolean` | Default: `true` | Status aktif publikasi logo klien. |
| `created_at` | `timestamp` | Nullable | Waktu pembuatan record. |
| `updated_at` | `timestamp` | Nullable | Waktu pembaruan record. |

---

## 10. Partners & Principals Integration (SPEC-01 Dependency)

- **Tidak Ada Entitas Baru:** Modul ini **TIDAK** membuat tabel atau resource `Principal` baru.
- **Ketergantungan Data:** Halaman publik *Mitra & Klien* (`/{locale}/partners-clients`) dan *Homepage Showcase* mengonsumsi data dari entitas **`Brand`** yang didefinisikan pada **SPEC-01 Catalog Management**:
  - Bagian *Our Principals*: Membaca `Brand` yang berstatus aktif (`is_active = true`), diurutkan berdasarkan `sort_order ASC`, dan menampilkan label khusus untuk `is_new_principal = true` (Era Biology).
  - Bagian *Our Clients*: Membaca entitas `Client` dari SPEC-02 yang berstatus aktif (`is_active = true`), diurutkan berdasarkan `sort_order ASC`.

---

## 11. Bilingual Rules & Localized Content

1. **Atribut Terjemahan Eksplisit (Bilingual Accessors):**
   Model `CompanyProfile`, `CoreValue`, dan `Management` menyediakan accessor dinamis:
   - `$model->tagline`: Mengembalikan `tagline_en` jika locale aktif `en` (fallback ke `tagline_id`), dan `tagline_id` jika locale `id`.
   - `$model->about`: Mengembalikan `about_en` jika locale `en` (fallback ke `about_id`), dan `about_id` jika locale `id`.
   - `$model->vision`: Mengembalikan `vision_en` jika locale `en` (fallback ke `vision_id`), dan `vision_id` jika locale `id`.
   - `$model->mission`: Mengembalikan `mission_en` jika locale `en` (fallback ke `mission_id`), dan `mission_id` jika locale `id`.
   - `$model->title` (CoreValue): Mengembalikan `title_en` jika locale `en` (fallback ke `title_id`), dan `title_id` jika locale `id`.
   - `$model->description` (CoreValue): Mengembalikan `description_en` jika locale `en` (fallback ke `description_id`), dan `description_id` jika locale `id`.
   - `$model->position` (Management): Mengembalikan `position_en` jika locale `en` (fallback ke `position_id`), dan `position_id` jika locale `id`.
   - `$model->bio` (Management): Mengembalikan `bio_en` jika locale `en` (fallback ke `bio_id`), dan `bio_id` jika locale `id`.
2. **Tanpa Translation Package:** Seluruh logika lokalisasi murni menggunakan kolom database terpisah tanpa package eksternal.

---

## 12. Filament 5 CMS Behavior

Seluruh pengelolaan konten perusahaan dikelompokkan di bawah grup navigasi **"Company Content"**:

```
[Group: Company Content]
├── Company Profile   (Singleton Edit Page)
├── Core Values       (CoreValueResource)
├── Management        (ManagementResource)
└── Clients           (ClientResource)
```

### 12.1. `CompanyProfilePage` (Singleton Custom Page / Resource)
- **Navigasi:** Group: *"Company Content"*, Icon: `heroicon-o-building-office`, Sort: 1.
- **Tipe Tampilan:** Singleton Edit Form (tanpa index tabel, create, atau delete action).
- **Form Sections / Tabs:**
  - Section *"Slogan & Profil Singkat"*: `tagline_id`, `tagline_en`, `about_id` (Textarea), `about_en` (Textarea).
  - Section *"Visi & Misi Perusahaan"*: `vision_id`, `vision_en`, `mission_id` (RichEditor), `mission_en` (RichEditor).
  - Section *"Informasi Kontak & Operasional"*: `address`, `phone`, `whatsapp`, `email`, `maps_embed_url`.
- **Aksi Simpan:** Menyimpan pembaruan ke ID record 1 dan menampilkan notifikasi sukses: *"Profil perusahaan berhasil diperbarui."*

### 12.2. `CoreValueResource`
- **Navigasi:** Group: *"Company Content"*, Icon: `heroicon-o-sparkles`, Sort: 2.
- **Tabel:** Kolom `sort_order`, `title_id`, `title_en`, `icon_name` (Badge), `is_active` (Toggle Column).
- **Searchable:** `title_id`, `title_en`.
- **Sortable:** `sort_order`, `title_id`.
- **Filter:** Filter status `is_active`.
- **Form:**
  - Section *"Nilai Inti"*: `title_id`, `title_en`, `icon_name` (Select icon atau TextInput identifier).
  - Section *"Deskripsi Nilai"*: `description_id` (Textarea), `description_en` (Textarea).
  - Section *"Pengaturan"*: `sort_order`, `is_active`.

### 12.3. `ManagementResource`
- **Navigasi:** Group: *"Company Content"*, Icon: `heroicon-o-user-group`, Sort: 3.
- **Tabel:** Kolom `photo_path` (Image Column), `name`, `position_id`, `position_en`, `sort_order`, `is_active` (Toggle Column).
- **Searchable:** `name`, `position_id`, `position_en`.
- **Sortable:** `sort_order`, `name`.
- **Filter:** Filter status `is_active`.
- **Form:**
  - Section *"Data Pimpinan"*: `name`, `photo_path` (FileUpload `image()`, max 2MB, `directory('management')`, `nullable()`).
  - Section *"Jabatan & Pengalaman"*: `position_id`, `position_en`, `bio_id` (Textarea), `bio_en` (Textarea).
  - Section *"Pengaturan"*: `sort_order`, `is_active` (Toggle).
- **Catatan Operasional CMS:** CMS mendukung penuh operasi pembuatan, pengubahan, upload foto, pengurutan, dan aktivasi/deaktivasi. Untuk initial deployment, record data 3 Founder dibuat dalam kondisi `is_active = false` sesuai arahan klien, dan admin dapat mengaktifkannya sewaktu-waktu tanpa batasan sistem permanen.

### 12.4. `ClientResource`
- **Navigasi:** Group: *"Company Content"*, Icon: `heroicon-o-briefcase`, Sort: 4.
- **Tabel:** Kolom `logo_path` (Image Column), `name`, `sort_order`, `is_active` (Toggle Column).
- **Searchable:** `name`.
- **Sortable:** `sort_order`, `name`, `created_at`.
- **Filter:** Filter status `is_active`.
- **Form:**
  - Section *"Identitas Klien"*: `name`, `logo_path` (FileUpload `image()`, max 2MB, `directory('clients')`).
  - Section *"Pengaturan"*: `sort_order`, `is_active`.

---

## 13. Media Rules (Storage & Lifecycle)

| Jenis Media | Direktori Penyimpanan | Ekstensi Valid | Batas Ukuran | Aturan Lifecycle |
|---|---|---|---|---|
| **Foto Profil Management** | `storage/app/public/management/` | `jpg, jpeg, png, webp` | 2 MB | File lama otomatis dihapus dari disk jika foto diganti atau data pimpinan dihapus. |
| **Logo Klien Korporat** | `storage/app/public/clients/` | `jpg, jpeg, png, webp` | 2 MB | File lama otomatis dihapus dari disk jika logo diganti atau data klien dihapus. |

- Seluruh URL gambar disajikan publik melalui symbolic link: `asset('storage/' . $path)`.

---

## 14. Activation & Ordering Rules

1. **Aturan Visibilitas Publik:**
   - `CoreValue`: Hanya menampilkan record dengan `is_active = true`.
   - `Management`: Hanya menampilkan record dengan `is_active = true`.
   - `Client`: Hanya menampilkan record dengan `is_active = true`.
2. **Aturan Pengurutan (Deterministic Ordering):**
   - `CoreValue`: `sort_order ASC`, kemudian `id ASC`.
   - `Management`: `sort_order ASC`, kemudian `name ASC`.
   - `Client`: `sort_order ASC`, kemudian `name ASC`.
   - `Brand` (Showcase Principal): Mengikuti `sort_order ASC` dari SPEC-01.

---

## 15. Deletion & Data Integrity

- **`CompanyProfile`:** Dilarang dihapus (*No Delete Action*).
- **`CoreValue`, `Management`, `Client`:** Hard delete diperbolehkan jika data sudah tidak digunakan. Saat aksi hapus dieksekusi:
  1. Sistem memeriksa dan menghapus file media fisik terkait dari disk storage lokal.
  2. Sistem menghapus record dari database MySQL.
  3. Menampilkan notifikasi sukses di Filament CMS.

---

## 16. Public Website Consumption

### 16.1. Halaman Beranda (`/{locale}`)
- Mengonsumsi `CompanyProfile` untuk pengantar singkat operasional perusahaan (>15 tahun pengalaman) dan slogan resmi.
- Mengonsumsi `Brand::where('is_active', true)` dan `Client::where('is_active', true)` untuk strip logo mitra/klien.
- Mengonsumsi `CompanyProfile` untuk informasi kontak cepat di footer global.

### 16.2. Halaman Tentang Kami (`/{locale}/about`)
- Mengonsumsi `CompanyProfile` untuk sejarah dan deskripsi profil lengkap ANS.
- Mengonsumsi `CompanyProfile` untuk pernyataan Visi dan 4 poin Misi resmi.
- Mengonsumsi `CoreValue::where('is_active', true)` untuk merender 6 kartu Nilai Inti dengan ikon visual.
- Mengonsumsi `Management::where('is_active', true)` untuk merender seluruh kartu profil pimpinan yang berstatus aktif berdasarkan `sort_order`.
  - **Perilaku Sembunyi Bersih (Graceful Visibility):** Jika seluruh data Management berstatus `is_active = false` (kondisi initial deployment), section *Leadership / Management* disembunyikan secara bersih dari halaman Tentang Kami tanpa menampilkan kartu kosong atau placeholder. Section ini otomatis muncul kembali begitu ada satu atau lebih record Management yang diaktifkan oleh admin.

### 16.3. Halaman Mitra & Klien (`/{locale}/partners-clients`)
- **Section Principal Resmi:** Merender seluruh `Brand::where('is_active', true)` dari SPEC-01 dalam format logo grid/carousel dengan highlight khusus principal baru (Era Biology).
- **Section Klien Korporat:** Merender seluruh `Client::where('is_active', true)` dari SPEC-02 dalam format logo grid informatif.

### 16.4. Halaman Kontak (`/{locale}/contact`)
- Mengonsumsi `CompanyProfile` untuk menampilkan alamat kantor Mensana Tower, nomor telepon kantor, nomor WhatsApp direct, email `admin@avenasa.co.id`, dan embed peta Google Maps interaktif.

---

## 17. Error & Edge Cases Handling

| Skenario Edge Case | Perilaku Sistem yang Ditetapkan |
|---|---|
| **Data `CompanyProfile` belum diisi di database** | Sistem menampilkan fallback placeholder yang rapi dan aman tanpa menimbulkan fatal error / null exception pada Blade view. |
| **Field terjemahan Bahasa Inggris (EN) kosong** | Sistem secara otomatis menggunakan nilai fallback dari field Bahasa Indonesia (ID) padanannya. |
| **Seluruh data `CoreValue` berstatus inactive** | Section Nilai Inti pada halaman Tentang Kami disembunyikan secara bersih tanpa meninggalkan area kosong rusak. |
| **Profil Management tidak memiliki foto** | Halaman Tentang Kami menampilkan *avatar placeholder* profesional bertema inisial nama atau ikon netral. |
| **Tidak ada data `Client` aktif** | Section Klien Korporat disembunyikan secara kondisional, halaman tetap menampilkan section Principal jika tersedia. |
| **Upaya menghapus singleton `CompanyProfile`** | Aksi delete dinonaktifkan di CMS dan dicegah di level policy/backend. |
| **Unggahan gambar melebihi batas 2 MB** | Validasi Filament/Laravel menolak request dan menampilkan pesan error inline: *"Ukuran gambar melebihi batas maksimal 2 MB."* |

---

## 18. Analytics Integration Points

Sesuai arsitektur analitik GA4 yang telah dikunci pada System Design:
- Halaman-halaman konten korporat (Tentang Kami, Mitra & Klien, Kontak) diukur secara otomatis melalui GA4 *Enhanced Measurement* (`page_view`, `scroll`, `user_engagement`).
- Klik pada tombol direct WhatsApp yang tercantum pada halaman profil atau kontak memicu event **`click_whatsapp`** (dengan parameter `location: 'contact_page' | 'navbar' | 'footer'`, `locale`).
- **Privasi Terjaga (No PII):** Tidak ada data pribadi direksi, narahubung, atau pengunjung yang dikirim ke GA4.

---

## 19. Acceptance Criteria (Format Given / When / Then)

### Kriteria 1: Pembaruan Profil Perusahaan di CMS
- **Given:** Admin membuka halaman Company Profile di Filament 5.
- **When:** Admin mengubah nomor WhatsApp menjadi `0822-614-614-00` dan memperbarui teks misi, lalu klik Save.
- **Then:** Data pada record singleton `company_profiles` tersimpan, dan perubahan seketika tercermin pada halaman Kontak dan Footer publik.

### Kriteria 2: Penyajian Konten Visi & Misi Dwibahasa
- **Given:** Data Visi dan Misi telah diisi dalam Bahasa Indonesia dan Bahasa Inggris di CMS.
- **When:** Pengunjung mengakses `/id/about`.
- **Then:** Halaman menyajikan teks Visi dan Misi dalam Bahasa Indonesia.
- **When:** Pengunjung mengakses `/en/about`.
- **Then:** Halaman menyajikan teks Visi dan Misi dalam Bahasa Inggris.

### Kriteria 3: Pengelolaan & Pengurutan 6 Core Values
- **Given:** Terdapat 6 Core Values aktif (Integritas s.d. Kesejahteraan) dengan `sort_order` 1 hingga 6.
- **When:** Pengunjung membuka halaman `/id/about`.
- **Then:** Sistem merender tepat 6 kartu nilai inti secara berurutan sesuai `sort_order`, lengkap dengan ikon visual dan deskripsi narasi.

### Kriteria 4: Status Awal & Visibilitas Data Management (Initial Inactive State)
- **Given:** Tiga data Founder/Management (Erik Haryanto, Fernanda Ramadhan F, Hazin Yusuf) telah disiapkan di CMS/database.
- **When:** Seluruh record tersebut berstatus `is_active = false` (kondisi default initial deployment).
- **Then:** Tidak ada satu pun Founder yang ditampilkan pada halaman publik `/id/about` maupun `/en/about`, dan section Leadership disembunyikan secara bersih.
- **When:** Admin mengubah status salah satu record Management menjadi `is_active = true` via Filament CMS.
- **Then:** Halaman `/id/about` seketika merender hanya profil Management yang berstatus aktif tersebut secara rapi.
- **When:** Record Management disiapkan dengan `photo_path` masih bernilai `NULL` dan status `is_active = false`.
- **Then:** Sistem tidak mengalami error, tidak ada foto rusak, dan data tidak muncul di public website.
- **When:** Record Management memiliki `photo_path` terisi foto tetapi status `is_active` masih `false`.
- **Then:** Record tetap tidak ditampilkan di public website sampai admin mengaktifkannya secara manual.

### Kriteria 5: Showcase Klien & Principal Terintegrasi
- **Given:** Terdapat 13 Brand aktif dari SPEC-01 dan 20 Client aktif dari SPEC-02.
- **When:** Pengunjung membuka halaman `/id/partners-clients`.
- **Then:** Halaman menampilkan section *"Principal Resmi"* memuat 13 logo brand dan section *"Klien Korporat"* memuat 20 logo klien dalam layout grid responsif.

### Kriteria 6: Proteksi Validasi Ukuran Foto
- **Given:** Admin mencoba mengunggah foto manajemen berukuran 4 MB (melebihi batas 2 MB).
- **When:** Admin memilih berkas tersebut pada form upload Filament.
- **Then:** Sistem menolak berkas, proses simpan dibatalkan, dan muncul notifikasi kesalahan validasi ukuran berkas.

---

## 20. Content Source & Initial Data Inventory (Company Profile 2026 Rev.02)

Data awal (*initial seed data*) yang bersumber dari dokumen resmi [Company Profile ANS 2026 Rev.02.pdf](file:///c:/laragon/www/avenasa/docs/references/Company%20Profile%20ANS%202026%20Rev.02.pdf):

1. **Tagline:** *"EMPOWERING SCIENCE FOR A PROSPEROUS FUTURE"*
2. **About Us:** Distributor & pemasar produk life science dengan pengalaman >15 tahun melayani farmasi, FnB, bioteknologi, kosmetik, lab pengujian, riset, universitas, dan RS.
3. **Visi:** *"To be a driving force of life science and industrial advancement for a prosperous future."*
4. **Misi:** 4 poin misi inovasi laboratorium, solusi berkelanjutan, kemitraan strategis, dan peningkatan nilai hidup.
5. **6 Core Values:**
   - 1. INTEGRITY
   - 2. INNOVATION
   - 3. COLLABORATION
   - 4. SUSTAINABILITY
   - 5. PROFESSIONALISM
   - 6. WELL-BEING
6. **Data Founder & Pimpinan (Prepared but Initially Inactive):**
   - Sesuai keputusan klien, data 3 Founder/Pimpinan berikut disiapkan di CMS/database dalam status **nonaktif (`is_active = false`)** dan tidak dipublikasikan ke website publik sampai materi teks dan foto HD final disetujui:
     - **Erik Haryanto** (Position ID: *Komisaris*, Position EN: *Commissioner* / *Marketing & Sales Manager and Commissioner*) -> Status awal: `is_active = false`, `photo_path = null`
     - **Fernanda Ramadhan F** (Position ID: *Direktur Penjualan*, Position EN: *Sales and Director*) -> Status awal: `is_active = false`, `photo_path = null`
     - **Hazin Yusuf** (Position ID: *Direktur*, Position EN: *Director* / *Marketing Manager and Director*) -> Status awal: `is_active = false`, `photo_path = null`
7. **Kontak Resmi:** Mensana Tower, Jl. Raya Kranggan RT.002/RW.016, Jatisampurna, Kota Bekasi, Jawa Barat. Telp: `021 39722772`, WA: `0822-614-614-00`, Email: `admin@avenasa.co.id`.

---

## 21. Implementation Dependencies

Fitur Company Content Management membutuhkan dependensi berikut sebelum implementasi:
1. **Laravel 12 Framework Foundation:** Berjalan pada PHP 8.3.x.
2. **Database Migrations MySQL 8.0+:** Tabel `company_profiles`, `core_values`, `managements`, dan `clients`.
3. **Storage Symlink:** Symlink `public/storage` terhubung ke `storage/app/public/`.
4. **Filament 5 CMS Panel:** Panel admin terpasang untuk merender Form & Table Resources.
5. **SPEC-01 Catalog Management:** Entitas `Brand` tersedia untuk showcase principal pada halaman Partners & Clients.

---

## 22. Out of Scope

Hal-hal berikut secara eksplisit **DI LUAR CAKUPAN (OUT OF SCOPE)** untuk fitur Company Content Management V1:
- Sistem manajemen multi-perusahaan / multi-cabang (*Multi-tenant / Multi-company*).
- Komponen *Page Builder* atau *Dynamic Layout Builder* bebas.
- Modul Blog, Berita (*News*), atau Artikel Perusahaan.
- Modul Testimonial klien kustom atau ulasan bintang.
- Modul Karir, Lowongan Kerja, dan Rekrutmen (*Career Portal*).
- Sistem Customer Portal atau Login Klien.
- Integrasi ke sistem CRM eksternal atau ERP korporat.
- Media Storage eksternal berbasis AWS S3 atau Cloud DAM.

---

*(Feature Specification Company Content Management telah selesai disusun dan menunggu final review sebelum dikunci untuk implementasi.)*
