<?php

namespace App\Filament\Widgets;

use App\Models\Aset;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengguna', User::count())
                ->icon('heroicon-o-users')
                ->description('Jumlah pengguna terdaftar')
                ->color('success'),
            Stat::make('Total Aset', Aset::count())
                ->icon('heroicon-s-archive-box')
                ->description('Jumlah seluruh aset')
                ->color('success'),
            Stat::make('Tersedia', Aset::where('status', 'tersedia')->count())
                ->icon('heroicon-o-check-badge')
                ->description('Aset siap digunakan')
                ->color('success'),
            
            Stat::make('Rusak', Aset::where('status', 'rusak')->count())
                ->icon('heroicon-o-x-circle')
                ->description('Aset dalam kondisi rusak')
                ->color('danger'),
                
            Stat::make('Rusak Ringan', Aset::where('status', 'rusak ringan')->count())
                ->icon('heroicon-o-exclamation-triangle')
                ->description('Aset perlu perbaikan ringan')
                ->color('warning'),
                
            Stat::make('Maintenance', Aset::where('status', 'maintenance')->count())
                ->icon('heroicon-o-wrench')
                ->description('Aset sedang diperbaiki')
                ->color('info'),
                
            Stat::make('Hilang', Aset::where('status', 'hilang')->count())
                ->icon('heroicon-o-question-mark-circle')
                ->description('Aset tidak ditemukan')
                ->color('gray'),
                
            Stat::make('Dipinjam', Aset::where('status', 'dipinjam')->count())
                ->icon('heroicon-o-arrow-up-on-square')
                ->description('Aset sedang dipinjam')
                ->color('primary'),
        ];
    }
}
