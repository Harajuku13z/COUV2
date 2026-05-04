<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --setup-primary: #365446;
            --setup-secondary: #1f2b25;
            --setup-accent: #d8892b;
        }

        body {
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: #163021;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top left, rgba(54, 84, 70, 0.16), transparent 28%),
                radial-gradient(circle at bottom right, rgba(216, 137, 43, 0.18), transparent 24%),
                linear-gradient(180deg, #f3f1ea 0%, #f7f8f4 38%, #eef3ef 100%);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            border-radius: 2rem;
            padding: 2.5rem;
            border: 1px solid rgba(255,255,255,0.7);
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(14px);
            box-shadow: 0 24px 80px rgba(28,44,37,0.1);
        }

        .login-kicker {
            letter-spacing: 0.28em;
            text-transform: uppercase;
            font-size: 0.76rem;
            font-weight: 800;
            color: var(--setup-primary);
        }

        .form-control {
            border-radius: 1rem;
            border-color: rgba(54,84,70,0.14);
            background: #f8faf7;
            padding: 0.9rem 1rem;
        }

        .form-control:focus {
            border-color: rgba(54,84,70,0.46);
            box-shadow: 0 0 0 0.25rem rgba(54,84,70,0.14);
            background: #fff;
        }

        .btn-login {
            border: 0;
            border-radius: 999px;
            padding: 0.9rem 1.4rem;
            font-weight: 700;
            color: #fff;
            width: 100%;
            background: linear-gradient(135deg, var(--setup-primary), #4e7662);
            box-shadow: 0 14px 30px rgba(54,84,70,0.2);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #2d4539, #3e6350);
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-kicker mb-2">Administration</div>
        <h1 class="fw-800 mb-1" style="font-size:1.8rem;font-weight:800;color:#17251f;">Connexion</h1>
        <p class="text-secondary mb-4" style="font-size:.95rem;">Acces reserve a l'administrateur du site.</p>

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 mb-4 py-2 px-3" style="font-size:.9rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/admin/login">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold text-secondary small">Adresse email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="admin@monsite.fr"
                       autofocus
                       required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary small">Mot de passe</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="••••••••"
                       required>
            </div>
            <button type="submit" class="btn btn-login">Se connecter</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
