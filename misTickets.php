<?php
    require_once 'bd.php';
    if(isset($_SESSION["logueado"]) && $_SESSION["logueado"] == "0") {
        $id = $_SESSION['id'];
        $tickets = empleadoTickets($id);
        if ($tickets) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis tickets</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #001f3f;
            color: #ffffff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #003366;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #003366;
        }
        tr:nth-child(even) {
            background-color: #f8f8f8;
        }
        tr:hover {
            background-color: #e8e8e8;
        }
    </style>    
</head>
<body>
    <div class="container">
        <h1>MIS TICKETS</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Asunto</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
                <th>Detalle</th>
            </tr>
            <?php
                foreach ($tickets as $ticket) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($ticket['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($ticket['asunto']) . "</td>";
                    echo "<td>" . htmlspecialchars($ticket['estado']) . "</td>";
                    echo "<td>" . htmlspecialchars($ticket['fecha_creacion']) . "</td>";
                    echo "<td><a href='detalleTicket.php?id=" . $ticket['id'] . "'>Ver detalles</a></td>";
                    echo "</tr>";
                }
            ?>
        </table>        
    </div>
</body>
</html>     
<?php   
    } else {
        echo "<p>No tienes tickets registrados.</p>";
    }
} else {
    header("Location:login.php");
    exit();
}
?>