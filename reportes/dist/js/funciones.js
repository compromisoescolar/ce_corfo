function deficion_factores_contextuales () {
  alertify.alert("Información adicional", "EL compromiso escolar es una variable altamente influenciada por factores contextuales y relacionales como la familia, el colegio y los pares, todos factores moldeables sobre los cuáles se puede intervenir en Ia medida que se tenga información sobre cómo estos factores afectan eI compromiso escolar.").set('resizable', true).resizeTo('60%', 200);
}

function definicion_apoyo_familia () {
  alertify.alert("Información adicional", "<p class'text-justify'>Se refiere a que los/las estudiantes perciben ser apoyados por sus profesores y/o sus familias.</p>").set('resizable', true).resizeTo('50%', 180);
}

function definicion_apoyo_pares () {
  alertify.alert("Información adicional", "<p class='text-justify'>Se define como la percepción que tienen los sujetos acerca de las relaciones interpersonales\n    entre compañeros, la preocupación, la confianza y el apoyo que se da entre pares, siendo estos importantes frente a\n    la integración escolar y frente a desafíos escolares y/o cuando tiene una dificultad académica.</p>").set('resizable', true).resizeTo('60%', 200);
}

function definicion_apoyo_profesores () {
  alertify.alert("Información adicional", "Se define como la percepción del (la) estudiante acerca del apoyo que recibe de sus profesores.").set('resizable', true).resizeTo('50%', 180);
}

function definicion_compromiso_escolar () {
  alertify.alert("Información adicional", "<p class='text-justify'>El Compromiso Escolar dice relación con la activa participación del (la) estudiante en las\n    actividades académicas, curriculares o extracurriculares. Estudiantes comprometidos encuentran que el aprendizaje es\n    significativo y están motivados y empeñados en su aprendizaje y futuro. El compromiso escolar impulsa al estudiante\n    hacia el aprendizaje, pudiendo ser alcanzado por todas y todos y es altamente influenciable por factores del\n    contexto.Diversos investigadores (Appleton et al., 2008; Bowles et al., 2013; Fredricks et al., 2004, Jimerson et\n    al., 2000; Lyche, 2010) concuerdan que el compromiso escolar es una variable clave en la desercion escolar, ya que\n    el abandonar la escuela no suele ser un acto repentino, sino que la etapa final de un proceso dinámico y acumulativo\n    de una pérdida de compromiso con los estudios (Rumberger, 2001). Por el contrario, cuando los estudiantes\n    desarrollan un compromiso positivo con sus estudios, ellos son más propensos a graduarse con bajos niveles de\n    conductas de riesgo y un alto rendimiento académico.</p>").set('resizable', true).resizeTo('60%', 330);
}

function definicion_compromiso_escolar_promedio (op) {
  var texto = "";
  if (op == 1) {
    
    texto = "EI/ la estudiante presenta indicadores que dan cuenta de una situación de fortaleza para esta dimensión.";
  } else if(op == 2) {
    texto = "EI/la estudiante presenta indicadores que dan cuenta de una situación devulnerabilidad para esta dimensión.";
  }

  alertify.alert(
    "Información adicional", 
    "<p class='text-justify'> " + texto + " </p>"
  ).set(
    'resizable', 
    true
  ).resizeTo(
    '60%', 
    230
  );
}

function definicion_factores_contextuales_promedio (op) {
  var texto = "";
  if (op == 1) {
    
    texto = "EI/ la estudiante presenta indicadores que dan cuenta de una situación de fortaleza para este factor.";
  } else if(op == 2) {
    texto = "EI/la estudiante presenta indicadores que dan cuenta de una situación de vulnerabilidad para este factor.";
  }

  alertify.alert(
    "Información adicional", 
    "<p class='text-justify'> " + texto + " </p>"
  ).set(
    'resizable', 
    true
  ).resizeTo(
    '60%', 
    230
  );
}

function definicion_afectiva () {
  
  alertify.alert("Información adicional", "<p class='text-justify'>El Compromiso Afectivo se define como el nivel de respuesta emocional del estudiante hacia el establecimiento educativo y su proceso de aprendizaje, caracterizado por un sentimiento de involucramiento al colegio y una consideración del colegio como un lugar que es valioso y vale la pena. El compromiso afectivo brinda el incentivo para participar y perseverar. Los estudiantes que están comprometidos afectivamente, se sienten parte de una comunidad escolar y que el colegio es significativo en sus vidas, al tiempo que reconocen que la escuela proporciona herramientas para obtener logros fuera de la escuela. Abarca reacciones hacia los profesores, compañeros y la escuela. Se presume que crea un vínculo con la escuela y una buena disposición hacia el trabajo estudiantil.</p>").set('resizable', true).resizeTo('60%', 300);
}

function definicion_niveles_ce () {
  
  alertify.alert("Definición Niveles ", "<p class='text-justify'>El Compromiso Afectivo se define como el nivel de respuesta emocional del estudiante hacia el establecimiento educativo y su proceso de aprendizaje, caracterizado por un sentimiento de involucramiento al colegio y una consideración del colegio como un lugar que es valioso y vale la pena. El compromiso afectivo brinda el incentivo para participar y perseverar. Los estudiantes que están comprometidos afectivamente, se sienten parte de una comunidad escolar y que el colegio es significativo en sus vidas, al tiempo que reconocen que la escuela proporciona herramientas para obtener logros fuera de la escuela. Abarca reacciones hacia los profesores, compañeros y la escuela. Se presume que crea un vínculo con la escuela y una buena disposición hacia el trabajo estudiantil.</p>").set('resizable', true).resizeTo('60%', 300);
}
function definicion_emergente_ce(){
  
  alertify.alert("Perfil Emergente ", "<p class='text-justify'>El <strong>Perfil Emergente</strong> se refiere cuando el Compromiso Escolar o uno de sus subtipos (afectivo, conductual, cognitivo) es emergente, se considera que requiere atención por parte del establecimiento escolar para revertir esta situación, toda vez que se constituye en un factor de riesgo para la trayectoria educativa del (la) estudiante. Se estima pertinente revisar los factores del contexto que pueden estar determinando esta situación y desplegar estrategias de apoyo en función a dichos factores, junto con realizar un seguimiento personalizado una vez que se despliegan las estrategias de apoyo.</p>").set('resizable', true).resizeTo('60%', 250);
}

function definicion_perfil_en_desarrollo_ce(){
  
  alertify.alert("Perfil En Desarrollo ", "<p class='text-justify'>El <strong>Perfil En Desarrollo</strong> se refiere cuando el compromiso escolar o uno de sus subtipos (afectivo, conductual, cognitivo) está en desarrollo se considera que si bien no está en una situación alarmante, sí requiere atención por parte del establecimiento escolar para evitar que se intensifiquen aquellos factores de riesgo y para que se revierta esta situación que de mantenerse así, puede afectar Ia trayectoria educativa del (la) estudiante. Se estima pertinente revisar los factores del contexto que pueden estar determinando esta situación y desplegar estrategias de apoyo grupales que permitan a estudiantes que presenten este nivel de compromiso escolar poder recibir un apoyo adicional para prevenir su intensificación.</p>").set('resizable', true).resizeTo('60%', 250);
}

function definicion_perfil_satisfactorio_ce(){
  
  alertify.alert("Perfil Satisfactorio ", "<p class='text-justify'>El <strong>Perfil Satisfactorio</strong> se refiere cuando el compromiso escolar o uno de sus subtipos (afectivo, conductual, cognitivo) está en un nivel satisfactorio, se estima que es un factor protector, en la medida que mayores niveles de compromiso estudiantil se vinculan con trayectorias educativas positivas y bajos niveles de conductas de riesgo que pueden culminar en la deserción escolar. Sin embargo, el o la estudiante aún puede presentar un mejor nivel de compromiso. Se estima pertinente levantar estrategias promociónales a nivel de colegio que permitan a estos estudiantes alcanzar el nivel óptimo de compromiso escolar.</p>").set('resizable', true).resizeTo('60%', 250);
}

function definicion_perfil_muy_desarrollado_ce(){
  
  alertify.alert("Perfil Muy Desarrollado ", "<p class='text-justify'>El <strong>Perfil muy Desarrollado</strong> es el nivel óptimo de compromiso, constituyéndose en un factor protector en Ia medida que un alto nivel de compromiso está asociado a trayectorias educativas exitosas y altas tasas de graduación del sistema escolar.</p>").set('resizable', true).resizeTo('50%', 200);
}

