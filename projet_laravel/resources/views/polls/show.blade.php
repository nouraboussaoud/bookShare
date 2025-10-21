@extends('layouts.app')

@section('title', $poll->title . ' - Sondage')

@push('styles')
<style>
    .poll-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 0.6rem; padding: 2rem; margin-bottom: 2rem; }
    .poll-status { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
    .status-badge { padding: 0.5rem 1rem; border-radius: 2rem; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-closed { background: #f3f4f6; color: #6b7280; }
    .card-section { background: #fff; border-radius: 0.6rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .section-title { font-size: 1.1rem; font-weight: 600; color: #1f2937; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
    .section-title i { color: #667eea; font-size: 1.3rem; }
    .option-item { padding: 1rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s; }
    .option-item:hover { border-color: #667eea; background: rgba(102, 126, 234, 0.05); }
    .rating-container { display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap; }
    .rating-btn { width: 50px; height: 50px; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 1.2rem; font-weight: 700; cursor: pointer; transition: all 0.2s; background: #fff; }
    .rating-btn:hover { border-color: #667eea; background: rgba(102, 126, 234, 0.1); }
    .rating-btn.selected { border-color: #667eea; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
    .progress-item { margin-bottom: 1.5rem; }
    .progress-label { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; font-size: 0.95rem; }
    .progress-bar { height: 8px; background: #e5e7eb; border-radius: 2rem; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); transition: width 0.3s ease; border-radius: 2rem; }
    .btn-action { padding: 0.75rem 1.5rem; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.95rem; font-weight: 600; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; }
    .btn-vote { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
    .btn-vote:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
    .btn-close { background: #fbbf24; color: #78350f; }
    .btn-close:hover { background: #f59e0b; }
    .btn-export { background: #10b981; color: #fff; }
    .btn-export:hover { background: #059669; }
    .btn-delete { background: #ef4444; color: #fff; }
    .btn-delete:hover { background: #dc2626; }
    .alert-success { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .alert-warning { background: #fef3c7; border: 1px solid #fcd34d; color: #78350f; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .average-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 1.5rem; border-radius: 0.6rem; text-align: center; }
    .average-card .number { font-size: 2.5rem; font-weight: 700; }
    .vote-count { font-size: 0.9rem; color: #d1d5db; }
    .actions-container { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem; }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Poll Header -->
            <div class="poll-header">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h3 class="mb-2">📊 {{ $poll->title }}</h3>
                        @if($poll->description)
                            <p class="mb-0 text-white-75">{{ $poll->description }}</p>
                        @endif
                    </div>
                    <a href="{{ route('reading-groups.events.show', [$event->readingGroup, $event]) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>

                <!-- Status Bar -->
                <div class="poll-status">
                    <span class="status-badge @if($poll->isActive()) status-active @else status-closed @endif">
                        @if($poll->isActive())
                            <i class="fas fa-check-circle"></i> Sondage actif
                        @else
                            <i class="fas fa-times-circle"></i> Sondage fermé
                        @endif
                    </span>
                    @if($poll->closes_at)
                        <span class="text-white-75">
                            <i class="fas fa-clock me-1"></i>
                            Se ferme: <strong>{{ $poll->closes_at->format('d/m/Y H:i') }}</strong>
                        </span>
                    @endif
                    <span class="text-white-75 ms-auto">
                        <i class="fas fa-users me-1"></i>
                        <strong>{{ $results['total_votes'] }}</strong> vote(s)
                    </span>
                </div>
            </div>

            <!-- Voting Section -->
            <div class="card-section">
                @if($poll->isActive() && !$userHasVoted)
                    <div class="section-title">
                        <i class="fas fa-pencil-alt"></i>
                        Voter maintenant
                    </div>
                    <form id="voteForm" action="{{ route('polls.vote', [$event->readingGroup, $event, $poll]) }}" method="POST">
                        @csrf
                        
                        @if($poll->type === 'yes_no' || $poll->type === 'multiple_choice')
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                @foreach($poll->options as $option)
                                    <label class="option-item" style="display: flex; align-items: center;">
                                        <input
                                            type="radio"
                                            name="poll_option_id"
                                            value="{{ $option->id }}"
                                            style="width: 20px; height: 20px; cursor: pointer;"
                                            required
                                        >
                                        <span style="margin-left: 1rem; font-size: 1rem; cursor: pointer;">{{ $option->text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif($poll->type === 'rating')
                            <div class="rating-container">
                                @for($i = 1; $i <= 5; $i++)
                                    <label style="cursor: pointer;">
                                        <input
                                            type="radio"
                                            name="rating_value"
                                            value="{{ $i }}"
                                            style="display: none;"
                                            required
                                        >
                                        <span class="rating-btn" onclick="this.parentElement.querySelector('input').checked=true; this.classList.add('selected');">
                                            {{ $i }}
                                        </span>
                                    </label>
                                @endfor
                            </div>
                        @endif

                        <button type="submit" class="btn-action btn-vote" style="width: 100%; justify-content: center; margin-top: 1.5rem;">
                            <i class="fas fa-check"></i> Voter
                        </button>
                    </form>
                @elseif($poll->isActive() && $userHasVoted)
                    <div class="alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Merci!</strong> Vous avez déjà voté pour ce sondage.
                    </div>
                @else
                    <div class="alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Sondage fermé</strong> - Les votes ne sont plus acceptés.
                    </div>
                @endif
            </div>

            <!-- Results Section -->
            <div class="card-section">
                <div class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    Résultats
                </div>

                @if($poll->type === 'rating')
                    <div>
                        @for($i = 1; $i <= 5; $i++)
                            @php
                                $votes = $results['data'][$i] ?? 0;
                                $percentage = $results['total_votes'] > 0 ? ($votes / $results['total_votes']) * 100 : 0;
                            @endphp
                            <div class="progress-item">
                                <div class="progress-label">
                                    <span>
                                        @for($j = 1; $j <= $i; $j++)
                                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                                        @endfor
                                    </span>
                                    <span style="color: #6b7280;">{{ $votes }} vote(s) • {{ round($percentage) }}%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endfor

                        @if(isset($results['average']))
                            <div class="average-card">
                                <div class="vote-count">Note moyenne</div>
                                <div class="number">{{ round($results['average'], 1) }}/5</div>
                            </div>
                        @endif
                    </div>
                @else
                    <div>
                        @forelse($results['data'] as $optionId => $result)
                            <div class="progress-item">
                                <div class="progress-label">
                                    <strong>{{ $result['text'] }}</strong>
                                    <span style="color: #6b7280;">{{ $result['votes'] }} vote(s) • {{ $result['percentage'] }}%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $result['percentage'] }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p style="color: #6b7280; text-align: center;">Aucun vote pour le moment</p>
                        @endforelse
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="actions-container">
                @if(auth()->id() === $poll->created_by)
                    @if($poll->isActive())
                        <form action="{{ route('polls.close', [$event->readingGroup, $event, $poll]) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-action btn-close" onclick="return confirm('Êtes-vous sûr de vouloir fermer ce sondage?')">
                                <i class="fas fa-lock"></i> Fermer le sondage
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('polls.export', [$event->readingGroup, $event, $poll]) }}" class="btn-action btn-export" download>
                        <i class="fas fa-download"></i> Exporter CSV
                    </a>

                    <form action="{{ route('polls.destroy', [$event->readingGroup, $event, $poll]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce sondage?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-refresh results for live voting
    const autoRefresh = setInterval(function() {
        @if($poll->isActive())
            fetch('{{ route("polls.results", [$event->readingGroup, $event, $poll]) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.is_active) {
                        location.reload(); // Refresh page to show updated results
                    }
                });
        @endif
    }, 5000); // Refresh every 5 seconds

    // Clear interval on page unload
    window.addEventListener('beforeunload', () => clearInterval(autoRefresh));

    // Enhanced rating selection
    document.querySelectorAll('.rating-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.rating-btn').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
</script>
@endsection
