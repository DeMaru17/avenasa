# ANS — Catalog Inventory

**Perusahaan:** PT Abhipraya Nawasena Sejahtera (ANS)
**Sumber utama:** `catalog-ans.pdf` (64 halaman) dan `Company Profile ANS 2026 Rev.02(1).pdf` (13 halaman)
**Fungsi dokumen:** Master inventory/reference sebelum pembuatan `ProductSeeder` dan pengisian data katalog pada CMS.

> **Aturan data:** Dokumen ini hanya mencatat nama produk/product family, model/article number, dan status yang didukung oleh sumber. Nilai yang belum terbaca jelas dari katalog tidak ditebak dan ditandai `needs-review`.

## Status Data

- **confirmed** — nama/family/model/article number didukung langsung oleh katalog atau company profile.
- **partial** — product/family jelas, tetapi model/article number atau rincian item belum sepenuhnya terbaca.
- **needs-review** — membutuhkan verifikasi manual dari PDF/gambar katalog sebelum masuk database.
- **principal-only** — ada di company profile sebagai principal/product line, tetapi belum memiliki rincian produk yang cukup pada katalog ini.
- **catalog-only** — muncul di katalog, tetapi status sebagai principal resmi belum dikonfirmasi oleh company profile.

---

# 1. Water Testing & Colour Measurement

**Brand/Principal:** Lovibond / Tintometer
**Company Profile reference:** Portfolio `Water quality test`.

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| Cooling and Industrial Process Water Test Kits | Tidak ditranskripsi seluruh article code | 5 | confirmed | Tabel parameter dan order code terlihat pada halaman katalog. |
| Hardness Test Kit | Tidak terbaca sebagai satu model tunggal | 6 | confirmed | Family tersurat pada halaman katalog. |
| Silt Density Index (SDI) Test Kit | Artikel/order code pada tabel belum seluruhnya ditranskripsi | 6 | confirmed | SDI test kit untuk reverse osmosis; 100 tests. |
| Three-Chamber Tester Chlorine / pH | Tidak tercantum model terpisah pada text extraction | 6 | confirmed | Range chlorine 0.1–3.0 mg/l, pH 6.8–8.2. |
| Non-Oxidising Biocide Kits | Tabel article/code ada, belum ditranskripsi penuh | 6 | partial | Beberapa varian terlihat pada tabel gambar. |
| Arsenic Test Kit (5ppb) | **400700** | 7 | confirmed | Order code tercantum eksplisit. |
| CHECKIT Comparator | Banyak disc/test kit; code belum ditranskripsi penuh | 8 | partial | Halaman berisi tabel besar single-parameter test kits. |
| Comparator 2000+ Complete Kits for Water Analysis | Banyak kit; contoh code terlihat pada tabel, belum ditranskripsi penuh | 9 | partial | Catalog menyebut standard kits dan customized equipment. |
| E-Comparator EC 2000 Pt-Co | **EC 2000** | 10 | confirmed | Accessories/reference items ada di tabel; model utama EC 2000. |
| Photometer | **MD 100, MD 110, MD 200** | 11 | confirmed | Ketiga model disebut eksplisit pada halaman photometry. |
| Photometers | **MD 600, MD 610** | 13 | confirmed | Model disebut eksplisit. |
| Photometer & Fluorometer for PTSA | **MD 640** | 13 | confirmed | Model dan fungsi PTSA/fluorescein disebut eksplisit. |
| Thermoreactor / COD Reactor | **RD 125** / Order Code **2418940** | 12 | confirmed | Order code tercantum eksplisit. |
| VIS / UV-VIS Spectrophotometer | **XD 7000, XD 7500** | 14 | confirmed | Kedua model disebut eksplisit. |
| Vario Reagents | Banyak reagent/test; code belum ditranskripsi penuh | 15–17 | partial | Tiga halaman tabel reagent. Jangan diperlakukan sebagai satu SKU tanpa mapping lebih lanjut. |
| Turbidity Meter | **TB 300 IR** | 18 | confirmed | Model eksplisit. |
| Turbidity Meter | **TB 211 IR** | 19 | confirmed | Model eksplisit. |
| Turbidity Meter | **TB 250 WL** | 20 | confirmed | Model eksplisit. |
| BOD Measurement System | **BD 600, BD 600 GLP** | 21 | confirmed | Kedua varian disebut eksplisit. |

---

# 2. Food Safety & Allergen Diagnostics

