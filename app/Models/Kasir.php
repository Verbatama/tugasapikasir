<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kasir extends Model
{
    protected $fillable = [
        'nama_kasir',
        'kode_kasir',
    ];

      public function juals(){
        return $this->hasMany(Jual::class,'kasir_id');
    }
}
