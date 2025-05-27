<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lokasi;

class AsetRekap extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $table = 'aset_rekaps';

    // View biasanya tidak memiliki kolom timestamps
    public $timestamps = false;

    // Non-editable by default
    protected $guarded = [];

    // Jika ingin memastikan tidak bisa create/update/delete
    public static function booted()
    {
        static::saving(fn () => false);
        static::creating(fn () => false);
        static::updating(fn () => false);
        static::deleting(fn () => false);
    }

    /**
     * Relasi ke Lokasi (untuk keperluan tampilan ruangan/gedung)
     */
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
}
