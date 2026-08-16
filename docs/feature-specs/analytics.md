# Feature Specification: Analytics & Behavioral Measurement

**Feature ID:** `SPEC-09-ANALYTICS`  
**Feature Name:** Analytics & Behavioral Measurement  
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
10. [docs/feature-specs/public-product-detail-experience.md](file:///c:/laragon/www/avenasa/docs/feature-specs/public-product-detail-experience.md)  
**Status Dokumen:** Ready for Final Review

---

## 1. Analytics Architecture & System Boundary

Spesifikasi ini menetapkan **Google Analytics 4 (GA4)** sebagai platform pengukuran perilaku pengguna (*behavioral analytics*) resmi pada website publik PT Abhipraya Nawasena Sejahtera (ANS).

### 1.1. Pemisahan Tegas: Data Bisnis vs. Data Perilaku
Sistem menerapkan pemisahan batas tanggung jawab (*system boundary*) yang ketat:

```
+-------------------------------------------------------------------+
|                        PENGUNJUNG WEBSITE                         |
+---------------------------------+---------------------------------+
|                                 |                                 |
| [Interaksi / Formulir / Aksi]  | [Kunjungan Halaman & Event]     |
|                ↓                |                ↓                |
| +-----------------------------+ | +-----------------------------+ |
| |     APLIKASI LARAVEL        | | |      GOOGLE ANALYTICS 4     | |
| |    Single Source of Truth   | | |      (Client-Side gtag)     | |
| |                             | | |                             | |
| | - Database MySQL            | | | - Agregasi Traffic & Sumber | |
| | - Record Quotation Resmi    | | | - Metrik Engagement & Sesi  | |
| | - Transaksi Master Katalog  | | | - Konversi / Key Event      | |
| | - Data Pribadi Aman (PII)   | | | - STRICT NO-PII POLICY      | |
| +-----------------------------+ | +-----------------------------+ |
+---------------------------------+---------------------------------+
```

1. **Business & Operational Data (Laravel + MySQL):**
   - MySQL adalah **Single Source of Truth** untuk seluruh data operasional bisnis: data katalog produk, kategori, brand, profil perusahaan, dan data lengkap formulir quotation/inquiry (termasuk nama pengirim, email, telepon, dan isi kebutuhan).
   - Seluruh data transaksi bisnis dikelola secara eksklusif oleh aplikasi Laravel.
2. **Behavioral Data (Google Analytics 4):**
   - GA4 digunakan murni sebagai instrumen pengukuran perilaku pengunjung secara agregat: asal lalu lintas (*traffic acquisition*), keterlibatan (*user engagement*), interaksi katalog, dan efektivitas konversi prospek B2B.
   - **GA4 BUKAN DATABASE BISNIS, BUKAN CRM, DAN BUKAN PENGGANTI DATABASE APLIKASI.**
3. **Kompatibilitas Shared Hosting Mutlak:**
   - Seluruh instrumen pelacakan berjalan di sisi klien (*client-side gtag.js*).
   - Arsitektur analitik **tidak membutuhkan** background daemon, Redis, queue worker, Elasticsearch, Node.js production runtime, atau database analitik lokal di server shared hosting cPanel.

---

## 2. GA4 Ownership, Property & Implementation Boundary

1. **Kepemilikan Properti (*Property Ownership*):**
   - Properti Google Analytics 4 adalah aset resmi milik PT Abhipraya Nawasena Sejahtera.
   - Website hanya bertindak sebagai pengirim sinyal data perilaku terstandarisasi. Pengembang dilarang mendaftarkan akun pribadi sebagai pemilik properti produksi jangka panjang.
2. **Pemisahan Konfigurasi Lingkungan (*Environment Strategy*):**
   - **Measurement ID (`GA_MEASUREMENT_ID`):** Disimpan secara terisolasi pada configuration layer (`config('services.google.analytics_id')` bersumber dari `.env`), tidak di-hardcode pada template Blade.
   - **Local / Development Environment:**
     - GA4 secara default **NONAKTIF** di lingkungan lokal (`APP_ENV=local` atau saat `GA_MEASUREMENT_ID` kosong/null).
     - Hal ini mencegah pencemaran data pengujian lokal (*test pollution*) ke properti produksi klien.
     - Pengujian analitik lokal hanya dapat dilakukan jika developer secara eksplisit mengisi Measurement ID khusus pengujian (*Staging/Test Stream*).
   - **Production Environment:**
     - GA4 aktif secara otomatis saat `GA_MEASUREMENT_ID` (format `G-XXXXXXXXXX`) terkonfigurasi pada lingkungan produksi.
3. **Batas Implementasi (*Implementation Boundary*):**
   - Script integrasi terpusat pada master layout publik Blade (`resources/views/layouts/app.blade.php`), tepat di dalam tag `<head>` dan tidak diduplikasi manual pada setiap halaman.
   - Menggunakan library resmi Google tag (`gtag.js`).
   - Tidak menggunakan third-party Laravel analytics package yang membebani dependensi runtime atau server.
   - **Tidak ada tabel database lokal:** Sistem **tidak membuat** tabel `page_views`, `visitors`, atau `analytics_logs` di MySQL.

---

## 3. GA4 Measurement Classifications & Coverage

Pengukuran GA4 pada website publik ANS diklasifikasikan secara presisi menjadi 3 tingkatan pengukuran:

### 3.1. Automatically Collected Events (Bawaan Otomatis GA4)
Event berikut dikumpulkan secara otomatis oleh infrastruktur dasar GA4 begitu script `gtag.js` aktif pada layout Blade:
- `page_view`: Terpicu saat halaman publik selesai dimuat. Parameter URL dan metadata halaman (`page_location`, `page_title`) dikumpulkan secara otomatis oleh GA4 (bukan custom payload aplikasi). Aplikasi bertanggung jawab memastikan path URL bersih dari PII.
- `first_visit`: Terpicu saat pengunjung pertama kali membuka website ANS.
- `session_start`: Terpicu saat sesi baru pengunjung dimulai.
- `user_engagement`: Mengukur durasi aktif pengunjung di halaman secara periodik.

### 3.2. Enhanced Measurement Events (Fitur Web Stream GA4)
Event berikut diaktifkan melalui fitur *Enhanced Measurement* pada pengaturan Web Data Stream GA4:
- `scroll`: Terpicu otomatis saat pengunjung menggulir halaman hingga 90% kedalaman vertikal.
- `click` (*outbound click*): Terpicu otomatis saat pengunjung mengklik tautan keluar menuju domain eksternal.
- `file_download`: Terpicu otomatis oleh GA4 saat terjadi klik pada tautan dokumen/file umum (pengukuran teknis generik).

> [!NOTE]
> **Hubungan `file_download` vs. `download_brochure`:**  
> Event `file_download` (Enhanced Measurement) adalah pengukuran teknis generik bawaan GA4 untuk tautan file. Sedangkan event `download_brochure` (Custom Event ANS) adalah pengukuran bisnis kontekstual yang membawa data terstruktur produk (`product_id`, `product_name`, `locale`). Keduanya **BOLEH tercatat bersamaan** untuk satu aksi pengunduhan brosur resmi karena memiliki fungsi pengukuran yang berbeda.

### 3.3. Custom Application Events (Dikelola Eksklusif oleh Aplikasi ANS)
Aplikasi ANS memicu 8 custom business events spesifik untuk menangkap interaksi dan konversi bisnis penting yang tidak dapat dicakup oleh pengukuran standar.

---

## 4. Custom Business Events Contract & Data Minimization

Terdapat 8 Custom Business Events resmi dengan spesifikasi pemicu, batasan PII, dan parameter terstandarisasi:

```
+-------------------------------------------------------------------+
|                  CUSTOM BUSINESS EVENTS SUMMARY                   |
+-------------------------------------------------------------------+
| [Katalog & Produk]     | view_product, product_filter, download_brochure
| [Konversi & Interaksi] | click_whatsapp, start_quotation, submit_quotation (KEY EVENT)
| [Navigasi & Global]    | language_switch, hero_cta_click  |
+-------------------------------------------------------------------+
```

### 4.1. Event: `view_product`
- **Tujuan Bisnis:** Mengukur minat pengunjung terhadap produk individual spesifik di katalog.
- **Pemicu & Timing:** Terpicu tepat 1x saat lembar detail produk publik (`/{locale}/products/{slug}`) selesai dirender di browser pengunjung (SPEC-08).
- **Halaman Sumber:** Halaman Detail Produk (`Public Product Detail Experience`).
- **Pencegahan Duplikasi:** Terikat pada lifecycle load halaman awal; tidak terpicu ulang oleh interaksi internal UI seperti pergantian tab galeri atau scrolling.
- **Status Konversi:** Behavioral Event.
- **Kontrak Parameter:**
  - *Wajib:* `product_id` (numerik ID produk), `product_name` (nama produk terlokalisasi), `locale` (`'id'` | `'en'`).
  - *Opsional:* `category_name`, `brand_name`.
  - *Larangan PII:* Dilarang menyertakan data pengguna atau seluruh raw record database.

### 4.2. Event: `product_filter`
- **Tujuan Bisnis:** Memahami preferensi pencarian dan kebutuhan kategori/prinsipal alat kesehatan oleh calon pembeli.
- **Pemicu & Timing:** Terpicu 1x pasca halaman katalog hasil filter selesai dirender di browser pengunjung saat URL memuat query parameter filter aktif (`category` atau `brand`) (SPEC-06).
- **Halaman Sumber:** Halaman Katalog Produk (`Public Catalog Experience`).
- **Pencegahan Duplikasi & Noise:** Dipicu murni per navigasi filter yang bermakna (*meaningful GET navigation*). Tidak dipicu saat pengunjung sekadar membuka/menutup drawer filter seluler tanpa menerapkan filter.
- **Status Konversi:** Behavioral Event.
- **Kontrak Parameter:**
  - *Wajib:* `filter_type` (`'category'`, `'brand'`, atau `'combined'`), `locale` (`'id'` | `'en'`).
  - *Opsional:* `category_slug`, `brand_slug`.
  - *Larangan PII:* Dilarang menyertakan search query bebas yang berpotensi memuat nomor telepon/email pengguna.

### 4.3. Event: `download_brochure`
- **Tujuan Bisnis:** Mengukur minat mendalam terhadap spesifikasi teknis produk melalui pengunduhan lembar brosur PDF resmi dengan konteks produk yang jelas.
- **Pemicu & Timing:** Terpicu saat pengunjung mengklik tombol *"Download Brochure"* yang valid pada halaman detail produk (SPEC-08).
- **Halaman Sumber:** Halaman Detail Produk (`SPEC-08`).
- **Pencegahan Duplikasi:** Dipicu per klik aksi unduh brosur yang valid.
- **Status Konversi:** Behavioral Event.
- **Kontrak Parameter:**
  - *Wajib:* `product_id`, `product_name`, `locale`.
  - *Opsional:* `file_format` (default: `'pdf'`).
  - *Larangan PII:* Dilarang menyertakan arbitrary filesystem path atau data pribadi pengunduh.

### 4.4. Event: `click_whatsapp`
- **Tujuan Bisnis:** Mengukur volume interaksi langsung via kanal komunikasi instan resmi ANS.
- **Pemicu & Timing:** Terpicu saat pengunjung mengklik tombol tautan WhatsApp resmi ANS (`https://wa.me/6282261461400`) di footer, halaman kontak, atau bilah aksi produk.
- **Halaman Sumber:** Seluruh halaman publik (Footer, Halaman Kontak, Product Detail Sticky Bar).
- **Pencegahan Duplikasi:** Dipicu per klik tautan eksternal WhatsApp.
- **Status Konversi:** Behavioral Event.
- **Kontrak Parameter:**
  - *Wajib:* `source_page` (misal: `'contact_page'`, `'footer'`, `'product_detail'`), `locale`.
  - *Opsional:* `product_id` (jika diklik dari konteks halaman produk spesifik).
  - *Larangan PII:* DILARANG KERAS menyertakan nomor telepon pengunjung, nama pengunjung, atau isi pesan pre-filled WhatsApp.

### 4.5. Event: `start_quotation`
- **Tujuan Bisnis:** Mengukur inisiasi corong permintaan penawaran harga (*quotation funnel initiation*).
- **Pemicu & Timing:** Memiliki dua jalur trigger yang valid:
  1. *Jalur CTA Produk:* Pengunjung mengklik tombol CTA *"Minta Penawaran"* pada lembar Detail Produk (`SPEC-08`).
  2. *Jalur Kontak Langsung:* Pengunjung membuka halaman Kontak secara langsung tanpa melalui CTA Produk, dan melakukan interaksi bermakna pertama kali dengan field formulir (`focusin` pertama) (`SPEC-04`).
- **Mekanisme Deduplikasi Lintas Halaman (*Cross-Page Journey Deduplication*):**
  - Untuk menjamin **maksimal 1 event `start_quotation` per perjalanan penawaran (*single quotation journey*)**, sistem menggunakan penanda client-side ringan (misal: `sessionStorage.setItem('quotation_journey_started', 'true')`).
  - Jika pengunjung mengklik CTA di Product Detail, event `start_quotation` terkirim dan flag `sessionStorage` diset. Saat berpindah ke halaman Kontak dan memfokuskan field formulir, sistem memeriksa flag tersebut sehingga event **TIDAK DIKIRIM ULANG**.
  - Jika pengunjung langsung membuka halaman Kontak (flag kosong), event `start_quotation` terkirim pada interaksi field pertama dan flag diset.
  - Refresh / reload halaman Kontak tidak memicu ulang event `start_quotation`.
  - Setelah formulir berhasil disubmit (`submit_quotation`), flag `sessionStorage` dibersihkan sehingga inisiasi perjalanan penawaran baru di kemudian waktu dapat memicu `start_quotation` kembali.
- **Halaman Sumber:** Halaman Detail Produk & Halaman Kontak.
- **Status Konversi:** Behavioral Event (Funnel Step 1).
- **Kontrak Parameter:**
  - *Wajib:* `source` (`'product_detail'` | `'contact_page'`), `locale`.
  - *Opsional:* `product_id` (jika membawa initial product context).
  - *Larangan PII:* Dilarang menyertakan karakter teks apapun yang mulai diketik oleh pengguna.

### 4.6. Event: `submit_quotation` (PRIMARY CONVERSION / KEY EVENT)
- **Tujuan Bisnis:** Mengukur keberhasilan konversi prospek B2B utama (*Primary Conversion/Key Event*) di mana calon pembeli resmi mengajukan permintaan penawaran harga.
- **Pemicu & Timing:** **HANYA TERPICU** setelah formulir quotation lolos validasi server-side dan record quotation **BERHASIL TERSIMPAN DI DATABASE MYSQL** (SPEC-04).
- **Aturan Ketahanan SMTP:** Sesuai SPEC-04, jika notifikasi Direct SMTP gagal terkirim namun data sukses tersimpan di MySQL, event `submit_quotation` **TETAP SAH TERPICU** karena transaksi bisnis telah sukses dicatat di database.
- **Larangan Pemicu Prematur:** **DILARANG KERAS** memicu event ini hanya berdasarkan klik tombol submit di browser, submit JavaScript tanpa respon server, atau validasi client-side.
- **Halaman Sumber:** Halaman Kontak pasca submit sukses (di-render via flash session context di Blade).
- **Pencegahan Duplikasi:** Dipicu tepat 1x memanfaatkan session flash `session('quotation_submitted')` yang otomatis hangus setelah halaman sukses dimuat, sehingga refresh halaman tidak memicu event ganda.
- **Status Konversi:** **PRIMARY KEY EVENT (CONVERSION)**.
- **Kontrak Parameter:**
  - *Wajib:* `has_company` (`true` | `false` - menandakan profil institusi), `source` (`'general_inquiry'` | `'product_specific'`), `locale`.
  - *Opsional:* `product_id` (jika terkait produk spesifik).
  - *Larangan PII Mutlak:* **DILARANG KERAS MENYERTAKAN:** `name`, `email`, `phone`, `company_name`, `subject`, `message`, atau identifier pribadi lainnya.

### 4.7. Event: `language_switch`
- **Tujuan Bisnis:** Mengukur penggunaan fitur dwibahasa dan preferensi bahasa audiens target (ID vs EN).
- **Pemicu & Timing:** Terpicu saat pengunjung mengklik tombol pengalih bahasa `ID | EN` pada header/drawer navigasi (SPEC-05 & SPEC-07).
- **Halaman Sumber:** Seluruh halaman publik yang memuat Language Switcher.
- **Pencegahan Duplikasi:** Dipicu per klik aksi pengalihan sebelum navigasi halaman terjadi.
- **Status Konversi:** Behavioral Event.
- **Kontrak Parameter:**
  - *Wajib:* `source_locale` (`'id'` | `'en'`), `target_locale` (`'id'` | `'en'`), `current_path` (murni path URL tanpa query string).
  - *Larangan PII:* Dilarang menyertakan parameter query string yang berpotensi memuat data sensitif.

### 4.8. Event: `hero_cta_click`
- **Tujuan Bisnis:** Mengukur efektivitas daya tarik visual hero banner di beranda terhadap aksi navigasi pengunjung (SPEC-03 & SPEC-07).
- **Pemicu & Timing:** Terpicu saat pengunjung mengklik tombol aksi CTA pada slide Hero Banner aktif di beranda.
- **Halaman Sumber:** Beranda (`/{locale}`).
- **Pencegahan Duplikasi:** Dipicu per klik tombol CTA banner.
- **Status Konversi:** Behavioral Event.
- **Kontrak Parameter:**
  - *Wajib:* `banner_id` (numerik ID banner), `locale`, `cta_type` (tipe aksi tombol), `destination_type` (`'internal_catalog'` | `'internal_page'` | `'external'`).
  - *Larangan PII:* Dilarang menyertakan parameter query string bebas.

---

## 5. Master Event Contract Table

| Nama Event | Kategori Pengukuran GA4 | Kategori Status | Pemicu (Trigger Timing) | Modul Sumber | Parameter Wajib (Aplikasi) | Aturan Privasi & PII |
|---|---|---|---|---|---|---|
| `page_view` | Automatically Collected | Baseline | Halaman publik selesai dimuat | Global Layout | *(Dikumpulkan otomatis oleh GA4)* | Aplikasi dilarang memasukkan PII ke URL |
| `first_visit` | Automatically Collected | Baseline | Kunjungan perdana pengguna baru | Browser Native | *(Dikumpulkan otomatis oleh GA4)* | Telemetri platform standar |
| `session_start` | Automatically Collected | Baseline | Inisiasi sesi browsing baru | Browser Native | *(Dikumpulkan otomatis oleh GA4)* | Telemetri platform standar |
| `user_engagement` | Automatically Collected | Baseline | Aktivitas pengguna periodik di tab | Browser Native | *(Dikumpulkan otomatis oleh GA4)* | Telemetri platform standar |
| `scroll` | Enhanced Measurement | Baseline | Gulir halaman mencapai kedalaman 90% | Browser Native | *(Dikumpulkan otomatis oleh GA4)* | Telemetri platform standar |
| `click` | Enhanced Measurement | Baseline | Klik tautan outbound menuju eksternal | Browser Native | *(Dikumpulkan otomatis oleh GA4)* | Telemetri platform standar |
| `file_download` | Enhanced Measurement | Baseline (Teknis) | Klik tautan unduhan file/dokumen | Browser Native | *(Dikumpulkan otomatis oleh GA4)* | Tanpa path internal filesystem |
| `view_product` | Custom Application Event | Behavioral | Detail produk berhasil dirender | Product Detail | `product_id`, `product_name`, `locale` | Tanpa data pengguna/record mentah |
| `product_filter` | Custom Application Event | Behavioral | Navigasi filter katalog selesai dirender | Catalog | `filter_type`, `locale` | Tanpa query string teks bebas |
| `download_brochure` | Custom Application Event | Behavioral (Bisnis) | Klik unduh brosur PDF valid dimulai | Product Detail | `product_id`, `product_name`, `locale` | Tanpa path internal filesystem |
| `click_whatsapp` | Custom Application Event | Behavioral | Klik tautan chat WhatsApp resmi ANS | Global / Contact / Product | `source_page`, `locale` | Tanpa nomor HP/nama pengunjung |
| `start_quotation` | Custom Application Event | Behavioral (Funnel) | Klik CTA penawaran / fokus input form (1x journey) | Product / Contact | `source`, `locale` | Tanpa teks ketikan pengguna |
| `submit_quotation` | Custom Application Event | **PRIMARY KEY EVENT** | Data quotation **sukses tersimpan di MySQL** | Contact Success | `has_company`, `source`, `locale` | **BEBAS PII 100% (No name, email, msg)** |
| `language_switch` | Custom Application Event | Behavioral | Klik tombol pengalih bahasa ID/EN | Global Header / Drawer | `source_locale`, `target_locale`, `current_path` | Murni path URL tanpa query string |
| `hero_cta_click` | Custom Application Event | Behavioral | Klik tombol CTA Hero Banner aktif | Home (`/{locale}`) | `banner_id`, `locale`, `cta_type`, `destination_type` | Tanpa parameter URL sensitif |

---

## 6. Page & Module Coverage Matrix

Pemetaan ketercakupan instrumen analitik pada seluruh modul publik website ANS:

| Halaman / Modul Publik | Rute Terlokalisasi | Spesifikasi Terkait | Event Analitik yang Berlaku |
|---|---|---|---|
| **Beranda (Home)** | `/{locale}` | SPEC-03, SPEC-07 | `page_view`, `hero_cta_click`, `click_whatsapp`, `language_switch` |
| **Tentang Kami (About)** | `/{locale}/about` | SPEC-02, SPEC-07 | `page_view`, `click_whatsapp`, `language_switch` |
| **Katalog Produk (Catalog)** | `/{locale}/products` | SPEC-01, SPEC-06 | `page_view`, `product_filter`, `click_whatsapp`, `language_switch` |
| **Detail Produk (Product Detail)** | `/{locale}/products/{slug}` | SPEC-01, SPEC-08 | `page_view`, `view_product`, `file_download`, `download_brochure`, `start_quotation`, `click_whatsapp`, `language_switch` |
| **Mitra & Klien (Partners & Clients)** | `/{locale}/partners-clients` | SPEC-02, SPEC-07 | `page_view`, `click_whatsapp`, `language_switch` |
| **Kontak & Quotation Form** | `/{locale}/contact` | SPEC-04, SPEC-07 | `page_view`, `start_quotation`, `submit_quotation` (saat sukses), `click_whatsapp`, `language_switch` |
| **Global Navigation / Footer** | Seluruh Halaman | SPEC-05, SPEC-07 | `language_switch`, `click_whatsapp` |

---

## 7. Privacy, Security & Strict No Direct PII Policy

PT Abhipraya Nawasena Sejahtera menerapkan **Strict No Direct PII Policy (No User-Submitted PII Policy)** yang menjamin bahwa tidak ada data pribadi masukan pengguna yang dikirimkan ke Google Analytics:

1. **Prinsip Larangan Pengiriman Data Pribadi (*No User-Submitted PII*):**
   - **DILARANG KERAS** mengirimkan informasi masukan identitas pribadi pengunjung ke GA4, mencakup:
     - Nama Lengkap Pengunjung;
     - Alamat Email;
     - Nomor Telepon / WhatsApp Pengunjung;
     - Nama Perusahaan/Institusi dalam bentuk string teks bebas (hanya diperbolehkan boolean flag `has_company`);
     - Alamat Fisik Pengunjung;
     - Subjek & Isi Narasi Kebutuhan / Pesan Quotation;
     - Hash data pribadi (SHA256 email/phone);
     - Pengenal finansial atau rekening bank.
2. **Telemetri Bawaan Platform GA4:**
   - Sistem mengakui secara arsitektural bahwa GA4 secara default mengumpulkan data teknis perangkat/browser non-PII standar (seperti tipe peramban, resolusi layar, negara/kota berbasis IP anonim, dan durasi sesi).
3. **Larangan Profiling Individu:**
   - Dilarang menetapkan parameter `user_id` di GA4 menggunakan alamat email, nomor telepon, atau data identitas pribadi lainnya.
4. **Data Minimization:**
   - Parameter event yang dibangun oleh aplikasi dibatasi murni pada pengenal kontekstual non-sensitif (misal: ID numerik produk, nama publik produk yang telah terbit di katalog, kode bahasa `'id'`/`'en'`, tipe tombol aksi).
5. **Isolasi Kredensial:**
   - `GA_MEASUREMENT_ID` adalah pengenal publik stream data (bukan secret key), namun kredensial administratif seperti Google API Service Account atau Private Key dilarang keras disimpan di frontend atau diekspos ke publik.

---

## 8. Resilience & Non-Blocking Failure Architecture

Instrumen analitik dirancang sebagai **auxiliary measurement layer** yang sepenuhnya terisolasi dari alur kerja aplikasi utama:

1. **Non-Blocking Execution:**
   - Jika script `gtag.js` diblokir oleh ad-blocker browser, mengalami network timeout, atau gagal dimuat karena gangguan koneksi, **seluruh fungsionalitas website tetap berjalan 100% normal**.
2. **Kemandirian Transaksi Bisnis:**
   - Penyimpanan formulir quotation ke database MySQL, pengunduhan brosur PDF, peralihan bahasa, dan navigasi katalog tidak boleh mengalami kegagalan (HTTP 500/Crash) akibat kegagalan analitik.
   - Fungsi pembungkus JavaScript analitik wajib menggunakan *safe invocation check* (misal: `if (typeof window.gtag === 'function') { ... }`).
3. **Cookie / Consent Boundary:**
   - Website ANS beroperasi menggunakan konfigurasi first-party cookie standar GA4. Sesuai dokumen URS dan System Design baseline, tidak ada modul third-party consent banner berbayar tambahan yang diperkenalkan pada V1. Jika di masa mendatang terdapat regulasi kepatuhan khusus, mekanisme persetujuan cookie akan diintegrasikan sebagai modul terpisah tanpa merusak baseline SPEC-09.

---

## 9. Testing & Validation Strategy

Validasi kepatuhan analitik dilakukan tanpa dependensi runtime pihak ketiga melalui prosedur pengujian terstruktur:

1. **Pengujian Isolasi Lingkungan (*Environment Isolation Testing*):**
   - Memastikan saat `APP_ENV=local` dan `GA_MEASUREMENT_ID` kosong, tidak ada network request yang dikirim ke endpoint `google-analytics.com/g/collect`.
2. **Validasi GA4 DebugView & Browser Network Inspection:**
   - Memverifikasi pengiriman event melalui GA4 DebugView / Google Analytics Debugger Extension di browser untuk seluruh custom event dan event otomatis.
3. **Pengujian Khusus Deduplikasi `start_quotation`:**
   - Menguji transisi CTA Product Detail → Contact Form untuk memastikan hanya ada 1 event `start_quotation` yang tercatat.
   - Menguji interaksi langsung di halaman Contact untuk memastikan event terpicu pada field pertama.
   - Menguji reload/refresh halaman Contact untuk memastikan tidak ada event duplikat.
4. **Verifikasi Koeksistensi `file_download` dan `download_brochure`:**
   - Memastikan bahwa saat mengklik unduh brosur PDF, event teknis `file_download` dan event bisnis `download_brochure` dapat muncul bersamaan tanpa dianggap sebagai bug duplikasi.
5. **Audit Ketiadaan PII (*Zero User-Submitted PII Audit*):**
   - Memeriksa seluruh query payload pada tab *Network* browser (parameter `ep.*`) untuk memastikan tidak ada string nama pengguna, email, nomor HP, atau isi pesan formulir yang bocor ke payload analitik.
6. **Pengujian Non-Blocking (Ad-Blocker Simulation):**
   - Mengaktifkan ad-blocker atau memutus koneksi script Google: memverifikasi bahwa pengiriman formulir quotation dan unduhan brosur tetap berhasil 100%.

---

## 10. Acceptance Criteria (Format Given / When / Then)

### AC-01: Isolasi GA4 pada Lingkungan Lokal (Local Inactive)
- **Given:** Website berjalan pada lingkungan lokal (`APP_ENV=local`) dengan konfigurasi `GA_MEASUREMENT_ID` kosong.
- **When:** Pengunjung membuka halaman beranda `/id`.
- **Then:** Script pelacakan GA4 tidak dimuat di HTML dan tidak ada request analitik yang dikirim ke Google Analytics.

### AC-02: Aktivasi GA4 pada Lingkungan Produksi
- **Given:** Website berjalan pada lingkungan produksi dengan `GA_MEASUREMENT_ID = 'G-XXXXXXXXXX'`.
- **When:** Pengunjung membuka halaman beranda `/id`.
- **Then:** Script Google tag dimuat dengan ID resmi klien dan stream data aktif.

### AC-03: Pengukuran Standar `page_view` Otomatis
- **Given:** Pengunjung menavigasi ke halaman publik manapun (misal: `/id/about`).
- **When:** Halaman selesai dimuat.
- **Then:** Event `page_view` tercatat secara otomatis di GA4 dengan path URL bersih tanpa PII.

### AC-04: Pelacakan Event `view_product`
- **Given:** Pengunjung membuka halaman detail produk `/id/products/alat-pcr-real-time`.
- **When:** Halaman detail produk selesai dirender.
- **Then:** Event `view_product` terkirim ke GA4 memuat `product_id`, `product_name`, dan `locale` tanpa data identitas pengunjung.

### AC-05: Pelacakan Event `product_filter`
- **Given:** Pengunjung membuka halaman katalog dengan parameter filter `/id/products?category=mikrobiologi`.
- **When:** Halaman hasil filter selesai dirender.
- **Then:** Event `product_filter` terkirim 1x dengan `filter_type = 'category'`, `category_slug = 'mikrobiologi'`, dan `locale = 'id'`.

### AC-06: Pelacakan Event `download_brochure` & Koeksistensi `file_download`
- **Given:** Pengunjung berada di halaman produk yang memiliki brosur PDF valid.
- **When:** Pengunjung mengklik tombol unduh brosur.
- **Then:** Event kontekstual bisnis `download_brochure` terkirim memuat `product_id`, `product_name`, dan `locale`, serta event bawaan `file_download` dapat tercatat secara berdampingan tanpa dianggap sebagai duplikasi bug.

### AC-07: Pelacakan Event `click_whatsapp`
- **Given:** Pengunjung mengklik tautan chat WhatsApp resmi ANS di footer atau halaman kontak.
- **When:** Aksi klik dieksekusi.
- **Then:** Event `click_whatsapp` terkirim memuat `source_page` dan `locale` tanpa mengirim nomor HP pengunjung.

### AC-08: Pelacakan & Deduplikasi `start_quotation` dari CTA Produk
- **Given:** Pengunjung mengklik tombol CTA *"Minta Penawaran"* pada halaman Detail Produk.
- **When:** Pengunjung berpindah ke halaman Kontak dan memfokuskan field formulir pertama.
- **Then:** Tepat satu event `start_quotation` tercatat untuk seluruh perjalanan penawaran tersebut (*single journey*), dan interaksi field formulir tidak memicu event ganda.

### AC-09: Pelacakan `start_quotation` dari Kunjungan Langsung ke Kontak
- **Given:** Pengunjung langsung membuka halaman Kontak tanpa melalui CTA Detail Produk.
- **When:** Pengunjung pertama kali memfokuskan atau berinteraksi dengan field formulir kontak.
- **Then:** Tepat satu event `start_quotation` terkirim dengan `source = 'contact_page'`.

### AC-10: Pencegahan Duplikasi `start_quotation` pada Reload Halaman
- **Given:** Pengunjung telah memulai perjalanan penawaran dan berada di halaman Kontak.
- **When:** Pengunjung melakukan reload / refresh halaman browser.
- **Then:** Event `start_quotation` **TIDAK DIKIRIM ULANG** untuk perjalanan penawaran yang sama.

### AC-11: Pelacakan Transaksi Sukses `submit_quotation`
- **Given:** Pengunjung mengisi formulir quotation dengan data valid.
- **When:** Form disubmit dan server sukses menyimpan record ke tabel `quotations` MySQL.
- **Then:** Event `submit_quotation` terkirim ke GA4 dengan parameter `has_company`, `source`, dan `locale`.

### AC-12: Penegasan `submit_quotation` sebagai Primary Key Event
- **Given:** Event `submit_quotation` terdaftar pada GA4 Property.
- **When:** Pengukuran konversi dievaluasi.
- **Then:** Event `submit_quotation` ditetapkan sebagai **Key Event (Conversion)** utama corong bisnis website ANS.

### AC-13: Pelacakan Event `language_switch`
- **Given:** Pengunjung berada di halaman `/id/about`.
- **When:** Pengunjung mengklik tombol switcher "EN".
- **Then:** Event `language_switch` terkirim memuat `source_locale = 'id'`, `target_locale = 'en'`, dan `current_path = '/id/about'`.

### AC-14: Pelacakan Event `hero_cta_click`
- **Given:** Pengunjung berada di beranda `/id`.
- **When:** Pengunjung mengklik tombol CTA pada Hero Banner aktif.
- **Then:** Event `hero_cta_click` terkirim memuat `banner_id`, `locale`, `cta_type`, dan `destination_type`.

### AC-15: Kepatuhan Mutlak Bebas Direct PII (No User-Submitted PII)
- **Given:** Pengunjung mengirimkan formulir penawaran dengan data pribadi lengkap (nama, email, telepon, isi pesan).
- **When:** Seluruh event analitik terkirim ke GA4.
- **Then:** Tidak ada satu pun parameter payload custom event GA4 yang memuat string nama, email, nomor telepon, atau isi pesan pengirim.

### AC-16: Ketahanan Sistem saat Kegagalan Analitik (Non-Blocking Failure)
- **Given:** Script GA4 terblokir oleh ad-blocker atau koneksi internet pengguna mengalami gangguan analitik.
- **When:** Pengunjung melakukan pengiriman formulir quotation atau mengunduh brosur.
- **Then:** Website berfungsi normal tanpa error JavaScript, record quotation tetap tersimpan sukses di database MySQL, dan pesan sukses tampil di layar.

### AC-17: Ketiadaan Tabel Analitik Lokal di Database
- **Given:** Struktur skema database MySQL dievaluasi.
- **When:** Daftar tabel diperiksa.
- **Then:** Tidak terdapat tabel database lokal untuk log kunjungan (`page_views`, `visitors`, `analytics_logs`).

### AC-18: Pencegahan Event Ganda pada Siklus Render `submit_quotation`
- **Given:** Halaman sukses submit quotation dimuat ulang (*refresh browser*).
- **When:** Halaman dirender kembali.
- **Then:** Event `submit_quotation` **TIDAK TERPICU KEDUA KALI** karena session flash context telah hangus.

### AC-19: Pencegahan Pencemaran Data Pengujian Lokal
- **Given:** Pengembang melakukan testing manual di mesin lokal development.
- **When:** Seluruh alur website dijalankan di local.
- **Then:** Data aktivitas lokal tidak masuk ke stream produksi GA4 milik klien.

### AC-20: Verifikasi Event Melalui GA4 DebugView
- **Given:** Website diuji menggunakan mode debugging.
- **When:** Seluruh 8 custom event dieksekusi secara berurutan.
- **Then:** Seluruh event tercatat secara akurat di GA4 DebugView sesuai skema kontrak parameter yang telah ditetapkan.

---

## 11. Security & Privacy Audit Summary

- **Audit PII (No User-Submitted PII):** **CLEARED (STRICT NO DIRECT PII)**. Seluruh 8 custom event bebas dari transmisi nama, email, telepon, dan isi pesan masukan pengguna.
- **Audit Data Minimization:** **CLEARED**. Parameter kustom dibatasi hanya pada pengenal kontekstual non-sensitif yang diperlukan untuk analisis tren bisnis.
- **Audit Kredensial:** **CLEARED**. Tidak ada secret key atau token Google API yang diekspos ke browser.
- **Audit Isolasi Lingkungan:** **CLEARED**. Lingkungan lokal terisolasi penuh dari stream data produksi klien.

---

## 12. Out of Scope

Hal-hal berikut secara tegas dinyatakan **DI LUAR CAKUPAN (OUT OF SCOPE)** untuk modul Analytics V1:
- Otomasi konfigurasi Google Analytics Admin via API.
- Integrasi BigQuery Export atau pembangunan custom data warehouse eksternal.
- Pelacakan sisi server (*Server-Side Tagging / Measurement Protocol*) yang membutuhkan server proxy tambahan.
- Integrasi data analytics ke platform CRM pihak ketiga berbayar (Salesforce, HubSpot).
- Dashboard analitik grafis kustom di dalam panel admin Filament (pelaporan analitik diakses langsung melalui dashboard resmi GA4 oleh tim ANS).
- Profiling individu pengunjung atau identifikasi lintas sistem (*cross-device visitor identity stitching*).
- Transmisi data pribadi pengguna dalam bentuk apapun ke Google Analytics.

---

## 13. Architecture Consistency Notes

- **Architecture Consistency Audit: PASS**
- **Konsistensi dengan Technology Baseline:** Kompatibel 100% dengan shared hosting cPanel, tanpa background worker daemon, Redis, Elasticsearch, atau Node.js runtime di server produksi.
- **Konsistensi dengan System Design & URS:** Mengimplementasikan secara presisi bab 14 Behavioral Analytics & GA4 Strategy serta kebutuhan pelaporan URS ANS 2026.
- **Konsistensi Klasifikasi Event GA4:** Memisahkan secara tegas *Automatically Collected Events*, *Enhanced Measurement Events*, dan *Custom Application Events*.
- **Konsistensi `start_quotation` Deduplication:** Menjamin tepat 1 event per quotation journey melalui mekanisme `sessionStorage` client-side tanpa membebani session server.
- **Konsistensi Koeksistensi Download:** `file_download` generik dan `download_brochure` kontekstual berdampingan secara harmonis.
- **Konsistensi dengan SPEC-03, SPEC-04, SPEC-05, SPEC-06, SPEC-07, SPEC-08:** Seluruh titik pemicu kustom terhubung presisi dan konsisten 100%.

---

*(Feature Specification Analytics & Behavioral Measurement telah selesai direvisi dan berstatus Ready for Final Review sebelum dikunci untuk implementasi.)*
