@extends('layouts.app')

@section('title', 'Créer un signalement')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md">
        <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white p-6 rounded-t-lg">
            <h1 class="text-2xl font-bold">Créer un signalement</h1>
            <p class="text-red-100 mt-2">Signalez un problème ou un comportement inapproprié</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('reports.store') }}" class="space-y-6">
                @csrf

                <!-- Type de rapport -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type de signalement *</label>
                    <select name="type" id="type" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 @error('type') border-red-500 @enderror">
                        <option value="">Sélectionnez un type</option>
                        @foreach(\App\Models\Report::getTypes() as $value => $label)
                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description du problème *</label>
                    <textarea name="description" id="description" rows="5" required
                              placeholder="Décrivez en détail le problème rencontré ou le comportement à signaler..."
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Minimum 10 caractères, maximum 1000 caractères</p>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Utilisateur signalé (si pas d'échange pré-sélectionné) -->
                @if(!$exchange)
                    <div id="reported-user-section" class="{{ old('type') === 'COMPORTEMENT' || (!old('type') && $reportedUser) ? '' : 'hidden' }}">
                        <label for="reported_user_id" class="block text-sm font-medium text-gray-700 mb-2">Utilisateur à signaler</label>
                        <input type="hidden" name="reported_user_id" value="{{ $reportedUser?->id ?? old('reported_user_id') }}">
                        
                        @if($reportedUser)
                            <div class="flex items-center p-3 bg-gray-50 rounded-md">
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-gray-600">{{ substr($reportedUser->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $reportedUser->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $reportedUser->email }}</p>
                                </div>
                            </div>
                        @else
                            <input type="text" placeholder="ID ou nom de l'utilisateur à signaler" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 @error('reported_user_id') border-red-500 @enderror">
                        @endif
                        
                        @error('reported_user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Échange associé -->
                <div id="exchange-section" class="{{ old('type') === 'CONFLIT_ECHANGE' || (!old('type') && $exchange) ? '' : 'hidden' }}">
                    <label for="exchange_id" class="block text-sm font-medium text-gray-700 mb-2">Échange concerné</label>
                    <input type="hidden" name="exchange_id" value="{{ $exchange?->id ?? old('exchange_id') }}">
                    
                    @if($exchange)
                        <div class="p-4 bg-gray-50 rounded-md">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $exchange->bookDemande->title ?? 'Livre non disponible' }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Échange entre {{ $exchange->initiateur->name }} et {{ $exchange->recepteur->name ?? 'Utilisateur non défini' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Statut: {{ $exchange->status }} • Créé le {{ $exchange->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    ID: {{ $exchange->id }}
                                </span>
                            </div>
                        </div>
                    @else
                        <input type="number" placeholder="ID de l'échange" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 @error('exchange_id') border-red-500 @enderror">
                    @endif
                    
                    @error('exchange_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Avertissement -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Important</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Les signalements abusifs ou répétés peuvent entraîner des sanctions. Assurez-vous que votre signalement est justifié et décrit fidèlement la situation.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-between">
                    <a href="{{ route('dashboard') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md transition duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md transition duration-200">
                        Envoyer le signalement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const reportedUserSection = document.getElementById('reported-user-section');
    const exchangeSection = document.getElementById('exchange-section');

    function toggleSections() {
        const selectedType = typeSelect.value;
        
        if (selectedType === 'COMPORTEMENT') {
            reportedUserSection?.classList.remove('hidden');
            exchangeSection?.classList.add('hidden');
        } else if (selectedType === 'CONFLIT_ECHANGE') {
            reportedUserSection?.classList.add('hidden');
            exchangeSection?.classList.remove('hidden');
        } else {
            reportedUserSection?.classList.add('hidden');
            exchangeSection?.classList.add('hidden');
        }
    }

    typeSelect.addEventListener('change', toggleSections);
    
    // Initialize on page load
    toggleSections();
});
</script>
@endsection