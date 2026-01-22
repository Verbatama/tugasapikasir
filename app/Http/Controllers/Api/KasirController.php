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

     public function transaksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_bon' => 'required|string|unique:invoices',
            'tanggal' => 'required|date',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.jumlah' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $invoice = Invoice::create([
            'no_bon' => $request->no_bon,
            'tanggal' => $request->tanggal,
            'total_harga' => 0,
        ]);

        $totalHarga = 0;
        foreach ($request->items as $itemData) {
            $item = Item::findOrFail($itemData['id']);
            $totalHarga += $item->harga * $itemData['jumlah'];

            $invoice->items()->attach($item->id, [
                'jumlah' => $itemData['jumlah'],
                'total_harga' => $item->harga * $itemData['jumlah'],
            ]);
        }

        $invoice->update(['total_harga' => $totalHarga]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil',
            'data' => $invoice,
        ]);
    }

    public function lihatTransaksi($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi ditemukan',
            'data' => $invoice,
        ]);
    }


}