<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\RentPayment;
use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $query = RentPayment::with(['lease.property', 'tenant', 'landlord', 'property']);

            if ($user->role === 'tenant') {
                $query->where('tenant_id', $user->id);
            } elseif ($user->role === 'landlord') {
                $query->where('landlord_id', $user->id);
            } elseif (!in_array($user->role, ['admin', 'super_admin', 'accountant'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }

            $payments = $query->latest()->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $payments
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function history(Request $request)
    {
        try {
            $user = $request->user();

            $query = RentPayment::with(['lease.property', 'property']);

            if ($user->role === 'tenant') {
                $query->where('tenant_id', $user->id);
            } elseif ($user->role === 'landlord') {
                $query->where('landlord_id', $user->id);
            } elseif (!in_array($user->role, ['admin', 'super_admin', 'accountant'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            if ($request->has('year')) {
                $query->whereYear('created_at', $request->year);
            }

            if ($request->has('month')) {
                $query->whereMonth('created_at', $request->month);
            }

            $payments = $query->latest()->paginate($request->per_page ?? 15);

            $totals = [
                'total_amount' => (float) $query->sum('total_amount'),
                'total_paid' => (float) (clone $query)->where('status', 'completed')->sum('total_amount'),
                'total_pending' => (float) (clone $query)->where('status', 'pending')->sum('total_amount'),
                'count' => $payments->total(),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'payments' => $payments,
                    'totals' => $totals,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function makePayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'lease_id' => 'required|exists:leases,id',
                'amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cash,mobile_money,bank_transfer,card,other',
                'payment_reference' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $lease = Lease::with('property')->findOrFail($request->lease_id);
            $user = $request->user();

            $lateFee = 0;
            if ($lease->due_day && now()->day > $lease->due_day) {
                if ($lease->late_fee) {
                    $lateFee = $lease->late_fee;
                } elseif ($lease->penalty_percent) {
                    $lateFee = ($lease->rent_amount * $lease->penalty_percent) / 100;
                }
            }

            $payment = RentPayment::create([
                'lease_id' => $lease->id,
                'tenant_id' => $user->id,
                'landlord_id' => $lease->landlord_id,
                'property_id' => $lease->property_id,
                'amount' => $request->amount,
                'late_fee_amount' => $lateFee,
                'total_amount' => $request->amount + $lateFee,
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                'period_start' => $lease->start_date,
                'period_end' => $lease->end_date,
                'paid_at' => now(),
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Malipo yamekamilika.',
                'data' => $payment->load(['lease.property', 'tenant'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
