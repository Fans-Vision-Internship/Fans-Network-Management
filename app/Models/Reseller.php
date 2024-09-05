<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reseller extends Model
{
    use HasFactory;

    protected $table = 'reseller';

    protected $fillable = [
        'nama',
        'alamat',
        'nohp',
        'tunggakan',
        'area',
        'bandwith',
        'olt_sn',
        'olt_type_modem',
        'olt_lokasi_pop',
        'olt_secret',
        'olt_ip_address',
        'olt_statik',
        'switch_type_sfp',
        'switch_sn_sfp',
        'switch_lokasi_pop',
        'switch_port_number',
        'switch_statik',
    ];
    

    // Scope untuk mendapatkan reseller yang belum melakukan transaksi bulan ini
    public function scopeBelumTransaksiBulanIni($query)
    {
        return $query->whereDoesntHave('pembayaran', function ($q) {
            $q->whereMonth('tanggal', Carbon::now()->month)
              ->whereYear('tanggal', Carbon::now()->year);
        });
    }

    // Relasi dengan model Pembayaran
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
