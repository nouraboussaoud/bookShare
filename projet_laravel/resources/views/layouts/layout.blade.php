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
            BookShare
        @endif
    </title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome - Version locale -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- SB Admin 2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
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