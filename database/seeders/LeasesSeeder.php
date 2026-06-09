<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeasesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        $tenants = [8, 9, 10];
        $landlords = [3, 4, 5];
        $agents = [6, 7];
        $properties = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $leases = [
            [
                'property_id' => 1,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[0],
                'agent_id' => $agents[0],
                'lease_number' => 'LEASE-' . $now->timestamp . '-001',
                'start_date' => $now->copy()->subMonths(2)->addDays(15),
                'end_date' => $now->copy()->addMonths(10)->addDays(15),
                'rent_amount' => 1500000,
                'deposit_amount' => 1500000,
                'deposit_paid' => true,
                'status' => 'active',
                'payment_frequency' => 'monthly',
                'due_day' => 5,
                'late_fee' => 50000,
                'penalty_percent' => 5.00,
                'renewal_count' => 0,
                'notes' => 'Mkataba wa kodi kwa mwezi. Mwenye nyumba anapaswa kufanya matengenezo muhimu. Mpangaji anapaswa kulipa kodi kwa wakati.',
                'created_at' => $now->copy()->subMonths(2)->addDays(10),
                'updated_at' => $now->copy()->subMonths(2)->addDays(12),
            ],
            [
                'property_id' => 2,
                'tenant_id' => $tenants[1],
                'landlord_id' => $landlords[0],
                'agent_id' => null,
                'lease_number' => 'LEASE-' . $now->timestamp . '-002',
                'start_date' => $now->copy()->subWeeks(3)->addDays(5),
                'end_date' => $now->copy()->addMonths(9)->addDays(5),
                'rent_amount' => 350000,
                'deposit_amount' => 350000,
                'deposit_paid' => true,
                'status' => 'active',
                'payment_frequency' => 'monthly',
                'due_day' => 1,
                'late_fee' => 20000,
                'penalty_percent' => 5.00,
                'renewal_count' => 0,
                'notes' => 'Mkataba wa kodi kwa mwezi. Studio ina bafu na jiko la kushiriki.',
                'created_at' => $now->copy()->subWeeks(3)->addDays(2),
                'updated_at' => $now->copy()->subWeeks(3)->addDays(3),
            ],
            [
                'property_id' => 3,
                'tenant_id' => $tenants[2],
                'landlord_id' => $landlords[1],
                'agent_id' => $agents[1],
                'lease_number' => 'LEASE-' . $now->timestamp . '-003',
                'start_date' => $now->copy()->subMonths(3)->addDays(20),
                'end_date' => $now->copy()->addMonths(9)->addDays(20),
                'rent_amount' => 2500000,
                'deposit_amount' => 2500000,
                'deposit_paid' => true,
                'status' => 'active',
                'payment_frequency' => 'monthly',
                'due_day' => 10,
                'late_fee' => 75000,
                'penalty_percent' => 5.00,
                'renewal_count' => 0,
                'notes' => 'Mkataba wa kodi kwa mwezi. Gorofa ina vyumba vinne vya kulala.',
                'created_at' => $now->copy()->subMonths(3)->addDays(15),
                'updated_at' => $now->copy()->subMonths(3)->addDays(18),
            ],
            [
                'property_id' => 4,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[2],
                'agent_id' => null,
                'lease_number' => 'LEASE-' . $now->timestamp . '-004',
                'start_date' => $now->copy()->subMonth()->addDays(20),
                'end_date' => $now->copy()->addMonths(11)->addDays(20),
                'rent_amount' => 2000000,
                'deposit_amount' => 1000000,
                'deposit_paid' => true,
                'status' => 'active',
                'payment_frequency' => 'monthly',
                'due_day' => 15,
                'late_fee' => 60000,
                'penalty_percent' => 5.00,
                'renewal_count' => 0,
                'notes' => 'Mkataba wa kodi kwa mwezi. Nyumba ina uwanja mkubwa wa nyuma.',
                'created_at' => $now->copy()->subMonth()->addDays(15),
                'updated_at' => $now->copy()->subMonth()->addDays(18),
            ],
            [
                'property_id' => 5,
                'tenant_id' => $tenants[1],
                'landlord_id' => $landlords[1],
                'agent_id' => $agents[0],
                'lease_number' => 'LEASE-' . $now->timestamp . '-005',
                'start_date' => $now->copy()->subWeeks(2)->addDays(10),
                'end_date' => $now->copy()->addMonths(10)->addDays(10),
                'rent_amount' => 1200000,
                'deposit_amount' => 600000,
                'deposit_paid' => true,
                'status' => 'active',
                'payment_frequency' => 'monthly',
                'due_day' => 5,
                'late_fee' => 40000,
                'penalty_percent' => 5.00,
                'renewal_count' => 0,
                'notes' => 'Mkataba wa kodi kwa mwezi. Flat la kisasa.',
                'created_at' => $now->copy()->subWeeks(2)->addDays(5),
                'updated_at' => $now->copy()->subWeeks(2)->addDays(7),
            ],
            [
                'property_id' => 7,
                'tenant_id' => $tenants[2],
                'landlord_id' => $landlords[2],
                'agent_id' => null,
                'lease_number' => 'LEASE-' . $now->timestamp . '-006',
                'start_date' => $now->copy()->subWeek()->addDays(15),
                'end_date' => $now->copy()->addMonths(11)->addDays(15),
                'rent_amount' => 3500000,
                'deposit_amount' => 3500000,
                'deposit_paid' => true,
                'status' => 'active',
                'payment_frequency' => 'monthly',
                'due_day' => 1,
                'late_fee' => 100000,
                'penalty_percent' => 5.00,
                'renewal_count' => 0,
                'notes' => 'Mkataba wa kodi kwa mwezi. Villa ya kifahari.',
                'created_at' => $now->copy()->subWeek()->addDays(10),
                'updated_at' => $now->copy()->subWeek()->addDays(12),
            ],
            [
                'property_id' => 8,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[1],
                'agent_id' => null,
                'lease_number' => 'LEASE-' . $now->timestamp . '-007',
                'start_date' => $now->copy()->subDays(15),
                'end_date' => $now->copy()->addMonths(11)->addDays(15),
                'rent_amount' => 800000,
                'deposit_amount' => 400000,
                'deposit_paid' => true,
                'status' => 'active',
                'payment_frequency' => 'monthly',
                'due_day' => 10,
                'late_fee' => 25000,
                'penalty_percent' => 5.00,
                'renewal_count' => 0,
                'notes' => 'Mkataba wa kodi kwa mwezi. Nyumba ndogo.',
                'created_at' => $now->copy()->subDays(10),
                'updated_at' => $now->copy()->subDays(12),
            ],
        ];

        DB::table('leases')->insert($leases);

        $this->command->info('Leases seeded successfully: ' . count($leases) . ' leases created.');
    }
}