function definicion_perfil_bajo_fc(){
  
  alertify.alert("Perfil Bajo ", "<p class='text-justify'>El <strong>Perfil Bajo</strong> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por ejemplo, Apoyo recibido de la familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores contextuales presentan un bajo o mediano desarrollo, estos pueden estar afectando o afectar el compromiso escolar en su conjunto o cualquiera de sus subtipos (cognitivo,afectivo, conductual), de allí la importancia de intervenir como establecimiento educacional, de manera tal de revertir dicha situación.</p>").set('resizable', true).resizeTo('60%', 250);
}

function definicion_perfil_mediano_fc(){
  
  alertify.alert("Perfil Mediano ", "<p class='text-justify'>El <strong>Perfil Mediano</strong> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por ejemplo, Apoyo recibido de Ia familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores contextuales presentan un bajo o mediano desarrollo, estos pueden estar afectando o afectar el compromiso escolar en su conjunto o cualquiera de sus subtipos (cognitivo, afectivo, conductual), de allí la importancia de intervenir como establecimiento educacional, de manera tal de revertir dicha situación.</p>").set('resizable', true).resizeTo('60%', 250);
}

function definicion_perfil_alto_fc(){  
  alertify.alert("Perfil Alto ", "<p class='text-justify'>El <strong>Perfil Alto</strong> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por ejemplo, Apoyo recibido de la familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores contextuales presentan un alto o muy alto, desarrollo, estos se constituyen en factores protectores para la trayectoria educativa del estudiante. Ahora, si teniendo el (la) estudiante un puntaje alto o muy alto en estas variables, aún presenta un bajo compromiso escolar, se requiere evaluar que otros factores contextuales pudiesen estar influyendo en el compromiso escolar del estudiante.</p>").set('resizable', true).resizeTo('60%', 250);
}

function definicion_perfil_muy_alto_fc(){  
  alertify.alert("Perfil muy Alto ", "<p class='text-justify'>El <strong>Perfil muy Alto</strong> los niveles de compromiso escolar son altamente influenciables por factores contextuales, por ejemplo, Apoyo recibido de Ia familia, Apoyo recibido de pares y Apoyo recibido de profesores. Si los factores contextuales presentan un alto o muy alto, desarrollo, estos se constituyen en factores protectores para la trayectoria educativa del estudiante. Ahora, si teniendo el (la) estudiante un puntaje alto o muy alto en estas variables, aún presenta un bajo compromiso escolar, se requiere evaluar que otros factores contextuales pudiesen estar influyendo en el compromiso escolar del estudiante.</p>").set('resizable', true).resizeTo('60%', 250);
}

function definicion_cuadrantes(){

	alertify.alert("Información de cuadrantes de gráfica", "<p class='text-justify'>El primer cuadrante <strong>Alto compromiso escolar y Factores contextuales limitantes (+,-):</strong> Estudiantes que presentan altos niveles de participación e interés en las actividades\n" +
		"académicas. En general consideran que el aprendizaje sea significativo para su presente\n" +
		"y futuro, al tiempo que se sienten parte de una comunidad escolar. Se trata de\n" +
		"estudiantes que presentan una disposición para invertir destrezas cognitivas dentro del\n" +
		"aprendizaje y logran un dominio de habilidades de gran complejidad. EI tener un\n" +
		"compromiso escolar desarrollado es un factor protector que puede facilitar las\n" +
		"trayectorias educativas exitosas y prevenir otras situaciones de riesgo asociadas a la\n" +
		"deserción escolar. EI compromiso escolar es altamente permeable por factores\n" +
		"contextuales. En este caso se aprecia que Ios factores contextuales evaluados (familia,\n" +
		"pares y profesores) se constituyen en factores de riesgo, por lo que se hace necesario\n" +
		"prestar atención pues puede el compromiso escolar verse alterado en el corto plazo si\n" +
		"esos factores de riesgo no se revierten.</p><br>" +
		"<p class='text-justify'>El segundo cuadrante <strong>Alto compromiso escolar y Factores contextuales facilitadores (+,+):</strong>" +
		" Estudiantes que presentan altos niveles de participación e interés en las actividades\n" +
		"académicas. En general consideran que el aprendizaje sea significativo para su presente\n" +
		"y futuro, al tiempo que se sienten parte de una comunidad escolar. Se trata de\n" +
		"estudiantes que presentan una disposición para invertir destrezas cognitivas dentro del\n" +
		"aprendizaje y logran un dominio de habilidades de gran complejidad. EI tener un\n" +
		"compromiso escolar desarrollado es un factor protector que puede facilitar las\n" +
		"trayectorias educativas exitosas y prevenir otras situaciones de riesgo asociadas a la\n" +
		"deserción escolar. EI compromiso escolar es altamente permeable por factores\n" +
		"contextuales, tales como el apoyo de la familia, Ios pares y los profesores. Estas\n" +
		"variables se constituyen en facilitadores del compromiso escolar para este grupo de\n" +
		"estudiantes.</p><br>" +
		"<p class='text-justify'>El tercer cuadrante <strong>Bajo compromiso Escolar y Factores contextuales facilitadores (-,+):</strong>" +
		" Estudiantes que presentan una débil participación e interés en las actividades\n" +
		"académicas. En general no consideran que el aprendizaje sea significativo para su\n" +
		"presente y futuro, al tiempo que no se sienten parte de una comunidad escolar. No hay\n" +
		"una disposición para invertir destrezas cognitivas dentro del aprendizaje y dominio de\n" +
		"nuevas habilidades de gran complejidad. EI no tener un compromiso escolar\n" +
		"desarrollado es un factor de riesgo de Ia deserción escolar, para graduarse con altos\n" +
		"niveles de conductas de riesgo y un bajo rendimiento académico. El compromiso escolar\n" +
		"es altamente permeable por factores contextuales. En este caso se aprecia que Ios\n" +
		"factores contextuales evaluados (familia, pares y profesores) no se constituyen en\n" +
		"factores de riesgo, por lo que se hace necesario evaluar qué otros factores pueden estar\n" +
		"afectando el compromiso escolar de Ios estudiantes, tales como prácticas educativas no\n" +
		"motivantes o precarios servicios de apoyo a la escuela.</p><br>" +
		"<p class='text-justify'>El cuarto cuadrante <strong>Bajo compromiso escolar y Factores contextuales limitantes (-,-);</strong>" +
		" Estudiantes que presentan una débil participación e interés en las actividades\n" +
		"académicas. En general, no consideran que el aprendizaje sea significativo para su\n" +
		"presente y futuro, al tiempo que no se sienten parte de una comunidad escolar. No hay\n" +
		"una disposición para invertir destrezas cognitivas dentro del aprendizaje y dominio de\n" +
		"nuevas habilidades de gran complejidad. EI no tener un compromiso escolar\n" +
		"desarrollado es un factor de riesgo de Ia deserción escolar, para graduarse con altos\n" +
		"niveles de conductas de riesgo y un bajo rendimiento académico. EI compromiso escolar\n" +
		"es altamente permeable por factores contextuales. En este caso se aprecia que el débil\n" +
		"compromiso escolar se vincula a un bajo involucramiento de Ia familia del estudiante en\n" +
		"su proceso de aprendizaje junto con un clima escolar deteriorado (relación con\n" +
		"profesores y pares) lo que termina desmotivando al estudiante. Un clima escolar\n" +
		"deteriorado se puede observar en malas relaciones entre estudiante y profesores, entre\n" +
		"Ios mismos estudiantes, y en un ambiente donde se han deteriorado los lazos de\n" +
		"respeto y confianza." +
		"</p>").set('resizable', true).resizeTo('60%', 460);
}

function referencias_ce_dimensiones_gen() {
	alertify.alert("Tabla de referencia dimensiones Compromiso escolar y factores contextuales","<img src='../../../assets/img/referencia_ce_dimensiones_gen.png' width='600' style='display: block;	margin-left: auto; margin-right: auto;'><p style='margin-top:10px'>La escala utilizada corresponde a valores del 1 al 5, donde 1 corresponde al puntaje más bajo y 5 al puntaje más alto.</p>").set('resizable', true).resizeTo('60%', 250);
}

function referencias_ce_dimensiones() {
	alertify.alert("Tabla de referencia dimensiones Compromiso escolar","<img src='../../../assets/img/referencia_ce_dimensiones.png' width='600' style='display: block;	margin-left: auto; margin-right: auto;'><p style='margin-top:10px'>La escala utilizada corresponde a valores del 1 al 5, donde 1 corresponde al puntaje más bajo y 5 al puntaje más alto.</p>").set('resizable', true).resizeTo('60%', 250);
}

