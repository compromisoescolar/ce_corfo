<?php

    require_once "dist/conf/require_conf.php";

    session_start();

    if (!isset($_SESSION['user'])) {
        header("location: index2.php");
        exit();
    }

    $conn = connectDB_demos();

    $establecimientos_basica = array();
    $establecimientos_media  = array();

    $usuario_stmt = $conn->prepare(
        "SELECT * FROM ce_usuarios where nombre_usu = :username"
    );
    $usuario_stmt->execute(array(
        'username' => $_SESSION['user']
    ));

    $usuario = $usuario_stmt->fetch();

    $sostenedor_stmt = $conn->prepare(
        "select * from ce_sostenedor as s inner join ce_usuarios as u on s.run_soste = u.nombre_usu where id_usu = :id"//"SELECT * from ce_sostenedor where usuario_id = :id"
    );

    /*
        Error identificado, no se mostraba la grafica de dispercion debido a que se estaba seleccionando a los sostenedores que tuvieran cierto 'usuario_id', pero este se encuentra nulo en la base de datos

        Al momento de generar un nuevo sostenedor, hay que agregar el id de cada usuario, pero por el momento 
        se utiliza esta sentencia 

    */

    $sostenedor_stmt->execute(array(
        'id' => $usuario['id_usu']
    ));
    $sostenedor = $sostenedor_stmt->fetch();

    $establecimiento_stmt = $conn->prepare(
        "SELECT ce_establecimiento.* 
        FROM ce_establecimiento
        join ce_establecimiento_sostenedor ces 
        on ce_establecimiento.id_ce_establecimiento = ces.establecimiento_id
        WHERE ces.sostenedor_id = :sostenedor_id"
    );
    $establecimiento_stmt->execute(array(
        'sostenedor_id' => $sostenedor['id_soste']
    ));

