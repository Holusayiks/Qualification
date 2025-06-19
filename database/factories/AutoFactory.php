<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auto>
 */
class AutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        $driver_id=$faker->unique()->numberBetween(1,15);
        $driver=Driver::find($driver_id);
        return [
            'nomer'=>$faker->unique()->numberBetween(1000,9999),
            'type'=>$faker->randomElement(['Цистерна','Рефрежиратор','Контейнер']),
            'lifting_weight'=>$faker->numberBetween(15000,25000),
            'driver_id'=>$driver_id,
            'station_id'=>$driver->station_id
        ];
    }
}
