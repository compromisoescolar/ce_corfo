<?php
	function genera_diploma($token) {
		require_once "../conf/conexion_db.php";
		require_once "../conf/funciones_db.php";

		$estudiante= select_estudiante($token);
		// Create Image From Existing File
		$jpg_image = imagecreatefromjpeg('diplomas/fondodiploma2.jpg');

		// Allocate A Color For The Text
		$white = imagecolorallocate($jpg_image, 0, 0, 0);
		$magenta = imagecolorallocate($jpg_image, 252, 69, 92);
		$cyan = imagecolorallocate($jpg_image, 64, 194, 212);
		// Set Path to Font File
		$font_path = __DIR__ .'/verdana_bold.ttf';
		$font_path_fecha = __DIR__ .'/verdana_italic.ttf';

		// Set Text to Be Printed On Image
		$text =  $estudiante["nombres"]." ". $estudiante["apellidos"];
		$arrMes = array(
			"1" => "Enero",
			"2" => "Febrero",
			"3" => "Marzo",
			"4" => "Abril",
			"5" => "Mayo",
			"6" => "Junio",
			"7" => "Julio",
			"8" => "Agosto",
			"9" => "Septiembre",
			"10" => "Octubre",
			"11" => "Noviembre",
			"12" => "Diciembre"
		);

		$mes = $arrMes[(int)date("m")];

		$anio = date("Y");
		$fecha = $mes . " " . $anio;

		$bbox = imagettfbbox(28, 0, $font_path, $text);
		$center1 = (imagesx($jpg_image) / 2) - (($bbox[2] - $bbox[0])/2);

		$bbox2 = imagettfbbox(28, 0, $font_path, $fecha);
		$center2 = (imagesx($jpg_image) / 2) - (($bbox2[2] - $bbox2[0])/2);

		imagettftext( 
			$jpg_image, 
			35, 
			0,
			(abs($center1)) + ((abs($center1))/60) - 80, 
			873.5, 
			$cyan, 
			$font_path, 
			$text 
		);

		imagettftext( $jpg_image , 28 , 0 , (abs($center2)) + ((abs($center2))/60)  , 1550 , $magenta , $font_path , $fecha );

		$imagen = imagejpeg($jpg_image,'diplomas/diploma_'.$token.'.jpg');
		file_put_contents($imagen, ob_get_contents());

		imagedestroy($jpg_image);
	}

  function envia_mail_diploma($cuenta,$dominio,$token){
	
	require_once "../../conf/conexion_db.php";
	require_once "../../conf/funciones_db.php";
	require_once "../../assets/librerias/vendor/autoload.php";    

	
	$correo = $cuenta."@".$dominio;
	$mail = new  PHPMailer\PHPMailer\PHPMailer(true);                              // Passing `true` enables exceptions
	try {
		//Server settings
	  //  $mail->SMTPDebug = 2;                                 // Enable verbose debug output
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.dreamhost.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'no-responder@compromisoescolar.com';                 // SMTP username
		$mail->Password = 'C.E.diplomas619';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to
	
		//Recipients
		$mail->setFrom('no-responder@compromisoescolar.com', 'Proyecto Compromiso Escolar');
		$mail->addAddress( $correo, 'Alumno');  
		$mail->Subject    = "Diploma - Compromiso Escolar";   // Add a recipient
	  //  $mail->addAddress('ellen@example.com');               // Name is optional
	
		//Attachments
	  //  $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		$mail->addAttachment('diploma_'.$token.'.jpg');    // Optional name
	
		//Content
	   $mail->isHTML(true);                                  // Set email format to HTML
	   $cuerpo = 'Muchas gracias por tu participación!<br><br>
	   El Compromiso Escolar (CE) es una variable clave para prevenir la desescolarización, ya que la decisión de abandonar la escuela es la etapa final de un proceso de pérdida de la vinculación con los estudios.
			   <br/><br/>
			   _<br/><br/>
			   <div style="font-size:12px;color:#585858;">
				   <strong>Estudio FONDEF ID14I10078-ID14I20078 "Compromiso Escolar"</strong><br/>
			   Universidad de La Frontera<br/>
				   <span style="font-size:10px;"></span>
		   </div>';
		   $mail->MsgHTML($cuerpo);
		//$mail->Body    = 'Hola esto es una prueba ';
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		$mail->CharSet = 'UTF-8';
	
		$mail->send();

		if(!$mail->Send()){
echo "Mailer Error: " . $mail->ErrorInfo; 
} else {
echo "Message has been sent";
} 
		
	} catch (Exception $e) {
		echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	}
}
function txt_inicia_encuesta($token, $pais){

$rawContent = file("respondiendo.txt"); //O usa una URL
$content = implode(" ",$rawContent);//Ya tenemos la cadena en memoria

//Se realiza la búsqueda usando expresiones regulares.
if (preg_match("/$token/",$content,$arrMatches)){

   
}
else{

  $fp = fopen("respondiendo.txt","a");
  fwrite($fp, "$token $pais\n". PHP_EOL);
  fclose($fp);
}
  
}

function txt_termina_encuesta($token, $pais){
  $rawContent = file("termina.txt"); //O usa una URL
  $content = implode(" ",$rawContent);//Ya tenemos la cadena en memoria
  
  //Se realiza la búsqueda usando expresiones regulares.
  if (preg_match("/$token/",$content,$arrMatches)){
  
	 
  }
  else{
  
	$fp = fopen("termina.txt","a");
	fwrite($fp, "$token $pais\n". PHP_EOL);
	fclose($fp);
  }
}
  ?>