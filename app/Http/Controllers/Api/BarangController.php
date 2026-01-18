<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Http\Resources\BarangResource;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
     public function index()
    {   
        $barangs = Barang::all();
        return new BarangResource(true, 'List Data Barang', $barangs);
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|max:100|unique:barangs',
            'harga'       => 'required|numeric|min:0',
        ]);

        $barang = Barang::create($validator->validated());

        return new BarangResource(true, 'Data Barang Berhasil Ditambahkan', $barang);
    }

    public function show($id)
    {
        $barang = Barang::find($id);

        if (is_null($barang)) {
            return new BarangResource(false, 'Data Barang Tidak Ditemukan', null);
        }
        

        return new BarangResource(true, 'Data Barang Ditemukan', $barang);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (is_null($barang)) {
            return new BarangResource(false, 'Data Barang Tidak Ditemukan', null);
        }

        $validator = Validator::make($request->all(), [
            'nama_barang' => 'sometimes|required|string|max:255',
            'kode_barang' => 'sometimes|required|string|max:100|unique:barangs,kode_barang,' . $id,
            'harga'       => 'sometimes|required|numeric|min:0',
        ]);

        $barang->update($validator->validated());

        return new BarangResource(true, 'Data Barang Berhasil Diupdate', $barang);
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (is_null($barang)) {
            return new BarangResource(false, 'Data Barang Tidak Ditemukan', null);
        }

        $barang->delete();

        return new BarangResource(true, 'Data Barang Berhasil Dihapus', null);
    }
}
