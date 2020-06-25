<?php

session_start();
if(isset($_SESSION['user'])){
       
require_once "../assets/librerias/vendor/autoload.php";

require_once "dist/conf/require_conf.php";

$total_estudi = $_SESSION["suma_estudi"];
$respondidos = $_SESSION["respon"];

date_default_timezone_set('America/Santiago');

$ce = suma_total_dimension_compromiso_escolar_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);



$demos_fc = suma_total_dimension_factores_contextuales_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);

$compromiso_escolar = suma_afectivo_coductual_cognitivo_final($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);

$factores_contextuales = fc_suma_familiar_pares_profesores($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div id="element-to-print" >
<div class="text-center"><h4>Reporte por Curso </h4></div>

<div class="pt-1">Establecimiento: <?php echo $_SESSION["establecimiento"] ?></div>
<div class="pt-1">Curso:  <?php echo $_SESSION["curso_nombre"] ?></div>
<div class="pt-1">Fecha: <?php echo date('d-m-Y') ?> | <?php echo date('H:i') ?></div>
<div class="pt-1">Total de Estudiantes: <?php echo $total_estudi ?> </div>
<div class="pt-1">Estudiantes Encuestados: <?php echo $respondidos ?></div>

<div class="text-center"><span class="font-weight-bold"><h5>Reporte por brechas</h5></span></div>

<div class="pt-1 text-justify">Compromiso Escolar: El Compromiso Escolar dice relación con la activa participación del (la) estudiante en las actividades académicas, curricuIares o extracurricuIares. Estudiantes comprometidos encuentran que el aprendizaje es significativo y están motivados y empeñados en su aprendizaje y futuro. EI compromiso escolar impuIsa aI estudiante hacia el aprendizaje, pudiendo ser aIcanzado por todas y todas. Es aItamente infIuenciabIe por factores del contexto. Diversos investigadores (Appleton et al., 2008; BowIes et al., 2013; Fredericks et al., 2004, Jimerson et al., 2000; Lyche, 2010) concuerdan que el compromiso escolar es una variabIe cIave en la deserción escolar, ya que el abandonar la escuela no suele ser un acto repentino, sino que la etapa finaI de un proceso dinámico y acumuIativo de una pérdida de compromiso con los estudios (Rumberger, 2001). Por el contrario, cuando los estudiantes desarrollan un compromiso positivo con sus estudios, ellos son más propensos a graduarse con bajos niveles de conductas de riesgo y un aIto rendimiento académico.</div>

<div class="pt-1">El <strong>Compromiso Escolar</strong> tiene 3 dimensiones</div>


<div class="text-justify pt-1" ><strong>Compromiso afectivo:</strong> El compromiso afectivo se define como el nivel de respuesta emocional del estudiante hacia el establecimiento educativo y su proceso de aprendizaje, caracterizado por un sentimiento de involucramiento al colegio y una consideración del colegio como un lugar que es valioso y vale la pena. El compromiso afectivo brinda el incentivo para participar y perseverar. Los estudiantes que están comprometidos afectivamente, se sienten parte de una comunidad escolar y que el colegio es significativo en sus vidas, al tiempo que reconocen que la escuela proporciona herramientas para obtener logros fuera de la escuela. Abarca conductas hacia los profesores, compañeros y la escuela. Se presume que crea un vínculo con la escuela y una buena disposición hacia el trabajo estudiantil.</div>

<div class="text-justify pt-1"><strong>Compromiso conductual:</strong> El compromiso conductual se basa en la idea de participación en el ámbito académico y actividades sociales o extracurriculares. El componente conductual del compromiso escolar incluye las interacciones y respuestas del estudiante, dentro de la sala de clases, del colegio y en ambientes extracurriculares. Este aspecto del compromiso escolar va desde un continuo, es decir, desde un involucramiento esperado de manera universal (asistencia diaria) a un involucramiento más intenso (Ej. Participación en el centro de alumnos).</div>

<div class="text-justify pt-1"><strong>Compromiso cognitivo:</strong> El compromiso cognitivo es el proceso mediante el cual se incorpora la conciencia y voluntad de ejercer el esfuerzo necesario para comprender ideas complejas y desarrollar habilidades difíciles (Appleton et al., 2008; Fredricks, Blumenfeld, y París, 2004). Es la inversión consciente de energía para construir aprendizajes complejos que van más allá de los requerimientos mínimos. Refleja la disposición del estudiante para utilizar y desarrollar sus destrezas cognitivas en el proceso de aprendizaje y dominio de nuevas habilidades de gran complejidad. Implica actuar de manera reflexiva y estar dispuesto a realizar el esfuerzo necesario para la comprensión de ideas complejas y desarrollar habilidades para el aprendizaje.</div>

<div class=" pt-1 pb-1"> <strong>Factores Contextuales</strong></div>

<div class="text-justify pt-1">El compromiso escolar es una variable altamente influenciada por factores contextuales y relacionales como la familia, el colegio y los pares, todos factores moldeables sobre los cuáles se puede intervenir en Ia medida que se tenga información sobre cómo estos factores afectan el compromiso escolar. Dentro de estos factores contextuaIes se incluyen las dimensiones de contextos: Apoyo recibido de la FAMILIA, Apoyo recibido de PARES y Apoyo recibido de PROFESORES.</div>

<div class="text-justify pt-1"> Para efectos de la presente evaluación, se consideran tres factores del contexto que pueden influir
en las trayectorias educativas y el compromiso escolar del estudiante:</div>

<div class="text-justify pt-1">
<strong>Apoyo de la familia: </strong>
Se refiere a que los/las estudiantes perciben ser apoyados por sus profesores y/o sus familias. La familia del (la) estudiante suele apoyar a su hijo(a) en el proceso de aprendizaje y cuando tiene problemas, ayudándolo con las tareas, conversando lo que sucede en la escuela, animándolo y motivándolo a trabajar bien. El (la) estudiante se siente motivado por sus profesores para aprender y que estos lo ayudan cuando tiene algún problema. El (la) estudiante mantiene en general buenas relaciones con sus profesores. Existe la impresión que los profesores se comportan de acuerdo con los valores que enseñan y mantienen un interés por el estudiante como persona y como estudiante, ayudándolo en caso de dificultades. El (la) estudiante considera que sus profesores lo tratan con respeto y lo alientan a realizar nuevamente una tarea si se ha equivocado El (la) estudiante siente que en el colegio se valora la participación de todos.
 </div>

 <div class="text-justify pt-1">
<strong>Apoyo de los pares: </strong>
Se define como la percepción que tienen los sujetos acerca de las relaciones interpersonales entre compañeros, la preocupación, la confianza y el apoyo que se da entre pares, siendo estos importantes frente a la integración escolar y frente a desafíos escolares y/o cuando tiene una dificultad académica.
 </div>

 <div class="text-justify pt-1">
<strong>Apoyo de los profesores: </strong>
Se define como la percepción del (la) estudiante acerca del apoyo que recibe de sus profesores.
 </div>

 <div class="text-justify pt-1">
 La gráfica de radar muestra la brecha entre el puntaje máximo posible para cada dimensión y el
puntaje obtenido para cada grupo curso.
 </div>

<?php  $ce = suma_total_dimension_compromiso_escolar_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); 
 $suma_ce = $ce["sum_total_afectivo"]+ $ce["sum_total_conductual"]+$ce["sum_total_cognitivo"];
