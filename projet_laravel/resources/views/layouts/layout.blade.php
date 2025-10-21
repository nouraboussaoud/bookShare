<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
        @hasSection('title')
            @yield('title')
        @else
            SB Admin 2
        @endif
    </title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- SB Admin 2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
</head>
<body id="page-top">
<<<<<<< Updated upstream
    <div id="wrapper">
        @auth
            <!-- Sidebar -->
            @include('partials.aside')
        @endauth
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column"@guest style="margin-left: 0;"@endguest>
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                @include('partials.navbar')
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
=======
    <!-- Navbar moderne en haut -->
    @auth
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm mb-0" style="background: rgba(255, 255, 255, 0.95); border-bottom: 1px solid #eef2f7; position: sticky; top: 0; z-index: 1030;">
        <div class="container-fluid">
            <!-- Logo BookShare -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}" style="font-weight:600; letter-spacing:.3px;">
                <img src="{{ asset('images/bookshare_logo.png') }}" alt="BookShare Logo" style="height: 40px; margin-right: 10px;">
                BookShare
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">Livres</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('reading-progress.*') ? 'active' : '' }}" href="{{ route('reading-progress.index') }}">Mes Lectures</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('reading-groups.*') ? 'active' : '' }}" href="{{ route('reading-groups.index') }}">Groupes</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('locations.marketplace') ? 'active' : '' }}" href="{{ route('locations.marketplace') }}">Marketplace</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">Locations</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                        Paiements
                        @php $pendingPayments = \App\Models\ReservationPayment::whereHas('location', function($q) { $q->where('locataire_id', auth()->id()); })->where('statut_paiement', 'en_attente')->count(); @endphp
                        @if($pendingPayments > 0)
                            <span class="badge bg-danger ms-1">{{ $pendingPayments }}</span>
                        @endif
                    </a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('exchanges.*') ? 'active' : '' }}" href="{{ route('exchanges.index') }}">Échanges</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.create') }}">
                        Signaler
                    </a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}" href="{{ route('reviews.index') }}">Avis</a></li>
                </ul>

                <!-- Recherche -->
                <form class="d-flex me-3" method="GET" action="{{ route('user.dashboard') }}">
                    <div class="input-group">
                        <input class="form-control border-0" name="search" type="search" placeholder="Rechercher un livre..." value="{{ request('search') }}" style="background:#f1f5f9; border-radius: 24px 0 0 24px;">
                        <button class="btn" type="submit" style="border-radius: 0 24px 24px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Notifications & User Menu -->
                <ul class="navbar-nav mb-0">
                    <!-- Notifications Dropdown -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell fs-5"></i>
                            @php $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
                            <li class="dropdown-header d-flex justify-content-between align-items-center">
                                <span><strong>Notifications</strong></span>
                                @if($unreadCount > 0)
                                    <small class="badge bg-primary">{{ $unreadCount }} nouvelles</small>
                                @endif
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            @php $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())->orderBy('created_at', 'desc')->take(5)->get(); @endphp
                            @forelse($recentNotifications as $notification)
                                <li class="px-3 py-2 {{ $notification->is_read ? 'bg-light' : 'bg-warning bg-opacity-10' }}" style="border-left: 3px solid {{ $notification->is_read ? '#e0e0e0' : '#ffc107' }};">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-2">
                                            @switch($notification->type)
                                                @case('exchange_request')
                                                @case('exchange_accepted')
                                                @case('exchange_rejected')
                                                    <i class="fas fa-exchange-alt text-primary"></i>
                                                    @break
                                                @case('report_created')
                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                    @break
                                                @case('review_added')
                                                    <i class="fas fa-star text-success"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-info-circle text-info"></i>
                                            @endswitch
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 small"><strong>{{ $notification->title ?? 'Notification' }}</strong></p>
                                            <p class="mb-1 text-muted small">{{ Str::limit($notification->message ?? 'Nouveau message', 60) }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </li>
                                @if(!$loop->last)<li><hr class="dropdown-divider my-1"></li>@endif
                            @empty
                                <li class="px-3 py-4 text-center text-muted">
                                    <i class="fas fa-bell-slash mb-2 d-block"></i>
                                    Aucune notification
                                </li>
                            @endforelse
                            
                            @if($recentNotifications->count() > 0)
                                <li><hr class="dropdown-divider"></li>
                                <li class="text-center">
                                    <a class="dropdown-item small" href="{{ route('notifications.index') }}">
                                        <i class="fas fa-list me-1"></i>Voir toutes les notifications
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (Route::has('profile.edit'))
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
>>>>>>> Stashed changes
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            @include('partials.footer')
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- SB Admin 2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>
    
    <script>
        // Ensure logout buttons work properly
        $(document).ready(function() {
            // Handle logout form submission
            $('form[action*="logout"]').on('submit', function(e) {
                console.log('Logout form submitted');
                return true; // Allow form submission
            });
            
            // Handle dropdown toggle
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                $(this).next('.dropdown-menu').toggle();
            });
        });
    </script>
</body>
</html>