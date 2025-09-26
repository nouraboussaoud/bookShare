<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="BookShare - Partagez vos livres préférés">
    <meta name="author" content="">

    <title>BookShare - Accueil</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- SB Admin 2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-book-open mr-2"></i>BookShare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt mr-1"></i>Connexion
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus mr-1"></i>Inscription
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container-fluid">
        <div class="row min-vh-100 align-items-center">
            <div class="col-12">
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show mx-auto" style="max-width: 500px;" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="text-center text-white">
                    <div class="mb-5">
                        <i class="fas fa-book-open fa-10x mb-4 opacity-75"></i>
                        <h1 class="display-1 fw-bold mb-4">BookShare</h1>
                        <p class="lead fs-3 mb-5">Partagez vos livres préférés avec la communauté</p>
                    </div>
                    
                    <div class="row justify-content-center mb-5">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="card bg-white bg-opacity-10 border-0 h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-share-alt fa-3x mb-3 text-white"></i>
                                            <h5 class="card-title text-white">Partagez</h5>
                                            <p class="card-text text-white-50">Partagez vos livres favoris avec d'autres lecteurs</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="card bg-white bg-opacity-10 border-0 h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-search fa-3x mb-3 text-white"></i>
                                            <h5 class="card-title text-white">Découvrez</h5>
                                            <p class="card-text text-white-50">Trouvez de nouveaux livres recommandés par la communauté</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="card bg-white bg-opacity-10 border-0 h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-users fa-3x mb-3 text-white"></i>
                                            <h5 class="card-title text-white">Connectez</h5>
                                            <p class="card-text text-white-50">Rejoignez une communauté de passionnés de lecture</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 me-3">
                            <i class="fas fa-user-plus mr-2"></i>Rejoindre BookShare
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 py-3">
                            <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2025 BookShare. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Partagez la passion de la lecture</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- SB Admin 2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>
</body>

</html>
