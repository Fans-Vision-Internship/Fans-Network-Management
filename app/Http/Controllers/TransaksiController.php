<?php

namespace App\Http\Controllers;

use App\Models\Reseller;
use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        // Ambil semua reseller yang belum melakukan transaksi bulan ini
        $resellers = Reseller::belumTransaksiBulanIni()->get();

        return view('transaksi.index', compact('resellers'));
    }

    public function store(Request $request, $resellerId)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'bandwith' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'spare' => 'nullable|numeric',
            'tunggakan' => 'required|numeric',
            'total_tagihan' => 'required|numeric',
            'total_pembayaran' => 'required|numeric',
            'harga_bw' => 'required|numeric',
            'biaya_aktivasi' => 'required|numeric',
        ]);
    
        $validatedData['reseller_id'] = $resellerId;
    
        // Simpan transaksi ke database
        Pembayaran::create($validatedData);
    
        // Update tunggakan reseller jika total tagihan lebih besar dari total pembayaran
        if ($validatedData['total_tagihan'] > $validatedData['total_pembayaran']) {
            $selisih = $validatedData['total_tagihan'] - $validatedData['total_pembayaran'];
            
            // Perbarui tunggakan pada tabel reseller dengan nilai selisih, bukan menambahkannya
            $reseller = Reseller::find($resellerId);
            $reseller->tunggakan = $selisih;
            $reseller->save();
        } else {
            // Jika tidak ada selisih, pastikan tunggakan di reset menjadi 0
            $reseller = Reseller::find($resellerId);
            $reseller->tunggakan = 0;
            $reseller->save();
        }
        // menambahkan transaksi bw trakhir ke reseller
        $reseller = Reseller::find($resellerId);
        $reseller->bandwith = $validatedData['bandwith'];
        $reseller->save();
        // Redirect ke halaman reseller
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }
    public function generateInvoice($resellerId)
    {
        $reseller = Reseller::find($resellerId);
        $pembayaran = Pembayaran::where('reseller_id', $resellerId)->latest()->first();

        // Calculate the total
        $total_tagihan = $pembayaran->harga_bw + $pembayaran->tunggakan + $pembayaran->biaya_aktivasi;

        // Data to be passed to the view
        $data = [
            'reseller' => $reseller,
            'tanggal' => $pembayaran->tanggal,
            'invoiceNo' => rand(1000, 9999),  // Generate a random invoice number
            'jatuhTempo' => now()->addDays(30)->format('d M Y'),
            'bandwith' => $pembayaran->bandwith,
            'harga_bw' => $pembayaran->harga_bw,
            'tunggakan' => $pembayaran->tunggakan,
            'biaya_aktivasi' => $pembayaran->biaya_aktivasi,
            'total_tagihan' => $total_tagihan
        ];

        // Load view into the PDF
        $pdf = Pdf::loadView('invoice', $data);

        // Download the PDF
        return $pdf->download('invoice.pdf');
    }
    
}
