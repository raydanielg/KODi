<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaintenanceRequestsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        $tenants = [8, 9, 10];
        $landlords = [3, 4, 5];
        $properties = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $requests = [
            [
                'property_id' => 1,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[0],
                'title' => 'Pampu ya Maji Haifanyi Kazi',
                'description' => 'Pampu kuu ya maji inatoka na kuingia, inahitaji kurekebishwa haraka. Maji hayapati kwenye vyumba vya juu.',
                'priority' => 'high',
                'status' => 'completed',
                'category' => 'plumbing',
                'scheduled_at' => $now->copy()->subMonths(2)->addDays(10),
                'completed_at' => $now->copy()->subMonths(2)->addDays(12),
                'assigned_to' => null,
                'estimated_cost' => 50000,
                'actual_cost' => 45000,
                'resolution_notes' => 'Pampu imebadilishwa na inafanya kazi vizuri.',
                'created_at' => $now->copy()->subMonths(2)->addDays(10),
                'updated_at' => $now->copy()->subMonths(2)->addDays(12),
            ],
            [
                'property_id' => 2,
                'tenant_id' => $tenants[1],
                'landlord_id' => $landlords[0],
                'title' => 'Umeme Unatoka',
                'description' => 'Umeme unatoka mara kwa mara, hasa wakati wa joto. Inahitaji mtu wa umeme akuja kuangalia.',
                'priority' => 'medium',
                'status' => 'completed',
                'category' => 'electrical',
                'scheduled_at' => $now->copy()->subMonth()->addDays(15),
                'completed_at' => $now->copy()->subMonth()->addDays(18),
                'assigned_to' => null,
                'estimated_cost' => 30000,
                'actual_cost' => 35000,
                'resolution_notes' => 'Umeme umerekebishwa, hakuna tatizo tena.',
                'created_at' => $now->copy()->subMonth()->addDays(15),
                'updated_at' => $now->copy()->subMonth()->addDays(18),
            ],
            [
                'property_id' => 3,
                'tenant_id' => $tenants[2],
                'landlord_id' => $landlords[1],
                'title' => 'Dirisha La Bafu Limevunjika',
                'description' => 'Dirisha la bafu kuu limevunjika, linahitaji kubadilishwa. Maji yanapita wakati wa mvua.',
                'priority' => 'high',
                'status' => 'in_progress',
                'category' => 'structural',
                'scheduled_at' => $now->copy()->subWeeks(2)->addDays(3),
                'completed_at' => null,
                'assigned_to' => null,
                'estimated_cost' => 80000,
                'actual_cost' => null,
                'resolution_notes' => null,
                'created_at' => $now->copy()->subWeeks(2)->addDays(3),
                'updated_at' => $now->copy()->subWeek()->addDays(5),
            ],
            [
                'property_id' => 4,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[2],
                'title' => 'Kiyoyozi Haifanyi Kazi',
                'description' => 'Kiyoyozi cha sebule kimeanza kutengeneza sauti na haifanyi kazi vizuri. Inahitaji kuanguliwa.',
                'priority' => 'medium',
                'status' => 'pending',
                'category' => 'appliances',
                'scheduled_at' => $now->copy()->subWeek()->addDays(2),
                'completed_at' => null,
                'assigned_to' => null,
                'estimated_cost' => 60000,
                'actual_cost' => null,
                'resolution_notes' => null,
                'created_at' => $now->copy()->subWeek()->addDays(2),
                'updated_at' => $now->copy()->subWeek()->addDays(2),
            ],
            [
                'property_id' => 5,
                'tenant_id' => $tenants[1],
                'landlord_id' => $landlords[1],
                'title' => 'Mlango Haufungiki Vizuri',
                'description' => 'Mlango mkuu wa mbele haufungiki vizuri, chufa inahitaji kubadilishwa.',
                'priority' => 'low',
                'status' => 'pending',
                'category' => 'other',
                'scheduled_at' => $now->copy()->subDays(5),
                'completed_at' => null,
                'assigned_to' => null,
                'estimated_cost' => 25000,
                'actual_cost' => null,
                'resolution_notes' => null,
                'created_at' => $now->copy()->subDays(5),
                'updated_at' => $now->copy()->subDays(5),
            ],
            [
                'property_id' => 7,
                'tenant_id' => $tenants[2],
                'landlord_id' => $landlords[2],
                'title' => 'Bwawa La Kuogelea Linahitaji Usafi',
                'description' => 'Bwawa la kuogelea limechafuka, linahitaji kusafishwa na kuongeza dawa ya kuua bacteria.',
                'priority' => 'medium',
                'status' => 'completed',
                'category' => 'cleaning',
                'scheduled_at' => $now->copy()->subWeeks(3)->addDays(1),
                'completed_at' => $now->copy()->subWeeks(3)->addDays(3),
                'assigned_to' => null,
                'estimated_cost' => 100000,
                'actual_cost' => 95000,
                'resolution_notes' => 'Bwawa limesafishwa vizuri.',
                'created_at' => $now->copy()->subWeeks(3)->addDays(1),
                'updated_at' => $now->copy()->subWeeks(3)->addDays(3),
            ],
            [
                'property_id' => 1,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[0],
                'title' => 'Jiko La Gas Halitumiwa',
                'description' => 'Jiko la gas halitumiwa, mpira wa gas unaonyesha kuwa umefika lakini jiko halitumika.',
                'priority' => 'high',
                'status' => 'in_progress',
                'category' => 'appliances',
                'scheduled_at' => $now->copy()->subDays(3),
                'completed_at' => null,
                'assigned_to' => null,
                'estimated_cost' => 40000,
                'actual_cost' => null,
                'resolution_notes' => null,
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(2),
            ],
            [
                'property_id' => 8,
                'tenant_id' => $tenants[0],
                'landlord_id' => $landlords[1],
                'title' => 'Dari Inatiririka Maji',
                'description' => 'Dari ya sebule inatiririka maji wakati wa mvua kubwa. Inahitaji kurekebishwa.',
                'priority' => 'high',
                'status' => 'pending',
                'category' => 'structural',
                'scheduled_at' => $now->copy()->subDays(1),
                'completed_at' => null,
                'assigned_to' => null,
                'estimated_cost' => 150000,
                'actual_cost' => null,
                'resolution_notes' => null,
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
        ];

        DB::table('maintenance_requests')->insert($requests);

        $this->command->info('Maintenance requests seeded successfully: ' . count($requests) . ' requests created.');
    }
}
