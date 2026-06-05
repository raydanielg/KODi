<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index()
    {
        return view('landlord.payouts.index');
    }

    public function requestPayout()
    {
        return view('landlord.payouts.request');
    }

    public function store(Request $request)
    {
        return redirect()->route('landlord.payouts.index');
    }
}
