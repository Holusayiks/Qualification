<?php

namespace Database\Seeders;

use App\Models\InsuranceAccountJournal;
use App\Models\Supplier;
use App\Models\Dispetcher;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $faker;
    public function run(): void
    {
        InsuranceAccountJournal::truncate();
        // $this->faker = \Faker\Factory::create();
        // $old_value = 0;


        // $suppliers = Supplier::all();
        // $dispetchers = Dispetcher::all();

        // $current_date = Carbon::now()->subDays(800);


        // for ($i = 0; $i < 800; $i++) {
        //     $type_user = rand(0, 1) == 1 ? 'supplier' : 'dispetcher';
        //     $user_id = $type_user == 'supplier'
        //         ? $suppliers->random()->user_id
        //         : $dispetchers->random()->user_id;

        //     $add = $type_user == 'supplier'?$this->faker->numberBetween(200, 3000):$this->faker->numberBetween(200, 1000);

        //     $old_value = $type_user == 'supplier'
        //         ? $old_value + $add
        //         : $old_value - $add;

        //     InsuranceAccountJournal::create([
        //         'user_id'     => $user_id,
        //         'current_sum' => $old_value,
        //         'created_at'  => $current_date,
        //     ]);

        //     $current_date->addDay();
        // }

    }
}
