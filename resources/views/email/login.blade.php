<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>


<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card p-4 shadow-lg rounded-3">
        <h1 class="mb-4 text-center" style="font-size: 150%">Iniciar Sesión</h1>

        <form action="{{ route('login.submit') }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <p>¿No tienes una cuenta?</p>
            <a href="{{ route('formularioRegistro') }}" class="btn btn-link">Regístrate</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>
