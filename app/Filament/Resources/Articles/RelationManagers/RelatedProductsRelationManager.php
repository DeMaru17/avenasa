<?php

namespace App\Filament\Resources\Articles\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RelatedProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'relatedProducts';

    protected static ?string $title = 'Produk Terkait (Related Products)';

    /**
     * Custom reorder implementation strictly isolated to the article_product pivot table.
     * This guarantees that products.sort_order is NEVER modified.
     *
     * @param  array<int | string>  $order
     */
    public function reorderTable(array $order, int|string|null $draggedRecordKey = null): void
    {
        DB::transaction(function () use ($order): void {
            $articleId = $this->getOwnerRecord()->getKey();
            foreach ($order as $index => $productId) {
                DB::table('article_product')
                    ->where('article_id', $articleId)
                    ->where('product_id', $productId)
                    ->update([
                        'sort_order' => $index + 1,
                        'updated_at' => now(),
                    ]);
            }
        });
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sort_order')
                    ->label('Urutan Tampilan')
                    ->numeric()
                    ->default(fn () => (DB::table('article_product')
                        ->where('article_id', $this->getOwnerRecord()->getKey())
                        ->max('sort_order') ?? 0) + 1)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name_id')
            ->reorderable('sort_order')
            ->defaultSort('article_product.sort_order', 'asc')
            ->columns([
                ImageColumn::make('primary_image_path')
                    ->label('Foto')
                    ->disk('public'),
                TextColumn::make('name_id')
                    ->label('Nama Produk (ID)')
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
                TextColumn::make('pivot.sort_order')
                    ->label('Urutan Pivot')
                    ->sortable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Hubungkan Produk')
                    ->recordSelectSearchColumns(['name_id', 'name_en'])
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->available())
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampilan')
                            ->helperText('Nomor urut produk pada artikel ini.')
                            ->numeric()
                            ->default(fn () => (DB::table('article_product')
                                ->where('article_id', $this->getOwnerRecord()->getKey())
                                ->max('sort_order') ?? 0) + 1)
                            ->required(),
                    ]),
            ])
            ->recordActions([
                DetachAction::make()->label('Lepas'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()->label('Lepas Terpilih'),
                ]),
            ]);
    }
}
