<?php 

function invierte_numero($numero){
    if($numero == 1){
        $numero = 5;
    }elseif($numero == 2){
        $numero = 4;
    }
    elseif($numero == 3){
        $numero = 3;        
    }
    elseif($numero == 4){
        $numero = 2;
    }
    elseif($numero == 5){
        $numero = 1;
    }
    return $numero;
}