**Company Profile reference:** `Food Safety` dan `Microbiology` termasuk portfolio ANS.

## 2.1 Gold Standard Diagnostics

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| SENSISpec Allergen Detection ELISA Kits | Banyak target/article number; belum ditranskripsi penuh | 24 | partial | Tabel menunjukkan banyak allergen assay. |
| SENSISpec Gluten Detection ELISA Kits | Beberapa item; article number belum ditranskripsi penuh | 25 | partial | Product table terpisah. |
| Supplementary Products | Beberapa item | 25 | partial | Tabel kecil pada halaman 25. |
| Allergen Control Materials | Beberapa item | 25 | partial | Tabel product/article no. terlihat. |
| DNAllergen2 PCR Kits | Beberapa target | 25 | partial | Dua kelompok tabel DNAllergen2 terlihat. |
| SENSIStrip Lateral Flow Tests | Banyak target/article number; belum ditranskripsi penuh | 26 | partial | Tabel besar lateral flow tests. |
| RapidScan LFD Reader | Article number belum diverifikasi penuh | 26 | needs-review | Teks menyebut RapidScan Lateral Flow Assay Reader; gambar/tabel perlu transkripsi manual. |

## 2.2 Neogen

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| Clean-Trace Hygiene Monitoring System | **UXL100, AQT200, AQF100** | 27 | confirmed | Luminometer + software + Surface/Water ATP components disebut eksplisit. |
| One Broth One Plate (OBOP) workflows | Listeria spp., Listeria monocytogenes, Salmonella spp. | 28 | confirmed | Product family/workflow; individual SKU belum dirinci. |

## 2.3 Fountain Scientific / GaBriFilm

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| GaBriFilm Rapid Aerobic Count | Tidak terbaca model/article code tunggal | 29 | confirmed | Product family tersurat. |
| GaBriFilm Rapid Coliform Count | Tidak terbaca model/article code tunggal | 30, 32–33 | confirmed | Family dan spesifikasi jelas. |
| GaBriFilm Yeast & Mold | Tidak terbaca model/article code tunggal | 30–31 | confirmed | Family tersurat. |
| GaBriFilm Staphylococcus aureus | Tidak terbaca model/article code tunggal | 31 | confirmed | Family tersurat. |
| GaBriFilm Rapid E. coli & Coliform Two in One | Tidak terbaca model/article code tunggal | 32 | confirmed | Product family tersurat. |
| GaBriFilm Rapid Coliform Count Plate (Fermented Milk) | Tidak terbaca model/article code tunggal | 32 | confirmed | Product khusus fermented milk. |
| GaBriFilm Rapid Salmonella Count Plate | Tidak terbaca model/article code tunggal | 33 | confirmed | Family tersurat. |
| GaBriFilm Environmental Listeria Test Plate | Tidak terbaca model/article code tunggal | 34 | confirmed | Family tersurat. |
| Counter 1000 Colony Reader | **1000 Colony Reader** | 34 | confirmed | Nama reader tercantum. |

---

# 3. Endotoxin & Pyrogen Testing

**Company Profile reference:** `Endotoxin and pyrogen testing`.

## Bioendo

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| Gel Clot Lyophilized Amebocyte Lysate | Single Test in Vial | 35 | confirmed | Variant disebut eksplisit. |
| Gel Clot Lyophilized Amebocyte Lysate | Single Test in Ampoule | 35 | confirmed | Variant disebut eksplisit. |
| Kinetic Chromogenic Endotoxin Assay | Tidak ada model/article number tunggal di text | 35 | confirmed | Sensitivity sampai 0.001 EU/ml disebut. |
| Pyrogen-free Microplates | **MPC96** | 36 | confirmed | 96-well plate strip catalog number MPC96. |
| 800 TS Absorbance Microplate Reader | **800 TS** | 37 | partial | Model jelas, tetapi atribusi principal/brand perlu dikonfirmasi. |

---

# 4. Sterilization Monitoring

