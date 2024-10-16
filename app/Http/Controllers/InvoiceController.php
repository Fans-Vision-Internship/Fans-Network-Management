<?php

namespace App\Http\Controllers;
use PDF;
use App\Models\Reseller;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        // Ambil semua reseller yang belum melakukan transaksi bulan ini
        $resellers = Reseller::where('status', 'Aktif')
                    ->belumTransaksiBulanIni()
                    ->get();

        return view('invoice.index', compact('resellers'));
    }
    public function generateInvoice(Request $request, $id)
    {
        $reseller = Reseller::findOrFail($id);
        $nomorKwitansi = $this->getNextKwitansiNumber();
        $data = [
            'reseller' => $reseller,
            'tanggal' => $request->tanggal,
            'jatuh_tempo' => $request->jatuh_tempo,
            'bandwith' => $request->bandwith,
            'biaya_aktivasi' => $request->biaya_aktivasi,
            'tunggakan' => $request->tunggakan,
            'harga' => $request->harga,
            'sub_total' => $request->sub_total,
            'nomor_kwitansi' => $nomorKwitansi, // Tambahkan nomor kwitansi ke data
        ];

        $pdf = FacadePdf::loadView('invoice.pdf_template', $data);

        return $pdf->download('invoice_' . $reseller->nama . '_' . $nomorKwitansi . '.pdf');
    }
    private function getNextKwitansiNumber()
{
    $path = public_path('kwitansi_counter.txt'); // Path ke file di folder public

    // Jika file tidak ditemukan, buat file dengan nomor awal 000000
    if (!file_exists($path)) {
        file_put_contents($path, '000000');
    }

    // Baca nomor terakhir dari file
    $lastNumber = (int) file_get_contents($path);

    // Tambahkan 1 ke nomor terakhir dan format menjadi 6 digit (000001, 000002, dst.)
    $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);

    // Simpan nomor baru ke file
    file_put_contents($path, $nextNumber);

    return $nextNumber;
}
}
