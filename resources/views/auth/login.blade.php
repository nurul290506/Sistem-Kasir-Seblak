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
            <div class="logo">
                <i class="fa-solid fa-fire-burner"></i>
            </div>
            <h2>Seblak Bundaka</h2>
            <p>Sistem Informasi Kasir & Persediaan</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3 mb-4 rounded-3" style="font-size: 14px; background-color: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.2); color: #fca5a5;">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-white-50" style="border: 1px solid rgba(255,255,255,0.1); border-top-left-radius: 12px; border-bottom-left-radius: 12px;"><i class="fa-regular fa-user"></i></span>
                    <input type="text" name="username" id="username" class="form-control border-start-0" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-white-50" style="border: 1px solid rgba(255,255,255,0.1); border-top-left-radius: 12px; border-bottom-left-radius: 12px;"><i class="fa-regular fa-keyboard"></i></span>
                    <input type="password" name="password" id="password" class="form-control border-start-0" placeholder="Masukkan password" required style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                </div>
            </div>

            <button type="submit" class="btn btn-submit w-100">
                Masuk <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
            </button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
