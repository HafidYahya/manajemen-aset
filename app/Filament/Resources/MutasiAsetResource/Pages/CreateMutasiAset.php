<?php

namespace App\Filament\Resources\MutasiAsetResource\Pages;

use App\Filament\Resources\MutasiAsetResource;
use App\Models\Aset;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMutasiAset extends CreateRecord
{
    // Override redirect setelah create
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    protected static string $resource = MutasiAsetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil data aset
        $aset = Aset::findOrFail($data['aset_id']);

        // Isi otomatis dari_lokasi & user
        $data['dari_lokasi_id'] = $aset->lokasi_id;
        $data['dipindahkan_oleh'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Update lokasi aset ke lokasi baru
        $this->record->aset->update([
            'lokasi_id' => $this->record->ke_lokasi_id,
        ]);
    }
}
