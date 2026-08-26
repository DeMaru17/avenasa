# BUKU MANUAL PENGGUNAAN
# WEBSITE & CONTENT MANAGEMENT SYSTEM
### PT ABHIPRAYA NAWASENA SEJAHTERA

---

## DAFTAR ISI

1. [Pendahuluan & Gambaran Umum](#1-pendahuluan--gambaran-umum)
2. [Akses & Autentikasi Admin (Login & Logout)](#2-akses--autentikasi-admin-login--logout)
3. [Navigasi & Dashboard CMS](#3-navigasi--dashboard-cms)
4. [Manajemen Kategori Produk (Categories)](#4-manajemen-kategori-produk-categories)
5. [Manajemen Brand / Principal (Brands)](#5-manajemen-brand--principal-brands)
6. [Manajemen Produk Lengkap (Products)](#6-manajemen-produk-lengkap-products)
7. [Manajemen Banner Beranda (Hero Banners)](#7-manajemen-banner-beranda-hero-banners)
8. [Manajemen Profil Perusahaan (Company Profile)](#8-manajemen-profil-perusahaan-company-profile)
9. [Manajemen Nilai Inti (Core Values)](#9-manajemen-nilai-inti-core-values)
10. [Manajemen Tim Pimpinan (Management)](#10-manajemen-tim-pimpinan-management)
11. [Manajemen Klien & Mitra (Clients)](#11-manajemen-klien--mitra-clients)
12. [Manajemen Permintaan Penawaran (Quotations / Inquiries)](#12-manajemen-permintaan-penawaran-quotations--inquiries)
13. [Manajemen Pengguna Administrator (Users)](#13-manajemen-pengguna-administrator-users)
14. [Panduan Pemeliharaan & Troubleshooting Pengguna](#14-panduan-pemeliharaan--troubleshooting-pengguna)

---

## 1. Pendahuluan & Gambaran Umum

Buku Manual Penggunaan ini disusun sebagai panduan operasional resmi bagi administrator dan staf PT Abhipraya Nawasena Sejahtera (ANS) dalam mengelola seluruh konten, katalog produk, galeri, profil perusahaan, serta data prospek/permintaan penawaran harga (*quotation*) yang masuk melalui situs web resmi ANS (**https://avenasa.co.id**).

Sistem pengelolaan konten (Content Management System / CMS) dibangun menggunakan teknologi **Filament 5.x** modern berbasis web yang intuitif, aman, dan responsif. Setiap pembaruan yang dilakukan melalui panel admin akan langsung tercermin secara *real-time* pada antarmuka publik situs web dwibahasa (Bahasa Indonesia & English).

### Prinsip Utama Pengelolaan Konten:
1. **Dukungan Dwibahasa (Bilingual):** Mayoritas entitas memiliki kolom input terpisah untuk Bahasa Indonesia (`ID`) dan Bahasa Inggris (`EN`). Disarankan untuk mengisi kedua bahasa agar pengunjung internasional mendapatkan pengalaman yang optimal.
2. **Kualitas Media:** Gunakan gambar berformat JPG, PNG, atau WebP dengan resolusi yang proporsional dan ukuran berkas maksimal 2 MB agar halaman web tetap cepat dimuat.
3. **Integritas Data:** Data riwayat transaksi penawaran harga (*quotation*) dipertahankan secara permanen sebagai arsip audit dan tidak dapat dihapus sembarangan dari tabel admin.

---

## 2. Akses & Autentikasi Admin (Login & Logout)

### 2.1 Alamat URL Panel Admin
Untuk mengakses panel administratif CMS:
* Buka peramban (browser) seperti Google Chrome, Mozilla Firefox, Microsoft Edge, atau Safari.
* Kunjungi tautan: `https://avenasa.co.id/admin`

[SCREENSHOT: Halaman Login Panel Admin — Form Email & Password]

### 2.2 Langkah Masuk (Login)
1. Masukkan **Alamat Email** resmi admin yang telah terdaftar (contoh: `admin@avenasa.co.id`).
2. Masukkan **Kata Sandi (Password)** akun Anda.
3. Klik tombol **Sign in** (Masuk).
4. Jika kredensial benar, Anda akan diarahkan langsung ke antarmuka utama Dashboard CMS.

> **Catatan Keamanan:**
> * Sistem dilengkapi proteksi brute-force otomatis dan enkripsi sesi aman.
> * Jangan pernah membagikan kata sandi Anda kepada pihak yang tidak berwenang.

### 2.3 Keluar dari Sistem (Logout)
1. Klik pada menu profil / nama Anda di pojok kanan atas atau bagian bawah bilah samping (sidebar).
2. Klik opsi **Sign out** (Keluar).
3. Anda akan dikembalikan ke halaman login admin.

---

## 3. Navigasi & Dashboard CMS

Setelah berhasil masuk, Anda akan melihat antarmuka panel Filament dengan tata letak menu di bilah samping kiri (sidebar):

[SCREENSHOT: Tampilan Dashboard Utama Panel Admin]

### Struktur Menu Navigasi:
* **Dashboard:** Halaman ikhtisar ringkas dan sambutan akun admin.
* **Catalog Management:**
  * *Categories:* Pengelolaan rumpun kategori produk laboratorium & kesehatan.
  * *Brands:* Pengelolaan mitra pabrikan / principal instrumen ilmiah.
  * *Products:* Pengelolaan detail spesifikasi, foto utama, galeri, dan brosur PDF produk.
* **Homepage:**
  * *Hero Banners:* Pengelolaan slider banner utama di halaman beranda.
* **Company Content:**
  * *Company Profile:* Slogan, visi, misi, narasi sejarah, alamat, kontak WhatsApp/telepon, dan peta Google Maps.
  * *Core Values:* 6 nilai integritas & komitmen pelayanan perusahaan beserta pemilihan ikon saintifik.
  * *Management:* Profil dewan komisaris dan direksi.
  * *Clients:* Logo dan nama institusi mitra/klien pengguna produk ANS.
* **Quotation / Inquiry Management:**
  * *Quotations:* Manajemen tiket prospek dan permintaan surat penawaran harga masuk, dilengkapi lencana (*badge counter*) notifikasi merah untuk status "New".
* **Settings:**
  * *Users:* Manajemen akun login dan hak akses administrator.

---

## 4. Manajemen Kategori Produk (Categories)

Menu ini digunakan untuk mengelompokkan katalog produk ke dalam divisi ilmiah yang terstruktur (contoh: *Microbiology*, *Diagnostics*, *Laboratory Equipment*, dll).

[SCREENSHOT: Halaman Kategori — Daftar Kategori & Form Tambah Kategori]

### 4.1 Menambah Kategori Baru
1. Klik menu **Catalog Management** → **Categories**.
2. Klik tombol **New category** (+ Buat Kategori) di sudut kanan atas.
3. Lengkapi formulir:
   * **Nama Kategori (ID):** Nama kategori dalam Bahasa Indonesia (contoh: `Mikrobiologi Industri`). *Wajib diisi.*
   * **Nama Kategori (EN):** Nama kategori dalam Bahasa Inggris (contoh: `Industrial Microbiology`). *Wajib diisi.*
   * **Urutan Tampilan (Sort Order):** Angka urutan (contoh: `1`, `2`, `3`). Angka lebih kecil akan muncul lebih awal di sidebar katalog publik.
   * **Status Aktif:** Geser toggle menjadi hijau (aktif) agar kategori muncul di katalog dan filter publik.
4. Klik **Create** (Simpan) atau **Create & create another** (Simpan & Buat Lagi).

### 4.2 Mengedit & Menghapus Kategori
* **Edit:** Klik baris kategori yang ingin diubah, lakukan penyesuaian teks/urutan, lalu klik **Save changes**.
* **Hapus:** Klik tombol aksi delete pada baris data.
* **Aturan Khusus Penghapusan:** Jika kategori masih memiliki produk aktif yang terhubung, sistem akan menolak penghapusan dengan notifikasi peringatan: *"Kategori ini masih memiliki produk terkait. Pindahkan atau hapus produk terlebih dahulu."*

---

## 5. Manajemen Brand / Principal (Brands)

Menu ini mengelola daftar prinsipal resmi (manufaktur produsen alat ilmiah kelas dunia) yang diwakili oleh ANS di Indonesia.

[SCREENSHOT: Halaman Brand — Daftar Principal & Form Input Brand]

### 5.1 Menambah Brand / Principal Baru
1. Klik menu **Catalog Management** → **Brands**.
2. Klik tombol **New brand**.
3. Isi kolom-kolom berikut:
   * **Nama Brand / Principal:** Nama resmi manufaktur (contoh: `Merck Millipore`, `Lovibond`, `Era Biology`). *Wajib diisi.*
   * **Website Resmi Principal:** Tautan lengkap diawali `https://` (contoh: `https://www.lovibond.com`). *Opsional.*
   * **Logo Resmi Principal:** Unggah logo berlatar transparan (format PNG/WebP, disarankan resolusi jernih, maksimal 2 MB). *Wajib diisi.*
   * **Deskripsi Principal (ID & EN):** Narasi ringkas profil dan keunggulan teknologi prinsipal. *Opsional.*
   * **Mitra Principal Baru (New Principal):** Aktifkan opsi ini khusus untuk mitra prinsipal yang baru bergabung guna menampilkan badge sorotan kemitraan baru.
   * **Urutan Tampilan & Status Aktif:** Tentukan nomor urut dan pastikan status aktif menyala.
4. Klik **Create**.

### 5.2 Dampak pada Website Publik
* Logo brand akan otomatis muncul pada **Slider Brand Beranda**, halaman **Mitra & Klien** (`/partners-clients`), serta menjadi filter utama pada **Katalog Produk** (`/products`).
* **Proteksi Hapus:** Brand yang memiliki keterkaitan dengan katalog produk tidak dapat dihapus sebelum relasi produknya dipindahkan.

---

## 6. Manajemen Produk Lengkap (Products)

Produk merupakan modul inti dari situs web ANS. Setiap produk menyajikan informasi teknis, foto utama, galeri multi-sudut, berkas PDF brosur yang dapat diunduh, serta tombol langsung untuk meminta penawaran harga (*Request Quotation*).

[SCREENSHOT: Halaman Product — Daftar Produk dengan Filter Kategori & Brand]

### 6.1 Langkah Membuat Produk Baru (Step-by-Step)
1. Klik menu **Catalog Management** → **Products**.
2. Klik tombol **New product** di sudut kanan atas.

[SCREENSHOT: Halaman Product — Form Create Product Bagian Atas]

3. **Bagian 1: Klasifikasi Produk**
   * **Kategori Produk:** Pilih kategori yang sesuai dari menu drop-down.
   * **Brand / Principal:** Pilih prinsipal produsen produk tersebut.

4. **Bagian 2: Identitas Produk**
   * **Nama Produk (ID):** Nama instrumen/reagen dalam Bahasa Indonesia (contoh: `Sistem Real-Time PCR Otomatis`).
   * **Nama Produk (EN):** Nama instrumen/reagen dalam Bahasa Inggris (contoh: `Automated Real-Time PCR System`).

5. **Bagian 3: Deskripsi & Ringkasan**
   * **Ringkasan Singkat (ID & EN):** Uraian ringkas 1–2 kalimat yang akan tampil pada kartu produk di halaman katalog.
   * **Deskripsi Lengkap (ID & EN):** Uraian komprehensif mengenai fitur unggulan, metode kerja, dan aplikasi pengujian klinis/industri.

6. **Bagian 4: Spesifikasi Teknis (Bilingual Key-Value)**
   * Klik tombol **+ Add to specifications** untuk menambah parameter teknis spesifik.
   * Isi:
     * *Parameter (ID):* contoh `Rentang Panjang Gelombang` | *Parameter (EN):* `Wavelength Range`
     * *Nilai (ID):* contoh `190 s.d. 1100 nm` | *Nilai (EN):* `190 to 1100 nm`
   * Anda dapat menambah baris spesifikasi sebanyak yang diperlukan (misal: Dimensi, Berat, Akurasi, Kapasitas Sampel).

7. **Bagian 5: Media & Dokumen Brosur**
   * **Foto Utama Produk:** Unggah foto produk berlatar putih bersih/transparan (rasio 1:1 persegi, format JPG/PNG/WebP, maks 2 MB).
   * **Berkas Brosur (PDF):** Unggah brosur resmi berformat PDF (maksimal 10 MB). Jika diunggah, tombol *"Unduh Brosur"* akan otomatis aktif di halaman produk publik.

8. **Bagian 6: Pengaturan & Visibilitas**
   * **Produk Unggulan (Featured):** Aktifkan jika ingin produk tampil di bagian *"Produk Unggulan"* pada halaman Beranda.
   * **Status Aktif:** Pastikan aktif agar dapat dicari dan dilihat publik.
   * **Urutan Tampilan:** Angka urutan dalam katalog.

9. Klik tombol **Create** di bagian bawah.

[SCREENSHOT: Halaman Product — Form Galeri Foto Tambahan]

### 6.2 Menambahkan Galeri Foto Tambahan (Product Gallery)
Setelah produk tersimpan (pada mode Edit Produk), gulir ke bagian bawah halaman pada tab **Galeri Foto Produk**:
1. Klik tombol **New foto galeri**.
2. Unggah foto tambahan (misal: sudut samping, panel kontrol, aksesori instrumen).
3. Masukkan keterangan foto (*caption ID & EN*).
4. Klik **Create**.

### 6.3 Aturan Proteksi Penghapusan Produk
* Jika sebuah produk pernah menerima permintaan penawaran (*Quotation*) dari pengunjung website, produk tersebut **TIDAK DAPAT DIHAPUS** secara permanen.
* Jika tombol hapus ditekan, sistem akan membatalkan aksi dan menampilkan pesan: *"Produk ini memiliki riwayat permintaan penawaran harga (Quotation). Nonaktifkan status produk alih-alih menghapus."*
* **Solusi:** Matikan tombol *Status Aktif (is_active)* pada produk tersebut agar produk tidak lagi tampil di website publik tanpa merusak riwayat transaksi.

---

## 7. Manajemen Banner Beranda (Hero Banners)

Modul ini mengelola tampilan visual utama pada layar pertama halaman depan website (Hero Slider).

[SCREENSHOT: Halaman Hero Banner — Form Konfigurasi Slide]

### Field & Pengaturan Banner:
1. **Judul Banner (ID & EN):** Headline teks promosi yang menarik dan profesional.
2. **Subjudul (ID & EN):** Penjelasan ringkas nilai solusi atau produk utama.
3. **Gambar Utama / Desktop:** Gambar horizontal beresolusi tinggi (rekomendasi: `1920 × 800 px`, maks 2 MB).
4. **Gambar Khusus Mobile (Opsional):** Gambar vertikal khusus layar ponsel (rekomendasi: `800 × 1000 px`, maks 2 MB) untuk tata letak responsif.
5. **Tombol Call To Action (CTA):**
   * *Teks Tombol (ID & EN):* contoh `Jelajahi Katalog` / `Explore Catalog`.
   * *Tautan Halaman Internal (button_url):* Path internal yang diawali `/` (contoh: `/products`, `/about`, `/contact`). Sistem akan secara otomatis mengarahkan pengunjung sesuai bahasa yang sedang aktif (`/id/products` atau `/en/products`).
6. **Status Aktif & Urutan:** Mengatur urutan pergantian slide banner.

---

## 8. Manajemen Profil Perusahaan (Company Profile)

Profil Perusahaan merupakan data tunggal (*singleton*) yang digunakan sebagai sumber data seluruh informasi legal, narasi visi-misi, dan kontak resmi di seluruh halaman website publik dan footer.

[SCREENSHOT: Halaman Company Profile — Form Visi Misi & Kontak]

### Pengaturan Data Profil:
* **Slogan Resmi (ID & EN):** Moto perusahaan yang tampil di bawah logo header/footer (contoh: *Memberdayakan Sains untuk Masa Depan Sejahtera*).
* **Profil & Sejarah (ID & EN):** Narasi latar belakang berdirinya ANS yang tampil pada halaman *Tentang Kami*.
* **Visi Perusahaan (ID & EN):** Pernyataan visi jangka panjang.
* **Misi Perusahaan (ID & EN):** Poin-poin misi. **Aturan pengisian:** Masukkan satu butir misi per baris (tekan Enter untuk baris baru). Sistem akan merendernya otomatis sebagai daftar bernomor/bullet yang rapi di website.
* **Alamat Kantor Resmi:** Alamat fisik gedung kantor operasional.
* **Nomor Telepon & WhatsApp:** Nomor resmi layanan pelanggan. Nomor WhatsApp ini langsung terhubung dengan tombol floating chat dan tombol kontak di website.
* **Email Resmi:** Alamat kontak publik (contoh: `admin@avenasa.co.id`).
* **URL Embed Google Maps:** Tautan `src` dari iframe Google Maps agar peta lokasi kantor muncul interaktif di halaman Kontak.

---

## 9. Manajemen Nilai Inti (Core Values)

Modul ini mengelola 6 nilai integritas, profesionalisme, dan komitmen pelayanan yang ditampilkan pada halaman *Tentang Kami* (`/about`).

[SCREENSHOT: Halaman Core Value — Pilihan Ikon Saintifik & Judul Nilai]

### Cara Mengelola Core Values:
1. Klik menu **Company Content** → **Core Values**.
2. Edit nilai yang ada atau buat baru.
3. **Pilih Ikon Visual Representatif:** Tersedia puluhan ikon saintifik terkurasi (contoh: *Shield Check* untuk integritas, *Beaker* untuk riset laboratorium, *Cpu Chip* untuk presisi teknologi, *Sparkles* untuk inovasi).
4. Masukkan **Judul Nilai (ID & EN)** dan **Uraian Penjelasan (ID & EN)**.
5. Atur **Urutan Tampilan** (1 sampai 6).

---

## 10. Manajemen Tim Pimpinan (Management)

Modul ini mendokumentasikan profil Dewan Komisaris, Direksi, dan pimpinan eksekutif PT Abhipraya Nawasena Sejahtera.

[SCREENSHOT: Halaman Management — Daftar Direksi & Form Input Profil]

### Pengaturan Data Pimpinan:
* **Nama Lengkap & Gelar:** Nama pimpinan beserta gelar akademik/profesional.
* **Foto Profil:** Foto resmi pimpinan (disarankan rasio 1:1 atau 3:4, maks 2 MB).
* **Jabatan Resmi (ID & EN):** contoh `Komisaris Utama` / `President Commissioner`, `Direktur` / `Director`.
* **Biografi / Riwayat Pengalaman (ID & EN):** Ringkasan pengalaman profesional dan kepemimpinan di industri alat kesehatan/laboratorium.
* **Status Aktif:** Menentukan apakah profil ditampilkan pada halaman *Tentang Kami*.

---

## 11. Manajemen Klien & Mitra (Clients)

Modul ini menampilkan institusi rumah sakit, laboratorium rujukan, universitas, dan korporasi industri yang telah menggunakan layanan dan produk ANS.

[SCREENSHOT: Halaman Clients — Grid Logo Klien & Form Tambah Klien]

### Menambah Klien Baru:
1. Klik menu **Company Content** → **Clients** → **New client**.
2. Masukkan **Nama Klien / Institusi** (contoh: `PT Kalbe Farma Tbk`, `RSUP Nasional Dr. Cipto Mangunkusumo`).
3. Unggah **Logo Klien** (format PNG transparan atau WebP, maks 2 MB).
4. Tentukan **Urutan Tampilan** dan pastikan **Status Aktif** menyala.
5. Klik **Create**.
6. Logo akan langsung tampil di *Client Showcase* Beranda dan halaman *Mitra & Klien* publik (`/partners-clients`).

---

## 12. Manajemen Permintaan Penawaran (Quotations / Inquiries)

Modul ini merupakan pusat penerimaan prospek bisnis dari formulir penawaran harga di website publik.

[SCREENSHOT: Halaman Quotation — Tabel Data Inquiries Masuk & Status Badges]

### 12.1 Alur Kerja Permintaan Penawaran (Workflow)
```
Pengunjung Website (Form Kontak / Produk)
   │
   ▼
Validasi Data & Filter Anti-Spam (Honeypot)
   │
   ▼
Data Tersimpan Aman di Database (Status: New) ───► Lencana Notifikasi Merah di CMS
   │
   ├──────────────────────────────┬──────────────────────────────┐
   ▼                              ▼                              ▼
Kirim Email Notifikasi       Kirim Email Konfirmasi       Kirim Event Konversi
ke Admin ANS (Reply-To Prospek)   ke Calon Klien             ke Google Analytics 4
```

### 12.2 Status Penanganan Prospek
Administrator dapat memperbarui status penanganan prospek pada setiap tiket quotation:

| Status | Warna Badge | Keterangan & Tindakan Admin |
| :--- | :--- | :--- |
| **New (Baru)** | 🔴 **Merah (Danger)** | Permintaan baru masuk dan belum ditindaklanjuti. Menambah angka counter pada menu admin. |
| **Contacted (Dihubungi)** | 🟡 **Kuning (Warning)** | Tim sales ANS sudah menghubungi prospek via Telepon, Email, atau WhatsApp. |
| **Quoted (Penawaran Terkirim)** | 🔵 **Biru (Info)** | Surat Penawaran Harga (SPH) resmi telah disusun dan dikirimkan ke calon pembeli. |
| **Closed (Selesai)** | 🟢 **Hijau (Success)** | Proses negosiasi selesai (berhasil menjadi pesanan / selesai konsultasi). |

[SCREENSHOT: Halaman Quotation — Detail View & Kolom Catatan Admin Internal]

### 12.3 Kolom Catatan Internal Admin (Admin Notes)
Pada halaman edit quotation, admin dapat mengisi **Catatan Internal Admin** (misal: *"Sudah dihubungi via WA tgl 24/08, prospek meminta diskon volume untuk 5 unit PCR"*). Catatan ini bersifat internal dan **tidak pernah terlihat oleh pengunjung website**.

### 12.4 Integritas & Keamanan Arsip
* Data quotation **tidak memiliki tombol hapus (*non-deletable*)** untuk memastikan rekam jejak audit dan riwayat prospek perusahaan tersimpan utuh dan tidak sengaja terhapus.

---

## 13. Manajemen Pengguna Administrator (Users)

Menu ini mengelola akun staf yang memiliki izin login ke dalam panel CMS Filament.

[SCREENSHOT: Halaman Users — Daftar Pengguna Admin & Form Edit Akun]

### 13.1 Menambah Administrator Baru
1. Buka menu **Settings** → **Users** → **New user**.
2. Masukkan **Nama Lengkap**.
3. Masukkan **Alamat Email** unik (contoh: `staff.sales@avenasa.co.id`).
4. Masukkan **Kata Sandi** dan **Konfirmasi Kata Sandi**.
5. Klik **Create**.

### 13.2 Mengubah Kata Sandi Sendiri / Pengguna Lain
1. Buka akun yang ingin diubah.
2. Pada kolom kata sandi, ketik kata sandi baru.
3. Jika tidak ingin mengubah kata sandi, biarkan kolom kata sandi **KOSONG**.
4. Klik **Save changes**.

---

## 14. Panduan Pemeliharaan & Troubleshooting Pengguna

Berikut panduan cepat saat menghadapi kendala operasional umum:

### 1. Gambar / Logo Tidak Tampil Setelah Diunggah
* **Penyebab:** Ukuran berkas melebihi 2 MB, atau koneksi terputus saat proses upload.
* **Solusi:** Kompres gambar menggunakan alat kompresi online (misal: TinyPNG/Squoosh) hingga berukuran di bawah 1 MB, lalu unggah ulang. Pastikan format berkas adalah `.jpg`, `.png`, atau `.webp`.

### 2. Perubahan Data di CMS Tidak Langsung Terlihat di Browser
* **Penyebab:** Peramban (browser) Anda menyimpan cache tampilan lama.
* **Solusi:** Lakukan *Hard Refresh* pada browser dengan menekan kombinasi tombol:
  * **Windows:** `Ctrl + F5` atau `Ctrl + Shift + R`
  * **Mac:** `Cmd + Shift + R`

### 3. Produk Tidak Muncul di Katalog Publik
* **Penyebab:** Status produk belum diaktifkan, atau Kategori / Brand yang menaungi produk tersebut dalam status **Nonaktif**.
* **Solusi:** Periksa status aktif produk, status aktif kategori terkait, dan status aktif brand terkait di CMS. Ketiganya harus dalam status **Aktif**.

### 4. Lupa Kata Sandi Login Admin
* **Solusi:** Hubungi Master Administrator / tim IT ANS untuk mereset kata sandi melalui panel Users atau akses database server.

---
*Manual Penggunaan CMS PT Abhipraya Nawasena Sejahtera — Versi 1.0 (Agustus 2026)*
