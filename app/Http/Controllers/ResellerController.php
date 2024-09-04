<?php

namespace App\Http\Controllers;

use App\Models\Reseller;
use Illuminate\Http\Request;

class ResellerController extends Controller
{
    public function index()
    {
        $resellers = Reseller::all();
        return view('reseller.index', compact('resellers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'nohp' => 'required',
            'tunggakan' => 'required',
            'area' => 'required',
        ]);

        Reseller::create($request->all());

        return redirect()->route('reseller.index')->with('success', 'Reseller berhasil ditambahkan');
    }

    public function update(Request $request, Reseller $reseller)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'nohp' => 'required',
            'tunggakan' => 'required',
            'area' => 'required',
        ]);

        $reseller->update($request->all());

        return redirect()->route('reseller.index')->with('success', 'Reseller berhasil diupdate');
    }

    public function destroy(Reseller $reseller)
    {
        $reseller->delete();

        return redirect()->route('reseller.index')->with('success', 'Reseller berhasil dihapus');
    }
}