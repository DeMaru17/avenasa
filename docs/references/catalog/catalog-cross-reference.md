# Catalog Cross-Reference
## PT Abhipraya Nawasena Sejahtera (ANS)

**Document ID:** `REF-03-CATALOG-CROSS-REFERENCE`  
**Milestone:** Phase 2 — Milestone 2.3.1 (Content Source & Legacy Cross-Reference Audit)  
**Status:** Official Reference Document — Schema-Aware & Read-Only (Refined)  
**Date:** August 2026  

---

### 1. Purpose

Dokumen ini merupakan hasil audit komprehensif, rekonsiliasi data, dan pemetaan silang (*cross-reference*) antara sumber data resmi terkini (**Current Official Sources**) dan data aset lama (**Legacy Reference Data**) PT Abhipraya Nawasena Sejahtera (ANS).

Tujuan utama Milestone 2.3.1:
1. Memetakan seluruh data konten bisnis dan katalog produk ke skema database MySQL yang **SUDAH ADA** tanpa mengubah skema atau membuat migrasi baru.
2. Mengidentifikasi status keabsahan data menggunakan pemisahan dua dimensi: **Current Source Status** (keabsahan pada katalog terkini) dan **Legacy Relationship** (hubungan historis dengan data lama).
3. Menetapkan aturan granularitas produk (*Product Granularity Rule*) untuk membedakan antara entri penawaran tingkat katalog (*catalog-level offering/family*) dan penyimpanan nomor model/artikel terstruktur.
4. Menyediakan inventaris nomor model, artikel, dan kode pemesanan (*model/article/order numbers*) untuk disimpan sebagai atribut terstruktur pada kolom `specifications` JSON.
5. Menjadi dasar acuan tunggal (*single source of truth*) yang siap pakai sebelum eksekusi seeding pada Milestone 2.3.2 (Core Data Seeding), 2.3.3 (Product Seeding), dan 2.3.4 (Asset & Media Alignment).

---

### 2. Source Hierarchy & Non-Review Boundaries

Hierarki prioritas pembuktian data ditetapkan secara ketat:

1. **Current Official Product Catalogue PDF (`catalog-ans.pdf` / 64 halaman):** Sumber kebenaran utama untuk spesifikasi teknis, nomor model, artikel/order code, lini produk, dan foto katalog produk aktif.
2. **Official Company Profile PDF (`Company Profile ANS 2026 Rev.02.pdf` / 13 halaman):** Sumber kebenaran utama untuk identitas perusahaan, visi (Halaman 4), 4 poin misi (Halaman 4), 6 core values (Halaman 5), 3 founder/manajemen (Halaman 2), portfolio bisnis (Halaman 6), daftar principal resmi (Halaman 7–9), daftar pelanggan/klien (Halaman 11–12), dan informasi kontak operasional (Halaman 13).
3. **Structured Current Catalogue Inventory (`catalog-inventory.md`):** Dokumen ekstraksi terstruktur famili produk dan kode artikel dari katalog dan profil perusahaan.
4. **Legacy Product Data (`products.json` / 306 baris):** Sumber referensi historis untuk ide deskripsi pemasaran, positioning pasar, fitur unggulan, dan identifikasi produk lama.

> [!IMPORTANT]
> **Batasan Audit & Scope Boundary:**
> - File `docs/references/existing-assets/legacy-data/resources.json` secara ketat **TIDAK DIREVIEW** atau dianalisis karena berkaitan dengan fitur newsletter/artikel legacy yang belum masuk scope implementasi saat ini.
> - Jika terjadi perbedaan data, **Current Official Sources selalu menang**.
> - Data legacy hanya berfungsi sebagai bahan referensi historis dan tidak boleh mengalahkan katalog resmi terkini.
> - Data legacy yang tidak ditemukan pada katalog terkini ditandai `LEGACY ONLY` (*"Tidak ditemukan pada katalog terbaru — memerlukan verifikasi"*), dan tidak diasumsikan discontinued tanpa konfirmasi resmi dari klien.

---

### 3. Database Schema Context & Media Nullability Rules

Audit dilakukan secara sadar-skema (*schema-aware*) terhadap 8 tabel bisnis yang telah dibangun pada Milestone 2.1:

| Dataset / Entity | Target Tabel | Kolom yang Tersedia pada Skema | Batasan & Aturan Skema |
|---|---|---|---|
| **Company Profile** | `company_profiles` | `tagline_id`, `tagline_en`, `about_id`, `about_en`, `vision_id`, `vision_en`, `mission_id`, `mission_en`, `address`, `phone`, `whatsapp`, `email`, `maps_embed_url` | Singleton record (1 baris data). Tidak ada kolom sosial media kustom terpisah. |
| **Core Values** | `core_values` | `title_id`, `title_en`, `description_id`, `description_en`, `icon_name`, `sort_order`, `is_active` | Tepat 6 nilai inti resmi. Ikon dipetakan ke 46 curated Heroicons lokal. |
| **Management** | `managements` | `name`, `position_id`, `position_en`, `bio_id`, `bio_en`, `photo_path`, `sort_order`, `is_active` | Tepat 3 founder/pimpinan. Kolom `photo_path` opsional (`nullable`). |
| **Categories** | `categories` | `name_id`, `name_en`, `slug_id`, `slug_en`, `sort_order`, `is_active` | Single-level flat taxonomy. **Tidak ada kolom `description`**. Auto-slug dikelola backend. |
| **Brands / Principals** | `brands` | `name`, `slug`, `logo_path`, `website_url`, `description_id`, `description_en`, `is_new_principal`, `sort_order`, `is_active` | **Tidak ada tabel `principals` terpisah**. Entitas principal direpresentasikan melalui tabel `brands`. Kolom `logo_path` opsional (`nullable`). Flag `is_new_principal = true` khusus untuk Era Biology. |
| **Products** | `products` | `category_id`, `brand_id`, `name_id`, `name_en`, `slug_id`, `slug_en`, `summary_id`, `summary_en`, `description_id`, `description_en`, `specifications`, `primary_image_path`, `brochure_path`, `is_featured`, `is_active`, `sort_order` | **Tidak ada kolom dedicated `model_number` / `article_number`**. Seluruh nomor model/artikel wajib disimpan di kolom `specifications` (JSON array). Kolom `primary_image_path` dan `brochure_path` opsional (`nullable`). |
| **Product Images** | `product_images` | `product_id`, `image_path`, `caption_id`, `caption_en`, `sort_order` | Galeri multi-foto pendukung per produk. Record hanya dibuat jika file media fisik benar-benar ada. |
| **Clients** | `clients` | `name`, `logo_path`, `sort_order`, `is_active` | Mitra/pelanggan korporat (bukan principal). Kolom `logo_path` opsional (`nullable`). |

> [!NOTE]
> **Aturan Media / Aset Gambar (Media Nullability Rule):**
> - Belum tersedianya aset media fisik (foto produk, brosur PDF, logo brand/klien, foto manajemen) adalah kondisi yang **disengaja**.
> - Sistem **DILARANG KERAS** membuat nama file palsu (*fake/dummy filename*), path perkiraan, atau placeholder image semu.
> - Pada initial seeding (Milestone 2.3.2 & 2.3.3), seluruh field media (`primary_image_path`, `brochure_path`, `logo_path`, `photo_path`) **dibiarkan bernilai `NULL`**.
> - Seluruh aset media fisik akan diunggah dan ditautkan secara manual/terverifikasi melalui Filament CMS pada Milestone 2.3.4 setelah aset resmi disetujui klien.

---

### 4. Product Granularity Rule (Aturan Granularitas Produk)

Dalam arsitektur database dan CMS PT Abhipraya Nawasena Sejahtera, aturan granularitas record produk ditetapkan sebagai berikut:

1. **Definisi Satu Record Produk:**  
   Satu record pada tabel `products` merepresentasikan satu produk mandiri atau satu kelompok famili produk (*product family*) yang dipresentasikan sebagai **satu penawaran tingkat katalog (*catalog-level offering*)** pada katalog resmi ANS.
