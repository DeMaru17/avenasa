<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cache Category IDs by name_en
        $categories = Category::pluck('id', 'name_en');

        // Cache Brand IDs by name
        $brands = Brand::pluck('id', 'name');

        $products = [
            // 1. Lovibond - Cooling and Industrial Process Water Test Kits (p. 5)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'Cooling and Industrial Process Water Test Kits',
                'name_en' => 'Cooling and Industrial Process Water Test Kits',
                'summary_id' => 'Kit pengujian multi-parameter portabel untuk pemantauan air pendingin dan boiler industri.',
                'summary_en' => 'Portable multi-parameter test kits for industrial cooling water and boiler monitoring.',
                'description_id' => 'Kit pengujian air industri Lovibond dirancang khusus untuk operator pengolahan air boiler dan cooling tower guna mencegah pembentukan kerak, korosi, dan pertumbuhan mikrobiologis.',
                'description_en' => 'Lovibond industrial water test kits are specifically designed for boiler and cooling tower operators to prevent scale formation, corrosion, and microbiological growth.',
                'specifications' => [
                    [
                        'key_id' => 'Aplikasi Pengujian',
                        'key_en' => 'Application',
                        'value_id' => 'Air Pendingin, Boiler & Air Proses Industri',
                        'value_en' => 'Cooling Water, Boilers & Industrial Process Water',
                    ],
                    [
                        'key_id' => 'Tipe Pengujian',
                        'key_en' => 'Testing Type',
                        'value_id' => 'Multi-Parameter Kolorimetri & Titrasi Lapangan',
                        'value_en' => 'Field Colorimetric & Titration Multi-Parameter',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],

            // 2. Lovibond - Hardness Test Kit (p. 6)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'Hardness Test Kit',
                'name_en' => 'Hardness Test Kit',
                'summary_id' => 'Kit uji titrasi cepat untuk penentuan kesadahan total dan kesadahan kalsium pada air.',
                'summary_en' => 'Rapid titration test kit for total and calcium hardness determination in water.',
                'description_id' => 'Kit uji kesadahan Lovibond menggunakan metode titrasi tetes drop-count yang praktis dan akurat untuk menentukan konsentrasi kalsium dan magnesium dalam air lunak maupun air sadah.',
                'description_en' => 'Lovibond hardness test kit utilizes practical and accurate drop-count titration methods to determine calcium and magnesium concentrations in soft and hard water.',
                'specifications' => [
                    [
                        'key_id' => 'Parameter Uji',
                        'key_en' => 'Test Parameters',
                        'value_id' => 'Kesadahan Total & Kesadahan Kalsium (CaCO3)',
                        'value_en' => 'Total Hardness & Calcium Hardness (CaCO3)',
                    ],
                    [
                        'key_id' => 'Metode',
                        'key_en' => 'Method',
                        'value_id' => 'Titrasi Drop Count',
                        'value_en' => 'Drop Count Titration',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],

            // 3. Lovibond - Silt Density Index (SDI) Test Kit (p. 6)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'Silt Density Index (SDI) Test Kit',
                'name_en' => 'Silt Density Index (SDI) Test Kit',
                'summary_id' => 'Kit uji SDI portabel untuk evaluasi potensi penyumbatan partikulat pada membran Reverse Osmosis (RO).',
                'summary_en' => 'Portable SDI test kit for evaluating particulate fouling potential on Reverse Osmosis (RO) membranes.',
                'description_id' => 'Sistem pengujian SDI Lovibond menyediakan peralatan lengkap dan filter membran 0.45 mikron untuk mengukur laju penurunan fluks penyaringan air umpan sistem desalinasi dan RO.',
                'description_en' => 'The Lovibond SDI test system provides complete equipment and 0.45 micron membrane filters to measure flow decay rates in desalination and RO feed water.',
                'specifications' => [
                    [
                        'key_id' => 'Kapasitas Pengujian',
                        'key_en' => 'Testing Capacity',
                        'value_id' => '100 Pengujian (Termasuk Membran 0.45 µm)',
                        'value_en' => '100 Tests (Includes 0.45 µm Membranes)',
                    ],
                    [
                        'key_id' => 'Aplikasi',
                        'key_en' => 'Application',
                        'value_id' => 'Evaluasi Air Umpan Membran RO / Reverse Osmosis',
                        'value_en' => 'Reverse Osmosis (RO) Feed Water Evaluation',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],

            // 4. Lovibond - Three-Chamber Tester Chlorine / pH (p. 6)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'Three-Chamber Tester Chlorine / pH',
                'name_en' => 'Three-Chamber Tester Chlorine / pH',
                'summary_id' => 'Alat uji kolorimetri visual tiga kompartemen untuk pengukuran klorin bebas/total dan pH air secara simultan.',
                'summary_en' => 'Visual colorimetric three-compartment tester for simultaneous free/total chlorine and pH measurement.',
                'description_id' => 'Alat uji praktis tiga kompartemen untuk pemantauan disinfeksi klorin dan kestabilan pH pada kolam renang, air bersih, dan pengolahan air minum.',
                'description_en' => 'Practical three-chamber block tester for routine monitoring of chlorine disinfection and pH stability in pools, potable water, and treatment facilities.',
                'specifications' => [
                    [
                        'key_id' => 'Rentang Pengukuran',
                        'key_en' => 'Measuring Range',
                        'value_id' => 'Klorin: 0.1 – 3.0 mg/l Cl2 | pH: 6.8 – 8.2',
                        'value_en' => 'Chlorine: 0.1 – 3.0 mg/l Cl2 | pH: 6.8 – 8.2',
                    ],
                    [
                        'key_id' => 'Reagen Digunakan',
                        'key_en' => 'Reagents Used',
                        'value_id' => 'Tablet DPD No.1 / Phenol Red',
                        'value_en' => 'DPD No.1 / Phenol Red Rapid Tablets',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],

            // 5. Lovibond - Arsenic Test Kit (5ppb) (p. 7)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'Arsenic Test Kit (5ppb)',
                'name_en' => 'Arsenic Test Kit (5ppb)',
                'summary_id' => 'Kit uji visual sensitivitas tinggi untuk deteksi arsenik anorganik dalam air minum.',
                'summary_en' => 'High-sensitivity visual test kit for inorganic arsenic detection in drinking water.',
                'description_id' => 'Kit pengujian arsenik Lovibond mendeteksi kontaminasi senyawa arsenik anorganik beracun pada tingkat jejak hingga batas regulasi internasional WHO (10 ppb) dan standar sensitif 5 ppb.',
                'description_en' => 'Lovibond arsenic test kit detects toxic inorganic arsenic contamination at trace levels complying with international WHO drinking water guidelines (10 ppb) down to 5 ppb.',
                'specifications' => [
                    [
                        'key_id' => 'Kode Pemesanan',
                        'key_en' => 'Order Code',
                        'value_id' => '400700',
                        'value_en' => '400700',
                    ],
                    [
                        'key_id' => 'Rentang Deteksi',
                        'key_en' => 'Detection Range',
                        'value_id' => '0.005 – 0.5 mg/l As (5 – 500 ppb)',
                        'value_en' => '0.005 – 0.5 mg/l As (5 – 500 ppb)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],

            // 6. Lovibond - E-Comparator EC 2000 Pt-Co (p. 10)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'E-Comparator EC 2000 Pt-Co',
                'name_en' => 'E-Comparator EC 2000 Pt-Co',
                'summary_id' => 'Komparator warna elektronik untuk pengukuran warna cairan transparan skala Platinum-Cobalt / Hazen / APHA.',
                'summary_en' => 'Electronic colour comparator for transparent liquid colour measurement in Platinum-Cobalt / Hazen / APHA scale.',
                'description_id' => 'Lovibond E-Comparator EC 2000 menjembatani komparator visual tradisional dengan akurasi digital fotometrik untuk pengukuran indeks warna cairan jernih.',
                'description_en' => 'The Lovibond E-Comparator EC 2000 bridges traditional visual comparators with digital photometric precision for clear liquid color grading.',
                'specifications' => [
                    [
                        'key_id' => 'Nomor Model',
                        'key_en' => 'Model Number',
                        'value_id' => 'EC 2000',
                        'value_en' => 'EC 2000',
                    ],
                    [
                        'key_id' => 'Skala Warna',
                        'key_en' => 'Colour Scale',
                        'value_id' => 'Pt-Co / Hazen / APHA (0 – 500 Pt-Co)',
                        'value_en' => 'Pt-Co / Hazen / APHA (0 – 500 Pt-Co)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],

            // 7. Lovibond - Photometer MD Series (p. 11)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'Photometer MD Series',
                'name_en' => 'Photometer MD Series',
                'summary_id' => 'Seri fotometer portabel tahan air IP68 presisi tinggi untuk analisis kualitas air lapangan dan laboratorium.',
                'summary_en' => 'High-precision IP68 waterproof portable photometers for field and laboratory water quality analysis.',
                'description_id' => 'Lini fotometer seri MD Lovibond menyediakan optik presisi filter interferensi pita sempit dengan LED stabil, memori penyimpanan data besar, dan transfer data nirkabel Bluetooth (seri MD 110) atau USB.',
                'description_en' => 'Lovibond MD series photometers provide high-precision narrow band interference filters with stable LEDs, extensive internal data storage, and wireless Bluetooth data transfer (MD 110 series) or USB connectivity.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'MD 100, MD 110 (dengan Bluetooth), MD 200 (Benchtop/Portable)',
                        'value_en' => 'MD 100, MD 110 (with Bluetooth), MD 200 (Benchtop/Portable)',
                    ],
                    [
                        'key_id' => 'Proteksi Lingkungan',
                        'key_en' => 'Protection Rating',
                        'value_id' => 'IP68 Tahan Air & Mengapung',
                        'value_en' => 'IP68 Waterproof & Floats',
                    ],
                    [
                        'key_id' => 'Metode Terkalibrasi',
                        'key_en' => 'Pre-Programmed Methods',
                        'value_id' => 'Hingga 120+ Parameter Analisis Kualitas Air',
                        'value_en' => 'Up to 120+ Water Quality Parameters',
                    ],
                ],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 7,
            ],

            // 8. Lovibond - Thermoreactor RD 125 (p. 12)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'Thermoreactor RD 125',
                'name_en' => 'Thermoreactor RD 125',
                'summary_id' => 'Reaktor digesti termal untuk persiapan sampel pengujian COD, Total Nitrogen, dan Total Fosfor.',
                'summary_en' => 'Thermal digestion reactor for sample preparation in COD, Total Nitrogen, and Total Phosphorus testing.',
                'description_id' => 'Reaktor digesti tabung RD 125 memanaskan hingga 24 tabung reaksi 16 mm secara presisi dan seragam dengan timer otomatis dan pilihan suhu digesti ganda.',
                'description_en' => 'The RD 125 vial digestion reactor precisely and uniformly heats up to 24 sample vials (16 mm) with automated timer and multiple selectable digestion temperatures.',
                'specifications' => [
                    [
                        'key_id' => 'Nomor Model / Kode Pemesanan',
                        'key_en' => 'Model / Order Code',
                        'value_id' => 'RD 125 (Order Code: 2418940)',
                        'value_en' => 'RD 125 (Order Code: 2418940)',
                    ],
                    [
                        'key_id' => 'Suhu Digesti',
                        'key_en' => 'Digestion Temperatures',
                        'value_id' => '100 °C / 120 °C / 150 °C / 165 °C',
                        'value_en' => '100 °C / 120 °C / 150 °C / 165 °C',
                    ],
                    [
                        'key_id' => 'Kapasitas Lubang',
                        'key_en' => 'Vial Capacity',
                        'value_id' => '24 Lubang Tabung Reaksi (16 mm diameter)',
                        'value_en' => '24 Holes for 16 mm Sample Vials',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 8,
            ],

            // 9. Lovibond - Multi-Parameter Photometers MD 600 / MD 610 / MD 640 (p. 13)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'Multi-Parameter Photometers MD 600 / MD 610 / MD 640',
                'name_en' => 'Multi-Parameter Photometers MD 600 / MD 610 / MD 640',
                'summary_id' => 'Fotometer canggih dengan lebih dari 120 metode pra-program dan pengukuran fluorometri PTSA.',
                'summary_en' => 'Advanced multi-parameter photometers with over 120 pre-programmed methods and PTSA fluorometric detection.',
                'description_id' => 'Fotometer tingkat lanjutan MD 600/610/640 menggabungkan optik multi-panjang gelombang dengan antarmuka transfer data Bluetooth dan pengukuran fluoresensi terintegrasi untuk pelacak fluoresen PTSA pada cooling tower.',
                'description_en' => 'The advanced MD 600/610/640 photometer series combines multi-wavelength optics with Bluetooth connectivity and integrated fluorometric detection for PTSA fluorescent tracers in cooling water.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'MD 600, MD 610 (Bluetooth), MD 640 (dengan PTSA Fluorometer)',
                        'value_en' => 'MD 600, MD 610 (Bluetooth), MD 640 (with PTSA Fluorometer)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 9,
            ],

            // 10. Lovibond - Spectrophotometers XD Series (XD 7000 / XD 7500) (p. 14)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'Spectrophotometers XD Series (XD 7000 / XD 7500)',
                'name_en' => 'Spectrophotometers XD Series (XD 7000 / XD 7500)',
                'summary_id' => 'Spektrofotometer referensi sinar ganda kelas atas dengan pengenalan otomatis barcode vial dan 150+ metode pra-program.',
                'summary_en' => 'High-end reference-beam spectrophotometers with automatic vial barcode recognition and 150+ pre-programmed methods.',
                'description_id' => 'Spektrofotometer seri XD Lovibond menghadirkan teknologi Reference Beam canggih untuk akurasi spektrofotometri tertinggi dengan pengenalan jenis kuvet otomatis, barcode test kit, dan pemindaian spektrum kontinu.',
                'description_en' => 'Lovibond XD series spectrophotometers deliver state-of-the-art Reference Beam technology for premium spectrophotometric precision featuring automatic cuvette type recognition, barcode test scanning, and continuous spectrum analysis.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'XD 7000 (VIS: 320–1100 nm), XD 7500 (UV-VIS: 190–1100 nm)',
                        'value_en' => 'XD 7000 (VIS: 320–1100 nm), XD 7500 (UV-VIS: 190–1100 nm)',
                    ],
                    [
                        'key_id' => 'Sistem Optik',
                        'key_en' => 'Optical System',
                        'value_id' => 'Reference Beam Technology (Sinar Ganda Referensi)',
                        'value_en' => 'Reference Beam Technology',
                    ],
                ],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 10,
            ],

            // 11. Lovibond - Turbidity Meters TB Series (TB 300 IR / TB 211 IR / TB 250 WL) (p. 18-20)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'Turbidity Meters TB Series (TB 300 IR / TB 211 IR / TB 250 WL)',
                'name_en' => 'Turbidity Meters TB Series (TB 300 IR / TB 211 IR / TB 250 WL)',
                'summary_id' => 'Instrumen pengukuran kekeruhan air presisi tinggi berbasis sinar inframerah ISO 7027 dan cahaya putih US EPA.',
                'summary_en' => 'High-precision turbidity instruments based on ISO 7027 infrared light source and US EPA white light.',
                'description_id' => 'Seri turbidimeter Lovibond menawarkan pengukuran kekeruhan nephelometrik dari tingkat rendah air minum hingga limbah pekat dengan standar kalibrasi formazin T-CAL terintegrasi.',
                'description_en' => 'Lovibond turbidity meters offer nephelometric turbidity measurement from ultra-clean drinking water to industrial wastewater with integrated T-CAL formazin calibration standards.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'TB 300 IR (Benchtop Laboratorium), TB 211 IR (Portabel ISO 7027), TB 250 WL (Portabel US EPA)',
                        'value_en' => 'TB 300 IR (Laboratory Benchtop), TB 211 IR (Portable ISO 7027), TB 250 WL (Portable US EPA)',
                    ],
                    [
                        'key_id' => 'Rentang Pengukuran',
                        'key_en' => 'Measurement Range',
                        'value_id' => '0.01 – 1100 NTU',
                        'value_en' => '0.01 – 1100 NTU',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 11,
            ],

            // 12. Lovibond - BOD Measurement System BD 600 / BD 600 GLP (p. 21)
            [
                'category_name_en' => 'Water Testing & Colour Measurement',
                'brand_name' => 'Lovibond',
                'name_id' => 'BOD Measurement System BD 600 / BD 600 GLP',
                'name_en' => 'BOD Measurement System BD 600 / BD 600 GLP',
                'summary_id' => 'Sistem otomatis pengukuran Biochemical Oxygen Demand (BOD) berbasis sensor respirometrik tanpa merkuri.',
                'summary_en' => 'Mercury-free automated respirometric BOD measurement system with GLP compliance.',
                'description_id' => 'Sistem BD 600 mengukur konsumsi oksigen biokimia secara respirometrik dalam wadah tertutup hingga 6 botol sampel dengan kontrol otomatis dan tampilan grafik langsung.',
                'description_en' => 'The BD 600 system measures biochemical oxygen consumption respirometrically in closed bottles for up to 6 samples with automatic data logging and direct graphical display.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'BD 600 (Standar), BD 600 GLP (dengan koneksi PC & kepatuhan GLP)',
                        'value_en' => 'BD 600 (Standard), BD 600 GLP (with PC Interface & GLP Compliance)',
                    ],
                    [
                        'key_id' => 'Rentang BOD',
                        'key_en' => 'BOD Measuring Range',
                        'value_id' => '1 – 4000 mg/l BOD (Pilihan Volume 40–428 ml)',
                        'value_en' => '1 – 4000 mg/l BOD (Sample Volume 40–428 ml)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 12,
            ],

            // 13. Gold Standard Diagnostics - SENSISpec Allergen Detection ELISA Kits (p. 24)
            [
                'category_name_en' => 'Food Safety & Allergen Diagnostics',
                'brand_name' => 'Gold Standard Diagnostics',
                'name_id' => 'SENSISpec Allergen Detection ELISA Kits',
                'name_en' => 'SENSISpec Allergen Detection ELISA Kits',
                'summary_id' => 'Kit uji ELISA kuantitatif sensitivitas tinggi untuk deteksi residu alergen makanan dalam matriks pangan olahan dan bilasan CIP.',
                'summary_en' => 'High-sensitivity quantitative ELISA test kits for food allergen residue detection in processed food matrices and CIP rinse water.',
                'description_id' => 'Kit SENSISpec ELISA dari Gold Standard Diagnostics menyediakan metode kuantitatif yang telah tervalidasi AOAC untuk mendeteksi kontaminasi silang alergen utama pada jalur produksi makanan.',
                'description_en' => 'SENSISpec ELISA kits from Gold Standard Diagnostics provide AOAC-validated quantitative methods for detecting major food allergen cross-contamination on food manufacturing lines.',
                'specifications' => [
                    [
                        'key_id' => 'Target Alergen Tersedia',
                        'key_en' => 'Target Allergens Available',
                        'value_id' => 'Susu, Telur, Kedelai, Kacang Tanah, Gluten, Hazelnut, Almond, Krustasea, Ikan, Mustar, Wijen, Lupinus',
                        'value_en' => 'Milk, Egg, Soy, Peanut, Gluten, Hazelnut, Almond, Crustacea, Fish, Mustard, Sesame, Lupin',
                    ],
                    [
                        'key_id' => 'Format Kit',
                        'key_en' => 'Kit Format',
                        'value_id' => '96 Uji (12 x 8 strip lepas)',
                        'value_en' => '96 Tests (12 x 8 breakable strip wells)',
                    ],
                ],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 13,
            ],

            // 14. Gold Standard Diagnostics - SENSISpec Gluten Detection ELISA Kits (p. 25)
            [
                'category_name_en' => 'Food Safety & Allergen Diagnostics',
                'brand_name' => 'Gold Standard Diagnostics',
                'name_id' => 'SENSISpec Gluten Detection ELISA Kits',
                'name_en' => 'SENSISpec Gluten Detection ELISA Kits',
                'summary_id' => 'Kit ELISA terstandarisasi Codex Alimentarius dengan antibodi monoklonal Mendez R5 untuk kuantifikasi gluten bebas pada makanan.',
                'summary_en' => 'Codex Alimentarius standardized ELISA kit using Mendez R5 monoclonal antibody for gluten quantification in food products.',
                'description_id' => 'Kit ELISA Gluten SENSISpec menggunakan antibodi monoklonal R5 yang diakui secara global sebagai standar baku emas (Codex Alimentarius) untuk sertifikasi produk bebas gluten.',
                'description_en' => 'SENSISpec Gluten ELISA kit utilizes the globally recognized Mendez R5 monoclonal antibody (Codex Alimentarius standard) for gluten-free certification.',
                'specifications' => [
                    [
                        'key_id' => 'Antibodi Digunakan',
                        'key_en' => 'Antibody System',
                        'value_id' => 'Mendez R5 Monoclonal Antibody',
                        'value_en' => 'Mendez R5 Monoclonal Antibody',
                    ],
                    [
                        'key_id' => 'Batas Kuantifikasi',
                        'key_en' => 'Limit of Quantification',
                        'value_id' => '2.5 ppm Gluten (1.25 ppm Gliadin)',
                        'value_en' => '2.5 ppm Gluten (1.25 ppm Gliadin)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 14,
            ],

            // 15. Gold Standard Diagnostics - DNAllergen2 Real-Time PCR Kits (p. 25)
            [
                'category_name_en' => 'Food Safety & Allergen Diagnostics',
                'brand_name' => 'Gold Standard Diagnostics',
                'name_id' => 'DNAllergen2 Real-Time PCR Kits',
                'name_en' => 'DNAllergen2 Real-Time PCR Kits',
                'summary_id' => 'Kit deteksi DNA alergen berbasis PCR Real-Time dengan spesifisitas mutlak untuk sampel makanan yang mengalami proses termal tinggi.',
                'summary_en' => 'Real-Time PCR allergen DNA detection kits with absolute specificity for highly thermally processed food matrices.',
                'description_id' => 'Rangkaian kit DNAllergen2 PCR mendeteksi urutan DNA spesifik alergen bahkan ketika protein alergen telah terdenaturasi oleh proses pemanasan atau pengolahan ekstrem industri pangan.',
                'description_en' => 'The DNAllergen2 PCR kit series detects allergen-specific DNA sequences even when allergen proteins have been denatured by intense industrial heating or processing.',
                'specifications' => [
                    [
                        'key_id' => 'Metode Deteksi',
                        'key_en' => 'Detection Method',
                        'value_id' => 'Multiplex Real-Time PCR dengan Kontrol Internal',
                        'value_en' => 'Multiplex Real-Time PCR with Internal Control',
                    ],
                    [
                        'key_id' => 'Sensitivitas',
                        'key_en' => 'Sensitivity Limit',
                        'value_id' => 'Hingga ≤ 0.4 ppm DNA Spesifik',
                        'value_en' => 'Down to ≤ 0.4 ppm Specific DNA',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 15,
            ],

            // 16. Gold Standard Diagnostics - SENSIStrip Lateral Flow Tests (p. 26)
            [
                'category_name_en' => 'Food Safety & Allergen Diagnostics',
                'brand_name' => 'Gold Standard Diagnostics',
                'name_id' => 'SENSIStrip Lateral Flow Tests',
                'name_en' => 'SENSIStrip Lateral Flow Tests',
                'summary_id' => 'Strip tes cepat kualitatif untuk skrining residu alergen di lini produksi dan permukaan alat dalam hitungan menit.',
                'summary_en' => 'Qualitative rapid test strips for allergen residue screening on production lines and surfaces in minutes.',
                'description_id' => 'SENSIStrip adalah strip imunokromatografi cepat untuk pengujian verifikasi sanitasi alergen mandiri di pabrik tanpa memerlukan peralatan laboratorium rumit.',
                'description_en' => 'SENSIStrip provides rapid immunochromatographic strips for on-site allergen sanitation verification in processing plants without specialized laboratory equipment.',
                'specifications' => [
                    [
                        'key_id' => 'Waktu Pembacaan',
                        'key_en' => 'Readout Time',
                        'value_id' => '5 – 10 Menit',
                        'value_en' => '5 – 10 Minutes',
                    ],
                    [
                        'key_id' => 'Teknologi Proteksi',
                        'key_en' => 'Protection Feature',
                        'value_id' => 'Hook Line Protection (Mencegah Negatif Palsu pada Konsentrasi Ekstrem)',
                        'value_en' => 'Hook Line Protection (Prevents False Negatives at High Concentrations)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 16,
            ],

            // 17. Gold Standard Diagnostics - RapidScan Lateral Flow Reader (p. 26)
            [
                'category_name_en' => 'Food Safety & Allergen Diagnostics',
                'brand_name' => 'Gold Standard Diagnostics',
                'name_id' => 'RapidScan Lateral Flow Reader',
                'name_en' => 'RapidScan Lateral Flow Reader',
                'summary_id' => 'Instrumen optik portabel untuk pembacaan dan dokumentasi kuantitatif hasil uji SENSIStrip lateral flow.',
                'summary_en' => 'Portable optical instrument for quantitative readout and digital documentation of SENSIStrip lateral flow tests.',
                'description_id' => 'Alat pembaca optik digital RapidScan mengeliminasi subjektivitas pembacaan visual mata manusia pada uji strip lateral flow dengan memberikan nilai kuantitatif terkalibrasi.',
                'description_en' => 'The RapidScan digital optical reader eliminates human visual subjectivity in lateral flow test reading by providing calibrated quantitative measurements.',
                'specifications' => [
                    [
                        'key_id' => 'Nomor Model',
                        'key_en' => 'Model Number',
                        'value_id' => 'RapidScan LFD Reader',
                        'value_en' => 'RapidScan LFD Reader',
                    ],
                    [
                        'key_id' => 'Tipe Analisis',
                        'key_en' => 'Analysis Type',
                        'value_id' => 'Kuantitatif & Semi-Kuantitatif Berbasis Citra Optik',
                        'value_en' => 'Optical Image-Based Quantitative & Semi-Quantitative',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 17,
            ],

            // 18. Neogen - Clean-Trace Hygiene Monitoring System (p. 27)
            [
                'category_name_en' => 'Food Safety & Allergen Diagnostics',
                'brand_name' => 'Neogen',
                'name_id' => 'Clean-Trace Hygiene Monitoring System',
                'name_en' => 'Clean-Trace Hygiene Monitoring System',
                'summary_id' => 'Sistem pemantauan verifikasi sanitasi dan ATP bioluminesensi cepat untuk permukaan dan air bilasan CIP industri pangan.',
                'summary_en' => 'Rapid ATP bioluminescence hygiene and sanitation monitoring system for food industry surfaces and CIP rinse water.',
                'description_id' => 'Sistem Clean-Trace Neogen memberikan verifikasi kebersihan pabrik dan integritas sanitasi lini produksi secara real-time dengan reagen bioluminesensi enzimatis luciferase presisi tinggi.',
                'description_en' => 'The Neogen Clean-Trace system provides real-time factory hygiene and sanitation verification using highly stable enzymatic luciferase bioluminescence reagents.',
                'specifications' => [
                    [
                        'key_id' => 'Komponen Sistem',
                        'key_en' => 'System Components',
                        'value_id' => 'Clean-Trace Luminometer LM1, Swab Permukaan UXL100 / AQF100, Swab Air AQT200',
                        'value_en' => 'Clean-Trace Luminometer LM1, Surface Swabs UXL100 / AQF100, Water Swabs AQT200',
                    ],
                    [
                        'key_id' => 'Kecepatan Hasil',
                        'key_en' => 'Result Speed',
                        'value_id' => '< 10 Detik per Pengujian',
                        'value_en' => '< 10 Seconds per Test',
                    ],
                ],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 18,
            ],

            // 19. Neogen - One Broth One Plate (OBOP) Workflows (p. 28)
            [
                'category_name_en' => 'Microbiology & Culture Media',
                'brand_name' => 'Neogen',
                'name_id' => 'One Broth One Plate (OBOP) Workflows',
                'name_en' => 'One Broth One Plate (OBOP) Workflows',
                'summary_id' => 'Alur kerja deteksi patogen cepat (Listeria & Salmonella) dengan satu tahap pengayaan kaldu dan satu cawan kultur.',
                'summary_en' => 'Rapid pathogen detection workflows (Listeria & Salmonella) using single broth enrichment and single agar plate.',
                'description_id' => 'Alur kerja OBOP Neogen menyederhanakan pengujian mikrobiologi patogen makanan dengan memangkas waktu konfirmasi hingga 48 jam lebih cepat dibanding metode kultur tradisional ISO.',
                'description_en' => 'Neogen OBOP workflows streamline food pathogen testing by reducing confirmation turnaround times by up to 48 hours compared to conventional ISO culture protocols.',
                'specifications' => [
                    [
                        'key_id' => 'Patogen Target',
                        'key_en' => 'Target Pathogens',
                        'value_id' => 'Listeria spp., Listeria monocytogenes, Salmonella spp.',
                        'value_en' => 'Listeria spp., Listeria monocytogenes, Salmonella spp.',
                    ],
                    [
                        'key_id' => 'Sertifikasi Validasi',
                        'key_en' => 'Validation Standards',
                        'value_id' => 'ISO 16140-2 / MicroVal / NF VALIDATION',
                        'value_en' => 'ISO 16140-2 / MicroVal / NF VALIDATION',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 19,
            ],

            // 20. Fountain Scientific - GaBriFilm Rapid Aerobic Count Plate (p. 29)
            [
                'category_name_en' => 'Microbiology & Culture Media',
                'brand_name' => 'Fountain Scientific',
                'name_id' => 'GaBriFilm Rapid Aerobic Count Plate',
                'name_en' => 'GaBriFilm Rapid Aerobic Count Plate',
                'summary_id' => 'Cawan film mikrobiologi kering siap pakai untuk enumerasi bakteri aerobik total dalam sampel makanan dan minuman.',
                'summary_en' => 'Ready-to-use dry rehydratable film plate for total aerobic bacterial enumeration in food and beverage samples.',
                'description_id' => 'GaBriFilm TPC adalah media kultur kering siap rehidrasi yang menghemat ruang inkubator dan waktu preparasi media lempeng agar konvensional.',
                'description_en' => 'GaBriFilm TPC is a ready-to-use dry rehydratable culture media plate saving incubator space and preparation time over conventional agar dishes.',
                'specifications' => [
                    [
                        'key_id' => 'Waktu Inkubasi',
                        'key_en' => 'Incubation Time',
                        'value_id' => '24 – 48 Jam pada 35 °C ± 1 °C',
                        'value_en' => '24 – 48 Hours at 35 °C ± 1 °C',
                    ],
                    [
                        'key_id' => 'Indikator Koloni',
                        'key_en' => 'Colony Indicator',
                        'value_id' => 'Pewarnaan Reduksi TTC (Koloni Merah Jelas)',
                        'value_en' => 'TTC Reduction Staining (Clear Red Colonies)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 20,
            ],

            // 21. Fountain Scientific - GaBriFilm Rapid Coliform Count Plate (p. 30)
            [
                'category_name_en' => 'Microbiology & Culture Media',
                'brand_name' => 'Fountain Scientific',
                'name_id' => 'GaBriFilm Rapid Coliform Count Plate',
                'name_en' => 'GaBriFilm Rapid Coliform Count Plate',
                'summary_id' => 'Cawan film mikrobiologi kering untuk deteksi dan enumerasi bakteri coliform dalam makanan dan lingkungan produksi.',
                'summary_en' => 'Dry rehydratable film plate for selective detection and enumeration of coliform bacteria.',
                'description_id' => 'Lembar hitung GaBriFilm Coliform memberikan hasil perhitungan koloni coliform dalam 24 jam dengan visualisasi warna spesifik yang mudah dibaca.',
                'description_en' => 'GaBriFilm Coliform count plates provide coliform enumeration within 24 hours with distinct specific color visualization for straightforward interpretation.',
                'specifications' => [
                    [
                        'key_id' => 'Waktu Inkubasi',
                        'key_en' => 'Incubation Time',
                        'value_id' => '24 Jam pada 35 °C ± 1 °C',
                        'value_en' => '24 Hours at 35 °C ± 1 °C',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 21,
            ],

            // 22. Fountain Scientific - GaBriFilm Yeast & Mold Count Plate (p. 30-31)
            [
                'category_name_en' => 'Microbiology & Culture Media',
                'brand_name' => 'Fountain Scientific',
                'name_id' => 'GaBriFilm Yeast & Mold Count Plate',
                'name_en' => 'GaBriFilm Yeast & Mold Count Plate',
                'summary_id' => 'Cawan film mikrobiologi untuk enumerasi kapang dan khamir dalam produk makanan dengan hasil lebih cepat.',
                'summary_en' => 'Dry rehydratable film plate for rapid enumeration of yeast and mold in food products.',
                'description_id' => 'GaBriFilm Yeast & Mold membedakan khamir (koloni biru-kehijauan kecil) dan kapang (koloni besar berserabut) secara akurat pada produk makanan dan kosmetik.',
                'description_en' => 'GaBriFilm Yeast & Mold accurately differentiates yeasts (small blue-green colonies) and molds (large filamentous colonies) in food and cosmetic products.',
                'specifications' => [
                    [
                        'key_id' => 'Waktu Inkubasi',
                        'key_en' => 'Incubation Time',
                        'value_id' => '48 – 72 Jam pada 25 °C / 28 °C',
                        'value_en' => '48 – 72 Hours at 25 °C / 28 °C',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 22,
            ],

            // 23. Fountain Scientific - GaBriFilm Staphylococcus aureus Plate (p. 31)
            [
                'category_name_en' => 'Microbiology & Culture Media',
                'brand_name' => 'Fountain Scientific',
                'name_id' => 'GaBriFilm Staphylococcus aureus Plate',
                'name_en' => 'GaBriFilm Staphylococcus aureus Plate',
                'summary_id' => 'Cawan film mikrobiologi spesifik untuk enumerasi Staphylococcus aureus pada sampel pangan olahan.',
                'summary_en' => 'Specific dry film plate for Staphylococcus aureus enumeration in processed food samples.',
                'description_id' => 'Media film selektif untuk konfirmasi cepat kontaminasi bakteri patogen Staphylococcus aureus penghasil enterotoksin.',
                'description_en' => 'Selective film media for prompt confirmation of enterotoxin-producing Staphylococcus aureus pathogen contamination.',
                'specifications' => [
                    [
                        'key_id' => 'Karakteristik Koloni',
                        'key_en' => 'Colony Characteristic',
                        'value_id' => 'Koloni Biru Keunguan Khas Terisolasi',
                        'value_en' => 'Distinct Violet-Blue Isolated Colonies',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 23,
            ],

            // 24. Fountain Scientific - GaBriFilm Rapid E. coli & Coliform Two-in-One (p. 32)
            [
                'category_name_en' => 'Microbiology & Culture Media',
                'brand_name' => 'Fountain Scientific',
                'name_id' => 'GaBriFilm Rapid E. coli & Coliform Two-in-One',
                'name_en' => 'GaBriFilm Rapid E. coli & Coliform Two-in-One',
                'summary_id' => 'Cawan film diferensial untuk identifikasi simultan E. coli (koloni biru dengan gelembung gas) dan Coliform umum.',
                'summary_en' => 'Differential dry film plate for simultaneous identification of E. coli and total coliforms.',
                'description_id' => 'Format 2-in-1 efisien yang menghitung total coliform sekaligus mengkonfirmasi keberadaan E. coli dalam satu lembar uji yang sama.',
                'description_en' => 'An efficient 2-in-1 format enumerating total coliforms while simultaneously confirming E. coli presence on a single test sheet.',
                'specifications' => [
                    [
                        'key_id' => 'Waktu Inkubasi',
                        'key_en' => 'Incubation Time',
                        'value_id' => '24 Jam pada 35 °C ± 1 °C',
                        'value_en' => '24 Hours at 35 °C ± 1 °C',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 24,
            ],

            // 25. Fountain Scientific - GaBriFilm Rapid Salmonella Count Plate (p. 33)
            [
                'category_name_en' => 'Microbiology & Culture Media',
                'brand_name' => 'Fountain Scientific',
                'name_id' => 'GaBriFilm Rapid Salmonella Count Plate',
                'name_en' => 'GaBriFilm Rapid Salmonella Count Plate',
                'summary_id' => 'Cawan film mikrobiologi selektif untuk deteksi presumtif Salmonella pada produk makanan dan pakan.',
                'summary_en' => 'Selective dry film plate for presumptive detection of Salmonella in food and feed products.',
                'description_id' => 'Media film kromogenik selektif untuk mempercepat skrining presumtif kontaminasi bakteri Salmonella dalam rantai pasok pangan.',
                'description_en' => 'Selective chromogenic dry film plate accelerating presumptive screening of Salmonella bacterial contamination in food supply chains.',
                'specifications' => [
                    [
                        'key_id' => 'Waktu Deteksi',
                        'key_en' => 'Detection Time',
                        'value_id' => '24 Jam Pasca Pra-Pengayaan',
                        'value_en' => '24 Hours Post Pre-Enrichment',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 25,
            ],

            // 26. Fountain Scientific - GaBriFilm Environmental Listeria Test Plate (p. 34)
            [
                'category_name_en' => 'Microbiology & Culture Media',
                'brand_name' => 'Fountain Scientific',
                'name_id' => 'GaBriFilm Environmental Listeria Test Plate',
                'name_en' => 'GaBriFilm Environmental Listeria Test Plate',
                'summary_id' => 'Cawan film untuk pemantauan keberadaan Listeria spp. pada permukaan lingkungan pabrik tanpa tahap transfer kultur.',
                'summary_en' => 'Dry film plate for environmental Listeria spp. surface monitoring without culture transfer steps.',
                'description_id' => 'Solusi praktis pemantauan kebersihan lingkungan fasilitas pengolahan makanan terhadap risiko kolonisasi Listeria.',
                'description_en' => 'Practical monitoring solution for food manufacturing facility hygiene against Listeria environmental colonization risks.',
                'specifications' => [
                    [
                        'key_id' => 'Aplikasi Pengujian',
                        'key_en' => 'Application',
                        'value_id' => 'Swab Permukaan Lingkungan Pabrik & Peralatan',
                        'value_en' => 'Plant Environment & Equipment Surface Swabs',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 26,
            ],

            // 27. Fountain Scientific - Counter 1000 Automated Colony Reader (p. 34)
            [
                'category_name_en' => 'Microbiology & Culture Media',
                'brand_name' => 'Fountain Scientific',
                'name_id' => 'Counter 1000 Automated Colony Reader',
                'name_en' => 'Counter 1000 Automated Colony Reader',
                'summary_id' => 'Instrumen pencitraan digital beresolusi tinggi untuk pembacaan dan penghitungan otomatis koloni cawan GaBriFilm.',
                'summary_en' => 'High-resolution digital imaging instrument for automated reading and counting of GaBriFilm plates.',
                'description_id' => 'Perangkat pembaca cawan otomatis Counter 1000 membaca koloni dalam hitungan detik, menyimpan citra digital, dan mengekspor laporan data analisis mikrobiologi.',
                'description_en' => 'The Counter 1000 automated plate reader scans colonies in seconds, archives digital images, and exports microbiology analytical reports.',
                'specifications' => [
                    [
                        'key_id' => 'Nomor Model',
                        'key_en' => 'Model Number',
                        'value_id' => '1000 Colony Reader',
                        'value_en' => '1000 Colony Reader',
                    ],
                    [
                        'key_id' => 'Kecepatan Pembacaan',
                        'key_en' => 'Reading Speed',
                        'value_id' => '< 2 Detik per Lembar Cawan',
                        'value_en' => '< 2 Seconds per Plate',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 27,
            ],

            // 28. Bioendo - Gel Clot Lyophilized Amebocyte Lysate (p. 35)
            [
                'category_name_en' => 'Endotoxin & Pyrogen Testing',
                'brand_name' => 'Bioendo',
                'name_id' => 'Gel Clot Lyophilized Amebocyte Lysate',
                'name_en' => 'Gel Clot Lyophilized Amebocyte Lysate',
                'summary_id' => 'Reagen LAL standar farmakope untuk deteksi kualitatif endotoksin bakteri pada produk parenteral farmasi dan alat kesehatan.',
                'summary_en' => 'Pharmacopeia standard LAL reagent for qualitative bacterial endotoxin detection in parenteral pharmaceuticals and medical devices.',
                'description_id' => 'Bioendo Gel Clot LAL diformulasikan dari lisat amebosit murni dengan sensitivitas tinggi yang terkalibrasi sesuai Standar Endotoksin Referensi USP dan Farmakope Indonesia.',
                'description_en' => 'Bioendo Gel Clot LAL is formulated from purified amebocyte lysate with high sensitivity calibrated against USP Reference Standard Endotoxin and international pharmacopeias.',
                'specifications' => [
                    [
                        'key_id' => 'Format Kemasan',
                        'key_en' => 'Packaging Options',
                        'value_id' => 'Single Test in Vial & Single Test in Ampoule',
                        'value_en' => 'Single Test in Vial & Single Test in Ampoule',
                    ],
                    [
                        'key_id' => 'Kepatuhan Farmakope',
                        'key_en' => 'Pharmacopeia Compliance',
                        'value_id' => 'USP <85>, EP 2.6.14, JP, Farmakope Indonesia',
                        'value_en' => 'USP <85>, EP 2.6.14, JP, Indonesian Pharmacopoeia',
                    ],
                ],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 28,
            ],

            // 29. Bioendo - Kinetic Chromogenic Endotoxin Assay (p. 35)
            [
                'category_name_en' => 'Endotoxin & Pyrogen Testing',
                'brand_name' => 'Bioendo',
                'name_id' => 'Kinetic Chromogenic Endotoxin Assay',
                'name_en' => 'Kinetic Chromogenic Endotoxin Assay',
                'summary_id' => 'Metode uji endotoksin kuantitatif kinetik kromogenik sensitivitas tinggi hingga 0.001 EU/ml untuk kontrol kualitas biologis.',
                'summary_en' => 'High-sensitivity kinetic chromogenic quantitative endotoxin assay down to 0.001 EU/ml for biological quality control.',
                'description_id' => 'Assay kinetik kromogenik Bioendo memberikan kapasitas pengujian kuantitatif presisi tinggi untuk sampel biologis, vaksin, dan produk farmasi bervolume besar.',
                'description_en' => 'Bioendo kinetic chromogenic assay provides high-precision quantitative endotoxin testing capability for biological samples, vaccines, and high-throughput pharmaceutical QC.',
                'specifications' => [
                    [
                        'key_id' => 'Rentang Sensitivitas',
                        'key_en' => 'Sensitivity Range',
                        'value_id' => '0.001 – 50 EU/ml',
                        'value_en' => '0.001 – 50 EU/ml',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 29,
            ],

            // 30. Bioendo - Pyrogen-Free Microplates (MPC96) (p. 36)
            [
                'category_name_en' => 'Endotoxin & Pyrogen Testing',
                'brand_name' => 'Bioendo',
                'name_id' => 'Pyrogen-Free Microplates (MPC96)',
                'name_en' => 'Pyrogen-Free Microplates (MPC96)',
                'summary_id' => 'Pelat mikro 96-sumur tersertifikasi bebas endotoksin (<0.0005 EU/well) untuk uji LAL kromogenik dan turbidimetrik.',
                'summary_en' => 'Certified endotoxin-free 96-well microplates (<0.0005 EU/well) for chromogenic and turbidimetric LAL testing.',
                'description_id' => 'Microplate MPC96 Bioendo diproduksi di ruang bersih standar medis dan diuji secara ketat bebas dari kontaminasi pirogen dan zat pengganggu endotoksin.',
                'description_en' => 'Bioendo MPC96 microplates are manufactured in cleanroom environments and certified free from pyrogens and endotoxin-interfering substances.',
                'specifications' => [
                    [
                        'key_id' => 'Nomor Katalog',
                        'key_en' => 'Catalog Number',
                        'value_id' => 'MPC96',
                        'value_en' => 'MPC96',
                    ],
                    [
                        'key_id' => 'Batas Bebas Pirogen',
                        'key_en' => 'Endotoxin Limit',
                        'value_id' => '< 0.0005 EU/well',
                        'value_en' => '< 0.0005 EU/well',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 30,
            ],

            // 31. Bioendo - Absorbance Microplate Reader 800 TS (p. 37)
            [
                'category_name_en' => 'Endotoxin & Pyrogen Testing',
                'brand_name' => 'Bioendo',
                'name_id' => 'Absorbance Microplate Reader 800 TS',
                'name_en' => 'Absorbance Microplate Reader 800 TS',
                'summary_id' => 'Pembaca mikroplat fotometrik presisi tinggi untuk pembacaan uji kinetik kromogenik endotoksin dan ELISA.',
                'summary_en' => 'High-precision photometric microplate reader for kinetic chromogenic endotoxin assays and ELISA testing.',
                'description_id' => 'Instrumen pembaca mikroplat 800 TS menyediakan optik stabil dengan rentang panjang gelombang 400–750 nm termasuk filter 405 nm yang disyaratkan untuk uji endotoksin kinetik.',
                'description_en' => 'The 800 TS microplate reader provides stable optics across 400–750 nm wavelength range including the 405 nm filter required for kinetic endotoxin assays.',
                'specifications' => [
                    [
                        'key_id' => 'Nomor Model',
                        'key_en' => 'Model Number',
                        'value_id' => '800 TS',
                        'value_en' => '800 TS',
                    ],
                    [
                        'key_id' => 'Rentang Panjang Gelombang',
                        'key_en' => 'Wavelength Range',
                        'value_id' => '400 – 750 nm (Termasuk Filter 405 nm)',
                        'value_en' => '400 – 750 nm (Includes 405 nm Filter)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 31,
            ],

            // 32. Terragene - UV Dosimeters for Disinfection Systems (p. 38)
            [
                'category_name_en' => 'Sterilization Monitoring',
                'brand_name' => 'Terragene',
                'name_id' => 'UV Dosimeters for Disinfection Systems',
                'name_en' => 'UV Dosimeters for Disinfection Systems',
                'summary_id' => 'Indikator kolorimetri visual untuk verifikasi paparan radiasi UV-C (254 nm) dan sistem desinfeksi cahaya berdenyut.',
                'summary_en' => 'Visual colorimetric indicators for verifying UV-C (254 nm) radiation exposure and pulsed light disinfection systems.',
                'description_id' => 'Dosimeter UV Terragene memberikan verifikasi visual cepat tingkat dosis radiasi ultraviolet pada ruang isolasi rumah sakit dan fasilitas produksi steril.',
                'description_en' => 'Terragene UV dosimeters provide quick visual verification of ultraviolet radiation dosage delivered in hospital isolation rooms and sterile production zones.',
                'specifications' => [
                    [
                        'key_id' => 'Panjang Gelombang Target',
                        'key_en' => 'Target Wavelength',
                        'value_id' => 'UV-C 254 nm & Pulsed Xenon Light',
                        'value_en' => 'UV-C 254 nm & Pulsed Xenon Light',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 32,
            ],

            // 33. Terragene - BioSurf Biological Indicator (BT97) (p. 38-39)
            [
                'category_name_en' => 'Sterilization Monitoring',
                'brand_name' => 'Terragene',
                'name_id' => 'BioSurf Biological Indicator (BT97)',
                'name_en' => 'BioSurf Biological Indicator (BT97)',
                'summary_id' => 'Indikator biologi permukaan mandiri untuk validasi efikasi desinfeksi udara dan permukaan ruangan.',
                'summary_en' => 'Self-contained surface biological indicator for air and room surface disinfection efficacy validation.',
                'description_id' => 'BioSurf BT97 mengandung spora Geobacillus stearothermophilus untuk validasi biologis siklus dekontaminasi uap hidrogen peroksida (VH2O2) dan kabut desinfektan.',
                'description_en' => 'BioSurf BT97 contains Geobacillus stearothermophilus spores for biological validation of vaporized hydrogen peroxide (VH2O2) and mist decontamination cycles.',
                'specifications' => [
                    [
                        'key_id' => 'Kode Model',
                        'key_en' => 'Model Code',
                        'value_id' => 'BT97 BioSurf',
                        'value_en' => 'BT97 BioSurf',
                    ],
                    [
                        'key_id' => 'Organisme Uji',
                        'key_en' => 'Test Organism',
                        'value_id' => 'Geobacillus stearothermophilus (ATCC 7953)',
                        'value_en' => 'Geobacillus stearothermophilus (ATCC 7953)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 33,
            ],

            // 34. Terragene - Ultra Rapid & Super Rapid Biological Indicators (p. 39)
            [
                'category_name_en' => 'Sterilization Monitoring',
                'brand_name' => 'Terragene',
                'name_id' => 'Ultra Rapid & Super Rapid Biological Indicators',
                'name_en' => 'Ultra Rapid & Super Rapid Biological Indicators',
                'summary_id' => 'Indikator biologi fluoresensi mandiri pembacaan super cepat (20 menit hingga 1 jam) untuk sterilisasi Steam, VH2O2, dan EO.',
                'summary_en' => 'Super rapid fluorescence self-contained biological indicators (20 min to 1 hour) for Steam, VH2O2, and EO sterilization.',
                'description_id' => 'Indikator biologi fluoresensi generasi terbaru Bionova memberikan hasil validasi pelepasan muatan steril CSSD dan industri farmasi hanya dalam waktu 20–30 menit.',
                'description_en' => 'Bionova next-generation fluorescence biological indicators deliver sterile load release confirmation for hospital CSSD and pharmaceutical sterilization in only 20–30 minutes.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'BT224 (Super Rapid Steam 20 min), BT222 (Ultra Rapid VH2O2), BT96 (Ultra Rapid Steam 30 min), BT102 (Rapid Steam 1h), BT110 (Rapid EO)',
                        'value_en' => 'BT224 (Super Rapid Steam 20 min), BT222 (Ultra Rapid VH2O2), BT96 (Ultra Rapid Steam 30 min), BT102 (Rapid Steam 1h), BT110 (Rapid EO)',
                    ],
                    [
                        'key_id' => 'Standar Kepatuhan',
                        'key_en' => 'Compliance Standards',
                        'value_id' => 'ISO 11138-1, ISO 11138-3, FDA 510(k)',
                        'value_en' => 'ISO 11138-1, ISO 11138-3, FDA 510(k)',
                    ],
                ],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 34,
            ],

            // 35. Terragene - Steam Process Challenge Devices (PCDs) (p. 39)
            [
                'category_name_en' => 'Sterilization Monitoring',
                'brand_name' => 'Terragene',
                'name_id' => 'Steam Process Challenge Devices (PCDs)',
                'name_en' => 'Steam Process Challenge Devices (PCDs)',
                'summary_id' => 'Perangkat uji tantangan proses sterilisasi uap CSSD untuk simulasi penetrasi uap pada beban instrumen berlubang.',
                'summary_en' => 'Steam process challenge devices for CSSD simulating steam penetration in hollow instrument loads.',
                'description_id' => 'Perangkat PCD Terragene mensimulasikan kondisi penetrasi uap terberat pada instrumen bedah lumen berongga guna memastikan keamanan pelepasan muatan autoklaf.',
                'description_en' => 'Terragene PCD devices simulate worst-case steam penetration conditions in hollow surgical instruments ensuring safe autoclave load release.',
                'specifications' => [
                    [
                        'key_id' => 'Tipe Sterilisasi',
                        'key_en' => 'Sterilization Type',
                        'value_id' => 'Sterilisasi Uap Tekanan (Steam 134 °C & 121 °C)',
                        'value_en' => 'Pressure Steam Sterilization (134 °C & 121 °C)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 35,
            ],

            // 36. Terragene - Conventional Biological Indicators & Spores (p. 40)
            [
                'category_name_en' => 'Sterilization Monitoring',
                'brand_name' => 'Terragene',
                'name_id' => 'Conventional Biological Indicators & Spores',
                'name_en' => 'Conventional Biological Indicators & Spores',
                'summary_id' => 'Jajaran indikator biologi konvensional dan ampul spora untuk validasi sterilisasi cairan dan barang industri farmasi.',
                'summary_en' => 'Conventional biological indicators and spore ampoules for pharmaceutical liquid and goods sterilization validation.',
                'description_id' => 'Pilihan lengkap spora mandiri, ampul spora cairan, dan strip spora untuk berbagai aplikasi validasi siklus sterilisasi panas basah, panas kering, dan gas etilen oksida.',
                'description_en' => 'A comprehensive selection of self-contained biological indicators, liquid spore ampoules, and spore strips for steam, dry heat, and ethylene oxide validation.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'IC10/20 (Konvensional 24h), BT21–BT24 (Ampul Spora Cair), BT31 (Ampul Spora + Media)',
                        'value_en' => 'IC10/20 (Conventional 24h), BT21–BT24 (Liquid Spore Ampoules), BT31 (Spore Ampoule + Medium)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 36,
            ],

            // 37. Terragene - Bowie-Dick Test Packs (BD125X/1 & BD125X/2) (p. 41)
            [
                'category_name_en' => 'Sterilization Monitoring',
                'brand_name' => 'Terragene',
                'name_id' => 'Bowie-Dick Test Packs (BD125X/1 & BD125X/2)',
                'name_en' => 'Bowie-Dick Test Packs (BD125X/1 & BD125X/2)',
                'summary_id' => 'Paket uji Bowie-Dick sekali pakai untuk deteksi kebocoran udara dan efisiensi pengeluaran udara pada autoklaf vakum.',
                'summary_en' => 'Single-use Bowie-Dick test packs for daily air removal and steam penetration efficiency verification in pre-vacuum autoclaves.',
                'description_id' => 'Paket uji Bowie-Dick harian siap pakai dengan lembar indikator perubahan warna merata untuk mendeteksi kegagalan pompa vakum atau kebocoran segel pintu autoklaf.',
                'description_en' => 'Ready-to-use daily Bowie-Dick test packs with uniform color change indicator sheets detecting vacuum pump failures or door gasket air leaks.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'BD125X/1 (134 °C, 3.5 menit), BD125X/2 (134 °C, 4.0 menit)',
                        'value_en' => 'BD125X/1 (134 °C, 3.5 min), BD125X/2 (134 °C, 4.0 min)',
                    ],
                    [
                        'key_id' => 'Standar ISO',
                        'key_en' => 'ISO Standard',
                        'value_id' => 'ISO 11140-1 Tipe 2 / ISO 11140-4',
                        'value_en' => 'ISO 11140-1 Type 2 / ISO 11140-4',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 37,
            ],

            // 38. IKA - HABITAT Research Benchtop Bioreactor (p. 42)
            [
                'category_name_en' => 'Laboratory Equipment & Instruments',
                'brand_name' => 'IKA',
                'name_id' => 'HABITAT Research Benchtop Bioreactor',
                'name_en' => 'HABITAT Research Benchtop Bioreactor',
                'summary_id' => 'Sistem bioreaktor / fermentor benchtop canggih pertama dengan unit kontrol ergonomis dan penutup fleksibel untuk bioteknologi.',
                'summary_en' => 'Advanced first-of-its-kind benchtop bioreactor / fermentor system with ergonomic control unit for bioprocess research.',
                'description_id' => 'HABITAT research dari IKA adalah bioreaktor laboratorium inovatif dengan dudukan kontrol cerdas, pemantauan sensor pH/DO/suhu terintegrasi, dan opsi wadah kaca atau sekali pakai (single-use).',
                'description_en' => 'IKA HABITAT research is an innovative laboratory bioreactor featuring intelligent control stands, integrated pH/DO/temperature sensing, and glass or single-use vessel options.',
                'specifications' => [
                    [
                        'key_id' => 'Nama Model',
                        'key_en' => 'Model Name',
                        'value_id' => 'HABITAT Research',
                        'value_en' => 'HABITAT Research',
                    ],
                    [
                        'key_id' => 'Volume Bejana',
                        'key_en' => 'Vessel Volume Options',
                        'value_id' => '0.5 Liter – 10 Liter (Wadah Kaca & Single-Use)',
                        'value_en' => '0.5 Liter – 10 Liters (Glass & Single-Use Vessels)',
                    ],
                ],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 38,
            ],

            // 39. IKA - Laboratory Stirrers, Shakers & Homogenizers (p. 43-45)
            [
                'category_name_en' => 'Laboratory Equipment & Instruments',
                'brand_name' => 'IKA',
                'name_id' => 'Laboratory Stirrers, Shakers & Homogenizers',
                'name_en' => 'Laboratory Stirrers, Shakers & Homogenizers',
                'summary_id' => 'Lini instrumen persiapan sampel Jerman: inkubator shaker KS 4000 dan disperser homogenizer berkecepatan tinggi ULTRA-TURRAX.',
                'summary_en' => 'German sample preparation line: KS 4000 incubator shaker and ULTRA-TURRAX high-speed digital dispersers.',
                'description_id' => 'Peralatan preparasi sampel andalan IKA Jerman mencakup pengocok inkubator presisi KS 4000 serta jajaran homogenizer disperser rotor-stator legendaris ULTRA-TURRAX.',
                'description_en' => 'IKA Germany flagship sample preparation equipment including KS 4000 precision incubator shakers and legendary ULTRA-TURRAX rotor-stator high-speed homogenizers.',
                'specifications' => [
                    [
                        'key_id' => 'Lini Produk Tersedia',
                        'key_en' => 'Available Product Lines',
                        'value_id' => 'KS 4000 control / ic (Shaker Inkubator), ULTRA-TURRAX T 18 / T 25 (Disperser Homogenizer)',
                        'value_en' => 'KS 4000 control / ic (Incubator Shaker), ULTRA-TURRAX T 18 / T 25 (Disperser Homogenizer)',
                    ],
                    [
                        'key_id' => 'Kecepatan Disperser',
                        'key_en' => 'Disperser Speed Range',
                        'value_id' => '3,000 – 25,000 rpm Kontrol Digital',
                        'value_en' => '3,000 – 25,000 rpm Digital Control',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 39,
            ],

            // 40. IKA - Universal Mill M 20 & ROTAVISC Viscometers (p. 46)
            [
                'category_name_en' => 'Laboratory Equipment & Instruments',
                'brand_name' => 'IKA',
                'name_id' => 'Universal Mill M 20 & ROTAVISC Viscometers',
                'name_en' => 'Universal Mill M 20 & ROTAVISC Viscometers',
                'summary_id' => 'Peralatan penggiling sampel kering keras M 20 dan viskometer digital ROTAVISC untuk pengukuran viskositas cairan presisi.',
                'summary_en' => 'M 20 universal batch mill for hard samples and ROTAVISC digital rotational viscometers for precise fluid viscosity testing.',
                'description_id' => 'Kombinasi penggiling batch universal M 20 berkekuatan tinggi dan viskometer rotasi digital ROTAVISC untuk riset reologi dan kontrol kualitas bahan baku.',
                'description_en' => 'High-performance M 20 universal batch mill and ROTAVISC digital rotational viscometers for rheology research and raw material quality control.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'M 20 Universal Mill (20,000 rpm), ROTAVISC (lo-vi, me-vi, hi-vi I, hi-vi II)',
                        'value_en' => 'M 20 Universal Mill (20,000 rpm), ROTAVISC (lo-vi, me-vi, hi-vi I, hi-vi II)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 40,
            ],

            // 41. IKA - Rotary Evaporators RV 8 & RV 10 Series (p. 47)
            [
                'category_name_en' => 'Laboratory Equipment & Instruments',
                'brand_name' => 'IKA',
                'name_id' => 'Rotary Evaporators RV 8 & RV 10 Series',
                'name_en' => 'Rotary Evaporators RV 8 & RV 10 Series',
                'summary_id' => 'Evaporator putar andal untuk destilasi pelarut organik laboratorium dengan pemanas mandi air/minyak otomatis.',
                'summary_en' => 'Reliable rotary evaporators for laboratory organic solvent distillation with automated heating bath.',
                'description_id' => 'Lini rotary evaporator IKA RV 8 dan RV 10 menawarkan efisiensi kondensasi tinggi, pengangkatan bermotor aman, dan kontrol vakum terintegrasi untuk ekstraksi kimia.',
                'description_en' => 'IKA RV 8 and RV 10 rotary evaporator lines offer superior condensation efficiency, motorized safety lift, and integrated vacuum control for chemical extraction.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'RV 8 (Manual Lift), RV 10 digital (Motor Lift), RV 10 auto',
                        'value_en' => 'RV 8 (Manual Lift), RV 10 digital (Motor Lift), RV 10 auto',
                    ],
                    [
                        'key_id' => 'Rentang Kecepatan Putar',
                        'key_en' => 'Rotation Speed Range',
                        'value_id' => '5 – 300 rpm',
                        'value_en' => '5 – 300 rpm',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 41,
            ],

            // 42. IKA - Midi Centrifuge G-L & PETTE Pipette Series (p. 48)
            [
                'category_name_en' => 'Laboratory Equipment & Instruments',
                'brand_name' => 'IKA',
                'name_id' => 'Midi Centrifuge G-L & PETTE Pipette Series',
                'name_en' => 'Midi Centrifuge G-L & PETTE Pipette Series',
                'summary_id' => 'Sentrifus midi kompak G-L dan jajaran mikropipet ergonomis presisi tinggi IKA PETTE untuk penanganan cairan laboratorium.',
                'summary_en' => 'Compact midi centrifuge G-L and IKA PETTE ergonomic high-precision micropipettes for liquid handling.',
                'description_id' => 'Sentrifus kompak IKA G-L dengan kecepatan hingga 15.700 rpm dan mikropipet mekanik ergonomis seri IKA PETTE dengan ketahanan autoklaf penuh.',
                'description_en' => 'IKA G-L midi centrifuge operating up to 15,700 rpm and fully autoclavable ergonomic IKA PETTE mechanical micropipette series.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'Centrifuge G-L (15,700 rpm / 16,500 x g), IKA PETTE (fix, vario, multi-channel 8 & 12)',
                        'value_en' => 'Centrifuge G-L (15,700 rpm / 16,500 x g), IKA PETTE (fix, vario, multi-channel 8 & 12)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 42,
            ],

            // 43. Fisher Scientific - Optima LC/MS & HPLC Grade Solvents (p. 49)
            [
                'category_name_en' => 'Chemicals, Solvents & Environmental Solutions',
                'brand_name' => 'Fisher Scientific',
                'name_id' => 'Optima LC/MS & HPLC Grade Solvents',
                'name_en' => 'Optima LC/MS & HPLC Grade Solvents',
                'summary_id' => 'Pelarut kromatografi berkualitas tinggi dengan latar belakang ionik ultra-rendah dan transmisi UV optimal untuk LC-MS dan HPLC.',
                'summary_en' => 'High-purity chromatography solvents with ultra-low ionic background and optimal UV transmittance for LC-MS and HPLC.',
                'description_id' => 'Pelarut Fisher Chemical Optima diproduksi khusus untuk analisis instrumen canggih LC-MS, HPLC, dan UHPLC dengan spesifikasi kemurnian dan transparansi optik tertinggi.',
                'description_en' => 'Fisher Chemical Optima solvents are specifically manufactured for advanced LC-MS, HPLC, and UHPLC analytical instruments with highest purity and optical transparency specifications.',
                'specifications' => [
                    [
                        'key_id' => 'Tingkat Kemurnian',
                        'key_en' => 'Purity Grade',
                        'value_id' => 'Optima LC/MS Grade & HPLC Grade (> 99.9%)',
                        'value_en' => 'Optima LC/MS Grade & HPLC Grade (> 99.9%)',
                    ],
                    [
                        'key_id' => 'Pelarut Tersedia',
                        'key_en' => 'Available Solvents',
                        'value_id' => 'Asetonitril, Metanol, Air LC/MS, Isopropanol, Asam Format',
                        'value_en' => 'Acetonitrile, Methanol, LC/MS Water, Isopropanol, Formic Acid',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 43,
            ],

            // 44. Fisher Scientific - Certified ACS Grade Solvents (p. 50)
            [
                'category_name_en' => 'Chemicals, Solvents & Environmental Solutions',
                'brand_name' => 'Fisher Scientific',
                'name_id' => 'Certified ACS Grade Solvents',
                'name_en' => 'Certified ACS Grade Solvents',
                'summary_id' => 'Pelarut kimia murni bersertifikat yang memenuhi spesifikasi ketat American Chemical Society untuk pengujian analitis umum.',
                'summary_en' => 'Certified chemical solvents meeting strict American Chemical Society specifications for general analytical testing.',
                'description_id' => 'Jajaran reagen dan pelarut Fisher Chemical Certified ACS memenuhi standar kemurnian American Chemical Society untuk analisis laboratorium terakreditasi.',
                'description_en' => 'Fisher Chemical Certified ACS reagents and solvents meet stringent American Chemical Society purity benchmarks for accredited laboratory testing.',
                'specifications' => [
                    [
                        'key_id' => 'Kepatuhan Standar',
                        'key_en' => 'Standard Compliance',
                        'value_id' => 'Spesifikasi American Chemical Society (ACS)',
                        'value_en' => 'American Chemical Society (ACS) Specifications',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 44,
            ],

            // 45. DLAB Scientific - LED Digital Rotary Evaporator RE100-Pro (p. 51-52)
            [
                'category_name_en' => 'Laboratory Equipment & Instruments',
                'brand_name' => 'DLAB Scientific',
                'name_id' => 'LED Digital Rotary Evaporator RE100-Pro',
                'name_en' => 'LED Digital Rotary Evaporator RE100-Pro',
                'summary_id' => 'Rotary evaporator digital dengan layar LCD besar dan bak pemanas 5L untuk pemisahan dan pemurnian pelarut laboratorium.',
                'summary_en' => 'Digital rotary evaporator with large LCD display and 5L heating bath for laboratory solvent separation.',
                'description_id' => 'DLAB RE100-Pro adalah evaporator putar digital ekonomis dan presisi dengan bak pemanas universal air/minyak 5L dan pengangkat otomatis motor.',
                'description_en' => 'The DLAB RE100-Pro is an economical, high-precision digital rotary evaporator featuring a 5L universal water/oil heating bath and motorized lift.',
                'specifications' => [
                    [
                        'key_id' => 'Nomor Model',
                        'key_en' => 'Model Number',
                        'value_id' => 'RE100-Pro',
                        'value_en' => 'RE100-Pro',
                    ],
                    [
                        'key_id' => 'Rentang Kecepatan',
                        'key_en' => 'Speed Range',
                        'value_id' => '20 – 280 rpm Kontrol Digital',
                        'value_en' => '20 – 280 rpm Digital Control',
                    ],
                    [
                        'key_id' => 'Kapasitas Bak Pemanas',
                        'key_en' => 'Bath Capacity',
                        'value_id' => '5 Liter (Suhu Ruang hingga 180 °C)',
                        'value_en' => '5 Liters (Room Temp to 180 °C)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 45,
            ],

            // 46. DLAB Scientific - LCD Digital Magnetic Hotplate Stirrer MS10-H500-Pro (p. 53-54)
            [
                'category_name_en' => 'Laboratory Equipment & Instruments',
                'brand_name' => 'DLAB Scientific',
                'name_id' => 'LCD Digital Magnetic Hotplate Stirrer MS10-H500-Pro',
                'name_en' => 'LCD Digital Magnetic Hotplate Stirrer MS10-H500-Pro',
                'summary_id' => 'Pengaduk pelat pemanas keramik tahan bahan kimia dengan kontrol suhu digital presisi hingga 500 °C.',
                'summary_en' => 'Chemical-resistant ceramic glass hotplate magnetic stirrer with precise digital temperature control up to 500 °C.',
                'description_id' => 'MS10-H500-Pro menawarkan pelat pemanas kaca keramik tahan kimia kuat dengan kontrol suhu presisi hingga 500 °C dan kapasitas pengadukan cairan hingga 10L.',
                'description_en' => 'The MS10-H500-Pro features a chemical-resistant glass ceramic plate with precise digital heating up to 500 °C and stirring capacity up to 10L.',
                'specifications' => [
                    [
                        'key_id' => 'Nomor Model',
                        'key_en' => 'Model Number',
                        'value_id' => 'MS10-H500-Pro',
                        'value_en' => 'MS10-H500-Pro',
                    ],
                    [
                        'key_id' => 'Suhu Pemanasan Maksimal',
                        'key_en' => 'Max Temperature',
                        'value_id' => '500 °C (Pelat Kaca Keramik)',
                        'value_en' => '500 °C (Glass Ceramic Work Plate)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 46,
            ],

            // 47. DLAB Scientific - Digital Orbital & Linear Shakers (SK-0330-Pro & SK-0180-Pro) (p. 55-56)
            [
                'category_name_en' => 'Laboratory Equipment & Instruments',
                'brand_name' => 'DLAB Scientific',
                'name_id' => 'Digital Orbital & Linear Shakers (SK-0330-Pro & SK-0180-Pro)',
                'name_en' => 'Digital Orbital & Linear Shakers (SK-0330-Pro & SK-0180-Pro)',
                'summary_id' => 'Shaker laboratorium digital dengan gerakan orbital atau linier halus untuk pencampuran kultur mikroba dan botol laboratorium.',
                'summary_en' => 'Digital laboratory shakers with smooth orbital or linear motion for microbial culture and flask mixing.',
                'description_id' => 'Shaker digital seri SK-Pro dari DLAB menjamin pencampuran stabil dan konsisten dengan penggerak motor DC brushless bebas perawatan.',
                'description_en' => 'DLAB SK-Pro series digital shakers ensure smooth and uniform mixing powered by maintenance-free brushless DC motors.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'SK-0330-Pro (Kapasitas Beban 7.5 kg), SK-0180-Pro (Kapasitas Beban 2.5 kg)',
                        'value_en' => 'SK-0330-Pro (Load Capacity 7.5 kg), SK-0180-Pro (Load Capacity 2.5 kg)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 47,
            ],

            // 48. DLAB Scientific - UV-Visible Spectrophotometers SP Series (p. 57-58)
            [
                'category_name_en' => 'Laboratory Equipment & Instruments',
                'brand_name' => 'DLAB Scientific',
                'name_id' => 'UV-Visible Spectrophotometers SP Series',
                'name_en' => 'UV-Visible Spectrophotometers SP Series',
                'summary_id' => 'Lini spektrofotometer sinar tunggal dan berkas terpisah untuk analisis kuantitatif rutin laboratorium fisika/kimia.',
                'summary_en' => 'Single-beam and split-beam spectrophotometer line for routine quantitative analysis in analytical laboratories.',
                'description_id' => 'Spektrofotometer seri SP DLAB menyediakan antarmuka pengguna digital yang mudah, kisi difraksi presisi, dan perangkat lunak analisis kuantitatif PC.',
                'description_en' => 'DLAB SP series spectrophotometers deliver user-friendly digital interfaces, high-precision blazed holographic gratings, and comprehensive PC quantitative software.',
                'specifications' => [
                    [
                        'key_id' => 'Model Tersedia',
                        'key_en' => 'Available Models',
                        'value_id' => 'SP-V1000 (VIS: 325–1000 nm), SP-UV1000 (UV-VIS: 200–1000 nm), SP-V1100 (Split Beam VIS), SP-UV1100 (Split Beam UV-VIS: 190–1100 nm)',
                        'value_en' => 'SP-V1000 (VIS: 325–1000 nm), SP-UV1000 (UV-VIS: 200–1000 nm), SP-V1100 (Split Beam VIS), SP-UV1100 (Split Beam UV-VIS: 190–1100 nm)',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 48,
            ],

            // 49. BIGBIO - BIGBIO Wastewater Bioaugmentation (p. 59)
            [
                'category_name_en' => 'Chemicals, Solvents & Environmental Solutions',
                'brand_name' => 'BIGBIO',
                'name_id' => 'BIGBIO Wastewater Bioaugmentation',
                'name_en' => 'BIGBIO Wastewater Bioaugmentation',
                'summary_id' => 'Formulasi konsorsium bakteri alami aktif untuk reduksi COD, BOD, TSS, minyak lemak, dan bau pada IPAL domestik/industri.',
                'summary_en' => 'Active natural bacterial consortium formulation for reducing COD, BOD, TSS, FOG, and odor in wastewater treatment plants.',
                'description_id' => 'BIGBIO adalah formulasi bioteknologi mikroba konsorsium aktif ramah lingkungan untuk mengoptimalkan penguraian beban organik dan menghilangkan bau H2S pada instalasi pengolahan air limbah.',
                'description_en' => 'BIGBIO is an eco-friendly microbial consortium biotechnology formulation optimizing organic load breakdown and eliminating H2S odors in wastewater treatment plants.',
                'specifications' => [
                    [
                        'key_id' => 'Bentuk Formulasi',
                        'key_en' => 'Formulation Form',
                        'value_id' => 'Cairan Konsentrat & Bubuk Bio-Kultur Aktif',
                        'value_en' => 'Liquid Concentrate & Active Powder Bio-Culture',
                    ],
                    [
                        'key_id' => 'Target Reduksi',
                        'key_en' => 'Target Reduction',
                        'value_id' => 'BOD, COD, Amonia, Lemak & Minyak (FOG), Bau H2S',
                        'value_en' => 'BOD, COD, Ammonia, Fats Oil Grease (FOG), H2S Odor',
                    ],
                ],
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 49,
            ],

            // 50. Cleanbio - Cleanbio Air-Fit Medical Air Purifiers (Air-Fit 50 / 30 / 20) (p. 60-62)
            [
                'category_name_en' => 'Chemicals, Solvents & Environmental Solutions',
                'brand_name' => 'Cleanbio',
                'name_id' => 'Cleanbio Air-Fit Medical Air Purifiers (Air-Fit 50 / 30 / 20)',
                'name_en' => 'Cleanbio Air-Fit Medical Air Purifiers (Air-Fit 50 / 30 / 20)',
                'summary_id' => 'Sistem pemurni udara tingkat medis dengan filter HEPA H14, plasma sterilisasi, dan fotokatalis untuk ruang isolasi dan lab.',
                'summary_en' => 'Medical-grade room air purifier with HEPA H14 filter, plasma sterilization, and photocatalyst for isolation rooms and labs.',
                'description_id' => 'Lini pemurni udara medis Cleanbio Air-Fit dirancang untuk sterilisasi udara aktif di ruang isolasi rumah sakit, klinik, laboratorium diagnostik, dan fasilitas industri farmasi.',
                'description_en' => 'Cleanbio Air-Fit medical air purifier line is designed for active continuous air sterilization in hospital isolation rooms, clinics, diagnostic laboratories, and pharmaceutical facilities.',
                'specifications' => [
                    [
                        'key_id' => 'Model & Cakupan Area',
                        'key_en' => 'Models & Coverage Area',
                        'value_id' => 'Air-Fit 50 (hingga 50 m²), Air-Fit 30 (hingga 30 m²), Air-Fit 20 (hingga 20 m²)',
                        'value_en' => 'Air-Fit 50 (up to 50 m²), Air-Fit 30 (up to 30 m²), Air-Fit 20 (up to 20 m²)',
                    ],
                    [
                        'key_id' => 'Tingkat Filtrasi',
                        'key_en' => 'Filtration Rating',
                        'value_id' => '≥ 99.995% (Medical Grade HEPA H14 + Plasma Ion + Fotokatalis)',
                        'value_en' => '≥ 99.995% (Medical Grade HEPA H14 + Plasma Ion + Photocatalyst)',
                    ],
                ],
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 50,
            ],
        ];

        foreach ($products as $productData) {
            $categoryNameEn = $productData['category_name_en'];
            $brandName = $productData['brand_name'];

            $categoryId = $categories[$categoryNameEn] ?? null;
            $brandId = $brands[$brandName] ?? null;

            if (! $categoryId || ! $brandId) {
                continue;
            }

            unset($productData['category_name_en'], $productData['brand_name']);

            $productData['category_id'] = $categoryId;
            $productData['brand_id'] = $brandId;
            $productData['primary_image_path'] = '';
            $productData['brochure_path'] = null;

            Product::updateOrCreate(
                ['name_en' => $productData['name_en']],
                $productData
            );
        }
    }
}
