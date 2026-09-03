# Design: Brand Website URL Integration on Public Pages

## 1. Architecture & Component Structure

Perubahan ini berfokus pada layer presentasi (Blade Views & Components) tanpa mengubah struktur database atau backend controller:

```
resources/views/
├── components/
│   ├── partners-clients/
│   │   └── principals.blade.php    # [MODIFY] Dual action bottom bar per brand card
│   └── product-detail/
│       └── identity.blade.php      # [MODIFY] Adjacent brand website link badge
└── pages/
    └── products/
        └── show.blade.php          # [MODIFY] JSON-LD Product schema brand url
```

## 2. Component Design Details

### 2.1 Kartu Principal (`principals.blade.php`)
- **Container Footer**: `mt-auto pt-4 border-t border-slate-100 flex items-center justify-between gap-3`
- **Kasus A: Ada Produk + Ada Website URL**:
  - Sisi Kiri: `<a href="{{ route('products.index', ['brand' => $brand->slug]) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-teal-700 hover:text-teal-800 ...">` (Lihat Produk / View Products).
  - Sisi Kanan: `<a href="{{ $brand->website_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 hover:text-teal-700 ...">` (Website Resmi ↗ / Official Site ↗).
- **Kasus B: Tidak Ada Produk + Ada Website URL**:
  - Tombol melebar: `<a href="{{ $brand->website_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-semibold text-teal-700 hover:text-teal-800 ...">` (Kunjungi Website Resmi ↗ / Visit Official Website ↗).
- **Kasus C: Ada Produk + Tanpa Website URL**:
  - Hanya menampilkan "Lihat Produk" seperti implementasi awal.

### 2.2 Identitas Detail Produk (`identity.blade.php`)
- Di bagian classification badges:
  ```blade
  @if ($product->brand)
      <a href="{{ route('products.index', ['brand' => $product->brand->slug]) }}" ...>
          {{ $product->brand->name }}
      </a>

      @if (!empty($product->brand->website_url))
          <a
              href="{{ $product->brand->website_url }}"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 hover:text-teal-700 bg-slate-50 hover:bg-teal-50/50 border border-slate-200/80 px-2.5 py-1 rounded-lg transition-colors focus-ring"
              title="{{ $currentLocale === 'en' ? 'Visit ' . $product->brand->name . ' official website' : 'Kunjungi situs resmi ' . $product->brand->name }}"
          >
              <span>{{ $currentLocale === 'en' ? 'Official Site' : 'Situs Resmi' }}</span>
              <svg class="w-3 h-3 text-slate-400 group-hover:text-teal-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
              </svg>
          </a>
      @endif
  @endif
  ```

### 2.3 SEO JSON-LD Enrichment (`show.blade.php`)
- Memperluas skema `Product` pada entity `brand`:
  ```php
  if ($product->brand) {
      $productSchema['brand'] = [
          '@type' => 'Brand',
          'name' => $product->brand->name,
      ];
      if (!empty($product->brand->website_url)) {
          $productSchema['brand']['url'] = $product->brand->website_url;
      }
  }
  ```

## 3. Package Structure (`C:\laragon\www\avenasa-update-4`)
Direktori paket update produksi baru:
```
avenasa-update-4/
├── application/
│   └── resources/
│       └── views/
│           ├── components/
│           │   ├── partners-clients/
│           │   │   └── principals.blade.php
│           │   └── product-detail/
│           │       └── identity.blade.php
│           └── pages/
│               └── products/
│                   └── show.blade.php
├── DEPLOYMENT-MANIFEST.md
├── UPLOAD-CHECKLIST.md
└── ROLLBACK.md
```
