<?php

namespace Database\Seeders;

use App\Models\InsuranceAccountJournal;
use Illuminate\Support\Facades\Hash;
use App\Models\Auto;
use App\Models\Dispetcher;
use App\Models\Driver;
use App\Models\Menedger;
use App\Models\Order;
use App\Models\Station;
use App\Models\Supplier;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //for station, menedger and dispetchers
        $this->faker = \Faker\Factory::create();

        $addresses = [
            "Київ, вул. Хрещатик, 1",
            "Львів, вул. Шевченка, 23",
            "Одеса, вул. Дерибасівська, 10",
            "Чернівці, вул. Героїв Майдану, 15",
            "Дніпро, пр. Дмитра Яворницького, 28",
            "Івано-Франківськ, вул. Незалежності, 24",
            "Харків, вул. Сумська, 45",
            "Запоріжжя, пр. Соборний, 12",
            "Полтава, вул. Пушкіна, 17",
            "Суми, вул. Горького, 8",
            "Миколаїв, вул. Радянська, 3",
            "Україна, Черкаси, бульвар  Шевченка, 10",
            "Кривий Ріг, вул. Михайла Грушевського, 22",
            "Хмельницький, вул. Проскурівська, 14",
            "Житомир, вул. Бернарда, 5"
        ];
        foreach ($addresses as $key => $address) {
            Station::factory()->create([
                'nomer' => $key + 1,
                'address' => $address
            ]);
        }
        DatabaseSeeder::createUser('menedger', 15);
        DatabaseSeeder::createUser('dispetcher', 15);
        DatabaseSeeder::createUser('supplier', 100);

        InsuranceAccountJournal::create([
            'user_id' => null,
            'current_sum' => 0,
        ]);
    }
    private $faker;
    public function createUser($user_type, $times)
    {

        for ($i = 1; $i <= $times; $i++) {

            $firstName = $this->faker->unique()->firstName;
            $lastName = $this->faker->lastName;
            $full_name = $firstName . " " . $lastName;

            $user = User::factory()->create([
                'name' => $full_name,
                'email' => $firstName . '@test.com',
                'password' => Hash::make('12345678'),
                'role' => $user_type,
            ]);

            switch ($user_type) {
                // case 'driver':
                //     $check_position = rand(0, 4);
                //     $status = $this->faker->randomElement(['В дорозі', 'Відпочиває', 'В відпусці', 'Аварійна ситуація']);
                //     $days_in_vacation = $status == "В відпусці" ? rand(1, 31) : null;
                //     $date_of_vacation = $status == "В відпусці" ? $this->faker->date() : null;
                //     Driver::factory()->create([
                //         'full_name' => $full_name,
                //         'phone' => $this->faker->phoneNumber(),
                //         'hire_date' => $this->faker->date(),
                //         'current_station' => $check_position < 2 ? null : rand(1, 15),
                //         'status' => $status,
                //         'days_in_vacation' => $days_in_vacation,
                //         'station_id' => $i,
                //         'date_of_vacation' => $date_of_vacation,
                //         'user_id' => $user->id // Прив'язуємо до створеного user_id
                //     ]);
                //     break;

                case 'menedger':
                    Menedger::factory()->create([
                        'full_name' => $full_name,
                        'phone' => $this->faker->phoneNumber(),
                        'station_id' => $i,
                        'user_id' => $user->id // Прив'язуємо до створеного user_id
                    ]);
                    break;

                case 'dispetcher':
                    Dispetcher::factory()->create([
                        'full_name' => $full_name,
                        'phone' => $this->faker->phoneNumber(),
                        'station_id' => $i,
                        'user_id' => $user->id // Прив'язуємо до створеного user_id
                    ]);
                    break;

                case 'supplier':
                    Supplier::factory()->create([
                        'full_name' => $full_name,
                        'phone' => $this->faker->phoneNumber(),
                        'address' => $this->faker->address(),
                        'user_id' => $user->id // Прив'язуємо до створеного user_id
                    ]);
                    break;
            }
        }
    }
}
