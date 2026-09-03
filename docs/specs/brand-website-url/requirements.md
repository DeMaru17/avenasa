# Requirements: Brand Website URL Integration on Public Pages

## 1. Problem Statement & Context
Tabel `brands` dan model `Brand` memiliki kolom `website_url` yang menyimpan tautan resmi website principal global. Di database server produksi, data `website_url` telah terisi lengkap. Namun, pada antarmuka publik (frontend), sistem belum menampilkan tautan ataupun tombol untuk mengakses website resmi principal tersebut.

Pengguna menginginkan peningkatan khusus pada antarmuka publik dengan menerapkan dua integrasi utama (Pilihan 1 & Pilihan 3):
1. **Halaman Mitra Bisnis (`/partners-clients`)**: Menampilkan tombol/tautan website resmi pada kartu masing-masing principal secara berdampingan (*dual action*) dengan tombol "Lihat Produk", serta menjadi aksi utama jika brand belum memiliki produk terkait.
2. **Halaman Detail Produk (`/products/{slug}`)**: Menampilkan tautan/badge eksternal ke website resmi principal di samping badge nama brand dan memperkaya JSON-LD Schema.org SEO.
3. **Deployment Package**: Mengompilasi dan menyalin perubahan ke folder update baru (`C:\laragon\www\avenasa-update-4`) dengan manifest, checklist, dan struktur siap upload cPanel hosting.

## 2. Functional Requirements (FR)

- **FR-01: Dual Action di Kartu Principal (`/partners-clients`)**
  - Untuk setiap brand yang memiliki `website_url`:
    - Jika memiliki produk (`products_count > 0`): Tampilkan tautan "Lihat Produk" (katalog ANS) dan tautan eksternal "Website Resmi ↗" / "Official Site ↗".
    - Jika tidak memiliki produk (`products_count == 0`): Tampilkan tautan "Kunjungi Website Resmi ↗" / "Visit Official Website ↗" sebagai CTA utama.
  - Untuk brand yang tidak memiliki `website_url`:
    - Jika memiliki produk: Tampilkan tautan "Lihat Produk" seperti sebelumnya.
    - Jika tidak memiliki produk dan tidak memiliki `website_url`: Tidak menampilkan baris aksi.

- **FR-02: Keamanan Tautan Eksternal**
  - Seluruh tautan eksternal ke `website_url` wajib menggunakan `target="_blank"` dan `rel="noopener noreferrer"` untuk mencegah serangan *tabnabbing* serta menjaga performa peramban.

- **FR-03: Tautan Website Resmi di Detail Produk (`/products/{slug}`)**
  - Jika produk memiliki brand dengan `website_url` yang tidak kosong, tampilkan badge/tautan "Situs Resmi ↗" / "Official Site ↗" tepat di samping badge nama brand.
  - Tautan memiliki atribut aksesibilitas (`title` / `aria-label`) dwibahasa (ID & EN).

- **FR-04: SEO & Structured Data Enrichment**
  - Pada skema JSON-LD `Product` di `resources/views/pages/products/show.blade.php`, tambahkan `'url' => $product->brand->website_url` pada entity `brand` jika kolom tersebut memiliki nilai.

- **FR-05: Lokalisasi & Responsivitas**
  - Teks antarmuka mendukung penuh Bahasa Indonesia (`/id`) dan Bahasa Inggris (`/en`).
  - Tata letak responsif pada tampilan mobile, tablet, dan desktop tanpa ada elemen yang bertumpuk (*wrap cleanly*).

- **FR-06: Paket Update Produksi (`avenasa-update-4`)**
  - Menyalin file yang dimodifikasi ke direktori `C:\laragon\www\avenasa-update-4\application\...`.
  - Membuat dokumentasi `DEPLOYMENT-MANIFEST.md`, `UPLOAD-CHECKLIST.md`, dan `ROLLBACK.md`.