2. **Penanganan Multi-Model & Multi-Varian:**  
   Jika sebuah katalog menyajikan beberapa varian model, nomor artikel, atau kode pemesanan dalam satu famili produk yang sama (misalnya: Lovibond Photometer seri MD 100/110/200, DLAB Spektrofotometer SP-V1000/SP-UV1000, atau Terragene Biological Indicators seri BT224/BT222/BT96), varian tersebut **TIDAK** dipecah menjadi puluhan database record terpisah, melainkan dihimpun ke dalam satu record produk induk dengan rincian model disimpan pada kolom `specifications` JSON.
3. **Interpretasi Jumlah Entri Katalog:**  
   Keberadaan 59 entri pada inventaris katalog (`catalog-ans.pdf`) adalah representasi 59 famili/entri penawaran katalog, bukan berarti tepat 59 individual SKU terisolasi.

---

### 5. Definisi Dimensi Status Audit

Untuk mencegah ambiguitas status pada hasil cross-reference, audit menggunakan dua dimensi status independen:

#### Dimensi 1: Current Source Status (Status Sumber Terkini)
- **CURRENT CONFIRMED:** Produk, nama famili, dan spesifikasi terbukti didukung langsung dan jelas oleh `catalog-ans.pdf` atau `Company Profile ANS 2026 Rev.02.pdf`.
- **CURRENT PARTIAL:** Famili produk jelas pada katalog, namun tabel model/artikel atau rincian teknis pada halaman sumber belum seluruhnya ditranskripsi.
- **CURRENT NEEDS REVIEW:** Membutuhkan klarifikasi manual dari gambar/tabel katalog resolusi rendah atau terdapat ambiguitas atribusi prinsipal.

#### Dimensi 2: Legacy Relationship (Hubungan dengan Data Lama)
- **LEGACY MATCH:** Produk pada katalog terkini memiliki padanan langsung yang teridentifikasi pada `products.json`.
- **CURRENT ONLY:** Produk terkonfirmasi ada pada katalog terkini 2026 / Company Profile 2026, tetapi **tidak pernah ada** pada `products.json` lama.
- **LEGACY ONLY:** Produk terdapat pada `products.json` lama, tetapi **tidak ditemukan** pada katalog terkini 64 halaman (*"Tidak ditemukan pada katalog terbaru — memerlukan verifikasi"*).
- **LEGACY MATCH — ATTRIBUTION REVIEW:** Produk lama teridentifikasi, tetapi atribusi brand/prinsipal pada katalog terkini berbeda (misal: micropipette DLAB vs IKA).
- **LEGACY MATCH — PARTIAL VERIFICATION:** Produk lama cocok pada tingkat famili, namun model spesifiknya perlu verifikasi ketersediaan pada katalog baru.

---

### 6. Company Profile Data Inventory

Berdasarkan ekstraksi langsung dari dokumen sumber resmi `Company Profile ANS 2026 Rev.02.pdf` (13 Halaman):

| Dataset | DB Field | Source Content | Status & Tipe Teks | Halaman Sumber | Notes |
|---|---|---|:---:|:---:|---|
| **Tagline** | `tagline_en` | *EMPOWERING SCIENCE FOR A PROSPEROUS FUTURE* | CURRENT CONFIRMED *(verbatim dari source)* | Halaman 1 | Slogan resmi bahasa Inggris pada Company Profile. |
| **Tagline** | `tagline_id` | *Memberdayakan Sains untuk Masa Depan yang Sejahtera* | CURRENT CONFIRMED *(padanan Bahasa Indonesia)* | Halaman 1 | Padanan Bahasa Indonesia resmi. |
| **About** | `about_en` | PT Abhipraya Nawasena Sejahtera (ANS) is a company that moving on marketer and distribution product life science for pharmacy industry, FnB, Biotechnology, cosmetic, service lab, research center, university & Hospitals.<br><br>We believe that science if directed with good intention, can do big power to bring our life towards for prosperous future.<br><br>Based on our experiences for more than 15 years, we growth because our commitment and dedication to our customers with best services, high quality product and professional technical support, as quick after sales service appropriate regulation. | CURRENT CONFIRMED *(verbatim dari source)* | Halaman 3 | Teks resmi dari section *About Us* Company Profile ANS 2026. |
| **About** | `about_id` | PT Abhipraya Nawasena Sejahtera (ANS) adalah perusahaan yang bergerak di bidang pemasaran dan distribusi produk ilmu hayati (life science) untuk industri farmasi, makanan & minuman (FnB), bioteknologi, kosmetik, laboratorium uji, pusat penelitian, universitas, dan rumah sakit.<br><br>Kami meyakini bahwa sains jika diarahkan dengan niat baik dapat memberikan kekuatan besar untuk membawa kehidupan kita menuju masa depan yang sejahtera.<br><br>Berdasarkan pengalaman kami selama lebih dari 15 tahun, kami berkembang berkat komitmen dan dedikasi kepada pelanggan melalui layanan terbaik, produk berkualitas tinggi, dan dukungan teknis profesional, serta layanan purnajual yang cepat sesuai regulasi. | CURRENT CONFIRMED *(terjemahan diselaraskan)* | Halaman 3 | Terjemahan Bahasa Indonesia akurat dari teks resmi Halaman 3. |
| **Vision** | `vision_en` | To be a driving force of life science and industrial advancement for a prosperous future. | CURRENT CONFIRMED *(verbatim dari source)* | Halaman 4 | Teks resmi dari section *Vision* Halaman 4 Company Profile. |
| **Vision** | `vision_id` | Menjadi motor penggerak kemajuan ilmu hayati (life science) dan industri untuk masa depan yang sejahtera. | CURRENT CONFIRMED *(terjemahan diselaraskan)* | Halaman 4 | Padanan Bahasa Indonesia akurat dari Visi resmi Halaman 4. |
| **Mission** | `mission_en` | <ol><li>Realizing integrated laboratory innovation to support science, industry and environment progress.</li><li>Providing responsible and continues solution that creating real benefits for society and the earth.</li><li>Buiding strategic cooperation with customer, business partner and principal for the growth.</li><li>Increasing life value trough science, technology and professional services.</li></ol> | CURRENT CONFIRMED *(verbatim dari source)* | Halaman 4 | 4 poin Misi dari section *Mision* Halaman 4 Company Profile (disajikan dalam format HTML list). |
| **Mission** | `mission_id` | <ol><li>Mewujudkan inovasi laboratorium terpadu untuk mendukung kemajuan sains, industri, dan lingkungan.</li><li>Menyediakan solusi yang bertanggung jawab dan berkelanjutan yang menciptakan manfaat nyata bagi masyarakat dan bumi.</li><li>Membangun kerja sama strategis dengan pelanggan, mitra bisnis, dan prinsipal untuk pertumbuhan bersama.</li><li>Meningkatkan nilai kehidupan melalui sains, teknologi, dan layanan profesional.</li></ol> | CURRENT CONFIRMED *(terjemahan diselaraskan)* | Halaman 4 | Padanan Bahasa Indonesia akurat dari 4 poin Misi resmi Halaman 4. |
| **Address** | `address` | Mensana Tower Lt. 15, Jl. Raya Kranggan RT.002/RW.016, Kel. Jatisampurna, Kec. Jatisampurna, Kota Bekasi, Jawa Barat 17433 | CURRENT CONFIRMED *(verbatim dari source)* | Halaman 13 | Alamat kantor pusat resmi pada Halaman 13 Company Profile. |
| **Phone** | `phone` | `021 39722772` | CURRENT CONFIRMED *(verbatim dari source)* | Halaman 13 | Nomor telepon kantor resmi. |
| **WhatsApp** | `whatsapp` | `0822-614-614-00` | CURRENT CONFIRMED *(verbatim dari source)* | Halaman 13 | Nomor WhatsApp resmi (0822-614-614-00). |
| **Email** | `email` | `admin@avenasa.co.id` | CURRENT CONFIRMED *(verbatim dari source)* | Halaman 13 | Email korespondensi resmi. |
| **Maps Embed** | `maps_embed_url` | `https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3408018314136!2d106.92349797499138!3d-6.350541793639343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699320e8bfa177%3A0x6b403fbe4019a16f!2sMensana%20Tower!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid` | CURRENT CONFIRMED *(koordinat Mensana Tower)* | — | Embed Google Maps resmi untuk Mensana Tower Kranggan Bekasi. |

---

### 7. Core Values Data Inventory

Tepat 6 Core Values resmi dari Halaman 5 `Company Profile ANS 2026 Rev.02.pdf` (*"Six core values that reflect each element of the spiral"*):

