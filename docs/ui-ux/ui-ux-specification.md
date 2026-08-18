# UI/UX Design Specification
## PT Abhipraya Nawasena Sejahtera (ANS)
### Website Company Profile & Bilingual Product Catalog

**Document ID:** `SPEC-UIUX-01`  
**Target Platform:** Laravel 12 Blade + Tailwind CSS 4.x + Alpine.js + Filament 5.x  
**Primary Reference:** Phase 0 Architecture, URS, Feature Specifications (SPEC-01 s.d. SPEC-10), and Official ANS Brand Assets.  
**Document Status:** Locked UI/UX Design Specification (Source of Truth for Frontend Implementation & Figma Make)

---

## 1. Executive Summary & Design Philosophy

Dokumen ini mendefinisikan secara komprehensif sistem desain (*Design System*), arsitektur antarmuka pengguna (*User Interface*), dan alur pengalaman pengguna (*User Experience*) untuk website resmi **PT Abhipraya Nawasena Sejahtera (ANS)**.

Dokumen ini berfungsi sebagai:
1. **Sumber Acuan Desain Utama:** Menyatukan karakter visual brand korporat ANS dengan kebutuhan fungsional katalog produk B2B.
2. **Design Direction untuk Frontend:** Pedoman implementasi langsung untuk Laravel Blade, Tailwind CSS 4.x, dan Alpine.js.
3. **Source of Truth untuk Figma Make:** Rujukan struktural dan semantik lengkap bagi pembuatan visual prototype di Figma Make.
4. **Jembatan Arsitektur & Bisnis:** Menjembatani seluruh batasan teknis (shared hosting compatibility, zero-daemon, server-rendered Blade, single source of truth) dengan pengalaman interaksi publik yang elegan, cepat, dan terpercaya.

---

## 2. Brand Identity & Visual Language Translation

### 2.1. Analisis Karakter Logo Resmi ANS
Logo resmi PT Abhipraya Nawasena Sejahtera (`docs/references/existing-assets/logos/company-logo/logo-ans.png`) memiliki identitas visual yang khas dan berbobot:
- **Bentuk Geometris & Sirkular:** Cincin spiral heksagonal luar yang dinamis mengelilingi inti sirkular bioteknologi/sel molekuler.
- **Karakter Warna Inti:**
  - *Deep Forest / Emerald Teal:* Melambangkan stabilitas, sains biologis, presisi laboratorium, dan pertumbuhan berkelanjutan.
  - *Vibrant Gold & Amber Orange:* Melambangkan energi, inovasi, kehangatan kemitraan, dan standar mutu tinggi.
  - *Clean White & Deep Slate:* Memberikan kontras profesional, keterbacaan tinggi, dan kemurnian klinis.
- **Kesan Bisnis:** Modern, kredibel, berbasis sains (*science-driven*), presisi, dan kokoh sebagai distributor resmi peralatan kesehatan, diagnostik, dan laboratorium terkemuka.

### 2.2. Design Personality & Visual Tone
- **Design Personality:** *Authoritative, Precise, Clean, Scientific, and Accessible.*
- **Visual Tone:**
  - Saat pertama kali dibuka, website harus memancarkan kredibilitas korporat B2B yang mapan (>15 tahun pengalaman industri).
  - Bersih dan lapang (*generous whitespace*), menghindari elemen visual dekoratif yang berlebihan, "gimmick" animasi yang mengganggu, atau saturasi warna neon yang melelahkan mata.
- **Brand Expression:**
  - Elemen hijau teal / zamrud bertindak sebagai jangkar visual (*primary anchor*) untuk navigasi, tombol utama, dan aksen data teknis.
  - Elemen kuning emas dan amber digunakan secara taktis sebagai aksen konversi (*accent highlights*), penanda fokus, badge prinsipal unggulan, dan elemen interaksi.
  - Latar belakang menggunakan permukaan putih bersih (`#FFFFFF`) dan slate lembut (`#F8FAFC`) untuk menjaga kenyamanan membaca spesifikasi teknis.
- **UX Philosophy:**
  - *Clarity Over Novelty:* Navigasi katalog dan pencarian produk harus jelas, cepat, dan deterministik.
  - *Zero-Friction Conversion:* Pengguna dapat bertransisi dari penemuan produk ke permintaan penawaran harga (*quotation*) dalam 2-3 langkah tanpa hambatan.
  - *Server-First Lightweight:* UI dioptimalkan untuk server-side rendering murni tanpa beban bundle JavaScript yang lambat.

---

## 3. Website-Wide Design System

### 3.1. Color System & Semantic Tokens

Sistem warna disusun berbasis semantic token dengan kontras rasio yang memenuhi standar **WCAG 2.2 AA** (rasio minimal 4.5:1 untuk normal text dan 3:1 untuk large text/UI components).

```
+---------------------------------------------------------------------------------------+
|                                  ANS SEMANTIC PALETTE                                 |
+---------------------------------------------------------------------------------------+
| PRIMARY (Teal/Emerald)  | #0F766E (Primary)     | #115E59 (Hover)    | #134E4A (Active) |
| ACCENT (Amber/Gold)     | #D97706 (Accent)      | #B45309 (Hover)    | #FEF3C7 (Surface)|
| SECONDARY (Navy Slate)  | #0F172A (Heading/Dark)| #1E293B (Surface)  | #334155 (Muted)  |
| NEUTRAL SURFACES        | #FFFFFF (Base Surface)| #F8FAFC (Subtle)   | #F1F5F9 (Border) |
| SEMANTIC FEEDBACK       | #15803D (Success)     | #B91C1C (Danger)   | #0369A1 (Info)   |
+---------------------------------------------------------------------------------------+
```

