<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Quotations\QuotationResource;
use App\Models\Quotation;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuotationStatusOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Inquiry Workflow Pipeline';

    protected ?string $description = 'Real-time breakdown of quotation requests across handling stages.';

    protected function getStats(): array
    {
        $newCount = Quotation::where('status', 'New')->count();
        $contactedCount = Quotation::where('status', 'Contacted')->count();
        $quotedCount = Quotation::where('status', 'Quoted')->count();
        $closedCount = Quotation::where('status', 'Closed')->count();

        return [
            Stat::make('New (Baru)', $newCount)
                ->description('Pending initial contact')
                ->descriptionIcon(Heroicon::OutlinedInboxArrowDown)
                ->color('danger')
                ->url(QuotationResource::getUrl('index')),

            Stat::make('Contacted (Dihubungi)', $contactedCount)
                ->description('Under active communication')
                ->descriptionIcon(Heroicon::OutlinedPhone)
                ->color('warning')
                ->url(QuotationResource::getUrl('index')),

            Stat::make('Quoted (Penawaran Dikirim)', $quotedCount)
                ->description('Formal quote provided')
                ->descriptionIcon(Heroicon::OutlinedDocumentText)
                ->color('info')
                ->url(QuotationResource::getUrl('index')),

            Stat::make('Closed (Selesai)', $closedCount)
                ->description('Deal completed / concluded')
                ->descriptionIcon(Heroicon::OutlinedCheckBadge)
                ->color('success')
                ->url(QuotationResource::getUrl('index')),
        ];
    }
}