| No | Title (EN) *(verbatim)* | Title (ID) *(padanan)* | Description (EN) | Description (ID) | Icon Name Candidate | Sort Order | Status |
|:---:|---|---|---|---|---|:---:|---|
| 1 | **INTEGRITY** | **Integritas** | Upholding honesty, business ethics, transparency, and regulatory compliance in every partnership and operational standard. | Menjunjung tinggi kejujuran, etika bisnis, transparansi, dan kepatuhan terhadap seluruh regulasi industri dalam setiap kemitraan. | `shield-check` | 1 | CURRENT CONFIRMED |
| 2 | **INNOVATION** | **Inovasi** | Continuously introducing cutting-edge laboratory technology, diagnostics, and integrated scientific solutions. | Terus menghadirkan teknologi laboratorium mutakhir, diagnostik, dan solusi saintifik terpadu untuk kemajuan industri. | `light-bulb` | 2 | CURRENT CONFIRMED |
| 3 | **COLLABORATION** | **Kolaborasi** | Building strong, strategic, and mutually beneficial cooperation with customers, business partners, and global principals. | Membangun kerja sama yang kokoh, strategis, dan saling menguntungkan dengan pelanggan, mitra bisnis, dan prinsipal global. | `user-group` | 3 | CURRENT CONFIRMED |
| 4 | **SUSTAINABILITY** | **Keberlanjutan** | Committed to responsible practices that support environmental sustainability, public welfare, and long-term industrial resilience. | Berkomitmen pada praktik bertanggung jawab yang mendukung kelestarian lingkungan, kesejahteraan masyarakat, dan ketahanan industri. | `arrow-path` | 4 | CURRENT CONFIRMED |
| 5 | **PROFESSIONALISM** | **Profesionalisme** | Delivering service excellence, high-level technical support, and dedicated quick after-sales service. | Memberikan keunggulan layanan, dukungan teknis berstandar tinggi, dan layanan purnajual cepat yang berdedikasi. | `briefcase` | 5 | CURRENT CONFIRMED |
| 6 | **WELL-BEING** | **Kesejahteraan** | Prioritizing health, safety, and enhancing the value of life through the advancement of science and technology. | Mengutamakan kesehatan, keselamatan, dan peningkatan nilai kehidupan melalui kemajuan sains dan teknologi. | `heart` | 6 | CURRENT CONFIRMED |

---

### 8. Management / Founder Data Inventory (Source Fact vs Implementation Decision)

Tepat 3 Founder / Owner resmi dari Halaman 2 `Company Profile ANS 2026 Rev.02.pdf`:

| Name | Position (EN dari PDF) | Position (ID) | Bio / Track Record *(verbatim sumber PDF Halaman 2)* | Photo Path | Implementation Decision | Source Status |
|---|---|---|---|---|:---:|:---:|
| **Erik Haryanto** | Marketing & sales manager and commissioner | Komisaris / Manajer Pemasaran & Penjualan | **2001 – 2013:** Pharmacy industry laboratory<br>**2013 – Now:** Marketing & sales manager and commissioner | `null` | Initial `is_active = false`, `photo_path = null` *(Disiapkan di CMS tapi sementara disembunyikan sampai foto HD disetujui)* | CURRENT CONFIRMED |
| **Fernanda Ramadhan F** | Sales and director | Direktur Penjualan | **2022 – Now:** Sales and director | `null` | Initial `is_active = false`, `photo_path = null` | CURRENT CONFIRMED |
| **Hazin Yusuf** | Marketing manager and director | Direktur / Manajer Pemasaran | **2001 – Now:** Has been pioneering the life science business as marketing manager and director | `null` | Initial `is_active = false`, `photo_path = null` | CURRENT CONFIRMED |

> [!NOTE]
> **Pemisahan Fakta Sumber vs Keputusan Bisnis:**  
> - **Fakta Sumber:** Data nama, jabatan, dan rekam jejak ketiga founder adalah **CURRENT CONFIRMED** dari Halaman 2 Company Profile.  
> - **Keputusan Implementasi:** Pengaturan `is_active = false` dan `photo_path = null` pada initial seeding murni merupakan kesepakatan tata kelola website agar profil pimpinan tidak tampil di publik sebelum foto profil HD resmi disetujui oleh direksi.

---

### 9. Brand / Principal Cross-Reference

Rekonsiliasi seluruh brand dan principal yang muncul di Company Profile (Halaman 7–9), Current Catalogue, dan Legacy Data:

| Brand / Principal Name | Company Profile (p. 7–9) | Current Catalogue (p. 5–64) | Legacy `products.json` | `is_new_principal` | Target `brands` Status | Source Status & Notes |
|---|:---:|:---:|:---:|:---:|---|---|
| **Lovibond / Tintometer** | Yes (p. 8) | Yes (p. 5–23) | Yes (`lovibond`) | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED. Principal utama uji kualitas air dan pengukuran warna. |
| **Gold Standard Diagnostics** | Yes (p. 7) | Yes (p. 24–26) | Yes (`goldstandard`) | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED. Principal pengujian alergen makanan (ELISA, PCR, LFD). |
| **Neogen** | Yes (p. 7) | Yes (p. 27–28) | No | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED. Principal hygiene monitoring (Clean-Trace ATP) & media mikrobiologi One Broth One Plate. |
| **Fountain Scientific** | Yes (p. 7) | Yes (p. 29–34) | Yes (`fountain`) | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED. Principal rapid microbial count plate (GaBriFilm). |
| **Bioendo** | No | Yes (p. 35–37) | Yes (`bioendo`) | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED (Catalog). Produsen spesialis uji endotoksin (LAL gel clot & microplates). Masuk `brands`. |
| **Terragene / Bionova** | Yes (p. 7) | Yes (p. 38–41) | Yes (`terragene`) | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED. Principal pemantauan sterilisasi (indikator biologi Bionova & Bowie-Dick). |
| **IKA** | Yes (p. 8) | Yes (p. 42–48) | Yes (`ika`) | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED. Principal instrumen laboratorium Jerman (bioreactor, stirrers, shakers, mills, rotavisc, rotavapor, centrifuge, pipettes). |
| **Fisher Scientific** | No | Yes (p. 49–50) | Yes (`fisher`) | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED (Catalog). Produsen pelarut kimia tingkat tinggi (HPLC, LC/MS, ACS Solvents). Masuk `brands`. |
| **DLAB Scientific** | Yes (p. 8) | Yes (p. 51–58) | Yes (`dlab`) | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED. Principal peralatan laboratorium (rotary evaporator, hotplate stirrer, shakers, spectrophotometers). |
| **BIGBIO** | No | Yes (p. 59) | No | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED (Catalog). Brand solusi bioaugmentasi air limbah domestik. Masuk `brands`. |
| **Cleanbio** | No | Yes (p. 60–62) | No | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED (Catalog). Brand pemurni udara & sterilisasi ruang (Air-Fit series). Masuk `brands`. |
| **ERA Biology** | Yes (p. 9) | No (Profile only) | No | `true` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED (New Principal). Ditampilkan eksplisit sebagai *New Principal* pada Company Profile p. 9 dengan 5 lini produk diagnostik klinis & mikologi. |
| **Merck** | Yes (p. 7) | No (Profile only) | No | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED (Profile). Principal reagen & mikrobiologi industri resmi pada Company Profile. |
| **HiMedia** | Yes (p. 7) | No (Profile only) | No | `false` | PARTIAL *(Media Pending)* | CURRENT CONFIRMED (Profile). Principal media kultur mikrobiologi pada Company Profile. |
| **Thermo Scientific** | Yes (p. 7) | No (Profile only) | No | `false` | CURRENT CONFIRMED (Profile) | Principal instrumen & bioteknologi pada Company Profile. |
| **Alliance Bio Expertise** | Yes (p. 7) | No (Profile only) | No | `false` | CURRENT CONFIRMED (Profile) | Principal otomasi laboratorium mikrobiologi pada Company Profile. |
| **Lonza** | Yes (p. 8) | No (Profile only) | No | `false` | CURRENT CONFIRMED (Profile) | Principal endotoksin & bioteknologi pada Company Profile. |
| **Labitex** | Yes (p. 8) | No (Profile only) | No | `false` | CURRENT CONFIRMED (Profile) | Principal peralatan laboratorium pada Company Profile. |

