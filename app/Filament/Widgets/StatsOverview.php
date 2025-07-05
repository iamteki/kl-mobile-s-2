<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ServiceProvider;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        // Today's stats
        $todayBookings = Booking::whereDate('created_at', today())->count();
        $todayRevenue = Booking::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total');
            
        // Month stats
        $monthBookings = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $monthRevenue = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('payment_status', 'paid')
            ->sum('total');
            
        // Calculate trends (comparing to last month)
        $lastMonthRevenue = Booking::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('payment_status', 'paid')
            ->sum('total');
            
        $revenueTrend = $lastMonthRevenue > 0 
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;
            
        // Active counts
        $activeCustomers = Customer::count();
        $activeProducts = Product::where('status', 'active')->count();
        $activeProviders = ServiceProvider::where('status', 'active')->count();

        return [
            Stat::make('Today\'s Revenue', 'LKR ' . number_format($todayRevenue))
                ->description($todayBookings . ' bookings today')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart([7, 3, 4, 5, 6, 8, 12])
                ->color('success'),
                
            Stat::make('Monthly Revenue', 'LKR ' . number_format($monthRevenue))
                ->description($revenueTrend > 0 ? $revenueTrend . '% increase' : abs($revenueTrend) . '% decrease')
                ->descriptionIcon($revenueTrend > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart([65, 70, 75, 78, 82, 85, 90])
                ->color($revenueTrend > 0 ? 'success' : 'danger'),
                
            Stat::make('Total Bookings', number_format($monthBookings))
                ->description('This month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
                
            Stat::make('Active Inventory', number_format($activeProducts + $activeProviders))
                ->description($activeProducts . ' products, ' . $activeProviders . ' providers')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),
        ];
    }
}