function referencias_fc_dimensiones() {
	alertify.alert("Tabla de referencia dimensiones Factores contextuales","<img src='../../../assets/img/referencia_fc_dimensiones.png' width='600' style='display: block;	margin-left: auto; margin-right: auto;'><p style='margin-top:10px'>La escala utilizada corresponde a valores del 1 al 5, donde 1 corresponde al puntaje más bajo y 5 al puntaje más alto.</p>").set('resizable', true).resizeTo('60%', 250);
}

function referencia_tabla_simbologia() {
	alertify.alert("Referencia tabla comparativa por ítem","<p>Esto significa que en la escala de 1 a 5, se calcula el promedio del curso para esa pregunta.</p>").set('resizable', true).resizeTo('60%', 170);
}

function fortaleza_afectiva(numero) {
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert("Información adicional", "<p class='text-justify'>"+tip_alerta+" \nEl (la) estudiante suele sentirse parte e integrado a su establecimiento escolar, cómodo y\n    orgulloso (a) de estar en dicho establecimiento. A la vez, siente que él o ella es importante para el colegio, que\n    allí es respetado, aceptado, aprecíandose que mantiene relaciones de colaboración y apoyo con sus\n    compañeros.\n</p>\n<br>\n<p class='text-justify'>\n    El (la) estudiante reconoce la importancia y utilidad del colegio y lo que allá aprenden, al\n    tiempo que siente que los profesores se preocupan de lo que se aprende en el colegio sea útil.\n</p>\n<br>\n<p class='text-justify'>\n    El (la) estudiante reconoce que asistir al establecimiento escolar y aprender en clases es importante para conseguir\n    metas\n    futuras, demostrando interés por las tareas académicas y por aprender.\n</p>").set('resizable', true).resizeTo('60%', 300);
}

function alerta_afectiva(numero) {
if(numero === 1){
var tip_alerta = '<strong>El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</strong><br><br>'
}else if(numero === 0){
var tip_alerta = '<strong>El o la estudiante posee alertas en algunos de los siguientes aspectos:</strong><br><br>'
}

  alertify.alert("Información adicional", "<p class='text-justify'>"+tip_alerta+" \n El (la) estudiante no suele sentirse parte ni integrado a su establecimiento escolar. Tampoco se\n    siente cómodo y orgulloso de estar en dicho establecimiento. A la vez, siente que él o ella no es importante para el\n    colegio, que allí no es respetado, aceptado, apreciándose que no mantiene relaciones de colaboración y apoyo con sus\n    compañeros. Lo anterior puede vincularse a una falta de instancias educativas que promuevan la identidad del\n    colegio, la integración entre sus estudiantes y el respeto de los estudiantes independiente de sus\n    diferencias.\n</p>\n<br>\n<p class='text-justify'>\n    El (la) estudiante no reconoce la importancia ni la utilidad del colegio y lo que allí \n    aprenden, al tiempo que siente que los profesores no se preocupan de lo que se aprende en el colegio sea útil. Lo\n    anterior puede vincularse a las prácticas pedagógicas dentro del aula que no permiten al estudiante vislumbrar la\n    importancia de lo que allí se aprende, tales como vincular la materia con problemáticas reales y su experiencia\n    cotidiana.\n</p>\n<br>\n<p class='text-justify'>\n    El (la) estudiante no suele considerar que asistir al establecimiento escolar y aprender en\n    clases es importante para conseguir metas futuras, ni tampoco demuestra interés por las tareas académicas. Lo\n    anterior puede vincularse a las prácticas pedagógicas dentro del aula que no permiten vislumbrar al estudiante la\n    importancia de lo que allí se aprende para alcanzar sus metas futuras.\n</p>").set('resizable', true).resizeTo('60%', 400);
}

function definicion_conductual () {
  alertify.alert("Información adicional", "<p class=\'text-justify\'>El compromiso conductual se basa en la idea de participación en el ámbito academíco y\n    actividades sociales o extracurriculares. El componente conductual del compromiso escolar incluye las interacciones\n    y respuestas del estudiante, dentro de la sala de clases, del colegio y en ambientes extracurriculares. Este aspecto\n    del compromiso escolar va desde un continuo, es decir, desde un involucramiento esperado de manera universal\n    (asistencia diaria) a un involucramiento más intenso (Ej. Participación en el centro de alumnos).</p>").set('resizable', true).resizeTo('60%', 250);
}

function fortaleza_conductual (numero) {
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert("Información adicional", "<p class='text-justify'>"+tip_alerta+" El (la) estudiante suele cumplir con las normas de convivencia escolar y el comportamiento\n    esperado dentro del colegio (Por ej. llega a la hora, no hace la cimarra), razón por la cual no es derivado a\n    inspectorá con frecuencia o son sus apoderados citados.</p>\n<br>\n<p class='text-justify'>\n    El (la) estudiante suele cumplir con el\n    comportamiento esperado dentro de la sala de clases (ej. no pelea con sus compañeros, pide permiso para salir de la\n    sala).\n</p>").set('resizable', true).resizeTo('60%', 300);
}

function alerta_conductual (numero) {
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee alertas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert("Información adicional", "<p class='text-justify'>"+tip_alerta+"\n El (la) estudiante no suele cumplir con las normas de convivencia escolar y el comportamiento\n    esperado dentro del colegio (Por ej. No llega a la hora, o hace la cimarra), razón por la cual es derivado a\n    inspectorá con frecuencia o son sus apoderados. Lo anterior puede vincularse también con reglas poco claras y\n    democráticas, o con una falta de coherencia entre éstas.\n</p>\n<br>\n<p class='text-justify'>\n    El (la) estudiante no suele cumplir con el\n    comportamiento esperado dentro de la sala de clases (ej; pelea con sus compañeros o no pide permiso para salir de la\n    sala). Lo anterior puede vincularse también con prácticas pedagógicas que no promueven la motivan de los estudiantes\n    con la materia, con reglas poco claras o una falta de coherencia entre éstas.\n</p>").set('resizable', true).resizeTo('60%', 300);
}

function definicion_cognitivo () {
  alertify.alert("Información adicional", "<p class='text-justify'>El compromiso cognitivo se basa en la idea de inversión; incorpora la conciencia y voluntad de\n    ejercer el esfuerzo necesario para comprender ideas complejas y desarrollar habilidades difíciles (Appleton et al.,\n    2008; Fredricks, Blumenfeld, y París, 2004). Es la inversión consciente de energía para comprender ideas complejas\n    con la finalidad de ir más allá de los requerimientos mínimos, y le facilita al estudiante el comprender material\n    complejo. Refleja la disposición del estudiante para invertir destrezas cognitivas dentro del aprendizaje y dominio\n    de nuevas habilidades de gran complejidad. Implica ser reflexivo y estar dispuesto a realizar el esfuerzo necesario\n    para la comprensión de ideas complejas y dominar habilidades difíciles. También implica ir más allí de los\n    requerimientos, una preferencia por el desafío y la voluntad de hacer un esfuerzo en las tareas de aprendizaje con\n    estrategias de autorregulación.</p>").set('resizable', true).resizeTo('60%', 350);
}


function fortaleza_cognitiva (numero) {
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert("Información adicional", "<p class='text-justify'>"+tip_alerta+"\n El (la) estudiante suele desplegar estrategias cognitivas profundas de aprendizaje (ej. integra\n    ideas, hace uso de la evidencia, relaciona materia con conocimientos previos, utiliza distintos recursos\n    complementarios, hace resúmenes, mapas conceptuales) en vez de aplicar estrategias superficiales (memorización,\n    repeticiÃ³n). Además, el (la) estudiante suele estar orientado al proceso por sobre el resultado, siendo importante\n    para él o ella lograr entender bien las tareas y la materia, tratar de hacer su trabajo a fondo y bien, en vez de\n    hacerlo por cumplir.\n</p>\n<br>\n<p class='text-justify'>\n    El (la) estudiante conoce qué hábitos y estrategias puede desplegar para realizar bien\n    sus tareas, al tiempo que está consciente de sus dificultades y de lo que tiene que trabajar para obtener mejores\n    calificaciones, como así también de sus propios intereses y motivaciones.\n</p>\n<br>\n<p class='text-justify'>\n    El (la) estudiante suele hacer\n    uso de estrategias de control y autorregulación en su proceso de aprendizaje, tales como panificación, supervisión y\n    autoevaluación. Lo anterior se evidencia cuando el estudiante revisa si sus tareas están bien hechas, si revisa si\n    ha logrado conseguir el objetivo propuesto, si planifica cómo estudiar, si pone atención a la retroalimentación que\n    le entregan de sus trabajos, si busca ayuda cuando no entiende y si monitorea sus progresos.\n</p>").set('resizable', true).resizeTo('60%', 400);
}

