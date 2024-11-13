<?php
// activar.php
include "configuracion_bd.php";
$bd = new PDO(
    "mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
    $bd_config["usuario"],
    $bd_config["clave"]
);

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Obtener el hash de contraseña de la base de datos
    $sql = "SELECT contraseña FROM usuarios WHERE email = :email";
    $stmt = $bd->prepare($sql);
    $stmt->execute([':email' => $email]);

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hash_contraseña = $row['contraseña'];

        // Verificar si el token coincide con la contraseña cifrada
        if ($hash_contraseña === $token) {
            echo "Cuenta activada con éxito. Puedes iniciar sesión.";
            // Aquí puedes redirigir al usuario a la página de inicio de sesión o cualquier otra acción que desees.
        } else {
            echo "Enlace de activación no válido.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
} else {
    echo "Enlace de activación inválido.";
}

?>
