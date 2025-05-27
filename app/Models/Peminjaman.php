<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Peminjaman extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'aset_id', 'user_id', 'status', 'tanggal_pinjam',
                'tanggal_kembali', 'tanggal_kembali_sesuai', 'kondisi_kembali',
                'disetujui_oleh', 'tanggal_disetujui', 'diterima_oleh'
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Peminjaman aset telah {$eventName}")
            ->useLogName('peminjaman');
    }
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
