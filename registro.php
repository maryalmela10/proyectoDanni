<?php 
session_start();
require_once 'bd.php';

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['nombre']) && !empty($_POST['nombre']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['clave']) && !empty($_POST['clave'])){
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $clave = $_POST['clave'];
        $rol = verificarEmailEmpresa($email);
        if($rol!=-1){
            if (registrarUsuario($nombre, $email, $clave, $rol)) {
                $mensaje = "¡Te has registrado exitosamente! Revisa tu correo para activar tu cuenta.";
                $_POST = array(); // Limpia todos los campos
            } else {
                $mensaje = "Error: El correo ya está registrado.";
            }
        } else{
            $mensaje = "Error: El correo debe ser un correo de la empresa";
        }
    } 
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Registro de Usuario</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <div class="login-container">
            <h2>Registro de Usuario</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <label for="nombre">Nombre</label>
                <input id="nombre" name="nombre" type="text" required>

                <label for="email">Correo Electrónico</label>
                <input id="email" name="email" type="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>

                <label for="clave">Contraseña</label>
                <input id="clave" name="clave" type="password" value="<?php echo isset($_POST['clave']) ? $_POST['clave'] : ''; ?>" required>

                <input type="submit" value="Registrarse">
            </form>

            <?php
            // Mostrar mensaje si existe
            if ($mensaje != '') {
                echo "<p>$mensaje</p>";
            }
            ?>

            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </body>
</html>



