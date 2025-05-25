<?php

namespace App\Notifications;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class PeminjamanDitolakNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Peminjaman $peminjaman) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return Notification::make()
            ->title('Peminjaman Ditolak')
            ->body("Peminjaman aset '{$this->peminjaman->aset->nama}' ditolak.")
            ->success()
            ->actions([
                Action::make('Lihat')
                    ->url(route('filament.admin.resources.peminjamen.index')),
            ])
            ->getDatabaseMessage();
    }
}
