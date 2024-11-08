<?php
require_once 'bd.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Nuevo Ticket</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #001f3f; /* Fondo azul oscuro */
            }

            .ticket-container {
                background-color: #ffffff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
                width: 400px;
                text-align: center; /* Centrar texto dentro del contenedor */
            }

            h2 {
                color: #003366; /* Azul oscuro para el título */
            }

            .button {
                display: inline-block;
                margin-top: 20px; /* Espacio entre el mensaje y el botón */
                padding: 10px 20px;
                background-color: #001f3f; /* Color azul para el botón */
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s;
            }

            .button:hover {
                background-color: #0056b3; /* Color más oscuro al pasar el mouse */
            }
        </style>
    </head>

    <body>
        <div class="ticket-container">
            <h2>Ticket creado exitosamente</h2>
            <a href="DprincipalSesiones.php" class="button">Volver a inicio</a>
        </div>
    </body>

    </html>
<?php
} else {
    // Redirigir al login si no es una solicitud GET
    header("Location: login.php");
}