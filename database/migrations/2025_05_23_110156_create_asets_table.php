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
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->foreignId('lokasi_id')->constrained('lokasi')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('vendor')->nullOnDelete();
            $table->date('tanggal_perolehan')->default(now())->nullable();
            $table->decimal('nilai_perolehan', 12, 2)->nullable();
            $table->enum('status', ['tersedia', 'rusak','rusak ringan', 'maintenance', 'hilang', 'dipinjam'])->default('tersedia');
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