#### Token Color Mapping (HEX & Tailwind Utility Equivalents)

| Token Name | HEX Value | Tailwind Utility Class | Deskripsi Penggunaan |
|---|---|---|---|
| `brand-primary` | `#0F766E` | `bg-teal-700` / `text-teal-700` | Warna identitas utama: header accents, primary CTA buttons, active state highlights. |
| `brand-primary-hover` | `#115E59` | `bg-teal-800` / `text-teal-800` | State hover untuk primary buttons dan tautan interaktif utama. |
| `brand-primary-dark` | `#134E4A` | `bg-teal-900` | State active/pressed dan footer branding accents. |
| `brand-primary-surface`| `#F0FDFA` | `bg-teal-50` | Latar belakang badge aktif, filter highlight container, alert info surface. |
| `brand-accent` | `#D97706` | `bg-amber-600` / `text-amber-600` | Aksen konversi: badge "Principal Baru", tombol CTA sekunder, rating/featured indicator. |
| `brand-accent-hover` | `#B45309` | `bg-amber-700` | Hover state untuk elemen beraksen amber. |
| `brand-accent-surface`| `#FEF3C7` | `bg-amber-100` | Latar badge aksen khusus dan callout highlight. |
| `text-primary` | `#0F172A` | `text-slate-900` | Warna teks utama: judul `<h1>` s.d. `<h6>`, nama produk, label penting. |
| `text-secondary` | `#475569` | `text-slate-600` | Warna teks pendukung: paragraf narasi, deskripsi produk, subtitle. |
| `text-muted` | `#64748B` | `text-slate-500` | Warna teks sekunder: metadata, breadcrumb, caption, placeholder formulir. |
| `surface-base` | `#FFFFFF` | `bg-white` | Latar belakang utama halaman, kartu produk, form container, modal drawer. |
| `surface-subtle` | `#F8FAFC` | `bg-slate-50` | Latar belakang selang-seling section, table header, drawer background. |
| `surface-muted` | `#F1F5F9` | `bg-slate-100` | Latar belakang thumbnail container, divider bar, disabled input surface. |
| `border-subtle` | `#E2E8F0` | `border-slate-200` | Garis batas kartu, pemisah section, batas tabel data. |
| `border-focus` | `#0F766E` | `focus:border-teal-700` | Garis batas input saat state aktif/terfokus. |
| `feedback-success` | `#15803D` | `text-green-700` / `bg-green-50` | Notifikasi permohonan quotation sukses, status closed. |
| `feedback-danger` | `#B91C1C` | `text-red-700` / `bg-red-50` | Pesan error validasi formulir, badge status New. |
| `feedback-warning` | `#B45309` | `text-amber-700` / `bg-amber-50` | Peringatan throttling, status Contacted. |
| `feedback-info` | `#0369A1` | `text-sky-700` / `bg-sky-50` | Pesan petunjuk, status Quoted. |

---

### 3.2. Typography System

Tipografi menggunakan font sans-serif modern berbobot klinis, berkarakter tegas, dan memiliki keterbacaan luar biasa pada layar desktop maupun mobile.

- **Primary Font Family:** `Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`
- **Monospace Font Family (Technical Data/Codes):** `ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace`

#### Type Hierarchy Scale

| Elemen / Level | Ukuran Font (Desktop) | Ukuran Font (Mobile) | Line Height | Font Weight | Letter Spacing |
|---|---|---|---|---|---|
| **Display / Hero H1** | `48px` (`3rem`) | `32px` (`2rem`) | `1.15` | `700` (Bold) | `-0.025em` |
| **Section Title H2** | `32px` (`2rem`) | `24px` (`1.5rem`) | `1.25` | `700` (Bold) | `-0.02em` |
| **Card Title / Subtitle H3**| `20px` (`1.25rem`) | `18px` (`1.125rem`) | `1.35` | `600` (SemiBold) | `-0.01em` |
| **Sub-heading / Feature H4**| `16px` (`1rem`) | `15px` (`0.9375rem`)| `1.4` | `600` (SemiBold) | `0em` |
| **Body Text (Regular)** | `16px` (`1rem`) | `15px` (`0.9375rem`)| `1.6` | `400` (Regular) | `0em` |
| **Body Text (Small/Lead)**| `14px` (`0.875rem`)| `14px` (`0.875rem`)| `1.5` | `400` / `500` | `0em` |
| **Caption / Badge / Label**| `12px` (`0.75rem`) | `12px` (`0.75rem`) | `1.4` | `600` (SemiBold) | `0.025em` |
| **Button Text** | `15px` (`0.9375rem`)| `15px` (`0.9375rem`)| `1` | `600` (SemiBold) | `0.01em` |
| **Technical Data / Table** | `14px` (`0.875rem`)| `13px` (`0.8125rem`)| `1.4` | `400` / `500` | `0em` |

---

### 3.3. Spacing Scale

Sistem spasi mengadopsi skala Tailwind standar berbasis kelipatan 4px:

- **`space-1` (4px):** Jarak mikro antar ikon dan teks.
- **`space-2` (8px):** Padding badge, gap tombol kecil, gap thumbnail galeri.
- **`space-3` (12px):** Padding input formulir vertikal, spacing list item rapat.
- **`space-4` (16px):** Padding kartu produk internal, gap elemen navigasi.
- **`space-6` (24px):** Padding kartu besar, gutter grid katalog produk.
- **`space-8` (32px):** Jarak antar section kecil, padding modal drawer.
- **`space-12` (48px):** Margin vertikal antar komponen utama.
- **`space-16` (64px):** Padding vertikal section halaman desktop (`py-16`).
- **`space-24` (96px):** Padding hero section dan footer korporat.

