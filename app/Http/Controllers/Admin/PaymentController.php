<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = RentPayment::with(['tenant', 'property']);

        if ($search = $request->get('search')) {
            $query->whereHas('tenant', fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%"));
        }
        if ($method = $request->get('method')) {
            $query->where('payment_method', $method);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($month = $request->get('month')) {
            $query->whereYear('paid_at', Carbon::parse($month)->year)
                  ->whereMonth('paid_at', Carbon::parse($month)->month);
        }

        $payments = $query->latest('paid_at')->paginate(25)->withQueryString();

        $now = Carbon::now();
        $stats = [
            'total_amount'    => RentPayment::where('status', 'completed')->sum('amount'),
            'this_month'      => RentPayment::where('status', 'completed')
                                    ->whereYear('paid_at', $now->year)
                                    ->whereMonth('paid_at', $now->month)
                                    ->sum('amount'),
            'count'           => RentPayment::count(),
            'completed'       => RentPayment::where('status', 'completed')->count(),
        ];

        $methods = RentPayment::select('payment_method')->distinct()->pluck('payment_method')->filter()->values();

        return view('admin.payments.index', compact('payments', 'stats', 'methods'));
    }

    public function show($id)
    {
        $payment = RentPayment::with(['tenant', 'property', 'lease'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function edit($id)
    {
        $payment = RentPayment::findOrFail($id);
        return view('admin.payments.edit', compact('payment'));
    }
}
