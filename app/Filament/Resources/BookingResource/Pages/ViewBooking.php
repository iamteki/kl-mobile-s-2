<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('download_invoice')
                ->label('Download Invoice')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->action(function () {
                    // TODO: Generate PDF invoice
                    \Filament\Notifications\Notification::make()
                        ->title('Invoice generation coming soon')
                        ->info()
                        ->send();
                }),
        ];
    }
}