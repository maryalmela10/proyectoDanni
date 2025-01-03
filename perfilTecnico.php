<?php
session_start();
require_once('bd.php');

if (!isset($_SESSION['id']) || $_SESSION["logueado"] != 1) {
    header("Location: login.php?redirigido=true");
    exit();
}

$datosUsuario = datosPerfil($_SESSION['id']);
if(!$datosUsuario){
    header("Location: login.php?redirigido=true");
    exit();
} 

$id_usuario = $_SESSION['id']; 
$nombre = $_SESSION["nombre"];
$email = $_SESSION["email"];
$direccion = $datosUsuario['direccion'];
$telefono= $datosUsuario['telefono'];
$departamento= $datosUsuario['departamento'];

// Determina si se debe mostrar el formulario de edición
$mostrar_formulario = isset($_POST['accion']) && $_POST['accion'] == 'editar';
// var_dump($mostrar_formulario);

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar'])) {
    $nombre = (empty($_POST['nombre']))? null : $_POST['nombre'];
    $telefono = (empty($_POST['telefono']))? null : $_POST['telefono'];
    $departamento = (empty($_POST['departamento']))? null : $_POST['departamento'];
    $direccion = (empty($_POST['direccion']))? null : $_POST['direccion'];
    
    // Llama a la función para actualizar el usuario (asegúrate de definir esta función)
    $actualizar = actualizarUsuario($id_usuario, $nombre, $direccion, $telefono, $departamento);
    
    // Redirige después de guardar
    header("Location: perfilTecnico.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Técnico</title>
    <style>
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
            background-color: white;
            padding: 10px;
            border-radius: 10px;
        }
        .content {
            background-color: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
        .edit-button, .save-button {
            padding: 10px 15px;
            background-color: #004080;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .edit-button:hover, .save-button:hover {
            background-color: #002b55;
        }
        .cancel-link {
            margin-left: 10px;
            color: #003366;
            text-decoration: none;
        }
        .foto {
        width: 100px; 
        height: 100px;
        border-radius: 50%;
        object-fit: cover; 
        display: inline-block;
        margin-right: 10px; 
        vertical-align: middle;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <a href="perfilTecnico.php" class="perfil-link">Perfil</a>
        </div>
        <div class="container content">
        <?php if (!empty($datosUsuario['foto_perfil'])): ?>
            <img src="<?php echo htmlspecialchars($datosUsuario['foto_perfil']); ?>" alt="Foto de Perfil" class="foto">
        <?php endif; ?>
            <h1>Perfil</h1>
            <p>Bienvenido/a, <?php echo htmlspecialchars($_SESSION["nombre"]); ?></p>
        </div>
    </header>
    <div class="container">
        <div class="content">
            <?php if(!$mostrar_formulario): ?>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($direccion ?? 'No especificado'); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono ?? 'No especificado'); ?></p>
                <p><strong>Departamento:</strong> <?php echo htmlspecialchars($departamento ?? 'No especificado'); ?></p>
                <form method="post">
                    <input type="hidden" name="accion" value="editar">
                    <button type="submit" class="edit-button">Modificar información</button>
                </form>
            <?php else : ?>
                <form method="post">
                <p>
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre ?? ''); ?>">
                    </p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p>
                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($direccion ?? ''); ?>">
                    </p>
                    <p>
                        <label for="telefono">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono ?? ''); ?>">
                    </p>
                    <p>
                        <label for="departamento">Departamento:</label>
                        <input type="text" id="departamento" name="departamento" value="<?php echo htmlspecialchars($departamento ?? ''); ?>">
                    </p>
                    <button type="submit" name="guardar" class="save-button">Guardar cambios</button>
                    <a href="perfilTecnico.php" class="cancel-link">Cancelar</a>
                </form>
            <?php endif; ?>
        </div>
        <div class="content">
        <form method="post" action="subir_imagenPerfil.php" enctype="multipart/form-data">
        <label for="foto"><strong>Subir imagen de perfil:</strong></label>
        <input type="file" id="foto" name="foto" accept="image/*" required>
        <button type="submit" class="edit-button" >Subir Imagen</button>
        </form>
    </div>
        <p><a href="paginaTecnico.php">Volver a Tickets</a></p>
    </div>

    <form action="cerrarSesion.php" method="post">
        <button type="submit" class="logout-button">Cerrar Sesión</button>
    </form>
</body>
</html>