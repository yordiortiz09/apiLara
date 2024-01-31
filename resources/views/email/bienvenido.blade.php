<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>
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

<body class="bg-dark>

    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title">¡Hola, {{ auth()->user()->name }}!</h5>
                    <p class="card-text">Gracias por iniciar sesión. Bienvenido de nuevo.</p>
                    <form action="{{ route('logOut') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Cerrar sesión</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>



</body>

</html>
