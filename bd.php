<?php

use PHPMailer\PHPMailer\PHPMailer;

require "../vendor/autoload.php";

function enviarEmail($destino, $origen, $asunto, $cuerpo, $origenEtiqueta)
{
	$mail = new PHPMailer(true);
	$mail->IsSMTP();
	// cambiar a 0 para no ver mensajes de error
	// Looking to send emails in production? Check out our Email API/SMTP product!
	$mail->SMTPDebug  = 0;
	$mail->SMTPAuth   = true;
	$mail->SMTPSecure = "tls";
	$mail->Host       = "sandbox.smtp.mailtrap.io";
	$mail->Port       = 2525;
	// introducir usuario de google
	$mail->Username   = "473224c3ed442c";
	// introducir clave
	$mail->Password   = "9adb71033c3016";
	$mail->SetFrom($origen, $origenEtiqueta);
	// asunto
	$mail->Subject    = $asunto;
	// cuerpo
	$mail->isHTML(true); // Establecer el formato del correo a HTML si es necesario
	$mail->Body    = nl2br($cuerpo); // Cuerpo del mensaje en HTML
	$mail->AltBody = strip_tags($cuerpo); // Cuerpo alternativo en texto plano para clientes que no soportan HTML
	// destinatario
	// $address = "probando@servidor.com";
	$mail->AddAddress($destino, "Test");
	// enviar
	$resul = $mail->Send();
	if (!$resul) {
		echo "Error" . $mail->ErrorInfo;
	}
};

function registrarUsuario($nombre, $email, $clave, $rol)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Verificar si el correo ya existe
	$check = "SELECT id FROM usuarios WHERE email = :email";
	$stmt_check = $bd->prepare($check);
	$stmt_check->execute([':email' => $email]);

	if ($stmt_check->rowCount() > 0) {
		return false; // El correo ya está registrado
	}

	// Generar token de activación
	$token = bin2hex(random_bytes(16));
	$clave_cifrada = password_hash($clave, PASSWORD_DEFAULT);

	// Insertar el usuario en la base de datos
	$insert = "INSERT INTO usuarios (nombre, email, contraseña, rol, token_activacion, activo) VALUES (:nombre, :email, :clave_cifrada, :rol, :token, 0)";
	$stmt_insert = $bd->prepare($insert);
	$result = $stmt_insert->execute([
		':nombre' => $nombre,
		':email' => $email,
		':clave_cifrada' => $clave_cifrada,
		':rol' => $rol,
		':token' => $token
	]);

	if ($result) {
		// Enviar correo de activación
		enviarCorreoActivacion($email, $token);
		return true;
	} else {
		return false;
	}
}

function enviarCorreoActivacion($email, $token)
{
	$destino = $email;
	$origen = "soporte@empresa.com";
	$origenEtiqueta = "no-replay";
	$asunto = "Activa tu cuenta";
	$dominio = $_SERVER['HTTP_HOST'];
	$subcarpeta = dirname($_SERVER['PHP_SELF']);
	$cuerpo = "Haz clic en el siguiente enlace para activar tu cuenta: <a href='http://" . $dominio . $subcarpeta . "/activar.php?email=" . urlencode($email) . "&token=" . urlencode($token) . "'>Activar cuenta</a>";
	enviarEmail($destino, $origen, $asunto, $cuerpo, $origenEtiqueta);
}

function activar($email, $token)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Verificar y activar la cuenta
	$update = "UPDATE usuarios SET activo = 1, token_activacion = NULL 
	   WHERE email = :email AND token_activacion = :token AND activo = 0";
	$stmt_update = $bd->prepare($update);
	$result = $stmt_update->execute([
		':email' => $email,
		':token' => $token
	]);

	if ($stmt_update->rowCount() > 0) {
		return true;
	} else {
		return false;
	}
}


function verificarEmailEmpresa($email)
{
	$patronSoporte = '/@soporte\.empresa\.com$/'; // Delimitador '/'
	$patronEmpleado = '/@empresa\.com$/'; // Delimitador '/'

	if (preg_match($patronSoporte, $email)) {
		return 1;
	} elseif (preg_match($patronEmpleado, $email)) {
		return 0;
	} else {
		return -1;
	}
}



