<?php

// namespace App\Filament\Pages;

// use Filament\Pages\Page;
// use App\Models\Aset;
// use Illuminate\Support\Facades\DB;

// class LaporanAset extends Page
// {
//     protected static ?string $navigationIcon = 'heroicon-s-chart-bar';
//     protected static ?string $title = 'Laporan & Rekapitulasi Aset';
//     protected static ?string $navigationGroup = 'Laporan';
//     protected static string $view = 'filament.pages.laporan-aset';

//     public $data;
//     public $rekapNamaLokasi;
//     public $filter_nama = null;
//     public $filter_lokasi = null;
//     public $filter_kategori = null;
//     public $filter_status = null;


//     public function mount()
//     {
//         $this->data = [
//             'total' => Aset::count(),
//             'byKategori' => Aset::selectRaw('kategori_id, COUNT(*) as jumlah')->groupBy('kategori_id')->with('kategori')->get(),
//             'byLokasi' => Aset::selectRaw('lokasi_id, COUNT(*) as jumlah')->groupBy('lokasi_id')->with('lokasi')->get(),
//             'byStatus' => Aset::selectRaw('status, COUNT(*) as jumlah')->groupBy('status')->pluck('jumlah', 'status'),
//             'nilaiTotal' => Aset::sum('nilai_perolehan'),
//         ];
//         $query = \App\Models\Aset::query();

//         if ($this->filter_nama) {
//             $query->where('nama', 'like', '%' . $this->filter_nama . '%');
//         }

//         if ($this->filter_lokasi) {
//             $query->where('lokasi_id', $this->filter_lokasi);
//         }

//         if ($this->filter_kategori) {
//             $query->where('kategori_id', $this->filter_kategori);
//         }

//         if ($this->filter_status) {
//             $query->where('status', $this->filter_status);
//         }

//         $this->rekapNamaLokasi = $query
//             ->selectRaw('
//                 nama, 
//                 lokasi_id, 
//                 COUNT(*) as jumlah, 
//                 SUM(nilai_perolehan) as total_nilai,
//                 MAX(status) as status_utama
//             ')
//             ->groupBy('nama', 'lokasi_id')
//             ->with('lokasi')
//             ->get();

//     }
// }
