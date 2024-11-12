<?php
require_once 'bd.php';
session_start();

if(!isset($_SESSION["logueado"])) {
    header("Location: login.php");
    exit();
}

$ticketId = isset($_GET['ticket_id']) ? $_GET['ticket_id'] : null;
$mensaje = '';

if (!$ticketId) {
    die("No se proporcionó ID de ticket");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contenidoMensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';
    
    if (!empty($contenidoMensaje)) {
        // implementar la función para guardar el mensaje en la base de datos
        if (crearMensaje($ticketId, $_SESSION['id'], $contenidoMensaje)) {
            $mensaje = "Mensaje enviado con éxito.";
        } else {
            $mensaje = "Error al enviar el mensaje.";
        }
    } else {
        $mensaje = "Por favor, escribe un mensaje.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Mensaje</title>
    <style>
        /* Estilos similares a los de la página de detalles del ticket */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #001f3f;
        }
        .message-container {
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
        textarea {
            width: 95%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
        .submit-button {
            background-color: #003366;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .submit-button:hover {
            background-color: #002244;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #003366;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <h2>Enviar Mensaje</h2>
        <?php if(!empty($mensaje)): ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form action="enviar_mensaje.php?ticket_id=<?php echo $ticketId; ?>" method="POST">
            <textarea name="mensaje" rows="4" placeholder="Escribe tu mensaje aquí" required></textarea>
            <input type="submit" value="Enviar Mensaje" class="submit-button">
        </form>
        <a href="detalleTicketEmpleado.php?id=<?php echo $ticketId; ?>" class="back-link">Volver a Detalles del Ticket</a>
    </div>
</body>
</html>