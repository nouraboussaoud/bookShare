<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-book"></i>
        </div>
        <div class="sidebar-brand-text mx-3">BookShare</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') || request()->routeIs('user.dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Gestion des Livres
    </div>

    <!-- Nav Item - Books -->
    <li class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBooks"
            aria-expanded="{{ request()->routeIs('books.*') ? 'true' : 'false' }}" aria-controls="collapseBooks">
            <i class="fas fa-fw fa-book"></i>
            <span>Livres</span>
        </a>
        <div id="collapseBooks" class="collapse {{ request()->routeIs('books.*') ? 'show' : '' }}" aria-labelledby="headingBooks" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestion des Livres:</h6>
                <a class="collapse-item {{ request()->routeIs('books.index') ? 'active' : '' }}" href="{{ route('books.index') }}">
                    <i class="fas fa-list"></i> Tous les Livres
                </a>
                <a class="collapse-item {{ request()->routeIs('books.create') ? 'active' : '' }}" href="{{ route('books.create') }}">
                    <i class="fas fa-plus"></i> Ajouter un Livre
                </a>
            </div>
        </div>
    </li>


    <!-- Nav Item - Reviews -->
    <li class="nav-item {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReviews"
            aria-expanded="{{ request()->routeIs('reviews.*') ? 'true' : 'false' }}" aria-controls="collapseReviews">
            <i class="fas fa-fw fa-star"></i>
            <span>Avis</span>
        </a>
        <div id="collapseReviews" class="collapse {{ request()->routeIs('reviews.*') ? 'show' : '' }}" aria-labelledby="headingReviews" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestion des Avis:</h6>
                <a class="collapse-item {{ request()->routeIs('reviews.index') ? 'active' : '' }}" href="{{ route('reviews.index') }}">
                    <i class="fas fa-list"></i> Tous les Avis
                </a>
                <a class="collapse-item {{ request()->routeIs('reviews.create') ? 'active' : '' }}" href="{{ route('reviews.create') }}">
                    <i class="fas fa-plus"></i> Ajouter un Avis
                </a>
            </div>
        </div>
    </li>

    @if(Auth::user()->isAdmin())
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Administration
        </div>

        <!-- Nav Item - User Management -->
        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Gestion Utilisateurs</span>
            </a>
        </li>

        <!-- Nav Item - Categories Management -->
        <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                <i class="fas fa-fw fa-tags"></i>
                <span>Gestion Catégories</span>
            </a>
        </li>

        <!-- Nav Item - Admin Reviews Management -->
        <li class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.reviews.index') }}">
                <i class="fas fa-fw fa-star-half-alt"></i>
                <span>Gestion Avis (Admin)</span>
            </a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
