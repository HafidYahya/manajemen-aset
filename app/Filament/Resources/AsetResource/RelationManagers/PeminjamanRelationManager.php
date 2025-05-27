<?php

namespace App\Filament\Resources\AsetResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

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
                    ->sortable()
                    ->date('l, d F Y'),
                TextColumn::make('tanggal_kembali')
                    ->label('Rencana Kembali')
                    ->sortable()
                    ->date('l, d F Y'),
                TextColumn::make('tanggal_kembali_sesuai')
                    ->label('Tgl. Kembali')
                    ->sortable()
                    ->date('l, d F Y'),
                TextColumn::make('kondisi_kembali')->label('Kondisi'),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'disetujui',
                        'danger' => 'ditolak',
                    ]),
            ])
            ->filters([
                SelectFilter::make('periode')
                    ->options([
                        'hari_ini' => 'Hari Ini',
                        'minggu_ini' => 'Minggu Ini',
                        'bulan_ini' => 'Bulan Ini',
                        'tahun_ini' => 'Tahun Ini',
                        'semua' => 'Semua Data',
                    ])
                    ->default('hari_ini')
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? 'hari_ini';
                        
                        return match ($value) {
                            'hari_ini' => $query->whereDate('tanggal_pinjam', today()),
                            'minggu_ini' => $query->whereBetween('tanggal_pinjam', [
                                now()->startOfWeek(),
                                now()->endOfWeek()
                            ]),
                            'bulan_ini' => $query->whereMonth('tanggal_pinjam', now()->month)
                                ->whereYear('tanggal_pinjam', now()->year),
                            'tahun_ini' => $query->whereYear('tanggal_pinjam', now()->year),
                            default => $query,
                        };
                    }),
            ])
            ->headerActions([
                // ... action lainnya
            ])
            ->actions([
                // ... action lainnya
            ])
            ->bulkActions([
                // ... bulk actions
            ])
            ->defaultSort('created_at', 'desc')
            ->persistFiltersInSession()
            ->persistSearchInSession();
    }
}
