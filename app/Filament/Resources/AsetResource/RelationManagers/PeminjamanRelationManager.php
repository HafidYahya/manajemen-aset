<?php

namespace App\Filament\Resources\AsetResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class PeminjamanRelationManager extends RelationManager
{
    protected static string $relationship = 'peminjaman';
    protected static ?string $title = 'Riwayat Peminjaman';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Nama Peminjam'),
                TextColumn::make('tanggal_pinjam')
                    ->label('Tgl. Pinjam')
                    ->date('l, d F Y'),
                TextColumn::make('tanggal_kembali')
                    ->label('Rencana Kembali')
                    ->date('l, d F Y'),
                TextColumn::make('tanggal_kembali_sesuai')
                    ->label('Tgl. Kembali')
                    ->date('l, d F Y'),
                TextColumn::make('kondisi_kembali')->label('Kondisi'),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'disetujui',
                        'danger' => 'ditolak',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
