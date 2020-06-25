<?php

error_reporting(E_ERROR | E_PARSE);
$params = session_get_cookie_params();
setcookie("PHPSESSID", session_id(), 0, $params["path"], $params["domain"],
    true,  // this is the secure flag you need to set. Default is false.
    true  // this is the httpOnly flag you need to set
);
require_once "dist/conf/require_conf.php";

session_start();

if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit();
}

$_SESSION["token"] = md5(uniqid(mt_rand(), true));


//definimos constantes para la cantidad de preguntas según el tipo, en esta encuesta CE + FC = 47 y COG + FAM + AF + CON + PAR + PRO = 47
define('NUM_CE', 29);
define('NUM_FC', 18);
define('NUM_COG', 12);
define('NUM_FAM', 3);
define('NUM_AF', 10);
define('NUM_CON', 7);
define('NUM_PAR', 7);
define('NUM_PRO', 8);

$conn = connectDB_demos();

$cursos_basica = array();
$cursos_media = array();

$cursos_basica_longitudinal = array();
$cursos_media_longitudinal = array();

$usuario_stmt = $conn->prepare('SELECT * FROM ce_usuarios where nombre_usu = :username');
$usuario_stmt->execute(array('username' => $_SESSION['user']));

$usuario = $usuario_stmt->fetch();

$role = $conn->query("SELECT ce_roles.* from ce_roles join ce_rol_user cru on ce_roles.id_rol = cru.id_roles_fk where cru.id_usuario_fk = {$usuario['id_usu']} AND id_rol = 2")->fetch();

if (!$role) {
    header("location: index.php");
    exit();
}

$establecimiento_id = $usuario['fk_establecimiento'];

$establecimiento_stmt = $conn->prepare("SELECT * FROM ce_establecimiento WHERE id_ce_establecimiento = :id");
$establecimiento_stmt->execute(array('id' => $establecimiento_id));
$establecimiento = $establecimiento_stmt->fetch();


$establecimiento_stmt = $conn->prepare("SELECT * FROM ce_establecimiento WHERE id_ce_establecimiento = :id");
$establecimiento_stmt->execute(array('id' => $establecimiento_id));
$establecimiento = $establecimiento_stmt->fetch();

$cursos_stmt = $conn->prepare("SELECT ce_curso_nombre as nombre,
           COUNT(id_ce_participantes)                                as participantes,
           SUM(ce_p1 + ce_p2 + ce_p3 + ce_p4 + ce_p5 + ce_p6 + ce_p7 + ce_p8 + ce_p9 + ce_p10 +
               ce_p11 + ce_p12 + ce_p13 + ce_p14 + ce_p15 + ce_p16 + ce_p17 + ce_p18 + ce_p19 + ce_p20 +
               ce_p21 + ce_p22 + ce_p23 + ce_p24 + ce_p25 + ce_p26 + ce_p27 + ce_p28 + ce_p29) /
           COUNT(ce_participantes.id_ce_participantes)               AS CE,
           SUM(ce_p30 +
               ce_p31 +
               ce_p32 +
               ce_p33 +
               ce_p34 +
               ce_p35 +
               ce_p36 +
               ce_p37 +
               ce_p38 +
               ce_p39 +
               ce_p40 +
               ce_p41 +
               ce_p42 +
               ce_p43 +
               ce_p44 +
               ce_p45 +
               ce_p46 +
               ce_p47) / COUNT(ce_participantes.id_ce_participantes) AS FC,
           ce_participantes.ce_fk_nivel as nivel
    FROM ce_encuesta_resultado
           JOIN ce_participantes ON ce_participantes_token_fk = ce_participanes_token
           JOIN ce_curso ON (ce_curso_id_ce_curso = id_ce_curso AND ce_anio_curso = ce_anio_contestada)
    WHERE ce_estado_encuesta = 1
      AND ce_establecimiento_id_ce_establecimiento = :id AND ce_anio_curso = :anio   
    GROUP BY ce_curso.ce_curso_nombre");

$cursos_stmt_longitudinal = $conn->prepare("SELECT ce_curso_nombre as nombre,
COUNT(id_ce_participantes)                                as participantes,
(SUM(ce_p1) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p2) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p3) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p4) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p5) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p6) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p7) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p8) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p9) / COUNT(ce_participantes.id_ce_participantes)  + SUM(ce_p10) / COUNT(ce_participantes.id_ce_participantes)  +
SUM(ce_p11) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p12) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p13) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p14) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p15) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p16) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p17) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p18) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p19) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p20) / COUNT(ce_participantes.id_ce_participantes)  +
SUM(ce_p21) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p22) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p23) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p24) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p25) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p26) / COUNT(ce_participantes.id_ce_participantes)  + 
SUM(ce_p27) / COUNT(ce_participantes.id_ce_participantes) + SUM(ce_p28) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p29) / COUNT(ce_participantes.id_ce_participantes) ) /  ". NUM_CE ."         AS CE,
(SUM(ce_p30) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p31) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p32) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p33) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p34) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p35) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p36) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p37) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p38) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p39) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p40) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p41) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p42) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p43) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p44) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p45) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p46) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p47) / COUNT(ce_participantes.id_ce_participantes)) / ". NUM_FC ." AS FC,
ce_participantes.ce_fk_nivel as nivel,
ce_anio_curso       
FROM ce_encuesta_resultado
JOIN ce_participantes ON (ce_participantes_token_fk = ce_participanes_token AND ce_anio_registro = ce_anio_contestada)
JOIN ce_curso ON ce_curso_id_ce_curso = id_ce_curso
WHERE ce_estado_encuesta = 1
AND ce_establecimiento_id_ce_establecimiento = :id
GROUP BY ce_curso.ce_curso_nombre ORDER BY ce_anio_curso ASC");

$cursos_dimensiones_1_stmt = $conn->prepare("SELECT ce_curso_nombre as nombre,
COUNT(id_ce_participantes)                                as participantes,
(
SUM(ce_p1)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p5)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p7)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p8)  / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p12) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p15) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p19) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p22) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p27) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p29) / COUNT(ce_participantes.id_ce_participantes)   ) /
". NUM_AF ."             AS Afectivo,
(SUM(ce_p3) / COUNT(ce_participantes.id_ce_participantes)  +
 SUM(ce_p4)  / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p9)  / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p11) / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p16) / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p23) / COUNT(ce_participantes.id_ce_participantes) +
 SUM(ce_p28) / COUNT(ce_participantes.id_ce_participantes)
) / ". NUM_CON ." AS Conductual,
(
SUM(ce_p2)  / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p6)  / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p10) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p13) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p14) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p17) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p18) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p20) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p21) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p24) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p25) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p26) / COUNT(ce_participantes.id_ce_participantes)) / ". NUM_COG ." AS Cognitivo,
ce_participantes.ce_fk_nivel as nivel,
ce_anio_curso       
FROM ce_encuesta_resultado
JOIN ce_participantes ON (ce_participantes_token_fk = ce_participanes_token AND ce_anio_registro = ce_anio_contestada)
JOIN ce_curso ON ce_curso_id_ce_curso = id_ce_curso
WHERE ce_estado_encuesta = 1
AND ce_establecimiento_id_ce_establecimiento = :id 
GROUP BY ce_curso.ce_curso_nombre  ORDER BY ce_anio_curso ASC");

$cursos_dimensiones_2_stmt = $conn->prepare("SELECT ce_curso_nombre as nombre,
COUNT(id_ce_participantes)                                as participantes,
(
SUM(ce_p30) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p31) / COUNT(ce_participantes.id_ce_participantes) + 
SUM(ce_p32) / COUNT(ce_participantes.id_ce_participantes)
) / ". NUM_FAM ."               AS Familia,
(
SUM(ce_p41) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p42) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p43) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p44) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p45) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p46) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p47) / COUNT(ce_participantes.id_ce_participantes) 
) / ". NUM_PAR ." AS Pares,
(
SUM(ce_p33) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p34) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p35) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p36) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p37) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p38) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p39) / COUNT(ce_participantes.id_ce_participantes) +
SUM(ce_p40) / COUNT(ce_participantes.id_ce_participantes) 
) / ". NUM_PRO ." AS Profesores,
ce_participantes.ce_fk_nivel as nivel,
ce_anio_curso       
FROM ce_encuesta_resultado
JOIN ce_participantes ON (ce_participantes_token_fk = ce_participanes_token AND ce_anio_registro = ce_anio_contestada)
JOIN ce_curso ON ce_curso_id_ce_curso = id_ce_curso
WHERE ce_estado_encuesta = 1
AND ce_establecimiento_id_ce_establecimiento = :id 
GROUP BY ce_curso.ce_curso_nombre ORDER BY ce_anio_curso ASC");

