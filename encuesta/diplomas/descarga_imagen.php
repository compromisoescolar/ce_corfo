<?php

require_once "genera_diploma.php";

if( $_GET["tipo"] == "im"){
    $filename = $_GET["diploma"]; 

    $file = $filename; 
    
    header("Content-Description: Descargar imagen"); 
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: application/force-download"); 
    header("Content-Length: " . filesize($file)); 
    header("Content-Transfer-Encoding: binary"); 
    
    readfile($file); 

    
}else if($_GET["tipo"] == "em"){
    
    require_once "../../conf/conexion_db.php";
    require_once "../../conf/funciones_db.php";
    require_once "../../assets/librerias/vendor/autoload.php";    
    
    $cuenta = $_GET["cuenta"];   
    $token = $_GET["token"];
    
    
    $correo = trim($cuenta);
    $mail = new  PHPMailer\PHPMailer\PHPMailer(true);                              
    try {                                
        $mail->isSMTP();                                    
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;                              
        $mail->Username = 'infocompromisoescolar@gmail.com';              
        $mail->Password = 'compromisoescolar2019';                       
        $mail->SMTPSecure = 'tls';                            
        $mail->Port = 587;                                  
    
       
        $mail->setFrom('no-responder@compromisoescolar.com', 'Proyecto Compromiso Escolar');
        $mail->addAddress( $correo, 'Estudiante');  
        $mail->Subject    = "Diploma - Compromiso Escolar";  
  
        $mail->addAttachment('diploma_'.$token.'.jpg');   
    
       $mail->isHTML(true);                                  
       $cuerpo = 'Gracias por participar<br><br>
       El Compromiso Escolar (CE) es una variable clave para prevenir la desescolarización, ya que la decisión de abandonar la escuela es la etapa final de un proceso de pérdida de la vinculación con los estudios.
               <br/><br/>
               _<br/><br/>
               <div style="font-size:12px;color:#585858;">
                   <strong>Estudio FONDEF ID14I10078-ID14I20078 "Compromiso Escolar"</strong><br/>
               Universidad de La Frontera<br/>
                   <span style="font-size:10px;"></span>
           </div>';
           $mail->MsgHTML($cuerpo);
      
        $mail->CharSet = 'UTF-8';    
    
        $validamos = $mail->send();
    
        if($validamos == TRUE){
         
            $datos = array('estado' => '1'); 
            header('Content-Type: application/json');
            echo json_encode($datos, JSON_FORCE_OBJECT);
        }
        else if($validamos == FALSE){
            $datos = array('estado' => '0'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);
        }
    
        
        
    } catch (Exception $e) {
        $excepcion = $mail->ErrorInfo;
        ce_excepciones($excepcion);
      
        $datos = array('estado' => 'error'); 
        header('Content-Type: application/json');
        echo json_encode($datos, JSON_FORCE_OBJECT);
    }
 
}
?>