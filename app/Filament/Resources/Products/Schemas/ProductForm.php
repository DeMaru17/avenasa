<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Klasifikasi Produk')
                    ->description('Tentukan kategori katalog dan brand principal pembuat produk.')
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori Produk')
                            ->relationship('category', 'name_id')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('brand_id')
                            ->label('Brand / Principal')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),

                Section::make('Identitas Produk')
                    ->description('Nama produk dalam Bahasa Indonesia dan Bahasa Inggris.')
                    ->schema([
                        TextInput::make('name_id')
                            ->label('Nama Produk (ID)')
                            ->placeholder('contoh: Sistem Real-Time PCR')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name_en')
                            ->label('Nama Produk (EN)')
                            ->placeholder('e.g., Real-Time PCR System')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Deskripsi & Ringkasan')
                    ->schema([
                        Textarea::make('summary_id')
                            ->label('Ringkasan Singkat (ID)')
                            ->placeholder('Penjelasan ringkas 1-2 kalimat untuk kartu produk di katalog')
                            ->rows(2)
                            ->nullable(),
                        Textarea::make('summary_en')
                            ->label('Ringkasan Singkat (EN)')
                            ->placeholder('Brief 1-2 sentence summary for product catalog cards')
                            ->rows(2)
                            ->nullable(),
                        RichEditor::make('description_id')
                            ->label('Deskripsi Lengkap (ID)')
                            ->placeholder('Uraian lengkap fitur, keunggulan, dan aplikasi produk...')
                            ->nullable()
                            ->columnSpanFull(),
                        RichEditor::make('description_en')
                            ->label('Deskripsi Lengkap (EN)')
                            ->placeholder('Detailed features, advantages, and product applications...')
                            ->nullable()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Spesifikasi Teknis (Bilingual Key-Value)')
                    ->description('Tambahkan parameter spesifikasi teknis dalam format key-value dwibahasa.')
                    ->schema([
                        Repeater::make('specifications')
                            ->label('Daftar Spesifikasi')
                            ->helperText('Tambahkan parameter teknis produk, misalnya Bentuk Media, Kemasan, atau Suhu Penyimpanan. Kosongkan jika produk tidak memiliki spesifikasi khusus.')
                            ->default([])
                            ->schema([
                                TextInput::make('key_id')
                                    ->label('Parameter (ID)')
                                    ->placeholder('Misal: Bentuk Media')
                                    ->required(),
                                TextInput::make('key_en')
                                    ->label('Parameter (EN)')
                                    ->placeholder('e.g., Media Form')
                                    ->required(),
                                TextInput::make('value_id')
                                    ->label('Nilai (ID)')
                                    ->placeholder('Misal: Bubuk Dehidrasi')
                                    ->required(),
                                TextInput::make('value_en')
                                    ->label('Nilai (EN)')
                                    ->placeholder('e.g., Dehydrated Powder')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Section::make('Media & Dokumen Brosur')
                    ->schema([
                        FileUpload::make('primary_image_path')
                            ->label('Foto Utama Produk')
                            ->helperText('Format JPG, PNG, atau WebP. Rekomendasi rasio 1:1 (persegi), minimal 800 × 800 px, maksimal 2 MB.')
                            ->image()
                            ->disk('public')
                            ->directory('products/primary')
                            ->maxSize(2048)
                            ->required(),
                        FileUpload::make('brochure_path')
                            ->label('Berkas Brosur (PDF)')
                            ->helperText('Format PDF, maksimal 10 MB. Tombol "Unduh Brosur" di website hanya muncul jika brosur tersedia.')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('brochures')
                            ->maxSize(10240)
                            ->nullable(),
                    ])->columns(2),

                Section::make('Pengaturan & Visibilitas')
                    ->schema([
                        Toggle::make('is_featured')
                            ->label('Produk Unggulan (Featured)')
                            ->helperText('Aktifkan untuk menampilkan produk sebagai produk unggulan di Beranda.')
                            ->default(false),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Aktifkan untuk mempublikasikan produk pada katalog publik.')
                            ->default(true)
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->helperText('Menentukan posisi tampilan pada katalog. Angka lebih kecil tampil lebih dahulu.')
                            ->numeric()
                            ->default(fn () => (Product::max('sort_order') ?? 0) + 1)
                            ->required(),
                    ])->columns(3),
            ]);
    }
}