function alerta_cognitiva (numero) {
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee alertas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert("Información adicional", "<p class='text-justify'>"+tip_alerta+"\n El (la) estudiante no suele desplegar estrategias cognitivas profundas de aprendizaje (ej.\n    integrar ideas, hacer uso de la evidencia, relacionar materia con conocimientos previos, utilizar distintos recursos\n    complementarios, hacer resúmenes, mapas conceptuales) en vez de aplicar estrategias superficiales (memorización,\n    repetición). Además, el (la) estudiante suele estar más orientado al resultado por sobre el proceso, siendo\n    importante hacer el trabajo por cumplir en vez de lograr entender bien las tareas y la materia. Lo anterior, también\n    puede vincularse con prácticas pedagógicas que no promueven el uso de estrategias profundas de aprendizaje, sino que\n    el uso de estrategias más bien superficiales, siendo los estudiantes evaluados por el uso de estas.\n</p>\n<br>\n<p class='text-justify'>\n    El (la)\n    estudiante presenta dificultades para reconocer lo que sabe y no sabe en relación a las tareas y las estrategias de\n    aprendizaje que puede utilizar para realizar bien sus tareas; al tiempo que demuestra dificultades para reconocer\n    sus propios intereses, motivaciones y lo que tiene que trabajar para obtener mejores calificaciones. Lo anterior\n    puede vincularse con la falta de instancias promovidas por el cuerpo docente para reflexionar sobre las tareas y las\n    estrategias que se utilizan, no existiendo espacios de retroalimentación luego de ser entregada una nota.\n</p>\n<br>\n<p class='text-justify'>\n    El\n    (la) estudiante no suele hacer uso de estrategias de control y autorregulación durante su proceso de aprendizaje,\n    tales como planificación, supervisión y autoevaluación. Lo anterior se evidencia porque el estudiante no suele\n    revisar si sus tareas están bien hechas, si ha logrado conseguir el objetivo propuesto, no suele planificar cómo\n    estudiar ni poner atención a la retroalimentación que le entregan de sus trabajos, no suele buscar ayuda cuando no\n    entiende y no tiende a monitorear sus progresos. Lo anterior puede vincularse a su vez con una falta de instancias\n    promovidas por el establecimiento escolar que permitan al estudiante desarrollar dichas estrategias.\n</p>").set('resizable', true).resizeTo('60%', 450);
}

function for_apoyo_familiar(numero) {
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert('Información adicional',''+tip_alerta+'-La familia del (la) estudiante suele apoyar a su hijo(a) en el proceso de aprendizaje y cuando tiene problemas, ayudándolo con las tareas, conversando Io que sucede en la escuela, animándolo y motivándolo a trabajar bien.').set('resizable',true).resizeTo('60%',200); 
}

function ale_apoyo_familiar(numero) {
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee alertas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert('Información adicional',''+tip_alerta+'-La familia del (la) estudiante no suele apoyar a su hijo(a) en el proceso de aprendizaje o cuando tiene problemas. Tampoco suele apoyarlo en las tareas, motivarlo a trabajar bien o conversar con él o ella sobre Io que sucede en la escuela.').set('resizable',true).resizeTo('60%',200); 
}

function for_apoyo_pares(numero) {
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert('Información adicional',''+tip_alerta+'-Existen buenas relaciones entre los compañeros, se apoyan y se preocupan por él (ella), que puede confiar en sus compañeros, siendo estos importantes para él (ella) y de manera inversa. Se siente integrado y apoyado frente a desafíos escolares y/o cuando tiene una dificultad académica.').set('resizable',true).resizeTo('60%',200); 
}

function ale_apoyo_pares(numero) {
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee alertas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert('Información adicional',''+tip_alerta+'-No existen buenas relaciones entre los compañeros ni que éstos Io apoyan y se preocupan por él (ella). <br><br>-EI (la estudiante) no siente que puede confiar en sus compañeros ni que estos son importantes para él (ella) y de manera inversa. En general no se siente integrado ni apoyado frente a desafíos escolares y/o cuando tiene una dificultad académica.').set('resizable',true).resizeTo('60%',300); 
}

function for_apoyo_docente(numero){
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee fortalezas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert('Información adicional',''+tip_alerta+'El (la) estudiante se siente motivado por sus profesores para aprender y que estos Io ayudan cuando tiene algún problema.<br><br>-EI (la) estudiante mantiene en general buenas relaciones con sus profesores. Existe la impresión de que los profesores mantienen un interés por el estudiante como persona y como estudiante, ayudándolo en caso de dificultades, Io tratan con respeto y lo alientan a realizar nuevamente una tarea si se ha equivocado.').set('resizable',true).resizeTo('60%',300); 
}

function ale_apoyo_docente(numero){
  if(numero === 1){
	var tip_alerta = '<strong>El o la estudiante posee alertas en la mayoria de los siguientes aspectos:</strong><br><br>'
	}else if(numero === 0){
	var tip_alerta = '<strong>El o la estudiante posee alertas en algunos de los siguientes aspectos:</strong><br><br>'
	}
  alertify.alert('Información adicional',''+tip_alerta+'-El (la) estudiante no se siente motivado por sus profesores para aprender y que estos Io ayudan cuando tiene algún problema. <br><br> -EI (la) estudiante no mantiene en general buenas relaciones con sus profesores. Existe la impresión de que los profesores no mantienen mayor interés por el estudiante o Io ayudan en caso de dificultades, no Io tratan con respeto y no Io alientan a realizar nuevamente una tarea si se ha equivocado.<br><br> -El (la) estudiante siente que en el colegio no se valora la participación de todos.').set('resizable',true).resizeTo('60%',340); 
}

function carga_individual_estudiante_factores_contextuales (token_estu, id_docente) {
  $.ajax({
	type: "POST",
	url: "calculo_factor_context.php",
	data: ({
	  "token_estudiante":token_estu,
	  "id_docente": id_docente
	}),
	cache: false,
	statusCode: {
	  404: function () {
		alertify.alert("Pagina no Encontrada");
	  },
	  502: function () {
		alertify.alert("Ha ocurrido un error al conectarse con el servidor");
	  }
	},
	success: function (response) {
	  $("#recibe_resultados_estudiantes_2").html(response);
	}
  });
}

function carga_factores_contextuales_index (var_combo) {
  var_combo.onchange = function () {
	var selected = var_combo.options[var_combo.selectedIndex].value;
	$.ajax({
	  type: "POST",
	  url: "calculo_factor_context.php",
	  data: "token_estudiante=" + selected,
	  cache: false,
	  statusCode: {
		404: function () {
		  alertify.alert("Pagina no Encontrada");
		},
		502: function () {
		  alertify.alert("Ha ocurrido un error al conectarse con el servidor");
		}
	  },
	  success: function (response) {
		console.log(response);
		$("#recibe_resultados_estudiantes_fc").html(response);
	  }

	})

  }
}

function grafica_de_dispersion_curso () {

  alertify.alert("Notificación", "<div id='container_dispersion'></div>").set('resizable', true).resizeTo('70%', 350);

}

function grafica_dispersion_estudiante (dato1) {
  Highcharts.chart('container_dispersionn', {
	  chart: {
		type: 'scatter',
		zoomType: 'xy'
	  },
	  title: {
		text: ''
	  },
	  subtitle: {
		text: ''
	  }, credits: {
		enabled: false
	  },
	  xAxis: {
		title: {
		  enabled: true,
		  text: 'Compromiso Escolar',
		  align: 'low'
		},
		startOnTick: true,
		endOnTick: true,
		showLastLabel: true,
		plotLines: [{
		  value: 172,
		  color: 'red',
		  width: 1,
		  zIndex: 4,
		  dashStyle: 'shortdash'
		}],
		min: 29,
		max: 145
	  },
	  yAxis: {
		title: {
		  text: 'Factores Contextuales',
		  align: 'low'
		},
		plotLines: [{
		  value: 45,
		  color: 'red',
		  width: 1,
		  zIndex: 4,
		  dashStyle: 'shortdash'
		}],
		min: 18,
		max: 90
	   
	  },

	  legend: {
		layout: 'vertical',
		align: 'left',
		verticalAlign: 'top',
		x: 100,
		y: 70,
		floating: true,
		backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
		borderWidth: 1
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
			headerFormat: '<b>{series.name}</b><br>',
			pointFormat: '{point.x} FC, {point.y} CE'
		  }
		}
	  },
	  series: [{
		name: 'Estudiante',
		color: 'rgb(95, 55, 188)',
		data: [dato1]

	  }]
	},

	function (chart) {
	  chart.renderer.label('Alto compromiso escolar y Factores contextuales', 600, 0)
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
	  chart.renderer.label('Alto compromiso escolar y bajos Factores contextuales', 45, 1)
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
	  chart.renderer.label('Bajo compromiso escolar y bajos Factores contextuales', 45, 310)
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
	  chart.renderer.label('Bajo compromiso escolar y bajo Factores contextuales', 600, 310)
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
  );


}

