<?php
session_start();

if (isset($_SESSION['logueado']) && $_SESSION['logueado'] == 1) {
        header("Location: login.php");
        exit();
        require_once 'bd.php';
}
    // Obtener el correo electrónico del técnico desde la sesión
    $usuario_email = $_SESSION['nombre'];
        // Consultar la base de datos para obtener los detalles del técnico

        // Crear la conexión
        $bd = new PDO(
            "mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
            $bd_config["usuario"],
            $bd_config["clave"]
        );
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Consultar la base de datos para obtener los detalles del técnico
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $bd->prepare($sql);
        $stmt->execute([':email' => $usuario_email]);
        $usuario = $stmt->fetch();
        
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil del Técnico</title>
        <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            color: #003366;
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #003366;
            color: white;
            padding: 10px 0;
        }
        .perfil-link {
            position: absolute;
            top: 10px;
            right: 30px;
            color: #003366;
            text-decoration: none;
            font-weight: bold;    
            background-color:white;  
            padding:10px; 
            border-radius:10px 10px;     
        }
        .logout-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 15px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-button:hover {
            background-color: #ff1a1a;
        }
    </style>
    </head>
    <body>
        <header>
            <div>
                <a href="perfilTecnico.php" class="perfil-link">Perfil</a>
            </div>
            <div class="container">
            <h1>Perfil del Técnico</h1>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><a href="paginaTecnico.php">Volver a Tickets</a></p>
            </div>
        </header>
        <!-- Botón de cerrar sesión -->
        <form action="cerrarSesion.php" method="post">
            <button type="submit" class="logout-button">Cerrar Sesión</button>
        </form>
    </body>
    </html>