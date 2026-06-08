<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT Support Ticketing</title>
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
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3fcef;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border-radius: var(--rounded-md) !important;
            border: 1px solid var(--color-border-light) !important;
            background-color: #ffffff;
            box-shadow: var(--shadow-lg) !important;
        }
        .login-logo {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body class="login-page">
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="login-logo">
                <i class="bi bi-shield-check"></i> OTTO IT
            </div>
            <p class="text-muted body-sm">Infrastructure Management Core</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm py-2 px-3 mb-3 text-danger body-sm rounded-default" style="background-color: #ffdad6; color: #93000a;">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('login') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label label text-secondary">EMAIL ADDRESS</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="admin@example.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label label text-secondary">PASSWORD</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" style="accent-color: var(--color-primary);">
                    <label class="form-check-label text-muted body-sm" for="remember">
                        Remember session
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-2">
                Sign In <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>
        
        <div class="text-center mt-3">
            <span class="text-muted body-sm">Or need immediate assistance?</span>
            <a href="{{ route('tickets.public-create') }}" class="btn btn-outline-success w-100 mt-2 py-2 d-flex align-items-center justify-content-center" style="border-radius: var(--rounded-default); font-weight: 600; border-color: var(--color-primary); color: var(--color-primary); background: transparent;">
                <i class="bi bi-file-earmark-plus me-2"></i> Submit Guest Ticket
            </a>
        </div>
        
        <div class="text-center mt-4">
            <span class="text-muted caption">Contact IT support if you cannot access your account.</span>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
