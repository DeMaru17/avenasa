<?php

namespace Database\Seeders;

use App\Models\CoreValue;
use Illuminate\Database\Seeder;

class CoreValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coreValues = [
            [
                'title_id' => 'Integritas',
                'title_en' => 'INTEGRITY',
                'description_id' => 'Menjunjung tinggi kejujuran, etika bisnis, transparansi, dan kepatuhan terhadap seluruh regulasi industri dalam setiap kemitraan.',
                'description_en' => 'Upholding honesty, business ethics, transparency, and regulatory compliance in every partnership and operational standard.',
                'icon_name' => 'shield-check',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title_id' => 'Inovasi',
                'title_en' => 'INNOVATION',
                'description_id' => 'Terus menghadirkan teknologi laboratorium mutakhir, diagnostik, dan solusi saintifik terpadu untuk kemajuan industri.',
                'description_en' => 'Continuously introducing cutting-edge laboratory technology, diagnostics, and integrated scientific solutions.',
                'icon_name' => 'light-bulb',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title_id' => 'Kolaborasi',
                'title_en' => 'COLLABORATION',
                'description_id' => 'Membangun kerja sama yang kokoh, strategis, dan saling menguntungkan dengan pelanggan, mitra bisnis, dan prinsipal global.',
                'description_en' => 'Building strong, strategic, and mutually beneficial cooperation with customers, business partners, and global principals.',
                'icon_name' => 'user-group',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title_id' => 'Keberlanjutan',
                'title_en' => 'SUSTAINABILITY',
                'description_id' => 'Berkomitmen pada praktik bertanggung jawab yang mendukung kelestarian lingkungan, kesejahteraan masyarakat, dan ketahanan industri.',
                'description_en' => 'Committed to responsible practices that support environmental sustainability, public welfare, and long-term industrial resilience.',
                'icon_name' => 'arrow-path',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title_id' => 'Profesionalisme',
                'title_en' => 'PROFESSIONALISM',
                'description_id' => 'Memberikan keunggulan layanan, dukungan teknis berstandar tinggi, dan layanan purnajual cepat yang berdedikasi.',
                'description_en' => 'Delivering service excellence, high-level technical support, and dedicated quick after-sales service.',
                'icon_name' => 'briefcase',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title_id' => 'Kesejahteraan',
                'title_en' => 'WELL-BEING',
                'description_id' => 'Mengutamakan kesehatan, keselamatan, dan peningkatan nilai kehidupan melalui kemajuan sains dan teknologi.',
                'description_en' => 'Prioritizing health, safety, and enhancing the value of life through the advancement of science and technology.',
                'icon_name' => 'heart',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($coreValues as $value) {
            CoreValue::updateOrCreate(
                ['title_en' => $value['title_en']],
                $value
            );
        }
    }
}
