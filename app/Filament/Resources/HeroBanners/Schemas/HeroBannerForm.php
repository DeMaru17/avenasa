<?php

namespace App\Filament\Resources\HeroBanners\Schemas;

use App\Models\HeroBanner;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Utama Banner')
                    ->description('Judul utama dan subjudul dalam Bahasa Indonesia dan Bahasa Inggris.')
                    ->schema([
                        TextInput::make('title_id')
                            ->label('Judul Banner (ID)')
                            ->placeholder('contoh: Solusi Distribusi Peralatan Laboratorium')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('title_en')
                            ->label('Judul Banner (EN)')
                            ->placeholder('e.g., Trusted Laboratory Distribution Solutions')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('subtitle_id')
                            ->label('Subjudul / Deskripsi Singkat (ID)')
                            ->placeholder('Penjelasan ringkas pesan promosi atau lini produk utama')
                            ->rows(2)
                            ->nullable(),
                        Textarea::make('subtitle_en')
                            ->label('Subjudul / Deskripsi Singkat (EN)')
                            ->placeholder('Brief summary of promotional message or core product line')
                            ->rows(2)
                            ->nullable(),
                    ])->columns(2),

                Section::make('Gambar Banner')
                    ->description('Gambar utama (desktop) wajib diunggah. Gambar mobile bersifat opsional untuk art-direction.')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Gambar Utama / Desktop')
                            ->helperText('Format JPG, PNG, atau WebP. Rekomendasi resolusi 1920 × 800 px (Desktop), maksimal 2 MB.')
                            ->image()
                            ->disk('public')
                            ->directory('hero-banners')
                            ->maxSize(2048)
                            ->required(),
                        FileUpload::make('mobile_image_path')
                            ->label('Gambar Khusus Mobile (Opsional)')
                            ->helperText('Format JPG, PNG, atau WebP. Rekomendasi resolusi 800 × 1000 px (Mobile), maksimal 2 MB.')
                            ->image()
                            ->disk('public')
                            ->directory('hero-banners')
                            ->maxSize(2048)
                            ->nullable(),
                    ])->columns(2),

                Section::make('Tombol Call To Action (CTA)')
                    ->description('Konfigurasi tombol aksi yang mengarahkan pengunjung ke halaman internal website.')
                    ->schema([
                        TextInput::make('button_text_id')
                            ->label('Teks Tombol (ID)')
                            ->placeholder('contoh: Jelajahi Katalog')
                            ->maxLength(255)
                            ->nullable(),
                        TextInput::make('button_text_en')
                            ->label('Teks Tombol (EN)')
                            ->placeholder('e.g., Explore Catalog')
                            ->maxLength(255)
                            ->nullable(),
                        TextInput::make('button_url')
                            ->label('Tautan Halaman Internal')
                            ->placeholder('/products atau /contact')
                            ->helperText('Gunakan path halaman internal yang diawali "/", misalnya /products, /about, atau /contact. Sistem akan menyesuaikan tautan dengan bahasa aktif di website.')
                            ->regex('/^\/[a-zA-Z0-9\-\_\/\?\=\&\#]*$/', 'Path halaman internal wajib diawali dengan tanda "/" (contoh: /products atau /contact).')
                            ->maxLength(255)
                            ->nullable()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Pengaturan & Visibilitas')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->helperText('Menentukan posisi urutan slide pada beranda. Angka lebih kecil tampil lebih dahulu.')
                            ->numeric()
                            ->default(fn () => (HeroBanner::max('sort_order') ?? 0) + 1)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Aktifkan untuk menampilkan banner pada slider beranda.')
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
