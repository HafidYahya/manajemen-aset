<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $fillable = [
        'kode', 
        'nama', 
        'kategori_id', 
        'lokasi_id',
        'vendor_id', 
        'tanggal_perolehan', 
        'nilai_perolehan',
        'status', 
        'deskripsi', 
        'foto'
    ];

    public function kategori() { return $this->belongsTo(Kategori::class); }
    public function lokasi() { return $this->belongsTo(Lokasi::class); }
    public function vendor() { return $this->belongsTo(Vendor::class); }

    public function peminjaman()
{
    return $this->hasMany(\App\Models\Peminjaman::class);
}

  
}
