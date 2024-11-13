<?php
require "ejercicioEmail1.php";
    session_start();
    $cadena_conexion = 'mysql:dbname=empresa;host=127.0.0.1';
    $usuario = 'root';
    $clave = '';
    // El try-catch es opcional
    try {
        $bd = new PDO($cadena_conexion, $usuario, $clave);
        $sql = "SELECT * FROM empleados";
        $resul = $bd->query($sql);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // foreach ($resul as $empleado) {
                if(isset($_POST['empleados'])) {
                    $empleados_seleccionados = $_POST['empleados'];
                    foreach($empleados_seleccionados as $empleadoPost) {
                        $correo = $empleadoPost."@empleado.com";
                        enviarEmail($correo,"jefe@hola.com","Invitación halloween","Ven pa la fiesta");
                    }
                  } else {
                    $_SESSION["error"]="No se seleccionó ningun empleado";
                  }
            // }
        } else {
            // comienza el else
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        div{
            border: 2px solid;
        }
    </style>
</head>
<body>
<h2>Selecciona los empleados:</h2>
    <div>
        <form action="ejercicioEmail2.php" method="post">
        <?php
        // Select
        foreach ($resul as $empleado) {
            echo '<input type="checkbox" name="empleados[]" value="' . $empleado['Nombre'] . '">'.$empleado["Nombre"].'<br>';
        }
  ?>
        <input type="submit" value="Enviar">
        </form>
    </div>
    <?php
     if(isset($_SESSION["error"])) {
        echo $_SESSION["error"];
        unset($_SESSION["error"]); // Limpia el mensaje de error después de mostrarlo
    } 
?>
</body>
</html>
<?php
        }
        // termina el else y salta al catch
    } catch (PDOException $e) {
        echo 'Error con la base de datos: ' . $e->getMessage();
    }
?>