## Terragene / Bionova

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| UV Dosimeters for Disinfection Systems | Tidak ada model tunggal yang terbaca | 38 | confirmed | UV-C 254 nm / pulsed light. |
| BioSurf Biological Indicator | **BT97 BioSurf** | 38–39 | confirmed | Bionova BioSurf BT97. |
| Ultra Rapid / Super Rapid / Rapid Biological Indicators | **BT224, BT222, BT96, BT102, BT110** | 39 | confirmed | Model disebut eksplisit bersama aplikasi. |
| Process Challenge Devices (Steam PCDs) | Model tidak ditranskripsi | 39 | confirmed | Family tersurat. |
| Conventional Biological Indicators | **IC10/20** | 40 | confirmed | IC10/20 disebut eksplisit. |
| Steam Sterilization Spore Ampoules | **BT21, BT22, BT23, BT24** | 40 | confirmed | Model dan strain disebut eksplisit. |
| Spore Strips | **Bionova Spore Strips** | 40 | confirmed | Family tersurat. |
| Spore Ampoule & Culture Medium | **BT31** | 40 | confirmed | BT31 BI disebut eksplisit. |
| Bowie-Dick Test Packs | **BD125X/1, BD125X/2** | 41 | confirmed | Model/product code disebut eksplisit. |

---

# 5. Laboratory Equipment & Consumables

## 5.1 IKA

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| HABITAT Research Bioreactor | **HABITAT Research** | 42 | confirmed | Product family/model tersurat. |
| Magnetic Stirrers | Tidak ditranskripsi model lengkap | 43 | partial | Family `Magnetic Stirrer`; model table tidak terbaca penuh dari text layer. |
| Overhead Stirrers | Tidak ditranskripsi model lengkap | 44 | partial | Family tersurat. |
| Shakers / Incubator Shakers | **KS 4000** disebut | 45 | confirmed | Model KS 4000 disebut eksplisit. |
| Dispersers / Homogenizers | **ULTRA-TURRAX** | 45 | confirmed | Product line tersurat. |
| Mills | **M 20 Universal Mill** disebut | 46 | confirmed | Family laboratory mills dan contoh model M 20. |
| Viscometers | **ROTAVISC** | 46 | confirmed | Series tersurat. |
| Rotary Evaporators | **RV 8, RV 10** disebut eksplisit | 47 | confirmed | RV 8 dan RV 10; tabel model lengkap perlu transkripsi manual. |
| Centrifuge | **G-L** | 48 | confirmed | IKA G-L midi centrifuge. |
| Pipettes | **fix, vario, multi** / PETTE series | 48 | confirmed | Series PETTE fix, vario, multi disebut. |

## 5.2 DLAB

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| LED Digital Rotary Evaporator | **RE100-Pro** referenced in compatibility note | 51 | partial | Product family jelas; model table ada di page 52 tetapi belum ditranskripsi semua. |
| Rotary Evaporator Models | Tabel model tersedia, model individual belum ditranskripsi dari gambar | 52 | needs-review | Halaman tabel bergambar perlu transkripsi manual. |
| Magnetic Hotplate Stirrers | **MS10-H500-Pro** | 53 | confirmed | Model eksplisit. |
| Magnetic Hotplate Stirrer Models | Banyak model dalam tabel | 54 | needs-review | Model individual belum ditranskripsi dari gambar. |
| Shakers | **SK-0330-Pro, SK-0180-Pro** | 55 | confirmed | Kedua model disebut pada halaman. |
| Shaker Models | Banyak model dalam tabel | 56 | needs-review | Tabel model perlu transkripsi manual. |
| UV-visible Spectrophotometer | **SP-V1000, SP-UV1000, SP-V1100, SP-UV1100** | 57–58 | confirmed | Model disebut pada tabel model halaman 58. |

---

# 6. Chemical Solvents

## Fisher Scientific

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| Optima LC/MS Solvents | Tidak ada model/article number di text | 49 | confirmed | Product family. |
| HPLC Grade Solvents | Tidak ada model/article number di text | 49 | confirmed | Product family. |
| Certified ACS Solvents | Tidak ada model/article number di text | 50 | confirmed | Product family. |

---

# 7. Environmental / Wastewater Solutions

## BIGBIO

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| BIGBIO | Liquid and powder formulation | 59 | confirmed | Product described as bioaugmentation solution for domestic wastewater. |

## Cleanbio

| Product / Family | Model / Article Number yang terbaca | Source Page | Status | Notes |
|---|---|---:|---|---|
| Cleanbio Air-Fit | **Air-Fit 50** | 61 | confirmed | Model disebut eksplisit. |
| Cleanbio Air-Fit | **Air-Fit 30** | 62 | confirmed | Model disebut eksplisit. |
| Cleanbio Air-Fit | **Air-Fit 20** | 62 | confirmed | Model disebut eksplisit. |
| Air-Fit 4-Stage Filtering | Tidak ada model terpisah | 60 | confirmed | Feature/system description, bukan product baru. |

---

