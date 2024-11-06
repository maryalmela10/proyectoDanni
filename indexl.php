<!-- 2. Modifica de nuevo el ejemplo de login visto (modificado por última vez "Ejercicios de sesiones") para que, al loguearse un usuario, 
se compruebe que las credenciales son correctas contra la bases de datos. Debes comprobarlo en la tabla "usuarios" de la base de datos "empresa". -->
<!-- 3. Añade a la página de login un enlace que permita registrarse a nuevos usuarios y crea esta página. La contraseña tiene al menos una mayúscula, una minúscula y un número. 
Los usuarios no pueden quedar registrados como administradores. -->
<?php
session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        // Miro a ver si me llega el formulario
    if(isset($_POST["usu"]) && isset($_POST["pas"]) && !empty($_POST["usu"]) && !empty($_POST["pas"])) {
    // Compruebo si está bien
    $cadena_conexion = 'mysql:dbname=pmp;host=127.0.0.1';
    $usuario = 'root';
    $clave = '';
    try {
        $bd = new PDO($cadena_conexion, $usuario, $clave);     
        // Select
        $sql = "SELECT email, contraseña, rol FROM usuarios WHERE email='".$_POST["usu"]."' AND contraseña='".$_POST["pas"]."'";
        $usuarios = $bd->query($sql);

        // Itero e imprimo los usuarios
        if($usuarios->rowCount()>0){
            foreach ($usuarios as $usuarito) {
                    $_SESSION["nombre"] = $usuarito['email'];
                    $_SESSION["id"] = $usuarito['id'];
                    $_SESSION["rol"] = $usuarito['rol'];
                    if($_SESSION["rol"]==0){
                        $_SESSION["logueado"] = 0;
                        // Le mando a la página principal
                        header("Location: DprincipalSesiones.php");
                    } else {
                        $_SESSION["logueado"] = 1;
                        header("Location: baseDatosEjercicio5Admin.php");
                    }
                
            }
        } else {
            $_SESSION["error"] = "Las credenciales están mal o no existe el usuario";
            header("Location: login.php");
            exit;
        }
    } catch (PDOException $e) {
        echo 'Error con la base de datos: ' . $e->getMessage();
    }
} else{
    header("Location: login.php");
} } else {
    ?>

    <html>
<head>
<link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<div class="login-container">
<form method="post" action="login.php">
    Usuario: <input type="text" name="usu"> <br>
    Clave: <input type="password" name="pas"><br>
    <input type="submit">
</form>
<?php
    if(isset($_SESSION["error"])) {
        echo $_SESSION["error"];
        unset($_SESSION["error"]); // Limpia el mensaje de error después de mostrarlo
    } 
?>
</div>
<!-- <a href="baseDatosEjercicio4.php">Registro para nuevos usuarios</a> -->
</body>
</html>
<?php
 }
?>
