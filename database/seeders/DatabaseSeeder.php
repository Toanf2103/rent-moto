<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\RentPackage\RentPackageStatus;
use App\Enums\User\UserRole;
use App\Models\RentPackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->state([
            'email' => 'admin@gmail.com',
            'role' => UserRole::ADMIN
        ])->create();

        User::factory(1)->state([
            'email' => 'employee@gmail.com',
            'role' => UserRole::EMPLOYEE
        ])->create();

        User::factory(100)->create();

        DB::table('rent_packages')->insert([
            ['name' => 'Mùa ế', 'status' => RentPackageStatus::ACTIVE]
        ]);

        DB::table('rent_package_details')->insert([
            [
                'rent_package_id' => RentPackage::first()->id,
                'percent' => 100,
                'rent_days_min' => 1,
                'rent_days_max' => 3,
            ],
            [
                'rent_package_id' => RentPackage::first()->id,
                'percent' => 90,
                'rent_days_min' => 4,
                'rent_days_max' => 7,
            ],
            [
                'rent_package_id' => RentPackage::first()->id,
                'percent' => 80,
                'rent_days_min' => 8,
                'rent_days_max' => config('define.max_date_rent'),
            ],
        ]);

        $this->call([
            MotoSeeder::class,
        ]);
    }
}