while ($establecimiento_result = $establecimiento_stmt->fetch()) {
    $establecimiento         = array();
    $participantes_ce_basica = 0;
    $total_ce_basica         = 0;
    $resultado_ce_basica     = 0;
    
    $participantes_ce_media = 0;
    $total_ce_media         = 0;
    $resultado_ce_media     = 0;
    
    $participantes_fc_basica = 0;
    $total_fc_basica         = 0;
    $resultado_fc_basica     = 0;
    
    $participantes_fc_media = 0;
    $total_fc_media         = 0;
    $resultado_fc_media     = 0;
    
    // Compromiso Escolar
    
    $stmt = $conn->prepare(
        "SELECT
        COUNT(b.id_ce_participantes) as participantes,
        SUM(a.ce_p1 + a.ce_p2 + a.ce_p3 + a.ce_p4 + a.ce_p5 + a.ce_p6 + a.ce_p7 + a.ce_p8 + a.ce_p9 + a.ce_p10 +
           a.ce_p11 + a.ce_p12 + a.ce_p13 +  a.ce_p14 + a.ce_p15 + a.ce_p16 + a.ce_p17 + a.ce_p18 + a.ce_p19 + a.ce_p20 + 
           a.ce_p21 + a.ce_p22 + a.ce_p23 + a.ce_p24 + a.ce_p25 + a.ce_p26 + a.ce_p27 + a.ce_p28 + a.ce_p29) AS total
        FROM ce_encuesta_resultado a
        INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
        join ce_establecimiento on ce_establecimiento.id_ce_establecimiento = b.ce_establecimiento_id_ce_establecimiento
        INNER JOIN ce_curso c ON b.ce_curso_id_ce_curso = c.id_ce_curso
        WHERE b.ce_fk_nivel = 1 AND ce_establecimiento.id_ce_establecimiento = :id"
    );
    
    $stmt->execute(array(
        'id' => $establecimiento_result['id_ce_establecimiento']
    ));
    
    if ($result = $stmt->fetch()) {
        $participantes_ce_basica = $result['participantes'];
        $total_ce_basica         = $result['total'];
    }
    
    if ($participantes_ce_basica != '0') {
        $resultado_ce_basica = floor($total_ce_basica / $participantes_ce_basica);
    }
    
    $stmt = $conn->prepare(
        "SELECT
        COUNT(b.id_ce_participantes) as participantes,
        SUM(a.ce_p1 + a.ce_p2 + a.ce_p3 + a.ce_p4 + a.ce_p5 + a.ce_p6 + a.ce_p7 + a.ce_p8 + a.ce_p9 + a.ce_p10 +
           a.ce_p11 + a.ce_p12 + a.ce_p13 +  a.ce_p14 + a.ce_p15 + a.ce_p16 + a.ce_p17 + a.ce_p18 + a.ce_p19 + a.ce_p20 + 
           a.ce_p21 + a.ce_p22 + a.ce_p23 + a.ce_p24 + a.ce_p25 + a.ce_p26 + a.ce_p27 + a.ce_p28 + a.ce_p29) AS total
        FROM ce_encuesta_resultado a
        INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
        join ce_establecimiento on ce_establecimiento.id_ce_establecimiento = b.ce_establecimiento_id_ce_establecimiento
        INNER JOIN ce_curso c ON b.ce_curso_id_ce_curso = c.id_ce_curso
        WHERE b.ce_fk_nivel = 2 AND ce_establecimiento.id_ce_establecimiento = :id"
    );
    
    $stmt->execute(array(
        'id' => $establecimiento_result['id_ce_establecimiento']
    ));
    
    if ($result = $stmt->fetch()) {
        $participantes_ce_media = $result['participantes'];
        $total_ce_media         = $result['total'];
    }
    
    if ($participantes_ce_media != '0') {
        $resultado_ce_media = floor($total_ce_media / $participantes_ce_media);
    }
    
    
    // Factores Contextuales
    
    $stmt = $conn->prepare("SELECT COUNT(b.id_ce_participantes) as participantes,
       SUM(a.ce_p30 + a.ce_p31 + a.ce_p32 + a.ce_p33 + a.ce_p34 + a.ce_p35 + a.ce_p36 + a.ce_p37 +
           a.ce_p38 + a.ce_p39 + a.ce_p40 + a.ce_p41 + a.ce_p42 + a.ce_p43 + a.ce_p44 + a.ce_p45 +
           a.ce_p46 + a.ce_p47)     AS total
FROM ce_encuesta_resultado a
       INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
       join ce_establecimiento on ce_establecimiento.id_ce_establecimiento = b.ce_establecimiento_id_ce_establecimiento
       INNER JOIN ce_curso c ON b.ce_curso_id_ce_curso = c.id_ce_curso
WHERE b.ce_fk_nivel = 1 AND ce_establecimiento.id_ce_establecimiento = :id");
    
    $stmt->execute(array(
        'id' => $establecimiento_result['id_ce_establecimiento']
    ));
    
    if ($result = $stmt->fetch()) {
        $participantes_fc_basica = $result['participantes'];
        $total_fc_basica         = $result['total'];
    }
    
    if ($participantes_fc_basica != 0) {
        $resultado_fc_basica = floor($total_fc_basica / $participantes_fc_basica);
    }
    
/////////////////////////////////////////////////////////////////////////////////////
    $stmt = $conn->prepare("SELECT COUNT(b.id_ce_participantes) as participantes,
       SUM(a.ce_p30 + a.ce_p31 + a.ce_p32 + a.ce_p33 + a.ce_p34 + a.ce_p35 + a.ce_p36 + a.ce_p37 +
           a.ce_p38 + a.ce_p39 + a.ce_p40 + a.ce_p41 + a.ce_p42 + a.ce_p43 + a.ce_p44 + a.ce_p45 +
           a.ce_p46 + a.ce_p47)     AS total
FROM ce_encuesta_resultado as a
       INNER JOIN ce_participantes as b ON a.ce_participantes_token_fk = b.ce_participanes_token
       join ce_establecimiento on ce_establecimiento.id_ce_establecimiento = b.ce_establecimiento_id_ce_establecimiento
       INNER JOIN ce_curso c ON b.ce_curso_id_ce_curso = c.id_ce_curso
WHERE b.ce_fk_nivel = 2 AND ce_establecimiento.id_ce_establecimiento = :id");
    
    $stmt->execute(array(
        'id' => $establecimiento_result['id_ce_establecimiento']
    ));
    
    // select * from ce_establecimiento_sostenedor where sostenedor_id=49;

    if ($result = $stmt->fetch()) {
        $participantes_fc_media = $result['participantes'];
        $total_fc_media         = $result['total'];
    }
    
    if ($participantes_fc_media != 0) {
        $resultado_fc_media = floor($total_fc_media / $participantes_fc_media);
    }

 /////////////////////////////////////////////////////////////////////////////////


    
    $establecimiento_basica['name']          = $establecimiento_result['ce_establecimiento_nombre'] . ' Basica';
    $establecimiento_basica['color']         = 'rgb(95, 55, 188)';
    $establecimiento_basica['x']             = $resultado_fc_basica;
    $establecimiento_basica['y']             = $resultado_ce_basica;
    $establecimiento_basica['participantes'] = $participantes_fc_basica;
    
    $establecimiento_media['name']          = $establecimiento_result['ce_establecimiento_nombre'] . ' Media';
    $establecimiento_media['color']         = 'rgb(95, 55, 188)';
    $establecimiento_media['x']             = $resultado_fc_media;
    $establecimiento_media['y']             = $resultado_ce_media;
    $establecimiento_media['participantes'] = $participantes_fc_media;
    
    
    array_push($establecimientos_basica, $establecimiento_basica);
    array_push($establecimientos_media, $establecimiento_media);
}

$totalParticipantesBasica = array_reduce($establecimientos_basica, function($accum, $item)
{
    return $accum + $item['participantes'];
}, 0);

$totalParticipantesMedia = array_reduce($establecimientos_media, function($accum, $item)
{
    return $accum + $item['participantes'];
}, 0);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reportes Compromiso Escolar</title>
    <!-- Tell the browser to be responsive to screen width -->
    <?php require "dist/css/css.php"; ?>
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <style type="text/css">
        a {
            color: #ffffff;
        }

        .main-sidebar {
            background: #f4af1f;
        }       

        .header {
            background: #f27611;
        }

        .sidebar {
            width: 220px; 
            float: right;
        }

        .user-panel {
            background: #f27611;
        }

        .content-header {
            background: #f4af1f; 
        }


        .panel {
            width:  100%;
            margin: 0;
            padding: 0;
        }

        .panel-body {
            width:  100%;
            padding: 15px;
            margin-top: 0;

        }

        .cursor_dimensiones{
            text-decoration: none;
        }

        div.label-alerta-media.hvr.hvr-grow {
            text-decoration: none;
        }

        .sidebar-menu > li > .treeview-menu {
            margin: 0 1px;
            background: #f27611;
        }

        .tit-menu {
            float: left; 
            text-align: left; 
            color: white;
        }

        .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side { 
            background-color: #f4af1f;
        }

        .nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
            font-size: 18px;
            font-weight: 400;
            color: black;
            background-color: #e6e6e6;
            border-bottom-color: #e6e6e68c;
        }

        .nav-pills > li > a { 
            font-size: 18px;
            border-radius: 0;
            border-top: 3px solid transparent;
            color: white;
            background-color: #cfd0d1;
        }

        /* pestañas superiores */
        .nav-pills > li.pest_li.active > a { 
            font-size: 18px;
            border-radius: 0;
            border-top: 3px solid transparent;
            color: white;
            background-color: #cfd0d1; /* gris claro */
        }

        .nav-pills > li.pest_li > a { 
            font-size: 18px;
            border-radius: 0;
            border-top: 0.5px solid white;
            border-left: 0.5px solid white;
            border-right: 0.5px solid white;
            color: white;
            background-color: #f4af1f; /* zapallo */
        }

        .nav-pills > li.pest_li:focus > a { 
            background-color: #f4af1f7d; /* zapallo */
        }

        .id_recur_edu {
            margin-top: 175px;
            margin-left: 20px;
            padding-top: 1px;
            padding-left: 10px;
            width: 195px;
            height: 300px;
            background-image: url("../assets/img/recursos-educativos.png");
            background-repeat: no-repeat;
            background-size:100% auto;
            text-shadow: 0.5px 0.5px 0.5px black;
            color: white; 
            border-radius: 10px;
        }

        .id_recur_edu:hover {
            cursor:pointer; 
            cursor: hand;
            opacity: 0.9;
        }

        .id_recur_edu:active { 
            cursor:pointer; 
            cursor: hand;
            opacity: 0.7;
        }

        #img_btn_salir:hover {
            cursor:pointer; 
            cursor: hand;
            opacity: 0.9;
        }

        #img_btn_salir:active { 
            cursor:pointer; 
            cursor: hand;
            opacity: 0.7;
        }


        a:hover, a:active, a:focus {
            outline: none;
            text-decoration: none;
            color: white;
        }

        li.active {
            border: 2px #418BCC;
        }

        .btn_cuadrado:hover { 
            cursor: pointer;
            opacity: 0.7;
        }

        .btn_cuadrado:active { 
            cursor: pointer;
            opacity: 0.4;
        }

        .btn_side:hover {
            cursor:pointer; 
            cursor: hand;
            opacity: 0.9;
        }

        .btn_side:active { 
            cursor:pointer; 
            cursor: hand;
            opacity: 0.7;
        }
    </style>
