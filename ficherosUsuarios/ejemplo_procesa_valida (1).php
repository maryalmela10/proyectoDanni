<html>
    <head></head>
    <body>
<?php
    // Voy a ver si me llega el formulario 
    if(isset($_POST["textito"])) {
        // Como el usuario puede ser malvado, voy a sustituir los carácteres especiales para que no pueda inyectarme código
        echo htmlspecialchars($_POST["textito"]);

        // Voy a ver si es un correo electronico (devolverá true o false)
        if(filter_var($_POST["textito"], FILTER_VALIDATE_EMAIL)) {
            echo "<br>SÍ me has pasado un email.";
        } else{
            echo "<br>NO me has pasado un email";
        }
    }
?>
</body>
</html>