<?php
    session_start();
    // Voy a ver si el usuario se ha logueado
    // si se ha logueado es que tiene que haber una variable que se llama $_SESSION["usu"]
    if(isset($_SESSION["logueado"])) {
        if($_SESSION["logueado"]=="0"){
            echo "Bienvenido/a empleado: " . $_SESSION["nombre"];
        } else {
            echo "No perteneces aquí";
        }
    } else {
        // Le mando al loguin
        header("Location: DloginSesiones.php");
    }
?>
