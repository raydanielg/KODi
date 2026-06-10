<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            PropertiesSeeder::class,
            AmenitiesSeeder::class,
            LeasesSeeder::class,
            ApplicationsSeeder::class,
            RentPaymentsSeeder::class,
            MaintenanceRequestsSeeder::class,
            SampleDataSeeder::class,
        ]);
    }
}
