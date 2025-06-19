<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->faker = \Faker\Factory::create();
        // $this->createDriver(15, 'В дорозі');
        for ($i = 1; $i <= 15; $i++) {
            $this->createDriver(12, 'Відпочиває', $i);
        }
        // $this->createDriver(15, 'В відпусці');
        // $this->createDriver(15, 'Аварійна ситуація'); //exist in Seeder
        // $this->createDriver(15, 'Відпочиває');
        // $this->createDriver(15, 'Відпочиває');
    }
    private $faker;
    public function createDriver($times, $status, $station_id)
    {
        for ($i = 1; $i <= $times; $i++) {

            $is_not_unique_name = true;
            $firstName = "";
            $lastName = "";

            while ($is_not_unique_name) {
                $firstName = $this->faker->unique()->firstName;
                $lastName = $this->faker->lastName;
                $email = $firstName . '@test.com';
                if (count(User::where('email', $email)->get()) == 0)
                    $is_not_unique_name = false;
            }
            $full_name = $firstName . " " . $lastName;

            $user = User::factory()->create([
                'name' => $full_name,
                'email' => $firstName . '@test.com',
                'password' => Hash::make('12345678'),
                'role' => 'driver',
            ]);
            $days_in_vacation = $status == "В відпусці" ? rand(1, 31) : null;
            $date_of_vacation = $status == "В відпусці" ? $this->faker->dateTimeBetween('-1 month', 'now') : null;
            Driver::factory()->create([
                'full_name' => $full_name,
                'phone' => $this->faker->phoneNumber(),
                'hire_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
                'current_station' => $station_id,
                'status' => $status,
                'days_in_vacation' => $days_in_vacation,
                'date_of_vacation' => $date_of_vacation,
                'station_id' => $station_id,
                'user_id' => $user->id
            ]);
        }
    }
}
