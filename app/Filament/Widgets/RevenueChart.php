<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Overview';
    
    protected static ?int $sort = 2;
    
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Trend::model(Booking::class)
            ->between(
                start: now()->subMonths(11)->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perMonth()
            ->sum('total');

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Revenue (LKR)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#9333EA',
                    'borderColor' => '#9333EA',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => 
                Carbon::parse($value->date)->format('M Y')
            ),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}