<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets
     */
    public function index(Request $request)
    {
        $customer = Auth::user()->customer;
        
        $status = $request->get('status', 'all');
        
        $query = $customer->tickets()->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $tickets = $query->paginate(10)->appends(['status' => $status]);
        
        $totalTickets = $customer->tickets()->count();
        $openTickets = $customer->tickets()->where('status', 'open')->count();
        $inProgressTickets = $customer->tickets()->where('status', 'in_progress')->count();
        $resolvedTickets = $customer->tickets()->where('status', 'resolved')->count();
        $declinedTickets = $customer->tickets()->where('status', 'declined')->count();
        
        return view('client.tickets.index', compact(
            'tickets', 
            'totalTickets', 
            'openTickets', 
            'inProgressTickets', 
            'resolvedTickets',
            'declinedTickets',
            'status'
        ));
    }

    /**
     * Show the form for creating a new ticket
     */
    public function create()
    {
        return view('client.tickets.create');
    }

    /**
     * Store a newly created ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:connectivity,billing,technical,account,other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $customer = Auth::user()->customer;

        $ticket = $customer->tickets()->create($validated);

        return redirect()->route('client.tickets.show', $ticket)
            ->with('success', 'Ticket created successfully');
    }

    /**
     * Display the specified ticket
     */
    public function show(Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated customer
        if ($ticket->customer_id !== Auth::user()->customer->id) {
            abort(403, 'Unauthorized access');
        }

        return view('client.tickets.show', compact('ticket'));
    }
}
