<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('name_id')
                    ->label('Nama Kategori (ID)')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name_en')
                    ->label('Nama Kategori (EN)')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug_id')
                    ->label('Slug (ID)')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('products_count')
                    ->label('Jumlah Produk')
                    ->counts('products')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
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
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Category $record) {
                        if ($record->products()->count() > 0) {
                            Notification::make()
                                ->danger()
                                ->title('Penghapusan Ditolak')
                                ->body('Kategori ini masih memiliki produk terkait. Pindahkan atau hapus produk terlebih dahulu.')
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
