<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<style>
    body {
        background-color: #282c35;
        color: #ffffff;
        margin: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card {
        background-color: #36383a;
        width: 400px;
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .card-title {
        font-size: 2.5rem;
        color: #e44d26;
        /* Naranja */
    }

    .form-control {
        border: 1px solid #495057;
        border-radius: 10px;
        background-color: #495057;
        color: #ffffff;
        transition: box-shadow 0.3s;
    }

    .form-control:focus {
        border-color: #e44d26;
        /* Naranja */
        box-shadow: 0 0 10px rgba(228, 77, 38, 0.3);
    }

    .btn-primary {
        background-color: #e44d26;
        /* Naranja */
        border: none;
        border-radius: 10px;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #d33f1a;
        /* Naranja más oscuro */
    }

    .btn-link {
        color: #e44d26;
        /* Naranja */
        text-decoration: none;
        transition: color 0.3s;
    }

    .btn-link:hover {
        color: #d33f1a;
        /* Naranja más oscuro */
    }
</style>

<body>

    <div class="card">
        <h3 class="mb-4 text-center card-title" style="color: #e44d26; font-size: 2.5rem; font-weight: bold;">
            Registro</h3>

        <form action="{{ route('registro') }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="name" required minlength="3" maxlength="30">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" class="form-control" name="email" required minlength="10" maxlength="50">

                @error('email')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" name="password" required minlength="8" maxlength="30">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Teléfono:</label>
                <input type="text" class="form-control" name="phone" required pattern="\d{10}" maxlength="10">

                @error('phone')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-1 text-center">
                <p>¿Ya tienes una cuenta?</p>
                <a href="{{ route('login.form') }}" class="btn btn-link" style="color: #e44d26;">Inicia Sesión</a>
            </div>




            <div class="mt-4 text-center d-grid">
                <div id="html_element" class="mt-4 text-center"></div>
                @error('g-recaptcha-response')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block"
                style="background-color: #e44d26; border: none; border-radius: 10px;">Registrarse</button>


        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

    <script type="text/javascript">
        var onloadCallback = function() {
            grecaptcha.render('html_element', {
                'sitekey': '6LfQkV8pAAAAALQAt1K2fJYjdG47IPXO4OHSnCU0',
                'theme': 'dark'

            });
        };
    </script>

</body>

</html>