---

### 3.4. Layout & Grid Architecture

- **Max Content Width:** `1280px` (`max-w-7xl`) dengan horizontal padding responsif:
  - Mobile (`< 640px`): `px-4` (16px).
  - Tablet (`640px - 1023px`): `px-6` (24px).
  - Desktop (`>= 1024px`): `px-8` (32px).
- **Section Vertical Padding:**
  - Mobile: `py-10` s.d. `py-12` (40px - 48px).
  - Desktop: `py-16` s.d. `py-20` (64px - 80px).
- **Katalog 2-Kolom Layout (Desktop `>= 1024px`):**
  - Kolom Filter Kiri: Lebar tetap `280px` (`w-70` / `flex-shrink-0`).
  - Kolom Grid Kanan: Sisa ruang fleksibel (`flex-1`).
- **Grid Kartu Produk:**
  - Mobile (`< 640px`): `grid-cols-1` (1 kartu penuh per baris).
  - Tablet (`640px - 1023px`): `grid-cols-2` (2 kartu simetris per baris).
  - Desktop (`>= 1024px`): `grid-cols-3` s.d. `grid-cols-4` (gap: `24px`).

---

### 3.5. Border Radius Scale

Menjaga karakter profesional, presisi, dan tidak kekanak-kanakan:
- **`rounded-none` (0px):** Elemen tabel data murni.
- **`rounded-sm` (2px):** Checkbox, radio custom indicator.
- **`rounded-md` (6px):** Input formulir, select dropdown, badge kecil.
- **`rounded-lg` (8px):** Tombol aksi (Buttons), kartu produk (Product Cards), filter container.
- **`rounded-xl` (12px):** Modal slide-over drawer, hero banner container, image highlight card.
- **`rounded-full` (9999px):** Avatar manajemen, language switcher pill, counter badge.

> [!NOTE]
> Menghindari penggunaan radius ekstrim (`rounded-3xl` / pill-shaped cards) pada kartu produk atau container bisnis agar tetap mempertahankan kesan B2B laboratorium yang solid.

---

### 3.6. Elevation & Shadows

Shadow digunakan secara halus (*subtle elevation*) murni untuk membedakan lapisan permukaan tanpa kesan melayang berlebihan:
- **`shadow-sm` (Subtle):** Kartu produk default (`box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05)`).
- **`shadow-md` (Hover/Floating):** Kartu produk saat hover, dropdown navbar, sticky action bar.
- **`shadow-lg` (Overlays):** Slide-over filter drawer mobile, image preview modal.
- **`shadow-none`:** Elemen tabel spesifikasi teknis, border-based dividers.

---

### 3.7. Iconography System

- **Icon Set:** Heroicons (Outline style untuk interface publik standar, Solid style untuk active indicators/checkmarks).
- **Stroke Width:** `1.5px` s.d. `2px` konsisten.
- **Standard Sizing Scale:**
  - Micro / Inline Icon: `16px x 16px` (`w-4 h-4`).
  - Standard Button / Action Icon: `20px x 20px` (`w-5 h-5`).
  - Section / Feature Header Icon: `24px x 24px` (`w-6 h-6`).
  - Core Values Feature Icon: `32px x 32px` (`w-8 h-8`).

---

## 4. Information Architecture → UI Screen Blueprints

### 4.1. Beranda (Home Page — `/{locale}`)

- **Tujuan Halaman:** Menyajikan pintu gerbang utama kredibilitas ANS, menonjolkan lini produk unggulan, dan memfasilitasi navigasi cepat ke katalog.
- **Primary User Goal:** Memahami kapabilitas ANS dan menemukan produk diagnostik/laboratorium yang dibutuhkan.
- **Primary CTA:** *"Jelajahi Katalog Produk"* (mengarah ke `/{locale}/products`).
- **Secondary CTA:** *"Pelajari Profil Kami"* (mengarah ke `/{locale}/about`) dan *"Hubungi Sales / WhatsApp"*.
- **Section Hierarchy & Layout Flow:**
  1. **Header Global:** Logo, Navigasi Utama, Switcher Bahasa `ID | EN`, CTA Kontak Cepat.
  2. **Hero Banner Carousel (SPEC-03 & SPEC-07):** Slider gambar produk berasio lebar desktop dan optimal mobile, teks judul tebal, sub-deskripsi, dan tombol CTA. Autoplay 5-6 detik dengan pause on hover/touch.
  3. **Company Highlights Strip:** Ringkasan nilai kunci: >15 Tahun Pengalaman, Mitra Prinsipal Resmi Dunia, Standar Mutu Terjamin, Layanan Dukungan Teknis.
  4. **Pilar Portofolio & Featured Products:** Grid 3-4 kartu produk unggulan teratas dengan badge kategori dan tautan langsung ke detail.
  5. **Showcase Prinsipal Resmi & Klien (Marquee/Grid):** Logo Merck, Neogen, Era Biology, dan institusi klien korporat.
  6. **Call to Action Section:** Banner konversi konsultasi teknis B2B menuju form penawaran.
  7. **Footer Global:** Informasi legalitas, alamat Mensana Tower, kontak telepon/WA/email, quick links.

---

### 4.2. Tentang Kami (About Us — `/{locale}/about`)

