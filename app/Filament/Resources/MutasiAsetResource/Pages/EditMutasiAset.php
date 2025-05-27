<?php

namespace App\Filament\Resources\MutasiAsetResource\Pages;

use App\Filament\Resources\MutasiAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMutasiAset extends EditRecord
{
    protected static string $resource = MutasiAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
