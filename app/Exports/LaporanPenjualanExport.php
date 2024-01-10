<?php

namespace App\Exports;

use App\Models\Nota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Nota::with(['relationToPembayaran', 'relationToProduk'])->whereBetween('created_at', [$this->startDate, $this->endDate])->get();
    }

    public function headings(): array
    {
        return [
            'Order',
            'Nama Pemesan',
            'Barang',
            'Qty',
            'Tanggal Acara',
            'Total',
            'Pembayaran',
            'Created_at',
        ];
    }

    public function map($nota): array
    {
        return [
            $nota->no_nota,
            $nota->nama_pemesan,
            $nota->relationToProduk->nama_produk,
            $nota->qty,
            $nota->tgl_acara,
            $nota->harga,
            $nota->relationToPembayaran->nama_pembayaran,
            $nota->created_at,
        ];
    }
}
