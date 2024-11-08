<?php
require_once 'bd.php';
/* Formulario de login habitual
si va bien abre sesión, guarda el nombre de usuario y redirige a principal.php 
si va mal, mensaje de error */
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
	
	$usu = comprobar_usuario($_POST['usuario'], $_POST['clave']);
	if($usu===false){
		$err = true;
		$usuario = $_POST['usuario'];
	}else{
		session_start();
		$_SESSION["nombre"] = $usu['email'];
		$_SESSION['id'] = $usu['id'];
		$_SESSION["logueado"] = $usu['rol'];
		if($_SESSION["logueado"]==0){
			// Le mando a la página principal
			header("Location: DprincipalSesiones.php");
		} else {
			header("Location: baseDatosEjercicio5Admin.php");
		}
	}	
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Formulario de login</title>
		<meta charset = "UTF-8">
		<link rel="stylesheet" href="stylesheet.css">
	</head>
	<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php 
        if(isset($_GET["redirigido"])){
            echo "<p class='error'>Haga login para continuar</p>";
        }
        if(isset($err) && $err == true){
            echo "<p class='error'>Revise usuario y contraseña</p>";
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <label for="usuario">Usuario</label>
            <input value="<?php echo isset($usuario) ? htmlspecialchars($usuario) : ''; ?>"
                   id="usuario" name="usuario" type="text" required>
            <label for="clave">Clave</label>
            <input id="clave" name="clave" type="password" required>
            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>
</html>