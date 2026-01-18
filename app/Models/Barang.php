<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'harga',
    ];

    // Relasi: Barang muncul di banyak detail jual
    public function detailJuals()
    {
        return $this->hasMany(DetailJual::class);
    }
}
