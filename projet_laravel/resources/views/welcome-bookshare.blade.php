<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="BookShare - Partagez vos livres préférés avec une communauté passionnée">
    <meta name="author" content="BookShare">

    <title>BookShare - Votre plateforme de partage de livres</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --dark-color: #2d3748;
            --light-color: #f7fafc;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-hero: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            overflow-x: hidden;
        }

        .hero-section {
            background: var(--gradient-hero);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="300" cy="700" r="120" fill="url(%23a)"/><circle cx="700" cy="800" r="80" fill="url(%23a)"/></svg>');
            opacity: 0.3;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-custom .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.8rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-custom .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-custom .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-custom .nav-link:hover::after {
            width: 100%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 8rem 0 4rem;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 4.5rem;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 3rem;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-custom {
            background: var(--gradient-secondary);
            border: none;
            padding: 1rem 2.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.6);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.8);
            padding: 1rem 2.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease-out 0.9s both;
        }

        .btn-outline-custom:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem 1.5rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-section {
            background: var(--light-color);
            padding: 5rem 0;
        }

        .stat-item {
            text-align: center;
            margin-bottom: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
        }

        .stat-label {
            font-size: 1.1rem;
            color: var(--dark-color);
            font-weight: 500;
        }

        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 1rem 0;
            position: relative;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 4rem;
            color: var(--accent-color);
            font-family: Georgia, serif;
        }

        .floating-book {
            position: absolute;
            opacity: 0.1;
            animation: floatBook 15s ease-in-out infinite;
        }

        @keyframes floatBook {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(10deg); }
        }

        .cta-section {
            background: var(--gradient-primary);
            color: white;
            padding: 5rem 0;
            text-align: center;
        }

        .footer-custom {
            background: var(--dark-color);
            color: white;
            padding: 3rem 0 1rem;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .btn-custom, .btn-outline-custom {
                padding: 0.8rem 2rem;
                font-size: 1rem;
                margin: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Floating Books Animation -->
    <div class="floating-book" style="top: 10%; left: 5%; font-size: 2rem;">
        <i class="fas fa-book text-primary"></i>
    </div>
    <div class="floating-book" style="top: 20%; right: 10%; font-size: 1.5rem;">
        <i class="fas fa-book-open text-secondary"></i>
    </div>
    <div class="floating-book" style="bottom: 30%; left: 15%; font-size: 1.8rem;">
        <i class="fas fa-bookmark text-info"></i>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-book-open me-2"></i>BookShare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fonctionnalités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Statistiques</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Témoignages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Connexion
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary rounded-pill px-3 ms-2" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Inscription
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show mx-auto mb-5" style="max-width: 500px;" role="alert" data-aos="fade-down">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="row align-items-center min-vh-100">
                    <div class="col-lg-6" data-aos="fade-right">
                        <h1 class="hero-title">
                            Partagez la <span style="color: var(--accent-color);">magie</span> des livres
                        </h1>
                        <p class="hero-subtitle">
                            Rejoignez une communauté passionnée de lecteurs, découvrez de nouveaux horizons littéraires et partagez vos coups de cœur avec le monde entier.
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="{{ route('register') }}" class="btn-custom">
                                <i class="fas fa-rocket me-2"></i>Commencer l'aventure
                            </a>
                            <a href="{{ route('login') }}" class="btn-outline-custom">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center" data-aos="fade-left">
                        <div class="position-relative">
                            <i class="fas fa-book-open" style="font-size: 15rem; color: rgba(255,255,255,0.8); text-shadow: 0 0 50px rgba(255,255,255,0.5);"></i>
                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <div class="d-flex justify-content-center mb-3">
                                        <i class="fas fa-star text-warning mx-1" style="font-size: 1.5rem;"></i>
                                        <i class="fas fa-star text-warning mx-1" style="font-size: 1.5rem;"></i>
                                        <i class="fas fa-star text-warning mx-1" style="font-size: 1.5rem;"></i>
                                        <i class="fas fa-star text-warning mx-1" style="font-size: 1.5rem;"></i>
                                        <i class="fas fa-star text-warning mx-1" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <p class="text-white fs-5 fw-bold">Plus de 1000+ livres partagés</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Pourquoi choisir BookShare ?</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Partagez facilement</h4>
                        <p class="text-muted">Ajoutez vos livres préférés en quelques clics et partagez vos découvertes littéraires avec une communauté bienveillante.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Découvrez sans limite</h4>
                        <p class="text-muted">Explorez une bibliothèque infinie de recommandations personnalisées basées sur vos goûts et vos lectures précédentes.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Communauté active</h4>
                        <p class="text-muted">Échangez avec des lecteurs passionnés, participez à des discussions enrichissantes et créez des liens authentiques.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Système de notes</h4>
                        <p class="text-muted">Notez et commentez vos lectures pour aider la communauté à découvrir les meilleurs ouvrages.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Accessible partout</h4>
                        <p class="text-muted">Interface responsive et intuitive, accessible depuis tous vos appareils pour ne jamais manquer une bonne lecture.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Sécurisé et privé</h4>
                        <p class="text-muted">Vos données sont protégées et votre vie privée respectée dans un environnement sécurisé et bienveillant.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="stats-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">BookShare en chiffres</h2>
            <div class="row">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <span class="stat-number">1,250+</span>
                        <span class="stat-label">Livres partagés</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Membres actifs</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <span class="stat-number">2,800+</span>
                        <span class="stat-label">Avis publiés</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Satisfaction</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Ce que disent nos membres</h2>
            <div class="row">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <p class="mb-3">"BookShare a révolutionné ma façon de découvrir de nouveaux livres. La communauté est fantastique et les recommandations sont toujours pertinentes."</p>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Sophie Martin</h6>
                                <small class="text-muted">Lectrice passionnée</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <p class="mb-3">"Grâce à BookShare, j'ai découvert des genres littéraires que je n'aurais jamais explorés seul. Une véritable mine d'or culturelle !"</p>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Alexandre Dubois</h6>
                                <small class="text-muted">Étudiant en littérature</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <p class="mb-3">"L'interface est intuitive et l'esprit communautaire est exceptionnel. Je recommande BookShare à tous les amoureux de la lecture."</p>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Marie Rousseau</h6>
                                <small class="text-muted">Bibliothécaire</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <h2 class="display-4 fw-bold mb-4">Prêt à rejoindre l'aventure ?</h2>
                    <p class="lead mb-5">Des milliers de lecteurs vous attendent déjà. Rejoignez BookShare dès aujourd'hui et découvrez votre prochaine lecture coup de cœur.</p>
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill me-3">
                        <i class="fas fa-rocket me-2"></i>Commencer maintenant
                    </a>
                    <a href="#features" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                        <i class="fas fa-info-circle me-2"></i>En savoir plus
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-book-open me-2"></i>BookShare
                    </h5>
                    <p class="text-light">La plateforme qui connecte les passionnés de lecture et transforme chaque livre en une aventure partagée.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light fs-4"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-light fs-4"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light fs-4"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light fs-4"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Plateforme</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Accueil</a></li>
                        <li><a href="{{ route('register') }}" class="text-light text-decoration-none">S'inscrire</a></li>
                        <li><a href="{{ route('login') }}" class="text-light text-decoration-none">Se connecter</a></li>
                        <li><a href="#features" class="text-light text-decoration-none">Fonctionnalités</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Communauté</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Blog</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Événements</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Forums</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Newsletter</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Aide</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Contact</a></li>
                        <li><a href="#" class="text-light text-decoration-none">FAQ</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Signaler un bug</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Légal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Confidentialité</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Conditions</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Cookies</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Mentions légales</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 text-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 BookShare. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Fait avec <i class="fas fa-heart text-danger"></i> pour les amoureux des livres</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom JS -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.15)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add hover effects to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Counter animation for stats
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200;

            counters.forEach(counter => {
                const target = +counter.innerText.replace(/[^\d]/g, '');
                const increment = target / speed;
                let current = 0;

                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        const display = Math.ceil(current);
                        const originalText = counter.innerText;
                        const suffix = originalText.replace(/[\d,]/g, '');
                        counter.innerText = display.toLocaleString() + suffix;
                        setTimeout(updateCounter, 1);
                    } else {
                        const originalText = counter.getAttribute('data-original') || counter.innerText;
                        counter.innerText = originalText;
                    }
                };

                // Store original text
                counter.setAttribute('data-original', counter.innerText);
                updateCounter();
            });
        }

        // Trigger counter animation when stats section is in view
        const statsSection = document.querySelector('#stats');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        if (statsSection) {
            observer.observe(statsSection);
        }

        // Add parallax effect to floating books
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            document.querySelectorAll('.floating-book').forEach((book, index) => {
                const speed = 0.5 + (index * 0.1);
                book.style.transform = `translateY(${rate * speed}px) rotate(${scrolled * 0.02}deg)`;
            });
        });
    </script>
</body>

</html>
