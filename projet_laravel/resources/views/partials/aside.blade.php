<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

<<<<<<< Updated upstream
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
=======
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

        <!-- Nav Item - Payments Management -->
        <li class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.payments.index') }}" style="border-radius: 8px; margin: 0.5rem 1rem; transition: all 0.3s;">
                <i class="fas fa-fw fa-money-bill-wave" style="color: #10b981;"></i>
                <span style="font-weight: 600;">Paiements</span>
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
>>>>>>> Stashed changes

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div>
    </li>

<<<<<<< Updated upstream
    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
=======

    <!-- Nav Item - Locations -->
    <li class="nav-item {{ request()->routeIs('locations.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLocations"
            aria-expanded="{{ request()->routeIs('locations.*') ? 'true' : 'false' }}" aria-controls="collapseLocations">
            <i class="fas fa-fw fa-handshake"></i>
            <span>Locations</span>
        </a>
        <div id="collapseLocations" class="collapse {{ request()->routeIs('locations.*') ? 'show' : '' }}" aria-labelledby="headingLocations" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestion des Locations:</h6>
                <a class="collapse-item {{ request()->routeIs('locations.marketplace') ? 'active' : '' }}" href="{{ route('locations.marketplace') }}">
                    <i class="fas fa-store"></i> Marketplace
                </a>
                <a class="collapse-item {{ request()->routeIs('locations.index') ? 'active' : '' }}" href="{{ route('locations.index') }}">
                    <i class="fas fa-list"></i> Mes Locations
                </a>
                <a class="collapse-item {{ request()->routeIs('locations.help') ? 'active' : '' }}" href="{{ route('locations.help') }}">
                    <i class="fas fa-question-circle"></i> Guide d'aide
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Exchanges -->
    <li class="nav-item {{ request()->routeIs('exchanges.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseExchanges"
            aria-expanded="{{ request()->routeIs('exchanges.*') ? 'true' : 'false' }}" aria-controls="collapseExchanges">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>Échanges</span>
        </a>
        <div id="collapseExchanges" class="collapse {{ request()->routeIs('exchanges.*') ? 'show' : '' }}" aria-labelledby="headingExchanges" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestion des Échanges:</h6>
                <a class="collapse-item {{ request()->routeIs('exchanges.index') ? 'active' : '' }}" href="{{ route('exchanges.index') }}">
                    <i class="fas fa-list"></i> Mes Échanges
                </a>
                <a class="collapse-item {{ request()->routeIs('exchanges.create') ? 'active' : '' }}" href="{{ route('exchanges.create') }}">
                    <i class="fas fa-plus"></i> Réserver un Livre
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
>>>>>>> Stashed changes
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item" href="login.html">Login</a>
                <a class="collapse-item" href="register.html">Register</a>
                <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item" href="blank.html">Blank Page</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
    <div class="sidebar-card d-none d-lg-flex">
        <i class="fas fa-rocket fa-3x text-primary mb-2"></i>
        <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
        <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
    </div>
</ul>