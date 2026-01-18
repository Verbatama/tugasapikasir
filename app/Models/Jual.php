<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jual extends Model
{
    

    protected $fillable = [
        'no_bon',
        'kasir_id',
        'kode_kasir',
        'total',
        'discount',
        'bayar',
        'kembali',
        'tanggal',
        'waktu',
    ];

    public function kasir()
    {
        return $this->belongsTo(Kasir::class);
    }

    public function detailJual()
    {
        return $this->hasMany(DetailJual::class, 'jual_id');
    }
}

