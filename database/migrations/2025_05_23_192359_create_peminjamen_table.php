<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->comment('Pemohon');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->enum('status', ['menunggu', 'disetujui','dipinjam','dikembalikan' ,'ditolak'])->default('menunggu');
            $table->text('keterangan')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->comment('Manajer/Petugas');
            $table->timestamp('tanggal_disetujui')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
