<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Models\Article;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama (Basic Information)')
                    ->description('Judul, ringkasan, tipe artikel, dan gambar sampul. URL slug dibuat secara otomatis dari judul.')
                    ->schema([
                        TextInput::make('title_id')
                            ->label('Judul Artikel (ID)')
                            ->placeholder('contoh: Peresmian Fasilitas Gudang Baru ANS')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('title_en')
                            ->label('Article Title (EN)')
                            ->placeholder('e.g., Opening of the New ANS Warehouse Facility')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('excerpt_id')
                            ->label('Ringkasan Singkat (ID)')
                            ->placeholder('Uraian singkat 1-2 kalimat untuk kartu artikel dan meta deskripsi SEO...')
                            ->rows(3)
                            ->nullable(),
                        Textarea::make('excerpt_en')
                            ->label('Brief Summary (EN)')
                            ->placeholder('A concise 1-2 sentence summary for article cards and SEO meta description...')
                            ->rows(3)
                            ->nullable(),
                        Select::make('type')
                            ->label('Tipe Artikel')
                            ->options([
                                'News' => 'News',
                                'Event' => 'Event',
                                'Product Update' => 'Product Update',
                                'Company Update' => 'Company Update',
                            ])
                            ->default('News')
                            ->required(),
                        FileUpload::make('cover_image_path')
                            ->label('Foto Sampul (Cover Image)')
                            ->helperText('Format JPG, PNG, atau WebP. Maksimal 5 MB. Ditampilkan pada kartu artikel, beranda, dan header detail.')
                            ->image()
                            ->disk('public')
                            ->directory('articles/covers')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->nullable(),
                    ])->columns(2),

                Section::make('Konten Artikel Dwibahasa (Rich Content)')
                    ->description('Tulis konten artikel lengkap dengan dukungan heading, gambar inline, daftar, dan pemformatan teks.')
                    ->schema([
                        RichEditor::make('content_id')
                            ->label('Konten Artikel (ID)')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('articles/editor')
                            ->fileAttachmentsMaxSize(5120)
                            ->fileAttachmentsAcceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->columnSpanFull(),
                        RichEditor::make('content_en')
                            ->label('Article Content (EN)')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('articles/editor')
                            ->fileAttachmentsMaxSize(5120)
                            ->fileAttachmentsAcceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->columnSpanFull(),
                    ]),

                Section::make('Produk Terkait (Related Products)')
                    ->description('Hubungkan artikel ini dengan produk katalog ANS. Pada halaman Edit, urutan tampilan produk dapat diatur melalui tabel visual di bawah.')
                    ->schema([
                        Select::make('relatedProducts')
                            ->label('Pilih Produk Terkait')
                            ->relationship('relatedProducts', 'name_id')
                            ->multiple()
                            ->searchable()
                            ->preload(false)
                            ->helperText('Cari dan pilih satu atau beberapa produk dari katalog. Produk terkait akan muncul di akhir artikel publik.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan Publikasi (Publishing Settings)')
                    ->schema([
                        DateTimePicker::make('published_at')
                            ->label('Waktu Publikasi (Published At)')
                            ->helperText('Kosongkan untuk menyimpan sebagai draft. Artikel hanya tampil ke publik jika waktu publikasi telah tiba.')
                            ->nullable()
                            ->default(null),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Nonaktifkan untuk menyembunyikan artikel dari seluruh halaman publik.')
                            ->default(true)
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('Artikel Unggulan (Featured)')
                            ->helperText('Tandai sebagai artikel unggulan perusahaan.')
                            ->default(false),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->helperText('Angka lebih kecil tampil lebih awal pada daftar terurut.')
                            ->numeric()
                            ->default(fn () => (Article::max('sort_order') ?? 0) + 1)
                            ->required(),
                    ])->columns(4),
            ]);
    }
}
