<?php

namespace App\Filament\Resources\AsetResource\RelationManagers;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class MaintenanceRelationManager extends RelationManager
{
    protected static string $relationship = 'maintenances';
    protected static ?string $title = 'Riwayat Maintenance';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_mulai')->label('Mulai')->date('l, d F Y'),
                TextColumn::make('tanggal_selesai')->label('Selesai')->date('l, d F Y')->default('-'),
                TextColumn::make('status')->badge()->label('Status'),
                TextColumn::make('penanggungJawab.name')->label('penanggungJawab')->default('-'),
                TextColumn::make('deskripsi')->label('Catatan')->limit(50)->wrap(),
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
                            'hari_ini' => $query->whereDate('tanggal_mulai', today()),
                            'minggu_ini' => $query->whereBetween('tanggal_mulai', [
                                now()->startOfWeek(),
                                now()->endOfWeek()
                            ]),
                            'bulan_ini' => $query->whereMonth('tanggal_mulai', now()->month)
                                ->whereYear('tanggal_mulai', now()->year),
                            'tahun_ini' => $query->whereYear('tanggal_mulai', now()->year),
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
