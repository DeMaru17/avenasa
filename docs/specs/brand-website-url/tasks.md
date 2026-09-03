# Tasks: Brand Website URL Integration on Public Pages

- [x] 1. Update kartu principal di `resources/views/components/partners-clients/principals.blade.php` dengan aksi ganda (*dual action*) dan fallback untuk brand tanpa produk.
- [x] 2. Update badge & link official site di `resources/views/components/product-detail/identity.blade.php`.
- [x] 3. Update JSON-LD schema di `resources/views/pages/products/show.blade.php`.
- [x] 4. Tulis unit / feature test di `tests/Feature/BrandWebsiteUrlTest.php` untuk memvalidasi render tombol, atribut keamanan (`target="_blank"`, `rel="noopener noreferrer"`), dan JSON-LD.
- [x] 5. Jalankan test suite dan Laravel Pint (`vendor/bin/pint --dirty --format agent`).
- [x] 6. Buat direktori dan struktur paket update produksi di `C:\laragon\www\avenasa-update-4` lengkap dengan `DEPLOYMENT-MANIFEST.md`, `UPLOAD-CHECKLIST.md`, dan `ROLLBACK.md`.
