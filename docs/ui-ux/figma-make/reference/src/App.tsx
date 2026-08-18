import { useState, useEffect, useRef } from 'react'
import logoAns from './imports/logo-ans.png'

type Page = 'home' | 'about' | 'catalog' | 'product-detail' | 'partners' | 'contact'
type Locale = 'id' | 'en'

interface Product {
  id: number
  slug_id: string
  slug_en: string
  name_id: string
  name_en: string
  summary_id: string
  summary_en: string
  description_id: string
  description_en: string
  category_id: number
  brand_id: number
  image: string
  images: string[]
  specifications: { key_id: string; key_en: string; value_id: string; value_en: string }[]
  hasBrochure: boolean
}

interface Category {
  id: number
  slug_id: string
  slug_en: string
  name_id: string
  name_en: string
}

interface Brand {
  id: number
  slug: string
  name: string
  description_id: string
  description_en: string
  logo: string
}

// ── DATA ──────────────────────────────────────────────────────────────────────

const CATEGORIES: Category[] = [
  { id: 1, slug_id: 'mikrobiologi', slug_en: 'microbiology', name_id: 'Mikrobiologi', name_en: 'Microbiology' },
  { id: 2, slug_id: 'biologi-molekuler', slug_en: 'molecular-biology', name_id: 'Biologi Molekuler', name_en: 'Molecular Biology' },
  { id: 3, slug_id: 'imunologi', slug_en: 'immunology', name_id: 'Imunologi', name_en: 'Immunology' },
  { id: 4, slug_id: 'diagnostik', slug_en: 'diagnostics', name_id: 'Diagnostik', name_en: 'Diagnostics' },
  { id: 5, slug_id: 'kromatografi', slug_en: 'chromatography', name_id: 'Kromatografi', name_en: 'Chromatography' },
  { id: 6, slug_id: 'perlengkapan-lab', slug_en: 'lab-consumables', name_id: 'Perlengkapan Lab', name_en: 'Lab Consumables' },
]

const BRANDS: Brand[] = [
  {
    id: 1, slug: 'merck', name: 'Merck',
    description_id: 'Perusahaan sains dan teknologi terkemuka dunia yang menyediakan solusi inovatif di bidang ilmu pengetahuan hayati, diagnostik, dan bahan kimia analitik kelas dunia.',
    description_en: 'A leading science and technology company providing innovative solutions in life sciences, diagnostics, and world-class analytical chemicals.',
    logo: 'https://images.unsplash.com/photo-1614935151651-0bea6508db6b?w=200&h=100&fit=crop&auto=format',
  },
  {
    id: 2, slug: 'neogen', name: 'Neogen',
    description_id: 'Pemimpin global dalam solusi keamanan pangan dan keamanan hewan, menyediakan produk pengujian cepat yang terpercaya untuk industri pangan, pertanian, dan veteriner.',
    description_en: 'A global leader in food and animal safety solutions, providing trusted rapid testing products for food, agriculture, and veterinary industries.',
    logo: 'https://images.unsplash.com/photo-1576086213369-b0e0dfc87f5e?w=200&h=100&fit=crop&auto=format',
  },
  {
    id: 3, slug: 'era-biology', name: 'Era Biology',
    description_id: 'Produsen kit ELISA, media kultur, dan reagen biologi molekuler berkualitas tinggi untuk keperluan penelitian, diagnostik klinis, dan uji keamanan pangan.',
    description_en: 'Manufacturer of high-quality ELISA kits, culture media, and molecular biology reagents for research, clinical diagnostics, and food safety testing.',
    logo: 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=200&h=100&fit=crop&auto=format',
  },
]

