<?php

namespace App\Http\Controllers;

use App\Exports\PembayaranExport;
use App\Models\Pembayaran;
use App\Models\Reseller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data reseller untuk ditampilkan di filter
        $resellers = Reseller::all();

        // Mendapatkan bulan dan tahun dari request
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        // Query pembayaran dengan filter bulan dan tahun
        $pembayarans = Pembayaran::with('reseller')
            ->when($bulan, function ($query, $bulan) {
                return $query->whereMonth('tanggal', $bulan);
            })
            ->when($tahun, function ($query, $tahun) {
                return $query->whereYear('tanggal', $tahun);
            })
            ->get();

        return view('pembayaran.index', compact('pembayarans', 'resellers', 'bulan', 'tahun'));
    }
    public function exportExcel()
{
    return Excel::download(new PembayaranExport, 'laporan_pembayaran.xlsx');
}
}
