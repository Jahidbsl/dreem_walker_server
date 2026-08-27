<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ProductsTrendChart extends ChartWidget
{
    protected ?string $heading = 'Products Added (Last 7 Days)';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::now()->subDays($i));

        $counts = $days->map(function ($day) {
            return Product::whereDate('created_at', $day->toDateString())->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Products Created',
                    'data' => $counts->toArray(),
                    'borderColor' => '#f97316',
                    'backgroundColor' => 'rgba(249, 115, 22, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $days->map(fn ($d) => $d->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}