<?php

namespace App\Filament\Resources\Quotations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuotationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengirim / Kontak')
                    ->description('Data prospek yang mengirimkan permintaan penawaran harga.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Pengirim')
                            ->placeholder('contoh: Dr. Budi Santoso')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->placeholder('budi.santoso@rs-diagnostik.co.id')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Nomor Telepon / WhatsApp')
                            ->placeholder('0812-3456-7890')
                            ->tel()
                            ->nullable()
                            ->maxLength(50),
                        TextInput::make('company')
                            ->label('Instansi / Perusahaan')
                            ->placeholder('contoh: RS Permata Diagnostika / Lab Klinik Utama')
                            ->nullable()
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Rincian Permintaan Penawaran')
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk Terkait (Konteks Katalog)')
                            ->relationship('product', 'name_id')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        TextInput::make('locale')
                            ->label('Bahasa Pengirim')
                            ->helperText('Bahasa antarmuka yang digunakan saat formulir dikirimkan.')
                            ->disabled()
                            ->dehydrated()
                            ->default('id'),
                        TextInput::make('subject')
                            ->label('Subjek Permintaan')
                            ->placeholder('contoh: Permintaan Penawaran Harga Kit Real-Time PCR')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('message')
                            ->label('Pesan / Kebutuhan Penawaran')
                            ->placeholder('Uraian estimasi kuantitas kebutuhan, spesifikasi khusus, dan jadwal pengiriman...')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Status Penanganan & Catatan Admin')
                    ->description('Kelola status tindak lanjut prospek oleh tim sales ANS.')
                    ->schema([
                        Select::make('status')
                            ->label('Status Penanganan')
                            ->options([
                                'New' => 'Baru (New)',
                                'Contacted' => 'Sudah Dihubungi (Contacted)',
                                'Quoted' => 'Penawaran Dikirim (Quoted)',
                                'Closed' => 'Selesai (Closed)',
                            ])
                            ->default('New')
                            ->required(),
                        Textarea::make('admin_notes')
                            ->label('Catatan Internal Admin')
                            ->placeholder('Catatan riwayat komunikasi via telepon/WhatsApp, tindak lanjut penawaran harga, dll.')
                            ->helperText('Catatan internal tim sales ANS mengenai komunikasi dengan prospek. Catatan ini tidak ditampilkan kepada publik.')
                            ->rows(3)
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