</head>
<body>

    <script type="text/javascript">
        console.log("<?php echo $establecimientos_media[0];     ?>");
    </script>
    <div id="linea-superior" style="background: #40c2d4; padding-left: 100px; "> 
        <table width="100%" height="100%" >
            <tr width="100%"> 
                <td align="left" width="50%">
                    <div style="display: flex; align-items: baseline; background: #40c2d4;">
                        <img style="height: 78px; width: 750px;"  src="../assets/img/C2_Resultados.png">
                        <div style="margin-top: 30px; margin-left: 195px; font-size: 20px; position: absolute; color: white;">
                            Módulo de Resultados
                        </div>
                    </div>
                </td> 
                <td align="right" width="50%">
                    <table width="100%">
                        <tr width="100%">
                            <td align="right" width="50%">
                                <h3 style="color: white;"><?php echo $_SESSION["profesor_nombres"];?></h3>
                            </td>
                            <td align="right" width="50%">
                                <button id="btn_cerrar_session" style="text-decoration: none; background: transparent; width: 100%; height: 100%;  background-repeat: no-repeat; border-radius: 35px; border: none; cursor:pointer; overflow: hidden; outline:none; background-position: center;">
                                    <img id="img_btn_salir" style="width: 128px; height: 48px; " src="../assets/img/salir-2.png">
                                </button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="contenido row justify-content-center align-items-center">
        <div class="card_c" style="padding-right: 200px; padding-left: 200px; padding-top: 10px; padding-bottom: 10px; background: #e6e6e6;">
            <div class="wrapper card_c" id="subir_head" style="border: 1.5px solid #A5A1A0; border-radius: 10px;">
                <aside class="main-sidebar">
                    <div style="position: absolute; top: 0px; left: 16px; color: white;">
                        <h4>
                            <?php echo $_SESSION["tipo_nombre"]; ?>        
                        </h4>
                    </div>
                   

                    <section class="sidebar" style="width: 220px; float: right;">
                        <!-- Sidebar user panel -->
                        <div class="user-panel" style="background: #f27611;">
                            <div class="pull-left image">
                                <br>
                            </div>
                            <div class="tit-menu">
                                <p>
                                    Menú de Navegación
                                </p>
                            </div>
                        </div>
                        <!-- sidebar menu: : style can be found in sidebar.less -->
                        <ul  class="sidebar-menu" data-widget="tree" style="background: #f27611;">
                            <li  class="active treeview" style="background: #f27611;">
                                <a class="menu-flag" id="menu_flag" href="#">
                                    <i class="fa fa-user-circle-o"></i>
                                    <span> <?php echo $_SESSION["tipo_nombre"]; ?> </span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li style='padding: 5px;' class="active">
                                        <a id="" href="#">
                                            <i class='fa fa-pie-chart' aria-hidden='true'></i>
                                             Reportes
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </section>
                    <div align="left" class="id_recur_edu">    
                        
                    </div>
                </aside>
                <div class="content-wrapper" >
                    <section class="content-header" style="background: #f4af1f;">
                        <a style="width: 60px; height: 60px; border: 0; color: white; background: #f4af1f; padding: 0; padding-right: 10px; font-size: 30px;" class="openbtn btn_side">☰</a> 
                        <h1 class="text-center">
                        
                        </h1>
                    </section>
                    
                    <div class="panel-body">
                        <?php 
                            if ($establecimientos_basica[0]["participantes"] != 0 || $establecimientos_media[0]["participantes"] != 0) {
                        ?>
                                <?php  
                                if ($establecimientos_basica[0]["participantes"] != 0) {
                                ?>
                                <div class="panel panel-info mt-4" style="padding-top: 0;">
                                    <div style="width: 100%; height: 60px; background: #d9edf7; margin-top: 0; padding-top: 0; border: 1px;">
                                        <table width="100%" style="height: 100%;">
                                            <tbody>
                                                <tr valign="center">
                                                    <td style="padding-left: 10px; font-weight: bold;" align="left" width="100%">
                                                        <h4><strong>Gráfico de Dispersión</strong></h4>
                                                    </td>
                                                </tr>      
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="panel-body">
                                        <div>
                                            <span>
                                                <p style="font-size: 20px; text-align: center">Reporte Sostenedor <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="definicion_cuadrantes()"></i></p>
                                            </span>
                                        </div>
                                        <div class="table-responsive mt-4" style="margin-top: 0; padding-top: 0;">
                                            <div id="dispersion-sostenedor-basica" style="width: 100%; padding: 0; height: 500px; margin: 0 auto">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php
                                    }  
                                    if ($establecimientos_media[0]["participantes"] != 0) {
                                ?>
                                <div class="panel panel-info mt-4" style="padding-top: 0;">
                                    <div style="width: 100%; height: 60px; background: #d9edf7; margin-top: 0; padding-top: 0; border: 1px;">
                                        <table width="100%" style="height: 100%;">
                                            <tbody>
                                                <tr valign="center">
                                                    <td style="padding-left: 10px; font-weight: bold;" align="left" width="100%">
                                                        <h4>
                                                            <strong>Grafica de Dispersión</strong>
                                                        </h4>
                                                    </td>
                                                </tr>      
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive mt-4" style="margin-top: 0; padding-top: 0;">
                                            <div id="ver_dispersion">
                                                <div id="dispersion-sostenedor-media" style="min-width: 310px; height: 500px; margin: 0 auto">
                                                            
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>
                            <?php
                                } else {
                                    echo "<div class='alert alert-danger text-center'>No hay Estudiantes encuestados en el Establecimiento</div>";
                                }
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="page-footer pt-4" style="margin-bottom: 0px; background: white; padding-top: 40px; padding-bottom: 0px; bottom: 0; height: 200px;">
            <div class="container" style="margin-bottom: 20px;">
               <table cellpadding="10">
                    <tr>
                        <td align="left" valign="center">
                            <div style="display: flex; align-items: baseline;">
                                <img style="margin-right: 5px;" width="44" src="../assets/img/mineduc.png">
                                <img style="margin-right: 5px;" width="120" src="../assets/img/fondef.png">
                                <img style="margin-right: 5px;" width="110" src="../assets/img/corfo.png">
                                <img style="margin-right: 5px; padding-top: 5px;" width="60" src="../assets/img/ufro.png">
                                <img style="margin-right: 5px; padding-bottom: 4px;" width="100" src="../assets/img/autonoma.png">
                                <img style="margin-right: 5px; padding-bottom: 4px;" width="160" src="../assets/img/fund_telefonica.png">
                            </div>
                        </td>
                        <td width="33%" align="center" valign="center" >
                            <p style="font-size: small; text-align: justify; font: condensed 100% sans-serif; color: #212529; overflow: hidden; white-space: nowrap;"> 
                                Estas encuestas forman parte del Proyecto FONDEF <br> ID14I10078-ID14I20078 Medición del compromiso del niño, niña <br>  y adolescente con sus estudios para la promoción de trayectorias educativas exitosas.
                            </p>
                        </td>
                    </tr>
               </table>
            </div>
        </footer>
    <div id="id_ingre_cod" class="modal fade">
            <div class="card" id="form-encuesta" style="">
                <div class="card-body">
                    <div class="modal-header"  style="text-align: center; line-height: 7px; border: 0; margin: 0; padding: 0;">
                        <h5 style="" class="modal-title">Formulario de acceso</h5>
                        <button id="btn_cerrar_modal" type="button" class="close" >&times;</button>
                    </div>
                    <div style="text-align: center; line-height: 7px;">
                        <hr style="background: #fc455c;">
                    </div>
                    <form id="form_admin">
                        <br>
                        <div style="text-align: center; margin-bottom: 4px;">
                            <i class="fa fa-user" style="color: #fc455c;" aria-hidden="true"></i> &nbsp; Administracion
                        </div>
                        <br>
                        <div class="form-group has-feedback">
                            <?php echo usuario_administrador(); ?>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" name="usuario" id="usuario" class="form-control" placeholder="usuario" required />
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" id="contrasena" name="contrasena" class="form-control"  autocomplete="password" placeholder="contraseña" required />
                        </div>
                        <button style="border-radius: 2px; background-color: #fc455c; font-family: ‘Source Sans Pro’, sans-serif; font-size: 12px; font-weight: 900; min-width:120px; height:30px; width: 100%; margin-top: 15px; border-radius: 5px; color: white; box-shadow: rgba(0, 0, 0, 0.22) 1px 1px 1px 1px; border: 1.5px solid #fc455c;" name="login-button" id="ingresar_admin" type="submit" class="icon-submit btn-limon-validar">
                            <span id="inicia_rep">
                                Ingresar
                            </span>
                            <div id="spinner"></div>
                        </button>  
                    </form>
                </div>
            </div>
        </div>
<?php require "dist/js/js.php"; ?>

<script>
 sesion();
    var url_base = window.location;
    var flag_menu = 1;
    var flag_boton = 1;
    var flag_sbar = 1;
    var fewSeconds = 5;
    var des_flag = false;
    const defaultOptions = {
        title: {
            text: null,
        },

        chart: {
            type: 'scatter'
        },
        subtitle: {
            text: ''
        }, credits: {
            enabled: false
        },

        xAxis: {
            title: {
                enabled: true,
                text: 'Factores Contextuales',
                align: 'low',
                style: {
                    fontSize: '14px'
                }
            },
            startOnTick: true,
            endOnTick: true,
            showLastLabel: true,
            plotLines: [{
                value: 45,
                color: 'red',
                width: 1,
                zIndex: 4,
                dashStyle: 'shortdash'
            }],
            min: 1,
            max: 95
        },

        yAxis: {
            title: {
                text: 'Compromiso Escolar',
                align: 'low',
                style: {
                    fontSize: '14px',
                    fontFamily: 'Arial'
                }
            },
            plotLines: [{
                value: 90,
                color: 'red',
                width: 1,
                zIndex: 4,
                dashStyle: 'shortdash'
            }],
            min: 29,
            max: 145
        },

        legend: {
            enabled: false,
        },

        plotOptions: {
            scatter: {
                marker: {
                    radius: 15,
                    states: {
                        hover: {
                            enabled: true,
                            lineColor: 'rgb(100,100,100)'
                        }
                    }
                },
                states: {
                    hover: {
                        marker: {
                            enabled: false
                        }
                    }
                },
                tooltip: {
                    headerFormat: '',
                    pointFormat: '<b>{point.name}</b> <br><div>{point.x} FC, {point.y} CE</div>'
                }
            }
        }
    }

    const labels = function (chart) {
        chart.renderer.label('Alto compromiso escolar y factores contextuales', window.value - 200, 40)
            .attr({
                fill: 'rgb(206, 225, 255)',
                padding: 1,
                zIndex: 1
            })
            .css({
                color: 'black',
                width: '200px',
                border: '10px'
            })
            .add();
        chart.renderer.label('Alto compromiso escolar y bajos factores contextuales', 70, 40)
            .attr({
                fill: 'rgb(206, 225, 255)',
                padding: 1,
                r: 1,
                zIndex: 1
            })
            .css({
                color: 'black',
                width: '200px'
            })
            .add();
        chart.renderer.label('Bajo compromiso escolar y bajos factores contextuales', 70, 390)
            .attr({
                fill: 'rgb(206, 225, 255)',
                padding: 1,
                r: 1,
                zIndex: 1
            })
            .css({
                color: 'black',
                width: '200px'
            })
            .add();
        chart.renderer.label('Bajo compromiso escolar y altos Factores contextuales', window.value - 200, 390)
            .attr({
                fill: 'rgb(206, 225, 255)',
                padding: 1,
                r: 1,
                zIndex: 1
            })
            .css({
                color: 'black',
                width: '200px'
            })
            .add();
    }

    function dispersionSostenedorBasicaInit() {
        window.value = document.getElementById('dispersion-sostenedor-basica').offsetWidth;
        const options = {
            ...defaultOptions,
            series: [{
                data: <?php echo json_encode($establecimientos_basica) ?>
            }]
        }

        Highcharts.chart('dispersion-sostenedor-basica', options, labels);
    }

    function dispersionSostenedorMediaInit() {
        window.value = document.getElementById('dispersion-sostenedor-media').offsetWidth;
        const options = {
            ...defaultOptions,
            title: {
                text: 'Reporte Sostenedor Media (<?php echo $totalParticipantesMedia?>)'
            },
            series: [{
                data: <?php echo json_encode($establecimientos_media) ?>
            }]
        }

        Highcharts.chart('dispersion-sostenedor-media', options, labels);
    }

    document.addEventListener('DOMContentLoaded', () => {
        dispersionSostenedorBasicaInit()
        dispersionSostenedorMediaInit()
    });


    $(window).ready(function() {
        $(window).resize(function() {
            dispersionSostenedorBasicaInit();
            dispersionSostenedorMediaInit();
        });

        $(".btn_side").click(function () {
            flag_sbar *= -1;
            if (flag_sbar != 1) {
                console.log("holaaa");
                $(".main-sidebar").css({"-webkit-transform":"translate(-230px, 0)"});
                $(".main-sidebar").css({"-ms-transform":"translate(-230px, 0)"});
                $(".main-sidebar").css({"-o-transform":"translate(-230px, 0)"});
                $(".main-sidebar").css({"transform":"translate(-230px, 0)"});
                $(".content-wrapper, .main-footer").css("margin-left", "0");

            } else {
                $(".main-sidebar").css({"-webkit-transform":"translate(0, 0)"});
                $(".main-sidebar").css({"-ms-transform":"translate(0, 0)"});
                $(".main-sidebar").css({"-o-transform":"translate(0, 0)"});
                $(".main-sidebar").css({"transform":"translate(0, 0)"});
                $(".content-wrapper, .main-footer").css("margin-left", "230px");
            }

            setTimeout(function() {
                dispersionSostenedorBasicaInit();
                dispersionSostenedorMediaInit();
            }, 
            300
            );
            
            
        });
    });

    function login_admin() {
                url_base_2 = url_base.protocol + "//" + url_base.host;
                dir = url_base_2 + "/php/valida_login.php";
                $('#ingresar_admin').on("click", function() {
                    const user = document.getElementById("usuario").value;
                    const pass = document.getElementById("contrasena").value;
                    if (user == "") {
                        alertify.notify("Debes ingresar el usuario");
                        document.getElementById("usuario").focus();
                        return false;
                    } else if (pass == "") {
                        alertify.notify("Debes ingresar la contraseña");
                        document.getElementById("contrasena").focus();
                        return false;
                    } else {
                        cadena = "usuario=" + $('#usuario').val() +
                            "&contrasena=" + $('#contrasena').val() +
                            "&tipo_usuario=" + $('#tipo_usuario').val() +
                            "&privilegios=" + "1";
                        $.ajax({
                            type: "POST",
                            url: dir,
                            data: cadena,
                            cache: false,
                            statusCode: {
                                404: function() {
                                    alertify.alert("Alerta", "Pagina no Encontrada");
                                    document.getElementById("ingresar_admin").disabled = false;
                                    document.getElementById("spinner").innerHTML = '';
                                    document.getElementById("inicia_rep").innerHTML = 'Ingresar';

                                },
                                502: function() {
                                    alertify.alert("alerta", "Ha ocurrido un error al conectarse con el servidor");
                                    document.getElementById("ingresar_admin").disabled = false;
                                    document.getElementById("spinner").innerHTML = '';
                                    document.getElementById("inicia_rep").innerHTML = 'Ingresar';
                                }
                            },
                            beforeSend: function() {
                                document.getElementById("ingresar_admin").disabled = true;
                                document.getElementById("inicia_rep").innerHTML = '';
                                document.getElementById("spinner").innerHTML = '</i> <i class="fa fa-spinner fa-2x fa-spin  fa-fw">';
                            },
                            success: function(r) {
                                if (r == 1) {
                                    window.location.replace(
                                        url_base.protocol + "//" + 
                                        url_base.host + "/" + 
                                        "modulos.php"
                                    );
                                } else {
                                    document.getElementById("ingresar_admin").disabled = false;
                                    document.getElementById("spinner").innerHTML = '';
                                    document.getElementById("inicia_rep").innerHTML = 'Ingresar';
                                    alertify.defaults.glossary.title = '<p class="text-center">Notificación<p>';
                                    alertify.alert('Usuario Incorrecto');
                                }
                            }
                        });
                    }
                });
            }

    var flag_menu = 1;
    var url_base = window.location;

    $(document).ready(function () {
        login_admin();
        $("body").css('padding', '0');
        /////////// cerrar modal ///////////////////////
        var modal = document.getElementById("id_ingre_cod");
        var span = document.getElementsByClassName("close")[0];

        $("#bt_admin").click(function() {
            $('#id_ingre_cod').modal('toggle');
           
        });

        $("#btn_cerrar_modal").click(function() {
            $('#id_ingre_cod').modal('toggle');
            
        });

        $("#select_estudiante").click(function () {
            $("#estudiantes").show();
            $("#curso_p").hide();
        });

        $("#id_sos").click(function () {
            flag_menu *= -1;
            if (flag_menu != 1) {
                $(".treeview-menu > li").css("background", "#f4af1f");
            } else {
                $(".treeview-menu > li").css("background", "#f27611");
            }
        });

        $(".id_recur_edu").click(function () {
            window.location.replace(
                url_base.protocol + "//" + 
                url_base.host + "/" + 
                "recursos_edu.php"
            );
        });

        $("#img_btn_salir").click(function () {
            window.location.replace(
                url_base.protocol + "//" + 
                url_base.host + "/" + 
                "salir.php"
            );
        });

        $("#bt_manual").click(function () {
            window.open(
                url_base.protocol + "//" + 
                url_base.host + "/" + 
                "documentos/Manual_de_Usuario_Plataforma_Compromiso_Escolar_11-09-2019.pdf", 
                '_blank'
            );
        })
    });
</script>
<style type="text/css">
    .sidebar-menu > li > a {
        border-left-color: white; 
    }

    .sidebar-menu > li.active > a {
        border-left-color: #418BCC; 
    }

    .sidebar-menu > li > a {
        border-left: 3px solid transparent;
    }

    .modal-title {
        margin-bottom: 0;
        display: flex;
        text-align: center;
        position: absolute;
        left: 28%;
    }

    .card {
        width: 400px; 
        height: 360px;
        min-height: 350px;
        position: absolute;
        left: 50%;
        top: 30%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
        border-radius: 15px;
        background: white;
    }
    body {
        padding: 0;
    }

    .card-body {
        padding-top: 15px;
        border-radius: 15px;
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1.25rem;
        background-color: white;
    }

  .small-box > .inner {
    padding: 10px;
    overflow: hidden;
    outline: auto;
    max-height: 100px;
  }

  .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
                font-weight: 900;
            }

        hr {
            border-top: 2px dashed #fc455c;
        }
</style>
</body>
</html>