---

### 10. Proposed Working Category Taxonomy

Taxonomy kategori berikut merupakan **usulan klasifikasi (*Proposed Taxonomy*)** yang diturunkan dari analisis struktur katalog dan area portofolio bisnis Company Profile 2026 (Halaman 6).  
*Status:* **`PROPOSED — READY FOR BUSINESS REVIEW`** (Konfirmasi final dilakukan sebelum/saat eksekusi seeding):

| No | Proposed Category Name (ID) | Proposed Category Name (EN) | Proposed Slug (ID / EN) | Cakupan Domain Produk & Principal |
|:---:|---|---|---|---|
| 1 | **Pengujian Kualitas Air & Warna** | **Water Testing & Colour Measurement** | `pengujian-kualitas-air-dan-warna` / `water-testing-and-colour-measurement` | Lovibond Photometer, Comparators, Turbidity Meter, BOD System, Test Kits, Chemical Reagents. |
| 2 | **Keamanan Pangan & Uji Alergen** | **Food Safety & Allergen Diagnostics** | `keamanan-pangan-dan-uji-alergen` / `food-safety-and-allergen-diagnostics` | Gold Standard Diagnostics (SENSISpec ELISA, SENSIStrip LFD, DNAllergen2 PCR, RapidScan Reader), Neogen (Clean-Trace ATP). |
| 3 | **Mikrobiologi & Media Kultur** | **Microbiology & Culture Media** | `mikrobiologi-dan-media-kultur` / `microbiology-and-culture-media` | GaBriFilm Rapid Count Plates, Counter 1000 Colony Reader, Neogen One Broth One Plate (OBOP), Merck, HiMedia culture media. |
| 4 | **Pengujian Endotoksin & Pirogen** | **Endotoxin & Pyrogen Testing** | `pengujian-endotoksin-dan-pirogen` / `endotoxin-and-pyrogen-testing` | Bioendo Gel Clot LAL, Kinetic Chromogenic Endotoxin Assay, Pyrogen-free Microplates MPC96, Microplate Reader 800 TS, Lonza, ERA TAL. |
| 5 | **Pemantauan Sterilisasi** | **Sterilization Monitoring** | `pemantauan-sterilisasi` / `sterilization-monitoring` | Terragene / Bionova Indikator Biologi (Ultra Rapid, Super Rapid, Conventional), Bowie-Dick Test Packs, Spore Ampoules, Spore Strips, PCDs. |
| 6 | **Peralatan & Instrumen Laboratorium** | **Laboratory Equipment & Instruments** | `peralatan-dan-instrumen-laboratorium` / `laboratory-equipment-and-instruments` | IKA (Bioreactor, Stirrers, Shakers, Homogenizers, Mills, Viscometers, Rotavapor, Centrifuges, Pipettes), DLAB (Rotary Evaporators, Hotplate Stirrers, Shakers, Spectrophotometers). |
| 7 | **Bahan Kimia, Solven & Solusi Lingkungan** | **Chemicals, Solvents & Environmental Solutions** | `bahan-kimia-solven-dan-solusi-lingkungan` / `chemicals-solvents-and-environmental-solutions` | Fisher Scientific (HPLC Solvents, LC/MS Solvents, ACS Grade), BIGBIO (Wastewater Bioaugmentation), Cleanbio (Air-Fit 20/30/50 Air Purification). |

---

### 11. Current Catalogue Product Inventory (59 Entri & Famili Produk)

Inventaris lengkap **59 entri produk dan famili produk dari `catalog-ans.pdf`** (64 halaman):

