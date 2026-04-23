<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'transaksis'; // ✅ SESUAI DATABASE KAMU

    protected $fillable = [
        'user_id',
        'nama_peminjam',
        'buku',
        'jumlah',
        'tanggal_pinjam',
        'jatuh_tempo',
        'tanggal_kembali',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}