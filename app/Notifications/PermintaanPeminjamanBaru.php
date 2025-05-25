<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as BaseNotification;
use App\Models\Peminjaman;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class PermintaanPeminjamanBaru extends BaseNotification implements ShouldQueue
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
            ->title('Permintaan Peminjaman Baru')
            ->body("Permintaan oleh {$this->peminjaman->user->name} untuk aset: {$this->peminjaman->aset->nama}")
            ->info()
            ->actions([
                Action::make('Lihat')
                    ->url(route('filament.admin.resources.peminjamen.index')),
            ])
            ->getDatabaseMessage(); // Penting untuk ikon lonceng di Filament
    }
}