function screen_grafica_radar_curso_ce(afec,conductual,cognitivo,p_afect,p_cond,p_cog){
if (afec <= 1400 || conductual <= 1400 || cognitivo <= 1400){
  var posicion = [100,400,700,1000,1300,1700,2000];
}else if(afec >= 1401 || conductual >= 1401 || cognitivo >= 1401){
  var posicion = [200,500,800,1100,1400,1700,2000,2300];
}
  var options = {
	chart: {
		polar: true,
		type: 'line',
		renderTo: 'chart',
		spacingTop: 0,
		spacingBottom: 0,
		spacingLeft: 0,
		spacingRight: 0,
		width: 500
	},
	legend: {
		enabled: false
	},
	credits: {enabled: false},
	title: {text: 'Compromiso Escolar'},
	xAxis: {
		gridLineColor: '#00C0EF',
		categories: ['Afectivo '+p_afect+'', 'Conductual '+p_cond+'', 'Cognitivo '+p_cog+''],
		tickmarkPlacement: 'on',
		lineWidth: 0,
		labels: {

		  style: {
			  fontSize: '14px'
			  
		  }}
	},
	yAxis: {
		gridLineInterpolation: 'polygon',
		gridLineColor: '#8a8a5c',
		gridLineWidth: 1,
		lineWidth: 0,
		max: 145,
		showLastLabel: true,
		tickPositions: posicion,
		labels: {
				enabled: false
			}
	},
	series: [
		{name: 'Sumatoria',
			data: [afec,conductual ,cognitivo],
			pointPlacement: 'on',
			color: 'rgb(51, 122, 183)'}
	]

};

  // URL to Highcharts export server
var exportUrl = 'https://export.highcharts.com/';

// POST parameter for Highcharts export server
var object = {
	options: JSON.stringify(options),
	type: 'image/png',
	async: true
};

// Ajax request
$.ajax({
	type: 'post',
	url: exportUrl,
	data: object,
	success: function (data) {
		//Submit data from your server
		 // Ajax request
		$.ajax({
			type: 'post',
			url: 'partes/radar_curso.php',//this your local file
			data: {'url' : exportUrl+data},
		});
	}
});
}

function screen_grafica_radar_curso_fc(fami,pares,docentes,p_fami,p_pares,p_doc){

  var options = {
	chart: {
		polar: true,
		type: 'line',
		renderTo: 'chart',
		spacingTop: 0,
		spacingBottom: 0,
		spacingLeft: 0,
		spacingRight: 0,
		width: 500
	},
	legend: {
		enabled: false
	},
	credits: {enabled: false},
	title: {text: 'Factores Contextuales'},
	xAxis: {
		gridLineColor: '#8a8a5c',
		categories: ['Apoyo Familia '+p_fami+'', 'Apoyo Pares '+p_pares+'', 'Apoyo Profesores '+p_doc+''],
		tickmarkPlacement: 'on',
		lineWidth: 0,
		gridLineWidth: 2, 
		labels: {

		  style: {
			  fontSize: '14px'
			  
		  }} 
	},
	yAxis: {
		gridLineInterpolation: 'polygon',
		gridLineColor: '#8a8a5c',
		gridLineWidth: 1,
		lineWidth: 0,
		max: 145,
		showLastLabel: true,
		tickPositions: [200,400,600,800,1000,1200],
		labels: {
				enabled: false
			}
	},
	series: [
		{name: 'Sumatoria',
			data: [fami,pares ,docentes],
			pointPlacement: 'on',
			color: '#DD4B39'}
	]

};

  // URL to Highcharts export server
var exportUrl = 'https://export.highcharts.com/';

// POST parameter for Highcharts export server
var object = {
	options: JSON.stringify(options),
	type: 'image/png',
	async: true
};

// Ajax request
$.ajax({
	type: 'post',
	url: exportUrl,
	data: object,
	success: function (data) {
		//Submit data from your server
		 // Ajax request
		$.ajax({
			type: 'post',
			url: 'partes/radar_curso_fc.php',//this your local file
			data: {'url' : exportUrl+data},
		});
	}
});
}

function screen_grafica_nivel_curso_afectivo(emer,desarrollo,satisfac,muy_desarrolla){
  var options = {
	chart: {
	  plotBackgroundColor: null,
	  plotBorderWidth: null,
	  plotShadow: false,
	  type: 'pie'
  },
  title: {
	  text: ''
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
				  y: emer,
				  color: '#fc455c'
				
			  },
			  {name: 'En Desarrollo',
				  y: desarrollo,
				  color: '#FFD700'
			  },
			  {name: 'Satisfactorio',
				  y: satisfac,
				  color: '#4169E1'
			  },
			  {name: 'Muy Desarrollado',
				  y: muy_desarrolla,
				  color: '#5CB85C'
			  }

		  ],
		  size: '65%',
		  innerSize: '60%',
		  showInLegend: true,
		  exporting: false,
		  dataLabels: {
			  enabled: true
		  }
	  }],
  exporting: {
	  enabled: false
  }
};

  // URL to Highcharts export server
var exportUrl = 'https://export.highcharts.com/';

// POST parameter for Highcharts export server
var object = {
	options: JSON.stringify(options),
	type: 'image/png',
	async: true
};

// Ajax request
$.ajax({
	type: 'post',
	url: exportUrl,
	data: object,
	success: function (data) {
		//Submit data from your server
		 // Ajax request
		$.ajax({
			type: 'post',
			url: 'partes/grafi_nivel_afectivo.php',//this your local file
			data: {'url' : exportUrl+data},
		});
	}
});
}
function screen_grafica_nivel_curso_conductual(emer,desarrollo,satisfac,muy_desarrolla){
  var options = {
	chart: {
	  plotBackgroundColor: null,
	  plotBorderWidth: null,
	  plotShadow: false,
	  type: 'pie'
  },
  title: {
	  text: ''
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
				  y: emer,
				  color: '#fc455c'
			  },
			  {name: 'En Desarrollo',
				  y: desarrollo,
				  color: '#FFD700'
			  },
			  {name: 'Satisfactorio',
				  y: satisfac,
				  color: '#4169E1'
			  },
			  {name: 'Muy Desarrollado',
				  y: muy_desarrolla,
				  color: '#5CB85C'
			  }

		  ],
		  size: '65%',
		  innerSize: '60%',
		  showInLegend: true,
		  exporting: false,
		  dataLabels: {
			  enabled: true
		  }
	  }],
  exporting: {
	  enabled: false
  }
};

  // URL to Highcharts export server
var exportUrl = 'https://export.highcharts.com/';

// POST parameter for Highcharts export server
var object = {
	options: JSON.stringify(options),
	type: 'image/png',
	async: true
};

// Ajax request
$.ajax({
	type: 'post',
	url: exportUrl,
	data: object,
	success: function (data) {
		//Submit data from your server
		 // Ajax request
		$.ajax({
			type: 'post',
			url: 'partes/grafi_nivel_conductual.php',//this your local file
			data: {'url' : exportUrl+data},
		});
	}
});
}
function screen_grafica_nivel_curso_cognitivo(emer,desarrollo,satisfac,muy_desarrolla){
  var options = {
	chart: {
	  plotBackgroundColor: null,
	  plotBorderWidth: null,
	  plotShadow: false,
	  type: 'pie'
  },
  title: {
	  text: ''
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
				  y: emer,
				  color: '#fc455c'
			  },
			  {name: 'En Desarrollo',
				  y: desarrollo,
				  color: '#FFD700'
			  },
			  {name: 'Satisfactorio',
				  y: satisfac,
				  color: '#4169E1'
			  },
			  {name: 'Muy Desarrollado',
				  y: muy_desarrolla,
				  color: '#5CB85C'
			  }

		  ],
		  size: '65%',
		  innerSize: '60%',
		  showInLegend: true,
		  exporting: false,
		  dataLabels: {
			  enabled: true
		  }
	  }],
  exporting: {
	  enabled: false
  }
};

  // URL to Highcharts export server
