<!-- <?php
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
?> -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Tickets - Página Principal</title>
    <link rel="stylesheet" href="stylesheetUsuario.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Sistema de Tickets</h1>
        </div>
    </header>
    
    <nav>
        <div class="container">
            <ul>
                <li><a href="#" onclick="showContent('crear-ticket')">Crear Nuevo Ticket</a></li>
                <li><a href="#" onclick="showContent('mis-tickets')">Mis Tickets</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div id="crear-ticket" class="content">
            <h2>Crear Nuevo Ticket</h2>
            <form action="#" method="POST">
                <label for="asunto">Asunto:</label>
                <input type="text" id="asunto" name="asunto" required><br><br>
                
                <label for="descripcion">Descripción:</label><br>
                <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea><br><br>
                
                <input type="submit" value="Crear Ticket">
            </form>
        </div>

        <div id="mis-tickets" class="content hidden">
            <h2>Mis Tickets</h2>
            <p>Aquí se mostrará la lista de tus tickets.</p>
            <!-- Aquí iría una tabla o lista con los tickets del empleado -->
        </div>
    </div>

    <script>
        function showContent(id) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.content').forEach(function(el) {
                el.classList.add('hidden');
            });
            // Mostrar el contenido seleccionado
            document.getElementById(id).classList.remove('hidden');
        }

        // Mostrar la sección de "Crear Nuevo Ticket" por defecto al cargar la página
        document.addEventListener("DOMContentLoaded", function() {
          showContent('crear-ticket');
        });
    </script>
</body>
</html>