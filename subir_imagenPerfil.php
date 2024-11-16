<?php
session_start();
require_once('bd.php');

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['id']) && isset($_FILES['foto'])) {
        $id_usuario = $_SESSION['id'];
        $directorio = 'perfiles/';
        $archivo = $_FILES['foto'];

        // Validar el tipo de archivo
        $tipoArchivo = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $tipoArchivoPermitido = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($tipoArchivo), $tipoArchivoPermitido)) {
            // Generar un nombre único
            $nombreArchivo = uniqid() . '.' . $tipoArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $directorio . $nombreArchivo)) {
                actualizarFotoPerfil($id_usuario, $directorio . $nombreArchivo);
                $mensaje = "Foto de perfil actualizada con éxito.";
            } else {
                $mensaje = "Error al mover el archivo.";
            }
        } else {
            $mensaje = "Tipo de archivo no permitido.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de Subida</title>
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

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #003366;
            margin-bottom: 20px;
        }

        .message {
            font-size: 1em;
            margin-bottom: 20px;
            color: #003366;
        }

        .error {
            color: red;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #ffffff;
            background-color: #003366;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .back-link:hover {
            background-color: #002244;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Resultado de la Subida</h2>
        <p class="message <?php echo empty($mensaje) ? 'error' : ''; ?>">
            <?php echo htmlspecialchars($mensaje ?: "No se recibió un archivo para procesar."); ?>
        </p>
        <a href="<?php echo ($_SESSION["logueado"] == 0) ? 'perfil.php' : 'perfilTecnico.php'; ?>" class="back-link">Volver al Perfil</a>
    </div>
</body>

</html>