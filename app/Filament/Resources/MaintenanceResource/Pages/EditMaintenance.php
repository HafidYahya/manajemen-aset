<?php

namespace App\Filament\Resources\MaintenanceResource\Pages;

use App\Filament\Resources\MaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;

class EditMaintenance extends EditRecord
{
    // Override redirect setelah create
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    protected static string $resource = MaintenanceResource::class;
    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('status')
                ->label('Status Maintenance')
                ->options([
                    'proses' => 'Dalam Proses',
                    'selesai' => 'Selesai',
                ])
                ->required(),
        ]);
    }
    

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
            // Actions\DeleteAction::make(),
        ];
    }
}
