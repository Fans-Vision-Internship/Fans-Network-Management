<?php

namespace App\Http\Controllers;

use App\Models\Reseller;
use App\Models\Pembayaran;
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
            'spare' => 'required|numeric',
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
    
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }
    
}
