<?php 

require 'conf/conexion_db.php';
require 'conf/funciones_db.php';
require_once "assets/librerias/vendor/autoload.php";

$fecha = date('Y-m-d');
$archivo = fopen("reportes/dist/conf/realizadas_$fecha.txt","w+");
$con = connectDB_demos();

$query = $con->prepare("SELECT * FROM ce_encuesta_resultado WHERE DATE(fecha_inicio)IN(:inicio)");
$query->execute([":inicio"=>$fecha]);

foreach ($query AS $fila) {

    $contenido =  $fila["ce_participantes_token_fk"];
    fwrite($archivo,"$contenido \n");

}

$correo = "jonathan.barrera@softpatagonia.com";
$mail = new  PHPMailer\PHPMailer\PHPMailer(true);                              
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
    $mail->addAddress($correo);  
    $mail->Subject    = "Realizadas $fecha";   


    $mail->addAttachment('reportes/dist/conf/realizadas_'.$fecha.'.txt');    // Optional name

    //Content
   $mail->isHTML(true);                                  // Set email format to HTML
   $cuerpo = 'Jonathan adjunto las realizadas el día de hoy '.$fecha.' Yo el pinguino Linux';
       $mail->MsgHTML($cuerpo);
   
    $mail->CharSet = 'UTF-8';


    $validamos = $mail->send();

    if($validamos == TRUE){
      echo "Mensaje Enviado";
    }
    else if($validamos == FALSE){
        echo "Mensaje no enviado";
    }

    
    
} catch (Exception $e) {
    $excepcion = $mail->ErrorInfo;
    ce_excepciones($excepcion);
   
}

?>