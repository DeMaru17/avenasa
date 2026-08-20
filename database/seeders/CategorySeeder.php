<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name_id' => 'Pengujian Kualitas Air & Warna',
                'name_en' => 'Water Testing & Colour Measurement',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name_id' => 'Keamanan Pangan & Uji Alergen',
                'name_en' => 'Food Safety & Allergen Diagnostics',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name_id' => 'Mikrobiologi & Media Kultur',
                'name_en' => 'Microbiology & Culture Media',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name_id' => 'Pengujian Endotoksin & Pirogen',
                'name_en' => 'Endotoxin & Pyrogen Testing',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name_id' => 'Pemantauan Sterilisasi',
                'name_en' => 'Sterilization Monitoring',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name_id' => 'Peralatan & Instrumen Laboratorium',
                'name_en' => 'Laboratory Equipment & Instruments',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name_id' => 'Bahan Kimia, Solven & Solusi Lingkungan',
                'name_en' => 'Chemicals, Solvents & Environmental Solutions',
                'sort_order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['name_en' => $categoryData['name_en']],
                $categoryData
            );
        }
    }
}
