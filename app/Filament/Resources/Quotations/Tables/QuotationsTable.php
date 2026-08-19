<?php

namespace App\Filament\Resources\Quotations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuotationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal Submit')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Pengirim')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company')
                    ->label('Instansi / Perusahaan')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('product.name_id')
                    ->label('Produk Terkait')
                    ->badge()
                    ->color('primary')
                    ->placeholder('Inquiry Umum'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'danger' => 'New',
                        'warning' => 'Contacted',
                        'info' => 'Quoted',
                        'success' => 'Closed',
                    ]),
                TextColumn::make('locale')
                    ->label('Bahasa')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Penanganan')
                    ->options([
                        'New' => 'Baru (New)',
                        'Contacted' => 'Sudah Dihubungi (Contacted)',
                        'Quoted' => 'Penawaran Dikirim (Quoted)',
                        'Closed' => 'Selesai (Closed)',
                    ]),
                SelectFilter::make('product_id')
                    ->label('Produk Terkait')
                    ->relationship('product', 'name_id'),
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
