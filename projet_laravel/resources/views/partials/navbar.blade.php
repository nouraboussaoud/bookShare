<nav class="navbar navbar-expand-lg navbar-light navbar-min shadow-sm mb-4">
    <div class="container-fluid">
        <!-- Sidebar Toggle (Mobile) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none me-3">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Logo BookShare -->
        <a class="navbar-brand d-flex align-items-center d-none d-lg-block" href="{{ route('dashboard') }}" style="font-weight:600; letter-spacing:.3px;">
            <i class="fas fa-book-open me-2" style="color: var(--accent-strong);"></i>
            BookShare
        </a>

        <!-- Menu Navigation -->
        <ul class="navbar-nav me-auto mb-0 d-none d-lg-flex">
            @auth
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">Livres</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('locations.marketplace') ? 'active' : '' }}" href="{{ route('locations.marketplace') }}">Marketplace</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">Réservations</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('reservation-payments.*') ? 'active' : '' }}" href="{{ route('reservation-payments.index') }}">Paiements</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('exchanges.*') ? 'active' : '' }}" href="{{ route('exchanges.index') }}">Échanges</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}" href="{{ route('reviews.index') }}">Avis</a></li>
            @endauth
        </ul>

        <!-- Recherche -->
        @auth
        <form class="d-none d-lg-flex me-lg-3" method="GET" action="{{ route('user.dashboard') }}">
            <div class="input-group">
                <input class="form-control border-0" name="search" type="search" placeholder="Rechercher un livre..." value="{{ request('search') }}" style="background:#f1f5f9; border-radius: 24px 0 0 24px;">
                <button class="btn btn-primary" type="submit" style="border-radius: 0 24px 24px 0;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        @endauth

        <!-- Notifications & User -->
        <ul class="navbar-nav mb-0">
        @auth
        <!-- Notifications -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <span class="badge badge-danger badge-counter" id="notification-counter" style="display: none;">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                aria-labelledby="notificationsDropdown">
                <h6 class="dropdown-header">
                    <i class="fas fa-bell me-2"></i>Notifications
                </h6>
                <div id="notifications-list">
                    <div class="dropdown-item text-center">
                        <i class="fas fa-spinner fa-spin"></i> Chargement...
                    </div>
                </div>
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.index') }}">
                    <i class="fas fa-eye me-1"></i>Voir toutes
                </a>
            </div>
        </li>

        <!-- User Menu -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                aria-labelledby="userDropdown">
                @if (Route::has('profile.edit'))
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                        Profil
                    </a>
                @endif
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline; width: 100%;">
                    @csrf
                    <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </li>
        @else
        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
        @if (Route::has('register'))
        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
        @endif
        @endauth
        </ul>
    </div>
</nav>
