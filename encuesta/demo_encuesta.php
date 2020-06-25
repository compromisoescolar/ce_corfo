<?php 

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING & ~E_NOTICE);

 session_start();

 $name = $_POST["nombre"];
 $respuesta = $_POST["respuesta"];
    
if($name == "Q001"){
    $_SESSION["Q001"] = $_POST["respuesta"];  
}
elseif($name == "Q002"){
    $_SESSION["Q002"] = $_POST["respuesta"]; 
   }
elseif($name == "Q003"){    
    $_SESSION["Q003"] = $_POST["respuesta"]; 
  }
elseif($name == "Q004"){    
    $_SESSION["Q004"] = $_POST["respuesta"];    
}
elseif($name == "Q005"){
    $_SESSION["Q005"] = $_POST["respuesta"];
}
elseif($name == "Q006"){
    $_SESSION["Q006"] = $_POST["respuesta"];
  
}
elseif($name == "Q007"){
    $_SESSION["Q007"] = $_POST["respuesta"];
    
}elseif($name == "Q008"){
    $_SESSION["Q008"] = $_POST["respuesta"];
    
}elseif($name == "Q009"){
    $_SESSION["Q009"] = $_POST["respuesta"];
    
}elseif($name == "Q0010"){
    $_SESSION["Q0010"] = $_POST["respuesta"];
   
}elseif($name == "Q0011"){
    $_SESSION["Q0011"] = $_POST["respuesta"];
   
}elseif($name == "Q0012"){
    $_SESSION["Q0012"] = $_POST["respuesta"];
   
}elseif($name == "Q0013"){
    $_SESSION["Q0013"] = $_POST["respuesta"];
   
}elseif($name == "Q0014"){
    $_SESSION["Q0014"] = $_POST["respuesta"];
    
}elseif($name == "Q0015"){
    $_SESSION["Q0015"] = $_POST["respuesta"];
   
}elseif($name == "Q0016"){
    $_SESSION["Q0016"] = $_POST["respuesta"];
   
}elseif($name == "Q0017"){
    $_SESSION["Q0017"] = $_POST["respuesta"];
   
}elseif($name == "Q0018"){
    $_SESSION["Q0018"] = $_POST["respuesta"];
    
}elseif($name == "Q0019"){
    $_SESSION["Q0019"] = $_POST["respuesta"];
    
}elseif($name == "Q0020"){
    $_SESSION["Q0020"] = $_POST["respuesta"];
   
}elseif($name == "Q0021"){
    $_SESSION["Q0021"] = $_POST["respuesta"];
   
}elseif($name == "Q0022"){
    $_SESSION["Q0022"] = $_POST["respuesta"];
  
}elseif($name == "Q0023"){
    $_SESSION["Q0023"] = $_POST["respuesta"];
    
}elseif($name == "Q0024"){
    $_SESSION["Q0024"] = $_POST["respuesta"];
  
}elseif($name == "Q0025"){
    $_SESSION["Q0025"] = $_POST["respuesta"];
   
}elseif($name == "Q0026"){
    $_SESSION["Q0026"] = $_POST["respuesta"];
   
}elseif($name == "Q0027"){
    $_SESSION["Q0027"] = $_POST["respuesta"];
   
}elseif($name == "Q0028"){
    $_SESSION["Q0028"] = $_POST["respuesta"];
  
}elseif($name == "Q0029"){
    $_SESSION["Q0029"] = $_POST["respuesta"];
   
}elseif($name == "Q0030"){
    $_SESSION["Q0030"] = $_POST["respuesta"];
    
}elseif($name == "Q0031"){
    $_SESSION["Q0031"] = $_POST["respuesta"];
   
}elseif($name == "Q0032"){
    $_SESSION["Q0032"] = $_POST["respuesta"];
 
}elseif($name == "Q0033"){
    $_SESSION["Q0033"] = $_POST["respuesta"];
   
}elseif($name == "Q0034"){
    $_SESSION["Q0034"] = $_POST["respuesta"];
   
}elseif($name == "Q0035"){
    $_SESSION["Q0035"] = $_POST["respuesta"];
   
}elseif($name == "Q0036"){
    $_SESSION["Q0036"] = $_POST["respuesta"];
   
}elseif($name == "Q0037"){
    $_SESSION["Q0037"] = $_POST["respuesta"];
   
}elseif($name == "Q0038"){
    $_SESSION["Q0038"] = $_POST["respuesta"];
   
}if($name == "Q0039"){
    $_SESSION["Q0039"] = $_POST["respuesta"];
   
}elseif($name == "Q0040"){
    $_SESSION["Q0040"] = $_POST["respuesta"];
   
}elseif($name == "Q0041"){
    $_SESSION["Q0041"] = $_POST["respuesta"];
   
}elseif($name == "Q0042"){
    $_SESSION["Q0042"] = $_POST["respuesta"];
   
}elseif($name == "Q0043"){
    $_SESSION["Q0043"] = $_POST["respuesta"];
   
}elseif($name == "Q0044"){
    $_SESSION["Q0044"] = $_POST["respuesta"];
    
}elseif($name == "Q0045"){
    $_SESSION["Q0045"] = $_POST["respuesta"];
   
}elseif($name == "Q0046"){
    $_SESSION["Q0046"] = $_POST["respuesta"];
   
}elseif($name == "Q0047"){
    $_SESSION["Q0047"] = $_POST["respuesta"];
  
}
?>