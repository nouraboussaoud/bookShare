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
            BookShare - Admin
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
    
    <style>
        /* Dynamic Sidebar Styles */
        #accordionSidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            z-index: 1030;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #content-wrapper {
            margin-left: 250px;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Ensure smooth transitions */
        .sidebar {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        /* Mobile responsiveness */
        @media (max-width: 767.98px) {
            #content-wrapper {
                margin-left: 0 !important;
            }
            #accordionSidebar {
                transform: translateX(0) !important;
                opacity: 1 !important;
            }
        }
    </style>
    
    @stack('styles')
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('partials.aside')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">

                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; BookShare {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- SB Admin 2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>
    
    @stack('scripts')

    <!-- Dynamic Sidebar Scroll Behavior -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let lastScrollTop = 0;
            const sidebar = document.getElementById('accordionSidebar');
            const contentWrapper = document.getElementById('content-wrapper');
            let isHidden = false;
            const sidebarWidth = sidebar.offsetWidth;

            // Add transition styles
            sidebar.style.transition = 'transform 0.3s ease-in-out, opacity 0.3s ease-in-out';
            contentWrapper.style.transition = 'margin-left 0.3s ease-in-out';

            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                // Only apply behavior when scrolled past 100px and not on mobile
                if (scrollTop > 100 && window.innerWidth >= 768) {
                    if (scrollTop > lastScrollTop && !isHidden) {
                        // Scrolling down - hide sidebar
                        sidebar.style.transform = 'translateX(-100%)';
                        sidebar.style.opacity = '0';
                        contentWrapper.style.marginLeft = '0';
                        isHidden = true;
                    } else if (scrollTop < lastScrollTop && isHidden) {
                        // Scrolling up - show sidebar
                        sidebar.style.transform = 'translateX(0)';
                        sidebar.style.opacity = '1';
                        contentWrapper.style.marginLeft = sidebarWidth + 'px';
                        isHidden = false;
                    }
                } else if (isHidden && window.innerWidth >= 768) {
                    // Show sidebar when back at top
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.style.opacity = '1';
                    contentWrapper.style.marginLeft = sidebarWidth + 'px';
                    isHidden = false;
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                const newSidebarWidth = sidebar.offsetWidth;
                if (window.innerWidth < 768) {
                    // On mobile, reset styles
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.style.opacity = '1';
                    contentWrapper.style.marginLeft = '0';
                    isHidden = false;
                } else if (!isHidden) {
                    contentWrapper.style.marginLeft = newSidebarWidth + 'px';
                }
            });
        });
    </script>
</body>
</html>
