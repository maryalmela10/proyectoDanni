1 <?php
	use PHPMailer\PHPMailer\PHPMailer;
	require "../vendor/autoload.php";
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
	$mail->SetFrom('user@gmail.com', 'Test');
	// asunto
	$mail->Subject    = "Correo de prueba";
	// cuerpo
	$mail->MsgHTML('Prueba');
	// adjuntos
	$mail->addAttachment("empleado.xsd");
	// destinatario
	$address = "probando@servidor.com";
	$mail->AddAddress($address, "Test");
	// enviar
	$resul = $mail->Send();
	if (!$resul) {
		echo "Error" . $mail->ErrorInfo;
	} else {
		echo "Enviado";
	}
