<?php
use PHPMailer\PHPMailer\PHPMailer;
require "../vendor/autoload.php";

function enviarEmail($destino, $origen, $asunto, $cuerpo){
	$mail = new PHPMailer();
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
	$mail->SetFrom($origen, 'Test');
	// asunto
	$mail->Subject    = $asunto;
	// cuerpo
	$mail->MsgHTML($cuerpo);
	// destinatario
	$address = "probando@servidor.com";
	$mail->AddAddress($destino, "Test");
	// enviar
	$resul = $mail->Send();
	if (!$resul) {
		echo "Error" . $mail->ErrorInfo;
	} else {
		echo "Enviado";
	}
};
?>