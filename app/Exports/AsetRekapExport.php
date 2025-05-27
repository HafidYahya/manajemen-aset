<?php

namespace App\Exports;

use App\Models\AsetRekap;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AsetRekapExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return AsetRekap::all([
            'nama',
            'lokasi_id',
            'jumlah',
            'total_nilai',
            'status_utama'
        ])->map(function ($item) {
            $item->lokasi = optional($item->lokasi)->ruangan;
            return [
                $item->nama,
                $item->lokasi,
                $item->jumlah,
                $item->total_nilai,
                $item->status_utama,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Aset', 'Lokasi', 'Jumlah', 'Total Nilai', 'Status'];
    }
}
