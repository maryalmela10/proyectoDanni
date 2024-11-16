<?php
require_once 'bd.php';
session_start();
$archivo_adjunto = null;
$mensaje = '';
$err = false;
// Voy a ver si el usuario se ha logueado
if (isset($_SESSION["logueado"]) && $_SESSION["logueado"] == "0") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["asunto"]) && isset($_POST["descripcion"]) && !empty($_POST["asunto"]) && !empty($_POST["descripcion"])) {
            $id = (int)$_SESSION['id'];
            $archivo= null;
        if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
            $archivo_adjunto = $_FILES['archivo']['name'];
            $archivo_tmp = $_FILES['archivo']['tmp_name'];
            $archivo_destino = 'ficherosUsuarios/' . $archivo_adjunto;
            
            if(move_uploaded_file($archivo_tmp, $archivo_destino)) {
                $archivo = $archivo_adjunto;
            } else {
                $err = true;
                $mensaje = "Error al subir el archivo.";
            }
        }
        
        $resultado = crearTicket($id, $_POST["descripcion"], $_POST["asunto"], $archivo);
        if($resultado) {
            header("Location: ticketCreado.php");
        } else {
            $err = true;
            $mensaje = "Error al crear el ticket.";
        } 
    }
}
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
                background-color: #001f3f;
                /* Fondo azul oscuro */
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
                /* Azul oscuro para el título */
            }

            form {
                display: flex;
                flex-direction: column;
            }

            label {
                margin-top: 10px;
                color: #003366;
            }

            input[type="text"],
            textarea {
                width: 100%;
                padding: 10px;
                margin: 5px 0 15px;
                border: 1px solid #003366;
                border-radius: 4px;
                box-sizing: border-box;
            }

            textarea {
                height: 100px;
                resize: vertical;
            }

            input[type="submit"] {
                width: 100%;
                padding: 10px;
                background-color: #003366;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: #002244;
            }
        </style>
    </head>

    <body>
        <div class="ticket-container">
            <h2>Crear Nuevo Ticket</h2>
            <form action="crearTickets.php" enctype="multipart/form-data" method="POST">
                <label for="asunto">Asunto:</label>
                <input type="text" id="asunto" name="asunto" required>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
                <label for="archivo">Adjuntar archivo:</label>
                <input type="file" id="archivo" name="archivo">
                <br>
                <input type="submit" value="Crear Ticket">
            </form>
            <?php if ($mensaje): ?>
                <div class="mensaje <?php echo $err ? 'error' : 'exito'; ?>">
                    <?php echo $mensaje;
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </body>

    </html>
<?php
} else {
    // Le mando al loguin
    header("Location: login.php");
}
