<?php

use Illuminate\Support\Facades\Route;
use App\Exports\AsetRekapExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Aset;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/export/aset-rekap/excel', function () {
    return Excel::download(new AsetRekapExport, 'laporan_aset.xlsx');
})->name('export.rekap.excel');

Route::get('/export/aset-rekap/pdf', function () {
    $data = \App\Models\AsetRekap::with('lokasi')->get();

    $pdf = Pdf::loadView('exports.aset-rekap-pdf', compact('data'));
    return $pdf->download('laporan_aset.pdf');
})->name('export.rekap.pdf');

Route::get('/scan/aset/{id}', function ($id) {
    $aset = Aset::with('lokasi')->findOrFail($id);
    return view('scan.show', compact('aset'));
})->name('scan.aset.show');

Route::get('/aset/{id}/qr-label', function ($id) {
    $aset = Aset::findOrFail($id);

    $pdf = Pdf::loadView('exports.qr-label', compact('aset'))
        ->setPaper([0, 0, 200, 120], 'portrait'); // label ukuran kecil (mm)

    return $pdf->download('label_qr_' . $aset->kode . '.pdf');
})->name('aset.qr.label');
