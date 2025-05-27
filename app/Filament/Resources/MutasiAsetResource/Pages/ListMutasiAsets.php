<?php

namespace App\Filament\Resources\MutasiAsetResource\Pages;

use App\Filament\Resources\MutasiAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMutasiAsets extends ListRecords
{
    protected static string $resource = MutasiAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Mutasi'),
        ];
    }
}
