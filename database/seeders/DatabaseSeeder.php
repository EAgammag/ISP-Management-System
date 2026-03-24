<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed service plans and test accounts
        $this->call([
            ServicePlanSeeder::class,
            PaymentSettingSeeder::class,
            TestAccountsSeeder::class,
        ]);
    }
}
