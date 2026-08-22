<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyProfile::updateOrCreate(
            ['id' => 1],
            [
                'tagline_id' => 'Memberdayakan Sains untuk Masa Depan yang Sejahtera',
                'tagline_en' => 'Empowering Science for a Prosperous Future',
                'about_id' => 'PT Abhipraya Nawasena Sejahtera adalah perusahaan yang bergerak di bidang pemasaran dan distribusi produk ilmu hayati (life science) untuk industri farmasi, makanan & minuman (FnB), bioteknologi, kosmetik, laboratorium uji, pusat penelitian, universitas, dan rumah sakit.<br><br>Kami meyakini bahwa sains jika diarahkan dengan niat baik dapat memberikan kekuatan besar untuk membawa kehidupan kita menuju masa depan yang sejahtera.<br><br>Berdasarkan pengalaman kami selama lebih dari 15 tahun, kami berkembang berkat komitmen dan dedikasi kepada pelanggan melalui layanan terbaik, produk berkualitas tinggi, dan dukungan teknis profesional, serta layanan purnajual yang cepat sesuai regulasi.',
                'about_en' => 'PT Abhipraya Nawasena Sejahtera is a company that moving on marketer and distribution product life science for pharmacy industry, FnB, Biotechnology, cosmetic, service lab, research center, university & Hospitals.<br><br>We believe that science if directed with good intention, can do big power to bring our life towards for prosperous future.<br><br>Based on our experiences for more than 15 years, we growth because our commitment and dedication to our customers with best services, high quality product and professional technical support, as quick after sales service appropriate regulation.',
                'vision_id' => 'Menjadi motor penggerak kemajuan ilmu hayati (life science) dan industri untuk masa depan yang sejahtera.',
                'vision_en' => 'To be a driving force of life science and industrial advancement for a prosperous future.',
                'mission_id' => "Mewujudkan inovasi laboratorium terpadu untuk mendukung kemajuan sains, industri, dan lingkungan.\nMenyediakan solusi yang bertanggung jawab dan berkelanjutan yang menciptakan manfaat nyata bagi masyarakat dan bumi.\nMembangun kerja sama strategis dengan pelanggan, mitra bisnis, dan prinsipal untuk pertumbuhan bersama.\nMeningkatkan nilai kehidupan melalui sains, teknologi, dan layanan profesional.",
                'mission_en' => "Realizing integrated laboratory innovation to support science, industry and environment progress.\nProviding responsible and continues solution that creating real benefits for society and the earth.\nBuiding strategic cooperation with customer, business partner and principal for the growth.\nIncreasing life value trough science, technology and professional services.",
                'address' => 'Mensana Tower Lt. 15, Jl. Raya Kranggan RT.002/RW.016, Kel. Jatisampurna, Kec. Jatisampurna, Kota Bekasi, Jawa Barat 17433',
                'phone' => '021 39722772',
                'whatsapp' => '0822-614-614-00',
                'email' => 'admin@avenasa.co.id',
                'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3408018314136!2d106.92349797499138!3d-6.350541793639343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699320e8bfa177%3A0x6b403fbe4019a16f!2sMensana%20Tower!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid',
            ]
        );
    }
}
