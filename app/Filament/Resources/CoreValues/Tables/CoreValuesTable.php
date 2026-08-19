<?php

namespace App\Filament\Resources\CoreValues\Tables;

use App\Filament\Resources\CoreValues\Schemas\CoreValueForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CoreValuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('icon_name')
                    ->label('Ikon')
                    ->icon(fn (?string $state): ?string => ! empty($state) ? "heroicon-o-{$state}" : null)
                    ->formatStateUsing(fn (?string $state): string => CoreValueForm::getIconTitle($state))
                    ->sortable(),
                TextColumn::make('title_id')
                    ->label('Judul Nilai (ID)')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title_en')
                    ->label('Judul Nilai (EN)')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
