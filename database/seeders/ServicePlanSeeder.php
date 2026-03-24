<?php

namespace Database\Seeders;

use App\Models\ServicePlan;
use Illuminate\Database\Seeder;

class ServicePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Plan 500',
                'description' => '5 Mbps internet plan for basic browsing and communication',
                'price' => 500.00,
                'speed' => 5,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 600',
                'description' => '6 Mbps internet plan for light streaming and browsing',
                'price' => 600.00,
                'speed' => 6,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 700',
                'description' => '7 Mbps internet plan for HD video streaming',
                'price' => 700.00,
                'speed' => 7,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 800',
                'description' => '8 Mbps internet plan for multiple devices',
                'price' => 800.00,
                'speed' => 8,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 900',
                'description' => '9 Mbps internet plan for families',
                'price' => 900.00,
                'speed' => 9,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 1000',
                'description' => '10 Mbps internet plan for home office',
                'price' => 1000.00,
                'speed' => 10,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 1200',
                'description' => '11 Mbps internet plan for gaming and streaming',
                'price' => 1200.00,
                'speed' => 11,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 1300',
                'description' => '12 Mbps internet plan for heavy users',
                'price' => 1300.00,
                'speed' => 12,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 1400',
                'description' => '13 Mbps internet plan for 4K streaming',
                'price' => 1400.00,
                'speed' => 13,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 1500',
                'description' => '15 Mbps internet plan for premium experience',
                'price' => 1500.00,
                'speed' => 15,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 2000',
                'description' => '25 Mbps internet plan for power users',
                'price' => 2000.00,
                'speed' => 25,
                'data_limit' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Plan 3000',
                'description' => '25 Mbps premium internet plan for business',
                'price' => 3000.00,
                'speed' => 25,
                'data_limit' => null,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            ServicePlan::updateOrCreate(
                ['name' => $plan['name']], // Match on name
                $plan // Update or create with these values
            );
        }

        $this->command->info('Service plans seeded successfully!');
    }
}
