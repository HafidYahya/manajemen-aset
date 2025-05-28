<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\DB;
use App\Models\AsetRekap;
use App\Models\Lokasi;
use App\Exports\AsetRekapExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class LaporanRekapAset extends Page implements HasTable
{
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole(['Admin', 'Petugas', 'Manajer Aset']);
    }
    protected static ?int $navigationSort = 5;
    use InteractsWithTable;

    public array $data = [];

    public function mount(): void
    {
        $this->data = [
            'total' => \App\Models\Aset::count(),
            'nilaiTotal' => \App\Models\Aset::sum('nilai_perolehan'),
        ];

         
    }
    protected static ?string $navigationIcon = 'heroicon-s-chart-bar';
    protected static ?string $title = 'Laporan Rekap Aset';
    protected static ?string $navigationGroup = 'Laporan';
    protected static string $view = 'filament.pages.laporan-rekap-aset';

    public function table(Tables\Table $table): Tables\Table
{
    return $table
        ->query(AsetRekap::query())
        ->columns([
            Tables\Columns\TextColumn::make('nama')->label('Nama Aset')->searchable(),
            Tables\Columns\TextColumn::make('lokasi_id')
                ->label('Lokasi')
                ->formatStateUsing(fn ($state) => optional(\App\Models\Lokasi::find($state))->ruangan ?? '-'),
            Tables\Columns\TextColumn::make('jumlah')->label('Jumlah')->sortable(),
            Tables\Columns\TextColumn::make('total_nilai')->label('Total Nilai')
                ->money('IDR', true),
            Tables\Columns\BadgeColumn::make('status_utama')
                ->label('Status')
                ->colors([
                    'success' => 'tersedia',
                    'danger' => ['rusak', 'hilang', 'rusak ringan'],
                    'warning' => ['dipinjam', 'maintenance'],
                ]),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('lokasi_id')
                ->label('Filter Lokasi')
                ->options(\App\Models\Lokasi::all()->pluck('ruangan', 'id')),
            Tables\Filters\SelectFilter::make('status_utama')
                ->label('Filter Status')
                ->options([
                    'tersedia' => 'Tersedia',
                    'dipinjam' => 'Dipinjam',
                    'rusak' => 'Rusak',
                    'rusak ringan' => 'Rusak Ringan',
                    'hilang' => 'Hilang',
                    'maintenance' => 'Maintenance',
                ]),
            ]);
}
}
