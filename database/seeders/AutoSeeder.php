<?php

namespace Database\Seeders;

use App\Models\Auto;
use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        Auto::truncate();

        for ($station_id = 1; $station_id <= 15; $station_id++) {
            $drivers = Driver::where('station_id', $station_id)->get();

            for ($i = 0; $i < 15; $i++) {
                $driver_id = null;
                if ($i < 10 && $drivers->count() > 0) {
                    $driver = $faker->randomElement($drivers);
                    $drivers = $drivers->filter(function ($value, $key) use ($driver) {
                        return $value->id != $driver->id;
                    })->values(); // скидаємо ключі після фільтрації
                    $driver_id = $driver->id;
                }
                $type = 'Контейнер';
                if($i<3) $type='Рефрежиратор';
                else if($i<6) $type='Цистерна';
                else if($i<10) $type='Контейнер';
                else $type = $faker->randomElement(['Рефрежиратор','Цистерна','Контейнер']);
                Auto::create([
                    'nomer' => $faker->unique()->numberBetween(1000, 9999),
                    'type' => $type,
                    'lifting_weight' => $type != 'Цистерна' ? $faker->numberBetween(15000, 25000) : 11000,
                    'driver_id' => $driver_id,
                    'station_id' => $station_id
                ]);
            }
        }
    }
}
