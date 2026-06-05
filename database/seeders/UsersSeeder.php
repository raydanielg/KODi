<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $users = [
            // Super Admin
            [
                'name' => 'Malkia Mkuu',
                'email' => 'superadmin@manna.com',
                'phone' => '0712000001',
                'role' => 'super_admin',
                'email_verified_at' => $now->copy()->subMonths(6),
                'created_at' => $now->copy()->subMonths(6),
                'updated_at' => $now->copy()->subMonths(6),
            ],
            // Admin
            [
                'name' => 'Admin Mkuu',
                'email' => 'admin@manna.com',
                'phone' => '0712000002',
                'role' => 'admin',
                'email_verified_at' => $now->copy()->subMonths(5)->addDays(10),
                'created_at' => $now->copy()->subMonths(5)->addDays(10),
                'updated_at' => $now->copy()->subMonths(5)->addDays(10),
            ],
            // Landlords
            [
                'name' => 'Juma Mohamed',
                'email' => 'landlord1@manna.com',
                'phone' => '0712345678',
                'role' => 'landlord',
                'email_verified_at' => $now->copy()->subMonths(5),
                'created_at' => $now->copy()->subMonths(5),
                'updated_at' => $now->copy()->subMonths(5),
            ],
            [
                'name' => 'Asha Hassan',
                'email' => 'landlord2@manna.com',
                'phone' => '0655123456',
                'role' => 'landlord',
                'email_verified_at' => $now->copy()->subMonths(4),
                'created_at' => $now->copy()->subMonths(4),
                'updated_at' => $now->copy()->subMonths(4),
            ],
            [
                'name' => 'Baraka Thomas',
                'email' => 'landlord3@manna.com',
                'phone' => '0765234567',
                'role' => 'landlord',
                'email_verified_at' => $now->copy()->subMonths(3)->addDays(15),
                'created_at' => $now->copy()->subMonths(3)->addDays(15),
                'updated_at' => $now->copy()->subMonths(3)->addDays(15),
            ],
            // Agents
            [
                'name' => 'Neema Charles',
                'email' => 'agent1@manna.com',
                'phone' => '0712345679',
                'role' => 'agent',
                'email_verified_at' => $now->copy()->subMonths(4)->addDays(5),
                'created_at' => $now->copy()->subMonths(4)->addDays(5),
                'updated_at' => $now->copy()->subMonths(4)->addDays(5),
            ],
            [
                'name' => 'Emmanuel John',
                'email' => 'agent2@manna.com',
                'phone' => '0620123456',
                'role' => 'agent',
                'email_verified_at' => $now->copy()->subMonths(2)->addDays(20),
                'created_at' => $now->copy()->subMonths(2)->addDays(20),
                'updated_at' => $now->copy()->subMonths(2)->addDays(20),
            ],
            // Tenants
            [
                'name' => 'Amina Juma',
                'email' => 'tenant1@manna.com',
                'phone' => '0712345680',
                'role' => 'tenant',
                'email_verified_at' => $now->copy()->subMonths(4)->addDays(12),
                'created_at' => $now->copy()->subMonths(4)->addDays(12),
                'updated_at' => $now->copy()->subMonths(4)->addDays(12),
            ],
            [
                'name' => 'David Mwangi',
                'email' => 'tenant2@manna.com',
                'phone' => '0754123456',
                'role' => 'tenant',
                'email_verified_at' => $now->copy()->subMonths(3)->addDays(5),
                'created_at' => $now->copy()->subMonths(3)->addDays(5),
                'updated_at' => $now->copy()->subMonths(3)->addDays(5),
            ],
            [
                'name' => 'Grace Peter',
                'email' => 'tenant3@manna.com',
                'phone' => '0655987654',
                'role' => 'tenant',
                'email_verified_at' => $now->copy()->subMonths(2)->addDays(8),
                'created_at' => $now->copy()->subMonths(2)->addDays(8),
                'updated_at' => $now->copy()->subMonths(2)->addDays(8),
            ],
            [
                'name' => 'Issa Mussa',
                'email' => 'tenant4@manna.com',
                'phone' => '0765345678',
                'role' => 'tenant',
                'email_verified_at' => $now->copy()->subMonth(),
                'created_at' => $now->copy()->subMonth(),
                'updated_at' => $now->copy()->subMonth(),
            ],
            // Support
            [
                'name' => 'Sarah Omary',
                'email' => 'support@manna.com',
                'phone' => '0712000003',
                'role' => 'support',
                'email_verified_at' => $now->copy()->subMonths(3)->addDays(20),
                'created_at' => $now->copy()->subMonths(3)->addDays(20),
                'updated_at' => $now->copy()->subMonths(3)->addDays(20),
            ],
            // Maintenance
            [
                'name' => 'Rajabu Hamisi',
                'email' => 'maintenance@manna.com',
                'phone' => '0712000004',
                'role' => 'maintenance',
                'email_verified_at' => $now->copy()->subMonths(2)->addDays(14),
                'created_at' => $now->copy()->subMonths(2)->addDays(14),
                'updated_at' => $now->copy()->subMonths(2)->addDays(14),
            ],
            // Accountant
            [
                'name' => 'Josephine Paul',
                'email' => 'accountant@manna.com',
                'phone' => '0712000005',
                'role' => 'accountant',
                'email_verified_at' => $now->copy()->subMonths(4)->addDays(3),
                'created_at' => $now->copy()->subMonths(4)->addDays(3),
                'updated_at' => $now->copy()->subMonths(4)->addDays(3),
            ],
            // Investor
            [
                'name' => 'Michael George',
                'email' => 'investor@manna.com',
                'phone' => '0712000006',
                'role' => 'investor',
                'email_verified_at' => $now->copy()->subWeeks(2),
                'created_at' => $now->copy()->subWeeks(2),
                'updated_at' => $now->copy()->subWeeks(2),
            ],
        ];

        $data = [];
        foreach ($users as $user) {
            $data[] = array_merge($user, [
                'password' => Hash::make('password'),
                'remember_token' => null,
            ]);
        }

        DB::table('users')->insert($data);

        $this->command->info('Users seeded successfully: ' . count($users) . ' users created.');
    }
}
