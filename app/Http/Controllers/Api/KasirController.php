<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kasir;
use App\Http\Resources\KasirResource;
use Illuminate\Support\Facades\Validator;

class KasirController extends Controller
{
    public function index()
    {
        $kasrs = Kasir::all();
        return new KasirResource(true, 'List Data Kasir', $kasrs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kasir' => 'required|string|max:255',
            'kode_kasir' => 'required|string|max:100|unique:kasirs',
        ]);

        $kasir = Kasir::create($validator->validated());

        return new KasirResource(true, 'Data Kasir Berhasil Ditambahkan', $kasir);
    }

    public function show($id)
    {
        $kasir = Kasir::find($id);

        if (is_null($kasir)) {
            return new KasirResource(false, 'Data Kasir Tidak Ditemukan', null);
        }

        return new KasirResource(true, 'Data Kasir Ditemukan', $kasir);
    }

    public function update(Request $request, $id)
    {
        $kasir = Kasir::find($id);

        if (is_null($kasir)) {
            return new KasirResource(false, 'Data Kasir Tidak Ditemukan', null);
        }

        $validator = Validator::make($request->all(), [
            'nama_kasir' => 'sometimes|required|string|max:255',
            'kode_kasir' => 'sometimes|required|string|max:100|unique:kasirs,kode_kasir,' . $id,
        ]);

        $kasir->update($validator->validated());

        return new KasirResource(true, 'Data Kasir Berhasil Diupdate', $kasir);
    }

    public function destroy($id)
    {
        $kasir = Kasir::find($id);

        if (is_null($kasir)) {
            return new KasirResource(false, 'Data Kasir Tidak Ditemukan', null);
        }

        $kasir->delete();

        return new KasirResource(true, 'Data Kasir Berhasil Dihapus', null);
    }
}