<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = [
        'nama'
    ];

    // nanti dipakai di buku
    public function bukus()
    {
        return $this->hasMany(Buku::class);
    }
}