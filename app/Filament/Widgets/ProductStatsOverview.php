<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('All products in store')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),

            Stat::make('Total Categories', Category::count())
                ->description('Product categories')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('info'),

            Stat::make('Total Variants', ProductVariant::count())
                ->description('Size/color combinations')
                ->descriptionIcon('heroicon-m-swatch')
                ->color('warning'),

            Stat::make('Total Stock', ProductVariant::sum('stock'))
                ->description('Units available')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('primary'),
        ];
    }
}