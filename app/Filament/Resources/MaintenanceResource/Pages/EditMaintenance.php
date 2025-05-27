<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use App\Filament\Resources\MaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaintenance extends EditRecord
{
    // Override redirect setelah create
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    protected static string $resource = MaintenanceResource::class;
    protected function afterSave(): void
    {
        if ($this->record->status === 'selesai') {
            $this->record->aset->update(['status' => 'tersedia']);
        } else {
            $this->record->aset->update(['status' => 'maintenance']);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