- **Tujuan Halaman:** Membangun kepercayaan institusional melalui pemaparan sejarah perusahaan, visi-misi, 6 nilai inti resmi, dan profil pimpinan.
- **Primary User Goal:** Memvalidasi kredibilitas, integritas, dan legalitas PT Abhipraya Nawasena Sejahtera.
- **Primary CTA:** *"Lihat Portofolio Produk"* (`/{locale}/products`).
- **Section Hierarchy & Layout Flow:**
  1. **Page Header Banner:** Judul *"Tentang PT Abhipraya Nawasena Sejahtera"* dengan breadcrumb navigasi.
  2. **Sejarah & Komitmen Mutu:** Narasi profil korporat bersumber dari model singleton `CompanyProfile`.
  3. **Visi & Misi:**
     - Kotak Visi: Tipografi terkemuka dengan latar belakang `brand-primary-surface`.
     - Kotak Misi: Daftar poin terstruktur dengan ikon centang teal.
  4. **6 Core Values ANS (Spiral Nilai):**
     - Grid 3 kolom (desktop) / 2 kolom (tablet) / 1 kolom (mobile) menampilkan 6 kartu nilai: *Customer Focus, Innovative, Integrity, Collaborative, Commitment, Agility*.
  5. **Jajaran Manajemen (Founders):**
     - Menampilkan profil manajemen yang berstatus `is_active = true`. Jika nonaktif, section secara anggun tidak dirender tanpa menyisakan ruang kosong.

---

### 4.3. Katalog Produk (Product Catalog — `/{locale}/products`)

- **Tujuan Halaman:** Direktori komprehensif produk laboratorium dan diagnostik dengan sistem pemfilteran ganda yang cepat dan presisi.
- **Primary User Goal:** Menemukan produk spesifik berdasarkan kategori produk dan/atau brand prinsipal tanpa kebingungan.
- **Primary CTA:** *"Lihat Detail Produk"* pada setiap kartu produk.
- **Filter Mechanics:** Single-select per dimensi dengan logika persilangan **AND** (`?category=...&brand=...`).
- **Section Hierarchy & Layout Flow:**
  1. **Page Header:** Judul *"Katalog Produk"* / *"Product Catalog"* dengan deskripsi singkat dan breadcrumb.
  2. **Mobile Filter Trigger Bar (`< 1024px`):** Tombol sticky filter *"Filter Produk"* dengan badge jumlah filter aktif dan dropdown pengurutan default.
  3. **Active Filter Chips Area:** Menampilkan badge filter yang sedang aktif (`[ Kategori: Mikrobiologi (x) ]`, `[ Brand: Merck (x) ]`, dan tombol *"Reset Semua"*).
  4. **Main Catalog Area (2 Kolom Desktop):**
     - **Sisi Kiri (Sidebar Filter Desktop `>= 1024px`):**
       - List Kategori Produk aktif dengan penanda highlight dan radio/checkbox visual.
       - List Brand/Prinsipal aktif.
       - Tombol *"Reset Semua Filter"*.
     - **Sisi Kanan (Product Grid & Pagination):**
       - Counter total produk ditemukan (misal: *"Menampilkan 12 dari 36 Produk"*).
       - Grid produk responsif (1 kolom mobile, 2 kolom tablet, 3-4 kolom desktop).
       - Pagination Navigation bar (`12 produk per halaman`) yang mempertahankan query string.
  5. **Empty State Component:** Tampil jika kombinasi filter menghasilkan 0 produk, dilengkapi ilustrasi bersih dan tombol *"Reset Semua Filter"*.

---

### 4.4. Detail Produk (Product Detail — `/{locale}/products/{slug}`)

- **Tujuan Halaman:** Memberikan lembar spesifikasi teknis lengkap, foto produk berkualitas, dokumen brosur PDF, dan memicu permintaan penawaran harga.
- **Primary User Goal:** Mengevaluasi kesesuaian teknis alat/reagen dan meminta penawaran resmi.
- **Primary CTA:** *"Minta Penawaran Harga"* (mengarahkan ke `/{locale}/contact?product_id={id}`).
- **Secondary CTA:** *"Download Brosur Produk (PDF)"* dan *"Chat WhatsApp"* (`0822-614-614-00`).
- **Section Hierarchy & Layout Flow:**
  1. **Breadcrumb Navigation:** `Home > Produk > [Kategori] > [Nama Produk]`.
  2. **Top Product Overview (2 Kolom Desktop):**
     - **Sisi Kiri (Galeri Foto Interaktif):** Foto utama rasio 1:1/4:3 dengan priority LCP, carousel swipe mobile, dan deretan thumbnail interaktif di bawahnya.
     - **Sisi Kanan (Identitas & Quick Actions):**
       - Badge Brand & Kategori resmi.
       - Tag `<h1>` Nama Produk Terlokalisasi.
       - Ringkasan singkat produk (`summary_id` / `summary_en`).
       - Box Aksi Utama: Tombol *"Minta Penawaran Harga"* (Teal/Emerald solid) dan Tombol *"Download Brosur PDF"* (jika tersedia).
       - Tautan cepat *"Tanya via WhatsApp Resmi"*.
  3. **Spesifikasi Teknis Dinamis (Tabel JSON):**
     - Tabel 2 kolom bergaris rapi (`Parameter Teknis` vs `Nilai Spesifikasi`) yang mendukung horizontal scroll pada mobile.
  4. **Deskripsi Lengkap Produk:** Paragraf narasi detail mengenai aplikasi dan keunggulan produk.
  5. **Navigasi Kontekstual Bawah:** Tombol *"← Kembali ke Katalog Produk"* atau *"← Lihat Produk Lain di Kategori [Nama Kategori]"*.
  6. **Mobile Sticky Bottom Action Bar (`< 768px`):** Tombol melayang di bawah layar dengan tombol *"Minta Penawaran"* instan.

