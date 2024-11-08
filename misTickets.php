<?php
    // Incluye el archivo con las funciones
    require_once 'bd.php';
    //session_start();
    if(isset($_SESSION["logueado"])) {
        if(isset($_SESSION["logueado"]) && $_SESSION["logueado"] == "0") {
            // Obtener el ID del usuario desde la sesión        
            $id = $_SESSION['id'];
            // Depurar el valor de la variable $_SESSION['id']
            //var_dump($id);  // Verifica qué valor tiene el ID en la sesión

            // Llamar a la función para obtener los tickets del usuario
           
            $tickets = empleadoTickets($id);

            //var_dump($tickets); // Esto te ayudará a depurar qué datos está recibiendo
            // Comprobar si la función devolvió resultados
            if ($tickets) {
                // Mostrar la tabla con los tickets        
            
?><!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mis tickets</title>
        <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background-color: #001f3f; /* Fondo azul oscuro */
                    } 
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    padding: 8px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                th {
                    background-color: #f2f2f2;
                }
        </style>    
    </head>
    <body>
        <h1>MIS TICKETS</h1>
        <table>
        <!--creamos tabla con la info del ticket¡-->
            <tr>
                <th>ID</th>
                <th>Asunto</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
            </tr>
            <?php
                // Recorrer los tickets y mostrarlos en la tabla
                foreach ($tickets as $ticket) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($ticket['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($ticket['asunto']) . "</td>";
                    echo "<td>" . htmlspecialchars($ticket['estado']) . "</td>";
                    echo "<td>" . htmlspecialchars($ticket['fecha_creacion']) . "</td>";
                    echo "</tr>";
                }
            ?>
        </table>        
        
    </body>
</html>     
<?php   
}else{
    echo"<p>No tienes tickets registrados.</p>";
}
}else{
    //pal login
    header("Location:login.php");
    exit();
}
    }
