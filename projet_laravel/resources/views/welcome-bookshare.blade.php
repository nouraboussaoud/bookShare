<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookShare - Partagez vos livres préférés</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #667eea !important;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        
        .nav-link-custom {
            color: #1e293b !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }
        
        .nav-link-custom:hover {
            color: #667eea !important;
        }
        
        /* Hero Section */
        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }
        
        .hero-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 0px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: row;
            align-items: center;
            min-height: 500px;
        }
        
        .hero-content {
            flex: 1;
            padding: 4rem;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 1rem;
            line-height: 1.2;
            
        }
        
        .hero-brand {
            color: #667eea;
            display: block;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .hero-description {
            font-size: 1.125rem;
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.7;
        }
        
        .btn-hero {
            padding: 1rem 2.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-right: 1rem;
            margin-bottom: 1rem;
        }
        
        .btn-primary-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: white;
        }
        
        .btn-outline-hero {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-outline-hero:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .hero-image {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
        
        .hero-image img {
            max-width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            filter: drop-shadow(0 10px 30px rgba(102, 126, 234, 0.3));
        }
        
        /* Features Section */
        .features-section {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 2rem;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 0px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }
        
        .feature-description {
            color: #64748b;
            line-height: 1.6;
        }
        
        /* Footer */
        .footer-custom {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem 0;
            margin-top: 4rem;
        }
        
        .footer-text {
            color: #64748b;
            margin: 0;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .hero-card {
                flex-direction: column;
            }
            
            .hero-content {
                padding: 2rem;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-image {
                padding: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.75rem;
            }
            
            .btn-hero {
                display: block;
                margin-right: 0;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/bookshare_logo.png') }}" alt="BookShare Logo">
                BookShare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link-custom" href="{{ url('/dashboard') }}">
                                <i class="fas fa-home me-1"></i>Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link-custom" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Connexion
                            </a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link-custom" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i>Inscription
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-container" style="margin-top: 100px;">
        <div class="hero-card">
            <div class="hero-content">
                <span class="hero-brand">BookShare</span>
                <h1 class="hero-title">
                    Partagez vos livres 
                </h1>
                <p class="hero-description">
                     découvrez de nouvelles lectures et connectez-vous avec une communauté passionnée de lecteurs.
                </p>
                <div>
                    <a href="{{ route('register') }}" class="btn-hero btn-primary-hero">
                        <i class="fas fa-rocket me-2"></i>Commencer
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero btn-outline-hero">
                        <i class="fas fa-book-open me-2"></i>Découvrir
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/bookshare_logo.png') }}" alt="BookShare">
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <h3 class="feature-title">Partagez</h3>
                    <p class="feature-description">
                        Partagez vos livres favoris avec d'autres passionnés de lecture et enrichissez la bibliothèque commune.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="feature-title">Découvrez</h3>
                    <p class="feature-description">
                        Trouvez de nouveaux livres recommandés par la communauté et élargissez vos horizons littéraires.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Connectez</h3>
                    <p class="feature-description">
                        Rejoignez une communauté dynamique de lecteurs et échangez vos impressions sur vos lectures.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="footer-text">&copy; {{ date('Y') }} BookShare. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="footer-text">Fait avec <i class="fas fa-heart text-danger"></i> pour les amoureux des livres</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