const PRODUCTS: Product[] = [
  {
    id: 1, slug_id: 'sistem-pcr-real-time', slug_en: 'real-time-pcr-system',
    name_id: 'Sistem PCR Real-Time LightCycler® 96', name_en: 'LightCycler® 96 Real-Time PCR System',
    summary_id: 'Instrumen PCR real-time beresolusi tinggi dengan 96-well untuk deteksi dan kuantifikasi DNA/RNA secara akurat dalam waktu singkat.',
    summary_en: 'High-resolution 96-well real-time PCR instrument for accurate and rapid DNA/RNA detection and quantification.',
    description_id: 'Sistem PCR Real-Time LightCycler® 96 adalah solusi canggih untuk laboratorium molekuler modern. Dengan kapasitas 96 sumur dan kecepatan siklus termal yang luar biasa, sistem ini memungkinkan analisis ekspresi gen, deteksi patogen, dan kuantifikasi copy number dengan presisi tinggi.\n\nKeunggulan sistem ini mencakup teknologi deteksi fluoresen multicolor, antarmuka perangkat lunak intuitif yang kompatibel dengan Windows, serta konsumsi energi rendah yang mendukung keberlanjutan lingkungan laboratorium.',
    description_en: 'The LightCycler® 96 Real-Time PCR System is an advanced solution for modern molecular laboratories. With a 96-well capacity and remarkable thermal cycling speed, this system enables gene expression analysis, pathogen detection, and copy number quantification with high precision.',
    category_id: 2, brand_id: 1,
    image: 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=800&h=800&fit=crop&auto=format',
      'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=800&h=800&fit=crop&auto=format',
      'https://images.unsplash.com/photo-1576086213369-b0e0dfc87f5e?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Kapasitas', key_en: 'Capacity', value_id: '96 sumur (format plate standar)', value_en: '96 wells (standard plate format)' },
      { key_id: 'Waktu Deteksi', key_en: 'Detection Time', value_id: '< 35 menit (40 siklus)', value_en: '< 35 minutes (40 cycles)' },
      { key_id: 'Rentang Suhu', key_en: 'Temperature Range', value_id: '37°C – 99°C', value_en: '37°C – 99°C' },
      { key_id: 'Akurasi Suhu', key_en: 'Temperature Accuracy', value_id: '±0.3°C', value_en: '±0.3°C' },
      { key_id: 'Saluran Fluoresen', key_en: 'Fluorescence Channels', value_id: '4 channel (FAM, HEX, Red 610, Red 670)', value_en: '4 channels (FAM, HEX, Red 610, Red 670)' },
      { key_id: 'Dimensi (P×L×T)', key_en: 'Dimensions (W×D×H)', value_id: '31 × 36 × 27 cm', value_en: '31 × 36 × 27 cm' },
      { key_id: 'Berat', key_en: 'Weight', value_id: '6.2 kg', value_en: '6.2 kg' },
      { key_id: 'Sumber Daya', key_en: 'Power Supply', value_id: '100–240 V AC, 50/60 Hz', value_en: '100–240 V AC, 50/60 Hz' },
    ],
    hasBrochure: true,
  },
  {
    id: 2, slug_id: 'media-kultur-vrba', slug_en: 'vrba-culture-media',
    name_id: 'Media Kultur VRBA (Violet Red Bile Agar)', name_en: 'VRBA Culture Media (Violet Red Bile Agar)',
    summary_id: 'Media selektif beresolusi tinggi untuk isolasi dan penghitungan bakteri koliform dalam sampel pangan, air, dan produk susu.',
    summary_en: 'High-resolution selective media for isolation and enumeration of coliform bacteria in food, water, and dairy samples.',
    description_id: 'Media Kultur VRBA adalah media selektif dan diferensial yang dirancang khusus untuk isolasi bakteri koliform yang menjadi indikator kualitas sanitasi pangan dan air minum.',
    description_en: 'VRBA Culture Media is a selective and differential medium specially designed for isolating coliform bacteria as indicators of food and drinking water sanitation quality.',
    category_id: 1, brand_id: 3,
    image: 'https://images.unsplash.com/photo-1576086213369-b0e0dfc87f5e?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1576086213369-b0e0dfc87f5e?w=800&h=800&fit=crop&auto=format',
      'https://images.unsplash.com/photo-1628595351029-c2bf17511435?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Bentuk', key_en: 'Form', value_id: 'Serbuk dehidrasi', value_en: 'Dehydrated powder' },
      { key_id: 'Ukuran Kemasan', key_en: 'Package Size', value_id: '500 g', value_en: '500 g' },
      { key_id: 'pH (25°C)', key_en: 'pH (25°C)', value_id: '7.4 ± 0.2', value_en: '7.4 ± 0.2' },
      { key_id: 'Suhu Inkubasi', key_en: 'Incubation Temperature', value_id: '37°C ± 1°C', value_en: '37°C ± 1°C' },
      { key_id: 'Waktu Inkubasi', key_en: 'Incubation Time', value_id: '18–24 jam', value_en: '18–24 hours' },
      { key_id: 'Penyimpanan', key_en: 'Storage', value_id: '2–30°C, tempat kering', value_en: '2–30°C, dry place' },
    ],
    hasBrochure: true,
  },
  {
    id: 3, slug_id: 'kit-elisa-salmonella', slug_en: 'salmonella-elisa-kit',
    name_id: 'Kit ELISA Deteksi Salmonella spp.', name_en: 'Salmonella spp. Detection ELISA Kit',
    summary_id: 'Kit ELISA sandwich beresolusi tinggi untuk deteksi dan kuantifikasi Salmonella spp. dalam sampel pangan, pakan ternak, dan lingkungan.',
    summary_en: 'High-resolution sandwich ELISA kit for detection and quantification of Salmonella spp. in food, animal feed, and environmental samples.',
    description_id: 'Kit ELISA Deteksi Salmonella spp. merupakan sistem imunoassay berbasis sandwich ELISA yang menggunakan antibodi monoklonal spesifik terhadap antigen permukaan Salmonella.',
    description_en: 'The Salmonella spp. Detection ELISA Kit is an immunoassay system based on sandwich ELISA using monoclonal antibodies specific to Salmonella surface antigens.',
    category_id: 3, brand_id: 2,
    image: 'https://images.unsplash.com/photo-1588421357574-87938a86fa28?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1588421357574-87938a86fa28?w=800&h=800&fit=crop&auto=format',
      'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Prinsip Uji', key_en: 'Test Principle', value_id: 'Sandwich ELISA', value_en: 'Sandwich ELISA' },
      { key_id: 'Format', key_en: 'Format', value_id: '96 sumur / plate', value_en: '96 wells / plate' },
      { key_id: 'Sensitivitas', key_en: 'Sensitivity', value_id: '0.5 ng/mL', value_en: '0.5 ng/mL' },
      { key_id: 'Spesifisitas', key_en: 'Specificity', value_id: '> 99%', value_en: '> 99%' },
      { key_id: 'Waktu Pengujian', key_en: 'Test Duration', value_id: '2.5 jam', value_en: '2.5 hours' },
      { key_id: 'Penyimpanan', key_en: 'Storage', value_id: '2–8°C', value_en: '2–8°C' },
      { key_id: 'Stabilitas', key_en: 'Stability', value_id: '12 bulan sejak tanggal produksi', value_en: '12 months from production date' },
    ],
    hasBrochure: false,
  },
  {
    id: 4, slug_id: 'cawan-petri-steril', slug_en: 'sterile-petri-dish',
    name_id: 'Cawan Petri Steril Sekali Pakai 90 mm', name_en: 'Sterile Disposable Petri Dish 90 mm',
    summary_id: 'Cawan petri plastik PS bermutu tinggi, steril radiasi gamma, clear dan tidak mengandung DNase/RNase untuk kultur mikrobiologi.',
    summary_en: 'High-quality PS plastic petri dishes, gamma radiation sterilized, clear and DNase/RNase-free for microbiological culture.',
    description_id: 'Cawan Petri Steril ini terbuat dari polistiren (PS) optik-clear Grade A yang bebas DNase dan RNase, disterilkan dengan iradiasi gamma untuk memastikan kondisi aseptik sempurna pada setiap penggunaan.',
    description_en: 'These Sterile Petri Dishes are made from optical-clear Grade A polystyrene (PS) free of DNase and RNase, sterilized by gamma irradiation to ensure perfect aseptic conditions.',
    category_id: 6, brand_id: 3,
    image: 'https://images.unsplash.com/photo-1628595351029-c2bf17511435?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1628595351029-c2bf17511435?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Material', key_en: 'Material', value_id: 'Polistiren (PS) Optical-Clear Grade A', value_en: 'Optical-Clear Grade A Polystyrene (PS)' },
      { key_id: 'Diameter', key_en: 'Diameter', value_id: '90 mm', value_en: '90 mm' },
      { key_id: 'Tinggi', key_en: 'Height', value_id: '15 mm', value_en: '15 mm' },
      { key_id: 'Volume Media Optimal', key_en: 'Optimal Media Volume', value_id: '15–20 mL', value_en: '15–20 mL' },
      { key_id: 'Metode Sterilisasi', key_en: 'Sterilization Method', value_id: 'Iradiasi Gamma', value_en: 'Gamma Irradiation' },
      { key_id: 'Kemasan', key_en: 'Packaging', value_id: '500 cawan / karton', value_en: '500 dishes / carton' },
    ],
    hasBrochure: false,
  },
  {
    id: 5, slug_id: 'kolom-hplc-c18', slug_en: 'hplc-column-c18',
    name_id: 'Kolom HPLC Fase Terbalik C18 LiChrospher®', name_en: 'LiChrospher® C18 Reversed-Phase HPLC Column',
    summary_id: 'Kolom HPLC fase terbalik C18 beresolusi ultra-tinggi dengan partikel silika 5 µm untuk pemisahan senyawa organik kompleks.',
    summary_en: 'Ultra-high resolution C18 reversed-phase HPLC column with 5 µm silica particles for separation of complex organic compounds.',
    description_id: 'Kolom HPLC LiChrospher® C18 menggunakan partikel silika berpori terikat fase C18 (oktadesil) beresolusi tinggi yang memberikan pemisahan senyawa organik nonpolar dengan reproducibility dan peak symmetry yang luar biasa.',
    description_en: 'The LiChrospher® C18 HPLC column uses high-resolution porous silica particles bonded with C18 (octadecyl) phase providing outstanding separation of nonpolar organic compounds with excellent reproducibility and peak symmetry.',
    category_id: 5, brand_id: 1,
    image: 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=800&h=800&fit=crop&auto=format',
      'https://images.unsplash.com/photo-1576086213369-b0e0dfc87f5e?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Fase Stasioner', key_en: 'Stationary Phase', value_id: 'Silika C18 (Oktadesil) End-Capped', value_en: 'C18 (Octadecyl) End-Capped Silica' },
      { key_id: 'Ukuran Partikel', key_en: 'Particle Size', value_id: '5 µm', value_en: '5 µm' },
      { key_id: 'Ukuran Pori', key_en: 'Pore Size', value_id: '100 Å', value_en: '100 Å' },
      { key_id: 'Dimensi Kolom', key_en: 'Column Dimensions', value_id: '250 × 4.6 mm', value_en: '250 × 4.6 mm' },
      { key_id: 'Rentang pH', key_en: 'pH Range', value_id: '2 – 9', value_en: '2 – 9' },
      { key_id: 'Tekanan Maks.', key_en: 'Max. Pressure', value_id: '350 bar', value_en: '350 bar' },
    ],
    hasBrochure: true,
  },
  {
    id: 6, slug_id: 'pembaca-lateral-flow', slug_en: 'lateral-flow-reader',
    name_id: 'Pembaca Lateral Flow Soleris® Digital', name_en: 'Soleris® Digital Lateral Flow Reader',
    summary_id: 'Pembaca strip lateral flow digital dengan teknologi optik untuk interpretasi kuantitatif dan penyimpanan data hasil uji cepat mikotoksin.',
    summary_en: 'Digital lateral flow strip reader with optical technology for quantitative interpretation and data storage of rapid mycotoxin test results.',
    description_id: 'Pembaca Lateral Flow Soleris® Digital memungkinkan interpretasi hasil strip uji lateral flow secara kuantitatif dan objektif, mengeliminasi variabilitas pembacaan visual antar operator.',
    description_en: 'The Soleris® Digital Lateral Flow Reader enables quantitative and objective interpretation of lateral flow test strip results, eliminating visual reading variability between operators.',
    category_id: 4, brand_id: 2,
    image: 'https://images.unsplash.com/photo-1605289982774-9a6fef564df8?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1605289982774-9a6fef564df8?w=800&h=800&fit=crop&auto=format',
      'https://images.unsplash.com/photo-1588421357574-87938a86fa28?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Teknologi Deteksi', key_en: 'Detection Technology', value_id: 'Optik reflektansi (CCD)', value_en: 'Reflectance optical (CCD)' },
      { key_id: 'Waktu Analisis', key_en: 'Analysis Time', value_id: '< 5 detik per strip', value_en: '< 5 seconds per strip' },
      { key_id: 'Rentang Pengukuran', key_en: 'Measurement Range', value_id: '1 – 500 ppb (tergantung kit)', value_en: '1 – 500 ppb (kit-dependent)' },
      { key_id: 'Penyimpanan Data', key_en: 'Data Storage', value_id: '10.000 hasil uji', value_en: '10,000 test results' },
      { key_id: 'Konektivitas', key_en: 'Connectivity', value_id: 'USB, Bluetooth 4.0', value_en: 'USB, Bluetooth 4.0' },
      { key_id: 'Daya', key_en: 'Power', value_id: 'Baterai Li-Ion atau adaptor 5V DC', value_en: 'Li-Ion battery or 5V DC adapter' },
    ],
    hasBrochure: true,
  },
  {
    id: 7, slug_id: 'sistem-elektroforesis-gel', slug_en: 'gel-electrophoresis-system',
    name_id: 'Sistem Elektroforesis Gel Agarosa Kompak', name_en: 'Compact Agarose Gel Electrophoresis System',
    summary_id: 'Sistem elektroforesis gel agarosa horizontal lengkap untuk pemisahan fragmen DNA/RNA dalam analisis biologi molekuler rutin.',
    summary_en: 'Complete horizontal agarose gel electrophoresis system for separation of DNA/RNA fragments in routine molecular biology analysis.',
    description_id: 'Sistem ini mencakup tangki elektroforesis, sisir multiformat, sumber daya DC regulasi, dan aksesoris lengkap untuk kebutuhan pemisahan asam nukleat rutin laboratorium.',
    description_en: 'This system includes an electrophoresis tank, multi-format combs, regulated DC power supply, and complete accessories for routine nucleic acid separation laboratory needs.',
    category_id: 2, brand_id: 1,
    image: 'https://images.unsplash.com/photo-1614935151651-0bea6508db6b?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1614935151651-0bea6508db6b?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Ukuran Gel', key_en: 'Gel Size', value_id: '7 × 10 cm dan 10 × 15 cm', value_en: '7 × 10 cm and 10 × 15 cm' },
      { key_id: 'Kapasitas Sumur', key_en: 'Well Capacity', value_id: '8, 12, atau 15 sumur', value_en: '8, 12, or 15 wells' },
      { key_id: 'Tegangan Operasi', key_en: 'Operating Voltage', value_id: '20–250 V DC', value_en: '20–250 V DC' },
      { key_id: 'Waktu Run Tipikal', key_en: 'Typical Run Time', value_id: '25–45 menit', value_en: '25–45 minutes' },
      { key_id: 'Buffer Kompatibel', key_en: 'Compatible Buffer', value_id: 'TAE, TBE', value_en: 'TAE, TBE' },
    ],
    hasBrochure: false,
  },
  {
    id: 8, slug_id: 'blood-agar-base', slug_en: 'blood-agar-base',
    name_id: 'Blood Agar Base (BAB) — Media Dasar Agar Darah', name_en: 'Blood Agar Base (BAB) — Basal Medium',
    summary_id: 'Media dasar berkualitas tinggi untuk pembuatan agar darah guna isolasi, identifikasi, dan uji sensitivitas bakteri patogen klinis.',
    summary_en: 'High-quality base medium for preparing blood agar for isolation, identification, and sensitivity testing of clinical pathogenic bacteria.',
    description_id: 'Blood Agar Base (BAB) merupakan media dasar nutrisi kompleks yang dirancang untuk digunakan bersama darah defibrinasi dalam pembuatan agar darah lengkap untuk keperluan diagnostik klinis dan penelitian mikrobiologi.',
    description_en: 'Blood Agar Base (BAB) is a complex nutrient base medium designed for use with defibrinated blood to prepare complete blood agar for clinical diagnostic and microbiological research purposes.',
    category_id: 1, brand_id: 3,
    image: 'https://images.unsplash.com/photo-1579165466741-7f35e4755660?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1579165466741-7f35e4755660?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Bentuk', key_en: 'Form', value_id: 'Serbuk granular dehidrasi', value_en: 'Dehydrated granular powder' },
      { key_id: 'Kemasan', key_en: 'Package', value_id: '500 g', value_en: '500 g' },
      { key_id: 'pH Akhir (25°C)', key_en: 'Final pH (25°C)', value_id: '7.3 ± 0.2', value_en: '7.3 ± 0.2' },
      { key_id: 'Suhu Sterilisasi', key_en: 'Sterilization Temp.', value_id: '121°C, 15 menit', value_en: '121°C, 15 minutes' },
      { key_id: 'Tambahan Darah', key_en: 'Blood Supplement', value_id: '5–10% darah defibrinasi', value_en: '5–10% defibrinated blood' },
    ],
    hasBrochure: false,
  },
  {
    id: 9, slug_id: 'kit-elisa-aflatoksin', slug_en: 'aflatoxin-elisa-kit',
    name_id: 'Kit ELISA Aflatoksin B1 Total — Uji Cepat', name_en: 'Total Aflatoxin B1 ELISA Kit — Rapid Test',
    summary_id: 'Kit ELISA kompetitif beresolusi tinggi untuk deteksi dan kuantifikasi aflatoksin B1 total dalam serealia, kacang, rempah, dan pakan ternak.',
    summary_en: 'High-resolution competitive ELISA kit for detection and quantification of total aflatoxin B1 in cereals, nuts, spices, and animal feed.',
    description_id: 'Kit ELISA ini menggunakan prinsip kompetitif dengan antibodi monoklonal spesifik terhadap aflatoksin B1 yang memberikan sensitivitas tinggi dan cross-reactivity minimal terhadap mikotoksin lainnya.',
    description_en: 'This ELISA kit uses a competitive principle with monoclonal antibodies specific to aflatoxin B1, providing high sensitivity and minimal cross-reactivity with other mycotoxins.',
    category_id: 3, brand_id: 2,
    image: 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Prinsip Uji', key_en: 'Test Principle', value_id: 'Kompetitif ELISA', value_en: 'Competitive ELISA' },
      { key_id: 'Batas Deteksi (LOD)', key_en: 'Detection Limit (LOD)', value_id: '0.1 ppb (µg/kg)', value_en: '0.1 ppb (µg/kg)' },
      { key_id: 'Rentang Kuantifikasi', key_en: 'Quantification Range', value_id: '0.5 – 50 ppb', value_en: '0.5 – 50 ppb' },
      { key_id: 'Recovery Rate', key_en: 'Recovery Rate', value_id: '80 – 120%', value_en: '80 – 120%' },
      { key_id: 'Waktu Total Uji', key_en: 'Total Test Time', value_id: '1.5 jam', value_en: '1.5 hours' },
    ],
    hasBrochure: true,
  },
  {
    id: 10, slug_id: 'spektrofotometer-uv-vis', slug_en: 'uv-vis-spectrophotometer',
    name_id: 'Spektrofotometer UV-Vis Genesys™ 150', name_en: 'Genesys™ 150 UV-Vis Spectrophotometer',
    summary_id: 'Spektrofotometer UV-Vis scanning presisi tinggi dengan rentang panjang gelombang 190–1100 nm untuk analisis senyawa organik dan anorganik.',
    summary_en: 'High-precision scanning UV-Vis spectrophotometer with 190–1100 nm wavelength range for analysis of organic and inorganic compounds.',
    description_id: 'Spektrofotometer UV-Vis Genesys™ 150 adalah instrumen analitik kelas referensi yang menggabungkan optik berkualitas tinggi dengan elektronik presisi untuk menghasilkan pengukuran absorbansi dan transmitansi yang akurat dan reproducible.',
    description_en: 'The Genesys™ 150 UV-Vis Spectrophotometer is a reference-class analytical instrument combining high-quality optics with precision electronics to deliver accurate and reproducible absorbance and transmittance measurements.',
    category_id: 4, brand_id: 1,
    image: 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=800&h=800&fit=crop&auto=format',
      'https://images.unsplash.com/photo-1614935151651-0bea6508db6b?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Rentang Panjang Gelombang', key_en: 'Wavelength Range', value_id: '190 – 1100 nm', value_en: '190 – 1100 nm' },
      { key_id: 'Bandwidth Spektral', key_en: 'Spectral Bandwidth', value_id: '0.5, 1.0, 2.0, 4.0 nm', value_en: '0.5, 1.0, 2.0, 4.0 nm' },
      { key_id: 'Akurasi Panjang Gelombang', key_en: 'Wavelength Accuracy', value_id: '±0.3 nm', value_en: '±0.3 nm' },
      { key_id: 'Rentang Fotometrik', key_en: 'Photometric Range', value_id: '-0.3 – 4.0 Abs', value_en: '-0.3 – 4.0 Abs' },
      { key_id: 'Layar', key_en: 'Display', value_id: '10" LCD touchscreen', value_en: '10" LCD touchscreen' },
      { key_id: 'Konektivitas', key_en: 'Connectivity', value_id: 'USB, Ethernet, Wi-Fi', value_en: 'USB, Ethernet, Wi-Fi' },
    ],
    hasBrochure: true,
  },
  {
    id: 11, slug_id: 'tabung-pcr-strip', slug_en: 'pcr-tubes-strips',
    name_id: 'Tabung & Strip PCR 0.2 mL Low-Profile', name_en: 'Low-Profile PCR Tubes & Strips 0.2 mL',
    summary_id: 'Tabung dan strip PCR 0.2 mL berprofile rendah yang kompatibel dengan blok standar PCR dan disterilkan bebas RNase/DNase/DNA.',
    summary_en: 'Low-profile 0.2 mL PCR tubes and strips compatible with standard PCR blocks, sterilized RNase/DNase/DNA-free.',
    description_id: 'Tabung PCR ini dirancang untuk memberikan kontak termal optimal antara sampel dan blok instrumen PCR, menghasilkan amplifikasi yang efisien dan reproducible pada setiap siklus termal.',
    description_en: 'These PCR tubes are designed to provide optimal thermal contact between the sample and PCR instrument block, resulting in efficient and reproducible amplification in every thermal cycle.',
    category_id: 6, brand_id: 3,
    image: 'https://images.unsplash.com/photo-1579165466741-7f35e4755660?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1579165466741-7f35e4755660?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Volume', key_en: 'Volume', value_id: '0.2 mL', value_en: '0.2 mL' },
      { key_id: 'Format', key_en: 'Format', value_id: 'Tabung individu & strip 8-tube', value_en: 'Individual tubes & 8-tube strips' },
      { key_id: 'Material', key_en: 'Material', value_id: 'Polipropilen (PP) murni', value_en: 'Pure Polypropylene (PP)' },
      { key_id: 'Sterilitas', key_en: 'Sterility', value_id: 'Bebas RNase, DNase, DNA, pirogenFree', value_en: 'RNase-free, DNase-free, DNA-free, Pyrogen-free' },
      { key_id: 'Kemasan', key_en: 'Packaging', value_id: '1.000 tabung / pak', value_en: '1,000 tubes / pack' },
    ],
    hasBrochure: false,
  },
  {
    id: 12, slug_id: 'kit-uji-mikotoksin', slug_en: 'mycotoxin-test-kit',
    name_id: 'Kit Uji Cepat Mikotoksin Multi-Analit Reveal Q+®', name_en: 'Reveal Q+® Multi-Analyte Mycotoxin Rapid Test Kit',
    summary_id: 'Kit uji lateral flow kuantitatif multi-analit untuk deteksi simultan aflatoksin, deoksinivalenol, dan fumonisin dalam satu strip uji cepat.',
    summary_en: 'Quantitative multi-analyte lateral flow test kit for simultaneous detection of aflatoxin, deoxynivalenol, and fumonisin in one rapid test strip.',
    description_id: 'Reveal Q+® adalah kit uji cepat generasi terbaru yang memungkinkan deteksi dan kuantifikasi simultan beberapa mikotoksin utama dalam satu proses pengujian menggunakan teknologi lateral flow terbaru.',
    description_en: 'Reveal Q+® is the latest generation rapid test kit enabling simultaneous detection and quantification of multiple major mycotoxins in a single testing process using the latest lateral flow technology.',
    category_id: 4, brand_id: 2,
    image: 'https://images.unsplash.com/photo-1605289982774-9a6fef564df8?w=800&h=800&fit=crop&auto=format',
    images: [
      'https://images.unsplash.com/photo-1605289982774-9a6fef564df8?w=800&h=800&fit=crop&auto=format',
    ],
    specifications: [
      { key_id: 'Analit Target', key_en: 'Target Analytes', value_id: 'Aflatoksin, DON, Fumonisin', value_en: 'Aflatoxin, DON, Fumonisin' },
      { key_id: 'Format', key_en: 'Format', value_id: 'Lateral flow strip kuantitatif', value_en: 'Quantitative lateral flow strip' },
      { key_id: 'Waktu Uji', key_en: 'Test Time', value_id: '7 menit', value_en: '7 minutes' },
      { key_id: 'Akurasi', key_en: 'Accuracy', value_id: 'Setara metode ELISA referensi', value_en: 'Equivalent to reference ELISA method' },
      { key_id: 'Kemasan', key_en: 'Packaging', value_id: '25 strip / kit', value_en: '25 strips / kit' },
      { key_id: 'Penyimpanan', key_en: 'Storage', value_id: '2–25°C', value_en: '2–25°C' },
    ],
    hasBrochure: false,
  },
]

