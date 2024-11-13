<?php
require_once 'bd.php';
session_start();

if (isset($_SESSION["logueado"]) && $_SESSION["logueado"] == "1") {
    // Obtener el término de búsqueda si existe
    $busqueda = isset($_POST['busqueda']) ? trim($_POST['busqueda']) : '';

    // Ejecutar búsqueda o mostrar todos los tickets si no hay búsqueda
    if (!empty($busqueda)) {
        $tickets = buscarTicketsPorDescripcion($busqueda);
    } else {
        $tickets = tecnicoTickets();
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tickets de Empleados</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                flex-direction: column;
                align-items: center;
                margin: 0;
                padding: 20px;
                background-color: #001f3f;
                /* Fondo azul oscuro */
                color: #ffffff;
            }

            h1 {
                color: #00c0ff;
                /* Azul claro para el título */
            }

            table {
                width: 100%;
                max-width: 800px;
                border-collapse: collapse;
                background-color: #ffffff;
                color: #333333;
                margin-top: 20px;
            }

            th,
            td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #003366;
                color: #ffffff;
            }

            tr:hover {
                background-color: #f2f2f2;
            }

            .logout-button {
                position: fixed;
                /* Fija el botón en la pantalla */
                bottom: 20px;
                /* Espaciado desde abajo */
                right: 20px;
                /* Espaciado desde la derecha */
                padding: 10px 15px;
                /* Espaciado interno */
                background-color: #ff4d4d;
                /* Color de fondo rojo */
                color: white;
                /* Color del texto */
                border: none;
                /* Sin borde */
                border-radius: 5px;
                /* Bordes redondeados */
                cursor: pointer;
                /* Cambia el cursor al pasar sobre el botón */
            }

            .logout-button:hover {
                background-color: #ff1a1a;
                /* Color más oscuro al pasar el mouse */
            }
        </style>
    </head>

    <body>
        <h1>Tickets de Empleados</h1>

        <!-- Formulario de búsqueda -->
        <form method="POST" action="" class="search-bar">
            <input type="text" name="busqueda" placeholder="Buscar por descripción"
                value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit">Buscar</button>
        </form>

        <?php if (is_array($tickets) && !empty($tickets)): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Empleado</th>
                    <th>Asunto</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Fecha de Creación</th>
                    <th>Fecha de Actualización</th>
                    <th>Detalles</th>
                </tr>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['id']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['nombre_empleado']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['asunto']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['estado']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['prioridad']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['fecha_creacion']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['fecha_actualizacion']); ?></td>
                        <td><a href='detalleTicketTecnico.php?id=<?php echo htmlspecialchars($ticket['id']); ?>'>Ver detalles</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No hay tickets que coincidan con la búsqueda.</p>
        <?php endif; ?>

        <!-- Botón de cerrar sesión -->
        <form action="cerrarSesion.php" method="post">
            <button type="submit" class="logout-button">Cerrar Sesión</button>
        </form>
    </body>

    </html>
    <?php
} else {
    // Redirigir al login si el usuario no está logueado o no es técnico
    header("Location: login.php");
    exit();
}

?>