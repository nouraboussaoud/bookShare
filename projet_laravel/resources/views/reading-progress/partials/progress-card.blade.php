<div class="col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm hover-shadow">
        <div class="card-body">
            <!-- Book Info -->
            <div class="d-flex mb-3">
                @if($progress->book->photo)
                    <img src="{{ asset('storage/' . $progress->book->photo) }}" 
                         alt="{{ $progress->book->title }}" 
                         class="rounded me-3"
                         style="width: 60px; height: 90px; object-fit: cover;">
                @else
                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 90px;">
                        <i class="fas fa-book fa-2x text-muted"></i>
                    </div>
                @endif
                
                <div class="flex-grow-1">
                    <h5 class="card-title mb-1" style="font-size: 1rem;">
                        <a href="{{ route('reading-progress.show', $progress) }}" class="text-decoration-none text-dark">
                            {{ Str::limit($progress->book->title, 40) }}
                        </a>
                    </h5>
                    <p class="text-muted small mb-1">{{ $progress->book->author }}</p>
                    <span class="badge bg-{{ $progress->status === 'reading' ? 'primary' : ($progress->status === 'completed' ? 'success' : ($progress->status === 'to_read' ? 'info' : 'secondary')) }}">
                        {{ $progress->status_label }}
                    </span>
                </div>
            </div>

            <!-- Progress Bar (only for reading/completed) -->
            @if($progress->total_pages && in_array($progress->status, ['reading', 'completed']))
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Progression</small>
                        <small class="fw-bold">{{ $progress->progress_percentage }}%</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: {{ $progress->progress_percentage }}%"
                             aria-valuenow="{{ $progress->progress_percentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted">{{ $progress->current_page }} / {{ $progress->total_pages }} pages</small>
                </div>
            @endif

            <!-- Stats -->
            <div class="d-flex justify-content-between text-muted small mb-3">
                @if($progress->reading_time_minutes > 0)
                    <div>
                        <i class="fas fa-clock me-1"></i>{{ $progress->formatted_reading_time }}
                    </div>
                @endif
                @if($progress->started_at)
                    <div>
                        <i class="fas fa-calendar me-1"></i>{{ $progress->started_at->format('d/m/Y') }}
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="d-flex gap-2">
                <a href="{{ route('reading-progress.show', $progress) }}" 
                   class="btn btn-sm btn-outline-primary flex-grow-1">
                    <i class="fas fa-eye me-1"></i>Détails
                </a>
                
                @if($progress->status === 'to_read')
                    <form action="{{ route('books.startReading', $progress->book) }}" method="POST" class="flex-grow-1">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success w-100">
                            <i class="fas fa-play me-1"></i>Commencer
                        </button>
                    </form>
                @elseif($progress->status === 'reading')
                    <form action="{{ route('reading-progress.complete', $progress) }}" method="POST" class="flex-grow-1">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success w-100">
                            <i class="fas fa-check me-1"></i>Terminer
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