---

### 4.5. Mitra & Klien (Partners & Clients — `/{locale}/partners-clients`)

- **Tujuan Halaman:** Menampilkan portofolio prinsipal manufaktur resmi dan daftar klien institusional terkemuka.
- **Primary User Goal:** Memastikan bahwa ANS adalah distributor resmi terdaftar dengan jaringan prinsipal global dan rekam jejak terpercaya.
- **Section Hierarchy & Layout Flow:**
  1. **Page Header:** Judul *"Mitra Prinsipal & Klien Korporat"* dan pengantar kemitraan.
  2. **Official Principals Showcase:** Grid logo prinsipal resmi (Merck, Neogen, Era Biology) di dalam kartu putih seragam dengan deskripsi singkat masing-masing prinsipal.
  3. **Corporate Clients Showcase:** Grid logo klien korporat (farmasi, lab riset, universitas, rumah sakit) dengan rasio proporsional dan tag alt deskriptif.

---

### 4.6. Kontak & Permintaan Penawaran (Contact & Quotation — `/{locale}/contact`)

- **Tujuan Halaman:** Pusat komunikasi resmi dan antarmuka formulir pengiriman permintaan penawaran harga.
- **Primary User Goal:** Mengirimkan rincian kebutuhan pengadaan atau pertanyaan teknis kepada tim sales ANS.
- **Primary CTA:** *"Kirim Permintaan Penawaran"* (Submit Form).
- **Section Hierarchy & Layout Flow:**
  1. **Page Header:** Judul *"Hubungi Kami & Permintaan Penawaran"*.
  2. **Two-Column Layout (Desktop `>= 1024px`):**
     - **Kolom Kiri (Informasi Kontak & Peta):**
       - Alamat fisik: *Mensana Tower Lt. 15, Jl. Raya Kranggan Kav. 1, Cibubur*.
       - Tautan interaktif: Telepon `(021) 39722772`, WhatsApp `0822-614-614-00`, Email `admin@avenasa.co.id`.
       - Iframe sematan Google Maps Mensana Tower responsif (`aspect-video`).
     - **Kolom Kanan (Formulir Penawaran & Inquiry):**
       - **Product Context Banner:** Jika diakses dari Product Detail (`?product_id={id}`), menampilkan badge: *"Produk yang Diminta: [Nama Produk]"* dengan tombol hapus konteks.
       - Input Fields: Nama Lengkap (*), Alamat Email (*), Telepon/WhatsApp (opsional), Nama Perusahaan/Institusi (opsional), Subjek Permintaan (*), Pesan Rincian Kebutuhan (*).
       - Honeypot anti-spam field tersembunyi (`website_url_hp`).
       - Tombol Submit dengan Alpine.js loading/disabled state debounce.
  3. **Success State Message:** Flash banner hijau pasca-submit sukses yang menginformasikan bahwa data telah tercatat dengan aman.

---

## 5. Reusable Component System

### 5.1. Global Components

#### 1. `Header / Navigation Bar`
- **Purpose:** Menyediakan navigasi utama, logo, language switcher, dan tautan kontak.
- **Anatomy:** Container `max-w-7xl`, Logo ANS kiri (`h-10 md:h-12`), Nav menu tengah, Language Switcher & Quick Contact kanan, Mobile Hamburger trigger.
- **States:** Default transparent/white surface, Scrolled state dengan subtle bottom border (`border-slate-200`) dan shadow ringan.
- **Responsive:** Menyusut menjadi compact header pada mobile dengan drawer menu vertikal.

#### 2. `Language Switcher (ID | EN)`
- **Purpose:** Pengalih bahasa instan yang menjaga path URL aktif.
- **Anatomy:** Segmented pill button container (`bg-slate-100 p-1 rounded-full`), teks `ID` dan `EN`.
- **States:** Active pill (`bg-teal-700 text-white font-semibold shadow-sm`), Inactive pill (`text-slate-600 hover:text-slate-900`).
- **Accessibility:** `aria-label="Pilih Bahasa / Select Language"`.

#### 3. `Button Component`
- **Variants:**
  - `Primary Button`: `bg-teal-700 hover:bg-teal-800 text-white font-semibold px-5 py-2.5 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-700 focus:ring-offset-2 transition-all`.
  - `Secondary Button`: `bg-amber-600 hover:bg-amber-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow-sm focus:ring-2 focus:ring-amber-600 focus:ring-offset-2 transition-all`.
  - `Outline Button`: `border border-slate-300 hover:border-teal-700 text-slate-700 hover:text-teal-700 bg-white font-medium px-4 py-2 rounded-lg transition-all`.
  - `Text/Ghost Button`: `text-teal-700 hover:text-teal-800 font-semibold px-3 py-2 rounded-md hover:bg-teal-50 transition-all`.
- **States:** Default, Hover, Focused (`focus:ring-2`), Active/Pressed, Disabled (`opacity-50 cursor-not-allowed`), Loading (spinner icon).
- **Touch Target:** Tinggi minimal `44px` di mobile.

#### 4. `Badge / Chip Component`
- **Variants:**
  - `Category Badge`: `bg-teal-50 text-teal-800 border border-teal-200 text-xs font-semibold px-2.5 py-1 rounded-md`.
  - `Brand Badge`: `bg-slate-100 text-slate-700 border border-slate-200 text-xs font-medium px-2 py-0.5 rounded-md`.
  - `New Principal Badge`: `bg-amber-100 text-amber-900 border border-amber-300 text-xs font-bold px-2 py-0.5 rounded-full`.
  - `Active Filter Chip`: `bg-teal-700 text-white text-xs font-medium pl-3 pr-2 py-1.5 rounded-full inline-flex items-center gap-1.5`.

