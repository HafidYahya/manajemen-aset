<?php

namespace App\Filament\Resources\PeminjamanResource\Pages;

use App\Filament\Resources\PeminjamanResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PermintaanPeminjamanBaru;
use Illuminate\Contracts\Queue\ShouldQueue;


class CreatePeminjaman extends CreateRecord implements ShouldQueue
{
    protected static string $resource = PeminjamanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id(); // Simpan user login sebagai pengaju
        return $data;
    }

    protected function afterCreate(): void
    {
        // Ambil user dengan role "Manajer Aset"
        $manajersDanPetugas = User::role(['Manajer Aset', 'Petugas'])->get();

        // Kirim notifikasi ke manajer aset
        Notification::send($manajersDanPetugas, new PermintaanPeminjamanBaru($this->record));
    }
}