| No | Product / Family Name | Brand / Principal | Source Page | Model / Order Code yang Terbaca | Current Source Status |
|:---:|---|---|:---:|---|:---:|
| 1 | Cooling and Industrial Process Water Test Kits | Lovibond | 5 | Multi-parameter test kits | CURRENT CONFIRMED |
| 2 | Hardness Test Kit | Lovibond | 6 | Total & Calcium Hardness | CURRENT CONFIRMED |
| 3 | Silt Density Index (SDI) Test Kit | Lovibond | 6 | SDI Test Kit for RO (100 tests) | CURRENT CONFIRMED |
| 4 | Three-Chamber Tester Chlorine / pH | Lovibond | 6 | Chlorine 0.1–3.0 mg/l, pH 6.8–8.2 | CURRENT CONFIRMED |
| 5 | Non-Oxidising Biocide Kits | Lovibond | 6 | Biocide test kits | CURRENT PARTIAL |
| 6 | Arsenic Test Kit (5ppb) | Lovibond | 7 | Order Code: **400700** | CURRENT CONFIRMED |
| 7 | CHECKIT Comparator Kits | Lovibond | 8 | Multi-disc single parameter kits | CURRENT PARTIAL |
| 8 | Comparator 2000+ Complete Kits | Lovibond | 9 | Comparator 2000+ System | CURRENT PARTIAL |
| 9 | E-Comparator EC 2000 Pt-Co | Lovibond | 10 | Model: **EC 2000** | CURRENT CONFIRMED |
| 10 | Photometer MD Series (Bench/Portable) | Lovibond | 11 | Models: **MD 100, MD 110, MD 200** | CURRENT CONFIRMED |
| 11 | Thermoreactor / COD Reactor | Lovibond | 12 | Model: **RD 125** / Order Code: **2418940** | CURRENT CONFIRMED |
| 12 | Multi-Parameter Photometers | Lovibond | 13 | Models: **MD 600, MD 610** | CURRENT CONFIRMED |
| 13 | Photometer & Fluorometer for PTSA | Lovibond | 13 | Model: **MD 640** | CURRENT CONFIRMED |
| 14 | VIS / UV-VIS Spectrophotometer | Lovibond | 14 | Models: **XD 7000, XD 7500** | CURRENT CONFIRMED |
| 15 | Vario Powder Pack Reagents | Lovibond | 15–17 | Vario Powder Reagents | CURRENT PARTIAL |
| 16 | Turbidity Meter (Infrared) | Lovibond | 18 | Model: **TB 300 IR** | CURRENT CONFIRMED |
| 17 | Compact Turbidity Meter | Lovibond | 19 | Model: **TB 211 IR** | CURRENT CONFIRMED |
| 18 | White Light Turbidity Meter | Lovibond | 20 | Model: **TB 250 WL** | CURRENT CONFIRMED |
| 19 | BOD Measurement System | Lovibond | 21 | Models: **BD 600, BD 600 GLP** | CURRENT CONFIRMED |
| 20 | SENSISpec Allergen Detection ELISA Kits | Gold Standard Diagnostics | 24 | Multi-target ELISA kits | CURRENT CONFIRMED |
| 21 | SENSISpec Gluten Detection ELISA Kits | Gold Standard Diagnostics | 25 | SENSISpec Gluten R5 | CURRENT CONFIRMED |
| 22 | Allergen Control Materials & Supplementary | Gold Standard Diagnostics | 25 | Reference materials | CURRENT PARTIAL |
| 23 | DNAllergen2 Real-Time PCR Kits | Gold Standard Diagnostics | 25 | Multi-target PCR assays | CURRENT CONFIRMED |
| 24 | SENSIStrip Lateral Flow Tests | Gold Standard Diagnostics | 26 | Multi-target LFD strips | CURRENT CONFIRMED |
| 25 | RapidScan Lateral Flow Reader | Gold Standard Diagnostics | 26 | Model: **RapidScan LFD Reader** | CURRENT CONFIRMED |
| 26 | Clean-Trace Hygiene Monitoring System | Neogen | 27 | Models: **UXL100, AQT200, AQF100** | CURRENT CONFIRMED |
| 27 | One Broth One Plate (OBOP) Workflows | Neogen | 28 | Listeria spp., L. monocytogenes, Salmonella | CURRENT CONFIRMED |
| 28 | GaBriFilm Rapid Aerobic Count Plate | Fountain Scientific | 29 | Rapid TPC Plate | CURRENT CONFIRMED |
| 29 | GaBriFilm Rapid Coliform Count Plate | Fountain Scientific | 30 | Rapid Coliform Plate | CURRENT CONFIRMED |
| 30 | GaBriFilm Yeast & Mold Count Plate | Fountain Scientific | 30–31 | Y&M Plate | CURRENT CONFIRMED |
| 31 | GaBriFilm Staphylococcus aureus Plate | Fountain Scientific | 31 | S. aureus Plate | CURRENT CONFIRMED |
| 32 | GaBriFilm Rapid E. coli & Coliform Two-in-One | Fountain Scientific | 32 | E. coli / Coliform Plate | CURRENT CONFIRMED |
| 33 | GaBriFilm Rapid Coliform (Fermented Milk) | Fountain Scientific | 32 | Fermented Milk Coliform Plate | CURRENT CONFIRMED |
| 34 | GaBriFilm Rapid Salmonella Count Plate | Fountain Scientific | 33 | Salmonella Plate | CURRENT CONFIRMED |
| 35 | GaBriFilm Environmental Listeria Test Plate | Fountain Scientific | 34 | Listeria Plate | CURRENT CONFIRMED |
| 36 | Counter 1000 Automated Colony Reader | Fountain Scientific | 34 | Model: **1000 Colony Reader** | CURRENT CONFIRMED |
| 37 | Gel Clot Lyophilized Amebocyte Lysate | Bioendo | 35 | Single Test in Vial & Ampoule | CURRENT CONFIRMED |
| 38 | Kinetic Chromogenic Endotoxin Assay | Bioendo | 35 | Sensitivity: 0.001 EU/ml | CURRENT CONFIRMED |
| 39 | Pyrogen-Free Microplates | Bioendo | 36 | Catalog No: **MPC96** | CURRENT CONFIRMED |
| 40 | Absorbance Microplate Reader | Bioendo / BioTek | 37 | Model: **800 TS** | CURRENT CONFIRMED |
| 41 | UV Dosimeters for Disinfection Systems | Terragene | 38 | UV-C 254 nm / pulsed light | CURRENT CONFIRMED |
| 42 | BioSurf Biological Indicator | Terragene | 38–39 | Model: **BT97 BioSurf** | CURRENT CONFIRMED |
| 43 | Ultra Rapid / Super Rapid Biological Indicators | Terragene | 39 | Models: **BT224, BT222, BT96, BT102, BT110** | CURRENT CONFIRMED |
| 44 | Steam Process Challenge Devices (PCDs) | Terragene | 39 | Steam PCD systems | CURRENT CONFIRMED |
| 45 | Conventional Biological Indicators & Spores | Terragene | 40 | Models: **IC10/20, BT21, BT22, BT23, BT24, BT31** | CURRENT CONFIRMED |
| 46 | Bowie-Dick Test Packs | Terragene | 41 | Models: **BD125X/1, BD125X/2** | CURRENT CONFIRMED |
| 47 | HABITAT Research Benchtop Bioreactor | IKA | 42 | Model: **HABITAT Research** | CURRENT CONFIRMED |
| 48 | Laboratory Stirrers, Shakers & Homogenizers | IKA | 43–45 | Models: **KS 4000, ULTRA-TURRAX** | CURRENT CONFIRMED |
| 49 | Laboratory Mills & Viscometers | IKA | 46 | Models: **M 20 Universal Mill, ROTAVISC** | CURRENT CONFIRMED |
| 50 | Rotary Evaporators | IKA | 47 | Models: **RV 8, RV 10** | CURRENT CONFIRMED |
| 51 | Midi Centrifuge & Liquid Handling Pipettes | IKA | 48 | Models: **G-L, PETTE series (fix, vario, multi)** | CURRENT CONFIRMED |
| 52 | Optima LC/MS & HPLC Grade Solvents | Fisher Scientific | 49 | High-Purity Solvents | CURRENT CONFIRMED |
| 53 | Certified ACS Grade Solvents | Fisher Scientific | 50 | ACS Specifications Solvents | CURRENT CONFIRMED |
| 54 | LED Digital Rotary Evaporator | DLAB Scientific | 51–52 | Model: **RE100-Pro** | CURRENT CONFIRMED |
| 55 | LCD Digital Magnetic Hotplate Stirrers | DLAB Scientific | 53–54 | Model: **MS10-H500-Pro** | CURRENT CONFIRMED |
| 56 | Digital Orbital & Linear Shakers | DLAB Scientific | 55–56 | Models: **SK-0330-Pro, SK-0180-Pro** | CURRENT CONFIRMED |
| 57 | UV-Visible Spectrophotometers | DLAB Scientific | 57–58 | Models: **SP-V1000, SP-UV1000, SP-V1100, SP-UV1100** | CURRENT CONFIRMED |
| 58 | BIGBIO Wastewater Bioaugmentation | BIGBIO | 59 | Liquid & Powder Formulation | CURRENT CONFIRMED |
| 59 | Cleanbio Air-Fit Medical Air Purifiers | Cleanbio | 60–62 | Models: **Air-Fit 50, Air-Fit 30, Air-Fit 20** | CURRENT CONFIRMED |

---

### 12. Legacy Product Inventory (22 Produk)

Inventaris 22 produk dari file legacy `docs/references/existing-assets/legacy-data/products.json`:

| No | Legacy Brand | Legacy Product Name | Legacy Description Snippet | Legacy Features & Badge |
|:---:|---|---|---|---|
| 1 | Lovibond | Photometer 7100 | Multiparameter photometer for water analysis | 35+ parameters, IP67 waterproof (`Best Seller`) |
| 2 | Lovibond | Tintometer Colour Comparator | Visual colour comparator for field testing | Portable design, no power required |
| 3 | Lovibond | MD 200 Photometer | Professional bench photometer | USB data export, 100+ test methods |
| 4 | Gold Standard Diagnostics | SENSISpec Allergen ELISA Kits | Full-range allergen ELISA detection kits | Milk, egg, peanut, gluten (`AOAC Approved`) |
| 5 | Gold Standard Diagnostics | SENSIStrip PowerLine Lateral Flow Tests | Rapid allergen lateral flow tests with hook-effect protection | Results in minutes, hook-line design (`New`) |
| 6 | Gold Standard Diagnostics | DNAllergen2 Real-Time PCR Kits | Molecular allergen detection for high-specificity testing | Sensitivity ≤ 0.4 ppm, thermally processed matrices |
| 7 | Gold Standard Diagnostics | RapidScan LFD Reader | Portable quantitative lateral flow test reader | Quantitative evaluation of SENSIStrip tests |
| 8 | Gold Standard Diagnostics | ThunderBolt ELISA Analyzer | Fully automated open-platform ELISA analyzer | Automated, validated for GSD ELISA |
| 9 | Fountain Scientific | Total Plate Count (TPC) Plate | Rapid total aerobic count plate | Results in 24-48h, pre-filled dry medium |
| 10 | Fountain Scientific | Coliform Count Plate | Selective coliform detection plate | Blue colony identification, 24h results |
| 11 | BIOENDO | Limulus Amebocyte Lysate (LAL) Kit | Gel-clot endotoxin detection kit | USP / EP compliant, high sensitivity |
| 12 | BIOENDO | Recombinant Factor C (rFC) Assay | Animal-free endotoxin testing | Fluorometric detection, no horseshoe crab (`Eco-Friendly`) |
| 13 | Terragene | Bionova Biological Indicators | Biological indicators for sterilization validation | Steam, EO, dry heat, rapid readout (1-3h) |
| 14 | Terragene | Chemical Integrators | Process challenge device for sterilization monitoring | Real-time assessment, class 4, 5, 6 |
| 15 | IKA | RCT Basic Hotplate Stirrer | Digital hotplate stirrer with external temperature sensor | Up to 340°C, 0–1500 rpm (`Popular`) |
| 16 | IKA | VORTEX 3 Vortex Mixer | High-performance vortex mixer | 500–2500 rpm, continuous & touch mode |
| 17 | IKA | T 18 Digital ULTRA-TURRAX | Digital disperser for homogenization | 10,000–24,000 rpm, digital speed control |
| 18 | Fisher Scientific | HPLC Grade Solvents | High-purity solvents for chromatography | Low UV absorbance, certified purity |
| 19 | Fisher Scientific | ACS Grade Reagents | American Chemical Society grade chemicals | Meets ACS specifications, CoA provided |
| 20 | DLAB Scientific | Single Channel Pipette (0.1–1000 µL) | Ergonomic single-channel micropipette series | Lightweight, ISO 8655 certified |
| 21 | DLAB Scientific | D1008E Microcentrifuge | Compact 24-place microcentrifuge | Max 13,400 rpm, LED display |
| 22 | DLAB Scientific | HandyStep Touch Repetitive Pipette | Electronic repetitive pipette for dispensing | 1–5000 µL range, touchscreen interface |

