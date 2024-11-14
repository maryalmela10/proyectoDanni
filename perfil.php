<?php
session_start(); // Inicia la sesión

// Verificar si el usuario está logueado
if (!isset($_SESSION["email"])) {
    // Si no está logueado, redirigir al login
    header("Location: login.php?redirigido=true");
    exit(); 
    // Asegúrate de detener la ejecución de la página después de la redirección
}

// Aquí, puedes obtener los detalles del usuario de la base de datos si es necesario
// Por ejemplo, si ya tienes la sesión iniciada y el nombre del usuario almacenado
// en la sesión, puedes acceder a él directamente.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            color: #003366;
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #003366;
            color: white;
            padding: 10px 0;
        }
        .perfil-link {
            position: absolute;
            top: 10px;
            right: 30px;
            color: #003366;
            text-decoration: none;
            font-weight: bold;
            background-color: white;
            padding: 10px;
            border-radius: 10px;
        }
        nav {
            background-color: #004080;
            padding: 10px 0;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            font-weight: bold;
            margin-right: 20px;
        }
        nav ul li a {
            color: #003366;
            background-color: white;
            border-radius: 5px;
            text-decoration: none;
            padding: 20px;
        }
        a:hover {
            background-color: #d9d9d9; /* Cambia el color al pasar el mouse */
        }
        .content {
            background-color: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .logout-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 15px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-button:hover {
            background-color: #ff1a1a;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <a href="perfil.php" class="perfil-link">Perfil</a>
        </div>
        <div class="container content">
            <h1>Perfil de empleado</h1>
            <p>Bienvenido/a, <?php echo htmlspecialchars($_SESSION["nombre"]); ?>.</p>
        </div>
    </header>
    

    <div class="container">
        <div class="content">
            <h2>Tu Perfil</h2>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($_SESSION["nombre"]); ?></p>
            <p><strong>Email:</strong> <?php echo isset($_SESSION["email"]) ? htmlspecialchars($_SESSION["email"]) : 'No disponible'; ?></p>
            <!-- Aquí puedes agregar más detalles si es necesario -->
        </div>
        <p><a href="DprincipalSesiones.php">Volver a Tickets</a></p>
    </div>

    <!-- Botón de cerrar sesión -->
    <form action="cerrarSesion.php" method="post">
        <button type="submit" class="logout-button">Cerrar Sesión</button>
    </form>

</body>
</html>
