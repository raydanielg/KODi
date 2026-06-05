<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropertiesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $landlords = [3, 4, 5];
        $agents = [6, 7];

        $properties = [
            [
                'user_id' => $landlords[0],
                'agent_id' => $agents[0],
                'title' => 'Nyumba ya Kisasa Kariakoo',
                'description' => 'Nyumba nzuri na safi iliyojengwa kwa kisasa. Ina vyumba vitatu vya kulala, sebule kubwa, jiko la kisasa, na bafu mbili. Ipo Kariakoo karibu na soko kuu na usafiri wa umma. Nyumba ina maji ya bomba, umeme, na wifi. Inapatikana kwa kodi ya TZS 1,500,000 kwa mwezi. Karibu kutembelea!',
                'property_type' => 'house',
                'status' => 'available',
                'price' => 1500000,
                'deposit' => 1500000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqft' => 1200,
                'location_area' => 'Kariakoo',
                'location_city' => 'Dar es Salaam',
                'location_region' => 'Dar es Salaam',
                'location_address' => 'Mtaa wa Market, Kariakoo, Dar es Salaam',
                'latitude' => -6.8195,
                'longitude' => 39.2802,
                'is_furnished' => true,
                'has_internet' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_parking' => true,
                'has_security' => false,
                'has_generator' => false,
                'is_featured' => true,
                'views_count' => 234,
                'approved_at' => $now->copy()->subMonths(4)->addDays(5),
                'created_at' => $now->copy()->subMonths(4)->addDays(3),
                'updated_at' => $now->copy()->subMonths(4)->addDays(3),
            ],
            [
                'user_id' => $landlords[0],
                'agent_id' => null,
                'title' => 'Studio Nzuri Mbezi',
                'description' => 'Studio nzuri na ya kisasa Mbezi Beach. Ina chumba kimoja cha kulala, jiko dogo, na bafu. Ipo karibu na hoteli za Mbezi na ufukwe. Inafaa kwa mtu mmoja au wanandoa. Kodi ni TZS 350,000 kwa mwezi. Maji na umeme vipo.',
                'property_type' => 'room',
                'status' => 'available',
                'price' => 350000,
                'deposit' => 350000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'area_sqft' => 350,
                'location_area' => 'Mbezi',
                'location_city' => 'Dar es Salaam',
                'location_region' => 'Dar es Salaam',
                'location_address' => 'Mbezi Beach, Dar es Salaam',
                'latitude' => -6.7411,
                'longitude' => 39.2329,
                'is_furnished' => true,
                'has_internet' => false,
                'has_water' => true,
                'has_electricity' => true,
                'has_parking' => false,
                'has_security' => false,
                'has_generator' => false,
                'is_featured' => false,
                'views_count' => 89,
                'approved_at' => $now->copy()->subMonths(4)->addDays(12),
                'created_at' => $now->copy()->subMonths(4)->addDays(10),
                'updated_at' => $now->copy()->subMonths(4)->addDays(10),
            ],
            [
                'user_id' => $landlords[1],
                'agent_id' => $agents[1],
                'title' => 'Gorofa La Posta',
                'description' => 'Gorofa nzuri sana ipo Posta, Dar es Salaam. Ghorofa ya 3 ina vyumba vinne vya kulala, sebule kubwa, jiko la kisasa, na bafu tatu. Ina mtazamo mzuri wa jiji. Karibu na benki, maduka, na stesheni ya mabasi. Inapatikana kwa kodi ya TZS 2,500,000 kwa mwezi.',
                'property_type' => 'apartment',
                'status' => 'rented',
                'price' => 2500000,
                'deposit' => 2500000,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area_sqft' => 1800,
                'location_area' => 'Posta',
                'location_city' => 'Dar es Salaam',
                'location_region' => 'Dar es Salaam',
                'location_address' => 'Mtaa wa Samora Avenue, Posta, Dar es Salaam',
                'latitude' => -6.8157,
                'longitude' => 39.2915,
                'is_furnished' => true,
                'has_internet' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_parking' => true,
                'has_security' => true,
                'has_generator' => true,
                'is_featured' => true,
                'views_count' => 412,
                'approved_at' => $now->copy()->subMonths(3)->addDays(10),
                'created_at' => $now->copy()->subMonths(3)->addDays(8),
                'updated_at' => $now->copy()->subMonths(3)->addDays(8),
            ],
            [
                'user_id' => $landlords[2],
                'agent_id' => null,
                'title' => 'Nyumba ya Familia Kijitonyama',
                'description' => 'Nyumba nzuri ya familia ipo Kijitonyama. Ina vyumba vinne vya kulala, sebule kubwa, jiko, bafu mbili, na karakana. Uwanja wa nyuma ni mkubwa kwa ajili ya watoto kucheza. Ipo katika eneo tulivu na salama. Kodi ni TZS 2,000,000 kwa mwezi.',
                'property_type' => 'house',
                'status' => 'available',
                'price' => 2000000,
                'deposit' => 1000000,
                'bedrooms' => 4,
                'bathrooms' => 2,
                'area_sqft' => 1500,
                'location_area' => 'Kijitonyama',
                'location_city' => 'Dar es Salaam',
                'location_region' => 'Dar es Salaam',
                'location_address' => 'Kijitonyama, Dar es Salaam',
                'latitude' => -6.7875,
                'longitude' => 39.2571,
                'is_furnished' => false,
                'has_internet' => false,
                'has_water' => true,
                'has_electricity' => true,
                'has_parking' => true,
                'has_security' => true,
                'has_generator' => false,
                'is_featured' => false,
                'views_count' => 156,
                'approved_at' => $now->copy()->subMonths(3)->addDays(20),
                'created_at' => $now->copy()->subMonths(3)->addDays(18),
                'updated_at' => $now->copy()->subMonths(3)->addDays(18),
            ],
            [
                'user_id' => $landlords[1],
                'agent_id' => $agents[0],
                'title' => 'Flat la Kisasa Njiro',
                'description' => 'Flat nzuri na ya kisasa ipo Njiro, Arusha. Ina vyumba viwili vya kulala, sebule, jiko la kisasa, na bafu mbili. Eneo ni tulivu na salama, karibu na maduka na shule. Kodi ni TZS 1,200,000 kwa mwezi. Maji na umeme vimewekwa.',
                'property_type' => 'apartment',
                'status' => 'available',
                'price' => 1200000,
                'deposit' => 600000,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area_sqft' => 900,
                'location_area' => 'Njiro',
                'location_city' => 'Arusha',
                'location_region' => 'Arusha',
                'location_address' => 'Njiro, Arusha',
                'latitude' => -3.3833,
                'longitude' => 36.6833,
                'is_furnished' => true,
                'has_internet' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_parking' => true,
                'has_security' => true,
                'has_generator' => false,
                'is_featured' => true,
                'views_count' => 198,
                'approved_at' => $now->copy()->subMonths(2)->addDays(15),
                'created_at' => $now->copy()->subMonths(2)->addDays(13),
                'updated_at' => $now->copy()->subMonths(2)->addDays(13),
            ],
            [
                'user_id' => $landlords[0],
                'agent_id' => null,
                'title' => 'Chumba Cha Kukodi Mwanza',
                'description' => 'Chumba kimoja cha kukodi ipo Mwanza. Ina bed, meza, na kabati. Bafu na jiko ni za kushiriki. Ipo karibu na kituo cha mabasi na sokoni. Kodi ni TZS 200,000 kwa mwezi. Inafaa kwa mtu mmoja anayetafuta malazi ya bei rahisi.',
                'property_type' => 'room',
                'status' => 'under_maintenance',
                'price' => 200000,
                'deposit' => 200000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'area_sqft' => 180,
                'location_area' => 'Mwanza',
                'location_city' => 'Mwanza',
                'location_region' => 'Mwanza',
                'location_address' => 'Mwanza, Tanzania',
                'latitude' => -2.5164,
                'longitude' => 32.9014,
                'is_furnished' => true,
                'has_internet' => false,
                'has_water' => true,
                'has_electricity' => true,
                'has_parking' => false,
                'has_security' => false,
                'has_generator' => false,
                'is_featured' => false,
                'views_count' => 45,
                'approved_at' => $now->copy()->subMonths(2)->addDays(5),
                'created_at' => $now->copy()->subMonths(2)->addDays(3),
                'updated_at' => $now->copy()->subMonths(2)->addDays(3),
            ],
            [
                'user_id' => $landlords[2],
                'agent_id' => $agents[1],
                'title' => 'Villa ya Kifahari Mbezi',
                'description' => 'Villa ya kifahari kabisa ipo Mbezi, Dar es Salaam. Ina vyumba vitano vya kulala, sebule kubwa, jiko la kisasa la kikabati, bafu nne, bwawa la kuogelea, na bustani nzuri. Nyumba ina maji ya bomba, umeme, jenereta, wifi, na ulinzi wa saa 24. Kodi ni TZS 3,500,000 kwa mwezi. Nyumba hii inafaa kwa familia kubwa au kampuni inayotafuta makazi ya kifahari.',
                'property_type' => 'house',
                'status' => 'available',
                'price' => 3500000,
                'deposit' => 3500000,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'area_sqft' => 3200,
                'location_area' => 'Mbezi',
                'location_city' => 'Dar es Salaam',
                'location_region' => 'Dar es Salaam',
                'location_address' => 'Mbezi Beach, Dar es Salaam',
                'latitude' => -6.7384,
                'longitude' => 39.2294,
                'is_furnished' => true,
                'has_internet' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_parking' => true,
                'has_security' => true,
                'has_generator' => true,
                'is_featured' => true,
                'views_count' => 567,
                'approved_at' => $now->copy()->subMonths(1)->addDays(20),
                'created_at' => $now->copy()->subMonths(1)->addDays(18),
                'updated_at' => $now->copy()->subMonths(1)->addDays(18),
            ],
            [
                'user_id' => $landlords[1],
                'agent_id' => null,
                'title' => 'Nyumba Ndogo Dodoma',
                'description' => 'Nyumba ndogo na nzuri ipo Dodoma. Ina vyumba viwili vya kulala, sebule, jiko, na bafu moja. Ipo karibu na Ikulu na benki kuu. Nyumba ina ua mdogo wa nyuma na karakana. Kodi ni TZS 800,000 kwa mwezi. Inafaa kwa familia ndogo.',
                'property_type' => 'house',
                'status' => 'rented',
                'price' => 800000,
                'deposit' => 400000,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'area_sqft' => 700,
                'location_area' => 'Dodoma',
                'location_city' => 'Dodoma',
                'location_region' => 'Dodoma',
                'location_address' => 'Dodoma, Tanzania',
                'latitude' => -6.1621,
                'longitude' => 35.7516,
                'is_furnished' => false,
                'has_internet' => false,
                'has_water' => true,
                'has_electricity' => true,
                'has_parking' => true,
                'has_security' => false,
                'has_generator' => false,
                'is_featured' => false,
                'views_count' => 112,
                'approved_at' => $now->copy()->subMonth()->addDays(10),
                'created_at' => $now->copy()->subMonth()->addDays(8),
                'updated_at' => $now->copy()->subMonth()->addDays(8),
            ],
            [
                'user_id' => $landlords[2],
                'agent_id' => null,
                'title' => 'Ghorofa la Biashara Dar',
                'description' => 'Ghorofa la biashara (commercial) ipo Dar es Salaam. Ina nafasi kubwa ya ofisi, vyoo viwili, na chumba cha mkutano. Ipo katika eneo la biashara lenye shughuli nyingi. Inafaa kwa ofisi ya kampuni, duka kubwa, au benki. Kodi ni TZS 3,000,000 kwa mwezi. Maji na umeme vipo, parking inapatikana.',
                'property_type' => 'commercial',
                'status' => 'available',
                'price' => 3000000,
                'deposit' => 3000000,
                'bedrooms' => 0,
                'bathrooms' => 2,
                'area_sqft' => 2500,
                'location_area' => 'Dar es Salaam',
                'location_city' => 'Dar es Salaam',
                'location_region' => 'Dar es Salaam',
                'location_address' => 'Dar es Salaam, Tanzania',
                'latitude' => -6.8135,
                'longitude' => 39.2882,
                'is_furnished' => false,
                'has_internet' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_parking' => true,
                'has_security' => true,
                'has_generator' => true,
                'is_featured' => true,
                'views_count' => 321,
                'approved_at' => $now->copy()->subWeeks(2)->addDays(3),
                'created_at' => $now->copy()->subWeeks(2)->addDays(1),
                'updated_at' => $now->copy()->subWeeks(2)->addDays(1),
            ],
            [
                'user_id' => $landlords[0],
                'agent_id' => $agents[0],
                'title' => 'Banda la Watalii Mbeya',
                'description' => 'Banda zuri la watalii ipo Mbeya mjini. Ina vyumba vitatu vya kulala, sebule ya kisasa, jiko, na bafu mbili. Mtazamo wa milima ni mzuri sana. Ipo karibu na hoteli na migahawa. Kodi ni TZS 1,800,000 kwa mwezi. Nyumba ina jenereta ya umeme na ulinzi.',
                'property_type' => 'house',
                'status' => 'available',
                'price' => 1800000,
                'deposit' => 900000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqft' => 1300,
                'location_area' => 'Mbeya',
                'location_city' => 'Mbeya',
                'location_region' => 'Mbeya',
                'location_address' => 'Mbeya, Tanzania',
                'latitude' => -8.9067,
                'longitude' => 33.4567,
                'is_furnished' => true,
                'has_internet' => true,
                'has_water' => true,
                'has_electricity' => true,
                'has_parking' => true,
                'has_security' => true,
                'has_generator' => true,
                'is_featured' => false,
                'views_count' => 78,
                'approved_at' => $now->copy()->subDays(5),
                'created_at' => $now->copy()->subDays(7),
                'updated_at' => $now->copy()->subDays(7),
            ],
        ];

        $amenitiesPool = [
            'Maji' => 'water',
            'Umeme' => 'electricity',
            'Parking' => 'car',
            'Wifi / Internet' => 'wifi',
            'Jenereta' => 'generator',
            'Ulinzi / Security' => 'shield-alt',
            'Jiko la Kisasa' => 'utensils',
            'Fridge / Jokofu' => 'snowflake',
            'TV / Televisheni' => 'tv',
            'Kibati / Wardrobe' => 'box',
            'Swimming Pool' => 'swimmer',
            'Bustani / Garden' => 'leaf',
            'AC / Kiyoyozi' => 'wind',
            'Sofa Set' => 'couch',
            'Dining Table' => 'table',
        ];

        $propertyId = DB::table('properties')->insertGetId([]);
        $firstId = null;

        foreach ($properties as $i => $property) {
            $slug = Str::slug($property['title']) . '-' . ($now->timestamp + $i);
            $property['slug'] = $slug;

            $id = DB::table('properties')->insertGetId($property);

            if ($i === 0) {
                $firstId = $id;
            }

            $images = [];
            $imageCount = rand(2, 3);
            for ($j = 1; $j <= $imageCount; $j++) {
                $images[] = [
                    'property_id' => $id,
                    'image_path' => 'properties/' . $slug . '/image-' . $j . '.jpg',
                    'is_primary' => $j === 1,
                    'sort_order' => $j,
                    'created_at' => $property['created_at'],
                    'updated_at' => $property['updated_at'],
                ];
            }
            DB::table('property_images')->insert($images);

            $selectedAmenities = $this->getRandomAmenities($amenitiesPool, $property);
            $amenityRows = [];
            foreach ($selectedAmenities as $name => $icon) {
                $amenityRows[] = [
                    'property_id' => $id,
                    'amenity_name' => $name,
                    'amenity_icon' => $icon,
                    'created_at' => $property['created_at'],
                    'updated_at' => $property['updated_at'],
                ];
            }
            DB::table('property_amenities')->insert($amenityRows);
        }

        $this->command->info('Properties seeded successfully: ' . count($properties) . ' properties created.');
    }

    private function getRandomAmenities(array $pool, array $property): array
    {
        $mandatoryNames = ['Maji', 'Umeme'];
        $poolNames = array_keys($pool);

        $selected = [];
        foreach ($mandatoryNames as $name) {
            if (isset($pool[$name])) {
                $selected[$name] = $pool[$name];
            }
        }

        $remainingNames = array_diff($poolNames, $mandatoryNames);

        if (!empty($property['has_parking'])) {
            $selected['Parking'] = $pool['Parking'];
            $remainingNames = array_diff($remainingNames, ['Parking']);
        }
        if (!empty($property['has_internet'])) {
            $selected['Wifi / Internet'] = $pool['Wifi / Internet'];
            $remainingNames = array_diff($remainingNames, ['Wifi / Internet']);
        }
        if (!empty($property['has_generator'])) {
            $selected['Jenereta'] = $pool['Jenereta'];
            $remainingNames = array_diff($remainingNames, ['Jenereta']);
        }
        if (!empty($property['has_security'])) {
            $selected['Ulinzi / Security'] = $pool['Ulinzi / Security'];
            $remainingNames = array_diff($remainingNames, ['Ulinzi / Security']);
        }

        $extraCount = rand(1, 2);
        $extraNames = array_rand(array_flip($remainingNames), min($extraCount, count($remainingNames)));
        if (is_array($extraNames)) {
            foreach ($extraNames as $name) {
                $selected[$name] = $pool[$name];
            }
        } elseif (is_string($extraNames)) {
            $selected[$extraNames] = $pool[$extraNames];
        }

        return $selected;
    }
}
