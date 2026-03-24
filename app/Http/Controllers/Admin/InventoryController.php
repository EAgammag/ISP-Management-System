<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\Customer;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $items = InventoryItem::paginate(20);
        
        $totalItems = InventoryItem::count();
        $inStockItems = InventoryItem::where('status', 'in_stock')->sum('quantity');
        $assignedItems = InventoryItem::where('status', 'assigned')->count();
        $lowStockItems = InventoryItem::where('status', 'in_stock')
            ->where('quantity', '<', 10)
            ->count();
        
        // Inventory by category
        $categories = InventoryItem::select('category')
            ->distinct()
            ->get()
            ->pluck('category');
        
        $categoryStats = [];
        foreach ($categories as $category) {
            $categoryStats[$category] = InventoryItem::where('category', $category)
                ->where('status', 'in_stock')
                ->sum('quantity');
        }
        
        return view('admin.inventory.index', compact(
            'items',
            'totalItems',
            'inStockItems',
            'assignedItems',
            'lowStockItems',
            'categoryStats'
        ));
    }

    public function create()
    {
        return view('admin.inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_type' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:inventory_items,serial_number',
            'category' => 'required|in:router,ont,cable,switch,other',
            'status' => 'required|in:in_stock,assigned,faulty,retired',
            'quantity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $item = InventoryItem::create($validated);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item added successfully');
    }

    public function assign(InventoryItem $item)
    {
        $customers = Customer::where('connection_status', 'active')
            ->get();
        
        return view('admin.inventory.assign', compact('item', 'customers'));
    }

    public function processAssignment(Request $request, InventoryItem $item)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1|max:' . $item->quantity,
            'notes' => 'nullable|string',
        ]);

        // Update item
        $item->customer_id = $validated['customer_id'];
        $item->status = 'assigned';
        $item->quantity -= $validated['quantity'];
        $item->notes = $validated['notes'];
        $item->assigned_at = now();
        $item->save();

        // If quantity is 0, mark as fully assigned
        if ($item->quantity == 0) {
            $item->status = 'assigned';
            $item->save();
        }

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Item assigned to customer successfully');
    }
}
