<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $transaksis = Peminjaman::where('user_id', Auth::id())->get();

        return view('user.dashboard', compact('transaksis'));
    }
}
