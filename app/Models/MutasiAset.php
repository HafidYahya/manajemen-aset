<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Aset;
use App\Models\Lokasi;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MutasiAset extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'aset_id', 'dari_lokasi_id', 'ke_lokasi_id', 'tanggal_mutasi', 'user_id', 'keterangan'
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Mutasi aset telah {$eventName}")
            ->useLogName('mutasi');
    }
    protected $guarded=[];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function dariLokasi()
    {
        return $this->belongsTo(Lokasi::class, 'dari_lokasi_id');
    }

    public function keLokasi()
    {
        return $this->belongsTo(Lokasi::class, 'ke_lokasi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dipindahkan_oleh');
    }

}
