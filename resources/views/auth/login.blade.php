<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Seblak Bundaka</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="auth-page">

    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fa-solid fa-fire-burner"></i>
            </div>
            <h3>Seblak Bundaka</h3>
            <p>Sistem Informasi Kasir & Persediaan</p>
        </div>

        <div class="auth-body">
            <form action="{{ route('login') }}" method="POST">
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 mb-4 rounded-3" style="font-size: 13px;">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-3">
                    <label for="username" class="form-label-custom">Username</label>
                    <input type="text" name="username" id="username" class="form-control-custom" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label-custom">Password</label>
                    <input type="password" name="password" id="password" class="form-control-custom" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn btn-primary-custom btn-auth">
                    Masuk <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
