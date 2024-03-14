<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Código</title>
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
        box-shadow: 0 0 10px rgba(228, 77, 38, 0.3);
    }

    .btn-primary {
        background-color: #e44d26;
        border: none;
        border-radius: 10px;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #d33f1a;
    }

    .alert-danger {
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px;
        margin-top: 10px;
    }
</style>

<body class="d-flex align-items-center justify-content-center vh-100 bg-dark">

    <div class="card p-4">
        <h1 class="mb-4 text-center">Verificación de 2 pasos</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('verificarCodigo') }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="codigo" class="form-label">Ingrese su código de verificación:</label>
                <input type="text" class="form-control" name="codigo" required maxlength="4">
            </div>

            <input type="hidden" name="user" value="{{ request('user') }}">
            <div class="center">
                <button type="submit" class="btn btn-primary">Verificar Código</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>
