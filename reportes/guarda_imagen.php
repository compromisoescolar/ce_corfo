
<?php 
require_once "dist/conf/require_conf.php"; 
session_start();
if(isset($_SESSION['user'])){
    $definido = $_POST['definido'];
    if($definido == 'estudiante'){

        $url = $_POST["url"];
        
        //datos del estudiante
        $id_doc = "No Aplica";
         $nombre_token = $_POST['nombre'];
         $imagedata = base64_decode($_POST['imgdata']);
         $datos_estudiante = datos_personales_reporte_individual($nombre_token, $id_doc, $definido);
         $resultado_estudiante = $datos_estudiante->fetch(PDO::FETCH_ASSOC);        
        $apellidos = $resultado_estudiante["apellidos"];
        $nombres = $resultado_estudiante["nombres"];
      
        ob_start();
        $img = imagecreatefrompng($url);
        $text_color = imagecolorallocate($img, 23, 26, 25);

        $im1 = imagecreatetruecolor(55, 30);
        $im2 = imagecreatetruecolor(55, 30);
        $im3 = imagecreatetruecolor(55, 30);
        $im4 = imagecreatetruecolor(55, 30);
        $im11 = ImageCreate(55, 30);

       /* $celeste = imagecolorallocate($im1, 206, 225, 255);
        imagefilledrectangle(
            $im11, 
            70, 
            4, 
            220, 
            37, 
            $celeste
        );

        ImageRectangle(
            $img,
            70, 
            4, 
            220, 
            37,
            $celeste
        );

        $white = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $white);
        ImageRectangle(
            $img,
            500, 
            4, 
            220, 
            37,
            $celeste
        );
        //ImageFill($img, 0, 0, $celeste);


        imagestring(
            $img, 
            1, 
            75, 12, 
            "Alto compromiso escolar y", 
            $text_color
        );

        imagestring(
            $img, 
            1, 
            75, 22, 
            "bajos Factores contextuales", 
            $text_color
        );


        imagestring(
            $img, 
            1, 
            700, 
            12, 
            "Alto compromiso escolar y ", 
            $text_color
        );

        imagestring(
            $img, 
            1, 
            700, 
            22, 
            "alto Factores contextuales", 
            $text_color
        );



        imagestring(
            $img, 
            1, 
            75, 
            318, 
            "Bajo compromiso escolar y ", 
            $text_color
        );

            imagestring(
            $img, 
            1, 
            75, 
            328, 
            "bajos Factores contextuales", 
            $text_color
        );




        imagestring(
            $img, 
            1, 
            75, 
            320, 
            "Bajo compromiso escolar y ", 
            $text_color
        );

        imagestring(
            $img, 
            1, 
            75, 
            320, 
            "bajo Factores contextuales", 
            $text_color
        );*/
        imagepng($img,"dist/img/individual/".$nombres.'_'.$apellidos.".png");
        imagedestroy($img);
        ob_end_clean();
         //file_put_contents($file,$imagedata);

    }if( $definido == 'curso'){
        $tipo = $_POS['tipo']; // no se esta ocupando
        $demo = $_POST['demo'];
        $id_doc = $_SESSION["id_profesor"];
        $nombre_token = $_SESSION["id_establecimiento"];
        $imagedata = base64_decode($_POST['imgdata']);
        $datos_estudiante = datos_personales_reporte_individual(
            $nombre_token, 
            $id_doc, $definido
        );
        $resultado_estudiante = $datos_estudiante->fetch(PDO::FETCH_ASSOC);

        $file =  'dist/img/curso/'.$demo.$tipo.$resultado_estudiante["curso"].' '.$resultado_estudiante["establecimiento"].'.png';
        file_put_contents($file,$imagedata);

    }
} else {
    header("location:login.php");
}