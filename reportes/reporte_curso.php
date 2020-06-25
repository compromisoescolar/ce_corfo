<?php
session_start();

if(isset($_SESSION['user'])){
   
require_once "../assets/librerias/vendor/autoload.php";

require_once "dist/conf/require_conf.php";

$total_estudi = $_SESSION["suma_estudi"];
$respondidos = $_SESSION["respon"];

$compromiso_escolar = suma_afectivo_coductual_cognitivo_final($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);


$factores_contextuales = fc_suma_familiar_pares_profesores($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);
date_default_timezone_set('America/Santiago');

$fecha = date('d-m-Y')." | ".date('H:i');

  
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
  
  $mpdf->SetHTMLHeader('<div style="text-align: center; font-weight: bold;"><img src="../assets/img/encabezado_informe_curso.jpg"/></div>', 'O', true);
  $mpdf->SetHTMLFooter('<div style="text-align: center;border-top: 1px solid #fc455c; color: #fc455c; padding-top:3px;">{PAGENO}</div>');
  
  $mpdf->WriteHTML('<div class="container">
  <div class="datos_curso">
  <span>Establecimiento: '.$_SESSION["establecimiento"].'</span><br>
  <span>Curso: ' .$_SESSION["curso_nombre"].'</span><br>
  <span>Fecha: ' . $fecha . '</span><br>
  <span>Número total de estudiantes: '.$total_estudi.'</span><br>
  <span>Número total de estudiantes encuestados: ' .$respondidos.'</span>
  </div>
  
  <div class="descrip_com_esco">

  <div class="subtitulos pt-1">Definiciones</div>

  <p><span>El Compromiso Escolar: </span>El Compromiso Escolar dice relación con la activa participación del (la) estudiante en las actividades académicas,
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
  
  <p><span>Compromiso conductual: </span> El compromiso conductual se basa en la idea de participación en el ámbito académico y
  actividades sociales o extracurriculares. El componente conductual del compromiso escolar incluye las interacciones
  y respuestas del estudiante, dentro de la sala de clases, del colegio y en ambientes extracurriculares. Este aspecto
  del compromiso escolar va desde un continuo, es decir, desde un involucramiento esperado de manera universal
  (asistencia diaria) a un involucramiento más intenso (Ej. Participación en el centro de alumnos).</p><br><br>
  
  <p><span>Compromiso cognitivo: </span> El compromiso cognitivo es el proceso mediante el cual se incorpora la conciencia y
  voluntad de ejercer el esfuerzo necesario para comprender ideas complejas y desarrollar habilidades difíciles
  (Appleton et al., 2008; Fredricks, Blumenfeld, y París, 2004). Es la inversión consciente de energía para construir
  aprendizajes complejos que van más allá de los requerimientos mínimos. Refleja la disposición del estudiante para
  utilizar y desarrollar sus destrezas cognitivas en el proceso de aprendizaje y dominio de nuevas habilidades de gran
  complejidad. Implica actuar de manera reflexiva y estar dispuesto a realizar el esfuerzo necesario para la
  comprensión de ideas complejas y desarrollar habilidades para el aprendizaje.</p>

  
<div class="subtitulos pt-1">Factores Contextuales</div>

<p>El compromiso escolar es una variable altamente influenciada por factores contextuales y relacionales como la
familia, el colegio y los pares, todos factores moldeables sobre los cuáles se puede intervenir en Ia medida que se
tenga información sobre cómo estos factores afectan el compromiso escolar. Dentro de estos factores contextuaIes se
incluyen las dimensiones de contextos: Apoyo recibido de la FAMILIA, Apoyo recibido de PARES y Apoyo recibido de
PROFESORES.<br> Para efectos de la presente evaluación, se consideran tres factores del contexto que pueden influir en las trayectorias
educativas y el compromiso escolar del estudiante:</p>

<p><span>Apoyo de la Familia: </span> Se refiere a que los/las estudiantes perciben ser apoyados por sus profesores y/o sus familias.
La familia del (la) estudiante suele apoyar a su hijo(a) en el proceso de aprendizaje y cuando tiene problemas,
ayudándolo con las tareas, conversando lo que sucede en la escuela, animándolo y motivándolo a trabajar bien. El (la)
estudiante se siente motivado por sus profesores para aprender y que estos lo ayudan cuando tiene algún problema.
El (la) estudiante mantiene en general buenas relaciones con sus profesores. Existe la impresión que los profesores
se comportan de acuerdo con los valores que enseñan y mantienen un interés por el estudiante como persona y como
estudiante, ayudándolo en caso de dificultades. El (la) estudiante considera que sus profesores lo tratan con respeto y
lo alientan a realizar nuevamente una tarea si se ha equivocado El (la) estudiante siente que en el colegio se valora la
participación de todos.</p>

<p><span>Apoyo de los Pares: </span>  Se define como la percepción que tienen los sujetos acerca de las relaciones interpersonales
entre compañeros, la preocupación, la confianza y el apoyo que se da entre pares, siendo estos importantes frente a
la integración escolar y frente a desafíos escolares y/o cuando tiene una dificultad académica.</p>

<p><span>Apoyo de los Profesores: </span>  Se define como la percepción del (la) estudiante acerca del apoyo que recibe de sus
profesores.</p>
<br>
<div class="subtitulos pt-1">Reporte por Dimensión</div>

<p>Este informe describe el comportamiento del curso en cada una de las preguntas, agrupadas por dimensión,
considerando solamente los resultados de los ítems correspondientes a los cuestionarios de estudiantes. Se calculará
el % de estudiantes que se distribuyen en la siguiente escala de respuesta:</p>

<table style="border-collapse: collapse; width: 100%; table-layout: fixed; margin-top:12px; border: 1px solid #fc455c;  " border="1" cellpadding="3"><thead><tr style="background-color:#fc455c;border: 1px solid #fc455c; color:white;"><td style="color:white;">Escala de respuesta</td><td style="color:white;">Sigla</td></tr></thead>
<tbody><tr><td style="border: 1px solid #fc455c;">Nunca O Casi Nunca</td><td style="border: 1px solid #fc455c;">NU</td></tr>
<tbody><tr><td style="border: 1px solid #fc455c;">Algunas veces</td><td style="border: 1px solid #fc455c;">AL</td></tr>
<tbody><tr><td style="border: 1px solid #fc455c;">A menudo</td><td style="border: 1px solid #fc455c;">AM</td></tr>
<tbody><tr><td style="border: 1px solid #fc455c;">Muchas veces</td><td style="border: 1px solid #fc455c;">MV</td></tr>
<tbody><tr><td style="border: 1px solid #fc455c;">Siempre o casi siempre</td><td style="border: 1px solid #fc455c;">SC</td></tr>


</table>

<p>Lo anterior, tanto a nivel de las 3 dimensiones de compromiso escolar (Afectivo, Conductual, Cognitivo) como de los 3
factores contextuales que se incluyen en el caso de esta evaluación (Apoyo de familia, apoyo de pares y apoyo de
profesores).</p>

<div class="subtitulos pt-1">Reporte por Dimensión</div>

<div class="mpdf_subtitulo">Compromiso Afectivo</div>
<table style="border-collapse: collapse; width: 100%; table-layout: fixed; margin-top:12px;" border="1" cellpadding="3"><thead><tr><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Items</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">NU</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AL</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AM</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">MV</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">SC</th></tr></thead>
<tbody>'.dimension_afectivo_curso_pdf($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]).'</tbody></table>

<div class="mpdf_subtitulo">Compromiso Conductual</div>
<table style="border-collapse: collapse; width: 100%; table-layout: fixed; margin-top:12px;" border="1" cellpadding="3"><thead><tr><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Items</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">NU</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AL</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AM</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">MV</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">SC</th></tr></thead>
<tbody>'.dimension_conductual_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]).'</tbody></table>

<div class="mpdf_subtitulo">Compromiso Cognitivo</div>
<table style="border-collapse: collapse; width: 100%; table-layout: fixed; margin-top:12px;" border="1" cellpadding="3"><thead><tr><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Items</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">NU</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AL</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AM</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">MV</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">SC</th></tr></thead>
<tbody>'.dimension_cognitivo_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]).'</tbody></table>

<div class="mpdf_subtitulo">Apoyo Familia</div>
<table style="border-collapse: collapse; width: 100%; table-layout: fixed; margin-top:12px;" border="1" cellpadding="3"><thead><tr><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Items</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">NU</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AL</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AM</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">MV</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">SC</th></tr></thead>
<tbody>'.dimension_apoyo_familiar_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]).'</tbody></table>

<div class="mpdf_subtitulo">Apoyo Profesores</div>
<table style="border-collapse: collapse; width: 100%; table-layout: fixed; margin-top:12px;" border="1" cellpadding="3"><thead><tr><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Items</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">NU</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AL</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AM</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">MV</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">SC</th></tr></thead>
<tbody>'.dimension_profesores_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]).'</tbody></table>

<div class="mpdf_subtitulo">Apoyo Pares</div>
<table style="border-collapse: collapse; width: 100%; table-layout: fixed; margin-top:12px;" border="1" cellpadding="3"><thead><tr><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">Items</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">NU</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AL</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">AM</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">MV</th><th style="background-color:#fc455c;border: 1px solid #fc455c;color:white; border-right-color:white;">SC</th></tr></thead>
<tbody>'.dimension_pares_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]).'</tbody></table>


<div class="subtitulos pt-1">Reporte por Niveles</div>

<p>Estos gráficos de tortas muestran el porcentaje de estudiantes que presenta cada perfil en cada dimensión de
compromiso escolar (afectivo, conductual y cognitivo) y en 3 factores de contexto (Apoyo de familia, apoyo de pares y
apoyo de profesores.)</p>

<p>En el caso de <span>Compromiso Escolar</span>, se consideran los siguientes perfiles posibles:</p>

<p>El <span>perfil emergente</span> se refiere cuando el Compromiso Escolar o uno de sus subtipos (afectivo, conductual, cognitivo)
es emergente, se considera que requiere atención por parte del establecimiento escolar para revertir esta situación,
toda vez que se constituye en un factor de riesgo para la trayectoria educativa del (la) estudiante. Se estima
pertinente revisar los factores del contexto que pueden estar determinando esta situación y desplegar estrategias de
apoyo en función a dichos factores, junto con realizar un seguimiento personalizado una vez que se despliegan las
estrategias de apoyo.</p>


<p>El <span>perfil en desarrollo</span> se refiere cuando el compromiso escolar o uno de sus subtipos (afectivo, conductual,
cognitivo) está en desarrollo se considera que si bien no está en una situación alarmante, sí requiere atención por
parte del establecimiento escolar para evitar que se intensifiquen aquellos factores de riesgo y para que se revierta
esta situación que de mantenerse así, puede afectar Ia trayectoria educativa del (la) estudiante. Se estima pertinenterevisar Ios factores del contexto que pueden estar determinando esta situación y desplegar estrategias de apoyo
grupales que permitan a estudiantes que presenten este nivel de compromiso escolar poder recibir un apoyo
adicional para prevenir su intensificación.</p>

<p>El <span>perfil satisfactorio</span> se refiere cuando el compromiso escolar o uno de sus subtipos (afectivo, conductual,
cognitivo) está en un nivel satisfactorio, se estima que es un factor protector, en la medida que mayores niveles de
compromiso estudiantil se vinculan con trayectorias educativas positivas y bajos niveles de conductas de riesgo que
pueden culminar en la deserción escolar. Sin embargo, el o la estudiante aún puede presentar un mejor nivel de
compromiso. Se estima pertinente levantar estrategias promociónales a nivel de colegio que permitan a estos
estudiantes alcanzar el nivel óptimo de compromiso escolar.</p>

<p>El <span>perfil muy desarrollado</span> es el nivel óptimo de compromiso, constituyéndose en un factor protector en Ia medida
que un alto nivel de compromiso está asociado a trayectorias educativas exitosas y altas tasas de graduación del
sistema escolar.</p>

<div class="subtitulos pt-1">Compromiso Afectivo</div>

<p>En el curso '.$_SESSION["curso_nombre"].', el '.$compromiso_escolar["porcentaje_afectivo_emergente"].'% de los estudiantes poseen un perfil emergente de COMPROMISO ESCOLAR AFECTIVO, el '.$compromiso_escolar["porcentaje_afectivo_en_desarrollo"].'% un perfil en desarrollo, el '.$compromiso_escolar["porcentaje_afectivo_satisfactorio"].'% un perfil satisfactorio y el '.$compromiso_escolar["porcentaje_afectivo_muy_desarrollado"].'% un perfil muy desarrollado.</p>
<div style="margin-left:50px;"><img src="dist/img/curso/'.$_SESSION["id_establecimiento"].'_'. $_SESSION["id_profesor"].'_afectivo.png"/></div>


<div class="subtitulos pt-1">Compromiso Conductual</div>

<p>En el curso '.$_SESSION["curso_nombre"].', el '.$compromiso_escolar["porcentaje_conductual_emergente"].'% de los estudiantes poseen un perfil emergente de COMPROMISO
ESCOLAR CONDUCTUAL, el '.$compromiso_escolar["porcentaje_conductual_en_desarrollo"].'% un perfil en desarrollo, el '.$compromiso_escolar["porcentaje_conductual_satisfactorio"].'% un perfil satisfactorio y el '.$compromiso_escolar["porcentaje_conductual_muy_desarrollado"].'% un
perfil muy desarrollado.</p>
<div style="margin-left:50px;"><img src="dist/img/curso/'.$_SESSION["id_establecimiento"].'_'. $_SESSION["id_profesor"].'_conductual.png"/></div>

<div class="subtitulos pt-1">Compromiso Cognitivo</div>

<p>En el curso '.$_SESSION["curso_nombre"].', el '.$compromiso_escolar["porcentaje_cognitivo_emergente"].'% de los estudiantes poseen un perfil emergente de COMPROMISO
ESCOLAR COGNITIVO, el '.$compromiso_escolar["porcentaje_cognitivo_en_desarrollo"].'% un perfil en desarrollo, el '.$compromiso_escolar["porcentaje_cognitivo_satisfactorio"].'% un perfil satisfactorio y el '.$compromiso_escolar["porcentaje_cognitivo_muy_desarrollado"].'% un
perfil muy desarrollado.</p>
<div style="margin-left:50px;"><img src="dist/img/curso/'.$_SESSION["id_establecimiento"].'_'. $_SESSION["id_profesor"].'_cognitivo.png"/></div>

<p>En el caso de <span>Factores Contextuales</span>, se consideran los siguientes perfiles posibles:</p>

<p>El <span>perfil bajo</span> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por ejemplo,
Apoyo recibido de la familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores contextuales
presentan un bajo o mediano desarrollo, estos pueden estar afectando o afectar el compromiso escolar en su conjunto
o cualquiera de sus subtipos (cognitivo, afectivo, conductual), de allí la importancia de intervenir como
establecimiento educacional, de manera tal de revertir dicha situación.</p>

<p>El <span>perfil mediano</span> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por
ejemplo, Apoyo recibido de Ia familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores
contextuales presentan un bajo o mediano desarrollo, estos pueden estar afectando o afectar el compromiso escolar
en su conjunto o cualquiera de sus subtipos (cognitivo, afectivo, conductual), de allí la importancia de intervenir
como establecimiento educacional, de manera tal de revertir dicha situación.</p>

<p>El <span>perfil alto</span> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por ejemplo,
Apoyo recibido de la familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores contextuales
presentan un alto o muy alto, desarrollo, estos se constituyen en factores protectores para la trayectoria educativa
del estudiante. Ahora, si teniendo el (la) estudiante un puntaje alto o muy alto en estas variables, aún presenta un
bajo compromiso escolar, se requiere evaluar que otros factores contextuales pudiesen estar influyendo en el
compromiso escolar del estudiante.</p>

<p>El <span>perfil muy alto</span> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por
ejemplo, Apoyo recibido de Ia familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores
contextuales presentan un alto o muy alto, desarrollo, estos se constituyen en factores protectores para la trayectoria
educativa del estudiante. Ahora, si teniendo el (la) estudiante un puntaje alto o muy alto en estas variables, aún
presenta un bajo compromiso escolar, se requiere evaluar que otros factores contextuales pudiesen estar influyendo
en el compromiso escolar del estudiante</p>

<div class="subtitulos  pt-1">Apoyo Familia</div>

<p>En el curso '.$_SESSION["curso_nombre"].', el '.$factores_contextuales["porcentaje_familiar_emergente"].'% de los estudiantes poseen un perfil bajo de APOYO Y EXPECTATIVAS DE ADULTOS SIGNIFICATIVOS, el '.$factores_contextuales["porcentaje_familiar_en_desarrollo"].'% un perfil mediano, el '.$factores_contextuales["porcentaje_familiar_satisfactorio"].'% un perfil alto y el '.$factores_contextuales["porcentaje_familiar_muy_desarrollado"].'% un
perfil muy alto.</p>
<div style="margin-left:50px;"><img src="dist/img/curso/'.$_SESSION["id_establecimiento"].'_'. $_SESSION["id_profesor"].'_familiar.png"/></div>

<div class="subtitulos pt-1">Apoyo Profesores</div>
<p>En el curso '.$_SESSION["curso_nombre"].', el '.$factores_contextuales["porcentaje_profesores_emergente"].'% de los estudiantes poseen un perfil bajo de APOYO Y EXPECTATIVAS DE PROFESORES, el '.$factores_contextuales["porcentaje_profesores_en_desarrollo"].'% un perfil mediano, el '.$factores_contextuales["porcentaje_profesores_satisfactorio"].'% un perfil alto y el '.$factores_contextuales["porcentaje_profesores_muy_desarrollado"].'% un
perfil muy alto.</p>

<div style="margin-left:50px;"><img src="dist/img/curso/'.$_SESSION["id_establecimiento"].'_'. $_SESSION["id_profesor"].'_profesores.png"/></div>

<div class="subtitulos pt-1">Apoyo Pares</div>
<p>En el curso  '.$_SESSION["curso_nombre"].', el '.$factores_contextuales["porcentaje_pares_emergente"].'% de los estudiantes poseen un perfil bajo de APOYO DE PARES, el '.$factores_contextuales["porcentaje_pares_en_desarrollo"].'% un perfil mediano, el '.$factores_contextuales["porcentaje_pares_satisfactorio"].'% un perfil alto y el '.$factores_contextuales["porcentaje_pares_muy_desarrollado"].'% un
perfil muy alto.</p>
<div style="margin-left:50px;"><img src="dist/img/curso/'.$_SESSION["id_establecimiento"].'_'. $_SESSION["id_profesor"].'_pares.png"/></div>
</div>
  </div>');


  $mpdf->Output('reporte_curso.pdf', 'I');




}else{
    header("location:login.php");
    }