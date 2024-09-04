<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;

class PembayaranExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        // Mengambil data pembayaran dan mengelompokkan berdasarkan bulan dan tahun
        $pembayaranPerBulan = Pembayaran::selectRaw('YEAR(tanggal) as year, MONTH(tanggal) as month')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $sheets = [];

        foreach ($pembayaranPerBulan as $periode) {
            $year = $periode->year;
            $month = $periode->month;

            $pembayaranData = Pembayaran::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->get();

            $sheets[] = new PembayaranSheet($pembayaranData, $month, $year);
        }

        return $sheets;
    }
}

class PembayaranSheet implements FromCollection, WithTitle
{
    private $pembayaranData;
    private $month;
    private $year;

    public function __construct(Collection $pembayaranData, int $month, int $year)
    {
        $this->pembayaranData = $pembayaranData;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        // Mengembalikan koleksi data pembayaran
        return $this->pembayaranData;
    }

    public function title(): string
    {
        // Mengatur judul sheet berdasarkan bulan dan tahun
        return date('F', mktime(0, 0, 0, $this->month, 10)) . ' ' . $this->year;
    }
}
