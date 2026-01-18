<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class PenjualanResource extends JsonResource
{
    public $status;
    public $message;

    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
    }

    public function toArray(Request $request): array
{
    // 🔥 JIKA RESOURCE NULL → JANGAN AKSES PROPERTI MODEL
    if ($this->resource === null) {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => null
        ];
    }

    return [
        'success' => $this->status,
        'message' => $this->message,
        'data' => [
            'no_bon' => $this->no_bon,

            'tanggal' => $this->created_at?->format('Y-m-d'),
            'waktu'   => $this->created_at?->format('H:i:s'),

            'kasir' => [
                'kode_kasir' => $this->kasir?->kode_kasir,
                'nama_kasir' => $this->kasir?->nama_kasir,
            ],

            'ringkasan' => [
                'total'    => $this->total,
                'discount' => $this->discount,
                'bayar'    => $this->bayar,
                'kembali'  => $this->kembali,
            ],

            'items' => $this->detailJual->map(function ($item) {
                return [
                    
                    'kode_barang' => $item->kode_barang,
                    'nama_barang' => $item->barang?->nama_barang,
                    'harga'       => $item->harga,
                    'jumlah'      => $item->jumlah,
                    'subtotal'    => $item->harga * $item->jumlah,
                ];
            }),
        ]
    ];
}

}

