<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'gcash_number',
                'value' => '09618377290',
                'type' => 'text',
                'description' => 'GCash account number for receiving payments'
            ],
            [
                'key' => 'gcash_account_name',
                'value' => 'Eddie Jr G.',
                'type' => 'text',
                'description' => 'GCash account holder name'
            ],
            [
                'key' => 'gcash_qr_code',
                'value' => null,
                'type' => 'image',
                'description' => 'GCash QR code image (optional, will generate from number if not provided)'
            ],
            [
                'key' => 'payment_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable/disable payment system'
            ]
        ];

        foreach ($settings as $setting) {
            \App\Models\PaymentSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
