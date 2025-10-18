@extends('layouts.app')
@section('title', 'Modifier l\'Avis')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Modifier l'Avis</h1>
            <p class="mb-0 text-gray-600">Modifier votre avis pour "{{ $review->book->title }}"</p>
        </div>
        <a href="{{ route('reviews.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Modifier l'Avis</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('reviews.update', $review) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Book Info (Read-only) -->
                        <div class="form-group">
                            <label>Livre évalué</label>
                            <div class="form-control-plaintext bg-light p-2 rounded">
                                <strong>{{ $review->book->title }}</strong> - {{ $review->book->author }}
                                <br>
                                <small class="text-muted">par {{ $review->book->user->name }}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="rating">Note <span class="text-danger">*</span></label>
                            <div class="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="star-label">
                                        <input type="radio" name="rating" value="{{ $i }}" 
                                               {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Cliquez sur les étoiles pour modifier votre note</small>
                        </div>

                        <div class="form-group">
                            <label for="comment">Commentaire</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" name="comment" rows="5" 
                                      placeholder="Modifiez votre opinion sur ce livre...">{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maximum 1000 caractères</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à Jour l'Avis
                            </button>
                            <a href="{{ route('reviews.show', $review) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Livre Évalué</h6>
                </div>
                <div class="card-body">
                    <div class="book-info">
                        <h5>{{ $review->book->title }}</h5>
                        <p class="text-muted">par {{ $review->book->author }}</p>
                        @if($review->book->category)
                            <span class="badge" style="background-color: {{ $review->book->category->color }}; color: white;">
                                {{ $review->book->category->name }}
                            </span>
                        @endif
                        <hr>
                        <small class="text-info">Propriétaire: {{ $review->book->user->name }}</small>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Avis Actuel</h6>
                </div>
                <div class="card-body">
                    <div class="current-rating mb-3">
                        <strong>Note actuelle:</strong>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                            <span class="ml-1">({{ $review->rating }}/5)</span>
                        </div>
                    </div>
                    
                    @if($review->comment)
                        <div class="current-comment">
                            <strong>Commentaire actuel:</strong>
                            <div class="bg-light p-2 rounded mt-1">
                                <small>{{ Str::limit($review->comment, 100) }}</small>
                            </div>
                        </div>
                    @endif
                    
                    <hr>
                    <small class="text-muted">
                        Créé le {{ $review->created_at->format('d/m/Y à H:i') }}
                    </small>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Conseils</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-lightbulb text-warning"></i> Soyez honnête dans votre évaluation</li>
                        <li><i class="fas fa-heart text-danger"></i> Respectez les autres lecteurs</li>
                        <li><i class="fas fa-comment text-info"></i> Évitez les spoilers</li>
                        <li><i class="fas fa-clock text-secondary"></i> Vous ne pouvez modifier que les avis en attente</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .rating-input {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
        }
        
        .star-label {
            cursor: pointer;
            font-size: 1.5rem;
            color: #ddd;
            transition: color 0.2s;
        }
        
        .star-label:hover,
        .star-label:hover ~ .star-label {
            color: #ffc107;
        }
        
        .star-label input[type="radio"] {
            display: none;
        }
        
        .star-label input[type="radio"]:checked ~ i,
        .star-label input[type="radio"]:checked + i {
            color: #ffc107;
        }
        
        .star-label:has(input[type="radio"]:checked) i {
            color: #ffc107;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-label');
            
            // Initialize stars based on current rating
            const currentRating = {{ $review->rating }};
            updateStars(currentRating - 1);
            
            // Star rating functionality
            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    updateStars(index);
                });
                
                star.addEventListener('mouseenter', function() {
                    // Highlight stars on hover
                    for (let i = 0; i <= index; i++) {
                        stars[i].querySelector('i').style.color = '#ffc107';
                    }
                    for (let i = index + 1; i < stars.length; i++) {
                        stars[i].querySelector('i').style.color = '#ddd';
                    }
                });
                
                star.addEventListener('mouseleave', function() {
                    // Reset to selected rating
                    const checkedInput = document.querySelector('input[name="rating"]:checked');
                    const checkedIndex = checkedInput ? parseInt(checkedInput.value) - 1 : -1;
                    updateStars(checkedIndex);
                });
            });
            
            function updateStars(selectedIndex) {
                stars.forEach((s, i) => {
                    s.querySelector('i').style.color = i <= selectedIndex ? '#ffc107' : '#ddd';
                });
            }
        });
    </script>
@endsection
