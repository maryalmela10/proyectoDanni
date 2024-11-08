<?php
// Incluye el archivo con las funciones
require_once 'bd.php';
session_start();

if (isset($_SESSION["logueado"]) && $_SESSION["logueado"] == "1") {
    // Obtener el ID del usuario desde la sesión        
    $id = $_SESSION['id'];
    // Depurar el valor de la variable $_SESSION['id']
    //var_dump($id);  // Verifica qué valor tiene el ID en la sesión

    // Llamar a la función para obtener los tickets del usuario

    $tickets = tecnicoTickets();

    //var_dump($tickets); // Esto te ayudará a depurar qué datos está recibiendo
    // Comprobar si la función devolvió resultados
    if ($tickets) {
        // Mostrar la tabla con los tickets        

        ?><!DOCTYPE html>
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
            </style>
        </head>

        <body>
            <h1>Tickets de Empleados</h1>

            <?php if ($tickets): ?>
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
                            <td><a href='detalleTicketTecnico.php?id="<?php echo htmlspecialchars($ticket['id']);?>"'>Ver detalles</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No hay tickets registrados.</p>
            <?php endif; ?>
        </body>

        </html>
        <?php
    } else {
        // Redirigir al login si el usuario no está logueado o no es técnico
        header("Location: login.php");
        exit();
    }
}