const CORE_VALUES = [
  { icon: '◎', title_id: 'Customer Focus', title_en: 'Customer Focus', desc_id: 'Menempatkan kebutuhan pelanggan sebagai prioritas utama dalam setiap keputusan bisnis dan solusi yang kami hadirkan.', desc_en: 'Placing customer needs as the top priority in every business decision and solution we deliver.' },
  { icon: '◈', title_id: 'Innovative', title_en: 'Innovative', desc_id: 'Mengadopsi teknologi dan solusi terkini untuk memberikan nilai tambah yang relevan dan terukur bagi pelanggan.', desc_en: 'Adopting the latest technologies and solutions to deliver relevant and measurable added value to customers.' },
  { icon: '◇', title_id: 'Integrity', title_en: 'Integrity', desc_id: 'Menjaga kejujuran, transparansi, dan etika bisnis yang tinggi dalam setiap hubungan dengan pelanggan, prinsipal, dan mitra.', desc_en: 'Maintaining honesty, transparency, and high business ethics in every relationship with customers, principals, and partners.' },
  { icon: '◉', title_id: 'Collaborative', title_en: 'Collaborative', desc_id: 'Membangun kemitraan sinergis dengan pelanggan, prinsipal global, dan tim internal untuk mencapai hasil yang optimal bersama.', desc_en: 'Building synergistic partnerships with customers, global principals, and internal teams to achieve optimal results together.' },
  { icon: '◑', title_id: 'Commitment', title_en: 'Commitment', desc_id: 'Memberikan dukungan teknis dan purna jual yang konsisten dan berkelanjutan untuk menjamin kepuasan dan keberhasilan pelanggan.', desc_en: 'Providing consistent and sustained technical and after-sales support to ensure customer satisfaction and success.' },
  { icon: '◐', title_id: 'Agility', title_en: 'Agility', desc_id: 'Beradaptasi secara cepat terhadap dinamika pasar, regulasi industri, dan kebutuhan pelanggan yang terus berkembang.', desc_en: 'Rapidly adapting to market dynamics, industry regulations, and continuously evolving customer needs.' },
]

const CLIENTS = [
  'RS Cipto Mangunkusumo', 'Bio Farma (Persero)', 'Prodia Laboratory', 'Universitas Indonesia',
  'Institut Teknologi Bandung', 'PT Kimia Farma Tbk.', 'RS Premier Bintaro', 'BPOM RI',
  'Universitas Gadjah Mada', 'LIPI / BRIN', 'PT Indofood Sukses Makmur', 'RS Pondok Indah Group',
]

const HERO_SLIDES = [
  {
    image: 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=1920&h=800&fit=crop&auto=format',
    title_id: 'Solusi Distribusi Peralatan Laboratorium & Diagnostik Terpercaya',
    title_en: 'Trusted Laboratory & Diagnostic Equipment Distribution Solutions',
    sub_id: 'Lebih dari 15 tahun melayani rumah sakit, laboratorium riset, institusi pendidikan, dan industri farmasi di Indonesia.',
    sub_en: 'Over 15 years serving hospitals, research laboratories, educational institutions, and pharmaceutical industries in Indonesia.',
    cta_id: 'Jelajahi Katalog Produk',
    cta_en: 'Explore Product Catalog',
  },
  {
    image: 'https://images.unsplash.com/photo-1579165466741-7f35e4755660?w=1920&h=800&fit=crop&auto=format',
    title_id: 'Distributor Resmi Merck, Neogen & Era Biology di Indonesia',
    title_en: 'Official Distributor of Merck, Neogen & Era Biology in Indonesia',
    sub_id: 'Produk autentik bersertifikat dari prinsipal terkemuka dunia, didukung dukungan teknis purna jual profesional.',
    sub_en: 'Certified authentic products from leading global principals, backed by professional technical after-sales support.',
    cta_id: 'Lihat Mitra Prinsipal',
    cta_en: 'View Principal Partners',
  },
  {
    image: 'https://images.unsplash.com/photo-1614935151651-0bea6508db6b?w=1920&h=800&fit=crop&auto=format',
    title_id: 'Dari Diagnostik Klinis hingga Penelitian Bioteknologi',
    title_en: 'From Clinical Diagnostics to Biotechnology Research',
    sub_id: 'Portofolio produk komprehensif mencakup reagen, instrumen, dan perlengkapan laboratorium untuk kebutuhan ilmiah terdepan.',
    sub_en: 'Comprehensive product portfolio covering reagents, instruments, and laboratory supplies for cutting-edge scientific needs.',
    cta_id: 'Minta Penawaran Harga',
    cta_en: 'Request a Quotation',
  },
]

// ── ICONS ─────────────────────────────────────────────────────────────────────

function IconMenu({ className = 'w-6 h-6' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
    </svg>
  )
}
function IconX({ className = 'w-6 h-6' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M6 18L18 6M6 6l12 12" />
    </svg>
  )
}
function IconChevronRight({ className = 'w-4 h-4' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8.25 4.5l7.5 7.5-7.5 7.5" />
    </svg>
  )
}
function IconChevronLeft({ className = 'w-5 h-5' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15.75 19.5L8.25 12l7.5-7.5" />
    </svg>
  )
}
function IconArrowRight({ className = 'w-4 h-4' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
    </svg>
  )
}
function IconPhone({ className = 'w-5 h-5' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
    </svg>
  )
}
function IconEnvelope({ className = 'w-5 h-5' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
    </svg>
  )
}
function IconMapPin({ className = 'w-5 h-5' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
    </svg>
  )
}
function IconWhatsApp({ className = 'w-5 h-5' }) {
  return (
    <svg className={className} fill="currentColor" viewBox="0 0 24 24">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
    </svg>
  )
}
function IconCheck({ className = 'w-5 h-5' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.5 12.75l6 6 9-13.5" />
    </svg>
  )
}
function IconDocument({ className = 'w-5 h-5' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    </svg>
  )
}
function IconFunnel({ className = 'w-5 h-5' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
    </svg>
  )
}
function IconSearch({ className = 'w-5 h-5' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z" />
    </svg>
  )
}
function IconBuildingOffice({ className = 'w-5 h-5' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
    </svg>
  )
}
function IconStar({ className = 'w-8 h-8' }) {
  return (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
    </svg>
  )
}

// ── ANS LOGO SVG ──────────────────────────────────────────────────────────────

function AnsLogo({ className = 'h-10', showText = true }: { className?: string; showText?: boolean }) {
  return (
    <div className="flex items-center gap-3">
      <img src={logoAns} alt="PT Abhipraya Nawasena Sejahtera" className={className} />
      {showText && (
        <div className="flex flex-col leading-none">
          <span className="text-[11px] font-semibold tracking-widest text-teal-700 uppercase" style={{ letterSpacing: '0.18em' }}>PT Abhipraya Nawasena</span>
          <span className="text-[15px] font-bold text-slate-900 tracking-tight">Sejahtera</span>
        </div>
      )}
    </div>
  )
}

// ── HEADER ────────────────────────────────────────────────────────────────────

function Header({ locale, setLocale, currentPage, setPage, scrolled }: {
  locale: Locale; setLocale: (l: Locale) => void;
  currentPage: Page; setPage: (p: Page) => void; scrolled: boolean
}) {
  const [mobileOpen, setMobileOpen] = useState(false)
  const t = (id: string, en: string) => locale === 'id' ? id : en

  const navItems: { label_id: string; label_en: string; page: Page }[] = [
    { label_id: 'Beranda', label_en: 'Home', page: 'home' },
    { label_id: 'Tentang Kami', label_en: 'About Us', page: 'about' },
    { label_id: 'Produk', label_en: 'Products', page: 'catalog' },
    { label_id: 'Mitra & Klien', label_en: 'Partners & Clients', page: 'partners' },
    { label_id: 'Kontak', label_en: 'Contact', page: 'contact' },
  ]

  return (
    <header className={`fixed top-0 left-0 right-0 z-50 transition-all duration-200 ${scrolled ? 'bg-white border-b border-slate-200 shadow-sm' : 'bg-white/95 backdrop-blur-sm'}`}>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16 md:h-18">
          {/* Logo */}
          <button onClick={() => { setPage('home'); setMobileOpen(false) }} className="flex-shrink-0 focus-ring rounded-lg">
            <AnsLogo className="h-9 md:h-10" />
          </button>

          {/* Desktop Nav */}
          <nav className="hidden lg:flex items-center gap-1">
            {navItems.map(item => (
              <button
                key={item.page}
                onClick={() => setPage(item.page)}
                className={`px-3.5 py-2 rounded-md text-sm font-medium transition-colors focus-ring ${
                  currentPage === item.page || (currentPage === 'product-detail' && item.page === 'catalog')
                    ? 'text-teal-700 bg-teal-50 font-semibold'
                    : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'
                }`}
              >
                {locale === 'id' ? item.label_id : item.label_en}
              </button>
            ))}
          </nav>

          {/* Right side */}
          <div className="flex items-center gap-3">
            {/* Language Switcher */}
            <div className="flex items-center bg-slate-100 rounded-full p-1 gap-0.5" aria-label="Pilih Bahasa / Select Language">
              {(['id', 'en'] as Locale[]).map(l => (
                <button
                  key={l}
                  onClick={() => setLocale(l)}
                  className={`px-3 py-1 rounded-full text-xs font-semibold transition-all focus-ring ${
                    locale === l ? 'bg-teal-700 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'
                  }`}
                >
                  {l.toUpperCase()}
                </button>
              ))}
            </div>

            {/* CTA */}
            <button
              onClick={() => setPage('contact')}
              className="hidden lg:flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors focus-ring"
            >
              {t('Hubungi Kami', 'Contact Us')}
            </button>

            {/* Mobile hamburger */}
            <button
              onClick={() => setMobileOpen(!mobileOpen)}
              className="lg:hidden p-2 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors focus-ring"
              aria-label="Toggle menu"
            >
              {mobileOpen ? <IconX /> : <IconMenu />}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Drawer */}
      {mobileOpen && (
        <div className="lg:hidden border-t border-slate-200 bg-white">
          <nav className="px-4 py-3 space-y-1">
            {navItems.map(item => (
              <button
                key={item.page}
                onClick={() => { setPage(item.page); setMobileOpen(false) }}
                className={`w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-colors ${
                  currentPage === item.page ? 'text-teal-700 bg-teal-50 font-semibold' : 'text-slate-700 hover:bg-slate-50'
                }`}
              >
                {locale === 'id' ? item.label_id : item.label_en}
              </button>
            ))}
            <div className="pt-2">
              <button
                onClick={() => { setPage('contact'); setMobileOpen(false) }}
                className="w-full bg-teal-700 text-white text-sm font-semibold py-3 rounded-lg transition-colors hover:bg-teal-800"
              >
                {t('Hubungi Kami', 'Contact Us')}
              </button>
            </div>
          </nav>
        </div>
      )}
    </header>
  )
}

// ── FOOTER ────────────────────────────────────────────────────────────────────