function comprobar_usuario($nombre, $clave)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	$ins = "SELECT * FROM usuarios WHERE email = :email";
	$stmt = $bd->prepare($ins);
	$stmt->execute([':email' => $nombre]);

	// Verifico si se encontró el usuario
	if ($stmt->rowCount() === 1) {
		$usuario = $stmt->fetch();
		if ($usuario['activo'] == 0) {
			return false; // usuarioInactivo
		}
		// Verifico la contraseña
		if (password_verify($clave, $usuario['contraseña'])) {
			return $usuario; // Retorna los datos del usuario si la contraseña es correcta
		} else {
			return false; // Contraseña incorrecta
		}
	} else {
		return false; // Usuario no encontrado
	}
}

function crearTicket($id_usu, $descr, $asunto, $archivo = null)
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
	$sql = "INSERT INTO tickets (fecha_creacion, fecha_actualizacion, id_usu, descripcion, asunto, archivo_adjunto) VALUES (:fecha_creacion, :fecha_actualizacion, :id_usu, :descr, :asunto, :archivo)";
	$stmt = $bd->prepare($sql);

	$fecha_actual = date("Y-m-d H:i:s");

	// Ejecutar la sentencia preparada
	$stmt->execute([
		':fecha_creacion' => $fecha_actual,
		':fecha_actualizacion' => $fecha_actual,
		':id_usu' => $id_usu,
		':descr' => $descr,
		':asunto' => $asunto,
		':archivo' => $archivo
	]);

	if ($stmt->rowCount() === 1) {
		$idTicket = $bd->lastInsertId();
		// function enviarEmail($destino, $origen, $asunto, $cuerpo, $origenEtiqueta){
		// foreach ($checkUser as $usu) {
		// 	$cuerpo = "<p>Le informamos que ha abierto un nuevo ticket en nuestro sistema. A continuación, se detallan los datos del ticket:</p>";
		// 	$cuerpo.= "<p>ID del Ticket: $idTicket</p>";
		// 	$cuerpo.= "<p>Asunto: $asunto</p>";
		// 	$cuerpo.= "<p>Fecha de Creación: $fecha_actual</p>";
		// 	$cuerpo.= "<p>Descripción: $descr</p>";
		// 	$cuerpo.= "<p>Nuestro equipo está revisando su solicitud y se pondrá en contacto con usted a la brevedad posible para brindarle asistencia.
		// 					Si tiene alguna pregunta o necesita más información, no dude en utilizar el chat de la aplicación.</p>";
		// 	enviarEmail($usu['email'], "soporte@empresa.com", "Creaste un nuevo ticket",$cuerpo,"no-replay");
		// }
		return $idTicket; // Devuelve el ID del ticket insertado
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

	// Devuelve todos los tickets en un array 
	$ticket = $stmt->fetch(PDO::FETCH_ASSOC);
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

function buscarTicketsPorDescripcion($busqueda)
{
	// Incluyo los parámetros de conexión y creo el objeto PDO
	include "configuracion_bd.php";
	$bd = new PDO(
		"mysql:dbname=" . $bd_config["nombrebd"] . ";host=" . $bd_config["ip"],
		$bd_config["usuario"],
		$bd_config["clave"]
	);

	// Crear la consulta SQL para buscar coincidencias en la descripción usando LIKE
	$sql = "SELECT tickets.*, usuarios.nombre AS nombre_empleado
            FROM tickets
            JOIN usuarios ON tickets.id_usu = usuarios.id
            WHERE tickets.descripcion LIKE :busqueda";

	// Preparar la consulta
	$stmt = $bd->prepare($sql);
	if ($stmt === false) {
		die("Error en la preparación de la consulta: " . $bd->errorInfo()[2]);
	}

	// Vincular el parámetro de búsqueda con comodines para LIKE
	$busqueda_param = '%' . $busqueda . '%';
	$stmt->bindParam(':busqueda', $busqueda_param, PDO::PARAM_STR);

	// Ejecutar la consulta
	$stmt->execute();

	// Obtener los resultados
	$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Verificar si hay resultados
	if ($tickets) {
		return $tickets; // Devuelve los tickets encontrados
	} else {
		return []; // Devuelve un array vacío si no hay coincidencias
	}
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

function obtenerMensajesTicket($ticketId)
{
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


function letraUpper($cadena)
{
	$cadenaDev = strtoupper($cadena[0]) . substr($cadena, 1);
	return $cadenaDev;
};

function crearMensaje($tickedId, $remitenteId, $contenido)
{
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
