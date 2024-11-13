<?php
require_once 'bd.php';

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = urldecode($_GET['email']);
    $token = urldecode($_GET['token']);
    $resultado = activar($email, $token);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activación de Cuenta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #001f3f;
        }
        .login-container {
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
        .message {
            text-align: center;
            color: #003366;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Activación de Cuenta</h2>
    <div class="message">
        <?php
        if (isset($resultado)) {
            if ($resultado) {
                echo "Tu cuenta ha sido activada exitosamente. Ahora puedes <a href='login.php'>iniciar sesión</a>.";
            } else {
                echo "<span class='error'>El enlace de activación no es válido, ha expirado o ya ha sido usado.</span>";
            }
        } else {
            echo "<span class='error'>Enlace de activación inválido.</span>";
        }
        ?>
    </div>
</div>

</body>
</html>
