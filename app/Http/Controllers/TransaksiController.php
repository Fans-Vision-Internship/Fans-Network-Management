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
        $resellers = Reseller::where('status', 'Aktif')
                    ->belumTransaksiBulanIni()
                    ->get();

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
    $pembayaran = Pembayaran::create($validatedData);

    // Update tunggakan reseller jika total tagihan lebih besar dari total pembayaran
    $reseller = Reseller::find($resellerId);
    if ($validatedData['total_tagihan'] > $validatedData['total_pembayaran']) {
        $selisih = $validatedData['total_tagihan'] - $validatedData['total_pembayaran'];
        $reseller->tunggakan = $selisih;
    } else {
        $reseller->tunggakan = 0;
    }
    $reseller->bandwith = $validatedData['bandwith'];
    $reseller->save();

    $totalTransaksi = Pembayaran::count();
    $nomor_kwitansi = str_pad($totalTransaksi + 1, 6, '0', STR_PAD_LEFT);
    // Siapkan data untuk PDF
    $data = [
        'tanggal' => $validatedData['tanggal'],
        'nomor_kwitansi' => $nomor_kwitansi, // Anda bisa mengubahnya sesuai kebutuhan
        'jatuh_tempo' => now()->addMonth()->format('d-m-Y'),
        'reseller' => $reseller,
        'bandwith' => $validatedData['bandwith'],
        'harga' => $validatedData['harga_bw'],
        'tunggakan' => $validatedData['tunggakan'],
        'biaya_aktivasi' => $validatedData['biaya_aktivasi'],
        'sub_total' => $validatedData['total_tagihan'],
        'terbilang' => $this->terbilang($validatedData['total_tagihan']),
    ];

    // Generate PDF
    $pdf = Pdf::loadView('transaksi.pdf_template_kwitansi', $data);

    // Simpan PDF ke server atau tampilkan langsung ke user
    return $pdf->download('kwitansi_' . $reseller->nama . '.pdf');

    // Redirect ke halaman reseller dengan pesan sukses
    return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan dan kwitansi dicetak!');
}

// Fungsi terbilang untuk angka dalam bahasa Indonesia
private function terbilang($nilai)
{
    // Implementasi fungsi terbilang
    $f = new \NumberFormatter('id', \NumberFormatter::SPELLOUT);
    return $f->format($nilai);
}

    
}
