<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('primary_image_path')
                    ->label('Foto')
                    ->disk('public'),
                TextColumn::make('name_id')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name_id')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name_id'),
                SelectFilter::make('brand_id')
                    ->label('Brand / Principal')
                    ->relationship('brand', 'name'),
                TernaryFilter::make('is_featured')
                    ->label('Status Unggulan'),
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Product $record) {
                        if ($record->quotations()->count() > 0) {
                            Notification::make()
                                ->danger()
                                ->title('Penghapusan Ditolak')
                                ->body('Produk ini memiliki riwayat permintaan penawaran harga (Quotation). Nonaktifkan status produk alih-alih menghapus.')
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
