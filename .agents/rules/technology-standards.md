---
trigger: always_on
---

Filament 5.x ONLY.

Do not generate Filament 3.x or Filament 4.x code.

Before implementing Filament functionality, verify the API against the installed Filament version and official Filament documentation.

Do not rely on remembered Filament APIs when version-specific behavior is involved.

Untuk setiap implementasi yang menggunakan Filament:

1. Periksa versi Filament yang benar-benar terpasang melalui composer.json / composer.lock.
2. Gunakan dokumentasi resmi Filament untuk major version tersebut.
3. Jangan menggunakan API berdasarkan asumsi atau pengetahuan dari Filament versi lain.
4. Jika terdapat perbedaan API antara versi yang diketahui Agent dan versi project, versi project selalu menjadi sumber kebenaran.
5. Jangan melakukan downgrade Filament untuk menyesuaikan kode yang dihasilkan Agent.
6. Jika tidak yakin terhadap API Filament 5, lakukan verifikasi terlebih dahulu sebelum menulis kode.