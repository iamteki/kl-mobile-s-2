<?php

namespace App\Filament\Resources\ServiceProviderResource\Pages;

use App\Filament\Resources\ServiceProviderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceProvider extends CreateRecord
{
    protected static string $resource = ServiceProviderResource::class;

    protected function getRedirectUrl(): string
    {
        // Redirect to view page after creating
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Service provider created successfully';
    }
}