#### 5. `Breadcrumb Navigation`
- **Purpose:** Menunjukkan hierarki lokasi halaman saat ini.
- **Anatomy:** `<nav aria-label="Breadcrumb">` dengan teks tautan abu-abu (`text-slate-500 hover:text-teal-700`), separator chevron (`w-4 h-4 text-slate-400`), dan halaman aktif berlabel tegas (`text-slate-900 font-medium`).

---

### 5.2. Product Catalog Components

#### 6. `Product Card`
- **Purpose:** Menampilkan ringkasan produk pada grid katalog dan beranda.
- **Anatomy:**
  - Container: `bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md hover:border-teal-300 transition-all flex flex-col h-full`.
  - Image Container: Aspect ratio 1:1 (`aspect-square`) atau 4:3 berlatar putih/slate-50 dengan CSS `object-contain p-4`.
  - Content Body: Padding `p-4` atau `p-5`, badge kategori/brand di atas, judul produk (`text-slate-900 font-semibold text-base line-clamp-2 hover:text-teal-700`), ringkasan produk (`text-slate-500 text-sm line-clamp-2 mt-1`).
  - Card Footer: Tombol aksi *"Lihat Detail"* di bagian bawah dengan transisi panah.
- **Fallback:** Placeholder siluet produk berlogo resmi ANS jika `primary_image_path` kosong.

#### 7. `Sidebar Filter (Desktop)`
- **Purpose:** Panel filter kategori dan brand permanen pada layar `>= 1024px`.
- **Anatomy:** Kartu filter `bg-white border border-slate-200 rounded-lg p-5 space-y-6`:
  - Section Header: *"Kategori Produk"* dengan tombol clear khusus kategori.
  - List Kategori: Link vertikal dengan indikator bullet/check aktif (`text-teal-700 font-semibold bg-teal-50 px-2.5 py-1.5 rounded-md`).
  - Section Header: *"Brand / Prinsipal"*.
  - List Brand: Link vertikal dengan visual brand logo mini/teks.
  - Tombol Reset: *"Reset Semua Filter"* di bagian bawah.

#### 8. `Slide-over Filter Drawer (Mobile)`
- **Purpose:** Panel filter modal dari bawah/samping layar untuk mobile `< 1024px`.
- **Anatomy:** Backdrop gelap `bg-black/50`, drawer container `bg-white max-h-[85vh] rounded-t-xl md:rounded-l-xl p-6 overflow-y-auto`:
  - Header: Judul *"Filter Produk"* dan tombol close (X).
  - Body: Pilihan Kategori dan Brand dengan touch target lapang (`min-h-[44px]`).
  - Footer Bar: Tombol *"Terapkan Filter"* (Primary solid full width) dan *"Reset"*.

---

### 5.3. Product Detail Components

#### 9. `Product Image Gallery`
- **Purpose:** Menampilkan foto utama resolusi tinggi dan koleksi foto tambahan per produk.
- **Anatomy:**
  - Main Image Frame: `aspect-square md:aspect-4/3 bg-white border border-slate-200 rounded-lg p-6 flex items-center justify-center relative overflow-hidden`.
  - Thumbnail Row: Deretan thumbnail kotak (`w-16 h-16 rounded-md border p-1 cursor-pointer transition-all`), thumbnail aktif memiliki `border-2 border-teal-700 ring-2 ring-teal-100`.
  - Controls: Tombol prev/next floating di samping foto utama, counter teks `"Foto 1 dari 4"`.

#### 10. `Technical Specifications Table`
- **Purpose:** Menyajikan spesifikasi alat laboratorium dari kolom JSON dinamis.
- **Anatomy:**
  - Container: `overflow-x-auto border border-slate-200 rounded-lg`.
  - Table: `min-w-full divide-y divide-slate-200`.
  - Row Styling: Selang-seling baris (`even:bg-slate-50`), kolom parameter (`w-1/3 bg-slate-50/50 font-semibold text-slate-700 p-3.5 text-sm`), kolom nilai (`text-slate-800 p-3.5 text-sm`).

#### 11. `Mobile Sticky Bottom Action Bar`
- **Purpose:** Menjamin aksesibilitas instan tombol penawaran harga pada smartphone (`< 768px`).
- **Anatomy:** `fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-200 p-3 px-4 shadow-lg flex items-center gap-3`:
  - Tombol CTA: *"Minta Penawaran"* full width `bg-teal-700 text-white text-center font-semibold py-3 rounded-lg`.
  - WhatsApp Icon Button: Tautan sekunder langsung ke WA.
  - Safe Area Padding: Kompensasi padding `pb-safe` untuk iPhone/Android gesture bar.

---

### 5.4. Form & Conversion Components

#### 12. `Form Input & Textarea Group`
- **Anatomy:**
  - Label: `<label class="block text-sm font-semibold text-slate-700 mb-1">` dengan penanda asterik merah `(*)` untuk field wajib.
  - Input: `<input class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-700/20 text-base transition-all">`.
  - Helper / Error Text: `<p class="mt-1 text-xs text-red-600 font-medium">` terhubung dengan `aria-describedby`.

#### 13. `Product Context Badge (Form In-Context)`
- **Anatomy:** Container alert `bg-teal-50 border border-teal-200 rounded-lg p-3 flex items-center justify-between`:
  - Teks: *"Permintaan Penawaran untuk: [Nama Produk]"*.
  - Aksi: Tombol *"Hapus Konteks"* yang mengembalikan form ke inquiry umum.

---

## 6. Interaction & State Design