function Footer({ locale, setPage }: { locale: Locale; setPage: (p: Page) => void }) {
  const t = (id: string, en: string) => locale === 'id' ? id : en

  return (
    <footer className="bg-slate-900 text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
          {/* Col 1 - Brand */}
          <div className="lg:col-span-1">
            <div className="flex items-center gap-3 mb-5">
              <img src={logoAns} alt="ANS" className="h-10 brightness-0 invert" />
              <div>
                <div className="text-[10px] font-semibold tracking-widest text-teal-400 uppercase">PT Abhipraya Nawasena</div>
                <div className="text-[14px] font-bold text-white">Sejahtera</div>
              </div>
            </div>
            <p className="text-slate-400 text-sm leading-relaxed">
              {t(
                'Distributor resmi peralatan kesehatan, diagnostik, dan laboratorium terkemuka di Indonesia. Melayani dengan standar mutu internasional sejak lebih dari 15 tahun.',
                'Official distributor of leading medical, diagnostic, and laboratory equipment in Indonesia. Serving with international quality standards for over 15 years.'
              )}
            </p>
          </div>

          {/* Col 2 - Quick Links */}
          <div>
            <h4 className="text-sm font-semibold text-white uppercase tracking-wider mb-5">{t('Tautan Cepat', 'Quick Links')}</h4>
            <ul className="space-y-3">
              {[
                { id: 'Beranda', en: 'Home', page: 'home' as Page },
                { id: 'Tentang Kami', en: 'About Us', page: 'about' as Page },
                { id: 'Katalog Produk', en: 'Product Catalog', page: 'catalog' as Page },
                { id: 'Mitra & Klien', en: 'Partners & Clients', page: 'partners' as Page },
                { id: 'Kontak', en: 'Contact', page: 'contact' as Page },
              ].map(item => (
                <li key={item.page}>
                  <button
                    onClick={() => setPage(item.page)}
                    className="text-slate-400 hover:text-teal-400 text-sm transition-colors"
                  >
                    {locale === 'id' ? item.id : item.en}
                  </button>
                </li>
              ))}
            </ul>
          </div>

          {/* Col 3 - Contact */}
          <div>
            <h4 className="text-sm font-semibold text-white uppercase tracking-wider mb-5">{t('Informasi Kontak', 'Contact Information')}</h4>
            <ul className="space-y-4">
              <li className="flex gap-3">
                <IconMapPin className="w-5 h-5 text-teal-400 flex-shrink-0 mt-0.5" />
                <span className="text-slate-400 text-sm leading-relaxed">Mensana Tower Lt. 15, Jl. Raya Kranggan Kav. 1, Cibubur, Bekasi, Jawa Barat 17433</span>
              </li>
              <li className="flex gap-3">
                <IconPhone className="w-5 h-5 text-teal-400 flex-shrink-0" />
                <a href="tel:02139722772" className="text-slate-400 hover:text-teal-400 text-sm transition-colors">(021) 39722772</a>
              </li>
              <li className="flex gap-3">
                <IconWhatsApp className="w-5 h-5 text-teal-400 flex-shrink-0" />
                <a href="https://wa.me/6282261461400" className="text-slate-400 hover:text-teal-400 text-sm transition-colors">0822-614-614-00</a>
              </li>
              <li className="flex gap-3">
                <IconEnvelope className="w-5 h-5 text-teal-400 flex-shrink-0" />
                <a href="mailto:admin@avenasa.co.id" className="text-slate-400 hover:text-teal-400 text-sm transition-colors">admin@avenasa.co.id</a>
              </li>
            </ul>
          </div>

          {/* Col 4 - Principals */}
          <div>
            <h4 className="text-sm font-semibold text-white uppercase tracking-wider mb-5">{t('Prinsipal Resmi', 'Official Principals')}</h4>
            <div className="space-y-3">
              {BRANDS.map(b => (
                <div key={b.id} className="flex items-center gap-2">
                  <div className="w-1.5 h-1.5 rounded-full bg-teal-400" />
                  <span className="text-slate-400 text-sm font-medium">{b.name}</span>
                </div>
              ))}
            </div>
            <div className="mt-6 pt-6 border-t border-slate-800">
              <button
                onClick={() => setPage('contact')}
                className="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors"
              >
                {t('Minta Penawaran', 'Request Quote')}
                <IconArrowRight />
              </button>
            </div>
          </div>
        </div>

        <div className="mt-12 pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
          <p className="text-slate-500 text-sm">© 2026 PT Abhipraya Nawasena Sejahtera. All rights reserved.</p>
          <p className="text-slate-600 text-xs">Mensana Tower, Cibubur · NIB: 8120215771558</p>
        </div>
      </div>
    </footer>
  )
}

// ── HOME PAGE ─────────────────────────────────────────────────────────────────

function HeroCarousel({ locale, setPage }: { locale: Locale; setPage: (p: Page) => void }) {
  const [current, setCurrent] = useState(0)
  const [isPaused, setIsPaused] = useState(false)

  useEffect(() => {
    if (isPaused) return
    const timer = setInterval(() => setCurrent(c => (c + 1) % HERO_SLIDES.length), 5500)
    return () => clearInterval(timer)
  }, [isPaused])

  const slide = HERO_SLIDES[current]

  return (
    <div
      className="relative w-full overflow-hidden"
      style={{ height: 'min(620px, 70vh)' }}
      onMouseEnter={() => setIsPaused(true)}
      onMouseLeave={() => setIsPaused(false)}
    >
      {/* Background images */}
      {HERO_SLIDES.map((s, i) => (
        <div
          key={i}
          className="absolute inset-0 transition-opacity duration-700"
          style={{ opacity: i === current ? 1 : 0 }}
        >
          <img
            src={s.image}
            alt=""
            className="w-full h-full object-cover"
            loading={i === 0 ? 'eager' : 'lazy'}
          />
          <div className="hero-overlay absolute inset-0" />
        </div>
      ))}

      {/* Content */}
      <div className="relative z-10 h-full flex items-center">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
          <div className="max-w-2xl">
            <div className="inline-flex items-center gap-2 bg-teal-700/30 backdrop-blur-sm border border-teal-400/30 rounded-full px-4 py-1.5 mb-6">
              <div className="w-2 h-2 rounded-full bg-amber-400 animate-pulse" />
              <span className="text-teal-100 text-xs font-semibold tracking-wide uppercase">
                {locale === 'id' ? 'Distributor Resmi Terpercaya' : 'Trusted Official Distributor'}
              </span>
            </div>
            <h1 className="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-5" style={{ letterSpacing: '-0.02em' }}>
              {locale === 'id' ? slide.title_id : slide.title_en}
            </h1>
            <p className="text-base md:text-lg text-teal-100/90 leading-relaxed mb-8 max-w-xl">
              {locale === 'id' ? slide.sub_id : slide.sub_en}
            </p>
            <div className="flex flex-wrap gap-3">
              <button
                onClick={() => setPage(current === 1 ? 'partners' : current === 2 ? 'contact' : 'catalog')}
                className="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-500 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition-all active:scale-[0.98]"
              >
                {locale === 'id' ? slide.cta_id : slide.cta_en}
                <IconArrowRight />
              </button>
              <button
                onClick={() => setPage('about')}
                className="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/30 text-white font-semibold px-6 py-3 rounded-lg transition-all"
              >
                {locale === 'id' ? 'Profil Perusahaan' : 'Company Profile'}
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Dots */}
      <div className="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        {HERO_SLIDES.map((_, i) => (
          <button
            key={i}
            onClick={() => setCurrent(i)}
            className={`transition-all focus-ring rounded-full ${i === current ? 'w-8 h-2.5 bg-white' : 'w-2.5 h-2.5 bg-white/40 hover:bg-white/60'}`}
            aria-label={`Slide ${i + 1}`}
          />
        ))}
      </div>

      {/* Prev/Next */}
      <button
        onClick={() => setCurrent(c => (c - 1 + HERO_SLIDES.length) % HERO_SLIDES.length)}
        className="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 flex items-center justify-center text-white transition-colors focus-ring"
      >
        <IconChevronLeft />
      </button>
      <button
        onClick={() => setCurrent(c => (c + 1) % HERO_SLIDES.length)}
        className="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 flex items-center justify-center text-white transition-colors focus-ring"
      >
        <IconChevronRight className="w-5 h-5" />
      </button>
    </div>
  )
}

