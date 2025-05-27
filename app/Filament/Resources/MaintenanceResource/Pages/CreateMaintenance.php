<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use App\Filament\Resources\MaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenance extends CreateRecord
{
    // Override redirect setelah create
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    protected static string $resource = MaintenanceResource::class;

    protected function afterCreate(): void
    {
        // Ubah status aset menjadi maintenance
        $this->record->aset->update(['status' => 'maintenance']);
    }
}
