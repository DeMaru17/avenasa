<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Models\Client;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Klien Korporat')
                    ->description('Nama institusi/perusahaan klien dan logo resmi.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Klien / Institusi')
                            ->placeholder('contoh: PT Kalbe Farma Tbk')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('logo_path')
                            ->label('Logo Klien')
                            ->helperText('Format PNG atau WebP (disarankan latar belakang transparan), maksimal 2 MB.')
                            ->image()
                            ->disk('public')
                            ->directory('clients')
                            ->maxSize(2048)
                            ->required(),
                    ])->columns(2),

                Section::make('Pengaturan & Visibilitas')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->helperText('Menentukan posisi urutan logo pada showcase klien.')
                            ->numeric()
                            ->default(fn () => (Client::max('sort_order') ?? 0) + 1)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Aktifkan untuk menampilkan logo pada showcase klien di website.')
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