?>

 <div class="text-justify pt-1">
  La suma de compromiso escolar de este curso corresponde a <strong><?php echo $suma_ce; ?></strong>
 </div>

 <div id="grafica_radar_curso" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>


 <?php  $demos_fc = suma_total_dimension_factores_contextuales_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);
 $suma_fc = $demos_fc["maximo_familiar"]+$demos_fc["maximo_pares"]+$demos_fc["maximo_profesores"];
?>
 
 <div class="text-justify pt-1">
  La suma de Factores Contextuales de este curso corresponde a <strong><?php echo  $suma_fc; ?></strong>
 </div>

 <div id="grafica_radar_curso_factores_contextuales" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>


<div class="text-center"><span class="font-weight-bold"><h5>Reporte por Dimensión</h5></div>

<div class="text-justify">Este informe describe el comportamiento del curso en cada una de las preguntas, agrupadas por dimensión, considerando solamente los resultados de los ítems correspondientes a los cuestionarios de estudiantes. Se calculará el % de estudiantes que se distribuyen en la siguiente escala de respuesta:</div>

<table class="table table-bordered">
  <thead class="text-center">
    <tr>
      <th scope="col" >Escala de respuesta</th>
      <th scope="col">Sigla</th>
      
    </tr>
  </thead>
  <tbody>
    <tr>
    <td>Nunca O Casi Nunca</td>
    <td>NU</td>     
    </tr>
    <tr>      
      <td>Algunas veces</td>
      <td>AL</td>
      </tr>
      <tr>      
      <td>A menudo</td>
      <td>AM</td>
      </tr>
      <tr>      
      <td>Muchas veces</td>
      <td>MV</td>
      </tr>
      <tr>      
      <td>Siempre o casi siempre</td>
      <td>SC</td>
      </tr>
 
  </tbody>
</table>

<div class="text-justify">Lo anterior, tanto a nivel de las 3 dimensiones de compromiso escolar (Afectivo, Conductual, Cognitivo) como de los 3 factores contextuales que se incluyen en el caso de esta evaluación (Apoyo recibido y expectativas de adultos significativos, Apoyo recibido de pares e Integración con los pares).</div>
<div class="pt-2 pb-2"><span class="font-weight-bold"><h5>Compromiso Afectivo</h5></div>

<table class="table table-bordered">
  <thead class="text-center">
    <tr>
      <th scope="col" >Items</th>
      <th scope="col">NU</th>
      <th scope="col">AL</th>
      <th scope="col">AM</th>
      <th scope="col">MV</th>
      <th scope="col">SC</th>
      
    </tr>
  </thead>
   <tbody>
   <?php echo dimension_afectivo_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"])?>
   </tbody>