function HomePage({ locale, setPage, setProductId }: { locale: Locale; setPage: (p: Page) => void; setProductId: (id: number) => void }) {
  const t = (id: string, en: string) => locale === 'id' ? id : en
  const featuredProducts = PRODUCTS.slice(0, 4)

  const highlights = [
    { num: '15+', label_id: 'Tahun Pengalaman', label_en: 'Years Experience' },
    { num: '3', label_id: 'Prinsipal Resmi Global', label_en: 'Global Official Principals' },
    { num: '200+', label_id: 'Produk Terverifikasi', label_en: 'Verified Products' },
    { num: 'ISO', label_id: 'Standar Mutu Terjamin', label_en: 'Quality Assurance Standard' },
  ]

  return (
    <div>
      <HeroCarousel locale={locale} setPage={setPage} />

      {/* Highlight Strip */}
      <div className="bg-teal-700">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 lg:grid-cols-4 divide-x divide-teal-600">
            {highlights.map((h, i) => (
              <div key={i} className="py-7 px-6 text-center">
                <div className="text-2xl font-bold text-white">{h.num}</div>
                <div className="text-teal-200 text-sm mt-1">{locale === 'id' ? h.label_id : h.label_en}</div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Company Intro */}
      <section className="py-16 lg:py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
              <div className="inline-block text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1 rounded-md uppercase tracking-wide mb-4">
                {t('Tentang ANS', 'About ANS')}
              </div>
              <h2 className="text-3xl lg:text-4xl font-bold text-slate-900 mb-5 leading-tight" style={{ letterSpacing: '-0.02em' }}>
                {t(
                  'Mitra Terpercaya untuk Kebutuhan Ilmiah & Laboratorium Anda',
                  'Your Trusted Partner for Scientific & Laboratory Needs'
                )}
              </h2>
              <p className="text-slate-600 text-base leading-relaxed mb-6">
                {t(
                  'PT Abhipraya Nawasena Sejahtera adalah distributor resmi peralatan kesehatan, diagnostik, dan laboratorium terkemuka yang telah melayani berbagai institusi ilmiah di Indonesia selama lebih dari satu dekade. Kami berkomitmen menghadirkan produk autentik dari prinsipal global terpercaya dengan dukungan teknis profesional.',
                  'PT Abhipraya Nawasena Sejahtera is an official distributor of leading medical, diagnostic, and laboratory equipment that has served various scientific institutions in Indonesia for over a decade. We are committed to delivering authentic products from trusted global principals with professional technical support.'
                )}
              </p>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                {[
                  { id: 'Produk Autentik & Tersertifikasi', en: 'Authentic & Certified Products' },
                  { id: 'Dukungan Teknis Purna Jual', en: 'After-Sales Technical Support' },
                  { id: 'Pengiriman Cepat & Terpercaya', en: 'Fast & Reliable Delivery' },
                  { id: 'Penawaran Harga Kompetitif', en: 'Competitive Pricing' },
                ].map((item, i) => (
                  <div key={i} className="flex items-center gap-3">
                    <div className="w-6 h-6 rounded-full bg-teal-50 border border-teal-200 flex items-center justify-center flex-shrink-0">
                      <IconCheck className="w-3.5 h-3.5 text-teal-700" />
                    </div>
                    <span className="text-slate-700 text-sm font-medium">{locale === 'id' ? item.id : item.en}</span>
                  </div>
                ))}
              </div>
              <button
                onClick={() => setPage('about')}
                className="inline-flex items-center gap-2 text-teal-700 hover:text-teal-800 font-semibold text-sm hover:bg-teal-50 px-4 py-2 rounded-lg transition-colors"
              >
                {t('Pelajari Profil Kami', 'Learn About Us')} <IconArrowRight />
              </button>
            </div>
            <div className="relative">
              <div className="relative rounded-xl overflow-hidden aspect-[4/3] bg-slate-100">
                <img
                  src="https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=800&h=600&fit=crop&auto=format"
                  alt="ANS Laboratory"
                  className="w-full h-full object-cover"
                  loading="lazy"
                />
              </div>
              <div className="absolute -bottom-5 -left-5 bg-teal-700 text-white rounded-xl p-5 shadow-xl max-w-[200px]">
                <div className="text-3xl font-bold mb-1">15+</div>
                <div className="text-teal-200 text-xs leading-snug">{t('Tahun Melayani Industri Ilmiah Indonesia', 'Years Serving Indonesian Scientific Industry')}</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Featured Products */}
      <section className="py-16 lg:py-20 bg-slate-50 section-pattern">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
              <div className="inline-block text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1 rounded-md uppercase tracking-wide mb-3">
                {t('Produk Unggulan', 'Featured Products')}
              </div>
              <h2 className="text-2xl lg:text-3xl font-bold text-slate-900" style={{ letterSpacing: '-0.02em' }}>
                {t('Portofolio Produk Pilihan', 'Selected Product Portfolio')}
              </h2>
              <p className="text-slate-600 text-sm mt-2 max-w-lg">
                {t('Produk terverifikasi dari prinsipal global Merck, Neogen, dan Era Biology untuk kebutuhan laboratorium profesional.', 'Verified products from global principals Merck, Neogen, and Era Biology for professional laboratory needs.')}
              </p>
            </div>
            <button
              onClick={() => setPage('catalog')}
              className="inline-flex items-center gap-2 border border-teal-700 text-teal-700 hover:bg-teal-700 hover:text-white font-semibold px-5 py-2.5 rounded-lg transition-all text-sm whitespace-nowrap self-start md:self-auto"
            >
              {t('Lihat Semua Produk', 'View All Products')} <IconArrowRight />
            </button>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {featuredProducts.map(product => {
              const cat = CATEGORIES.find(c => c.id === product.category_id)
              const brand = BRANDS.find(b => b.id === product.brand_id)
              return (
                <div
                  key={product.id}
                  className="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md hover:border-teal-300 transition-all flex flex-col h-full group cursor-pointer"
                  onClick={() => { setProductId(product.id); setPage('product-detail') }}
                >
                  <div className="aspect-square bg-slate-50 overflow-hidden">
                    <img
                      src={product.image}
                      alt={locale === 'id' ? product.name_id : product.name_en}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                      loading="lazy"
                    />
                  </div>
                  <div className="p-4 flex flex-col flex-1">
                    <div className="flex flex-wrap gap-1.5 mb-2.5">
                      <span className="text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200 px-2 py-0.5 rounded-md">
                        {locale === 'id' ? cat?.name_id : cat?.name_en}
                      </span>
                      <span className="text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 px-2 py-0.5 rounded-md">
                        {brand?.name}
                      </span>
                    </div>
                    <h3 className="text-sm font-semibold text-slate-900 line-clamp-2 mb-1.5 group-hover:text-teal-700 transition-colors">
                      {locale === 'id' ? product.name_id : product.name_en}
                    </h3>
                    <p className="text-xs text-slate-500 line-clamp-2 flex-1">
                      {locale === 'id' ? product.summary_id : product.summary_en}
                    </p>
                    <div className="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                      <span className="text-xs font-semibold text-teal-700 group-hover:text-teal-800 transition-colors flex items-center gap-1">
                        {locale === 'id' ? 'Lihat Detail' : 'View Details'} <IconChevronRight className="w-3.5 h-3.5" />
                      </span>
                    </div>
                  </div>
                </div>
              )
            })}
          </div>
        </div>
      </section>

      {/* Principals Showcase */}
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-10">
            <div className="inline-block text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1 rounded-md uppercase tracking-wide mb-3">
              {t('Prinsipal Resmi', 'Official Principals')}
            </div>
            <h2 className="text-2xl lg:text-3xl font-bold text-slate-900 mb-3" style={{ letterSpacing: '-0.02em' }}>
              {t('Mitra Prinsipal Global Terpercaya', 'Trusted Global Principal Partners')}
            </h2>
            <p className="text-slate-600 text-sm max-w-lg mx-auto">
              {t(
                'Kami adalah distributor resmi terdaftar dari manufaktur terkemuka dunia di bidang ilmu hayati, diagnostik, dan keamanan pangan.',
                'We are official registered distributors from world-leading manufacturers in life sciences, diagnostics, and food safety.'
              )}
            </p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {BRANDS.map(brand => (
              <div key={brand.id} className="bg-white border border-slate-200 rounded-xl p-7 hover:border-teal-300 hover:shadow-md transition-all group">
                <div className="h-14 flex items-center mb-5">
                  <div className="w-full h-12 bg-slate-100 rounded-lg flex items-center justify-center text-xl font-bold text-teal-700">
                    {brand.name}
                  </div>
                </div>
                <p className="text-slate-600 text-sm leading-relaxed">
                  {locale === 'id' ? brand.description_id : brand.description_en}
                </p>
              </div>
            ))}
          </div>
          <div className="text-center mt-8">
            <button
              onClick={() => setPage('partners')}
              className="inline-flex items-center gap-2 text-teal-700 hover:text-teal-800 font-semibold text-sm hover:bg-teal-50 px-4 py-2 rounded-lg transition-colors"
            >
              {t('Lihat Semua Mitra & Klien', 'View All Partners & Clients')} <IconArrowRight />
            </button>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 lg:py-20 teal-gradient">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-2xl lg:text-3xl font-bold text-white mb-4" style={{ letterSpacing: '-0.02em' }}>
            {t('Siap Mendiskusikan Kebutuhan Laboratorium Anda?', 'Ready to Discuss Your Laboratory Needs?')}
          </h2>
          <p className="text-teal-100 text-base mb-8 max-w-xl mx-auto leading-relaxed">
            {t(
              'Tim sales dan teknis ANS siap membantu Anda menemukan solusi peralatan yang tepat. Ajukan permintaan penawaran harga sekarang.',
              'ANS sales and technical team is ready to help you find the right equipment solutions. Submit a price quotation request now.'
            )}
          </p>
          <div className="flex flex-wrap gap-3 justify-center">
            <button
              onClick={() => setPage('contact')}
              className="inline-flex items-center gap-2 bg-white text-teal-700 hover:bg-teal-50 font-bold px-7 py-3.5 rounded-lg shadow-lg transition-all active:scale-[0.98]"
            >
              {t('Minta Penawaran Harga', 'Request a Quotation')} <IconArrowRight />
            </button>
            <a
              href="https://wa.me/6282261461400"
              className="inline-flex items-center gap-2 bg-teal-800 hover:bg-teal-900 text-white font-semibold px-7 py-3.5 rounded-lg transition-all"
            >
              <IconWhatsApp />
              {t('Chat via WhatsApp', 'Chat via WhatsApp')}
            </a>
          </div>
        </div>
      </section>
    </div>
  )
}

// ── ABOUT PAGE ─────────────────────────────────────────────────────────────────

function AboutPage({ locale, setPage }: { locale: Locale; setPage: (p: Page) => void }) {
  const t = (id: string, en: string) => locale === 'id' ? id : en

  return (
    <div className="pt-16">
      {/* Page Header */}
      <div className="bg-slate-900 py-14 lg:py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="flex items-center gap-2 text-xs text-slate-400 mb-5">
            <button onClick={() => setPage('home')} className="hover:text-teal-400 transition-colors">{t('Beranda', 'Home')}</button>
            <IconChevronRight className="w-3 h-3" />
            <span className="text-slate-300">{t('Tentang Kami', 'About Us')}</span>
          </nav>
          <h1 className="text-3xl lg:text-4xl font-bold text-white mb-4" style={{ letterSpacing: '-0.02em' }}>
            {t('Tentang PT Abhipraya Nawasena Sejahtera', 'About PT Abhipraya Nawasena Sejahtera')}
          </h1>
          <p className="text-slate-400 text-base max-w-2xl leading-relaxed">
            {t(
              'Mengenal lebih dalam perjalanan, komitmen, visi, dan nilai-nilai inti yang mendorong ANS menjadi mitra ilmiah terpercaya di Indonesia.',
              'Learn more about the journey, commitment, vision, and core values that drive ANS to become a trusted scientific partner in Indonesia.'
            )}
          </p>
        </div>
      </div>

      {/* Company Profile Narrative */}
      <section className="py-16 lg:py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-14 items-start">
            <div>
              <div className="inline-block text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1 rounded-md uppercase tracking-wide mb-5">
                {t('Profil Perusahaan', 'Company Profile')}
              </div>
              <h2 className="text-2xl lg:text-3xl font-bold text-slate-900 mb-5 leading-tight" style={{ letterSpacing: '-0.02em' }}>
                {t('Komitmen Kami untuk Sains & Presisi', 'Our Commitment to Science & Precision')}
              </h2>
              <div className="space-y-4 text-slate-600 text-base leading-relaxed">
                <p>
                  {t(
                    'PT Abhipraya Nawasena Sejahtera (ANS) didirikan dengan komitmen untuk mendekatkan inovasi ilmiah global kepada komunitas laboratorium, rumah sakit, institusi pendidikan, dan industri farmasi di seluruh Indonesia. Dalam lebih dari satu dekade perjalanan kami, kepercayaan pelanggan adalah aset terbesar yang selalu kami jaga.',
                    'PT Abhipraya Nawasena Sejahtera (ANS) was founded with a commitment to bringing global scientific innovation closer to the laboratory community, hospitals, educational institutions, and pharmaceutical industries throughout Indonesia. In over a decade of our journey, customer trust is the greatest asset we always preserve.'
                  )}
                </p>
                <p>
                  {t(
                    'Sebagai distributor resmi dari prinsipal-prinsipal terkemuka dunia — Merck (Jerman), Neogen (Amerika Serikat), dan Era Biology (Tiongkok) — ANS menjamin keaslian, kualitas, dan ketersediaan produk laboratorium yang Anda butuhkan.',
                    'As an official distributor of world-leading principals — Merck (Germany), Neogen (United States), and Era Biology (China) — ANS guarantees the authenticity, quality, and availability of laboratory products you need.'
                  )}
                </p>
                <p>
                  {t(
                    'Tim kami terdiri dari tenaga profesional berpengalaman di bidang sains hayati dan teknik laboratorium yang siap memberikan konsultasi teknis mendalam, layanan purna jual responsif, dan solusi pengadaan yang efisien bagi setiap institusi ilmiah di Indonesia.',
                    'Our team consists of experienced professionals in life sciences and laboratory engineering who are ready to provide in-depth technical consultation, responsive after-sales service, and efficient procurement solutions for every scientific institution in Indonesia.'
                  )}
                </p>
              </div>
            </div>
            <div>
              <div className="rounded-xl overflow-hidden aspect-[4/3] bg-slate-100">
                <img
                  src="https://images.unsplash.com/photo-1576086213369-b0e0dfc87f5e?w=800&h=600&fit=crop&auto=format"
                  alt="ANS Team"
                  className="w-full h-full object-cover"
                  loading="lazy"
                />
              </div>
              <div className="grid grid-cols-2 gap-4 mt-4">
                <div className="bg-teal-50 border border-teal-200 rounded-xl p-5">
                  <div className="text-3xl font-bold text-teal-700 mb-1">15+</div>
                  <div className="text-slate-600 text-sm">{t('Tahun Pengalaman Industri', 'Years Industry Experience')}</div>
                </div>
                <div className="bg-amber-50 border border-amber-200 rounded-xl p-5">
                  <div className="text-3xl font-bold text-amber-700 mb-1">200+</div>
                  <div className="text-slate-600 text-sm">{t('Produk Terverifikasi', 'Verified Products')}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Vision & Mission */}
      <section className="py-16 lg:py-20 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <div className="inline-block text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1 rounded-md uppercase tracking-wide mb-3">
              {t('Visi & Misi', 'Vision & Mission')}
            </div>
            <h2 className="text-2xl lg:text-3xl font-bold text-slate-900" style={{ letterSpacing: '-0.02em' }}>
              {t('Arah & Tujuan ANS', 'ANS Direction & Purpose')}
            </h2>
          </div>
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {/* Vision */}
            <div className="bg-teal-700 rounded-2xl p-8 lg:p-10">
              <div className="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mb-6">
                <IconStar className="w-6 h-6 text-white" />
              </div>
              <h3 className="text-lg font-bold text-white mb-4 uppercase tracking-wide">{t('Visi', 'Vision')}</h3>
              <p className="text-teal-100 text-lg leading-relaxed font-light italic">
                {t(
                  '"Menjadi mitra distribusi ilmiah paling terpercaya di Indonesia yang menghubungkan inovasi global dengan kebutuhan laboratorium lokal secara presisi, bertanggung jawab, dan berkelanjutan."',
                  '"To become the most trusted scientific distribution partner in Indonesia that connects global innovation with local laboratory needs precisely, responsibly, and sustainably."'
                )}
              </p>
            </div>
            {/* Mission */}
            <div className="bg-white border border-slate-200 rounded-2xl p-8 lg:p-10">
              <div className="w-12 h-12 rounded-full bg-teal-50 border border-teal-200 flex items-center justify-center mb-6">
                <IconCheck className="w-6 h-6 text-teal-700" />
              </div>
              <h3 className="text-lg font-bold text-slate-900 mb-4 uppercase tracking-wide">{t('Misi', 'Mission')}</h3>
              <ul className="space-y-3">
                {[
                  { id: 'Mendistribusikan produk laboratorium autentik dari prinsipal global terkemuka dengan standar kualitas yang tidak kompromi.', en: 'Distributing authentic laboratory products from leading global principals with uncompromising quality standards.' },
                  { id: 'Memberikan dukungan teknis dan konsultasi mendalam kepada setiap pelanggan untuk optimasi penggunaan produk.', en: 'Providing in-depth technical support and consultation to every customer for product usage optimization.' },
                  { id: 'Membangun kemitraan jangka panjang berbasis kepercayaan, integritas, dan nilai bersama dengan pelanggan dan prinsipal.', en: 'Building long-term partnerships based on trust, integrity, and shared values with customers and principals.' },
                  { id: 'Berkontribusi pada kemajuan sains dan kesehatan di Indonesia melalui akses produk ilmiah berkualitas internasional.', en: 'Contributing to science and health advancement in Indonesia through access to internationally quality scientific products.' },
                ].map((item, i) => (
                  <li key={i} className="flex gap-3">
                    <div className="w-5 h-5 rounded-full bg-teal-50 border border-teal-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                      <div className="w-2 h-2 rounded-full bg-teal-700" />
                    </div>
                    <span className="text-slate-600 text-sm leading-relaxed">{locale === 'id' ? item.id : item.en}</span>
                  </li>
                ))}
              </ul>
            </div>
          </div>
        </div>
      </section>

      {/* Core Values */}
      <section className="py-16 lg:py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <div className="inline-block text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1 rounded-md uppercase tracking-wide mb-3">
              {t('Nilai Inti', 'Core Values')}
            </div>
            <h2 className="text-2xl lg:text-3xl font-bold text-slate-900 mb-3" style={{ letterSpacing: '-0.02em' }}>
              {t('6 Nilai Inti ANS — Spiral Nilai', '6 ANS Core Values — Value Spiral')}
            </h2>
            <p className="text-slate-600 text-sm max-w-xl mx-auto">
              {t('Enam nilai fundamental yang memandu setiap keputusan, interaksi, dan inovasi di ANS.', 'Six fundamental values that guide every decision, interaction, and innovation at ANS.')}
            </p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {CORE_VALUES.map((v, i) => (
              <div key={i} className="bg-white border border-slate-200 rounded-xl p-7 hover:border-teal-300 hover:shadow-md transition-all group">
                <div className="w-12 h-12 rounded-xl bg-teal-50 border border-teal-200 flex items-center justify-center mb-5 text-teal-700 text-xl group-hover:bg-teal-700 group-hover:text-white transition-all">
                  {v.icon}
                </div>
                <h3 className="text-base font-bold text-slate-900 mb-2.5">
                  {locale === 'id' ? v.title_id : v.title_en}
                </h3>
                <p className="text-slate-600 text-sm leading-relaxed">
                  {locale === 'id' ? v.desc_id : v.desc_en}
                </p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-14 bg-slate-50 border-t border-slate-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-2xl font-bold text-slate-900 mb-4" style={{ letterSpacing: '-0.02em' }}>
            {t('Lihat Portofolio Produk Kami', 'View Our Product Portfolio')}
          </h2>
          <p className="text-slate-600 text-sm mb-7 max-w-md mx-auto">
            {t('Temukan lebih dari 200 produk laboratorium terverifikasi dari Merck, Neogen, dan Era Biology.', 'Discover over 200 verified laboratory products from Merck, Neogen, and Era Biology.')}
          </p>
          <button
            onClick={() => setPage('catalog')}
            className="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold px-7 py-3.5 rounded-lg shadow-sm transition-colors"
          >
            {t('Jelajahi Katalog Produk', 'Explore Product Catalog')} <IconArrowRight />
          </button>
        </div>
      </section>
    </div>
  )
}

// ── CATALOG PAGE ───────────────────────────────────────────────────────────────

function CatalogPage({ locale, setPage, setProductId }: { locale: Locale; setPage: (p: Page) => void; setProductId: (id: number) => void }) {
  const t = (id: string, en: string) => locale === 'id' ? id : en
  const [filterCategory, setFilterCategory] = useState<number | null>(null)
  const [filterBrand, setFilterBrand] = useState<number | null>(null)
  const [drawerOpen, setDrawerOpen] = useState(false)
  const [page, setCurrentPage] = useState(1)
  const perPage = 12

  const filtered = PRODUCTS.filter(p => {
    if (filterCategory && p.category_id !== filterCategory) return false
    if (filterBrand && p.brand_id !== filterBrand) return false
    return true
  })

  const totalPages = Math.ceil(filtered.length / perPage)
  const paginated = filtered.slice((page - 1) * perPage, page * perPage)

  const activeFilterCount = (filterCategory ? 1 : 0) + (filterBrand ? 1 : 0)

  const resetFilters = () => { setFilterCategory(null); setFilterBrand(null); setCurrentPage(1) }

  function FilterContent({ onApply }: { onApply?: () => void }) {
    return (
      <div className="space-y-6">
        {/* Categories */}
        <div>
          <h4 className="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">{t('Kategori Produk', 'Product Category')}</h4>
          <ul className="space-y-1">
            {CATEGORIES.map(cat => (
              <li key={cat.id}>
                <button
                  onClick={() => { setFilterCategory(filterCategory === cat.id ? null : cat.id); setCurrentPage(1); onApply?.() }}
                  className={`w-full text-left px-3 py-2.5 rounded-lg text-sm transition-colors flex items-center gap-2.5 ${
                    filterCategory === cat.id
                      ? 'bg-teal-50 text-teal-800 font-semibold border border-teal-200'
                      : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'
                  }`}
                >
                  {filterCategory === cat.id && <IconCheck className="w-3.5 h-3.5 text-teal-700 flex-shrink-0" />}
                  <span className={filterCategory === cat.id ? '' : 'ml-[18px]'}>
                    {locale === 'id' ? cat.name_id : cat.name_en}
                  </span>
                </button>
              </li>
            ))}
          </ul>
        </div>

        {/* Brands */}
        <div>
          <h4 className="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">{t('Brand / Prinsipal', 'Brand / Principal')}</h4>
          <ul className="space-y-1">
            {BRANDS.map(brand => (
              <li key={brand.id}>
                <button
                  onClick={() => { setFilterBrand(filterBrand === brand.id ? null : brand.id); setCurrentPage(1); onApply?.() }}
                  className={`w-full text-left px-3 py-2.5 rounded-lg text-sm transition-colors flex items-center gap-2.5 ${
                    filterBrand === brand.id
                      ? 'bg-teal-50 text-teal-800 font-semibold border border-teal-200'
                      : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'
                  }`}
                >
                  {filterBrand === brand.id && <IconCheck className="w-3.5 h-3.5 text-teal-700 flex-shrink-0" />}
                  <span className={filterBrand === brand.id ? '' : 'ml-[18px]'}>{brand.name}</span>
                </button>
              </li>
            ))}
          </ul>
        </div>

        {/* Reset */}
        {activeFilterCount > 0 && (
          <button
            onClick={() => { resetFilters(); onApply?.() }}
            className="w-full text-center text-sm text-red-600 hover:text-red-700 font-medium py-2 rounded-lg hover:bg-red-50 transition-colors"
          >
            {t('Reset Semua Filter', 'Reset All Filters')}
          </button>
        )}
      </div>
    )
  }

  return (
    <div className="pt-16 min-h-screen">
      {/* Page Header */}
      <div className="bg-slate-900 py-12 lg:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="flex items-center gap-2 text-xs text-slate-400 mb-4">
            <button onClick={() => setPage('home')} className="hover:text-teal-400 transition-colors">{t('Beranda', 'Home')}</button>
            <IconChevronRight className="w-3 h-3" />
            <span className="text-slate-300">{t('Katalog Produk', 'Product Catalog')}</span>
          </nav>
          <h1 className="text-2xl lg:text-3xl font-bold text-white mb-2" style={{ letterSpacing: '-0.02em' }}>
            {t('Katalog Produk', 'Product Catalog')}
          </h1>
          <p className="text-slate-400 text-sm max-w-xl">
            {t('Temukan produk laboratorium, diagnostik, dan ilmu hayati yang Anda butuhkan dari mitra prinsipal global kami.', 'Find the laboratory, diagnostic, and life science products you need from our global principal partners.')}
          </p>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Mobile Filter Trigger */}
        <div className="lg:hidden flex items-center justify-between mb-5 gap-3">
          <button
            onClick={() => setDrawerOpen(true)}
            className="inline-flex items-center gap-2 border border-slate-300 bg-white hover:border-teal-300 text-slate-700 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors"
          >
            <IconFunnel />
            {t('Filter Produk', 'Filter Products')}
            {activeFilterCount > 0 && (
              <span className="ml-1 bg-teal-700 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">
                {activeFilterCount}
              </span>
            )}
          </button>
          <span className="text-slate-500 text-sm">
            {filtered.length} {t('produk', 'products')}
          </span>
        </div>

        {/* Active Filter Chips */}
        {activeFilterCount > 0 && (
          <div className="flex flex-wrap gap-2 mb-5">
            {filterCategory && (() => {
              const cat = CATEGORIES.find(c => c.id === filterCategory)
              return (
                <button
                  onClick={() => { setFilterCategory(null); setCurrentPage(1) }}
                  className="inline-flex items-center gap-1.5 bg-teal-700 text-white text-xs font-medium pl-3 pr-2 py-1.5 rounded-full hover:bg-teal-800 transition-colors"
                >
                  {t('Kategori:', 'Category:')} {locale === 'id' ? cat?.name_id : cat?.name_en}
                  <IconX className="w-3 h-3" />
                </button>
              )
            })()}
            {filterBrand && (() => {
              const brand = BRANDS.find(b => b.id === filterBrand)
              return (
                <button
                  onClick={() => { setFilterBrand(null); setCurrentPage(1) }}
                  className="inline-flex items-center gap-1.5 bg-teal-700 text-white text-xs font-medium pl-3 pr-2 py-1.5 rounded-full hover:bg-teal-800 transition-colors"
                >
                  Brand: {brand?.name}
                  <IconX className="w-3 h-3" />
                </button>
              )
            })()}
            <button
              onClick={resetFilters}
              className="text-xs text-red-600 hover:text-red-700 font-medium px-2 py-1.5 hover:bg-red-50 rounded-full transition-colors"
            >
              {t('Hapus Semua', 'Clear All')}
            </button>
          </div>
        )}

        <div className="flex gap-7">
          {/* Sidebar Filter Desktop */}
          <aside className="hidden lg:block w-64 flex-shrink-0">
            <div className="bg-white border border-slate-200 rounded-xl p-5 sticky top-24">
              <h3 className="text-sm font-bold text-slate-900 mb-5">{t('Filter Produk', 'Filter Products')}</h3>
              <FilterContent />
            </div>
          </aside>

          {/* Main content */}
          <div className="flex-1 min-w-0">
            {/* Counter */}
            <div className="flex items-center justify-between mb-5">
              <p className="text-sm text-slate-500">
                {t(
                  `Menampilkan ${paginated.length} dari ${filtered.length} produk`,
                  `Showing ${paginated.length} of ${filtered.length} products`
                )}
              </p>
            </div>

            {/* Grid */}
            {paginated.length === 0 ? (
              <div className="bg-slate-50 border border-slate-200 rounded-xl p-12 text-center">
                <div className="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <IconSearch className="w-8 h-8 text-slate-400" />
                </div>
                <h3 className="text-base font-semibold text-slate-900 mb-2">{t('Tidak Ada Produk yang Sesuai', 'No Products Found')}</h3>
                <p className="text-slate-500 text-sm mb-6 max-w-sm mx-auto">
                  {t('Kami tidak menemukan produk yang cocok dengan kombinasi filter yang Anda pilih. Coba sesuaikan atau hapus filter Anda.', 'We could not find products matching your selected filter combination. Try adjusting or clearing your filters.')}
                </p>
                <button onClick={resetFilters} className="inline-flex items-center gap-2 bg-teal-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-teal-800 transition-colors">
                  {t('Reset Semua Filter', 'Reset All Filters')}
                </button>
              </div>
            ) : (
              <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                {paginated.map(product => {
                  const cat = CATEGORIES.find(c => c.id === product.category_id)
                  const brand = BRANDS.find(b => b.id === product.brand_id)
                  return (
                    <div
                      key={product.id}
                      className="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md hover:border-teal-300 transition-all flex flex-col h-full group cursor-pointer"
                      onClick={() => { setProductId(product.id); setPage('product-detail') }}
                    >
                      <div className="aspect-square bg-slate-50 overflow-hidden">
                        <img
                          src={product.image}
                          alt={locale === 'id' ? product.name_id : product.name_en}
                          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                          loading="lazy"
                        />
                      </div>
                      <div className="p-4 flex flex-col flex-1">
                        <div className="flex flex-wrap gap-1.5 mb-2.5">
                          <span className="text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200 px-2 py-0.5 rounded-md">
                            {locale === 'id' ? cat?.name_id : cat?.name_en}
                          </span>
                          <span className="text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 px-2 py-0.5 rounded-md">
                            {brand?.name}
                          </span>
                        </div>
                        <h3 className="text-sm font-semibold text-slate-900 line-clamp-2 mb-1.5 group-hover:text-teal-700 transition-colors leading-snug">
                          {locale === 'id' ? product.name_id : product.name_en}
                        </h3>
                        <p className="text-xs text-slate-500 line-clamp-2 flex-1 leading-relaxed">
                          {locale === 'id' ? product.summary_id : product.summary_en}
                        </p>
                        <div className="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                          <span className="text-xs font-semibold text-teal-700 group-hover:text-teal-800 flex items-center gap-1">
                            {t('Lihat Detail', 'View Details')} <IconChevronRight className="w-3.5 h-3.5" />
                          </span>
                          {product.hasBrochure && (
                            <span className="text-xs text-slate-400 flex items-center gap-1">
                              <IconDocument className="w-3.5 h-3.5" /> PDF
                            </span>
                          )}
                        </div>
                      </div>
                    </div>
                  )
                })}
              </div>
            )}

            {/* Pagination */}
            {totalPages > 1 && (
              <nav className="mt-8 flex items-center justify-center gap-1" aria-label={t('Navigasi Halaman', 'Page Navigation')}>
                <button
                  onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
                  disabled={page === 1}
                  className="flex items-center gap-1 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                  aria-label={t('Halaman Sebelumnya', 'Previous Page')}
                >
                  <IconChevronLeft /> {t('Sebelumnya', 'Previous')}
                </button>
                {Array.from({ length: totalPages }, (_, i) => i + 1).map(n => (
                  <button
                    key={n}
                    onClick={() => setCurrentPage(n)}
                    className={`w-9 h-9 rounded-lg text-sm font-medium transition-colors ${
                      n === page ? 'bg-teal-700 text-white' : 'text-slate-600 hover:bg-slate-100'
                    }`}
                  >
                    {n}
                  </button>
                ))}
                <button
                  onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))}
                  disabled={page === totalPages}
                  className="flex items-center gap-1 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                  aria-label={t('Halaman Berikutnya', 'Next Page')}
                >
                  {t('Berikutnya', 'Next')} <IconChevronRight />
                </button>
              </nav>
            )}
          </div>
        </div>
      </div>

      {/* Mobile Filter Drawer */}
      {drawerOpen && (
        <div className="lg:hidden fixed inset-0 z-50 flex flex-col justify-end">
          <div className="absolute inset-0 bg-black/50" onClick={() => setDrawerOpen(false)} />
          <div className="relative bg-white rounded-t-2xl max-h-[85vh] flex flex-col">
            <div className="flex items-center justify-between p-5 border-b border-slate-200">
              <h3 className="text-base font-bold text-slate-900">{t('Filter Produk', 'Filter Products')}</h3>
              <button onClick={() => setDrawerOpen(false)} className="text-slate-500 hover:text-slate-700 focus-ring rounded-md">
                <IconX />
              </button>
            </div>
            <div className="flex-1 overflow-y-auto p-5">
              <FilterContent />
            </div>
            <div className="p-4 border-t border-slate-200 flex gap-3">
              <button
                onClick={() => { resetFilters(); setDrawerOpen(false) }}
                className="flex-1 border border-slate-300 text-slate-700 text-sm font-medium py-3 rounded-lg hover:bg-slate-50 transition-colors"
              >
                {t('Reset', 'Reset')}
              </button>
              <button
                onClick={() => setDrawerOpen(false)}
                className="flex-2 flex-1 bg-teal-700 text-white text-sm font-semibold py-3 rounded-lg hover:bg-teal-800 transition-colors"
              >
                {t('Terapkan Filter', 'Apply Filter')}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

// ── PRODUCT DETAIL PAGE ────────────────────────────────────────────────────────

function ProductDetailPage({ productId, locale, setPage }: { productId: number; locale: Locale; setPage: (p: Page) => void }) {
  const t = (id: string, en: string) => locale === 'id' ? id : en
  const product = PRODUCTS.find(p => p.id === productId) ?? PRODUCTS[0]
  const cat = CATEGORIES.find(c => c.id === product.category_id)
  const brand = BRANDS.find(b => b.id === product.brand_id)
  const [activeImg, setActiveImg] = useState(0)

  useEffect(() => { setActiveImg(0) }, [productId])

  const name = locale === 'id' ? product.name_id : product.name_en
  const summary = locale === 'id' ? product.summary_id : product.summary_en
  const description = locale === 'id' ? product.description_id : product.description_en
  const catName = locale === 'id' ? cat?.name_id : cat?.name_en

  return (
    <div className="pt-16 min-h-screen bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Breadcrumb */}
        <nav className="flex items-center gap-2 text-xs text-slate-500 mb-7 flex-wrap">
          <button onClick={() => setPage('home')} className="hover:text-teal-700 transition-colors">{t('Beranda', 'Home')}</button>
          <IconChevronRight className="w-3 h-3" />
          <button onClick={() => setPage('catalog')} className="hover:text-teal-700 transition-colors">{t('Produk', 'Products')}</button>
          <IconChevronRight className="w-3 h-3" />
          <button onClick={() => setPage('catalog')} className="hover:text-teal-700 transition-colors">{catName}</button>
          <IconChevronRight className="w-3 h-3" />
          <span className="text-slate-900 font-medium line-clamp-1">{name}</span>
        </nav>

        {/* Top Product Overview */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
          {/* Left: Gallery */}
          <div>
            <div className="aspect-square md:aspect-[4/3] bg-white border border-slate-200 rounded-xl overflow-hidden mb-3 flex items-center justify-center">
              <img
                src={product.images[activeImg]}
                alt={name}
                className="w-full h-full object-cover"
                loading="eager"
              />
            </div>
            {product.images.length > 1 && (
              <div className="flex gap-2.5">
                {product.images.map((img, i) => (
                  <button
                    key={i}
                    onClick={() => setActiveImg(i)}
                    className={`w-16 h-16 rounded-lg border-2 overflow-hidden flex-shrink-0 transition-all focus-ring ${
                      i === activeImg ? 'border-teal-700 ring-2 ring-teal-100' : 'border-slate-200 hover:border-teal-300'
                    }`}
                    aria-label={`${t('Pilih Foto', 'Select Photo')} ${i + 1}`}
                  >
                    <img src={img} alt="" className="w-full h-full object-cover" loading="lazy" />
                  </button>
                ))}
                <div className="flex items-center text-xs text-slate-400 px-2">
                  {t(`Foto ${activeImg + 1} dari ${product.images.length}`, `Photo ${activeImg + 1} of ${product.images.length}`)}
                </div>
              </div>
            )}
          </div>

          {/* Right: Identity & Actions */}
          <div>
            <div className="flex flex-wrap gap-2 mb-4">
              <span className="text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200 px-2.5 py-1 rounded-md">{catName}</span>
              <span className="text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-1 rounded-md">{brand?.name}</span>
            </div>
            <h1 className="text-2xl lg:text-3xl font-bold text-slate-900 mb-4 leading-tight" style={{ letterSpacing: '-0.015em' }}>
              {name}
            </h1>
            <p className="text-slate-600 text-base leading-relaxed mb-7 border-l-2 border-teal-700 pl-4">
              {summary}
            </p>

            {/* Action Box */}
            <div className="bg-slate-50 border border-slate-200 rounded-xl p-6 space-y-3">
              <button
                onClick={() => setPage('contact')}
                className="w-full flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold py-3.5 rounded-lg shadow-sm transition-colors active:scale-[0.98]"
              >
                {t('Minta Penawaran Harga', 'Request a Quotation')} <IconArrowRight />
              </button>
              {product.hasBrochure && (
                <button className="w-full flex items-center justify-center gap-2 border border-teal-700 text-teal-700 hover:bg-teal-50 font-semibold py-3 rounded-lg transition-colors text-sm">
                  <IconDocument /> {t('Unduh Brosur Produk (PDF)', 'Download Product Brochure (PDF)')}
                </button>
              )}
              <a
                href="https://wa.me/6282261461400"
                className="w-full flex items-center justify-center gap-2 border border-slate-200 text-slate-700 hover:bg-slate-100 font-medium py-2.5 rounded-lg transition-colors text-sm"
              >
                <IconWhatsApp className="w-4 h-4 text-green-600" />
                {t('Tanya via WhatsApp Resmi', 'Ask via Official WhatsApp')}
              </a>
            </div>

            {/* Contact info strip */}
            <div className="mt-5 flex flex-wrap gap-4 text-xs text-slate-500">
              <span className="flex items-center gap-1"><IconCheck className="w-3.5 h-3.5 text-teal-600" /> {t('Produk Autentik', 'Authentic Product')}</span>
              <span className="flex items-center gap-1"><IconCheck className="w-3.5 h-3.5 text-teal-600" /> {t('Dukungan Teknis', 'Technical Support')}</span>
              <span className="flex items-center gap-1"><IconCheck className="w-3.5 h-3.5 text-teal-600" /> {t('Bersertifikat', 'Certified')}</span>
            </div>
          </div>
        </div>

        {/* Technical Specifications */}
        {product.specifications.length > 0 && (
          <section className="mb-10">
            <h2 className="text-lg font-bold text-slate-900 mb-4">{t('Spesifikasi Teknis', 'Technical Specifications')}</h2>
            <div className="overflow-x-auto border border-slate-200 rounded-xl">
              <table className="min-w-full divide-y divide-slate-200">
                <tbody className="divide-y divide-slate-200">
                  {product.specifications.map((spec, i) => (
                    <tr key={i} className={i % 2 === 1 ? 'bg-slate-50' : 'bg-white'}>
                      <td className="w-1/3 px-5 py-3.5 text-sm font-semibold text-slate-700 bg-slate-50/50">
                        {locale === 'id' ? spec.key_id : spec.key_en}
                      </td>
                      <td className="px-5 py-3.5 text-sm text-slate-800 font-mono">
                        {locale === 'id' ? spec.value_id : spec.value_en}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </section>
        )}

        {/* Full Description */}
        {description && (
          <section className="mb-10">
            <h2 className="text-lg font-bold text-slate-900 mb-4">{t('Deskripsi Lengkap Produk', 'Full Product Description')}</h2>
            <div className="bg-slate-50 border border-slate-200 rounded-xl p-6">
              <p className="text-slate-600 text-sm leading-relaxed">{description}</p>
            </div>
          </section>
        )}

        {/* Back Navigation */}
        <div className="pt-6 border-t border-slate-200 flex flex-wrap gap-3">
          <button
            onClick={() => setPage('catalog')}
            className="inline-flex items-center gap-2 text-teal-700 hover:text-teal-800 font-semibold text-sm hover:bg-teal-50 px-4 py-2.5 rounded-lg border border-teal-200 hover:border-teal-300 transition-all"
          >
            <IconChevronLeft /> {t('Kembali ke Katalog Produk', 'Back to Product Catalog')}
          </button>
          <button
            onClick={() => setPage('contact')}
            className="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-sm px-5 py-2.5 rounded-lg shadow-sm transition-colors"
          >
            {t('Minta Penawaran', 'Request Quote')} <IconArrowRight />
          </button>
        </div>
      </div>

      {/* Mobile Sticky Bottom Bar */}
      <div className="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-200 p-3 shadow-lg flex items-center gap-2">
        <button
          onClick={() => setPage('contact')}
          className="flex-1 bg-teal-700 hover:bg-teal-800 text-white text-sm font-bold py-3.5 rounded-lg transition-colors text-center"
        >
          {t('Minta Penawaran', 'Request Quote')}
        </button>
        <a
          href="https://wa.me/6282261461400"
          className="flex-shrink-0 w-12 h-12 flex items-center justify-center border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors"
        >
          <IconWhatsApp className="w-5 h-5 text-green-600" />
        </a>
      </div>
    </div>
  )
}

// ── PARTNERS PAGE ──────────────────────────────────────────────────────────────

function PartnersPage({ locale, setPage }: { locale: Locale; setPage: (p: Page) => void }) {
  const t = (id: string, en: string) => locale === 'id' ? id : en

  return (
    <div className="pt-16 min-h-screen">
      {/* Page Header */}
      <div className="bg-slate-900 py-12 lg:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="flex items-center gap-2 text-xs text-slate-400 mb-4">
            <button onClick={() => setPage('home')} className="hover:text-teal-400 transition-colors">{t('Beranda', 'Home')}</button>
            <IconChevronRight className="w-3 h-3" />
            <span className="text-slate-300">{t('Mitra & Klien', 'Partners & Clients')}</span>
          </nav>
          <h1 className="text-2xl lg:text-3xl font-bold text-white mb-2" style={{ letterSpacing: '-0.02em' }}>
            {t('Mitra Prinsipal & Klien Korporat', 'Principal Partners & Corporate Clients')}
          </h1>
          <p className="text-slate-400 text-sm max-w-xl">
            {t('Rekam jejak kemitraan resmi dan kepercayaan institusi ilmiah terkemuka Indonesia.', 'Track record of official partnerships and trust from leading Indonesian scientific institutions.')}
          </p>
        </div>
      </div>

      {/* Principals */}
      <section className="py-16 lg:py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <div className="inline-block text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1 rounded-md uppercase tracking-wide mb-3">
              {t('Prinsipal Resmi', 'Official Principals')}
            </div>
            <h2 className="text-2xl lg:text-3xl font-bold text-slate-900 mb-3" style={{ letterSpacing: '-0.02em' }}>
              {t('Distributor Resmi Terdaftar', 'Registered Official Distributor')}
            </h2>
            <p className="text-slate-600 text-sm max-w-xl mx-auto">
              {t(
                'ANS adalah distributor resmi terdaftar dari tiga manufaktur terkemuka dunia di bidang ilmu hayati, keamanan pangan, dan diagnostik.',
                'ANS is a registered official distributor of three world-leading manufacturers in life sciences, food safety, and diagnostics.'
              )}
            </p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {BRANDS.map((brand, i) => (
              <div key={brand.id} className="bg-white border border-slate-200 rounded-2xl p-8 hover:border-teal-300 hover:shadow-lg transition-all">
                <div className="bg-slate-100 rounded-xl h-24 flex items-center justify-center mb-6">
                  <span className="text-2xl font-bold text-teal-700">{brand.name}</span>
                </div>
                {i === 0 && (
                  <span className="inline-block text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 px-2 py-0.5 rounded-full mb-3">
                    {t('Prinsipal Unggulan', 'Featured Principal')}
                  </span>
                )}
                <h3 className="text-lg font-bold text-slate-900 mb-3">{brand.name}</h3>
                <p className="text-slate-600 text-sm leading-relaxed">
                  {locale === 'id' ? brand.description_id : brand.description_en}
                </p>
                <div className="mt-5 pt-5 border-t border-slate-100 flex items-center gap-2">
                  <IconCheck className="w-4 h-4 text-teal-700" />
                  <span className="text-xs text-teal-700 font-semibold">{t('Distributor Resmi Terdaftar', 'Registered Official Distributor')}</span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Clients */}
      <section className="py-16 lg:py-20 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <div className="inline-block text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1 rounded-md uppercase tracking-wide mb-3">
              {t('Klien Korporat', 'Corporate Clients')}
            </div>
            <h2 className="text-2xl lg:text-3xl font-bold text-slate-900 mb-3" style={{ letterSpacing: '-0.02em' }}>
              {t('Dipercaya oleh Institusi Terkemuka', 'Trusted by Leading Institutions')}
            </h2>
            <p className="text-slate-600 text-sm max-w-xl mx-auto">
              {t(
                'Produk ANS telah dipercaya dan digunakan oleh rumah sakit, universitas, lembaga riset, dan industri farmasi terkemuka di Indonesia.',
                'ANS products have been trusted and used by leading hospitals, universities, research institutions, and pharmaceutical industries in Indonesia.'
              )}
            </p>
          </div>
          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            {CLIENTS.map((client, i) => (
              <div key={i} className="bg-white border border-slate-200 rounded-xl p-5 flex items-center justify-center text-center hover:border-teal-300 hover:shadow-sm transition-all min-h-[80px]">
                <span className="text-sm font-semibold text-slate-700">{client}</span>
              </div>
            ))}
          </div>
          <div className="mt-10 text-center bg-white border border-slate-200 rounded-2xl p-8">
            <h3 className="text-lg font-bold text-slate-900 mb-2">{t('Bergabunglah Bersama Ribuan Pelanggan Terpercaya', 'Join Thousands of Trusted Customers')}</h3>
            <p className="text-slate-600 text-sm mb-6">
              {t('Hubungi tim ANS hari ini untuk informasi lebih lanjut tentang program kemitraan dan penawaran harga terbaik.', 'Contact the ANS team today for more information about partnership programs and the best pricing offers.')}
            </p>
            <button
              onClick={() => setPage('contact')}
              className="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold px-6 py-3 rounded-lg shadow-sm transition-colors"
            >
              {t('Hubungi Kami', 'Contact Us')} <IconArrowRight />
            </button>
          </div>
        </div>
      </section>
    </div>
  )
}

// ── CONTACT PAGE ───────────────────────────────────────────────────────────────

function ContactPage({ locale, setPage, productId }: { locale: Locale; setPage: (p: Page) => void; productId: number | null }) {
  const t = (id: string, en: string) => locale === 'id' ? id : en
  const [contextProductId, setContextProductId] = useState<number | null>(productId)
  const contextProduct = contextProductId ? PRODUCTS.find(p => p.id === contextProductId) : null

  const [form, setForm] = useState({ name: '', email: '', phone: '', company: '', subject: '', message: '' })
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [loading, setLoading] = useState(false)
  const [success, setSuccess] = useState(false)

  const validate = () => {
    const e: Record<string, string> = {}
    if (!form.name.trim()) e.name = t('Nama lengkap wajib diisi.', 'Full name is required.')
    if (!form.email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) e.email = t('Alamat email tidak valid.', 'Invalid email address.')
    if (!form.subject.trim()) e.subject = t('Subjek permintaan wajib diisi.', 'Subject is required.')
    if (!form.message.trim() || form.message.trim().length < 20) e.message = t('Pesan minimal 20 karakter.', 'Message must be at least 20 characters.')
    setErrors(e)
    return Object.keys(e).length === 0
  }

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    if (!validate()) return
    setLoading(true)
    setTimeout(() => { setLoading(false); setSuccess(true) }, 1800)
  }

  return (
    <div className="pt-16 min-h-screen">
      {/* Page Header */}
      <div className="bg-slate-900 py-12 lg:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="flex items-center gap-2 text-xs text-slate-400 mb-4">
            <button onClick={() => setPage('home')} className="hover:text-teal-400 transition-colors">{t('Beranda', 'Home')}</button>
            <IconChevronRight className="w-3 h-3" />
            <span className="text-slate-300">{t('Kontak', 'Contact')}</span>
          </nav>
          <h1 className="text-2xl lg:text-3xl font-bold text-white mb-2" style={{ letterSpacing: '-0.02em' }}>
            {t('Hubungi Kami & Permintaan Penawaran', 'Contact Us & Request Quotation')}
          </h1>
          <p className="text-slate-400 text-sm max-w-xl">
            {t('Ajukan permintaan penawaran harga atau pertanyaan teknis kepada tim ANS. Kami akan merespons dalam 1×24 jam kerja.', 'Submit a price quotation request or technical inquiry to the ANS team. We will respond within 1×24 business hours.')}
          </p>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div className="grid grid-cols-1 lg:grid-cols-5 gap-10">
          {/* Left: Contact Info */}
          <div className="lg:col-span-2">
            <h2 className="text-lg font-bold text-slate-900 mb-6">{t('Informasi Kontak Resmi', 'Official Contact Information')}</h2>
            <ul className="space-y-5 mb-8">
              <li className="flex gap-4">
                <div className="w-10 h-10 rounded-xl bg-teal-50 border border-teal-200 flex items-center justify-center flex-shrink-0">
                  <IconMapPin className="w-5 h-5 text-teal-700" />
                </div>
                <div>
                  <div className="text-sm font-semibold text-slate-900 mb-0.5">{t('Alamat Kantor', 'Office Address')}</div>
                  <div className="text-sm text-slate-600 leading-relaxed">Mensana Tower Lt. 15, Jl. Raya Kranggan Kav. 1, Cibubur, Bekasi, Jawa Barat 17433</div>
                </div>
              </li>
              <li className="flex gap-4">
                <div className="w-10 h-10 rounded-xl bg-teal-50 border border-teal-200 flex items-center justify-center flex-shrink-0">
                  <IconPhone className="w-5 h-5 text-teal-700" />
                </div>
                <div>
                  <div className="text-sm font-semibold text-slate-900 mb-0.5">{t('Telepon', 'Phone')}</div>
                  <a href="tel:02139722772" className="text-sm text-teal-700 hover:text-teal-800 font-medium transition-colors">(021) 39722772</a>
                </div>
              </li>
              <li className="flex gap-4">
                <div className="w-10 h-10 rounded-xl bg-teal-50 border border-teal-200 flex items-center justify-center flex-shrink-0">
                  <IconWhatsApp className="w-5 h-5 text-teal-700" />
                </div>
                <div>
                  <div className="text-sm font-semibold text-slate-900 mb-0.5">WhatsApp</div>
                  <a href="https://wa.me/6282261461400" className="text-sm text-teal-700 hover:text-teal-800 font-medium transition-colors">0822-614-614-00</a>
                </div>
              </li>
              <li className="flex gap-4">
                <div className="w-10 h-10 rounded-xl bg-teal-50 border border-teal-200 flex items-center justify-center flex-shrink-0">
                  <IconEnvelope className="w-5 h-5 text-teal-700" />
                </div>
                <div>
                  <div className="text-sm font-semibold text-slate-900 mb-0.5">Email</div>
                  <a href="mailto:admin@avenasa.co.id" className="text-sm text-teal-700 hover:text-teal-800 font-medium transition-colors">admin@avenasa.co.id</a>
                </div>
              </li>
            </ul>

            {/* Map placeholder */}
            <div className="rounded-xl overflow-hidden bg-slate-100 border border-slate-200 aspect-video flex flex-col items-center justify-center text-center p-6">
              <IconMapPin className="w-8 h-8 text-teal-700 mb-3" />
              <div className="text-sm font-semibold text-slate-900 mb-1">Mensana Tower, Cibubur</div>
              <div className="text-xs text-slate-500 mb-4">Jl. Raya Kranggan Kav. 1, Bekasi</div>
              <a
                href="https://maps.google.com/?q=Mensana+Tower+Cibubur"
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center gap-1.5 text-xs text-teal-700 border border-teal-200 hover:bg-teal-50 px-3 py-1.5 rounded-lg transition-colors font-medium"
              >
                {t('Buka di Google Maps', 'Open in Google Maps')} <IconArrowRight className="w-3 h-3" />
              </a>
            </div>
          </div>

          {/* Right: Form */}
          <div className="lg:col-span-3">
            {success ? (
              <div className="bg-green-50 border border-green-200 rounded-2xl p-10 text-center">
                <div className="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-5">
                  <IconCheck className="w-8 h-8 text-green-700" />
                </div>
                <h3 className="text-xl font-bold text-slate-900 mb-3">
                  {t('Permintaan Berhasil Dikirim!', 'Request Successfully Submitted!')}
                </h3>
                <p className="text-slate-600 text-sm mb-6 max-w-md mx-auto leading-relaxed">
                  {t(
                    'Terima kasih telah menghubungi PT Abhipraya Nawasena Sejahtera. Data permintaan penawaran Anda telah kami terima dengan aman. Tim sales kami akan menghubungi Anda dalam 1×24 jam kerja.',
                    'Thank you for contacting PT Abhipraya Nawasena Sejahtera. Your quotation request has been safely received. Our sales team will contact you within 1×24 business hours.'
                  )}
                </p>
                <button
                  onClick={() => { setSuccess(false); setForm({ name: '', email: '', phone: '', company: '', subject: '', message: '' }) }}
                  className="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-semibold px-6 py-3 rounded-lg transition-colors"
                >
                  {t('Ajukan Permintaan Lain', 'Submit Another Request')}
                </button>
              </div>
            ) : (
              <div className="bg-white border border-slate-200 rounded-2xl p-7 lg:p-9">
                <h2 className="text-lg font-bold text-slate-900 mb-6">{t('Formulir Permintaan Penawaran', 'Quotation Request Form')}</h2>

                {/* Product Context Banner */}
                {contextProduct && (
                  <div className="bg-teal-50 border border-teal-200 rounded-xl p-4 mb-6 flex items-start justify-between gap-3">
                    <div className="flex items-start gap-3">
                      <div className="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <IconDocument className="w-4 h-4 text-teal-700" />
                      </div>
                      <div>
                        <div className="text-xs font-semibold text-teal-700 mb-0.5">{t('Permintaan Penawaran untuk:', 'Quotation Request for:')}</div>
                        <div className="text-sm font-bold text-slate-900 line-clamp-1">{locale === 'id' ? contextProduct.name_id : contextProduct.name_en}</div>
                      </div>
                    </div>
                    <button onClick={() => setContextProductId(null)} className="text-slate-400 hover:text-slate-600 flex-shrink-0 focus-ring rounded">
                      <IconX className="w-4 h-4" />
                    </button>
                  </div>
                )}

                <form onSubmit={handleSubmit} noValidate>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                    {/* Name */}
                    <div>
                      <label htmlFor="name" className="block text-sm font-semibold text-slate-700 mb-1.5">
                        {t('Nama Lengkap', 'Full Name')} <span className="text-red-500">*</span>
                      </label>
                      <input
                        id="name" type="text" required aria-required="true"
                        value={form.name}
                        onChange={e => setForm(f => ({ ...f, name: e.target.value }))}
                        placeholder={t('Dr. Ahmad Prasetyo', 'Dr. Ahmad Prasetyo')}
                        className={`w-full px-4 py-2.5 rounded-lg border text-slate-900 placeholder:text-slate-400 text-sm focus:outline-none focus:ring-2 transition-all ${errors.name ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'border-slate-300 focus:border-teal-700 focus:ring-teal-700/20'}`}
                      />
                      {errors.name && <p className="mt-1 text-xs text-red-600 font-medium" role="alert">{errors.name}</p>}
                    </div>
                    {/* Email */}
                    <div>
                      <label htmlFor="email" className="block text-sm font-semibold text-slate-700 mb-1.5">
                        {t('Alamat Email', 'Email Address')} <span className="text-red-500">*</span>
                      </label>
                      <input
                        id="email" type="email" required aria-required="true"
                        value={form.email}
                        onChange={e => setForm(f => ({ ...f, email: e.target.value }))}
                        placeholder="ahmad@laboratorium.co.id"
                        className={`w-full px-4 py-2.5 rounded-lg border text-slate-900 placeholder:text-slate-400 text-sm focus:outline-none focus:ring-2 transition-all ${errors.email ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'border-slate-300 focus:border-teal-700 focus:ring-teal-700/20'}`}
                      />
                      {errors.email && <p className="mt-1 text-xs text-red-600 font-medium" role="alert">{errors.email}</p>}
                    </div>
                    {/* Phone */}
                    <div>
                      <label htmlFor="phone" className="block text-sm font-semibold text-slate-700 mb-1.5">
                        {t('Telepon / WhatsApp', 'Phone / WhatsApp')}
                        <span className="text-slate-400 font-normal ml-1 text-xs">({t('opsional', 'optional')})</span>
                      </label>
                      <input
                        id="phone" type="tel"
                        value={form.phone}
                        onChange={e => setForm(f => ({ ...f, phone: e.target.value }))}
                        placeholder="0812-3456-7890"
                        className="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder:text-slate-400 text-sm focus:outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-700/20 transition-all"
                      />
                    </div>
                    {/* Company */}
                    <div>
                      <label htmlFor="company" className="block text-sm font-semibold text-slate-700 mb-1.5">
                        {t('Nama Perusahaan / Institusi', 'Company / Institution Name')}
                        <span className="text-slate-400 font-normal ml-1 text-xs">({t('opsional', 'optional')})</span>
                      </label>
                      <input
                        id="company" type="text"
                        value={form.company}
                        onChange={e => setForm(f => ({ ...f, company: e.target.value }))}
                        placeholder={t('RS Cipto Mangunkusumo', 'Cipto Mangunkusumo Hospital')}
                        className="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder:text-slate-400 text-sm focus:outline-none focus:border-teal-700 focus:ring-2 focus:ring-teal-700/20 transition-all"
                      />
                    </div>
                  </div>

                  {/* Subject */}
                  <div className="mb-5">
                    <label htmlFor="subject" className="block text-sm font-semibold text-slate-700 mb-1.5">
                      {t('Subjek Permintaan', 'Request Subject')} <span className="text-red-500">*</span>
                    </label>
                    <input
                      id="subject" type="text" required aria-required="true"
                      value={form.subject}
                      onChange={e => setForm(f => ({ ...f, subject: e.target.value }))}
                      placeholder={t('Permintaan Penawaran Harga LightCycler® 96 Real-Time PCR System', 'Price Quotation Request for LightCycler® 96 Real-Time PCR System')}
                      className={`w-full px-4 py-2.5 rounded-lg border text-slate-900 placeholder:text-slate-400 text-sm focus:outline-none focus:ring-2 transition-all ${errors.subject ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'border-slate-300 focus:border-teal-700 focus:ring-teal-700/20'}`}
                    />
                    {errors.subject && <p className="mt-1 text-xs text-red-600 font-medium" role="alert">{errors.subject}</p>}
                  </div>

                  {/* Message */}
                  <div className="mb-6">
                    <label htmlFor="message" className="block text-sm font-semibold text-slate-700 mb-1.5">
                      {t('Pesan / Rincian Kebutuhan', 'Message / Requirements Detail')} <span className="text-red-500">*</span>
                    </label>
                    <textarea
                      id="message" required aria-required="true" rows={5}
                      value={form.message}
                      onChange={e => setForm(f => ({ ...f, message: e.target.value }))}
                      placeholder={t('Jelaskan kebutuhan pengadaan Anda secara detail, termasuk jumlah unit, spesifikasi yang dibutuhkan, dan jadwal pengadaan yang diharapkan...', 'Describe your procurement needs in detail, including number of units, required specifications, and expected procurement schedule...')}
                      className={`w-full px-4 py-2.5 rounded-lg border text-slate-900 placeholder:text-slate-400 text-sm focus:outline-none focus:ring-2 transition-all resize-y min-h-[120px] ${errors.message ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'border-slate-300 focus:border-teal-700 focus:ring-teal-700/20'}`}
                    />
                    {errors.message && <p className="mt-1 text-xs text-red-600 font-medium" role="alert">{errors.message}</p>}
                  </div>

                  {/* Honeypot (hidden) */}
                  <input type="text" name="website_url_hp" className="sr-only" tabIndex={-1} aria-hidden="true" />

                  <button
                    type="submit"
                    disabled={loading}
                    className={`w-full flex items-center justify-center gap-2 font-bold py-3.5 rounded-lg text-sm shadow-sm transition-all active:scale-[0.98] ${loading ? 'bg-teal-500 cursor-not-allowed opacity-75' : 'bg-teal-700 hover:bg-teal-800 text-white'}`}
                  >
                    {loading ? (
                      <>
                        <svg className="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                          <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                          <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        {t('Memproses...', 'Processing...')}
                      </>
                    ) : (
                      <>{t('Kirim Permintaan Penawaran', 'Submit Quotation Request')} <IconArrowRight /></>
                    )}
                  </button>

                  <p className="text-xs text-slate-400 text-center mt-4">
                    {t('Data Anda aman dan tidak akan dibagikan kepada pihak ketiga.', 'Your data is secure and will not be shared with third parties.')}
                  </p>
                </form>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  )
}

// ── APP ROOT ───────────────────────────────────────────────────────────────────

export default function App() {
  const [locale, setLocale] = useState<Locale>('id')
  const [currentPage, setCurrentPage] = useState<Page>('home')
  const [productId, setProductId] = useState<number>(1)
  const [scrolled, setScrolled] = useState(false)
  const [contactProductId, setContactProductId] = useState<number | null>(null)
  const topRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 10)
    window.addEventListener('scroll', onScroll, { passive: true })
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  const navigateTo = (page: Page) => {
    setCurrentPage(page)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  const goToProductDetail = (id: number) => {
    setProductId(id)
    setCurrentPage('product-detail')
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  const goToContact = (page: Page) => {
    navigateTo(page)
  }

  return (
    <div ref={topRef} className="min-h-screen bg-white font-sans">
      <Header
        locale={locale}
        setLocale={setLocale}
        currentPage={currentPage}
        setPage={navigateTo}
        scrolled={scrolled}
      />

      <main>
        {currentPage === 'home' && (
          <HomePage locale={locale} setPage={navigateTo} setProductId={goToProductDetail} />
        )}
        {currentPage === 'about' && (
          <AboutPage locale={locale} setPage={navigateTo} />
        )}
        {currentPage === 'catalog' && (
          <CatalogPage locale={locale} setPage={navigateTo} setProductId={goToProductDetail} />
        )}
        {currentPage === 'product-detail' && (
          <ProductDetailPage productId={productId} locale={locale} setPage={navigateTo} />
        )}
        {currentPage === 'partners' && (
          <PartnersPage locale={locale} setPage={navigateTo} />
        )}
        {currentPage === 'contact' && (
          <ContactPage locale={locale} setPage={navigateTo} productId={contactProductId} />
        )}
      </main>

      {/* Don't show footer on product detail on mobile due to sticky bar */}
      <Footer locale={locale} setPage={navigateTo} />
    </div>
  )
}
