<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class CleanAssetLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset-locations:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus data lama di tabel asset_locations jika lebih dari 5000 baris';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = DB::table('asset_locations')->count();

        if ($count > 5000) {
            // Hitung jumlah yang akan dihapus (biar sisa 2000 data terbaru)
            $deleteCount = $count - 2000;

            DB::table('asset_locations')
                ->orderBy('created_at', 'asc') // urutkan dari data paling lama
                ->limit($deleteCount)
                ->delete();

            $this->info("✅ Berhasil menghapus {$deleteCount} data lama. Sisa data: 2000");
        } else {
            $this->info("ℹ️ Data masih aman ({$count} rows), tidak ada yang dihapus.");
        }

        return 0;
    }
}