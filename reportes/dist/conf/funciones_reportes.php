<?php 

function incrementa_tickposition($cantidad_tickposition){
if( $cantidad_tickposition <= 1000){
    $valor = 10;
    for ($i=2; $i < $valor; $i++) { 
        if (($i % 2) == 0) { 
            echo $i."00"." ".",";
            } 
        
     }
}
if( $cantidad_tickposition >= 1001){
    $valor = 24;
    for ($i=2; $i < $valor; $i++) { 
        if (($i % 3) == 0) { 
            echo $i."00"." ".",";
            } 
        
     }
}   
}
function incrementa_tickposition_fc($cantidad_tickposition_fc){
    if( $cantidad_tickposition_fc < 1000){
        $valor = 10;
        for ($i=2; $i < $valor; $i++) { 
            if (($i % 2) == 0) { 
                echo $i."00"." ".",";
                } 
            
         }
    }
    if( $cantidad_tickposition_fc >= 1001){
        $valor = 24;
        for ($i=2; $i < $valor; $i++) { 
            if (($i % 3) == 0) { 
                echo $i."00"." ".",";
                } 
            
         }
    }
}

function iniciales($str) { 
$ret = ''; 
    foreach (explode(' ', $str) as $word) 
    $ret .= strtoupper($word[0])."."; 
    return elimina_ultimo_caracter($ret); 
}

function elimina_ultimo_caracter($cadena){
    $myString = trim($cadena, '.');
    echo $myString." <i class='fa fa-sign-out hvr-grow' aria-hidden='true'></i>";
}

function ce_carpetas(){
    $ruta_individual = 'dist/img/individual';
	$ruta_curso = 'dist/img/curso';
if(file_exists($ruta_individual)||file_exists($ruta_curso)){
	
   }else{
		mkdir($ruta_individual, 0777,TRUE);
		mkdir($ruta_curso, 0777,TRUE);
		
   }
}