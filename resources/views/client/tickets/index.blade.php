@extends('layouts.client')

@section('page-title', 'Support Tickets')
@section('page-description', 'View and manage your support tickets')

@section('content')
<!-- Ticket Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">Total Tickets</p>
                <p class="text-2xl font-bold text-gray-800">{{ $tickets->total() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">Open Tickets</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $tickets->where('status', 'open')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">Resolved</p>
                <p class="text-2xl font-bold text-green-600">{{ $tickets->where('status', 'resolved')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">In Progress</p>
                <p class="text-2xl font-bold text-red-600">{{ $tickets->where('status', 'in_progress')->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Create New Ticket Button -->
<div class="mb-6">
    <a href="{{ route('client.tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Create New Ticket
    </a>
</div>

<!-- Tickets List -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px" aria-label="Tabs">
            <a href="{{ route('client.tickets.index', ['status' => 'all']) }}" 
               class="group inline-flex items-center px-6 py-4 border-b-2 font-medium text-sm transition
                      {{ $status === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="mr-2 h-5 w-5 {{ $status === 'all' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                All Tickets
                <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium 
                             {{ $status === 'all' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900' }}">
                    {{ $totalTickets }}
                </span>
            </a>

            <a href="{{ route('client.tickets.index', ['status' => 'open']) }}" 
               class="group inline-flex items-center px-6 py-4 border-b-2 font-medium text-sm transition
                      {{ $status === 'open' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="mr-2 h-5 w-5 {{ $status === 'open' ? 'text-yellow-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Open
                <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium 
                             {{ $status === 'open' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-900' }}">
                    {{ $openTickets }}
                </span>
            </a>

            <a href="{{ route('client.tickets.index', ['status' => 'in_progress']) }}" 
               class="group inline-flex items-center px-6 py-4 border-b-2 font-medium text-sm transition
                      {{ $status === 'in_progress' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="mr-2 h-5 w-5 {{ $status === 'in_progress' ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                In Progress
                <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium 
                             {{ $status === 'in_progress' ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-900' }}">
                    {{ $inProgressTickets }}
                </span>
            </a>

            <a href="{{ route('client.tickets.index', ['status' => 'resolved']) }}" 
               class="group inline-flex items-center px-6 py-4 border-b-2 font-medium text-sm transition
                      {{ $status === 'resolved' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="mr-2 h-5 w-5 {{ $status === 'resolved' ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Resolved
                <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium 
                             {{ $status === 'resolved' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-900' }}">
                    {{ $resolvedTickets }}
                </span>
            </a>

            <a href="{{ route('client.tickets.index', ['status' => 'declined']) }}" 
               class="group inline-flex items-center px-6 py-4 border-b-2 font-medium text-sm transition
                      {{ $status === 'declined' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg class="mr-2 h-5 w-5 {{ $status === 'declined' ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Declined
                <span class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium 
                             {{ $status === 'declined' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-900' }}">
                    {{ $declinedTickets }}
                </span>
            </a>
        </nav>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Update</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                        #TKT-{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $ticket->subject }}</p>
                            <p class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($ticket->message, 60) }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $ticket->priority == 'urgent' ? 'bg-red-100 text-red-800' : 
                               ($ticket->priority == 'high' ? 'bg-orange-100 text-orange-800' : 
                               ($ticket->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                            {{ ucfirst($ticket->priority ?? 'normal') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $ticket->status == 'open' ? 'bg-yellow-100 text-yellow-800' : 
                               ($ticket->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                               ($ticket->status == 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $ticket->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $ticket->updated_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('client.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        @if($status === 'all')
                            <p class="text-gray-500 mb-4">No tickets found.</p>
                            <a href="{{ route('client.tickets.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Create your first ticket
                            </a>
                        @elseif($status === 'open')
                            <p class="text-gray-500 mb-4">No open tickets.</p>
                            <p class="text-gray-400 text-sm">All your tickets have been addressed.</p>
                        @elseif($status === 'in_progress')
                            <p class="text-gray-500 mb-4">No tickets in progress.</p>
                            <p class="text-gray-400 text-sm">Our team is currently not working on any of your tickets.</p>
                        @elseif($status === 'resolved')
                            <p class="text-gray-500 mb-4">No resolved tickets.</p>
                            <p class="text-gray-400 text-sm">You don't have any resolved tickets yet.</p>
                        @elseif($status === 'declined')
                            <p class="text-gray-500 mb-4">No declined tickets.</p>
                            <p class="text-gray-400 text-sm">None of your tickets have been declined.</p>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