try {

    $cursos_stmt->execute(array('id' => $establecimiento_id, 'anio' => date('Y')));

    
    $cursos_stmt_longitudinal->execute(array('id' => $establecimiento_id));

    $cursos_dimensiones_1_stmt->execute(array('id' => $establecimiento_id));

    $cursos_dimensiones_2_stmt->execute(array('id' => $establecimiento_id));

    $dim1 = $cursos_dimensiones_1_stmt->fetchAll();


    $dim2 = $cursos_dimensiones_2_stmt->fetchAll();

    //$all = $cursos_stmt->fetchAll();

    while ($cursos_result = $cursos_stmt->fetch()) {
        $curso = array(
            'name' => $cursos_result['nombre'],
            'x' => (float) $cursos_result['FC'],
            'y' => (float) $cursos_result['CE'],
            'participantes' => (int) $cursos_result['participantes']
        );
        if ($cursos_result['nivel'] == 1) {
            //$curso['color'] = 'rgb(206, 225, 255)';
            $curso['color'] = 'rgb(95, 55, 188)';
            array_push($cursos_basica, $curso);
        } else {
            if ($cursos_result['nivel'] == 2) {
                $curso['color'] = 'rgb(95, 55, 188)';

                array_push($cursos_media, $curso);
            }
        }
    }
        while ($cursos_result_longitudinal = $cursos_stmt_longitudinal->fetch()) {
            $curso_longitudinal = array(
                'name' => $cursos_result_longitudinal['nombre'],
                'x' => (float) $cursos_result_longitudinal['FC'],
                'y' => (float) $cursos_result_longitudinal['CE'],
                'participantes' => (int) $cursos_result_longitudinal['participantes'],
                'anio' => $cursos_result_longitudinal['ce_anio_curso']
            );

            if ($cursos_result_longitudinal['nivel'] == 1) {
                //$curso['color'] = 'rgb(206, 225, 255)';
                $curso_longitudinal['color'] = 'rgb(95, 55, 188)';
                array_push($cursos_basica_longitudinal, $curso_longitudinal);
            } else {
                if ($cursos_result_longitudinal['nivel'] == 2) {
                    $curso_longitudinal['color'] = 'rgb(95, 55, 188)';
    
                    array_push($cursos_media_longitudinal, $curso_longitudinal);
                }
            }
        }

    $totalParticipantesBasica = array_reduce($cursos_basica, function ($accum, $item) {
        return $accum + $item['participantes'];
    }, 0);

    $totalParticipantesMedia = array_reduce($cursos_media, function ($accum, $item) {
        return $accum + $item['participantes'];
    }, 0);

    if ($totalParticipantesBasica == 0) {
        $hidden_basica = "hidden";
    } else {
        $hidden_basica = "";
    }
    if ($totalParticipantesMedia == 0) {
        $hidden_media = "hidden";
    } else {
        $hidden_media = "";
    }
} catch (Exception $e) {
    echo $e;
}

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
    <script src="../assets/js/mresize.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>


    <style type="text/css">
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
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .panel-body {
            width: 100%;
            padding: 15px;
            margin-top: 0;

        }

        .cursor_dimensiones {
            text-decoration: none;
        }

        div.label-alerta-media.hvr.hvr-grow {
            text-decoration: none;
        }

        .skin-blue .sidebar-menu .treeview-menu>li.active>a,
        .skin-blue .sidebar-menu .treeview-menu>li>a:hover {
            color: #ffffff;
            background-color: #f2761185;
        }

        .skin-blue .sidebar-menu>li>.treeview-menu {
            margin: 0 1px;
            background: #f27611;
        }

        .skin-blue .sidebar-menu>li>a {
            border-left-color: white;
        }

        .skin-blue .sidebar-menu .treeview-menu>li>a {
            color: #ffffff85;
        }

        .skin-blue .sidebar-menu>li:hover>a,
        .skin-blue .sidebar-menu>li.active>a,
        .skin-blue .sidebar-menu>li.menu-open>a {
            color: #ffffff;
            background: #f4af1f;
        }

        .skin-blue .sidebar-menu>li:hover>a,
        .skin-blue .sidebar-menu>li.active>a,
        .skin-blue .sidebar-menu>li.treeview>a {
            color: #ffffff;
            background: #f4af1f;
        }

        .tit-menu {
            float: left;
            text-align: left;
            color: white;
        }

        .skin-blue .wrapper,
        .skin-blue .main-sidebar,
        .skin-blue .left-side {
            background-color: #f4af1f;
        }

        .nav-pills>li.active>a,
        .nav-pills>li.active>a:hover,
        .nav-pills>li.active>a:focus {
            font-size: 18px;
            font-weight: 400;
            color: black;
            background-color: #e6e6e6;
            border-bottom-color: #e6e6e68c;
        }

        .nav-pills>li>a {
            font-size: 18px;
            border-radius: 0;
            border-top: 3px solid transparent;
            color: white;
            background-color: #cfd0d1;
        }

        /* pestañas superiores */
        .nav-pills>li.pest_li.active>a {
            font-size: 18px;
            border-radius: 0;
            border-top: 3px solid transparent;
            color: white;
            background-color: #cfd0d1;
            /* gris claro */
        }

        .nav-pills>li.pest_li>a {
            font-size: 18px;
            border-radius: 0;
            border-top: 0.5px solid white;
            border-left: 0.5px solid white;
            border-right: 0.5px solid white;
            color: white;
            background-color: #f4af1f;
            /* zapallo */
        }

        .nav-pills>li.pest_li:focus>a {
            background-color: #f4af1f7d;
            /* zapallo */
        }

        .id_recur_edu {
            margin-top: 380px;
            margin-left: 20px;
            padding-top: 1px;
            padding-left: 10px;
            width: 195px;
            height: 300px;
            background-image: url("../assets/img/recursos-educativos.png");
            background-repeat: no-repeat;
            background-size: 100% auto;
            text-shadow: 0.5px 0.5px 0.5px black;
            color: white;
            border-radius: 10px;
        }

        .id_recur_edu:hover {
            cursor: pointer;
            cursor: hand;
            opacity: 0.9;
        }

        .id_recur_edu:active {
            cursor: pointer;
            cursor: hand;
            opacity: 0.7;
        }

        #img_btn_salir:hover {
            cursor: pointer;
            cursor: hand;
            opacity: 0.9;
        }

        #img_btn_salir:active {
            cursor: pointer;
            cursor: hand;
            opacity: 0.7;
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
            cursor: pointer;
            cursor: hand;
            opacity: 0.9;
        }

        .btn_side:active {
            cursor: pointer;
            cursor: hand;
            opacity: 0.7;
        }

        .wrapper.card_c {
            overflow-y: hidden;
        }

        html {
            padding: 0;
            margin: 0;
        }

        body {
            padding: 0;
            margin: 0;
        }

        @media (max-width: 1299px) {
            .main-sidebar {
                -webkit-transform: translate(-230px, 0);
                -ms-transform: translate(-230px, 0);
                -o-transform: translate(-230px, 0);
                transform: translate(-230px, 0);
            }
        }

        @media (min-width: 1300px) {
            .sidebar-collapse .main-sidebar {
                -webkit-transform: translate(-230px, 0);
                -ms-transform: translate(-230px, 0);
                -o-transform: translate(-230px, 0);
                transform: translate(-230px, 0);
            }
        }

        @media (max-width: 1299px) {
            .sidebar-open .main-sidebar {
                -webkit-transform: translate(0, 0);
                -ms-transform: translate(0, 0);
                -o-transform: translate(0, 0);
                transform: translate(0, 0);
            }
        }

        @media (max-width: 1299px) {
            .main-sidebar {
                -webkit-transform: translate(-230px, 0);
                -ms-transform: translate(-230px, 0);
                -o-transform: translate(-230px, 0);
                transform: translate(-230px, 0);
                margin-left: 0;
            }
        }

        @media (max-width: 1300px) {

            .content-wrapper,
            .main-footer {
                margin-left: 0;
            }
        }

        .selected-active, .selected-active > a {
            background-color:#f4af1f !important;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div id="linea-superior" style="background: #40c2d4; padding-left: 100px; ">
        <table width="100%" height="100%">
            <tr width="100%">
                <td align="left" width="50%">
                    <div style="display: flex; align-items: baseline; background: #40c2d4;">
                        <img style="height: 78px; width: 750px;" src="../assets/img/C2_Resultados.png">
                        <div style="margin-top: 30px; margin-left: 195px; font-size: 20px; position: absolute; color: white;">
                            Módulo de Resultados
                        </div>
                    </div>
                </td>
                <td align="right" width="50%">
                    <table width="100%">
                        <tr width="100%">
                            <td align="right" width="50%">
                                <!-- <h3 style="color: white;"><'?php echo $_SESSION["profesor_nombres"];?></h3> -->

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
                    <div style="position: absolute; top: 0px; left: 16px; color: white; padding-bottom: 0;">
                        <h4><?php echo $_SESSION["tipo_nombre"]; ?></h4>
                    </div>
                    <section class="sidebar">
                        <div class="user-panel">
                            <div class="pull-left image">
                                <br>
                            </div>
                            <div class="pull-left info" style="left: 0;">
                                <p>
                                    Menú De Navegación
                                </p>
                            </div>
                        </div>
                        <ul class="sidebar-menu" data-widget="tree">
                            <li class="active treeview">
                                <a href="#" id="id_estab">
                                    <i class="fa fa-th"></i>
                                    <span>Establecimiento</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu menu-open">
                                    <li class="active treeview" style="padding: 5px;">
                                        <a id='select_estudiante' href='#'>
                                            <i class='fa fa-pie-chart' aria-hidden='true'></i>
                                            <span>Reportes</span>
                                            <span class="pull-right-container">
                                                <i class="fa fa-angle-left pull-right"></i>
                                            </span>
                                        </a>
                                        <ul class="treeview-menu">
                                            <li class="treeview active selected-active" style="padding: 5px;">
                                                <a id='select_dispersion' href='#'>
                                                    <i class="fa fa-pie-chart"></i>
                                                    <span>Dispersión</span>
                                                </a>
                                            </li>
                                            <li class="treeview" style="padding: 5px;">
                                                <a id='select_estudiante' href='#'>
                                                    <i class='fa fa-bar-chart' aria-hidden='true'></i>
                                                    <span>Seguimiento</span>
                                                    <span class="pull-right-container">
                                                        <i class="fa fa-angle-left pull-right"></i>
                                                    </span>
                                                </a>
                                                <ul class="treeview-menu">
                                                    <li class="treeview" style="padding: 5px;">
                                                        <a id='select_establecimiento' href='#'>
                                                            <i class="fa fa-building"></i>
                                                            <span>Establecimiento</span>
                                                        </a>
                                                    </li>
                                                    <li class="treeview" style="padding: 5px;">
                                                        <a id='select_cursos' href='#'>
                                                            <i class="fa fa-users"></i>
                                                            <span>Cursos</span>
                                                        </a>
                                                    </li>
                                                    <li class="treeview" style="padding: 5px;">
                                                        <a id='select_alumnos' href='#'>

                                                            <i class="fa fa-user"></i>
                                                            <span>Estudiantes</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                    </section>
                    <div align="left" class="id_recur_edu">

                    </div>
                </aside>
                <div class="content-wrapper">
                    <section class="content-header" style="background: #f4af1f; padding-bottom: 20px;">
                        <a style="width: 60px; height: 60px; border: 0; color: white; background: #f4af1f; padding: 0; padding-right: 10px; font-size: 30px;" class="openbtn btn_side">☰</a>
                        <h1 class="text-center">
                            <?php echo $establecimiento['ce_establecimiento_nombre'] ?>
                        </h1>
                    </section>
                    <section class="content" id="content-main">
                        <div class="row">
                            <?php tarjeta_de_presentacion_establecimiento($establecimiento_id); ?>
                        </div>
                        <div class="row">
                        <div class=container>
                                    <div class="alert alert-warning alert-dismissable" style="width:550px; height: 100px; margin-left:155px;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                        <strong>
                                        <span class="fa-stack fa-2x">
                                        <i class="fa fa-circle fa-stack-2x" style="position:absolute; top: 12px;color:#2d6693; font-size: 32px;" aria-hidden="true"></i>
                                        <i class="fa fa-question fa-stack-1x" style="color:#fff; font-size: 24px;" aria-hidden="true"></i>
                                        </span>
                                        <p style="text-align: justify;position: relative;top: -37px;margin-left: 50px;">Al hacer click sobre este icono nos entregará más información respecto a los resultados obtenidos.</p>
                                        </strong>
                                    </div>
                                </div>
                            <div class="col-xs-12" hidden>
                                <div class="badge_compromiso_escolar" data-toggle="collapse" data-target="#demo">
                                    Bajo compromiso escolar (-) y Factores contextuales limitantes (-). <span> Ver descripción</span>
                                </div>
                                <div id="demo" class="collapse">
                                    <p class="text-justify">
                                        Estudiantes que presentan una débil participación e interés en las actividades académicas. En general, no consideran que el aprendizaje sea significativo para su presente y futuro, al tiempo que no se sienten parte de una comunidad escolar. No hay una disposición para invertir destrezas cognitivas dentro del aprendizaje y dominio de nuevas habilidades de gran complejidad. EI no tener un compromiso escolar desarrollado es un factor de riesgo de la deserción escolar, para graduarse con altos niveles de conductas de riesgo y un bajo rendimiento académico. El compromiso escolar es altamente permeable por factores contextuales. En este caso se aprecia que el débil compromiso escolar se vincula a un bajo involucramiento de la familia del estudiante en su proceso de aprendizaje junto con un clima escolar deteriorado (relación con profesores y pares) Io que termina desmotivando al estudiante. Un clima escolar deteriorado se puede observar en malas relaciones entre estudiante y profesores, entre los mismos estudiantes, y en un ambiente donde se han deteriorado los lazos de respeto y confianza.
                                    </p>
                                </div>
                            </div>

                            <div class="col-xs-12" style="margin-bottom: 10px;">
                                <button id="id_btn_pdf" style="margin-top:2.3%;" class="btn btn-primary pull-right" disabled>
                                    <span id="id_reporte_cargado" hidden>
                                        Descargar Reporte
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                    </span>
                                    <span id="id_cargando_reporte">
                                        Cargando Reporte
                                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                                    </span>
                                </button>

                            </div>
                            <div class="col-xs-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4>Gráfico de Dispersión</h4>
                                    </div>
                                    <div class="panel-body">
                                        <div>
                                            <span>
                                                <p style="font-size: 20px; text-align: center">Reporte de establecimiento Básica <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="definicion_cuadrantes()"></i></p>
                                            </span>
                                        </div>
                                        <div id="id_graficos_b">
                                            <div id="dispersion-establecimiento-basica" style="min-width: 310px; height: 500px; margin: 0 auto" <?php echo $hidden_basica; ?>>
                                            </div>
                                        </div>
                                        <div id="id_graficos_m">
                                            <div id="dispersion-establecimiento-media" style="min-width: 310px; height: 500px; margin: 0 auto" <?php echo $hidden_media; ?>>
                                            </div>
                                        </div>

                                        <div id="id_error" class="alert alert-danger text-center" hidden>
                                            No hay Estudiantes encuestados en el Establecimiento
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="content" id="content-establecimiento" style="display:none; padding-bottom: 50px;">
                        <section class="content-main">
                            <div class="row">
                                <?php tarjeta_de_presentacion_establecimiento($establecimiento_id); ?>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                    <div class=container>
                                    <div class="alert alert-warning alert-dismissable" style="width:550px; height: 100px; margin-left:70px;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                        <strong>
                                        <span class="fa-stack fa-2x">
                                        <i class="fa fa-circle fa-stack-2x" style="position:absolute; top: 12px;color:#2d6693; font-size: 32px;" aria-hidden="true"></i>
                                        <i class="fa fa-question fa-stack-1x" style="color:#fff; font-size: 24px;" aria-hidden="true"></i>
                                        </span>
                                        <p style="text-align: justify;position: relative;top: -37px;margin-left: 50px;">Al hacer click sobre este icono nos entregará más información respecto a los resultados obtenidos.</p>
                                        </strong>
                                    </div>
                                </div>
                                        <button id="id_btn_pdf_est_ce_fc" style="margin-top:3%;" class="btn btn-primary pull-right">
                                            <span id="id_reporte_cargado">
                                                Descargar Reporte
                                                <i class="fa fa-download"></i>
                                            </span>
                                        </button>
                                        <h2>Reporte seguimiento de establecimiento</h2>
                                        <p style="margin-top: 15px;font-size:1.2em;">En esta sección se puede apreciar la dinámica del compromiso escolar y sus factores contextuales a través del tiempo.</p>
                                        <hr style="border-top: 2px solid #fc455c; margin-top: 0;">
                                        <h3 style="margin-top: 30px; margin-bottom:30px;">Establecimiento: <span style="font-weight: normal"><?php echo $establecimiento["ce_establecimiento_nombre"] ?></span></h3>

                                        <h3 class="text-center" style="padding-bottom: 15px;">Compromiso escolar y factores contextuales <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="referencias_ce_dimensiones_gen()"></i></h3>

                                    </div>
                                </div>
                                <div class="col-md-10 col-md-offset-1 hide">
                                    <table class="table table-striped" id="tabla_ce_fc">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="row" style="text-align:right">Compromiso escolar</th>
                                                <th scope="row" style="text-align:right">Factores contextuales</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $x = 0;
                                            while ($x < count($cursos_basica_longitudinal)) {
                                                echo "<tr>
                                            <td><span style='font-weight:bold'>" . $cursos_basica_longitudinal[$x]["anio"] . "</span></td>
                                            <td align='right'>" . round($cursos_basica_longitudinal[$x]["y"]) . "</td>
                                            <td align='right'>" . round($cursos_basica_longitudinal[$x]["x"]) . "</td></tr>";
                                                $x++;
                                            }

                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-10 col-md-offset-1">
                                    <p style="margin-top: 15px;font-size:1.2em;">La gráfica muestra el seguimiento del compromiso escolar y sus factores contextuales, respecto del establecimiento educacional.</p>

                                    <div id="container_grafico_ce_cf"></div>
                                    <figure class="highcharts-figure">
                                        <div id="container-graf-establecimiento-ce-fc"></div>
                                    </figure>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-center" style="padding-left: 15px; padding-bottom: 15px; margin-top:60px">Compromiso escolar por dimensión <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="referencias_ce_dimensiones()"></i></h3>

                                </div>
                                <div class="col-md-10 col-md-offset-1 hide">
                                    <table class="table table-striped" id="tabla_dim1">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="row" style="text-align:right">Afectivo</th>
                                                <th scope="row" style="text-align:right">Conductual</th>
                                                <th scope="row" style="text-align:right">Cognitivo</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $x = 0;
                                            while ($x < count($dim1)) {
                                                echo "<tr>
                                            <td><span style='font-weight:bold'>" . $dim1[$x]["ce_anio_curso"] . "</span></td>
                                            <td align='right'>" . round($dim1[$x]["Afectivo"]) . "</td>
                                            <td align='right'>" . round($dim1[$x]["Conductual"]) . "</td>                                            
                                            <td align='right'>" . round($dim1[$x]["Cognitivo"]) . "</td></tr>";
                                                $x++;
                                            }

                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-10 col-md-offset-1">
                                    <p style="margin-top: 15px;font-size:1.2em;">La gráfica muestra el seguimiento del compromiso escolar y sus dimensiones afectivo, conductual y cognitivo, respecto del establecimiento educacional.</p>

                                    <div id="container_grafico_dim1"></div>
                                    <figure class="highcharts-figure">
                                        <div id="container_graf_dim1"></div>
                                    </figure>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-center" style="padding-left: 15px; padding-bottom: 15px; margin-top:60px">Factores contextuales por dimensión <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="referencias_fc_dimensiones()"></i></h3>

                                </div>
                                <div class="col-md-10 col-md-offset-1 hide">
                                    <table class="table table-striped" id="tabla_dim2">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="row" style="text-align:right">Familia</th>
                                                <th scope="row" style="text-align:right">Pares</th>
                                                <th scope="row" style="text-align:right">Profesores</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $x = 0;
                                            while ($x < count($dim2)) {
                                                echo "<tr>
                                            <td><span style='font-weight:bold'>" . $dim2[$x]["ce_anio_curso"] . "</span></td>
                                            <td align='right'>" . round($dim2[$x]["Familia"]) . "</td>
                                            <td align='right'>" . round($dim2[$x]["Pares"]) . "</td>                                            
                                            <td align='right'>" . round($dim2[$x]["Profesores"]) . "</td></tr>";
                                                $x++;
                                            }

                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-10 col-md-offset-1">
                                    <p style="margin-top: 15px;font-size:1.2em; margin-bottom:10px;">La gráfica muestra el seguimiento de los factores contextuales y sus dimensiones familia, pares y profesores respecto del establecimiento educacional.</p>

                                    <div id="container_grafico_dim2"></div>
                                    <figure class="highcharts-figure">
                                        <div id="container_graf_dim2"></div>
                                    </figure>
                                </div>
                            </div>
                        </section>
                    </section>
                    <section class="content" id="content-cursos" style="display:none; padding-bottom:50px;">
                        <section class="content-main">
                            <div class="row">
                                <?php tarjeta_de_presentacion_establecimiento($establecimiento_id); ?>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                    <div class=container>
                                    <div class="alert alert-warning alert-dismissable" style="width:550px; height: 100px; margin-left:70px;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                        <strong>
                                        <span class="fa-stack fa-2x">
                                        <i class="fa fa-circle fa-stack-2x" style="position:absolute; top: 12px;color:#2d6693; font-size: 32px;" aria-hidden="true"></i>
                                        <i class="fa fa-question fa-stack-1x" style="color:#fff; font-size: 24px;" aria-hidden="true"></i>
                                        </span>
                                        <p style="text-align: justify;position: relative;top: -37px;margin-left: 50px;">Al hacer click sobre este icono nos entregará más información respecto a los resultados obtenidos.</p>
                                        </strong>
                                    </div>
                                </div>
                                        <h2>Reporte seguimiento de curso</h2>
                                        <p style="margin-top: 15px;font-size:1.2em;">En esta sección se puede apreciar la dinámica del compromiso escolar y sus factores contextuales a través del tiempo.</p>
                                        <hr style="border-top: 2px solid #fc455c; margin-top: 0;">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h4 class="border-bottom pb-1 mb-2">Selecciona un curso:</h4>
                                                <select name="sel_curso" id="sel_curso" class="form-control mb-3">
                                                    <?php echo select_curso($establecimiento_id); ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row" id="curso-seleccionado" style="display:none">
                                    <div class="col-md-10 col-md-offset-1">
                                        <button id="id_btn_pdf_cur_ce_fc" style="margin-top:3%;" class="btn btn-primary pull-right">
                                            <span>
                                                Descargar Reporte
                                                <i class="fa fa-download"></i>
                                            </span>
                                        </button>
                                        <h3 style="margin-top: 30px; margin-bottom:30px;">Curso: <span style="font-weight: normal" id="desc_curso"></span></h3>
                                        <h3 class="text-center" style="padding-bottom: 15px;">Compromiso escolar y factores contextuales <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="referencias_ce_dimensiones_gen()"></i></h3>
                                        <div id="tabla_ce_fc_curso">

                                        </div>
                                    </div>
                                    <div class="col-md-10 col-md-offset-1">
                                        <p style="margin-top: 15px;font-size:1.2em;">La gráfica muestra el seguimiento del compromiso escolar y sus factores contextuales, respecto del curso.</p>

                                        <div id="container_grafico_ce_fc_cursos"></div>
                                        <figure class="highcharts-figure">
                                            <div id="container_grafico_ce_fc_cursos"></div>
                                        </figure>
                                        <div>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="text-center" style="padding-left: 15px; padding-bottom: 15px; margin-top:60px">Compromiso escolar por dimensión <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="referencias_ce_dimensiones()"></i></h3>

                                        </div>
                                        <div class="col-md-10 col-md-offset-1">
                                            <div id="tabla_dim1_curso">

                                            </div>

                                        </div>
                                        <div class="col-md-10 col-md-offset-1">
                                            <p style="margin-top: 15px;font-size:1.2em;">La gráfica muestra el seguimiento del compromiso escolar y sus dimensiones afectivo, conductual y cognitivo, respecto del curso.</p>

                                            <div id="container_grafico_dim1_curso"></div>
                                            <figure class="highcharts-figure">
                                                <div id="container_grafico_dim1_curso"></div>
                                            </figure>
                                            <div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <h3 class="text-center" style="padding-left: 15px; padding-bottom: 15px; margin-top:60px">Factores contextuales por dimensión <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="referencias_fc_dimensiones()"></i></h3>

                                        </div>
                                        <div class="col-md-10 col-md-offset-1">
                                            <div id="tabla_dim2_curso">
                                            </div>
                                        </div>
                                        <div class="col-md-10 col-md-offset-1">
                                            <p style="margin-top: 15px;font-size:1.2em; margin-bottom: 10px;">La gráfica muestra el seguimiento de los factores contextuales y sus dimensiones familia, pares y profesores respecto del curso.</p>

                                            <div id="container_grafico_dim2_curso"></div>
                                            <figure class="highcharts-figure">
                                                <div id="container_grafico_dim2_curso"></div>
                                            </figure>
                                            <div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <h3 class="text-center" style="padding-left: 15px; padding-bottom: 15px; margin-top:60px">Comparación temporal por ítem</i></h3>

                                        </div>
                                        <div class="col-md-10 col-md-offset-1">
                                        </div>
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="row">
                                                <div class="col-md-8 col-md-offset-2">                                                
                                                    <h4 class="text-center">Simbología</h4>
                                                    <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="row" style="text-align:center;max-width:10px;">Ítem</th>
                                                            <th scope="row" style="text-align:center">Descripción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align:center;">&#8593;</td>
                                                            <td style="text-align:left;">Mejoró la tendencia para esa pregunta respecto al año anterior</td>                                                          
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:center;">0</td>
                                                            <td style="text-align:left;">Se mantiene la tendencia para esa pregunta respecto al año anterior</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:center;">&#8595;</td>
                                                            <td style="text-align:left;">Bajó la tendencia para esa pregunta respecto al año anterior</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                </div>
                                               
                                            </div>
                                            <div id="tabla_simbologia">
                                            </div>

                                        </div>
                                    </div>
                        </section>
                    </section>
                    <section class="content" id="content-alumnos" style="display:none; padding-bottom: 50px;">
                        <section class="content-main">
                            <div class="row">
                                <?php tarjeta_de_presentacion_establecimiento($establecimiento_id); ?>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                <div class=container>
                                    <div class="alert alert-warning alert-dismissable" style="width:550px; height: 100px; margin-left:70px;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                        <strong>
                                        <span class="fa-stack fa-2x">
                                        <i class="fa fa-circle fa-stack-2x" style="position:absolute; top: 12px;color:#2d6693; font-size: 32px;" aria-hidden="true"></i>
                                        <i class="fa fa-question fa-stack-1x" style="color:#fff; font-size: 24px;" aria-hidden="true"></i>
                                        </span>
                                        <p style="text-align: justify;position: relative;top: -37px;margin-left: 50px;">Al hacer click sobre este icono nos entregará más información respecto a los resultados obtenidos.</p>
                                        </strong>
                                    </div>
                                </div>
                                        <h2>Reporte seguimiento estudiante</h2>
                                        <p style="margin-top: 15px;font-size:1.2em;">En esta sección se puede apreciar la dinámica del compromiso escolar y sus factores contextuales a través del tiempo.</p>
                                        <hr style="border-top: 2px solid #fc455c; margin-top: 0;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h4 class="border-bottom pb-1 mb-2">Selecciona un estudiante:</h4>
                                                <select name="sel_alumno" id="sel_alumno" class="form-control mb-3">
                                                    <?php echo select_estudiantes_por_establecimiento($establecimiento_id); ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row" id="alumno-seleccionado" style="display:none">
                                    <div class="col-md-10 col-md-offset-1">
                                        <button id="id_btn_pdf_alumno" style="margin-top:2.3%;" class="btn btn-primary pull-right">
                                            <span id="id_reporte_cargado">
                                                Descargar Reporte
                                                <i class="fa fa-download"></i>
                                            </span>
                                        </button>
                                        <h3 style="margin-top: 30px; margin-bottom:5px;">Estudiante: <span style="font-weight: normal" id="desc_alumno"></span></h3>
                                        <h3 style="margin-top: 0; margin-bottom:30px;">Curso: <span style="font-weight: normal" id="desc_alumno_curso"></span></h3>
                                        
                                        <h3 class="text-center" style="padding-bottom: 15px;">Compromiso escolar y factores contextuales <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="referencias_ce_dimensiones_gen()"></i></h3>
                                        <div id="tabla_ce_fc_alumno">

                                        </div>
                                    </div>
                                    <div class="col-md-10 col-md-offset-1">
                                        <p style="margin-top: 15px;font-size:1.2em;">La gráfica muestra el seguimiento del compromiso escolar y sus factores contextuales, respecto del estudiante.</p>

                                        <div id="container_grafico_ce_fc_alumno"></div>
                                        <figure class="highcharts-figure">
                                            <div id="container_grafico_ce_fc_alumno"></div>
                                        </figure>
                                        <div>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="text-center" style="padding-left: 15px; padding-bottom: 15px; margin-top:60px">Compromiso escolar por dimensión <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="referencias_ce_dimensiones()"></i></h3>

                                        </div>
                                        <div class="col-md-10 col-md-offset-1">
                                            <div id="tabla_dim1_alumno">

                                            </div>

                                        </div>
                                        <div class="col-md-10 col-md-offset-1">
                                            <p style="margin-top: 15px;font-size:1.2em;">La gráfica muestra el seguimiento del compromiso escolar y sus dimensiones afectiva, conductual y cognitiva, respecto del estudiante.</p>

                                            <div id="container_grafico_dim1_alumno"></div>
                                            <figure class="highcharts-figure">
                                                <div id="container_grafico_dim1_alumno"></div>
                                            </figure>
                                            <div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <h3 class="text-center" style="padding-left: 15px; padding-bottom: 15px; margin-top:60px">Factores contextuales por dimensión <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="referencias_fc_dimensiones()"></i></h3>

                                        </div>
                                        <div class="col-md-10 col-md-offset-1">
                                            <div id="tabla_dim2_alumno">
                                            </div>
                                        </div>
                                        <div class="col-md-10 col-md-offset-1">
                                            <p style="margin-top: 15px;font-size:1.2em; margin-bottom: 10px;">La gráfica muestra el seguimiento de los factores contextuales y sus dimensiones familia, profesores y pares, respecto del estudiante.</p>

                                            <div id="container_grafico_dim2_alumno"></div>
                                            <figure class="highcharts-figure">
                                                <div id="container_grafico_dim2_alumno"></div>
                                            </figure>
                                            <div>

                                            </div>
                                        </div>
                                    </div>
                        </section>
                    </section>
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
                    <td width="33%" align="center" valign="center">
                        <p style="font-size: small; text-align: justify; font: condensed 100% sans-serif; color: #212529;">
                            Estas encuestas forman parte del Proyecto FONDEF ID14I10078-ID14I20078 Medición del compromiso del niño, niña y adolescente con sus estudios para la promoción de trayectorias educativas exitosas.
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </footer>
    <div id="id_ingre_cod" class="modal fade">
        <div class="card" id="form-encuesta" style="">
            <div class="card-body">
                <div class="modal-header" style="text-align: center; line-height: 7px; border: 0; margin: 0; padding: 0;">
                    <h5 style="" class="modal-title">Formulario de acceso</h5>
                    <button id="btn_cerrar_modal" type="button" class="close">&times;</button>
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
                        <input type="password" id="contrasena" name="contrasena" class="form-control" autocomplete="password" placeholder="contraseña" required />

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
        var aniosCeFc = <?php echo count($cursos_basica_longitudinal) ?>;
        
        Highcharts.chart('container_grafico_ce_cf', {
            data: {
                table: 'tabla_ce_fc'
            },
            colors: [
                '#40c3d4',
                '#fc455c',
                '#f39c12'
            ],
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Puntaje'
                },
                max: 5
            },
            credits: {
                enabled: false
            },
            series: [{
                    type: 'column',
                    tooltip: {
                        formatter: function() {
                            if (this.point.name != undefined) {
                                return '<b>' + this.series.name + '</b><br/>' +
                                    this.point.y + ' ' + this.point.name.toLowerCase();
                            }
                        }
                    }
                },
                {
                    type: 'column',
                    tooltip: {
                        formatter: function() {
                            if (this.point.name != undefined) {
                                return '<b>' + this.series.name + '</b><br/>' +
                                    this.point.y + ' ' + this.point.name.toLowerCase();
                            }
                        }
                    }
                }                
            ]
        });

        Highcharts.chart('container_grafico_dim1', {
            data: {
                table: 'tabla_dim1'
            },
            colors: [
                '#40c3d4',
                '#fc455c',
                '#f39c12'
            ],
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Puntaje'
                },
                max: 5
            },
            credits: {
                enabled: false
            },
            series: [{
                    type: 'column',
                    tooltip: {
                        formatter: function() {
                            if (this.point.name != undefined) {
                                return '<b>' + this.series.name + '</b><br/>' +
                                    this.point.y + ' ' + this.point.name.toLowerCase();
                            }
                        }
                    }
                },
                {
                    type: 'column',
                    tooltip: {
                        formatter: function() {
                            if (this.point.name != undefined) {
                                return '<b>' + this.series.name + '</b><br/>' +
                                    this.point.y + ' ' + this.point.name.toLowerCase();
                            }
                        }
                    }
                },
                {
                    type: 'column',
                    tooltip: {
                        formatter: function() {
                            if (this.point.name != undefined) {
                                return '<b>' + this.series.name + '</b><br/>' +
                                    this.point.y + ' ' + this.point.name.toLowerCase();
                            }
                        }
                    }
                }
            ]
        });

        Highcharts.chart('container_grafico_dim2', {
            data: {
                table: 'tabla_dim2'
            },
            colors: [
                '#40c3d4',
                '#fc455c',
                '#f39c12'
            ],
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Puntaje'
                },
                max: 5
            },
            credits: {
                enabled: false
            },
            series: [{
                    type: 'column',
                    tooltip: {
                        formatter: function() {
                            if (this.point.name != undefined) {
                                return '<b>' + this.series.name + '</b><br/>' +
                                    this.point.y + ' ' + this.point.name.toLowerCase();
                            }
                        }
                    }
                },
                {
                    type: 'column',
                    tooltip: {
                        formatter: function() {
                            if (this.point.name != undefined) {
                                return '<b>' + this.series.name + '</b><br/>' +
                                    this.point.y + ' ' + this.point.name.toLowerCase();
                            }
                        }
                    }
                },
                {
                    type: 'column',
                    tooltip: {
                        formatter: function() {
                            if (this.point.name != undefined) {
                                return '<b>' + this.series.name + '</b><br/>' +
                                    this.point.y + ' ' + this.point.name.toLowerCase();
                            }
                        }
                    }
                }
            ]
        });

        function generaGraficoCeFcCursos() {
            Highcharts.chart('container_grafico_ce_fc_cursos', {
                data: {
                    table: 'tabla_ce_fc_curso'
                },
                colors: [
                    '#40c3d4',
                    '#fc455c',
                    '#f39c12'
                ],
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Puntaje'
                    },
                    max: 5
                },
                credits: {
                    enabled: false
                },
                series: [{
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    },
                    {
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    }
                ]
            });
        }

        function generaGraficoCeFcAlumno() {
            Highcharts.chart('container_grafico_ce_fc_alumno', {
                data: {
                    table: 'tabla_ce_fc_alumno'
                },
                colors: [
                    '#40c3d4',
                    '#fc455c',
                    '#f39c12'
                ],
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Puntaje'
                    },
                    max: 5
                },
                credits: {
                    enabled: false
                },
                series: [{
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    },
                    {
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    }
                ]
            });
        }

        function generaGraficoDim1Cursos() {
            Highcharts.chart('container_grafico_dim1_curso', {
                data: {
                    table: 'tabla_dim1_curso'
                },
                colors: [
                    '#40c3d4',
                    '#fc455c',
                    '#f39c12'
                ],
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Puntaje'
                    },
                    max: 5
                },
                series: [{
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    },
                    {
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    },
                    {
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    }
                ],
                credits: {
                    enabled: false
                }
            });
        }

        function generaGraficoDim2Cursos() {
            Highcharts.chart('container_grafico_dim2_curso', {
                data: {
                    table: 'tabla_dim2_curso'
                },
                colors: [
                    '#40c3d4',
                    '#fc455c',
                    '#f39c12'
                ],
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Puntaje'
                    },
                    max: 5
                },
                credits: {
                    enabled: false
                },
                series: [{
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    },
                    {
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    },
                    {
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    }
                ]
            });
        }

        function generaGraficoDim1Alumno() {
            Highcharts.chart('container_grafico_dim1_alumno', {
                data: {
                    table: 'tabla_dim1_alumno'
                },
                colors: [
                    '#40c3d4',
                    '#fc455c',
                    '#f39c12'
                ],
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Puntaje'
                    },
                    max: 5
                },
                credits: {
                    enabled: false
                },
                series: [{
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    },
                    {
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    },
                    {
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    }
                ]
            });
        }

        function generaGraficoDim2Alumno() {
            Highcharts.chart('container_grafico_dim2_alumno', {
                data: {
                    table: 'tabla_dim2_alumno'
                },
                colors: [
                    '#40c3d4',
                    '#fc455c',
                    '#f39c12'
                ],
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Puntaje'
                    },
                    max: 5
                },
                credits: {
                    enabled: false
                },
                series: [{
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    },
                    {
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    },
                    {
                        type: 'column',
                        tooltip: {
                            formatter: function() {
                                if (this.point.name != undefined) {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        this.point.y + ' ' + this.point.name.toLowerCase();
                                }
                            }
                        }
                    }
                ]
            });
        }

        sesion();
        var url_base = window.location;
        var flag_menu = 1;
        var flag_boton = 1;
        var flag_sbar = 1;
        var fewSeconds = 5;
        var des_flag = false;

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


        const defaultOptions = {
            title: {
                text: 'Reporte Establecimiento Básica (<?php echo $totalParticipantesBasica ?>)'
            },

            chart: {
                type: 'scatter'
            },
            subtitle: {
                text: ''
            },
            credits: {
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

        const labels = function(chart) {
            chart.renderer.label('Alto compromiso escolar y Factores contextuales', window.value - 200, 40)
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
            chart.renderer.label('Alto compromiso escolar y bajos Factores contextuales', 70, 40)
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
            chart.renderer.label('Bajo compromiso escolar y bajos Factores contextuales', 70, 390)
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
            chart.renderer.label('Bajo compromiso escolar y Altos Factores contextuales', window.value - 200, 390)
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

        function dispersionEstablecimientoBasicaInit() {
            window.value = document.getElementById('dispersion-establecimiento-basica').offsetWidth;
            const options = {
                ...defaultOptions,

                title: {
                    text: null
                },

                series: [{
                    data: <?php echo json_encode($cursos_basica) ?>
                }]
            }

            Highcharts.chart('dispersion-establecimiento-basica', options, labels);
        }

        function dispersionEstablecimientoMediaInit() {
            window.value = document.getElementById('dispersion-establecimiento-media').offsetWidth;
            const options = {
                ...defaultOptions,
                title: {
                    text: 'Reporte de establecimiento Media'
                },

                series: [{
                    data: <?php echo json_encode($cursos_media) ?>
                }]
            }

            Highcharts.chart('dispersion-establecimiento-media', options, labels);
        }

        document.addEventListener('DOMContentLoaded', () => {
            dispersionEstablecimientoBasicaInit()
            dispersionEstablecimientoMediaInit()
        })



        $("#id_graficos_p").ready(function() {
            var basica = parseInt("<?php echo $totalParticipantesBasica; ?>");
            var media = parseInt("<?php echo $totalParticipantesMedia; ?>");

            if (basica == 0 && media == 0) {
                $("#id_error").removeAttr("hidden");
                $("#id_graficos_m").attr("hidden", true);
                $("#id_graficos_b").attr("hidden", true);
            } else if (basica == 0 && media != 0) {
                $("#id_error").attr("hidden", true);
                $("#id_graficos_m").removeAttr("hidden");
                $("#id_graficos_b").attr("hidden", true);

                $(window).resize(function() {
                    dispersionEstablecimientoMediaInit();
                });

                $(".sidebar-open").on("mresize", function() {
                    dispersionEstablecimientoMediaInit();
                });
            } else if (basica != 0 && media == 0) {
                $("#id_error").attr("hidden", true);
                $("#id_graficos_m").attr("hidden");
                $("#id_graficos_b").removeAttr("hidden", true);

                $(window).resize(function() {
                    dispersionEstablecimientoBasicaInit();
                });

                $(".sidebar-open").on("mresize", function() {
                    alert("dfs");
                    dispersionEstablecimientoBasicaInit();
                });

                $(".btn_side").click(function() {
                    dispersionEstablecimientoBasicaInit();
                });
            } else if (basica != 0 && media != 0) {
                $("#id_error").attr("hidden", true);
                $("#id_graficos_m").removeAttr("hidden", true);
                $("#id_graficos_b").removeAttr("hidden", true);

                $(window).resize(function() {
                    dispersionEstablecimientoBasicaInit();
                });


                $(window).resize(function() {
                    dispersionEstablecimientoMediaInit();
                });

            }
        });





        $(document).ready(function() {
            login_admin();


            $("body").css('padding', '0');
            /////////// cerrar modal ///////////////////////
            var modal = document.getElementById("id_ingre_cod");
            var span = document.getElementsByClassName("close")[0];

            $("#select_curso").click(function() {
                $("#estudiantes").hide();
                $("#curso_p").show();
            });



            $("#select_estudiante").click(function() {
                $("#estudiantes").show();
                $("#curso_p").hide();
            });

            $("#bt_manual").click(function() {
                window.open(
                    url_base.protocol + "//" +
                    url_base.host + "/" +
                    "documentos/Manual_de_Usuario_Plataforma_Compromiso_Escolar_11-09-2019.pdf",
                    '_blank'
                );
            });

            $("#bt_admin").click(function() {
                $('#id_ingre_cod').modal('toggle');

            });

            $("#btn_cerrar_modal").click(function() {
                $('#id_ingre_cod').modal('toggle');

            });

            $(".btn_side").click(function() {
                flag_sbar *= -1;
                if (flag_sbar != 1) {
                    console.log("holaaa");
                    $(".main-sidebar").css({
                        "-webkit-transform": "translate(-230px, 0)"
                    });
                    $(".main-sidebar").css({
                        "-ms-transform": "translate(-230px, 0)"
                    });
                    $(".main-sidebar").css({
                        "-o-transform": "translate(-230px, 0)"
                    });
                    $(".main-sidebar").css({
                        "transform": "translate(-230px, 0)"
                    });
                    $(".content-wrapper, .main-footer").css("margin-left", "0");

                } else {
                    $(".main-sidebar").css({
                        "-webkit-transform": "translate(0, 0)"
                    });
                    $(".main-sidebar").css({
                        "-ms-transform": "translate(0, 0)"
                    });
                    $(".main-sidebar").css({
                        "-o-transform": "translate(0, 0)"
                    });
                    $(".main-sidebar").css({
                        "transform": "translate(0, 0)"
                    });
                    $(".content-wrapper, .main-footer").css("margin-left", "230px");
                }
                setTimeout(function() {
                        dispersionEstablecimientoBasicaInit();
                        dispersionEstablecimientoMediaInit();
                    },
                    300
                );

            });

            $("#id_estab").click(function() {
                flag_menu *= -1;
                if (flag_menu != 1) {
                    $(".skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.treeview > a").css("background", "#f27611");
                } else {
                    $(".skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.treeview > a").css("background", "#f4af1f");
                }
            });

            $(".id_recur_edu").click(function() {
                window.location.replace(
                    url_base.protocol + "//" +
                    url_base.host + "/" +
                    "recursos_edu.php"
                );
            });

            $("#img_btn_salir").click(function() {
                window.location.href = 
                    url_base.protocol + "//" +
                    url_base.host + "/" +
                    "salir.php?csrf=<?php echo $_SESSION["token"]; ?>";
            });

            $('#select_establecimiento').on('click', function() {
                $('#sel_curso').val(-1);
                $('#sel_alumno').val(-1);
                $('#curso-seleccionado').hide();
                $('#content-main').hide();
                $('#content-establecimiento').show();
                $('#content-cursos').hide();
                $('#alumno-seleccionado').hide();
                $('.treeview.active').not('.menu-open').removeClass('active selected-active');
                $('#select_establecimiento').parent().addClass('active selected-active');

                $('#select_establecimiento').parent().parent().parent().addClass('active');
                $('#content-alumnos').hide();

                $('#select_alumnos').parent().removeClass('active selected-active');
                $('#select_cursos').parent().removeClass('active selected-active');
            });

            $('#select_cursos').on('click', function() {
                $('#sel_curso').val(-1);
                $('#curso-seleccionado').hide();
                $('#content-main').hide();
                $('#content-establecimiento').hide();
                $('#content-alumnos').hide();

                $('#alumno-seleccionado').hide();

                $('#content-cursos').show();
                $('.treeview.active').not('.menu-open').removeClass('active selected-active');
                $('#select_cursos').parent().addClass('active selected-active');

                $('#select_cursos').parent().parent().parent().addClass('active');

                $('#select_alumnos').parent().removeClass('active selected-active');

                $('#select_establecimiento').parent().removeClass('active selected-active');
            });

            $('#select_alumnos').on('click', function() {
                $('#sel_curso').val(-1);
                $('#sel_alumno').val(-1);
                $('#curso-seleccionado').hide();
                $('#content-main').hide();
                $('#content-establecimiento').hide();
                $('#content-cursos').hide();

                $('#content-alumnos').show();

                $('.treeview.active').not('.menu-open').removeClass('active selected-active');
                $('#select_alumnos').parent().addClass('active selected-active');

                $('#select_alumnos').parent().parent().parent().addClass('active');

                $('#select_cursos').parent().removeClass('active selected-active');

                $('#select_establecimiento').parent().removeClass('active selected-active');
            });

            $('#select_dispersion').on('click', function() {
                $('#sel_alumno').val(-1);
                $('#sel_curso').val(-1);
                $('#curso-seleccionado').hide();
                $('#content-main').show();
                $('#content-establecimiento').hide();

                $('#content-alumnos').hide();
                $('#alumno-seleccionado').hide();

                $('#content-cursos').hide();

                $('.treeview.active').not('.menu-open').removeClass('active selected-active');
                $('#select_dispersion').parent().addClass('active');

                $('#select_establecimiento').parent().parent().parent().removeClass('active selected-active');

                $('#select_establecimiento').parent().removeClass('active selected-active');

                $('#select_alumnos').parent().parent().parent().removeClass('active selected-active');

                $('#select_alumnos').parent().removeClass('active selected-active');

                $('#select_cursos').parent().parent().parent().removeClass('active selected-active');

                $('#select_cursos').parent().removeClass('active selected-active');

                $(this).parent().addClass('selected-active');
            });

            $('#sel_curso').on('change', function() {
                if ($('#sel_curso').val() != -1) {
                    url_base_2 = url_base.protocol + "//" + url_base.host;
                    dir = url_base_2 + "/php/get_informes_longitudinales.php";
                    cadena = "method=get_info_curso&curso=" + $('#sel_curso').val();
                    $.ajax({
                        type: "POST",
                        url: dir,
                        data: cadena,
                        dataType: 'json',
                        statusCode: {
                            404: function() {
                                alertify.alert("Alerta", "Pagina no Encontrada");
                            },
                            502: function() {
                                alertify.alert("alerta", "Ha ocurrido un error al conectarse con el servidor");
                            }
                        },
                        success: function(r) {
                            if (r[0].length > 0) {
                                var tabla_inicio = '<table class="table table-striped hide"><thead> ' +
                                    '<tr>' +
                                    '<th scope="col"></th>' +
                                    '<th scope="row" style="text-align:right">Compromiso escolar</th>' +
                                    '<th scope="row" style="text-align:right">Factores contextuales</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody>';
                                var aux = '';
                                for (var i = 0; i < r[0].length; i++) {
                                    aux += '<tr>' +
                                        '<td><span style="font-weight:bold">' + r[0][i]["ce_anio_curso"] + '</span></td>' +
                                        '<td align="right">' + parseFloat((r[0][i]["CE"])).toFixed() + '</td>' +
                                        '<td align="right">' + parseFloat((r[0][i]["FC"])).toFixed() + '</td></tr>';
                                }
                                var tabla_fin = '</tbody></table>';

                                var tabla_ce_fc_curso = tabla_inicio + aux + tabla_fin;

                                $('#tabla_ce_fc_curso')[0].innerHTML = tabla_ce_fc_curso;

                                tabla_inicio = '<table class="table table-striped hide"><thead> ' +
                                    '<tr>' +
                                    '<th scope="col"></th>' +
                                    '<th scope="row" style="text-align:right">Afectivo</th>' +
                                    '<th scope="row" style="text-align:right">Conductual</th>' +
                                    '<th scope="row" style="text-align:right">Cognitivo</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody>';
                                aux = '';

                                for (var i = 0; i < r[0].length; i++) {
                                    aux += '<tr>' +
                                        '<td><span style="font-weight:bold">' + r[0][i]["ce_anio_curso"] + '</span></td>' +
                                        '<td align="right">' + parseFloat((r[0][i]["Afectivo"])).toFixed() + '</td>' +
                                        '<td align="right">' + parseFloat((r[0][i]["Conductual"])).toFixed() + '</td>' +
                                        '<td align="right">' + parseFloat((r[0][i]["Cognitivo"])).toFixed() + '</td></tr>';
                                }

                                var tabla_fin = '</tbody></table>';
                                var tabla_dim1_curso = tabla_inicio + aux + tabla_fin;

                                $('#tabla_dim1_curso')[0].innerHTML = tabla_dim1_curso;

                                tabla_inicio = '<table class="table table-striped hide"><thead> ' +
                                    '<tr>' +
                                    '<th scope="col"></th>' +
                                    '<th scope="row" style="text-align:right">Familia</th>' +
                                    '<th scope="row" style="text-align:right">Pares</th>' +
                                    '<th scope="row" style="text-align:right">Profesores</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody>';
                                aux = '';

                                for (var i = 0; i < r[0].length; i++) {
                                    aux += '<tr>' +
                                        '<td><span style="font-weight:bold">' + r[0][i]["ce_anio_curso"] + '</span></td>' +
                                        '<td align="right">' + parseFloat((r[0][i]["Familia"])).toFixed() + '</td>' +
                                        '<td align="right">' + parseFloat((r[0][i]["Pares"])).toFixed() + '</td>' +
                                        '<td align="right">' + parseFloat((r[0][i]["Profesores"])).toFixed() + '</td></tr>';
                                }

                                var tabla_fin = '</tbody></table>';

                                var tabla_dim2_curso = tabla_inicio + aux + tabla_fin;

                                $('#tabla_dim2_curso')[0].innerHTML = tabla_dim2_curso;

                                $('#desc_curso')[0].innerHTML = r[0][0]['nombre'];
                                generaGraficoCeFcCursos();
                                generaGraficoDim1Cursos();
                                generaGraficoDim2Cursos();
                                $('#curso-seleccionado').show();
                            } else {
                                document.getElementById("ingresar_admin").disabled = false;
                                document.getElementById("spinner").innerHTML = '';
                                document.getElementById("inicia_rep").innerHTML = 'Ingresar';
                                alertify.defaults.glossary.title = '<p class="text-center">Notificación<p>';
                                alertify.alert('Se ha producido un error inesperado');
                            }
                            var tabla_inicio = '<table class="table table-striped"><thead> ' +
                                '<col>' +
                                '<colgroup span="2"></colgroup>' +
                                '<colgroup span="2"></colgroup>' +
                                '<tr>' +
                                '<th colspan="1" scope="colgroup" style="border-top:none !important;background-color:#ecf0f5;"></th>' +
                                '<th colspan="2" scope="colgroup" style="border-top:none !important;background-color:#ecf0f5; text-align:center; min-width:180px"><span style="margin-right:10px">Promedio del curso</span> <i class="fa fa-question-circle" style="color:#2d6693; font-size: 28px" aria-hidden="true" onclick="referencia_tabla_simbologia()"></i></th>' +
                                '<th scope="col" style="border-top:none !important;text-align:right; background-color:#ecf0f5;"></th>' +
                                '</tr>' +
                                '<tr>' +
                                '<th></th>' +
                                '<th scope="col" style="text-align:center">' + r[2][0].ce_anio_contestada + '</th>' +
                                '<th scope="col" style="text-align:center">' + r[1][0].ce_anio_contestada + '</th>' +
                                '<th scope="col">Tendencia</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody>';
                            var aux = '';
                            var d = new Date();
                            var currentYear = d.getFullYear();
                            var previousYear = d.getFullYear() - 1;
                            var icon1 = '';
                            if (Math.round((r[1][0]["ce_p1"])) - Math.round((r[2][0]["ce_p1"])) == 0) {
                                icon1 = '0';
                            } else if (Math.round((r[1][0]["ce_p1"])) - Math.round((r[2][0]["ce_p1"])) < 0) {
                                icon1 = '&#8595; ' + (Math.round((r[1][0]["ce_p1"])) - Math.round((r[2][0]["ce_p1"]))).toString();
                            } else {
                                icon1 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p1"])) - Math.round((r[2][0]["ce_p1"]))).toString() + '</span>';
                            }
                            var icon2 = '';
                            if (Math.round((r[1][0]["ce_p2"])) - Math.round((r[2][0]["ce_p2"])) == 0) {
                                icon2 = '0';
                            } else if (Math.round((r[1][0]["ce_p2"])) - Math.round((r[2][0]["ce_p2"])) < 0) {
                                icon2 = '&#8595; ' + (Math.round((r[1][0]["ce_p2"])) - Math.round((r[2][0]["ce_p2"]))).toString();
                            } else {
                                icon2 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p2"])) - Math.round((r[2][0]["ce_p2"]))).toString();
                            }
                            var icon3 = '';
                            if (Math.round((r[1][0]["ce_p3"])) - Math.round((r[2][0]["ce_p3"])) == 0) {
                                icon3 = '0';
                            } else if (Math.round((r[1][0]["ce_p3"])) - Math.round((r[2][0]["ce_p3"])) < 0) {
                                icon3 = '&#8595; ' + (Math.round((r[1][0]["ce_p3"])) - Math.round((r[2][0]["ce_p3"]))).toString();
                            } else {
                                icon3 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p3"])) - Math.round((r[2][0]["ce_p3"]))).toString();
                            }
                            var icon4 = '';
                            if (Math.round((r[1][0]["ce_p4"])) - Math.round((r[2][0]["ce_p4"])) == 0) {
                                icon4 = '0';
                            } else if (Math.round((r[1][0]["ce_p4"])) - Math.round((r[2][0]["ce_p4"])) < 0) {
                                icon4 = '&#8595; ' + (Math.round((r[1][0]["ce_p4"])) - Math.round((r[2][0]["ce_p4"]))).toString();
                            } else {
                                icon4 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p4"])) - Math.round((r[2][0]["ce_p4"]))).toString();
                            }
                            var icon5 = '';
                            if (Math.round((r[1][0]["ce_p5"])) - Math.round((r[2][0]["ce_p5"])) == 0) {
                                icon5 = '0';
                            } else if (Math.round((r[1][0]["ce_p5"])) - Math.round((r[2][0]["ce_p5"])) < 0) {
                                icon5 = '&#8595; ' + (Math.round((r[1][0]["ce_p5"])) - Math.round((r[2][0]["ce_p5"]))).toString();
                            } else {
                                icon5 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p5"])) - Math.round((r[2][0]["ce_p5"]))).toString();
                            }
                            var icon6 = '';
                            if (Math.round((r[1][0]["ce_p6"])) - Math.round((r[2][0]["ce_p6"])) == 0) {
                                icon6 = '0';
                            } else if (Math.round((r[1][0]["ce_p6"])) - Math.round((r[2][0]["ce_p6"])) < 0) {
                                icon6 = '&#8595; ' + (Math.round((r[1][0]["ce_p6"])) - Math.round((r[2][0]["ce_p6"]))).toString();
                            } else {
                                icon6 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p6"])) - Math.round((r[2][0]["ce_p6"]))).toString();
                            }
                            var icon7 = '';
                            if (Math.round((r[1][0]["ce_p7"])) - Math.round((r[2][0]["ce_p7"])) == 0) {
                                icon7 = '0';
                            } else if (Math.round((r[1][0]["ce_p7"])) - Math.round((r[2][0]["ce_p7"])) < 0) {
                                icon7 = '&#8595; ' + (Math.round((r[1][0]["ce_p7"])) - Math.round((r[2][0]["ce_p7"]))).toString();
                            } else {
                                icon7 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p7"])) - Math.round((r[2][0]["ce_p7"]))).toString();
                            }
                            var icon8 = '';
                            if (Math.round((r[1][0]["ce_p8"])) - Math.round((r[2][0]["ce_p8"])) == 0) {
                                icon8 = '0';
                            } else if (Math.round((r[1][0]["ce_p8"])) - Math.round((r[2][0]["ce_p8"])) < 0) {
                                icon8 = '&#8595; ' + (Math.round((r[1][0]["ce_p8"])) - Math.round((r[2][0]["ce_p8"]))).toString();
                            } else {
                                icon8 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p8"])) - Math.round((r[2][0]["ce_p8"]))).toString();
                            }
                            var icon9 = '';
                            if (Math.round((r[1][0]["ce_p9"])) - Math.round((r[2][0]["ce_p9"])) == 0) {
                                icon9 = '0';
                            } else if (Math.round((r[1][0]["ce_p9"])) - Math.round((r[2][0]["ce_p9"])) < 0) {
                                icon9 = '&#8595; ' + (Math.round((r[1][0]["ce_p9"])) - Math.round((r[2][0]["ce_p9"]))).toString();
                            } else {
                                icon9 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p9"])) - Math.round((r[2][0]["ce_p9"]))).toString();
                            }
                            var icon10 = '';
                            if (Math.round((r[1][0]["ce_p10"])) - Math.round((r[2][0]["ce_p10"])) == 0) {
                                icon10 = '0';
                            } else if (Math.round((r[1][0]["ce_p10"])) - Math.round((r[2][0]["ce_p10"])) < 0) {
                                icon10 = '&#8595; ' + (Math.round((r[1][0]["ce_p10"])) - Math.round((r[2][0]["ce_p10"]))).toString();
                            } else {
                                icon10 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p10"])) - Math.round((r[2][0]["ce_p10"]))).toString();
                            }
                            var icon11 = '';
                            if (Math.round((r[1][0]["ce_p11"])) - Math.round((r[2][0]["ce_p11"])) == 0) {
                                icon11 = '0';
                            } else if (Math.round((r[1][0]["ce_p11"])) - Math.round((r[2][0]["ce_p11"])) < 0) {
                                icon11 = '&#8595; ' + (Math.round((r[1][0]["ce_p11"])) - Math.round((r[2][0]["ce_p11"]))).toString();
                            } else {
                                icon11 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p11"])) - Math.round((r[2][0]["ce_p11"]))).toString();
                            }
                            var icon12 = '';
                            if (Math.round((r[1][0]["ce_p12"])) - Math.round((r[2][0]["ce_p12"])) == 0) {
                                icon12 = '0';
                            } else if (Math.round((r[1][0]["ce_p12"])) - Math.round((r[2][0]["ce_p12"])) < 0) {
                                icon12 = '&#8595; ' + (Math.round((r[1][0]["ce_p12"])) - Math.round((r[2][0]["ce_p12"]))).toString();
                            } else {
                                icon12 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p12"])) - Math.round((r[2][0]["ce_p12"]))).toString();
                            }
                            var icon13 = '';
                            if (Math.round((r[1][0]["ce_p13"])) - Math.round((r[2][0]["ce_p13"])) == 0) {
                                icon13 = '0';
                            } else if (Math.round((r[1][0]["ce_p13"])) - Math.round((r[2][0]["ce_p13"])) < 0) {
                                icon13 = '&#8595; ' + (Math.round((r[1][0]["ce_p13"])) - Math.round((r[2][0]["ce_p13"]))).toString();
                            } else {
                                icon13 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p13"])) - Math.round((r[2][0]["ce_p13"]))).toString();
                            }
                            var icon14 = '';
                            if (Math.round((r[1][0]["ce_p14"])) - Math.round((r[2][0]["ce_p14"])) == 0) {
                                icon14 = '0';
                            } else if (Math.round((r[1][0]["ce_p14"])) - Math.round((r[2][0]["ce_p14"])) < 0) {
                                icon14 = '&#8595; ' + (Math.round((r[1][0]["ce_p14"])) - Math.round((r[2][0]["ce_p14"]))).toString();
                            } else {
                                icon14 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p14"])) - Math.round((r[2][0]["ce_p14"]))).toString();
                            }
                            var icon15 = '';
                            if (Math.round((r[1][0]["ce_p15"])) - Math.round((r[2][0]["ce_p15"])) == 0) {
                                icon15 = '0';
                            } else if (Math.round((r[1][0]["ce_p15"])) - Math.round((r[2][0]["ce_p15"])) < 0) {
                                icon15 = '&#8595; ' + (Math.round((r[1][0]["ce_p15"])) - Math.round((r[2][0]["ce_p15"]))).toString();
                            } else {
                                icon15 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p15"])) - Math.round((r[2][0]["ce_p15"]))).toString();
                            }
                            var icon16 = '';
                            if (Math.round((r[1][0]["ce_p16"])) - Math.round((r[2][0]["ce_p16"])) == 0) {
                                icon16 = '0';
                            } else if (Math.round((r[1][0]["ce_p16"])) - Math.round((r[2][0]["ce_p16"])) < 0) {
                                icon16 = '&#8595; ' + (Math.round((r[1][0]["ce_p16"])) - Math.round((r[2][0]["ce_p16"]))).toString();
                            } else {
                                icon16 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p16"])) - Math.round((r[2][0]["ce_p16"]))).toString();
                            }
                            var icon17 = '';
                            if (Math.round((r[1][0]["ce_p17"])) - Math.round((r[2][0]["ce_p17"])) == 0) {
                                icon17 = '0';
                            } else if (Math.round((r[1][0]["ce_p17"])) - Math.round((r[2][0]["ce_p17"])) < 0) {
                                icon17 = '&#8595; ' + (Math.round((r[1][0]["ce_p17"])) - Math.round((r[2][0]["ce_p17"]))).toString();
                            } else {
                                icon17 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p17"])) - Math.round((r[2][0]["ce_p17"]))).toString();
                            }
                            var icon18 = '';
                            if (Math.round((r[1][0]["ce_p18"])) - Math.round((r[2][0]["ce_p18"])) == 0) {
                                icon18 = '0';
                            } else if (Math.round((r[1][0]["ce_p18"])) - Math.round((r[2][0]["ce_p18"])) < 0) {
                                icon18 = '&#8595; ' + (Math.round((r[1][0]["ce_p18"])) - Math.round((r[2][0]["ce_p18"]))).toString();
                            } else {
                                icon18 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p18"])) - Math.round((r[2][0]["ce_p18"]))).toString();
                            }
                            var icon19 = '';
                            if (Math.round((r[1][0]["ce_p19"])) - Math.round((r[2][0]["ce_p19"])) == 0) {
                                icon19 = '0';
                            } else if (Math.round((r[1][0]["ce_p19"])) - Math.round((r[2][0]["ce_p19"])) < 0) {
                                icon19 = '&#8595; ' + (Math.round((r[1][0]["ce_p19"])) - Math.round((r[2][0]["ce_p19"]))).toString();
                            } else {
                                icon19 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p19"])) - Math.round((r[2][0]["ce_p19"]))).toString();
                            }
                            var icon20 = '';
                            if (Math.round((r[1][0]["ce_p20"])) - Math.round((r[2][0]["ce_p20"])) == 0) {
                                icon20 = '0';
                            } else if (Math.round((r[1][0]["ce_p20"])) - Math.round((r[2][0]["ce_p20"])) < 0) {
                                icon20 = '&#8595; ' + (Math.round((r[1][0]["ce_p20"])) - Math.round((r[2][0]["ce_p20"]))).toString();
                            } else {
                                icon20 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p20"])) - Math.round((r[2][0]["ce_p20"]))).toString();
                            }
                            var icon21 = '';
                            if (Math.round((r[1][0]["ce_p21"])) - Math.round((r[2][0]["ce_p21"])) == 0) {
                                icon21 = '0';
                            } else if (Math.round((r[1][0]["ce_p21"])) - Math.round((r[2][0]["ce_p21"])) < 0) {
                                icon21 = '&#8595; ' + (Math.round((r[1][0]["ce_p21"])) - Math.round((r[2][0]["ce_p21"]))).toString();
                            } else {
                                icon21 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p21"])) - Math.round((r[2][0]["ce_p21"]))).toString();
                            }
                            var icon22 = '';
                            if (Math.round((r[1][0]["ce_p22"])) - Math.round((r[2][0]["ce_p22"])) == 0) {
                                icon22 = '0';
                            } else if (Math.round((r[1][0]["ce_p22"])) - Math.round((r[2][0]["ce_p22"])) < 0) {
                                icon22 = '&#8595; ' + (Math.round((r[1][0]["ce_p22"])) - Math.round((r[2][0]["ce_p22"]))).toString();
                            } else {
                                icon22 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p22"])) - Math.round((r[2][0]["ce_p22"]))).toString();
                            }
                            var icon23 = '';
                            if (Math.round((r[1][0]["ce_p23"])) - Math.round((r[2][0]["ce_p23"])) == 0) {
                                icon23 = '0';
                            } else if (Math.round((r[1][0]["ce_p23"])) - Math.round((r[2][0]["ce_p23"])) < 0) {
                                icon23 = '&#8595; ' + (Math.round((r[1][0]["ce_p23"])) - Math.round((r[2][0]["ce_p23"]))).toString();
                            } else {
                                icon23 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p23"])) - Math.round((r[2][0]["ce_p23"]))).toString();
                            }
                            var icon24 = '';
                            if (Math.round((r[1][0]["ce_p24"])) - Math.round((r[2][0]["ce_p24"])) == 0) {
                                icon24 = '0';
                            } else if (Math.round((r[1][0]["ce_p24"])) - Math.round((r[2][0]["ce_p24"])) < 0) {
                                icon24 = '&#8595; ' + (Math.round((r[1][0]["ce_p24"])) - Math.round((r[2][0]["ce_p24"]))).toString();
                            } else {
                                icon24 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p24"])) - Math.round((r[2][0]["ce_p24"]))).toString();
                            }
                            var icon25 = '';
                            if (Math.round((r[1][0]["ce_p25"])) - Math.round((r[2][0]["ce_p25"])) == 0) {
                                icon25 = '0';
                            } else if (Math.round((r[1][0]["ce_p25"])) - Math.round((r[2][0]["ce_p25"])) < 0) {
                                icon25 = '&#8595; ' + (Math.round((r[1][0]["ce_p25"])) - Math.round((r[2][0]["ce_p25"]))).toString();
                            } else {
                                icon25 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p25"])) - Math.round((r[2][0]["ce_p25"]))).toString();
                            }
                            var icon26 = '';
                            if (Math.round((r[1][0]["ce_p26"])) - Math.round((r[2][0]["ce_p26"])) == 0) {
                                icon26 = '0';
                            } else if (Math.round((r[1][0]["ce_p26"])) - Math.round((r[2][0]["ce_p26"])) < 0) {
                                icon26 = '&#8595; ' + (Math.round((r[1][0]["ce_p26"])) - Math.round((r[2][0]["ce_p26"]))).toString();
                            } else {
                                icon26 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p26"])) - Math.round((r[2][0]["ce_p26"]))).toString();
                            }
                            var icon27 = '';
                            if (Math.round((r[1][0]["ce_p27"])) - Math.round((r[2][0]["ce_p27"])) == 0) {
                                icon27 = '0';
                            } else if (Math.round((r[1][0]["ce_p27"])) - Math.round((r[2][0]["ce_p27"])) < 0) {
                                icon27 = '&#8595; ' + (Math.round((r[1][0]["ce_p27"])) - Math.round((r[2][0]["ce_p27"]))).toString();
                            } else {
                                icon27 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p27"])) - Math.round((r[2][0]["ce_p27"]))).toString();
                            }
                            var icon28 = '';
                            if (Math.round((r[1][0]["ce_p28"])) - Math.round((r[2][0]["ce_p28"])) == 0) {
                                icon28 = '0';
                            } else if (Math.round((r[1][0]["ce_p28"])) - Math.round((r[2][0]["ce_p28"])) < 0) {
                                icon28 = '&#8595; ' + (Math.round((r[1][0]["ce_p28"])) - Math.round((r[2][0]["ce_p28"]))).toString();
                            } else {
                                icon28 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p28"])) - Math.round((r[2][0]["ce_p28"]))).toString();
                            }
                            var icon29 = '';
                            if (Math.round((r[1][0]["ce_p29"])) - Math.round((r[2][0]["ce_p29"])) == 0) {
                                icon29 = '0';
                            } else if (Math.round((r[1][0]["ce_p29"])) - Math.round((r[2][0]["ce_p29"])) < 0) {
                                icon29 = '&#8595; ' + (Math.round((r[1][0]["ce_p29"])) - Math.round((r[2][0]["ce_p29"]))).toString();
                            } else {
                                icon29 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p29"])) - Math.round((r[2][0]["ce_p29"]))).toString();
                            }
                            var icon30 = '';
                            if (Math.round((r[1][0]["ce_p30"])) - Math.round((r[2][0]["ce_p30"])) == 0) {
                                icon30 = '0';
                            } else if (Math.round((r[1][0]["ce_p30"])) - Math.round((r[2][0]["ce_p30"])) < 0) {
                                icon30 = '&#8595; ' + (Math.round((r[1][0]["ce_p30"])) - Math.round((r[2][0]["ce_p30"]))).toString();
                            } else {
                                icon30 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p30"])) - Math.round((r[2][0]["ce_p30"]))).toString();
                            }
                            var icon31 = '';
                            if (Math.round((r[1][0]["ce_p31"])) - Math.round((r[2][0]["ce_p31"])) == 0) {
                                icon31 = '0';
                            } else if (Math.round((r[1][0]["ce_p31"])) - Math.round((r[2][0]["ce_p31"])) < 0) {
                                icon31 = '&#8595; ' + (Math.round((r[1][0]["ce_p31"])) - Math.round((r[2][0]["ce_p31"]))).toString();
                            } else {
                                icon31 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p31"])) - Math.round((r[2][0]["ce_p31"]))).toString();
                            }
                            var icon32 = '';
                            if (Math.round((r[1][0]["ce_p32"])) - Math.round((r[2][0]["ce_p32"])) == 0) {
                                icon32 = '0';
                            } else if (Math.round((r[1][0]["ce_p32"])) - Math.round((r[2][0]["ce_p32"])) < 0) {
                                icon32 = '&#8595; ' + (Math.round((r[1][0]["ce_p32"])) - Math.round((r[2][0]["ce_p32"]))).toString();
                            } else {
                                icon32 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p32"])) - Math.round((r[2][0]["ce_p32"]))).toString();
                            }
                            var icon33 = '';
                            if (Math.round((r[1][0]["ce_p33"])) - Math.round((r[2][0]["ce_p33"])) == 0) {
                                icon33 = '0';
                            } else if (Math.round((r[1][0]["ce_p33"])) - Math.round((r[2][0]["ce_p33"])) < 0) {
                                icon33 = '&#8595; ' + (Math.round((r[1][0]["ce_p33"])) - Math.round((r[2][0]["ce_p33"]))).toString();
                            } else {
                                icon33 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p33"])) - Math.round((r[2][0]["ce_p33"]))).toString();
                            }
                            var icon34 = '';
                            if (Math.round((r[1][0]["ce_p34"])) - Math.round((r[2][0]["ce_p34"])) == 0) {
                                icon34 = '0';
                            } else if (Math.round((r[1][0]["ce_p34"])) - Math.round((r[2][0]["ce_p34"])) < 0) {
                                icon34 = '&#8595; ' + (Math.round((r[1][0]["ce_p34"])) - Math.round((r[2][0]["ce_p34"]))).toString();
                            } else {
                                icon34 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p34"])) - Math.round((r[2][0]["ce_p34"]))).toString();
                            }
                            var icon35 = '';
                            if (Math.round((r[1][0]["ce_p35"])) - Math.round((r[2][0]["ce_p35"])) == 0) {
                                icon35 = '0';
                            } else if (Math.round((r[1][0]["ce_p35"])) - Math.round((r[2][0]["ce_p35"])) < 0) {
                                icon35 = '&#8595; ' + (Math.round((r[1][0]["ce_p35"])) - Math.round((r[2][0]["ce_p35"]))).toString();
                            } else {
                                icon35 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p35"])) - Math.round((r[2][0]["ce_p35"]))).toString();
                            }
                            var icon36 = '';
                            if (Math.round((r[1][0]["ce_p36"])) - Math.round((r[2][0]["ce_p36"])) == 0) {
                                icon36 = '0';
                            } else if (Math.round((r[1][0]["ce_p36"])) - Math.round((r[2][0]["ce_p36"])) < 0) {
                                icon36 = '&#8595; ' + (Math.round((r[1][0]["ce_p36"])) - Math.round((r[2][0]["ce_p36"]))).toString();
                            } else {
                                icon36 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p36"])) - Math.round((r[2][0]["ce_p36"]))).toString();
                            }
                            var icon37 = '';
                            if (Math.round((r[1][0]["ce_p37"])) - Math.round((r[2][0]["ce_p37"])) == 0) {
                                icon37 = '0';
                            } else if (Math.round((r[1][0]["ce_p37"])) - Math.round((r[2][0]["ce_p37"])) < 0) {
                                icon37 = '&#8595; ' + (Math.round((r[1][0]["ce_p37"])) - Math.round((r[2][0]["ce_p37"]))).toString();
                            } else {
                                icon37 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p37"])) - Math.round((r[2][0]["ce_p37"]))).toString();
                            }
                            var icon38 = '';
                            if (Math.round((r[1][0]["ce_p38"])) - Math.round((r[2][0]["ce_p38"])) == 0) {
                                icon38 = '0';
                            } else if (Math.round((r[1][0]["ce_p38"])) - Math.round((r[2][0]["ce_p38"])) < 0) {
                                icon38 = '&#8595; ' + (Math.round((r[1][0]["ce_p38"])) - Math.round((r[2][0]["ce_p38"]))).toString();
                            } else {
                                icon38 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p38"])) - Math.round((r[2][0]["ce_p38"]))).toString();
                            }
                            var icon39 = '';
                            if (Math.round((r[1][0]["ce_p39"])) - Math.round((r[2][0]["ce_p39"])) == 0) {
                                icon39 = '0';
                            } else if (Math.round((r[1][0]["ce_p39"])) - Math.round((r[2][0]["ce_p39"])) < 0) {
                                icon39 = '&#8595; ' + (Math.round((r[1][0]["ce_p39"])) - Math.round((r[2][0]["ce_p39"]))).toString();
                            } else {
                                icon39 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p39"])) - Math.round((r[2][0]["ce_p39"]))).toString();
                            }
                            var icon40 = '';
                            if (Math.round((r[1][0]["ce_p40"])) - Math.round((r[2][0]["ce_p40"])) == 0) {
                                icon40 = '0';
                            } else if (Math.round((r[1][0]["ce_p40"])) - Math.round((r[2][0]["ce_p40"])) < 0) {
                                icon40 = '&#8595; ' + (Math.round((r[1][0]["ce_p40"])) - Math.round((r[2][0]["ce_p40"]))).toString();
                            } else {
                                icon40 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p40"])) - Math.round((r[2][0]["ce_p40"]))).toString();
                            }
                            var icon41 = '';
                            if (Math.round((r[1][0]["ce_p41"])) - Math.round((r[2][0]["ce_p41"])) == 0) {
                                icon41 = '0';
                            } else if (Math.round((r[1][0]["ce_p41"])) - Math.round((r[2][0]["ce_p41"])) < 0) {
                                icon41 = '&#8595; ' + (Math.round((r[1][0]["ce_p41"])) - Math.round((r[2][0]["ce_p41"]))).toString();
                            } else {
                                icon41 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p41"])) - Math.round((r[2][0]["ce_p41"]))).toString();
                            }
                            var icon42 = '';
                            if (Math.round((r[1][0]["ce_p42"])) - Math.round((r[2][0]["ce_p42"])) == 0) {
                                icon42 = '0';
                            } else if (Math.round((r[1][0]["ce_p42"])) - Math.round((r[2][0]["ce_p42"])) < 0) {
                                icon42 = '&#8595; ' + (Math.round((r[1][0]["ce_p42"])) - Math.round((r[2][0]["ce_p42"]))).toString();
                            } else {
                                icon42 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p42"])) - Math.round((r[2][0]["ce_p42"]))).toString();
                            }
                            var icon43 = '';
                            if (Math.round((r[1][0]["ce_p43"])) - Math.round((r[2][0]["ce_p43"])) == 0) {
                                icon43 = '0';
                            } else if (Math.round((r[1][0]["ce_p43"])) - Math.round((r[2][0]["ce_p43"])) < 0) {
                                icon43 = '&#8595; ' + (Math.round((r[1][0]["ce_p43"])) - Math.round((r[2][0]["ce_p43"]))).toString();
                            } else {
                                icon43 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p43"])) - Math.round((r[2][0]["ce_p43"]))).toString();
                            }
                            var icon44 = '';
                            if (Math.round((r[1][0]["ce_p44"])) - Math.round((r[2][0]["ce_p44"])) == 0) {
                                icon44 = '0';
                            } else if (Math.round((r[1][0]["ce_p44"])) - Math.round((r[2][0]["ce_p44"])) < 0) {
                                icon44 = '&#8595; ' + (Math.round((r[1][0]["ce_p44"])) - Math.round((r[2][0]["ce_p44"]))).toString();
                            } else {
                                icon44 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p44"])) - Math.round((r[2][0]["ce_p44"]))).toString();
                            }
                            var icon45 = '';
                            if (Math.round((r[1][0]["ce_p45"])) - Math.round((r[2][0]["ce_p45"])) == 0) {
                                icon45 = '0';
                            } else if (Math.round((r[1][0]["ce_p45"])) - Math.round((r[2][0]["ce_p45"])) < 0) {
                                icon45 = '&#8595; ' + (Math.round((r[1][0]["ce_p45"])) - Math.round((r[2][0]["ce_p45"]))).toString();
                            } else {
                                icon45 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p45"])) - Math.round((r[2][0]["ce_p45"]))).toString();
                            }
                            var icon46 = '';
                            if (Math.round((r[1][0]["ce_p46"])) - Math.round((r[2][0]["ce_p46"])) == 0) {
                                icon46 = '0';
                            } else if (Math.round((r[1][0]["ce_p46"])) - Math.round((r[2][0]["ce_p46"])) < 0) {
                                icon46 = '&#8595; ' + (Math.round((r[1][0]["ce_p46"])) - Math.round((r[2][0]["ce_p46"]))).toString();
                            } else {
                                icon46 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p46"])) - Math.round((r[2][0]["ce_p46"]))).toString();
                            }
                            var icon47 = '';
                            if (Math.round((r[1][0]["ce_p47"])) - Math.round((r[2][0]["ce_p47"])) == 0) {
                                icon47 = '0';
                            } else if (Math.round((r[1][0]["ce_p47"])) - Math.round((r[2][0]["ce_p47"])) < 0) {
                                icon47 = '&#8595; ' + (Math.round((r[1][0]["ce_p47"])) - Math.round((r[2][0]["ce_p47"]))).toString();
                            } else {
                                icon47 = '<span class="text-success"> &#8593; +' + (Math.round((r[1][0]["ce_p47"])) - Math.round((r[2][0]["ce_p47"]))).toString();
                            }
                            aux += '<tr>' +
                                '<td>' + r[3][0][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p1"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p1"])) + '</td>' +
                                '<td align="right">' + icon1 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][1][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p2"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p2"])) + '</td>' +
                                '<td align="right">' + icon2 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][2][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p3"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p3"])) + '</td>' +
                                '<td align="right">' + icon3 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][3][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p4"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p4"])) + '</td>' +
                                '<td align="right">' + icon4 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][4][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p5"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p5"])) + '</td>' +
                                '<td align="right">' + icon5 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][5][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p6"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p6"])) + '</td>' +
                                '<td align="right">' + icon6 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][6][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p7"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p7"])) + '</td>' +
                                '<td align="right">' + icon7 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][7][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p8"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p8"])) + '</td>' +
                                '<td align="right">' + icon8 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][8][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p9"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p9"])) + '</td>' +
                                '<td align="right">' + icon9 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][9][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p10"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p10"])) + '</td>' +
                                '<td align="right">' + icon10 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][10][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p11"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p11"])) + '</td>' +
                                '<td align="right">' + icon11 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][11][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p12"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p12"])) + '</td>' +
                                '<td align="right">' + icon12 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][12][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p13"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p13"])) + '</td>' +
                                '<td align="right">' + icon13 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][13][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p14"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p14"])) + '</td>' +
                                '<td align="right">' + icon14 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][14][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p15"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p15"])) + '</td>' +
                                '<td align="right">' + icon15 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][15][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p16"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p16"])) + '</td>' +
                                '<td align="right">' + icon16 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][16][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p17"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p17"])) + '</td>' +
                                '<td align="right">' + icon17 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][17][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p18"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p18"])) + '</td>' +
                                '<td align="right">' + icon18 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][18][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p19"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p19"])) + '</td>' +
                                '<td align="right">' + icon19 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][19][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p20"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p20"])) + '</td>' +
                                '<td align="right">' + icon20 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][20][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p21"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p21"])) + '</td>' +
                                '<td align="right">' + icon21 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][21][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p22"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p22"])) + '</td>' +
                                '<td align="right">' + icon22 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][22][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p23"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p23"])) + '</td>' +
                                '<td align="right">' + icon23 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][23][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p24"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p24"])) + '</td>' +
                                '<td align="right">' + icon24 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][24][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p25"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p25"])) + '</td>' +
                                '<td align="right">' + icon25 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][25][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p26"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p26"])) + '</td>' +
                                '<td align="right">' + icon26 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][26][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p27"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p27"])) + '</td>' +
                                '<td align="right">' + icon27 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][27][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p28"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p28"])) + '</td>' +
                                '<td align="right">' + icon28 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][28][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p29"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p29"])) + '</td>' +
                                '<td align="right">' + icon29 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][29][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p30"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p30"])) + '</td>' +
                                '<td align="right">' + icon30 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][30][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p31"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p31"])) + '</td>' +
                                '<td align="right">' + icon31 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][31][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p32"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p32"])) + '</td>' +
                                '<td align="right">' + icon32 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][32][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p33"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p33"])) + '</td>' +
                                '<td align="right">' + icon33 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][33][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p34"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p34"])) + '</td>' +
                                '<td align="right">' + icon34 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][34][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p35"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p35"])) + '</td>' +
                                '<td align="right">' + icon35 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][35][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p36"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p36"])) + '</td>' +
                                '<td align="right">' + icon36 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][36][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p37"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p37"])) + '</td>' +
                                '<td align="right">' + icon37 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][37][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p38"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p38"])) + '</td>' +
                                '<td align="right">' + icon38 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][38][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p39"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p39"])) + '</td>' +
                                '<td align="right">' + icon39 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][39][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p40"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p40"])) + '</td>' +
                                '<td align="right">' + icon40 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][40][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p41"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p41"])) + '</td>' +
                                '<td align="right">' + icon41 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][41][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p42"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p42"])) + '</td>' +
                                '<td align="right">' + icon42 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][42][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p43"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p43"])) + '</td>' +
                                '<td align="right">' + icon43 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][43][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p44"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p44"])) + '</td>' +
                                '<td align="right">' + icon44 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][44][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p45"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p45"])) + '</td>' +
                                '<td align="right">' + icon45 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][45][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p46"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p46"])) + '</td>' +
                                '<td align="right">' + icon46 + '</td></tr>' +
                                '<tr>' +
                                '<td>' + r[3][46][0] + '</td>' +
                                '<td align="center">' + Math.round((r[2][0]["ce_p47"])) + '</td>' +
                                '<td align="center">' + Math.round((r[1][0]["ce_p47"])) + '</td>' +
                                '<td align="right">' + icon47 + '</td></tr>'

                            ;

                            var tabla_fin = '</tbody></table>';
                            var tabla_simbologia = tabla_inicio + aux + tabla_fin;
                            $('#tabla_simbologia')[0].innerHTML = tabla_simbologia;
                        }
                    });
                } else {
                    $('#curso-seleccionado').hide();
                }
            });

            $('#sel_alumno').on('change', function() {
                if ($('#sel_alumno').val() != -1 && $('#sel_alumno').val() != 0) {
                    url_base_2 = url_base.protocol + "//" + url_base.host;
                    dir = url_base_2 + "/php/get_informes_longitudinales.php";
                    cadena = "method=get_info_alumno&alumno=" + $('#sel_alumno').val();
                    $.ajax({
                        type: "POST",
                        url: dir,
                        data: cadena,
                        dataType: 'json',
                        statusCode: {
                            404: function() {
                                alertify.alert("Alerta", "Pagina no Encontrada");
                            },
                            502: function() {
                                alertify.alert("alerta", "Ha ocurrido un error al conectarse con el servidor");
                            }
                        },
                        success: function(r) {
                            if (r.length > 0) {
                                var tabla_inicio = '<table class="table table-striped hide"><thead> ' +
                                    '<tr>' +
                                    '<th scope="col"></th>' +
                                    '<th scope="row" style="text-align:right">Compromiso escolar</th>' +
                                    '<th scope="row" style="text-align:right">Factores contextuales</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody>';
                                var aux = '';
                                for (var i = 0; i < r.length; i++) {
                                    aux += '<tr>' +
                                        '<td><span style="font-weight:bold">' + r[i]["ce_anio_curso"] + '</span></td>' +
                                        '<td align="right">' + parseFloat((r[i]["CE"])).toFixed() + '</td>' +
                                        '<td align="right">' + parseFloat((r[i]["FC"])).toFixed() + '</td></tr>';
                                }
                                var tabla_fin = '</tbody></table>';

                                var tabla_ce_fc_curso = tabla_inicio + aux + tabla_fin;

                                $('#tabla_ce_fc_alumno')[0].innerHTML = tabla_ce_fc_curso;

                                tabla_inicio = '<table class="table table-striped hide"><thead> ' +
                                    '<tr>' +
                                    '<th scope="col"></th>' +
                                    '<th scope="row" style="text-align:right">Afectivo</th>' +
                                    '<th scope="row" style="text-align:right">Conductual</th>' +
                                    '<th scope="row" style="text-align:right">Cognitivo</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody>';
                                aux = '';

                                for (var i = 0; i < r.length; i++) {
                                    aux += '<tr>' +
                                        '<td><span style="font-weight:bold">' + r[i]["ce_anio_curso"] + '</span></td>' +
                                        '<td align="right">' + parseFloat((r[i]["Afectivo"])).toFixed() + '</td>' +
                                        '<td align="right">' + parseFloat((r[i]["Conductual"])).toFixed() + '</td>' +
                                        '<td align="right">' + parseFloat((r[i]["Cognitivo"])).toFixed() + '</td></tr>';
                                }

                                var tabla_fin = '</tbody></table>';
                                var tabla_dim1_curso = tabla_inicio + aux + tabla_fin;

                                $('#tabla_dim1_alumno')[0].innerHTML = tabla_dim1_curso;

                                tabla_inicio = '<table class="table table-striped hide"><thead> ' +
                                    '<tr>' +
                                    '<th scope="col"></th>' +
                                    '<th scope="row" style="text-align:right">Familia</th>' +
                                    '<th scope="row" style="text-align:right">Pares</th>' +
                                    '<th scope="row" style="text-align:right">Profesores</th>' +
                                    '</tr>' +
                                    '</thead>' +
                                    '<tbody>';
                                aux = '';

                                for (var i = 0; i < r.length; i++) {
                                    aux += '<tr>' +
                                        '<td><span style="font-weight:bold">' + r[i]["ce_anio_curso"] + '</span></td>' +
                                        '<td align="right">' + parseFloat((r[i]["Familia"])).toFixed() + '</td>' +
                                        '<td align="right">' + parseFloat((r[i]["Pares"])).toFixed() + '</td>' +
                                        '<td align="right">' + parseFloat((r[i]["Profesores"])).toFixed() + '</td></tr>';
                                }

                                var tabla_fin = '</tbody></table>';

                                var tabla_dim2_curso = tabla_inicio + aux + tabla_fin;

                                $('#tabla_dim2_alumno')[0].innerHTML = tabla_dim2_curso;

                                $('#desc_alumno')[0].innerHTML = r[0]['nombre_participante'];
                                $('#desc_alumno_curso')[0].innerHTML = r[0]['nombre'];

                                generaGraficoCeFcAlumno();
                                generaGraficoDim1Alumno();
                                generaGraficoDim2Alumno();
                                $('#alumno-seleccionado').show();
                            } else {
                                document.getElementById("ingresar_admin").disabled = false;
                                document.getElementById("spinner").innerHTML = '';
                                document.getElementById("inicia_rep").innerHTML = 'Ingresar';
                                alertify.defaults.glossary.title = '<p class="text-center">Notificación<p>';
                                alertify.alert('Se ha producido un error inesperado');
                            }
                        }
                    });
                } else {
                    $('#alumno-seleccionado').hide();
                }
            });

            $("#id_btn_pdf_cur_ce_fc").click(function() {
                var curso = $('#sel_curso').val();
                window.open(
                    url_base.protocol + "//" +
                    url_base.host + "/" +
                    "reportes/curso_reporte_pdf_ce_fc.php?id_curso=" + curso,
                    '_blank'
                );
            });

            $("#id_btn_pdf_alumno").click(function() {
                var alumno = $('#sel_alumno').val();
                window.open(
                    url_base.protocol + "//" +
                    url_base.host + "/" +
                    "reportes/alumno_reporte_pdf_ce_fc.php?id_alumno=" + alumno,
                    '_blank'
                );
            });

            $("#id_btn_pdf_est_ce_fc").click(function() {
                window.open(
                    url_base.protocol + "//" +
                    url_base.host + "/" +
                    "reportes/colegio_reporte_pdf_ce_fc.php",
                    '_blank'
                );
            });

        });

        $("#dispersion-establecimiento-basica").ready(function() {
            flag_boton *= -1
            setTimeout(function() {
                    $("#id_btn_pdf").removeAttr("disabled");
                    $("#id_cargando_reporte").hide();
                    $("#id_reporte_cargado").show();
                },
                5000
            );

            $("#id_btn_pdf").click(function() {
                window.open(
                    url_base.protocol + "//" +
                    url_base.host + "/" +
                    "reportes/colegio_reporte_pdf.php",
                    '_blank'
                );
            });
        });
    </script>
    <style type="text/css">
        .autocomplete {
            /*the container must be positioned relative:*/
            position: relative;
            display: inline-block;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        .autocomplete-items div:hover {
            /*when hovering an item:*/
            background-color: #e9e9e9;
        }

        .autocomplete-active {
            /*when navigating through the items using the arrow keys:*/
            background-color: DodgerBlue !important;
            color: #ffffff;
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

        .small-box>.inner {
            padding: 10px;
            overflow: hidden;
            outline: auto;
            max-height: 100px;
        }

        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 900;
        }

        hr {
            border-top: 2px dashed #fc455c;
        }
    </style>
</body>

</html>