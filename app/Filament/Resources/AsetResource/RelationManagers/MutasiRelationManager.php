<?php

namespace App\Filament\Resources\AsetResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MutasiRelationManager extends RelationManager
{
    protected static string $relationship = 'mutasi';
    protected static ?string $title = 'Riwayat Mutasi';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dariLokasi.ruangan')->label('Dari'),
                TextColumn::make('keLokasi.ruangan')->label('Ke'),
                TextColumn::make('tanggal_mutasi')->label('Tanggal')->date('l, d F Y'),
                TextColumn::make('user.name')->label('Dipindahkan Oleh'),
                TextColumn::make('keterangan')->limit(30)->wrap(),
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
                            'hari_ini' => $query->whereDate('tanggal_mutasi', today()),
                            'minggu_ini' => $query->whereBetween('tanggal_mutasi', [
                                now()->startOfWeek(),
                                now()->endOfWeek()
                            ]),
                            'bulan_ini' => $query->whereMonth('tanggal_mutasi', now()->month)
                                ->whereYear('tanggal_mutasi', now()->year),
                            'tahun_ini' => $query->whereYear('tanggal_mutasi', now()->year),
                            default => $query,
                        };
                    }),
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->persistFiltersInSession()
            ->persistSearchInSession();
    }
}
