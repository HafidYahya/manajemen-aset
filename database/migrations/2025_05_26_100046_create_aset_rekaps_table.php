<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW aset_rekaps AS
            SELECT 
                ROW_NUMBER() OVER () as id,
                nama,
                lokasi_id,
                COUNT(*) AS jumlah,
                SUM(nilai_perolehan) AS total_nilai,
                MAX(status) AS status_utama
            FROM asets
            GROUP BY nama, lokasi_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS aset_rekaps");
    }
};
