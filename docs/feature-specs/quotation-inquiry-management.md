# Feature Specification: Quotation & Inquiry Management

**Feature ID:** `SPEC-04-QUOTATION-INQUIRY`  
**Feature Name:** Quotation & Inquiry Management  
**Proyek:** Website Company Profile & Katalog Produk  
**Klien:** PT Abhipraya Nawasena Sejahtera (ANS)  
**Dokumen Referensi & Sumber Kebenaran:**
1. [docs/architecture/technology-baseline.md](file:///c:/laragon/www/avenasa/docs/architecture/technology-baseline.md)
2. [docs/architecture/system-design.md](file:///c:/laragon/www/avenasa/docs/architecture/system-design.md)
3. [docs/urs/ans-website-urs.md](file:///c:/laragon/www/avenasa/docs/urs/ans-website-urs.md)
4. [docs/feature-specs/catalog-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/catalog-management.md)
5. [docs/feature-specs/company-content-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/company-content-management.md)
6. [docs/feature-specs/hero-banner-management.md](file:///c:/laragon/www/avenasa/docs/feature-specs/hero-banner-management.md)
7. [docs/references/Company Profile ANS 2026 Rev.02.pdf](file:///c:/laragon/www/avenasa/docs/references/Company%20Profile%20ANS%202026%20Rev.02.pdf)  
**Status Dokumen:** Draft Feature Specification — Under Review

---

## 1. Feature Overview

Fitur **Quotation & Inquiry Management** adalah modul pemrosesan prospek penawaran harga (*leads*) dan pertanyaan resmi dari pengunjung website publik PT Abhipraya Nawasena Sejahtera.

Sistem ini melayani dua jalur masuk formulir:
1. **Jalur Kontekstual Produk (Product Detail CTA):** Pengunjung menekan tombol *"Minta Penawaran / Quotation"* pada halaman detail produk, sehingga formulir otomatis membawa konteks produk terkait (`product_id`).
2. **Jalur Umum (General Contact / Quotation Page):** Pengunjung mengakses menu *Contact* publik secara langsung untuk mengajukan pertanyaan umum atau kerja sama korporat (`product_id = null`).

Tujuan modul ini:
- Menangkap data leads bisnis secara aman, cepat, dan bebas spam.
- Menyimpan setiap permintaan ke database MySQL sebagai **Single Source of Truth** sebelum mencoba pengiriman email.
- Menyediakan antarmuka pengelolaan bagi staf admin ANS di Filament 5 CMS untuk memantau, memfilter, mengubah status tindak lanjut, dan mencatat catatan internal.
- Mengirimkan email notifikasi instan ke staf admin (`admin@avenasa.co.id`) dan email konfirmasi otomatis kepada calon klien dalam bahasa yang sesuai dengan locale pengiriman (ID/EN).
- Memicu event analitik konversi GA4 (`submit_quotation`) secara murni *client-side* tanpa membocorkan data pribadi (*No PII*).

---

## 2. Core Architectural Principle: Database as Single Source of Truth

Modul ini wajib mematuhi prinsip arsitektur **Database as Source of Truth** dengan urutan eksekusi proses bisnis yang tegas:

```
[User Submits Form]
        ↓
[1. Server-Side Validation & Anti-Spam Check] (Honeypot + Throttling + CSRF)
        ↓
[2. Save Quotation to Database] (Status: 'New')
        ↓
[3. Attempt Admin Notification Email] (admin@avenasa.co.id via SMTP)
        ↓ (Kegagalan SMTP ditangkap & di-log, tidak membatalkan transaksi)
[4. Attempt User Confirmation Email] (Email Pengunjung via SMTP)
        ↓ (Kegagalan SMTP ditangkap & di-log, tidak membatalkan transaksi)
[5. Return Success Response to User] (Flash Message + GA4 submit_quotation trigger)
```

### Aturan Kritis Penanganan Kegagalan Email (Email Failure Rule):
- **Database Persistence Mutlak:** Jika data quotation berhasil divalidasi dan disimpan ke database MySQL, transaksi dinyatakan **SUKSES**.
- **Isolasi Kegagalan SMTP:** Kegagalan koneksi SMTP (timeout, authentication failure, mail server down) ditangkap melalui blok penanganan error (`try-catch`), dicatat pada berkas *application log* (`storage/logs/laravel.log`), dan **DILARANG KERAS** membatalkan record database (*no rollback*) atau melempar error `HTTP 500` kepada pengguna publik.
- **Transparansi Respon:** Pengguna publik tetap menerima respon sukses ("Permintaan Anda telah berhasil diterima") karena data prospek mereka telah aman tersimpan di CMS dan siap ditindaklanjuti oleh admin.

---

## 3. Scope & Single Entity Architecture

Scope fitur ini mencakup 1 entitas database utama yang dikelola di Filament 5:
- **`Quotation` Entity:** Menjadi entitas tunggal (*single entity*) untuk seluruh jenis permintaan penawaran harga dan pesan inquiry kontak pada V1.
- **Tabel Database:** `quotations`
- **Model Eloquent:** `App\Models\Quotation`

> [!NOTE]
> Sistem **TIDAK MEMBUAT** entitas duplikat seperti `Inquiry`, `ContactMessage`, `Lead`, atau `QuoteRequest`. Seluruh komunikasi masuk disatukan dalam tabel `quotations` untuk kesederhanaan pemeliharaan di shared hosting.

---

## 4. Actors & Permissions

### 4.1. Public Visitor (Calon Klien B2B)
- Mengakses formulir quotation di halaman kontak atau via tombol CTA produk.
- Mengirimkan data identitas (Nama, Email, Perusahaan, Telepon/WA, Subjek, Pesan).
- Menerima umpan balik sukses di layar dan email konfirmasi otomatis.
- **Batasan:** Tidak memerlukan akun/login, tidak memiliki akses langsung ke database, dan tidak dapat melihat data quotation pengunjung lain.

### 4.2. Administrator ANS
- Mengakses panel Filament 5 CMS di bawah grup navigasi *"Quotation / Inquiry Management"*.
- Melihat daftar quotation terbaru dengan badge status (*New*, *Contacted*, *Quoted*, *Closed*).
- Mencari data berdasarkan nama, email, perusahaan, atau subjek.
- Membuka halaman detail quotation untuk membaca pesan lengkap secara aman (*escaped text*).
- Memperbarui status penanganan prospek dan menulis catatan internal staf.

---

## 5. Core Data Model: `Quotation`

- **Tabel:** `quotations`
- **Model:** `App\Models\Quotation`

### 5.1. Skema Kolom & Validasi Server-Side
| Nama Kolom | Tipe Data | Constraint / Rule | Deskripsi & Perilaku |
|---|---|---|---|
| `id` | `bigint unsigned` | Primary Key, Auto Increment | Identifikator unik data quotation. |
| `product_id` | `bigint unsigned` | Nullable, Foreign Key (`products.id`), Set Null on Delete | Relasi ke produk yang diminati (jika berasal dari Product Detail). |
| `name` | `varchar(255)` | Required, String, Max: 255 | Nama lengkap pengirim / narahubung. |
| `email` | `varchar(255)` | Required, Email, Max: 255 | Alamat email resmi pengirim untuk konfirmasi & balasan (`email:rfc`). |
| `phone` | `varchar(50)` | Nullable, String, Max: 50 | Nomor telepon / nomor WhatsApp aktif pengirim. |
| `company` | `varchar(255)` | Nullable, String, Max: 255 | Nama institusi / perusahaan / universitas / laboratorium pengirim. |
| `subject` | `varchar(255)` | Required, String, Max: 255 | Judul topik permintaan atau nama produk yang diminta. |
| `message` | `text` | Required, String, Max: 5000 | Isi rincian kebutuhan penawaran atau pertanyaan teknis. |
| `status` | `enum` | Default: `'New'`, Values: `'New'`, `'Contacted'`, `'Quoted'`, `'Closed'` | Status siklus penanganan prospek oleh tim sales ANS. |
| `locale` | `varchar(10)` | Default: `'id'`, Values: `'id'`, `'en'` | Bahasa antarmuka saat pengguna melakukan submit. |
| `admin_notes` | `text` | Nullable | Catatan internal staf admin (hanya dapat dibaca/diisi via CMS). |
| `created_at` | `timestamp` | Nullable | Waktu submit formulir. |
| `updated_at` | `timestamp` | Nullable | Waktu pembaruan status / catatan admin. |

### 5.2. Relasi Eloquent
- `product()`: `belongsTo(Product::class, 'product_id')`

---

## 6. Product Context & Form Routing Flow

### 6.1. Alur Masuk dari Product Detail (Product Context Flow)
1. Pengunjung menekan tombol CTA *"Minta Penawaran / Quotation"* di halaman `/{locale}/products/{slug}`.
2. Halaman diarahkan ke endpoint kontak dengan parameter ID produk: `/{locale}/contact?product_id={id}` (tanpa membawa parameter string nama produk di URL).
3. **Resolusi Server-Side (Database as Source of Truth):**
   - Server membaca `product_id` dari query string dan mencari record `Product` terkait di database MySQL.
   - **Jika `product_id` valid dan produk berstatus aktif:** Form kontak merender badge konteks resmi *"Produk yang Diminta: [Nama Produk dari Database]"*, mengisi default teks subjek secara otomatis, dan menyematkan `product_id` pada input tersembunyi.
   - **Jika `product_id` tidak valid atau produk nonaktif:** Server secara aman memperlakukan request sebagai inquiry umum (`product_id = null`), tidak mempercayai data dari client, dan merender form bersih tanpa menampilkan badge produk rusak.

### 6.2. Alur Masuk Umum (General Contact Flow)
1. Pengunjung membuka halaman kontak langsung via menu navbar `/{locale}/contact`.
2. Form disajikan dalam kondisi bersih (`product_id = null`).
3. Pengunjung mengisi seluruh field secara manual.

---

## 7. Public Form Fields & Validation Rules Matrix

| Field Form Publik | Tipe Input HTML | Aturan Validasi Server-Side | Pesan Error / Penanganan |
|---|---|---|---|
| `name` | `text` | `required\|string\|max:255` | Wajib diisi, otomatis di-trim spasi depan/belakang. |
| `email` | `email` | `required\|email:rfc\|max:255` | Format email wajib valid secara sintaksis (`rfc`). |
| `phone` | `tel` | `nullable\|string\|max:50` | Opsional, membersihkan karakter ilegal. |
| `company` | `text` | `nullable\|string\|max:255` | Opsional, nama institusi pengirim. |
| `subject` | `text` | `required\|string\|max:255` | Wajib diisi, dibersihkan dari karakter kontrol mail header injection (`\r`, `\n`). |
| `message` | `textarea` | `required\|string\|min:10\|max:5000` | Wajib diisi minimal 10 karakter bermakna dan maksimal 5.000 karakter. |
| `product_id` | `hidden` | `nullable\|exists:products,id` | Disimpan jika valid; disetel null jika tidak ditemukan. |
| `website_url_hp` | `text (hidden)` | `nullable\|max:0` | **Honeypot Anti-Spam Field**. Jika terisi karakter apa pun, request langsung ditolak secara diam-diam. |

---

## 8. Anti-Spam, Rate Limiting & Security Architecture

1. **Honeypot Anti-Spam (`website_url_hp`):**
   - Disematkan pada form Blade dengan teknik CSS off-screen (`display: none` atau posisi tersembunyi).
   - Manusia tidak dapat melihat atau mengisi field ini. Bot spam otomatis yang mengisi field ini akan ditolak oleh server secara instan (mengembalikan respon sukses semu / *silent drop* tanpa menyimpan ke database dan tanpa mengirim email).
2. **Rate Limiting & Throttling:**
   - Dibatasi maksimal **5 request per menit per IP address** (`throttle:5,1`).
   - Jika melebihi ambang batas, server mengembalikan respon `HTTP 429 Too Many Requests` dengan notifikasi: *"Terlalu banyak permintaan penawaran. Silakan coba kembali dalam 1 menit."*
3. **CSRF Protection:**
   - Seluruh pengiriman formulir wajib menyertakan token `@csrf` Laravel yang valid.
4. **Pencegahan Mail Header Injection:**
   - Input `subject`, `name`, dan `email` disaring ketat dari karakter baris baru (`\r`, `\n`) sebelum diproses oleh Mailer.
5. **Output Escaping & Sanitasi:**
   - Isi `message` dan seluruh input pengguna diperlakukan sebagai teks mentah yang tidak tepercaya (*untrusted text*). Saat dirender di Filament CMS atau template email, seluruh konten di-escape secara otomatis (`e()` / `htmlspecialchars`) untuk mencegah serangan Cross-Site Scripting (XSS).
6. **Proteksi Duplikasi Submit (Idempotency & Button Debounce):**
   - Tombol submit form pada public Blade diproteksi dengan Alpine.js state (`x-data="{ submitting: false }" @submit="submitting = true"`) yang langsung menonaktifkan tombol (*disabled*) setelah klik pertama untuk mencegah *accidental double submission*.

---

## 9. Quotation Status Workflow & Transitions

Status penanganan quotation diatur secara sederhana dan fleksibel tanpa *state machine* yang kaku:

```
[New] ──> [Contacted] ──> [Quoted] ──> [Closed]
  │           │              │
  └─── (Dapat diubah langsung oleh Admin ke status mana pun) ───┘
```

### Definisi Status Bisnis & Pemetaan Semantik UI:
1. **`New` (Baru):** Status default saat quotation baru masuk dari public website dan belum dilihat/ditindaklanjuti oleh staf. Pemetaan semantik UI: *Danger / Alert*.
2. **`Contacted` (Telah Dihubungi):** Staf admin/sales telah menghubungi calon klien via WhatsApp atau telepon untuk mengonfirmasi rincian kebutuhan. Pemetaan semantik UI: *Warning / In-Progress*.
3. **`Quoted` (Penawaran Terkirim):** Surat penawaran harga resmi (Quotation Letter) telah diterbitkan dan dikirimkan kepada klien. Pemetaan semantik UI: *Primary / Info*.
4. **`Closed` (Selesai / Ditutup):** Prospek telah selesai diproses (menjadi transaksi pembelian atau deal selesai/dibatalkan). Pemetaan semantik UI: *Success / Neutral*.
*(Catatan: Token warna aktual dan styling badge mengikuti design system Filament 5 pada tahap UI implementation).*

---

## 10. Filament 5 CMS Behavior: `QuotationResource`

Pengelolaan quotation dikelompokkan di bawah grup navigasi **"Quotation / Inquiry Management"**:

### 10.1. Konfigurasi Navigasi Panel
- **Group Navigasi:** *"Quotation / Inquiry Management"*.
- **Label Navigasi:** *"Quotations & Inquiries"*.
- **Icon:** `heroicon-o-chat-bubble-left-right`.
- **Sort Order Navigasi:** 1.
- **Badge Notifikasi:** Menampilkan counter jumlah quotation baru yang berstatus `'New'` pada sidebar panel admin (`getNavigationBadge()`).

### 10.2. Tampilan Tabel Index (`QuotationResource::table`)
- **Kolom Tabel:**
  - `created_at`: DateTime Column (`d M Y H:i`), sortable, default sort: `desc`.
  - `name`: Text Column, searchable, sortable, weight: bold.
  - `company`: Text Column, searchable, placeholder: *"- (Individu) -"*.
  - `email`: Text Column, searchable, copyable.
  - `subject`: Text Column, searchable, limit: 40 karakter.
  - `product.name_id`: Text Column, label: *"Produk"*, badge, placeholder: *"Umum"*.
  - `locale`: Text Column, badge, label: *"Bahasa"* (ID / EN).
  - `status`: Select Column / Badge Column, dengan pemetaan semantik status (New, Contacted, Quoted, Closed).
- **Searchable Fields:** `name`, `email`, `company`, `subject`, `phone`.
- **Sortable Fields:** `created_at`, `status`, `name`.
- **Filter Tabel:**
  - Select Filter status (`New`, `Contacted`, `Quoted`, `Closed`).
  - Select Filter produk berelasi (`product_id`).
  - Select Filter locale (`id`, `en`).
  - Date Range Filter untuk tanggal submit (`created_at`).
- **Aksi Tabel:** View Action (membuka modal/halaman detail) dan Edit Status/Notes Action.
- **Kebijakan Penghapusan Data (No Delete Action on V1):** Sesuai aturan bisnis, Quotation merupakan rekaman data prospek bisnis resmi yang dipertahankan utuh. Pada workflow operasional admin V1, **TIDAK DISEDIAKAN** aksi hard-delete biasa (*No Delete Action*) untuk mencegah penghapusan data prospek yang tidak disengaja.

### 10.3. Tampilan Detail & Form Editor (`QuotationResource::view` & `form`)
- **Section 1: "Informasi Calon Klien & Kontak" (Grid 2 Kolom):**
  - `name`: TextInput, readonly.
  - `email`: TextInput, readonly.
  - `phone`: TextInput, readonly, dengan tautan cepat *"Kirim WhatsApp"* jika nomor valid.
  - `company`: TextInput, readonly.
  - `locale`: TextInput, readonly.
  - `created_at`: TextInput, readonly.
- **Section 2: "Konteks Permintaan & Produk":**
  - `product_id`: Select readonly ke master Produk (menampilkan nama produk, kategori, dan brand).
  - `subject`: TextInput, readonly.
  - `message`: Textarea, readonly, rows: 6 (dirender aman sebagai teks murni).
- **Section 3: "Status & Tindak Lanjut Internal" (Area Interaktif Admin):**
  - `status`: Select, required, options: `New`, `Contacted`, `Quoted`, `Closed`.
  - `admin_notes`: Textarea, label: *"Catatan Internal Staf"*, placeholder: *"Tuliskan catatan tindak lanjut, no penawaran internal, atau hasil diskusi..."*.

---

## 11. Email Notification & Delivery Architecture

Sistem menggunakan **Direct SMTP** bawaan Laravel (`Illuminate\Support\Facades\Mail`) yang kompatibel penuh dengan shared hosting:

### 11.1. Email Notifikasi Admin (`QuotationAdminNotificationMail`)
- **Penerima:** `admin@avenasa.co.id`
- **Subjek Email:** `[New Inquiry] - {Subject} - {Sender Name}`
- **Bahasa Template:** Bahasa Indonesia resmi korporat.
- **Isi Email:**
  - Ringkasan data lengkap pengirim (Nama, Email, Perusahaan, Telepon).
  - Konteks produk yang diminta (jika ada) beserta link menuju produk.
  - Teks subjek dan pesan lengkap calon klien.
  - Timestamp pengiriman dan locale antarmuka pengirim.
  - Tombol CTA direct ke admin panel Filament untuk meninjau data.

### 11.2. Email Konfirmasi Pengunjung (`QuotationConfirmationMail`)
- **Penerima:** Alamat email pengirim (`$quotation->email`).
- **Subjek Email Berdasarkan Locale:**
  - Locale ID: `Konfirmasi Permintaan Penawaran - PT Abhipraya Nawasena Sejahtera`
  - Locale EN: `Quotation Request Confirmation - PT Abhipraya Nawasena Sejahtera`
- **Bahasa Template Dinamis:**
  - Submission dari `/id/...`: Template Bahasa Indonesia resmi yang ramah dan profesional.
  - Submission dari `/en/...`: Template Bahasa Inggris resmi korporat.
- **Isi Email (Bebas Janji SLA / Waktu Respon Spesifik):**
  - Sapaan resmi kepada pengirim (`$quotation->name`).
  - Konfirmasi bahwa pesan dan permintaan penawaran telah berhasil diterima oleh sistem ANS.
  - Ringkasan subjek dan konteks produk yang diminta.
  - Pernyataan konfirmasi: *"Tim ANS akan meninjau dan menindaklanjuti permintaan Anda sesuai kebutuhan."* (tanpa menjanjikan estimasi waktu respons spesifik/SLA).
  - Informasi kontak resmi perusahaan (Alamat Mensana Tower, Telepon `021 39722772`, WhatsApp `0822-614-614-00`).

---

## 12. Analytics Integration Points (GA4 Events)

Pelacakan analitik konversi penawaran harga diatur secara presisi murni *client-side* tanpa melanggar privasi:

1. **`start_quotation` Event:**
   - **Pemicu:** Pengunjung pertama kali memfokuskan kursor/mengetik pada field formulir quotation, atau mengklik tombol CTA "Minta Penawaran" pada halaman detail produk.
   - **Payload Parameter:**
     - `product_id`: ID produk terkait (jika dari Product Detail) atau `null`.
     - `product_name`: Nama produk terkait atau `null`.
     - `source`: `'product_detail'` atau `'contact_page'`.
     - `locale`: Bahasa aktif (`id` atau `en`).
2. **`submit_quotation` Conversion Event:**
   - **Pemicu:** Server-side validation berhasil **DAN** record quotation telah tersimpan sukses di database MySQL.
   - **Payload Parameter:**
     - `product_id`: ID produk terkait atau `null`.
     - `has_company`: Boolean (`true` jika field perusahaan diisi, `false` jika kosong).
     - `source`: `'product_detail'` atau `'contact_page'`.
     - `locale`: Bahasa aktif (`id` atau `en`).
   - *(Catatan: `quotation_id` internal database sengaja tidak dikirim ke GA4 karena tidak diperlukan untuk behavioral measurement).*
3. **Kepatuhan Privasi Mutlak (No PII):**
   - **DILARANG KERAS** mengirim parameter: `name`, `email`, `phone`, `company` (nama string), `subject`, atau isi `message` ke Google Analytics 4.

---

## 13. Mobile UX & Accessibility Standards

- **Mobile-First Single Column Layout:** Formulir disajikan dalam 1 kolom vertikal yang lapang pada layar mobile (`< 768px`) untuk mencegah pergeseran layout saat keyboard virtual aktif.
- **Area Sentuh Optimal (Touch Targets):** Seluruh input field dan tombol submit memiliki tinggi minimal `48px` dan padding yang nyaman.
- **Tipografi Input Ramah Ponsel:** Ukuran font input disetel minimal `16px` (`text-base`) untuk mencegah browser Safari/Chrome di iOS melakukan auto-zoom yang mengganggu saat input difokuskan.
- **Aksesibilitas Label & Error (WCAG 2.1 AA):** Setiap input memiliki `<label>` eksplisit dengan atribut `for`, penanda required yang jelas, dan pesan error validasi yang terhubung dengan `aria-describedby`.

---

## 14. Error & Edge Cases Handling Matrix

| Skenario Edge Case | Perilaku Sistem yang Ditetapkan |
|---|---|
| **Format email tidak valid (kesalahan sintaksis)** | Server menolak submit, form menampilkan error inline: *"Alamat email tidak valid."* |
| **Field wajib (Nama, Email, Subjek, Pesan) kosong** | Server menolak submit dan menyorot field yang belum terisi. |
| **Bot mengisi field honeypot `website_url_hp`** | Server menghentikan proses secara diam-diam (*silent drop*), tidak menyimpan ke DB, dan tidak mengirim email. |
| **Pengiriman melebihi 5x per menit dari IP yang sama** | Server menolak dengan status `HTTP 429 Too Many Requests`. |
| **Koneksi SMTP down / gagal saat kirim email admin/user** | Sistem mencatat error di log; record quotation **TETAP TERSIMPAN** di database, dan user tetap menerima pesan sukses di layar. |
| **Database server MySQL mengalami failure saat save** | Server menangani exception, mencatat log sistem, tidak mengirim email, dan menampilkan pesan error umum: *"Maaf, terjadi kendala teknis. Silakan coba kembali."* |
| **`product_id` tidak ditemukan di DB atau produk nonaktif** | Sistem menyimpan quotation sebagai inquiry umum (`product_id = null`) dengan teks subjek yang tetap utuh tanpa menampilkan badge produk yang rusak. |
| **Pengguna melakukan refresh halaman setelah submit sukses** | Halaman merender form bersih baru tanpa melakukan resubmission data ganda (*Post-Redirect-Get pattern*). |
| **Pesan mengandung tag HTML / JavaScript (`<script>`)** | Tag HTML di-escape secara aman (`&lt;script&gt;`) saat ditampilkan di admin panel maupun di dalam email. |
| **Pengguna membuka form langsung dari menu Contact** | Form berjalan normal dengan `product_id = null`. |

---

## 15. Acceptance Criteria (Format Given / When / Then)

### Kriteria 1: Pengiriman Quotation Berhasil dari Product Detail
- **Given:** Pengunjung berada di halaman `/id/products/media-agar-merck` dan mengklik tombol *"Minta Penawaran"*.
- **When:** Pengunjung diarahkan ke `/id/contact?product_id=5`, server me-resolve nama produk dari database, pengunjung mengisi Nama, Email, Perusahaan, Pesan, lalu klik Submit.
- **Then:** Data tersimpan di tabel `quotations` dengan status `'New'` dan `product_id = 5`; email notifikasi dikirim ke admin; email konfirmasi ID dikirim ke user tanpa janji SLA waktu; event GA4 `submit_quotation` terpicu (membawa `product_id: 5`, `has_company: true`, `source: 'product_detail'`, `locale: 'id'`); dan muncul notifikasi sukses *"Permintaan penawaran Anda telah berhasil diterima."*

### Kriteria 2: Pengiriman Quotation Umum Tanpa Produk
- **Given:** Pengunjung membuka `/en/contact` langsung dari menu utama.
- **When:** Pengunjung mengisi data valid dan mengirimkan formulir.
- **Then:** Data tersimpan dengan `product_id = null` dan `locale = 'en'`; email konfirmasi terkirim dalam Bahasa Inggris; data muncul di tabel Filament CMS.

### Kriteria 3: Penolakan Bot via Honeypot
- **Given:** Bot spam mengisi field tersembunyi `website_url_hp = "http://spam.com"`.
- **When:** Bot mengirimkan request POST ke endpoint quotation.
- **Then:** Sistem menolak request secara instan, tidak ada data yang masuk ke database, dan tidak ada email yang dikirim.

### Kriteria 4: Throttling / Rate Limiting (5 Request / Menit / IP)
- **Given:** Pengguna telah mengirimkan 5 quotation dalam rentang waktu 1 menit.
- **When:** Pengguna mencoba mengirimkan quotation ke-6 dari IP yang sama.
- **Then:** Request ditolak dengan kode status `HTTP 429` dan muncul pesan peringatan pembatasan frekuensi.

### Kriteria 5: Database Persistence Saat SMTP Mengalami Gangguan
- **Given:** Layanan server SMTP eksternal sedang mengalami offline/gangguan.
- **When:** Pengunjung mengirimkan formulir quotation dengan data valid.
- **Then:** Record quotation berhasil disimpan ke database MySQL dengan status `'New'`; kegagalan SMTP dicatat di log file; browser **TIDAK** menampilkan HTTP 500; dan pengunjung tetap menerima pesan sukses bahwa permintaan telah diterima.

### Kriteria 6: Pengelolaan & Perubahan Status di Filament 5 CMS (Tanpa Aksi Hapus)
- **Given:** Staf admin login ke Filament panel di bawah grup *"Quotation / Inquiry Management"* dan melihat quotation berstatus `'New'`.
- **When:** Admin membuka detail quotation, menulis catatan internal di `admin_notes`, dan mengubah status menjadi `'Contacted'`.
- **Then:** Status record di database terbarui, badge counter berkurang, perubahan status tercatat dengan benar, dan tidak ada tombol aksi delete pada tabel operasional.

### Kriteria 7: Privasi Analitik GA4 (No PII & No Internal ID)
- **Given:** Pengunjung bernama "Budi" (`budi@perusahaan.com`, Telp: `0812345678`) mengirim quotation sukses dengan context produk.
- **When:** Event GA4 `submit_quotation` terkirim dari browser.
- **Then:** Parameter yang dikirim hanya memuat `product_id`, `has_company: true`, `source`, dan `locale` tanpa memuat `quotation_id` internal, nama, email, nomor telepon, atau isi pesan Budi.

---

## 16. Implementation Dependencies

Fitur Quotation & Inquiry Management membutuhkan dependensi berikut sebelum implementasi kode dimulai:
1. **Laravel 12 Framework Foundation:** Berjalan pada PHP 8.3.x dengan library `Illuminate\Mail`.
2. **Database Migration MySQL 8.0+:** Tabel `quotations` dengan relasi foreign key ke `products.id`.
3. **Filament 5 Admin Panel:** Terpasang untuk merender `QuotationResource` di bawah grup *"Quotation / Inquiry Management"*.
4. **Direct SMTP Configuration:** Konfigurasi akun email `admin@avenasa.co.id` pada `.env`.
5. **SPEC-01 Catalog Management:** Entitas `Product` tersedia untuk integrasi konteks produk.
6. **Localization Architecture:** Middleware `SetLocaleMiddleware` untuk penentuan bahasa template email konfirmasi.
7. **GA4 Base Architecture:** Penampung dataLayer untuk pemicu event `start_quotation` dan `submit_quotation`.

---

## 17. Out of Scope

Hal-hal berikut secara tegas dinyatakan **DI LUAR CAKUPAN (OUT OF SCOPE)** untuk fitur Quotation & Inquiry Management V1:
- Sistem Sales CRM lengkap (Lead scoring, SLA tracking, opportunity pipeline).
- Generator otomatis surat penawaran PDF (*Automated PDF Quotation Builder*).
- Fitur tanda tangan digital (*E-Signature*).
- Gateway pembayaran online atau checkout e-commerce.
- Fitur unggah dokumen lampiran oleh pengunjung (*User File Attachment Upload*).
- Integrasi bot otomatisasi WhatsApp API berbayar atau SMS gateway.
- Sistem buletin email marketing / newsletter berkala.
- Integrasi dua arah dengan sistem ERP korporat.

---

*(Feature Specification Quotation & Inquiry Management telah selesai disusun dan menunggu final review sebelum dikunci untuk implementasi.)*
