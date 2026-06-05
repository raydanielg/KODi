<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Mwenye mamlaka kamili juu ya mfumo wote'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Msimamizi mkuu wa mfumo'],
            ['name' => 'Landlord', 'slug' => 'landlord', 'description' => 'Mmiliki wa nyumba / mwenye mali'],
            ['name' => 'Agent', 'slug' => 'agent', 'description' => 'Wakala wa mali isiyohamishika'],
            ['name' => 'Tenant', 'slug' => 'tenant', 'description' => 'Mpangaji / mtu anayekodi nyumba'],
            ['name' => 'Support', 'slug' => 'support', 'description' => 'Mfanyakazi wa msaada kwa wateja'],
            ['name' => 'Maintenance', 'slug' => 'maintenance', 'description' => 'Mfanyakazi wa matengenezo'],
            ['name' => 'Accountant', 'slug' => 'accountant', 'description' => 'Mhasibu wa kampuni'],
            ['name' => 'Investor', 'slug' => 'investor', 'description' => 'Mwekezaji katika mali isiyohamishika'],
        ];

        $this->command->info('Jukumu (roles) linatumia safu ya "role" kwenye jedwali la users.');
        $this->command->info('Majukumu yafuatayo yanapatikana: ' . implode(', ', array_column($roles, 'name')));
    }
}