var exportUrl = 'https://export.highcharts.com/';

// POST parameter for Highcharts export server
var object = {
	options: JSON.stringify(options),
	type: 'image/png',
	async: true
};

// Ajax request
$.ajax({
	type: 'post',
	url: exportUrl,
	data: object,
	success: function (data) {
		//Submit data from your server
		 // Ajax request
		$.ajax({
			type: 'post',
			url: 'partes/grafi_nivel_cognitivo.php',//this your local file
			data: {'url' : exportUrl+data},
		});
	}
});
}

function screen_grafica_nivel_curso_familiar(emer,desarrollo,satisfac,muy_desarrolla){
  var options = {
	chart: {
	  plotBackgroundColor: null,
	  plotBorderWidth: null,
	  plotShadow: false,
	  type: 'pie'
  },
  title: {
	  text: ''
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
			  {name: 'Bajo',
				  y: emer,
				  color: '#fc455c'
			  },
			  {name: 'Mediano',
				  y: desarrollo,
				  color: '#FFD700'
			  },
			  {name: 'Alto',
				  y: satisfac,
				  color: '#4169E1'
			  },
			  {name: 'Muy Alto',
				  y: muy_desarrolla,
				  color: '#5CB85C'
			  }

		  ],
		  size: '65%',
		  innerSize: '60%',
		  showInLegend: true,
		  exporting: false,
		  dataLabels: {
			  enabled: true
		  }
	  }],
  exporting: {
	  enabled: false
  }
};

  // URL to Highcharts export server
var exportUrl = 'https://export.highcharts.com/';

// POST parameter for Highcharts export server
var object = {
	options: JSON.stringify(options),
	type: 'image/png',
	async: true
};

// Ajax request
$.ajax({
	type: 'post',
	url: exportUrl,
	data: object,
	success: function (data) {
		//Submit data from your server
		 // Ajax request
		$.ajax({
			type: 'post',
			url: 'partes/grafi_nivel_familiar.php',//this your local file
			data: {'url' : exportUrl+data},
		});
	}
});
}

function screen_grafica_nivel_curso_pares(emer,desarrollo,satisfac,muy_desarrolla){
  var options = {
	chart: {
	  plotBackgroundColor: null,
	  plotBorderWidth: null,
	  plotShadow: false,
	  type: 'pie'
  },
  title: {
	  text: ''
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
			  {name: 'Bajo',
				  y: emer,
				  color: '#fc455c'
			  },
			  {name: 'Mediano',
				  y: desarrollo,
				  color: '#FFD700'
			  },
			  {name: 'Alto',
				  y: satisfac,
				  color: '#4169E1'
			  },
			  {name: 'Muy Alto',
				  y: muy_desarrolla,
				  color: '#5CB85C'
			  }

		  ],
		  size: '65%',
		  innerSize: '60%',
		  showInLegend: true,
		  exporting: false,
		  dataLabels: {
			  enabled: true
		  }
	  }],
  exporting: {
	  enabled: false
  }
};

  // URL to Highcharts export server
var exportUrl = 'https://export.highcharts.com/';

// POST parameter for Highcharts export server
var object = {
	options: JSON.stringify(options),
	type: 'image/png',
	async: true
};

// Ajax request
$.ajax({
	type: 'post',
	url: exportUrl,
	data: object,
	success: function (data) {
		//Submit data from your server
		 // Ajax request
		$.ajax({
			type: 'post',
			url: 'partes/grafi_nivel_pares.php',//this your local file
			data: {'url' : exportUrl+data},
		});
	}
});
}

function screen_grafica_nivel_curso_profesores(emer,desarrollo,satisfac,muy_desarrolla){
  var options = {
	chart: {
	  plotBackgroundColor: null,
	  plotBorderWidth: null,
	  plotShadow: false,
	  type: 'pie'
  },
  title: {
	  text: ''
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
			  {name: 'Bajo',
				  y: emer,
				  color: '#fc455c'
			  },
			  {name: 'Mediano',
				  y: desarrollo,
				  color: '#FFD700'
			  },
			  {name: 'Alto',
				  y: satisfac,
				  color: '#4169E1'
			  },
			  {name: 'Muy Alto',
				  y: muy_desarrolla,
				  color: '#5CB85C'
			  }

		  ],
		  size: '65%',
		  innerSize: '60%',
		  showInLegend: true,
		  exporting: false,
		  dataLabels: {
			  enabled: true
		  }
	  }],
  exporting: {
	  enabled: false
  }
};

  // URL to Highcharts export server
var exportUrl = 'https://export.highcharts.com/';

// POST parameter for Highcharts export server
var object = {
	options: JSON.stringify(options),
	type: 'image/png',
	async: true
};

// Ajax request
$.ajax({
	type: 'post',
	url: exportUrl,
	data: object,
	success: function (data) {
		//Submit data from your server
		 // Ajax request
		$.ajax({
			type: 'post',
			url: 'partes/grafi_nivel_profesores.php',//this your local file
			data: {'url' : exportUrl+data},
		});
	}
});
}
function grafica_dispersion_estudi(size, dato1, dato2,token) {
 console.log(size);
 window.value=size;

 var opts = {
  chart: {
    type: 'scatter'
  },
  title: {
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
    layout: 'vertical',
    align: 'left',
    verticalAlign: 'bottom',
    x: 450,
    y: 10,
    floating: true,
    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
   
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
      headerFormat: '<b>{series.name}</b><br>',
      pointFormat: '{point.x} FC, {point.y} CE'
    }
    }
  },
  series: [{
    name: 'Estudiante',
    color: 'rgb(95, 55, 188)',
    data: [[dato1, dato2]]

  }],
  exporting: {
    enabled: false,
    fallbackToExportServer: false
  }
    
  };

  var grafico = Highcharts.chart(
    'demo_dispersion_alumno', 
    opts, 
    function (chart) {
    console.log(window.value - 200);
    
    // aqui mod
    chart.renderer.label('Alto compromiso escolar y alto Factores contextuales', size - 230, 0)
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
    chart.renderer.label('Alto compromiso escolar y bajos Factores contextuales', 100, 1)
    .attr({
      fill: 'rgb(206, 225, 255)',
      padding: 4,
      r: 1,
      zIndex: 1
    })
    .css({
      color: 'black',
      width: '200px'
    })
    .add();
    chart.renderer.label('Bajo compromiso escolar y bajos Factores contextuales', 100, 310)
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
    chart.renderer.label('Bajo compromiso escolar y bajo Factores contextuales', size - 230, 310)
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
  );

  

  var options ={
	chart: {
	  type: 'scatter'
	},
	title: {
	  text: ''
	},
	 credits: {
	  enabled: false
	},
	xAxis: {
	  title: {
		enabled: true,
		text: 'Factores Contextuales',
		align: 'low'
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
		align: 'low'
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
	  layout: 'vertical',
	  align: 'left',
	  verticalAlign: 'bottom',
	  x: 450,
	  y: 10,
	  floating: true,
	  backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
	 
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
		  headerFormat: '<b>{series.name}</b><br>',
		  pointFormat: '{point.x} FC, {point.y} CE'
		}
	  }
	},
	series: [{
	  name: 'Estudiante',
	  color: 'rgb(95, 55, 188)',
	  data: [[dato1, dato2]]

	}],              
	exporting: {
    
		sourceWidth: 900,
		sourceHeight: 400,
		// scale: 2 (default)
		chartOptions: {
			subtitle: null
		}
	},


  function (chart) {
    
    // aqui mod
    chart.renderer.label('Alto compromiso escolar y alto Factores contextuales', size - 230, 0)
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
    chart.renderer.label('Alto compromiso escolar y bajos Factores contextuales', 100, 1)
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
    chart.renderer.label('Bajo compromiso escolar y bajos Factores contextuales', 100, 310)
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
    chart.renderer.label('Bajo compromiso escolar y bajo Factores contextuales', size - 230, 310)
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
  }

};

// URL to Highcharts export server
var exportUrl = 'https://export.highcharts.com/';
var nombre = token;
// POST parameter for Highcharts export server
var object = {
	options: JSON.stringify(opts),
	type: 'image/png',
	async: true
};

