<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class MostRequestedProductsWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Most Requested Products')
            ->description('Products with the highest inquiry interest from prospective clients.')
            ->query(
                Product::query()
                    ->has('quotations')
                    ->with(['brand', 'category'])
                    ->withCount('quotations')
                    ->orderByDesc('quotations_count')
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5])
            ->emptyStateHeading('No Product Inquiries Yet')
            ->emptyStateDescription('When clients request quotes for specific catalog items, they will appear here.')
            ->emptyStateIcon(Heroicon::OutlinedCube)
            ->columns([
                TextColumn::make('name_id')
                    ->label('Product Name')
                    ->limit(25)
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->label('Partner Brand')
                    ->placeholder('-'),
                TextColumn::make('quotations_count')
                    ->label('Inquiries')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Product $record): string => ProductResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
