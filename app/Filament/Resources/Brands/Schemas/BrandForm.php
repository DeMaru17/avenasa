<?php

namespace App\Filament\Resources\Brands\Schemas;

use App\Models\Brand;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Brand / Principal')
                    ->description('Nama resmi, logo principal, dan tautan website resmi.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Brand / Principal')
                            ->placeholder('contoh: Merck Millipore')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('website_url')
                            ->label('Website Resmi Principal')
                            ->placeholder('https://www.merckmillipore.com')
                            ->helperText('Tautan eksternal ke situs resmi principal. Gunakan URL lengkap yang diawali http:// atau https://.')
                            ->url()
                            ->nullable()
                            ->maxLength(255),
                        FileUpload::make('logo_path')
                            ->label('Logo Resmi Principal')
                            ->helperText('Format PNG atau WebP (disarankan latar belakang transparan), maksimal 2 MB.')
                            ->image()
                            ->disk('public')
                            ->directory('brands')
                            ->maxSize(2048)
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Deskripsi Profil Principal')
                    ->schema([
                        Textarea::make('description_id')
                            ->label('Deskripsi Principal (ID)')
                            ->placeholder('Ringkasan profil dan keunggulan principal dalam Bahasa Indonesia')
                            ->rows(3)
                            ->nullable(),
                        Textarea::make('description_en')
                            ->label('Deskripsi Principal (EN)')
                            ->placeholder('Summary of principal profile and strengths in English')
                            ->rows(3)
                            ->nullable(),
                    ])->columns(2),

                Section::make('Status & Pengurutan')
                    ->schema([
                        Toggle::make('is_new_principal')
                            ->label('Mitra Principal Baru (New Principal)')
                            ->helperText('Aktifkan untuk memberikan highlight khusus kemitraan prinsipal baru (khusus Era Biology).')
                            ->default(false),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->helperText('Menentukan posisi tampilan pada daftar brand. Angka lebih kecil tampil lebih dahulu.')
                            ->numeric()
                            ->default(fn () => (Brand::max('sort_order') ?? 0) + 1)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Aktifkan untuk menampilkan brand pada filter katalog dan mitra publik.')
                            ->default(true)
                            ->required(),
                    ])->columns(3),
            ]);
    }
}
