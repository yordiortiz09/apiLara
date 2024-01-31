<!-- resources/views/verificarCodigo.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Código</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card p-4">
        <h1 class="mb-4 text-center">Verificación de Código</h1>

        <form action="{{ route('verificarCodigo') }}" method="post">
            @csrf

            <div class="mb-3">
                <label for="codigo" class="form-label">Código de Verificación:</label>
                <input type="text" class="form-control" name="codigo" required>
            </div>

            <button type="submit" class="btn btn-primary">Verificar Código</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>
