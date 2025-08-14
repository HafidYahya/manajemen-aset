<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetLocation extends Model
{
    protected $guarded = [];


    public function aset()
    {
        return $this->belongsTo(Aset::class, 'imei', 'gps_imei');
    }
}