// Ajax request
$.ajax({
	type: 'post',
	url: exportUrl,
	data: object,
	success: function (data) {
		//Submit data from your server
		 // Ajax request
		$.ajax({
			type: 'post',
			url: 'guarda_imagen.php',//this your local file
			data: {'url' : exportUrl+data,'definido': 'estudiante','nombre':nombre},
		});
	}
});
  
return grafico;
}
function radar_curso_ce(token,profesor) {
  //la diferencia es que acepta a token como el id del establecimiento
  html2canvas($('#grafica_radar_curso'), {
  onrendered: function (canvas) {
	  var imagedata = canvas.toDataURL('image/png');
	  var nombre = token;
	  var definido = 'curso';
	  var demo = 'ce';
var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
//ajax call to save image inside folder
$.ajax({
url: 'guarda_imagen.php',
data: {
	  imgdata:imgdata,
	  nombre:nombre,
	  definido:definido,
	  demo:demo
  },
type: 'post'

});
  }
});
}

function radar_curso_fc(token,profesor) {
  //la diferencia es que acepta a token como el id del establecimiento
  html2canvas($('#grafica_radar_curso_factores_contextuales_photos'), {
  onrendered: function (canvas) {
	  var imagedata = canvas.toDataURL('image/png');
	  var nombre = token;     
	  var definido = 'curso';
	  var demo = 'fc';
var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
//ajax call to save image inside folder
$.ajax({
url: 'guarda_imagen.php',
data: {
	  imgdata:imgdata,
	  nombre:nombre,
	  definido:definido,
	  demo:demo
  },
type: 'post'

});
  }
});
}

function torta_conductual_CE(token,profesor) {
  //la diferencia es que acepta a token como el id del establecimiento
  html2canvas($('#grafico_nivel_curso_conductual_photo'), {
  onrendered: function (canvas) {
	  var imagedata = canvas.toDataURL('image/png');
	  var nombre = token;     
	  var definido = 'curso';
	  var demo = 'conductual_curso';
var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
//ajax call to save image inside folder
$.ajax({
url: 'guarda_imagen.php',
data: {
	  imgdata:imgdata,
	  nombre:nombre,
	  definido:definido,
	  demo:demo
  },
type: 'post'

});
  }
});
}
function torta_afectivo_CE(token,profesor) {
  //la diferencia es que acepta a token como el id del establecimiento
  html2canvas($('#grafico_nivel_curso_afectivo_photos'), {
  onrendered: function (canvas) {
	  var imagedata = canvas.toDataURL('image/png');
	  var nombre = token;     
	  var definido = 'curso';
	  var demo = 'afectivo_curso';
var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
//ajax call to save image inside folder
$.ajax({
url: 'guarda_imagen.php',
data: {
	  imgdata:imgdata,
	  nombre:nombre,
	  definido:definido,
	  demo:demo
  },
type: 'post'

});
  }
});
}

function torta_cognitivo_CE(token,profesor) {
  //la diferencia es que acepta a token como el id del establecimiento
  html2canvas($('#grafico_nivel_curso_cognitivo_photos'), {
  onrendered: function (canvas) {
	  var imagedata = canvas.toDataURL('image/png');
	  var nombre = token;     
	  var definido = 'curso';
	  var demo = 'cognitivo_curso';
var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
//ajax call to save image inside folder
$.ajax({
url: 'guarda_imagen.php',
data: {
	  imgdata:imgdata,
	  nombre:nombre,
	  definido:definido,
	  demo:demo
  },
type: 'post'

});
  }
});
}

function torta_apoyo_familia_fc(token,profesor) {
  //la diferencia es que acepta a token como el id del establecimiento
  html2canvas($('#grafico_nivel_apoyo_familia_photos'), {
  onrendered: function (canvas) {
	  var imagedata = canvas.toDataURL('image/png');
	  var nombre = token;     
	  var definido = 'curso';
	  var demo = 'familia_curso';
var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
//ajax call to save image inside folder
$.ajax({
url: 'guarda_imagen.php',
data: {
	  imgdata:imgdata,
	  nombre:nombre,
	  definido:definido,
	  demo:demo
  },
type: 'post'

});
  }
});
}

function torta_apoyo_pares_fc(token,profesor) {
  //la diferencia es que acepta a token como el id del establecimiento
  html2canvas($('#grafico_nivel_apoyo_pares_photos'), {
  onrendered: function (canvas) {
	  var imagedata = canvas.toDataURL('image/png');
	  var nombre = token;     
	  var definido = 'curso';
	  var demo = 'pares_curso';
var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
//ajax call to save image inside folder
$.ajax({
url: 'guarda_imagen.php',
data: {
	  imgdata:imgdata,
	  nombre:nombre,
	  definido:definido,
	  demo:demo
  },
type: 'post'

});
  }
});
}

function torta_apoyo_profesores_fc(token,profesor) {
  //la diferencia es que acepta a token como el id del establecimiento
  html2canvas($('#grafico_nivel_apoyo_profesores_photos'), {
  onrendered: function (canvas) {
	  var imagedata = canvas.toDataURL('image/png');
	  var nombre = token;     
	  var definido = 'curso';
	  var demo = 'profesores_curso';
var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
//ajax call to save image inside folder
$.ajax({
url: 'guarda_imagen.php',
data: {
	  imgdata:imgdata,
	  nombre:nombre,
	  definido:definido,
	  demo:demo
  },
type: 'post'

});
  }
});
}

function ver_dispersion(){
  $([document.documentElement, document.body]).animate({
	scrollTop: $("#ver_dispersion").offset().top

}, 1500);
$('#demo_dispersion_alumno').removeAttr('hidden');
}
function subir_a_cabezera(){
  $([document.documentElement, document.body]).animate({
	scrollTop: $("#subir_head").offset().top
   
}, 1500);

}

if(typeof(grecaptcha) != "undefined") {
	grecaptcha.ready(function() {
		grecaptcha.execute('6Le4kagZAAAAAPrJvezXbADOrTQVxo69xZg1cyK6', {action: 'submit'}).then(function(token) {
			$('#token').val(token); // here i set value to hidden field
		});
	});
}

function login_final(){
	$('#inicia_reporte').submit(function (e) {
		e.preventDefault();
		if(typeof(grecaptcha) != "undefined") {
			grecaptcha.ready(function() {
				grecaptcha.execute('6Le4kagZAAAAAPrJvezXbADOrTQVxo69xZg1cyK6', {action: 'submit'}).then(function(token) {
					$('#token').val(token); // here i set value to hidden field
				});
			});
		}
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
  }else{
	cadena = "usuario=" + $('#usuario').val() +
						"&contrasena=" + $('#contrasena').val()+
						"&tipo_usuario=" + $('#tipo_usuario').val()+
						"&privilegios=" + "0" + 
						"&token=" + $("#token").val();
						$.ajax({
						  type: "POST",
						  url: "../php/valida_login.php",
						  data: cadena,
						  cache: false,
						  statusCode: {
							404: function () {
								alertify.alert("Alerta","Pagina no Encontrada");
								document.getElementById("ingresar_rep").disabled =false;
								  document.getElementById("spinner").innerHTML = '';
								  document.getElementById("inicia_rep").innerHTML = 'Ingresar';
		
							},
							502: function () {
								alertify.alert("alerta","Ha ocurrido un error al conectarse con el servidor");
								document.getElementById("ingresar_rep").disabled =false;
								  document.getElementById("spinner").innerHTML = '';
								  document.getElementById("inicia_rep").innerHTML = 'Ingresar';
		
							}
						},
						  beforeSend: function(){
							  document.getElementById("ingresar_rep").disabled =true;
							  document.getElementById("inicia_rep").innerHTML = '';
							  document.getElementById("spinner").innerHTML = 'Cargando... <i class="fa fa-spinner fa-2x fa-spin  fa-fw">';
						  },
						  success: function (r) {
							  if (r == 1) {
								  window.location.href = "../modulos.php";
							  }
							  if (r == 0) {
								  document.getElementById("ingresar_rep").disabled =false;
								  document.getElementById("spinner").innerHTML = '';
								  document.getElementById("inicia_rep").innerHTML = 'Ingresar';
								  alertify.defaults.glossary.title = '<p class="text-center">Notificación<p>';
								  alertify.alert('Usuario Incorrecto');
							  }
							  else if (r == -1) {
								document.getElementById("ingresar_rep").disabled = false;
								document.getElementById("spinner").innerHTML = '';
								document.getElementById("inicia_rep").innerHTML = 'Ingresar';
								alertify.defaults.glossary.title = '<p class="text-center">Notificación<p>';
								alertify.alert('Error, captcha inválido');
							}
						  }
					  });
  
  }
   
  })
   
	  
}


