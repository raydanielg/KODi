<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AmenitiesSeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            ['name' => 'Maji', 'icon' => 'water', 'category' => 'Utilities'],
            ['name' => 'Umeme', 'icon' => 'electricity', 'category' => 'Utilities'],
            ['name' => 'Parking', 'icon' => 'car', 'category' => 'Parking'],
            ['name' => 'Wifi / Internet', 'icon' => 'wifi', 'category' => 'Utilities'],
            ['name' => 'Jenereta', 'icon' => 'generator', 'category' => 'Utilities'],
            ['name' => 'Ulinzi / Security', 'icon' => 'shield-alt', 'category' => 'Security'],
            ['name' => 'Jiko la Kisasa', 'icon' => 'utensils', 'category' => 'Furniture'],
            ['name' => 'Fridge / Jokofu', 'icon' => 'snowflake', 'category' => 'Appliances'],
            ['name' => 'TV / Televisheni', 'icon' => 'tv', 'category' => 'Appliances'],
            ['name' => 'Kibati / Wardrobe', 'icon' => 'box', 'category' => 'Furniture'],
            ['name' => 'Swimming Pool', 'icon' => 'swimmer', 'category' => 'Recreation'],
            ['name' => 'Bustani / Garden', 'icon' => 'leaf', 'category' => 'Outdoor'],
            ['name' => 'AC / Kiyoyozi', 'icon' => 'wind', 'category' => 'Utilities'],
            ['name' => 'Sofa Set', 'icon' => 'couch', 'category' => 'Furniture'],
            ['name' => 'Dining Table', 'icon' => 'table', 'category' => 'Furniture'],
            ['name' => 'Mifereji / Drainage', 'icon' => 'water', 'category' => 'Utilities'],
            ['name' => 'Karakana / Garage', 'icon' => 'warehouse', 'category' => 'Parking'],
            ['name' => 'CCTV', 'icon' => 'video', 'category' => 'Security'],
            ['name' => 'Askari / Guard', 'icon' => 'user-shield', 'category' => 'Security'],
            ['name' => 'Kibanda / Store', 'icon' => 'archive', 'category' => 'Storage'],
        ];

        $this->command->info('Amenities reference loaded: ' . count($amenities) . ' amenity types available.');
        $this->command->info('Amenities are assigned per property in the PropertiesSeeder via the property_amenities table.');
    }
}