</table>


<div class="pt-2 pb-2"><span class="font-weight-bold"><h5>Compromiso Conductual</h5></div>

<table class="table table-bordered">
  <thead class="text-center">
    <tr>
      <th scope="col" >Items</th>
      <th scope="col">NU</th>
      <th scope="col">AL</th>
      <th scope="col">AM</th>
      <th scope="col">MV</th>
      <th scope="col">SC</th>
      
    </tr>
  </thead>
   <tbody>
   <?php echo dimension_conductual_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"])?>
   </tbody>
</table>


<div class="pt-2 pb-2"><span class="font-weight-bold"><h5>Compromiso Cognitivo</h5></div>

<table class="table table-bordered">
  <thead class="text-center">
    <tr>
      <th scope="col" >Items</th>
      <th scope="col">NU</th>
      <th scope="col">AL</th>
      <th scope="col">AM</th>
      <th scope="col">MV</th>
      <th scope="col">SC</th>
      
    </tr>
  </thead>
   <tbody>
   <?php echo dimension_cognitivo_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"])?>
   </tbody>
</table>

<div class="pt-2 pb-2"><span class="font-weight-bold"><h5>Apoyo Familiar</h5></div>

<table class="table table-bordered">
  <thead class="text-center">
    <tr>
      <th scope="col" >Items</th>
      <th scope="col">NU</th>
      <th scope="col">AL</th>
      <th scope="col">AM</th>
      <th scope="col">MV</th>
      <th scope="col">SC</th>
      
    </tr>
  </thead>
   <tbody>
   <?php echo dimension_apoyo_familiar_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"])?>
   </tbody>
</table>

<div class="pt-2 pb-2"><span class="font-weight-bold"><h5>Apoyo Docentes</h5></div>

<table class="table table-bordered">
  <thead class="text-center">
    <tr>
      <th scope="col" >Items</th>
      <th scope="col">NU</th>
      <th scope="col">AL</th>
      <th scope="col">AM</th>
      <th scope="col">MV</th>
      <th scope="col">SC</th>
      
    </tr>
  </thead>
   <tbody>
   <?php echo dimension_profesores_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"])?>
   </tbody>
</table>
<br>
<br>
<div class="pt-2 pb-2"><span class="font-weight-bold"><h5>Apoyo Pares</h5></div>

<table class="table table-bordered">
  <thead class="text-center">
    <tr>
      <th scope="col" >Items</th>
      <th scope="col">NU</th>
      <th scope="col">AL</th>
      <th scope="col">AM</th>
      <th scope="col">MV</th>
      <th scope="col">SC</th>
      
    </tr>
  </thead>
   <tbody>
   <?php echo dimension_pares_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"])?>
   </tbody>
</table>


<div ><span class="font-weight-bold"><h5>Reporte por Niveles</h5></div>


<div class="text-justify">Estos gráficos de tortas muestran el porcentaje de estudiantes que presenta cada perfil en cada dimensión de compromiso escolar (afectivo, conductual y cognitivo) y en 3 factores de contexto (Apoyo recibido y expectativas de adultos significativos, Apoyo recibido de pares e Integración con los pares)</div>

<div class="text-justify" style="margin-top: 17px;">Compromiso Escolar: El Compromiso Escolar dice relación con la activa participación del (la) estudiante en las actividades académicas, curricuIares o extracurricuIares. Estudiantes comprometidos encuentran que el aprendizaje es significativo y están motivados y empeñados en su aprendizaje y futuro. EI compromiso escolar impuIsa aI estudiante hacia el aprendizaje, pudiendo ser aIcanzado por todas y todas. Es aItamente infIuenciabIe por factores del contexto. Diversos investigadores (Appleton et al., 2008; BowIes et al., 2013; Fredericks et al., 2004, Jimerson et al., 2000; Lyche, 2010) concuerdan que el compromiso escolar es una variabIe cIave en la deserción escolar, ya que el abandonar la escuela no suele ser un acto repentino, sino que la etapa finaI de un proceso dinámico y acumuIativo de una pérdida de compromiso con los estudios (Rumberger, 2001). Por el contrario, cuando los estudiantes desarroIIan un compromiso positivo con sus estudios, eIIos son más propensos a graduarse con bajos niveles de conductas de riesgo y un aIto rendimiento académico.</div>

<div class="pt-1">El <strong>Compromiso Escolar</strong> tiene 3 dimensiones:</div>