function login_admin(){
  url_base = window.location;
  dir = url_base.protocol + "//" + url_base.host + "/valida_login.php";
  $('#ingresar_admin').on("click",function () {
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
  }else{
	cadena = "usuario=" + $('#usuario').val() +
	"&contrasena=" + $('#contrasena').val()+
	"&tipo_usuario=" + $('#tipo_usuario').val()+
	"&privilegios=" + "1";
	$.ajax({
	  type: "POST",
	  url: dir,
	  data: cadena,
	  cache: false,
	  statusCode: {
		404: function () {
			alertify.alert("Alerta","Pagina no Encontrada");
			document.getElementById("ingresar_admin").disabled =false;
			  document.getElementById("spinner").innerHTML = '';
			  document.getElementById("inicia_admin").innerHTML = 'Ingresar';

		},
		502: function () {
			alertify.alert("alerta","Ha ocurrido un error al conectarse con el servidor");
			document.getElementById("ingresar_admin").disabled =false;
			  document.getElementById("spinner").innerHTML = '';
			  document.getElementById("inicia_admin").innerHTML = 'Ingresar';

		}
	},
	  beforeSend: function(){
		  document.getElementById("ingresar_admin").disabled =true;
		  document.getElementById("inicia_admin").innerHTML = '';
		  document.getElementById("spinner").innerHTML = '</i> <i class="fa fa-spinner fa-2x fa-spin  fa-fw">';
	  },
	  success: function (r) {
		  if (r == 1) {
			  window.location.href = "../modulos.php";
		  } else {
			  document.getElementById("ingresar_admin").disabled =false;
			  document.getElementById("spinner").innerHTML = '';
			  document.getElementById("inicia_admin").innerHTML = 'Ingresar';
			  alertify.defaults.glossary.title = '<p class="text-center">Notificación<p>';
			  alertify.alert('Usuario Incorrecto');
			  

		  }
	  }
  });

}
   
  })
   
	  
}
function zoom() {
  document.body.style.zoom = "99%";
}


/// GRAFICA SUMATORIA COMPROMISO ESCOLAR 
function grafica_radar_compromiso_escolar (sum_afectivo, sum_conductual,sum_cognitivo){
  Highcharts.chart('grafica_radar_curso',{
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
	  legend :{
		  enabled: false
	  },
	  credits: {enabled: false},
   title: {text: ''},
   xAxis :{
   gridLineColor: '#8a8a5c',
   categories: ['Afectivo', 'Conductual', 'Cognitivo'],
	tickmarkPlacement: 'on',
	lineWidth: 0
   },
   yAxis:{
	  gridLineInterpolation: 'polygon',
	  gridLineColor: '#8a8a5c',
		gridLineWidth: 1,
		lineWidth: 0,
		max: 5,
		showLastLabel: true,
		tickPositions: [100,200,300,400,500,600,700]
   },
   series:[
	  {name: 'Sumatoria',
	  data:[sum_afectivo,sum_conductual,sum_cognitivo],
	  pointPlacement: 'on',
	  color: 'rgb(51, 122, 183)'}
   ]
  
  });
}

function cargando(){
  $(document).ready(function(){     
	setTimeout(function(){   $('#loading').hide();  }, 5000);
  });

}

function sesion(){
  var cierraSesionIrLogeo = "../salir"; 
//la sessión durara 30 minutos y luego se destruira

//300000 1800000
setTimeout(function(){ location.href=cierraSesionIrLogeo; }, 1800000); 
}

function llena_profesores_admin(){
  $(document).ready(function(){
	  $("#id_profesor").change(function () {
		  $("#id_profesor option:selected").each(function () {
			  id_establecimiento = $(this).val();
			  tipo = "llena_cursos"
			  $.post("../../php/llena_select.php", { id_establecimiento: id_establecimiento, tipo: tipo  }, function(data){
				  $("#id_curso").html(data);
			  });            
		  });
	  })
  });
}

function carga_excel_admin(){
  $(document).ready(function(){
	  $(function(){
		  $("#formulario_excel").on("submit", function(e){
			  e.preventDefault();
			  var f = $(this);
			  var formData = new FormData(document.getElementById("formulario_excel"));
			  formData.append("dato", "valor");

			  var id_profesor = $("#id_profesor").val();
			  var id_curso = $("#id_curso").val();
			  
			 if( id_profesor == null || id_curso == null){
			  alertify.alert("Notificación","Los campos son abligatorios")
			 }
			 else{
			  $.ajax({
				  url: "carga_excel.php",
				  type: "post",
				  dataType: "html",
				  data: formData,
				  cache: false,
				  contentType: false,
		   processData: false,
		   beforeSend: function(){
			  $("#boton_subir_excel").attr('disabled', true);
			  document.getElementById("dowload").innerHTML = ('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only"></span>');
			 
		   },
		   success: function(response){
			   console.log(response);
			 var respuesta = (JSON.parse(response));
			 if(respuesta.estado === "1"){
			  $("#boton_subir_excel").attr('disabled', false);
				 $('#file').val('');
			  alertify.set('notifier','position', 'center-top');
			  alertify.success("<div class='text-white text-center'>Carga Existosa</div>");
				 document.getElementById("dowload").innerHTML = (respuesta.archivo);
				 $("#curso_establecimiento_selec").load("../../lista_curso_establecimientos_admin.php");
			 
			 }
			 else if(respuesta.estado === "0"){
			  $("#boton_subir_excel").attr('disabled', false);
				 $('#file').val('');
				 document.getElementById("dowload").innerHTML = ('');
			  alertify.set('notifier','position', 'center-top');
			  alertify.error("<div class='text-white text-center'>Error al realizar la carga</div>");
			  $("#curso_establecimiento_selec").load("../../lista_curso_establecimientos_admin.php");
			 
			 }
			 else if(respuesta.estado === "2"){
			  $("#boton_subir_excel").attr('disabled', false);
				 $('#file').val('');
				 document.getElementById("dowload").innerHTML = ('');
			  alertify.set('notifier','position', 'center-top');
			  alertify.error("<div class='text-white text-center'>La carga asociada al docente ya se a realizado</div>");
				
			  $("#curso_establecimiento_selec").load("../../lista_curso_establecimientos_admin.php");
			 }
			 else if(respuesta.estado === "3"){
			  $("#boton_subir_excel").attr('disabled', false);
				 $('#file').val('');
				 document.getElementById("dowload").innerHTML = ('');
			  alertify.set('notifier','position', 'center-top');
			  alertify.error("<div class='text-white text-center'>Ya se encuentran registros de estudiantes a registrar</div>");
			  $("#curso_establecimiento_selec").load("../../lista_curso_establecimientos_admin.php");
			 
			 }
			
  
		   }
			  })

			 }                         
									 
				  
		  });
	  });
  })
}

function validar_extension_admin() {
  $(document).ready(function () {       
		  $(document).on('change', '#file', function () {
		 
			  var formData = new FormData();
			  formData.append('file', $('#file')[0].files[0]);
  
			  $.ajax({
				  url: "../../php/conf_excel.php",
				  type: "POST",
				  dataType: "html",
				  data: formData,
				  cache: false,
				  contentType: false,
				  processData: false,
				  statusCode: {
					  404: function () {
						  alertify.alert('Notificación',"Pagina no Encontrada");
  
					  },
					  502: function () {
						  alertify.alert('Notificación',"Ha ocurrido un error al conectarse con el servidor");
  
					  }
				  },
				  success: function (response) { 
															  
					  var extension = (JSON.parse(response));

					  if(extension.estado === "1"){
						  alertify.alert("Notificación","El archivo no tiene el formato definido");
						  $("#file").val('');
					  }
					  else if(extension.estado === "3"){
						  alertify.alert("Notificación","Algunos campos el archivo se encuentran vacios");
						  $("#file").val('');
					  }
					  else if(extension.estado === "4"){
						  alertify.alert("Notificación","El archivo no tiene el formato correcto");
						  $("#file").val('');
					  }else if(extension.estado === "5"){

					  }
					  
  
				  }
			  });
			  
  
		  

	  });
  });
}

function fecha_picker() {
  $(document).ready(function () {
	  $('.datepicker').datepicker({
		  format: 'yyyy-mm-dd',
		  language: 'es',
		  todayBtn: true,
		  todayHighlight: true,
		  autoclose: true
	  });
  });
}
