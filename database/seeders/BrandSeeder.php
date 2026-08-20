<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Lovibond',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal utama instrumen pengujian kualitas air, fotometer, dan pengukuran warna visual/spektrofotometer.',
                'description_en' => 'Leading global principal for water quality testing instruments, photometers, and visual/spectrophotometric colour measurement.',
                'is_new_principal' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Gold Standard Diagnostics',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal pengujian alergen makanan (ELISA, PCR, Lateral Flow Strip) dan pembaca diagnostik rapid.',
                'description_en' => 'Principal for food allergen diagnostics (ELISA, PCR, Lateral Flow Strips) and rapid diagnostic readers.',
                'is_new_principal' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Neogen',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal sistem hygiene monitoring (Clean-Trace ATP) dan media mikrobiologi rapid One Broth One Plate.',
                'description_en' => 'Principal for hygiene monitoring systems (Clean-Trace ATP) and One Broth One Plate rapid microbiology media.',
                'is_new_principal' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Fountain Scientific',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal lembar hitung mikrobiologi cepat (GaBriFilm) dan pembaca koloni otomatis.',
                'description_en' => 'Principal for rapid microbial count dry sheets (GaBriFilm) and automated colony counters.',
                'is_new_principal' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Bioendo',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Produsen spesialis uji endotoksin dan pirogen (LAL gel clot, assays chromogenic, microplate bebas pirogen).',
                'description_en' => 'Specialized manufacturer for endotoxin and pyrogen testing (LAL gel clot, chromogenic assays, pyrogen-free microplates).',
                'is_new_principal' => false,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Terragene',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal pemantauan sterilisasi (indikator biologi Bionova, Bowie-Dick test pack, dan PCD).',
                'description_en' => 'Principal for sterilization monitoring (Bionova biological indicators, Bowie-Dick test packs, and PCDs).',
                'is_new_principal' => false,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'IKA',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal instrumen laboratorium Jerman (bioreaktor, pengaduk magnetik, pengocok, homogenizer, viskometer, mikropipet).',
                'description_en' => 'German laboratory equipment principal (bioreactors, magnetic stirrers, shakers, homogenizers, viscometers, micropipettes).',
                'is_new_principal' => false,
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Fisher Scientific',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Produsen pelarut dan reagen kimia tingkat tinggi (HPLC Grade, LC/MS Solvents, ACS Grade).',
                'description_en' => 'Manufacturer of high-purity chemical solvents and reagents (HPLC Grade, LC/MS Solvents, ACS Grade).',
                'is_new_principal' => false,
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'DLAB Scientific',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal peralatan laboratorium (rotary evaporator, hotplate stirrer, shakers, spektrofotometer).',
                'description_en' => 'Laboratory equipment principal (rotary evaporators, hotplate stirrers, shakers, spectrophotometers).',
                'is_new_principal' => false,
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'BIGBIO',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Brand solusi bioaugmentasi dan pengolahan mikrobiologi air limbah domestik/industri.',
                'description_en' => 'Brand for bioaugmentation and microbiological treatment solutions for domestic/industrial wastewater.',
                'is_new_principal' => false,
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Cleanbio',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Brand pemurni udara tingkat medis dan sistem sterilisasi ruang (Air-Fit series).',
                'description_en' => 'Medical-grade air purifier and room sterilization system brand (Air-Fit series).',
                'is_new_principal' => false,
                'sort_order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'ERA Biology',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal baru solusi diagnostik penyakit infeksi dan mikologi invasif (TAL, CLIA, LFA, PCR, ELISA).',
                'description_en' => 'New principal for invasive fungal and infectious disease diagnostic solutions (TAL, CLIA, LFA, PCR, ELISA).',
                'is_new_principal' => true,
                'sort_order' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Merck',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal resmi reagen kimia, media kultur, dan bahan mikrobiologi industri.',
                'description_en' => 'Official principal for chemical reagents, culture media, and industrial microbiology materials.',
                'is_new_principal' => false,
                'sort_order' => 13,
                'is_active' => true,
            ],
            [
                'name' => 'HiMedia',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal media kultur dan reagen mikrobiologi laboratorium.',
                'description_en' => 'Principal for culture media and laboratory microbiology reagents.',
                'is_new_principal' => false,
                'sort_order' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Thermo Scientific',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal instrumen laboratorium, perangkat analisis, dan bioteknologi.',
                'description_en' => 'Principal for laboratory instruments, analytical devices, and biotechnology.',
                'is_new_principal' => false,
                'sort_order' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Alliance Bio Expertise',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal peralatan otomasi laboratorium dan preparasi media mikrobiologi.',
                'description_en' => 'Principal for laboratory automation equipment and microbiology media preparation.',
                'is_new_principal' => false,
                'sort_order' => 16,
                'is_active' => true,
            ],
            [
                'name' => 'Lonza',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal solusi pengujian endotoksin dan produk bioteknologi farmasi.',
                'description_en' => 'Principal for endotoxin testing solutions and pharmaceutical biotechnology products.',
                'is_new_principal' => false,
                'sort_order' => 17,
                'is_active' => true,
            ],
            [
                'name' => 'Labitex',
                'website_url' => null,
                'logo_path' => '',
                'description_id' => 'Principal peralatan dan habis pakai laboratorium umum.',
                'description_en' => 'Principal for general laboratory equipment and consumables.',
                'is_new_principal' => false,
                'sort_order' => 18,
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brandData) {
            Brand::updateOrCreate(
                ['name' => $brandData['name']],
                $brandData
            );
        }
    }
}
