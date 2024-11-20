<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PembayaranExport implements WithMultipleSheets
{
    public function sheets(): array
    {
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

class PembayaranSheet implements FromCollection, WithHeadings, WithTitle, WithStyles
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
        return $this->pembayaranData->map(function ($pembayaran) {
            return [
                $pembayaran->id,
                $pembayaran->reseller->nama,
                $pembayaran->tanggal,
                $pembayaran->bandwith . ' Mbps',
                $pembayaran->spare . ' Mbps',
                $pembayaran->keterangan,
                'Rp ' . number_format($pembayaran->harga_bw, 0, ',', '.'),
                'Rp ' . number_format($pembayaran->biaya_aktivasi, 0, ',', '.'),
                'Rp ' . number_format($pembayaran->tunggakan, 0, ',', '.'),
                'Rp ' . number_format($pembayaran->total_tagihan, 0, ',', '.'),
                'Rp ' . number_format($pembayaran->total_pembayaran, 0, ',', '.'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Reseller',
            'Tanggal',
            'Bandwidth',
            'Spare',
            'Keterangan',
            'Harga BW',
            'Biaya Aktivasi',
            'Tunggakan',
            'Total Tagihan',
            'Total Pembayaran',
        ];
    }

    public function title(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 10)) . ' ' . $this->year;
    }

    
    public function styles(Worksheet $sheet)
    {
        // Styling the header row
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '007BFF'], // Blue background color
            ],
        ]);

        // Auto size columns
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $sheet;
    }
}
