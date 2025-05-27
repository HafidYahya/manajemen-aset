<?php

use Illuminate\Support\Facades\Route;
use App\Exports\AsetRekapExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

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


