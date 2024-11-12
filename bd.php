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

function obtenerTicket($id)
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
	$query = "SELECT * FROM tickets WHERE id = :id";
	//sentencia para ejecutarla de forma segura
	$stmt = $bd->prepare($query);
	//la consulta con el ID del usuario como parámetro
	$stmt->execute([':id' => $id]);

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

function actualizarEstadoTicket($idTicket, $nuevoEstado)
{
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

	$stmt->execute([
		':estado' => $nuevoEstado,
		':id' => $idTicket
	]);
	// Verificar si se actualizó alguna fila
	if ($stmt->rowCount() > 0) {
		return true; // Actualización exitosa
	} else {
		return false; // No se encontró el ticket o no se realizaron cambios

	}
}

function obtenerMensajesTicket($ticketId){
	// Incluyo los parámetros de conexión y creo el objeto PDO
	// Conexión a la base de datos incluyendo los datos 
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);
	// Consulta SQL para traer los mensajes

	$query = "SELECT m.*, u.nombre FROM mensajes m JOIN tickets t on m.ticket_id =t.id JOIN usuarios u ON m.remitente_id=u.id WHERE m.ticket_id=:id ORDER BY m.fecha_envio DESC";
	// Preparar la sentencia
	$stmt = $bd->prepare($query);

	$stmt->execute([
		':id' => $ticketId
	]);

	// Devuelve todos los tickets en un array asociativo
	$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $mensajes;
}


function letraUpper($cadena){
$cadenaDev =strtoupper($cadena[0]).substr($cadena, 1);
return $cadenaDev; 
};

function crearMensaje($tickedId, $remitenteId, $contenido){
// Incluyo los parámetros de conexión y creo el objeto PDO
	// Conexión a la base de datos incluyendo los datos 
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Verificar si el usuario existe
	$checkUser = $bd->prepare("SELECT id FROM usuarios WHERE id = :id_usu");
	$checkUser->execute([':id_usu' => $remitenteId]);
	if ($checkUser->rowCount() === 0) {
		// El usuario no existe
		return FALSE;
	}

	// Verificar si el ticket existe
	$checkUser = $bd->prepare("SELECT id FROM tickets WHERE id = :ticked_id");
	$checkUser->execute([':ticked_id' => $tickedId]);
	if ($checkUser->rowCount() === 0) {
		// El usuario no existe
		return FALSE;
	}

	// Preparar la sentencia SQL para insertar el mensaje
	$stmt = $bd->prepare("INSERT INTO mensajes (ticket_id, remitente_id, contenido, fecha_envio) VALUES (:ticket_id, :remitente_id, :contenido_m, :fecha_envio)");

	$fecha_actual = date("Y-m-d H:i:s");

	// Ejecutar la sentencia preparada
	$stmt->execute([
		':ticket_id' => $tickedId,
		':remitente_id' => $remitenteId,
		':contenido_m' => $contenido,
		':fecha_envio' => $fecha_actual,
	]);

	if ($stmt->rowCount() === 1) {
		return $bd->lastInsertId(); // Devuelve el ID del mensaje insertado
	} else {
		return FALSE;
	}

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