<div class="text-justify pt-2"><strong>Compromiso afectivo</strong>: El compromiso afectivo se define como el nivel de respuesta emocional del estudiante hacia el establecimiento educativo y su proceso de aprendizaje, caracterizado por un sentimiento de involucramiento al colegio y una consideración del colegio como un lugar que es valioso y vale la pena. El compromiso afectivo brinda el incentivo para participar y perseverar. Los estudiantes que están comprometidos afectivamente, se sienten parte de una comunidad escolar y que el colegio es significativo en sus vidas, al tiempo que reconocen que la escuela proporciona herramientas para obtener logros fuera de Ia escuela. Abarca reacciones hacia los profesores, compañeros y la escuela. Se presume que crea un vínculo con la escuela y una buena disposición hacia el trabajo estudiantil.</div>
<div class="text-justify pt-2"><strong>Compromiso conductual</strong>: El compromiso conductual se basa en la idea de participación en el ámbito académico y actividades sociales o extracurriculares. El componente conductual del compromiso escolar incluye las interacciones y respuestas del estudiante, dentro de la sala de clases, del colegio y en ambientes extracurriculares. Este aspecto del compromiso escolar va desde un continuo, es decir, desde un involucramiento esperado de manera universal (asistencia diaria) a un involucramiento más intenso (Ej. Participación en el centro de alumnos).</div>

<div class="text-justify pt-2"><strong>Compromiso cognitivo</strong>: El compromiso cognitivo se basa en la idea de inversión; incorpora la conciencia y voluntad de ejercer el esfuerzo necesario para comprender ideas complejas y desarrollar habilidades difíciles (Appleton et al., 2008; Fredericks, Blumenfeld, y París, 2004). Es la inversión consciente de energía para comprender ideas complejas con Ia finalidad de ir más allá de los requerimientos mínimos, y le facilita al estudiante el comprender material complejo. Refleja la disposición del estudiante para invertir destrezas cognitivas dentro del aprendizaje y dominio de nuevas habilidades de gran complejidad. Implica ser reflexivo y estar dispuesto a realizar el esfuerzo necesario para la comprensión de ideas complejas y dominar habilidades difíciles. También implica ir más allá de los requerimientos, una preferencia por el desafío y la voluntad de hacer un esfuerzo en las tareas de aprendizaje con estrategias de auto regulación.</div>


<div class="text-justify pt-2">El Compromiso Escolar es una variable altamente influenciada por factores contextuales y relacionales como la familia, el colegio y los pares, todos factores moldeables sobre los cuáles se puede intervenir en Ia medida que se tenga información sobre cómo estos factores afectan el compromiso escolar. Dentro de estos factores contextuaIes se incluyen las dimensiones de contextos: Apoyo recibido de la FAMILIA, Apoyo recibido de PARES y Apoyo recibido de PROFESORES.</div>

<div class="text-justify pt-2"><strong>Apoyo de la familia</strong>: Se refiere a que los/las estudiantes perciben ser apoyados por sus profesores y/o sus familias. La familia del (la) estudiante suele apoyar a su hijo(a) en el proceso de aprendizaje y cuando tiene problemas, ayudándolo con las tareas, conversando lo que sucede en la escuela, animándolo y motivándolo a trabajar bien. El (la) estudiante se siente motivado por sus profesores para aprender y que estos lo ayudan cuando tiene algún problema. El (la) estudiante mantiene en general buenas relaciones con sus profesores. Existe la impresión que los profesores se comportan de acuerdo con Ios valores que enseñan y mantienen un interés por el estudiante como persona y como estudiante, ayudándolo en caso de dificultades. El (la) estudiante considera que sus profesores lo tratan con respeto y lo alientan a realizar nuevamente una tarea si se ha equivocado El (la) estudiante siente que en el colegio se valora la participación de todos.</div>

<div class="text-justify pt-2"><strong>Apoyo de los pares</strong>: Se define como la percepción que tienen los sujetos acerca de las relaciones interpersonales entre compañeros, la preocupación, la confianza y el apoyo que se da entre pares, siendo estos importantes frente a la integración escolar y frente a desafíos escolares y/o cuando tiene una dificultad académica.</div>

<div class="text-justify pt-2"><strong>Apoyo de los profesores</strong>: Se define como la percepción del (la) estudiante acerca del apoyo que recibe de sus profesores.</div>


<div class="pt-1">En el caso de <strong>Compromiso Escolar</strong>, se consideran los siguientes perfiles:</div>

<div class="text-justify pt-2">El <strong>perfil emergente</strong> se refiere cuando el Compromiso Escolar o uno de sus subtipos (afectivo, conductual, cognitivo) es emergente, se considera que requiere atención por parte del establecimiento escolar para revertir esta situación, toda vez que se constituye en un factor de riesgo para la trayectoria educativa del (la) estudiante. Se estima pertinente revisar los factores del contexto que pueden estar determinando esta situación y desplegar estrategias de apoyo en función a dichos factores, junto con realizar un seguimiento personalizado una vez que se despliegan las estrategias de apoyo.</div>

