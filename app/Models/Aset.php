<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Aset extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['kode', 'nama', 'kategori_id', 'lokasi_id', 'vendor_id', 'status'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Aset telah {$eventName}")
            ->useLogName('aset');
    }
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
        'foto',
        'qr_code',
        'gps_imei',
    ];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
    public function kategori() { return $this->belongsTo(Kategori::class); }
    public function lokasi() { return $this->belongsTo(Lokasi::class); }
    public function vendor() { return $this->belongsTo(Vendor::class); }

    public function peminjaman()
    {
        return $this->hasMany(\App\Models\Peminjaman::class);
    }
    public function mutasi()
    {
        return $this->hasMany(MutasiAset::class);
    }

    public function latestLocation()
    {
        return $this->hasOne(AssetLocation::class, 'imei', 'gps_imei')->latestOfMany('upload_time');
    }
    
    protected static function booted()
    {
        static::created(function ($aset) {
            $url = route('scan.aset.show', $aset->id);

            $filename = 'qrcodes/aset-' . $aset->id . '.svg';
            Storage::disk('public')->put($filename, QrCode::format('svg')->size(200)->generate($url));

            $aset->update(['qr_code' => $filename]);
        });
    }

  
}
