<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\ProductImage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Galeri Foto Produk';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image_path')
                    ->label('Foto Galeri')
                    ->helperText('Format JPG, PNG, atau WebP, maksimal 2 MB.')
                    ->image()
                    ->disk('public')
                    ->directory('products/gallery')
                    ->maxSize(2048)
                    ->required(),
                TextInput::make('caption_id')
                    ->label('Keterangan Foto (ID)')
                    ->placeholder('contoh: Tampak Samping / Aksesori')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('caption_en')
                    ->label('Keterangan Foto (EN)')
                    ->placeholder('e.g., Side View / Accessories')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('sort_order')
                    ->label('Urutan Tampilan')
                    ->helperText('Menentukan posisi urutan foto pada galeri detail produk.')
                    ->numeric()
                    ->default(fn () => (ProductImage::where('product_id', $this->getOwnerRecord()->getKey())->max('sort_order') ?? 0) + 1)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('image_path')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Foto')
                    ->disk('public'),
                TextColumn::make('caption_id')
                    ->label('Keterangan (ID)')
                    ->searchable(),
                TextColumn::make('caption_en')
                    ->label('Keterangan (EN)')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
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