<div class="text-justify pt-2">El <strong>perfil en desarrollo</strong> se refiere cuando el compromiso escolar o uno de sus subtipos (afectivo, conductual, cognitivo) está en desarrollo se considera que, si bien no está en una situación alarmante, sí requiere atención por parte del establecimiento escolar para evitar que se intensifiquen aquellos factores de riesgo y para que se revierta esta situación que, de mantenerse así, puede afectar la trayectoria educativa del (la) estudiante. Se estima pertinente revisar los factores del contexto que pueden estar determinando esta situación y desplegar estrategias de apoyo grupales que permitan a estudiantes que presenten este nivel de compromiso escolar poder recibir un apoyo adicional para prevenir su intensificación. </div>
<div class="text-justify pt-2">El <strong>perfil satisfactorio</strong> se refiere cuando el compromiso escolar o uno de sus subtipos (afectivo, conductual, cognitivo) está en un nivel satisfactorio, se estima que es un factor protector, en la medida que mayores niveles de compromiso estudiantil se vinculan con trayectorias educativas positivas y bajos niveles de conductas de riesgo que pueden culminar en la deserción escolar. Sin embargo, el o la estudiante aún puede presentar un mejor nivel de compromiso. Se estima pertinente levantar estrategias promociónales a nivel de colegio que permitan a estos estudiantes alcanzar el nivel óptimo de compromiso escolar.</div>

<div class="text-justify pt-2 pb-4">El <strong>perfil muy desarrollado</strong> es el nivel óptimo de compromiso, constituyéndose en un factor protector en Ia medida que un alto nivel de compromiso está asociado a trayectorias educativas exitosas y altas tasas de graduación del sistema escolar. </div>

<?php $compromiso_escolar = suma_afectivo_coductual_cognitivo_final($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]);?>

<div class="text-justify pt-5">En el curso <?php echo $_SESSION["curso_nombre"]?>, el <?php echo $compromiso_escolar["porcentaje_afectivo_emergente"]?>% de los estudiantes poseen un perfil emergente de COMPROMISO ESCOLAR AFECTIVO, el <?php echo $compromiso_escolar["porcentaje_afectivo_en_desarrollo"]?>% un perfil en desarrollo, el <?php echo $compromiso_escolar["porcentaje_afectivo_satisfactorio"]?>% un perfil satisfactorio y el <?php echo $compromiso_escolar["porcentaje_afectivo_muy_desarrollado"]?>% un perfil muy desarrollado.
</div>

<div class="float-left">

<div id="grafico_nivel_curso_afectivo" class="mr-6"></div>

</div>

<br><br><br><br>


<div class="text-justify pt-6">En el curso <?php echo $_SESSION["curso_nombre"]?> , el <?php echo $compromiso_escolar["porcentaje_conductual_emergente"]?>% de los estudiantes poseen un perfil emergente de COMPROMISO ESCOLAR CONDUCTUAL, el <?php echo $compromiso_escolar["porcentaje_conductual_en_desarrollo"]?>% un perfil en desarrollo, el <?php echo $compromiso_escolar["porcentaje_conductual_satisfactorio"]?>% un perfil satisfactorio y el <?php echo $compromiso_escolar["porcentaje_conductual_muy_desarrollado"]?>% un
perfil muy desarrollado.</div>

<div class="float-left">
<div  id="grafico_nivel_curso_conductual"></div>
</div>

<div class="text-justify pt-5">En el curso <?php echo $_SESSION["curso_nombre"]?>, el <?php echo $compromiso_escolar["porcentaje_cognitivo_emergente"]?>% de los estudiantes poseen un perfil emergente de COMPROMISO
ESCOLAR COGNITIVO, el <?php echo $compromiso_escolar["porcentaje_cognitivo_en_desarrollo"]?>% un perfil en desarrollo, el <?php echo $compromiso_escolar["porcentaje_cognitivo_satisfactorio"]?>% un perfil satisfactorio y el <?php echo $compromiso_escolar["porcentaje_cognitivo_muy_desarrollado"]?>% un
perfil muy desarrollado.</div>
<br><br>
<div class="float-left">
<div  id="grafico_nivel_curso_cognitivo"></div>
</div>

<div class="text-justify pt-2">En el caso de <strong>Factores Contextuales</strong>, se consideran los siguientes perfiles:</div>


<div class="text-justify pt-2">El <strong>perfil bajo</strong> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por ejemplo, Apoyo recibido de la familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores contextuales presentan un bajo o mediano desarrollo, estos pueden estar afectando o afectar el compromiso escolar en su conjunto o cualquiera de sus subtipos (cognitivo, afectivo, conductual), de allí la importancia de intervenir como establecimiento educacional, de manera tal de revertir dicha situación.</div>

<div class="text-justify pt-2">El <strong>perfil mediano</strong> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por ejemplo, Apoyo recibido de Ia familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores contextuales presentan un bajo o mediano desarrollo, estos pueden estar afectando o afectar el compromiso escolar en su conjunto o cualquiera de sus subtipos (cognitivo, afectivo,
conductual), de allí la importancia de intervenir como establecimiento educacional, de manera tal de revertir dicha situación.
</div>

<div class="text-justify pt-2">El <strong>perfil alto</strong> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por ejemplo, Apoyo recibido de la familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores contextuales presentan un alto o muy alto, desarrollo, estos se constituyen en factores protectores para la trayectoria educativa del estudiante. Ahora, si teniendo el (la) estudiante un puntaje alto o muy alto en estas variables, aún presenta un bajo compromiso
escolar, se requiere evaluar que otros factores contextuales pudiesen estar influyendo en el compromiso escolar del estudiante.
</div>

