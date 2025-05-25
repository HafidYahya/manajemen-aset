<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = 
    [
        'nama',
    ];
    public function asets()
    {
        return $this->hasMany(Aset::class);
        // Relasi one-to-many: 1 kategori punya banyak aset
    }
}
