<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Bienvenido, {{ $name }}</h1>
    </header>
    <main>
        <p>Hola {{ $name }}, gracias por registrarte, para activar tu cuenta da clic en el siguiente enlace, en
            seguida
            recibira un codigo al numero registrado:
        </p>

        <a href="{{ $url }}">Activar Cuenta</a>
    </main>
</body>

</html>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #fff;
        margin: 0;
        padding: 0;
        color: #333;
    }

    header {
        background-color: #007bff;
        padding: 20px;
        color: #fff;
        text-align: center;
    }

    h1,
    h2 {
        color: #007bff;
    }

    main {
        padding: 20px;
        text-align: center;
    }

    a {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-size: 18px;
    }

    a:hover {
        background-color: #0056b3;
    }
</style>
