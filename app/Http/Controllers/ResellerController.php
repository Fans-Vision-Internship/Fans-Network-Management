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
            'tunggakan' => 'required|numeric',
            'area' => 'required',
            'device_type' => 'required',
            // Validate OLT fields
            'olt_sn' => 'nullable',
            'olt_type_modem' => 'nullable',
            // Validate Switch fields
            'switch_type_sfp' => 'nullable',
            'switch_sn_sfp' => 'nullable',
        ]);

        // Create reseller with OLT or Switch data
        Reseller::create($request->all());

        return redirect()->route('reseller.index')->with('success', 'Reseller berhasil ditambahkan');
    }

    public function update(Request $request, Reseller $reseller)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'nohp' => 'required',
            'tunggakan' => 'required|numeric',
            'area' => 'required',
            'device_type' => 'required',
            'olt_sn' => 'nullable|required_if:device_type,olt',
            'olt_type_modem' => 'nullable|required_if:device_type,olt',
            'switch_type_sfp' => 'nullable|required_if:device_type,switch',
            'switch_sn_sfp' => 'nullable|required_if:device_type,switch',
        ]);

        // Update reseller with OLT or Switch data
        $reseller->update($request->all());

        return redirect()->route('reseller.index')->with('success', 'Reseller berhasil diupdate');
    }


    public function destroy(Reseller $reseller)
    {
        $reseller->delete();

        return redirect()->route('reseller.index')->with('success', 'Reseller berhasil dihapus');
    }
}
