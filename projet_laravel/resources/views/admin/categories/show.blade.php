@extends('layouts.layout')

@section('title', 'Détails de la Catégorie - Admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📚 Détails: {{ $category->name }}</h1>
    <div>
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <!-- Category Details -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Informations de la Catégorie</h6>
                <div>
                    @if($category->is_featured)
                        <span class="badge badge-warning"><i class="fas fa-star"></i> Mise en avant</span>
                    @endif
                    <span class="badge badge-{{ $category->is_active ? 'success' : 'secondary' }}">
                        {{ $category->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Informations Générales</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Nom:</strong></td>
                                <td>
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} text-primary mr-2"></i>
                                    @endif
                                    {{ $category->name }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Description:</strong></td>
                                <td>{{ $category->description ?: 'Aucune description' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Âge minimum:</strong></td>
                                <td><span class="badge badge-info">{{ $category->age_allowed }}+</span></td>
                            </tr>
                            <tr>
                                <td><strong>Couleur:</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 20px; height: 20px; background-color: {{ $category->color }}; border-radius: 3px; margin-right: 8px;"></div>
                                        {{ $category->color }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Icône:</strong></td>
                                <td>
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}"></i> {{ $category->icon }}
                                    @else
                                        <span class="text-muted">Aucune icône</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Ordre d'affichage:</strong></td>
                                <td>{{ $category->sort_order }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Statistiques</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Nombre de livres:</strong></td>
                                <td><span class="badge badge-primary">{{ $booksCount }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Créée le:</strong></td>
                                <td>{{ $category->created_at->format('d/m/Y à H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Modifiée le:</strong></td>
                                <td>{{ $category->updated_at->format('d/m/Y à H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Statut:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $category->is_active ? 'success' : 'secondary' }}">
                                        {{ $category->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Mise en avant:</strong></td>
                                <td>
                                    @if($category->is_featured)
                                        <span class="badge badge-warning"><i class="fas fa-star"></i> Oui</span>
                                    @else
                                        <span class="badge badge-light">Non</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($category->reading_tips)
                    <hr>
                    <h6 class="text-primary">Conseils de Lecture</h6>
                    <p class="text-muted">{{ $category->reading_tips }}</p>
                @endif

                @if($category->popular_authors && count($category->popular_authors) > 0)
                    <hr>
                    <h6 class="text-primary">Auteurs Populaires</h6>
                    <div class="d-flex flex-wrap">
                        @foreach($category->popular_authors as $author)
                            <span class="badge badge-outline-primary mr-2 mb-2">{{ $author }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Books in this Category -->
        @if($recentBooks->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Livres Récents dans cette Catégorie</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Auteur</th>
                                    <th>Propriétaire</th>
                                    <th>Statut</th>
                                    <th>Ajouté le</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBooks as $book)
                                    <tr>
                                        <td>
                                            <strong>{{ $book->title }}</strong>
                                            @if($book->photo)
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-image"></i> Avec photo
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ $book->author }}</td>
                                        <td>{{ $book->user->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $book->status == 'available' ? 'success' : 'warning' }}">
                                                {{ $book->status == 'available' ? 'Disponible' : 'Réservé' }}
                                            </span>
                                        </td>
                                        <td>{{ $book->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($booksCount > 5)
                        <div class="text-center">
                            <small class="text-muted">
                                Affichage des 5 livres les plus récents sur {{ $booksCount }} au total.
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Actions Sidebar -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-block mb-2">
                    <i class="fas fa-edit"></i> Modifier la Catégorie
                </a>
                
                <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="mb-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-{{ $category->is_active ? 'secondary' : 'success' }} btn-block">
                        <i class="fas fa-{{ $category->is_active ? 'times' : 'check' }}"></i>
                        {{ $category->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                </form>

                <form action="{{ route('admin.categories.toggle-featured', $category) }}" method="POST" class="mb-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-{{ $category->is_featured ? 'outline-warning' : 'warning' }} btn-block">
                        <i class="fas fa-star"></i>
                        {{ $category->is_featured ? 'Retirer de la mise en avant' : 'Mettre en avant' }}
                    </button>
                </form>

                @if($booksCount == 0)
                    <form action="{{ route('admin.categories.destroy', $category) }}" 
                          method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> Supprimer la Catégorie
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-block" disabled title="Impossible de supprimer - contient des livres">
                        <i class="fas fa-trash"></i> Supprimer ({{ $booksCount }} livres)
                    </button>
                @endif
            </div>
        </div>

        <!-- Category Preview -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-eye"></i> Aperçu Public
                </h6>
            </div>
            <div class="card-body">
                <div class="card" style="border-left: 4px solid {{ $category->color }};">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            @if($category->icon)
                                <i class="{{ $category->icon }} text-primary mr-2"></i>
                            @endif
                            <h6 class="mb-0">{{ $category->name }}</h6>
                            @if($category->is_featured)
                                <i class="fas fa-star text-warning ml-2" title="Mise en avant"></i>
                            @endif
                        </div>
                        @if($category->description)
                            <p class="text-muted small mb-2">{{ $category->description }}</p>
                        @endif
                        <div>
                            <span class="badge badge-info">{{ $category->age_allowed }}+</span>
                            <span class="badge badge-secondary">{{ $booksCount }} livre{{ $booksCount > 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                </div>
                <small class="text-muted">Aperçu de l'affichage pour les utilisateurs</small>
            </div>
        </div>
    </div>
</div>
@endsection