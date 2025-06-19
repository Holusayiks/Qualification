<?php

namespace Database\Seeders;

use App\Models\Auto;
use App\Models\Driver;
use App\Models\InsuranceAccountJournal;
use App\Models\Order;
use App\Models\Ride;
use App\Models\Station;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Dispetcher;
use App\Models\Menedger;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RemoveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        InsuranceAccountJournal::truncate();
        Ride::truncate();
        Order::truncate();
        // Supplier::truncate();
        Auto::truncate();
        Driver::truncate();
        // Dispetcher::truncate();
        // Menedger::truncate();
        // Station::truncate();
        // User::truncate();
    }
    private $faker;
}
