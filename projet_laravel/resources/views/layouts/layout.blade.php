<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="BookShare">
    <meta name="author" content="">

    <title>
        @hasSection('title')
            @yield('title')
        @else
            BookShare
        @endif
    </title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- SB Admin 2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --bg: #f7f8fb;            /* blanc cassé */
            --card: #ffffff;          /* cartes */
            --accent: #a5c7f9;        /* bleu clair */
            --accent-strong: #5aa6ff; /* bleu accent */
            --success: #8bd3a3;       /* vert statuts */
            --text: #1f2937;          /* gris foncé */
            --muted: #6b7280;         /* gris moyen */
        }
        
        html, body {
            font-family: 'Poppins', 'Inter', 'Open Sans', 'Nunito', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        
        .navbar-min {
            backdrop-filter: saturate(180%) blur(8px);
            background: rgba(255, 255, 255, 0.92) !important;
            border-bottom: 1px solid #eef2f7;
        }
        
        .card.smooth {
            background: var(--card);
            border: 1px solid #eef2f7;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(17, 24, 39, 0.06);
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
        .badge-soft {
            background: #eef2ff;
            color: #3b82f6;
            border-radius: 10px;
            padding: 0.35rem 0.6rem;
            font-weight: 500;
        }
        a:hover { text-decoration: none; }
        
        /* Ajustement pour la navbar fixe */
        #wrapper {
            margin-top: 0;
        }
        
        .sidebar {
            top: 0;
            height: 100vh;
        }
    </style>

    @stack('styles')
</head>
<body id="page-top">
    <!-- Navbar moderne en haut -->
    @auth
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm mb-0" style="background: rgba(255, 255, 255, 0.95); border-bottom: 1px solid #eef2f7; position: sticky; top: 0; z-index: 1030;">
        <div class="container-fluid">
            <!-- Logo BookShare -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}" style="font-weight:600; letter-spacing:.3px;">
                <i class="fas fa-book-open me-2" style="color: var(--accent-strong);"></i>
                BookShare
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">Livres</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('locations.marketplace') ? 'active' : '' }}" href="{{ route('locations.marketplace') }}">Marketplace</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}" href="{{ route('locations.index') }}">Locations</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('exchanges.*') ? 'active' : '' }}" href="{{ route('exchanges.index') }}">Échanges</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}" href="{{ route('reviews.index') }}">Avis</a></li>
                </ul>

                <!-- Recherche -->
                <form class="d-flex me-3" method="GET" action="{{ route('user.dashboard') }}">
                    <div class="input-group">
                        <input class="form-control border-0" name="search" type="search" placeholder="Rechercher un livre..." value="{{ request('search') }}" style="background:#f1f5f9; border-radius: 24px 0 0 24px;">
                        <button class="btn btn-primary" type="submit" style="border-radius: 0 24px 24px 0;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- User Menu -->
                <ul class="navbar-nav mb-0">
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
            </div>
        </div>
    </nav>
    @endauth
    
    <!-- Main Content (sans sidebar) -->
    <div class="container-fluid py-4">
        @yield('content')
    </div>
    
    @include('partials.footer')
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
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/fr.min.js"></script>
    <script>moment.locale('fr');</script>

    <script>
        // Ensure logout buttons work properly
        $(document).ready(function() {
            // Handle logout form submission
            $('form[action*="logout"]').on('submit', function(e) {
                console.log('Logout form submitted');
                return true;
            });
            
            // Handle dropdown toggle
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                $(this).next('.dropdown-menu').toggle();
            });

            // Load notifications
            loadNotifications();
            loadNotificationCount();

            // Refresh notifications every 30 seconds
            setInterval(loadNotificationCount, 30000);
        });

        function loadNotificationCount() {
            @auth
            $.ajax({
                url: '{{ route("notifications.unreadCount") }}',
                method: 'GET',
                success: function(response) {
                    const counter = $('#notification-counter');
                    if (response.count > 0) {
                        counter.text(response.count > 9 ? '9+' : response.count);
                        counter.show();
                    } else {
                        counter.hide();
                    }
                },
                error: function() {
                    console.log('Erreur lors du chargement du compteur de notifications');
                }
            });
            @endauth
        }

        function loadNotifications() {
            @auth
            $('#notificationsDropdown').on('click', function() {
                $.ajax({
                    url: '{{ route("notifications.recent") }}',
                    method: 'GET',
                    success: function(response) {
                        const notificationsList = $('#notifications-list');
                        notificationsList.empty();

                        if (response.notifications.length === 0) {
                            notificationsList.html(`
                                <div class="dropdown-item text-center text-muted">
                                    <i class="fas fa-bell-slash mr-2"></i>Aucune notification
                                </div>
                            `);
                        } else {
                            response.notifications.forEach(function(notification) {
                                const iconClass = getNotificationIcon(notification.type);
                                const timeAgo = moment(notification.created_at).fromNow();
                                const isUnread = !notification.is_read;
                                
                                notificationsList.append(`
                                    <a class="dropdown-item d-flex align-items-center ${isUnread ? 'bg-light' : ''}" 
                                       href="/notifications/${notification.id}/mark-read">
                                        <div class="mr-3">
                                            <div class="icon-circle ${getNotificationBgClass(notification.type)}">
                                                <i class="${iconClass} text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small text-gray-500">${timeAgo}</div>
                                            <span class="font-weight-bold">${notification.title}</span>
                                            <div class="small text-gray-600">${notification.message.substring(0, 60)}...</div>
                                        </div>
                                        ${isUnread ? '<div class="ml-2"><span class="badge badge-primary">Nouveau</span></div>' : ''}
                                    </a>
                                `);
                            });
                        }
                    },
                    error: function() {
                        $('#notifications-list').html(`
                            <div class="dropdown-item text-center text-danger">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Erreur de chargement
                            </div>
                        `);
                    }
                });
            });
            @endauth
        }

        function getNotificationIcon(type) {
            switch(type) {
                case 'exchange_request':
                    return 'fas fa-handshake';
                case 'exchange_status_change':
                    return 'fas fa-sync-alt';
                default:
                    return 'fas fa-bell';
            }
        }

        function getNotificationBgClass(type) {
            switch(type) {
                case 'exchange_request':
                    return 'bg-warning';
                case 'exchange_status_change':
                    return 'bg-info';
                default:
                    return 'bg-primary';
            }
        }
    </script>

    @stack('scripts')
    
    <!-- Report Modal -->
    @auth
        @include('components.report-modal')
    @endauth
</body>
</html>