---

### 13. Legacy ↔ Current Product Cross-Reference (Pemetaan Silang Dua Dimensi)

Pemetaan silang komprehensif antara data produk legacy dan katalog resmi terkini dengan status dua dimensi yang jelas:

| Legacy Brand | Legacy Product | Current Match | Model / Article | Current Source Status | Legacy Relationship | Evidence & Notes |
|---|---|---|---|:---:|:---:|---|
| **Lovibond** | Photometer 7100 | MD Series / XD Spectrophotometers | MD 100/200, XD 7000/7500 | — | **LEGACY ONLY** | `catalog-ans.pdf` p. 11 & 14 menampilkan seri MD dan XD; model 7100 tidak terdaftar pada katalog terkini. |
| **Lovibond** | Tintometer Colour Comparator | CHECKIT & Comparator 2000+ System | EC 2000, 2000+ | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 8–10 memuat CHECKIT, Comparator 2000+, dan E-Comparator EC 2000. |
| **Lovibond** | MD 200 Photometer | Photometer MD 200 | **MD 200** | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 11 memuat MD 200 secara eksplisit. Match sempurna nama model dan spesifikasi. |
| **Gold Standard Diagnostics** | SENSISpec Allergen ELISA Kits | SENSISpec Allergen Detection ELISA Kits | Multi-target ELISA | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 24 memuat lini produk SENSISpec Allergen ELISA. |
| **Gold Standard Diagnostics** | SENSIStrip PowerLine Lateral Flow Tests | SENSIStrip Lateral Flow Tests | Multi-target LFD | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 26 memuat SENSIStrip Lateral Flow Tests. |
| **Gold Standard Diagnostics** | DNAllergen2 Real-Time PCR Kits | DNAllergen2 PCR Kits | Multi-target PCR | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 25 memuat DNAllergen2 PCR Kits. |
| **Gold Standard Diagnostics** | RapidScan LFD Reader | RapidScan LFD Reader | **RapidScan LFD Reader** | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 26 mencantumkan RapidScan Lateral Flow Reader. |
| **Gold Standard Diagnostics** | ThunderBolt ELISA Analyzer | Mentioned in ELISA notes | ThunderBolt | **CURRENT NEEDS REVIEW** | **LEGACY MATCH — ATTRIBUTION REVIEW** | `catalog-ans.pdf` tidak memiliki halaman produk mandiri untuk ThunderBolt, namun disebut sebagai instrumen kompatibel di halaman ELISA. |
| **Fountain Scientific** | Total Plate Count (TPC) Plate | GaBriFilm Rapid Aerobic Count Plate | GaBriFilm TPC | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 29 memuat GaBriFilm Rapid Aerobic Count (TPC). |
| **Fountain Scientific** | Coliform Count Plate | GaBriFilm Rapid Coliform Count Plate | GaBriFilm Coliform | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 30 memuat GaBriFilm Rapid Coliform Count. |
| **BIOENDO** | Limulus Amebocyte Lysate (LAL) Kit | Gel Clot Lyophilized Amebocyte Lysate | Single Test Vial/Ampoule | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 35 memuat Gel Clot LAL single test vial & ampul. |
| **BIOENDO** | Recombinant Factor C (rFC) Assay | Not in current catalog | — | — | **LEGACY ONLY** | `catalog-ans.pdf` p. 35–37 fokus pada Gel Clot LAL, Kinetic Chromogenic, MPC96, dan 800 TS Reader. |
| **Terragene** | Bionova Biological Indicators | Bionova Biological Indicators (Rapid/Super Rapid/Conventional) | BT97, BT224, BT222, BT96, IC10/20 | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 38–40 memuat seluruh jajaran Bionova BI. |
| **Terragene** | Chemical Integrators | Bowie-Dick Test Packs & Steam PCDs | BD125X/1, BD125X/2 | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 39 & 41 memuat Bowie-Dick Test Pack dan PCD Steam. |
| **IKA** | RCT Basic Hotplate Stirrer | Magnetic Stirrers family | RCT Basic / Magnetic Stirrer | **CURRENT PARTIAL** | **LEGACY MATCH — PARTIAL VERIFICATION** | `catalog-ans.pdf` p. 43 memuat Magnetic Stirrers family, model spesifik perlu transkripsi lanjut. |
| **IKA** | VORTEX 3 Vortex Mixer | Shakers & Mixers line | KS 4000 / Mixers | **CURRENT NEEDS REVIEW** | **LEGACY MATCH — PARTIAL VERIFICATION** | `catalog-ans.pdf` p. 45 memuat Shakers (KS 4000) dan ULTRA-TURRAX. |
| **IKA** | T 18 Digital ULTRA-TURRAX | Dispersers / Homogenizers | **ULTRA-TURRAX** | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 45 mencantumkan lini ULTRA-TURRAX secara eksplisit. |
| **Fisher Scientific** | HPLC Grade Solvents | HPLC Grade Solvents | High Purity Solvents | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 49 memuat HPLC Grade Solvents. |
| **Fisher Scientific** | ACS Grade Reagents | Certified ACS Solvents | ACS Grade Solvents | **CURRENT CONFIRMED** | **LEGACY MATCH** | `catalog-ans.pdf` p. 50 memuat Certified ACS Solvents. |
| **DLAB Scientific** | Single Channel Pipette (0.1–1000 µL) | IKA PETTE series (p. 48) | PETTE fix/vario/multi | **CURRENT NEEDS REVIEW** | **LEGACY MATCH — ATTRIBUTION REVIEW** | `catalog-ans.pdf` menampilkan micropipette pada brand IKA (p. 48). Bagian DLAB (p. 51–58) memuat Rotavapor, Stirrer, Shaker, Spectro. |
| **DLAB Scientific** | D1008E Microcentrifuge | IKA G-L Midi Centrifuge (p. 48) | G-L Centrifuge | — | **LEGACY ONLY** | `catalog-ans.pdf` p. 48 memuat centrifuge brand IKA G-L; model DLAB D1008E tidak tercantum pada p. 51–58. |
| **DLAB Scientific** | HandyStep Touch Repetitive Pipette | Not in current catalog | — | — | **LEGACY ONLY** | `catalog-ans.pdf` tidak memuat HandyStep. (HandyStep secara umum merupakan produk Brand GMBH). |

---

### 14. Legacy-Only Products (Produk yang Hanya Ada di Data Lama)

Daftar produk yang tercantum pada legacy `products.json` tetapi **TIDAK DITEMUKAN** pada katalog terkini 64 halaman (`catalog-ans.pdf`):

| No | Legacy Brand | Legacy Product Name | Status & Keterangan | Rekomendasi Seeding |
|:---:|---|---|---|---|
| 1 | Lovibond | Photometer 7100 | *Tidak ditemukan pada katalog terbaru — memerlukan verifikasi.* (Katalog terkini menampilkan seri MD dan XD). | **Jangan di-seed** sebagai produk aktif sampai ada konfirmasi resmi penggantian ke seri MD/XD. |
| 2 | BIOENDO | Recombinant Factor C (rFC) Assay | *Tidak ditemukan pada katalog terbaru — memerlukan verifikasi.* (Katalog terkini BIOENDO fokus pada LAL Gel Clot & Kinetic Chromogenic). | **Jangan di-seed**. |
| 3 | DLAB Scientific | D1008E Microcentrifuge | *Tidak ditemukan pada katalog terbaru — memerlukan verifikasi.* (Sentrifus pada katalog terkini dipegang oleh IKA G-L). | **Jangan di-seed**. |
| 4 | DLAB Scientific | HandyStep Touch Repetitive Pipette | *Tidak ditemukan pada katalog terbaru — memerlukan verifikasi.* (HandyStep bukan lini DLAB pada katalog terkini). | **Jangan di-seed**. |

> **Catatan:** Produk berstatus `LEGACY ONLY` tidak boleh dianggap sebagai produk aktif dan tidak dimasukkan ke dalam initial database seeding.

---

