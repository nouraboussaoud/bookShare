@extends('layouts.layout')
@section('title', 'Nouvel Avis')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Nouvel Avis</h1>
            <p class="mb-0 text-gray-600">Donner votre avis sur un livre</p>
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
                    <h6 class="m-0 font-weight-bold text-primary">Informations de l'Avis</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="book_id">Livre à évaluer <span class="text-danger">*</span></label>
                            <select class="form-control @error('book_id') is-invalid @enderror" 
                                    id="book_id" name="book_id" required>
                                <option value="">Sélectionnez un livre</option>
                                @foreach($books as $bookOption)
                                    <option value="{{ $bookOption->id }}" 
                                            {{ (old('book_id') == $bookOption->id || (isset($book) && $book->id == $bookOption->id)) ? 'selected' : '' }}
                                            data-title="{{ $bookOption->title }}"
                                            data-author="{{ $bookOption->author }}"
                                            data-category="{{ $bookOption->category->name ?? 'Aucune' }}"
                                            data-owner="{{ $bookOption->user->name }}">
                                        {{ $bookOption->title }} - {{ $bookOption->author }} (par {{ $bookOption->user->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="rating">Note <span class="text-danger">*</span></label>
                            <div class="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="star-label">
                                        <input type="radio" name="rating" value="{{ $i }}" 
                                               {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Cliquez sur les étoiles pour donner votre note (1 = très mauvais, 5 = excellent)</small>
                        </div>

                        <div class="form-group">
                            <label for="comment">Commentaire</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" name="comment" rows="5" 
                                      placeholder="Partagez votre opinion sur ce livre...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maximum 1000 caractères</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Publier l'Avis
                            </button>
                            <a href="{{ route('reviews.index') }}" class="btn btn-secondary">
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
                    <h6 class="m-0 font-weight-bold text-primary">Livre Sélectionné</h6>
                </div>
                <div class="card-body">
                    <div id="book-preview">
                        @if(isset($book))
                            <div class="book-info">
                                <h5>{{ $book->title }}</h5>
                                <p class="text-muted">par {{ $book->author }}</p>
                                @if($book->category)
                                    <span class="badge" style="background-color: {{ $book->category->color }}; color: white;">
                                        {{ $book->category->name }}
                                    </span>
                                @endif
                                <hr>
                                <small class="text-info">Propriétaire: {{ $book->user->name }}</small>
                            </div>
                        @else
                            <p class="text-muted text-center">Sélectionnez un livre pour voir ses détails</p>
                        @endif
                    </div>
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
                        <li><i class="fas fa-check text-success"></i> Votre avis sera modéré avant publication</li>
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
            const bookSelect = document.getElementById('book_id');
            const bookPreview = document.getElementById('book-preview');
            const stars = document.querySelectorAll('.star-label');
            
            // Book selection preview
            bookSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (selectedOption.value) {
                    const title = selectedOption.dataset.title;
                    const author = selectedOption.dataset.author;
                    const category = selectedOption.dataset.category;
                    const owner = selectedOption.dataset.owner;
                    
                    bookPreview.innerHTML = `
                        <div class="book-info">
                            <h5>${title}</h5>
                            <p class="text-muted">par ${author}</p>
                            <span class="badge badge-info">${category}</span>
                            <hr>
                            <small class="text-info">Propriétaire: ${owner}</small>
                        </div>
                    `;
                } else {
                    bookPreview.innerHTML = '<p class="text-muted text-center">Sélectionnez un livre pour voir ses détails</p>';
                }
            });
            
            // Star rating functionality
            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    // Reset all stars
                    stars.forEach(s => s.querySelector('i').style.color = '#ddd');
                    
                    // Fill stars up to clicked one
                    for (let i = 0; i <= index; i++) {
                        stars[i].querySelector('i').style.color = '#ffc107';
                    }
                });
                
                star.addEventListener('mouseenter', function() {
                    // Highlight stars on hover
                    for (let i = 0; i <= index; i++) {
                        stars[i].querySelector('i').style.color = '#ffc107';
                    }
                });
                
                star.addEventListener('mouseleave', function() {
                    // Reset to selected rating
                    const checkedInput = document.querySelector('input[name="rating"]:checked');
                    const checkedIndex = checkedInput ? parseInt(checkedInput.value) - 1 : -1;
                    
                    stars.forEach((s, i) => {
                        s.querySelector('i').style.color = i <= checkedIndex ? '#ffc107' : '#ddd';
                    });
                });
            });
        });
    </script>
@endsection
