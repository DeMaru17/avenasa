<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->description('Nama kategori dalam Bahasa Indonesia dan Bahasa Inggris.')
                    ->schema([
                        TextInput::make('name_id')
                            ->label('Nama Kategori (ID)')
                            ->placeholder('contoh: Mikrobiologi Industri')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name_en')
                            ->label('Nama Kategori (EN)')
                            ->placeholder('e.g., Industrial Microbiology')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Pengaturan & Visibilitas')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->helperText('Menentukan posisi tampilan. Angka lebih kecil tampil lebih dahulu.')
                            ->numeric()
                            ->default(fn () => (Category::max('sort_order') ?? 0) + 1)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Aktifkan untuk menampilkan kategori pada katalog dan navigasi publik.')
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
