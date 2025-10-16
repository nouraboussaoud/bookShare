<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BookShare') }}</title>

        <!-- Google Fonts - Poppins -->
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
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            }
            
            .auth-container {
                width: 100%;
                max-width: 480px;
            }
            
            .auth-logo {
                text-align: center;
                margin-bottom: 2rem;
            }
            
            .auth-logo img {
                height: 80px;
                margin-bottom: 1rem;
                filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.2));
            }
            
            .auth-logo h1 {
                color: white;
                font-size: 2rem;
                font-weight: 700;
                margin: 0;
            }
            
            .auth-card {
                background: white;
                border-radius: 20px;
                padding: 3rem 2.5rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }
            
            .auth-title {
                font-size: 1.75rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 0.5rem;
                text-align: center;
            }
            
            .auth-subtitle {
                color: #64748b;
                text-align: center;
                margin-bottom: 2rem;
                font-size: 0.95rem;
            }
            
            .form-label {
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }
            
            .form-control {
                border: 2px solid #e2e8f0;
                border-radius: 10px;
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
                transition: all 0.3s ease;
            }
            
            .form-control:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                outline: none;
            }
            
            .form-check-input {
                border: 2px solid #e2e8f0;
                border-radius: 5px;
                cursor: pointer;
            }
            
            .form-check-input:checked {
                background-color: #667eea;
                border-color: #667eea;
            }
            
            .form-check-label {
                color: #64748b;
                font-size: 0.9rem;
                cursor: pointer;
            }
            
            .btn-primary-auth {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 10px;
                padding: 0.875rem 2rem;
                font-weight: 600;
                font-size: 1rem;
                color: white;
                width: 100%;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }
            
            .btn-primary-auth:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            }
            
            .auth-link {
                color: #667eea;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
                font-size: 0.9rem;
            }
            
            .auth-link:hover {
                color: #764ba2;
                text-decoration: underline;
            }
            
            .auth-divider {
                text-align: center;
                margin: 1.5rem 0;
                color: #94a3b8;
                font-size: 0.9rem;
            }
            
            .error-message {
                color: #ef4444;
                font-size: 0.85rem;
                margin-top: 0.25rem;
            }
            
            .success-message {
                background: #d1fae5;
                color: #065f46;
                padding: 1rem;
                border-radius: 10px;
                margin-bottom: 1.5rem;
                font-size: 0.9rem;
            }
            
            @media (max-width: 576px) {
                .auth-card {
                    padding: 2rem 1.5rem;
                }
                
                .auth-title {
                    font-size: 1.5rem;
                }
            }
        </style>
    </head>
    <body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; font-family: 'Poppins', sans-serif !important;">
        <div class="auth-container">
            <div class="auth-logo">
                <a href="/">
                    <img src="{{ asset('images/bookshare_logo.png') }}" alt="BookShare Logo">
                    <h1>BookShare</h1>
                </a>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </body>
</html>
