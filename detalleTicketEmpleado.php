<?php
require_once 'bd.php';
    session_start();
    if(!isset($_SESSION["logueado"]) || $_SESSION["logueado"] != "0") {
        header("Location: login.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $ticket = obtenerTicket($_GET['id']);
            if ($ticket) {
            
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
    </style>
</head>
<body>
    <div class="ticket-container">
        <h2>Detalles del Ticket</h2>
        <div class="ticket-info">
            <p><span class="label">ID:</span> <span class="value"><?php echo htmlspecialchars($ticket['id']); ?></span></p>
            <p><span class="label">Asunto:</span> <span class="value"><?php echo htmlspecialchars($ticket['asunto']); ?></span></p>
            <p><span class="label">Estado:</span> <span class="value"><?php echo htmlspecialchars($ticket['estado']); ?></span></p>
            <p><span class="label">Fecha de Creación:</span> <span class="value"><?php echo htmlspecialchars($ticket['fecha_creacion']); ?></span></p>
            <p><span class="label">Descripción:</span></p>
            <p class="value"><?php echo nl2br(htmlspecialchars($ticket['descripcion'])); ?></p>
        </div>
        <div class="messages">
        <a href="enviar_mensaje.php?ticket_id=<?php echo $ticketId; ?>" class="button">Enviar Mensaje</a>
            <h3>Mensajes</h3>
            <?php
            $mensajes = obtenerMensajesTicket($ticketId); // Asegúrate de implementar esta función
            if ($mensajes) {
                foreach ($mensajes as $mensaje) {
                    echo "<div class='message'>";
                    $nombre = letraUpper($mensaje['nombre']);
                    echo "<p>" . htmlspecialchars($nombre) . "</p>";
                    echo "<p>" . htmlspecialchars($mensaje['contenido']) . "</p>";
                    echo "<p class='message-date'>" . htmlspecialchars($mensaje['fecha_envio']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay mensajes para este ticket.</p>";
            }
            ?>
        </div>
        <a href="DprincipalSesiones.php" class="back-link">Volver a Mis Tickets</a>
    </div>
</body>
</html>
<?php
 }
} else{
    header("Location: DprincipalSesiones.php");
exit();
}
      } 