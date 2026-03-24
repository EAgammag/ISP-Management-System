<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Display account settings
     */
    public function index()
    {
        $customer = Auth::user()->customer;
        
        return view('client.account.index', compact('customer'));
    }

    /**
     * Update customer information
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $customer = Auth::user()->customer;
        $customer->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        // Update email in users table
        Auth::user()->update([
            'email' => $validated['email'],
        ]);

        return redirect()->route('client.account.index')
            ->with('success', 'Information updated successfully');
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('client.account.index')
            ->with('success', 'Password changed successfully');
    }

    /**
     * Update Wi-Fi password via TR-069
     */
    public function updateWifiPassword(Request $request)
    {
        $request->validate([
            'wifi_password' => 'required|min:8|confirmed',
        ]);

        // TR-069 integration logic would be implemented here
        // This would communicate with the customer's router to change the Wi-Fi password
        
        return redirect()->route('client.account.index')
            ->with('success', 'Wi-Fi password update request submitted. Changes will take effect shortly.');
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
            'billing_reminders' => 'nullable|boolean',
            'promotional_offers' => 'nullable|boolean',
        ]);

        $customer = Auth::user()->customer;
        
        // Update notification preferences with proper boolean conversion
        $customer->update([
            'email_notifications' => $request->has('email_notifications'),
            'sms_notifications' => $request->has('sms_notifications'),
            'billing_reminders' => $request->has('billing_reminders'),
            'promotional_offers' => $request->has('promotional_offers'),
        ]);
        
        return redirect()->route('client.account.index')
            ->with('success', 'Notification preferences updated successfully');
    }
}
