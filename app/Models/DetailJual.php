<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailJual extends Model
{
 

    protected $fillable = [
        'barang_id',
        'jual_id',
        'kode_barang',
        'no_bon',
        'harga',
        'jumlah',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function jual()
    {
        return $this->belongsTo(Jual::class, 'jual_id');
    }
}
