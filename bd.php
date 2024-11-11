<?php
function comprobar_usuario($nombre, $clave)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Creo la sentencia SQL y ejecuto	
	$ins = "select * from usuarios where email = '$nombre' 
			and contraseña = '$clave'";
	$resul = $bd->query($ins);
	if ($resul->rowCount() === 1) {
		return $resul->fetch();
	} else {
		return FALSE;
	}
}

function crearTicket($id_usu, $descr, $asunto)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Verificar si el usuario existe
	$checkUser = $bd->prepare("SELECT id FROM usuarios WHERE id = :id_usu");
	$checkUser->execute([':id_usu' => $id_usu]);
	if ($checkUser->rowCount() === 0) {
		// El usuario no existe
		return FALSE;
	}

	// Preparar la sentencia SQL para insertar el ticket
	$stmt = $bd->prepare("INSERT INTO tickets (fecha_creacion, fecha_actualizacion, id_usu, descripcion, asunto) VALUES (:fecha_creacion, :fecha_actualizacion, :id_usu, :descr, :asunto)");

	$fecha_actual = date("Y-m-d H:i:s");

	// Ejecutar la sentencia preparada
	$stmt->execute([
		':fecha_creacion' => $fecha_actual,
		':fecha_actualizacion' => $fecha_actual,
		':id_usu' => $id_usu,
		':descr' => $descr,
		':asunto' => $asunto
	]);

	if ($stmt->rowCount() === 1) {
		return $bd->lastInsertId(); // Devuelve el ID del ticket insertado
	} else {
		return FALSE;
	}
}


function empleadoTickets($id_usu)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	// Conexión a la base de datos incluyendo los datos 
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);
	// Consulta SQL para seleccionar los tickets del usuario, ordenados por fecha de creación
	$query = "SELECT id, asunto, estado, fecha_creacion 
	   				FROM tickets WHERE id_usu = :id_usu ORDER BY
					 fecha_creacion DESC";
	//sentencia para ejecutarla de forma segura
	$stmt = $bd->prepare($query);
	//la consulta con el ID del usuario como parámetro
	$stmt->execute([':id_usu' => $id_usu]);

	// Devuelve todos los tickets en un array asociativo
	$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
	// Esto te ayudará a ver lo que se está devolviendo
	//var_dump($tickets);  
	//return $tickets;

	// Devuelve los tickets
	return $tickets;
}

function obtenerTicket($id){
											// Incluyo los parámetros de conexión y creo el objeto PDO
	   // Conexión a la base de datos incluyendo los datos 
	   include "configuracion_bd.php";
	   $bd = new PDO(
		   "mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		   $bd_config["usuario"],
		   $bd_config["clave"]
	   );
	   // Consulta SQL para seleccionar los tickets del usuario, ordenados por fecha de creación
	   $query = "SELECT * FROM tickets WHERE id = :id";
		//sentencia para ejecutarla de forma segura
		$stmt = $bd->prepare($query);
		//la consulta con el ID del usuario como parámetro
    	$stmt->execute([':id' =>$id]);

		// Devuelve todos los tickets en un array asociativo
		$ticket =  $stmt->fetch(PDO::FETCH_ASSOC);
		    // Verificar si hay resultados
			if ($ticket) {
				return $ticket;
			} else {
				return false; // 
			}
}

function tecnicoTickets()
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	// Conexión a la base de datos incluyendo los datos 
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);
	// Consulta SQL para seleccionar los tickets del usuario, ordenados por fecha de creación
	$query = "SELECT t.id, u.nombre AS nombre_empleado, t.asunto, t.estado, t.prioridad, t.fecha_creacion, t.fecha_actualizacion
              FROM tickets t
              JOIN usuarios u ON t.id_usu = u.id
              ORDER BY t.fecha_creacion DESC";
	//sentencia para ejecutarla de forma segura
	$stmt = $bd->prepare($query);
	//la consulta con el ID del usuario como parámetro
	$stmt->execute();

	// Devuelve todos los tickets en un array asociativo
	$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
	// Esto te ayudará a ver lo que se está devolviendo
	//var_dump($tickets);  
	//return $tickets;
	// Devuelve los tickets
	return $tickets;
}

