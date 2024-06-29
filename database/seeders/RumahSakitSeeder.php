<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RumahSakit;
use Faker\Factory as Faker;

class RumahSakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        
        for ($i = 0; $i < 20; $i++) {
            RumahSakit::create([
                'nama' => $faker->company,
                'alamat' => $faker->address,
                'email' => $faker->companyEmail,
                'telepon' => $faker->phoneNumber,
            ]);
        }
    }
}
