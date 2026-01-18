<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jual;
use App\Models\Barang;
use App\Models\Kasir;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PenjualanResource;
use App\Models\DetailJual;

class PenjualanController extends Controller
{
    /**
     * POST /penjualan
     */
  public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'no_bon'   => 'required|string|unique:juals,no_bon',
        'discount' => 'nullable|numeric|min:0',
        'bayar'    => 'required|numeric|min:0',
        'kasir_id' => 'required|exists:kasirs,id',

        'items'             => 'required|array|min:1',
        'items.*.barang_id' => 'required|exists:barangs,id',
        'items.*.jumlah'    => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return new PenjualanResource(false, $validator->errors(), null);
    }

    $jual = null;

    DB::transaction(function () use ($request, &$jual) {

        $total = 0;

        foreach ($request->items as $item) {
            $barang = Barang::findOrFail($item['barang_id']);
            $total += $barang->harga * $item['jumlah'];
        }

        $discount = $request->discount ?? 0;

        $kasir = Kasir::findOrFail($request->kasir_id);

        $jual = Jual::create([
            'no_bon'    => $request->no_bon,
            'kasir_id'  => $kasir->id,
            'kode_kasir'=> $kasir->kode_kasir,
            'total'     => $total,
            'discount'  => $discount,
            'bayar'     => $request->bayar,
            'kembali'   => $request->bayar - ($total - $discount),
            'tanggal'   => now()->toDateString(),
            'waktu'     => now()->format('H:i:s'),
        ]);

        foreach ($request->items as $item) {
            $barang = Barang::findOrFail($item['barang_id']);

            $jual->detailJual()->create([
                'no_bon'      => $jual->no_bon,          // 🔥 HISTORY
                'barang_id'   => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'harga'       => $barang->harga,
                'jumlah'      => $item['jumlah'],
            ]);
        }
    });

    if (!$jual) {
        return new PenjualanResource(false, 'Gagal menyimpan transaksi', null);
    }

    return new PenjualanResource(
        true,
        'Data Penjualan Berhasil Disimpan',
        $jual->load(['detailJual.barang', 'kasir'])
    );
}



    /**
     * GET /penjualan/{id}
     */
    public function show($id)
    {
        $jual = Jual::with(['detailJual.barang', 'kasir'])->find($id);

        if (!$jual) {
            return new PenjualanResource(false, 'Data Penjualan Tidak Ditemukan', null);
        }

        return new PenjualanResource(
            true,
            'Data Penjualan Ditemukan',
            $jual
        );
    }
}
