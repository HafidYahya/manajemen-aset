<?php

namespace App\Filament\Pages;

use App\Models\Aset;
use App\Models\AssetLocation;
use Filament\Pages\Page;

class AssetMap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static string $view = 'filament.pages.asset-map';
    protected static ?string $title = 'Peta Lokasi Aset';

    public array $asetData = [];
    public array $asetPaths = [];

    public function mount(): void
    {
        $asets = Aset::whereNotNull('gps_imei')->get();

        foreach ($asets as $aset) {
            $imei = $aset->gps_imei;

            $locations = AssetLocation::where('imei', $imei)
                ->orderBy('upload_time')
                ->get(['latitude', 'longitude']);

            if ($locations->count() < 1) continue;

            // Simpan lintasan
            $this->asetPaths[$imei] = $locations
                ->map(fn ($loc) => [$loc->latitude, $loc->longitude])
                ->toArray();

            // Simpan data aset (lokasi terakhir)
            $last = end($this->asetPaths[$imei]);

            $this->asetData[] = [
                'nama' => $aset->nama,
                'imei' => $imei,
                'color' => $this->generateColorFromIMEI($imei),
                'last' => $last,
            ];
        }
    }

    private function generateColorFromIMEI(string $imei): string
    {
        $hash = substr(md5($imei), 0, 6); // Ambil 6 karakter hex
        return '#' . $hash;
    }
}