Seluruh elemen interaktif wajib memiliki definisi state yang jelas:

| State | Visual Behavior & Feedback |
|---|---|
| **Default** | Warna dan border normal sesuai design system. |
| **Hover** | Perubahan warna latar/border 1 tingkat lebih gelap (`bg-teal-800`, `border-teal-300`), shadow meningkat halus (`transition duration-150`). |
| **Focus Visible** | Outline ring 2px tebal dengan offset 2px (`focus:ring-2 focus:ring-teal-700 focus:ring-offset-2`). |
| **Active / Pressed** | Skala sedikit tertekan (`active:scale-[0.98]`) dan warna latar lebih pekat (`bg-teal-900`). |
| **Disabled** | Opacity 50% (`opacity-50 cursor-not-allowed pointer-events-none`). |
| **Loading / Submitting** | Teks tombol digantikan spinner loader berputar dengan teks *"Memproses..."*, tombol di-disabled via Alpine.js. |
| **Inline Validation Error** | Border input berubah menjadi merah (`border-red-500 focus:ring-red-500/20`), pesan error muncul di bawah field. |
| **Empty State** | Kartu berlatar slate-50 dengan ikon netral dan tombol aksi pemulihan (*recovery CTA*). |

---

## 7. Accessibility Specifications (WCAG 2.2 AA Compliance)

1. **Color Contrast:**
   - Seluruh teks normal memiliki rasio kontras terhadap background minimal `4.5:1` (`#0F172A` di atas `#FFFFFF` rasio > 14:1; `#0F766E` di atas `#FFFFFF` rasio > 4.5:1).
   - Teks tombol putih di atas `#0F766E` memiliki rasio kontras `4.6:1` (PASS AA).
2. **Keyboard Navigation & Focus Management:**
   - Seluruh tautan, tombol, kontrol carousel, opsi filter, dan input form dapat dijangkau penuh dengan tombol `Tab`.
   - Menggunakan `focus-visible` ring yang tidak pernah disembunyikan (`outline: none` dilarang tanpa pengganti focus ring).
   - Drawer mobile membatasi fokus keyboard (*focus trap*) saat terbuka dan dapat ditutup dengan tombol `ESC`.
3. **Form Accessibility:**
   - Setiap elemen `<input>` dan `<textarea>` memiliki elemen `<label>` eksplisit dengan atribut `for` yang berpasangan dengan `id` input.
   - Field wajib memiliki atribut `aria-required="true"`.
4. **Touch Target Sizing:**
   - Seluruh tombol aksi, thumbnail galeri, switcher bahasa, dan pemicu menu memiliki area sentuh minimal **`44px x 44px`** pada perangkat seluler.
5. **Prefers-Reduced-Motion:**
   - Seluruh transisi CSS dan animasi otomatis (seperti autoplay Hero Carousel) secara otomatis dinonaktifkan jika sistem operasi mendeteksi `@media (prefers-reduced-motion: reduce)`.

---

## 8. Image & Media Direction

### 8.1. Pedoman Format & Rasio Aspek (Aspect Ratios)

| Jenis Media | Rasio Aspek Rekomendasi | Resolusi Target Standar | Format Prioritas | Behavior & Fit |
|---|---|---|---|---|
| **Hero Banner (Desktop)** | `16:9` atau `21:9` | `1920 x 800 px` | WebP / JPEG | `object-cover`, eager LCP load |
| **Hero Banner (Mobile)** | `4:5` atau `1:1` | `800 x 800 px` | WebP / JPEG | `object-cover`, fallback to desktop |
| **Foto Utama Produk** | `1:1` (Square) | `800 x 800 px` | WebP / PNG | `object-contain`, latar putih bersih |
| **Galeri Foto Produk** | `1:1` atau `4:3` | `800 x 800 px` | WebP / JPEG | `object-contain` |
| **Logo Prinsipal / Klien** | Bebas (Max `h-16`) | `Max 300 x 120 px` | PNG transparan / WebP | `object-contain`, preservasi rasio |
| **Foto Manajemen / Founder**| `3:4` (Portrait) | `600 x 800 px` | WebP / JPEG | `object-cover rounded-lg` |

### 8.2. Placeholder Asset Treatment
- Jika foto produk atau logo belum diunggah oleh admin CMS, sistem menyajikan placeholder siluet profesional berlogo ANS dengan palet netral (`#F1F5F9` dan `#94A3B8`) yang ter-render via CSS/SVG tanpa broken image icon.

---

## 9. Motion & Micro-Interaction Strategy

Motion diterapkan secara konservatif murni untuk memberikan umpan balik status (*state feedback*) dan kenyamanan navigasi:
- **Transisi Standar:** Durasi cepat `150ms` s.d. `200ms` dengan kurva `ease-out` untuk hover kartu, tombol, dan warna teks.
- **Slide-over Drawer Transition:** Durasi `250ms` `ease-in-out` untuk pembukaan drawer filter seluler dan mobile navbar.
- **Hero Banner Transition:** Durasi `500ms` fade/cross-fade halus antar slide.
- **Larangan:** Dilarang menggunakan animasi scroll parallax berat, floating text 3D, atau scroll-jacking yang membebani browser seluler dan CPU shared hosting.

---

## 10. SEO, Performance & Shared Hosting Design Alignment

