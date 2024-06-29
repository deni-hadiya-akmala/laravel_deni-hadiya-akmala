<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\RumahSakit;
use Faker\Factory as Faker;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        $rumahSakitIds = RumahSakit::pluck('id')->toArray();
        
        for ($i = 0; $i < 20; $i++) {
            Pasien::create([
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'no_telepon' => $faker->phoneNumber,
                'rumah_sakit_id' => $faker->randomElement($rumahSakitIds),
            ]);
        }
    }
}
