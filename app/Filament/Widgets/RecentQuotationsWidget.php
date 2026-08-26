<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Quotations\QuotationResource;
use App\Models\Quotation;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentQuotationsWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Inquiries')
            ->description('Latest quotation and consultation requests submitted by website visitors.')
            ->query(
                Quotation::query()->with('product')->latest()
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10])
            ->columns([
                TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Sender Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company')
                    ->label('Company / Institution')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('product.name_id')
                    ->label('Product Context')
                    ->badge()
                    ->color('primary')
                    ->placeholder('General Inquiry'),
                TextColumn::make('subject')
                    ->label('Subject')
                    ->limit(35)
                    ->tooltip(fn (Quotation $record): ?string => $record->subject),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'danger' => 'New',
                        'warning' => 'Contacted',
                        'info' => 'Quoted',
                        'success' => 'Closed',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn (Quotation $record): string => QuotationResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
