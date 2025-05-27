<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Maintenance extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['aset_id', 'tanggal_mulai', 'tanggal_selesai', 'status', 'deskripsi', 'dikerjakan_oleh'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Maintenance aset telah {$eventName}")
            ->useLogName('maintenance');
    }
    use HasFactory;
    protected $guarded=[];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'dikerjakan_oleh');
    }
}
