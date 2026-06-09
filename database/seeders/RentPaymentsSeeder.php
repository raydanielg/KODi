<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentPaymentsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        $tenants = [8, 9, 10];
        $landlords = [3, 4, 5];
        $properties = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $payments = [
            [
                'property_id' => 1,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[0],
                'amount' => 1500000,
                'payment_method' => 'bank_transfer',
                'payment_date' => $now->copy()->subMonths(2)->addDays(5),
                'due_date' => $now->copy()->subMonths(2)->addDays(1),
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'description' => 'Kodi ya mwezi Januari - Nyumba ya Kisasa Kariakoo',
                'created_at' => $now->copy()->subMonths(2)->addDays(5),
                'updated_at' => $now->copy()->subMonths(2)->addDays(5),
            ],
            [
                'property_id' => 1,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[0],
                'amount' => 1500000,
                'payment_method' => 'mobile_money',
                'payment_date' => $now->copy()->subMonth()->addDays(3),
                'due_date' => $now->copy()->subMonth()->addDays(1),
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'description' => 'Kodi ya mwezi Februari - Nyumba ya Kisasa Kariakoo',
                'created_at' => $now->copy()->subMonth()->addDays(3),
                'updated_at' => $now->copy()->subMonth()->addDays(3),
            ],
            [
                'property_id' => 2,
                'tenant_id' => $tenants[1],
                'landlord_id' => $landlords[0],
                'amount' => 350000,
                'payment_method' => 'cash',
                'payment_date' => $now->copy()->subWeeks(3)->addDays(2),
                'due_date' => $now->copy()->subWeeks(3),
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'description' => 'Kodi ya mwezi - Studio Nzuri Mbezi',
                'created_at' => $now->copy()->subWeeks(3)->addDays(2),
                'updated_at' => $now->copy()->subWeeks(3)->addDays(2),
            ],
            [
                'property_id' => 3,
                'tenant_id' => $tenants[2],
                'landlord_id' => $landlords[1],
                'amount' => 2500000,
                'payment_method' => 'bank_transfer',
                'payment_date' => $now->copy()->subMonths(3)->addDays(10),
                'due_date' => $now->copy()->subMonths(3)->addDays(1),
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'description' => 'Kodi ya mwezi - Gorofa La Posta',
                'created_at' => $now->copy()->subMonths(3)->addDays(10),
                'updated_at' => $now->copy()->subMonths(3)->addDays(10),
            ],
            [
                'property_id' => 3,
                'tenant_id' => $tenants[2],
                'landlord_id' => $landlords[1],
                'amount' => 2500000,
                'payment_method' => 'mobile_money',
                'payment_date' => $now->copy()->subMonths(2)->addDays(8),
                'due_date' => $now->copy()->subMonths(2)->addDays(1),
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'description' => 'Kodi ya mwezi - Gorofa La Posta',
                'created_at' => $now->copy()->subMonths(2)->addDays(8),
                'updated_at' => $now->copy()->subMonths(2)->addDays(8),
            ],
            [
                'property_id' => 4,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[2],
                'amount' => 2000000,
                'payment_method' => 'bank_transfer',
                'payment_date' => $now->copy()->subMonth()->addDays(15),
                'due_date' => $now->copy()->subMonth()->addDays(1),
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'description' => 'Kodi ya mwezi - Nyumba ya Familia Kijitonyama',
                'created_at' => $now->copy()->subMonth()->addDays(15),
                'updated_at' => $now->copy()->subMonth()->addDays(15),
            ],
            [
                'property_id' => 5,
                'tenant_id' => $tenants[1],
                'landlord_id' => $landlords[1],
                'amount' => 1200000,
                'payment_method' => 'mobile_money',
                'payment_date' => $now->copy()->subWeeks(2)->addDays(5),
                'due_date' => $now->copy()->subWeeks(2)->addDays(1),
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'description' => 'Kodi ya mwezi - Flat la Kisasa Njiro',
                'created_at' => $now->copy()->subWeeks(2)->addDays(5),
                'updated_at' => $now->copy()->subWeeks(2)->addDays(5),
            ],
            [
                'property_id' => 7,
                'tenant_id' => $tenants[2],
                'landlord_id' => $landlords[2],
                'amount' => 3500000,
                'payment_method' => 'bank_transfer',
                'payment_date' => $now->copy()->subWeek()->addDays(3),
                'due_date' => $now->copy()->subWeek()->addDays(1),
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'description' => 'Kodi ya mwezi - Villa ya Kifahari Mbezi',
                'created_at' => $now->copy()->subWeek()->addDays(3),
                'updated_at' => $now->copy()->subWeek()->addDays(3),
            ],
            [
                'property_id' => 8,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[1],
                'amount' => 800000,
                'payment_method' => 'cash',
                'payment_date' => $now->copy()->subDays(10),
                'due_date' => $now->copy()->subDays(1),
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'description' => 'Kodi ya mwezi - Nyumba Ndogu Dodoma',
                'created_at' => $now->copy()->subDays(10),
                'updated_at' => $now->copy()->subDays(10),
            ],
            [
                'property_id' => 1,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[0],
                'amount' => 1500000,
                'payment_method' => 'mobile_money',
                'payment_date' => null,
                'due_date' => $now->copy()->addDays(5),
                'status' => 'pending',
                'transaction_id' => null,
                'description' => 'Kodi ya mwezi Machi - Nyumba ya Kisasa Kariakoo',
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ],
            [
                'property_id' => 4,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[2],
                'amount' => 2000000,
                'payment_method' => 'bank_transfer',
                'payment_date' => null,
                'due_date' => $now->copy()->addDays(8),
                'status' => 'pending',
                'transaction_id' => null,
                'description' => 'Kodi ya mwezi - Nyumba ya Familia Kijitonyama',
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
            [
                'property_id' => 5,
                'tenant_id' => $tenants[1],
                'landlord_id' => $landlords[1],
                'amount' => 1200000,
                'payment_method' => 'mobile_money',
                'payment_date' => null,
                'due_date' => $now->copy()->addDays(12),
                'status' => 'pending',
                'transaction_id' => null,
                'description' => 'Kodi ya mwezi - Flat la Kisasa Njiro',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('rent_payments')->insert($payments);

        $this->command->info('Rent payments seeded successfully: ' . count($payments) . ' payments created.');
    }
}
