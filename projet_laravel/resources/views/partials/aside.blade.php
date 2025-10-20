<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 50%, #3b82f6 100%);">
    
    <!-- Sidebar - Brand avec animation -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}" style="padding: 1.5rem 0;">
        <div class="sidebar-brand-icon" style="animation: bookFloat 3s ease-in-out infinite;">
            <i class="fas fa-book-reader" style="font-size: 2rem; color: #60a5fa;"></i>
        </div>
        <div class="sidebar-brand-text mx-3" style="font-weight: 700; font-size: 1.3rem; letter-spacing: 1px;">
            Book<span style="color: #60a5fa;">Share</span>
        </div>
    </a>

    <!-- Divider élégant -->
    <hr class="sidebar-divider my-0" style="border-color: rgba(255,255,255,0.1);">

    @if(Auth::user()->isAdmin())
        <!-- Divider avec style -->
        <hr class="sidebar-divider" style="border-color: rgba(255,255,255,0.2); margin: 1rem 0;">

        <!-- Heading Admin avec badge -->
        <div class="sidebar-heading" style="color: #fbbf24; font-size: 0.7rem; font-weight: 800; letter-spacing: 2px; padding: 0 1rem;">
            👑 ADMINISTRATION
        </div>

        <!-- Nav Item - Dashboard Admin -->
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}" style="border-radius: 8px; margin: 0.5rem 1rem; transition: all 0.3s; background: rgba(96, 165, 250, 0.15);">
                <i class="fas fa-fw fa-crown" style="color: #fbbf24;"></i>
                <span style="font-weight: 700; color: #fbbf24;">Dashboard Admin</span>
            </a>
        </li>

        <!-- Nav Item - User Management -->
        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}" style="border-radius: 8px; margin: 0.5rem 1rem; transition: all 0.3s;">
                <i class="fas fa-fw fa-users-cog" style="color: #60a5fa;"></i>
                <span style="font-weight: 600;">Utilisateurs</span>
            </a>
        </li>

        <!-- Nav Item - Categories Management -->
        <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.categories.index') }}" style="border-radius: 8px; margin: 0.5rem 1rem; transition: all 0.3s;">
                <i class="fas fa-fw fa-tags" style="color: #a78bfa;"></i>
                <span style="font-weight: 600;">Catégories</span>
            </a>
        </li>

        <!-- Nav Item - Admin Reviews Management -->
        <li class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.reviews.index') }}" style="border-radius: 8px; margin: 0.5rem 1rem; transition: all 0.3s;">
                <i class="fas fa-fw fa-star-half-alt" style="color: #fbbf24;"></i>
                <span style="font-weight: 600;">Avis (Modération)</span>
            </a>
        </li>

        <!-- Nav Item - Exchanges Management -->
        <li class="nav-item {{ request()->routeIs('admin.exchanges.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.exchanges.index') }}" style="border-radius: 8px; margin: 0.5rem 1rem; transition: all 0.3s;">
                <i class="fas fa-fw fa-exchange-alt" style="color: #60a5fa;"></i>
                <span style="font-weight: 600;">Échanges</span>
            </a>
        </li>

        <!-- Nav Item - Locations (Réservations) Management -->
        <li class="nav-item {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.locations.index') }}" style="border-radius: 8px; margin: 0.5rem 1rem; transition: all 0.3s;">
                <i class="fas fa-fw fa-calendar-check" style="color: #8b5cf6;"></i>
                <span style="font-weight: 600;">Réservations</span>
            </a>
        </li>

        <!-- Nav Item - Groupes Management -->
        <li class="nav-item {{ request()->routeIs('admin.groupes.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.groupes.index') }}" style="border-radius: 8px; margin: 0.5rem 1rem; transition: all 0.3s;">
                <i class="fas fa-fw fa-user-friends" style="color: #34d399;"></i>
                <span style="font-weight: 600;">Groupes</span>
            </a>
        </li>

        <!-- Nav Item - Événements Management -->
        <li class="nav-item {{ request()->routeIs('admin.evenements.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.evenements.index') }}" style="border-radius: 8px; margin: 0.5rem 1rem; transition: all 0.3s;">
                <i class="fas fa-fw fa-calendar-alt" style="color: #93c5fd;"></i>
                <span style="font-weight: 600;">Événements</span>
            </a>
        </li>

        <!-- Nav Item - Reports Management avec badge urgent -->
        @php 
            $pendingReports = \App\Models\Report::pending()->count(); 
            $urgentReports = \App\Models\Report::urgent()->pending()->count();
        @endphp
        <li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.reports.index') }}" style="border-radius: 8px; margin: 0.5rem 1rem; transition: all 0.3s; position: relative;">
                <i class="fas fa-fw fa-flag" style="color: #ef4444;"></i>
                <span style="font-weight: 600;">Signalements</span>
                @if($urgentReports > 0)
                    <span class="badge badge-danger ml-auto" style="font-size: 0.7rem; animation: pulse 2s infinite; position: absolute; right: 1rem;">{{ $urgentReports }}</span>
                @elseif($pendingReports > 0)
                    <span class="badge badge-warning ml-auto" style="font-size: 0.7rem; position: absolute; right: 1rem;">{{ $pendingReports }}</span>
                @endif
            </a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block" style="border-color: rgba(255,255,255,0.1);">

    <!-- Sidebar Toggler avec style amélioré -->
    <div class="text-center d-none d-md-inline" style="padding: 1rem 0;">
        <button class="rounded-circle border-0" id="sidebarToggle" style="background: rgba(255,255,255,0.1); width: 40px; height: 40px; transition: all 0.3s;"></button>
    </div>

    <!-- User info badge en bas -->
    <div class="sidebar-card d-none d-lg-flex" style="background: linear-gradient(135deg, rgba(96, 165, 250, 0.2) 0%, rgba(59, 130, 246, 0.2) 100%); border-radius: 10px; margin: 1rem; padding: 1rem; border: 1px solid rgba(96, 165, 250, 0.3);">
        <div class="text-center">
            <div class="mb-2">
                <i class="fas fa-user-circle" style="font-size: 2rem; color: #60a5fa;"></i>
            </div>
            <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">{{ Auth::user()->name }}</div>
            <div style="font-size: 0.7rem; color: #93c5fd;">{{ Auth::user()->email }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light" style="font-size: 0.75rem; border-radius: 20px;">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>
</ul>

<style>
/* Animation flottante pour l'icône du logo */
@keyframes bookFloat {
    0%, 100% { transform: translateY(0px) rotate(-15deg); }
    50% { transform: translateY(-10px) rotate(-15deg); }
}

/* Animation pulse pour le badge urgent */
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.1); }
}

/* Hover effects pour les liens */
.sidebar .nav-link:hover {
    background: rgba(96, 165, 250, 0.15) !important;
    transform: translateX(5px);
}

.sidebar .nav-link.active {
    background: rgba(96, 165, 250, 0.25) !important;
    border-left: 4px solid #60a5fa;
}

/* Collapse items hover */
.sidebar .collapse-inner .collapse-item:hover {
    background: rgba(96, 165, 250, 0.15) !important;
    color: #60a5fa !important;
    transform: translateX(5px);
}

.sidebar .collapse-inner .collapse-item.active {
    background: rgba(96, 165, 250, 0.25) !important;
    color: #60a5fa !important;
    font-weight: 700 !important;
}

/* Sidebar toggler hover */
#sidebarToggle:hover {
    background: rgba(96, 165, 250, 0.3) !important;
    transform: scale(1.1);
}

/* Badge styles */
.badge-counter {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
}

.sidebar .nav-link {
    position: relative;
}

/* Scrollbar personnalisée */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(0,0,0,0.1);
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(96, 165, 250, 0.5);
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(96, 165, 250, 0.8);
}
</style>

