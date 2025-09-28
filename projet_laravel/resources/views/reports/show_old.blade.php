@extends('layouts.app')

@section('title', 'Détails du signalement')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6 rounded-t-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold">Signalement #{{ $report->id }}</h1>
                        <p class="text-blue-100 mt-2">{{ \App\Models\Report::getTypes()[$report->type] }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($report->status === 'EN_ATTENTE') bg-yellow-200 text-yellow-800
                        @elseif($report->status === 'TRAITE') bg-green-200 text-green-800
                        @else bg-red-200 text-red-800 @endif">
                        {{ \App\Models\Report::getStatuses()[$report->status] }}
                    </span>
                </div>
            </div>

            <!-- Report Details -->
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $report->description }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Informations générales</h3>
                            <dl class="grid grid-cols-1 gap-2">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <dt class="font-medium text-gray-600">Date de création:</dt>
                                    <dd class="text-gray-900">{{ $report->created_at->format('d/m/Y à H:i') }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <dt class="font-medium text-gray-600">Dernière mise à jour:</dt>
                                    <dd class="text-gray-900">{{ $report->updated_at->format('d/m/Y à H:i') }}</dd>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <dt class="font-medium text-gray-600">Type:</dt>
                                    <dd class="text-gray-900">{{ \App\Models\Report::getTypes()[$report->type] }}</dd>
                                </div>
                                <div class="flex justify-between py-2">
                                    <dt class="font-medium text-gray-600">Statut:</dt>
                                    <dd>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @if($report->status === 'EN_ATTENTE') bg-yellow-100 text-yellow-800
                                            @elseif($report->status === 'TRAITE') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ \App\Models\Report::getStatuses()[$report->status] }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        @if($report->reportedUser)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Utilisateur signalé</h3>
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center mr-4">
                                            <span class="text-lg font-medium text-gray-600">
                                                {{ substr($report->reportedUser->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $report->reportedUser->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $report->reportedUser->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($report->exchange)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Échange concerné</h3>
                                <div class="bg-gray-50 p-4 rounded-md">
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-medium text-gray-900">
                                                {{ $report->exchange->bookDemande->title ?? 'Livre non disponible' }}
                                            </h4>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                #{{ $report->exchange->id }}
                                            </span>
                                        </div>
                                        
                                        <div class="text-sm text-gray-600">
                                            <p><strong>Demandeur:</strong> {{ $report->exchange->initiateur->name ?? 'N/A' }}</p>
                                            @if($report->exchange->recepteur)
                                                <p><strong>Propriétaire:</strong> {{ $report->exchange->recepteur->name }}</p>
                                            @endif
                                            <p><strong>Statut de l'échange:</strong> {{ $report->exchange->status }}</p>
                                            <p><strong>Date de début:</strong> {{ $report->exchange->dateDebut ? \Carbon\Carbon::parse($report->exchange->dateDebut)->format('d/m/Y') : 'N/A' }}</p>
                                        </div>

                                        <div class="pt-2">
                                            <a href="{{ route('exchanges.show', $report->exchange) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm underline">
                                                Voir les détails de l'échange →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Status Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Statut du signalement</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                @if($report->status === 'EN_ATTENTE')
                                    <div class="flex items-center text-yellow-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium">En attente de traitement</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">
                                        Votre signalement est en cours d'examen par les administrateurs. Vous recevrez une notification dès qu'une décision sera prise.
                                    </p>
                                @elseif($report->status === 'TRAITE')
                                    <div class="flex items-center text-green-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium">Signalement traité</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">
                                        Votre signalement a été examiné et traité par les administrateurs. Des mesures appropriées ont été prises.
                                    </p>
                                @else
                                    <div class="flex items-center text-red-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium">Signalement rejeté</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">
                                        Après examen, les administrateurs ont déterminé que ce signalement ne nécessitait pas d'action particulière.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between">
            <a href="{{ route('reports.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md transition duration-200">
                ← Retour aux signalements
            </a>
            
            @if($report->isPending())
                <div class="text-sm text-gray-500">
                    Ce signalement est en cours de traitement
                </div>
            @endif
        </div>
    </div>
</div>
@endsection