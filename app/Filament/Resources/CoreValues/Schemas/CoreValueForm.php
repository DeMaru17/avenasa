<?php

namespace App\Filament\Resources\CoreValues\Schemas;

use App\Models\CoreValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CoreValueForm
{
    /**
     * Curated list of human-friendly Heroicon options relevant to corporate & laboratory values.
     *
     * @return array<string, array{title: string, description: string}>
     */
    public static function getCuratedIcons(): array
    {
        return [
            // Kualitas, Kepercayaan & Integritas
            'shield-check' => [
                'title' => 'Shield Check',
                'description' => 'Kepatuhan, Kualitas & Integritas',
            ],
            'check-badge' => [
                'title' => 'Check Badge',
                'description' => 'Standar Mutu & Sertifikasi Resmi',
            ],
            'check-circle' => [
                'title' => 'Check Circle',
                'description' => 'Keandalan, Kepastian & Ketepatan',
            ],
            'scale' => [
                'title' => 'Scale',
                'description' => 'Integritas, Etika Bisnis & Keadilan',
            ],
            'document-check' => [
                'title' => 'Document Check',
                'description' => 'Akuntabilitas & Kepatuhan Regulasi',
            ],
            'lock-closed' => [
                'title' => 'Lock Closed',
                'description' => 'Keamanan Informasi & Kerahasiaan',
            ],
            'star' => [
                'title' => 'Star',
                'description' => 'Keunggulan, Reputasi & Layanan Prima',
            ],
            'trophy' => [
                'title' => 'Trophy',
                'description' => 'Pencapaian & Standar Prestasi Tertinggi',
            ],

            // Sains, Laboratorium & Teknologi
            'beaker' => [
                'title' => 'Beaker',
                'description' => 'Laboratorium, Riset & Instrumen Ilmiah',
            ],
            'cpu-chip' => [
                'title' => 'CPU Chip',
                'description' => 'Teknologi Presisi & Otomasi Canggih',
            ],
            'cog' => [
                'title' => 'Cog',
                'description' => 'Efisiensi Operasional & Sistem Terpadu',
            ],
            'wrench-screwdriver' => [
                'title' => 'Wrench & Screwdriver',
                'description' => 'Layanan Purnajual, Pemeliharaan & Servis',
            ],
            'wrench' => [
                'title' => 'Wrench',
                'description' => 'Dukungan Teknis & Problem Solving',
            ],
            'server' => [
                'title' => 'Server',
                'description' => 'Infrastruktur Kuat & Kehandalan Sistem',
            ],
            'command-line' => [
                'title' => 'Command Line',
                'description' => 'Digitalisasi & Otomasi Alur Kerja',
            ],
            'circle-stack' => [
                'title' => 'Circle Stack',
                'description' => 'Manajemen Data & Rekam Jejak Akurat',
            ],
            'academic-cap' => [
                'title' => 'Academic Cap',
                'description' => 'Kompetensi Saintifik & Edukasi Berkelanjutan',
            ],

            // Inovasi & Pertumbuhan
            'light-bulb' => [
                'title' => 'Light Bulb',
                'description' => 'Inovasi, Ide Kreatif & Terobosan Baru',
            ],
            'sparkles' => [
                'title' => 'Sparkles',
                'description' => 'Pembaruan Terus Menerus & Nilai Tambah',
            ],
            'rocket-launch' => [
                'title' => 'Rocket Launch',
                'description' => 'Akselerasi Pertumbuhan & Kecepatan Eksekusi',
            ],
            'bolt' => [
                'title' => 'Bolt',
                'description' => 'Ketangkasan & Tanggap Respons Cepat',
            ],
            'arrow-trending-up' => [
                'title' => 'Arrow Trending Up',
                'description' => 'Kemajuan & Peningkatan Berkesinambungan',
            ],

            // Tim, Kolaborasi & Kemitraan
            'user-group' => [
                'title' => 'User Group',
                'description' => 'Kerja Sama Solid & Sinergi Antar Tim',
            ],
            'users' => [
                'title' => 'Users',
                'description' => 'Kolaborasi Ekosistem & Komunitas Pengguna',
            ],
            'user-plus' => [
                'title' => 'User Plus',
                'description' => 'Pemberdayaan Talenta & Pengembangan SDM',
            ],
            'user' => [
                'title' => 'User',
                'description' => 'Fokus Kebutuhan Pelanggan Individu',
            ],
            'user-circle' => [
                'title' => 'User Circle',
                'description' => 'Kepemimpinan & Karakter Profesional',
            ],
            'hand-raised' => [
                'title' => 'Hand Raised',
                'description' => 'Dedikasi, Komitmen & Tanggung Jawab',
            ],
            'chat-bubble-left-right' => [
                'title' => 'Chat Bubble',
                'description' => 'Komunikasi Terbuka, Responsif & Konsultatif',
            ],
            'heart' => [
                'title' => 'Heart',
                'description' => 'Kepedulian, Empati & Pelayanan Sepenuh Hati',
            ],
            'link' => [
                'title' => 'Link',
                'description' => 'Jejaring Distribusi & Kemitraan Strategis',
            ],
            'share' => [
                'title' => 'Share',
                'description' => 'Sinergi Pengetahuan & Nilai Bersama',
            ],
            'envelope' => [
                'title' => 'Envelope',
                'description' => 'Transparansi & Keterbukaan Komunikasi',
            ],
            'phone' => [
                'title' => 'Phone',
                'description' => 'Aksesibilitas & Kesiapsiagaan Layanan',
            ],

            // Bisnis, Korporat & Distribusi
            'building-office' => [
                'title' => 'Building Office',
                'description' => 'Stabilitas Korporat & Tata Kelola Baik',
            ],
            'building-office-2' => [
                'title' => 'Building Office 2',
                'description' => 'Struktur Perusahaan & Cabang Distribusi',
            ],
            'briefcase' => [
                'title' => 'Briefcase',
                'description' => 'Profesionalisme & Etika Bisnis Tinggi',
            ],
            'presentation-chart-bar' => [
                'title' => 'Presentation Chart Bar',
                'description' => 'Strategi Terukur & Visi Masa Depan',
            ],
            'presentation-chart-line' => [
                'title' => 'Presentation Chart Line',
                'description' => 'Kinerja Berkelanjutan & Pencapaian Target',
            ],
            'chart-bar' => [
                'title' => 'Chart Bar',
                'description' => 'Produktivitas & Hasil Nyata',
            ],
            'chart-pie' => [
                'title' => 'Chart Pie',
                'description' => 'Analisis Data & Keputusan Berbasis Bukti',
            ],

            // Jangkauan & Lingkungan
            'globe-alt' => [
                'title' => 'Globe Alt',
                'description' => 'Jangkauan Luas & Kemitraan Internasional',
            ],
            'map' => [
                'title' => 'Map',
                'description' => 'Jangkauan Jaringan Distribusi Nusantara',
            ],
            'arrow-path' => [
                'title' => 'Arrow Path',
                'description' => 'Keberlanjutan & Siklus Solusi Menyeluruh',
            ],
            'sun' => [
                'title' => 'Sun',
                'description' => 'Optimisme & Masa Depan Kesehatan Indonesia',
            ],
            'cloud' => [
                'title' => 'Cloud',
                'description' => 'Fleksibilitas Solusi & Kemudahan Akses',
            ],
        ];
    }

    /**
     * Get options with strictly sized visual Heroicon SVG and contextual description for the select picker.
     *
     * @return array<string, string>
     */
    public static function getSelectOptions(): array
    {
        $icons = self::getCuratedIcons();
        $options = [];

        foreach ($icons as $identifier => $details) {
            $title = htmlspecialchars($details['title'], ENT_QUOTES, 'UTF-8');
            $desc = htmlspecialchars($details['description'], ENT_QUOTES, 'UTF-8');

            try {
                $svg = svg("heroicon-o-{$identifier}", [
                    'style' => 'width: 1.25rem; height: 1.25rem; min-width: 1.25rem; max-width: 1.25rem; display: inline-block; vertical-align: middle; margin-right: 0.5rem; color: #d97706;',
                    'width' => '20',
                    'height' => '20',
                ])->toHtml();
            } catch (\Throwable) {
                $svg = '';
            }

            $options[$identifier] = "<span style=\"display: inline-flex; align-items: center; line-height: 1.25; vertical-align: middle;\">{$svg} <span><strong>{$title}</strong> <span style=\"font-size: 0.75rem; opacity: 0.75; margin-left: 0.25rem;\">({$desc})</span></span></span>";
        }

        return $options;
    }

    /**
     * Get title for an icon identifier.
     */
    public static function getIconTitle(?string $identifier): string
    {
        if (! $identifier) {
            return '-';
        }

        $icons = self::getCuratedIcons();

        return $icons[$identifier]['title'] ?? $identifier;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Nilai Inti')
                    ->description('Judul nilai inti dalam Bahasa Indonesia dan Bahasa Inggris beserta pemilihan ikon visual representatif.')
                    ->schema([
                        TextInput::make('title_id')
                            ->label('Judul Nilai Inti (ID)')
                            ->placeholder('contoh: Integritas & Kepatuhan')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('title_en')
                            ->label('Judul Nilai Inti (EN)')
                            ->placeholder('e.g., Integrity & Compliance')
                            ->required()
                            ->maxLength(255),
                        Select::make('icon_name')
                            ->label('Ikon Nilai Inti')
                            ->placeholder('Pilih ikon visual...')
                            ->helperText('Pilih representasi visual yang mencerminkan esensi nilai inti perusahaan.')
                            ->options(self::getSelectOptions())
                            ->allowHtml()
                            ->prefixIcon(fn (?string $state) => ! empty($state) ? "heroicon-o-{$state}" : null)
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Uraian & Penjelasan Nilai')
                    ->schema([
                        Textarea::make('description_id')
                            ->label('Uraian Penjelasan (ID)')
                            ->placeholder('Uraian komitmen penerapan nilai inti dalam kegiatan operasional')
                            ->rows(3)
                            ->required(),
                        Textarea::make('description_en')
                            ->label('Uraian Penjelasan (EN)')
                            ->placeholder('Explanation of core value implementation in corporate operations')
                            ->rows(3)
                            ->required(),
                    ])->columns(2),

                Section::make('Pengaturan & Visibilitas')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->helperText('Menentukan posisi urutan nilai inti (1 s.d. 6).')
                            ->numeric()
                            ->default(fn () => (CoreValue::max('sort_order') ?? 0) + 1)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Aktifkan untuk menampilkan nilai inti pada halaman Tentang Kami.')
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
