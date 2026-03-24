<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ServicePlan;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display service management page
     */
    public function index()
    {
        $customer = Auth::user()->customer;
        
        $subscription = $customer->activeSubscription();
        $currentPlan = $subscription ? $subscription->servicePlan : null;
        
        $availablePlans = ServicePlan::where('is_active', true)->get();
        $addons = Addon::active()->get();
        $activeAddons = $customer->addons()
            ->wherePivot('status', 'active')
            ->wherePivot('expires_at', '>=', now())
            ->get();
        
        return view('client.services.index', compact(
            'currentPlan',
            'availablePlans',
            'addons',
            'activeAddons',
            'subscription'
        ));
    }

    /**
     * Upgrade or change plan
     */
    public function upgrade(ServicePlan $plan, Request $request)
    {
        try {
            $customer = Auth::user()->customer;
            
            if (!$customer) {
                return redirect()->route('client.services.index')
                    ->with('error', 'Customer profile not found. Please contact support.');
            }
            
            $currentSubscription = $customer->activeSubscription();
            $currentPlan = $currentSubscription ? $currentSubscription->servicePlan : null;
            
            // Validate reason length if provided
            $request->validate([
                'reason' => 'nullable|string|max:500'
            ]);
            
            // Prevent requesting the same plan
            if ($currentPlan && $currentPlan->id === $plan->id) {
                return redirect()->route('client.services.index')
                    ->with('error', 'You are already subscribed to this plan.');
            }
            
            // Create a support ticket for the upgrade request
            $subject = $currentPlan 
                ? "Plan Change Request: {$currentPlan->name} to {$plan->name}"
                : "New Subscription Request: {$plan->name}";
            
            $message = "Customer Information:\n";
            $message .= "- Name: {$customer->name}\n";
            $message .= "- Email: {$customer->email}\n";
            $message .= "- Phone: {$customer->phone}\n\n";
            
            $message .= $currentPlan
                ? "Requesting plan change from {$currentPlan->name} (₱" . number_format($currentPlan->price, 2) . "/month) to {$plan->name} (₱" . number_format($plan->price, 2) . "/month).\n\n"
                : "Requesting new subscription to {$plan->name} (₱" . number_format($plan->price, 2) . "/month).\n\n";
            
            if ($request->filled('reason')) {
                $message .= "Customer's reason:\n" . $request->input('reason');
            }
            
            $customer->tickets()->create([
                'subject' => $subject,
                'description' => $message,
                'priority' => 'medium',
                'status' => 'open',
                'category' => 'billing',
            ]);
            
            return redirect()->route('client.services.index')
                ->with('success', 'Your plan change request has been submitted successfully. Our admin team will review and process it shortly.');
        } catch (\Exception $e) {
            return redirect()->route('client.services.index')
                ->with('error', 'Failed to submit request. Please try again or contact support.');
        }
    }

    /**
     * Purchase an addon
     */
    public function purchaseAddon(Addon $addon)
    {
        $customer = Auth::user()->customer;
        
        $expiresAt = now()->addDays($addon->validity_days);
        
        $customer->addons()->attach($addon->id, [
            'purchased_at' => now(),
            'expires_at' => $expiresAt,
            'status' => 'active',
        ]);
        
        return redirect()->route('client.services.index')
            ->with('success', 'Add-on purchased successfully');
    }
}
