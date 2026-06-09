<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        $tenants = [8, 9, 10];
        $landlords = [3, 4, 5];
        $agents = [6, 7];
        $properties = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $applications = [
            [
                'property_id' => 1,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[0],
                'agent_id' => $agents[0],
                'status' => 'approved',
                'expected_move_in' => $now->copy()->subMonths(2)->addDays(15),
                'monthly_offer' => 1500000,
                'tenant_message' => 'Nimependa sana nyumba hii, inaonekana vizuri na salama. Nina tayari kuanza lala tarehe 15.',
                'landlord_notes' => 'Ombi limekubaliwa. Karibu ujionee nyumba.',
                'viewed_at' => $now->copy()->subMonths(2)->addDays(11),
                'decided_at' => $now->copy()->subMonths(2)->addDays(12),
                'created_at' => $now->copy()->subMonths(2)->addDays(10),
                'updated_at' => $now->copy()->subMonths(2)->addDays(12),
            ],
            [
                'property_id' => 2,
                'tenant_id' => $tenants[1],
                'landlord_id' => $landlords[0],
                'agent_id' => null,
                'status' => 'approved',
                'expected_move_in' => $now->copy()->subWeeks(3)->addDays(5),
                'monthly_offer' => 350000,
                'tenant_message' => 'Studio inaonekana nzuri sana, inafaa kwa mimi. Nina tayari kulipa.',
                'landlord_notes' => 'Ombi limekubaliwa. Tafadhali wasiliana nami kwa ajili ya mkataba.',
                'viewed_at' => $now->copy()->subWeeks(3)->addDays(3),
                'decided_at' => $now->copy()->subWeeks(3)->addDays(3),
                'created_at' => $now->copy()->subWeeks(3)->addDays(2),
                'updated_at' => $now->copy()->subWeeks(3)->addDays(3),
            ],
            [
                'property_id' => 3,
                'tenant_id' => $tenants[2],
                'landlord_id' => $landlords[1],
                'agent_id' => $agents[1],
                'status' => 'approved',
                'expected_move_in' => $now->copy()->subMonths(3)->addDays(20),
                'monthly_offer' => 2500000,
                'tenant_message' => 'Gorofa ni nzuri sana, ina vyumba vya kutosha kwa familia yangu.',
                'landlord_notes' => 'Ombi limekubaliwa. Karibu kuja kukagua nyumba.',
                'viewed_at' => $now->copy()->subMonths(3)->addDays(17),
                'decided_at' => $now->copy()->subMonths(3)->addDays(18),
                'created_at' => $now->copy()->subMonths(3)->addDays(15),
                'updated_at' => $now->copy()->subMonths(3)->addDays(18),
            ],
            [
                'property_id' => 4,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[2],
                'agent_id' => null,
                'status' => 'approved',
                'expected_move_in' => $now->copy()->subMonth()->addDays(20),
                'monthly_offer' => 2000000,
                'tenant_message' => 'Nyumba inaonekana nzuri kwa familia. Nina watoto wawili.',
                'landlord_notes' => 'Ombi limekubaliwa. Tunaenda kukutana kwa ajili ya mkataba.',
                'viewed_at' => $now->copy()->subMonth()->addDays(17),
                'decided_at' => $now->copy()->subMonth()->addDays(18),
                'created_at' => $now->copy()->subMonth()->addDays(15),
                'updated_at' => $now->copy()->subMonth()->addDays(18),
            ],
            [
                'property_id' => 5,
                'tenant_id' => $tenants[1],
                'landlord_id' => $landlords[1],
                'agent_id' => $agents[0],
                'status' => 'approved',
                'expected_move_in' => $now->copy()->subWeeks(2)->addDays(10),
                'monthly_offer' => 1200000,
                'tenant_message' => 'Flat ni nzuri sana, ina mahali pa kutosha.',
                'landlord_notes' => 'Ombi limekubaliwa. Karibu.',
                'viewed_at' => $now->copy()->subWeeks(2)->addDays(6),
                'decided_at' => $now->copy()->subWeeks(2)->addDays(7),
                'created_at' => $now->copy()->subWeeks(2)->addDays(5),
                'updated_at' => $now->copy()->subWeeks(2)->addDays(7),
            ],
            [
                'property_id' => 7,
                'tenant_id' => $tenants[2],
                'landlord_id' => $landlords[2],
                'agent_id' => null,
                'status' => 'approved',
                'expected_move_in' => $now->copy()->subWeek()->addDays(15),
                'monthly_offer' => 3500000,
                'tenant_message' => 'Villa ni ya kifahari sana, ina yote ninayohitaji.',
                'landlord_notes' => 'Ombi limekubaliwa. Tunaenda kukutana.',
                'viewed_at' => $now->copy()->subWeek()->addDays(11),
                'decided_at' => $now->copy()->subWeek()->addDays(12),
                'created_at' => $now->copy()->subWeek()->addDays(10),
                'updated_at' => $now->copy()->subWeek()->addDays(12),
            ],
            [
                'property_id' => 8,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[1],
                'agent_id' => null,
                'status' => 'approved',
                'expected_move_in' => $now->copy()->subDays(15),
                'monthly_offer' => 800000,
                'tenant_message' => 'Nyumba ndogo inafaa kwa familia yangu ndogo.',
                'landlord_notes' => 'Ombi limekubaliwa.',
                'viewed_at' => $now->copy()->subDays(11),
                'decided_at' => $now->copy()->subDays(12),
                'created_at' => $now->copy()->subDays(10),
                'updated_at' => $now->copy()->subDays(12),
            ],
            [
                'property_id' => 9,
                'tenant_id' => $tenants[1],
                'landlord_id' => $landlords[0],
                'agent_id' => $agents[1],
                'status' => 'pending',
                'expected_move_in' => $now->copy()->addDays(20),
                'monthly_offer' => 2800000,
                'tenant_message' => 'Ghorofa la biashara inaonekana nzuri kwa ofisi yetu.',
                'landlord_notes' => null,
                'viewed_at' => null,
                'decided_at' => null,
                'created_at' => $now->copy()->subDays(5),
                'updated_at' => $now->copy()->subDays(5),
            ],
            [
                'property_id' => 10,
                'tenant_id' => $tenants[2],
                'landlord_id' => $landlords[0],
                'agent_id' => $agents[0],
                'status' => 'pending',
                'expected_move_in' => $now->copy()->addDays(25),
                'monthly_offer' => 1700000,
                'tenant_message' => 'Banda la watalii linapendeza sana, mtazamo wa milima ni mzuri.',
                'landlord_notes' => null,
                'viewed_at' => null,
                'decided_at' => null,
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(3),
            ],
            [
                'property_id' => 6,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[0],
                'agent_id' => null,
                'status' => 'rejected',
                'expected_move_in' => $now->copy()->subMonths(1)->addDays(10),
                'monthly_offer' => 180000,
                'tenant_message' => 'Chumba kinaonekana chini sana, lakini nitajaribu.',
                'landlord_notes' => 'Samahani, chumba kiko chini ya matengenezo.',
                'viewed_at' => $now->copy()->subMonths(1)->addDays(6),
                'decided_at' => $now->copy()->subMonths(1)->addDays(7),
                'created_at' => $now->copy()->subMonths(1)->addDays(5),
                'updated_at' => $now->copy()->subMonths(1)->addDays(7),
            ],
        ];

        DB::table('applications')->insert($applications);

        $this->command->info('Applications seeded successfully: ' . count($applications) . ' applications created.');
    }
}