### 15. Needs Review Items (Item yang Memerlukan Klarifikasi Lanjutan)

Daftar item dan ambiguitas yang belum dapat dipastikan secara definitif dan **TIDAK DISELESAIKAN DENGAN ASUMSI**:

1. **DLAB vs IKA Micropipette Attribution:** Data legacy mengatribusikan micropipette ke DLAB, sedangkan katalog terkini (p. 48) menempatkan seri PETTE di bawah brand IKA.
2. **ThunderBolt ELISA Analyzer:** Disebut sebagai instrumen pendukung pembacaan ELISA pada catatan teknis GSD, namun belum memiliki halaman spesifikasi mandiri pada katalog PDF 64 halaman.
3. **BioTek 800 TS vs BIOENDO Reader Attribution:** Pada katalog p. 37, instrumen 800 TS ditampilkan di section Endotoxin BIOENDO. Perlu dipastikan apakah brand diatribusikan ke BioTek atau BIOENDO.
4. **Lovibond Vario Reagents Multi-Row (p. 15–17):** Berisi puluhan kode reagen bubuk; tidak boleh di-seed sebagai satu produk tunggal tanpa pemetaan grup parameter pada tahap seeding.
5. **Gold Standard Diagnostics Allergen Specific Target Assays (p. 24–26):** Tabel berisi puluhan target alergen spesifik (Milk, Egg, Soy, Peanut, Gluten, Almond, Hazelnut, Walnut, Mustard, Fish, Crustacea, dll.) yang perlu distandarisasi strukturnya.

---

### 16. Product Identifier Inventory (Nomor Model, Artikel & Order Code)

Daftar nomor model, artikel, dan kode pemesanan resmi yang terbukti secara eksplisit pada sumber katalog:

| Product / Instrument | Identifier Type | Identifier / Code | Source Document & Page | Confidence |
|---|---|---|---|:---:|
| Lovibond Arsenic Test Kit 5ppb | Order Code | **400700** | `catalog-ans.pdf` p. 7 | HIGH |
| Lovibond E-Comparator | Model Number | **EC 2000** | `catalog-ans.pdf` p. 10 | HIGH |
| Lovibond Photometer MD 100 | Model Number | **MD 100** | `catalog-ans.pdf` p. 11 | HIGH |
| Lovibond Photometer MD 110 | Model Number | **MD 110** | `catalog-ans.pdf` p. 11 | HIGH |
| Lovibond Photometer MD 200 | Model Number | **MD 200** | `catalog-ans.pdf` p. 11 | HIGH |
| Lovibond Thermoreactor / COD Reactor | Model Number / Order Code | **RD 125** / **2418940** | `catalog-ans.pdf` p. 12 | HIGH |
| Lovibond Photometer MD 600 | Model Number | **MD 600** | `catalog-ans.pdf` p. 13 | HIGH |
| Lovibond Photometer MD 610 | Model Number | **MD 610** | `catalog-ans.pdf` p. 13 | HIGH |
| Lovibond PTSA Photometer & Fluorometer | Model Number | **MD 640** | `catalog-ans.pdf` p. 13 | HIGH |
| Lovibond Spectrophotometer VIS | Model Number | **XD 7000** | `catalog-ans.pdf` p. 14 | HIGH |
| Lovibond Spectrophotometer UV-VIS | Model Number | **XD 7500** | `catalog-ans.pdf` p. 14 | HIGH |
| Lovibond Turbidity Meter (Infrared) | Model Number | **TB 300 IR** | `catalog-ans.pdf` p. 18 | HIGH |
| Lovibond Compact Turbidity Meter | Model Number | **TB 211 IR** | `catalog-ans.pdf` p. 19 | HIGH |
| Lovibond White Light Turbidity Meter | Model Number | **TB 250 WL** | `catalog-ans.pdf` p. 20 | HIGH |
| Lovibond BOD Measurement System | Model Number | **BD 600** | `catalog-ans.pdf` p. 21 | HIGH |
| Lovibond BOD GLP System | Model Number | **BD 600 GLP** | `catalog-ans.pdf` p. 21 | HIGH |
| Neogen Clean-Trace Luminometer | Model / Product Code | **UXL100** | `catalog-ans.pdf` p. 27 | HIGH |
| Neogen Clean-Trace Water ATP Swabs | Product Code | **AQT200** | `catalog-ans.pdf` p. 27 | HIGH |
| Neogen Clean-Trace Surface ATP Swabs | Product Code | **AQF100** | `catalog-ans.pdf` p. 27 | HIGH |
| Fountain Scientific Colony Reader | Model Number | **1000 Colony Reader** | `catalog-ans.pdf` p. 34 | HIGH |
| Bioendo Pyrogen-Free Microplates | Catalog Number | **MPC96** | `catalog-ans.pdf` p. 36 | HIGH |
| Bioendo Absorbance Microplate Reader | Model Number | **800 TS** | `catalog-ans.pdf` p. 37 | HIGH |
| Terragene BioSurf Biological Indicator | Model Code | **BT97 BioSurf** | `catalog-ans.pdf` p. 38–39 | HIGH |
| Terragene Super Rapid Steam BI | Model Code | **BT224** | `catalog-ans.pdf` p. 39 | HIGH |
| Terragene Ultra Rapid VH2O2 BI | Model Code | **BT222** | `catalog-ans.pdf` p. 39 | HIGH |
| Terragene Ultra Rapid Steam BI | Model Code | **BT96** | `catalog-ans.pdf` p. 39 | HIGH |
| Terragene Rapid Steam BI | Model Code | **BT102** | `catalog-ans.pdf` p. 39 | HIGH |
| Terragene Rapid EO BI | Model Code | **BT110** | `catalog-ans.pdf` p. 39 | HIGH |
| Terragene Conventional Steam BI | Model Code | **IC10/20** | `catalog-ans.pdf` p. 40 | HIGH |
| Terragene Steam Spore Ampoules | Model Codes | **BT21, BT22, BT23, BT24** | `catalog-ans.pdf` p. 40 | HIGH |
| Terragene Spore Ampoule & Culture Medium | Model Code | **BT31** | `catalog-ans.pdf` p. 40 | HIGH |
| Terragene Bowie-Dick Test Pack 134°C 3.5 min | Model Code | **BD125X/1** | `catalog-ans.pdf` p. 41 | HIGH |
| Terragene Bowie-Dick Test Pack 134°C 4.0 min | Model Code | **BD125X/2** | `catalog-ans.pdf` p. 41 | HIGH |
| IKA Benchtop Bioreactor | Model Name | **HABITAT Research** | `catalog-ans.pdf` p. 42 | HIGH |
| IKA Incubator Shaker | Model Number | **KS 4000** | `catalog-ans.pdf` p. 45 | HIGH |
| IKA Digital Homogenizer | Product Line | **ULTRA-TURRAX (T 18 / T 25)** | `catalog-ans.pdf` p. 45 | HIGH |
| IKA Universal Mill | Model Number | **M 20** | `catalog-ans.pdf` p. 46 | HIGH |
| IKA Rotational Viscometers | Product Series | **ROTAVISC** | `catalog-ans.pdf` p. 46 | HIGH |
| IKA Rotary Evaporators | Model Numbers | **RV 8, RV 10** | `catalog-ans.pdf` p. 47 | HIGH |
| IKA Midi Centrifuge | Model Number | **G-L** | `catalog-ans.pdf` p. 48 | HIGH |
| IKA Single/Multi-Channel Pipettes | Product Series | **PETTE (fix, vario, multi)** | `catalog-ans.pdf` p. 48 | HIGH |
| DLAB LED Digital Rotary Evaporator | Model Number | **RE100-Pro** | `catalog-ans.pdf` p. 51 | HIGH |
| DLAB LCD Magnetic Hotplate Stirrer | Model Number | **MS10-H500-Pro** | `catalog-ans.pdf` p. 53 | HIGH |
| DLAB Digital Orbital Shaker | Model Number | **SK-0330-Pro** | `catalog-ans.pdf` p. 55 | HIGH |
| DLAB Digital Linear Shaker | Model Number | **SK-0180-Pro** | `catalog-ans.pdf` p. 55 | HIGH |
| DLAB Visible Spectrophotometer (Single Beam) | Model Number | **SP-V1000** | `catalog-ans.pdf` p. 58 | HIGH |
| DLAB UV-Visible Spectrophotometer (Single Beam) | Model Number | **SP-UV1000** | `catalog-ans.pdf` p. 58 | HIGH |
| DLAB Visible Spectrophotometer (Split Beam) | Model Number | **SP-V1100** | `catalog-ans.pdf` p. 58 | HIGH |
| DLAB UV-Visible Spectrophotometer (Split Beam) | Model Number | **SP-UV1100** | `catalog-ans.pdf` p. 58 | HIGH |
| Cleanbio Medical Air Purifier 50 m² | Model Number | **Air-Fit 50** | `catalog-ans.pdf` p. 61 | HIGH |
| Cleanbio Medical Air Purifier 30 m² | Model Number | **Air-Fit 30** | `catalog-ans.pdf` p. 62 | HIGH |
| Cleanbio Compact Air Purifier 20 m² | Model Number | **Air-Fit 20** | `catalog-ans.pdf` p. 62 | HIGH |