# 8. ERA Biology — Principal/Product Lines

**Company Profile:** ERA Biology ditampilkan sebagai `New Principal` dengan lima product lines.

| Product Line | Model / Article Number | Source Page | Status | Notes |
|---|---|---:|---|---|
| Tachypleus Amebocyte Lysate (TAL) | — | Company Profile 9 | principal-only | Product line confirmed; no individual product catalog in `Catalog ANS (4).pdf`. |
| CLIA Solution for infectious diseases | — | Company Profile 9 | principal-only | Product line confirmed only. |
| Lateral Flow Assay | — | Company Profile 9 | principal-only | Product line confirmed only. |
| Real-time PCR | — | Company Profile 9 | principal-only | Product line confirmed only. |
| ELISA | — | Company Profile 9 | principal-only | Product line confirmed only. |

---

# 9. Principal / Brand Master Cross-Reference

## Confirmed by Company Profile

Company Profile pages 7–8 visually present the principal logos:

- Fountain Scientific
- Neogen
- Gold Standard Diagnostics
- Terragene
- Alliance Bio Expertise
- Thermo Scientific
- Merck
- HiMedia
- IKA
- DLAB
- Labitex
- Lonza
- Lovibond

Company Profile page 9 adds **ERA Biology** as new principal.

## Appears in Catalog but Principal Status Needs Verification

- Bioendo
- Fisher Scientific
- BIGBIO
- Cleanbio

> `catalog-only` means the name is present in the product catalog, not that it has been independently confirmed as a current official principal in the Company Profile.

---

# 10. Proposed Working Category Taxonomy

This is a **working taxonomy for inventory**, not yet a final database taxonomy.

1. Water Testing & Colour Measurement
2. Food Safety & Allergen Diagnostics
3. Microbiology
4. Endotoxin & Pyrogen Testing
5. Sterilization Monitoring
6. Laboratory Equipment & Instruments
7. Laboratory Consumables & Reagents
8. Chemical Solvents
9. Environmental & Wastewater Solutions
10. Air Purification & Sterilization
11. Clinical Diagnostics

**Important:** The Company Profile's portfolio terminology remains the primary business framing: Microbiology, Endotoxin and Pyrogen Testing, Food Safety, Water Quality Test, Clinical Diagnostic, and Laboratory Equipment and Consumable.

Because the architecture uses flat/single-level categories, the working taxonomy must be reviewed before large-scale Product seeding.

---

# 11. Items Explicitly Not Yet Ready for Product Seeder

The following should **not** be converted automatically into individual products yet:

1. Vario Reagents — multiple reagent rows/article codes across pages 15–17.
2. SENSISpec ELISA — many target-specific rows on page 24.
3. SENSISpec Gluten / Supplementary / Allergen Control / DNAllergen2 — multiple rows on page 25.
4. SENSIStrip Lateral Flow Tests — many target-specific rows on page 26.
5. DLAB rotary evaporator model table — page 52.
6. DLAB magnetic hotplate stirrer model table — page 54.
7. DLAB shaker model table — page 56.
8. Several Lovibond test-kit/comparator tables — pages 5–10.
9. Any product whose model/article number is only visible in a low-resolution image and has not yet been manually transcribed.
10. ERA Biology product lines from Company Profile page 9 — principal-only, no detailed catalog in the supplied product PDF.

---

# 12. Source Documents

### Company Profile

`Company Profile ANS 2026 Rev.02(1).pdf`

- 13 pages.
- Primary source for company profile, vision/mission, six core values, founders/owners, portfolio, principal list, market segment, customers, contact details.

### Product Catalog

`Catalog ANS (4).pdf`

- 64 pages.
- Primary source for product/product-family inventory, product descriptions, specifications, model numbers, article/order codes, and catalog images.

---

# 13. Final Inventory Verdict

```text
============================================================
ANS CATALOG INVENTORY V1
============================================================
Company Profile Reference : VERIFIED
Catalog Reference         : VERIFIED
Product Families          : INVENTORIED
Explicit Models/Codes     : CAPTURED WHERE READABLE
Image/Table-only Data     : MARKED FOR REVIEW
Principal Cross-check     : COMPLETE
Category Taxonomy         : WORKING DRAFT
Ready for Product Seeder  : NO — INVENTORY REVIEW FIRST
============================================================
```

This document is intended to become the **source-of-truth inventory** before Milestone 2.3B product seeding. It deliberately does not invent article/model numbers that are not reliably readable from the supplied documents.
