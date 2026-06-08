<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'Dashboard') - IT Support Ticketing</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Style Sheet -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Workspace -->
        <div class="main-content">
            <!-- Header/Navbar Component -->
            <x-navbar />

            <!-- Core Dashboard Content -->
            <main class="content-container">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarMenu = document.getElementById('sidebarMenu');
            
            if (sidebarToggle && sidebarMenu) {
                sidebarToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    sidebarMenu.classList.toggle('show');
                });
                
                document.addEventListener('click', function (e) {
                    if (!sidebarMenu.contains(e.target) && sidebarMenu.classList.contains('show')) {
                        sidebarMenu.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>
</html>
