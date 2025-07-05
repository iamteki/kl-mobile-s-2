<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;

class BookingStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Booking Status Distribution';
    
    protected static ?int $sort = 3;
    
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $statuses = Booking::selectRaw('booking_status, COUNT(*) as count')
            ->whereMonth('created_at', now()->month)
            ->groupBy('booking_status')
            ->pluck('count', 'booking_status');

        return [
            'datasets' => [
                [
                    'label' => 'Bookings by Status',
                    'data' => [
                        $statuses['pending'] ?? 0,
                        $statuses['confirmed'] ?? 0,
                        $statuses['in_progress'] ?? 0,
                        $statuses['completed'] ?? 0,
                        $statuses['cancelled'] ?? 0,
                    ],
                    'backgroundColor' => [
                        '#F59E0B', // pending - amber
                        '#10B981', // confirmed - green
                        '#3B82F6', // in_progress - blue
                        '#059669', // completed - emerald
                        '#EF4444', // cancelled - red
                    ],
                ],
            ],
            'labels' => ['Pending', 'Confirmed', 'In Progress', 'Completed', 'Cancelled'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}