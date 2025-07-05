<?php

namespace App\Filament\Resources\BookingResource\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStats extends BaseWidget
{
    protected function getStats(): array
    {
        $todayBookings = Booking::whereDate('created_at', today())->count();
        $pendingBookings = Booking::where('booking_status', 'pending')->count();
        $todayRevenue = Booking::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total');
        $monthRevenue = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('payment_status', 'paid')
            ->sum('total');

        return [
            Stat::make('Today\'s Bookings', $todayBookings)
                ->description('New bookings today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
                
            Stat::make('Pending Bookings', $pendingBookings)
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Today\'s Revenue', 'LKR ' . number_format($todayRevenue))
                ->description('Total revenue today')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Month Revenue', 'LKR ' . number_format($monthRevenue))
                ->description(now()->format('F') . ' total')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
        ];
    }
}