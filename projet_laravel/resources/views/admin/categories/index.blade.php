@extends('layouts.admin-layout')

@section('title', 'Gestion des Catégories - Admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">📚 Gestion des Catégories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle Catégorie
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Liste des Catégories</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Âge Min.</th>
                        <th>Couleur</th>
                        <th>Livres</th>
                        <th>Statut</th>
                        <th>Mise en avant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} text-primary mr-2"></i>
                                    @endif
                                    <strong>{{ $category->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ Str::limit($category->description, 50) }}
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $category->age_allowed }}+</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="color-preview" 
                                         style="width: 20px; height: 20px; background-color: {{ $category->color }}; border-radius: 3px; margin-right: 8px;"></div>
                                    <small>{{ $category->color }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $category->books_count }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-{{ $category->is_active ? 'success' : 'secondary' }}" 
                                            title="{{ $category->is_active ? 'Désactiver' : 'Activer' }}">
                                        <i class="fas fa-{{ $category->is_active ? 'check' : 'times' }}"></i>
                                        {{ $category->is_active ? 'Actif' : 'Inactif' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.categories.toggle-featured', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-{{ $category->is_featured ? 'warning' : 'outline-warning' }}" 
                                            title="{{ $category->is_featured ? 'Retirer de la mise en avant' : 'Mettre en avant' }}">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.show', $category) }}" 
                                       class="btn btn-sm btn-info" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($category->books_count == 0)
                                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled title="Impossible de supprimer - contient des livres">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                                <strong>Aucune catégorie trouvée</strong><br>
                                <small>Créez votre première catégorie pour organiser les livres.</small><br>
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Créer une catégorie
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Catégories
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Catégories Actives
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->where('is_active', true)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Mises en avant
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->where('is_featured', true)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Livres
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->sum('books_count') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
