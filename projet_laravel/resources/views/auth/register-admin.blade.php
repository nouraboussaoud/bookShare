<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BookShare - Inscription</title>

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
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image">
                        <div class="p-5 d-flex align-items-center justify-content-center h-100">
                            <div class="text-center">
                                <i class="fas fa-user-plus fa-10x text-primary opacity-25"></i>
                                <h1 class="h4 text-gray-900 mt-4">Rejoignez BookShare</h1>
                                <p class="text-gray-600">Créez votre compte pour partager vos livres</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Créer un compte !</h1>
                            </div>
                            
                            <form class="user" method="POST" action="{{ route('register') }}">
                                @csrf
                                
                                <div class="form-group">
                                    <input type="text" 
                                           class="form-control form-control-user @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Nom complet..." 
                                           required 
                                           autofocus 
                                           autocomplete="name">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <input type="email" 
                                           class="form-control form-control-user @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Adresse Email..." 
                                           required 
                                           autocomplete="username">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" 
                                               class="form-control form-control-user @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Mot de passe..." 
                                               required 
                                               autocomplete="new-password">
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" 
                                               class="form-control form-control-user @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Confirmer le mot de passe..." 
                                               required 
                                               autocomplete="new-password">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    S'inscrire
                                </button>
                            </form>
                            
                            <hr>
                            
                            <div class="text-center">
                                <a class="small" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                            </div>
                            
                            <div class="text-center">
                                <a class="small" href="{{ route('login') }}">Vous avez déjà un compte ? Connectez-vous !</a>
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
