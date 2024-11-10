<?php
session_start();
if(!isset($_SESSION["logueado"]) || $_SESSION["logueado"] != "0") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Tickets - Página Principal</title>
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
            font-weight:bold;
            margin-right: 20px;
        }
        nav ul li a {
            color: #003366;
            background-color: white;
            border-radius: 5px;
            text-decoration: none;
            padding: 20px;
        }
        .content {
            background-color: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .hidden {
            display: none;
        }
        nav ul li a:hover {
    background-color: #d9d9d9; /* Cambia el color al pasar el mouse */
}
.logout-button {
                    position: fixed; /* Fija el botón en la pantalla */
                    bottom: 20px; /* Espaciado desde abajo */
                    right: 20px; /* Espaciado desde la derecha */
                    padding: 10px 15px; /* Espaciado interno */
                    background-color: #ff4d4d; /* Color de fondo rojo */
                    color: white; /* Color del texto */
                    border: none; /* Sin borde */
                    border-radius: 5px; /* Bordes redondeados */
                    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
                }

                .logout-button:hover {
                    background-color: #ff1a1a; /* Color más oscuro al pasar el mouse */
                }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Sistema de Tickets</h1>
            <p>Bienvenido/a empleado: <?php echo htmlspecialchars($_SESSION["nombre"]); ?></p>
        </div>
    </header>
    
    <nav>
        <div class="container">
            <ul>
                <li><a href="crearTickets.php">Crear Nuevo Ticket</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div id="mis-tickets" class="content">
            <?php include 'misTickets.php'; ?>
        </div>
    </div>
    <!-- Botón de cerrar sesión -->
    <form action="cerrarSesion.php" method="post">
                <button type="submit" class="logout-button">Cerrar Sesión</button>
            </form>
</body>
</html>