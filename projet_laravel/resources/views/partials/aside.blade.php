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

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

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