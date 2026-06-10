<?php

namespace App\Http\Controllers\Api\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Lease;
use App\Models\RentPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LandlordSmsController extends Controller
{
    private function sendSms(string $phone, string $message): bool
    {
        try {
            // Normalize Tanzania phone number
            $phone = preg_replace('/\D/', '', $phone);
            if (strlen($phone) == 9) $phone = '255' . $phone;
            if (substr($phone, 0, 1) == '0') $phone = '255' . substr($phone, 1);

            // Use Twilio or Africa's Talking or any SMS provider
            // For now, log and return true (integrate your SMS provider here)
            Log::info("SMS to $phone: $message");

            // Uncomment and configure your SMS provider:
            // $client = new \Twilio\Rest\Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
            // $client->messages->create('+' . $phone, [
            //     'from' => env('TWILIO_FROM'),
            //     'body' => $message,
            // ]);

            // Africa's Talking (AT) integration:
            // $AT = new AfricasTalking\SDK\AfricasTalking(env('AT_USERNAME'), env('AT_API_KEY'));
            // $sms = $AT->sms();
            // $sms->send(['to' => '+' . $phone, 'message' => $message]);

            return true;
        } catch (\Exception $e) {
            Log::error("SMS failed to $phone: " . $e->getMessage());
            return false;
        }
    }

    // ─── Send to paid tenants ─────────────────────────────────────────────────

    public function sendToPaidTenants(Request $request)
    {
        $user = $request->user();
        $message = $request->message ?? 'Asante kwa kulipa kodi yako. Tunakushukuru!';
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');
        $leaseIds = Lease::whereIn('property_id', $propertyIds)->where('status', 'active')->pluck('id');

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $paidLeaseIds = RentPayment::whereIn('lease_id', $leaseIds)
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->where('status', 'completed')
            ->pluck('lease_id');

        $paidTenantIds = Lease::whereIn('id', $paidLeaseIds)->pluck('tenant_id');
        $tenants = User::whereIn('id', $paidTenantIds)->get();

        $sentCount = 0;
        $failedCount = 0;

        foreach ($tenants as $tenant) {
            if ($tenant->phone) {
                if ($this->sendSms($tenant->phone, $message)) {
                    $sentCount++;
                } else {
                    $failedCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "SMS imetumwa kwa wapangaji $sentCount",
            'data' => ['sent_count' => $sentCount, 'failed_count' => $failedCount, 'total' => $tenants->count()]
        ]);
    }

    // ─── Send to unpaid tenants ───────────────────────────────────────────────

    public function sendToUnpaidTenants(Request $request)
    {
        $user = $request->user();
        $message = $request->message ?? 'Habari! Kodi yako bado haijafika. Tafadhali lipa haraka iwezekanavyo.';
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');
        $activeLeases = Lease::whereIn('property_id', $propertyIds)->where('status', 'active')->get();

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $paidLeaseIds = RentPayment::whereIn('lease_id', $activeLeases->pluck('id'))
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->where('status', 'completed')
            ->pluck('lease_id');

        $unpaidLeases = $activeLeases->whereNotIn('id', $paidLeaseIds);
        $unpaidTenantIds = $unpaidLeases->pluck('tenant_id');
        $tenants = User::whereIn('id', $unpaidTenantIds)->get();

        $sentCount = 0;
        $failedCount = 0;

        foreach ($tenants as $tenant) {
            if ($tenant->phone) {
                $personalMsg = str_replace('{name}', $tenant->name, $message);
                if ($this->sendSms($tenant->phone, $personalMsg)) {
                    $sentCount++;
                } else {
                    $failedCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "SMS imetumwa kwa wapangaji $sentCount wasiolipa",
            'data' => ['sent_count' => $sentCount, 'failed_count' => $failedCount, 'total' => $tenants->count()]
        ]);
    }

    // ─── Send utility bill to one tenant ─────────────────────────────────────

    public function sendUtilityBill(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|integer',
            'utility_type' => 'required|in:electricity,water',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|string',
        ]);

        $user = $request->user();
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');

        // Verify tenant belongs to this landlord
        $isMyTenant = Lease::whereIn('property_id', $propertyIds)
            ->where('tenant_id', $request->tenant_id)
            ->where('status', 'active')
            ->exists();

        if (!$isMyTenant) {
            return response()->json(['success' => false, 'message' => 'Tenant not found'], 404);
        }

        $tenant = User::findOrFail($request->tenant_id);
        $utilityName = $request->utility_type == 'electricity' ? 'Umeme' : 'Maji';
        $amount = number_format($request->amount);

        $message = $request->custom_message
            ?? "Habari {$tenant->name}! Bili ya {$utilityName} kwa {$request->period}: TZS {$amount}. Tafadhali lipa kabla ya mwisho wa mwezi. - " . config('app.name');

        $sent = $tenant->phone ? $this->sendSms($tenant->phone, $message) : false;

        return response()->json([
            'success' => $sent,
            'message' => $sent ? "SMS ya {$utilityName} imetumwa kwa {$tenant->name}" : 'Imeshindikana kutuma SMS',
            'data' => ['tenant' => $tenant->name, 'utility' => $utilityName, 'amount' => $request->amount]
        ]);
    }

    // ─── Bulk utility bills ───────────────────────────────────────────────────

    public function sendBulkUtilityBills(Request $request)
    {
        $request->validate([
            'property_id' => 'required|integer',
            'utility_type' => 'required|in:electricity,water',
            'period' => 'required|string',
            'bills' => 'required|array',
            'bills.*.tenant_id' => 'required|integer',
            'bills.*.amount' => 'required|numeric',
        ]);

        $user = $request->user();
        $property = Property::where('id', $request->property_id)->where('user_id', $user->id)->firstOrFail();
        $utilityName = $request->utility_type == 'electricity' ? 'Umeme' : 'Maji';

        $sentCount = 0;
        $failedCount = 0;

        foreach ($request->bills as $bill) {
            $tenant = User::find($bill['tenant_id']);
            if (!$tenant || !$tenant->phone) { $failedCount++; continue; }

            $amount = number_format($bill['amount']);
            $message = "Habari {$tenant->name}! Bili ya {$utilityName} ({$property->title}) kwa {$request->period}: TZS {$amount}. - " . config('app.name');

            if ($this->sendSms($tenant->phone, $message)) $sentCount++;
            else $failedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "SMS za {$utilityName} zimetumwa: {$sentCount} zilifanikiwa",
            'data' => ['sent_count' => $sentCount, 'failed_count' => $failedCount]
        ]);
    }

    // ─── Send custom SMS to one tenant ───────────────────────────────────────

    public function sendToTenant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|integer',
            'message' => 'required|string|max:500',
        ]);

        $user = $request->user();
        $propertyIds = Property::where('user_id', $user->id)->pluck('id');

        $isMyTenant = Lease::whereIn('property_id', $propertyIds)
            ->where('tenant_id', $request->tenant_id)->exists();

        if (!$isMyTenant) {
            return response()->json(['success' => false, 'message' => 'Tenant not found'], 404);
        }

        $tenant = User::findOrFail($request->tenant_id);
        $sent = $tenant->phone ? $this->sendSms($tenant->phone, $request->message) : false;

        return response()->json([
            'success' => $sent,
            'message' => $sent ? "SMS imetumwa kwa {$tenant->name}" : 'Imeshindikana kutuma SMS'
        ]);
    }

    // ─── SMS history ──────────────────────────────────────────────────────────

    public function smsHistory(Request $request)
    {
        // Return empty for now — wire up to a sms_logs table if you create one
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'SMS history will be available soon'
        ]);
    }
}
