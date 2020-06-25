<?php
    error_reporting(E_ERROR | E_PARSE);
    $params = session_get_cookie_params();
    setcookie("PHPSESSID", session_id(), 0, $params["path"], $params["domain"],
        true,  // this is the secure flag you need to set. Default is false.
        true  // this is the httpOnly flag you need to set
    );

function mayordearray( $array ){
	$a = array_unique( $array );
	$s = 0;
	if( is_array( $a ) )
		foreach( $a as $v )
			$s = intval( $v ) > $s ? $v : $s;
	return $s;
}


function select_user($user,$pass,$tipo){
    try{
        $con = connectDB_demos();
        $query = "SELECT a.id_roles_fk AS tipo, b.nombre_usu as usuario, b.contrasena_usu as contrasena, c.nombre_rol as rol, b.fk_establecimiento as id_estable
        FROM ce_rol_user a
        INNER JOIN ce_usuarios b ON a.id_usuario_fk = b.id_usu
        INNER JOIN ce_roles c ON a.id_roles_fk = c.id_rol
        WHERE b.nombre_usu = '$user' AND c.id_rol = '$tipo'";
        $consulta = $con->query($query)->fetch();
        $con = NULL;
        return $consulta;

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function ingreso_nuevo_sostenedor($nom_sos,$apelli_sos,$run_sos,$id_establecimiento,$sos_user, $sos_pass){
    try{
        date_default_timezone_set('America/Santiago'); 

        $fecha_registro =  date('Y-m-d H:m:s');

        $con = connectDB_demos();     

        $query = $con->prepare("SELECT COUNT(*) as resultado FROM ce_sostenedor WHERE nom_soste= :nom_soste AND apelli_soste= :apelli_soste AND run_soste=:run_soste");
        $query->execute(array(":nom_soste"=>$nom_sos,
        ":apelli_soste"=>$apelli_sos,
        ":run_soste"=>$run_sos));

        $resultado = $query->fetch(PDO::FETCH_ASSOC);

        if($resultado["resultado"] >= 1){
            $con = NULL;
            return "existe";

        }else if($resultado["resultado"] <= 0){
            $query = $con->prepare("INSERT INTO ce_sostenedor(nom_soste,apelli_soste,run_soste) VALUES(:nom_soste,:apelli_soste,:run_soste)");
            $query->execute(array(":nom_soste"=>$nom_sos,
            ":apelli_soste"=>$apelli_sos,
            ":run_soste"=>$run_sos));
    
            $id_sostenedor = ingresamos_id_sostenedor();
    
            id_establecimiento_id_sostenedor($id_sostenedor,$id_establecimiento);
    
            $query = $con->prepare("INSERT INTO ce_usuarios(nombre_usu,contrasena_usu,fecha_ingreso_usu,fk_establecimiento) VALUES(:nombre_usu,:contrasena_usu,:fecha_ingreso,:fk_establecimiento)");
            $query->execute(array(":nombre_usu"=>$sos_user,
            ":contrasena_usu"=>$sos_pass,
            ":fecha_ingreso"=>$fecha_registro,
            ":fk_establecimiento"=>$id_establecimiento));
            
            $temp = $con->lastInsertId();
    
            $query = $con->prepare("INSERT INTO ce_rol_user(id_usuario_fk,id_roles_fk) VALUES(:id_usuario_fk,:id_roles_fk)");
            $query->execute(array(":id_usuario_fk"=>$temp,":id_roles_fk" => "3"));
    
    
            $con = NULL;
            return  "no_existe";

        }

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function ingresamos_id_sostenedor(){
    try{
        $con = connectDB_demos();
        $query =  $con->query("SELECT id_soste FROM ce_sostenedor ORDER BY id_soste DESC");
        $con = NULL;
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        return  $resultado["id_soste"];

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function id_establecimiento_id_sostenedor($id_sostenedor,$id_estableci){
    try{
        $con = connectDB_demos();
        $query =  $con->query("INSERT INTO ce_establecimiento_sostenedor(sostenedor_id,establecimiento_id) VALUES('$id_sostenedor','$id_estableci') ");
        $con = NULL;      

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function actualizar_sostenedor($id_sosten,$nom_sosten,$apelli_sosten,$run_sosten,$id_usu_sosten,$usu_sosten,$pass_sosten){
    try{
        $con = connectDB_demos();
        $query =  $con->query("UPDATE ce_sostenedor  SET nom_soste='$nom_sosten',apelli_soste='$apelli_sosten',run_soste='$run_sosten' WHERE id_soste='$id_sosten'");
        $query = $con->prepare("UPDATE ce_usuarios SET  nombre_usu = :nombre_usu, contrasena_usu =:contrasena_usu WHERE id_usu = :id_usu ");
        $query->execute(array(":nombre_usu"=>$usu_sosten,
        ":contrasena_usu"=>$pass_sosten,
        ":id_usu"=>$id_usu_sosten));
        $con = NULL;
        return $query;      

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function vista_sostenedores($id_estableci){
    try{
        $con = connectDB_demos();
        $query =  $con->query("SELECT b.id_soste AS codigo, b.nom_soste AS nombre, b.apelli_soste AS apellido, b.run_soste AS run, b.fecha_registro_soste AS fecha_registro, c.ce_establecimiento_nombre AS establecimiento
        FROM ce_establecimiento_sostenedor a 
        INNER JOIN ce_sostenedor b ON a.sostenedor_id = b.id_soste
        INNER JOIN ce_establecimiento c ON a.establecimiento_id = c.id_ce_establecimiento
        WHERE a.establecimiento_id = '$id_estableci'");
        $cantidad = $query->rowCount();
        $con = NULL;

        if($cantidad >= 1){
            echo '<div class="table-responsive"><table id="tabla_sostenedor" class="table table-striped">'
            .'<thead class="text-white">'
            .'<tr><th>Código</th>'
            .'<th>Nombre Sostenedor</th>'
            .'<th>Apellido Sostenedor</th>'
            .'<th>Run Sostenedor</th>'
            .'<th>Fecha Registro</th>'
            .'<th>Establecimiento</th>'
            .'<th>Cód. Usuario</th>'
            .'<th>Usuario</th>'
            .'<th>Contraseña</th>'
            .'<th>Editar</th>'
            .'</tr></thead><tbody>';
        foreach($query AS $fila){
            $run_sostenedor =  user_docente(trim($fila["run"]));
           
            echo   '<tr style="background:#418BCC; color:white;">'
            .'<td>'.$fila["codigo"].'</td>' 
            .'<td>'.$fila["nombre"].'</td>' 
            .'<td>'.$fila["apellido"].'</td>' 
            .'<td>'.$fila["run"].'</td>' 
            .'<td>'.$fila["fecha_registro"].'</td>' 
            .'<td>'.$fila["establecimiento"].'</td>'
            .'<td>'.$run_sostenedor["id_usu"].'</td>'
            .'<td>'.$run_sostenedor["nombre_usu"].'</td>'
            .'<td>'.$run_sostenedor["contrasena_usu"].'</td>'
            .'<td><button id="actualiza_sostenedor" class="btn btn-danger actualiza_sostenedor"  data-toggle="modal" data-target="#modal_actualizar_sostenedor">Editar</button></td></tr>';      
        }  
        echo '</tbody></table></div>';

    }else{
        echo  '<div class="alert alert-danger text-center" role="alert">No existen sostenedor asociado al Establecimiento</div>';

    }    

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}



function nuevo_docente($nom_docente,$apelli_docente,$run_docente,$email_docente,$id_estable,$user_docente,$pass_docente){
    try{
        date_default_timezone_set('America/Santiago'); 
        $fecha_registro =  date('Y-m-d H:m:s');
        $con = connectDB_demos();
        $query = $con->prepare("SELECT COUNT(*) as cantidad FROM ce_docente WHERE ce_docente_run=:ce_docente_run");
        $query->execute(array(":ce_docente_run"=>$run_docente));
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        if($resultado["cantidad"] <= 0){

        $query =  $con->query("INSERT INTO ce_docente(ce_docente_nombres,ce_docente_apellidos,ce_docente_run,ce_docente_email,ce_establecimiento_id_ce_establecimiento) VALUES('$nom_docente','$apelli_docente','$run_docente','$email_docente','$id_estable')");
        $query_doc = $con->query("SELECT id_ce_docente FROM ce_docente ORDER BY id_ce_docente DESC");
        $resultad_doc = $query_doc->fetch(PDO::FETCH_ASSOC);
        $id_docente =  $resultad_doc["id_ce_docente"];

        $query_insert_doc_id = $con->query("INSERT INTO ce_estable_curso_docente(ce_fk_establecimiento,ce_fk_docente) VALUES('$id_estable','$id_docente')");

        $sql = $con->prepare("INSERT INTO ce_usuarios (nombre_usu,contrasena_usu,fecha_ingreso_usu,fk_establecimiento) VALUES (?,?,?,?)");       

        $sql->execute(array($user_docente,$pass_docente,$fecha_registro,$id_estable));

        $temp = $con->lastInsertId();

        $sql_user = $con->prepare("INSERT INTO ce_rol_user (id_usuario_fk,id_roles_fk) VALUES (?,?)");    
       
        $sql_user->execute(array($temp,"1"));
        

        $con = NULL;
        return  "no_existe";

}else if($resultado["cantidad"] >= 1){
    $con = NULL;
    return "existe";
}
        
    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function actualizar_docente($id_docente,$nom_docente, $apelli_docente, $run_docente, $email_docente, $id_estable,$usuario_id,$docente_user, $docente_pass){
    try{
        $con = connectDB_demos();
        $query =  $con->query("UPDATE ce_docente SET ce_docente_nombres = '$nom_docente',ce_docente_apellidos='$apelli_docente',ce_docente_run='$run_docente',ce_docente_email='$email_docente',ce_establecimiento_id_ce_establecimiento='$id_estable' WHERE id_ce_docente='$id_docente'");
       
        $query = $con->prepare("UPDATE ce_usuarios SET nombre_usu=:nombre_usu,contrasena_usu=:contrasena_usu WHERE id_usu=:id_usu");
        $query->execute(array(":nombre_usu"=>$docente_user,":contrasena_usu"=>$docente_pass, ":id_usu"=>$usuario_id));
        $con = NULL;
        return $query;      

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}


function vista_profesores($id_establecimiento){
    try{
        $con = connectDB_demos();

        $query =  $con->query("SELECT b.id_ce_docente AS id_doc,
        b.ce_docente_nombres AS nom_doc,
        b.ce_docente_apellidos AS apelli_doc,
        b.ce_docente_run AS run_docente,
        b.ce_docente_email AS email_doc,
       IFNULL(c.ce_curso_nombre, 'indefinido') AS nom_curso, 
       IFNULL(d.ce_nombre, 'indefinido') AS nivel_curso,     
       e.id_ce_establecimiento AS id_estable
       
       FROM ce_estable_curso_docente a
       
       LEFT JOIN ce_docente b ON a.ce_fk_docente = b.id_ce_docente
       LEFT JOIN ce_curso c ON a.ce_fk_curso = c.id_ce_curso
       LEFT JOIN ce_niveles d ON a.ce_fk_nivel = d.ce_id_niveles
       LEFT JOIN ce_establecimiento e ON a.ce_fk_establecimiento = e.id_ce_establecimiento
       WHERE e.id_ce_establecimiento = '$id_establecimiento'");       

       $cantidad = $query->rowCount();

       $con = NULL;

       if($cantidad >= 1){
           echo '<div class="table-responsive"><table id="tabla_docente" class="table table-striped">'
           .'<thead class="text-white">'
           .'<tr><th>Código</th>'
           .'<th>Nombre Docente</th>'
           .'<th>Apellido Docente</th>'
           .'<th>Run Docente</th>'
           .'<th>Email Docente</th>' 
           .'<th>Curso</th>'    
           .'<th>Nivel</th>'  
           .'<th>Cód. Usuario</th>'
           .'<th>Usuario</th>'      
           .'<th>Contraseña</th>'
           //.'<th>Especialidad</th>' 
           .'<th>Editar</th>'
           .'</tr></thead><tbody>';
           
       foreach( $query AS $fila){

         
    $docente_run =  user_docente(trim($fila["run_docente"]));
           echo   '<tr style="background:#418BCC; color:white;">'
           .'<td>'.trim($fila["id_doc"]).'</td>' 
           .'<td>'.trim($fila["nom_doc"]).'</td>' 
           .'<td>'.trim($fila["apelli_doc"]).'</td>' 
           .'<td>'.trim($fila["run_docente"]).'</td>' 
           .'<td>'.trim($fila["email_doc"]).'</td>' 
           .'<td>'.trim($fila["nom_curso"]).'</td>' 
           .'<td>'.trim($fila["nivel_curso"]).'</td>'   
           .'<td>'.trim($docente_run["id_usu"]).'</td>'
           .'<td>'.trim($docente_run["nombre_usu"]).'</td>'
           .'<td>'.trim($docente_run["contrasena_usu"]).'</td>'        
           .'<td><button id="actualiza_docente" class="btn btn-danger actualiza_docente"  data-toggle="modal" data-target="#modal_actualizar_docente">Editar</button></td></tr>';  
          
       }  
       echo '</tbody></table></div>';

   }else{
       echo  '<div class="alert alert-danger text-center" role="alert">No existen Docentes asociado al Establecimiento</div>';

   }       

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}
function user_docente($run_docente){
    try{
        $con = connectDB_demos();

        $query =  $con->prepare("SELECT id_usu,nombre_usu,contrasena_usu FROM ce_usuarios WHERE nombre_usu = :nombre_usu");  

        $query->execute(array(":nombre_usu"=>$run_docente));

        $con = NULL;

        return $resultado = $query->fetch(PDO::FETCH_ASSOC);       

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function ListarDir($dir, $tip) {
    $aux = array();
    $aux2 = array();
    
    if ($tip == 0) {
        foreach (scandir($dir) as $arch ) {
            array_push($aux, $arch);
        }
        $folder = Convert($aux);
    } else {
        $n1 = glob($dir."/*.{jpg,gif,png,jpeg}", GLOB_BRACE);
        $n2 = glob($dir."/*.{pdf,mp4,doc,docx,pptx}", GLOB_BRACE);

        foreach ($n1 as $arch ) {
            $arch = substr($arch, 3, strlen($arch));
            array_push($aux, $arch);
        }

        foreach ($n2 as $arch ) {
            $arch = substr($arch, 3, strlen($arch));
            array_push($aux2, $arch);
        }
        $folder = array($aux, $aux2);
    }
    return $folder;
}

function Convert($aux) {
    $folder = "";

    for ($i = 2; $i < count($aux); $i++) { 
        $folder .= $aux[$i].",";
    }

    $folder = rtrim($folder,',');
    $folder = explode(",", $folder);

    return $folder;
}

function js_str($s)
{
    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}

function js_array($array)
{
    $temp = array_map('js_str', $array);
    return '[' . implode(',', $temp) . ']';
}





function lista_docente_establecimiento($id_estable){
    try{
        $con = connectDB_demos();
        $query =  $con->query("SELECT id_ce_docente AS id_docente,ce_docente_nombres AS nom_docente, ce_docente_apellidos AS apelli_docente FROM ce_docente  WHERE ce_establecimiento_id_ce_establecimiento = '$id_estable'");       
        $con = NULL;  
        $resultado = $query->rowCount();
        if($resultado >= 1){
            echo '<select name="id_curso_docente" id="id_curso_docente" class="form-control">';      
            foreach($query as $fila){
                echo '<option value="'.$fila["id_docente"].'">'.$fila["nom_docente"].' '.$fila["apelli_docente"].'</option>';
            }   
            
            echo "</select>"; 
        }else{
            echo "<div class='text-danger'>No hay docentes registrados</div>";

        }
      


    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function lista_docente_establecimiento_update($id_estable){
    try{
        $con = connectDB_demos();
        $query =  $con->query("SELECT id_ce_docente AS id_docente,ce_docente_nombres AS nom_docente, ce_docente_apellidos AS apelli_docente FROM ce_docente  WHERE ce_establecimiento_id_ce_establecimiento = '$id_estable'");       
        $con = NULL;  
        $resultado = $query->rowCount();
        if($resultado >= 1){
            echo '<select name="id_curso_docente_update" id="id_curso_docente_update" class="form-control">';      
            foreach($query as $fila){
                echo '<option value="'.$fila["id_docente"].'">'.$fila["nom_docente"].' '.$fila["apelli_docente"].'</option>';
            }   
            
            echo "</select>"; 
        }else{
            echo "<div class='text-danger'>No hay docentes registrados</div>";

        }
      


    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function niveles_compromiso_escolar(){
    try{
        $con = connectDB_demos();
        $query =  $con->query("SELECT ce_id_niveles,ce_nombre  FROM ce_niveles ");       
        $con = NULL;  
        $resultado = $query->rowCount();
        if($resultado >= 1){
            echo '<select name="niveles_ce" id="niveles_ce" class="niveles_ce form-control ">';      
            foreach($query as $fila){
                echo '<option value="'.$fila["ce_id_niveles"].'">'.$fila["ce_nombre"].'</option>';
            }   
            echo "</select>"; 
        }else{
            echo "<div class='text-danger'>No existen niveles</div>";


        }
      


    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function cargar_lista_anios() {
    try{
        $anio = date('Y');
        for($i = 0; $i < 10; $i++){
            echo '<option value="'.$anio.'">'.$anio.'</option>';
            $anio++;
        }
        
    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function niveles_compromiso_escolar_update(){
    try{
        $con = connectDB_demos();
        $query =  $con->query("SELECT ce_id_niveles,ce_nombre  FROM ce_niveles ");       
        $con = NULL;  
        $resultado = $query->rowCount();
        if($resultado >= 1){
            echo '<select name="niveles_ce_update" id="niveles_ce_update" class="form-control">';      
            foreach($query as $fila){
                echo '<option value="'.$fila["ce_id_niveles"].'">'.$fila["ce_nombre"].'</option>';
            }   
            
            echo "</select>"; 
        }else{
            echo "<div class='text-danger'>No existen niveles</div>";

        }
      


    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function registro_nuevo_curso($nom_curso,$id_estable,$id_docente, $id_nivel, $anio_curso){
    try{

        $con = connectDB_demos();

        $queryValida = $con->query("SELECT * FROM ce_curso WHERE ce_curso_nombre = '$nom_curso' AND ce_anio_curso = $anio_curso");
        $contador = $queryValida->rowCount();
        if($contador == 0) 
        {
            $pre_curso = $con->prepare("SELECT ce_fk_establecimiento,ce_fk_docente,IFNULL(ce_fk_curso, 'indefinido') AS curso FROM ce_estable_curso_docente WHERE ce_fk_establecimiento = :ce_fk_establecimiento AND ce_fk_docente = :ce_fk_docente");
            $pre_curso->execute([':ce_fk_establecimiento' =>$id_estable, ':ce_fk_docente' => $id_docente]);
            $resultado_curso = $pre_curso->fetch(PDO::FETCH_ASSOC);

            if( $resultado_curso["curso"] == "indefinido"){

                $query =  $con->query("INSERT INTO ce_curso(ce_curso_nombre,ce_fk_establecimiento,ce_docente_id_ce_docente,ce_fk_nivel,ce_anio_curso) VALUES('$nom_curso','$id_estable','$id_docente','$id_nivel','$anio_curso')");       
            $selec_id_curso = $con->query("SELECT id_ce_curso FROM ce_curso ORDER BY id_ce_curso DESC LIMIT 1");
            $resultado =  $selec_id_curso->fetch(PDO::FETCH_ASSOC);
            $id_ultimo = $resultado["id_ce_curso"];

                $pre_curso = $con->prepare("UPDATE ce_estable_curso_docente  SET  ce_fk_curso=:ce_fk_curso, ce_fk_nivel=:ce_fk_nivel WHERE ce_fk_establecimiento = :ce_fk_establecimiento AND ce_fk_docente = :ce_fk_docente");
                $pre_curso->execute([
                ':ce_fk_establecimiento' =>$id_estable,
                ':ce_fk_docente' => $id_docente,
                ':ce_fk_curso'   => $id_ultimo,
                ':ce_fk_nivel'   => $id_nivel]);
            
            }else{

                $query =  $con->query("INSERT INTO ce_curso(ce_curso_nombre,ce_fk_establecimiento,ce_docente_id_ce_docente,ce_fk_nivel, ce_anio_curso) VALUES('$nom_curso','$id_estable','$id_docente','$id_nivel','$anio_curso')");       
                $selec_id_curso = $con->query("SELECT id_ce_curso FROM ce_curso ORDER BY id_ce_curso DESC LIMIT 1");
                $resultado =  $selec_id_curso->fetch(PDO::FETCH_ASSOC);
                $id_ultimo = $resultado["id_ce_curso"];
                $con->query("INSERT INTO ce_estable_curso_docente(ce_fk_establecimiento,ce_fk_docente,ce_fk_curso,ce_fk_nivel) VALUES('$id_estable','$id_docente','$id_ultimo','$id_nivel')");

                $con = NULL;

           }         
        return $query;
        }

        return FALSE;



    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}

function vista_curso($id_establecimiento){
    try{
        $con = connectDB_demos();

        $query =  $con->query("SELECT a.id_esta_curs_doc AS id_pivot, b.id_ce_curso AS id_curso,b.ce_curso_nombre AS nombre_curso,d.ce_nombre AS nivel, CONCAT(c.ce_docente_nombres,' ',c.ce_docente_apellidos) AS nom_docente,
        d.ce_id_niveles AS id_nivel, c.id_ce_docente as id_docente, b.ce_anio_curso
        FROM ce_estable_curso_docente a      
        INNER JOIN ce_curso b ON a.ce_fk_curso = b.id_ce_curso
        INNER JOIN ce_docente c ON a.ce_fk_docente = c.id_ce_docente
        INNER JOIN ce_niveles d ON a.ce_fk_nivel = d.ce_id_niveles
        WHERE a.ce_fk_establecimiento = '$id_establecimiento' ");       
       $cantidad = $query->rowCount();
       $con = NULL;

       if($cantidad >= 1){
           echo '<div class="table-responsive"><table id="tabla_curso" class="table table-striped" style="width:100%;">'
           .'<thead class="text-white">'          
           .'<tr><th style="display:none">Codigo</th>'  
           .'<th>Año Curso</th>'         
           .'<th>Nombre Curso</th>'
           .'<th>Nivel Curso</th>'          
           .'<th>Nombre Docente</th>'                   
           .'<th>Editar</th>'        
           .'</tr></thead><tbody>';
       foreach( $query AS $fila){
           echo   '<tr style="background:#418BCC; color:white;">'         
           .'<td style="display:none">'.$fila["id_curso"].'</td>' 
           .'<td>'.$fila["ce_anio_curso"].'</td>'
           .'<td>'.$fila["nombre_curso"].'</td>' 
           .'<td>'.$fila["nivel"].'</td>'         
           .'<td>'.$fila["nom_docente"].'</td>'                         
           .'<td><button id="actualiza_curso" class="btn btn-danger actualiza_curso"  data-toggle="modal" data-target="#modal_actualizar_curso">Editar</button></td></tr>';  
               
       }  
       echo '</tbody></table></div>';

   }else{
       echo  '<div class="alert alert-danger text-center" role="alert">No existen cursos asociado al establecimiento</div>';

   }       

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}
function estable_curso_nivel($id_docente){
    try{
        $con = connectDB_demos();
        $query = $con->prepare("SELECT ce_fk_docente,ce_fk_curso FROM ce_estable_curso_docente WHERE ce_fk_docente = :id_docente");
        $query->execute(array(":id_docente"=>$id_docente));
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        $con = NULL;
        $result = "";
        if($resultado["ce_fk_docente"] != "" AND $resultado["ce_fk_curso"] != ""){
            $result = 1;
        }
       else if($resultado["ce_fk_docente"] == "" AND $resultado["ce_fk_curso"] == ""){
            $result = 0;
        }
return $result;

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());

    }
} 

function segun_tipo_usuario($user_clave, $user_tipo){
    try{
        $con = connectDB_demos();
        if($user_tipo == 1){

            $query = "SELECT id_ce_docente,ce_docente_nombres,ce_docente_apellidos, ce_establecimiento_id_ce_establecimiento FROM ce_docente WHERE ce_docente_run='$user_clave'";

           $consulta = $con->query($query)->fetch();

          $id_docente = $consulta["id_ce_docente"];

          $id_establecimiento = $consulta["ce_establecimiento_id_ce_establecimiento"];

          $_SESSION["id_establecimiento"] = $id_establecimiento;

          $_SESSION["id_profesor"] =  $id_docente;

          $docente_nombres = $consulta["ce_docente_nombres"]." ".$consulta["ce_docente_apellidos"];

          $_SESSION["profesor_nombres"] = $docente_nombres;

           return  $consulta;
          
            $con = NULL;    
                   

        }elseif($user_tipo == 2){
            
        }elseif($user_tipo == 3){
            
        }

    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }
}
function update_curso($id_curso, $curso_nombre, $id_establecimiento, $id_docente, $id_nivel, $ce_anio_curso){
    try{
        $con = connectDB_demos();
        $queryValida = $con->query("SELECT * FROM ce_curso WHERE ce_curso_nombre = '$curso_nombre' AND ce_anio_curso = $ce_anio_curso");
        $contador = $queryValida->rowCount();
        if($contador == 0) 
        {
            $query =$con->prepare("UPDATE ce_curso SET  ce_curso_nombre = :ce_curso_nombre, ce_fk_establecimiento = :ce_fk_establecimiento, 
            ce_docente_id_ce_docente = :ce_docente_id_ce_docente, ce_fk_nivel = :ce_fk_nivel, ce_anio_curso = :ce_anio_curso WHERE id_ce_curso = :id_ce_curso");
            $query->execute(array(":ce_curso_nombre"=>$curso_nombre,      
            ":ce_fk_establecimiento"=>$id_establecimiento,
            ":ce_docente_id_ce_docente"=>$id_docente,
            ":ce_fk_nivel"=> $id_nivel,
            ":id_ce_curso"=> $id_curso,
            ":ce_anio_curso"=>$ce_anio_curso));
    
            $query = $con->prepare("UPDATE ce_estable_curso_docente SET ce_fk_docente = :ce_fk_docente, ce_fk_nivel = :ce_fk_nivel WHERE ce_fk_curso = :ce_fk_curso AND ce_fk_establecimiento = :ce_fk_establecimiento");
            $query->execute(array(":ce_fk_establecimiento" => $id_establecimiento,
        ":ce_fk_docente"=>$id_docente,
        ":ce_fk_curso"=>$id_curso,
        ":ce_fk_nivel"=> $id_nivel));
    
        if( $query == TRUE){
            return "exito";
        }
        else if( $query == FALSE){
            return "no_exito";
        }
        }

        return "no_exito";


    }catch(Exception $ex){
        exit("Excepción Captutrada: ".$ex->getMessage());
    }

}

//funcion para seleccionar todos los cursos distintos del colegio


function selec_cursos_admin_establecimiento($rbd)
{
    try {
        $conexion = connectDB_demos();

        $resultado = $conexion->query("SELECT DISTINCT a.ce_establecimiento_id_ce_establecimiento AS id_establecimiento,
        b.ce_establecimiento_nombre AS establecimiento,
        b.ce_establecimiento_rbd AS rbd,
        c.id_ce_curso AS id_curso,
        a.ce_docente_id_ce_docente AS id_docente,
        c.ce_curso_nombre AS curso, 
        c.ce_anio_curso AS anio_curso,
        COUNT(a.id_ce_participantes) AS cantidad_curso,
        COUNT(d.id_ce_encuesta_resultado) AS encuestados,
        (COUNT(a.id_ce_participantes) - COUNT(d.id_ce_encuesta_resultado)) AS restantes,
        ROUND((COUNT(d.id_ce_encuesta_resultado)*100)/COUNT(a.id_ce_participantes),2) AS avance
        FROM ce_participantes a
        INNER JOIN ce_establecimiento b ON a.ce_establecimiento_id_ce_establecimiento = b.id_ce_establecimiento
        INNER JOIN ce_curso c ON a.ce_curso_id_ce_curso = c.id_ce_curso
        LEFT JOIN ce_encuesta_resultado  d ON a.ce_participanes_token = d.ce_participantes_token_fk
        WHERE  b.ce_establecimiento_rbd = '$rbd' GROUP BY c.ce_curso_nombre
        ");
        $conexion = null;
        $contador = $resultado->rowCount();
        $titulo = 0;
        $rgbd = 0;
        if ($contador != 0) {
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                if ($titulo <= 0) {
                    echo '<div class="col-md-12">'
                        . '<h3 class="text-white text-center titulo">' . $row["establecimiento"] . '</h3>'
                        . '</div>';
                    $titulo++;
                }

                echo '<div class="col-md-3">'
                    . '<div class="card text-white bg-success mb-3 mb-4 mt-4 hvr-grow" style="max-width: 18rem;">'
                    . '<div class="card-body">'
                   . '<a class="btn btn-success" href="eliminar_datos.php?curso=' . $row['id_curso'] .'&docente='. $row['id_docente'] . '&establecimiento=' . $row['id_establecimiento'] .'" data-toggle="tooltip" data-placement="top" title="Ver Curso">Curso: ' . $row["curso"] .' - '. $row["anio_curso"]
                    . '</a>'
                    . '<a class="btn btn-success" href="exportar_por_curso.php?curso_comp=' . $row['id_curso'] .'&docente_comp='.$row['id_docente']. '&establecimiento_comp=' . $row['id_establecimiento'] . '&comple=' .'encuestados'.'" data-toggle="tooltip" data-placement="top" title="Exportar Estudiantes Encuesta Costestada">Contestadas: ' . $row["encuestados"] . '</a>'
                    . '<a class="btn btn-success" href="exportar_por_curso.php?curso_comp=' . $row['id_curso'] .'&docente_comp='.$row['id_docente']. '&establecimiento_comp=' . $row['id_establecimiento'] . '&comple=' .'no_encuestados'.'" data-toggle="tooltip" data-placement="top" title="Exportar Estudiantes Encuesta No Contestada">No Contestadas: ' .  $row["restantes"]  . '</a>'
                    . '<a class="btn btn-secondary mb-2">Avance: ' . $row['avance'] . '%</a>'
                    . '<br>'
                    . '<a class="btn btn-secondary">Total: ' .$row['cantidad_curso']. '</a></div>'
                    . '<div class="card-footer text-center">'
                    . '<a class="text-white" href="exportar_por_curso.php?curso_comp='. $row['id_curso'] .'&docente_comp='.$row['id_docente'].'&establecimiento_comp=' . $row['id_establecimiento'].'&comple=' . 'curso' .'">Exportar Curso a Excel</a>'
                    . '</div>'
                    . ' </div>'
                    . '</div>';
                //$rgbd = $row['rbd'];
                $id_establecimiento = $row['id_establecimiento'];
                $id_curso = $row['id_curso'];
                $id_docente = $row['id_docente'];

            }

            echo '<div class="col-md-12">'
               . '<div class="alert alert-success text-center" role="alert"><a href="exportar_por_curso.php?curso_comp='.  $id_curso .'&docente_comp='. $id_docente.'&establecimiento_comp=' . $id_establecimiento . '&comple=' . 'establecimiento' .'">Exportar Lista Completa del Establecimiento</a></div>'
                . '</div>';
        } else {
            echo '<div class="col-md-12">'
                . '<br>'
                . '<div class="alert alert-success text-center" role="alert">No hay Cursos Asociados a este Establecimiento</div>'
                . '</div>';
        }
        return $resultado;
    } catch (Exception $ex) {

        echo 'Error Capturado:', $ex->getMessage(), "\n";
    }
}

function selec_cursos_admin_establecimiento_admin($rbd)
{
    try {
        $conexion = connectDB_demos();

        $resultado = $conexion->query("SELECT DISTINCT a.ce_establecimiento_id_ce_establecimiento AS id_establecimiento,
        b.ce_establecimiento_nombre AS establecimiento,
        b.ce_establecimiento_rbd AS rbd,
        c.id_ce_curso AS id_curso,
        a.ce_docente_id_ce_docente AS id_docente,
        c.ce_curso_nombre AS curso, 
        COUNT(a.id_ce_participantes) AS cantidad_curso,
        COUNT(d.id_ce_encuesta_resultado) AS encuestados,
        (COUNT(a.id_ce_participantes) - COUNT(d.id_ce_encuesta_resultado)) AS restantes,
        ROUND((COUNT(d.id_ce_encuesta_resultado)*100)/COUNT(a.id_ce_participantes),2) AS avance
        FROM ce_participantes a
        INNER JOIN ce_establecimiento b ON a.ce_establecimiento_id_ce_establecimiento = b.id_ce_establecimiento
        INNER JOIN ce_curso c ON a.ce_curso_id_ce_curso = c.id_ce_curso
        LEFT JOIN ce_encuesta_resultado  d ON a.ce_participanes_token = d.ce_participantes_token_fk
        WHERE  b.ce_establecimiento_rbd = '$rbd' GROUP BY c.ce_curso_nombre
        ");
        $conexion = null;
        $contador = $resultado->rowCount();
        $titulo = 0;
        $rgbd = 0;
        if ($contador != 0) {
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {               

                echo '<div class="col-md-3">'
                    . '<div class="card text-white bg-info mb-3 mb-4 mt-4 hvr-grow">'
                    .'<div class="card-header"><h4 class=""><a class="card-link" href="editar_curso.php?curso=' . $row['id_curso'] .'&docente='. $row['id_docente'] . '&establecimiento=' . $row['id_establecimiento'] .'" data-toggle="tooltip" data-placement="top" title="Ver Curso">Curso: ' . $row["curso"].'</a></h4></div>'
                    . '<div class="card-body">'                  
                    . '<p><a class="btn btn-success" href="../../exportar_por_curso.php?curso_comp=' . $row['id_curso'] .'&docente_comp='.$row['id_docente']. '&establecimiento_comp=' . $row['id_establecimiento'] . '&comple=' .'encuestados'.'" data-toggle="tooltip" data-placement="top" title="Exportar Estudiantes Encuesta Costestada">Contestadas: ' . $row["encuestados"] . '</a></p>'
                    . '<p><a class="btn btn-success" href="../../exportar_por_curso.php?curso_comp=' . $row['id_curso'] .'&docente_comp='.$row['id_docente']. '&establecimiento_comp=' . $row['id_establecimiento'] . '&comple=' .'no_encuestados'.'" data-toggle="tooltip" data-placement="top" title="Exportar Estudiantes Encuesta No Contestada">No Contestadas: ' .  $row["restantes"]  . '</a></p>'
                    . '<p class="btn btn-success">Avance:'.$row['avance']. '%</p>'                 
                    . '<p class="btn btn-success pull-right">Total:'.$row['cantidad_curso'].'</p></div>'
                    . '<div class="card-footer text-center">'
                    . '<a class="text-white" href="../../exportar_por_curso.php?curso_comp='. $row['id_curso'] .'&docente_comp='.$row['id_docente'].'&establecimiento_comp=' . $row['id_establecimiento'].'&comple=' . 'curso' .'">Exportar Curso a Excel</a>'
                    . '</div>'
                    . ' </div>'
                    . '</div>';
                //$rgbd = $row['rbd'];
                $id_establecimiento = $row['id_establecimiento'];
                $id_curso = $row['id_curso'];
                $id_docente = $row['id_docente'];

            }

            echo '<div class="col-md-12">'
               . '<div class="alert alert-success text-center" role="alert"><a href="../../exportar_por_curso.php?curso_comp='.  $id_curso .'&docente_comp='. $id_docente.'&establecimiento_comp=' . $id_establecimiento . '&comple=' . 'establecimiento' .'">Exportar Lista Completa del Establecimiento</a></div>'
                . '</div>';
        } else {
            echo '<div class="col-md-12">'
                . '<br>'
                . '<div class="alert alert-success text-center" role="alert">No hay Cursos Asociados a este Establecimiento</div>'
                . '</div>';
        }
        return $resultado;
    } catch (Exception $ex) {

        echo 'Error Capturado:', $ex->getMessage(), "\n";
    }
}



function actualiza_estudiantes($id_estu ,$nom_estu, $apelli_estu, $run_estu, $fech_naci_estu,$ciud_estu, $token_estu)
{
    $conexion = connectDB_demos();
    $query = $conexion->prepare("UPDATE ce_participantes SET ce_participantes_nombres = :ce_participantes_nombres, ce_participantes_apellidos = :ce_participantes_apellidos, ce_participantes_run = :ce_participantes_run,
    ce_participantes_fecha_nacimiento = :ce_participantes_fecha_nacimiento, ce_ciudad = :ce_ciudad, ce_participanes_token = :ce_participanes_token
     WHERE  id_ce_participantes = :id_ce_participantes");
     $query->execute([
     ":ce_participantes_nombres"=>$nom_estu,
     ":ce_participantes_apellidos"=>$apelli_estu,
     ":ce_participantes_run"=>$run_estu,
     ":ce_participantes_fecha_nacimiento"=>$fech_naci_estu,
     ":ce_ciudad"=>$ciud_estu,
     ":ce_participanes_token"=>$token_estu,
     ":id_ce_participantes"=>$id_estu
     ]);
     $con = NULL;
    return $query;
}


function editar_estudiantes_curso($id_establecimiento, $id_docente, $id_curso)
{
    try {

        $conexion =connectDB_demos();
        $query = $conexion->query("SELECT COUNT(a.id_ce_participantes) AS cantidad,
        a.id_ce_participantes AS identificador,
        a.ce_participantes_nombres AS nombres,
        a.ce_participantes_apellidos AS apellidos,
        a.ce_participantes_run AS run,
        a.ce_participantes_fecha_nacimiento AS fecha_nac,
        a.ce_participanes_token AS token,
        a.ce_participantes_fecha_registro AS ingreso,
        a.ce_ciudad AS ciudad,
        b.ce_curso_nombre AS curso,
        a.ce_establecimiento_id_ce_establecimiento AS id_establecimiento,
        a.ce_docente_id_ce_docente as id_docente,
        a.ce_curso_id_ce_curso as id_curso
        FROM ce_participantes a
        INNER JOIN ce_curso b ON a.ce_curso_id_ce_curso = b.id_ce_curso
        INNER JOIN ce_establecimiento c ON a.ce_establecimiento_id_ce_establecimiento = c.id_ce_establecimiento
        INNER JOIN ce_docente d ON a.ce_docente_id_ce_docente = d.id_ce_docente 
        WHERE 
        a.ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND
        a.ce_docente_id_ce_docente = '$id_docente' AND
        a.ce_curso_id_ce_curso = '$id_curso'  GROUP BY a.id_ce_participantes");
        $contador = $query->rowCount();
        $button = 0;
        echo '  <table class="bg-info text-white" id="myTable">'
            . '  <thead>'
            . ' <tr>'
            . '<th data-toggle="tooltip" data-placement="top" title="Identificador">Codigo</th>'
            . '<th ><a data-toggle="tooltip" data-placement="top" title="Nombres Estudiante">Nombres</a></th>'
            . '<th data-toggle="tooltip" data-placement="top" title="Apellidos Estudiante">Apellidos</th>'
            . '<th data-toggle="tooltip" data-placement="top" title="Run Estudiante">Run</th>'
            . '<th data-toggle="tooltip" data-placement="top" title="Fecha Nacimiento Estudiante YY-m-d">Fecha Naci.. </th>'
            . '<th>Ciudad </th>'
            . '<th data-toggle="tooltip" data-placement="top" title="Fecha Ingreso YY-m-d">Fecha Carga.. </th>'
            . '<th data-toggle="tooltip" data-placement="top" title="Contraseña Estudiante">Contraseña</th>' 
            . '<th data-toggle="tooltip" data-placement="top" title="Curso Estudiante">Curso</th>'
            . '<th>Eliminar</th>'
            . '<th>Actualizar</th>'
            . '</tr>'
            . '</thead>'
            . '<tbody id="miTabla">'
            . '<div class="text-right">'
            . '<a class="float-left text-white" href="index.php" data-toggle="tooltip" data-placement="bottom" title="Volver"><i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i></a>'
            . '<button id="new_student" class="btn btn-success new_student"  type="button" data-toggle="modal" data-target="#modal_nuevo"">Nuevo Estudiante</button>'
            . '<div>';

        if ($contador > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                if ($button <= 0) {
                    echo "<div class='text-center text-white'><h3>Lista de Estudiantes de " . $row['curso']++ . " (" .   $contador . ")" . "</h3></div>";
                    echo '<div id="id_docente" class="invisible">' .$row["id_docente"]. '</div>';
                    echo '<div id="id_curso" class="invisible">' .$row["id_curso"]. '</div>';
                    echo '<div id="id_establecimiento" class="invisible">' .$row["id_establecimiento"]. '</div>';
                    $button++;
                }
                echo '<tr  class="bg-info text-white" id="pruebas">'
                    . '<td id="id">' . $row['identificador'] . '</td>'
                    . '<td>' . $row['nombres'] . '</td>'
                    . '<td>' . $row['apellidos'] . '</td>'
                    . '<td>' . $row['run'] . '</td>'
                    . '<td>' . $row['fecha_nac'] . '</td>'
                    . '<td>' . $row['ciudad'] . '</td>'
                    . '<td>' . $row['ingreso'] . '</td>'
                    . '<td>' . $row['token'] . '</td>'
                    . '<td id="new_school">' . $row['curso'] . '</td>'         
                    . '<td><div class="con" id="'.$row['identificador'].'"><i class="fa fa-trash fa-2x hvr-grow text-danger"></i></div></td>'
                    . '<td><a data-toggle="modal" data-target="#myModal"><i class="fa fa-edit fa fa-2x hvr-grow update_estu"></i></a></td>'
                    . '</tr>';
            }
            echo '</tbody>'
                . '</table>';
        } else {
            echo '<div class="col-md-12">'
                . '<div class="alert alert-success text-center" role="alert"><a class="float-left" href="index.php">Volver</a>No hay Estudiantes Asociados a este Curso</div>'
                . '</div>';
        }
    } catch (Exception $ex) {
        echo 'Excepción Capturada', $ex->getMessage(), "\n";
    }
}

function eliminar_estudiante($id_estudiante)
{   
    $conexion =connectDB_demos();
    $query = $conexion->prepare("DELETE FROM ce_participantes WHERE id_ce_participantes = :id_estudiante");
    $query->execute([":id_estudiante"=>$id_estudiante]);
    $conexion = NULL;

    return $query;
}

function nuevo_estudiante_uni($nom_estu,$apelli_estu,$run_estu,$fech_naci_estu,$token_estu,$ciu_estu,$id_establecimiento,$id_docente,$id_curso,$id_nivel,$id_pais, $anio)
{
    try{
        date_default_timezone_set('America/Santiago'); 

        $fecha_registro =  date('Y-m-d H:m:s');
        $conexion =connectDB_demos();   
        $estado_encuesta = 0;
        

     //   $fk_nivel = nivel_estudiante_establecimiento($id_establecimiento,$id_docente,$id_curso);          
               $query = $conexion->prepare("INSERT INTO ce_participantes(ce_estado_encuesta,ce_participantes_nombres,ce_participantes_apellidos,ce_participantes_run,ce_participantes_fecha_nacimiento,ce_participantes_fecha_registro,ce_participanes_token,ce_ciudad,ce_establecimiento_id_ce_establecimiento,ce_docente_id_ce_docente,ce_curso_id_ce_curso,ce_fk_nivel,ce_pais_id_ce_pais, ce_anio_registro) 
               VALUES (:estado_encuesta,:nom_estu,:apelli_estu,:run_estu,:fech_naci_estu,:fecha_registro,:token_estu,:ciu_estu,:id_establecimiento,:id_docente,:id_curso,:id_nivel,:id_pais,:anio_registro)");
               $query->execute([
               ":estado_encuesta"=>$estado_encuesta,
               ":nom_estu"=>$nom_estu,
               ":apelli_estu"=>$apelli_estu,
               ":run_estu"=>$run_estu,
               ":fech_naci_estu"=>$fech_naci_estu,
               ":fecha_registro"=>$fecha_registro,
               ":token_estu"=>$token_estu,
               ":ciu_estu"=>$ciu_estu,
               ":id_establecimiento"=>$id_establecimiento,
               ":id_docente"=>$id_docente,
               ":id_curso"=>$id_curso,
               ":id_nivel"=>$id_nivel,
               ":id_pais"=>$id_pais,
               ":anio_registro"=>$anio]);

               $conexion = NULL;

           return $query;

    }
    catch(Exception $ex){
    $excepcion = $ex->getMessage();
    ce_excepciones($excepcion);
        exit("Excepción Capturada".$ex->getMessage());
        
    }
   
}

function compro_existen_estudiante($run){
try{
    $conexion =connectDB_demos();  
    $anio = date("Y");
    $query = $conexion->prepare("SELECT ce_participantes_run FROM ce_participantes WHERE ce_participantes_run= :ce_participantes_run AND ce_anio_registro=:anio");
    $query->execute([":ce_participantes_run"=>$run, ":anio"=>$anio]);
    $conexion = NULL;
    $resultado = $query->rowCount();

    if($resultado <= 0){
        return 0;
    }else if($resultado >= 1){
        return 1;
    }

}catch(Exception $ex){
    $excepcion = $ex->getMessage();
    ce_excepciones($excepcion);
        exit("Excepción Capturada".$ex->getMessage());
        
    }
}

function curso_datos($id_estable, $id_docente, $id_curso){
    try{
        $conexion =connectDB_demos();  
        $query = $conexion->prepare("SELECT d.ce_curso_nombre as nomcurso,COUNT(e.id_ce_participantes) AS estudiantes
        FROM ce_estable_curso_docente a
        INNER JOIN ce_establecimiento b ON a.ce_fk_establecimiento = b.id_ce_establecimiento
        INNER JOIN ce_docente c ON a.ce_fk_docente = c.id_ce_docente 
        INNER JOIN ce_curso d ON a.ce_fk_curso = d.id_ce_curso
        JOIN ce_participantes e ON d.id_ce_curso = e.ce_curso_id_ce_curso
        WHERE a.ce_fk_establecimiento IN (:id_estable) AND a.ce_fk_docente IN (:id_docente) AND a.ce_fk_curso IN (:id_curso)");
        $query->execute([
            "id_estable"=>$id_estable,
            "id_docente"=>$id_docente,
            "id_curso"=>$id_curso
            ]);

            foreach($query as $fila){
                $resultado = array("nomcurso"=>$fila["nomcurso"],"estudiantes"=>$fila["estudiantes"]);
            }
            return $resultado;

    }catch(Exception $ex){
        $excepcion = $ex->getMessage();
        ce_excepciones($excepcion);
            exit("Excepción Capturada".$ex->getMessage());
            
        }
}
function nivel_estudiante_establecimiento($id_estab,$id_doc,$id_curso){
    try{
        $con =connectDB_demos();
        $query = $con->prepare("SELECT ce_fk_nivel FROM ce_participantes WHERE ce_establecimiento_id_ce_establecimiento=:id_estable AND ce_docente_id_ce_docente =:id_docen AND ce_curso_id_ce_curso=:id_curso");
        $query->execute([
            "id_estable"=>$id_estab,
            "id_docen"=>$id_doc,
            "id_curso"=>$id_curso
        ]);
        $con = NULL;
        $resultado = $query->fetch(PDO::FETCH_ASSOC);

        return $resultado["ce_fk_nivel"];

    }catch(Exception $ex){
        exit("Excepción Capturada".$ex->getMessage());
    }

}




//COMIENZA CON LA BASE DE DATOS DEL 2.0
function select_establecimiento($id_establecimiento)
{
    try{

    $con = connectDB_demos();
    $query = $con->query("SELECT DISTINCT c.id_ce_docente as id_docente,c.ce_docente_nombres as nom_docente, c.ce_docente_apellidos as apelli_docente
    FROM ce_estable_curso_docente a
    INNER JOIN ce_establecimiento b ON a.ce_fk_establecimiento = b.id_ce_establecimiento
    INNER JOIN ce_docente c ON a.ce_fk_docente = c.id_ce_docente
    INNER JOIN ce_curso d ON a.ce_fk_curso = d.id_ce_curso
    WHERE a.ce_fk_establecimiento = '$id_establecimiento'");
    

    $con = NULL;

    echo ' <option value="0" disabled selected>Seleccione</option>';
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' .$row["id_docente"].'">' .$row["nom_docente"]. ' '.$row["apelli_docente"]. '</option>';
    }
    }catch(Exception $ex){
        exit("Excepción Capturada: ".$ex->getMessage());
    }
       
}
function select_docente($id_docente)
{
    $con = connectDB_demos();
    $query = $con->query("SELECT d.id_ce_curso as id_curso,d.ce_curso_nombre AS nom_curso, a.ce_fk_nivel AS id_nivel, e.ce_nombre AS nom_nivel, d.ce_anio_curso
    FROM ce_estable_curso_docente a
   INNER JOIN ce_establecimiento b ON a.ce_fk_establecimiento = b.id_ce_establecimiento
   INNER JOIN ce_docente c ON a.ce_fk_docente = c.id_ce_docente
   INNER JOIN ce_curso d ON a.ce_fk_curso = d.id_ce_curso
   INNER JOIN ce_niveles e ON a.ce_fk_nivel = e.ce_id_niveles
   WHERE a.ce_fk_docente = '$id_docente'");
      $con = null;        
    
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' .$row["id_curso"].','.$row["id_nivel"]. '">' .$row["nom_curso"].' - '.$row["ce_anio_curso"].'</option>';
        }
  
}
function valida_carga_excel($id_establecimiento, $id_docente, $id_curso, $anio)
{
    try{

        $con = connectDB_demos();
        $query = $con->query("SELECT *
         FROM ce_participantes a
         WHERE a.ce_establecimiento_id_ce_establecimiento ='$id_establecimiento' AND a.ce_docente_id_ce_docente ='$id_docente' AND
         a.ce_curso_id_ce_curso ='$id_curso' AND a.ce_anio_registro = '$anio'");
          $con = null;
          $resultado = $query->fetch(PDO::FETCH_ASSOC);
          return $resultado;

    }catch(Exception $ex){
        exit("Excepción Capturada: ".$ex->getMessage());
    }
   
  
}



function select_establecimientos_distintos()
{
    try{
        $con = connectDB_demos();
        $query = $con->prepare("SELECT id_ce_establecimiento , ce_establecimiento_nombre, ce_establecimiento_rbd 
        FROM ce_establecimiento");
        $query->execute();
          $con = null;
          $resultado = $query->fetch(PDO::FETCH_ASSOC);      
          if( $resultado != 0){
            foreach($query AS $fila){
                echo ' <div class="col-sm-4 mt-4">'
                 .'<div class="card" style="width: 18rem; height:10rem;">'
                  .' <div class="card-body">'
                     .'<h5 class="card-title">'.$fila["ce_establecimiento_nombre"].'</h5>'
                     .'<p class="card-text"> RBD: '.$fila["ce_establecimiento_rbd"].'</p>'                    
                   .'</div>'
                 .'</div>'
               .'</div>';               
             }
          }     

        else if( $resultado <= 0){
              echo '<div class="col-md-12 mt-4"><div class="alert alert-danger text-center" role="alert"> No hay establecimientos Registrados  </div></div> ';              
        }                  

    }catch(Exception $ex){
        exit("Excepción Capturada: ".$ex->getMessage());
    }
   
  
}

function guarda_establecimientos($nombre,$rbd,$id_pais)
{
    try{

        $con = connectDB_demos();
        $query = $con->query("SELECT COUNT(*) as cantidad
        FROM ce_establecimiento a 
        WHERE a.ce_establecimiento_nombre ='$nombre' OR a.ce_establecimiento_rbd ='$rbd'");
        $resultado = $query->fetch(PDO::FETCH_ASSOC);

        if($resultado["cantidad"] <= 0){
            $query_guarda = $con->query("INSERT INTO ce_establecimiento (ce_establecimiento_nombre,ce_establecimiento_rbd,id_pais) VALUES
            ('$nombre','$rbd','$id_pais')");

            $query_select_ulti = $con->query("SELECT id_ce_establecimiento AS id_establecimiento FROM ce_establecimiento ORDER BY  id_ce_establecimiento DESC LIMIT 1");
            $resulta_estable = $query_select_ulti->fetch(PDO::FETCH_ASSOC);
            $con = NULL;
            $id_estableci = $resulta_estable["id_establecimiento"];

          generar_user_establecimiento($nombre, $rbd,$id_estableci);

            return $query_guarda;          

        }else if($resultado["cantidad"] >= 1 ){
            
          return  'existe';
        }
          
          
    }catch(Exception $ex){
        exit("Excepción Capturada: ".$ex->getMessage());
    }
   
  
}

function pais_establecimiento($id_pais){
    try{
        $con = connectDB_demos();

        //obtenemos el id del establecimiento almacenado en la tabla establecimiento

        $query = $con->prepare("SELECT id_pais FROM ce_establecimiento WHERE ce_establecimiento_rbd=:id_pais");
        $query->execute([":id_pais"=>$id_pais]);
        
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION["pais_establecimiento"] =  $resultado["id_pais"];

    }catch(Exception $ex){
        return FALSE;
    }
}

function nombre_establecimiento($rbd_establecimiento){

    try{
        $con = connectDB_demos();

        //obtenemos el id del establecimiento almacenado en la tabla establecimiento

        $query = $con->prepare("SELECT ce_establecimiento_nombre FROM ce_establecimiento WHERE ce_establecimiento_rbd=:rbd_establecimiento");
        $query->execute([":rbd_establecimiento"=>$rbd_establecimiento]);
        $con = NULL;
        
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        return $resultado["ce_establecimiento_nombre"];

    }catch(Exception $ex){
        return FALSE;
    }

}

function generar_user_establecimiento($nombre_user,$pass,$id_estableci)
{
    try {
        date_default_timezone_set('America/Santiago'); 

        $fecha_registro =  date('Y-m-d H:m:s');

        $con = connectDB_demos();
        $pass_final = "E".$pass;
       
        $query = $con->query("INSERT INTO ce_usuarios(nombre_usu,contrasena_usu,fecha_ingreso_usu,fk_establecimiento) VALUES('$pass','$pass_final',' $fecha_registro','$id_estableci')");

        $query_retorna = $con->query("SELECT id_usu FROM ce_usuarios ORDER BY id_usu DESC LIMIT 1");
        $resultado_retorno = $query_retorna->fetch(PDO::FETCH_ASSOC);

       $id_usuario =  $resultado_retorno['id_usu'];
        $query_rol = $con->query("INSERT INTO ce_rol_user(id_usuario_fk,id_roles_fk) VALUES ('$id_usuario','2')");

        $con = null;
      
    } catch (Exception $e) {
        
    }
}
function select_seccion()
{
    $con = connectDB_demos();
    $query = $con->query("SELECT sec_id,sec_nombre FROM ce_sec_seccion ORDER BY sec_id");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<option value="' .$row["sec_id"]. '">' .$row["sec_nombre"]. '</option>';
    }

    $con = null;
}
function select_talleres()
{
    try{
        $con = connectDB_demos();
        $query = $con->prepare("SELECT id_tip_taller,nom_taller FROM tipo_talleres ORDER BY id_tip_taller");
        $query->execute();
        $con = null;
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' .$row["id_tip_taller"]. '">' .$row["nom_taller"]. '</option>';
                }
            
    }  

    catch(Exception $ex){
        exit("Excepción Capturada: ".$ex->getMessage());
        }
}
function select_pais()
{
    try {
        $con = connectDB_demos();
        $query = $con->query("SELECT id_ce_pais,ce_pais_nombre FROM ce_pais");
        $con = null;
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' .$row["id_ce_pais"]. '">' .$row["ce_pais_nombre"]. '</option>';
        }
    } catch (Exception $e) {
        echo '<option value="error">' . "Error" . '</option>';

    } catch (MySQLException $e) {
        // other mysql exception (not duplicate key entry)
        $e->getMessage();
    }

}

function select_curso($id_establecimiento)
{
    try {
        $curso = "5°A B";
        $con = connectDB_demos();
        $query = $con->prepare('SELECT * FROM ce_curso WHERE ce_curso_nombre=:curso AND ce_fk_establecimiento=:establecimiento');
        $query->execute([
            ':curso' =>$curso,
            ':establecimiento' => $id_establecimiento
        ]);
        $con = null;
        echo '<option value="-1">Seleccione</option>';
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' .$row["id_ce_curso"]. '">' .$row["ce_curso_nombre"]. '</option>';
        }
    } catch (Exception $e) {
        echo '<option value="error">' . "Error" . '</option>';

    } catch (MySQLException $e) {
        // other mysql exception (not duplicate key entry)
        $e->getMessage();
    }

}

//GUARDAMOS DOCUMENTACION DE APOYO DOCENTE

function insertar_material($doc_nombre,$ruta_doc,$doc_ruta_imagen_tipo,$doc_extension, $id_seccion,$id_talleres)
{
    try{
        $con = connectDB_demos();
        $query = $con->prepare('INSERT INTO ce_doc_documentos(doc_nombre,doc_ruta,doc_ruta_imagen_tipo,doc_extension,doc_id_seccion,id_tipo_talleres) VALUES(:doc_nombre,:doc_ruta,:doc_ruta_imagen_tipo,:doc_extension,:doc_id_seccion,:id_talleres)');
        $query->execute([
            'doc_nombre' =>$doc_nombre,
            'doc_ruta' =>$ruta_doc,
            'doc_ruta_imagen_tipo' =>$doc_ruta_imagen_tipo,
            'doc_extension' =>$doc_extension,        
            'doc_id_seccion' =>$id_seccion,
            'id_talleres'=>$id_talleres
        ]);
        $con = NULL;
        return $query;
    }catch(Exception $ex){
        exit("Excepción Capturada: ".$ex->getMessage());
        }
  
}

function icono_recurso($tipo){

    switch($tipo){
        case "pdf":
        return '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>';
        break;
        case "mp4":
        return '<i class="fa fa-file-video-o" aria-hidden="true"></i>';
        break;
        case "mp3":
        return '<i class="fa fa-file-audio-o" aria-hidden="true"></i>';
        break;
        case "docx":
        return '<i class="fa fa-file-text-o" aria-hidden="true"></i>';
        break;
    }

}

function select_material_talleres_aula()
{
    
    $id_tipo_taller = '1';
    $con = connectDB_demos();
    $query = $con->prepare('SELECT * FROM ce_doc_documentos WHERE  id_tipo_talleres=:id_tipo_talleres');
    $query->execute([
        'id_tipo_talleres' =>$id_tipo_taller
    ]);
    $resultado = $query->rowCount();
    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): 
            $icono = icono_recurso( $row["doc_extension"])
        ?>
 <div class="col-md-4 hvr-float-shadow">
<div class="tarjeta-recursos">
                     <div class="tarjeta-header">
                         <div class="text-center">
                        <?php echo $icono ?>
                         </div>
                     </div>
                     <div class="tarjeta-body">
                         <div class="text-tarjeta"><?php echo $row["doc_nombre"]?></div>
                         <div class="tarjeta-descargar">
                        <!--  <a data-toggle="tooltip" data-placement="top" title="Visualizar archivo"><i class="fa fa-eye fa-2x" aria-hidden="true"></i></a>   -->
                         <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"] ?>" data-toggle="tooltip" data-placement="top" title="Descargar Recurso"><i class="fa fa-download fa-2x" aria-hidden="true"></i></a>
                         </div>
                     </div>                     
                 </div>

                 </div>

        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados a esta sección</strong>
        </div>
    <?php endif;
}

function select_material_talleres_familia()
{
    
    $id_tipo_taller = '2';
    $con = connectDB_demos();
    $query = $con->prepare('SELECT * FROM ce_doc_documentos WHERE  id_tipo_talleres=:id_tipo_talleres');
    $query->execute([
        'id_tipo_talleres' =>$id_tipo_taller
    ]);
    $resultado = $query->rowCount();
    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): 
            $icono = icono_recurso( $row["doc_extension"])
        ?>
 <div class="col-md-4 hvr-float-shadow">
<div class="tarjeta-recursos">
                     <div class="tarjeta-header-familia">
                         <div class="text-center">
                        <?php echo $icono ?>
                         </div>
                     </div>
                     <div class="tarjeta-body">
                         <div class="text-tarjeta"><?php echo $row["doc_nombre"]?></div>
                         <div class="tarjeta-descargar">
                         <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"] ?>" data-toggle="tooltip" data-placement="top" title="Descargar Recurso"><i class="fa fa-download fa-2x" aria-hidden="true"></i></a>
                         </div>
                     </div>                     
                 </div>

                 </div>

        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados a esta sección</strong>
        </div>
    <?php endif;
}

function select_material_talleres_otros()
{
    
    $id_tipo_taller = '3';
    $con = connectDB_demos();
    $query = $con->prepare('SELECT * FROM ce_doc_documentos WHERE  id_tipo_talleres=:id_tipo_talleres');
    $query->execute([
        'id_tipo_talleres' =>$id_tipo_taller
    ]);
    $resultado = $query->rowCount();
    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): 
            $icono = icono_recurso( $row["doc_extension"])
        ?>
 <div class="col-md-4 hvr-float-shadow text-center">
<div class="tarjeta-recursos">
                     <div class="tarjeta-header-otros">
                         <div class="text-center">
                        <?php echo $icono ?>
                         </div>
                     </div>
                     <div class="tarjeta-body">
                         <div class="text-tarjeta"><?php echo $row["doc_nombre"]?></div>
                         <div class="tarjeta-descargar">
                         <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"] ?>" data-toggle="tooltip" data-placement="top" title="Descargar Recurso"><i class="fa fa-download fa-2x" aria-hidden="true"></i></a>
                         </div>
                     </div>                     
                 </div>

</div>

        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados a esta sección</strong>
        </div>
    <?php endif;
}


function select_material_pdf()
{
    $id_seccion = '1';
    $con = connectDB_demos();
    $query = $con->prepare('SELECT * FROM ce_doc_documentos WHERE  doc_id_seccion=:doc_id_seccion');
    $query->execute([
        'doc_id_seccion' =>$id_seccion
    ]);
    $resultado = $query->rowCount();
    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-3">
                <div class="card border border-success mt-4 hvr-grow" style="width: 14rem;">
                    <img class="card-img-top mt-2" src="<?php echo $row["doc_ruta_imagen_tipo"] ?>" alt="Card image cap"
                         width="100px" height="100px">
                    <div class="card-body border border-top-success mt-2 mb-2">
                        <div class="card-title expande">
                            <p><?php echo $row["doc_nombre"] ?></p>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"] ?>"
                               class="btn btn-link text-success"
                               data-toggle="tooltip" data-placement="top"
                               title="Descargar" target="_new">
                                <div class="fa fa-download"></div>
                            </a>                         
                              
                                <a id="delete" class="btn btn-link text-danger <?php  echo $_SESSION["invisible"]?>" data-toggle="tooltip" data-placement="top"
                                        title="Borrar" onclick="elimina_documento('<?php echo $row['doc_id']?>','<?php echo $row['doc_nombre'].'.'.$row['doc_extension']?>')">
                                    <div class="fa fa-trash"></div>
                                </a>
                            
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados a esta sección</strong>
        </div>
    <?php endif;
}

function select_material_docx()
{
    $id_seccion = '2';
    $con = connectDB_demos();
    $query = $con->query("SELECT * FROM ce_doc_documentos WHERE doc_id_seccion='$id_seccion'");
    $resultado = $query->rowCount();

    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-3">
                <div class="card border border-success mt-4 hvr-grow" style="width: 14rem;">
                    <img class="card-img-top mt-2" src="<?php echo $row["doc_ruta_imagen_tipo"] ?>" alt="Card image cap"
                         width="100px" height="100px">
                    <div class="card-body border border-top-success mt-2 mb-2">
                        <div class="card-title expande">
                            <p><?php echo $row["doc_nombre"] ?></p>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"] ?>"
                               class="btn btn-link text-success"
                               data-toggle="tooltip" data-placement="top"
                               title="Descargar">
                                <div class="fa fa-download"></div>
                            </a>

                            <a id="delete" class="btn btn-link text-danger <?php  echo $_SESSION["invisible"]?>" data-toggle="tooltip" data-placement="top"
                                        title="Borrar" onclick="elimina_documento('<?php echo $row['doc_id']?>','<?php echo $row['doc_nombre'].'.'.$row['doc_extension']?>')">
                                    <div class="fa fa-trash"></div>
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados a esta sección</strong>
        </div>
    <?php endif;
}

function select_material_pptx()
{
    $id_seccion = '5';
    $con = connectDB_demos();
    $query = $con->query("SELECT * FROM ce_doc_documentos WHERE doc_id_seccion='$id_seccion'");
    $resultado = $query->rowCount();
    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-3">
                <div class="card border border-success mt-4 hvr-grow" style="width: 14rem;">
                    <img class="card-img-top mt-2" src="<?php echo $row["doc_ruta_imagen_tipo"] ?>" alt="Card image cap"
                         width="100px" height="100px">
                    <div class="card-body border border-top-success mt-2 mb-2">
                        <div class="card-title expande">
                            <p><?php echo $row["doc_nombre"] ?></p>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"]?>"
                               class="btn btn-link text-success"
                               data-toggle="tooltip" data-placement="top"
                               title="Descargar">
                                <div class="fa fa-download"></div>
                            </a>

                            <a id="delete" class="btn btn-link text-danger <?php  echo $_SESSION["invisible"]?>" data-toggle="tooltip" data-placement="top"
                                        title="Borrar" onclick="elimina_documento('<?php echo $row['doc_id']?>','<?php echo $row['doc_nombre'].'.'.$row['doc_extension']?>')">
                                    <div class="fa fa-trash"></div>
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados a esta sección</strong>
        </div>
    <?php endif;
}

function select_material_video()
{
    $id_seccion = '4';
    $con = connectDB_demos();
    $query = $con->query("SELECT * FROM ce_doc_documentos WHERE doc_id_seccion='$id_seccion'");
    $resultado = $query->rowCount();
    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-3">
                <div class="card border border-success mt-4 hvr-grow" style="width: 14rem;">
                    <img class="card-img-top mt-2" src="<?php echo $row["doc_ruta_imagen_tipo"] ?>" alt="Card image cap"
                         width="100px" height="100px">
                    <div class="card-body border border-top-success mt-2 mb-2">
                        <div class="card-title expande">
                            <p><?php echo $row["doc_nombre"] ?></p>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"]?>"
                               class="btn btn-link text-success"
                               data-toggle="tooltip" data-placement="top"
                               title="Descargar" target="_new">
                                <div class="fa fa-download"></div>
                            </a>

                            <a id="delete" class="btn btn-link text-danger <?php  echo $_SESSION["invisible"]?>" data-toggle="tooltip" data-placement="top"
                                        title="Borrar" onclick="elimina_documento('<?php echo $row['doc_id']?>','<?php echo $row['doc_nombre'].'.'.$row['doc_extension']?>')">
                                    <div class="fa fa-trash"></div>
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados a esta sección</strong>
        </div>
    <?php endif;
}

function select_material_mp3()
{
    $id_seccion = '3';
    $con = connectDB_demos();
    $query = $con->query("SELECT * FROM ce_doc_documentos WHERE doc_id_seccion='$id_seccion'");
    $resultado = $query->rowCount();
    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-3">
                <div class="card border border-success mt-4 hvr-grow" style="width: 14rem;">
                    <img class="card-img-top mt-2" src="<?php echo $row["doc_ruta_imagen_tipo"] ?>" alt="Card image cap"
                         width="100px" height="100px">
                    <div class="card-body border border-top-success mt-2 mb-2">
                        <div class="card-title expande">
                            <p><?php echo $row["doc_nombre"] ?></p>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"]?>"
                               class="btn btn-link text-success"
                               data-toggle="tooltip" data-placement="top"
                               title="Descargar" target="_new">
                                <div class="fa fa-download"></div>
                            </a>

                            <a id="delete" class="btn btn-link text-danger <?php  echo $_SESSION["invisible"]?>" data-toggle="tooltip" data-placement="top"
                                        title="Borrar" onclick="elimina_documento('<?php echo $row['doc_id']?>','<?php echo $row['doc_nombre'].'.'.$row['doc_extension']?>')">
                                    <div class="fa fa-trash"></div>
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados a esta sección</strong>
        </div>
    <?php endif;
}

//Hasta aqui 24-05-2019
function select_documentos_aula()
{
   
    $con = connectDB_demos();
    $query = $con->prepare("SELECT * FROM ce_doc_documentos WHERE id_tipo_talleres=1");
    $query->execute();
    $resultado = $query->rowCount();
    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-3">
                <div class="card border border-success mt-4 hvr-grow" style="width: 14rem;">
                    <img class="card-img-top mt-2" src="<?php echo $row["doc_ruta_imagen_tipo"] ?>" alt="Card image cap"
                         width="100px" height="100px">
                    <div class="card-body border border-top-success mt-2 mb-2">
                        <div class="card-title expande">
                            <p><?php echo $row["doc_nombre"] ?></p>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"]?>" class="btn btn-link text-success" data-toggle="tooltip" data-placement="top"
                               title="Descargar" target="_new">
                                <div class="fa fa-download"></div>
                            </a>

                           
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados a Talleres para aula.</strong>
        </div>
    <?php endif;
}

function select_documentos_familia()
{
   
    $con = connectDB_demos();
    $query = $con->prepare("SELECT * FROM ce_doc_documentos WHERE id_tipo_talleres=2");
    $query->execute();
    $resultado = $query->rowCount();
    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-3">
                <div class="card border border-success mt-4 hvr-grow" style="width: 14rem;">
                    <img class="card-img-top mt-2" src="<?php echo $row["doc_ruta_imagen_tipo"] ?>" alt="Card image cap"
                         width="100px" height="100px">
                    <div class="card-body border border-top-success mt-2 mb-2">
                        <div class="card-title expande">
                            <p><?php echo $row["doc_nombre"] ?></p>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"]?>" class="btn btn-link text-success" data-toggle="tooltip" data-placement="top"
                               title="Descargar" target="_new">
                                <div class="fa fa-download"></div>
                            </a>

                           
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados a Talleres para familia.</strong>
        </div>
    <?php endif;
}

function select_documentos_otros()
{
   
    $con = connectDB_demos();
    $query = $con->prepare("SELECT * FROM ce_doc_documentos WHERE id_tipo_talleres=3");
    $query->execute();
    $resultado = $query->rowCount();
    if ($resultado >= 1):
        while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col-md-3">
                <div class="card border border-success mt-4 hvr-grow" style="width: 14rem;">
                    <img class="card-img-top mt-2" src="<?php echo $row["doc_ruta_imagen_tipo"] ?>" alt="Card image cap"
                         width="100px" height="100px">
                    <div class="card-body border border-top-success mt-2 mb-2">
                        <div class="card-title expande">
                            <p><?php echo $row["doc_nombre"] ?></p>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <a href="documentos/material/<?php echo $row["doc_nombre"].".".$row["doc_extension"]?>" class="btn btn-link text-success" data-toggle="tooltip" data-placement="top"
                               title="Descargar" target="_new">
                                <div class="fa fa-download"></div>
                            </a>

                           
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endwhile;
    else: ?>
        <div class="alert alert-danger mt-3 justify-content-center" role="alert">
            <strong>No hay archivos asociados Otros Materiales.</strong>
        </div>
    <?php endif;
}

//SECCION DE ADMINISTRADOR


function nom_establecimiento($id_establecimiento){
    try{
        $con = connectDB_demos();
        $query = $con->query("SELECT a.id_ce_establecimiento AS id_esta, a.ce_establecimiento_nombre AS nom_esta
        FROM ce_establecimiento a
        WHERE a.id_ce_establecimiento = '$id_establecimiento'");
        $con = NULL;
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION["establecimiento"] = $resultado["nom_esta"];

    }catch(Exception $ex){
    exit("Excepción Capturada: ".$ex->getMessage());
    }

}
//QUERY DE EJMPLOS

function preguntas_chile()
{
    $con = connectDB_demos();
    $query = $con->query("SELECT * FROM ce_preguntas WHERE ce_preguntas.ce_dimension_id = 1 AND   ce_preguntas.ce_pais_id_ce_pais = 1");
    $con = null;
    $contador = 0;

    echo "<table class='table text-white'>
    <thead>
        <tr>
            <th class=''>Identificador</th>
            <th>pregunta</th>
            
        </tr>
    </thead>
    <tbody>";
    while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>'
            . '<td>' . $fila["id_ce_preguntas"] . '</td>'
            . '<td>' . $fila["ce_pregunta_nombre"] . '</td>'
            . '</tr>';
        $contador++;
    }
    echo '<tr>'
        . '<td>Total</td>'
        . '<td>' . $contador . '</td>';
    echo '</tbody></table>';

}

function preguntas_peru()
{
    $con = connectDB_demos();
    $query = $con->query("SELECT * FROM ce_preguntas WHERE ce_preguntas.ce_dimension_id = 1 AND   ce_preguntas.ce_pais_id_ce_pais = 3");
    $con = null;
    $contador = 0;
    echo "<table class='table text-white'>
    <thead>
        <tr>
            <th class=''>Identificador</th>
            <th>pregunta</th>
            
        </tr>
    </thead>
    <tbody>";
    while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>'
            . '<td>' . $fila["id_ce_preguntas"] . '</td>'
            . '<td>' . $fila["ce_pregunta_nombre"] . '</td>'
            . '</tr>';
        $contador++;
    }
    echo '<tr>'
        . '<td>Total</td>'
        . '<td>' . $contador . '</td>';
    echo '</tbody></table>';

}

function preguntas_uruguay()
{
    $con = connectDB_demos();
    $query = $con->query("SELECT * FROM ce_preguntas WHERE ce_preguntas.ce_dimension_id = 1 AND   ce_preguntas.ce_pais_id_ce_pais = 2");
    $con = null;
    $contador = 0;

    echo "<table class='table text-white'>
    <thead>
        <tr>
            <th class=''>Identificador</th>
            <th>pregunta</th>
            
        </tr>
    </thead>
    <tbody>";
    while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>'
            . '<td>' . $fila["id_ce_preguntas"] . '</td>'
            . '<td>' . $fila["ce_pregunta_nombre"] . '</td>'
            . '</tr>';
        $contador++;

    }

    echo '<tr>'
        . '<td>Total</td>'
        . '<td>' . $contador . '</td>';

    echo '</tbody></table>';

}

function preguntas_ediva()
{
    $con = connectDB_demos();
    $query = $con->query("SELECT * FROM ce_preguntas WHERE ce_preguntas.ce_dimension_id = 1 AND   ce_preguntas.ce_pais_id_ce_pais = 5");
    $con = null;
    $contador = 0;

    echo "<table class='table text-white'>
    <thead>
        <tr>
            <th class=''>Identificador</th>
            <th>pregunta</th>
            
        </tr>
    </thead>
    <tbody>";
    while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>'
            . '<td>' . $fila["id_ce_preguntas"] . '</td>'
            . '<td>' . $fila["ce_pregunta_nombre"] . '</td>'
            . '</tr>';
        $contador++;

    }

    echo '<tr>'
        . '<td>Total</td>'
        . '<td>' . $contador . '</td>';

    echo '</tbody></table>';
}

function selecciona_paises()
{
    $con = connectDB_demos();
    $query = $con->query("SELECT * FROM ce_pais");
    $con = null;

    echo "<select name='select_pais' id='select_pais' class='form-control'>";
    echo "<option value='' disabled selected hidden>Seleccione un Pais</option>";

    while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $fila['id_ce_pais'] . "'>" . $fila['ce_pais_nombre'] . "</option>";
    }
    echo "</select>";

}

function selecciona_dimension()
{
    $con = connectDB_demos();
    $query = $con->query("SELECT * FROM ce_dimension");
    $con = null;

    echo "<select name='select_dimension' id='select_dimension' class='form-control'>";
    echo "<option value='' disabled selected hidden>Seleccione dimensión</option>";

    while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $fila['di_id'] . "'>" . $fila['di_nombre'] . "</option>";
    }
    echo "</select>";
}

function selecciona_preguntas_por_pais($id_pais)
{
    $con = connectDB_demos();
    $query = $con->query("SELECT ce_preguntas.id_ce_preguntas as id_pregunta, ce_preguntas.ce_pregunta_nombre as nombre_pregunta,
    ce_preguntas.ce_nivel as nivel ,ce_preguntas.ce_orden as orden,
    ce_dimension.di_nombre as dimension, ce_pais.ce_pais_nombre as nombre_pais
    FROM ce_preguntas 
    INNER JOIN ce_dimension ON  ce_preguntas.ce_dimension_id = ce_dimension.di_id
    INNER JOIN ce_pais ON ce_preguntas.ce_pais_id_ce_pais = ce_pais.id_ce_pais
    WHERE 1=1 and ce_preguntas.ce_pais_id_ce_pais = '$id_pais'");
    $con = null;

    echo '<table name ="tabla_pais" id="tabla_pais" class="table text-white">
    <thead>
      <tr>
        <th>identificador</th>
        <th>Nombre Pregunta</th>
        <th>Nivel</th>
        <th>Orden</th>
        <th>Dimensión</th>        
        <th>Pais</th>
        <th>Editar</th>
      </tr>
    </thead>
    <tbody>';
    while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
        $ce_nivel = $fila["nivel"];
        if($ce_nivel == 1){
            $ce_nivel = "BÁSICA";
        }
        else if($ce_nivel == 2){
            $ce_nivel = "MEDIA";
        }
        echo '<tr>'
            . '<td>'.$fila["id_pregunta"].'</td>'
            . '<td>'.$fila["nombre_pregunta"].'</td>'
            . '<td>'.$ce_nivel.'</td>'
            . '<td>'.$fila["orden"].'</td>'
            . '<td>'.$fila["dimension"].'</td>'
            . '<td>'.$fila["nombre_pais"].'</td>'
            . '<td><a class="fa fa-edit fa-2x hvr-grow text-warning" href="editar_pregunta.php?id_pregunta='.$fila["id_pregunta"].'" target="_new" data-toggle="tooltip" data-placement="top" title="Editar"></a></td>'
            . '</tr>';
    }
    echo "</tbody>
    </table>";
    echo '<div class="invisible"><script>
editar_pregunta();       
$("#tabla_pais").hpaging({
    "limit": 5
});
</script></div>';
    return $query;
}

function update_pregunta($id_pregunta, $nom_pregun, $pre_dimen, $pre_pais)
{
    try{
        $con = connectDB_demos();
    $query = $con->query("UPDATE ce_preguntas
    SET ce_pregunta_nombre='$nom_pregun',
    ce_dimension_id='$pre_dimen',
    ce_pais_id_ce_pais='$pre_pais' WHERE id_ce_preguntas='$id_pregunta'");
    $con = null;
    return $query;

    }    
    catch(Exception $ex){

        exit("Excepción Capturada: ".$ex->getMessage());
  }

}

//SECCIÓN --ENCUESTA ESTUDIANTE

function select_estudiante($token_estudent)
{
    $con = connectDB_demos();
    $query = $con->query("SELECT ce_participantes_nombres AS nombres, ce_participantes_apellidos AS apellidos FROM
     ce_participantes
      WHERE ce_participanes_token = '$token_estudent';");
    $con = NULL;
    $row = $query->fetch(PDO::FETCH_ASSOC);
    return $row;
  
}

function guarda_respuesta($id_token, $fecha_inicio, $fecha_termino, $num1, $num2, $num3, $num4, $num5, $num6, $num7, $num8, $num9, $num10
    , $num11, $num12, $num13, $num14, $num15, $num16, $num17, $num18, $num19, $num20,
                          $num21, $num22, $num23, $num24, $num25, $num26, $num27, $num28, $num29, $num30,
                          $num31, $num32, $num33, $num34, $num35, $num36, $num37, $num38, $num39, $num40,
                          $num41, $num42, $num43, $num44, $num45, $num46, $num47)
{
    try{
        $anio = date("Y");
        $con = connectDB_demos();
        $query = $con->prepare("INSERT INTO ce_encuesta_resultado(ce_participantes_token_fk,fecha_inicio,fecha_termino,ce_p1,ce_p2,ce_p3,ce_p4,ce_p5,ce_p6,ce_p7,ce_p8,ce_p9,ce_p10,
        ce_p11,ce_p12,ce_p13,ce_p14,ce_p15,ce_p16,ce_p17,ce_p18,ce_p19,ce_p20,
        ce_p21,ce_p22,ce_p23,ce_p24,ce_p25,ce_p26,ce_p27,ce_p28,ce_p29,ce_p30,
        ce_p31,ce_p32,ce_p33,ce_p34,ce_p35,ce_p36,ce_p37,ce_p38,ce_p39,ce_p40,
        ce_p41,ce_p42,ce_p43,ce_p44,ce_p45,ce_p46,ce_p47) 
        VALUES(:id_token,:fecha_inicio,:fecha_termino,:num1,:num2,:num3,:num4,:num5,:num6,:num7,:num8,:num9,:num10,
        :num11,:num12,:num13,:num14,:num15,:num16,:num17,:num18,:num19,:num20,
        :num21,:num22,:num23,:num24,:num25,:num26,:num27,:num28,:num29,:num30,
        :num31,:num32,:num33,:num34,:num35,:num36,:num37,:num38,:num39,:num40,
        :num41,:num42,:num43,:num44,:num45,:num46,:num47,:anio)");
        $query->execute([":id_token"=>$id_token,":fecha_inicio"=>$fecha_inicio,":fecha_termino"=>$fecha_termino,":num1"=>$num1,":num2"=>$num2,":num3"=>$num3,":num4"=>$num4,":num5"=>$num5,":num6"=>$num6,":num7"=>$num7,":num8"=>$num8,":num9"=>$num9,":num10"=>$num10,":num11"=>$num11,":num12"=>$num12,":num13"=>$num13,":num14"=>$num14, ":num15"=>$num15,
            ":num16"=>$num16,":num17"=>$num17,":num18"=>$num18,":num19"=>$num19,":num20"=>$num20,":num21"=>$num21,":num22"=>$num22,":num23"=>$num23,":num24"=>$num24,":num25"=>$num25,":num26"=>$num26, ":num27"=>$num27,":num28"=>$num28,":num29"=>$num29,":num30"=>$num30, 
            ":num31"=>$num31,":num32"=>$num32,":num33"=>$num33,":num34"=>$num34,":num35"=>$num35,":num36"=>$num36,":num37"=>$num37,":num38"=>$num38,":num39"=>$num39,":num40"=>$num40,":num41"=>$num41, ":num42"=>$num42,":num43"=>$num43,":num44"=>$num44,":num45"=>$num45,
            ":num46"=>$num46,":num47"=>$num47,":anio"=>$anio
        ]);
        
        if($query != FALSE){
          $query_update = $con->prepare("UPDATE ce_participantes SET ce_estado_encuesta = 1 WHERE ce_participanes_token =:id_token");
            $query_update->execute([
                ":id_token"=>$id_token
            ]);
        }        
        $con = null;    
        return $query;

    }
    catch(Exception $ex){
        $excepcion = $ex->getMessage();
        ce_excepciones($excepcion);
        return FALSE;
    }   
   
}


//ejemplo
function suma_afectivo_coductual_cognitivo_final($establecimiento, $profesor)
{
    try {
        //if(isset($_SESSION["estudiante"]) ){
        $con = connectDB_demos();
        $query = $con->query("SELECT ce_participantes.ce_participantes_nombres AS 'nombre',
      ce_participantes.ce_fk_nivel AS nivel,
    ce_encuesta_resultado.ce_p1 AS p1,
    ce_encuesta_resultado.ce_p5 AS p5,
    ce_encuesta_resultado.ce_p7 AS p7,
    ce_encuesta_resultado.ce_p8 AS p8,
    ce_encuesta_resultado.ce_p12 AS p12,
    ce_encuesta_resultado.ce_p15 AS p15,
    ce_encuesta_resultado.ce_p19 AS p19,
    ce_encuesta_resultado.ce_p22 AS p22,
    ce_encuesta_resultado.ce_p27 AS p27,
    ce_encuesta_resultado.ce_p29 AS p29,
    #inicio de preguntas conductual
    ce_encuesta_resultado.ce_p3 AS p3,
    ce_encuesta_resultado.ce_p4 AS p4,
    ce_encuesta_resultado.ce_p9 AS p9,
    ce_encuesta_resultado.ce_p11 AS p11,
    ce_encuesta_resultado.ce_p16 AS p16,
    ce_encuesta_resultado.ce_p23 AS p23,
    ce_encuesta_resultado.ce_p28 AS p28,
    #Inicio de preguntas Cognitivo
    ce_encuesta_resultado.ce_p2 AS p2,
    ce_encuesta_resultado.ce_p6 AS p6,
    ce_encuesta_resultado.ce_p10 AS p10,
    ce_encuesta_resultado.ce_p13 AS p13,
    ce_encuesta_resultado.ce_p14 AS p14,
    ce_encuesta_resultado.ce_p17 AS p17,
    ce_encuesta_resultado.ce_p18 AS p18,
    ce_encuesta_resultado.ce_p20 AS p20,
    ce_encuesta_resultado.ce_p21 AS p21,
    ce_encuesta_resultado.ce_p24 AS p24,
    ce_encuesta_resultado.ce_p25 AS p25,
    ce_encuesta_resultado.ce_p26 AS p26,
    #Inicio de preguntas Familias
    ce_encuesta_resultado.ce_p30 AS p30,
    ce_encuesta_resultado.ce_p31 AS p31,
    ce_encuesta_resultado.ce_p32 AS p32,
   #Inicio de preguntas Apoyo Profesores
    ce_encuesta_resultado.ce_p33 AS p33,
    ce_encuesta_resultado.ce_p34 AS p34,
    ce_encuesta_resultado.ce_p35 AS p35,
    ce_encuesta_resultado.ce_p36 AS p36,
    ce_encuesta_resultado.ce_p37 AS p37,
    ce_encuesta_resultado.ce_p38 AS p38,
    ce_encuesta_resultado.ce_p39 AS p39,
    ce_encuesta_resultado.ce_p40 AS p40,
    #Inicio de preguntas Apoyo Pares
    ce_encuesta_resultado.ce_p41 AS p41,
    ce_encuesta_resultado.ce_p42 AS p42,
    ce_encuesta_resultado.ce_p43 AS p43,
    ce_encuesta_resultado.ce_p44 AS p44,
    ce_encuesta_resultado.ce_p45 AS p45,
    ce_encuesta_resultado.ce_p46 AS p46,
    ce_encuesta_resultado.ce_p47 AS p47

    FROM ce_encuesta_resultado
    INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
    WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND ce_participantes.ce_docente_id_ce_docente = '$profesor' ");
        $con = null;
        $lenght_consulta = $query->RowCount();     
       
            while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
                $nombre = $fila["nombre"];
                //afectivo
                $p1 = $fila["p1"];
                $p5 = $fila["p5"];
                $p7 = $fila["p7"];
                $p8 = $fila["p8"];
                $p12 = $fila["p12"];
                $p15 = $fila["p15"];
                $p19 = $fila["p19"];
                $p22 = $fila["p22"];
                $p27 = $fila["p27"];
                $p29 = $fila["p29"];
                //conductual
                $p3 = $fila["p3"];
                $p4 = $fila["p4"];
                $p9 = $fila["p9"];
                $p11 = $fila["p11"];
                $p16 = $fila["p16"];
                $p23 = $fila["p23"];
                $p28 = $fila["p28"];
                //Cognitivo
                $p2 = $fila["p2"];
                $p6 = $fila["p6"];
                $p10 = $fila["p10"];
                $p13 = $fila["p13"];
                $p14 = $fila["p14"];
                $p17 = $fila["p17"];
                $p18 = $fila["p18"];
                $p20 = $fila["p20"];
                $p21 = $fila["p21"];
                $p24 = $fila["p24"];
                $p25 = $fila["p25"];
                $p26 = $fila["p26"];
                //Apoyo Familia
                $p30 = $fila["p30"];
                $p31 = $fila["p31"];
                $p32 = $fila["p32"];
                //Apoyo Profesores
                $p33 = $fila["p33"];
                $p34 = $fila["p34"];
                $p35 = $fila["p35"];
                $p36 = $fila["p36"];
                $p37 = $fila["p37"];
                $p38 = $fila["p38"];
                $p39 = $fila["p39"];
                $p40 = $fila["p40"];
                //Apoyo Pares
                $p41 = $fila["p41"];
                $p42 = $fila["p42"];
                $p43 = $fila["p43"];
                $p44 = $fila["p44"];
                $p45 = $fila["p45"];
                $p46 = $fila["p46"];
                $p47 = $fila["p47"];

                $nivel = $fila["nivel"];

                $compromiso_escolar = $p1 + $p5 + $p7 + $p8 + $p12 + $p15 + $p19 + $p22 + $p27 + $p29 + $p3 + $p4 + $p9 + $p11 + $p16 + $p23 + $p28 + $p2 + $p6 + $p10 + $p13 + $p14 + $p17 + $p18 + $p20 + $p21 + $p24 + $p25 + $p26;

                //promedio afectivo
                $suma_afectivo = $p1 + $p5 + $p7 + $p8 + $p12 + $p15 + $p19 + $p22 + $p27 + $p29;


                //promedio conductual
                $suma_conductual = $p3 + $p4 + $p9 + $p11 + $p16 + $p23 + $p28;


                //promedio cognitivo
                $suma_cognitivo = $p2 + $p6 + $p10 + $p13 + $p14 + $p17 + $p18 + $p20 + $p21 + $p24 + $p25 + $p26;


                //promedio apoyo familiar
                $suma_apoyo_familiar = $p30 + $p31 + $p32;


                //promedio apoyo profesores
                $suma_apoyo_profesores = $p33 + $p34 + $p35 + $p36 + $p37 + $p38 + $p39 + $p40;


                //promedio apoyo profesores
                $suma_apoyo_pares = $p41 + $p42 + $p43 + $p44 + $p45 + $p46 + $p47;

                $factores_contextuales = $p30 + $p31 + $p32 + $p33 + $p34 + $p35 + $p36 + $p37 + $p38 + $p39 + $p40 + $p41 + $p42 + $p43 + $p44 + $p45 + $p46 + $p47;

                $sumas_ce_fc[] = ["compromiso_escolar" => $compromiso_escolar, "factores_contextuales" => $factores_contextuales, "suma_afectivo"=>$suma_afectivo,
            "suma_conductual"=>$suma_conductual,"suma_cognitivo"=> $suma_cognitivo, "nivel"=>$nivel];

            }
        

     
       $afectivo_emergente = 0;
       $afectivo_en_desarrollo = 0;
       $afectivo_satisfactorio = 0;    
       $afectivo_muy_desarrollado = 0;

       $conductual_emergente = 0;
       $conductual_en_desarrollo = 0;
       $conductual_satisfactorio = 0;    
       $conductual_muy_desarrollado = 0;

       $cognitivo_emergente = 0;
       $cognitivo_en_desarrollo = 0;
       $cognitivo_satisfactorio = 0;    
       $cognitivo_muy_desarrollado = 0;

      foreach($sumas_ce_fc AS $fila){
          //Básica
         if($fila["nivel"] = 1){

            if( $fila["suma_afectivo"] >= 10 AND  $fila["suma_afectivo"] <= 28 ){
                $afectivo_emergente++;
            }
            elseif(  $fila["suma_afectivo"] >= 29 AND  $fila["suma_afectivo"] <= 36){
                $afectivo_en_desarrollo++;
            }
            elseif( $fila["suma_afectivo"] >= 37 AND  $fila["suma_afectivo"] <= 43){
                $afectivo_satisfactorio++;
            }
            elseif( $fila["suma_afectivo"] >= 44 AND  $fila["suma_afectivo"] <= 50){
                $afectivo_muy_desarrollado++;
            }
     
            if( $fila["suma_conductual"] >= 7 AND  $fila["suma_conductual"] <= 17 ){
             $conductual_emergente++;
         }
         elseif(  $fila["suma_conductual"] >= 18 AND  $fila["suma_conductual"] <= 23){
             $conductual_en_desarrollo++;
         }
         elseif( $fila["suma_conductual"] >= 24 AND  $fila["suma_conductual"] <= 29){
             $conductual_satisfactorio++;
         }
         elseif( $fila["suma_conductual"] >= 29 AND  $fila["suma_conductual"] <= 35){
             $conductual_muy_desarrollado++;
         }
         
         if( $fila["suma_cognitivo"] >= 12 AND  $fila["suma_cognitivo"] <= 38 ){
             $cognitivo_emergente++;
         }
         elseif(  $fila["suma_cognitivo"] >= 39 AND  $fila["suma_cognitivo"] <= 44){
             $cognitivo_en_desarrollo++;
         }
         elseif( $fila["suma_cognitivo"] >= 45 AND  $fila["suma_cognitivo"] <= 53){
             $cognitivo_satisfactorio++;
         }
         elseif( $fila["suma_cognitivo"] >= 54 AND  $fila["suma_cognitivo"] <= 60){
             $cognitivo_muy_desarrollado++;
         }

         } 
         //MEDIA
         elseif($fila["nivel"] = 2){
            if( $fila["suma_afectivo"] >= 10 AND  $fila["suma_afectivo"] <= 26 ){
                $afectivo_emergente++;
            }
            elseif(  $fila["suma_afectivo"] >= 27 AND  $fila["suma_afectivo"] <= 34){
                $afectivo_en_desarrollo++;
            }
            elseif( $fila["suma_afectivo"] >= 35 AND  $fila["suma_afectivo"] <= 42){
                $afectivo_satisfactorio++;
            }
            elseif( $fila["suma_afectivo"] >= 43 AND  $fila["suma_afectivo"] <= 50){
                $afectivo_muy_desarrollado++;
            }
     
            if( $fila["suma_conductual"] >= 7 AND  $fila["suma_conductual"] <= 21 ){
             $conductual_emergente++;
         }
         elseif(  $fila["suma_conductual"] >= 22 AND  $fila["suma_conductual"] <= 27){
             $conductual_en_desarrollo++;
         }
         elseif( $fila["suma_conductual"] >= 28 AND  $fila["suma_conductual"] <= 31){
             $conductual_satisfactorio++;
         }
         elseif( $fila["suma_conductual"] >= 32 AND  $fila["suma_conductual"] <= 35){
             $conductual_muy_desarrollado++;
         }
         
         if( $fila["suma_cognitivo"] >= 12 AND  $fila["suma_cognitivo"] <= 31 ){
             $cognitivo_emergente++;
         }
         elseif(  $fila["suma_cognitivo"] >= 32 AND  $fila["suma_cognitivo"] <= 42){
             $cognitivo_en_desarrollo++;
         }
         elseif( $fila["suma_cognitivo"] >= 43 AND  $fila["suma_cognitivo"] <= 50){
             $cognitivo_satisfactorio++;
         }
         elseif( $fila["suma_cognitivo"] >= 51 AND  $fila["suma_cognitivo"] <= 60){
             $cognitivo_muy_desarrollado++;
         }
     
             
         }
      
    
          

      }

      $porcentaje_afectivo_emergente =  round(($afectivo_emergente*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
      $porcentaje_afectivo_en_desarrollo =  round(($afectivo_en_desarrollo*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
      $porcentaje_afectivo_satisfactorio =  round(($afectivo_satisfactorio*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
      $porcentaje_afectivo_muy_desarrollado =  round(($afectivo_muy_desarrollado*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);

      $porcentaje_conductual_emergente =  round(($conductual_emergente*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
      $porcentaje_conductual_en_desarrollo =  round(($conductual_en_desarrollo*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
      $porcentaje_conductual_satisfactorio =  round(($conductual_satisfactorio*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
      $porcentaje_conductual_muy_desarrollado =  round(($conductual_muy_desarrollado*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);

      $porcentaje_cognitivo_emergente =  round(($cognitivo_emergente*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
      $porcentaje_cognitivo_en_desarrollo =  round(($cognitivo_en_desarrollo*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
      $porcentaje_cognitivo_satisfactorio =  round(($cognitivo_satisfactorio*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
      $porcentaje_cognitivo_muy_desarrollado =  round(($cognitivo_muy_desarrollado*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);

      $valores_finales = array("afectivo_emergente"=>$afectivo_emergente, "afectivo_en_desarrollo"=>$afectivo_en_desarrollo, "afectivo_satisfactorio"=>$afectivo_satisfactorio, "afectivo_muy_desarrollado"=>$afectivo_muy_desarrollado,
      "conductual_emergente"=>$conductual_emergente, "conductual_en_desarrollo"=>$conductual_en_desarrollo, "conductual_satisfactorio"=>$conductual_satisfactorio, "conductual_muy_desarrollado"=>$conductual_muy_desarrollado,
      "cognitivo_emergente"=>$cognitivo_emergente, "cognitivo_en_desarrollo"=>$cognitivo_en_desarrollo, "cognitivo_satisfactorio"=>$cognitivo_satisfactorio, "cognitivo_muy_desarrollado"=>$cognitivo_muy_desarrollado,
      "porcentaje_afectivo_emergente"=>$porcentaje_afectivo_emergente,"porcentaje_afectivo_en_desarrollo"=>$porcentaje_afectivo_en_desarrollo,"porcentaje_afectivo_satisfactorio"=>$porcentaje_afectivo_satisfactorio,"porcentaje_afectivo_muy_desarrollado"=>$porcentaje_afectivo_muy_desarrollado,
      "porcentaje_conductual_emergente"=>$porcentaje_conductual_emergente,"porcentaje_conductual_en_desarrollo"=>$porcentaje_conductual_en_desarrollo,"porcentaje_conductual_satisfactorio"=>$porcentaje_conductual_satisfactorio,"porcentaje_conductual_muy_desarrollado"=>$porcentaje_conductual_muy_desarrollado,
      "porcentaje_cognitivo_emergente"=>$porcentaje_cognitivo_emergente,"porcentaje_cognitivo_en_desarrollo"=>$porcentaje_cognitivo_en_desarrollo,"porcentaje_cognitivo_satisfactorio"=>$porcentaje_cognitivo_satisfactorio,"porcentaje_cognitivo_muy_desarrollado"=>$porcentaje_cognitivo_muy_desarrollado); 

return  $valores_finales;
    
    } catch (Exception $e) {
        echo 'Mensage: ' . $e->getMessage();
    }


}

function fc_suma_familiar_pares_profesores($establecimiento,$profesor){
    try{
        $con = connectDB_demos();
        $query = $con->query(" SELECT ce_participantes.ce_participantes_nombres AS 'nombre',
        ce_participantes.ce_fk_nivel AS nivel, 
      #Inicio de preguntas Familias
      ce_encuesta_resultado.ce_p30 AS p30,
      ce_encuesta_resultado.ce_p31 AS p31,
      ce_encuesta_resultado.ce_p32 AS p32,
     #Inicio de preguntas Apoyo Profesores
      ce_encuesta_resultado.ce_p33 AS p33,
      ce_encuesta_resultado.ce_p34 AS p34,
      ce_encuesta_resultado.ce_p35 AS p35,
      ce_encuesta_resultado.ce_p36 AS p36,
      ce_encuesta_resultado.ce_p37 AS p37,
      ce_encuesta_resultado.ce_p38 AS p38,
      ce_encuesta_resultado.ce_p39 AS p39,
      ce_encuesta_resultado.ce_p40 AS p40,
      #Inicio de preguntas Apoyo Pares
      ce_encuesta_resultado.ce_p41 AS p41,
      ce_encuesta_resultado.ce_p42 AS p42,
      ce_encuesta_resultado.ce_p43 AS p43,
      ce_encuesta_resultado.ce_p44 AS p44,
      ce_encuesta_resultado.ce_p45 AS p45,
      ce_encuesta_resultado.ce_p46 AS p46,
      ce_encuesta_resultado.ce_p47 AS p47  
      FROM ce_encuesta_resultado
      INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
      WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND ce_participantes.ce_docente_id_ce_docente = '$profesor' ");

$con = null;
$lenght_consulta = $query->RowCount();
for ($i = 0; $i < $lenght_consulta; $i++) {
    while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
        $nombre = $fila["nombre"];

        //Apoyo Familia
        $p30 = $fila["p30"];
        $p31 = $fila["p31"];
        $p32 = $fila["p32"];
        //Apoyo Profesores
        $p33 = $fila["p33"];
        $p34 = $fila["p34"];
        $p35 = $fila["p35"];
        $p36 = $fila["p36"];
        $p37 = $fila["p37"];
        $p38 = $fila["p38"];
        $p39 = $fila["p39"];
        $p40 = $fila["p40"];
        //Apoyo Pares
        $p41 = $fila["p41"];
        $p42 = $fila["p42"];
        $p43 = $fila["p43"];
        $p44 = $fila["p44"];
        $p45 = $fila["p45"];
        $p46 = $fila["p46"];
        $p47 = $fila["p47"];

        $nivel = $fila["nivel"];

        //$compromiso_escolar = $p1 + $p5 + $p7 + $p8 + $p12 + $p15 + $p19 + $p22 + $p27 + $p29 + $p3 + $p4 + $p9 + $p11 + $p16 + $p23 + $p28 + $p2 + $p6 + $p10 + $p13 + $p14 + $p17 + $p18 + $p20 + $p21 + $p24 + $p25 + $p26;

        //promedio apoyo familiar
        $suma_apoyo_familiar = $p30 + $p31 + $p32;


        //promedio apoyo profesores
        $suma_apoyo_profesores = $p33 + $p34 + $p35 + $p36 + $p37 + $p38 + $p39 + $p40;


        //promedio apoyo profesores
        $suma_apoyo_pares = $p41 + $p42 + $p43 + $p44 + $p45 + $p46 + $p47;

        $factores_contextuales = $p30 + $p31 + $p32 + $p33 + $p34 + $p35 + $p36 + $p37 + $p38 + $p39 + $p40 + $p41 + $p42 + $p43 + $p44 + $p45 + $p46 + $p47;

        $sumas_fc[] = ["factores_contextuales" => $factores_contextuales, "suma_apoyo_familiar"=>$suma_apoyo_familiar,
    "suma_apoyo_profesores"=>$suma_apoyo_profesores,"suma_apoyo_pares"=> $suma_apoyo_pares];

    }
}
$apoyo_familiar_emergente = 0;
$apoyo_familiar_en_desarrollo = 0;
$apoyo_familiar_satisfactorio = 0;    
$apoyo_familiar_muy_desarrollado = 0;

$apoyo_profesores_emergente = 0;
$apoyo_profesores_en_desarrollo = 0;
$apoyo_profesores_satisfactorio = 0;    
$apoyo_profesores_muy_desarrollado = 0;

$apoyo_pares_emergente = 0;
$apoyo_pares_en_desarrollo = 0;
$apoyo_pares_satisfactorio = 0;    
$apoyo_pares_muy_desarrollado = 0;

foreach($sumas_fc AS $fila){

     if( $fila["suma_apoyo_familiar"] >= 3 AND  $fila["suma_apoyo_familiar"] <= 5 ){
         $apoyo_familiar_emergente++;
     }
     elseif(  $fila["suma_apoyo_familiar"] >= 6 AND  $fila["suma_apoyo_familiar"] <= 8){
         $apoyo_familiar_en_desarrollo++;
     }
     elseif( $fila["suma_apoyo_familiar"] >= 9 AND  $fila["suma_apoyo_familiar"] <= 11){
         $apoyo_familiar_satisfactorio++;
     }
     elseif( $fila["suma_apoyo_familiar"] >= 12 AND  $fila["suma_apoyo_familiar"] <= 15){
         $apoyo_familiar_muy_desarrollado++;
     }

     if( $fila["suma_apoyo_profesores"] >= 8 AND  $fila["suma_apoyo_profesores"] <= 21 ){
      $apoyo_profesores_emergente++;
  }
  elseif(  $fila["suma_apoyo_profesores"] >= 22 AND  $fila["suma_apoyo_profesores"] <= 29){
      $apoyo_profesores_en_desarrollo++;
  }
  elseif( $fila["suma_apoyo_profesores"] >= 30 AND  $fila["suma_apoyo_profesores"] <= 35){
      $apoyo_profesores_satisfactorio++;
  }
  elseif( $fila["suma_apoyo_profesores"] >= 36 AND  $fila["suma_apoyo_profesores"] <= 40){
      $apoyo_profesores_muy_desarrollado++;
  }
  
  if( $fila["suma_apoyo_pares"] >= 7 AND  $fila["suma_apoyo_pares"] <= 16 ){
      $apoyo_pares_emergente++;
  }
  elseif(  $fila["suma_apoyo_pares"] >= 17 AND  $fila["suma_apoyo_pares"] <= 23){
      $apoyo_pares_en_desarrollo++;
  }
  elseif( $fila["suma_apoyo_pares"] >= 24 AND  $fila["suma_apoyo_pares"] <= 29){
      $apoyo_pares_satisfactorio++;
  }
  elseif( $fila["suma_apoyo_pares"] >= 30 AND  $fila["suma_apoyo_pares"] <= 35){
      $apoyo_pares_muy_desarrollado++;
  }
  

}


$porcentaje_familiar_emergente =  round(($apoyo_familiar_emergente*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
$porcentaje_familiar_en_desarrollo =  round(($apoyo_familiar_en_desarrollo*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
$porcentaje_familiar_satisfactorio =  round(($apoyo_familiar_satisfactorio*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
$porcentaje_familiar_muy_desarrollado =  round(($apoyo_familiar_muy_desarrollado*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);

$porcentaje_profesores_emergente=  round(($apoyo_profesores_emergente*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
$porcentaje_profesores_en_desarrollo =  round(($apoyo_profesores_en_desarrollo*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
$porcentaje_profesores_satisfactorio =  round(($apoyo_profesores_satisfactorio*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
$porcentaje_profesores_muy_desarrollado =  round(($apoyo_profesores_muy_desarrollado*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);

$porcentaje_pares_emergente=  round(($apoyo_pares_emergente*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
$porcentaje_pares_en_desarrollo =  round(($apoyo_pares_en_desarrollo*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
$porcentaje_pares_satisfactorio =  round(($apoyo_pares_satisfactorio*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);
$porcentaje_pares_muy_desarrollado =  round(($apoyo_pares_muy_desarrollado*100)/$lenght_consulta, 1, PHP_ROUND_HALF_UP);

$valores_finales_fc = array("apoyo_familiar_emergente"=>$apoyo_familiar_emergente, "apoyo_familiar_en_desarrollo"=>$apoyo_familiar_en_desarrollo, "apoyo_familiar_satisfactorio"=>$apoyo_familiar_satisfactorio, "apoyo_familiar_muy_desarrollado"=>$apoyo_familiar_muy_desarrollado,
"apoyo_profesores_emergente"=>$apoyo_profesores_emergente, "apoyo_profesores_en_desarrollo"=>$apoyo_profesores_en_desarrollo, "apoyo_profesores_satisfactorio"=>$apoyo_profesores_satisfactorio, "apoyo_profesores_muy_desarrollado"=>$apoyo_profesores_muy_desarrollado,
"apoyo_pares_emergente"=>$apoyo_pares_emergente, "apoyo_pares_en_desarrollo"=>$apoyo_pares_en_desarrollo, "apoyo_pares_satisfactorio"=>$apoyo_pares_satisfactorio, "apoyo_pares_muy_desarrollado"=>$apoyo_pares_muy_desarrollado,
"porcentaje_familiar_emergente"=>$porcentaje_familiar_emergente, "porcentaje_familiar_en_desarrollo"=>$porcentaje_familiar_en_desarrollo, "porcentaje_familiar_satisfactorio"=>$porcentaje_familiar_satisfactorio, "porcentaje_familiar_muy_desarrollado"=>$porcentaje_familiar_muy_desarrollado,
"porcentaje_profesores_emergente"=>$porcentaje_profesores_emergente, "porcentaje_profesores_en_desarrollo"=>$porcentaje_profesores_en_desarrollo, "porcentaje_profesores_satisfactorio"=>$porcentaje_profesores_satisfactorio, "porcentaje_profesores_muy_desarrollado"=>$porcentaje_profesores_muy_desarrollado,
"porcentaje_pares_emergente"=>$porcentaje_pares_emergente, "porcentaje_pares_en_desarrollo"=>$porcentaje_pares_en_desarrollo, "porcentaje_pares_satisfactorio"=>$porcentaje_pares_satisfactorio, "porcentaje_pares_muy_desarrollado"=>$porcentaje_pares_muy_desarrollado); 

   return $valores_finales_fc;

    }catch(Exception $ex){
        exit('Excepción Capturada: '. $ex->getMessage());

    }

}

function recorre_fc_ce($array_fc_ce)
{
    try {
        foreach ($array_fc_ce as $filas) {
            $resultado_ce_fc = "[" . $filas["factores_contextuales"] . "," . $filas["compromiso_escolar"] . "],";
            echo $resultado_ce_fc;
        }

    } catch (Exception $ex) {
        exit('Excepción Capturada: '. $ex->getMessage());

    }
}

function suma_afectivo_coductual_cognitivo($establecimiento, $curso)
{
    try {
        //if(isset($_SESSION["estudiante"]) ){
        $con = connectDB_demos();
        $query = $con->query("SELECT ce_participantes.ce_participantes_nombres AS 'nombre',
    ce_encuesta_resultado.ce_p1 AS p1,
    ce_encuesta_resultado.ce_p5 AS p5,
    ce_encuesta_resultado.ce_p7 AS p7,
    ce_encuesta_resultado.ce_p8 AS p8,
    ce_encuesta_resultado.ce_p12 AS p12,
    ce_encuesta_resultado.ce_p15 AS p15,
    ce_encuesta_resultado.ce_p19 AS p19,
    ce_encuesta_resultado.ce_p22 AS p22,
    ce_encuesta_resultado.ce_p27 AS p27,
    ce_encuesta_resultado.ce_p29 AS p29,
    #inicio de preguntas conductual
    ce_encuesta_resultado.ce_p3 AS p3,
    ce_encuesta_resultado.ce_p4 AS p4,
    ce_encuesta_resultado.ce_p9 AS p9,
    ce_encuesta_resultado.ce_p11 AS p11,
    ce_encuesta_resultado.ce_p16 AS p16,
    ce_encuesta_resultado.ce_p23 AS p23,
    ce_encuesta_resultado.ce_p28 AS p28,
    #Inicio de preguntas Cognitivo
    ce_encuesta_resultado.ce_p2 AS p2,
    ce_encuesta_resultado.ce_p6 AS p6,
    ce_encuesta_resultado.ce_p10 AS p10,
    ce_encuesta_resultado.ce_p13 AS p13,
    ce_encuesta_resultado.ce_p14 AS p14,
    ce_encuesta_resultado.ce_p17 AS p17,
    ce_encuesta_resultado.ce_p18 AS p18,
    ce_encuesta_resultado.ce_p20 AS p20,
    ce_encuesta_resultado.ce_p21 AS p21,
    ce_encuesta_resultado.ce_p24 AS p24,
    ce_encuesta_resultado.ce_p25 AS p25,
    ce_encuesta_resultado.ce_p26 AS p26,
    #Inicio de preguntas Familias
    ce_encuesta_resultado.ce_p30 AS p30,
    ce_encuesta_resultado.ce_p31 AS p31,
    ce_encuesta_resultado.ce_p32 AS p32,
   #Inicio de preguntas Apoyo Profesores
    ce_encuesta_resultado.ce_p33 AS p33,
    ce_encuesta_resultado.ce_p34 AS p34,
    ce_encuesta_resultado.ce_p35 AS p35,
    ce_encuesta_resultado.ce_p36 AS p36,
    ce_encuesta_resultado.ce_p37 AS p37,
    ce_encuesta_resultado.ce_p38 AS p38,
    ce_encuesta_resultado.ce_p39 AS p39,
    ce_encuesta_resultado.ce_p40 AS p40,
    #Inicio de preguntas Apoyo Pares
    ce_encuesta_resultado.ce_p41 AS p41,
    ce_encuesta_resultado.ce_p42 AS p42,
    ce_encuesta_resultado.ce_p43 AS p43,
    ce_encuesta_resultado.ce_p44 AS p44,
    ce_encuesta_resultado.ce_p45 AS p45,
    ce_encuesta_resultado.ce_p46 AS p46,
    ce_encuesta_resultado.ce_p47 AS p47

    FROM ce_encuesta_resultado
    INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
    WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND ce_participantes.ce_curso_id_ce_curso = '$curso' ");
        $con = null;
        $lenght_consulta = $query->RowCount();
        for ($i = 0; $i < $lenght_consulta; $i++) {
            while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
                $nombre = $fila["nombre"];
                $p1 = $fila["p1"];
                $p5 = $fila["p5"];
                $p7 = $fila["p7"];
                $p8 = $fila["p8"];
                $p12 = $fila["p12"];
                $p15 = $fila["p15"];
                $p19 = $fila["p19"];
                $p22 = $fila["p22"];
                $p27 = $fila["p27"];
                $p29 = $fila["p29"];
                //conductual
                $p3 = $fila["p3"];
                $p4 = $fila["p4"];
                $p9 = $fila["p9"];
                $p11 = $fila["p11"];
                $p16 = $fila["p16"];
                $p23 = $fila["p23"];
                $p28 = $fila["p28"];
                //Cognitivo
                $p2 = $fila["p2"];
                $p6 = $fila["p6"];
                $p10 = $fila["p10"];
                $p13 = $fila["p13"];
                $p14 = $fila["p14"];
                $p17 = $fila["p17"];
                $p18 = $fila["p18"];
                $p20 = $fila["p20"];
                $p21 = $fila["p21"];
                $p24 = $fila["p24"];
                $p25 = $fila["p25"];
                $p26 = $fila["p26"];
                //Apoyo Familia
                $p30 = $fila["p30"];
                $p31 = $fila["p31"];
                $p32 = $fila["p32"];
                //Apoyo Profesores
                $p33 = $fila["p33"];
                $p34 = $fila["p34"];
                $p35 = $fila["p35"];
                $p36 = $fila["p36"];
                $p37 = $fila["p37"];
                $p38 = $fila["p38"];
                $p39 = $fila["p39"];
                $p40 = $fila["p40"];
                //Apoyo Pares
                $p41 = $fila["p41"];
                $p42 = $fila["p42"];
                $p43 = $fila["p43"];
                $p44 = $fila["p44"];
                $p45 = $fila["p45"];
                $p46 = $fila["p46"];
                $p47 = $fila["p47"];

                //promedio afectivo
                $suma_afectivo = $p1 + $p5 + $p7 + $p8 + $p12 + $p15 + $p19 + $p22 + $p27 + $p29;
                $promedio_afectivo = $suma_afectivo / 10;

                //promedio conductual
                $suma_conductual = $p3 + $p4 + $p9 + $p11 + $p16 + $p23 + $p28;
                $promedio_conductual = $suma_conductual / 7;

                //promedio cognitivo
                $suma_cognitivo = $p2 + $p6 + $p10 + $p13 + $p14 + $p17 + $p18 + $p20 + $p21 + $p24 + $p25 + $p26;
                $promedio_cognitivo = $suma_cognitivo / 12;

                //promedio apoyo familiar
                $suma_apoyo_familiar = $p30 + $p31 + $p32;
                $promedio_apoyo_familiar = $suma_apoyo_familiar / 3;

                //promedio apoyo profesores
                $suma_apoyo_profesores = $p33 + $p34 + $p35 + $p36 + $p37 + $p38 + $p39 + $p40;
                $promedio_apoyo_profesores = $suma_apoyo_profesores / 8;

                //promedio apoyo profesores
                $suma_apoyo_pares = $p41 + $p42 + $p43 + $p44 + $p45 + $p46 + $p47;
                $promedio_apoyo_pares = $suma_apoyo_pares / 7;

                $premedios_acc[] = ["promedio_afectivo" => $promedio_afectivo, "nombre" => $fila["nombre"], "promedio_conductual" => $promedio_conductual
                    , "promedio_cognitivo" => $promedio_cognitivo, "promedio_apoyo_familiar" => $promedio_apoyo_familiar, "promedio_apoyo_profesores" => $promedio_apoyo_profesores
                    , "promedio_apoyo_pares" => $promedio_apoyo_pares];


            }
        }
        return $premedios_acc;
        /* }else{
    echo "No hay sesión;";

}*/

    } catch (Exception $e) {
        echo 'Mensage: ' . $e->getMessage();
    }


}




function respuesta_no_respuesta($id_establecimiento, $id_docente)
{
    try {
        $con = connectDB_demos();
        $query = $con->query(" SELECT COUNT(id_ce_participantes) AS respondidas
        FROM ce_participantes
        WHERE ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND
        ce_docente_id_ce_docente = '$id_docente' AND
        ce_estado_encuesta = 1 
         UNION  ALL
         #No respondidas
        SELECT COUNT(id_ce_participantes)  AS respondidas
        FROM ce_participantes
        WHERE ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND
        ce_docente_id_ce_docente = '$id_docente' AND
         ce_estado_encuesta = 0");

        return $query;

    } catch (Exception $e) {

        echo 'Excepción Capturada: ' . $e->getMessage();
    }
}


function RespondidasEstDocente($id_establecimiento, $id_docente) {
    try {
        $con = connectDB_demos();
        $query = $con->query(" SELECT COUNT(id_ce_participantes) AS respondidas
        FROM ce_participantes
        WHERE ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND
        ce_docente_id_ce_docente = '$id_docente' AND
        ce_estado_encuesta = 1 
        ");

        return $query;

    } catch (Exception $e) {

        echo 'Excepción Capturada: ' . $e->getMessage();
    }
}

function NoRespondidasEstDocente($id_establecimiento, $id_docente) {
    try {
        $con = connectDB_demos();
        $query = $con->query(" SELECT COUNT(id_ce_participantes) AS no_respondidas
        FROM ce_participantes
        WHERE ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND
        ce_docente_id_ce_docente = '$id_docente' AND
        ce_estado_encuesta = 0 
        ");

        return $query;

    } catch (Exception $e) {

        echo 'Excepción Capturada: ' . $e->getMessage();
    }
}

function respuesta_no_respuesta_establecimiento($id_establecimiento) {
    try {
        $con = connectDB_demos();
        $query = $con->prepare(" SELECT COUNT(id_ce_participantes) AS respondidas
        FROM ce_participantes
        WHERE ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND     
        ce_estado_encuesta = 1 
         UNION  ALL
         #No respondidas
        SELECT COUNT(id_ce_participantes)  AS respondidas
        FROM ce_participantes
        WHERE ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND
       
         ce_estado_encuesta = 0");

        $query->execute([]);

        return $query;
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }
}

function RespondidasEstablecimiento($id_establecimiento) {
    try {
        $con = connectDB_demos();
        $query = $con->prepare(" SELECT COUNT(id_ce_participantes) AS respondidas
        FROM ce_participantes
        WHERE ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND     
        ce_estado_encuesta = 1 
        ");

        $query->execute([]);

        return $query;
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }
}

function NoRespondidasEstablecimiento($id_establecimiento) {
    try {
        $con = connectDB_demos();
        $query = $con->prepare(" SELECT COUNT(id_ce_participantes) AS no_respondidas
        FROM ce_participantes
        WHERE ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' AND     
        ce_estado_encuesta = 0 
        ");

        $query->execute([]);

        return $query;
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }
}

function NumeroEstudiantes($id_establecimiento) {
    try {
        $con = connectDB_demos();
        $query = $con->prepare(
            "SELECT COUNT(id_ce_participantes) AS estudiantes
            FROM ce_participantes WHERE ce_establecimiento_id_ce_establecimiento = '$id_establecimiento'
            ");

        $query->execute([]);

        return $query;
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }
}

function suma_afectivo_punto_corte($puntaje){
    $afectivo_emergente = 0;
    $afectivo_en_desarrollo = 0;
    $afectivo_satisfactorio = 0;function suma_afectivo_punto_corte($puntaje){
    $afectivo_emergente = 0;
    $afectivo_en_desarrollo = 0;
    $afectivo_satisfactorio = 0;
    $afectivo_muy_desarrollado = 0;

    if($puntaje >= 10 AND $puntaje < 26 ){
        $afectivo_emergente++;
    }
    elseif($puntaje >= 27 AND $puntaje < 34){
        $afectivo_en_desarrollo++;
    }
    elseif($puntaje >= 35 AND $puntaje < 42){
        $afectivo_satisfactorio++;
    }
    elseif($puntaje >= 43 AND $puntaje < 50){
        $afectivo_muy_desarrollado++;
    }

    echo ''
    . '<td>' . round($suma_1_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
    . '<td>' . round($suma_2_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
    . '<td>' . round($suma_3_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
    . '<td>' . round($suma_4_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
    . '<td>' . round($suma_5_p1, 1, PHP_ROUND_HALF_UP) . '</td>';
    
}
    $afectivo_muy_desarrollado = 0;

    if($puntaje >= 10 AND $puntaje < 26 ){
        $afectivo_emergente++;
    }
    elseif($puntaje >= 27 AND $puntaje < 34){
        $afectivo_en_desarrollo++;
    }
    elseif($puntaje >= 35 AND $puntaje < 42){
        $afectivo_satisfactorio++;
    }
    elseif($puntaje >= 43 AND $puntaje < 50){
        $afectivo_muy_desarrollado++;
    }

    echo ''
    . '<td>' . round($suma_1_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
    . '<td>' . round($suma_2_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
    . '<td>' . round($suma_3_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
    . '<td>' . round($suma_4_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
    . '<td>' . round($suma_5_p1, 1, PHP_ROUND_HALF_UP) . '</td>';
    
}

function preguntas_compromiso_escolar($nivel = "2" , $pais = "1"){
    try{
        $con = connectDB_demos();
        $query = $con->prepare("SELECT ce_pregunta_nombre FROM ce_preguntas WHERE ce_nivel=:nivel AND ce_pais_id_ce_pais=:pais ORDER BY ce_orden ASC");
        $query->execute(array(":nivel"=>$nivel, 
        ":pais"=>$pais
    ));
    $con = null;
    $contador = 0;
        foreach($query AS $fila){
            $contador++;
            $preguntas["p0$contador"] = $fila["ce_pregunta_nombre"];
        }
        return $preguntas;

    }catch(Exception $ex){
        exit("Excepcion Capturada". $ex->getMessage());
    }

}

function preguntas_compromiso_escolar_encuesta($nivel, $pais){
    try{
        $con = connectDB_demos();
        $query = $con->prepare("SELECT ce_pregunta_nombre FROM ce_preguntas WHERE ce_nivel=:nivel AND ce_pais_id_ce_pais=:pais ORDER BY ce_orden ASC");
        $query->execute(array(":nivel"=>$nivel, 
        ":pais"=>$pais
    ));
    $con = null;
    $contador = 0;
        foreach($query AS $fila){
            $contador++;
            $preguntas["p0$contador"] = $fila["ce_pregunta_nombre"];
        }
        return $preguntas;

    }catch(Exception $ex){
        exit("Excepcion Capturada". $ex->getMessage());
    }

}

function dimension_afectivo_curso_copia($establecimiento, $profesor)
{
    try {
        #pregunta número 1
        $suma_1_p1 = 0;
        $suma_2_p1 = 0;
        $suma_3_p1 = 0;
        $suma_4_p1 = 0;
        $suma_5_p1 = 0;
        #pregunta número 5
        $suma_1_p5 = 0;
        $suma_2_p5 = 0;
        $suma_3_p5 = 0;
        $suma_4_p5 = 0;
        $suma_5_p5 = 0;
        #pregunta número 7
        $suma_1_p7 = 0;
        $suma_2_p7 = 0;
        $suma_3_p7 = 0;
        $suma_4_p7 = 0;
        $suma_5_p7 = 0;
        #pregunta número 8
        $suma_1_p8 = 0;
        $suma_2_p8 = 0;
        $suma_3_p8 = 0;
        $suma_4_p8 = 0;
        $suma_5_p8 = 0;
        #pregunta número 12
        $suma_1_p12 = 0;
        $suma_2_p12 = 0;
        $suma_3_p12 = 0;
        $suma_4_p12 = 0;
        $suma_5_p12 = 0;
        #pregunta número 15
        $suma_1_p15 = 0;
        $suma_2_p15 = 0;
        $suma_3_p15 = 0;
        $suma_4_p15 = 0;
        $suma_5_p15 = 0;
        #pregunta número 19
        $suma_1_p19 = 0;
        $suma_2_p19 = 0;
        $suma_3_p19 = 0;
        $suma_4_p19 = 0;
        $suma_5_p19 = 0;
        #pregunta número 22
        $suma_1_p22 = 0;
        $suma_2_p22 = 0;
        $suma_3_p22 = 0;
        $suma_4_p22 = 0;
        $suma_5_p22 = 0;
        #pregunta número 27
        $suma_1_p27 = 0;
        $suma_2_p27 = 0;
        $suma_3_p27 = 0;
        $suma_4_p27 = 0;
        $suma_5_p27 = 0;
        #pregunta número 29
        $suma_1_p29 = 0;
        $suma_2_p29 = 0;
        $suma_3_p29 = 0;
        $suma_4_p29 = 0;
        $suma_5_p29 = 0;

        $con = connectDB_demos();
        $query = $con->query("SELECT ce_encuesta_resultado.ce_p1 AS p1,
        ce_encuesta_resultado.ce_p5 AS p5,
        ce_encuesta_resultado.ce_p7 AS p7,
        ce_encuesta_resultado.ce_p8 AS p8,
        ce_encuesta_resultado.ce_p12 AS p12,
        ce_encuesta_resultado.ce_p15 AS p15,
        ce_encuesta_resultado.ce_p19 AS p19,
        ce_encuesta_resultado.ce_p22 AS p22,
        ce_encuesta_resultado.ce_p27 AS p27,
        ce_encuesta_resultado.ce_p29 AS p29
       
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND 
        ce_participantes.ce_docente_id_ce_docente = '$profesor' AND ce_participantes.ce_estado_encuesta = 1");
        $cantidad = $query->RowCount();
        $con = null;

        while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
            #pregunta número 1
            $p1 = $fila["p1"];
            if ($p1 == 1) {
                $suma_1_p1++;
            } elseif ($p1 == 2) {
                $suma_2_p1++;
            } elseif ($p1 == 3) {
                $suma_3_p1++;
            } elseif ($p1 == 4) {
                $suma_4_p1++;
            } elseif ($p1 == 5) {
                $suma_5_p1++;
            }
            #pregunta númerop 5
            $p5 = $fila["p5"];
            if ($p5 == 1) {
                $suma_1_p5++;
            } elseif ($p5 == 2) {
                $suma_2_p5++;
            } elseif ($p5 == 3) {
                $suma_3_p5++;
            } elseif ($p5 == 4) {
                $suma_4_p5++;
            } elseif ($p5 == 5) {
                $suma_5_p5++;
            }
            #pregunta númerop 7
            $p7 = $fila["p7"];
            if ($p7 == 1) {
                $suma_1_p7++;
            } elseif ($p7 == 2) {
                $suma_2_p7++;
            } elseif ($p7 == 3) {
                $suma_3_p7++;
            } elseif ($p7 == 4) {
                $suma_4_p7++;
            } elseif ($p7 == 5) {
                $suma_5_p7++;
            }
            #pregunta númerop 8
            $p8 = $fila["p8"];
            if ($p8 == 1) {
                $suma_1_p8++;
            } elseif ($p8 == 2) {
                $suma_2_p8++;
            } elseif ($p8 == 3) {
                $suma_3_p8++;
            } elseif ($p8 == 4) {
                $suma_4_p8++;
            } elseif ($p8 == 5) {
                $suma_5_p8++;
            }
            #pregunta númerop 12
            $p12 = $fila["p12"];
            if ($p12 == 1) {
                $suma_1_p12++;
            } elseif ($p12 == 2) {
                $suma_2_p12++;
            } elseif ($p12 == 3) {
                $suma_3_p12++;
            } elseif ($p12 == 4) {
                $suma_4_p12++;
            } elseif ($p12 == 5) {
                $suma_5_p12++;
            }
            #pregunta número 15
            $p15 = $fila["p15"];
            if ($p15 == 1) {
                $suma_1_p15++;
            } elseif ($p15 == 2) {
                $suma_2_p15++;
            } elseif ($p15 == 3) {
                $suma_3_p15++;
            } elseif ($p15 == 4) {
                $suma_4_p15++;
            } elseif ($p15 == 5) {
                $suma_5_p15++;
            }
            #pregunta númerop 19
            $p19 = $fila["p19"];
            if ($p19 == 1) {
                $suma_1_p19++;
            } elseif ($p19 == 2) {
                $suma_2_p19++;
            } elseif ($p19 == 3) {
                $suma_3_p19++;
            } elseif ($p19 == 4) {
                $suma_4_p19++;
            } elseif ($p19 == 5) {
                $suma_5_p19++;
            }
            #pregunta númerop 22
            $p22 = $fila["p22"];
            if ($p22 == 1) {
                $suma_1_p22++;
            } elseif ($p22 == 2) {
                $suma_2_p22++;
            } elseif ($p22 == 3) {
                $suma_3_p22++;
            } elseif ($p22 == 4) {
                $suma_4_p22++;
            } elseif ($p22 == 5) {
                $suma_5_p22++;
            }
            #pregunta númerop 27
            $p27 = $fila["p27"];
            if ($p27 == 1) {
                $suma_1_p27++;
            } elseif ($p27 == 2) {
                $suma_2_p27++;
            } elseif ($p27 == 3) {
                $suma_3_p27++;
            } elseif ($p27 == 4) {
                $suma_4_p27++;
            } elseif ($p27 == 5) {
                $suma_5_p27++;
            }
            #pregunta númerop 29
            $p29 = $fila["p29"];
            if ($p29 == 1) {
                $suma_1_p29++;
            } elseif ($p29 == 2) {
                $suma_2_p29++;
            } elseif ($p29 == 3) {
                $suma_3_p29++;
            } elseif ($p29 == 4) {
                $suma_4_p29++;
            } elseif ($p29 == 5) {
                $suma_5_p29++;
            }

            $suma = $p1;

            $array_suma[] = array("suma"=>$suma);
           
        }
        
if ($cantidad != 0){


        #pregunta númerop 1
        $suma_1_p1 = ($suma_1_p1 * 100) / $cantidad;
        $suma_2_p1 = ($suma_2_p1 * 100) / $cantidad;
        $suma_3_p1 = ($suma_3_p1 * 100) / $cantidad;
        $suma_4_p1 = ($suma_4_p1 * 100) / $cantidad;
        $suma_5_p1 = ($suma_5_p1 * 100) / $cantidad;

        #pregunta númerop 5
        $suma_1_p5 = ($suma_1_p5 * 100) / $cantidad;
        $suma_2_p5 = ($suma_2_p5 * 100) / $cantidad;
        $suma_3_p5 = ($suma_3_p5 * 100) / $cantidad;
        $suma_4_p5 = ($suma_4_p5 * 100) / $cantidad;
        $suma_5_p5 = ($suma_5_p5 * 100) / $cantidad;

        #pregunta númerop 7
        $suma_1_p7 = ($suma_1_p7 * 100) / $cantidad;
        $suma_2_p7 = ($suma_2_p7 * 100) / $cantidad;
        $suma_3_p7 = ($suma_3_p7 * 100) / $cantidad;
        $suma_4_p7 = ($suma_4_p7 * 100) / $cantidad;
        $suma_5_p7 = ($suma_5_p7 * 100) / $cantidad;
        #pregunta númerop 8
        $suma_1_p8 = ($suma_1_p8 * 100) / $cantidad;
        $suma_2_p8 = ($suma_2_p8 * 100) / $cantidad;
        $suma_3_p8 = ($suma_3_p8 * 100) / $cantidad;
        $suma_4_p8 = ($suma_4_p8 * 100) / $cantidad;
        $suma_5_p8 = ($suma_5_p8 * 100) / $cantidad;

        #pregunta númerop 12
        $suma_1_p12 = ($suma_1_p12 * 100) / $cantidad;
        $suma_2_p12 = ($suma_2_p12 * 100) / $cantidad;
        $suma_3_p12 = ($suma_3_p12 * 100) / $cantidad;
        $suma_4_p12 = ($suma_4_p12 * 100) / $cantidad;
        $suma_5_p12 = ($suma_5_p12 * 100) / $cantidad;

        #pregunta númerop 15
        $suma_1_p15 = ($suma_1_p15 * 100) / $cantidad;
        $suma_2_p15 = ($suma_2_p15 * 100) / $cantidad;
        $suma_3_p15 = ($suma_3_p15 * 100) / $cantidad;
        $suma_4_p15 = ($suma_4_p15 * 100) / $cantidad;
        $suma_5_p15 = ($suma_5_p15 * 100) / $cantidad;

        #pregunta númerop 19
        $suma_1_p19 = ($suma_1_p19 * 100) / $cantidad;
        $suma_2_p19 = ($suma_2_p19 * 100) / $cantidad;
        $suma_3_p19 = ($suma_3_p19 * 100) / $cantidad;
        $suma_4_p19 = ($suma_4_p19 * 100) / $cantidad;
        $suma_5_p19 = ($suma_5_p19 * 100) / $cantidad;

        #pregunta númerop 22
        $suma_1_p22 = ($suma_1_p22 * 100) / $cantidad;
        $suma_2_p22 = ($suma_2_p22 * 100) / $cantidad;
        $suma_3_p22 = ($suma_3_p22 * 100) / $cantidad;
        $suma_4_p22 = ($suma_4_p22 * 100) / $cantidad;
        $suma_5_p22 = ($suma_5_p22 * 100) / $cantidad;

        #pregunta númerop 27
        $suma_1_p27 = ($suma_1_p27 * 100) / $cantidad;
        $suma_2_p27 = ($suma_2_p27 * 100) / $cantidad;
        $suma_3_p27 = ($suma_3_p27 * 100) / $cantidad;
        $suma_4_p27 = ($suma_4_p27 * 100) / $cantidad;
        $suma_5_p27 = ($suma_5_p27 * 100) / $cantidad;
        #pregunta númerop 29
        $suma_1_p2s9 = ($suma_1_p29 * 100) / $cantidad;
        $suma_2_p29 = ($suma_2_p29 * 100) / $cantidad;
        $suma_3_p29 = ($suma_3_p29 * 100) / $cantidad;
        $suma_4_p29 = ($suma_4_p29 * 100) / $cantidad;
        $suma_5_p29 = ($suma_5_p29 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        $dato = '<tr><td style="width:45%;">'.$pregunta["p01"].'</td>'
         
            . '<td>' . round($suma_1_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        $dato.= '<tr><td>'.$pregunta["p05"].'</td>'
      
            . '<td>' . round($suma_1_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p07"].'</td>'
     
            . '<td>' . round($suma_1_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p08"].'</td>'
       
            . '<td>' . round($suma_1_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p012"].'</td>'
       
            . '<td>' . round($suma_1_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p015"].'</td>'
     
            . '<td>' . round($suma_1_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p019"].'</td>'
     
            . '<td>' . round($suma_1_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p022"].'</td>'
      
            . '<td>' . round($suma_1_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p027"].'</td>'
        
            . '<td>' . round($suma_1_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p029"].'</td>'
        
            . '<td>' . round($suma_1_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
return $dato;
}else{
  return "No hay resultados para este curso";
}
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }


}

function dimension_afectivo_curso_pdf($establecimiento, $profesor)
{
    try {
        #pregunta número 1
        $suma_1_p1 = 0;
        $suma_2_p1 = 0;
        $suma_3_p1 = 0;
        $suma_4_p1 = 0;
        $suma_5_p1 = 0;
        #pregunta número 5
        $suma_1_p5 = 0;
        $suma_2_p5 = 0;
        $suma_3_p5 = 0;
        $suma_4_p5 = 0;
        $suma_5_p5 = 0;
        #pregunta número 7
        $suma_1_p7 = 0;
        $suma_2_p7 = 0;
        $suma_3_p7 = 0;
        $suma_4_p7 = 0;
        $suma_5_p7 = 0;
        #pregunta número 8
        $suma_1_p8 = 0;
        $suma_2_p8 = 0;
        $suma_3_p8 = 0;
        $suma_4_p8 = 0;
        $suma_5_p8 = 0;
        #pregunta número 12
        $suma_1_p12 = 0;
        $suma_2_p12 = 0;
        $suma_3_p12 = 0;
        $suma_4_p12 = 0;
        $suma_5_p12 = 0;
        #pregunta número 15
        $suma_1_p15 = 0;
        $suma_2_p15 = 0;
        $suma_3_p15 = 0;
        $suma_4_p15 = 0;
        $suma_5_p15 = 0;
        #pregunta número 19
        $suma_1_p19 = 0;
        $suma_2_p19 = 0;
        $suma_3_p19 = 0;
        $suma_4_p19 = 0;
        $suma_5_p19 = 0;
        #pregunta número 22
        $suma_1_p22 = 0;
        $suma_2_p22 = 0;
        $suma_3_p22 = 0;
        $suma_4_p22 = 0;
        $suma_5_p22 = 0;
        #pregunta número 27
        $suma_1_p27 = 0;
        $suma_2_p27 = 0;
        $suma_3_p27 = 0;
        $suma_4_p27 = 0;
        $suma_5_p27 = 0;
        #pregunta número 29
        $suma_1_p29 = 0;
        $suma_2_p29 = 0;
        $suma_3_p29 = 0;
        $suma_4_p29 = 0;
        $suma_5_p29 = 0;

        $con = connectDB_demos();
        $query = $con->query("SELECT ce_encuesta_resultado.ce_p1 AS p1,
        ce_encuesta_resultado.ce_p5 AS p5,
        ce_encuesta_resultado.ce_p7 AS p7,
        ce_encuesta_resultado.ce_p8 AS p8,
        ce_encuesta_resultado.ce_p12 AS p12,
        ce_encuesta_resultado.ce_p15 AS p15,
        ce_encuesta_resultado.ce_p19 AS p19,
        ce_encuesta_resultado.ce_p22 AS p22,
        ce_encuesta_resultado.ce_p27 AS p27,
        ce_encuesta_resultado.ce_p29 AS p29
       
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND 
        ce_participantes.ce_docente_id_ce_docente = '$profesor' AND ce_participantes.ce_estado_encuesta = 1");
        $cantidad = $query->RowCount();
        $con = null;

        while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
            #pregunta número 1
            $p1 = $fila["p1"];
            if ($p1 == 1) {
                $suma_1_p1++;
            } elseif ($p1 == 2) {
                $suma_2_p1++;
            } elseif ($p1 == 3) {
                $suma_3_p1++;
            } elseif ($p1 == 4) {
                $suma_4_p1++;
            } elseif ($p1 == 5) {
                $suma_5_p1++;
            }
            #pregunta númerop 5
            $p5 = $fila["p5"];
            if ($p5 == 1) {
                $suma_1_p5++;
            } elseif ($p5 == 2) {
                $suma_2_p5++;
            } elseif ($p5 == 3) {
                $suma_3_p5++;
            } elseif ($p5 == 4) {
                $suma_4_p5++;
            } elseif ($p5 == 5) {
                $suma_5_p5++;
            }
            #pregunta númerop 7
            $p7 = $fila["p7"];
            if ($p7 == 1) {
                $suma_1_p7++;
            } elseif ($p7 == 2) {
                $suma_2_p7++;
            } elseif ($p7 == 3) {
                $suma_3_p7++;
            } elseif ($p7 == 4) {
                $suma_4_p7++;
            } elseif ($p7 == 5) {
                $suma_5_p7++;
            }
            #pregunta númerop 8
            $p8 = $fila["p8"];
            if ($p8 == 1) {
                $suma_1_p8++;
            } elseif ($p8 == 2) {
                $suma_2_p8++;
            } elseif ($p8 == 3) {
                $suma_3_p8++;
            } elseif ($p8 == 4) {
                $suma_4_p8++;
            } elseif ($p8 == 5) {
                $suma_5_p8++;
            }
            #pregunta númerop 12
            $p12 = $fila["p12"];
            if ($p12 == 1) {
                $suma_1_p12++;
            } elseif ($p12 == 2) {
                $suma_2_p12++;
            } elseif ($p12 == 3) {
                $suma_3_p12++;
            } elseif ($p12 == 4) {
                $suma_4_p12++;
            } elseif ($p12 == 5) {
                $suma_5_p12++;
            }
            #pregunta número 15
            $p15 = $fila["p15"];
            if ($p15 == 1) {
                $suma_1_p15++;
            } elseif ($p15 == 2) {
                $suma_2_p15++;
            } elseif ($p15 == 3) {
                $suma_3_p15++;
            } elseif ($p15 == 4) {
                $suma_4_p15++;
            } elseif ($p15 == 5) {
                $suma_5_p15++;
            }
            #pregunta númerop 19
            $p19 = $fila["p19"];
            if ($p19 == 1) {
                $suma_1_p19++;
            } elseif ($p19 == 2) {
                $suma_2_p19++;
            } elseif ($p19 == 3) {
                $suma_3_p19++;
            } elseif ($p19 == 4) {
                $suma_4_p19++;
            } elseif ($p19 == 5) {
                $suma_5_p19++;
            }
            #pregunta númerop 22
            $p22 = $fila["p22"];
            if ($p22 == 1) {
                $suma_1_p22++;
            } elseif ($p22 == 2) {
                $suma_2_p22++;
            } elseif ($p22 == 3) {
                $suma_3_p22++;
            } elseif ($p22 == 4) {
                $suma_4_p22++;
            } elseif ($p22 == 5) {
                $suma_5_p22++;
            }
            #pregunta númerop 27
            $p27 = $fila["p27"];
            if ($p27 == 1) {
                $suma_1_p27++;
            } elseif ($p27 == 2) {
                $suma_2_p27++;
            } elseif ($p27 == 3) {
                $suma_3_p27++;
            } elseif ($p27 == 4) {
                $suma_4_p27++;
            } elseif ($p27 == 5) {
                $suma_5_p27++;
            }
            #pregunta númerop 29
            $p29 = $fila["p29"];
            if ($p29 == 1) {
                $suma_1_p29++;
            } elseif ($p29 == 2) {
                $suma_2_p29++;
            } elseif ($p29 == 3) {
                $suma_3_p29++;
            } elseif ($p29 == 4) {
                $suma_4_p29++;
            } elseif ($p29 == 5) {
                $suma_5_p29++;
            }

            $suma = $p1;

            $array_suma[] = array("suma"=>$suma);
           
        }
        
if ($cantidad != 0){


        #pregunta númerop 1
        $suma_1_p1 = ($suma_1_p1 * 100) / $cantidad;
        $suma_2_p1 = ($suma_2_p1 * 100) / $cantidad;
        $suma_3_p1 = ($suma_3_p1 * 100) / $cantidad;
        $suma_4_p1 = ($suma_4_p1 * 100) / $cantidad;
        $suma_5_p1 = ($suma_5_p1 * 100) / $cantidad;

        #pregunta númerop 5
        $suma_1_p5 = ($suma_1_p5 * 100) / $cantidad;
        $suma_2_p5 = ($suma_2_p5 * 100) / $cantidad;
        $suma_3_p5 = ($suma_3_p5 * 100) / $cantidad;
        $suma_4_p5 = ($suma_4_p5 * 100) / $cantidad;
        $suma_5_p5 = ($suma_5_p5 * 100) / $cantidad;

        #pregunta númerop 7
        $suma_1_p7 = ($suma_1_p7 * 100) / $cantidad;
        $suma_2_p7 = ($suma_2_p7 * 100) / $cantidad;
        $suma_3_p7 = ($suma_3_p7 * 100) / $cantidad;
        $suma_4_p7 = ($suma_4_p7 * 100) / $cantidad;
        $suma_5_p7 = ($suma_5_p7 * 100) / $cantidad;
        #pregunta númerop 8
        $suma_1_p8 = ($suma_1_p8 * 100) / $cantidad;
        $suma_2_p8 = ($suma_2_p8 * 100) / $cantidad;
        $suma_3_p8 = ($suma_3_p8 * 100) / $cantidad;
        $suma_4_p8 = ($suma_4_p8 * 100) / $cantidad;
        $suma_5_p8 = ($suma_5_p8 * 100) / $cantidad;

        #pregunta númerop 12
        $suma_1_p12 = ($suma_1_p12 * 100) / $cantidad;
        $suma_2_p12 = ($suma_2_p12 * 100) / $cantidad;
        $suma_3_p12 = ($suma_3_p12 * 100) / $cantidad;
        $suma_4_p12 = ($suma_4_p12 * 100) / $cantidad;
        $suma_5_p12 = ($suma_5_p12 * 100) / $cantidad;

        #pregunta númerop 15
        $suma_1_p15 = ($suma_1_p15 * 100) / $cantidad;
        $suma_2_p15 = ($suma_2_p15 * 100) / $cantidad;
        $suma_3_p15 = ($suma_3_p15 * 100) / $cantidad;
        $suma_4_p15 = ($suma_4_p15 * 100) / $cantidad;
        $suma_5_p15 = ($suma_5_p15 * 100) / $cantidad;

        #pregunta númerop 19
        $suma_1_p19 = ($suma_1_p19 * 100) / $cantidad;
        $suma_2_p19 = ($suma_2_p19 * 100) / $cantidad;
        $suma_3_p19 = ($suma_3_p19 * 100) / $cantidad;
        $suma_4_p19 = ($suma_4_p19 * 100) / $cantidad;
        $suma_5_p19 = ($suma_5_p19 * 100) / $cantidad;

        #pregunta númerop 22
        $suma_1_p22 = ($suma_1_p22 * 100) / $cantidad;
        $suma_2_p22 = ($suma_2_p22 * 100) / $cantidad;
        $suma_3_p22 = ($suma_3_p22 * 100) / $cantidad;
        $suma_4_p22 = ($suma_4_p22 * 100) / $cantidad;
        $suma_5_p22 = ($suma_5_p22 * 100) / $cantidad;

        #pregunta númerop 27
        $suma_1_p27 = ($suma_1_p27 * 100) / $cantidad;
        $suma_2_p27 = ($suma_2_p27 * 100) / $cantidad;
        $suma_3_p27 = ($suma_3_p27 * 100) / $cantidad;
        $suma_4_p27 = ($suma_4_p27 * 100) / $cantidad;
        $suma_5_p27 = ($suma_5_p27 * 100) / $cantidad;
        #pregunta númerop 29
        $suma_1_p2s9 = ($suma_1_p29 * 100) / $cantidad;
        $suma_2_p29 = ($suma_2_p29 * 100) / $cantidad;
        $suma_3_p29 = ($suma_3_p29 * 100) / $cantidad;
        $suma_4_p29 = ($suma_4_p29 * 100) / $cantidad;
        $suma_5_p29 = ($suma_5_p29 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        $dato = '<tr><td style="width:60%;border: 1px solid #fc455c;">'.$pregunta["p01"].'</td>'
         
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p05"].'</td>'
      
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p07"].'</td>'
     
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p08"].'</td>'
       
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p012"].'</td>'
       
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p015"].'</td>'
     
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p019"].'</td>'
     
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p022"].'</td>'
      
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p027"].'</td>'
        
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p029"].'</td>'
        
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
return $dato;
}else{
  return "No hay resultados para este curso";
}
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }


}

function dimension_afectivo_establecimiento($establecimiento)
{
    try {
        #pregunta número 1
        $suma_1_p1 = 0;
        $suma_2_p1 = 0;
        $suma_3_p1 = 0;
        $suma_4_p1 = 0;
        $suma_5_p1 = 0;
        #pregunta número 5
        $suma_1_p5 = 0;
        $suma_2_p5 = 0;
        $suma_3_p5 = 0;
        $suma_4_p5 = 0;
        $suma_5_p5 = 0;
        #pregunta número 7
        $suma_1_p7 = 0;
        $suma_2_p7 = 0;
        $suma_3_p7 = 0;
        $suma_4_p7 = 0;
        $suma_5_p7 = 0;
        #pregunta número 8
        $suma_1_p8 = 0;
        $suma_2_p8 = 0;
        $suma_3_p8 = 0;
        $suma_4_p8 = 0;
        $suma_5_p8 = 0;
        #pregunta número 12
        $suma_1_p12 = 0;
        $suma_2_p12 = 0;
        $suma_3_p12 = 0;
        $suma_4_p12 = 0;
        $suma_5_p12 = 0;
        #pregunta número 15
        $suma_1_p15 = 0;
        $suma_2_p15 = 0;
        $suma_3_p15 = 0;
        $suma_4_p15 = 0;
        $suma_5_p15 = 0;
        #pregunta número 19
        $suma_1_p19 = 0;
        $suma_2_p19 = 0;
        $suma_3_p19 = 0;
        $suma_4_p19 = 0;
        $suma_5_p19 = 0;
        #pregunta número 22
        $suma_1_p22 = 0;
        $suma_2_p22 = 0;
        $suma_3_p22 = 0;
        $suma_4_p22 = 0;
        $suma_5_p22 = 0;
        #pregunta número 27
        $suma_1_p27 = 0;
        $suma_2_p27 = 0;
        $suma_3_p27 = 0;
        $suma_4_p27 = 0;
        $suma_5_p27 = 0;
        #pregunta número 29
        $suma_1_p29 = 0;
        $suma_2_p29 = 0;
        $suma_3_p29 = 0;
        $suma_4_p29 = 0;
        $suma_5_p29 = 0;

        $con = connectDB_demos();
        $query = $con->query("SELECT ce_encuesta_resultado.ce_p1 AS p1,
        ce_encuesta_resultado.ce_p5 AS p5,
        ce_encuesta_resultado.ce_p7 AS p7,
        ce_encuesta_resultado.ce_p8 AS p8,
        ce_encuesta_resultado.ce_p12 AS p12,
        ce_encuesta_resultado.ce_p15 AS p15,
        ce_encuesta_resultado.ce_p19 AS p19,
        ce_encuesta_resultado.ce_p22 AS p22,
        ce_encuesta_resultado.ce_p27 AS p27,
        ce_encuesta_resultado.ce_p29 AS p29
       
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento'
        AND ce_participantes.ce_estado_encuesta = 1");
        $cantidad = $query->RowCount();
        $con = null;

        while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
            #pregunta número 1
            $p1 = $fila["p1"];
            if ($p1 == 1) {
                $suma_1_p1++;
            } elseif ($p1 == 2) {
                $suma_2_p1++;
            } elseif ($p1 == 3) {
                $suma_3_p1++;
            } elseif ($p1 == 4) {
                $suma_4_p1++;
            } elseif ($p1 == 5) {
                $suma_5_p1++;
            }
            #pregunta númerop 5
            $p5 = $fila["p5"];
            if ($p5 == 1) {
                $suma_1_p5++;
            } elseif ($p5 == 2) {
                $suma_2_p5++;
            } elseif ($p5 == 3) {
                $suma_3_p5++;
            } elseif ($p5 == 4) {
                $suma_4_p5++;
            } elseif ($p5 == 5) {
                $suma_5_p5++;
            }
            #pregunta númerop 7
            $p7 = $fila["p7"];
            if ($p7 == 1) {
                $suma_1_p7++;
            } elseif ($p7 == 2) {
                $suma_2_p7++;
            } elseif ($p7 == 3) {
                $suma_3_p7++;
            } elseif ($p7 == 4) {
                $suma_4_p7++;
            } elseif ($p7 == 5) {
                $suma_5_p7++;
            }
            #pregunta númerop 8
            $p8 = $fila["p8"];
            if ($p8 == 1) {
                $suma_1_p8++;
            } elseif ($p8 == 2) {
                $suma_2_p8++;
            } elseif ($p8 == 3) {
                $suma_3_p8++;
            } elseif ($p8 == 4) {
                $suma_4_p8++;
            } elseif ($p8 == 5) {
                $suma_5_p8++;
            }
            #pregunta númerop 12
            $p12 = $fila["p12"];
            if ($p12 == 1) {
                $suma_1_p12++;
            } elseif ($p12 == 2) {
                $suma_2_p12++;
            } elseif ($p12 == 3) {
                $suma_3_p12++;
            } elseif ($p12 == 4) {
                $suma_4_p12++;
            } elseif ($p12 == 5) {
                $suma_5_p12++;
            }
            #pregunta número 15
            $p15 = $fila["p15"];
            if ($p15 == 1) {
                $suma_1_p15++;
            } elseif ($p15 == 2) {
                $suma_2_p15++;
            } elseif ($p15 == 3) {
                $suma_3_p15++;
            } elseif ($p15 == 4) {
                $suma_4_p15++;
            } elseif ($p15 == 5) {
                $suma_5_p15++;
            }
            #pregunta númerop 19
            $p19 = $fila["p19"];
            if ($p19 == 1) {
                $suma_1_p19++;
            } elseif ($p19 == 2) {
                $suma_2_p19++;
            } elseif ($p19 == 3) {
                $suma_3_p19++;
            } elseif ($p19 == 4) {
                $suma_4_p19++;
            } elseif ($p19 == 5) {
                $suma_5_p19++;
            }
            #pregunta númerop 22
            $p22 = $fila["p22"];
            if ($p22 == 1) {
                $suma_1_p22++;
            } elseif ($p22 == 2) {
                $suma_2_p22++;
            } elseif ($p22 == 3) {
                $suma_3_p22++;
            } elseif ($p22 == 4) {
                $suma_4_p22++;
            } elseif ($p22 == 5) {
                $suma_5_p22++;
            }
            #pregunta númerop 27
            $p27 = $fila["p27"];
            if ($p27 == 1) {
                $suma_1_p27++;
            } elseif ($p27 == 2) {
                $suma_2_p27++;
            } elseif ($p27 == 3) {
                $suma_3_p27++;
            } elseif ($p27 == 4) {
                $suma_4_p27++;
            } elseif ($p27 == 5) {
                $suma_5_p27++;
            }
            #pregunta númerop 29
            $p29 = $fila["p29"];
            if ($p29 == 1) {
                $suma_1_p29++;
            } elseif ($p29 == 2) {
                $suma_2_p29++;
            } elseif ($p29 == 3) {
                $suma_3_p29++;
            } elseif ($p29 == 4) {
                $suma_4_p29++;
            } elseif ($p29 == 5) {
                $suma_5_p29++;
            }

            $suma = $p1;

            $array_suma[] = array("suma"=>$suma);
           
        }
        
if ($cantidad != 0){


        #pregunta númerop 1
        $suma_1_p1 = ($suma_1_p1 * 100) / $cantidad;
        $suma_2_p1 = ($suma_2_p1 * 100) / $cantidad;
        $suma_3_p1 = ($suma_3_p1 * 100) / $cantidad;
        $suma_4_p1 = ($suma_4_p1 * 100) / $cantidad;
        $suma_5_p1 = ($suma_5_p1 * 100) / $cantidad;

        #pregunta númerop 5
        $suma_1_p5 = ($suma_1_p5 * 100) / $cantidad;
        $suma_2_p5 = ($suma_2_p5 * 100) / $cantidad;
        $suma_3_p5 = ($suma_3_p5 * 100) / $cantidad;
        $suma_4_p5 = ($suma_4_p5 * 100) / $cantidad;
        $suma_5_p5 = ($suma_5_p5 * 100) / $cantidad;

        #pregunta númerop 7
        $suma_1_p7 = ($suma_1_p7 * 100) / $cantidad;
        $suma_2_p7 = ($suma_2_p7 * 100) / $cantidad;
        $suma_3_p7 = ($suma_3_p7 * 100) / $cantidad;
        $suma_4_p7 = ($suma_4_p7 * 100) / $cantidad;
        $suma_5_p7 = ($suma_5_p7 * 100) / $cantidad;
        #pregunta númerop 8
        $suma_1_p8 = ($suma_1_p8 * 100) / $cantidad;
        $suma_2_p8 = ($suma_2_p8 * 100) / $cantidad;
        $suma_3_p8 = ($suma_3_p8 * 100) / $cantidad;
        $suma_4_p8 = ($suma_4_p8 * 100) / $cantidad;
        $suma_5_p8 = ($suma_5_p8 * 100) / $cantidad;

        #pregunta númerop 12
        $suma_1_p12 = ($suma_1_p12 * 100) / $cantidad;
        $suma_2_p12 = ($suma_2_p12 * 100) / $cantidad;
        $suma_3_p12 = ($suma_3_p12 * 100) / $cantidad;
        $suma_4_p12 = ($suma_4_p12 * 100) / $cantidad;
        $suma_5_p12 = ($suma_5_p12 * 100) / $cantidad;

        #pregunta númerop 15
        $suma_1_p15 = ($suma_1_p15 * 100) / $cantidad;
        $suma_2_p15 = ($suma_2_p15 * 100) / $cantidad;
        $suma_3_p15 = ($suma_3_p15 * 100) / $cantidad;
        $suma_4_p15 = ($suma_4_p15 * 100) / $cantidad;
        $suma_5_p15 = ($suma_5_p15 * 100) / $cantidad;

        #pregunta númerop 19
        $suma_1_p19 = ($suma_1_p19 * 100) / $cantidad;
        $suma_2_p19 = ($suma_2_p19 * 100) / $cantidad;
        $suma_3_p19 = ($suma_3_p19 * 100) / $cantidad;
        $suma_4_p19 = ($suma_4_p19 * 100) / $cantidad;
        $suma_5_p19 = ($suma_5_p19 * 100) / $cantidad;

        #pregunta númerop 22
        $suma_1_p22 = ($suma_1_p22 * 100) / $cantidad;
        $suma_2_p22 = ($suma_2_p22 * 100) / $cantidad;
        $suma_3_p22 = ($suma_3_p22 * 100) / $cantidad;
        $suma_4_p22 = ($suma_4_p22 * 100) / $cantidad;
        $suma_5_p22 = ($suma_5_p22 * 100) / $cantidad;

        #pregunta númerop 27
        $suma_1_p27 = ($suma_1_p27 * 100) / $cantidad;
        $suma_2_p27 = ($suma_2_p27 * 100) / $cantidad;
        $suma_3_p27 = ($suma_3_p27 * 100) / $cantidad;
        $suma_4_p27 = ($suma_4_p27 * 100) / $cantidad;
        $suma_5_p27 = ($suma_5_p27 * 100) / $cantidad;
        #pregunta númerop 29
        $suma_1_p2s9 = ($suma_1_p29 * 100) / $cantidad;
        $suma_2_p29 = ($suma_2_p29 * 100) / $cantidad;
        $suma_3_p29 = ($suma_3_p29 * 100) / $cantidad;
        $suma_4_p29 = ($suma_4_p29 * 100) / $cantidad;
        $suma_5_p29 = ($suma_5_p29 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        $dato = '<tr><td style="width:40%;">'.$pregunta["p01"].'</td>'
         
            . '<td>' . round($suma_1_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        $dato.= '<tr><td>'.$pregunta["p05"].'</td>'
      
            . '<td>' . round($suma_1_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p07"].'</td>'
     
            . '<td>' . round($suma_1_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p08"].'</td>'
       
            . '<td>' . round($suma_1_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p012"].'</td>'
       
            . '<td>' . round($suma_1_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p015"].'</td>'
     
            . '<td>' . round($suma_1_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p019"].'</td>'
     
            . '<td>' . round($suma_1_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p022"].'</td>'
      
            . '<td>' . round($suma_1_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p027"].'</td>'
        
            . '<td>' . round($suma_1_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato.= '<tr><td>'.$pregunta["p029"].'</td>'
        
            . '<td>' . round($suma_1_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
return $dato;
}else{
  return "No hay resultados para este curso";
}
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }


}


function dimension_afectivo_curso($establecimiento, $profesor)
{
    try {
        #pregunta número 1
        $suma_1_p1 = 0;
        $suma_2_p1 = 0;
        $suma_3_p1 = 0;
        $suma_4_p1 = 0;
        $suma_5_p1 = 0;
        #pregunta número 5
        $suma_1_p5 = 0;
        $suma_2_p5 = 0;
        $suma_3_p5 = 0;
        $suma_4_p5 = 0;
        $suma_5_p5 = 0;
        #pregunta número 7
        $suma_1_p7 = 0;
        $suma_2_p7 = 0;
        $suma_3_p7 = 0;
        $suma_4_p7 = 0;
        $suma_5_p7 = 0;
        #pregunta número 8
        $suma_1_p8 = 0;
        $suma_2_p8 = 0;
        $suma_3_p8 = 0;
        $suma_4_p8 = 0;
        $suma_5_p8 = 0;
        #pregunta número 12
        $suma_1_p12 = 0;
        $suma_2_p12 = 0;
        $suma_3_p12 = 0;
        $suma_4_p12 = 0;
        $suma_5_p12 = 0;
        #pregunta número 15
        $suma_1_p15 = 0;
        $suma_2_p15 = 0;
        $suma_3_p15 = 0;
        $suma_4_p15 = 0;
        $suma_5_p15 = 0;
        #pregunta número 19
        $suma_1_p19 = 0;
        $suma_2_p19 = 0;
        $suma_3_p19 = 0;
        $suma_4_p19 = 0;
        $suma_5_p19 = 0;
        #pregunta número 22
        $suma_1_p22 = 0;
        $suma_2_p22 = 0;
        $suma_3_p22 = 0;
        $suma_4_p22 = 0;
        $suma_5_p22 = 0;
        #pregunta número 27
        $suma_1_p27 = 0;
        $suma_2_p27 = 0;
        $suma_3_p27 = 0;
        $suma_4_p27 = 0;
        $suma_5_p27 = 0;
        #pregunta número 29
        $suma_1_p29 = 0;
        $suma_2_p29 = 0;
        $suma_3_p29 = 0;
        $suma_4_p29 = 0;
        $suma_5_p29 = 0;

        $con = connectDB_demos();
        $query = $con->query("SELECT ce_encuesta_resultado.ce_p1 AS p1,
        ce_encuesta_resultado.ce_p5 AS p5,
        ce_encuesta_resultado.ce_p7 AS p7,
        ce_encuesta_resultado.ce_p8 AS p8,
        ce_encuesta_resultado.ce_p12 AS p12,
        ce_encuesta_resultado.ce_p15 AS p15,
        ce_encuesta_resultado.ce_p19 AS p19,
        ce_encuesta_resultado.ce_p22 AS p22,
        ce_encuesta_resultado.ce_p27 AS p27,
        ce_encuesta_resultado.ce_p29 AS p29
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND 
        ce_participantes.ce_docente_id_ce_docente = '$profesor' AND ce_participantes.ce_estado_encuesta = 1");
        $cantidad = $query->RowCount();
        $con = null;

        while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
            #pregunta número 1
            $p1 = $fila["p1"];
            if ($p1 == 1) {
                $suma_1_p1++;
            } elseif ($p1 == 2) {
                $suma_2_p1++;
            } elseif ($p1 == 3) {
                $suma_3_p1++;
            } elseif ($p1 == 4) {
                $suma_4_p1++;
            } elseif ($p1 == 5) {
                $suma_5_p1++;
            }
            #pregunta númerop 5
            $p5 = $fila["p5"];
            if ($p5 == 1) {
                $suma_1_p5++;
            } elseif ($p5 == 2) {
                $suma_2_p5++;
            } elseif ($p5 == 3) {
                $suma_3_p5++;
            } elseif ($p5 == 4) {
                $suma_4_p5++;
            } elseif ($p5 == 5) {
                $suma_5_p5++;
            }
            #pregunta númerop 7
            $p7 = $fila["p7"];
            if ($p7 == 1) {
                $suma_1_p7++;
            } elseif ($p7 == 2) {
                $suma_2_p7++;
            } elseif ($p7 == 3) {
                $suma_3_p7++;
            } elseif ($p7 == 4) {
                $suma_4_p7++;
            } elseif ($p7 == 5) {
                $suma_5_p7++;
            }
            #pregunta númerop 8
            $p8 = $fila["p8"];
            if ($p8 == 1) {
                $suma_1_p8++;
            } elseif ($p8 == 2) {
                $suma_2_p8++;
            } elseif ($p8 == 3) {
                $suma_3_p8++;
            } elseif ($p8 == 4) {
                $suma_4_p8++;
            } elseif ($p8 == 5) {
                $suma_5_p8++;
            }
            #pregunta númerop 12
            $p12 = $fila["p12"];
            if ($p12 == 1) {
                $suma_1_p12++;
            } elseif ($p12 == 2) {
                $suma_2_p12++;
            } elseif ($p12 == 3) {
                $suma_3_p12++;
            } elseif ($p12 == 4) {
                $suma_4_p12++;
            } elseif ($p12 == 5) {
                $suma_5_p12++;
            }
            #pregunta número 15
            $p15 = $fila["p15"];
            if ($p15 == 1) {
                $suma_1_p15++;
            } elseif ($p15 == 2) {
                $suma_2_p15++;
            } elseif ($p15 == 3) {
                $suma_3_p15++;
            } elseif ($p15 == 4) {
                $suma_4_p15++;
            } elseif ($p15 == 5) {
                $suma_5_p15++;
            }
            #pregunta númerop 19
            $p19 = $fila["p19"];
            if ($p19 == 1) {
                $suma_1_p19++;
            } elseif ($p19 == 2) {
                $suma_2_p19++;
            } elseif ($p19 == 3) {
                $suma_3_p19++;
            } elseif ($p19 == 4) {
                $suma_4_p19++;
            } elseif ($p19 == 5) {
                $suma_5_p19++;
            }
            #pregunta númerop 22
            $p22 = $fila["p22"];
            if ($p22 == 1) {
                $suma_1_p22++;
            } elseif ($p22 == 2) {
                $suma_2_p22++;
            } elseif ($p22 == 3) {
                $suma_3_p22++;
            } elseif ($p22 == 4) {
                $suma_4_p22++;
            } elseif ($p22 == 5) {
                $suma_5_p22++;
            }
            #pregunta númerop 27
            $p27 = $fila["p27"];
            if ($p27 == 1) {
                $suma_1_p27++;
            } elseif ($p27 == 2) {
                $suma_2_p27++;
            } elseif ($p27 == 3) {
                $suma_3_p27++;
            } elseif ($p27 == 4) {
                $suma_4_p27++;
            } elseif ($p27 == 5) {
                $suma_5_p27++;
            }
            #pregunta númerop 29
            $p29 = $fila["p29"];
            if ($p29 == 1) {
                $suma_1_p29++;
            } elseif ($p29 == 2) {
                $suma_2_p29++;
            } elseif ($p29 == 3) {
                $suma_3_p29++;
            } elseif ($p29 == 4) {
                $suma_4_p29++;
            } elseif ($p29 == 5) {
                $suma_5_p29++;
            }

            $suma = $p1;

            $array_suma[] = array("suma"=>$suma);
           
        }
        
   
          
        $suma_1_p1 = ($suma_1_p1 * 100) / $cantidad;        
     
        $suma_2_p1 = ($suma_2_p1 * 100) / $cantidad;
       
        $suma_3_p1 = ($suma_3_p1 * 100) / $cantidad;
       
        $suma_4_p1 = ($suma_4_p1 * 100) / $cantidad;
        
        $suma_5_p1 = ($suma_5_p1 * 100) / $cantidad;
       

        #pregunta númerop 5
        $suma_1_p5 = ($suma_1_p5 * 100) / $cantidad;
        
        $suma_2_p5 = ($suma_2_p5 * 100) / $cantidad;
        
        $suma_3_p5 = ($suma_3_p5 * 100) / $cantidad;
        
        $suma_4_p5 = ($suma_4_p5 * 100) / $cantidad;
        
        $suma_5_p5 = ($suma_5_p5 * 100) / $cantidad;
      

        #pregunta númerop 7
        $suma_1_p7 = ($suma_1_p7 * 100) / $cantidad;
        $suma_2_p7 = ($suma_2_p7 * 100) / $cantidad;
        $suma_3_p7 = ($suma_3_p7 * 100) / $cantidad;
        $suma_4_p7 = ($suma_4_p7 * 100) / $cantidad;
        $suma_5_p7 = ($suma_5_p7 * 100) / $cantidad;
        #pregunta númerop 8
        $suma_1_p8 = ($suma_1_p8 * 100) / $cantidad;
        $suma_2_p8 = ($suma_2_p8 * 100) / $cantidad;
        $suma_3_p8 = ($suma_3_p8 * 100) / $cantidad;
        $suma_4_p8 = ($suma_4_p8 * 100) / $cantidad;
        $suma_5_p8 = ($suma_5_p8 * 100) / $cantidad;

        #pregunta númerop 12
        $suma_1_p12 = ($suma_1_p12 * 100) / $cantidad;
        $suma_2_p12 = ($suma_2_p12 * 100) / $cantidad;
        $suma_3_p12 = ($suma_3_p12 * 100) / $cantidad;
        $suma_4_p12 = ($suma_4_p12 * 100) / $cantidad;
        $suma_5_p12 = ($suma_5_p12 * 100) / $cantidad;

        #pregunta númerop 15
        $suma_1_p15 = ($suma_1_p15 * 100) / $cantidad;
        $suma_2_p15 = ($suma_2_p15 * 100) / $cantidad;
        $suma_3_p15 = ($suma_3_p15 * 100) / $cantidad;
        $suma_4_p15 = ($suma_4_p15 * 100) / $cantidad;
        $suma_5_p15 = ($suma_5_p15 * 100) / $cantidad;

        #pregunta númerop 19
        $suma_1_p19 = ($suma_1_p19 * 100) / $cantidad;
        $suma_2_p19 = ($suma_2_p19 * 100) / $cantidad;
        $suma_3_p19 = ($suma_3_p19 * 100) / $cantidad;
        $suma_4_p19 = ($suma_4_p19 * 100) / $cantidad;
        $suma_5_p19 = ($suma_5_p19 * 100) / $cantidad;

        #pregunta númerop 22
        $suma_1_p22 = ($suma_1_p22 * 100) / $cantidad;
        $suma_2_p22 = ($suma_2_p22 * 100) / $cantidad;
        $suma_3_p22 = ($suma_3_p22 * 100) / $cantidad;
        $suma_4_p22 = ($suma_4_p22 * 100) / $cantidad;
        $suma_5_p22 = ($suma_5_p22 * 100) / $cantidad;

        #pregunta númerop 27
        $suma_1_p27 = ($suma_1_p27 * 100) / $cantidad;
        $suma_2_p27 = ($suma_2_p27 * 100) / $cantidad;
        $suma_3_p27 = ($suma_3_p27 * 100) / $cantidad;
        $suma_4_p27 = ($suma_4_p27 * 100) / $cantidad;
        $suma_5_p27 = ($suma_5_p27 * 100) / $cantidad;
        #pregunta númerop 29
        $suma_1_p2s9 = ($suma_1_p29 * 100) / $cantidad;
        $suma_2_p29 = ($suma_2_p29 * 100) / $cantidad;
        $suma_3_p29 = ($suma_3_p29 * 100) / $cantidad;
        $suma_4_p29 = ($suma_4_p29 * 100) / $cantidad;
        $suma_5_p29 = ($suma_5_p29 * 100) / $cantidad;


        echo '<tr><td style="width:40%;">Siento que soy parte del colegio</td>'
         
            . '<td>' . round($suma_1_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p1, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>Puedo ser yo mismo(a) en este colegio</td>'
      
            . '<td>' . round($suma_1_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p5, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>La mayoría de las cosas que aprendo en el colegio son útiles</td>'
     
            . '<td>' . round($suma_1_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p7, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>La mayoría de los profesores se preocupan de que la materia que aprendamos sea útil.</td>'
       
            . '<td>' . round($suma_1_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p8, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>Siento orgullo de estar en este colegio.</td>'
       
            . '<td>' . round($suma_1_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p12, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>Para mí es muy importante lo que hacemos en la escuela.</td>'
     
            . '<td>' . round($suma_1_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p15, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>Me tratan con respeto en este colegio.</td>'
     
            . '<td>' . round($suma_1_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p19, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>Lo que aprendo en clases es importante para conseguir mis metas futuras.</td>'
      
            . '<td>' . round($suma_1_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p22, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>Siento que soy importante para el colegio.</td>'
        
            . '<td>' . round($suma_1_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p27, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>Me siento bien en este colegio.</td>'
        
            . '<td>' . round($suma_1_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_p29, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
   
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }


}

function dimension_cognitivo_curso($establecimiento, $profesor)
{

    try {
        #pregunta número 2
        $suma_1_p2 = 0;
        $suma_2_p2 = 0;
        $suma_3_p2 = 0;
        $suma_4_p2 = 0;
        $suma_5_p2 = 0;
        #pregunta número 6
        $suma_1_p6 = 0;
        $suma_2_p6 = 0;
        $suma_3_p6 = 0;
        $suma_4_p6 = 0;
        $suma_5_p6 = 0;
        #pregunta número 10
        $suma_1_p10 = 0;
        $suma_2_p10 = 0;
        $suma_3_p10 = 0;
        $suma_4_p10 = 0;
        $suma_5_p10 = 0;
        #pregunta número 13
        $suma_1_p13 = 0;
        $suma_2_p13 = 0;
        $suma_3_p13 = 0;
        $suma_4_p13 = 0;
        $suma_5_p13 = 0;
        #pregunta número 14
        $suma_1_p14 = 0;
        $suma_2_p14 = 0;
        $suma_3_p14 = 0;
        $suma_4_p14 = 0;
        $suma_5_p14 = 0;
        #pregunta número 17
        $suma_1_p17 = 0;
        $suma_2_p17 = 0;
        $suma_3_p17 = 0;
        $suma_4_p17 = 0;
        $suma_5_p17 = 0;
        #pregunta número 18
        $suma_1_p18 = 0;
        $suma_2_p18 = 0;
        $suma_3_p18 = 0;
        $suma_4_p18 = 0;
        $suma_5_p18 = 0;
        #pregunta número 20
        $suma_1_p20 = 0;
        $suma_2_p20 = 0;
        $suma_3_p20 = 0;
        $suma_4_p20 = 0;
        $suma_5_p20 = 0;
        #pregunta número 21
        $suma_1_p21 = 0;
        $suma_2_p21 = 0;
        $suma_3_p21 = 0;
        $suma_4_p21 = 0;
        $suma_5_p21 = 0;
        #pregunta número 24
        $suma_1_p24 = 0;
        $suma_2_p24 = 0;
        $suma_3_p24 = 0;
        $suma_4_p24 = 0;
        $suma_5_p24 = 0;
        #pregunta número 25
        $suma_1_p25 = 0;
        $suma_2_p25 = 0;
        $suma_3_p25 = 0;
        $suma_4_p25 = 0;
        $suma_5_p25 = 0;
        #pregunta número 26
        $suma_1_p26 = 0;
        $suma_2_p26 = 0;
        $suma_3_p26 = 0;
        $suma_4_p26 = 0;
        $suma_5_p26 = 0;
        $con = connectDB_demos();
        $query = $con->query("SELECT
        ce_encuesta_resultado.ce_p2 AS p2,
        ce_encuesta_resultado.ce_p6 AS p6,
        ce_encuesta_resultado.ce_p10 AS p10,
        ce_encuesta_resultado.ce_p13 AS p13,
        ce_encuesta_resultado.ce_p14 AS p14,
        ce_encuesta_resultado.ce_p17 AS p17,
        ce_encuesta_resultado.ce_p18 AS p18,
        ce_encuesta_resultado.ce_p20 AS p20,
        ce_encuesta_resultado.ce_p21 AS p21,
        ce_encuesta_resultado.ce_p24 AS p24,
        ce_encuesta_resultado.ce_p25 AS p25,
        ce_encuesta_resultado.ce_p26 AS p26
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND  ce_participantes.ce_docente_id_ce_docente = '$profesor'");
        $cantidad = $query->RowCount();

        foreach ($query as $fila) {
            #pregunta número 2
            $p2 = $fila["p2"];
            if ($p2 == 1) {
                $suma_1_p2++;
            } elseif ($p2 == 2) {
                $suma_2_p2++;
            } elseif ($p2 == 3) {
                $suma_3_p2++;
            } elseif ($p2 == 4) {
                $suma_4_p2++;
            } elseif ($p2 == 5) {
                $suma_5_p2++;
            }
            #pregunta número 6
            $p6 = $fila["p6"];
            if ($p6 == 1) {
                $suma_1_p6++;
            } elseif ($p6 == 2) {
                $suma_2_p6++;
            } elseif ($p6 == 3) {
                $suma_3_p6++;
            } elseif ($p6 == 4) {
                $suma_4_p6++;
            } elseif ($p6 == 5) {
                $suma_5_p6++;
            }
            #pregunta número 10
            $p10 = $fila["p10"];
            if ($p10 == 1) {
                $suma_1_p10++;
            } elseif ($p10 == 2) {
                $suma_2_p10++;
            } elseif ($p10 == 3) {
                $suma_3_p10++;
            } elseif ($p10 == 4) {
                $suma_4_p10++;
            } elseif ($p10 == 5) {
                $suma_5_p10++;
            }
            #pregunta número 13
            $p13 = $fila["p13"];
            if ($p13 == 1) {
                $suma_1_p13++;
            } elseif ($p13 == 2) {
                $suma_2_p13++;
            } elseif ($p13 == 3) {
                $suma_3_p13++;
            } elseif ($p13 == 4) {
                $suma_4_p13++;
            } elseif ($p13 == 5) {
                $suma_5_p13++;
            }
            #pregunta número 14
            $p14 = $fila["p14"];
            if ($p14 == 1) {
                $suma_1_p14++;
            } elseif ($p14 == 2) {
                $suma_2_p14++;
            } elseif ($p14 == 3) {
                $suma_3_p14++;
            } elseif ($p14 == 4) {
                $suma_4_p14++;
            } elseif ($p14 == 5) {
                $suma_5_p14++;
            }
            #pregunta número 17
            $p17 = $fila["p17"];
            if ($p17 == 1) {
                $suma_1_p17++;
            } elseif ($p17 == 2) {
                $suma_2_p17++;
            } elseif ($p17 == 3) {
                $suma_3_p17++;
            } elseif ($p17 == 4) {
                $suma_4_p17++;
            } elseif ($p17 == 5) {
                $suma_5_p17++;
            }
            #pregunta número 18
            $p18 = $fila["p18"];
            if ($p18 == 1) {
                $suma_1_p18++;
            } elseif ($p18 == 2) {
                $suma_2_p18++;
            } elseif ($p18 == 3) {
                $suma_3_p18++;
            } elseif ($p18 == 4) {
                $suma_4_p18++;
            } elseif ($p18 == 5) {
                $suma_5_p18++;
            }
            #pregunta número 20
            $p20 = $fila["p20"];
            if ($p20 == 1) {
                $suma_1_p20++;
            } elseif ($p20 == 2) {
                $suma_2_p20++;
            } elseif ($p20 == 3) {
                $suma_3_p20++;
            } elseif ($p20 == 4) {
                $suma_4_p20++;
            } elseif ($p20 == 5) {
                $suma_5_p20++;
            }
            #pregunta número 21
            $p21 = $fila["p21"];
            if ($p21 == 1) {
                $suma_1_p21++;
            } elseif ($p21 == 2) {
                $suma_2_p21++;
            } elseif ($p21 == 3) {
                $suma_3_p21++;
            } elseif ($p21 == 4) {
                $suma_4_p21++;
            } elseif ($p21 == 5) {
                $suma_5_p21++;
            }
            #pregunta número 24
            $p24 = $fila["p24"];
            if ($p24 == 1) {
                $suma_1_p24++;
            } elseif ($p24 == 2) {
                $suma_2_p24++;
            } elseif ($p24 == 3) {
                $suma_3_p24++;
            } elseif ($p24 == 4) {
                $suma_4_p24++;
            } elseif ($p24 == 5) {
                $suma_5_p24++;
            }
            #pregunta número 25
            $p25 = $fila["p25"];
            if ($p25 == 1) {
                $suma_1_p25++;
            } elseif ($p25 == 2) {
                $suma_2_p25++;
            } elseif ($p25 == 3) {
                $suma_3_p25++;
            } elseif ($p25 == 4) {
                $suma_4_p25++;
            } elseif ($p25 == 5) {
                $suma_5_p25++;
            }
            #pregunta número 26
            $p26 = $fila["p26"];
            if ($p26 == 1) {
                $suma_1_p26++;
            } elseif ($p26 == 2) {
                $suma_2_p26++;
            } elseif ($p26 == 3) {
                $suma_3_p26++;
            } elseif ($p26 == 4) {
                $suma_4_p26++;
            } elseif ($p26 == 5) {
                $suma_5_p26++;
            }
        }
        if($cantidad != 0){
        //pregunta numero 2
        $suma_1_2 = ($suma_1_p2 * 100) / $cantidad;
        $suma_2_2 = ($suma_2_p2 * 100) / $cantidad;
        $suma_3_2 = ($suma_3_p2 * 100) / $cantidad;
        $suma_4_2 = ($suma_4_p2 * 100) / $cantidad;
        $suma_5_2 = ($suma_5_p2 * 100) / $cantidad;
        //pregunta numero 6
        $suma_1_6 = ($suma_1_p6 * 100) / $cantidad;
        $suma_2_6 = ($suma_2_p6 * 100) / $cantidad;
        $suma_3_6 = ($suma_3_p6 * 100) / $cantidad;
        $suma_4_6 = ($suma_4_p6 * 100) / $cantidad;
        $suma_5_6 = ($suma_5_p6 * 100) / $cantidad;
        //pregunta numero 10
        $suma_1_10 = ($suma_1_p10 * 100) / $cantidad;
        $suma_2_10 = ($suma_2_p10 * 100) / $cantidad;
        $suma_3_10 = ($suma_3_p10 * 100) / $cantidad;
        $suma_4_10 = ($suma_4_p10 * 100) / $cantidad;
        $suma_5_10 = ($suma_5_p10 * 100) / $cantidad;
        //pregunta numero 13
        $suma_1_13 = ($suma_1_p13 * 100) / $cantidad;
        $suma_2_13 = ($suma_2_p13 * 100) / $cantidad;
        $suma_3_13 = ($suma_3_p13 * 100) / $cantidad;
        $suma_4_13 = ($suma_4_p13 * 100) / $cantidad;
        $suma_5_13 = ($suma_5_p13 * 100) / $cantidad;
        //pregunta numero 14
        $suma_1_14 = ($suma_1_p14 * 100) / $cantidad;
        $suma_2_14 = ($suma_2_p14 * 100) / $cantidad;
        $suma_3_14 = ($suma_3_p14 * 100) / $cantidad;
        $suma_4_14 = ($suma_4_p14 * 100) / $cantidad;
        $suma_5_14 = ($suma_5_p14 * 100) / $cantidad;
        //pregunta numero 17
        $suma_1_17 = ($suma_1_p17 * 100) / $cantidad;
        $suma_2_17 = ($suma_2_p17 * 100) / $cantidad;
        $suma_3_17 = ($suma_3_p17 * 100) / $cantidad;
        $suma_4_17 = ($suma_4_p17 * 100) / $cantidad;
        $suma_5_17 = ($suma_5_p17 * 100) / $cantidad;
        //pregunta numero 18
        $suma_1_18 = ($suma_1_p18 * 100) / $cantidad;
        $suma_2_18 = ($suma_2_p18 * 100) / $cantidad;
        $suma_3_18 = ($suma_3_p18 * 100) / $cantidad;
        $suma_4_18 = ($suma_4_p18 * 100) / $cantidad;
        $suma_5_18 = ($suma_5_p18 * 100) / $cantidad;
        //pregunta numero 20
        $suma_1_20 = ($suma_1_p20 * 100) / $cantidad;
        $suma_2_20 = ($suma_2_p20 * 100) / $cantidad;
        $suma_3_20 = ($suma_3_p20 * 100) / $cantidad;
        $suma_4_20 = ($suma_4_p20 * 100) / $cantidad;
        $suma_5_20 = ($suma_5_p20 * 100) / $cantidad;
        //pregunta numero 21
        $suma_1_21 = ($suma_1_p21 * 100) / $cantidad;
        $suma_2_21 = ($suma_2_p21 * 100) / $cantidad;
        $suma_3_21 = ($suma_3_p21 * 100) / $cantidad;
        $suma_4_21 = ($suma_4_p21 * 100) / $cantidad;
        $suma_5_21 = ($suma_5_p21 * 100) / $cantidad;
        //pregunta numero 24
        $suma_1_24 = ($suma_1_p24 * 100) / $cantidad;
        $suma_2_24 = ($suma_2_p24 * 100) / $cantidad;
        $suma_3_24 = ($suma_3_p24 * 100) / $cantidad;
        $suma_4_24 = ($suma_4_p24 * 100) / $cantidad;
        $suma_5_24 = ($suma_5_p24 * 100) / $cantidad;
        //pregunta numero 25
        $suma_1_25 = ($suma_1_p25 * 100) / $cantidad;
        $suma_2_25 = ($suma_2_p25 * 100) / $cantidad;
        $suma_3_25 = ($suma_3_p25 * 100) / $cantidad;
        $suma_4_25 = ($suma_4_p25 * 100) / $cantidad;
        $suma_5_25 = ($suma_5_p25 * 100) / $cantidad;
        //pregunta numero 26
        $suma_1_26 = ($suma_1_p26 * 100) / $cantidad;
        $suma_2_26 = ($suma_2_p26 * 100) / $cantidad;
        $suma_3_26 = ($suma_3_p26 * 100) / $cantidad;
        $suma_4_26 = ($suma_4_p26 * 100) / $cantidad;
        $suma_5_26 = ($suma_5_p26 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        echo '<tr><td style="width:60%;">'.$pregunta["p02"].'</td>'
          
            . '<td>' . round($suma_1_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p06"].'</td>'
          
            . '<td>' . round($suma_1_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p010"].'</td>'
           
            . '<td>' . round($suma_1_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p013"].'</td>'
           
            . '<td>' . round($suma_1_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p014"].'</td>'
            
            . '<td>' . round($suma_1_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p017"].'</td>'
          
            . '<td>' . round($suma_1_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p018"].'</td>'
           
            . '<td>' . round($suma_1_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p020"].'</td>'
          
            . '<td>' . round($suma_1_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p021"].'</td>'
           
            . '<td>' . round($suma_1_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p024"].'</td>'
           
            . '<td>' . round($suma_1_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p025"].'</td>'
            
            . '<td>' . round($suma_1_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p026"].'</td>'
           
            . '<td>' . round($suma_1_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
        }else{
            return "No hay resultados para este curso";
        }
    } catch (Exception $e) {
        echo 'Excepción Capturada ' . $e->getMessage();
    }

}


function dimension_cognitivo_establecimiento($establecimiento)
{

    try {
        #pregunta número 2
        $suma_1_p2 = 0;
        $suma_2_p2 = 0;
        $suma_3_p2 = 0;
        $suma_4_p2 = 0;
        $suma_5_p2 = 0;
        #pregunta número 6
        $suma_1_p6 = 0;
        $suma_2_p6 = 0;
        $suma_3_p6 = 0;
        $suma_4_p6 = 0;
        $suma_5_p6 = 0;
        #pregunta número 10
        $suma_1_p10 = 0;
        $suma_2_p10 = 0;
        $suma_3_p10 = 0;
        $suma_4_p10 = 0;
        $suma_5_p10 = 0;
        #pregunta número 13
        $suma_1_p13 = 0;
        $suma_2_p13 = 0;
        $suma_3_p13 = 0;
        $suma_4_p13 = 0;
        $suma_5_p13 = 0;
        #pregunta número 14
        $suma_1_p14 = 0;
        $suma_2_p14 = 0;
        $suma_3_p14 = 0;
        $suma_4_p14 = 0;
        $suma_5_p14 = 0;
        #pregunta número 17
        $suma_1_p17 = 0;
        $suma_2_p17 = 0;
        $suma_3_p17 = 0;
        $suma_4_p17 = 0;
        $suma_5_p17 = 0;
        #pregunta número 18
        $suma_1_p18 = 0;
        $suma_2_p18 = 0;
        $suma_3_p18 = 0;
        $suma_4_p18 = 0;
        $suma_5_p18 = 0;
        #pregunta número 20
        $suma_1_p20 = 0;
        $suma_2_p20 = 0;
        $suma_3_p20 = 0;
        $suma_4_p20 = 0;
        $suma_5_p20 = 0;
        #pregunta número 21
        $suma_1_p21 = 0;
        $suma_2_p21 = 0;
        $suma_3_p21 = 0;
        $suma_4_p21 = 0;
        $suma_5_p21 = 0;
        #pregunta número 24
        $suma_1_p24 = 0;
        $suma_2_p24 = 0;
        $suma_3_p24 = 0;
        $suma_4_p24 = 0;
        $suma_5_p24 = 0;
        #pregunta número 25
        $suma_1_p25 = 0;
        $suma_2_p25 = 0;
        $suma_3_p25 = 0;
        $suma_4_p25 = 0;
        $suma_5_p25 = 0;
        #pregunta número 26
        $suma_1_p26 = 0;
        $suma_2_p26 = 0;
        $suma_3_p26 = 0;
        $suma_4_p26 = 0;
        $suma_5_p26 = 0;
        $con = connectDB_demos();
        $query = $con->query("SELECT
        ce_encuesta_resultado.ce_p2 AS p2,
        ce_encuesta_resultado.ce_p6 AS p6,
        ce_encuesta_resultado.ce_p10 AS p10,
        ce_encuesta_resultado.ce_p13 AS p13,
        ce_encuesta_resultado.ce_p14 AS p14,
        ce_encuesta_resultado.ce_p17 AS p17,
        ce_encuesta_resultado.ce_p18 AS p18,
        ce_encuesta_resultado.ce_p20 AS p20,
        ce_encuesta_resultado.ce_p21 AS p21,
        ce_encuesta_resultado.ce_p24 AS p24,
        ce_encuesta_resultado.ce_p25 AS p25,
        ce_encuesta_resultado.ce_p26 AS p26
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento'");
        $cantidad = $query->RowCount();

        foreach ($query as $fila) {
            #pregunta número 2
            $p2 = $fila["p2"];
            if ($p2 == 1) {
                $suma_1_p2++;
            } elseif ($p2 == 2) {
                $suma_2_p2++;
            } elseif ($p2 == 3) {
                $suma_3_p2++;
            } elseif ($p2 == 4) {
                $suma_4_p2++;
            } elseif ($p2 == 5) {
                $suma_5_p2++;
            }
            #pregunta número 6
            $p6 = $fila["p6"];
            if ($p6 == 1) {
                $suma_1_p6++;
            } elseif ($p6 == 2) {
                $suma_2_p6++;
            } elseif ($p6 == 3) {
                $suma_3_p6++;
            } elseif ($p6 == 4) {
                $suma_4_p6++;
            } elseif ($p6 == 5) {
                $suma_5_p6++;
            }
            #pregunta número 10
            $p10 = $fila["p10"];
            if ($p10 == 1) {
                $suma_1_p10++;
            } elseif ($p10 == 2) {
                $suma_2_p10++;
            } elseif ($p10 == 3) {
                $suma_3_p10++;
            } elseif ($p10 == 4) {
                $suma_4_p10++;
            } elseif ($p10 == 5) {
                $suma_5_p10++;
            }
            #pregunta número 13
            $p13 = $fila["p13"];
            if ($p13 == 1) {
                $suma_1_p13++;
            } elseif ($p13 == 2) {
                $suma_2_p13++;
            } elseif ($p13 == 3) {
                $suma_3_p13++;
            } elseif ($p13 == 4) {
                $suma_4_p13++;
            } elseif ($p13 == 5) {
                $suma_5_p13++;
            }
            #pregunta número 14
            $p14 = $fila["p14"];
            if ($p14 == 1) {
                $suma_1_p14++;
            } elseif ($p14 == 2) {
                $suma_2_p14++;
            } elseif ($p14 == 3) {
                $suma_3_p14++;
            } elseif ($p14 == 4) {
                $suma_4_p14++;
            } elseif ($p14 == 5) {
                $suma_5_p14++;
            }
            #pregunta número 17
            $p17 = $fila["p17"];
            if ($p17 == 1) {
                $suma_1_p17++;
            } elseif ($p17 == 2) {
                $suma_2_p17++;
            } elseif ($p17 == 3) {
                $suma_3_p17++;
            } elseif ($p17 == 4) {
                $suma_4_p17++;
            } elseif ($p17 == 5) {
                $suma_5_p17++;
            }
            #pregunta número 18
            $p18 = $fila["p18"];
            if ($p18 == 1) {
                $suma_1_p18++;
            } elseif ($p18 == 2) {
                $suma_2_p18++;
            } elseif ($p18 == 3) {
                $suma_3_p18++;
            } elseif ($p18 == 4) {
                $suma_4_p18++;
            } elseif ($p18 == 5) {
                $suma_5_p18++;
            }
            #pregunta número 20
            $p20 = $fila["p20"];
            if ($p20 == 1) {
                $suma_1_p20++;
            } elseif ($p20 == 2) {
                $suma_2_p20++;
            } elseif ($p20 == 3) {
                $suma_3_p20++;
            } elseif ($p20 == 4) {
                $suma_4_p20++;
            } elseif ($p20 == 5) {
                $suma_5_p20++;
            }
            #pregunta número 21
            $p21 = $fila["p21"];
            if ($p21 == 1) {
                $suma_1_p21++;
            } elseif ($p21 == 2) {
                $suma_2_p21++;
            } elseif ($p21 == 3) {
                $suma_3_p21++;
            } elseif ($p21 == 4) {
                $suma_4_p21++;
            } elseif ($p21 == 5) {
                $suma_5_p21++;
            }
            #pregunta número 24
            $p24 = $fila["p24"];
            if ($p24 == 1) {
                $suma_1_p24++;
            } elseif ($p24 == 2) {
                $suma_2_p24++;
            } elseif ($p24 == 3) {
                $suma_3_p24++;
            } elseif ($p24 == 4) {
                $suma_4_p24++;
            } elseif ($p24 == 5) {
                $suma_5_p24++;
            }
            #pregunta número 25
            $p25 = $fila["p25"];
            if ($p25 == 1) {
                $suma_1_p25++;
            } elseif ($p25 == 2) {
                $suma_2_p25++;
            } elseif ($p25 == 3) {
                $suma_3_p25++;
            } elseif ($p25 == 4) {
                $suma_4_p25++;
            } elseif ($p25 == 5) {
                $suma_5_p25++;
            }
            #pregunta número 26
            $p26 = $fila["p26"];
            if ($p26 == 1) {
                $suma_1_p26++;
            } elseif ($p26 == 2) {
                $suma_2_p26++;
            } elseif ($p26 == 3) {
                $suma_3_p26++;
            } elseif ($p26 == 4) {
                $suma_4_p26++;
            } elseif ($p26 == 5) {
                $suma_5_p26++;
            }
        }
        if($cantidad != 0){
        //pregunta numero 2
        $suma_1_2 = ($suma_1_p2 * 100) / $cantidad;
        $suma_2_2 = ($suma_2_p2 * 100) / $cantidad;
        $suma_3_2 = ($suma_3_p2 * 100) / $cantidad;
        $suma_4_2 = ($suma_4_p2 * 100) / $cantidad;
        $suma_5_2 = ($suma_5_p2 * 100) / $cantidad;
        //pregunta numero 6
        $suma_1_6 = ($suma_1_p6 * 100) / $cantidad;
        $suma_2_6 = ($suma_2_p6 * 100) / $cantidad;
        $suma_3_6 = ($suma_3_p6 * 100) / $cantidad;
        $suma_4_6 = ($suma_4_p6 * 100) / $cantidad;
        $suma_5_6 = ($suma_5_p6 * 100) / $cantidad;
        //pregunta numero 10
        $suma_1_10 = ($suma_1_p10 * 100) / $cantidad;
        $suma_2_10 = ($suma_2_p10 * 100) / $cantidad;
        $suma_3_10 = ($suma_3_p10 * 100) / $cantidad;
        $suma_4_10 = ($suma_4_p10 * 100) / $cantidad;
        $suma_5_10 = ($suma_5_p10 * 100) / $cantidad;
        //pregunta numero 13
        $suma_1_13 = ($suma_1_p13 * 100) / $cantidad;
        $suma_2_13 = ($suma_2_p13 * 100) / $cantidad;
        $suma_3_13 = ($suma_3_p13 * 100) / $cantidad;
        $suma_4_13 = ($suma_4_p13 * 100) / $cantidad;
        $suma_5_13 = ($suma_5_p13 * 100) / $cantidad;
        //pregunta numero 14
        $suma_1_14 = ($suma_1_p14 * 100) / $cantidad;
        $suma_2_14 = ($suma_2_p14 * 100) / $cantidad;
        $suma_3_14 = ($suma_3_p14 * 100) / $cantidad;
        $suma_4_14 = ($suma_4_p14 * 100) / $cantidad;
        $suma_5_14 = ($suma_5_p14 * 100) / $cantidad;
        //pregunta numero 17
        $suma_1_17 = ($suma_1_p17 * 100) / $cantidad;
        $suma_2_17 = ($suma_2_p17 * 100) / $cantidad;
        $suma_3_17 = ($suma_3_p17 * 100) / $cantidad;
        $suma_4_17 = ($suma_4_p17 * 100) / $cantidad;
        $suma_5_17 = ($suma_5_p17 * 100) / $cantidad;
        //pregunta numero 18
        $suma_1_18 = ($suma_1_p18 * 100) / $cantidad;
        $suma_2_18 = ($suma_2_p18 * 100) / $cantidad;
        $suma_3_18 = ($suma_3_p18 * 100) / $cantidad;
        $suma_4_18 = ($suma_4_p18 * 100) / $cantidad;
        $suma_5_18 = ($suma_5_p18 * 100) / $cantidad;
        //pregunta numero 20
        $suma_1_20 = ($suma_1_p20 * 100) / $cantidad;
        $suma_2_20 = ($suma_2_p20 * 100) / $cantidad;
        $suma_3_20 = ($suma_3_p20 * 100) / $cantidad;
        $suma_4_20 = ($suma_4_p20 * 100) / $cantidad;
        $suma_5_20 = ($suma_5_p20 * 100) / $cantidad;
        //pregunta numero 21
        $suma_1_21 = ($suma_1_p21 * 100) / $cantidad;
        $suma_2_21 = ($suma_2_p21 * 100) / $cantidad;
        $suma_3_21 = ($suma_3_p21 * 100) / $cantidad;
        $suma_4_21 = ($suma_4_p21 * 100) / $cantidad;
        $suma_5_21 = ($suma_5_p21 * 100) / $cantidad;
        //pregunta numero 24
        $suma_1_24 = ($suma_1_p24 * 100) / $cantidad;
        $suma_2_24 = ($suma_2_p24 * 100) / $cantidad;
        $suma_3_24 = ($suma_3_p24 * 100) / $cantidad;
        $suma_4_24 = ($suma_4_p24 * 100) / $cantidad;
        $suma_5_24 = ($suma_5_p24 * 100) / $cantidad;
        //pregunta numero 25
        $suma_1_25 = ($suma_1_p25 * 100) / $cantidad;
        $suma_2_25 = ($suma_2_p25 * 100) / $cantidad;
        $suma_3_25 = ($suma_3_p25 * 100) / $cantidad;
        $suma_4_25 = ($suma_4_p25 * 100) / $cantidad;
        $suma_5_25 = ($suma_5_p25 * 100) / $cantidad;
        //pregunta numero 26
        $suma_1_26 = ($suma_1_p26 * 100) / $cantidad;
        $suma_2_26 = ($suma_2_p26 * 100) / $cantidad;
        $suma_3_26 = ($suma_3_p26 * 100) / $cantidad;
        $suma_4_26 = ($suma_4_p26 * 100) / $cantidad;
        $suma_5_26 = ($suma_5_p26 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        echo '<tr><td style="width:60%;">'.$pregunta["p02"].'</td>'
          
            . '<td>' . round($suma_1_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p06"].'</td>'
          
            . '<td>' . round($suma_1_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p010"].'</td>'
           
            . '<td>' . round($suma_1_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p013"].'</td>'
           
            . '<td>' . round($suma_1_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p014"].'</td>'
            
            . '<td>' . round($suma_1_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p017"].'</td>'
          
            . '<td>' . round($suma_1_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p018"].'</td>'
           
            . '<td>' . round($suma_1_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p020"].'</td>'
          
            . '<td>' . round($suma_1_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p021"].'</td>'
           
            . '<td>' . round($suma_1_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p024"].'</td>'
           
            . '<td>' . round($suma_1_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p025"].'</td>'
            
            . '<td>' . round($suma_1_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p026"].'</td>'
           
            . '<td>' . round($suma_1_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
        }else{
            return "No hay resultados para este curso";
        }
    } catch (Exception $e) {
        echo 'Excepción Capturada ' . $e->getMessage();
    }

}

function dimension_cognitivo_curso_copia($establecimiento, $profesor)
{

    try {
        #pregunta número 2
        $suma_1_p2 = 0;
        $suma_2_p2 = 0;
        $suma_3_p2 = 0;
        $suma_4_p2 = 0;
        $suma_5_p2 = 0;
        #pregunta número 6
        $suma_1_p6 = 0;
        $suma_2_p6 = 0;
        $suma_3_p6 = 0;
        $suma_4_p6 = 0;
        $suma_5_p6 = 0;
        #pregunta número 10
        $suma_1_p10 = 0;
        $suma_2_p10 = 0;
        $suma_3_p10 = 0;
        $suma_4_p10 = 0;
        $suma_5_p10 = 0;
        #pregunta número 13
        $suma_1_p13 = 0;
        $suma_2_p13 = 0;
        $suma_3_p13 = 0;
        $suma_4_p13 = 0;
        $suma_5_p13 = 0;
        #pregunta número 14
        $suma_1_p14 = 0;
        $suma_2_p14 = 0;
        $suma_3_p14 = 0;
        $suma_4_p14 = 0;
        $suma_5_p14 = 0;
        #pregunta número 17
        $suma_1_p17 = 0;
        $suma_2_p17 = 0;
        $suma_3_p17 = 0;
        $suma_4_p17 = 0;
        $suma_5_p17 = 0;
        #pregunta número 18
        $suma_1_p18 = 0;
        $suma_2_p18 = 0;
        $suma_3_p18 = 0;
        $suma_4_p18 = 0;
        $suma_5_p18 = 0;
        #pregunta número 20
        $suma_1_p20 = 0;
        $suma_2_p20 = 0;
        $suma_3_p20 = 0;
        $suma_4_p20 = 0;
        $suma_5_p20 = 0;
        #pregunta número 21
        $suma_1_p21 = 0;
        $suma_2_p21 = 0;
        $suma_3_p21 = 0;
        $suma_4_p21 = 0;
        $suma_5_p21 = 0;
        #pregunta número 24
        $suma_1_p24 = 0;
        $suma_2_p24 = 0;
        $suma_3_p24 = 0;
        $suma_4_p24 = 0;
        $suma_5_p24 = 0;
        #pregunta número 25
        $suma_1_p25 = 0;
        $suma_2_p25 = 0;
        $suma_3_p25 = 0;
        $suma_4_p25 = 0;
        $suma_5_p25 = 0;
        #pregunta número 26
        $suma_1_p26 = 0;
        $suma_2_p26 = 0;
        $suma_3_p26 = 0;
        $suma_4_p26 = 0;
        $suma_5_p26 = 0;
        $con = connectDB_demos();
        $query = $con->query("SELECT
        ce_encuesta_resultado.ce_p2 AS p2,
        ce_encuesta_resultado.ce_p6 AS p6,
        ce_encuesta_resultado.ce_p10 AS p10,
        ce_encuesta_resultado.ce_p13 AS p13,
        ce_encuesta_resultado.ce_p14 AS p14,
        ce_encuesta_resultado.ce_p17 AS p17,
        ce_encuesta_resultado.ce_p18 AS p18,
        ce_encuesta_resultado.ce_p20 AS p20,
        ce_encuesta_resultado.ce_p21 AS p21,
        ce_encuesta_resultado.ce_p24 AS p24,
        ce_encuesta_resultado.ce_p25 AS p25,
        ce_encuesta_resultado.ce_p26 AS p26
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND  ce_participantes.ce_docente_id_ce_docente = '$profesor'");
        $cantidad = $query->RowCount();

        foreach ($query as $fila) {
            #pregunta número 2
            $p2 = $fila["p2"];
            if ($p2 == 1) {
                $suma_1_p2++;
            } elseif ($p2 == 2) {
                $suma_2_p2++;
            } elseif ($p2 == 3) {
                $suma_3_p2++;
            } elseif ($p2 == 4) {
                $suma_4_p2++;
            } elseif ($p2 == 5) {
                $suma_5_p2++;
            }
            #pregunta número 6
            $p6 = $fila["p6"];
            if ($p6 == 1) {
                $suma_1_p6++;
            } elseif ($p6 == 2) {
                $suma_2_p6++;
            } elseif ($p6 == 3) {
                $suma_3_p6++;
            } elseif ($p6 == 4) {
                $suma_4_p6++;
            } elseif ($p6 == 5) {
                $suma_5_p6++;
            }
            #pregunta número 10
            $p10 = $fila["p10"];
            if ($p10 == 1) {
                $suma_1_p10++;
            } elseif ($p10 == 2) {
                $suma_2_p10++;
            } elseif ($p10 == 3) {
                $suma_3_p10++;
            } elseif ($p10 == 4) {
                $suma_4_p10++;
            } elseif ($p10 == 5) {
                $suma_5_p10++;
            }
            #pregunta número 13
            $p13 = $fila["p13"];
            if ($p13 == 1) {
                $suma_1_p13++;
            } elseif ($p13 == 2) {
                $suma_2_p13++;
            } elseif ($p13 == 3) {
                $suma_3_p13++;
            } elseif ($p13 == 4) {
                $suma_4_p13++;
            } elseif ($p13 == 5) {
                $suma_5_p13++;
            }
            #pregunta número 14
            $p14 = $fila["p14"];
            if ($p14 == 1) {
                $suma_1_p14++;
            } elseif ($p14 == 2) {
                $suma_2_p14++;
            } elseif ($p14 == 3) {
                $suma_3_p14++;
            } elseif ($p14 == 4) {
                $suma_4_p14++;
            } elseif ($p14 == 5) {
                $suma_5_p14++;
            }
            #pregunta número 17
            $p17 = $fila["p17"];
            if ($p17 == 1) {
                $suma_1_p17++;
            } elseif ($p17 == 2) {
                $suma_2_p17++;
            } elseif ($p17 == 3) {
                $suma_3_p17++;
            } elseif ($p17 == 4) {
                $suma_4_p17++;
            } elseif ($p17 == 5) {
                $suma_5_p17++;
            }
            #pregunta número 18
            $p18 = $fila["p18"];
            if ($p18 == 1) {
                $suma_1_p18++;
            } elseif ($p18 == 2) {
                $suma_2_p18++;
            } elseif ($p18 == 3) {
                $suma_3_p18++;
            } elseif ($p18 == 4) {
                $suma_4_p18++;
            } elseif ($p18 == 5) {
                $suma_5_p18++;
            }
            #pregunta número 20
            $p20 = $fila["p20"];
            if ($p20 == 1) {
                $suma_1_p20++;
            } elseif ($p20 == 2) {
                $suma_2_p20++;
            } elseif ($p20 == 3) {
                $suma_3_p20++;
            } elseif ($p20 == 4) {
                $suma_4_p20++;
            } elseif ($p20 == 5) {
                $suma_5_p20++;
            }
            #pregunta número 21
            $p21 = $fila["p21"];
            if ($p21 == 1) {
                $suma_1_p21++;
            } elseif ($p21 == 2) {
                $suma_2_p21++;
            } elseif ($p21 == 3) {
                $suma_3_p21++;
            } elseif ($p21 == 4) {
                $suma_4_p21++;
            } elseif ($p21 == 5) {
                $suma_5_p21++;
            }
            #pregunta número 24
            $p24 = $fila["p24"];
            if ($p24 == 1) {
                $suma_1_p24++;
            } elseif ($p24 == 2) {
                $suma_2_p24++;
            } elseif ($p24 == 3) {
                $suma_3_p24++;
            } elseif ($p24 == 4) {
                $suma_4_p24++;
            } elseif ($p24 == 5) {
                $suma_5_p24++;
            }
            #pregunta número 25
            $p25 = $fila["p25"];
            if ($p25 == 1) {
                $suma_1_p25++;
            } elseif ($p25 == 2) {
                $suma_2_p25++;
            } elseif ($p25 == 3) {
                $suma_3_p25++;
            } elseif ($p25 == 4) {
                $suma_4_p25++;
            } elseif ($p25 == 5) {
                $suma_5_p25++;
            }
            #pregunta número 26
            $p26 = $fila["p26"];
            if ($p26 == 1) {
                $suma_1_p26++;
            } elseif ($p26 == 2) {
                $suma_2_p26++;
            } elseif ($p26 == 3) {
                $suma_3_p26++;
            } elseif ($p26 == 4) {
                $suma_4_p26++;
            } elseif ($p26 == 5) {
                $suma_5_p26++;
            }
        }
        //pregunta numero 2
        $suma_1_2 = ($suma_1_p2 * 100) / $cantidad;
        $suma_2_2 = ($suma_2_p2 * 100) / $cantidad;
        $suma_3_2 = ($suma_3_p2 * 100) / $cantidad;
        $suma_4_2 = ($suma_4_p2 * 100) / $cantidad;
        $suma_5_2 = ($suma_5_p2 * 100) / $cantidad;
        //pregunta numero 6
        $suma_1_6 = ($suma_1_p6 * 100) / $cantidad;
        $suma_2_6 = ($suma_2_p6 * 100) / $cantidad;
        $suma_3_6 = ($suma_3_p6 * 100) / $cantidad;
        $suma_4_6 = ($suma_4_p6 * 100) / $cantidad;
        $suma_5_6 = ($suma_5_p6 * 100) / $cantidad;
        //pregunta numero 10
        $suma_1_10 = ($suma_1_p10 * 100) / $cantidad;
        $suma_2_10 = ($suma_2_p10 * 100) / $cantidad;
        $suma_3_10 = ($suma_3_p10 * 100) / $cantidad;
        $suma_4_10 = ($suma_4_p10 * 100) / $cantidad;
        $suma_5_10 = ($suma_5_p10 * 100) / $cantidad;
        //pregunta numero 13
        $suma_1_13 = ($suma_1_p13 * 100) / $cantidad;
        $suma_2_13 = ($suma_2_p13 * 100) / $cantidad;
        $suma_3_13 = ($suma_3_p13 * 100) / $cantidad;
        $suma_4_13 = ($suma_4_p13 * 100) / $cantidad;
        $suma_5_13 = ($suma_5_p13 * 100) / $cantidad;
        //pregunta numero 14
        $suma_1_14 = ($suma_1_p14 * 100) / $cantidad;
        $suma_2_14 = ($suma_2_p14 * 100) / $cantidad;
        $suma_3_14 = ($suma_3_p14 * 100) / $cantidad;
        $suma_4_14 = ($suma_4_p14 * 100) / $cantidad;
        $suma_5_14 = ($suma_5_p14 * 100) / $cantidad;
        //pregunta numero 17
        $suma_1_17 = ($suma_1_p17 * 100) / $cantidad;
        $suma_2_17 = ($suma_2_p17 * 100) / $cantidad;
        $suma_3_17 = ($suma_3_p17 * 100) / $cantidad;
        $suma_4_17 = ($suma_4_p17 * 100) / $cantidad;
        $suma_5_17 = ($suma_5_p17 * 100) / $cantidad;
        //pregunta numero 18
        $suma_1_18 = ($suma_1_p18 * 100) / $cantidad;
        $suma_2_18 = ($suma_2_p18 * 100) / $cantidad;
        $suma_3_18 = ($suma_3_p18 * 100) / $cantidad;
        $suma_4_18 = ($suma_4_p18 * 100) / $cantidad;
        $suma_5_18 = ($suma_5_p18 * 100) / $cantidad;
        //pregunta numero 20
        $suma_1_20 = ($suma_1_p20 * 100) / $cantidad;
        $suma_2_20 = ($suma_2_p20 * 100) / $cantidad;
        $suma_3_20 = ($suma_3_p20 * 100) / $cantidad;
        $suma_4_20 = ($suma_4_p20 * 100) / $cantidad;
        $suma_5_20 = ($suma_5_p20 * 100) / $cantidad;
        //pregunta numero 21
        $suma_1_21 = ($suma_1_p21 * 100) / $cantidad;
        $suma_2_21 = ($suma_2_p21 * 100) / $cantidad;
        $suma_3_21 = ($suma_3_p21 * 100) / $cantidad;
        $suma_4_21 = ($suma_4_p21 * 100) / $cantidad;
        $suma_5_21 = ($suma_5_p21 * 100) / $cantidad;
        //pregunta numero 24
        $suma_1_24 = ($suma_1_p24 * 100) / $cantidad;
        $suma_2_24 = ($suma_2_p24 * 100) / $cantidad;
        $suma_3_24 = ($suma_3_p24 * 100) / $cantidad;
        $suma_4_24 = ($suma_4_p24 * 100) / $cantidad;
        $suma_5_24 = ($suma_5_p24 * 100) / $cantidad;
        //pregunta numero 25
        $suma_1_25 = ($suma_1_p25 * 100) / $cantidad;
        $suma_2_25 = ($suma_2_p25 * 100) / $cantidad;
        $suma_3_25 = ($suma_3_p25 * 100) / $cantidad;
        $suma_4_25 = ($suma_4_p25 * 100) / $cantidad;
        $suma_5_25 = ($suma_5_p25 * 100) / $cantidad;
        //pregunta numero 26
        $suma_1_26 = ($suma_1_p26 * 100) / $cantidad;
        $suma_2_26 = ($suma_2_p26 * 100) / $cantidad;
        $suma_3_26 = ($suma_3_p26 * 100) / $cantidad;
        $suma_4_26 = ($suma_4_p26 * 100) / $cantidad;
        $suma_5_26 = ($suma_5_p26 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        $dato = '<tr><td style="width:60%;border: 1px solid #fc455c;">'.$pregunta["p02"].'</td>'
          
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_2, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .=  '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p06"].'</td>'
          
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_6, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p010"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_10, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p013"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_13, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p014"].'</td>'
            
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_14, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p017"].'</td>'
          
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_17, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p018"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_18, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p020"].'</td>'
          
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_20, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p021"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_21, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p024"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_24, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p025"].'</td>'
            
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_25, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p026"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_26, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            return $dato;

    } catch (Exception $e) {
        echo 'Excepción Capturada ' . $e->getMessage();
    }

}

//Calculas la dimension apoyo familiar
function dimension_apoyo_familiar_curso($establecimiento, $profesor)
{
    try {
        //pregunta numero 30
        $suma_1_p30 = 0;
        $suma_2_p30 = 0;
        $suma_3_p30 = 0;
        $suma_4_p30 = 0;
        $suma_5_p30 = 0;

        //pregunta numero 31
        $suma_1_p31 = 0;
        $suma_2_p31 = 0;
        $suma_3_p31 = 0;
        $suma_4_p31 = 0;
        $suma_5_p31 = 0;

        //pregunta numero 32
        $suma_1_p32 = 0;
        $suma_2_p32 = 0;
        $suma_3_p32 = 0;
        $suma_4_p32 = 0;
        $suma_5_p32 = 0;

        $con = connectDB_demos();
        $query = $con->query("SELECT ce_encuesta_resultado.ce_p30 AS p30,
        ce_encuesta_resultado.ce_p31 AS p31,
        ce_encuesta_resultado.ce_p32 AS p32
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND  ce_participantes.ce_docente_id_ce_docente = '$profesor'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            $p30 = $fila["p30"];
            if ($p30 == 1) {
                $suma_1_p30++;
            } elseif ($p30 == 2) {
                $suma_2_p30++;
            } elseif ($p30 == 3) {
                $suma_3_p30++;
            } elseif ($p30 == 4) {
                $suma_4_p30++;
            } elseif ($p30 == 5) {
                $suma_5_p30++;
            }

            $p31 = $fila["p31"];
            if ($p31 == 1) {
                $suma_1_p31++;
            } elseif ($p31 == 2) {
                $suma_2_p31++;
            } elseif ($p31 == 3) {
                $suma_3_p31++;
            } elseif ($p31 == 4) {
                $suma_4_p31++;
            } elseif ($p31 == 5) {
                $suma_5_p31++;
            }

            $p32 = $fila["p32"];
            if ($p32 == 1) {
                $suma_1_p32++;
            } elseif ($p32 == 2) {
                $suma_2_p32++;
            } elseif ($p32 == 3) {
                $suma_3_p32++;
            } elseif ($p32 == 4) {
                $suma_4_p32++;
            } elseif ($p32 == 5) {
                $suma_5_p32++;
            }
           
        }
        if($cantidad != 0){

        

        //pregunta numero 30
        $suma_1_30 = ($suma_1_p30 * 100) / $cantidad;
        $suma_2_30 = ($suma_2_p30 * 100) / $cantidad;
        $suma_3_30 = ($suma_3_p30 * 100) / $cantidad;
        $suma_4_30 = ($suma_4_p30 * 100) / $cantidad;
        $suma_5_30 = ($suma_5_p30 * 100) / $cantidad;

        //pregunta numero 31
        $suma_1_31 = ($suma_1_p31 * 100) / $cantidad;
        $suma_2_31 = ($suma_2_p31 * 100) / $cantidad;
        $suma_3_31 = ($suma_3_p31 * 100) / $cantidad;
        $suma_4_31 = ($suma_4_p31 * 100) / $cantidad;
        $suma_5_31 = ($suma_5_p31 * 100) / $cantidad;

        //pregunta numero 32
        $suma_1_32 = ($suma_1_p32 * 100) / $cantidad;
        $suma_2_32 = ($suma_2_p32 * 100) / $cantidad;
        $suma_3_32 = ($suma_3_p32 * 100) / $cantidad;
        $suma_4_32 = ($suma_4_p32 * 100) / $cantidad;
        $suma_5_32 = ($suma_5_p32 * 100) / $cantidad;
        $pregunta = preguntas_compromiso_escolar();
        echo '<tr><td>'.$pregunta["p030"].'</td>'
            
            . '<td>' . round($suma_1_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
        echo '<tr><td>'.$pregunta["p031"].'</td>'
            
            . '<td>' . round($suma_1_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
        echo '<tr><td>'.$pregunta["p032"].'</td>'
           
            . '<td>' . round($suma_1_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        }else{
            return "No hay resultados para este curso";
        }
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }


}

//Calculas la dimension apoyo familiar establecimiento
function dimension_apoyo_familiar_establecimiento($establecimiento)
{
    try {
        //pregunta numero 30
        $suma_1_p30 = 0;
        $suma_2_p30 = 0;
        $suma_3_p30 = 0;
        $suma_4_p30 = 0;
        $suma_5_p30 = 0;

        //pregunta numero 31
        $suma_1_p31 = 0;
        $suma_2_p31 = 0;
        $suma_3_p31 = 0;
        $suma_4_p31 = 0;
        $suma_5_p31 = 0;

        //pregunta numero 32
        $suma_1_p32 = 0;
        $suma_2_p32 = 0;
        $suma_3_p32 = 0;
        $suma_4_p32 = 0;
        $suma_5_p32 = 0;

        $con = connectDB_demos();
        $query = $con->query("SELECT ce_encuesta_resultado.ce_p30 AS p30,
        ce_encuesta_resultado.ce_p31 AS p31,
        ce_encuesta_resultado.ce_p32 AS p32
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            $p30 = $fila["p30"];
            if ($p30 == 1) {
                $suma_1_p30++;
            } elseif ($p30 == 2) {
                $suma_2_p30++;
            } elseif ($p30 == 3) {
                $suma_3_p30++;
            } elseif ($p30 == 4) {
                $suma_4_p30++;
            } elseif ($p30 == 5) {
                $suma_5_p30++;
            }

            $p31 = $fila["p31"];
            if ($p31 == 1) {
                $suma_1_p31++;
            } elseif ($p31 == 2) {
                $suma_2_p31++;
            } elseif ($p31 == 3) {
                $suma_3_p31++;
            } elseif ($p31 == 4) {
                $suma_4_p31++;
            } elseif ($p31 == 5) {
                $suma_5_p31++;
            }

            $p32 = $fila["p32"];
            if ($p32 == 1) {
                $suma_1_p32++;
            } elseif ($p32 == 2) {
                $suma_2_p32++;
            } elseif ($p32 == 3) {
                $suma_3_p32++;
            } elseif ($p32 == 4) {
                $suma_4_p32++;
            } elseif ($p32 == 5) {
                $suma_5_p32++;
            }
           
        }
        if($cantidad != 0){

        

        //pregunta numero 30
        $suma_1_30 = ($suma_1_p30 * 100) / $cantidad;
        $suma_2_30 = ($suma_2_p30 * 100) / $cantidad;
        $suma_3_30 = ($suma_3_p30 * 100) / $cantidad;
        $suma_4_30 = ($suma_4_p30 * 100) / $cantidad;
        $suma_5_30 = ($suma_5_p30 * 100) / $cantidad;

        //pregunta numero 31
        $suma_1_31 = ($suma_1_p31 * 100) / $cantidad;
        $suma_2_31 = ($suma_2_p31 * 100) / $cantidad;
        $suma_3_31 = ($suma_3_p31 * 100) / $cantidad;
        $suma_4_31 = ($suma_4_p31 * 100) / $cantidad;
        $suma_5_31 = ($suma_5_p31 * 100) / $cantidad;

        //pregunta numero 32
        $suma_1_32 = ($suma_1_p32 * 100) / $cantidad;
        $suma_2_32 = ($suma_2_p32 * 100) / $cantidad;
        $suma_3_32 = ($suma_3_p32 * 100) / $cantidad;
        $suma_4_32 = ($suma_4_p32 * 100) / $cantidad;
        $suma_5_32 = ($suma_5_p32 * 100) / $cantidad;
        $pregunta = preguntas_compromiso_escolar();
        echo '<tr><td>'.$pregunta["p030"].'</td>'
            
            . '<td>' . round($suma_1_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
        echo '<tr><td>'.$pregunta["p031"].'</td>'
            
            . '<td>' . round($suma_1_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
        echo '<tr><td>'.$pregunta["p032"].'</td>'
           
            . '<td>' . round($suma_1_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        }else{
            return "No hay resultados para este curso";
        }
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }


}

//Calculas la dimension apoyo familiar
function dimension_apoyo_familiar_curso_copia($establecimiento, $profesor)
{
    try {
        //pregunta numero 30
        $suma_1_p30 = 0;
        $suma_2_p30 = 0;
        $suma_3_p30 = 0;
        $suma_4_p30 = 0;
        $suma_5_p30 = 0;

        //pregunta numero 31
        $suma_1_p31 = 0;
        $suma_2_p31 = 0;
        $suma_3_p31 = 0;
        $suma_4_p31 = 0;
        $suma_5_p31 = 0;

        //pregunta numero 32
        $suma_1_p32 = 0;
        $suma_2_p32 = 0;
        $suma_3_p32 = 0;
        $suma_4_p32 = 0;
        $suma_5_p32 = 0;

        $con = connectDB_demos();
        $query = $con->query("SELECT ce_encuesta_resultado.ce_p30 AS p30,
        ce_encuesta_resultado.ce_p31 AS p31,
        ce_encuesta_resultado.ce_p32 AS p32
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND  ce_participantes.ce_docente_id_ce_docente = '$profesor'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            $p30 = $fila["p30"];
            if ($p30 == 1) {
                $suma_1_p30++;
            } elseif ($p30 == 2) {
                $suma_2_p30++;
            } elseif ($p30 == 3) {
                $suma_3_p30++;
            } elseif ($p30 == 4) {
                $suma_4_p30++;
            } elseif ($p30 == 5) {
                $suma_5_p30++;
            }

            $p31 = $fila["p31"];
            if ($p31 == 1) {
                $suma_1_p31++;
            } elseif ($p31 == 2) {
                $suma_2_p31++;
            } elseif ($p31 == 3) {
                $suma_3_p31++;
            } elseif ($p31 == 4) {
                $suma_4_p31++;
            } elseif ($p31 == 5) {
                $suma_5_p31++;
            }

            $p32 = $fila["p32"];
            if ($p32 == 1) {
                $suma_1_p32++;
            } elseif ($p32 == 2) {
                $suma_2_p32++;
            } elseif ($p32 == 3) {
                $suma_3_p32++;
            } elseif ($p32 == 4) {
                $suma_4_p32++;
            } elseif ($p32 == 5) {
                $suma_5_p32++;
            }
           
        }

        //pregunta numero 30
        $suma_1_30 = ($suma_1_p30 * 100) / $cantidad;
        $suma_2_30 = ($suma_2_p30 * 100) / $cantidad;
        $suma_3_30 = ($suma_3_p30 * 100) / $cantidad;
        $suma_4_30 = ($suma_4_p30 * 100) / $cantidad;
        $suma_5_30 = ($suma_5_p30 * 100) / $cantidad;

        //pregunta numero 31
        $suma_1_31 = ($suma_1_p31 * 100) / $cantidad;
        $suma_2_31 = ($suma_2_p31 * 100) / $cantidad;
        $suma_3_31 = ($suma_3_p31 * 100) / $cantidad;
        $suma_4_31 = ($suma_4_p31 * 100) / $cantidad;
        $suma_5_31 = ($suma_5_p31 * 100) / $cantidad;

        //pregunta numero 32
        $suma_1_32 = ($suma_1_p32 * 100) / $cantidad;
        $suma_2_32 = ($suma_2_p32 * 100) / $cantidad;
        $suma_3_32 = ($suma_3_p32 * 100) / $cantidad;
        $suma_4_32 = ($suma_4_p32 * 100) / $cantidad;
        $suma_5_32 = ($suma_5_p32 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        $dato= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p030"].'</td>'
            
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_30, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
            $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p031"].'</td>'
            
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_31, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
            $dato.= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p032"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_32, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            return $dato;

    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }


}

function dimension_profesores_curso($establecimiento, $profesor)
{
    try {
        //pregunta numero 33
        $suma_1_p33 = 0;
        $suma_2_p33 = 0;
        $suma_3_p33 = 0;
        $suma_4_p33 = 0;
        $suma_5_p33 = 0;
        //pregunta numero 34
        $suma_1_p34 = 0;
        $suma_2_p34 = 0;
        $suma_3_p34 = 0;
        $suma_4_p34 = 0;
        $suma_5_p34 = 0;
        //pregunta numero 35
        $suma_1_p35 = 0;
        $suma_2_p35 = 0;
        $suma_3_p35 = 0;
        $suma_4_p35 = 0;
        $suma_5_p35 = 0;
        //pregunta numero 36
        $suma_1_p36 = 0;
        $suma_2_p36 = 0;
        $suma_3_p36 = 0;
        $suma_4_p36 = 0;
        $suma_5_p36 = 0;
        //pregunta numero 37
        $suma_1_p37 = 0;
        $suma_2_p37 = 0;
        $suma_3_p37 = 0;
        $suma_4_p37 = 0;
        $suma_5_p37 = 0;
        //pregunta numero 38
        $suma_1_p38 = 0;
        $suma_2_p38 = 0;
        $suma_3_p38 = 0;
        $suma_4_p38 = 0;
        $suma_5_p38 = 0;
        //pregunta numero 39
        $suma_1_p39 = 0;
        $suma_2_p39 = 0;
        $suma_3_p39 = 0;
        $suma_4_p39 = 0;
        $suma_5_p39 = 0;
        //pregunta numero 40
        $suma_1_p40 = 0;
        $suma_2_p40 = 0;
        $suma_3_p40 = 0;
        $suma_4_p40 = 0;
        $suma_5_p40 = 0;
        $con = connectDB_demos();
        $query = $con->query("SELECT
        ce_encuesta_resultado.ce_p33 AS p33,
        ce_encuesta_resultado.ce_p34 AS p34,
        ce_encuesta_resultado.ce_p35 AS p35,
        ce_encuesta_resultado.ce_p36 AS p36,
        ce_encuesta_resultado.ce_p37 AS p37,
        ce_encuesta_resultado.ce_p38 AS p38,
        ce_encuesta_resultado.ce_p39 AS p39, 
        ce_encuesta_resultado.ce_p40 AS p40
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND  ce_participantes.ce_docente_id_ce_docente = '$profesor'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            #pregunta número 33
            $p33 = $fila["p33"];
            if ($p33 == 1) {
                $suma_1_p33++;
            } elseif ($p33 == 2) {
                $suma_2_p33++;
            } elseif ($p33 == 3) {
                $suma_3_p33++;
            } elseif ($p33 == 4) {
                $suma_4_p33++;
            } elseif ($p33 == 5) {
                $suma_5_p33++;
            }
            #pregunta número 34
            $p34 = $fila["p34"];
            if ($p34 == 1) {
                $suma_1_p34++;
            } elseif ($p34 == 2) {
                $suma_2_p34++;
            } elseif ($p34 == 3) {
                $suma_3_p34++;
            } elseif ($p34 == 4) {
                $suma_4_p34++;
            } elseif ($p34 == 5) {
                $suma_5_p34++;
            }
            #pregunta número 35
            $p35 = $fila["p35"];
            if ($p35 == 1) {
                $suma_1_p35++;
            } elseif ($p35 == 2) {
                $suma_2_p35++;
            } elseif ($p35 == 3) {
                $suma_3_p35++;
            } elseif ($p35 == 4) {
                $suma_4_p35++;
            } elseif ($p35 == 5) {
                $suma_5_p35++;
            }
            #pregunta número 36
            $p36 = $fila["p36"];
            if ($p36 == 1) {
                $suma_1_p36++;
            } elseif ($p36 == 2) {
                $suma_2_p36++;
            } elseif ($p36 == 3) {
                $suma_3_p36++;
            } elseif ($p36 == 4) {
                $suma_4_p36++;
            } elseif ($p36 == 5) {
                $suma_5_p36++;
            }
            #pregunta número 37
            $p37 = $fila["p37"];
            if ($p37 == 1) {
                $suma_1_p37++;
            } elseif ($p37 == 2) {
                $suma_2_p37++;
            } elseif ($p37 == 3) {
                $suma_3_p37++;
            } elseif ($p37 == 4) {
                $suma_4_p37++;
            } elseif ($p37 == 5) {
                $suma_5_p37++;
            }
            #pregunta número 38
            $p38 = $fila["p38"];
            if ($p38 == 1) {
                $suma_1_p38++;
            } elseif ($p38 == 2) {
                $suma_2_p38++;
            } elseif ($p38 == 3) {
                $suma_3_p38++;
            } elseif ($p38 == 4) {
                $suma_4_p38++;
            } elseif ($p38 == 5) {
                $suma_5_p38++;
            }
            #pregunta número 39
            $p39 = $fila["p39"];
            if ($p39 == 1) {
                $suma_1_p39++;
            } elseif ($p39 == 2) {
                $suma_2_p39++;
            } elseif ($p39 == 3) {
                $suma_3_p39++;
            } elseif ($p39 == 4) {
                $suma_4_p39++;
            } elseif ($p39 == 5) {
                $suma_5_p39++;
            }
            #pregunta número 40
            $p40 = $fila["p40"];
            if ($p40 == 1) {
                $suma_1_p40++;
            } elseif ($p40 == 2) {
                $suma_2_p40++;
            } elseif ($p40 == 3) {
                $suma_3_p40++;
            } elseif ($p40 == 4) {
                $suma_4_p40++;
            } elseif ($p40 == 5) {
                $suma_5_p40++;
            }
        }
        if( $cantidad != 0){
        //pregunta numero 33
        $suma_1_33 = ($suma_1_p33 * 100) / $cantidad;
        $suma_2_33 = ($suma_2_p33 * 100) / $cantidad;
        $suma_3_33 = ($suma_3_p33 * 100) / $cantidad;
        $suma_4_33 = ($suma_4_p33 * 100) / $cantidad;
        $suma_5_33 = ($suma_5_p33 * 100) / $cantidad;
        //pregunta numero 34
        $suma_1_34 = ($suma_1_p34 * 100) / $cantidad;
        $suma_2_34 = ($suma_2_p34 * 100) / $cantidad;
        $suma_3_34 = ($suma_3_p34 * 100) / $cantidad;
        $suma_4_34 = ($suma_4_p34 * 100) / $cantidad;
        $suma_5_34 = ($suma_5_p34 * 100) / $cantidad;
        //pregunta numero 35
        $suma_1_35 = ($suma_1_p35 * 100) / $cantidad;
        $suma_2_35 = ($suma_2_p35 * 100) / $cantidad;
        $suma_3_35 = ($suma_3_p35 * 100) / $cantidad;
        $suma_4_35 = ($suma_4_p35 * 100) / $cantidad;
        $suma_5_35 = ($suma_5_p35 * 100) / $cantidad;
        //pregunta numero 36
        $suma_1_36 = ($suma_1_p36 * 100) / $cantidad;
        $suma_2_36 = ($suma_2_p36 * 100) / $cantidad;
        $suma_3_36 = ($suma_3_p36 * 100) / $cantidad;
        $suma_4_36 = ($suma_4_p36 * 100) / $cantidad;
        $suma_5_36 = ($suma_5_p36 * 100) / $cantidad;
        //pregunta numero 37
        $suma_1_37 = ($suma_1_p37 * 100) / $cantidad;
        $suma_2_37 = ($suma_2_p37 * 100) / $cantidad;
        $suma_3_37 = ($suma_3_p37 * 100) / $cantidad;
        $suma_4_37 = ($suma_4_p37 * 100) / $cantidad;
        $suma_5_37 = ($suma_5_p37 * 100) / $cantidad;
        //pregunta numero 38
        $suma_1_38 = ($suma_1_p38 * 100) / $cantidad;
        $suma_2_38 = ($suma_2_p38 * 100) / $cantidad;
        $suma_3_38 = ($suma_3_p38 * 100) / $cantidad;
        $suma_4_38 = ($suma_4_p38 * 100) / $cantidad;
        $suma_5_38 = ($suma_5_p38 * 100) / $cantidad;
        //pregunta numero 39
        $suma_1_39 = ($suma_1_p39 * 100) / $cantidad;
        $suma_2_39 = ($suma_2_p39 * 100) / $cantidad;
        $suma_3_39 = ($suma_3_p39 * 100) / $cantidad;
        $suma_4_39 = ($suma_4_p39 * 100) / $cantidad;
        $suma_5_39 = ($suma_5_p39 * 100) / $cantidad;
        //pregunta numero 40
        $suma_1_40 = ($suma_1_p40 * 100) / $cantidad;
        $suma_2_40 = ($suma_2_p40 * 100) / $cantidad;
        $suma_3_40 = ($suma_3_p40 * 100) / $cantidad;
        $suma_4_40 = ($suma_4_p40 * 100) / $cantidad;
        $suma_5_40 = ($suma_5_p40 * 100) / $cantidad;
        $pregunta = preguntas_compromiso_escolar();
        echo '<tr><td>'.$pregunta["p033"].'</td>'
         
            . '<td>' . round($suma_1_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p034"].'</td>'
           
            . '<td>' . round($suma_1_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p035"].'</td>'
          
            . '<td>' . round($suma_1_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p036"].'</td>'
           
            . '<td>' . round($suma_1_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p037"].'</td>'
           
            . '<td>' . round($suma_1_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p038"].'</td>'
          
            . '<td>' . round($suma_1_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p039"].'</td>'
           
            . '<td>' . round($suma_1_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p040"].'</td>'
           
            . '<td>' . round($suma_1_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
        }else{
            return "No hay resultados para este curso";
        }
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }
}

function dimension_profesores_establecimiento($establecimiento)
{
    try {
        //pregunta numero 33
        $suma_1_p33 = 0;
        $suma_2_p33 = 0;
        $suma_3_p33 = 0;
        $suma_4_p33 = 0;
        $suma_5_p33 = 0;
        //pregunta numero 34
        $suma_1_p34 = 0;
        $suma_2_p34 = 0;
        $suma_3_p34 = 0;
        $suma_4_p34 = 0;
        $suma_5_p34 = 0;
        //pregunta numero 35
        $suma_1_p35 = 0;
        $suma_2_p35 = 0;
        $suma_3_p35 = 0;
        $suma_4_p35 = 0;
        $suma_5_p35 = 0;
        //pregunta numero 36
        $suma_1_p36 = 0;
        $suma_2_p36 = 0;
        $suma_3_p36 = 0;
        $suma_4_p36 = 0;
        $suma_5_p36 = 0;
        //pregunta numero 37
        $suma_1_p37 = 0;
        $suma_2_p37 = 0;
        $suma_3_p37 = 0;
        $suma_4_p37 = 0;
        $suma_5_p37 = 0;
        //pregunta numero 38
        $suma_1_p38 = 0;
        $suma_2_p38 = 0;
        $suma_3_p38 = 0;
        $suma_4_p38 = 0;
        $suma_5_p38 = 0;
        //pregunta numero 39
        $suma_1_p39 = 0;
        $suma_2_p39 = 0;
        $suma_3_p39 = 0;
        $suma_4_p39 = 0;
        $suma_5_p39 = 0;
        //pregunta numero 40
        $suma_1_p40 = 0;
        $suma_2_p40 = 0;
        $suma_3_p40 = 0;
        $suma_4_p40 = 0;
        $suma_5_p40 = 0;
        $con = connectDB_demos();
        $query = $con->query("SELECT
        ce_encuesta_resultado.ce_p33 AS p33,
        ce_encuesta_resultado.ce_p34 AS p34,
        ce_encuesta_resultado.ce_p35 AS p35,
        ce_encuesta_resultado.ce_p36 AS p36,
        ce_encuesta_resultado.ce_p37 AS p37,
        ce_encuesta_resultado.ce_p38 AS p38,
        ce_encuesta_resultado.ce_p39 AS p39, 
        ce_encuesta_resultado.ce_p40 AS p40
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' ");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            #pregunta número 33
            $p33 = $fila["p33"];
            if ($p33 == 1) {
                $suma_1_p33++;
            } elseif ($p33 == 2) {
                $suma_2_p33++;
            } elseif ($p33 == 3) {
                $suma_3_p33++;
            } elseif ($p33 == 4) {
                $suma_4_p33++;
            } elseif ($p33 == 5) {
                $suma_5_p33++;
            }
            #pregunta número 34
            $p34 = $fila["p34"];
            if ($p34 == 1) {
                $suma_1_p34++;
            } elseif ($p34 == 2) {
                $suma_2_p34++;
            } elseif ($p34 == 3) {
                $suma_3_p34++;
            } elseif ($p34 == 4) {
                $suma_4_p34++;
            } elseif ($p34 == 5) {
                $suma_5_p34++;
            }
            #pregunta número 35
            $p35 = $fila["p35"];
            if ($p35 == 1) {
                $suma_1_p35++;
            } elseif ($p35 == 2) {
                $suma_2_p35++;
            } elseif ($p35 == 3) {
                $suma_3_p35++;
            } elseif ($p35 == 4) {
                $suma_4_p35++;
            } elseif ($p35 == 5) {
                $suma_5_p35++;
            }
            #pregunta número 36
            $p36 = $fila["p36"];
            if ($p36 == 1) {
                $suma_1_p36++;
            } elseif ($p36 == 2) {
                $suma_2_p36++;
            } elseif ($p36 == 3) {
                $suma_3_p36++;
            } elseif ($p36 == 4) {
                $suma_4_p36++;
            } elseif ($p36 == 5) {
                $suma_5_p36++;
            }
            #pregunta número 37
            $p37 = $fila["p37"];
            if ($p37 == 1) {
                $suma_1_p37++;
            } elseif ($p37 == 2) {
                $suma_2_p37++;
            } elseif ($p37 == 3) {
                $suma_3_p37++;
            } elseif ($p37 == 4) {
                $suma_4_p37++;
            } elseif ($p37 == 5) {
                $suma_5_p37++;
            }
            #pregunta número 38
            $p38 = $fila["p38"];
            if ($p38 == 1) {
                $suma_1_p38++;
            } elseif ($p38 == 2) {
                $suma_2_p38++;
            } elseif ($p38 == 3) {
                $suma_3_p38++;
            } elseif ($p38 == 4) {
                $suma_4_p38++;
            } elseif ($p38 == 5) {
                $suma_5_p38++;
            }
            #pregunta número 39
            $p39 = $fila["p39"];
            if ($p39 == 1) {
                $suma_1_p39++;
            } elseif ($p39 == 2) {
                $suma_2_p39++;
            } elseif ($p39 == 3) {
                $suma_3_p39++;
            } elseif ($p39 == 4) {
                $suma_4_p39++;
            } elseif ($p39 == 5) {
                $suma_5_p39++;
            }
            #pregunta número 40
            $p40 = $fila["p40"];
            if ($p40 == 1) {
                $suma_1_p40++;
            } elseif ($p40 == 2) {
                $suma_2_p40++;
            } elseif ($p40 == 3) {
                $suma_3_p40++;
            } elseif ($p40 == 4) {
                $suma_4_p40++;
            } elseif ($p40 == 5) {
                $suma_5_p40++;
            }
        }
        if( $cantidad != 0){
        //pregunta numero 33
        $suma_1_33 = ($suma_1_p33 * 100) / $cantidad;
        $suma_2_33 = ($suma_2_p33 * 100) / $cantidad;
        $suma_3_33 = ($suma_3_p33 * 100) / $cantidad;
        $suma_4_33 = ($suma_4_p33 * 100) / $cantidad;
        $suma_5_33 = ($suma_5_p33 * 100) / $cantidad;
        //pregunta numero 34
        $suma_1_34 = ($suma_1_p34 * 100) / $cantidad;
        $suma_2_34 = ($suma_2_p34 * 100) / $cantidad;
        $suma_3_34 = ($suma_3_p34 * 100) / $cantidad;
        $suma_4_34 = ($suma_4_p34 * 100) / $cantidad;
        $suma_5_34 = ($suma_5_p34 * 100) / $cantidad;
        //pregunta numero 35
        $suma_1_35 = ($suma_1_p35 * 100) / $cantidad;
        $suma_2_35 = ($suma_2_p35 * 100) / $cantidad;
        $suma_3_35 = ($suma_3_p35 * 100) / $cantidad;
        $suma_4_35 = ($suma_4_p35 * 100) / $cantidad;
        $suma_5_35 = ($suma_5_p35 * 100) / $cantidad;
        //pregunta numero 36
        $suma_1_36 = ($suma_1_p36 * 100) / $cantidad;
        $suma_2_36 = ($suma_2_p36 * 100) / $cantidad;
        $suma_3_36 = ($suma_3_p36 * 100) / $cantidad;
        $suma_4_36 = ($suma_4_p36 * 100) / $cantidad;
        $suma_5_36 = ($suma_5_p36 * 100) / $cantidad;
        //pregunta numero 37
        $suma_1_37 = ($suma_1_p37 * 100) / $cantidad;
        $suma_2_37 = ($suma_2_p37 * 100) / $cantidad;
        $suma_3_37 = ($suma_3_p37 * 100) / $cantidad;
        $suma_4_37 = ($suma_4_p37 * 100) / $cantidad;
        $suma_5_37 = ($suma_5_p37 * 100) / $cantidad;
        //pregunta numero 38
        $suma_1_38 = ($suma_1_p38 * 100) / $cantidad;
        $suma_2_38 = ($suma_2_p38 * 100) / $cantidad;
        $suma_3_38 = ($suma_3_p38 * 100) / $cantidad;
        $suma_4_38 = ($suma_4_p38 * 100) / $cantidad;
        $suma_5_38 = ($suma_5_p38 * 100) / $cantidad;
        //pregunta numero 39
        $suma_1_39 = ($suma_1_p39 * 100) / $cantidad;
        $suma_2_39 = ($suma_2_p39 * 100) / $cantidad;
        $suma_3_39 = ($suma_3_p39 * 100) / $cantidad;
        $suma_4_39 = ($suma_4_p39 * 100) / $cantidad;
        $suma_5_39 = ($suma_5_p39 * 100) / $cantidad;
        //pregunta numero 40
        $suma_1_40 = ($suma_1_p40 * 100) / $cantidad;
        $suma_2_40 = ($suma_2_p40 * 100) / $cantidad;
        $suma_3_40 = ($suma_3_p40 * 100) / $cantidad;
        $suma_4_40 = ($suma_4_p40 * 100) / $cantidad;
        $suma_5_40 = ($suma_5_p40 * 100) / $cantidad;
        $pregunta = preguntas_compromiso_escolar();
        echo '<tr><td>'.$pregunta["p033"].'</td>'
         
            . '<td>' . round($suma_1_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p034"].'</td>'
           
            . '<td>' . round($suma_1_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p035"].'</td>'
          
            . '<td>' . round($suma_1_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p036"].'</td>'
           
            . '<td>' . round($suma_1_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p037"].'</td>'
           
            . '<td>' . round($suma_1_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p038"].'</td>'
          
            . '<td>' . round($suma_1_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p039"].'</td>'
           
            . '<td>' . round($suma_1_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p040"].'</td>'
           
            . '<td>' . round($suma_1_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';
        }else{
            return "No hay resultados para este curso";
        }
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }
}

function dimension_profesores_curso_copia($establecimiento, $profesor)
{
    try {
        //pregunta numero 33
        $suma_1_p33 = 0;
        $suma_2_p33 = 0;
        $suma_3_p33 = 0;
        $suma_4_p33 = 0;
        $suma_5_p33 = 0;
        //pregunta numero 34
        $suma_1_p34 = 0;
        $suma_2_p34 = 0;
        $suma_3_p34 = 0;
        $suma_4_p34 = 0;
        $suma_5_p34 = 0;
        //pregunta numero 35
        $suma_1_p35 = 0;
        $suma_2_p35 = 0;
        $suma_3_p35 = 0;
        $suma_4_p35 = 0;
        $suma_5_p35 = 0;
        //pregunta numero 36
        $suma_1_p36 = 0;
        $suma_2_p36 = 0;
        $suma_3_p36 = 0;
        $suma_4_p36 = 0;
        $suma_5_p36 = 0;
        //pregunta numero 37
        $suma_1_p37 = 0;
        $suma_2_p37 = 0;
        $suma_3_p37 = 0;
        $suma_4_p37 = 0;
        $suma_5_p37 = 0;
        //pregunta numero 38
        $suma_1_p38 = 0;
        $suma_2_p38 = 0;
        $suma_3_p38 = 0;
        $suma_4_p38 = 0;
        $suma_5_p38 = 0;
        //pregunta numero 39
        $suma_1_p39 = 0;
        $suma_2_p39 = 0;
        $suma_3_p39 = 0;
        $suma_4_p39 = 0;
        $suma_5_p39 = 0;
        //pregunta numero 40
        $suma_1_p40 = 0;
        $suma_2_p40 = 0;
        $suma_3_p40 = 0;
        $suma_4_p40 = 0;
        $suma_5_p40 = 0;
        $con = connectDB_demos();
        $query = $con->query("SELECT
        ce_encuesta_resultado.ce_p33 AS p33,
        ce_encuesta_resultado.ce_p34 AS p34,
        ce_encuesta_resultado.ce_p35 AS p35,
        ce_encuesta_resultado.ce_p36 AS p36,
        ce_encuesta_resultado.ce_p37 AS p37,
        ce_encuesta_resultado.ce_p38 AS p38,
        ce_encuesta_resultado.ce_p39 AS p39, 
        ce_encuesta_resultado.ce_p40 AS p40
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND  ce_participantes.ce_docente_id_ce_docente = '$profesor'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            #pregunta número 33
            $p33 = $fila["p33"];
            if ($p33 == 1) {
                $suma_1_p33++;
            } elseif ($p33 == 2) {
                $suma_2_p33++;
            } elseif ($p33 == 3) {
                $suma_3_p33++;
            } elseif ($p33 == 4) {
                $suma_4_p33++;
            } elseif ($p33 == 5) {
                $suma_5_p33++;
            }
            #pregunta número 34
            $p34 = $fila["p34"];
            if ($p34 == 1) {
                $suma_1_p34++;
            } elseif ($p34 == 2) {
                $suma_2_p34++;
            } elseif ($p34 == 3) {
                $suma_3_p34++;
            } elseif ($p34 == 4) {
                $suma_4_p34++;
            } elseif ($p34 == 5) {
                $suma_5_p34++;
            }
            #pregunta número 35
            $p35 = $fila["p35"];
            if ($p35 == 1) {
                $suma_1_p35++;
            } elseif ($p35 == 2) {
                $suma_2_p35++;
            } elseif ($p35 == 3) {
                $suma_3_p35++;
            } elseif ($p35 == 4) {
                $suma_4_p35++;
            } elseif ($p35 == 5) {
                $suma_5_p35++;
            }
            #pregunta número 36
            $p36 = $fila["p36"];
            if ($p36 == 1) {
                $suma_1_p36++;
            } elseif ($p36 == 2) {
                $suma_2_p36++;
            } elseif ($p36 == 3) {
                $suma_3_p36++;
            } elseif ($p36 == 4) {
                $suma_4_p36++;
            } elseif ($p36 == 5) {
                $suma_5_p36++;
            }
            #pregunta número 37
            $p37 = $fila["p37"];
            if ($p37 == 1) {
                $suma_1_p37++;
            } elseif ($p37 == 2) {
                $suma_2_p37++;
            } elseif ($p37 == 3) {
                $suma_3_p37++;
            } elseif ($p37 == 4) {
                $suma_4_p37++;
            } elseif ($p37 == 5) {
                $suma_5_p37++;
            }
            #pregunta número 38
            $p38 = $fila["p38"];
            if ($p38 == 1) {
                $suma_1_p38++;
            } elseif ($p38 == 2) {
                $suma_2_p38++;
            } elseif ($p38 == 3) {
                $suma_3_p38++;
            } elseif ($p38 == 4) {
                $suma_4_p38++;
            } elseif ($p38 == 5) {
                $suma_5_p38++;
            }
            #pregunta número 39
            $p39 = $fila["p39"];
            if ($p39 == 1) {
                $suma_1_p39++;
            } elseif ($p39 == 2) {
                $suma_2_p39++;
            } elseif ($p39 == 3) {
                $suma_3_p39++;
            } elseif ($p39 == 4) {
                $suma_4_p39++;
            } elseif ($p39 == 5) {
                $suma_5_p39++;
            }
            #pregunta número 40
            $p40 = $fila["p40"];
            if ($p40 == 1) {
                $suma_1_p40++;
            } elseif ($p40 == 2) {
                $suma_2_p40++;
            } elseif ($p40 == 3) {
                $suma_3_p40++;
            } elseif ($p40 == 4) {
                $suma_4_p40++;
            } elseif ($p40 == 5) {
                $suma_5_p40++;
            }
        }
        //pregunta numero 33
        $suma_1_33 = ($suma_1_p33 * 100) / $cantidad;
        $suma_2_33 = ($suma_2_p33 * 100) / $cantidad;
        $suma_3_33 = ($suma_3_p33 * 100) / $cantidad;
        $suma_4_33 = ($suma_4_p33 * 100) / $cantidad;
        $suma_5_33 = ($suma_5_p33 * 100) / $cantidad;
        //pregunta numero 34
        $suma_1_34 = ($suma_1_p34 * 100) / $cantidad;
        $suma_2_34 = ($suma_2_p34 * 100) / $cantidad;
        $suma_3_34 = ($suma_3_p34 * 100) / $cantidad;
        $suma_4_34 = ($suma_4_p34 * 100) / $cantidad;
        $suma_5_34 = ($suma_5_p34 * 100) / $cantidad;
        //pregunta numero 35
        $suma_1_35 = ($suma_1_p35 * 100) / $cantidad;
        $suma_2_35 = ($suma_2_p35 * 100) / $cantidad;
        $suma_3_35 = ($suma_3_p35 * 100) / $cantidad;
        $suma_4_35 = ($suma_4_p35 * 100) / $cantidad;
        $suma_5_35 = ($suma_5_p35 * 100) / $cantidad;
        //pregunta numero 36
        $suma_1_36 = ($suma_1_p36 * 100) / $cantidad;
        $suma_2_36 = ($suma_2_p36 * 100) / $cantidad;
        $suma_3_36 = ($suma_3_p36 * 100) / $cantidad;
        $suma_4_36 = ($suma_4_p36 * 100) / $cantidad;
        $suma_5_36 = ($suma_5_p36 * 100) / $cantidad;
        //pregunta numero 37
        $suma_1_37 = ($suma_1_p37 * 100) / $cantidad;
        $suma_2_37 = ($suma_2_p37 * 100) / $cantidad;
        $suma_3_37 = ($suma_3_p37 * 100) / $cantidad;
        $suma_4_37 = ($suma_4_p37 * 100) / $cantidad;
        $suma_5_37 = ($suma_5_p37 * 100) / $cantidad;
        //pregunta numero 38
        $suma_1_38 = ($suma_1_p38 * 100) / $cantidad;
        $suma_2_38 = ($suma_2_p38 * 100) / $cantidad;
        $suma_3_38 = ($suma_3_p38 * 100) / $cantidad;
        $suma_4_38 = ($suma_4_p38 * 100) / $cantidad;
        $suma_5_38 = ($suma_5_p38 * 100) / $cantidad;
        //pregunta numero 39
        $suma_1_39 = ($suma_1_p39 * 100) / $cantidad;
        $suma_2_39 = ($suma_2_p39 * 100) / $cantidad;
        $suma_3_39 = ($suma_3_p39 * 100) / $cantidad;
        $suma_4_39 = ($suma_4_p39 * 100) / $cantidad;
        $suma_5_39 = ($suma_5_p39 * 100) / $cantidad;
        //pregunta numero 40
        $suma_1_40 = ($suma_1_p40 * 100) / $cantidad;
        $suma_2_40 = ($suma_2_p40 * 100) / $cantidad;
        $suma_3_40 = ($suma_3_p40 * 100) / $cantidad;
        $suma_4_40 = ($suma_4_p40 * 100) / $cantidad;
        $suma_5_40 = ($suma_5_p40 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        $dato = '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p033"].'</td>'
         
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_33, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p034"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_34, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p035"].'</td>'
          
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_35, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p036"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_36, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p037"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_37, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p038"].'</td>'
          
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_38, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p039"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_39, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p040"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_40, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            return  $dato;

    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }
}

function dimension_pares_curso($establecimiento, $profesor)
{
    try {
        //pregunta numero 41
        $suma_1_p41 = 0;
        $suma_2_p41 = 0;
        $suma_3_p41 = 0;
        $suma_4_p41 = 0;
        $suma_5_p41 = 0;
        //pregunta numero 42
        $suma_1_p42 = 0;
        $suma_2_p42 = 0;
        $suma_3_p42 = 0;
        $suma_4_p42 = 0;
        $suma_5_p42 = 0;
        //pregunta numero 43
        $suma_1_p43 = 0;
        $suma_2_p43 = 0;
        $suma_3_p43 = 0;
        $suma_4_p43 = 0;
        $suma_5_p43 = 0;
        //pregunta numero 44
        $suma_1_p44 = 0;
        $suma_2_p44 = 0;
        $suma_3_p44 = 0;
        $suma_4_p44 = 0;
        $suma_5_p44 = 0;
        //pregunta numero 45
        $suma_1_p45 = 0;
        $suma_2_p45 = 0;
        $suma_3_p45 = 0;
        $suma_4_p45 = 0;
        $suma_5_p45 = 0;
        //pregunta numero 46
        $suma_1_p46 = 0;
        $suma_2_p46 = 0;
        $suma_3_p46 = 0;
        $suma_4_p46 = 0;
        $suma_5_p46 = 0;
        //pregunta numero 47
        $suma_1_p47 = 0;
        $suma_2_p47 = 0;
        $suma_3_p47 = 0;
        $suma_4_p47 = 0;
        $suma_5_p47 = 0;

        $con = connectDB_demos();
        $query = $con->query("SELECT  
        ce_encuesta_resultado.ce_p41 AS p41,
        ce_encuesta_resultado.ce_p42 AS p42, 
        ce_encuesta_resultado.ce_p43 AS p43,
        ce_encuesta_resultado.ce_p44 AS p44,
        ce_encuesta_resultado.ce_p45 AS p45,
        ce_encuesta_resultado.ce_p46 AS p46,
        ce_encuesta_resultado.ce_p47 AS p47
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND  ce_participantes.ce_docente_id_ce_docente = '$profesor'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            #pregunta número 41
            $p41 = $fila["p41"];
            if ($p41 == 1) {
                $suma_1_p41++;
            } elseif ($p41 == 2) {
                $suma_2_p41++;
            } elseif ($p41 == 3) {
                $suma_3_p41++;
            } elseif ($p41 == 4) {
                $suma_4_p41++;
            } elseif ($p41 == 5) {
                $suma_5_p41++;
            }
            #pregunta número 42
            $p42 = $fila["p42"];
            if ($p42 == 1) {
                $suma_1_p42++;
            } elseif ($p42 == 2) {
                $suma_2_p42++;
            } elseif ($p42 == 3) {
                $suma_3_p42++;
            } elseif ($p42 == 4) {
                $suma_4_p42++;
            } elseif ($p42 == 5) {
                $suma_5_p42++;
            }
            #pregunta número 43
            $p43 = $fila["p43"];
            if ($p43 == 1) {
                $suma_1_p43++;
            } elseif ($p43 == 2) {
                $suma_2_p43++;
            } elseif ($p43 == 3) {
                $suma_3_p43++;
            } elseif ($p43 == 4) {
                $suma_4_p43++;
            } elseif ($p43 == 5) {
                $suma_5_p43++;
            }
            #pregunta número 44
            $p44 = $fila["p44"];
            if ($p44 == 1) {
                $suma_1_p44++;
            } elseif ($p44 == 2) {
                $suma_2_p44++;
            } elseif ($p44 == 3) {
                $suma_3_p44++;
            } elseif ($p44 == 4) {
                $suma_4_p44++;
            } elseif ($p44 == 5) {
                $suma_5_p44++;
            }
            #pregunta número 45
            $p45 = $fila["p45"];
            if ($p45 == 1) {
                $suma_1_p45++;
            } elseif ($p45 == 2) {
                $suma_2_p45++;
            } elseif ($p45 == 3) {
                $suma_3_p45++;
            } elseif ($p45 == 4) {
                $suma_4_p45++;
            } elseif ($p45 == 5) {
                $suma_5_p45++;
            }
            #pregunta número 46
            $p46 = $fila["p46"];
            if ($p46 == 1) {
                $suma_1_p46++;
            } elseif ($p46 == 2) {
                $suma_2_p46++;
            } elseif ($p46 == 3) {
                $suma_3_p46++;
            } elseif ($p46 == 4) {
                $suma_4_p46++;
            } elseif ($p46 == 5) {
                $suma_5_p46++;
            }
            #pregunta número 47
            $p47 = $fila["p47"];
            if ($p47 == 1) {
                $suma_1_p47++;
            } elseif ($p47 == 2) {
                $suma_2_p47++;
            } elseif ($p47 == 3) {
                $suma_3_p47++;
            } elseif ($p47 == 4) {
                $suma_4_p47++;
            } elseif ($p47 == 5) {
                $suma_5_p47++;
            }

        }
        if($cantidad != 0){

        
        //pregunta numero 41
        $suma_1_41 = ($suma_1_p41 * 100) / $cantidad;
        $suma_2_41 = ($suma_2_p41 * 100) / $cantidad;
        $suma_3_41 = ($suma_3_p41 * 100) / $cantidad;
        $suma_4_41 = ($suma_4_p41 * 100) / $cantidad;
        $suma_5_41 = ($suma_5_p41 * 100) / $cantidad;
        //pregunta numero 42
        $suma_1_42 = ($suma_1_p42 * 100) / $cantidad;
        $suma_2_42 = ($suma_2_p42 * 100) / $cantidad;
        $suma_3_42 = ($suma_3_p42 * 100) / $cantidad;
        $suma_4_42 = ($suma_4_p42 * 100) / $cantidad;
        $suma_5_42 = ($suma_5_p42 * 100) / $cantidad;
        //pregunta numero 43
        $suma_1_43 = ($suma_1_p43 * 100) / $cantidad;
        $suma_2_43 = ($suma_2_p43 * 100) / $cantidad;
        $suma_3_43 = ($suma_3_p43 * 100) / $cantidad;
        $suma_4_43 = ($suma_4_p43 * 100) / $cantidad;
        $suma_5_43 = ($suma_5_p43 * 100) / $cantidad;
        //pregunta numero 44
        $suma_1_44 = ($suma_1_p44 * 100) / $cantidad;
        $suma_2_44 = ($suma_2_p44 * 100) / $cantidad;
        $suma_3_44 = ($suma_3_p44 * 100) / $cantidad;
        $suma_4_44 = ($suma_4_p44 * 100) / $cantidad;
        $suma_5_44 = ($suma_5_p44 * 100) / $cantidad;
        //pregunta numero 45
        $suma_1_45 = ($suma_1_p45 * 100) / $cantidad;
        $suma_2_45 = ($suma_2_p45 * 100) / $cantidad;
        $suma_3_45 = ($suma_3_p45 * 100) / $cantidad;
        $suma_4_45 = ($suma_4_p45 * 100) / $cantidad;
        $suma_5_45 = ($suma_5_p45 * 100) / $cantidad;
        //pregunta numero 46
        $suma_1_46 = ($suma_1_p46 * 100) / $cantidad;
        $suma_2_46 = ($suma_2_p46 * 100) / $cantidad;
        $suma_3_46 = ($suma_3_p46 * 100) / $cantidad;
        $suma_4_46 = ($suma_4_p46 * 100) / $cantidad;
        $suma_5_46 = ($suma_5_p46 * 100) / $cantidad;
        //pregunta numero 47
        $suma_1_47 = ($suma_1_p47 * 100) / $cantidad;
        $suma_2_47 = ($suma_2_p47 * 100) / $cantidad;
        $suma_3_47 = ($suma_3_p47 * 100) / $cantidad;
        $suma_4_47 = ($suma_4_p47 * 100) / $cantidad;
        $suma_5_47 = ($suma_5_p47 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();
        
        echo '<tr><td>'.$pregunta["p041"].'</td>'
           
            . '<td>' . round($suma_5_41, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_41, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_41, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_41, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_41, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p042"].'</td>'
         
            . '<td>' . round($suma_5_42, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_42, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_42, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_42, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_42, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p043"].'</td>'
           
            . '<td>' . round($suma_5_43, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_43, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_43, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_43, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_43, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p044"].'</td>'
            
            . '<td>' . round($suma_5_44, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_44, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_44, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_44, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_44, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p045"].'</td>'
           
            . '<td>' . round($suma_5_45, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_45, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_45, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_45, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_45, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p046"].'</td>'
          
            . '<td>' . round($suma_5_46, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_46, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_46, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_46, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_46, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p047"].'</td>'
           
            . '<td>' . round($suma_5_47, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_47, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_47, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_47, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_47, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
        }else{
            return "No hay resultados para este curso";
        }
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }

}

function dimension_pares_establecimiento($establecimiento)
{
    try {
        //pregunta numero 41
        $suma_1_p41 = 0;
        $suma_2_p41 = 0;
        $suma_3_p41 = 0;
        $suma_4_p41 = 0;
        $suma_5_p41 = 0;
        //pregunta numero 42
        $suma_1_p42 = 0;
        $suma_2_p42 = 0;
        $suma_3_p42 = 0;
        $suma_4_p42 = 0;
        $suma_5_p42 = 0;
        //pregunta numero 43
        $suma_1_p43 = 0;
        $suma_2_p43 = 0;
        $suma_3_p43 = 0;
        $suma_4_p43 = 0;
        $suma_5_p43 = 0;
        //pregunta numero 44
        $suma_1_p44 = 0;
        $suma_2_p44 = 0;
        $suma_3_p44 = 0;
        $suma_4_p44 = 0;
        $suma_5_p44 = 0;
        //pregunta numero 45
        $suma_1_p45 = 0;
        $suma_2_p45 = 0;
        $suma_3_p45 = 0;
        $suma_4_p45 = 0;
        $suma_5_p45 = 0;
        //pregunta numero 46
        $suma_1_p46 = 0;
        $suma_2_p46 = 0;
        $suma_3_p46 = 0;
        $suma_4_p46 = 0;
        $suma_5_p46 = 0;
        //pregunta numero 47
        $suma_1_p47 = 0;
        $suma_2_p47 = 0;
        $suma_3_p47 = 0;
        $suma_4_p47 = 0;
        $suma_5_p47 = 0;

        $con = connectDB_demos();
        $query = $con->query("SELECT  
        ce_encuesta_resultado.ce_p41 AS p41,
        ce_encuesta_resultado.ce_p42 AS p42, 
        ce_encuesta_resultado.ce_p43 AS p43,
        ce_encuesta_resultado.ce_p44 AS p44,
        ce_encuesta_resultado.ce_p45 AS p45,
        ce_encuesta_resultado.ce_p46 AS p46,
        ce_encuesta_resultado.ce_p47 AS p47
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            #pregunta número 41
            $p41 = $fila["p41"];
            if ($p41 == 1) {
                $suma_1_p41++;
            } elseif ($p41 == 2) {
                $suma_2_p41++;
            } elseif ($p41 == 3) {
                $suma_3_p41++;
            } elseif ($p41 == 4) {
                $suma_4_p41++;
            } elseif ($p41 == 5) {
                $suma_5_p41++;
            }
            #pregunta número 42
            $p42 = $fila["p42"];
            if ($p42 == 1) {
                $suma_1_p42++;
            } elseif ($p42 == 2) {
                $suma_2_p42++;
            } elseif ($p42 == 3) {
                $suma_3_p42++;
            } elseif ($p42 == 4) {
                $suma_4_p42++;
            } elseif ($p42 == 5) {
                $suma_5_p42++;
            }
            #pregunta número 43
            $p43 = $fila["p43"];
            if ($p43 == 1) {
                $suma_1_p43++;
            } elseif ($p43 == 2) {
                $suma_2_p43++;
            } elseif ($p43 == 3) {
                $suma_3_p43++;
            } elseif ($p43 == 4) {
                $suma_4_p43++;
            } elseif ($p43 == 5) {
                $suma_5_p43++;
            }
            #pregunta número 44
            $p44 = $fila["p44"];
            if ($p44 == 1) {
                $suma_1_p44++;
            } elseif ($p44 == 2) {
                $suma_2_p44++;
            } elseif ($p44 == 3) {
                $suma_3_p44++;
            } elseif ($p44 == 4) {
                $suma_4_p44++;
            } elseif ($p44 == 5) {
                $suma_5_p44++;
            }
            #pregunta número 45
            $p45 = $fila["p45"];
            if ($p45 == 1) {
                $suma_1_p45++;
            } elseif ($p45 == 2) {
                $suma_2_p45++;
            } elseif ($p45 == 3) {
                $suma_3_p45++;
            } elseif ($p45 == 4) {
                $suma_4_p45++;
            } elseif ($p45 == 5) {
                $suma_5_p45++;
            }
            #pregunta número 46
            $p46 = $fila["p46"];
            if ($p46 == 1) {
                $suma_1_p46++;
            } elseif ($p46 == 2) {
                $suma_2_p46++;
            } elseif ($p46 == 3) {
                $suma_3_p46++;
            } elseif ($p46 == 4) {
                $suma_4_p46++;
            } elseif ($p46 == 5) {
                $suma_5_p46++;
            }
            #pregunta número 47
            $p47 = $fila["p47"];
            if ($p47 == 1) {
                $suma_1_p47++;
            } elseif ($p47 == 2) {
                $suma_2_p47++;
            } elseif ($p47 == 3) {
                $suma_3_p47++;
            } elseif ($p47 == 4) {
                $suma_4_p47++;
            } elseif ($p47 == 5) {
                $suma_5_p47++;
            }

        }
        if($cantidad != 0){

        
        //pregunta numero 41
        $suma_1_41 = ($suma_1_p41 * 100) / $cantidad;
        $suma_2_41 = ($suma_2_p41 * 100) / $cantidad;
        $suma_3_41 = ($suma_3_p41 * 100) / $cantidad;
        $suma_4_41 = ($suma_4_p41 * 100) / $cantidad;
        $suma_5_41 = ($suma_5_p41 * 100) / $cantidad;
        //pregunta numero 42
        $suma_1_42 = ($suma_1_p42 * 100) / $cantidad;
        $suma_2_42 = ($suma_2_p42 * 100) / $cantidad;
        $suma_3_42 = ($suma_3_p42 * 100) / $cantidad;
        $suma_4_42 = ($suma_4_p42 * 100) / $cantidad;
        $suma_5_42 = ($suma_5_p42 * 100) / $cantidad;
        //pregunta numero 43
        $suma_1_43 = ($suma_1_p43 * 100) / $cantidad;
        $suma_2_43 = ($suma_2_p43 * 100) / $cantidad;
        $suma_3_43 = ($suma_3_p43 * 100) / $cantidad;
        $suma_4_43 = ($suma_4_p43 * 100) / $cantidad;
        $suma_5_43 = ($suma_5_p43 * 100) / $cantidad;
        //pregunta numero 44
        $suma_1_44 = ($suma_1_p44 * 100) / $cantidad;
        $suma_2_44 = ($suma_2_p44 * 100) / $cantidad;
        $suma_3_44 = ($suma_3_p44 * 100) / $cantidad;
        $suma_4_44 = ($suma_4_p44 * 100) / $cantidad;
        $suma_5_44 = ($suma_5_p44 * 100) / $cantidad;
        //pregunta numero 45
        $suma_1_45 = ($suma_1_p45 * 100) / $cantidad;
        $suma_2_45 = ($suma_2_p45 * 100) / $cantidad;
        $suma_3_45 = ($suma_3_p45 * 100) / $cantidad;
        $suma_4_45 = ($suma_4_p45 * 100) / $cantidad;
        $suma_5_45 = ($suma_5_p45 * 100) / $cantidad;
        //pregunta numero 46
        $suma_1_46 = ($suma_1_p46 * 100) / $cantidad;
        $suma_2_46 = ($suma_2_p46 * 100) / $cantidad;
        $suma_3_46 = ($suma_3_p46 * 100) / $cantidad;
        $suma_4_46 = ($suma_4_p46 * 100) / $cantidad;
        $suma_5_46 = ($suma_5_p46 * 100) / $cantidad;
        //pregunta numero 47
        $suma_1_47 = ($suma_1_p47 * 100) / $cantidad;
        $suma_2_47 = ($suma_2_p47 * 100) / $cantidad;
        $suma_3_47 = ($suma_3_p47 * 100) / $cantidad;
        $suma_4_47 = ($suma_4_p47 * 100) / $cantidad;
        $suma_5_47 = ($suma_5_p47 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();
        
        echo '<tr><td>'.$pregunta["p041"].'</td>'
           
            . '<td>' . round($suma_5_41, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_41, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_41, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_41, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_41, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p042"].'</td>'
         
            . '<td>' . round($suma_5_42, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_42, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_42, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_42, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_42, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p043"].'</td>'
           
            . '<td>' . round($suma_5_43, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_43, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_43, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_43, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_43, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p044"].'</td>'
            
            . '<td>' . round($suma_5_44, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_44, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_44, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_44, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_44, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p045"].'</td>'
           
            . '<td>' . round($suma_5_45, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_45, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_45, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_45, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_45, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p046"].'</td>'
          
            . '<td>' . round($suma_5_46, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_46, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_46, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_46, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_46, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p047"].'</td>'
           
            . '<td>' . round($suma_5_47, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_47, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_47, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_47, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_47, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
        }else{
            return "No hay resultados para este curso";
        }
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }

}
function dimension_pares_curso_copia($establecimiento, $profesor)
{
    try {
        //pregunta numero 41
        $suma_1_p41 = 0;
        $suma_2_p41 = 0;
        $suma_3_p41 = 0;
        $suma_4_p41 = 0;
        $suma_5_p41 = 0;
        //pregunta numero 42
        $suma_1_p42 = 0;
        $suma_2_p42 = 0;
        $suma_3_p42 = 0;
        $suma_4_p42 = 0;
        $suma_5_p42 = 0;
        //pregunta numero 43
        $suma_1_p43 = 0;
        $suma_2_p43 = 0;
        $suma_3_p43 = 0;
        $suma_4_p43 = 0;
        $suma_5_p43 = 0;
        //pregunta numero 44
        $suma_1_p44 = 0;
        $suma_2_p44 = 0;
        $suma_3_p44 = 0;
        $suma_4_p44 = 0;
        $suma_5_p44 = 0;
        //pregunta numero 45
        $suma_1_p45 = 0;
        $suma_2_p45 = 0;
        $suma_3_p45 = 0;
        $suma_4_p45 = 0;
        $suma_5_p45 = 0;
        //pregunta numero 46
        $suma_1_p46 = 0;
        $suma_2_p46 = 0;
        $suma_3_p46 = 0;
        $suma_4_p46 = 0;
        $suma_5_p46 = 0;
        //pregunta numero 47
        $suma_1_p47 = 0;
        $suma_2_p47 = 0;
        $suma_3_p47 = 0;
        $suma_4_p47 = 0;
        $suma_5_p47 = 0;

        $con = connectDB_demos();
        $query = $con->query("SELECT  
        ce_encuesta_resultado.ce_p41 AS p41,
        ce_encuesta_resultado.ce_p42 AS p42, 
        ce_encuesta_resultado.ce_p43 AS p43,
        ce_encuesta_resultado.ce_p44 AS p44,
        ce_encuesta_resultado.ce_p45 AS p45,
        ce_encuesta_resultado.ce_p46 AS p46,
        ce_encuesta_resultado.ce_p47 AS p47
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND  ce_participantes.ce_docente_id_ce_docente = '$profesor'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            #pregunta número 41
            $p41 = $fila["p41"];
            if ($p41 == 1) {
                $suma_1_p41++;
            } elseif ($p41 == 2) {
                $suma_2_p41++;
            } elseif ($p41 == 3) {
                $suma_3_p41++;
            } elseif ($p41 == 4) {
                $suma_4_p41++;
            } elseif ($p41 == 5) {
                $suma_5_p41++;
            }
            #pregunta número 42
            $p42 = $fila["p42"];
            if ($p42 == 1) {
                $suma_1_p42++;
            } elseif ($p42 == 2) {
                $suma_2_p42++;
            } elseif ($p42 == 3) {
                $suma_3_p42++;
            } elseif ($p42 == 4) {
                $suma_4_p42++;
            } elseif ($p42 == 5) {
                $suma_5_p42++;
            }
            #pregunta número 43
            $p43 = $fila["p43"];
            if ($p43 == 1) {
                $suma_1_p43++;
            } elseif ($p43 == 2) {
                $suma_2_p43++;
            } elseif ($p43 == 3) {
                $suma_3_p43++;
            } elseif ($p43 == 4) {
                $suma_4_p43++;
            } elseif ($p43 == 5) {
                $suma_5_p43++;
            }
            #pregunta número 44
            $p44 = $fila["p44"];
            if ($p44 == 1) {
                $suma_1_p44++;
            } elseif ($p44 == 2) {
                $suma_2_p44++;
            } elseif ($p44 == 3) {
                $suma_3_p44++;
            } elseif ($p44 == 4) {
                $suma_4_p44++;
            } elseif ($p44 == 5) {
                $suma_5_p44++;
            }
            #pregunta número 45
            $p45 = $fila["p45"];
            if ($p45 == 1) {
                $suma_1_p45++;
            } elseif ($p45 == 2) {
                $suma_2_p45++;
            } elseif ($p45 == 3) {
                $suma_3_p45++;
            } elseif ($p45 == 4) {
                $suma_4_p45++;
            } elseif ($p45 == 5) {
                $suma_5_p45++;
            }
            #pregunta número 46
            $p46 = $fila["p46"];
            if ($p46 == 1) {
                $suma_1_p46++;
            } elseif ($p46 == 2) {
                $suma_2_p46++;
            } elseif ($p46 == 3) {
                $suma_3_p46++;
            } elseif ($p46 == 4) {
                $suma_4_p46++;
            } elseif ($p46 == 5) {
                $suma_5_p46++;
            }
            #pregunta número 47
            $p47 = $fila["p47"];
            if ($p47 == 1) {
                $suma_1_p47++;
            } elseif ($p47 == 2) {
                $suma_2_p47++;
            } elseif ($p47 == 3) {
                $suma_3_p47++;
            } elseif ($p47 == 4) {
                $suma_4_p47++;
            } elseif ($p47 == 5) {
                $suma_5_p47++;
            }

        }
        //pregunta numero 41
        $suma_1_41 = ($suma_1_p41 * 100) / $cantidad;
        $suma_2_41 = ($suma_2_p41 * 100) / $cantidad;
        $suma_3_41 = ($suma_3_p41 * 100) / $cantidad;
        $suma_4_41 = ($suma_4_p41 * 100) / $cantidad;
        $suma_5_41 = ($suma_5_p41 * 100) / $cantidad;
        //pregunta numero 42
        $suma_1_42 = ($suma_1_p42 * 100) / $cantidad;
        $suma_2_42 = ($suma_2_p42 * 100) / $cantidad;
        $suma_3_42 = ($suma_3_p42 * 100) / $cantidad;
        $suma_4_42 = ($suma_4_p42 * 100) / $cantidad;
        $suma_5_42 = ($suma_5_p42 * 100) / $cantidad;
        //pregunta numero 43
        $suma_1_43 = ($suma_1_p43 * 100) / $cantidad;
        $suma_2_43 = ($suma_2_p43 * 100) / $cantidad;
        $suma_3_43 = ($suma_3_p43 * 100) / $cantidad;
        $suma_4_43 = ($suma_4_p43 * 100) / $cantidad;
        $suma_5_43 = ($suma_5_p43 * 100) / $cantidad;
        //pregunta numero 44
        $suma_1_44 = ($suma_1_p44 * 100) / $cantidad;
        $suma_2_44 = ($suma_2_p44 * 100) / $cantidad;
        $suma_3_44 = ($suma_3_p44 * 100) / $cantidad;
        $suma_4_44 = ($suma_4_p44 * 100) / $cantidad;
        $suma_5_44 = ($suma_5_p44 * 100) / $cantidad;
        //pregunta numero 45
        $suma_1_45 = ($suma_1_p45 * 100) / $cantidad;
        $suma_2_45 = ($suma_2_p45 * 100) / $cantidad;
        $suma_3_45 = ($suma_3_p45 * 100) / $cantidad;
        $suma_4_45 = ($suma_4_p45 * 100) / $cantidad;
        $suma_5_45 = ($suma_5_p45 * 100) / $cantidad;
        //pregunta numero 46
        $suma_1_46 = ($suma_1_p46 * 100) / $cantidad;
        $suma_2_46 = ($suma_2_p46 * 100) / $cantidad;
        $suma_3_46 = ($suma_3_p46 * 100) / $cantidad;
        $suma_4_46 = ($suma_4_p46 * 100) / $cantidad;
        $suma_5_46 = ($suma_5_p46 * 100) / $cantidad;
        //pregunta numero 47
        $suma_1_47 = ($suma_1_p47 * 100) / $cantidad;
        $suma_2_47 = ($suma_2_p47 * 100) / $cantidad;
        $suma_3_47 = ($suma_3_p47 * 100) / $cantidad;
        $suma_4_47 = ($suma_4_p47 * 100) / $cantidad;
        $suma_5_47 = ($suma_5_p47 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        $dato = '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p041"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_41, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_41, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_41, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_41, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_41, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p042"].'</td>'
         
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_42, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_42, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_42, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_42, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_42, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p043"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_43, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_43, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_43, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_43, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_43, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p044"].'</td>'
            
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_44, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_44, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_44, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_44, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_44, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p045"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_45, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_45, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_45, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_45, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_45, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p046"].'</td>'
          
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_46, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_46, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_46, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_46, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_46, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p047"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_47, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_47, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_47, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_47, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_47, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

            return $dato;

    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }

}

function dimension_conductual_curso($establecimiento, $profesor)
{
    try {
        //pregunta numero 3
        $suma_1_p3 = 0;
        $suma_2_p3 = 0;
        $suma_3_p3 = 0;
        $suma_4_p3 = 0;
        $suma_5_p3 = 0;
        //pregunta numero 4
        $suma_1_p4 = 0;
        $suma_2_p4 = 0;
        $suma_3_p4 = 0;
        $suma_4_p4 = 0;
        $suma_5_p4 = 0;
        //pregunta numero 9
        $suma_1_p9 = 0;
        $suma_2_p9 = 0;
        $suma_3_p9 = 0;
        $suma_4_p9 = 0;
        $suma_5_p9 = 0;
        //pregunta numero 11
        $suma_1_p11 = 0;
        $suma_2_p11 = 0;
        $suma_3_p11 = 0;
        $suma_4_p11 = 0;
        $suma_5_p11 = 0;
        //pregunta numero 16
        $suma_1_p16 = 0;
        $suma_2_p16 = 0;
        $suma_3_p16 = 0;
        $suma_4_p16 = 0;
        $suma_5_p16 = 0;
        //pregunta numero 23
        $suma_1_p23 = 0;
        $suma_2_p23 = 0;
        $suma_3_p23 = 0;
        $suma_4_p23 = 0;
        $suma_5_p23 = 0;
        //pregunta numero 28
        $suma_1_p28 = 0;
        $suma_2_p28 = 0;
        $suma_3_p28 = 0;
        $suma_4_p28 = 0;
        $suma_5_p28 = 0;
        $con = connectDB_demos();
        $query = $con->query("SELECT
        ce_encuesta_resultado.ce_p3 AS p3,
        ce_encuesta_resultado.ce_p4 AS p4,
        ce_encuesta_resultado.ce_p9 AS p9,
        ce_encuesta_resultado.ce_p11 AS p11,
        ce_encuesta_resultado.ce_p16 AS p16,
        ce_encuesta_resultado.ce_p23 AS p23,
        ce_encuesta_resultado.ce_p28 AS p28
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND  ce_participantes.ce_docente_id_ce_docente = '$profesor'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            $p3 = $fila["p3"];
            if ($p3 == 1) {
                $suma_1_p3++;
            } elseif ($p3 == 2) {
                $suma_2_p3++;
            } elseif ($p3 == 3) {
                $suma_3_p3++;
            } elseif ($p3 == 4) {
                $suma_4_p3++;
            } elseif ($p3 == 5) {
                $suma_5_p3++;
            }
            //pregunta numero 4
            $p4 = $fila["p4"];
            if ($p4 == 1) {
                $suma_1_p4++;
            } elseif ($p4 == 2) {
                $suma_2_p4++;
            } elseif ($p4 == 3) {
                $suma_3_p4++;
            } elseif ($p4 == 4) {
                $suma_4_p4++;
            } elseif ($p4 == 5) {
                $suma_5_p4++;
            }
            //pregunta numero 4
            $p9 = $fila["p9"];
            if ($p9 == 1) {
                $suma_1_p9++;
            } elseif ($p9 == 2) {
                $suma_2_p9++;
            } elseif ($p9 == 3) {
                $suma_3_p9++;
            } elseif ($p9 == 4) {
                $suma_4_p9++;
            } elseif ($p9 == 5) {
                $suma_5_p9++;
            }
            //pregunta numero 11
            $p11 = $fila["p11"];
            if ($p11 == 1) {
                $suma_1_p11++;
            } elseif ($p11 == 2) {
                $suma_2_p11++;
            } elseif ($p11 == 3) {
                $suma_3_p11++;
            } elseif ($p11 == 4) {
                $suma_4_p11++;
            } elseif ($p9 == 5) {
                $suma_5_p11++;
            }
            //pregunta numero 16
            $p16 = $fila["p16"];
            if ($p16 == 1) {
                $suma_1_p16++;
            } elseif ($p16 == 2) {
                $suma_2_p16++;
            } elseif ($p16 == 3) {
                $suma_3_p16++;
            } elseif ($p16 == 4) {
                $suma_4_p16++;
            } elseif ($p16 == 5) {
                $suma_5_p16++;
            }
            //pregunta numero 23
            $p23 = $fila["p23"];
            if ($p23 == 1) {
                $suma_1_p23++;
            } elseif ($p23 == 2) {
                $suma_2_p23++;
            } elseif ($p23 == 3) {
                $suma_3_p23++;
            } elseif ($p23 == 4) {
                $suma_4_p23++;
            } elseif ($p23 == 5) {
                $suma_5_p23++;
            }
            //pregunta numero 28
            $p28 = $fila["p28"];
            if ($p28 == 1) {
                $suma_1_p28++;
            } elseif ($p28 == 2) {
                $suma_2_p28++;
            } elseif ($p28 == 3) {
                $suma_3_p28++;
            } elseif ($p28 == 4) {
                $suma_4_p28++;
            } elseif ($p28 == 5) {
                $suma_5_p28++;
            }

        }
        if($cantidad != 0 ){
        //pregunta numero 3
        $suma_1_3 = ($suma_1_p3 * 100) / $cantidad;
        $suma_2_3 = ($suma_2_p3 * 100) / $cantidad;
        $suma_3_3 = ($suma_3_p3 * 100) / $cantidad;
        $suma_4_3 = ($suma_4_p3 * 100) / $cantidad;
        $suma_5_3 = ($suma_5_p3 * 100) / $cantidad;
        //pregunta numero 4
        $suma_1_4 = ($suma_1_p4 * 100) / $cantidad;
        $suma_2_4 = ($suma_2_p4 * 100) / $cantidad;
        $suma_3_4 = ($suma_3_p4 * 100) / $cantidad;
        $suma_4_4 = ($suma_4_p4 * 100) / $cantidad;
        $suma_5_4 = ($suma_5_p4 * 100) / $cantidad;
        //pregunta numero 9
        $suma_1_9 = ($suma_1_p9 * 100) / $cantidad;
        $suma_2_9 = ($suma_2_p9 * 100) / $cantidad;
        $suma_3_9 = ($suma_3_p9 * 100) / $cantidad;
        $suma_4_9 = ($suma_4_p9 * 100) / $cantidad;
        $suma_5_9 = ($suma_5_p9 * 100) / $cantidad;

        //pregunta numero 11
        $suma_1_11 = ($suma_1_p11 * 100) / $cantidad;
        $suma_2_11 = ($suma_2_p11 * 100) / $cantidad;
        $suma_3_11 = ($suma_3_p11 * 100) / $cantidad;
        $suma_4_11 = ($suma_4_p11 * 100) / $cantidad;
        $suma_5_11 = ($suma_5_p11 * 100) / $cantidad;
        //pregunta numero 16
        $suma_1_16 = ($suma_1_p16 * 100) / $cantidad;
        $suma_2_16 = ($suma_2_p16 * 100) / $cantidad;
        $suma_3_16 = ($suma_3_p16 * 100) / $cantidad;
        $suma_4_16 = ($suma_4_p16 * 100) / $cantidad;
        $suma_5_16 = ($suma_5_p16 * 100) / $cantidad;
        //pregunta numero 23
        $suma_1_23 = ($suma_1_p23 * 100) / $cantidad;
        $suma_2_23 = ($suma_2_p23 * 100) / $cantidad;
        $suma_3_23 = ($suma_3_p23 * 100) / $cantidad;
        $suma_4_23 = ($suma_4_p23 * 100) / $cantidad;
        $suma_5_23 = ($suma_5_p23 * 100) / $cantidad;
        //pregunta numero 28
        $suma_1_28 = ($suma_1_p28 * 100) / $cantidad;
        $suma_2_28 = ($suma_2_p28 * 100) / $cantidad;
        $suma_3_28 = ($suma_3_p28 * 100) / $cantidad;
        $suma_4_28 = ($suma_4_p28 * 100) / $cantidad;
        $suma_5_28 = ($suma_5_p28 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        echo '<tr><td style="width:40%;">'.$pregunta["p03"].'</td>'
           
            . '<td>' . round($suma_5_3, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_3, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_3, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_3, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_3, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p04"].'</td>'
            
            . '<td>' . round($suma_5_4, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_4, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_4, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_4, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_4, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p09"].'</td>'
         
            . '<td>' . round($suma_5_9, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_9, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_9, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_9, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_9, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
        echo '<tr><td>'.$pregunta["p011"].'</td>'
          
            . '<td>' . round($suma_5_11, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_11, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_11, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_11, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_11, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p016"].'</td>'
         
            . '<td>' . round($suma_1_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p023"].'</td>'
            
            . '<td>' . round($suma_5_23, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_23, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_23, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_23, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_23, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
        echo '<tr><td>'.$pregunta["p028"].'</td>'
           
            . '<td>' . round($suma_5_28, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_28, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_28, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_28, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_28, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
        }else{
            return "No hay resultados para este curso";
        }
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }

}


function dimension_conductual_establecimiento($establecimiento)
{
    try {
        //pregunta numero 3
        $suma_1_p3 = 0;
        $suma_2_p3 = 0;
        $suma_3_p3 = 0;
        $suma_4_p3 = 0;
        $suma_5_p3 = 0;
        //pregunta numero 4
        $suma_1_p4 = 0;
        $suma_2_p4 = 0;
        $suma_3_p4 = 0;
        $suma_4_p4 = 0;
        $suma_5_p4 = 0;
        //pregunta numero 9
        $suma_1_p9 = 0;
        $suma_2_p9 = 0;
        $suma_3_p9 = 0;
        $suma_4_p9 = 0;
        $suma_5_p9 = 0;
        //pregunta numero 11
        $suma_1_p11 = 0;
        $suma_2_p11 = 0;
        $suma_3_p11 = 0;
        $suma_4_p11 = 0;
        $suma_5_p11 = 0;
        //pregunta numero 16
        $suma_1_p16 = 0;
        $suma_2_p16 = 0;
        $suma_3_p16 = 0;
        $suma_4_p16 = 0;
        $suma_5_p16 = 0;
        //pregunta numero 23
        $suma_1_p23 = 0;
        $suma_2_p23 = 0;
        $suma_3_p23 = 0;
        $suma_4_p23 = 0;
        $suma_5_p23 = 0;
        //pregunta numero 28
        $suma_1_p28 = 0;
        $suma_2_p28 = 0;
        $suma_3_p28 = 0;
        $suma_4_p28 = 0;
        $suma_5_p28 = 0;
        $con = connectDB_demos();
        $query = $con->query("SELECT
        ce_encuesta_resultado.ce_p3 AS p3,
        ce_encuesta_resultado.ce_p4 AS p4,
        ce_encuesta_resultado.ce_p9 AS p9,
        ce_encuesta_resultado.ce_p11 AS p11,
        ce_encuesta_resultado.ce_p16 AS p16,
        ce_encuesta_resultado.ce_p23 AS p23,
        ce_encuesta_resultado.ce_p28 AS p28
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            $p3 = $fila["p3"];
            if ($p3 == 1) {
                $suma_1_p3++;
            } elseif ($p3 == 2) {
                $suma_2_p3++;
            } elseif ($p3 == 3) {
                $suma_3_p3++;
            } elseif ($p3 == 4) {
                $suma_4_p3++;
            } elseif ($p3 == 5) {
                $suma_5_p3++;
            }
            //pregunta numero 4
            $p4 = $fila["p4"];
            if ($p4 == 1) {
                $suma_1_p4++;
            } elseif ($p4 == 2) {
                $suma_2_p4++;
            } elseif ($p4 == 3) {
                $suma_3_p4++;
            } elseif ($p4 == 4) {
                $suma_4_p4++;
            } elseif ($p4 == 5) {
                $suma_5_p4++;
            }
            //pregunta numero 4
            $p9 = $fila["p9"];
            if ($p9 == 1) {
                $suma_1_p9++;
            } elseif ($p9 == 2) {
                $suma_2_p9++;
            } elseif ($p9 == 3) {
                $suma_3_p9++;
            } elseif ($p9 == 4) {
                $suma_4_p9++;
            } elseif ($p9 == 5) {
                $suma_5_p9++;
            }
            //pregunta numero 11
            $p11 = $fila["p11"];
            if ($p11 == 1) {
                $suma_1_p11++;
            } elseif ($p11 == 2) {
                $suma_2_p11++;
            } elseif ($p11 == 3) {
                $suma_3_p11++;
            } elseif ($p11 == 4) {
                $suma_4_p11++;
            } elseif ($p9 == 5) {
                $suma_5_p11++;
            }
            //pregunta numero 16
            $p16 = $fila["p16"];
            if ($p16 == 1) {
                $suma_1_p16++;
            } elseif ($p16 == 2) {
                $suma_2_p16++;
            } elseif ($p16 == 3) {
                $suma_3_p16++;
            } elseif ($p16 == 4) {
                $suma_4_p16++;
            } elseif ($p16 == 5) {
                $suma_5_p16++;
            }
            //pregunta numero 23
            $p23 = $fila["p23"];
            if ($p23 == 1) {
                $suma_1_p23++;
            } elseif ($p23 == 2) {
                $suma_2_p23++;
            } elseif ($p23 == 3) {
                $suma_3_p23++;
            } elseif ($p23 == 4) {
                $suma_4_p23++;
            } elseif ($p23 == 5) {
                $suma_5_p23++;
            }
            //pregunta numero 28
            $p28 = $fila["p28"];
            if ($p28 == 1) {
                $suma_1_p28++;
            } elseif ($p28 == 2) {
                $suma_2_p28++;
            } elseif ($p28 == 3) {
                $suma_3_p28++;
            } elseif ($p28 == 4) {
                $suma_4_p28++;
            } elseif ($p28 == 5) {
                $suma_5_p28++;
            }

        }
        if($cantidad != 0 ){
        //pregunta numero 3
        $suma_1_3 = ($suma_1_p3 * 100) / $cantidad;
        $suma_2_3 = ($suma_2_p3 * 100) / $cantidad;
        $suma_3_3 = ($suma_3_p3 * 100) / $cantidad;
        $suma_4_3 = ($suma_4_p3 * 100) / $cantidad;
        $suma_5_3 = ($suma_5_p3 * 100) / $cantidad;
        //pregunta numero 4
        $suma_1_4 = ($suma_1_p4 * 100) / $cantidad;
        $suma_2_4 = ($suma_2_p4 * 100) / $cantidad;
        $suma_3_4 = ($suma_3_p4 * 100) / $cantidad;
        $suma_4_4 = ($suma_4_p4 * 100) / $cantidad;
        $suma_5_4 = ($suma_5_p4 * 100) / $cantidad;
        //pregunta numero 9
        $suma_1_9 = ($suma_1_p9 * 100) / $cantidad;
        $suma_2_9 = ($suma_2_p9 * 100) / $cantidad;
        $suma_3_9 = ($suma_3_p9 * 100) / $cantidad;
        $suma_4_9 = ($suma_4_p9 * 100) / $cantidad;
        $suma_5_9 = ($suma_5_p9 * 100) / $cantidad;

        //pregunta numero 11
        $suma_1_11 = ($suma_1_p11 * 100) / $cantidad;
        $suma_2_11 = ($suma_2_p11 * 100) / $cantidad;
        $suma_3_11 = ($suma_3_p11 * 100) / $cantidad;
        $suma_4_11 = ($suma_4_p11 * 100) / $cantidad;
        $suma_5_11 = ($suma_5_p11 * 100) / $cantidad;
        //pregunta numero 16
        $suma_1_16 = ($suma_1_p16 * 100) / $cantidad;
        $suma_2_16 = ($suma_2_p16 * 100) / $cantidad;
        $suma_3_16 = ($suma_3_p16 * 100) / $cantidad;
        $suma_4_16 = ($suma_4_p16 * 100) / $cantidad;
        $suma_5_16 = ($suma_5_p16 * 100) / $cantidad;
        //pregunta numero 23
        $suma_1_23 = ($suma_1_p23 * 100) / $cantidad;
        $suma_2_23 = ($suma_2_p23 * 100) / $cantidad;
        $suma_3_23 = ($suma_3_p23 * 100) / $cantidad;
        $suma_4_23 = ($suma_4_p23 * 100) / $cantidad;
        $suma_5_23 = ($suma_5_p23 * 100) / $cantidad;
        //pregunta numero 28
        $suma_1_28 = ($suma_1_p28 * 100) / $cantidad;
        $suma_2_28 = ($suma_2_p28 * 100) / $cantidad;
        $suma_3_28 = ($suma_3_p28 * 100) / $cantidad;
        $suma_4_28 = ($suma_4_p28 * 100) / $cantidad;
        $suma_5_28 = ($suma_5_p28 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        echo '<tr><td style="width:40%;">'.$pregunta["p03"].'</td>'
           
            . '<td>' . round($suma_5_3, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_3, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_3, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_3, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_3, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p04"].'</td>'
            
            . '<td>' . round($suma_5_4, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_4, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_4, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_4, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_4, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p09"].'</td>'
         
            . '<td>' . round($suma_5_9, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_9, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_9, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_9, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_9, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
        echo '<tr><td>'.$pregunta["p011"].'</td>'
          
            . '<td>' . round($suma_5_11, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_11, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_11, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_11, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_11, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

        echo '<tr><td>'.$pregunta["p016"].'</td>'
         
            . '<td>' . round($suma_1_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_2_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_3_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_4_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td>' . round($suma_5_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

        echo '<tr><td>'.$pregunta["p023"].'</td>'
            
            . '<td>' . round($suma_5_23, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_23, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_23, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_23, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_23, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
        echo '<tr><td>'.$pregunta["p028"].'</td>'
           
            . '<td>' . round($suma_5_28, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td>' . round($suma_4_28, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td>' . round($suma_3_28, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td>' . round($suma_2_28, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td>' . round($suma_1_28, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
        }else{
            return "No hay resultados para este curso";
        }
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }

}

function dimension_conductual_curso_copia($establecimiento, $profesor)
{
    try {
        //pregunta numero 3
        $suma_1_p3 = 0;
        $suma_2_p3 = 0;
        $suma_3_p3 = 0;
        $suma_4_p3 = 0;
        $suma_5_p3 = 0;
        //pregunta numero 4
        $suma_1_p4 = 0;
        $suma_2_p4 = 0;
        $suma_3_p4 = 0;
        $suma_4_p4 = 0;
        $suma_5_p4 = 0;
        //pregunta numero 9
        $suma_1_p9 = 0;
        $suma_2_p9 = 0;
        $suma_3_p9 = 0;
        $suma_4_p9 = 0;
        $suma_5_p9 = 0;
        //pregunta numero 11
        $suma_1_p11 = 0;
        $suma_2_p11 = 0;
        $suma_3_p11 = 0;
        $suma_4_p11 = 0;
        $suma_5_p11 = 0;
        //pregunta numero 16
        $suma_1_p16 = 0;
        $suma_2_p16 = 0;
        $suma_3_p16 = 0;
        $suma_4_p16 = 0;
        $suma_5_p16 = 0;
        //pregunta numero 23
        $suma_1_p23 = 0;
        $suma_2_p23 = 0;
        $suma_3_p23 = 0;
        $suma_4_p23 = 0;
        $suma_5_p23 = 0;
        //pregunta numero 28
        $suma_1_p28 = 0;
        $suma_2_p28 = 0;
        $suma_3_p28 = 0;
        $suma_4_p28 = 0;
        $suma_5_p28 = 0;
        $con = connectDB_demos();
        $query = $con->query("SELECT
        ce_encuesta_resultado.ce_p3 AS p3,
        ce_encuesta_resultado.ce_p4 AS p4,
        ce_encuesta_resultado.ce_p9 AS p9,
        ce_encuesta_resultado.ce_p11 AS p11,
        ce_encuesta_resultado.ce_p16 AS p16,
        ce_encuesta_resultado.ce_p23 AS p23,
        ce_encuesta_resultado.ce_p28 AS p28
        FROM ce_encuesta_resultado
        INNER JOIN ce_participantes ON ce_encuesta_resultado.ce_participantes_token_fk = ce_participantes.ce_participanes_token
        WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND  ce_participantes.ce_docente_id_ce_docente = '$profesor'");
        $con = null;
        $cantidad = $query->RowCount();
        foreach ($query as $fila) {
            $p3 = $fila["p3"];
            if ($p3 == 1) {
                $suma_1_p3++;
            } elseif ($p3 == 2) {
                $suma_2_p3++;
            } elseif ($p3 == 3) {
                $suma_3_p3++;
            } elseif ($p3 == 4) {
                $suma_4_p3++;
            } elseif ($p3 == 5) {
                $suma_5_p3++;
            }
            //pregunta numero 4
            $p4 = $fila["p4"];
            if ($p4 == 1) {
                $suma_1_p4++;
            } elseif ($p4 == 2) {
                $suma_2_p4++;
            } elseif ($p4 == 3) {
                $suma_3_p4++;
            } elseif ($p4 == 4) {
                $suma_4_p4++;
            } elseif ($p4 == 5) {
                $suma_5_p4++;
            }
            //pregunta numero 4
            $p9 = $fila["p9"];
            if ($p9 == 1) {
                $suma_1_p9++;
            } elseif ($p9 == 2) {
                $suma_2_p9++;
            } elseif ($p9 == 3) {
                $suma_3_p9++;
            } elseif ($p9 == 4) {
                $suma_4_p9++;
            } elseif ($p9 == 5) {
                $suma_5_p9++;
            }
            //pregunta numero 11
            $p11 = $fila["p11"];
            if ($p11 == 1) {
                $suma_1_p11++;
            } elseif ($p11 == 2) {
                $suma_2_p11++;
            } elseif ($p11 == 3) {
                $suma_3_p11++;
            } elseif ($p11 == 4) {
                $suma_4_p11++;
            } elseif ($p9 == 5) {
                $suma_5_p11++;
            }
            //pregunta numero 16
            $p16 = $fila["p16"];
            if ($p16 == 1) {
                $suma_1_p16++;
            } elseif ($p16 == 2) {
                $suma_2_p16++;
            } elseif ($p16 == 3) {
                $suma_3_p16++;
            } elseif ($p16 == 4) {
                $suma_4_p16++;
            } elseif ($p16 == 5) {
                $suma_5_p16++;
            }
            //pregunta numero 23
            $p23 = $fila["p23"];
            if ($p23 == 1) {
                $suma_1_p23++;
            } elseif ($p23 == 2) {
                $suma_2_p23++;
            } elseif ($p23 == 3) {
                $suma_3_p23++;
            } elseif ($p23 == 4) {
                $suma_4_p23++;
            } elseif ($p23 == 5) {
                $suma_5_p23++;
            }
            //pregunta numero 28
            $p28 = $fila["p28"];
            if ($p28 == 1) {
                $suma_1_p28++;
            } elseif ($p28 == 2) {
                $suma_2_p28++;
            } elseif ($p28 == 3) {
                $suma_3_p28++;
            } elseif ($p28 == 4) {
                $suma_4_p28++;
            } elseif ($p28 == 5) {
                $suma_5_p28++;
            }

        }
        //pregunta numero 3
        $suma_1_3 = ($suma_1_p3 * 100) / $cantidad;
        $suma_2_3 = ($suma_2_p3 * 100) / $cantidad;
        $suma_3_3 = ($suma_3_p3 * 100) / $cantidad;
        $suma_4_3 = ($suma_4_p3 * 100) / $cantidad;
        $suma_5_3 = ($suma_5_p3 * 100) / $cantidad;
        //pregunta numero 4
        $suma_1_4 = ($suma_1_p4 * 100) / $cantidad;
        $suma_2_4 = ($suma_2_p4 * 100) / $cantidad;
        $suma_3_4 = ($suma_3_p4 * 100) / $cantidad;
        $suma_4_4 = ($suma_4_p4 * 100) / $cantidad;
        $suma_5_4 = ($suma_5_p4 * 100) / $cantidad;
        //pregunta numero 9
        $suma_1_9 = ($suma_1_p9 * 100) / $cantidad;
        $suma_2_9 = ($suma_2_p9 * 100) / $cantidad;
        $suma_3_9 = ($suma_3_p9 * 100) / $cantidad;
        $suma_4_9 = ($suma_4_p9 * 100) / $cantidad;
        $suma_5_9 = ($suma_5_p9 * 100) / $cantidad;

        //pregunta numero 11
        $suma_1_11 = ($suma_1_p11 * 100) / $cantidad;
        $suma_2_11 = ($suma_2_p11 * 100) / $cantidad;
        $suma_3_11 = ($suma_3_p11 * 100) / $cantidad;
        $suma_4_11 = ($suma_4_p11 * 100) / $cantidad;
        $suma_5_11 = ($suma_5_p11 * 100) / $cantidad;
        //pregunta numero 16
        $suma_1_16 = ($suma_1_p16 * 100) / $cantidad;
        $suma_2_16 = ($suma_2_p16 * 100) / $cantidad;
        $suma_3_16 = ($suma_3_p16 * 100) / $cantidad;
        $suma_4_16 = ($suma_4_p16 * 100) / $cantidad;
        $suma_5_16 = ($suma_5_p16 * 100) / $cantidad;
        //pregunta numero 23
        $suma_1_23 = ($suma_1_p23 * 100) / $cantidad;
        $suma_2_23 = ($suma_2_p23 * 100) / $cantidad;
        $suma_3_23 = ($suma_3_p23 * 100) / $cantidad;
        $suma_4_23 = ($suma_4_p23 * 100) / $cantidad;
        $suma_5_23 = ($suma_5_p23 * 100) / $cantidad;
        //pregunta numero 28
        $suma_1_28 = ($suma_1_p28 * 100) / $cantidad;
        $suma_2_28 = ($suma_2_p28 * 100) / $cantidad;
        $suma_3_28 = ($suma_3_p28 * 100) / $cantidad;
        $suma_4_28 = ($suma_4_p28 * 100) / $cantidad;
        $suma_5_28 = ($suma_5_p28 * 100) / $cantidad;

        $pregunta = preguntas_compromiso_escolar();

        $dato = '<tr><td style="width:40%; border: 1px solid #fc455c;">'.$pregunta["p03"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_3, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_3, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_3, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_3, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_3, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p04"].'</td>'
            
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_4, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_4, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_4, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_4, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_4, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

            $dato .=  '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p09"].'</td>'
         
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_9, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_9, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_9, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_9, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_9, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
            $dato .= '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p011"].'</td>'
          
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_11, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_11, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_11, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_11, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_11, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';

            $dato .=  '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p016"].'</td>'
         
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_16, 1, PHP_ROUND_HALF_UP) . '</td>'
            . '</tr>';

            $dato .=  '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p023"].'</td>'
            
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_23, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_23, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_23, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_23, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_23, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
            $dato .=  '<tr><td style="border: 1px solid #fc455c;">'.$pregunta["p028"].'</td>'
           
            . '<td style="border: 1px solid #fc455c;">' . round($suma_5_28, 1, PHP_ROUND_HALF_UP) . '</td>'//1
            . '<td style="border: 1px solid #fc455c;">' . round($suma_4_28, 1, PHP_ROUND_HALF_UP) . '</td>'//2
            . '<td style="border: 1px solid #fc455c;">' . round($suma_3_28, 1, PHP_ROUND_HALF_UP) . '</td>'//3
            . '<td style="border: 1px solid #fc455c;">' . round($suma_2_28, 1, PHP_ROUND_HALF_UP) . '</td>'//4
            . '<td style="border: 1px solid #fc455c;">' . round($suma_1_28, 1, PHP_ROUND_HALF_UP) . '</td>'//5
            . '</tr>';
return $dato;
    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }

}

function curso_de_establecimiento($docente)
{

    try {

        $con = connectDB_demos();
        $query = $con->query("SELECT DISTINCT (b.ce_curso_nombre) as curso
        FROM ce_participantes a
        INNER JOIN ce_curso b ON b.id_ce_curso = a.ce_curso_id_ce_curso
        WHERE a.ce_docente_id_ce_docente = $docente");
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        $con = NULL;
        $_SESSION["curso_nombre"] = $resultado["curso"];
        echo $resultado["curso"];


    } catch (Exception $e) {
        echo 'Excepción Capturada: ' . $e->getMessage();

    }


}

//Seleccionamos los estudiantes por establecimiento, profesor y curso
function select_estudiantes_por_curso($id_profesor)
{
    /*
    En caso de cualquier cosa 
    ,FN_OBTIENE_NIVEL_CURSOS(a.id_ce_participantes) as nivelCurso
    */
    try {
        $con = connectDB_demos();
        $query = $con->prepare("SELECT a.id_ce_participantes as id_estudiante, a.ce_participanes_token as token,a.ce_participantes_nombres as nombres,a.ce_participantes_apellidos as apellidos,a.ce_curso_id_ce_curso
    FROM ce_participantes a INNER JOIN ce_docente b  ON a.ce_docente_id_ce_docente = b.id_ce_docente AND b.id_ce_docente = :id_ce_docente
    WHERE a.ce_estado_encuesta = 1 ");
        $query->execute([
            'id_ce_docente' =>$id_profesor
        ]);
        $con = null;

        $resultado = $query->rowCount();
        if( $resultado >= 1){
            foreach ($query AS $row) { ?>
                <option value="<?php echo $row["token"] ?>">
                    <?php 
                        echo $row["nombres"]. ' ' .$row["apellidos"]; 
                    ?>                        
                </option>
            <?php }
        }else if($resultado <= 0){
             echo  '<option value="0"> No se encontraron estudiantes</option>';
        }
       

    } catch (Exception $ex) {
        $_SESSION['status'] = [
            'type' => 'danger',
            'message' => 'Excepción Capturada: ' . $ex->getMessage()
        ];
    }
}

function select_estudiantes_por_establecimiento($establecimiento)
{
    /*
    En caso de cualquier cosa 
    ,FN_OBTIENE_NIVEL_CURSOS(a.id_ce_participantes) as nivelCurso
    */
    try {
        $con = connectDB_demos();
        $query = $con->prepare("SELECT a.id_ce_participantes as id_estudiante, a.ce_participanes_token as token,a.ce_participantes_nombres as nombres,a.ce_participantes_apellidos as apellidos
        FROM ce_participantes a INNER JOIN ce_curso cc ON a.ce_curso_id_ce_curso = cc.id_ce_curso INNER JOIN ce_establecimiento ce ON ce.id_ce_establecimiento = cc.ce_fk_establecimiento
        WHERE a.ce_estado_encuesta = 1 AND ce.id_ce_establecimiento = :id_establecimiento GROUP BY token ORDER by  nombres");
        $query->execute([
            ':id_establecimiento' =>$establecimiento
        ]);
        $con = null;

        $resultado = $query->rowCount();
        if( $resultado >= 1){
            ?><option value="-1">Seleccione</option>
            <?php
            foreach ($query AS $row) { ?>
                <option value="<?php echo $row["id_estudiante"] ?>">
                    <?php 
                        echo $row["nombres"]. ' ' .$row["apellidos"]; 
                    ?>                        
                </option>
            <?php }
        }else if($resultado <= 0){
             echo  '<option value="0"> No se encontraron estudiantes</option>';
        }
       

    } catch (Exception $ex) {
        $_SESSION['status'] = [
            'type' => 'danger',
            'message' => 'Excepción Capturada: ' . $ex->getMessage()
        ];
    }
}

function select_curso_por_establecimiento($id_establecimiento)
{
    try {
        $con = connectDB_demos();
        $query = $con->prepare("SELECT DISTINCT c.id_ce_curso AS id_curso , c.ce_curso_nombre AS curso     
        FROM ce_encuesta_resultado a
        INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
        INNER JOIN ce_curso c ON b.ce_curso_id_ce_curso = c.id_ce_curso
        WHERE b.ce_establecimiento_id_ce_establecimiento = '$id_establecimiento' GROUP BY c.id_ce_curso ");
        $query->execute([
            'b.ce_establecimiento_id_ce_establecimiento' => $id_establecimiento
        ]);
        $con = null;
        echo '<option value="33">Todos</option>';
        foreach ($query AS $row) { ?>
            <option value="<?php echo $row["id_curso"]?>"><?php echo $row["curso"]?></option>
        <?php }

    } catch (Exception $ex) {
        $_SESSION['status'] = [
            'type' => 'danger',
            'message' => 'Excepción Capturada: ' . $ex->getMessage()
        ];
    }
}

function reporte_compromiso_escolar_estudiante($token_estudiante)
{
    try {
        $con = connectDB_demos();
        $query = $con->query("SELECT (
			ce_p1+ce_p2+ce_p3+ce_p4+ce_p5+ce_p6+ce_p7+ce_p8+ce_p9+ce_p10+ce_p11+
			ce_p12+ce_p13+ce_p14+ce_p15+ce_p16+ce_p17+ce_p18+ce_p19+ce_p20+ce_p21+ce_p22+
			ce_p23+ce_p24+ce_p25+ce_p26+ce_p27+ce_p28+ce_p29
		 ) AS sumaCE,(
		 ce_p1+ce_p5+ce_p7+ce_p8+ce_p12+ce_p15+ce_p19+ce_p22+ce_p27+ce_p29
		 ) AS sumaAfectiva,(
		 ce_p3+ce_p4+ce_p9+ce_p11+
		   ce_p16+ce_p23+ce_p28
		 ) AS sumaConductual,
		 	(	 
			ce_p2+ce_p6+ce_p10+ce_p13+ce_p14+ce_p17+
			ce_p18+ce_p20+ce_p21+ce_p24+ce_p25+ce_p26
		 ) AS sumaCognitiva
		 
		 FROM ce_encuesta_resultado a 
         WHERE UPPER(a.ce_participantes_token_fk) = UPPER('$token_estudiante')");
        $con = null;

        return $query;

    } catch (Exception $ex) {
        echo 'Excepción Capturada: ' . $ex->getMessage();
    }
}

function obtenemos_media_o_basica($token_estu)
{
    try {
        $con = connectDB_demos();
 
        $query_id_estudiante = $con->query("SELECT ce_fk_nivel as nivel FROM ce_participantes WHERE ce_participanes_token='$token_estu'");    
        $resul_media_basica = $query_id_estudiante->fetch(PDO::FETCH_ASSOC);
        $resul_media_basica_final = $resul_media_basica['nivel'];
        $con = null;

        if ($resul_media_basica_final == 2) {
            $resul_media_basica_final = "2";

        } elseif ($resul_media_basica_final == 1) {
            $resul_media_basica_final = "1";

        }
        return $resul_media_basica_final;


    } catch (Exception $ex) {

        echo 'Excepción Capturada: ' . $ex->getMessage();

    }
}

function alert_o_fortaleza_compromiso_escolar($token_estudiante, $total_compromiso_escolar) {                          
    try {
        $nivel_alumno = obtenemos_media_o_basica($token_estudiante);
        if ($nivel_alumno == "2") {
            if ($total_compromiso_escolar >= 29 AND $total_compromiso_escolar <= 92) {
                $total_compromiso_escolar = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-danger hvr hvr-grow' onclick='definicion_compromiso_escolar_promedio(2);'>Alerta Alta</div>";
            } elseif ($total_compromiso_escolar >= 93 AND $total_compromiso_escolar <= 110) {
                $total_compromiso_escolar = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-alerta-media hvr hvr-grow' onclick='definicion_compromiso_escolar_promedio(2);'>Alerta Moderada</div>";
            } elseif ($total_compromiso_escolar >= 111 AND $total_compromiso_escolar <= 125) {
                $total_compromiso_escolar = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-info hvr hvr-grow' onclick='definicion_compromiso_escolar_promedio(1);'>Fortaleza Moderada</div>";
            } elseif ($total_compromiso_escolar >= 126 AND $total_compromiso_escolar < 147) {
                $total_compromiso_escolar = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-primary hvr hvr-grow' onclick='definicion_compromiso_escolar_promedio(1);'>Fortaleza Alta</div>";
            } else {
                $total_compromiso_escolar = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-default'>Incompleta</div>";
            }

        } elseif ($nivel_alumno == "1") {
            if ($total_compromiso_escolar >= 29 AND $total_compromiso_escolar <= 85) {
                $total_compromiso_escolar = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-danger hvr hvr-grow' onclick='definicion_compromiso_escolar_promedio(2);'>Alerta Alta</div>";
            } elseif ($total_compromiso_escolar >= 86 AND $total_compromiso_escolar <= 97) {
                $total_compromiso_escolar = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-alerta-media hvr hvr-grow' onclick='definicion_compromiso_escolar_promedio(2);'>Alerta Moderada</div>";
            } elseif ($total_compromiso_escolar >= 98 AND $total_compromiso_escolar <= 112) {
                $total_compromiso_escolar = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-info hvr hvr-grow' onclick='definicion_compromiso_escolar_promedio(1);'>Fortaleza Moderada</div>";
            } elseif ($total_compromiso_escolar >= 113 AND $total_compromiso_escolar < 145) {
                $total_compromiso_escolar = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-primary hvr hvr-grow' onclick='definicion_compromiso_escolar_promedio(1);'>Fortaleza Alta</div>";
            } else {
                $total_compromiso_escolar = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-default'>Incompleta</div>";
            }
        }
        return $total_compromiso_escolar;
    } catch (Exception $ex) {
        echo 'Excepción Capturada: ' . $ex->getMessage();
    }
}

function alerta_o_fortaleza_indicador_compromiso_escolar($token_estudiante_indi, $total_compromiso_escolar_indi)
{

    try {
        $nivel_alumno_indi = obtenemos_media_o_basica($token_estudiante_indi);
        if ($nivel_alumno_indi == "2") {
            if ($total_compromiso_escolar_indi >= 29 AND $total_compromiso_escolar_indi <= 92) {
                $total_compromiso_escolar_indi = "Alerta Alta";
            } elseif ($total_compromiso_escolar_indi >= 93 AND $total_compromiso_escolar_indi <= 110) {
                $total_compromiso_escolar_indi = "Alerta Moderada";
            } elseif ($total_compromiso_escolar_indi >= 111 AND $total_compromiso_escolar_indi <= 125) {
                $total_compromiso_escolar_indi = "Fortaleza Moderada";
            } elseif ($total_compromiso_escolar_indi >= 126 AND $total_compromiso_escolar_indi < 147) {
                $total_compromiso_escolar_indi = "Fortaleza Alta";
            } else {
                $total_compromiso_escolar_indi = "Incompleta";
            }

        } elseif ($nivel_alumno_indi == "1") {
            if ($total_compromiso_escolar_indi >= 29 AND $total_compromiso_escolar_indi <= 85) {
                $total_compromiso_escolar_indi = "Alerta Alta";

            } elseif ($total_compromiso_escolar_indi >= 86 AND $total_compromiso_escolar_indi <= 97) {
                $total_compromiso_escolar_indi = "Alerta Moderada";
            } elseif ($total_compromiso_escolar_indi >= 98 AND $total_compromiso_escolar_indi <= 112) {
                $total_compromiso_escolar_indi = "Fortaleza Moderada";
            } elseif ($total_compromiso_escolar_indi >= 113 AND $total_compromiso_escolar_indi < 145) {
                $total_compromiso_escolar_indi = "Fortaleza Alta";
            }else {
                $total_compromiso_escolar_indi = "Incompleta";
            }
        }
        return $total_compromiso_escolar_indi;
    } catch (Exception $ex) {
        echo 'Excepción Capturada: ' . $ex->getMessage();
    }
}

function alert_o_fortaleza_ce_afectivo($token_estudiante, $total_ce_afectivo) {
    try {

        $nivel_alumno = obtenemos_media_o_basica($token_estudiante);
        if ($nivel_alumno == "2") {
            if ($total_ce_afectivo >= 10 AND $total_ce_afectivo <= 26) {
                $total_ce_afectivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-danger hvr hvr-grow' onclick='alerta_afectiva(1);'>Alerta Alta</div>";
            } elseif ($total_ce_afectivo >= 27 AND $total_ce_afectivo <= 34) {
                $total_ce_afectivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-alerta-media hvr hvr-grow' onclick='alerta_afectiva(0);'>Alerta Moderada</div>";
            } elseif ($total_ce_afectivo >= 35 AND $total_ce_afectivo <= 42) {
                $total_ce_afectivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-info hvr hvr-grow' onclick='fortaleza_afectiva(0);'>Fortaleza Moderada</div>";
            } elseif ($total_ce_afectivo >= 43 AND $total_ce_afectivo <= 50) {
                $total_ce_afectivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-primary hvr hvr-grow' onclick='fortaleza_afectiva(1);'>Fortaleza Alta</div>";
            } else {
                $total_ce_afectivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-default'>Incompleta</div>";
            }

        } elseif ($nivel_alumno == "1") {
            if ($total_ce_afectivo >= 10 AND $total_ce_afectivo <= 28) {
                $total_ce_afectivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-danger hvr hvr-grow' onclick='alerta_afectiva(1);'>Alerta Alta</div>";

            } elseif ($total_ce_afectivo >= 29 AND $total_ce_afectivo <= 36) {
                $total_ce_afectivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-alerta-media hvr hvr-grow' onclick='alerta_afectiva(0);'>Alerta Moderada</div>";
            } elseif ($total_ce_afectivo >= 37 AND $total_ce_afectivo <= 43) {
                $total_ce_afectivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-info hvr hvr-grow' onclick='fortaleza_afectiva(0);'>Fortaleza Moderada</div>";
            } elseif ($total_ce_afectivo >= 44 AND $total_ce_afectivo <= 50) {
                $total_ce_afectivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-primary hvr hvr-grow' onclick='fortaleza_afectiva(1);'>Fortaleza Alta</div>";
            } else {
                $total_ce_afectivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-default'>Incompleta</div>";
            }

        }

        return $total_ce_afectivo;

    } catch (Exception $ex){

        echo 'Excepción Capturada: ' . $ex->getMessage();

    }
}

function alerta_o_fortaleza_indicador_ce_afectivo($token_estudiante_indi, $total_ce_afectivo_indi)
{
    try {

        $nivel_alumno_indi = obtenemos_media_o_basica($token_estudiante_indi);
        if ($nivel_alumno_indi == "2") {
            if ($total_ce_afectivo_indi >= 10 AND $total_ce_afectivo_indi <= 26) {
                $total_ce_afectivo_indi = "Alerta Alta";
            } elseif ($total_ce_afectivo_indi >= 27 AND $total_ce_afectivo_indi <= 34) {
                $total_ce_afectivo_indi = "Alerta Moderada";
            } elseif ($total_ce_afectivo_indi >= 35 AND $total_ce_afectivo_indi <= 42) {
                $total_ce_afectivo_indi = "Fortaleza Moderada";
            } elseif ($total_ce_afectivo_indi >= 43 AND $total_ce_afectivo_indi <= 50) {
                $total_ce_afectivo_indi = "Fortaleza Alta";
            } else {
                $total_ce_afectivo_indi = "Incompleta";
            }
         
        } elseif ($nivel_alumno_indi == "1") {

            if ($total_ce_afectivo_indi >= 10 AND $total_ce_afectivo_indi <= 28) {
                $total_ce_afectivo_indi = "Alerta Alta";

            } elseif ($total_ce_afectivo_indi >= 29 AND $total_ce_afectivo_indi <= 36) {
                $total_ce_afectivo_indi = "Alerta Moderada";
            } elseif ($total_ce_afectivo_indi >= 37 AND $total_ce_afectivo_indi <= 43) {
                $total_ce_afectivo_indi = "Fortaleza Moderada";
            } elseif ($total_ce_afectivo_indi >= 44 AND $total_ce_afectivo_indi <= 50) {
                $total_ce_afectivo_indi = "Fortaleza Alta";
            } else {
                $total_ce_afectivo_indi = "Incompleta";
            }

        }

        return $total_ce_afectivo_indi;

    } catch (Exception $ex) {

        echo 'Excepción Capturada: ' . $ex->getMessage();

    }

}



function alert_o_fortaleza_ce_conductual($token_estudiante, $total_ce_conductual)
{
    try {
        $nivel_alumno = obtenemos_media_o_basica($token_estudiante);
        if ($nivel_alumno == "2") {
            if ($total_ce_conductual >= 7 AND $total_ce_conductual <= 21) {
                $total_ce_conductual = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-danger hvr hvr-grow' onclick='alerta_conductual(1);'>Alerta Alta</div>";

            } elseif ($total_ce_conductual >= 22 AND $total_ce_conductual <= 27) {
                $total_ce_conductual = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-alerta-media hvr hvr-grow' onclick='alerta_conductual(0);'>Alerta Moderada</div>";
            } elseif ($total_ce_conductual >= 28 AND $total_ce_conductual <= 31) {
                $total_ce_conductual = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-info hvr hvr-grow' onclick='fortaleza_conductual(0);'>Fortaleza Moderada</div>";
            } elseif ($total_ce_conductual >= 32 AND $total_ce_conductual <= 35) {
                $total_ce_conductual = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-primary hvr hvr-grow' onclick='fortaleza_conductual(1);'>Fortaleza Alta</div>";
            } else {
                $total_ce_conductual = "<div class='label label-default'>Incompleta</div>";
            }

        } elseif ($nivel_alumno == "1") {
            if ($total_ce_conductual >= 7 AND $total_ce_conductual <= 17) {
                $total_ce_conductual = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-danger hvr hvr-grow' onclick='alerta_conductual(1);'>Alerta Alta</div>";

            } elseif ($total_ce_conductual >= 18 AND $total_ce_conductual <= 23) {
                $total_ce_conductual = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-alerta-media hvr hvr-grow' onclick='alerta_conductual(0);'>Alerta Moderada</div>";
            } elseif ($total_ce_conductual >= 24 AND $total_ce_conductual <= 29) {
                $total_ce_conductual = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-info hvr hvr-grow' onclick='fortaleza_conductual(0);'>Fortaleza Moderada</div>";
            } elseif ($total_ce_conductual >= 30 AND $total_ce_conductual <= 35) {// PREGUNTAR POR SI LA PUNTUACIÓN ESTA BIEN?
                $total_ce_conductual = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-primary hvr hvr-grow' onclick='fortaleza_conductual(1);'>Fortaleza Alta</div>";
            } else {
                $total_ce_conductual = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-default'>Incompleta</div>";
            }

        }

        return $total_ce_conductual;

    } catch (Exception $ex) {

        echo 'Excepción Capturada: ' . $ex->getMessage();

    }
}

function alerta_o_fortaleza_indicador_ce_conductual($token_estudiante_indi, $total_ce_conductual_indi)
{

    try {
        $nivel_alumno_indi = obtenemos_media_o_basica($token_estudiante_indi);
        if ($nivel_alumno_indi == "2") {
            

if ($total_ce_conductual_indi >= 7 AND $total_ce_conductual_indi <= 21) {
    $total_ce_conductual_indi = "Alerta Alta";

} elseif ($total_ce_conductual_indi >= 22 AND $total_ce_conductual_indi <= 27) {
    $total_ce_conductual_indi = "Alerta Moderada";
} elseif ($total_ce_conductual_indi >= 28 AND $total_ce_conductual_indi <= 31) {
    $total_ce_conductual_indi = "Fortaleza Moderada";
} elseif ($total_ce_conductual_indi >= 32 AND $total_ce_conductual_indi <= 35) {
    $total_ce_conductual_indi = "Fortaleza Alta";
} else {
    $total_ce_conductual_indi = "Incompleta";
}

        } elseif ($nivel_alumno_indi == "1") {

            if ($total_ce_conductual_indi >= 7 AND $total_ce_conductual_indi <= 17) {
                $total_ce_conductual_indi = "Alerta Alta";

            } elseif ($total_ce_conductual_indi >= 18 AND $total_ce_conductual_indi <= 23) {
                $total_ce_conductual_indi = "Alerta Moderada";
            } elseif ($total_ce_conductual_indi >= 24 AND $total_ce_conductual_indi <= 29) {
                $total_ce_conductual_indi = "Fortaleza Moderada";
            } elseif ($total_ce_conductual_indi >= 30 AND $total_ce_conductual_indi <= 35) {// PREGUNTAR POR SI LA PUNTUACIÓN ESTA BIEN?
                $total_ce_conductual_indi = "Fortaleza Alta";
            } else {
                $total_ce_conductual_indi = "Incompleta";
            }

       
        }
        return $total_ce_conductual_indi;


    } catch (Exception $ex) {

        echo 'Excepción Capturada: ' . $ex->getMessage();

    }

}


function alert_o_fortaleza_ce_cognitivo($token_estudiante, $total_ce_cognitivo)
{
    try {
        $nivel_alumno = obtenemos_media_o_basica($token_estudiante);
        if ($nivel_alumno == "2") {
            if ($total_ce_cognitivo >= 12 AND $total_ce_cognitivo <= 31) {
                $total_ce_cognitivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-danger hvr hvr-grow' onclick='alerta_cognitiva(1);'>Alerta Alta</div>";

            } elseif ($total_ce_cognitivo >= 32 AND $total_ce_cognitivo <= 42) {
                $total_ce_cognitivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-alerta-media hvr hvr-grow' onclick='alerta_cognitiva(0);'>Alerta Moderada</div>";
            } elseif ($total_ce_cognitivo >= 43 AND $total_ce_cognitivo <= 50) {
                $total_ce_cognitivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-info hvr hvr-grow' onclick='fortaleza_cognitiva(0);'>Fortaleza Moderada</div>";
            } elseif ($total_ce_cognitivo >= 51 AND $total_ce_cognitivo <= 60) {
                $total_ce_cognitivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-primary hvr hvr-grow' onclick='fortaleza_cognitiva(1);'>Fortaleza Alta</div>";
            } else {
                $total_ce_cognitivo = "<div class='label label-default'>Incompleta</div>";
            }

        } elseif ($nivel_alumno == "1") {
            if ($total_ce_cognitivo >= 12 AND $total_ce_cognitivo <= 38) {
                $total_ce_cognitivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-danger hvr hvr-grow' onclick='alerta_cognitiva(1);'>Alerta Alta</div>";

            } elseif ($total_ce_cognitivo >= 39 AND $total_ce_cognitivo <= 44) {
                $total_ce_cognitivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-alerta-media hvr hvr-grow' onclick='alerta_cognitiva(0);'>Alerta Moderada</div>";
            } elseif ($total_ce_cognitivo >= 45 AND $total_ce_cognitivo <= 53) {
                $total_ce_cognitivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-info hvr hvr-grow' onclick='fortaleza_cognitiva(0);'>Fortaleza Moderada</div>";
            } elseif ($total_ce_cognitivo >= 54 AND $total_ce_cognitivo <= 60) {
                $total_ce_cognitivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-primary hvr hvr-grow' onclick='fortaleza_cognitiva(1);'>Fortaleza Alta</div>";
            } else {
                $total_ce_cognitivo = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-default'>Incompleta</div>";
            }

        }

        return $total_ce_cognitivo;

    } catch (Exception $ex) {

        echo 'Excepción Capturada: ' . $ex->getMessage();

    }
}

function alerta_o_fortaleza_indicador_ce_cognitivo($token_estudiante_indi, $total_ce_cognitivo_indi)
{

    try {
        $nivel_alumno_indi = obtenemos_media_o_basica($token_estudiante_indi);
        if ($nivel_alumno_indi == "2") {
          

            if ($total_ce_cognitivo_indi >= 12 AND $total_ce_cognitivo_indi <= 31) {
                $total_ce_cognitivo = "Alerta Alta";
            
            } elseif ($total_ce_cognitivo_indi >= 32 AND $total_ce_cognitivo_indi <= 42) {
                $total_ce_cognitivo_indi = "Alerta Moderada";
            } elseif ($total_ce_cognitivo_indi >= 43 AND $total_ce_cognitivo_indi <= 50) {
                $total_ce_cognitivo_indi = "Fortaleza Moderada";
            } elseif ($total_ce_cognitivo_indi >= 51 AND $total_ce_cognitivo_indi <= 60) {
                $total_ce_cognitivo_indi = "Fortaleza Alta";
            } else {
                $total_ce_cognitivo_indi = "Incompleta";
            }
            

        } elseif ($nivel_alumno_indi == "1") {

            if ($total_ce_cognitivo_indi >= 12 AND $total_ce_cognitivo_indi <= 38) {
                $total_ce_cognitivo_indi = "Alerta Alta";
            } elseif ($total_ce_cognitivo_indi >= 39 AND $total_ce_cognitivo_indi <= 44) {
                $total_ce_cognitivo_indi = "Alerta Moderada";
            } elseif ($total_ce_cognitivo_indi >= 45 AND $total_ce_cognitivo_indi <= 53) {
                $total_ce_cognitivo_indi = "Fortaleza Moderada";
            } elseif ($total_ce_cognitivo_indi >= 54 AND $total_ce_cognitivo_indi <= 60) {
                $total_ce_cognitivo_indi = "Fortaleza Alta";
            } else {
                $total_ce_cognitivo_indi = "Incompleta";
            }

        }
        return $total_ce_cognitivo_indi;


    } catch (Exception $ex) {

        echo 'Excepción Capturada: ' . $ex->getMessage();

    }

}

function alerta_o_fortaleza_indicador_ce_cognitivo_pantalla($token_estudiante_indi, $total_ce_cognitivo_indi)
{

    try {
        $nivel_alumno_indi = obtenemos_media_o_basica($token_estudiante_indi);
        if ($nivel_alumno_indi == "2") {
            if ($total_ce_cognitivo_indi >= 12 AND $total_ce_cognitivo_indi <= 42) {
                $total_ce_cognitivo_indi = "<div class='label label-alerta-alta' onclick='alerta_cognitiva();'>Alerta <i class='fa fa-arrow-left hvr hvr-grow animated infinite pulse'></i></div>";
            } elseif ($total_ce_cognitivo_indi >= 43 AND $total_ce_cognitivo_indi <= 60) {
                $total_ce_cognitivo_indi = "<div class='label label-primary' onclick='fortaleza_cognitiva();'>Fortaleza <i class='fa fa-arrow-left hvr hvr-grow animated infinite pulse'></i></div>";
            } else {
                $total_ce_cognitivo_indi = "<div class='label label-default'>Incompleta</div>";
            }


        } elseif ($nivel_alumno_indi == "1") {
            if ($total_ce_cognitivo_indi >= 12 AND $total_ce_cognitivo_indi <= 44) {
                $total_ce_cognitivo_indi = "<div class='label label-alerta-alta' onclick='alerta_cognitiva();'>Alerta <i class='fa fa-arrow-left hvr hvr-grow animated infinite pulse'></i></div>";
            } elseif ($total_ce_cognitivo_indi >= 45 AND $total_ce_cognitivo_indi <= 60) {
                $total_ce_cognitivo_indi = "<div class='label label-primary'  onclick='fortaleza_cognitiva();'>Fortaleza <i class='fa fa-arrow-left hvr hvr-grow animated infinite pulse'></i></div>";
            } else {
                $total_ce_cognitivo_indi = "<div class='label label-default'>Incompleta</div>";
            }

        }
        return $total_ce_cognitivo_indi;


    } catch (Exception $ex) {

        echo 'Excepción Capturada: ' . $ex->getMessage();

    }

}


function factores_contextuales_estudiante($token_estudiante, $id_docente)
{
    try {

        $con = connectDB_demos();
        $query = $con->query("SELECT (
			ce_p1+ce_p2+ce_p3+ce_p4+ce_p5+ce_p6+ce_p7+ce_p8+ce_p9+ce_p10+ce_p11+
			ce_p12+ce_p13+ce_p14+ce_p15+ce_p16+ce_p17+ce_p18+ce_p19+ce_p20+ce_p21+ce_p22+
			ce_p23+ce_p24+ce_p25+ce_p26+ce_p27+ce_p28+ce_p29
		 ) AS sumaCE,(
		 ce_p1+ce_p5+ce_p7+ce_p8+ce_p12+ce_p15+ce_p19+ce_p22+ce_p27+ce_p29
		 ) AS sumaAfectiva,(
		 ce_p3+ce_p4+ce_p9+ce_p11+
		   ce_p16+ce_p23+ce_p28
		 ) AS sumaConductual,
		 	(	 
			ce_p2+ce_p6+ce_p10+ce_p13+ce_p14+ce_p17+
			ce_p18+ce_p20+ce_p21+ce_p24+ce_p25+ce_p26
		 ) AS sumaCognitiva,
		  (
			ce_p30+ce_p31+ce_p32+ce_p33+ce_p34+ce_p35+
			ce_p36+ce_p37+ce_p38+ce_p39+ce_p40+ce_p41+
			ce_p42+ce_p43+ce_p44+ce_p45+ce_p46+ce_p47
		 ) as sumaFC,(
				ce_p30+ce_p31+ce_p32
		 ) as sumaFamilia,(
			ce_p33+ce_p34+ce_p35+ce_p36+
			ce_p37+ce_p38+ce_p39+ce_p40
		 ) as sumaProfes,(
			ce_p41+ce_p42+ce_p43+
			ce_p44+ce_p45+ce_p46+ce_p47
		 ) as sumaPares
		 
from ce_encuesta_resultado a where UPPER(a.ce_participantes_token_fk) = UPPER('$token_estudiante')");
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        $incompleta_ce = $resultado["sumaCE"];
        $incompleta_afectivo = $resultado["sumaAfectiva"];
        $incompleta_conductual = $resultado["sumaConductual"];
        $incompleta_cognitivo = $resultado["sumaCognitiva"];

        $incompleta_fc = $resultado["sumaFC"];
        $incompleta_sf = $resultado["sumaFamilia"];
        $incompleta_sprofes = $resultado["sumaProfes"];
        $incompleta_spares = $resultado["sumaPares"];
        $estado = '';
        if ($incompleta_ce == '') {
            $incompleta_ce = 0;
         
            if($incompleta_ce == 0){
                $estado = "hidden";
            }else{
                $estado = "";
            }
        }
        if ($incompleta_afectivo == '') {
            $incompleta_afectivo = 0;
        }
        if ($incompleta_conductual == '') {
            $incompleta_conductual = 0;
        }
        if ($incompleta_cognitivo == '') {
            $incompleta_cognitivo = 0;
        }

        if ($incompleta_fc == '') {
            $incompleta_fc = 0;
        }
        if ($incompleta_sf == '') {
            $incompleta_sf = 0;
        }
        if ($incompleta_sprofes == '') {
            $incompleta_sprofes = 0;
        }
        if ($incompleta_spares == '') {
            $incompleta_spares = 0;
        }

        ?>
        <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/jquery.loading.js"></script>
    <script src="../assets/js/jquery.loading.min.js"></script>
        <div>
            <input id="suma_fc" value="<?php echo factores_contextuales_estudiante_suma($token_estudiante) ?>"
                   class="hidden">
            <input id="suma_ce" value="<?php echo factores_compromiso_escolar_suma($token_estudiante) ?>"
                   class="hidden">

        </div>

        <table width="100%" class="table-responsive botones_sup">
            <tr valign="bottom">
                <td width="50%" valign="bottom">
                    <label>
                        Seleccione un Estudiante <i class="fa fa-user"></i>:
                    </label>
                </td>
            </tr>
            <tr id="btn_sup" valign="center" style="display: block;">
                <td class="td-res" width="50%" align="left" valign="center" style="padding-bottom: 30px; padding-top: 0px"  <?php echo $estado;?> >
                    <select onchange="E_seleccionado(this)" name="sle_estudiantes2" id="sle_estudiantes2" class="form-control" style="width: 300px;">
                        <?php
                            select_estudiantes_por_curso($id_docente);
                        ?>
                    </select>  
                    <div class="loader" id="loading_flag" hidden></div>               
                </td>
                <script type="text/javascript">
                    

                    function E_seleccionado(seleccionado) {
                       window.estudiante_seleccionado = seleccionado.selectedIndex;
                       console.log(window.estudiante_seleccionado);
                       $("#loading_flag").show();
                    }

                    $(document).ready(function () {
                        $("#loading_flag").hide();
                        if (window.hasOwnProperty("estudiante_seleccionado")) {
                            $('#sle_estudiantes2 option').eq(
                                window.estudiante_seleccionado
                            ).prop(
                                'selected', 
                                true
                            );
                        }
                    });
                </script>
                <td id="id_btn_grafica" class="td-res" width="25%" align="right" valign="center" style="padding-bottom: 30px; padding-top: 0px"  <?php echo $estado;?> >
                    <button class="btn btn-primary"  onclick="ver_dispersion();">
                            Ver Grafica de Dispersión 
                            <i class="fa fa-eye"></i>
                    </button>                   
                </td>
                <td id="id_btn_descargar" class="td-res" width="25%" align="right" valign="center" style="padding-bottom: 30px; padding-top: 0px"  <?php echo $estado;?> >
                    <button id="btn_reporte_descargar" style="display: none;" disabled="false" onclick="DescargarPDF('<?php echo $token_estudiante ?>');" class="btn btn-primary"  >
                        Descargar Reporte 
                        <i class="fa fa-download"></i>
                    </button>
                    <button id="btn_aux" class="btn btn-primary" type="button" disabled>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      Cargando...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <i class="fa fa-download"></i>
                    </button>
                </td>
            </tr>
        </table>

        
<div class="panel panel-info mt-4" style="padding-top: 0;">
    <div style="width: 100%; height: 60px; background: #d9edf7; margin-top: 0; padding-top: 0; border: 1px;">
        <table width="100%" style="height: 100%;">
            <tr valign="center">
                <td style="padding-left: 10px; font-weight: bold;" align="left" width="50%"><h4><strong>Compromiso Escolar</strong></h4></td>
                <td style="padding-left: 10px; font-weight: bold;" align="left" width="50%"><h4><strong>Factores Contextuales</strong></h4></td>
            </tr>      
        </table>
    </div>
<div class="panel-body">
    <div class="table-responsive mt-4" style="margin-top: 0; padding-top: 0;">                
        <table width="100%" class="table table-bordered" border="1" style="background: white;">
            <thead> 
                <tr id="id_sub_tit_niveles" valign="center" align="center" style="background: white;">
                    <table cellpadding="10" width="100%" style="background: white; height: 100%;">
                        <thead>
                            <th  class="niv" align="center" style="background: white;"> 
                                <td  class="niv" width="20%" align="right">
                                    &ensp;&ensp;Fortaleza Alta &ensp;
                                    <i class="fa fa-square" style="color:#3c8dbc; font-size:16px;" aria-hidden="true">  </i>
                                </td>
                                <td  class="niv" width="20%" align="right">
                                    &ensp;&ensp;Fortaleza Moderada &ensp;
                                    <i class="fa fa-square" style="color:#00c0ef; font-size:16px;" aria-hidden="true"></i> 
                                </td>
                                <td  class="niv" width="20%" align="center">
                                    &ensp;&ensp;Alerta Alta &ensp;
                                    <i class="fa fa-square" style="color:#DD4B39; font-size:16px;" aria-hidden="true"></i>
                                </td>
                                <td  class="niv" width="20%" align="left">
                                    &ensp;&ensp;Alerta Moderada &ensp;
                                    <i class="fa fa-square" style="color:#FFA420; font-size:16px;" aria-hidden="true"></i>
                                </td>
                                <td  class="niv" width="20%" align="left">
                                    &ensp;&ensp;Incompleta &ensp;
                                    <i class="fa fa-square" style="color:#808181; font-size:16px;" aria-hidden="true"></i>
                                </td>
                            </th>
                        </thead>
                    </table>
                </tr>
            </thead>
            <tbody>
                <tr>
                    
                    <table width="100%">
                        <tr id="id_panel_niveles">
                    <td width="50%" >
                        <div class="">
            <div class="panel-body">
                <div class="table-responsive mt-4">
                    <table width="100%" class="table table-bordered" border="0" style="font-size:16px;">
                        <thead>
                        <tr style="background: #d9edf7;">
                            <th>Dimensión</th>
                            <th>Resultado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr align="left">
                            <td width="70%" align="left">
                                <a class="cursor_dimensiones" onclick="definicion_compromiso_escolar();"> 
                                    Compromiso Escolar
                                </a>
                            </td>
                            <td width="30%" style="max-height: 25px;"  align="left">
                                <a class="cursor_resultados" style="max-height: 25px; width: 100%;">
                                    <?php 
                                        echo alert_o_fortaleza_compromiso_escolar(
                                            $token_estudiante, 
                                            $resultado["sumaCE"]
                                        ) 
                                    ?>
                                </a>
                            </td>
                        </tr>
                        <tr align="left">
                            <td width="70%" align="left">
                                <a class="cursor_dimensiones" onclick="definicion_afectiva();"> 
                                    <div class="tamaño-letra-tablas">
                                        Afectivo
                                    </div>
                                </a>
                            </td>
                            <td width="30%" style="max-height: 25px;"  align="left">
                                <a class="cursor_resultados" >
                                    <?php 
                                        echo alert_o_fortaleza_ce_afectivo(
                                            $token_estudiante, 
                                            $resultado["sumaAfectiva"]
                                        ) 
                                    ?>        
                                </a>
                            </td>
                            
                        </tr>

                        <tr>
                            <td width="70%" align="left">
                                <a class="cursor_dimensiones" onclick="definicion_conductual();">
                                    Conductual
                                </a>
                            </td>
                            <td width="30%" style="max-height: 25px;"  align="left">
                                <a class="cursor_resultados" style="max-height: 25px; width: 100%;">
                                    <?php 
                                        echo alert_o_fortaleza_ce_conductual(
                                            $token_estudiante, 
                                            $resultado["sumaConductual"]
                                        ) 
                                    ?> 
                                </a>
                            </td>
                          
                        </tr>

                        <tr>
                            <td width="70%" align="left">
                                <a class="cursor_dimensiones" onclick="definicion_cognitivo();">
                                    Cognitivo
                                </a>
                            </td>
                            <td width="30%" style="max-height: 25px;"  align="left">
                                <a class="cursor_resultados" style="max-height: 25px; width: 100%;">
                                    <?php 
                                        echo alert_o_fortaleza_ce_cognitivo(
                                            $token_estudiante, 
                                            $resultado["sumaCognitiva"]
                                        ) 
                                    ?>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
                    </td>
                    <td width="50%" align="left">
                        <div class="" >
            <div class="panel-body" >
                <div class="table-responsive mt-4">
                    <table width="100%" class="table table-bordered" style="font-size:16px;">
                        <thead>
                        <tr style="background: #d9edf7;">
                            <th>Dimensión</th>
                            <th>Resultado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td width="70%" align="left">
                                <a class="cursor_dimensiones" onclick="deficion_factores_contextuales();"> 
                                    Factores Contextuales
                                </a>
                            </td>
                            <td width="30%" style="max-height: 25px;"  align="left">
                                <a class="cursor_resultados" style="max-height: 25px; width: 100%;">
                                    <?php 
                                        echo alerta_o_fortaleza_factor_contextu_media(
                                            $resultado["sumaFC"]
                                        ) 
                                    ?>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td width="70%" align="left">
                                <a class="cursor_dimensiones" onclick="definicion_apoyo_familia();"> 
                                    Apoyo Familia
                                </a>
                            </td>
                            <td width="30%" style="max-height: 25px;"  align="left">
                                <a class="cursor_resultados" style="max-height: 25px; width: 100%;">
                                    <?php 
                                        echo alerta_o_fortaleza_apoyo_familia_media(
                                            $resultado["sumaFamilia"]
                                        ) 
                                    ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td width="70%" align="left">
                                <a class="cursor_dimensiones" onclick="definicion_apoyo_pares();">
                                    Apoyo Pares
                                </a>
                            </td>
                            <td width="30%" style="max-height: 25px;"  align="left">
                                <a class="cursor_resultados" style="max-height: 25px; width: 100%;">
                                    <?php 
                                        echo alerta_o_fortaleza_apoyo_pares_media(
                                            $resultado["sumaPares"]
                                        ) 
                                    ?>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td width="70%" align="left">
                                <a class="cursor_dimensiones" onclick="definicion_apoyo_profesores();">
                                    Apoyo Profesores
                                </a>
                            </td>
                            <td width="30%" style="max-height: 25px;"  align="left">
                                <a class="cursor_resultados" style="max-height: 25px; width: 100%;">
                                    <?php 
                                        echo alerta_o_fortaleza_apoyo_profes_media(
                                            $resultado["sumaProfes"]
                                        ) 
                                    ?>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            
            
        </div>
                    </td>
                </tr>
            </table>

                </tr>
            </tbody>
        </table>
        </div>
    </div>
</div>


<br>

<div class="panel panel-info mt-4" style="padding-top: 0;">
    <div style="width: 100%; height: 60px; background: #d9edf7; margin-top: 0; padding-top: 0; border: 1px;">
        <table width="100%" style="height: 100%;">
            <tr valign="center">
                <td style="padding-left: 10px; font-weight: bold;" align="left" width="100%">
                    <h4>
                        <span><strong>Gráfico de Dispersión</strong><span>
                    </h4>
                </td>
            </tr>      
        </table>
    </div>
    <div id="id_panel_grafico" class="panel-body">
        <div>
        <span>
            <p style="font-size: 20px; text-align: center">Reporte Estudiante <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="definicion_cuadrantes()"></i></p>
        </span>
        </div>
        <div class="table-responsive mt-4" style="margin-top: 0; padding-top: 0;">
            <div id="ver_dispersion">
                <div id="demo_dispersion_alumno" style="height: 450px; margin: auto;" hidden>  
                    
                </div>
            </div>
        </div>
    </div>
</div>

        <script> 
            var grafico;

            // no quitar eso es para q el grafico se actualice $(window).resize
            var size = document.getElementById('id_panel_grafico').offsetWidth;
            grafico = grafica_dispersion_estudi(
                size,
                <?php 
                    echo factores_contextuales_estudiante_suma($token_estudiante)
                ?>, 
                <?php
                    echo factores_compromiso_escolar_suma($token_estudiante)
                ?>, 
                '<?php 
                    echo $token_estudiante 
                ?>'
            );

            $(window).ready(function() {
                $(window).resize(function() {
                    var size = document.getElementById('id_panel_grafico').offsetWidth;
                    grafico = grafica_dispersion_estudi(
                        size,
                        <?php 
                            echo factores_contextuales_estudiante_suma($token_estudiante)
                        ?>, 
                        <?php
                            echo factores_compromiso_escolar_suma($token_estudiante)
                        ?>, 
                        '<?php 
                            echo $token_estudiante 
                        ?>'
                    );
                    
                });

                $(".btn_side").click(function () {
                

                    setTimeout(function() {
                        var size = document.getElementById('id_panel_grafico').offsetWidth;
                    grafico = grafica_dispersion_estudi(
                        size,
                        <?php 
                            echo factores_contextuales_estudiante_suma($token_estudiante)
                        ?>, 
                        <?php
                            echo factores_compromiso_escolar_suma($token_estudiante)
                        ?>, 
                        '<?php 
                            echo $token_estudiante 
                        ?>'
                    );
                    }, 
                    300
                    );
                
                });
            });
  
  


        </script>
        <?php
    } catch (Exception $ex) {
        echo 'Excepción Capturada: ' . $ex->getMessage();
    }
}
function descripcion_cuadrantes_ce_fc($token_estudiante){
    $fc = factores_contextuales_estudiante_suma($token_estudiante);
    $ce = factores_compromiso_escolar_suma($token_estudiante);
    $nivel = obtenemos_media_o_basica($token_estudiante);
    
    echo $nivel.$fc.$ce;
    if($nivel == 'BASICA'){
                
    }
    
}

function alerta_o_fortaleza_factor_contextu_media($total_FC)
{
    if ($total_FC <= 63) {
        //Alerta Alta
        if ($total_FC >= 18 AND $total_FC <= 48) {
            $total_FC = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-danger hvr hvr-grow' onclick='definicion_factores_contextuales_promedio(2);'>Alerta Alta</div>";
            
        } //Alerta Media
        elseif ($total_FC >= 50 AND $total_FC <= 63) {
            $total_FC = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-alerta-media hvr hvr-grow' onclick='definicion_factores_contextuales_promedio(2);'>Alerta Moderada</div>";
        } else {
            $total_FC = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-default'>Incompleta</div>";
        }
    } elseif ($total_FC >= 64 AND $total_FC <= 90) {
        //Fortaleza Media
        if ($total_FC >= 64 AND $total_FC <= 76) {
             $total_FC = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-info hvr hvr-grow' onclick='definicion_factores_contextuales_promedio(1);'>Fortaleza Moderada</div>";
        } //Fortaleza Alta
        elseif ($total_FC >= 77 AND $total_FC <= 90) {
            $total_FC = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-primary hvr hvr-grow' onclick='definicion_factores_contextuales_promedio(1);'>Fortaleza Alta</div>";
        } else {
            $total_FC = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-default'>Incompleta</div>";
        }
    }
    return $total_FC;
}



function alerta_o_fortaleza_indicador_fc_reporte_individual($total)
{
        if ($total >= 18 AND $total <= 48) {
            $total = "Alerta Alta";
            
        } 

        elseif ($total >= 50 AND $total <= 63) {
            $total = "Alerta Moderada";
        } 

        elseif ($total >= 64 AND $total <= 76) {
            $total = "Fortaleza Moderada";
        }

        elseif ($total >= 77 AND $total <= 90) {
            $total = "Fortaleza Alta";
        }        
        else {
            $total = "Incompleta";
        }
       
   

    return $total;
}


function alerta_o_fortaleza_apoyo_familia_media($total_AF)
{
    if ($total_AF <= 8) {
        //Alerta Alta
        if ($total_AF >= 3 AND $total_AF <= 5) {
            $total_AF = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-danger hvr hvr-grow' onclick='ale_apoyo_familiar(1);'>Alerta Alta</div>";
        } //Alerta Media
        elseif ($total_AF >= 6 AND $total_AF <= 8) {
            $total_AF = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-alerta-media hvr hvr-grow' onclick='ale_apoyo_familiar(0);'>Alerta Moderada</div>";
        } else {
            $total_AF = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-default'>Incompleta</div>";
        }
    } elseif ($total_AF >= 9 AND $total_AF <= 15) {
        //Fortaleza Media
        if ($total_AF >= 9 AND $total_AF <= 11) {
            $total_AF = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-info hvr hvr-grow' onclick='for_apoyo_familiar(0);'>Fortaleza Moderada</div>";
        } //Fortaleza Alta
        elseif ($total_AF >= 12 AND $total_AF <= 15) {
            $total_AF = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-primary hvr hvr-grow' onclick='for_apoyo_familiar(1);'>Fortaleza Alta</div>";
        } else {
            $total_AF = "<div style='width: 100%; max-height: 25px;  color: white; padding: 1px; text-align: center;' class='label-default'>Incompleta</div>";
        }
    }
    return $total_AF;
}



function alerta_o_fortaleza_indicador_af_reporte_individual($total)
{
 
        //Alerta Alta
        if ($total >= 3 AND $total <= 5) {
            $total = "Alerta Alta";
        } //Alerta Media
        elseif ($total >= 6 AND $total <= 8) {
            $total = "Alerta Moderada";
        }
        //Fortaleza Media
        elseif ($total >= 9 AND $total <= 11) {
            $total = "Fortaleza Moderada";
        } //Fortaleza Alta
        elseif ($total >= 12 AND $total <= 15) {
            $total = "Fortaleza Alta";
        } else {
            $total = "Incompleta";
        }
   
    return $total;
}

function alerta_o_fortaleza_apoyo_pares_media($total_APares)
{
    if ($total_APares <= 23) {
        //Alerta Alta
        if ($total_APares >= 7 AND $total_APares <= 16) {
            $total_APares = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-danger hvr hvr-grow' onclick='ale_apoyo_pares(1);'>Alerta Alta</div>
           ";
        } //Alerta Media
        elseif ($total_APares >= 17 AND $total_APares <= 23) {
            $total_APares = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-alerta-media hvr hvr-grow' onclick='ale_apoyo_pares(0);'>Alerta Moderada</div>";
        } else {
            $total_APares = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-default'>Incompleta</div>";
        }
    } elseif ($total_APares >= 24 AND $total_APares <= 35) {
        //Fortaleza Media
        if ($total_APares >= 24 AND $total_APares <= 29) {
            $total_APares = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-info hvr hvr-grow' onclick='for_apoyo_pares(1);'>Fortaleza Moderada</div>";
        } //Fortaleza Alta
        elseif ($total_APares >= 30 AND $total_APares <= 35) {
            $total_APares = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-primary hvr hvr-grow' onclick='for_apoyo_pares(0);'>Fortaleza Alta</div>";
        } else {
            $total_APares = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-default'>Incompleta</div>";
        }
    }
    return $total_APares;
}

function alerta_o_fortaleza_indicador_apoyo_pares_reporte_individual($total)
{
   
        //Alerta Alta
        if ($total >= 7 AND $total <= 16) {
            $total = "Alerta Alta";
        } //Alerta Media
        elseif ($total >= 17 AND $total <= 23) {
            $total = "Alerta Moderada";
        }    
        //Fortaleza Media
        elseif ($total >= 24 AND $total <= 29) {
            $total = "Fortaleza Moderada";
        } //Fortaleza Alta
        elseif ($total >= 30 AND $total <= 35) {
            $total = "Fortaleza Alta";
        } else {
            $total = "Incompleta";
        }
    
    return $total;
}



function alerta_o_fortaleza_apoyo_profes_media($total_AProfes)
{
    if ($total_AProfes <= 29) {
        //Alerta Alta
        if ($total_AProfes >= 8 AND $total_AProfes <= 21) {
            $total_AProfes = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-danger hvr hvr-grow' onclick='ale_apoyo_docente(1);'>Alerta Alta</div>
           
            ";
        } //Alerta Media
        elseif ($total_AProfes >= 22 AND $total_AProfes <= 29) {
            $total_AProfes = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-alerta-media hvr hvr-grow' onclick='ale_apoyo_docente(0);'>Alerta Moderada</div>";
        } else {
            $total_AProfes = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-default'>Incompleta</div>";
        }
    } elseif ($total_AProfes >= 30 AND $total_AProfes <= 40) {
        //Fortaleza Media
        if ($total_AProfes >= 30 AND $total_AProfes <= 35) {
            $total_AProfes = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-info hvr hvr-grow' onclick='for_apoyo_docente(0);'>Fortaleza Moderada</div>";
        } //Fortaleza Alta
        elseif ($total_AProfes >= 36 AND $total_AProfes <= 40) {
            $total_AProfes = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-primary hvr hvr-grow' onclick='for_apoyo_docente(1);'>Fortaleza Alta</div>";
        } else {
            $total_AProfes = "<div style='width: 100%; max-height: 25px;  padding: 1px; text-align: center; color: white;' class='label-default'>Incompleta</div>";
        }
    }
    return $total_AProfes;
}

function alerta_o_fortaleza_indicador_apoyo_profesores_reporte_individual($total)
{
            //Alerta Alta
        if ($total >= 8 AND $total <= 21) {
            $total = "Alerta Alta";      
           
        } //Alerta Media
        elseif ($total >= 22 AND $total <= 29) {
            $total = "Alerta Moderada";
        } 
        //Fortaleza Media
        elseif ($total >= 30 AND $total <= 35) {
            $total = "Fortaleza Moderada";
        } //Fortaleza Alta
        elseif ($total >= 36 AND $total <= 40) {
            $total = "Fortaleza Alta";
        } else {
            $total = "Incompleta";
        }
    

    return $total;
}



//Reporte Individual factores Contextuales

function reporte_factores_contextuales_estudiante($token_estudiante)
{
    try {
        $con = connectDB_demos();
        $query = $con->query("SELECT (
			ce_p30+ce_p31+ce_p32+ce_p33+ce_p34+ce_p35+
			ce_p36+ce_p37+ce_p38+ce_p39+ce_p40+ce_p41+
			ce_p42+ce_p43+ce_p44+ce_p45+ce_p46+ce_p47
		 ) as sumaFC,(
				ce_p30+ce_p31+ce_p32
		 ) as sumaFamilia,(
			ce_p33+ce_p34+ce_p35+ce_p36+
			ce_p37+ce_p38+ce_p39+ce_p40
		 ) as sumaProfes,(
			ce_p41+ce_p42+ce_p43+
			ce_p44+ce_p45+ce_p46+ce_p47
		 ) as sumaPares
         
from ce_encuesta_resultado a where UPPER(a.ce_participantes_token_fk) = UPPER('$token_estudiante')");

    } catch (Exception $ex) {
        echo 'Excepción Capturada: ' . $ex->getMessage();
    }

    return $query;
}

function descripcion_indicador($nombre_indicador)
{
    if ($nombre_indicador == "Fortaleza") {
        $nombre_indicador = " -El/La estudiante presenta indicadores que dan cuenta de una situación de fortaIeza para este factor.";
    } elseif ($nombre_indicador == "Alerta") {
        $nombre_indicador = " -El/La estudiante presenta indicadores que dan cuenta de una situación de vulnerabiIidad para este factor.";
    } else {
        $nombre_indicador = "Indefinido";
    }

    return $nombre_indicador;
}

function descripcion_indicador_conductual($nombre_indicador)
{
    if ($nombre_indicador == "Fortaleza Alta") {
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante suele cumplir con las normas de convivencia escolar y el comportamiento esperado dentro del
    colegio (Por ej. llega a la hora, no hace la cimarra), razón por la cual no es derivado a inspectoría con frecuencia
    o son sus apoderados citados.</p>
<br>
<p> -El (la) estudiante suele cumplir con el comportamiento esperado dentro de la sala de clases (ej. no pelea con sus
    compañeros, pide permiso para salir de la sala).</p>";
    }
    elseif($nombre_indicador == "Fortaleza Moderada"){
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante suele cumplir con las normas de convivencia escolar y el comportamiento esperado dentro del
        colegio (Por ej. llega a la hora, no hace la cimarra), razón por la cual no es derivado a inspectoría con frecuencia
        o son sus apoderados citados.</p>
    <br>
    <p> -El (la) estudiante suele cumplir con el comportamiento esperado dentro de la sala de clases (ej. no pelea con sus
        compañeros, pide permiso para salir de la sala).</p>";

    }
    elseif ($nombre_indicador == "Alerta Moderada") {
        $nombre_indicador = "<p> El o la estudiante posee alertas en algunos de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante no suele cumplir con las normas de convivencia escolar y el comportamiento esperado dentro del colegio (Por ej. no llega a la hora, o hace la cimarra), razón por la cual es derivado a inspectoría con frecuencia o son sus apoderados citados.</p>
<br>
<p> -El (la) estudiante no suele cumplir con el comportamiento esperado dentro de la sala de clases (ej; pelea con sus compañeros o no pide permiso para salir de la sala).</p>";
    }
    elseif ($nombre_indicador == "Alerta Alta") {
        $nombre_indicador = "<p> El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante no suele cumplir con las normas de convivencia escolar y el comportamiento esperado dentro del colegio (Por ej. no llega a la hora, o hace la cimarra), razón por la cual es derivado a inspectoría con frecuencia o son sus apoderados citados.</p>
<br>
<p> -El (la) estudiante no suele cumplir con el comportamiento esperado dentro de la sala de clases (ej; pelea con sus compañeros o no pide permiso para salir de la sala).</p>";
    } else {
        $nombre_indicador = "Indefinido";
    }

    return $nombre_indicador;
}

function descripcion_indicador_ce_afectivo_($nombre_indicador)
{
    if ($nombre_indicador == "Fortaleza Alta") {
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante suele sentirse parte e integrado a su establecimiento escolar, cómodo y orgulloso de estar en
    dicho establecimiento. A la vez, siente que él o ella es importante para el colegio, que allí es respetado,
    aceptado, apreciándose que mantiene relaciones de colaboración y apoyo con sus compañeros.</p>
<br>
<p> -El (la) estudiante reconoce la importancia y utilidad del colegio y Io que allí aprenden, al tiempo que siente que
    los profesores se preocupan de Io que se aprende en el colegio sea útil.</p>
<br>
<p> -El (la) estudiante reconoce que asistir al establecimiento escolar y aprender en clases es importante para conseguir
    metas futuras, demostrando interés por las tareas académicas y por aprender.</p>";
    } 
    
    elseif ($nombre_indicador == "Fortaleza Moderada") {
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante suele sentirse parte e integrado a su establecimiento escolar, cómodo y orgulloso de estar en
    dicho establecimiento. A la vez, siente que él o ella es importante para el colegio, que allí es respetado,
    aceptado, apreciándose que mantiene relaciones de colaboración y apoyo con sus compañeros.</p>
<br>
<p> -El (la) estudiante reconoce la importancia y utilidad del colegio y Io que allí aprenden, al tiempo que siente que
    los profesores se preocupan de Io que se aprende en el colegio sea útil.</p>
<br>
<p> -El (la) estudiante reconoce que asistir al establecimiento escolar y aprender en clases es importante para conseguir
    metas futuras, demostrando interés por las tareas académicas y por aprender.</p>";
    }

    elseif ($nombre_indicador == "Alerta Alta" ) {
        $nombre_indicador = "<p> El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante no suele sentirse parte ni integrado a su establecimiento escolar. Tampoco se siente cómodo y
    orgulloso de estar en dicho establecimiento. A la vez, siente que él o ella no es importante para el colegio, que
    allí no es respetado, aceptado, apreciándose que no mantiene relaciones de colaboración y apoyo con sus compañeros.
    Lo anterior puede vincularse a una falta de instancias educativas que promuevan la identidad del colegio, la
    integración entre sus estudiantes y el respeto de los estudiantes independiente de sus diferencias.</p>
<br>
<p> -El (la) estudiante no reconoce la importancia ni la utilidad del colegio y Io que allí aprenden, al tiempo que siente
    que los profesores no se preocupan de Io que se aprende en el colegio sea útil. Lo anterior puede vincularse a las
    prácticas pedagógicas dentro del aula que no permiten al estudiante vislumbrar la importancia de Io que allí se
    aprende, tales como vincular la materia con problemáticas reales y su experiencia cotidiana.</p>
<br>
<p> -El (la) estudiante no suele considerar que asistir al establecimiento escolar y aprender en clases es importante para
    conseguir metas futuras, ni tampoco demuestra interés por las tareas académicas. Lo anterior puede vincularse a las
    práctica pedagógicas dentro del aula que no permiten vislumbrar al estudiante la importancia de Io que allí se
    aprende para alcanzar sus metas futuras.</p>";
    }
    
    elseif ($nombre_indicador == "Alerta Moderada" ) {
        $nombre_indicador = "<p> El o la estudiante posee alertas en algunos de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante no suele sentirse parte ni integrado a su establecimiento escolar. Tampoco se siente cómodo y
    orgulloso de estar en dicho establecimiento. A la vez, siente que él o ella no es importante para el colegio, que
    allí no es respetado, aceptado, apreciándose que no mantiene relaciones de colaboración y apoyo con sus compañeros.
    Lo anterior puede vincularse a una falta de instancias educativas que promuevan la identidad del colegio, la
    integración entre sus estudiantes y el respeto de los estudiantes independiente de sus diferencias.</p>
<br>
<p> -El (la) estudiante no reconoce la importancia ni la utilidad del colegio y Io que allí aprenden, al tiempo que siente
    que los profesores no se preocupan de Io que se aprende en el colegio sea útil. Lo anterior puede vincularse a las
    prácticas pedagógicas dentro del aula que no permiten al estudiante vislumbrar la importancia de Io que allí se
    aprende, tales como vincular la materia con problemáticas reales y su experiencia cotidiana.</p>
<br>
<p> -El (la) estudiante no suele considerar que asistir al establecimiento escolar y aprender en clases es importante para
    conseguir metas futuras, ni tampoco demuestra interés por las tareas académicas. Lo anterior puede vincularse a las
    práctica pedagógicas dentro del aula que no permiten vislumbrar al estudiante la importancia de Io que allí se
    aprende para alcanzar sus metas futuras.</p>";
    }else {
        $nombre_indicador = "Indefinido";
    }

    return $nombre_indicador;
}

function descripcion_indicador_cognitivo($nombre_indicador)
{
    if ($nombre_indicador == "Fortaleza Alta") {
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante suele desplegar estrategias cognitivas profundas de aprendizaje (ej. integra ideas, hace uso de la
    evidencia, relaciona materia con conocimientos previos, utiliza distintos recursos complementarios, hace resúmenes,
    mapas conceptuales) en vez de aplicar estrategias superficiales (memorización, repetición). Además, el (la)
    estudiante suele estar orientado al proceso por sobre el resultado, siendo importante para él o ella lograr entender
    bien las tareas y la materia, tratar de hacer su trabajo a fondo y bien, en vez de hacerlo por cumplir.</p>
<br>
<p> -El (la) estudiante conoce qué hábitos y estrategias puede desplegar para realizar bien sus tareas, al tiempo que está
    consciente de sus dificultades y de lo que tiene que trabajar para obtener mejores calificaciones, como así también
    de sus propios intereses y motivaciones.</p>
<br>
<p> -El (la) estudiante suele hacer uso de estrategias de control y autorregulación en su proceso de aprendizaje, tales
    como panificación, supervisión y autoevaluación. Lo anterior se evidencia cuando el estudiante revisa si sus tareas
    están bien hechas, si revisa si ha logrado conseguir el objetivo propuesto, si planifica cómo estudiar, si pone
    atención a la retroalimentación que le entregan de sus trabajos, si busca ayuda cuando no entiende y si monitorea
    sus progresos.</p>";
    } 
    elseif ($nombre_indicador == "Fortaleza Moderada") {
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante suele desplegar estrategias cognitivas profundas de aprendizaje (ej. integra ideas, hace uso de la
    evidencia, relaciona materia con conocimientos previos, utiliza distintos recursos complementarios, hace resúmenes,
    mapas conceptuales) en vez de aplicar estrategias superficiales (memorización, repetición). Además, el (la)
    estudiante suele estar orientado al proceso por sobre el resultado, siendo importante para él o ella lograr entender
    bien las tareas y la materia, tratar de hacer su trabajo a fondo y bien, en vez de hacerlo por cumplir.</p>
<br>
<p> -El (la) estudiante conoce qué hábitos y estrategias puede desplegar para realizar bien sus tareas, al tiempo que está
    consciente de sus dificultades y de lo que tiene que trabajar para obtener mejores calificaciones, como así también
    de sus propios intereses y motivaciones.</p>
<br>
<p> -El (la) estudiante suele hacer uso de estrategias de control y autorregulación en su proceso de aprendizaje, tales
    como panificación, supervisión y autoevaluación. Lo anterior se evidencia cuando el estudiante revisa si sus tareas
    están bien hechas, si revisa si ha logrado conseguir el objetivo propuesto, si planifica cómo estudiar, si pone
    atención a la retroalimentación que le entregan de sus trabajos, si busca ayuda cuando no entiende y si monitorea
    sus progresos.</p>";
    } 
    
    elseif ($nombre_indicador == "Alerta Moderada") {
        $nombre_indicador = "<p> El o la estudiante posee Alertas en algunos de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante no suele desplegar estrategias cognitivas profundas de aprendizaje (ej. integrar ideas, hacer uso
    de la evidencia, relacionar materia con conocimientos previos, utilizar distintos recursos complementarios, hacer
    resúmenes, mapas conceptuales) en vez de aplicar estrategias superficiales (memorización, repetición). Además, el
    (la) estudiante suele estar más orientado al resultado por sobre el proceso, siendo importante hacer el trabajo por
    cumplir en vez de lograr entender bien las tareas y la materia. Lo anterior, también puede vincularse con prácticas
    pedagógicas que no promueven el uso de estrategias profundas de aprendizaje, sino que el uso de estrategias más bien
    superficiales, siendo los estudiantes evaluados por el uso de estas.</p>
<br>
<p> -El (la) estudiante presenta dificultades para reconocer lo que sabe y no sabe en relación a las tareas y las
    estrategias de aprendizaje que puede utilizar para realizar bien sus tareas; al tiempo que demuestra dificultades
    para reconocer sus propios intereses, motivaciones y lo que tiene que trabajar para obtener mejores calificaciones.
    Lo anterior puede vincularse con la falta de instancias promovidas por el cuerpo docente para reflexionar sobre las
    tareas y las estrategias que se utilizan, no existiendo espacios de retroalimentación luego de ser entregada una
    nota.</p>
<br>
<p> -El (la) estudiante no suele hacer uso de estrategias de control y autorregulación durante su proceso de aprendizaje,
    tales como planificación, supervisión y autoevaluación. Lo anterior se evidencia porque el estudiante no suele
    revisar si sus tareas están bien hechas, si ha logrado conseguir el objetivo propuesto, no suele planificar cómo
    estudiar ni poner atención a la retroalimentación que le entregan de sus trabajos, no suele buscar ayuda cuando no
    entiende y no tiende a monitorear sus progresos. Lo anterior puede vincularse a su vez con una falta de instancias
    promovidas por el establecimiento escolar que permitan al estudiante desarrollar dichas estrategias.</p>";
    } 
    elseif ($nombre_indicador == "Alerta Alta") {
        $nombre_indicador = "<p> El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante no suele desplegar estrategias cognitivas profundas de aprendizaje (ej. integrar ideas, hacer uso
    de la evidencia, relacionar materia con conocimientos previos, utilizar distintos recursos complementarios, hacer
    resúmenes, mapas conceptuales) en vez de aplicar estrategias superficiales (memorización, repetición). Además, el
    (la) estudiante suele estar más orientado al resultado por sobre el proceso, siendo importante hacer el trabajo por
    cumplir en vez de lograr entender bien las tareas y la materia. Lo anterior, también puede vincularse con prácticas
    pedagógicas que no promueven el uso de estrategias profundas de aprendizaje, sino que el uso de estrategias más bien
    superficiales, siendo los estudiantes evaluados por el uso de estas.</p>
<br>
<p> -El (la) estudiante presenta dificultades para reconocer lo que sabe y no sabe en relación a las tareas y las
    estrategias de aprendizaje que puede utilizar para realizar bien sus tareas; al tiempo que demuestra dificultades
    para reconocer sus propios intereses, motivaciones y lo que tiene que trabajar para obtener mejores calificaciones.
    Lo anterior puede vincularse con la falta de instancias promovidas por el cuerpo docente para reflexionar sobre las
    tareas y las estrategias que se utilizan, no existiendo espacios de retroalimentación luego de ser entregada una
    nota.</p>
<br>
<p> -El (la) estudiante no suele hacer uso de estrategias de control y autorregulación durante su proceso de aprendizaje,
    tales como planificación, supervisión y autoevaluación. Lo anterior se evidencia porque el estudiante no suele
    revisar si sus tareas están bien hechas, si ha logrado conseguir el objetivo propuesto, no suele planificar cómo
    estudiar ni poner atención a la retroalimentación que le entregan de sus trabajos, no suele buscar ayuda cuando no
    entiende y no tiende a monitorear sus progresos. Lo anterior puede vincularse a su vez con una falta de instancias
    promovidas por el establecimiento escolar que permitan al estudiante desarrollar dichas estrategias.</p>";
    }
    else {
        $nombre_indicador = "Indefinido";
    }

    return $nombre_indicador;
}

//datos de estudiante para reporte personal

function descripcion_indicador_familiar($nombre_indicador)
{
    if ($nombre_indicador == "Fortaleza Alta" ) {
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</p><br>
        <p> -La familia del (la) estudiante suele apoyar a su hijo(a) en el proceso de aprendizaje y cuando tiene problemas, ayudándolo con las tareas, conversando lo que sucede en la escuela, animándolo y motivándolo a trabajar bien.</p>";
    }
    elseif ($nombre_indicador == "Fortaleza Moderada" ) {
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</p><br>
        <p> -La familia del (la) estudiante suele apoyar a su hijo(a) en el proceso de aprendizaje y cuando tiene problemas, ayudándolo con las tareas, conversando Io que sucede en la escuela, animándolo y motivándolo a trabajar bien.</p>";
    }
    elseif ($nombre_indicador == "Alerta Alta") {
        $nombre_indicador = "<p> El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</p><br>
        <p> -La familia del (la) estudiante no suele apoyar a su hijo(a) en el proceso de aprendizaje o cuando tiene problemas. Tampoco suele apoyarlo en las tareas, motivarlo a trabajar bien o conversar con él o ella sobre Io que sucede en la escuela.</p>";
    } 
    elseif ( $nombre_indicador == "Alerta Moderada") {
        $nombre_indicador = "<p> El o la estudiante posee alertas en algunos de los siguientes aspectos:</p><br>
        <p> -La familia del (la) estudiante no suele apoyar a su hijo(a) en el proceso de aprendizaje o cuando tiene problemas. Tampoco suele apoyarlo en las tareas, motivarlo a trabajar bien o conversar con él o ella sobre Io que sucede en la escuela.</p>";
    } 
    else {
        $nombre_indicador = "Indefinido";
    }

    return $nombre_indicador;
}

function descripcion_indicador_pares($nombre_indicador)
{
    if ($nombre_indicador == "Fortaleza Alta" ) {
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</p><br>
        <p> -Existen buenas relaciones entre los compañeros, se apoyan y se preocupan por él (ella), que puede confiar en sus compañeros, siendo estos importantes para él (ella) y de manera inversa. Se siente integrado y apoyado frente a desafíos escolares y/o cuando tiene una dificultad académica.</p>";
    }
    
    elseif ($nombre_indicador == "Fortaleza Moderada") {
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</p><br>
        <p> -Existen buenas relaciones entre los compañeros, se apoyan y se preocupan por él (ella), que puede confiar en sus compañeros, siendo estos importantes para él (ella) y de manera inversa. Se siente integrado y apoyado frente a desafíos escolares y/o cuando tiene una dificultad académica.</p>";
    }
    elseif ($nombre_indicador == "Alerta Alta") {
        $nombre_indicador = "<p> El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</p><br>
        <p> -No existen buenas relaciones entre los compañeros ni que éstos Io apoyan y se preocupan por él (ella).</p>
<br>
<p> -El (la estudiante) no siente que puede confiar en sus compañeros ni que estos son importantes para él (ella) y de
    manera inversa. En general no se siente integrado ni apoyado frente a desafíos escolares y/o cuando tiene una
    dificultad académica.</p>";
    } 
    elseif ($nombre_indicador == "Alerta Moderada") {
        $nombre_indicador = "<p> El o la estudiante posee alertas en algunos de los siguientes aspectos:</p><br>
        <p> -No existen buenas relaciones entre los compañeros ni que éstos Io apoyan y se preocupan por él (ella).</p>
<br>
<p> -El (la estudiante) no siente que puede confiar en sus compañeros ni que estos son importantes para él (ella) y de
    manera inversa. En general no se siente integrado ni apoyado frente a desafíos escolares y/o cuando tiene una
    dificultad académica.</p>";
    } 
    else {
        $nombre_indicador = "Indefinido";
    }

    return $nombre_indicador;
}

function descripcion_indicador_profesores($nombre_indicador)
{
    if ($nombre_indicador == "Fortaleza Alta") {
        $nombre_indicador = "<p> El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante se siente motivado por sus profesores para aprender y que estos lo ayudan cuando tiene algún
    problema.</p>
<br>
<p> -El (la) estudiante mantiene en general buenas relaciones con sus profesores. Existe la impresión de que los
    profesores mantienen un interés por el estudiante como persona y como estudiante, ayudándolo en caso de
    dificultades, lo tratan con respeto y lo alientan a realizar nuevamente una tarea si se ha equivocado.</p>";
    } 
    elseif ($nombre_indicador == "Fortaleza Moderada") {
        $nombre_indicador = "<p>El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante se siente motivado por sus profesores para aprender y que estos lo ayudan cuando tiene algún
    problema.</p>
<br>
<p> -El (la) estudiante mantiene en general buenas relaciones con sus profesores. Existe la impresión de que los
    profesores mantienen un interés por el estudiante como persona y como estudiante, ayudándolo en caso de
    dificultades, lo tratan con respeto y lo alientan a realizar nuevamente una tarea si se ha equivocado.</p>";
    }
    elseif ($nombre_indicador == "Alerta Alta") {
        $nombre_indicador = "<p>-El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante no se siente motivado por sus profesores para aprender y que estos lo ayudan cuando tiene algún
    problema.</p>
<br>
<p> -El (la) estudiante no mantiene en general buenas relaciones con sus profesores. Existe la impresión de que los
    profesores no mantienen mayor interés por el estudiante o Io ayudan en caso de dificultades, no Io tratan con
    respeto y no lo alientan a realizar nuevamente una tarea si se ha equivocado.</p>
<br>
<p> -El (la) estudiante siente que en el colegio no se valora la participación de todos.</p>";
    }
    
    elseif ($nombre_indicador == "Alerta Moderada") {
        $nombre_indicador = "<p> El o la estudiante posee alertas en algunos de los siguientes aspectos:</p><br>
        <p> -El (la) estudiante no se siente motivado por sus profesores para aprender y que estos lo ayudan cuando tiene algún
    problema.</p>
<br>
<p> -El (la) estudiante no mantiene en general buenas relaciones con sus profesores. Existe la impresión de que los
    profesores no mantienen mayor interés por el estudiante o Io ayudan en caso de dificultades, no Io tratan con
    respeto y no lo alientan a realizar nuevamente una tarea si se ha equivocado.</p>
<br>
<p> -El (la) estudiante siente que en el colegio no se valora la participación de todos.</p>";
    }else {
        $nombre_indicador = "Indefinido";
    }

    return $nombre_indicador;
}

function getDuracionEncuesta($token) {
    try {
        $con = connectDB_demos();
        $query = $con->query("SELECT FLOOR((fecha_termino - fecha_inicio) / 60) AS minutos, ROUND(((MOD((fecha_termino - fecha_inicio) / 60,100) % 1) * 60) ) AS segundos FROM ce_encuesta_resultado WHERE ce_encuesta_resultado.ce_participantes_token_fk='$token'");
        $con = null;

        return $query; 
    } catch (Exception $ex) {
        echo 'Excepción Capturada: ' . $ex->getMessage();
    }
}

function datos_personales_reporte_individual($token,$id_doc,$tipo_user)
{

    try {
        if($tipo_user == 'estudiante'){
            $con = connectDB_demos();
        $query = $con->query("SELECT ce_participantes.ce_participantes_nombres AS nombres,
            ce_participantes.ce_participantes_apellidos AS apellidos,
            ce_participantes.ce_fk_nivel AS nivel,
            ce_curso.ce_curso_nombre as curso,
            ce_establecimiento.ce_establecimiento_nombre AS establecimiento
            FROM ce_participantes
            INNER JOIN ce_curso ON ce_participantes.ce_curso_id_ce_curso = ce_curso.id_ce_curso
            INNER JOIN ce_establecimiento ON ce_participantes.ce_establecimiento_id_ce_establecimiento = ce_establecimiento.id_ce_establecimiento
            WHERE ce_participantes.ce_participanes_token = '$token'");
        $con = null;

        return $query;

        }elseif($tipo_user == 'curso'){
            $con = connectDB_demos();
        $query = $con->query("SELECT ce_participantes.ce_participantes_nombres AS nombres,
            ce_participantes.ce_participantes_apellidos AS apellidos,
            ce_curso.ce_curso_nombre as curso,
            ce_establecimiento.ce_establecimiento_nombre AS establecimiento
            FROM ce_participantes
            INNER JOIN ce_curso ON ce_participantes.ce_curso_id_ce_curso = ce_curso.id_ce_curso
            INNER JOIN ce_establecimiento ON ce_participantes.ce_establecimiento_id_ce_establecimiento = ce_establecimiento.id_ce_establecimiento
            WHERE ce_participantes.ce_establecimiento_id_ce_establecimiento = '$token' AND ce_participantes.ce_docente_id_ce_docente = '$id_doc' ");
        $con = null;

        return $query;

        }
        
       
    } catch (Exception $ex) {

        echo 'Excepción Capturada: ' . $ex->getMessage();

    }

}

function brecha_alta_limitante_estudiante($fc,$ce,$nivel)
{
    try {
        if($fc != 0 || $ce != 0){
            if($fc <= 63){
                $fc_descripcion = " y Factores contextuales bajos (-)";
            }
            else if($fc >= 64){
                $fc_descripcion = " y Factores contextuales altos (+)";
            }
    
            if($nivel == 1){
                if($ce <= 97){
                    $ce_descripcion = " Bajo Compromiso Escolar (-)";
                }
                if($ce >= 98){
                    $ce_descripcion = " Alto Compromiso Escolar (+)";
                }
            }
            if($nivel == 2){
                if($ce <= 110){
                    $ce_descripcion = " Bajo Compromiso Escolar (-)";
                }
                if($ce >= 111){
                    $ce_descripcion = " Alto Compromiso Escolar (+)";
                }
            }
    
            return $ce_descripcion.$fc_descripcion;

        }else{
           
            $ce_descripcion = "Indeterminado";
            $fc_descripcion = " Indeterminado";
            return $ce_descripcion.$fc_descripcion;
        }
       
       
        } 
        
    catch (Exception $ex) {
        exit('Excepción Capturada: ' . $ex->getMessage());

    }

}

function tarjeta_de_presentacion($id_estable, $id_curso) {
    try {
        $no_respondidas = NoRespondidasEstDocente($id_estable, $id_curso)->fetch(PDO::FETCH_ASSOC);
        $respondidas = RespondidasEstDocente($id_estable, $id_curso)->fetch(PDO::FETCH_ASSOC);
        $suma_estudi = $no_respondidas["no_respondidas"] + $respondidas["respondidas"];

        $respon = $respondidas["respondidas"];
        $no_respon = $no_respondidas["no_respondidas"];

        if($suma_estudi != 0) {
            $avance_curso = ($respon * 100) / $suma_estudi;
        } else {
            $avance_curso = 0;
        }

        echo "<div class='col-lg-4 col-xs-6'><div class='small-box bg-aqua'>"
            . "<div class='inner'>"
            . "<h3>" . $suma_estudi . "</h3>"
            . "<p><h4>Número total de estudiantes</h4></p>"
            . "</div>"
            . "<div class='icon'>"
            . "<i class='fa fa-users'></i>"
            . "</div>"

            . "</div>"
            . "</div>"

            . "<div class='col-lg-4 col-xs-6'>"

            . "<div class='small-box bg-yellow'>"
            . "<div class='inner'>"
            . " <h3>" . $respon . "</h3>"

            . "<p><h4>Estudiantes Encuestados</h4></p>"
            . "</div>"
            . "<div class='icon'>"
            . "<i class='fa fa-user'></i>"
            . "</div>"

            . "</div>"
            . "</div>"

            . "<div class='col-lg-4 col-xs-6'>"

            . "<div class='small-box bg-red'>"
            . "<div class='inner'>"
            . "<h3>" . round($avance_curso, 1, PHP_ROUND_HALF_UP) . '%' . "</h3>"
            . "<p><h4>Total curso encuestado</h4></p>"
            . "</div>"
            . "<div class='icon'>"
            . "<i class='fa fa-pie-chart'></i>"
            . "</div>"

            . "</div>"
            . "</div>";

    } catch (Exception $ex) {
        echo 'Excepción Capturada' . $ex->getMessage();
    }
}

function tarjeta_de_presentacion_establecimiento($id_estable) {
    try {
        $no_respondidas = NoRespondidasEstablecimiento($id_estable)->fetch(PDO::FETCH_ASSOC);
        $respondidas = RespondidasEstablecimiento($id_estable)->fetch(PDO::FETCH_ASSOC);
        $suma_estudi = $no_respondidas["no_respondidas"] + $respondidas["respondidas"];

        $respon = $respondidas["respondidas"];
        $no_respon = $no_respondidas["no_respondidas"];

        if($suma_estudi != 0) {
            $avance_curso = ($respon * 100) / $suma_estudi;
        } else {
            $avance_curso = 0;
        }

        echo "<div class='col-lg-4 col-xs-6'><div class='small-box bg-aqua'>"
            . "<div class='inner'>"
            . "<h3>" . $suma_estudi . "</h3>"
            . "<p><h4>Número total de estudiantes</h4></p>"
            . "</div>"
            . "<div class='icon'>"
            . "<i class='fa fa-users'></i>"
            . "</div>"

            . "</div>"
            . "</div>"

            . "<div class='col-lg-4 col-xs-6'>"

            . "<div class='small-box bg-yellow'>"
            . "<div class='inner'>"
            . " <h3>" . $respon . "</h3>"

            . "<p><h4>Estudiantes Encuestados</h4></p>"
            . "</div>"
            . "<div class='icon'>"
            . "<i class='fa fa-user'></i>"
            . "</div>"

            . "</div>"
            . "</div>"

            . "<div class='col-lg-4 col-xs-6'>"

            . "<div class='small-box bg-red'>"
            . "<div class='inner'>"
            . "<h3>" . round($avance_curso, 1, PHP_ROUND_HALF_UP) . '%' . "</h3>"
            . "<p><h4>De los cursos inscritos</h4></p>"
            . "</div>"
            . "<div class='icon'>"
            . "<i class='fa fa-pie-chart'></i>"
            . "</div>"

            . "</div>"
            . "</div>";

    } catch (Exception $ex) {
        echo 'Excepción Capturada' . $ex->getMessage();
    }
}

function menu_modulos($id_rol)
{

    try {
        $con = connectDB_demos();
        $query = $con->query("SELECT menu,modulos_usuario FROM ce_roles where id_rol = '$id_rol'");
        //$resultado = $query->fetch(PDO::FETCH_ASSOC);

        return $query;
    } catch (Exception $ex) {
        echo 'Excepción Capturada: ' . $ex->getMessage();

    }

}

function curso_docentes($run_docente)
{

    try {
        $con = connectDB_demos();
        $query = $con->prepare("SELECT DISTINCT c.id_ce_curso AS id_curso ,c.ce_curso_nombre AS curso, a.ce_fk_nivel AS nivel
        FROM ce_estable_curso_docente a
        INNER JOIN ce_docente b ON  a.ce_fk_docente = b.id_ce_docente
        INNER JOIN ce_curso c ON a.ce_fk_curso = c.id_ce_curso
        WHERE b.ce_docente_run = :ce_docente_run");
        $query->execute([
            "ce_docente_run"=>$run_docente
        ]);
       
        echo ' <div class="float-left pb-3">';

        foreach($query AS $fila){
            $_SESSION["id_curso"] = $fila["id_curso"];
            $_SESSION["id_nivel"] = $fila["nivel"];
            header("Location: reportes/estudiante.php");          
     
        
        }
        echo  '</div>';
   
    } catch (Exception $ex) {
        echo 'Excepción Capturada: ' . $ex->getMessage();

    }

}

function factores_contextuales_estudiante_suma($token_estudiante)
{
    try {
        $con = connectDB_demos();
        $query = $con->query("SELECT (
                ce_p30+ce_p31+ce_p32+ce_p33+ce_p34+ce_p35+
                ce_p36+ce_p37+ce_p38+ce_p39+ce_p40+ce_p41+
                ce_p42+ce_p43+ce_p44+ce_p45+ce_p46+ce_p47
            ) AS suma_fc
             
    from ce_encuesta_resultado a where UPPER(a.ce_participantes_token_fk) = UPPER('$token_estudiante')");
        $con = null;
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        return $resultado["suma_fc"];
    } catch (Exception $ex) {
        echo 'Excepción Capturada: ' . $ex->getMessage();
    }


}

function factores_compromiso_escolar_suma($token_estudiante)
{
    try {
        $con = connectDB_demos();
        $query = $con->query(" SELECT (
                    ce_p1+ce_p2+ce_p3+ce_p4+ce_p5+ce_p6+ce_p7+ce_p8+ce_p9+ce_p10+ce_p11+
                    ce_p12+ce_p13+ce_p14+ce_p15+ce_p16+ce_p17+ce_p18+ce_p19+ce_p20+ce_p21+ce_p22+
                    ce_p23+ce_p24+ce_p25+ce_p26+ce_p27+ce_p28+ce_p29
                 ) AS sumaCE
                
                 
        from ce_encuesta_resultado a where UPPER(a.ce_participantes_token_fk) = UPPER('$token_estudiante')");
        $con = null;
        $resultado = $query->fetch(PDO::FETCH_ASSOC);
        return $resultado["sumaCE"];
    } catch (Exception $ex) {
        echo 'Excepción Capturada: ' . $e->getMessage();
    }

    echo $query;
}

function tipo_usuario(){
    try{
        $con = connectDB_demos();

        $query = $con->prepare("SELECT id_rol, display_nombre_rol FROM ce_roles WHERE id_rol IN (2)");
        $query->execute();        
        echo '<select name="tipo_usuario" id="tipo_usuario" class="form-control">';      
        foreach($query as $fila){
            echo '<option value="'.$fila["id_rol"].'">'.$fila["display_nombre_rol"].'</option>';
        }   
        
        echo "</select>";

    }catch(Exception $ex){

        exit("Excepción Capturada: ".$ex->getMessage());
    }
}

function usuario_administrador(){
    try{
        $con = connectDB_demos();
        $query = $con->prepare("SELECT id_rol, display_nombre_rol FROM ce_roles WHERE id_rol IN (2,4)");
        $query->execute();        
        echo '<select name="tipo_usuario" id="tipo_usuario" class="form-control">';      
        foreach($query as $fila){
            echo '<option value="'.$fila["id_rol"].'">'.$fila["display_nombre_rol"].'</option>';
        }   
        
        echo "</select>";

    }catch(Exception $ex){

        exit("Excepción Capturada: ".$ex->getMessage());
    }
}


function suma_total_dimension_compromiso_escolar($establecimiento,$curso){
    try{
        $con = connectDB_demos();
        $query = $con->query("SELECT
        SUM(a.ce_p1+a.ce_p5+a.ce_p7+a.ce_p8+a.ce_p12+a.ce_p15+a.ce_p19+a.ce_p22+a.ce_p27+a.ce_p29) AS suma_afectivo,
        SUM(a.ce_p3+a.ce_p4+a.ce_p9+a.ce_p11+a.ce_p16+a.ce_p23+a.ce_p28) AS suma_conductual,
        SUM(a.ce_p2+a.ce_p6+a.ce_p10+a.ce_p13+a.ce_p14+a.ce_p17+a.ce_p18+a.ce_p20+a.ce_p21+a.ce_p24+a.ce_p25+a.ce_p26) AS suma_cognitivo,
        SUM(a.ce_p1+a.ce_p5+a.ce_p7+a.ce_p8+a.ce_p12+a.ce_p15+a.ce_p19+a.ce_p22+a.ce_p27+a.ce_p29+
        a.ce_p3+a.ce_p4+a.ce_p9+a.ce_p11+a.ce_p16+a.ce_p23+a.ce_p28+
        a.ce_p2+a.ce_p6+a.ce_p10+a.ce_p13+a.ce_p14+a.ce_p17+a.ce_p18+a.ce_p20+a.ce_p21+a.ce_p24+a.ce_p25+a.ce_p26) AS compromiso_Escolar 
        FROM ce_encuesta_resultado a
        INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
        WHERE b.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND
        b.ce_curso_id_ce_curso = '$curso'");
        $con = NULL;
        return  $query;

    }catch(Exception $ex){

      exit("Excepción Capturada: ".$ex->getMessage());
}
}

function suma_total_dimension_compromiso_escolar_copia($establecimiento,$id_profesor){
    try{
        $con = connectDB_demos();
        $query_count = $con->query("SELECT COUNT(*) AS estud_cantidad
        FROM ce_encuesta_resultado a
        INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
        WHERE b.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND
        b.ce_docente_id_ce_docente = '$id_profesor'");

        $cantidad_estudiantes =  $query_count->fetch(PDO::FETCH_ASSOC);
        $cant_final = $cantidad_estudiantes["estud_cantidad"];

        $query = $con->query("SELECT b.id_ce_participantes,
        SUM(a.ce_p1+a.ce_p5+a.ce_p7+a.ce_p8+a.ce_p12+a.ce_p15+a.ce_p19+a.ce_p22+a.ce_p27+a.ce_p29) AS suma_afectivo,
        SUM(a.ce_p3+a.ce_p4+a.ce_p9+a.ce_p11+a.ce_p16+a.ce_p23+a.ce_p28) AS suma_conductual,
        SUM(a.ce_p2+a.ce_p6+a.ce_p10+a.ce_p13+a.ce_p14+a.ce_p17+a.ce_p18+a.ce_p20+a.ce_p21+a.ce_p24+a.ce_p25+a.ce_p26) AS suma_cognitivo,
        SUM(a.ce_p1+a.ce_p5+a.ce_p7+a.ce_p8+a.ce_p12+a.ce_p15+a.ce_p19+a.ce_p22+a.ce_p27+a.ce_p29+
        a.ce_p3+a.ce_p4+a.ce_p9+a.ce_p11+a.ce_p16+a.ce_p23+a.ce_p28+
        a.ce_p2+a.ce_p6+a.ce_p10+a.ce_p13+a.ce_p14+a.ce_p17+a.ce_p18+a.ce_p20+a.ce_p21+a.ce_p24+a.ce_p25+a.ce_p26) AS compromiso_Escolar 
        FROM ce_encuesta_resultado a
        INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
        WHERE b.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND
        b.ce_docente_id_ce_docente = '$id_profesor'  GROUP BY b.id_ce_participantes");
        $con = NULL;
        $afectivo = array();
        $conductual = array();
        $cognitivo = array();
       
        foreach($query AS $fila){
            $afectivo[] = $fila["suma_afectivo"];
            $conductual[] = $fila["suma_conductual"];
            $cognitivo[] = $fila["suma_cognitivo"];
                        
        } 

       $maximo_afectivo = 50 * $cant_final;
       $sum_total_afectivo = array_sum($afectivo);
       $brecha_afectivo = $maximo_afectivo-array_sum($afectivo);
       $complimiento_afectivo = ( $sum_total_afectivo * 100 ) / $maximo_afectivo;


       $maximo_conductual = 35 * $cant_final;
       $sum_total_conductual = array_sum($conductual);
       $brecha_conductual = $maximo_conductual-array_sum($conductual);
       $cumplimiento_conductual = ( $sum_total_conductual * 100 ) / $maximo_conductual;

       $maximo_cognitivo = 60 * $cant_final;
       $sum_total_cognitivo = array_sum($cognitivo);
       $brecha_cognitivo = $maximo_cognitivo-array_sum($cognitivo);
       $cumplimiento_cognitivo = ( $sum_total_cognitivo * 100 ) /  $maximo_cognitivo;

       $suma_para_radar_ce = mayordearray(array( $maximo_afectivo,$maximo_conductual, $maximo_cognitivo));

       $datos_compromiso_escolar = array("maximo_afectivo"=>$maximo_afectivo,"sum_total_afectivo"=>$sum_total_afectivo,"brecha_afectivo"=>$brecha_afectivo,"complimiento_afectivo"=>round($complimiento_afectivo,1,PHP_ROUND_HALF_UP),
       "maximo_conductual"=>$maximo_conductual,"sum_total_conductual"=>$sum_total_conductual,"brecha_conductual"=>$brecha_conductual,"cumplimiento_conductual"=>round($cumplimiento_conductual,1,PHP_ROUND_HALF_UP),
       "maximo_cognitivo"=>$maximo_cognitivo,"sum_total_cognitivo"=>$sum_total_cognitivo,"brecha_cognitivo"=>$brecha_cognitivo,"cumplimiento_cognitivo"=>round($cumplimiento_cognitivo,1,PHP_ROUND_HALF_UP),"demo"=>$suma_para_radar_ce);

       return $datos_compromiso_escolar;

    }catch(Exception $ex){

      exit("Excepción Capturada: ".$ex->getMessage());
}
}

function suma_total_dimension_factores_contextuales_copia($establecimiento,$profesor){
    try{
        $con = connectDB_demos();
        $query_count = $con->query("SELECT COUNT(*) AS estud_cantidad
        FROM ce_encuesta_resultado a
        INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
        WHERE b.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND
        b.ce_docente_id_ce_docente = '$profesor' AND b.ce_estado_encuesta = 1");

        $cantidad_estudiantes =  $query_count->fetch(PDO::FETCH_ASSOC);
        $cant_final = $cantidad_estudiantes["estud_cantidad"];

        $query = $con->query("SELECT b.id_ce_participantes,
        SUM(a.ce_p30+a.ce_p31+a.ce_p32) AS suma_familiar,
        SUM(a.ce_p33+a.ce_p34+a.ce_p35+a.ce_p36+a.ce_p37+a.ce_p38+a.ce_p39+a.ce_p40) AS suma_profesores,
        SUM(a.ce_p41+a.ce_p42+a.ce_p43+a.ce_p44+a.ce_p45+a.ce_p46+a.ce_p47) AS suma_pares,
        SUM(a.ce_p30+a.ce_p31+a.ce_p32+
        a.ce_p33+a.ce_p34+a.ce_p35+a.ce_p36+a.ce_p37+a.ce_p38+a.ce_p39+a.ce_p40+
        a.ce_p41+a.ce_p42+a.ce_p43+a.ce_p44+a.ce_p45+a.ce_p46+a.ce_p47
        ) AS Factores_Contextuales
        FROM ce_encuesta_resultado a
        INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
        WHERE b.ce_establecimiento_id_ce_establecimiento = '$establecimiento' AND
        b.ce_docente_id_ce_docente = '$profesor' GROUP BY b.id_ce_participantes");
        $con = NULL;
        $familiar = array();
        $profesores = array();
        $pares = array();
        foreach($query AS $fila){
            $familiar[] = $fila["suma_familiar"];
            $profesores[] = $fila["suma_profesores"];
            $pares[] = $fila["suma_pares"];
        }

       $maximo_familiar = 15 * $cant_final;
      // $maximo_familiar = mayordearray($familiar) * $cant_final;
       $sum_total_familiar = array_sum($familiar);
       $brecha_familiar = $maximo_familiar-array_sum($familiar);
       $cumplimiento_familiar = ( $sum_total_familiar * 100 )/ $maximo_familiar;

       $maximo_profesores = 40 * $cant_final;
      // $maximo_profesores = mayordearray($profesores) * $cant_final;
       $sum_total_profesores = array_sum($profesores);
       $brecha_profesores = $maximo_profesores-array_sum($profesores);
       $cumplimiento_profesores = ( $sum_total_profesores * 100 ) /  $maximo_profesores;

      //$maximo_pares = mayordearray($pares) * $cant_final;

       $maximo_pares = 35 * $cant_final;
       $sum_total_pares = array_sum($pares);
       $brecha_pares = $maximo_pares-array_sum($pares);
       $cumplimiento_pares = (  $sum_total_pares * 100 )/  $maximo_pares;

       $datos_factores_contextuales = array("maximo_familiar"=>$maximo_familiar,"sum_total_familiar"=>$sum_total_familiar,"brecha_familiar"=>$brecha_familiar, "cumplimiento_familiar"=> round($cumplimiento_familiar,1,PHP_ROUND_HALF_UP),
       "maximo_profesores"=>$maximo_profesores,"sum_total_profesores"=>$sum_total_profesores,"brecha_profesores"=>$brecha_profesores,"cumplimiento_profesores" => round($cumplimiento_profesores,1,PHP_ROUND_HALF_UP),
       "maximo_pares"=>$maximo_pares,"sum_total_pares"=>$sum_total_pares,"brecha_pares"=>$brecha_pares,"cumplimiento_pares"=>round($cumplimiento_pares,1,PHP_ROUND_HALF_UP));

       return $datos_factores_contextuales;
        
    }catch(Exception $ex){

     exit("Excepción Capturada: ".$ex->getMessage());
   }
}

function sostenedor_colegio($id_estableblecimiento){
    try{
        
        $con = connectDB_demos();
        $query_count = $con->prepare("SELECT COUNT(a.sostenedor_id) AS id
        FROM ce_establecimiento_sostenedor a
        WHERE a.establecimiento_id = :establecimiento_id");
        $query_count->execute(["establecimiento_id"=>$id_estableblecimiento]);
        $resultado = $query_count->fetch(PDO::FETCH_ASSOC);
        $con = NULL;

        return  $resultado["id"];

    }catch(Exception $ex){

        exit("Excepción Capturada: ".$ex->getMessage());
    }
}

function docentes_colegio($id_estableblecimiento){
    try{
        
        $con = connectDB_demos();
        $query_count = $con->prepare("SELECT COUNT(a.ce_fk_docente) AS id_docente
        FROM ce_estable_curso_docente a
        WHERE a.ce_fk_establecimiento = :establecimiento_id");
        $query_count->execute(["establecimiento_id"=>$id_estableblecimiento]);
        $resultado = $query_count->fetch(PDO::FETCH_ASSOC);
        $con = NULL;

        return  $resultado["id_docente"];

    }catch(Exception $ex){

        exit("Excepción Capturada: ".$ex->getMessage());
    }
}


function etimologia_textos($id_pais){
    try{
      
        $con = connectDB_demos();
        $query = $con->prepare("SELECT text_1_ini,text_1_intro FROM ce_etimologia WHERE id_pais=:id_pais");
        $query->execute([
            "id_pais"=>$id_pais
        ]);      
        $con = NULL;
       return $resultado = $query->fetch(PDO::FETCH_ASSOC);       
     
        
    }catch(Exception $ex){
        
        exit("Excepción Capturada: ".$ex->getMessage());
    }
}



/// PARTE ADMINISTRACIÓN DE UN ESTABLECIMIENTO

function insertar_usuario($user, $pass){
    try{
        $contrasena = password_hash($pass, PASSWORD_BCRYPT);
        $con = connectDB_demos();
        $query_count = $con->query("INSERT INTO ce_usuarios(nombre_usu,contrasena_usu)
        VALUES('$user','$contrasena')");

    }catch(Exception $ex){

        exit("Excepción Capturada: ".$ex->getMessage());
    }
}

function suma_compromiso_escolar_factores_contextuales($establecimiento,$curso){
    try{
        $con = connectDB_demos();
        $query = $con->prepare("SELECT
       COUNT(b.id_ce_participantes) as participantes, b.ce_fk_nivel AS nivel,

       SUM(a.ce_p1 + a.ce_p2 + a.ce_p3 + a.ce_p4 + a.ce_p5 + a.ce_p6 + a.ce_p7 + a.ce_p8 + a.ce_p9 + a.ce_p10 +
           a.ce_p11 + a.ce_p12 + a.ce_p13 +  a.ce_p14 + a.ce_p15 + a.ce_p16 + a.ce_p17 + a.ce_p18 + a.ce_p19 + a.ce_p20 +a.ce_p21 + a.ce_p22 + a.ce_p23 + a.ce_p24 + a.ce_p25 + a.ce_p26 + a.ce_p27 + a.ce_p28 + a.ce_p29) CE,

       SUM(a.ce_p30 + a.ce_p31 + a.ce_p32 + a.ce_p33 + a.ce_p34 + a.ce_p35 + a.ce_p36 + a.ce_p37 +
           a.ce_p38 + a.ce_p39 + a.ce_p40 + a.ce_p41 + a.ce_p42 + a.ce_p43 + a.ce_p44 + a.ce_p45 +
           a.ce_p46 + a.ce_p47) FC

          FROM ce_encuesta_resultado a
          INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token		 
		  WHERE b.ce_estado_encuesta = 1 AND b.ce_establecimiento_id_ce_establecimiento = :ce_establecimiento_id_ce_establecimiento AND b.ce_curso_id_ce_curso = :ce_curso_id_ce_curso
		  GROUP BY b.id_ce_participantes  ");
        $query->execute([
            "ce_establecimiento_id_ce_establecimiento"=>$establecimiento,
            "ce_curso_id_ce_curso"=>$curso
        ]);
        $con = NULL;
        return  $query;

    }catch(Exception $ex){

      exit("Excepción Capturada: ".$ex->getMessage());
}
}

function ce_excepciones($excepcion){

    try{
        $con = connectDB_demos();
        $query = $con->prepare("INSERT INTO ce_excepciones(nom_excep) VALUES(:nom_excep)");
        $query->execute([":nom_excep"=>$excepcion]);
        $con = NULL;

    }catch(Exception $ex){
        exit("Excepción Capturada".$ex->getMessage());
    }

}