<?php

namespace App\Filament\Widgets;

use App\Models\Aset;
use App\Models\Kategori;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class KaryawanWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return Auth::user()->hasRole('Karyawan');
    }
    
    protected function getStats(): array
    {
        $stats = [];
        $categories = Kategori::whereHas('asets', function($query) {
            $query->where('status', 'tersedia');
        })->withCount(['asets' => function($query) {
            $query->where('status', 'tersedia');
        }])->get();

        foreach ($categories as $category) {
            $stats[] = Stat::make($category->nama, $category->asets_count)
                ->description("Aset tersedia")
                ->icon($this->getCategoryIcon($category->nama))
                ->color('success')
                ->chart($this->getCategoryTrend($category->id));
        }
        
        return $stats;
    }
    protected function getCategoryIcon(string $categoryName): string
    {
        return match(strtolower($categoryName)) {
            'laptop' => 'heroicon-o-computer-desktop',
            'monitor' => 'heroicon-o-computer-desktop',
            default => 'heroicon-o-cube'
        };
    }
    protected function getCategoryTrend(int $categoryId): array
    {
        // Contoh data trend (bisa disesuaikan dengan data historis)
        return [5, 10, 12, 8, 15, 17, 20];
    }
}
