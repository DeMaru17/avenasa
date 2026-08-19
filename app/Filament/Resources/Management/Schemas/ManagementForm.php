<?php

namespace App\Filament\Resources\Management\Schemas;

use App\Models\Management;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ManagementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pimpinan / Founder')
                    ->description('Nama lengkap, gelar, dan foto profil resmi.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap & Gelar')
                            ->placeholder('contoh: Erik Haryanto')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('photo_path')
                            ->label('Foto Profil (Opsional)')
                            ->helperText('Format JPG, PNG, atau WebP. Gunakan foto dengan rasio 1:1 atau 3:4, maksimal 2 MB.')
                            ->image()
                            ->disk('public')
                            ->directory('management')
                            ->maxSize(2048)
                            ->nullable(),
                    ])->columns(2),

                Section::make('Jabatan & Riwayat Pengalaman')
                    ->schema([
                        TextInput::make('position_id')
                            ->label('Jabatan Resmi (ID)')
                            ->placeholder('contoh: Komisaris / Direktur')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('position_en')
                            ->label('Jabatan Resmi (EN)')
                            ->placeholder('e.g., Commissioner / Director')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('bio_id')
                            ->label('Riwayat Pengalaman / Biografi (ID)')
                            ->placeholder('Ringkasan riwayat karir dan kepemimpinan dalam industri laboratorium')
                            ->rows(3)
                            ->nullable(),
                        Textarea::make('bio_en')
                            ->label('Riwayat Pengalaman / Biografi (EN)')
                            ->placeholder('Professional summary and executive leadership background in the laboratory industry')
                            ->rows(3)
                            ->nullable(),
                    ])->columns(2),

                Section::make('Pengaturan & Visibilitas')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->helperText('Menentukan posisi tampilan profil pimpinan.')
                            ->numeric()
                            ->default(fn () => (Management::max('sort_order') ?? 0) + 1)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Aktifkan untuk menampilkan data pada website publik.')
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
