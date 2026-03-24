<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    /**
     * Display payment settings
     */
    public function index()
    {
        // Ensure default settings exist
        $this->ensureDefaultSettings();
        
        $settings = PaymentSetting::all()->keyBy('key');
        return view('admin.settings.payment', compact('settings'));
    }

    /**
     * Ensure default settings exist
     */
    private function ensureDefaultSettings()
    {
        $defaults = [
            'gcash_number' => '09618377290',
            'gcash_account_name' => 'Eddie Jr G.',
            'gcash_qr_code' => null,
        ];

        foreach ($defaults as $key => $value) {
            if (!PaymentSetting::where('key', $key)->exists()) {
                PaymentSetting::create([
                    'key' => $key,
                    'value' => $value,
                    'type' => $key === 'gcash_qr_code' ? 'image' : 'text',
                    'description' => $this->getDescription($key)
                ]);
            }
        }
    }

    /**
     * Get setting description
     */
    private function getDescription($key)
    {
        $descriptions = [
            'gcash_number' => 'GCash account number for receiving payments',
            'gcash_account_name' => 'GCash account holder name',
            'gcash_qr_code' => 'GCash QR code image (optional)',
        ];

        return $descriptions[$key] ?? null;
    }

    /**
     * Update payment settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'gcash_number' => 'required|string|max:20',
            'gcash_account_name' => 'required|string|max:100',
            'gcash_qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'gcash_number.required' => 'GCash number is required',
            'gcash_account_name.required' => 'Account name is required',
            'gcash_qr_code.image' => 'QR code must be an image',
            'gcash_qr_code.mimes' => 'QR code must be a jpeg, png, jpg, or gif file',
            'gcash_qr_code.max' => 'QR code image must not exceed 2MB'
        ]);

        try {
            // Update GCash number
            PaymentSetting::set('gcash_number', $validated['gcash_number'], 'text', 'GCash account number for receiving payments');
            
            // Update account name
            PaymentSetting::set('gcash_account_name', $validated['gcash_account_name'], 'text', 'GCash account holder name');

            // Handle QR code image upload
            if ($request->hasFile('gcash_qr_code')) {
                // Delete old QR code if exists
                $oldQrCode = PaymentSetting::get('gcash_qr_code');
                if ($oldQrCode && \Storage::disk('public')->exists($oldQrCode)) {
                    \Storage::disk('public')->delete($oldQrCode);
                }
                
                // Store new QR code
                $path = $request->file('gcash_qr_code')->store('payment-qr-codes', 'public');
                PaymentSetting::set('gcash_qr_code', $path, 'image', 'GCash QR code image');
            }

            return redirect()->route('admin.settings.payment.index')
                ->with('success', 'Payment settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.payment.index')
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }
}
