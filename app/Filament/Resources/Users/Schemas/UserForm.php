<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun Pengguna')
                    ->description('Kelola identitas dan kredensial login akun administrator CMS.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->placeholder('contoh: Administrator ANS')
                            ->required()
                            ->maxLength(255)
                            ->validationMessages([
                                'required' => 'Nama pengguna wajib diisi.',
                                'max' => 'Nama pengguna tidak boleh lebih dari 255 karakter.',
                            ]),
                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->placeholder('admin@avenasa.co.id')
                            ->email()
                            ->required()
                            ->unique(User::class, ignoreRecord: true)
                            ->maxLength(255)
                            ->validationMessages([
                                'required' => 'Alamat email wajib diisi.',
                                'email' => 'Masukkan alamat email yang valid.',
                                'unique' => 'Email tersebut sudah digunakan oleh pengguna lain.',
                                'max' => 'Alamat email tidak boleh lebih dari 255 karakter.',
                            ]),
                        TextInput::make('password')
                            ->label('Kata Sandi (Password)')
                            ->password()
                            ->helperText('Biarkan kosong saat mengedit data jika tidak ingin mengubah kata sandi.')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->confirmed()
                            ->maxLength(255)
                            ->validationMessages([
                                'required' => 'Kata sandi wajib diisi.',
                                'confirmed' => 'Konfirmasi kata sandi tidak sama dengan kata sandi.',
                                'max' => 'Kata sandi tidak boleh lebih dari 255 karakter.',
                            ]),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Kata Sandi')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(false)
                            ->maxLength(255)
                            ->validationMessages([
                                'required' => 'Konfirmasi kata sandi wajib diisi.',
                                'max' => 'Konfirmasi kata sandi tidak boleh lebih dari 255 karakter.',
                            ]),
                    ])->columns(2),
            ]);
    }
}
