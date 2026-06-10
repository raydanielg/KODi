<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use App\Models\Lease;
use App\Models\RentPayment;
use App\Models\MaintenanceRequest;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Comprehensive sample data seeder for the Manna / KODI platform.
 * Creates realistic Tanzanian landlord, tenants, properties, leases and payments.
 *
 * Run with: php artisan db:seed --class=SampleDataSeeder
 */
class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ─── 1. Landlord ──────────────────────────────────────────────────────
        $landlord = User::firstOrCreate(
            ['email' => 'landlord@manna.co.tz'],
            [
                'name'              => 'Juma Mohamed Rashid',
                'phone'             => '+255712345678',
                'role'              => 'landlord',
                'password'          => Hash::make('password'),
                'is_subscribed'     => true,
                'subscription_plan' => 'professional',
                'subscription_until'=> $now->copy()->addYear(),
                'email_verified_at' => $now->copy()->subMonths(6),
                'gender'            => 'male',
                'created_at'        => $now->copy()->subMonths(8),
            ]
        );

        $landlord2 = User::firstOrCreate(
            ['email' => 'bibi.nyumba@manna.co.tz'],
            [
                'name'              => 'Fatuma Ali Kombo',
                'phone'             => '+255755123456',
                'role'              => 'landlord',
                'password'          => Hash::make('password'),
                'is_subscribed'     => true,
                'subscription_plan' => 'starter',
                'subscription_until'=> $now->copy()->addMonths(3),
                'email_verified_at' => $now->copy()->subMonths(3),
                'gender'            => 'female',
                'created_at'        => $now->copy()->subMonths(3),
            ]
        );

        // ─── 2. Tenants ───────────────────────────────────────────────────────
        $tenantsData = [
            ['name' => 'Ahmed Salim Bakari',    'email' => 'ahmed.salim@gmail.com',    'phone' => '+255756123001', 'gender' => 'male'],
            ['name' => 'Grace Mwangi Wangari',  'email' => 'grace.mwangi@gmail.com',   'phone' => '+255712345002', 'gender' => 'female'],
            ['name' => 'Baraka Ndugu Hamisi',   'email' => 'baraka.ndugu@yahoo.com',   'phone' => '+255768000003', 'gender' => 'male'],
            ['name' => 'Amina Juma Seif',       'email' => 'amina.juma@gmail.com',     'phone' => '+255756000004', 'gender' => 'female'],
            ['name' => 'Charles Maina Kamau',   'email' => 'charles.maina@gmail.com',  'phone' => '+255712000005', 'gender' => 'male'],
            ['name' => 'Zawadi Hassan Omar',    'email' => 'zawadi.hassan@gmail.com',  'phone' => '+255768000006', 'gender' => 'female'],
            ['name' => 'Peter Kimani Njoroge',  'email' => 'peter.kimani@outlook.com', 'phone' => '+255756000007', 'gender' => 'male'],
            ['name' => 'Halima Said Mwinyi',    'email' => 'halima.said@gmail.com',    'phone' => '+255712000008', 'gender' => 'female'],
        ];

        $tenants = [];
        foreach ($tenantsData as $td) {
            $tenants[] = User::firstOrCreate(
                ['email' => $td['email']],
                [
                    'name'             => $td['name'],
                    'phone'            => $td['phone'],
                    'role'             => 'tenant',
                    'password'         => Hash::make('password'),
                    'gender'           => $td['gender'],
                    'email_verified_at'=> $now->copy()->subMonths(rand(1, 5)),
                    'created_at'       => $now->copy()->subMonths(rand(2, 7)),
                ]
            );
        }

        // ─── 3. Properties ────────────────────────────────────────────────────
        $propertiesData = [
            [
                'title'            => 'Ghorofa ya Kisasa - Kariakoo Block A',
                'description'      => 'Ghorofa nzuri na ya kisasa ipo Kariakoo, karibu na soko kuu la Kariakoo. Ina vyumba vitatu vya kulala, sebule ya kisasa, jiko kubwa, na bafu mbili. Nyumba ina maji ya bomba saa 24, umeme wa TANESCO na generator ya dharura, WiFi ya haraka sana. Parking ipo. Mazingira ya usalama na utulivu. Karibu kuja kuona!',
                'property_type'    => 'apartment',
                'status'           => 'rented',
                'price'            => 850000,
                'deposit'          => 1700000,
                'bedrooms'         => 3,
                'bathrooms'        => 2,
                'area_sqft'        => 1200,
                'location_area'    => 'Kariakoo',
                'location_city'    => 'Dar es Salaam',
                'location_region'  => 'Dar es Salaam',
                'location_address' => 'Barabara ya Mkunguni, Nyumba No. 15',
                'currency'         => 'TZS',
                'is_furnished'     => false,
                'has_water'        => true,
                'has_electricity'  => true,
                'has_internet'     => true,
                'has_parking'      => true,
                'has_security'     => true,
                'has_generator'    => true,
                'is_featured'      => true,
            ],
            [
                'title'            => 'Nyumba ya Familia - Sinza Mwisho',
                'description'      => 'Nyumba nzuri ya familia yenye bustani na ukumbi. Ina vyumba 4, sebule 2, jiko kubwa, na stoo. Ipo Sinza Mwisho karibu na shule na hospitali. Maji, umeme, na usalama mzuri. Nzuri kwa familia.',
                'property_type'    => 'house',
                'status'           => 'rented',
                'price'            => 650000,
                'deposit'          => 1300000,
                'bedrooms'         => 4,
                'bathrooms'        => 2,
                'area_sqft'        => 1800,
                'location_area'    => 'Sinza',
                'location_city'    => 'Dar es Salaam',
                'location_region'  => 'Dar es Salaam',
                'location_address' => 'Mtaa wa Sinza Mwisho, Barabara ya Mwenge',
                'currency'         => 'TZS',
                'is_furnished'     => false,
                'has_water'        => true,
                'has_electricity'  => true,
                'has_internet'     => false,
                'has_parking'      => true,
                'has_security'     => false,
                'has_generator'    => false,
                'is_featured'      => false,
            ],
            [
                'title'            => 'Studio ya Kisasa - Masaki Waterfront',
                'description'      => 'Studio nzuri sana ipo Masaki karibu na bahari. Vifaa vya kisasa, A/C, internet ya haraka. Nzuri kwa vijana wanaofanya kazi na wageni wa muda mrefu. Usalama wa hali ya juu, parking, na gym.',
                'property_type'    => 'apartment',
                'status'           => 'available',
                'price'            => 1200000,
                'deposit'          => 2400000,
                'bedrooms'         => 1,
                'bathrooms'        => 1,
                'area_sqft'        => 650,
                'location_area'    => 'Masaki',
                'location_city'    => 'Dar es Salaam',
                'location_region'  => 'Dar es Salaam',
                'location_address' => 'Haile Selasie Road, Masaki',
                'currency'         => 'TZS',
                'is_furnished'     => true,
                'has_water'        => true,
                'has_electricity'  => true,
                'has_internet'     => true,
                'has_parking'      => true,
                'has_security'     => true,
                'has_generator'    => true,
                'is_featured'      => true,
            ],
            [
                'title'            => 'Chumba cha Kujitegemea - Kinondoni',
                'description'      => 'Chumba kizuri na cha kujitegemea kilichopo Kinondoni. Kina bafu yake na jiko dogo. Maji ya bomba na umeme. Usafiri rahisi kwenda mjini. Nzuri kwa wanafunzi na wafanyakazi wapweke.',
                'property_type'    => 'room',
                'status'           => 'rented',
                'price'            => 180000,
                'deposit'          => 360000,
                'bedrooms'         => 1,
                'bathrooms'        => 1,
                'area_sqft'        => 280,
                'location_area'    => 'Kinondoni',
                'location_city'    => 'Dar es Salaam',
                'location_region'  => 'Dar es Salaam',
                'location_address' => 'Mtaa wa Kinondoni, Nyumba No. 42B',
                'currency'         => 'TZS',
                'is_furnished'     => false,
                'has_water'        => true,
                'has_electricity'  => true,
                'has_internet'     => false,
                'has_parking'      => false,
                'has_security'     => false,
                'has_generator'    => false,
                'is_featured'      => false,
            ],
            [
                'title'            => 'Ofisi ya Biashara - Posta / CBD',
                'description'      => 'Nafasi nzuri ya ofisi ipo kituo cha biashara cha Dar es Salaam. Orofa ya 3, maoni mazuri, maji, umeme, lift, A/C na internet ya haraka. Nzuri kwa makampuni ya biashara, madaktari, na washauri.',
                'property_type'    => 'commercial',
                'status'           => 'available',
                'price'            => 2500000,
                'deposit'          => 5000000,
                'bedrooms'         => 0,
                'bathrooms'        => 2,
                'area_sqft'        => 2400,
                'location_area'    => 'Posta',
                'location_city'    => 'Dar es Salaam',
                'location_region'  => 'Dar es Salaam',
                'location_address' => 'Samora Avenue, CBD - Floor 3',
                'currency'         => 'TZS',
                'is_furnished'     => true,
                'has_water'        => true,
                'has_electricity'  => true,
                'has_internet'     => true,
                'has_parking'      => true,
                'has_security'     => true,
                'has_generator'    => true,
                'is_featured'      => false,
            ],
            [
                'title'            => 'Nyumba ya Gorofa 2 - Mikocheni B',
                'description'      => 'Nyumba nzuri na pana ipo Mikocheni B. Ghorofa ya 2 yenye balcony, vyumba 3, sebule kubwa, na jiko kisasa. Ina tank la maji na solar panels. Mazingira mazuri na ya utulivu. Karibu na barabara kuu.',
                'property_type'    => 'house',
                'status'           => 'rented',
                'price'            => 950000,
                'deposit'          => 1900000,
                'bedrooms'         => 3,
                'bathrooms'        => 2,
                'area_sqft'        => 1600,
                'location_area'    => 'Mikocheni',
                'location_city'    => 'Dar es Salaam',
                'location_region'  => 'Dar es Salaam',
                'location_address' => 'Mikocheni B Road, Plot No. 8',
                'currency'         => 'TZS',
                'is_furnished'     => false,
                'has_water'        => true,
                'has_electricity'  => true,
                'has_internet'     => true,
                'has_parking'      => true,
                'has_security'     => true,
                'has_generator'    => false,
                'is_featured'      => true,
            ],
            [
                'title'            => 'Apartment ya Bei Nafuu - Buguruni',
                'description'      => 'Apartment nzuri na ya bei nafuu ipo Buguruni. Vyumba 2 vya kulala, sebule, jiko na bafu moja. Usafiri rahisi. Nzuri kwa familia ndogo.',
                'property_type'    => 'apartment',
                'status'           => 'available',
                'price'            => 320000,
                'deposit'          => 640000,
                'bedrooms'         => 2,
                'bathrooms'        => 1,
                'area_sqft'        => 750,
                'location_area'    => 'Buguruni',
                'location_city'    => 'Dar es Salaam',
                'location_region'  => 'Dar es Salaam',
                'location_address' => 'Buguruni Malapa, Nyumba 7',
                'currency'         => 'TZS',
                'is_furnished'     => false,
                'has_water'        => true,
                'has_electricity'  => true,
                'has_internet'     => false,
                'has_parking'      => false,
                'has_security'     => false,
                'has_generator'    => false,
                'is_featured'      => false,
            ],
            [
                'title'            => 'Nyumba ya Starehe - Oyster Bay',
                'description'      => 'Nyumba ya kifahari ipo Oyster Bay karibu na bahari. Vyumba 5 vya kulala, sebule 2, dining room, jiko la kisasa, bustani kubwa na bwawa la kuogelea. Inalindwa na usalama wa 24h. Nzuri kwa familia za wasomi na wadiplomasia.',
                'property_type'    => 'house',
                'status'           => 'rented',
                'price'            => 4500000,
                'deposit'          => 9000000,
                'bedrooms'         => 5,
                'bathrooms'        => 4,
                'area_sqft'        => 4500,
                'location_area'    => 'Oyster Bay',
                'location_city'    => 'Dar es Salaam',
                'location_region'  => 'Dar es Salaam',
                'location_address' => 'Ocean Road, Oyster Bay - Lote No. 3',
                'currency'         => 'TZS',
                'is_furnished'     => true,
                'has_water'        => true,
                'has_electricity'  => true,
                'has_internet'     => true,
                'has_parking'      => true,
                'has_security'     => true,
                'has_generator'    => true,
                'is_featured'      => true,
            ],
        ];

        $properties = [];
        foreach ($propertiesData as $i => $pd) {
            $ownerId = ($i % 3 === 0) ? $landlord2->id : $landlord->id;
            $existing = Property::where('title', $pd['title'])->first();
            if (!$existing) {
                $prop = Property::create(array_merge($pd, [
                    'user_id'    => $ownerId,
                    'slug'       => Str::slug($pd['title']) . '-' . Str::random(6),
                    'approved_at'=> $now->copy()->subMonths(rand(2, 8)),
                    'views_count'=> rand(10, 500),
                    'created_at' => $now->copy()->subMonths(rand(3, 9)),
                    'updated_at' => $now->copy()->subWeeks(rand(1, 4)),
                ]));
                $properties[] = $prop;
            } else {
                $properties[] = $existing;
            }
        }

        // ─── 4. Leases ────────────────────────────────────────────────────────
        $rentedProperties = collect($properties)->where('status', 'rented')->values();
        $leaseCreated = [];

        foreach ($rentedProperties as $idx => $prop) {
            if (!isset($tenants[$idx])) continue;
            $tenant  = $tenants[$idx];
            $ownerId = $prop->user_id;
            $start   = $now->copy()->subMonths(rand(2, 8))->startOfMonth();
            $end     = $start->copy()->addYear();

            $existing = Lease::where('property_id', $prop->id)->where('tenant_id', $tenant->id)->first();
            if (!$existing) {
                $lease = Lease::create([
                    'property_id'      => $prop->id,
                    'tenant_id'        => $tenant->id,
                    'landlord_id'      => $ownerId,
                    'lease_number'     => 'LS-' . strtoupper(Str::random(8)),
                    'status'           => 'active',
                    'start_date'       => $start->toDateString(),
                    'end_date'         => $end->toDateString(),
                    'rent_amount'      => $prop->price,
                    'deposit_amount'   => $prop->deposit ?? ($prop->price * 2),
                    'deposit_paid'     => true,
                    'currency'         => 'TZS',
                    'payment_frequency'=> 'monthly',
                    'due_day'          => 1,
                    'late_fee'         => 10000,
                    'penalty_percent'  => 5,
                    'created_at'       => $start,
                    'updated_at'       => $start,
                ]);
                $leaseCreated[] = $lease;

                // Mark property as rented
                $prop->update(['status' => 'rented']);
            } else {
                $leaseCreated[] = $existing;
            }
        }

        // ─── 5. Rent Payments ─────────────────────────────────────────────────
        foreach ($leaseCreated as $i => $lease) {
            $leaseStart = Carbon::parse($lease->start_date);
            $monthsActive = $leaseStart->diffInMonths($now);

            // Create payments for past months (some paid, some not)
            for ($m = 0; $m < min($monthsActive, 6); $m++) {
                $payDate  = $leaseStart->copy()->addMonths($m)->setDay(rand(1, 8));
                $isPaid   = ($m < $monthsActive - 1) || ($i % 3 !== 0); // most paid

                if ($isPaid) {
                    $exists = RentPayment::where('lease_id', $lease->id)
                        ->whereYear('paid_at', $payDate->year)
                        ->whereMonth('paid_at', $payDate->month)
                        ->exists();

                    if (!$exists) {
                        RentPayment::create([
                            'lease_id'       => $lease->id,
                            'tenant_id'      => $lease->tenant_id,
                            'landlord_id'    => $lease->landlord_id,
                            'amount'         => $lease->rent_amount,
                            'currency'       => 'TZS',
                            'payment_method' => ['mpesa', 'cash', 'bank_transfer'][rand(0, 2)],
                            'status'         => 'completed',
                            'paid_at'        => $payDate,
                            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                            'notes'          => 'Malipo ya kodi - ' . $payDate->format('F Y'),
                            'created_at'     => $payDate,
                            'updated_at'     => $payDate,
                        ]);
                    }
                }
            }
        }

        // ─── 6. Maintenance Requests ──────────────────────────────────────────
        $maintenanceData = [
            ['title' => 'Bomba la maji limevunjika', 'desc' => 'Bomba la maji la bafu linawaka maji. Inahitaji fundi wa haraka.', 'status' => 'open', 'priority' => 'high'],
            ['title' => 'Umeme wa sebule hauendi', 'desc' => 'Umeme wa taa za sebule zimekwama. Fuse imeungua labda.', 'status' => 'in_progress', 'priority' => 'medium'],
            ['title' => 'Mlango wa nyuma umeharibika', 'desc' => 'Kufuli ya mlango wa nyuma imevunjika. Haifungwi vizuri.', 'status' => 'open', 'priority' => 'medium'],
            ['title' => 'Dari ya jikoni inapita maji', 'desc' => 'Wakati wa mvua, maji yanaingia jikoni kutoka dari.', 'status' => 'completed', 'priority' => 'high'],
            ['title' => 'AC inatoa kelele', 'desc' => 'AC ya chumba cha kulala inatoa sauti ya kelele kubwa usiku.', 'status' => 'open', 'priority' => 'low'],
        ];

        foreach ($maintenanceData as $i => $md) {
            if (!isset($leaseCreated[$i])) continue;
            $lease = $leaseCreated[$i];

            $exists = MaintenanceRequest::where('property_id', $lease->property_id)
                ->where('title', $md['title'])->exists();

            if (!$exists) {
                MaintenanceRequest::create([
                    'property_id'  => $lease->property_id,
                    'tenant_id'    => $lease->tenant_id,
                    'landlord_id'  => $lease->landlord_id,
                    'title'        => $md['title'],
                    'description'  => $md['desc'],
                    'status'       => $md['status'],
                    'priority'     => $md['priority'],
                    'created_at'   => $now->copy()->subDays(rand(1, 30)),
                    'updated_at'   => $now->copy()->subDays(rand(0, 5)),
                ]);
            }
        }

        $this->command->info('✅ SampleDataSeeder completed!');
        $this->command->info("   Landlords : 2");
        $this->command->info("   Properties: " . count($properties));
        $this->command->info("   Tenants   : " . count($tenants));
        $this->command->info("   Leases    : " . count($leaseCreated));
        $this->command->info("   Login     : landlord@manna.co.tz / password");
    }
}
