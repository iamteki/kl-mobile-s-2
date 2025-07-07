<?php

namespace App\Filament\Resources\ServiceProviderResource\Pages;

use App\Filament\Resources\ServiceProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

class EditServiceProvider extends EditRecord
{
    protected static string $resource = ServiceProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle profile image upload if using regular file upload
        if (isset($data['profile_image_upload'])) {
            if ($data['profile_image_upload'] instanceof TemporaryUploadedFile) {
                // Clear existing media
                $this->record->clearMediaCollection('profile');
                
                // Add new media
                $this->record->addMedia($data['profile_image_upload']->getRealPath())
                    ->usingName($this->record->name . ' Profile')
                    ->usingFileName(Str::slug($this->record->name) . '-profile.' . $data['profile_image_upload']->getClientOriginalExtension())
                    ->toMediaCollection('profile');
            }
            
            // Remove the temporary upload field from data
            unset($data['profile_image_upload']);
        }
        
        return $data;
    }

    protected function afterSave(): void
    {
        // If you need to do anything after saving
        // For example, clear cache or dispatch jobs
    }

    protected function getRedirectUrl(): string
    {
        // Redirect to view page after saving
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Service provider updated successfully';
    }

    
}