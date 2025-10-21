@extends('layouts.app')

@section('title', 'Test Notifications & Polls')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">🧪 Test Notifications & Polls</h1>

        <!-- Quick Test Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            <!-- Test Event Reminders -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">🔔 Test Event Reminders</h2>
                
                <form action="{{ route('test.event.reminder') }}" method="POST" class="space-y-3">
                    @csrf
                    
                    <select name="event_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        <option value="">-- Select an Event --</option>
                        @foreach(\App\Models\GroupEvent::all() as $event)
                            <option value="{{ $event->id }}">{{ $event->title }} ({{ $event->event_date->format('d/m/Y') }})</option>
                        @endforeach
                    </select>

                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="reminder_type" value="24h" checked class="w-4 h-4">
                            <span class="ml-2">24 Hours Before</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="reminder_type" value="1h" class="w-4 h-4">
                            <span class="ml-2">1 Hour Before</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Send Reminder
                    </button>
                </form>

                @if(session('reminder_sent'))
                    <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        ✅ {{ session('reminder_sent') }}
                    </div>
                @endif
            </div>

            <!-- Test Poll Started -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">📊 Test Poll Started</h2>
                
                <form action="{{ route('test.poll.started') }}" method="POST" class="space-y-3">
                    @csrf
                    
                    <select name="poll_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        <option value="">-- Select a Poll --</option>
                        @foreach(\App\Models\Poll::all() as $poll)
                            <option value="{{ $poll->id }}">{{ $poll->title }} ({{ $poll->event->title }})</option>
                        @endforeach
                    </select>

                    <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Send Poll Notification
                    </button>
                </form>

                @if(session('poll_notif_sent'))
                    <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        ✅ {{ session('poll_notif_sent') }}
                    </div>
                @endif
            </div>

        </div>

        <!-- Status Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">📈 System Status</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ \App\Models\GroupEvent::count() }}</div>
                    <div class="text-sm text-gray-600">Events</div>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Poll::count() }}</div>
                    <div class="text-sm text-gray-600">Polls</div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ \App\Models\PollVote::count() }}</div>
                    <div class="text-sm text-gray-600">Votes</div>
                </div>

                <div class="bg-orange-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600">{{ \App\Models\Notification::where('user_id', auth()->id())->count() }}</div>
                    <div class="text-sm text-gray-600">Your Notifications</div>
                </div>
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">📬 Your Recent Notifications</h2>
            
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse(\App\Models\Notification::where('user_id', auth()->id())->orderBy('created_at', 'desc')->limit(10)->get() as $notif)
                    <div class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex-1">
                            <div class="font-medium text-gray-900">{{ $notif->title }}</div>
                            <div class="text-sm text-gray-600 mt-1">{{ $notif->message }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $notif->created_at->diffForHumans() }}
                                @if(!$notif->is_read)
                                    <span class="inline-block ml-2 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">New</span>
                                @endif
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">{{ $notif->type }}</span>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500">
                        No notifications yet
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
