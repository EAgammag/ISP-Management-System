@extends('layouts.client')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900 p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('client.tickets.index') }}" class="text-cyan-400 hover:text-cyan-300 mb-4 inline-block">
                ← Back to Tickets
            </a>
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-cyan-400">Ticket #{{ $ticket->id }}</h1>
                    <p class="text-gray-400 mt-2">{{ $ticket->subject }}</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($ticket->status == 'open') bg-yellow-500 text-white
                        @elseif($ticket->status == 'in_progress') bg-blue-500 text-white
                        @elseif($ticket->status == 'resolved') bg-green-500 text-white
                        @else bg-gray-500 text-white
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($ticket->priority == 'urgent') bg-red-500 text-white
                        @elseif($ticket->priority == 'high') bg-orange-500 text-white
                        @elseif($ticket->priority == 'medium') bg-yellow-500 text-white
                        @else bg-gray-500 text-white
                        @endif">
                        {{ ucfirst($ticket->priority) }} Priority
                    </span>
                </div>
            </div>
        </div>

        <!-- Ticket Details -->
        <div class="bg-gray-800 bg-opacity-50 border border-cyan-500 rounded-lg p-6 shadow-lg mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6 pb-6 border-b border-gray-700">
                <div>
                    <p class="text-gray-400 text-sm">Category</p>
                    <p class="text-white font-semibold">{{ ucfirst($ticket->category) }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Created</p>
                    <p class="text-white font-semibold">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Last Updated</p>
                    <p class="text-white font-semibold">{{ $ticket->updated_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Assigned To</p>
                    <p class="text-white font-semibold">{{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Unassigned' }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-cyan-400 font-bold text-lg mb-3">Description</h3>
                <div class="bg-gray-900 bg-opacity-50 rounded-lg p-4">
                    <p class="text-gray-300 whitespace-pre-wrap">{{ $ticket->description }}</p>
                </div>
            </div>

            @if($ticket->resolved_at)
            <div class="mt-6 bg-green-900 bg-opacity-30 border border-green-500 rounded-lg p-4">
                <p class="text-green-400 font-semibold">✓ Resolved on {{ $ticket->resolved_at->format('M d, Y h:i A') }}</p>
            </div>
            @endif
        </div>

        <!-- Communication / Updates Section -->
        <div class="bg-gray-800 bg-opacity-50 border border-cyan-500 rounded-lg p-6 shadow-lg">
            <h3 class="text-cyan-400 font-bold text-lg mb-4">Updates & Communication</h3>
            
            <div class="space-y-4">
                <!-- Initial ticket creation -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-cyan-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr($ticket->customer->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1 bg-gray-900 bg-opacity-50 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-white font-semibold">{{ $ticket->customer->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <p class="text-gray-300">Created this ticket</p>
                    </div>
                </div>

                <!-- Placeholder for future updates/comments -->
                <div class="bg-blue-900 bg-opacity-20 border border-blue-500 rounded-lg p-4 text-center">
                    <p class="text-blue-300">No updates yet. Our support team will respond soon.</p>
                </div>
            </div>

            <!-- Add Comment Form (for future implementation) -->
            <div class="mt-6">
                <h4 class="text-white font-semibold mb-3">Add a Comment</h4>
                <form action="#" method="POST">
                    @csrf
                    <textarea rows="4" class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg px-4 py-3 focus:border-cyan-500 focus:outline-none mb-3" placeholder="Add additional information or ask a question..."></textarea>
                    <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                        Add Comment
                    </button>
                </form>
            </div>
        </div>

        <!-- Actions -->
        @if($ticket->status != 'closed')
        <div class="mt-6 flex gap-4">
            @if($ticket->status == 'resolved')
            <form action="#" method="POST">
                @csrf
                <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Close Ticket
                </button>
            </form>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
