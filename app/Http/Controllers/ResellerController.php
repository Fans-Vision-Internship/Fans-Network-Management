<?php

namespace App\Http\Controllers;

use App\Models\Reseller;
use Illuminate\Http\Request;

class ResellerController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request, defaultnya kosong (tidak ada filter)
        $filter = $request->get('filter', '');

        // Jika tidak ada filter, tampilkan semua reseller
        if ($filter == '') {
            $resellers = Reseller::all();
        } else {
            // Jika ada filter, lakukan filter sesuai dengan status
            $resellers = Reseller::when($filter == 'aktif', function ($query) {
                    return $query->aktif();
                })
                ->when($filter == 'nonaktif', function ($query) {
                    return $query->nonaktif();
                })
                ->get();
        }

        return view('reseller.index', compact('resellers', 'filter'));
    }

    public function toggleStatus($id)
    {
        $reseller = Reseller::findOrFail($id);
        $reseller->status = $reseller->status == 'aktif' ? 'nonaktif' : 'aktif';
        $reseller->save();

        return redirect()->route('reseller.index')->with('success', 'Status reseller berhasil diubah.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'nohp' => 'required',
            'tunggakan' => 'required|numeric',
            'area' => 'required',
            'device_type' => 'nullable',
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
            'device_type' => 'nullable',
            'olt_sn' => 'nullable|required_if:device_type,olt',
            'olt_type_modem' => 'nullable|required_if:device_type,olt',
            'switch_type_sfp' => 'nullable|required_if:device_type,switch',
            'switch_sn_sfp' => 'nullable|required_if:device_type,switch',
        ]);

        $data = $request->all();

        // Jika device_type adalah 'olt', set semua field terkait switch ke null
        if ($request->device_type === 'olt') {
            $data['switch_type_sfp'] = null;
            $data['switch_sn_sfp'] = null;
            $data['switch_lokasi_pop'] = null;
            $data['switch_port_number'] = null;
            $data['switch_statik'] = null;
        }

        // Jika device_type adalah 'switch', set semua field terkait olt ke null
        if ($request->device_type === 'switch') {
            $data['olt_sn'] = null;
            $data['olt_type_modem'] = null;
            $data['olt_lokasi_pop'] = null;
            $data['olt_secret'] = null;
            $data['olt_ip_address'] = null;
            $data['olt_statik'] = null;
        }

        // Update reseller dengan data yang telah disesuaikan
        $reseller->update($data);

        return redirect()->route('reseller.index')->with('success', 'Reseller berhasil diupdate');
    }



    public function destroy(Reseller $reseller)
    {
        $reseller->delete();

        return redirect()->route('reseller.index')->with('success', 'Reseller berhasil dihapus');
    }
}