function actualizarEstadoTicket($idTicket, $nuevoEstado){
// Incluyo los parámetros de conexión y creo el objeto PDO
	// Conexión a la base de datos incluyendo los datos 
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);
// Consulta SQL para actualizar el estado del ticket
$query = "UPDATE tickets SET estado = :estado, fecha_actualizacion = NOW() WHERE id = :id";

// Preparar la sentencia
$stmt = $bd->prepare($query);

// Vincular los parámetros
$stmt->bindParam(':estado', $nuevoEstado, PDO::PARAM_STR);
$stmt->bindParam(':id', $idTicket, PDO::PARAM_INT);

// Ejecutar la consulta
	$stmt->execute();
	// Verificar si se actualizó alguna fila
	if ($stmt->rowCount() > 0) {
		return true; // Actualización exitosa
	} else {
		return false; // No se encontró el ticket o no se realizaron cambios

}
}

function cargar_categorias()
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Creo la sentencia SQL y ejecuto	
	$ins = "select codCat, nombre from categorias";
	$resul = $bd->query($ins);
	if (!$resul) {
		return FALSE;
	}
	if ($resul->rowCount() === 0) {
		return FALSE;
	}
	//si hay 1 o más
	return $resul;
}
function cargar_categoria($codCat)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Creo la sentencia SQL y ejecuto	
	$ins = "select nombre, descripcion from categorias where codcat = $codCat";
	$resul = $bd->query($ins);
	if (!$resul) {
		return FALSE;
	}
	if ($resul->rowCount() === 0) {
		return FALSE;
	}
	//si hay 1 o más
	return $resul->fetch();
}
function cargar_productos_categoria($codCat)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Creo la sentencia SQL y ejecuto		
	$sql = "select * from productos where categoria  = $codCat";
	$resul = $bd->query($sql);
	if (!$resul) {
		return FALSE;
	}
	if ($resul->rowCount() === 0) {
		return FALSE;
	}
	//si hay 1 o más
	return $resul;
}
// recibe un array de códigos de productos
// devuelve un cursor con los datos de esos productos
function cargar_productos($codigosProductos)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Creo la sentencia SQL y ejecuto	
	$texto_in = implode(",", $codigosProductos);
	$ins = "select * from productos where codProd in($texto_in)";
	$resul = $bd->query($ins);
	if (!$resul) {
		return FALSE;
	}
	return $resul;
}
function insertar_pedido($carrito, $codRes)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Creo la sentencia SQL y ejecuto	
	$bd->beginTransaction();
	$hora = date("Y-m-d H:i:s", time());
	// insertar el pedido
	$sql = "insert into pedidos(fecha, enviado, restaurante) 
			values('$hora',0, $codRes)";
	$resul = $bd->query($sql);
	if (!$resul) {
		return FALSE;
	}
	// coger el id del nuevo pedido para las filas detalle
	$pedido = $bd->lastInsertId();
	// insertar las filas en pedidoproductos
	foreach ($carrito as $codProd => $unidades) {
		$sql = "insert into pedidosproductos(Pedido, Producto, Unidades) 
		             values( $pedido, $codProd, $unidades)";
		$resul = $bd->query($sql);
		if (!$resul) {
			$bd->rollback();
			return FALSE;
		}
	}
	$bd->commit();
	return $pedido;
}

function buscarProducto($codigoProducto)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Creo la sentencia SQL y ejecuto	
	$ins = "select * from productos where codProd = $codigoProducto";
	$resul = $bd->query($ins);
	if (!$resul) {
		return FALSE;
	}
	return $resul;
}
