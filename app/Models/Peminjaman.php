<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamen';


    protected $fillable = [
        'user_id',
        'aset_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'keterangan',
        'status',
        'disetujui_oleh',
        'tanggal_disetujui',
        'tanggal_kembali_sesuai',
        'kondisi_kembali',
        'diterima_oleh',
    ];
    
    public function aset() {
        return $this->belongsTo(Aset::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function disetujuiOleh() {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
