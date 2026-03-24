<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\ServicePlan;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\NetworkDevice;
use App\Models\IpAllocation;
use Illuminate\Support\Facades\Hash;

class TestAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@isp.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        echo "✓ Admin account created:\n";
        echo "  Email: admin@isp.com\n";
        echo "  Password: admin123\n\n";

        // Create Client User
        $clientUser = User::create([
            'name' => 'John Doe',
            'email' => 'client@isp.com',
            'password' => Hash::make('client123'),
            'role' => 'client',
        ]);

        // Create Customer Profile for Client
        $customer = Customer::create([
            'user_id' => $clientUser->id,
            'name' => 'John Doe',
            'email' => 'client@isp.com',
            'phone' => '+1-234-567-8900',
            'address' => '123 Main Street, City, State 12345',
            'connection_status' => 'active',
            'balance' => 1500.00,
        ]);

        echo "✓ Client account created:\n";
        echo "  Email: client@isp.com\n";
        echo "  Password: client123\n\n";

        // Use existing service plan (Plan 1000 - 10Mbps)
        $servicePlan = ServicePlan::where('name', 'Plan 1000')->first();
        
        if (!$servicePlan) {
            echo "⚠ No service plans found. Please run ServicePlanSeeder first.\n\n";
            return;
        }

        echo "✓ Using existing service plan: {$servicePlan->name}\n\n";

        // Create Active Subscription for Client
        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'service_plan_id' => $servicePlan->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => 'active',
        ]);

        echo "✓ Active subscription created for client\n\n";

        // Create IP Allocation
        $ipAllocation = IpAllocation::create([
            'customer_id' => $customer->id,
            'ip_address' => '192.168.1.100',
            'type' => 'static',
            'subnet_mask' => '255.255.255.0',
            'gateway' => '192.168.1.1',
            'dns_primary' => '8.8.8.8',
            'dns_secondary' => '8.8.4.4',
            'status' => 'active',
            'allocated_at' => now(),
        ]);

        echo "✓ IP allocation created (192.168.1.100 - Static)\n\n";

        // Create Sample Invoices
        Invoice::create([
            'customer_id' => $customer->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-000001',
            'amount' => $servicePlan->price,
            'status' => 'paid',
            'description' => 'Monthly Subscription - ' . $servicePlan->name . ' - January 2026',
            'billing_period' => 'Jan 2026 - Feb 2026',
            'due_date' => now()->subDays(15),
        ]);

        Invoice::create([
            'customer_id' => $customer->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-000002',
            'amount' => $servicePlan->price,
            'status' => 'unpaid',
            'description' => 'Monthly Subscription - ' . $servicePlan->name . ' - February 2026',
            'billing_period' => 'Feb 2026 - Mar 2026',
            'due_date' => now()->addDays(15),
        ]);

        echo "✓ Sample invoices created\n\n";

        // Create Network Devices
        NetworkDevice::create([
            'name' => 'Main Router',
            'type' => 'router',
            'ip_address' => '192.168.1.1',
            'mac_address' => '00:11:22:33:44:55',
            'location' => 'Data Center - Rack A1',
            'status' => 'online',
            'uptime' => 2592000, // 30 days in seconds
            'cpu_usage' => 45.50,
            'memory_usage' => 62.30,
            'connected_clients' => 150,
            'last_seen' => now(),
        ]);

        NetworkDevice::create([
            'name' => 'Access Point - North',
            'type' => 'access_point',
            'ip_address' => '192.168.1.10',
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'location' => 'North Tower - Floor 5',
            'status' => 'online',
            'uptime' => 1728000, // 20 days
            'cpu_usage' => 32.10,
            'memory_usage' => 48.90,
            'connected_clients' => 45,
            'last_seen' => now(),
        ]);

        NetworkDevice::create([
            'name' => 'Core Switch',
            'type' => 'switch',
            'ip_address' => '192.168.1.2',
            'mac_address' => '11:22:33:44:55:66',
            'location' => 'Data Center - Rack A2',
            'status' => 'online',
            'uptime' => 3024000, // 35 days
            'cpu_usage' => 28.75,
            'memory_usage' => 55.20,
            'connected_clients' => 200,
            'last_seen' => now(),
        ]);

        NetworkDevice::create([
            'name' => 'Backup Router',
            'type' => 'router',
            'ip_address' => '192.168.1.254',
            'mac_address' => '99:88:77:66:55:44',
            'location' => 'Data Center - Rack B1',
            'status' => 'offline',
            'uptime' => 0,
            'connected_clients' => 0,
            'last_seen' => now()->subHours(2),
        ]);

        echo "✓ Network devices created (4 devices)\n\n";

        echo "═══════════════════════════════════════════════\n";
        echo "   Test Accounts Setup Complete!\n";
        echo "═══════════════════════════════════════════════\n\n";
        echo "ADMIN LOGIN:\n";
        echo "  URL: http://localhost/isp-management-system/admin/dashboard\n";
        echo "  Email: admin@isp.com\n";
        echo "  Password: admin123\n\n";
        echo "CLIENT LOGIN:\n";
        echo "  URL: http://localhost/isp-management-system/dashboard\n";
        echo "  Email: client@isp.com\n";
        echo "  Password: client123\n\n";
    }
}
