<?php

namespace App\Filament\Resources\CompanyProfiles\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Slogan & Narasi Perusahaan')
                    ->description('Slogan resmi dan narasi sejarah/operasional dalam Bahasa Indonesia dan Bahasa Inggris.')
                    ->schema([
                        TextInput::make('tagline_id')
                            ->label('Slogan Resmi (ID)')
                            ->placeholder('contoh: Memberdayakan Sains untuk Masa Depan Sejahtera')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tagline_en')
                            ->label('Slogan Resmi (EN)')
                            ->placeholder('e.g., Empowering Science for a Prosperous Future')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('about_id')
                            ->label('Profil & Sejarah Perusahaan (ID)')
                            ->placeholder('Uraian lengkap sejarah pendirian, visi operasional, dan komitmen PT Abhipraya Nawasena Sejahtera...')
                            ->rows(4)
                            ->required(),
                        Textarea::make('about_en')
                            ->label('Profil & Sejarah Perusahaan (EN)')
                            ->placeholder('Full operational profile, establishment history, and corporate commitments of PT Abhipraya Nawasena Sejahtera...')
                            ->rows(4)
                            ->required(),
                    ])->columns(2),

                Section::make('Visi & Misi Perusahaan')
                    ->description('Pernyataan visi dan poin-poin misi resmi perusahaan.')
                    ->schema([
                        Textarea::make('vision_id')
                            ->label('Pernyataan Visi (ID)')
                            ->placeholder('contoh: Menjadi mitra distribusi ilmiah terkemuka dan terpercaya di Indonesia...')
                            ->rows(3)
                            ->required(),
                        Textarea::make('vision_en')
                            ->label('Pernyataan Visi (EN)')
                            ->placeholder('e.g., To become the leading and most trusted scientific distribution partner in Indonesia...')
                            ->rows(3)
                            ->required(),
                        Textarea::make('mission_id')
                            ->label('Poin Misi Perusahaan (ID)')
                            ->placeholder("1. Menyediakan produk diagnostik berkualitas tinggi.\n2. Memberikan layanan teknis profesional.")
                            ->rows(5)
                            ->required(),
                        Textarea::make('mission_en')
                            ->label('Poin Misi Perusahaan (EN)')
                            ->placeholder("1. Providing high-quality diagnostic products.\n2. Delivering professional technical support.")
                            ->rows(5)
                            ->required(),
                    ])->columns(2),

                Section::make('Informasi Kontak & Lokasi Kantor')
                    ->description('Alamat kantor resmi, nomor kontak, email, dan embed Google Maps.')
                    ->schema([
                        Textarea::make('address')
                            ->label('Alamat Kantor Resmi')
                            ->placeholder('contoh: Mensana Tower Lt. 15, Jl. Raya Kranggan, Jatisampurna, Bekasi, Jawa Barat')
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('phone')
                            ->label('Nomor Telepon Kantor')
                            ->placeholder('(021) 39722772')
                            ->tel()
                            ->required()
                            ->maxLength(50),
                        TextInput::make('whatsapp')
                            ->label('Nomor WhatsApp Resmi')
                            ->placeholder('0822-614-614-00')
                            ->tel()
                            ->required()
                            ->maxLength(50),
                        TextInput::make('email')
                            ->label('Email Resmi')
                            ->placeholder('admin@avenasa.co.id')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Textarea::make('maps_embed_url')
                            ->label('URL Embed Google Maps')
                            ->placeholder('https://www.google.com/maps/embed?pb=...')
                            ->helperText('Tautan iframe embed dari Google Maps untuk ditampilkan pada halaman Kontak.')
                            ->rows(2)
                            ->nullable()
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }
}
