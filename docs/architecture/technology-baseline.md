# Technology Baseline

**Proyek:** Website Company Profile & Katalog Produk  
**Klien:** PT Abhipraya Nawasena Sejahtera (ANS)  
**Dokumen Referensi:**
- [docs/urs/ans-website-urs.md](file:///c:/laragon/www/avenasa/docs/urs/ans-website-urs.md)
- [docs/architecture/system-design.md](file:///c:/laragon/www/avenasa/docs/architecture/system-design.md)  
**Status Tahap:** Locked Technology Baseline (Pra-Inisialisasi)

---

## 1. Current Local Environment

Berdasarkan environment lokal pengembang yang aktif saat ini:
- **PHP Version:** `PHP 8.3.30` (CLI / Web Server)
- **Local Web Server:** Laragon (Apache / Nginx / LiteSpeed-compatible)
- **Database Engine:** MySQL 8.0+
- **Composer:** Kompatibel dengan PHP 8.3

Seluruh pengembangan lokal dan pengujian awal akan dieksekusi menggunakan target environment PHP 8.3.30 ini.

---

## 2. Proposed Technology Stack

Stack teknologi yang dirancang untuk proyek ANS:

- **Runtime / Language:** PHP 8.3.x (Local target: 8.3.30)
- **Backend Framework:** Laravel 12.x
- **Admin CMS Framework:** Filament 5.x
- **Reactive UI Library:** Livewire 4.x (Dependency inti dari Filament 5)
- **Frontend View Layer:** Laravel Blade + Tailwind CSS 4.x
- **Micro-interactivity:** Alpine.js (minimal)
- **Database:** MySQL 8.0+
- **Storage:** Laravel Local Public Filesystem (`storage/app/public`)
- **Mail:** Laravel Mailer via Direct SMTP

---

## 3. Compatibility Verification

Verifikasi kompatibilitas antar-komponen stack teknologi dilakukan per **16 Agustus 2026** berdasarkan dokumentasi dan metadata resmi:

| Komponen | Versi Target | Requirement PHP | Upstream Compatibility | Status Verifikasi Resmi |
|---|---|---|---|---|
| **Laravel** | 12.x | PHP 8.2 – 8.5 | PHP 8.3.30 (Fully Compatible) | **Verified** (Laravel Official Documentation) |
| **Livewire** | 4.x | PHP 8.2+ | Laravel 12.x & PHP 8.3 | **Verified** (Livewire Official Documentation) |
| **Filament** | 5.x | PHP 8.2+ | Laravel 12.x + Livewire 4.x + PHP 8.3 | **Verified** (Filament 5 Release Documentation) |
| **Tailwind CSS** | 4.x | Node / Vite CLI | Laravel 12 Blade Templates | **Verified** (Tailwind & Laravel Vite Integration) |
| **MySQL** | 8.0+ | N/A | PDO MySQL Extension on PHP 8.3 | **Verified** |

### Hasil Verifikasi Konflik:
- **Filament 5 & Livewire 4:** Filament 5 dirilis resmi dengan tujuan utama mengadopsi dan mendukung penuh arsitektur **Livewire 4**.
- **Filament 5 & Laravel 12:** Kompatibel penuh tanpa konflik dependensi.
- **PHP 8.3 Compatibility:** Seluruh paket inti (Laravel 12, Filament 5, Livewire 4) mendukung penuh PHP 8.3.30.

---

## 4. Laravel Version Decision (Laravel 12 vs Laravel 13)

### 4.1. Analisis Perbandingan

| Parameter Evaluasi | Laravel 12.x | Laravel 13.x |
|---|---|---|
| **PHP Requirement** | PHP 8.2 – 8.5 | PHP 8.3 – 8.5 (Hard Minimum PHP 8.3) |
| **Local Compatibility** | Sangat Kompatibel (PHP 8.3.30) | Kompatibel (PHP 8.3.30) |
| **Shared Hosting Compatibility** | **Sangat Tinggi** (Mendukung hosting dengan PHP 8.2 maupun 8.3) | **Moderat / Restriktif** (Gagal total jika hosting klien tertahan di PHP 8.2) |
| **Filament 5 Compatibility** | Terbukti Stabil & Matang | Baru / Tahap Awal Siklus Hidup |
| **Siklus Dukungan Keamanan** | Aktif hingga Februari 2027 | Aktif hingga Maret 2028 |
| **Kebutuhan Sistem ANS** | Company Profile & Katalog (Bukan aplikasi enterprise kompleks) | Fitur enterprise baru tidak esensial untuk katalog ANS |

### 4.2. Keputusan Akhir: **Laravel 12.x**
**Alasan Keputusan:**
1. **Shared Hosting Resilience:** Mengingat spesifikasi pasti server shared hosting/cPanel klien belum dikonfigurasi, Laravel 12 memberikan fleksibilitas operasional yang lebih aman karena mendukung runtime PHP 8.2 maupun PHP 8.3.
2. **Stabilitas Ekosistem Filament 5:** Filament 5 memiliki kompatibilitas yang teruji dan stabil di atas Laravel 12.
3. **Prinsip Proyek:** Sesuai prinsip *"Simple enough for shared hosting, structured enough for long-term maintenance"*, kita tidak mengejar versi framework paling baru jika versi tersebut mempersempit toleransi lingkungan hosting tanpa memberikan keuntungan fungsional nyata bagi website katalog ANS.

---

## 5. Filament Version Decision

### Keputusan Akhir: **Filament 5.x (Livewire 4)**

**Alasan Keputusan:**
1. **Standar Generasi Terbaru:** Filament 5.x adalah standar modern Filament yang memanfaatkan performa dan efisiensi Livewire 4.
2. **Kesesuaian dengan Aturan Proyek:** Selaras dengan aturan teknologi proyek (`technology-standards.md`) yang mewajibkan penggunaan Filament 5.x secara eksklusif.

---

## 6. Filament Version Policy

Kebijakan berikut bersifat mengikat dan wajib dipatuhi selama seluruh siklus perancangan dan implementasi:

1. **Filament 5.x ONLY:**
   - Dilarang membuat kode atau form schema berbasis Filament 3.x atau Filament 4.x.
   - Dilarang menggunakan API usang (*deprecated*) dari versi lama.
2. **Single Source of Truth:**
   - File `composer.json` dan `composer.lock` adalah satu-satunya sumber kebenaran terkait versi aktual yang terpasang.
   - Dokumentasi resmi Filament 5 adalah satu-satunya rujukan sah untuk API syntax, Form Components, Table Columns, Actions, dan Resource Pages.
3. **No Downgrading:**
   - Dilarang melakukan downgrade ke Filament 3/4 dengan alasan apa pun.
4. **Verifikasi Sebelum Menulis Kode:**
   - Jika terdapat keraguan mengenai method signature atau perubahan API pada Filament 5, lakukan verifikasi terhadap dokumentasi resmi sebelum menghasilkan kode.

---

## 7. AI Development & Version Verification Policy

Untuk memastikan pengembangan berbasis AI Agent berjalan akurat dan bebas halusinasi API:

1. **Pemeriksaan Versi Dependensi Aktual:**
   - Agent wajib memeriksa file `composer.json` sebelum menulis atau mengubah kode backend.
2. **Verifikasi Dokumentasi Resmi:**
   - Jangan mengandalkan memori internal model jika menyangkut API Filament 5 yang spesifik. Gunakan web search atau dokumentasi resmi untuk memvalidasi syntax.
3. **Larangan Adaptasi Kode Usang:**
   - Jangan menyalin/mengadaptasi *boilerplate* dari repositori publik yang masih menggunakan Filament 3 atau 4 tanpa penyesuaian ke Filament 5.
4. **Prinsip "Stop & Verify":**
   - Jika ditemukan kegagalan pemanggilan method atau perbedaan API pada Filament 5, Agent wajib berhenti, mencari referensi resmi Filament 5, dan menyesuaikan kode sesuai standar v5 yang benar.

---

## 8. Laravel Boost Evaluation

### 8.1. Apa itu Laravel Boost?
Laravel Boost adalah perkakas resmi dari tim Laravel yang bertindak sebagai **Model Context Protocol (MCP) server** untuk menghubungkan AI coding agent secara aman ke aplikasi Laravel lokal.

### 8.2. Evaluasi Manfaat untuk Proyek ANS
1. **MCP Tools untuk Agent:** Menyediakan lebih dari 15 tools terintegrasi untuk membaca log, memeriksa skema database, menjalankan query, dan mengevaluasi Artisan/Tinker tanpa manipulasi manual yang rentan salah.
2. **Native Filament 5 Support:** Menyediakan *Context-Aware AI Guidelines* khusus untuk Filament (termasuk v5), membantu AI menghasilkan kode Resource, Form Builder, dan Table Schema yang sesuai konvensi resmi.
3. **Semantic Documentation Search:** Mengintegrasikan basis pengetahuan resmi ekosistem Laravel secara real-time.

### 8.3. Rekomendasi & Batasan Penggunaan
- **Rekomendasi:** **Direkomendasikan** untuk dipasang sebagai development tool saat inisialisasi framework nanti.
- **Scope Penggunaan:** **Development Dependency ONLY (`require-dev`).**
- **Non-Runtime Constraint:** Laravel Boost murni merupakan perkakas lokal developer/AI dan **TIDAK AKAN** dipasang pada environment production shared hosting (`composer install --no-dev`).

---

## 9. Shared Hosting Compatibility Matrix

| Parameter / Layanan | Dukungan di Shared Hosting | Solusi Arsitektur ANS |
|---|---|---|
| **PHP Runtime** | PHP 8.2 / 8.3 via cPanel MultiPHP | Mengunci Laravel 12 yang kompatibel dengan PHP 8.2 & 8.3. |
| **Web Server** | Apache (`.htaccess`) / LiteSpeed | File `.htaccess` standar Laravel untuk URL rewriting ke `public_html`. |
| **Database** | MySQL via cPanel MySQL Wizard | Skema standar InnoDB, UTF8mb4, migrasi native Laravel. |
| **File Storage** | Local Storage (`storage/app/public`) | Symlink publik tanpa ketergantungan AWS S3. |
| **Background Worker** | Tidak Tersedia / Dibatasi | Pengiriman email langsung via SMTP (Try-Catch + DB First); tanpa Redis/Horizon. |
| **Task Scheduling** | cPanel Cron Job | 1 baris cron standar `php artisan schedule:run` (jika diperlukan). |

---

## 10. Final Locked Baseline

Berikut adalah spesifikasi baseline teknologi resmi yang terkunci untuk proyek website PT Abhipraya Nawasena Sejahtera:

```
================================================================================
FINAL LOCKED TECHNOLOGY BASELINE - PT ABHIPRAYA NAWASENA SEJAHTERA (ANS)
================================================================================
- Local PHP Target      : PHP 8.3.30 (PHP 8.3.x)
- Hosting PHP Range     : PHP 8.2 – 8.3+
- Backend Framework     : Laravel 12.x
- Admin Panel CMS       : Filament 5.x (STRICT POLICY: v5.x ONLY)
- Fullstack Component   : Livewire 4.x
- Frontend Templating   : Laravel Blade
- Frontend Styling      : Tailwind CSS 4.x
- Micro-interactivity   : Alpine.js (minimal)
- Database Engine       : MySQL 8.0+ (InnoDB, UTF8mb4, Native JSON Support)
- Filesystem Storage    : Laravel Local Public Filesystem (`storage/app/public`)
- Mail Protocol         : SMTP (Direct synchronous with DB persistence first)
- Dev Tooling (AI)      : Laravel Boost (Development-only MCP server)
- Testing Framework     : PHPUnit (Standard Laravel feature & unit test suite)
================================================================================
```

---

## 11. Verification Sources

Data verifikasi pada dokumen ini dirujuk dari sumber resmi per **16 Agustus 2026**:
1. **Laravel Releases & Support Lifecycle:** [laravel.com/docs/releases](https://laravel.com)
2. **Filament 5 Announcement & Documentation:** [filamentphp.com](https://filamentphp.com)
3. **Packagist Official Package Metadata:** [packagist.org/packages/filament/filament](https://packagist.org/packages/filament/filament)
4. **Laravel Boost MCP Documentation:** [github.com/laravel/boost](https://github.com/laravel/boost)
5. **Livewire 4 Compatibility Guide:** [livewire.laravel.com](https://livewire.laravel.com)

---

*(Dokumen Technology Baseline selesai dan telah dikunci. Tidak ada kode aplikasi, migrasi, atau composer installation yang dijalankan pada tahap ini. Menunggu review Anda.)*
