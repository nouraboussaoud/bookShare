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
    
    <!-- Custom Styles -->
    <style>
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.12) !important;
        }
        .card-img-top {
            transition: transform 0.3s ease;
        }
        .card:hover .card-img-top {
            transform: scale(1.02);
        }
        .btn-group .btn {
            border-radius: 0;
        }
        .btn-group .btn:first-child {
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }
        .btn-group .btn:last-child {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }
        .badge {
            font-size: 0.75em;
        }
        .card-title a {
            color: #2c3e50;
            text-decoration: none;
        }
        .card-title a:hover {
            color: #007bff;
            text-decoration: none;
        }
        .book-card {
            overflow: hidden;
        }
        .book-card .card-img-top {
            border-radius: 0.25rem 0.25rem 0 0;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        .btn-group-sm .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .card-img-top {
            object-fit: cover;
            width: 100%;
        }
        .bg-gradient-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
        }
        .text-shadow {
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }
        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }
        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }
    </style>
</head>
<body id="page-top">
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