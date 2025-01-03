<?php
require_once 'bd.php';
session_start();
if (!isset($_SESSION["logueado"]) || $_SESSION["logueado"] != "1") {
    header("Location: login.php");
    exit();
}

$ticket = null;

// Obtener el ID del ticket
$ticketId = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['ticket_id']) ? $_POST['ticket_id'] : null);

if (!$ticketId) {
    die("No se proporcionó ID de ticket");
}

// Procesar POST si existe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['opcionesEstado']) && !empty($_POST['opcionesEstado'])) {
        $nuevoEstado = $_POST['opcionesEstado'];
        actualizarEstadoTicket($ticketId, $nuevoEstado);
    }
}

// Obtener información del ticket (tanto para GET como para POST)
$ticket = obtenerTicket($ticketId);

if (!$ticket) {
    die("No se pudo obtener la información del ticket");
}

$empleadoCreador = datosPerfil($ticket['id_usu']);
$nombreEmpleadoCreador = $empleadoCreador['nombre'];
// Aquí comienza el HTML
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #001f3f;
        }

        .ticket-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 400px;
        }

        h2 {
            text-align: center;
            color: #003366;
        }

        .ticket-info {
            margin-bottom: 20px;
        }

        .ticket-info p {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
            color: #003366;
        }

        .value {
            margin-left: 10px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #003366;
            text-decoration: none;
            padding: 10px;
            background-color: #e0e0e0;
            border-radius: 4px;
        }

        .back-link:hover {
            background-color: #d0d0d0;
        }

        .messages {
            margin-top: 20px;
            border-top: 1px solid #003366;
            padding-top: 20px;
        }

        .message {
            background-color: #f0f0f0;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .message-date {
            font-size: 0.8em;
            color: #666;
        }

        .button {
            display: block;
            background-color: #003366;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            float: right;
            text-align: center;
        }

        .button:hover {
            background-color: #002244;
        }

        .derecha {
            float: right;
        }

        .file-link {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .file-link:hover {
            background-color: #45a049;
        }

        

        .label-container {
            width: 150px;
            display: flex;
            align-items: center;
            background-color: #f0f0f0; 
            padding: 5px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
     
        }

        .foto{
            width: 50px; 
            height: 50px;
            border-radius: 50%; 
            object-fit: cover; 
            border: 2px solid #fff; 
        }

        /* Estilo para la imagen de perfil */
        .fotico {
            width: 40px; 
            height: 40px;
            border-radius: 50%; 
            object-fit: cover; 
            border: 2px solid #fff; 
        }

        .value {
            text-decoration: none;
            color: #0066cc;
            font-weight: 500;
        }

        .value:hover {
            background-color: #f0f0f0; 
        }

        .message-container {
        display: flex; /* Activa el diseño flexbox */
        align-items: center; /* Centra verticalmente los elementos */
        gap: 10px; /* Espacio entre la imagen y el texto */
        margin: 10px 0; /* Espaciado entre mensajes */
        }


        .foto {
            width: 40px; /* Ajusta el tamaño de la imagen */
            height: 40px;
            border-radius: 50%; /* Hace la imagen circular */
            object-fit: cover; /* Evita distorsión de la imagen */
            border: 2px solid #fff; /* Borde opcional */
        }

        .text-container {
            justify-content: center; 
            color: #333; /* Color del texto */
        }

        .rol{
            float: right;
            padding: 5px;
            border-radius: 25px;
            background-color: rgba(20, 4, 255, 0.5);
            font-size: 0.9em; /* Reduce ligeramente el tamaño del rol */
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <h2>Detalles del Ticket</h2>
        <?php if (!empty($mensaje)): ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <div class="ticket-info">
            <p><span>ID:</span> <span class="value"><?php echo htmlspecialchars($ticket['id']); ?></span></p>
            <p class="creador">
                <span class="label">Creado por:</span>
                <div class="label-container">
                    <?php if (!empty($empleadoCreador['foto_perfil'])): ?>
                        <img src="<?php echo htmlspecialchars($empleadoCreador['foto_perfil']); ?>" alt="Foto de Perfil" class="fotico">
                    <?php endif; ?>
                    <a class="value" href="perfil.php?id=<?php echo htmlspecialchars($ticket['id_usu']); ?>">
                        <?php echo htmlspecialchars($nombreEmpleadoCreador); ?>
                    </a>
                </div>
            </p>
            <p><span class="label">Asunto:</span> <span class="value"><?php echo htmlspecialchars($ticket['asunto']); ?></span></p>
            <p><span class="label">Estado:</span>
            <form action="detalleTicketTecnico.php?id=<?php echo $ticketId; ?>" method="POST">
                <input type="hidden" name="ticket_id" value="<?php echo $ticketId; ?>">
                <select id="opciones" name="opcionesEstado">
                    <option value="Creado" <?php echo ($ticket['estado'] == 'Creado') ? 'selected' : ''; ?>>Creado</option>
                    <option value="En proceso" <?php echo ($ticket['estado'] == 'En proceso') ? 'selected' : ''; ?>>En proceso</option>
                    <option value="Solucionado" <?php echo ($ticket['estado'] == 'Solucionado') ? 'selected' : ''; ?>>Solucionado</option>
                    <option value="Cerrado" <?php echo ($ticket['estado'] == 'Cerrado') ? 'selected' : ''; ?>>Cerrado</option>
                </select>
                <input type="submit" value="Actualizar Estado" class="back-link derecha">
            </form>
            <p><span class="label">Descripción:</span></p>
            <p class="value"><?php echo nl2br(htmlspecialchars($ticket['descripcion'])); ?></p>
            <?php
            if (!empty($ticket['archivo_adjunto'])) {
                echo '<p><a href="ficherosUsuarios/' . htmlspecialchars($ticket['archivo_adjunto']) . '" class="file-link" download>Descargar archivo</a></p>';
            }
            ?>
        </div>
        <div class="messages">
            <a href="enviar_mensaje.php?ticket_id=<?php echo $ticketId; ?>" class="button">Enviar Mensaje</a>
            <h3>Mensajes</h3>
            <?php
            $mensajes = obtenerMensajesTicket($ticketId); 
            if ($mensajes) {
                foreach ($mensajes as $mensaje) {
                    $rol = ($mensaje['rol']==0)? "Empleado" : "Técnico";
                    $nombre = letraUpper($mensaje['nombre']);
                    echo "<div class='message'>";
                    if (!empty($mensaje['foto_perfil'])){
                        echo '<p class="message-container">'.'<img src="' . htmlspecialchars($mensaje['foto_perfil']).'" alt="Foto de Perfil" class="foto">' . htmlspecialchars($nombre) . '</p><span class="rol">'.$rol.'</span>';
                     } else {
                        echo "<p>". htmlspecialchars($nombre) . '<span class="rol">'.$rol.'</span></p>';
                     }
                    echo "<p>" . htmlspecialchars($mensaje['contenido']) . "</p>";
                    echo "<p class='message-date'>" . htmlspecialchars($mensaje['fecha_envio']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay mensajes para este ticket.</p>";
            }
            ?>
        </div>
        <a href="paginaTecnico.php" class="back-link">Volver a Mis Tickets</a>
    </div>
</body>

</html>