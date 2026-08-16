USER REQUIREMENT SPECIFICATION (URS)

Proyek: Pengembangan Website Company Profile & Katalog Produk

Klien: PT Abhipraya Nawasena Sejahtera (ANS)



1. Pendahuluan

Dokumen ini mendefinisikan spesifikasi teknis dan kebutuhan fungsional untuk pengembangan website resmi PT Abhipraya Nawasena Sejahtera (ANS). Website ini dirancang sebagai profil perusahaan dwibahasa dan katalog produk interaktif untuk menjangkau target pasar industri farmasi, bioteknologi, laboratorium, rumah sakit, dan sektor terkait lainnya.

2. Arsitektur & Teknologi (Tech Stack)

Pengembangan website menggunakan ekosistem terintegrasi yang berfokus pada kecepatan, SEO, dan kemudahan pengelolaan.

Backend Framework: Laravel (PHP).

Frontend (Tampilan Klien): Laravel Blade dikombinasikan dengan Tailwind CSS.

Admin Panel (Backend UI): Filament PHP.

Database: MySQL atau PostgreSQL.

Metode Interaksi Data: Classic Filter dengan parameter HTTP GET.

3. Kebutuhan Fungsional Utama

Sistem Bilingual (ID/EN): Pengguna dapat mengubah bahasa antarmuka secara keseluruhan melalui tombol pengalih bahasa di header.

Katalog Interaktif (Dual-Filter): Halaman direktori produk memiliki panel sisi (sidebar) yang memungkinkan pengunjung memfilter produk berdasarkan Kategori Produk dan Brand/Principal secara bersamaan menggunakan parameter URL (HTTP GET).

Halaman Detail Produk Spesifik: Setiap produk yang diklik dari grid katalog akan membuka Halaman Baru (New Page) yang memuat informasi spesifikasi lengkap dan tombol quotation.

Hero Banner Carousel: Halaman beranda memuat slider otomatis yang menampilkan visual lini produk unggulan.

Formulir Permintaan (Quotation): Formulir kontak yang terintegrasi dengan email perusahaan admin@avenasa.co.id dan dasbor Filament.

CMS Filament: Staf admin mengelola seluruh data melalui antarmuka Filament.

4. Arsitektur Halaman & Kebutuhan Konten

Beranda: Logo, navigasi dwibahasa, carousel produk, slogan perusahaan, dan pengantar operasional perusahaan.

Tentang Kami: Profil perusahaan, visi & misi bilingual, poin-poin nilai inti (Core Values), dan profil jajaran manajemen.

Katalog Produk: Sidebar filter ganda (Kategori dan Brand), grid kartu produk, dan halaman detail produk tunggal.

Mitra & Klien: Menampilkan logo-logo Principal dan Klien Korporat dalam format grid atau carousel berjalan.

Kontak: Alamat fisik, telepon, email, peta interaktif, dan formulir pesan.

5. Alur Pengguna (User Flow) Berdasarkan Modul

Bagian ini mendeskripsikan langkah-langkah dan pengalaman pengunjung saat berinteraksi dengan antarmuka website.

5.1. Modul Navigasi & Beranda

Pengguna membuka situs web ANS.

Sistem menampilkan halaman utama dengan Hero Banner Carousel yang bergerak secara otomatis menampilkan lini produk unggulan perusahaan.

Pengguna mengeklik tombol pengalih bahasa "ID | EN" di bagian atas halaman. Sistem seketika memuat ulang dan mengubah seluruh teks antarmuka, banner, dan deskripsi ke bahasa yang dipilih.

Pengguna mengeklik tombol "Pelajari Lebih Lanjut" atau CTA pada banner. Sistem mengarahkan pengguna langsung ke Modul Katalog Produk.

5.2. Modul Katalog Produk (Pencarian & Pemfilteran)

Pengguna mengeklik menu "Katalog Produk" pada bilah navigasi.

Sistem menampilkan tata letak halaman yang terbagi menjadi dua bagian utama: panel filter di sisi kiri dan grid kartu produk di sisi kanan.

Pengguna mengeklik salah satu nama kategori di bawah menu "Kategori Produk" (misalnya: Microbiology).

Sistem memuat ulang halaman dan secara spesifik hanya menampilkan daftar produk kategori Microbiology. Nama kategori di panel sisi akan diberi sorotan warna (highlight) sebagai penanda filter aktif.

Pada halaman yang sama, pengguna melanjutkan pencarian dengan mengeklik nama merek di bawah menu "Brand / Principal" (misalnya: Merck).

Sistem kembali memuat ulang halaman dan menampilkan hasil persilangan data, yaitu produk yang termasuk kategori Microbiology dan diproduksi oleh merek Merck.

Pengguna dapat mengeklik tautan "Reset Semua Filter" di panel sisi untuk menghapus semua kriteria pencarian dan kembali melihat daftar katalog utuh.

5.3. Modul Detail Produk

Setelah menemukan produk yang dicari di grid katalog, pengguna mengeklik area gambar, nama produk, atau tombol "Detail".

Sistem akan melakukan transisi dari halaman katalog menuju Halaman Baru (New Page) yang didedikasikan sepenuhnya untuk produk tersebut.

Di halaman baru ini, pengguna dapat membaca spesifikasi alat/media yang mendetail, melihat foto dalam ukuran lebih besar, dan meninjau informasi merek/kategori.

Pengguna mengeklik tombol "Minta Penawaran / Quotation" yang tersedia di halaman detail tersebut untuk dialihkan ke formulir kontak.

5.4. Modul Tentang Kami serta Mitra & Klien

Pengguna mengeklik menu "Tentang Kami". Sistem menampilkan profil sejarah operasional, visi-misi, 6 poin nilai inti (Core Values), dan biografi singkat jajaran pimpinan perusahaan.

Pengguna mengeklik menu "Mitra & Klien". Sistem menampilkan kumpulan visual logo-logo dari Principal (penyedia produk) dan daftar klien korporat secara informatif.

5.5. Modul Kontak & Permintaan Penawaran

Pengguna mengakses formulir ini melalui menu "Kontak" atau tombol "Quotation" dari Modul Detail Produk.

Pengguna mengisi informasi wajib ke dalam kolom yang disediakan (Nama, Alamat Email, Subjek/Nama Produk, dan Pesan).

Pengguna mengeklik tombol "Kirim Pesan".

Sistem memproses pengiriman data menuju alamat email admin@avenasa.co.id serta menyimpannya ke dalam database dasbor Filament.

Sistem menampilkan notifikasi hijau/pesan sukses di layar pengguna yang menginformasikan bahwa permintaan penawaran telah berhasil dikirimkan.