> **Strategi Penyimpanan Skema Database:**  
> Database tidak memiliki kolom terpisah seperti `model_number`, `article_number`, `SKU`, atau `product_code`. Oleh karena itu, seluruh identifier yang telah teridentifikasi secara struktural akan direpresentasikan dalam kolom `specifications` bertipe JSON pada tabel `products`. Normalisasi final pasangan key-value akan dilakukan pada tahap product seeding (Milestone 2.3.3) tanpa perubahan DDL migrasi baru.

---

### 17. Legacy Content Worth Reusing (Kandidat Konten Legacy)

*Status:* **`LEGACY CONTENT CANDIDATE — CURRENT SOURCE VALIDATION REQUIRED`**  
Seluruh deskripsi, poin fitur, dan klaim regulasi lama berikut dapat dijadikan bahan referensi pengayaan teks, namun **wajib divalidasi kembali** terhadap katalog resmi terkini sebelum disetujui untuk publikasi:

1. **Lovibond MD 200 Photometer:**
   - *Kandidat Fitur:* 100+ metode pengujian air terkalibrasi pabrik, ekspor data USB, layar backlit besar, proteksi tahan air IP68.
   - *Kandidat Target Pasar:* Laboratorium pengolahan air bersih, industri FnB, dan pemantauan air limbah lingkungan.
2. **Gold Standard Diagnostics Allergen ELISA & Lateral Flow:**
   - *Kandidat Fitur:* Proteksi garis kait (*hook-line design*) untuk mencegah negatif palsu (*hook effect*), sensitivitas tinggi hingga ≤ 0.4 ppm, validasi metode AOAC Performance Tested.
   - *Kandidat Regulasi:* EU Regulation No. 1169/2011 & standar pengujian keamanan pangan internasional.
3. **Bioendo Gel Clot LAL Assay:**
   - *Kandidat Fitur:* Sensitivitas tinggi untuk deteksi pirogen dan endotoksin bakteri gram negatif pada sampel farmasi cair.
   - *Kandidat Kepatuhan Farmakope:* Standar USP <85>, EP 2.6.14, dan Farmakope Indonesia.
4. **Terragene Bionova Biological Indicators:**
   - *Kandidat Fitur:* Pembacaan super cepat (1–3 jam) dengan indikator fluoresensi, kepatuhan penuh ISO 11138 dan ISO 11140.
   - *Kandidat Target Pengguna:* CSSD Rumah Sakit, sterilisasi industri farmasi, dan produsen alat kesehatan.
5. **Fisher Scientific HPLC & ACS Solvents:**
   - *Kandidat Fitur:* Absorbansi UV ultra-rendah, kemurnian tersertifikasi (*Certificate of Analysis* lengkap), bebas partikel pengotor kromatografi.
   - *Kandidat Aplikasi:* Analisis instrumen HPLC, LC-MS, dan riset formulasi farmasi.

> **Peringatan Integritas Data:** Klaim regulasi, sertifikasi, badge pemasaran lama (seperti *"Best Seller"*, *"Popular"*, *"New"*), atau klaim performa tidak boleh diadopsi secara otomatis tanpa validasi kesesuaian pada dokumen resmi terbaru.

---

### 18. Database Seeding Readiness Matrix

Status kesiapan dataset sebelum memasuki tahapan seeding data:

| Dataset | Seeding Readiness Status | Keterangan & Tindakan yang Diperlukan | Target Milestone Seeding |
|---|---|---|:---:|
| **Company Profile** | **READY** | Seluruh data teks ID/EN, alamat, kontak, dan maps embed lengkap sesuai Halaman 1–13 PDF. | Milestone 2.3.2 |
| **Core Values** | **READY** | 6 nilai inti ID/EN (Halaman 5 PDF) dan 46 curated Heroicons lokal siap di-seed. | Milestone 2.3.2 |
| **Management** | **READY — MEDIA OPTIONAL / PHOTO PENDING** | Data entitas 3 founder lengkap. Foto diset `null` dan `is_active = false` sesuai keputusan bisnis. | Milestone 2.3.2 |
| **Categories** | **PROPOSED — READY FOR BUSINESS REVIEW** | 7 kategori merupakan proposed taxonomy yang siap dikonfirmasi final sebelum seeding. | Milestone 2.3.2 |
| **Brands / Principals** | **PARTIAL — ENTITY DATA READY, MEDIA ASSETS PENDING** | Data entitas 18 brand/principal siap di-seed. Kolom `logo_path` diset `null` sampai aset logo resmi disinkronkan. | Milestone 2.3.2 |
| **Products** | **READY FOR CORE SEEDING — CONTENT ENRICHMENT PENDING** | Data inti produk terkonfirmasi siap di-seed. Pengayaan narasi dan penyusunan `specifications` JSON dilakukan bertahap. Kolom `primary_image_path` dan `brochure_path` diset `null`. | Milestone 2.3.3 |
| **Product Images & Brochures** | **PENDING — ASSETS NOT YET PREPARED** | Aset fisik media dan brosur PDF belum disiapkan. Tidak ada record dummy yang dibuat pada seeding awal. | Milestone 2.3.4 |
| **Clients** | **PARTIAL — ENTITY DATA READY, MEDIA ASSETS PENDING** | Nama klien korporat (Halaman 11–12 PDF) siap di-seed. Kolom `logo_path` diset `null` sampai aset logo resmi disinkronkan. | Milestone 2.3.2 |

---

### 19. Final Findings

1. **Konsistensi Inventaris Data Terkini:** Inventaris katalog resmi (`catalog-ans.pdf`) terdiri dari **59 entri produk dan famili produk** tingkat katalog (*catalog-level offerings*), sedangkan data legacy (`products.json`) mencakup **22 entri produk historis**.
2. **Penerapan Granularitas Produk:** 59 entri katalog merepresentasikan famili dan kelompok penawaran katalog, bukan 59 individual SKU yang kaku. Seluruh 52 nomor model dan kode pemesanan (*article/order numbers*) yang teridentifikasi akan disimpan secara terstruktur di dalam kolom `specifications` JSON tanpa modifikasi skema database.
3. **Pemisahan Entitas Sesuai Skema:** Tidak ada kebutuhan untuk membuat tabel `principals` terpisah karena seluruh pabrikan/mitra resmi dapat dipetakan secara sempurna ke tabel `brands` dengan atribut `is_new_principal`.
4. **Rekonsiliasi Legacy Dua Dimensi:** Dari 22 produk pada products.json, terdapat 14 produk dengan padanan langsung (LEGACY MATCH), 2 produk dengan padanan parsial (LEGACY MATCH — PARTIAL VERIFICATION), 2 produk yang memerlukan review atribusi (LEGACY MATCH — ATTRIBUTION REVIEW), dan 4 produk berstatus LEGACY ONLY.
5. **Kebijakan Media Nullable yang Disengaja:** Seluruh field media (`primary_image_path`, `brochure_path`, `logo_path`, `photo_path`) dibiarkan bernilai `NULL` pada initial seeding tanpa membuat path atau placeholder palsu. Pengunggahan aset dilakukan secara manual dan terverifikasi melalui Filament CMS setelah aset fisik disetujui klien.

---

*(Dokumen audit cross-reference ini telah disempurnakan dan dikunci sebagai acuan tunggal sebelum pelaksanaan Milestone 2.3.2 Seeding & Content Foundation).*