1. **Semantic HTML5 Foundation:**
   - Penggunaan struktur semantik baku: `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, dan `<footer>`.
   - Tepat satu tag `<h1>` per halaman yang memuat judul utama terlokalisasi.
2. **Core Web Vitals Optimization:**
   - **LCP (Largest Contentful Paint):** Foto utama produk dan hero banner slide pertama diberi prioritas muat tinggi (`loading="eager" fetchpriority="high"`).
   - **CLS (Cumulative Layout Shift):** Seluruh container gambar memiliki dimensi rasio aspek eksplisit (`aspect-square`, `aspect-video`) untuk mencegah pergeseran layout saat gambar selesai dimuat.
   - **FID / INP (Interactivity):** Seluruh logika UI dibangun di atas Alpine.js yang sangat ringan (< 15 KB gzipped), memastikan browser tidak mengalami blocking main thread.
3. **No Heavy Client Frameworks:**
   - Antarmuka dirancang murni untuk Blade server-rendered templates, tanpa ketergantungan pada React, Vue, Next.js, atau Inertia.

---

## 11. Conceptual Implementation Mapping

Berikut adalah pemetaan konseptual antara sistem desain dan tumpukan teknologi implementasi:

```
+---------------------------------------------------------------------------------------+
|                                IMPLEMENTATION MAPPING                                 |
+---------------------------------------------------------------------------------------+
| DESIGN SYSTEM TOKEN     ──► Tailwind CSS 4 utility classes (theme configuration)      |
| REUSABLE UI COMPONENT   ──► Laravel Blade Components (resources/views/components/...) |
| CLIENT MICRO-INTERACTION──► Alpine.js directives (x-data, x-show, x-cloak, @click)   |
| SERVER-SIDE DATA FLOW   ──► Laravel Blade Views + Eloquent Models + Query Scopes      |
| ADMINISTRATIVE CMS      ──► Filament 5.x Resources & Relation Managers                |
+---------------------------------------------------------------------------------------+
```

### 11.1. Pemetaan Komponen Blade Target
- `x-layout.app`: Master layout publik (Header, Meta SEO, Footer, Google Tag).
- `x-header`: Global navigation bar dan language switcher pill.
- `x-footer`: Global corporate footer dan informasi kontak resmi.
- `x-product-card`: Kartu produk pada grid katalog dan beranda.
- `x-catalog-sidebar`: Sidebar filter vertikal desktop.
- `x-catalog-drawer`: Slide-over modal drawer filter mobile berbasis Alpine.js.
- `x-gallery`: Galeri foto produk interaktif dengan thumbnail switcher.
- `x-specs-table`: Renderer tabel spesifikasi teknis dari data JSON.
- `x-button`: Tombol serbaguna dengan varian primary, secondary, outline, dan ghost.
- `x-badge`: Badge status, kategori, brand, dan filter chips.

---

## 12. Open Design Decisions & Rationale

| Area / Keputusan Desain | Kondisi di Phase 0 | Keputusan Desain Final (UI/UX) | Rationale Bisnis & Teknis |
|---|---|---|---|
| **Palette Teal & Amber** | Diderivasi dari logo resmi ANS | Primary: Teal `#0F766E`, Accent: Amber `#D97706` | Menghadirkan keseimbangan antara sains laboratorium (teal) dan kehangatan/energi kemitraan (amber). |
| **Grid Katalog Default** | SPEC-06 menetapkan 12 item/page | Desktop: 3 s.d. 4 kolom, Tablet: 2 kolom, Mobile: 1 kolom | Memberikan rasio kartu produk yang simetris dan ruang baca yang nyaman untuk nama produk farmasi/medis yang panjang. |
| **Mobile Sticky Action Bar** | Diatur pada SPEC-08 | Bilah lengket fixed-bottom pada viewport `< 768px` | Memastikan konversi prospek B2B tetap mudah dijangkau satu tangan saat membaca tabel spesifikasi panjang di ponsel. |
| **Management Inactive Handling** | Sesuai keputusan klien | Section Manajemen di About page disembunyikan secara bersih jika 0 record aktif | Mencegah tampilan broken/placeholder kosong pada fase awal peluncuran sebelum foto HD pimpinan siap. |
| **Single-Select Filter UI** | Sesuai SPEC-06 | Radio/bullet-style links dengan reset per dimensi | Menjaga kesederhanaan navigasi query HTTP GET dan kompatibilitas shared hosting tanpa query kombinasi yang rumit. |

---

## 13. Figma Make Readiness Checklist

Dokumen spesifikasi UI/UX ini telah memenuhi seluruh kriteria kesiapan sebagai sumber acuan (*Source of Truth*) bagi pembuatan visual prototype di **Figma Make**:

- [x] **Brand & Color System:** Palet lengkap dengan semantic token HEX dan Tailwind classes.
- [x] **Typography Scale:** Hierarki font Inter lengkap dari Display H1 hingga Caption dengan ukuran font, line height, dan font weight terukur.
- [x] **Layout & Spacing Grid:** Grid system responsif (1280px max-width, padding desktop/tablet/mobile, gutter, section spacing).
- [x] **Component Anatomy & Specs:** Blueprint terperinci untuk 13 komponen antarmuka kunci beserta varian dan state.
- [x] **Page Flow & Wireframe Hierarchies:** Struktur seksional untuk ke-6 halaman publik resmi (Home, About, Catalog, Product Detail, Partners & Clients, Contact).
- [x] **Mobile-First Responsive Behavior:** Panduan adaptasi tata letak pada breakpoint Mobile (<640px), Tablet (640-1023px), dan Desktop (>=1024px).
- [x] **Accessibility & Interactions:** Panduan kontras WCAG 2.2 AA, focus states, touch targets minimal 44px, dan micro-interactions.

---

*(Dokumen UI/UX Design Specification selesai dibuat dan terkunci sebagai fondasi desain resmi sebelum implementasi kode frontend dimulai.)*
