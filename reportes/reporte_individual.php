<?php
session_start();
if(isset($_SESSION['user'])){

require_once "../assets/librerias/vendor/autoload.php";

require_once "dist/conf/require_conf.php";

date_default_timezone_set('America/Santiago');
//$token_estudiante = 'A22254801';
$token_estudiante = filter_input(INPUT_GET, 'token');

$tipo_user = 'estudiante';
$id_doc = 0;
$datos_estudiante = datos_personales_reporte_individual($token_estudiante,$tipo_user,$id_doc);

$resultado_estudiante = $datos_estudiante->fetch(PDO::FETCH_ASSOC);
$duracion_encuesta = getDuracionEncuesta($token_estudiante);
$duracion_encuesta = $duracion_encuesta->fetch(PDO::FETCH_ASSOC);

$resultado_compromiso_escolar = reporte_compromiso_escolar_estudiante($token_estudiante);
$result_ce_final = $resultado_compromiso_escolar->fetch(PDO::FETCH_ASSOC);
$indicador_CE_suma = alerta_o_fortaleza_indicador_compromiso_escolar($token_estudiante, $result_ce_final["sumaCE"]);
$indicador_CE_afectivo_suma = alerta_o_fortaleza_indicador_ce_afectivo($token_estudiante, $result_ce_final["sumaAfectiva"]);
$indicador_CE_conductual_suma = alerta_o_fortaleza_indicador_ce_conductual($token_estudiante, $result_ce_final["sumaConductual"]);
$indicador_CE_cognitivo_suma = alerta_o_fortaleza_indicador_ce_cognitivo($token_estudiante, $result_ce_final["sumaCognitiva"]);


$consulta_factores_contextuales = reporte_factores_contextuales_estudiante($token_estudiante);
$resultado = $consulta_factores_contextuales->fetch(PDO::FETCH_ASSOC);


$indicador_fc_suma = alerta_o_fortaleza_indicador_fc_reporte_individual($resultado["sumaFC"]);
$indicador_af_suma = alerta_o_fortaleza_indicador_af_reporte_individual($resultado["sumaFamilia"]);
$indicador_apoyo_pares_suma = alerta_o_fortaleza_indicador_apoyo_pares_reporte_individual($resultado["sumaPares"]);

$indicador_apoyo_profes_suma = alerta_o_fortaleza_indicador_apoyo_profesores_reporte_individual($resultado["sumaProfes"]);
  
$mpdfConfig = array(
    'mode' => 'utf-8', 
    'format' => 'A4',
    'orientation' => 'P',
    'margin_header'=>0,  
    'margin_bottom'=>20,
    'margin_left'=>0,
    'margin_right'=>0, 
    'margin_top'=> 30 
  );

  $mpdf = new \Mpdf\Mpdf($mpdfConfig);

  $mpdf->SetHTMLHeader('<div style="text-align: center; font-weight: bold;"><img src="../assets/img/encabezado_informe_estudiante.jpg"/></div>', 'O', true);
  $mpdf->SetHTMLFooter('<div style="text-align: center;border-top: 1px solid #fc455c; color: #fc455c; padding-top:3px;">{PAGENO}</div>');
  
if (intval($duracion_encuesta["minutos"]) != 0 && intval($duracion_encuesta["segundos"]) != 0) {
  $duracion = $duracion_encuesta["minutos"].' minutos con '.$duracion_encuesta["segundos"]." segundos";
} else {
  $duracion = "No Especificada";
}

$fecha = date('d-m-Y')." | ".date('H:i');

$mpdf->WriteHTML('<style> table { min-height: 500px; } </style><div class="container">
<div class="datos_curso">
<span>Nombre Estudiante: '. $resultado_estudiante["nombres"] . ' ' . $resultado_estudiante["apellidos"] . '</span><br>
<span>Curso:' . $resultado_estudiante["curso"] . '</span><br>
<span>Establecimiento:' . $resultado_estudiante["establecimiento"] . '</span><br>
<span>Fecha: ' .$fecha. '</span><br>
<!-- <span>Duracion encuesta: '.$duracion.'</span> -->
</div>
<div class="descrip_com_esco">
<div class="subtitulos pt-1">Definiciones</div>

<p><span>El Compromiso Escolar</span> dice relación con la activa participación del (la) estudiante en las actividades académicas,
curriculares o extracurriculares. Estudiantes comprometidos encuentran que el aprendizaje es significativo y están
motivados y empeñados en su aprendizaje y futuro. El compromiso escolar impulsa al estudiante hacia el aprendizaje,
pudiendo ser alcanzado por todas y todos y es altamente influenciable por factores del contexto. Diversos
investigadores (Appleton etal., 2008; Bowles et al., 2013; Fredricks et al., 2004, Jimerson et al., 2000; Lyche, 2010)
concuerdan que el compromiso escolar es una variable clave en la deserción escolar, ya que el abandonar la escuela
no suele ser un acto repentino, sino que la etapa final de un proceso dinámico y acumulativo de una pérdida de
compromiso con los estudios (Rumberger, 2001). Por el contrario, cuando los estudiantes desarrollan un compromiso
positivo con sus estudios, ellos son más propensos a graduarse con bajos niveles de conductas de riesgo y un alto
rendimiento académico.</p>

<div class="subtitulos">El Compromiso Escolar tiene 3 dimensiones</div>

<p><span>Compromiso afectivo: </span>  El compromiso afectivo se define como el nivel de respuesta emocional del estudiante hacia
el establecimiento educativo y su proceso de aprendizaje, caracterizado por un sentimiento de involucramiento al
colegio y una consideración del colegio como un lugar que es valioso y vale la pena. El compromiso afectivo brinda el
incentivo para participar y perseverar. Los estudiantes que están comprometidos afectivamente, se sienten parte de
una comunidad escolar y que el colegio es significativo en sus vidas, al tiempo que reconocen que la escuela
proporciona herramientas para obtener logros fuera de la escuela. Abarca conductas hacia los profesores,
compañeros y la escuela. Se presume que crea un vínculo con la escuela y una buena disposición hacia el trabajo
estudiantil.</p>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<p><span>Compromiso conductual: </span> El compromiso conductual se basa en la idea de participación en el ámbito académico y
actividades sociales o extracurriculares. El componente conductual del compromiso escolar incluye las interacciones
y respuestas del estudiante, dentro de la sala de clases, del colegio y en ambientes extracurriculares. Este aspecto
del compromiso escolar va desde un continuo, es decir, desde un involucramiento esperado de manera universal
(asistencia diaria) a un involucramiento más intenso (Ej. Participación en el centro de alumnos).</p>
<br><br>
<p><span>Compromiso cognitivo: </span> El compromiso cognitivo es el proceso mediante el cual se incorpora la conciencia y
voluntad de ejercer el esfuerzo necesario para comprender ideas complejas y desarrollar habilidades difíciles
(Appleton et al., 2008; Fredricks, Blumenfeld, y París, 2004). Es la inversión consciente de energía para construir
aprendizajes complejos que van más allá de los requerimientos mínimos. Refleja la disposición del estudiante para
utilizar y desarrollar sus destrezas cognitivas en el proceso de aprendizaje y dominio de nuevas habilidades de gran
complejidad. Implica actuar de manera reflexiva y estar dispuesto a realizar el esfuerzo necesario para la
comprensión de ideas complejas y desarrollar habilidades para el aprendizaje.</p>

<div class="subtitulos">Reporte de fortalezas y alertas</div>

<p>Este reporte describe las alertas y fortalezas para cada una de las dimensiones de Compromiso Escolar, desde la perspectiva de los estudiantes. Estas alertas y fortalezas podrían ser medias o altas en el caso de las Alertas y altas o medias en el caso de las Fortalezas.</p>

<div class="subtitulos">1.1. Compromiso Afectivo</div>

<div class="pt-1">
<table style="border-collapse:collapse; width: 800px; min-width: 400px;">
<thead>
<tr>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Resultado</th>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white;">Descripción</th>

</tr>
</thead>
<tbody>
 <tr>
 <td style="border: 1px solid #fc455c;">' . $indicador_CE_afectivo_suma . '</td>
 <td style="border: 1px solid #fc455c;text-align: justify;padding-left: 10px;padding-right: 10px; ">' . descripcion_indicador_ce_afectivo_($indicador_CE_afectivo_suma) . '</td>
 
 </tr>
</tbody>

</table>
</div><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><div class="subtitulos pt-1">1.2. Compromiso Conductual</div>
<div class="pt-1">
<table style="border-collapse:collapse; width: 800px;">
<thead>
<tr>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Resultado</th>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white;">Descripción</th>

</tr>
</thead>
<tbody>
 <tr>
 <td style="border: 1px solid #fc455c;">' . $indicador_CE_conductual_suma . '</td>
 <td style="border: 1px solid #fc455c;text-align: justify;padding-left: 10px;padding-right: 10px; ">' . descripcion_indicador_conductual($indicador_CE_conductual_suma) . '</td>
 
 </tr>
</tbody>

</table>
</div>

<div class="subtitulos pt-1">1.3. Compromiso Cognitivo</div>

<div class="pt-1">
<table style="border-collapse:collapse; width: 800px;">
<thead>
<tr>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Resultado</th>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white;">Descripción</th>

</tr>
</thead>
<tbody>
 <tr>
 <td style="border: 1px solid #fc455c;">' .  $indicador_CE_cognitivo_suma  . '</td>
 <td style="border: 1px solid #fc455c;text-align: justify;padding-left: 10px;padding-right: 10px; ">' . descripcion_indicador_cognitivo($indicador_CE_cognitivo_suma) . '</td>
 
 </tr>
</tbody>

</table>
</div>

<div class="subtitulos pt-1">2.- Factores Contextuales</div>

<p><span>El Compromiso Escolar</span> es una variable altamente influenciada por factores contextuales y relacionales como la
familia, el colegio y los pares, todos factores moldeables sobre los cuáles se puede intervenir en Ia medida que se
tenga información sobre cómo estos factores afectan el compromiso escolar. Dentro de estos factores contextuaIes se
incluyen las dimensiones de contextos: Apoyo recibido de la FAMILIA, Apoyo recibido de PARES y Apoyo recibido de
PROFESORES.</p>

<p><span>Apoyo Familia:</span> Se refiere a que los/las estudiantes perciben ser apoyados por sus profesores y/o sus familias. La
familia del (la) estudiante suele apoyar a su hijo(a) en el proceso de aprendizaje y cuando tiene problemas,
ayudándolo con las tareas, conversando lo que sucede en la escuela, animándolo y motivándolo a trabajar bien. El
(Ia) estudiante se siente motivado por sus profesores para aprender y que estos lo ayudan cuando tiene algún
problema. El (la) estudiante mantiene en general buenas relaciones con sus profesores. Existe la impresión que los profesores se comportan de acuerdo con Ios valores que enseñan y mantienen un interés por el estudiante como
persona y como estudiante, ayudándolo en caso de dificultades.</p>

<p><span>Apoyo Pares: </span> Se define como la percepción que tienen Ios sujetos acerca de las relaciones interpersonales entre
compañeros, la preocupación, la confianza y el apoyo que se da entre pares, siendo estos importantes frente a Ia
integración escolar y frente a desafíos escolares y/o cuando tiene una dificultad académica.</p>

<p><span>Apoyo Profesores: </span> Se define como la percepción del (la) estudiante acerca del apoyo que recibe de sus profesores.</p>

<div class="subtitulos pt-1">2.1. Apoyo Familia</div>

<table style="border-collapse:collapse; width: 800px;">
<thead>
<tr>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Resultado</th>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white;">Descripción</th>

</tr>
</thead>
<tbody>
 <tr>
 <td style="border: 1px solid #fc455c;">' .   $indicador_af_suma   . '</td>
 <td style="border: 1px solid #fc455c;text-align: justify;padding-left: 10px;padding-right: 10px; ">' . descripcion_indicador_familiar($indicador_af_suma) . '</td>
 
 </tr>
</tbody>

</table>

<div class="subtitulos pt-1">2.2. Apoyo Pares</div>

<table style="border-collapse:collapse; width: 800px;">
<thead>
<tr>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Resultado</th>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white;">Descripción</th>

</tr>
</thead>
<tbody>
 <tr>
 <td style="border: 1px solid #fc455c;">' .   $indicador_apoyo_pares_suma   . '</td>
 <td style="border: 1px solid #fc455c;text-align: justify;padding-left: 10px;padding-right: 10px; ">' . descripcion_indicador_pares($indicador_apoyo_pares_suma) . '</td>
 
 </tr>
</tbody>

</table><br><br><br>
<br><br><br>
<br><br><br><br><br>
<br><br><br>
<br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br>
<br><br><br><br><br>

<div class="subtitulos pt-1">2.3. Apoyo Profesores</div>

<table style="border-collapse:collapse; width: 800px;">
<thead>
<tr>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Resultado</th>
<th style="background-color:#fc455c;border: 1px solid #fc455c;color:white;">Descripción</th>

</tr>
</thead>
<tbody>
 <tr>
 <td style="border: 1px solid #fc455c;">' .   $indicador_apoyo_profes_suma   . '</td>
 <td style="border: 1px solid #fc455c;text-align: justify;padding-left: 10px;padding-right: 10px; ">' . descripcion_indicador_profesores($indicador_apoyo_profes_suma) . '</td>
 
 </tr>
</tbody>

</table>

<div class="subtitulos pt-1">Reporte de Cuadrantes</div>
<p>Este reporte permite identificar al estudiante dentro de uno de los siguientes Cuadrantes (Alto Compromiso Escolar y bajos factores contextuales,
alto compromiso escolar y factores contextuales, bajo compromiso escolar y bajo factores contextuales.)</p>


<p><span>Alto compromiso escolar y Factores contextuales limitantes (+,-): </span> Estudiantes que presentan altos niveles de
participación e interés en las actividades académicas. En general consideran que el aprendizaje sea significativo para
su presente y futuro, al tiempo que se sienten parte de una comunidad escolar. Se trata de estudiantes que presentan
una disposición para invertir destrezas cognitivas dentro del aprendizaje y logran un dominio de habilidades de gran
complejidad. EI tener un compromiso escolar desarrollado es un factor protector que puede facilitar las trayectorias
educativas exitosas y prevenir otras situaciones de riesgo asociadas a la deserción escolar. EI compromiso escolar es
altamente permeable por factores contextuales. En este caso se aprecia que Ios factores contextuales evaluados
(familia, pares y profesores) se constituyen en factores de riesgo, por lo que se hace necesario prestar atención pues puede
el compromiso escolar verse alterado en el corto plazo si esos factores de riesgo no se revierten.</p>

<p><span>Alto compromiso escolar y Factores contextuales facilitadores (+,+): </span>  Estudiantes que presentan altos niveles
de participación e interés en las actividades académicas. En general consideran que el aprendizaje sea significativo
para su presente y futuro, al tiempo que se sienten parte de una comunidad escolar. Se trata de estudiantes que
presentan una disposición para invertir destrezas cognitivas dentro del aprendizaje y logran un dominio de
habilidades de gran complejidad. EI tener un compromiso escolar desarrollado es un factor protector que puede
facilitar las trayectorias educativas exitosas y prevenir otras situaciones de riesgo asociadas a la deserción escolar. EI
compromiso escolar es altamente permeable por factores contextuales, tales como el apoyo de la familia, Ios pares y
los profesores. Estas variables se constituyen en facilitadores del compromiso escolar para este grupo de estudiantes.</p>


<p><span>Bajo compromiso Escolar y Factores contextuales facilitadores ( -,+): </span> Estudiantes que presentan una débil
participación e interés en las actividades académicas. En general no consideran que el aprendizaje sea significativo
para su presente y futuro, al tiempo que no se sienten parte de una comunidad escolar. No hay una disposición para
invertir destrezas cognitivas dentro del aprendizaje y dominio de nuevas habilidades de gran complejidad. EI no tener
un compromiso escolar desarrollado es un factor de riesgo de Ia deserción escolar, para graduarse con altos niveles
de conductas de riesgo y un bajo rendimiento académico. El compromiso escolar es altamente permeable por factores
contextuales. En este caso se aprecia que Ios factores contextuales evaluados (familia, pares y profesores) no se
constituyen en factores de riesgo, por lo que se hace necesario evaluar qué otros factores pueden estar afectando el
compromiso escolar de Ios estudiantes, tales como prácticas educativas no motivantes o precarios servicios de apoyo
a la escuela.</p>

<p><span>Bajo compromiso escolar y Factores contextuales limitantes (-, -): </span>  Estudiantes que presentan una débil
participación e interés en las actividades académicas. En general, no consideran que el aprendizaje sea significativo
para su presente y futuro, al tiempo que no se sienten parte de una comunidad escolar. No hay una disposición para
invertir destrezas cognitivas dentro del aprendizaje y dominio de nuevas habilidades de gran complejidad. EI no tener
un compromiso escolar desarrollado es un factor de riesgo de Ia deserción escolar, para graduarse con altos niveles
de conductas de riesgo y un bajo rendimiento académico. EI compromiso escolar es altamente permeable por
factores contextuales. En este caso se aprecia que el débil compromiso escolar se vincula a un bajo involucramiento
de Ia familia del estudiante en su proceso de aprendizaje junto con un clima escolar deteriorado (relación con
profesores y pares) lo que termina desmotivando al estudiante. Un clima escolar deteriorado se puede observar en
malas relaciones entre estudiante y profesores, entre Ios mismos estudiantes, y en un ambiente donde se han
deteriorado los lazos de respeto y confianza.</p>

<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<div class=" subtitulos pt-1 text-center">'.brecha_alta_limitante_estudiante(
  $resultado["sumaFC"],
  $result_ce_final["sumaCE"],
  $resultado_estudiante["nivel"]
  ).'</div>
<div class="pt-1">
  <img src="dist/img/individual/'.$resultado_estudiante["nombres"].'_'. $resultado_estudiante['apellidos'].'.png"/>
  </div>
</div>
</div>');


$mpdf->Output("../documentos/reporte_individual/reporte" . $resultado_estudiante['nombres'] . " " . $resultado_estudiante['apellidos'] . ".pdf", \Mpdf\Output\Destination::FILE);


$mpdf->Output('reporte_individual.pdf', 'I');
}else{
    header("location:login.php");
    }
    