<div class="text-justify pt-2">El <strong>perfil muy alto</strong> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por ejemplo, Apoyo recibido de Ia familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores contextuales presentan un alto o muy alto, desarrollo, estos se constituyen en factores protectores para la trayectoria educativa del estudiante. Ahora, si teniendo el (la) estudiante un puntaje alto o muy alto en estas variables, aún presenta un bajo compromiso escolar, se requiere evaluar que otros factores contextuales pudiesen estar
influyendo en el compromiso escolar del estudiante.</div>

<?php $factores_contextuales = fc_suma_familiar_pares_profesores($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>


<div class="text-justify pt-2">En el curso <?php echo $_SESSION["curso_nombre"] ?>, el <?php echo $factores_contextuales["porcentaje_familiar_emergente"] ?>% de los estudiantes poseen un perfil bajo de APOYO Y EXPECTATIVAS DE ADULTOS SIGNIFICATIVOS, el <?php echo  $factores_contextuales["porcentaje_familiar_en_desarrollo"]?>% un perfil mediano, el <?php echo $factores_contextuales["porcentaje_familiar_satisfactorio"]?>% un perfil alto y el <?php echo  $factores_contextuales["porcentaje_familiar_muy_desarrollado"]?>% un
perfil muy alto.</div>
<div class="float-left">
<div  id="grafico_nivel_apoyo_familia"></div>
</div>
<div class="text-justify pt-2">En el curso <?php echo $_SESSION["curso_nombre"]?>, el <?php echo $factores_contextuales["porcentaje_profesores_emergente"]?>% de los estudiantes poseen un perfil bajo de APOYO Y EXPECTATIVAS DE PROFESORES, el <?php echo $factores_contextuales["porcentaje_profesores_en_desarrollo"]?>% un perfil mediano, el <?php echo $factores_contextuales["porcentaje_profesores_satisfactorio"]?>% un perfil alto y el <?php echo $factores_contextuales["porcentaje_profesores_muy_desarrollado"]?>% un
perfil muy alto.</div>

<div class="float-left">
<div  id="grafico_nivel_apoyo_profesores"></div>
</div>


<div class="text-justify pt-2">En el curso <?php echo $_SESSION["curso_nombre"]?>, el <?php echo $factores_contextuales["porcentaje_pares_emergente"]?>% de los estudiantes poseen un perfil bajo de APOYO DE PARES, el <?php echo $factores_contextuales["porcentaje_pares_en_desarrollo"]?>% un perfil mediano, el <?php echo $factores_contextuales["porcentaje_pares_satisfactorio"]?>% un perfil alto y el <?php echo $factores_contextuales["porcentaje_pares_muy_desarrollado"]?>% un
perfil muy alto.</div>

<div class="float-left">
<div  id="grafico_nivel_apoyo_pares"></div>
</div>



</div>
<script src="../assets/js/jquery-3.3.1.min.js"></script>
<script src="../assets/js/bootstrap/bootstrap.min.js"></script>
<script src="../assets/librerias/highcharts/highcharts.js"></script>
<script src="../assets/librerias/highcharts/highcharts-more.js"></script>
<script src="dist/js/html2pdf.bundle.min.js"></script>


    <script>

 Highcharts.chart('grafica_radar_curso', {

                    chart: {
                        polar: true,
                        type: 'line',
                        renderTo: 'chart',
                        spacingTop: 0,
                        spacingBottom: 0,
                        spacingLeft: 0,
                        spacingRight: 0,
                        width: 450
                    },
                    legend: {
                        enabled: false
                    },
                    credits: {enabled: false},
                    title: {text: ''},
                    xAxis: {
                        gridLineColor: '#8a8a5c',
                        categories: ['Afectivo', 'Conductual', 'Cognitivo'],
                        tickmarkPlacement: 'on',
                        lineWidth: 0
                    },
                    yAxis: {
                        gridLineInterpolation: 'polygon',
                        gridLineColor: '#8a8a5c',
                        gridLineWidth: 1,
                        lineWidth: 0,
                        max: 145,
                        showLastLabel: true,
                        tickPositions: [<?php echo incrementa_tickposition($ce["demo"]); ?>]
                    },
                    series: [
                        {name: 'Sumatoria',
                            data: [<?php echo $ce["sum_total_afectivo"]; ?>, <?php echo $ce["sum_total_conductual"]; ?>,<?php echo $ce["sum_total_cognitivo"]; ?>],
                            pointPlacement: 'on',
                            color: 'rgb(51, 122, 183)'}
                    ]

                });

                Highcharts.chart('grafica_radar_curso_factores_contextuales', {

chart: {
    polar: true,
    type: 'line',
    renderTo: 'chart',
    spacingTop: 0,
    spacingBottom: 0,
    spacingLeft: 0,
    spacingRight: 0,
    width: 450
},
legend: {
    enabled: false
},
credits: {enabled: false},

title: {text: ''},
xAxis: {
    gridLineColor: '#8a8a5c',
    categories: ['Apoyo Familia', 'Apoyo Pares', 'Apoyo Profesores'],
    tickmarkPlacement: 'on',
    lineWidth: 0
},
yAxis: {
    gridLineInterpolation: 'polygon',
    gridLineColor: '#8a8a5c',
    gridLineWidth: 1,
    lineWidth: 0,
    max: 5,
    showLastLabel: true,
    tickPositions: [300,600,900,1200,1500,1700]
},
series: [
    {name: 'Sumatoria',
        data: [<?php echo $demos_fc["maximo_familiar"]; ?>,<?php echo $demos_fc["maximo_pares"]; ?>, <?php echo $demos_fc["maximo_profesores"]; ?>],
        pointPlacement: 'on',
        color: '#DD4B39'}
]

});


       Highcharts.chart('grafico_nivel_curso_afectivo', {
                    chart: {

                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie',
                        width: 800,
                    },
                    title: {
                        text: 'Afectivo'
                    },

                    credits: {
                        enabled: false,

                    },

                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                distance: 3,
                                enabled: true,
                                format: '{point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                },
                                connectorColor: 'black'
                            }
                        }
                    },
                    series: [{
                            name: 'Estudiantes',
                            data: [
                                {name: 'Emergente',
                                    y: <?php echo $compromiso_escolar["afectivo_emergente"]; ?>,
                                    color: '#fc455c'
                                },
                                {name: 'En Desarrollo',
                                    y: <?php echo $compromiso_escolar["afectivo_en_desarrollo"]; ?>,
                                    color: '#FFD700'
                                },
                                {name: 'Satisfactorio',
                                    y: <?php echo $compromiso_escolar["afectivo_satisfactorio"]; ?>,
                                    color: '#4169E1'
                                },
                                {name: 'Muy Desarrollado',
                                    y: <?php echo $compromiso_escolar["afectivo_muy_desarrollado"]; ?>,
                                    color: '#5CB85C'
                                }

                            ],
                            size: '65%',
                            innerSize: '60%',
                            showInLegend: false,
                            exporting: false,
                            dataLabels: {
                                enabled: true
                            }
                        }],
                    exporting: {
                        enabled: false
                    }
                });

                                // Build the chart
                                Highcharts.chart('grafico_nivel_curso_conductual', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie',
                        width: 800,
                    },
                    title: {
                        text: 'Conductual'
                    },

                    credits: {
                        enabled: false
                    },

                    plotOptions: {
                        pie: {

                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                distance: 6,
                                enabled: true,
                                format: '{point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                },
                                connectorColor: 'black'
                            }
                        }
                    },
                    series: [{
                            name: 'Estudiantes',
                            data: [
                                {name: 'Emergente',
                                    y: <?php echo $compromiso_escolar["conductual_emergente"]; ?>,
                                    color: '#fc455c'
                                },
                                {name: 'En Desarrollo',
                                    y: <?php echo $compromiso_escolar["conductual_en_desarrollo"]; ?>,
                                    color: '#FFD700'
                                },
                                {name: 'Satisfactorio',
                                    y: <?php echo $compromiso_escolar["conductual_satisfactorio"]; ?>,
                                    color: '#4169E1'
                                },
                                {name: 'Muy Desarrollado',
                                    y: <?php echo $compromiso_escolar["conductual_muy_desarrollado"]; ?>,
                                    color: '#5CB85C'
                                }

                            ],
                            size: '65%',
                            innerSize: '60%',
                            showInLegend: false,
                            dataLabels: {
                                enabled: true
                            }
                        }],
                    exporting: {
                        enabled: false
                    }
                });

                    // Build the chart
                    Highcharts.chart('grafico_nivel_curso_cognitivo', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie',
                        width: 800,
                    },
                    title: {
                        text: 'Cognitivo'
                    },

                    credits: {
                        enabled: false
                    },

                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                distance: 3,
                                enabled: true,
                                format: '{point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                },
                                connectorColor: 'black'
                            }
                        }
                    },
                    series: [{
                            name: 'Estudiantes',
                            data: [
                                {name: 'Emergente',
                                    y: <?php echo $compromiso_escolar["cognitivo_emergente"]; ?>,
                                    color: '#fc455c'
                                },
                                {name: 'En Desarrollo',
                                    y: <?php echo $compromiso_escolar["cognitivo_en_desarrollo"]; ?>,
                                    color: '#FFD700'
                                },
                                {name: 'Satisfactorio',
                                    y: <?php echo $compromiso_escolar["cognitivo_satisfactorio"]; ?>,
                                    color: '#4169E1'
                                },
                                {name: 'Muy Desarrollado',
                                    y: <?php echo $compromiso_escolar["cognitivo_muy_desarrollado"]; ?>,
                                    color: '#5CB85C'
                                }

                            ],
                            size: '65%',
                            innerSize: '60%',
                            showInLegend: false,
                            dataLabels: {
                                enabled: true
                            }
                        }],
                    exporting: {
                        enabled: false
                    }
                });
    // Build the chart

        // Build the chart
        Highcharts.chart('grafico_nivel_apoyo_familia', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie',
                        width: 800,
                    },
                    title: {
                        text: 'Apoyo Familiar'
                    },

                    credits: {
                        enabled: false
                    },

                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                distance: 3,
                                enabled: true,
                                format: '{point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                },
                                connectorColor: 'black'
                            }
                        }
                    },
                    series: [{
                            name: 'Estudiantes',
                            data: [
                                {name: 'Bajo',
                                    y: <?php echo $factores_contextuales["apoyo_familiar_emergente"]; ?>,
                                    color: '#fc455c'
                                },
                                {name: 'Mediano',
                                    y: <?php echo $factores_contextuales["apoyo_familiar_en_desarrollo"]; ?>,
                                    color: '#FFD700'
                                },
                                {name: 'Alto',
                                    y: <?php echo $factores_contextuales["apoyo_familiar_satisfactorio"]; ?>,
                                    color: '#4169E1'
                                },
                                {name: 'Muy Alto',
                                    y: <?php echo $factores_contextuales["apoyo_familiar_muy_desarrollado"]; ?>,
                                    color: '#5CB85C'
                                }

                            ],
                            size: '65%',
                            innerSize: '60%',
                            showInLegend: false,
                            dataLabels: {
                                enabled: true
                            }
                        }],
                    exporting: {
                        enabled: false
                    }
                });

                // Build the chart
                Highcharts.chart('grafico_nivel_apoyo_profesores', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie',
                        width: 800,
                    },
                    title: {
                        text: 'Apoyo Profesores'
                    },

                    credits: {
                        enabled: false
                    },

                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                distance: 3,
                                enabled: true,
                                format: '{point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                },
                                connectorColor: 'black'
                            }
                        }
                    },
                    series: [{
                            name: 'Estudiantes',
                            data: [
                                {name: 'Emergente',
                                    y: <?php echo $factores_contextuales["apoyo_profesores_emergente"]; ?>,
                                    color: '#fc455c'
                                },
                                {name: 'En Desarrollo',
                                    y: <?php echo $factores_contextuales["apoyo_profesores_en_desarrollo"]; ?>,
                                    color: '#FFD700'
                                },
                                {name: 'Desarrollado',
                                    y: <?php echo $factores_contextuales["apoyo_profesores_satisfactorio"]; ?>,
                                    color: '#4169E1'
                                },
                                {name: 'Muy Desarrollado',
                                    y: <?php echo $factores_contextuales["apoyo_profesores_muy_desarrollado"]; ?>,
                                    color: '#5CB85C'
                                }

                            ],
                            size: '65%',
                            innerSize: '60%',
                            showInLegend: false,
                            dataLabels: {
                                enabled: true
                            }
                        }],
                    exporting: {
                        enabled: false
                    }
                });
    // Build the chart
    Highcharts.chart('grafico_nivel_apoyo_pares', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie',
                        width: 800,
                    },
                    title: {
                        text: 'Apoyo Pares'
                    },

                    credits: {
                        enabled: false
                    },

                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                distance: 3,
                                enabled: true,
                                format: '{point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                },
                                connectorColor: 'black'
                            }
                        }
                    },
                    series: [{
                            name: 'Estudiantes',
                            data: [
                                {name: 'Emergente',
                                    y: <?php echo $factores_contextuales["apoyo_pares_emergente"]; ?>,
                                    color: '#fc455c'
                                },
                                {name: 'En Desarrollo',
                                    y: <?php echo $factores_contextuales["apoyo_pares_en_desarrollo"]; ?>,
                                    color: '#FFD700'
                                },
                                {name: 'Desarrollado',
                                    y: <?php echo $factores_contextuales["apoyo_pares_satisfactorio"]; ?>,
                                    color: '#4169E1'
                                },
                                {name: 'Muy Desarrollado',
                                    y: <?php echo $factores_contextuales["apoyo_pares_muy_desarrollado"]; ?>,
                                    color: '#5CB85C'
                                }

                            ],
                            size: '65%',
                            innerSize: '60%',
                            showInLegend: false,
                            dataLabels: {
                                enabled: true
                            }
                        }],
                    exporting: {
                        enabled: false
                    }
                });
    // Build the charts

document.addEventListener('DOMContentLoaded', async () => {
  setTimeout(() => {
    const options = {       
    margin: [10, 15, 10, 15],
    filename: 'reporte_curso.pdf',
    image:{ type: 'jpeg', quality: 1 },   
     
  }

  const element = document.getElementById('element-to-print');


html2pdf().from(element).outputPdf().then(function(pdf) {
    //This logs the right base64
    var mi_pdf = (btoa(pdf));
    
    $.ajax({
url: 'pdf.php',
data: {
      pdf:mi_pdf
    
  },
type: 'post',
success:function(){
    window.location.href = "final_pdf.php"
}

});
});

}, 3000);
window.setTimeout(function(){
    
   // window.close();

},4000);
  
})



    </script>
</body>
</html>

<?php
}else{
    header("location:login.php");
    }
?>