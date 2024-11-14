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
        <title>Paginal Principal tenico - Tickets de Empleados</title>
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
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        box-sizing: border-box;
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
    h1, p {
        margin: 0;
        color: white;
    }
    .content {
        background-color: white;
        padding: 20px;
        margin-top: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
        color: #333333;
        margin-top: 20px;
        table-layout: fixed; /* Ajusta el ancho de cada columna */
    }
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
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
            <div class="container">
                <h1>Tickets de Empleados</h1>
                <p>Bienvenido/a tecnico: <?php echo htmlspecialchars($_SESSION["nombre"]); ?></p>
            </div>
        </header>
        
        
        <div class="container content">
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
        </div>
        

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