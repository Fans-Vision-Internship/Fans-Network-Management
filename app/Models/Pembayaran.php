<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'pembayaran';
    protected $fillable = [
        'reseller_id',
        'tanggal',
        'bandwith',
        'keterangan',
        'spare',
        'tunggakan',
        'total_tagihan',
        'total_pembayaran',
        'harga_bw',
        'biaya_aktivasi',
    ];

    // Relasi dengan model Reseller
    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }
}
