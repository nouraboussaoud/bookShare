<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BookShare')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #f7f8fb;
            --card: #ffffff;
            --accent: #a5c7f9;
            --accent-strong: #5aa6ff;
            --success: #8bd3a3;
            --text: #1f2937;
            --muted: #6b7280;
        }
        
        html, body {
            font-family: 'Poppins', 'Inter', 'Open Sans', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        
        .navbar-min {
            backdrop-filter: saturate(180%) blur(8px);
            background: rgba(255, 255, 255, 0.92) !important;
            border-bottom: 1px solid #eef2f7;
            z-index: 1030;
        }
        
        .card.smooth {
            background: var(--card);
            border: 1px solid #eef2f7;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(17, 24, 39, 0.06);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        
        .card.smooth:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(17, 24, 39, .08) !important;
        }
        
        .btn-primary {
            background: var(--accent-strong);
            border-color: var(--accent-strong);
        }
        
        .btn-outline-primary:hover {
            background: var(--accent-strong);
            border-color: var(--accent-strong);
            color: #fff;
        }
        
        .btn {
            transition: all .2s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .badge-soft {
            background: #eef2ff;
            color: #3b82f6;
            border-radius: 10px;
            padding: 0.35rem 0.6rem;
            font-weight: 500;
        }
        
        /* Profile Page Styles */
        .avatar-circle {
            transition: transform 0.3s ease;
        }
        
        .avatar-circle:hover {
            transform: scale(1.05);
        }
        
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }
        
        .stats-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.95) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-info:hover, .btn-outline-warning:hover, .btn-outline-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        /* Smaller navbar text */
        .navbar-brand {
            font-size: 1.1rem !important;
        }
        
        .navbar-nav .nav-link {
            font-size: 0.9rem !important;
            padding: 0.5rem 0.75rem !important;
        }
        
        .dropdown-menu .dropdown-item {
            font-size: 0.85rem !important;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar minimaliste Yo!Kart -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-min shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}" style="font-weight:600; letter-spacing:.3px;">
                <img src="{{ asset('images/bookshare_logo.png') }}" alt="BookShare Logo" style="height: 40px; margin-right: 10px;">
                BookShare
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('user.dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}">
                                <i class="fas fa-home me-1"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" 
                               href="{{ route('books.index') }}">
                                <i class="fas fa-book me-1"></i> Livres
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reading-progress.*') ? 'active' : '' }}" 
                               href="{{ route('reading-progress.index') }}">
                                <i class="fas fa-bookmark me-1"></i> Ma Lecture
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reading-groups.*') ? 'active' : '' }}" 
                               href="{{ route('reading-groups.index') }}">
                                <i class="fas fa-users me-1"></i> Groupes
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="communitiesDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-handshake me-1"></i> Communauté
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="communitiesDropdown">
                                <li><a class="dropdown-item" href="{{ route('exchanges.index') }}"><i class="fas fa-exchange-alt me-2"></i> Échanges</a></li>
                                <li><a class="dropdown-item" href="{{ route('locations.marketplace') }}"><i class="fas fa-store me-2"></i> Marché</a></li>
                                <li><a class="dropdown-item" href="{{ route('reviews.index') }}"><i class="fas fa-star me-2"></i> Avis</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('reports.index') }}"><i class="fas fa-flag me-2"></i> Signalements</a></li>
                            </ul>
                        </li>
                        @if(Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-cog me-1"></i> Admin
                            </a>
                        </li>
                        @endif
                    @endauth
                </ul>

                @auth
                <form class="d-flex me-lg-3 my-2 my-lg-0" method="GET" action="{{ route('user.dashboard') }}">
                    <div class="input-group">
                        <input class="form-control border-0" name="search" type="search" placeholder="Rechercher des livres..." value="{{ request('search') }}" style="background:#f1f5f9; border-radius: 24px 0 0 24px;">
                        <button class="btn" type="submit" style="border-radius: 0 24px 24px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                @endauth

                <ul class="navbar-nav mb-2 mb-lg-0 ms-lg-2">
                    @auth
                        <!-- Notifications Dropdown -->
                        <li class="nav-item dropdown me-2">
                            <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fs-5"></i>
                                @php $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
                                @if($unreadCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 320px; max-height: 380px; overflow-y: auto;" aria-labelledby="notificationsDropdown">
                                <li class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span><strong>Notifications</strong></span>
                                    @if($unreadCount > 0)
                                        <small class="badge bg-primary">{{ $unreadCount }} nouveau{{ $unreadCount > 1 ? 'x' : '' }}</small>
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
                                                    @case('group_event')
                                                        <i class="fas fa-calendar text-info"></i>
                                                        @break
                                                    @default
                                                        <i class="fas fa-info-circle text-info"></i>
                                                @endswitch
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-1 small"><strong>{{ $notification->title ?? 'Notification' }}</strong></p>
                                                <p class="mb-1 text-muted small">{{ Str::limit($notification->message ?? 'New message', 50) }}</p>
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
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                        @if (Route::has('register'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">S'inscrire</a></li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="container-fluid py-4" style="padding-top: 1rem !important;">
        @yield('content')
    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
    @stack('scripts')

    <!-- Profile Page Animations -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stats cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe stats cards
            document.querySelectorAll('.card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            // Add loading animation to forms
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Traitement...';
                        submitBtn.disabled = true;
                    }
                });
            });

            // Success message auto-hide
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-success');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    <!-- Chatbot Widget -->
    @auth
        @include('components.chatbot-widget')
    @endauth
</body>
</html>
