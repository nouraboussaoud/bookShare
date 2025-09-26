<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BookShare - Login</title>

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
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <div class="p-5 d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center">
                                        <i class="fas fa-book-open fa-10x text-primary opacity-25"></i>
                                        <h1 class="h4 text-gray-900 mt-4">BookShare</h1>
                                        <p class="text-gray-600">Partagez vos livres préférés</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenue !</h1>
                                    </div>

                                    <!-- Session Status -->
                                    @if (session('status'))
                                        <div class="alert alert-success mb-4" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <form class="user" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        
                                        <div class="form-group">
                                            <input type="email" 
                                                   class="form-control form-control-user @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email') }}" 
                                                   placeholder="Adresse Email..." 
                                                   required 
                                                   autofocus 
                                                   autocomplete="username">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <input type="password" 
                                                   class="form-control form-control-user @error('password') is-invalid @enderror" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Mot de passe..." 
                                                   required 
                                                   autocomplete="current-password">
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                                                <label class="custom-control-label" for="remember_me">Se souvenir de moi</label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Se connecter
                                        </button>
                                    </form>
                                    
                                    <hr>
                                    
                                    <div class="text-center">
                                        @if (Route::has('password.request'))
                                            <a class="small" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                                        @endif
                                    </div>
                                    
                                    <div class="text-center">
                                        @if (Route::has('register'))
                                            <a class="small" href="{{ route('register') }}">Créer un compte !</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- SB Admin 2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>
</body>

</html>
