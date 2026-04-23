<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buku', // Ini kolom teks di DB kamu
        'tanggal_pinjam',
        'jatuh_tempo',
        'tanggal_kembali',
        'status',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}