<?php

namespace App\Filament\Resources\CompanyProfiles\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompanyProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tagline_id')
                    ->label('Slogan (ID)')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email Resmi')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon'),
                TextColumn::make('whatsapp')
                    ->label('WhatsApp'),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                // Non-deletable singleton per SPEC-02
            ]);
    }
}
