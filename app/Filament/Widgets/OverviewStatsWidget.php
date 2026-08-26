<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Brands\BrandResource;
use App\Filament\Resources\Clients\ClientResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Quotations\QuotationResource;
use App\Models\Brand;
use App\Models\Client;
use App\Models\Product;
use App\Models\Quotation;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $activeProductsCount = Product::where('is_active', true)->count();
        $activeBrandsCount = Brand::where('is_active', true)->count();
        $activeClientsCount = Client::where('is_active', true)->count();
        $totalInquiriesCount = Quotation::count();
        $newInquiriesCount = Quotation::where('status', 'New')->count();

        return [
            Stat::make('Active Products', $activeProductsCount)
                ->description('Published in public catalog')
                ->descriptionIcon(Heroicon::OutlinedCube)
                ->color('success')
                ->url(ProductResource::getUrl('index')),

            Stat::make('Business Partners', $activeBrandsCount)
                ->description('Active global partner brands')
                ->descriptionIcon(Heroicon::OutlinedBuildingOffice2)
                ->color('info')
                ->url(BrandResource::getUrl('index')),

            Stat::make('Clients', $activeClientsCount)
                ->description('Active institutional clients')
                ->descriptionIcon(Heroicon::OutlinedUserGroup)
                ->color('primary')
                ->url(ClientResource::getUrl('index')),

            Stat::make('Total Inquiries', $totalInquiriesCount)
                ->description('All recorded quotations')
                ->descriptionIcon(Heroicon::OutlinedChatBubbleBottomCenterText)
                ->color('gray')
                ->url(QuotationResource::getUrl('index')),

            Stat::make('New Inquiries', $newInquiriesCount)
                ->description($newInquiriesCount > 0 ? 'Requires follow up' : 'All inquiries reviewed')
                ->descriptionIcon($newInquiriesCount > 0 ? Heroicon::OutlinedExclamationCircle : Heroicon::OutlinedCheckCircle)
                ->color($newInquiriesCount > 0 ? 'danger' : 'success')
                ->url(QuotationResource::getUrl('index')),
        ];
    }
}
