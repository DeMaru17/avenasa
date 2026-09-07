# Panduan Deployment Manual Fitur Articles (Zero-SSH Hostinger)

Dokumen ini berisi panduan langkah-demi-langkah deployment modul **Articles** pada lingkungan production PT Abhipraya Nawasena Sejahtera (ANS) di Hostinger Shared Hosting.

> [!IMPORTANT]
> Lingkungan production ANS tidak memiliki akses terminal / SSH. Seluruh tahapan deployment dilakukan secara visual melalui **Hostinger File Manager** dan **phpMyAdmin**.

---

## Ringkasan Struktur Direktori Target Production

```
/home/u290252867/domains/avenasa.co.id/
├── application/            <-- Laravel application root (Private, di luar web root)
└── public_html/            <-- Web root publik
    ├── build/              <-- Bundle aset Vite (CSS, JS, manifest.json)
    └── storage/            <-- Berkas unggahan publik
        ├── articles/
        │   ├── covers/     <-- Foto sampul artikel
        │   └── editor/     <-- Berkas gambar inline RichEditor
```

---

## Langkah Deployment

### STEP 1 — PENCADANGAN DATABASE (BACKUP)
1. Buka Hostinger Control Panel (hPanel).
2. Masuk ke menu **Databases** -> klik **phpMyAdmin** pada database produksi ANS.
3. Pilih database ANS di bilah kiri, lalu klik tab **Export** di bilah atas.
4. Pilih metode **Quick** dan format **SQL**, lalu klik **Export**. Simpan berkas cadangan `.sql` ke komputer lokal sebagai langkah preventif.

---

### STEP 2 — EKSEKUSI MIGRASI DATABASE (SQL MIGRATION)
1. Pada phpMyAdmin database ANS, klik tab **SQL** di bilah atas.
2. Buka berkas [deployment/migrations/create_articles_feature.sql](file:///c:/laragon/www/avenasa/deployment/migrations/create_articles_feature.sql) di komputer lokal.
3. Salin (*copy*) seluruh isi script SQL tersebut, lalu tempelkan (*paste*) ke dalam kotak query SQL di phpMyAdmin.
4. Klik tombol **Go** / **Kirim** untuk mengeksekusi script.
5. Verifikasi hasil eksekusi:
   - Pastikan tabel `articles` telah terbentuk dengan 17 kolom dan indeks yang tepat.
   - Pastikan tabel `article_product` telah terbentuk dengan foreign keys dan unique constraints.
   - Buka tabel `migrations`, pastikan terdapat 2 baris baru:
     - `2026_09_07_100001_create_articles_table`
     - `2026_09_07_100002_create_article_product_table`
     dengan nomor batch dinamis (`MAX(batch) + 1`).

---

### STEP 3 — UPLOAD SOURCE CODE APLIKASI
1. Buka **Hostinger File Manager**.
2. Masuk ke direktori:
   `/home/u290252867/domains/avenasa.co.id/application/`
3. Upload seluruh isi folder dari paket lokal:
   `avenasa-update-articles/application/`
   ke direktori `application/` production.
4. File yang diunggah meliputi:
   - `app/Filament/Resources/Articles/` (Seluruh resource, schemas, tables, relation managers, pages)
   - `app/Http/Controllers/ArticleController.php`
   - `app/Http/Controllers/HomeController.php`
   - `app/Http/Controllers/SitemapController.php`
   - `app/Models/Article.php`
   - `app/Models/Product.php`
   - `app/Services/LocalizationService.php`
   - `database/migrations/2026_09_07_100001_create_articles_table.php`
   - `database/migrations/2026_09_07_100002_create_article_product_table.php`
   - `lang/id.json` & `lang/en.json`
   - `resources/views/components/articles/card.blade.php`
   - `resources/views/components/home/latest-articles.blade.php`
   - `resources/views/components/header.blade.php`
   - `resources/views/components/footer.blade.php`
   - `resources/views/pages/articles/index.blade.php`
   - `resources/views/pages/articles/show.blade.php`
   - `resources/views/pages/home.blade.php`
   - `resources/views/sitemap.blade.php`
   - `routes/web.php`

> [!CAUTION]
> DILARANG mengunggah atau menimpa berkas `application/.env`. Konfigurasi environment production yang ada harus tetap terjaga.

---

### STEP 4 — UPLOAD HASIL BUILD FRONTEND (VITE ASSETS)
1. Di Hostinger File Manager, masuk ke direktori:
   `/home/u290252867/domains/avenasa.co.id/public_html/build/`
2. Upload seluruh isi dari folder paket lokal:
   `avenasa-update-articles/public_html/build/`
   (terdiri atas file `manifest.json` dan folder `assets/`).

---

### STEP 5 — PEMERIKSAAN & PEMBUATAN FOLDER STORAGE
1. Masuk ke direktori:
   `/home/u290252867/domains/avenasa.co.id/public_html/storage/`
2. Pastikan subdirektori berikut telah tersedia. Jika belum ada, buat folder baru:
   - `articles/covers/` (untuk foto sampul artikel)
   - `articles/editor/` (untuk gambar inline RichEditor)
3. Pastikan izin akses folder (*permissions*) adalah `0755` sehingga berkas unggahan dapat diakses secara publik oleh peramban web.

---

### STEP 6 — PEMBERSIHAN CACHE MANUAL (TANPA SSH)
Karena server tidak memiliki akses terminal untuk menjalankan `php artisan optimize:clear`, lakukan pembersihan cache framework secara manual melalui Hostinger File Manager:
1. Masuk ke direktori:
   `/home/u290252867/domains/avenasa.co.id/application/bootstrap/cache/`
2. Hapus berkas-berkas cache berikut jika ada:
   - `packages.php`
   - `services.php`
   - `routes-v7.php`
   - `config.php`
   - `events.php`
   *(Catatan: JANGAN menghapus folder `cache/` itu sendiri atau file `.gitignore`)*.
3. Masuk ke direktori:
   `/home/u290252867/domains/avenasa.co.id/application/storage/framework/views/`
4. Hapus berkas-berkas view cache yang berformat `*.php` agar template Blade yang baru segera dikompilasi ulang oleh Laravel.

---

### STEP 7 — PENGUJIAN & VERIFIKASI POST-DEPLOYMENT
1. **Verifikasi Admin Panel:**
   - Login ke `https://avenasa.co.id/admin`.
   - Buka menu **Company Content** -> **Articles**.
   - Klik **New Article**: Buat artikel uji, upload foto sampul, isi RichEditor (coba sisipkan gambar inline), hubungkan Related Products, isi waktu publikasi, dan simpan.
2. **Verifikasi Pengurutan Related Products:**
   - Buka halaman Edit artikel tersebut, lakukan drag-and-drop urutan produk pada tabel Relation Manager di bawah form. Simpan dan pastikan urutan pivot tersimpan.
3. **Verifikasi Halaman Publik:**
   - Buka `https://avenasa.co.id/id/articles` dan `https://avenasa.co.id/en/articles`.
   - Buka detail artikel dan periksa gambar sampul, inline image (pastikan tidak ada overflow di layar smartphone), dan kartu Related Products.
   - Coba switch bahasa pada header dan pastikan beralih ke slug artikel pasangan.
   - Buka Beranda `https://avenasa.co.id/id` dan periksa seksi Latest Articles.
4. **Verifikasi Sitemap:**
   - Buka `https://avenasa.co.id/sitemap.xml` dan pastikan URL artikel terdaftar.
