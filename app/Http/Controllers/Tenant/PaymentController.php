<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('tenant.payments.index');
    }

    public function makePayment()
    {
        return view('tenant.payments.make');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.payments.index');
    }

    public function history()
    {
        return view('tenant.payments.history');
    }
}
