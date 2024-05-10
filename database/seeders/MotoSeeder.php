<?php

namespace Database\Seeders;

use App\Models\Moto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('moto_types')->insert([
            ['name' => 'Xe ga'],
            ['name' => 'Xe số'],
            ['name' => 'Xe côn']
        ]);
        Moto::factory(1000)->create();
    }
}
