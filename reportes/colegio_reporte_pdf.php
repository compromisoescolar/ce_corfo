<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

require_once "../assets/librerias/vendor/autoload.php";
require_once "dist/conf/require_conf.php";

use GuzzleHttp\Client;

session_start();

if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit();
}


date_default_timezone_set('America/Santiago');

$conn = connectDB_demos();

$cursos_basica = [];
$cursos_media = [];

$usuario_stmt = $conn->prepare('SELECT * FROM ce_usuarios where nombre_usu = :username');
$usuario_stmt->execute([
    'username' => $_SESSION['user']
]);

$usuario = $usuario_stmt->fetch();

$role = $conn->query("SELECT ce_roles.* from ce_roles join ce_rol_user cru on ce_roles.id_rol = cru.id_roles_fk where cru.id_usuario_fk = {$usuario['id_usu']} AND id_rol = 2")->fetch();

if (!$role) {
    header("location: index.php");
    exit();
}

$establecimiento_id = $usuario['fk_establecimiento'];

$establecimiento_stmt = $conn->prepare("SELECT * FROM ce_establecimiento WHERE id_ce_establecimiento = :id");
$establecimiento_stmt->execute([
    'id' => $establecimiento_id
]);

$establecimiento = $establecimiento_stmt->fetch();

$totalParticipantesStmt = $conn->prepare('SELECT count(id_ce_participantes) as participantes from ce_participantes cp INNER JOIN ce_curso cc ON
cp.ce_curso_id_ce_curso = cc.id_ce_curso where ce_establecimiento_id_ce_establecimiento = :id AND ce_anio_curso = :anio');
$totalParticipantesStmt->execute([
    'id' => $establecimiento_id,
    'anio' => date('Y')
]);

$totalParticipantes = $totalParticipantesStmt->fetch()['participantes'];

$cursos_stmt = $conn->prepare("SELECT ce_curso_nombre as nombre,
       COUNT(id_ce_participantes) as participantes,
       SUM(ce_p1 + ce_p2 + ce_p3 + ce_p4 + ce_p5 + ce_p6 + ce_p7 + ce_p8 + ce_p9 + ce_p10 +
           ce_p11 + ce_p12 + ce_p13 + ce_p14 + ce_p15 + ce_p16 + ce_p17 + ce_p18 + ce_p19 + ce_p20 +
           ce_p21 + ce_p22 + ce_p23 + ce_p24 + ce_p25 + ce_p26 + ce_p27 + ce_p28 + ce_p29) /
       COUNT(ce_participantes.id_ce_participantes) AS CE,
       SUM(ce_p30 +
           ce_p31 +
           ce_p32 +
         #Inicio de preguntas Apoyo Profesores
           ce_p33 +
           ce_p34 +
           ce_p35 +
           ce_p36 +
           ce_p37 +
           ce_p38 +
           ce_p39 +
           ce_p40 +
         #Inicio de preguntas Apoyo Pares
           ce_p41 +
           ce_p42 +
           ce_p43 +
           ce_p44 +
           ce_p45 +
           ce_p46 +
           ce_p47) / COUNT(ce_participantes.id_ce_participantes) AS FC,
           ce_curso.ce_fk_nivel as nivel
       FROM ce_encuesta_resultado
       JOIN ce_participantes ON ce_participantes_token_fk = ce_participanes_token
       JOIN ce_curso ON (ce_curso_id_ce_curso = id_ce_curso AND ce_anio_curso = ce_anio_contestada)
       WHERE ce_estado_encuesta = 1
       AND ce_establecimiento_id_ce_establecimiento = :id AND ce_anio_curso = :anio
       group by ce_curso_nombre");

$cursos_stmt->execute([
    'id' => $establecimiento_id,
    'anio' => date('Y')
]);

while ($cursos_result = $cursos_stmt->fetch()) {
    $curso = [
        'name' => $cursos_result['nombre'],
        'x' => ceil($cursos_result['FC']),
        'y' => ceil($cursos_result['CE']),
        'participantes' => (int) $cursos_result['participantes']
    ];

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

$totalParticipantesBasica = array_reduce($cursos_basica, function ($accum, $item) {
    return $accum + $item['participantes'];
}, 0);

$totalParticipantesMedia = array_reduce($cursos_media, function ($accum, $item) {
    return $accum + $item['participantes'];
}, 0);

// ESTABLECIMIENTO

$stmt = $conn->prepare("SELECT
       COUNT(b.id_ce_participantes) as participantes,
       SUM(a.ce_p1 + a.ce_p2 + a.ce_p3 + a.ce_p4 + a.ce_p5 + a.ce_p6 + a.ce_p7 + a.ce_p8 + a.ce_p9 + a.ce_p10 +
           a.ce_p11 + a.ce_p12 + a.ce_p13 +  a.ce_p14 + a.ce_p15 + a.ce_p16 + a.ce_p17 + a.ce_p18 + a.ce_p19 + a.ce_p20 + 
           a.ce_p21 + a.ce_p22 + a.ce_p23 + a.ce_p24 + a.ce_p25 + a.ce_p26 + a.ce_p27 + a.ce_p28 + a.ce_p29) / COUNT(b.id_ce_participantes) AS CE,
       SUM(a.ce_p30 + a.ce_p31 + a.ce_p32 + a.ce_p33 + a.ce_p34 + a.ce_p35 + a.ce_p36 + a.ce_p37 +
           a.ce_p38 + a.ce_p39 + a.ce_p40 + a.ce_p41 + a.ce_p42 + a.ce_p43 + a.ce_p44 + a.ce_p45 +
           a.ce_p46 + a.ce_p47) / COUNT(b.id_ce_participantes) AS FC
FROM ce_encuesta_resultado a
       INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
       JOIN ce_establecimiento on ce_establecimiento.id_ce_establecimiento = b.ce_establecimiento_id_ce_establecimiento
       INNER JOIN ce_curso c ON b.ce_curso_id_ce_curso = c.id_ce_curso
WHERE b.ce_fk_nivel = 1 AND ce_estado_encuesta = 1 AND ce_establecimiento.id_ce_establecimiento = :id");

$stmt->execute(['id' => $establecimiento_id]);

if ($result = $stmt->fetch()) {
    $sum_fc = ceil($result['FC']);
    $sum_ce = ceil($result['CE']);
    $establecimiento_basica = [
        'x' => ceil($result['FC']),
        'y' => ceil($result['CE']),
        'participantes' => (int) $result['participantes']
    ];
} else {
    $establecimiento_basica = [
        'x' => 0,
        'y' => 0,
        'participantes' => 0
    ];
}

//By Jonathan Barrera basica
$brecha_fc_basica =$sum_fc;
$brecha_ce_basica = $sum_ce;
$nivel_basica = 1;
$brechas_basica = brecha_alta_limitante_estudiante($brecha_fc_basica,$brecha_ce_basica,$nivel_basica);

// MEDIA

$stmt = $conn->prepare("SELECT
       COUNT(b.id_ce_participantes) as participantes,
       SUM(a.ce_p1 + a.ce_p2 + a.ce_p3 + a.ce_p4 + a.ce_p5 + a.ce_p6 + a.ce_p7 + a.ce_p8 + a.ce_p9 + a.ce_p10 +
           a.ce_p11 + a.ce_p12 + a.ce_p13 +  a.ce_p14 + a.ce_p15 + a.ce_p16 + a.ce_p17 + a.ce_p18 + a.ce_p19 + a.ce_p20 + 
           a.ce_p21 + a.ce_p22 + a.ce_p23 + a.ce_p24 + a.ce_p25 + a.ce_p26 + a.ce_p27 + a.ce_p28 + a.ce_p29) / COUNT(b.id_ce_participantes) AS CE,
       SUM(a.ce_p30 + a.ce_p31 + a.ce_p32 + a.ce_p33 + a.ce_p34 + a.ce_p35 + a.ce_p36 + a.ce_p37 +
           a.ce_p38 + a.ce_p39 + a.ce_p40 + a.ce_p41 + a.ce_p42 + a.ce_p43 + a.ce_p44 + a.ce_p45 +
           a.ce_p46 + a.ce_p47)    / COUNT(b.id_ce_participantes) AS FC
FROM ce_encuesta_resultado a
       INNER JOIN ce_participantes b ON a.ce_participantes_token_fk = b.ce_participanes_token
       join ce_establecimiento on ce_establecimiento.id_ce_establecimiento = b.ce_establecimiento_id_ce_establecimiento
       INNER JOIN ce_curso c ON b.ce_curso_id_ce_curso = c.id_ce_curso
WHERE b.ce_fk_nivel = 2 AND ce_estado_encuesta = 1 AND ce_establecimiento.id_ce_establecimiento = :id");

$stmt->execute(['id' => $establecimiento_id]);

if ($result = $stmt->fetch()) {
    $establecimiento_media = [
        'x' => ceil($result['FC']),
        'y' => ceil($result['CE']),
        'participantes' => (int) $result['participantes']
    ];
} else {
    $establecimiento_media = [
        'x' => 0,
        'y' => 0,
        'participantes' => 0
    ];
}

$brecha_fc_media = $result['FC'];
$brecha_ce_media = $result['CE'];
$nivel_media = 2;
$brechas_media = brecha_alta_limitante_estudiante($brecha_fc_media,$brecha_ce_media,$nivel_media);
// Calculo de cuadrante

// Basica

$perfil_basica = '';
$definicion_basica = '';

$perfiles = [
    [
        'perfil' => 'Bajo compromiso escolar y Factores contextuales limitantes (-, -)',
        'descripcion' => 'Estudiantes que presentan una débil participación e interés en las actividades académicas. En general, no consideran que el aprendizaje sea significativo para su presente y futuro, al tiempo que no se sienten parte de una comunidad escolar. No hay una disposición para invertir destrezas cognitivas dentro del aprendizaje y dominio de nuevas habilidades de gran complejidad. EI no tener un compromiso escolar desarrollado es un factor de riesgo de la deserción escolar, para graduarse con altos niveles de conductas de riesgo y un bajo rendimiento académico. EI compromiso escolar es altamente permeable por factores contextuales. En este caso se aprecia que el débil compromiso escolar se vincula a un bajo involucramiento de la familia del estudiante en su proceso de aprendizaje junto con un clima escolar deteriorado (relación con profesores y pares) Io que termina desmotivando al estudiante. Un clima escolar deteriorado se puede observar en malas relaciones entre estudiante y profesores, entre los mismos estudiantes, y en un ambiente donde se han deteriorado los lazos de respeto y confianza.'
    ],
    [
        'perfil' => 'Bajo compromiso Escolar (-) y Factores contextuales facilitadores (+)',
        'descripcion' => 'Estudiantes que presentan una débil participación e interés en las actividades académicas. En general no consideran que el aprendizaje sea significativo para su presente y futuro, al tiempo que no se sienten parte de una comunidad escolar. No hay una disposición para invertir destrezas cognitivas dentro del aprendizaje y dominio de nuevas habilidades de gran complejidad. EI no tener un compromiso escolar desarrollado es un factor de riesgo de la deserción escolar, para graduarse con altos niveles de conductas de riesgo y un bajo rendimiento académico. EI compromiso escolar es altamente permeable por factores contextuales. En este caso se aprecia que los factores contextuales evaluados (familia, pares y profesores) no se constituyen en factores de riesgo, por lo que se hace necesario  evaluar qué  otros  factores  pueden  estar  afectando el compromiso escolar de los estudiantes, tales como prácticas educativas no motivantes o precarios servicios de apoyo a la escuela.'
    ],
    [
        'perfil' => 'Alto compromiso escolar y Factores contextuales limitantes (+, -)',
        'descripcion' => 'Estudiantes que presentan altos niveles de participación e interés en las actividades académicas. En general consideran que el aprendizaje sea significativo para su presente y futuro, al tiempo que se sienten parte de una comunidad escolar. Se trata de estudiantes que presentan una disposición para invertir destrezas cognitivas dentro del aprendizaje y logran un dominio de habilidades de gran complejidad. EI tener un compromiso escolar desarrollado es un factor protector que puede facilitar las trayectorias educativas exitosas y prevenir otras situaciones de riesgo asociadas a la deserción escolar. EI compromiso escolar es altamente permeable por factores contextuales. En este caso se aprecia que los factores contextuales evaluados (familia, pares y profesores) se constituyen en factores de riesgo, por lo que se hace necesario prestar atención pues puede el compromiso escolar verse alterado en el corto plazo si esos factores de riesgo no se revierten.'
    ],
    [
        'perfil' => 'Alto compromiso escolar y Factores contextuales facilitadores (+, +)',
        'descripcion' => 'Estudiantes que presentan altos niveles de participación e interés en las actividades académicas. En general consideran que el aprendizaje sea significativo para su presente y futuro, al tiempo que se sienten parte de una comunidad escolar. Se trata de estudiantes que presentan una disposición para invertir destrezas cognitivas dentro del aprendizaje y logran un dominio de habilidades de gran complejidad. EI tener un compromiso escolar desarrollado es un factor protector que puede facilitar las trayectorias educativas exitosas y prevenir otras situaciones de riesgo asociadas a la deserción escolar. EI compromiso escolar es altamente permeable por factores contextuales tales como el apoyo de la familia los pares y los profesores. Estas variables se constituyen en facilitadores del compromiso escolar para este grupo de estudiantes.'
    ]
];

$opcionesBasica = [
    "chart" => [
        "type" => 'scatter',
        "zoomType" => 'xy',
        "width" => 600,
        "alignTicks" => false,
    ],

    'title' => [
        'text' => "Reporte Establecimiento Básica "
    ],
    'subtitle' => [
        'text' => ''
    ],

    'credits' => [
        "enabled" => false
    ],

    "legend" => [
        "enabled" => false,
    ],

    "plotOptions" => [
        "scatter" => [
            "marker" => [
                "radius" => 15,
                "states" => [
                    "hover" => [
                        "enabled" => true,
                        "lineColor" => 'rgb(100,100,100)'
                    ]
                ]
            ],

            "states" => [
                "hover" => [
                    "marker" => [
                        "enabled" => false
                    ]
                ]
            ],
            "tooltip" => [
                "headerFormat" => '',
                "pointFormat" => '<b>{point.name}</b> <br><div>{point.x} FC, {point.y} CE</div>'
            ]
        ]
    ],

    "xAxis" => [
        "title" => [
            "text" => 'Factores Contextuales',
            "align" => 'low'
        ],

        "startOnTick" => true,
        "endOnTick" => true,
        "showLastLabel" => true,

        "plotLines" => [
            [
                "value" => 45,
                "color" => 'red',
                "width" => 1,
                "zIndex" => 4,
                "dashStyle" => 'shortdash'
            ]
        ],
        "min" => 1,
        "max" => 95
    ],

    "yAxis" => [
        "title" => [
            "enabled" => true,
            "text" => 'Compromiso Escolar',
            "align" => 'low'
        ],

        "plotLines" => [
            [
                "value" => 90,
                "color" => 'red',
                "width" => 1,
                "zIndex" => 4,
                "dashStyle" => 'shortdash'
            ]
        ],
        "min" => 29,
        "max" => 145,
    ],

    "series" => [[
        "data" => $cursos_basica
    ]]
];

$opcionesImgBasica = [
    [
        'name' => 'options',
        'contents' => json_encode($opcionesBasica)
    ],

    [
        'name' => 'filename',
        'contents' => 'reporte_basica.png'
    ],

    [
        'name' => 'type',
        'contents' => 'type/png'
    ],

    [
        'name' => 'callback',
        'contents' => "function (chart) {
        chart.renderer.label('Alto compromiso escolar y Factores contextuales', 440, 40)
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
        chart.renderer.label('Bajo compromiso escolar y bajos Factores contextuales', 70, 290)
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
        chart.renderer.label('Bajo compromiso escolar y Altos Factores contextuales', 440, 290)
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
    }"
    ]
];

$opcionesMedia = [
    "chart" => [
        "type" => 'scatter',
        "zoomType" => 'xy',
        "width" => 600,
        "alignTicks" => false,
    ],

    'title' => [
        'text' => "Reporte Establecimiento Media "
    ],
    'subtitle' => [
        'text' => ''
    ],

    'credits' => [
        "enabled" => false
    ],

    "legend" => [
        "enabled" => false,
    ],

    "plotOptions" => [
        "scatter" => [
            "marker" => [
                "radius" => 15,
                "states" => [
                    "hover" => [
                        "enabled" => true,
                        "lineColor" => 'rgb(100,100,100)'
                    ]
                ]
            ],

            "states" => [
                "hover" => [
                    "marker" => [
                        "enabled" => false
                    ]
                ]
            ],
            "tooltip" => [
                "headerFormat" => '',
                "pointFormat" => '<b>{point.name}</b> <br><div>{point.x} FC, {point.y} CE</div>'
            ]
        ]
    ],

    "xAxis" => [
        "title" => [
            "enabled" => true,
            "text" => 'Factores Contextuales',
            "align" => 'low'
        ],

        "startOnTick" => true,
        "endOnTick" => true,
        "showLastLabel" => true,

        "plotLines" => [
            [
                "value" => 45,
                "color" => 'red',
                "width" => 1,
                "zIndex" => 4,
                "dashStyle" => 'shortdash'
            ]
        ],
        "min" => 1,
        "max" => 95
    ],

    "yAxis" => [
        "title" => [
            "text" => 'Compromiso Escolar',
            "align" => 'low'
        ],
        "plotLines" => [
            [
                "value" => 90,
                "color" => 'red',
                "width" => 1,
                "zIndex" => 4,
                "dashStyle" => 'shortdash'
            ]
        ],
        "min" => 29,
        "max" => 145
    ],

    "series" => [[
        "data" => $cursos_media
    ]]
];



$opcionesImgMedia = [
    [
        'name' => 'options',
        'contents' => json_encode($opcionesMedia)
    ],

    [
        'name' => 'filename',
        'contents' => 'reporte_basica.png'
    ],

    [
        'name' => 'type',
        'contents' => 'type/png'
    ],

    [
        'name' => 'callback',
        'contents' => "function (chart) {
        chart.renderer.label('Alto compromiso escolar y Factores contextuales', 440, 40)
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
        chart.renderer.label('Bajo compromiso escolar y bajos Factores contextuales', 70, 290)
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
        chart.renderer.label('Bajo compromiso escolar y Altos Factores contextuales', 440, 290)
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
    }"
    ]
];

try {
    $client = new Client();

    if ($totalParticipantesBasica != 0) {
        $res = $client->post('http://export.highcharts.com/', [
            'multipart' => $opcionesImgBasica
        ]);

        $imagen_basica = "data:image/png;base64,".base64_encode($res->getBody());
    } 

    if($totalParticipantesMedia != 0) {
        $res = $client->post('http://export.highcharts.com/', [
            'multipart' => $opcionesImgMedia
        ]);

        $imagen_media = "data:image/png;base64,".base64_encode($res->getBody());
    }
} catch (\GuzzleHttp\Exception\ClientException $exception) {
    echo $exception->getResponse()->getBody();
    return;
}

if ($totalParticipantesBasica != 0 && $totalParticipantesMedia == 0) {
    $graficos_edu = '<center><div class="subtitulos">'.$brechas_basica.'</div>
    <br>
    <div style="width=100%;"><img src="'.$imagen_basica.'"></div>
    </center>
    </div>
    </div>';

    $dat_cur_bas = '<br><br><div class="subtitulos" style="width: 650px; margin-left: auto; margin-right: auto;">Detalle Educación Básica</div><br><center><table  style="margin-left: auto; margin-right: auto; border-collapse: collapse; border: 1px solid #fc455c; width: 600px;">
    <thead>
    <tr style="background-color:#fc455c;border: 1px solid #fc455c;">
    <th style="color:white;">Nombre</th>
    <th align="center" style="color:white;">FC</th>
    <th align="center" style="color:white;">CE</th>
    </tr>
    </thead>
    <tbody>';

    for ($i = 0; $i < count($cursos_basica); $i++) { 
        // FC= x  CE= y
        $dat_cur_bas = $dat_cur_bas."<tr><td>".$cursos_basica[$i]['name']. "</td> <td align='center'>".$cursos_basica[$i]['x']."</td> <td align='center'>".$cursos_basica[$i]['y']."</td></tr>";
    }
    $dat_cur_bas = $dat_cur_bas."</tbody></table></center>";

    $graficos_edu = $graficos_edu."".$dat_cur_bas;
} else if($totalParticipantesBasica == 0 && $totalParticipantesMedia != 0) {
    $graficos_edu = '
    <center>
    <div class="subtitulos">'.$brechas_media.'</div>
    <br>
    <div style="width=100%;"><img src="'.$imagen_media.'"></div>
    </center>
    </div>
    </div>';

    $dat_cur_med = '<br><br><div class="subtitulos" style="width: 650px; margin-left: auto; margin-right: auto;">Detalle Educación Media</div><br><center><table  style="margin-left: auto; margin-right: auto; border-collapse: collapse; border: 1px solid #fc455c; width: 600px;">
    <thead>
    <tr style="background-color:#fc455c;border: 1px solid #fc455c;">
    <th style="color:white;">Nombre</th>
    <th align="center" style="color:white;">FC</th>
    <th align="center" style="color:white;">CE</th>
    </tr>
    </thead>
    <tbody>';

    for ($i = 0; $i < count($cursos_media); $i++) { 
        // FC= x  CE= y
        $dat_cur_med = $dat_cur_med."<tr><td>".$cursos_media[$i]['name']. "</td> <td align='center'>".$cursos_media[$i]['x']."</td> <td align='center'>".$cursos_media[$i]['y']."</td></tr>";
    }
    $dat_cur_med = $dat_cur_med."</tbody></table></center>";

    $graficos_edu = $graficos_edu."".$dat_cur_med;
} else if($totalParticipantesBasica != 0 && $totalParticipantesMedia != 0) {
    $graficos_edu_bas = '
            <center>
            <div class="subtitulos">'.$brechas_basica.'</div>
            <br>
            <div style="width=100%;"><img src="'.$imagen_basica.'"></div>
            ';

    $dat_cur_bas = '<br><br><div class="subtitulos" style="width: 650px; margin-left: auto; margin-right: auto;">Detalle Educación Básica</div><br><center><table  style="margin-left: auto; margin-right: auto; border-collapse: collapse; border: 1px solid #fc455c; width: 600px;">
    <thead>
    <tr style="background-color:#fc455c;border: 1px solid #fc455c;">
    <th style="color:white;">Nombre</th>
    <th align="center" style="color:white;">FC</th>
    <th align="center" style="color:white;">CE</th>
    </tr>
    </thead>
    <tbody>';

    for ($i = 0; $i < count($cursos_basica); $i++) { 
        // FC= x  CE= y
        $dat_cur_bas = $dat_cur_bas."<tr><td>".$cursos_basica[$i]['name']. "</td> <td align='center'>".$cursos_basica[$i]['x']."</td> <td align='center'>".$cursos_basica[$i]['y']."</td></tr>";
    }
    $dat_cur_bas = $dat_cur_bas."</tbody></table></center>";

    $graficos_edu_bas = $graficos_edu_bas."".$dat_cur_bas;

    $graficos_edu_med = '<br>
            <div class="subtitulos">'.$brechas_media.'</div>
            <br>
            <div style="width=100%;"><img src="'.$imagen_media.'"></div>
            </center>
        </div>
    </div>';

    $dat_cur_med = '<br><br><div class="subtitulos" style="width: 650px; margin-left: auto; margin-right: auto;">Detalle Educación Media</div><br><center><table  style="margin-left: auto; margin-right: auto; border-collapse: collapse; border: 1px solid #fc455c; width: 600px;">
    <thead>
    <tr style="background-color:#fc455c;border: 1px solid #fc455c;">
    <th style="color:white;">Nombre</th>
    <th align="center" style="color:white;">FC</th>
    <th align="center" style="color:white;">CE</th>
    </tr>
    </thead>
    <tbody>';

    for ($i = 0; $i < count($cursos_media); $i++) { 
        // FC= x  CE= y
        $dat_cur_med = $dat_cur_med."<tr><td>".$cursos_media[$i]['name']. "</td> <td align='center'>".$cursos_media[$i]['x']."</td> <td align='center'>".$cursos_media[$i]['y']."</td></tr>";
    }
    $dat_cur_med = $dat_cur_med."</tbody></table></center>";

    $graficos_edu_med = $graficos_edu_med."".$dat_cur_med;

    $graficos_edu = $graficos_edu_bas." <br> ".$graficos_edu_med;
}



 
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

$mpdf->SetHTMLHeader('<div style="text-align: center; font-weight: bold;"><img src="../assets/img/encabezado_informe_colegio.png"/></div>', 'O', true);

$mpdf->SetHTMLFooter('<div style="text-align: center;border-top: 1px solid #fc455c; color: #fc455c; padding-top:3px;">{PAGENO}</div>');

$mpdf->WriteHTML('<div class="container">
<div class="datos_curso">
<span>Fecha Reporte: '.date('d-m-Y')." | ".date('H:i').'</span><br>
<span>Porcentaje de respuesta estudiantes: '.ceil((($totalParticipantesBasica + $totalParticipantesMedia) * 100) / $totalParticipantes).'%</span><br>
<span>Total Estudiantes: '.$totalParticipantes.'</span><br>
<span>Estudiantes Encuestados: '.($totalParticipantesBasica + $totalParticipantesMedia).'</span>
</div>

<div class="descrip_com_esco">

<div class="subtitulos pt-1">Definiciones</div>
<p> <span>El Compromiso Escolar: </span> El Compromiso Escolar dice relación con la activa participación del (la) estudiante en las actividades académicas, curriculares o extracurriculares. Estudiantes comprometidos encuentran que el aprendizaje es significativo y están motivados y empeñados en su aprendizaje y futuro. El compromiso escolar impulsa al estudiante hacia el aprendizaje, pudiendo ser alcanzado por todas y todos y es altamente influenciable por factores del contexto. Diversos investigadores (Appleton etal., 2008; Bowles et al., 2013; Fredricks et al., 2004, Jimerson et al., 2000; Lyche, 2010) concuerdan que el compromiso escolar es una variable clave en la deserción escolar, ya que el abandonar la escuela no suele ser un acto repentino, sino que la etapa final de un proceso dinámico y acumulativo de una pérdida de compromiso con los estudios (Rumberger, 2001). Por el contrario, cuando los estudiantes desarrollan un compromiso positivo con sus estudios, ellos son más propensos a graduarse con bajos niveles de conductas de riesgo y un alto rendimiento académico.</p>

 <div class="subtitulos">El Compromiso Escolar tiene 3 dimensiones</div>

 <p><span>Compromiso afectivo: </span>  El compromiso afectivo se define como el nivel de respuesta emocional del estudiante hacia
  el establecimiento educativo y su proceso de aprendizaje, caracterizado por un sentimiento de involucramiento al
  colegio y una consideración del colegio como un lugar que es valioso y vale la pena. El compromiso afectivo brinda el
  incentivo para participar y perseverar. Los estudiantes que están comprometidos afectivamente, se sienten parte de
  una comunidad escolar y que el colegio es significativo en sus vidas, al tiempo que reconocen que la escuela
  proporciona herramientas para obtener logros fuera de la escuela. Abarca conductas hacia los profesores,
  compañeros y la escuela. Se presume que crea un vínculo con la escuela y una buena disposición hacia el trabajo
  estudiantil.</p>

<p><span>Compromiso Conductual: </span>El compromiso conductual se basa en la idea de participación en el ámbito
académico y actividades sociales o extracurriculares. El componente conductual del compromiso escolar
incluye las interacciones y respuestas del estudiante, dentro de la sala de clases, del colegio y en
ambientes extracurriculares. Este aspecto del compromiso escolar va desde un continuo, es decir, desde
un involucramiento esperado de manera universal (asistencia diaria) a un involucramiento más intenso
(Ej. Participación en el centro de alumnos).</p>

<p><span>Compromiso cognitivo: </span>El compromiso cognitivo es el proceso mediante el cual se incorpora la conciencia
y voluntad de ejercer el esfuerzo necesario para comprender ideas complejas y desarrollar habilidades
difíciles (Appleton et al., 2008; Fredricks, Blumenfeld, y París, 2004). Es la inversión consciente de
energía para construir aprendizajes complejos que van más allá de los requerimientos mínimos. Refleja
la disposición del estudiante para utilizar y desarrollar sus destrezas cognitivas en el proceso de aprendizaje
y dominio de nuevas habilidades de gran complejidad. Implica actuar de manera reflexiva y estar
dispuesto a realizar el esfuerzo necesario para la comprensión de ideas complejas y desarrollar habilidades
para el aprendizaje.</p>

<div class="subtitulos">Factores Contextuales</div>

<p>El compromiso escolar es una variable altamente influenciada por factores contextuales y relacionales
como la familia, el colegio y los pares, todos factores moldeables sobre los cuáles se puede intervenir en
Ia medida que se tenga información sobre cómo estos factores afectan el compromiso escolar. Dentro
de estos factores contextuaIes se incluyen las dimensiones de contextos: Apoyo recibido de la FAMILIA,
Apoyo recibido de PARES y Apoyo recibido de PROFESORES.<br>
Para efectos de la presente evaluación, se consideran tres factores del contexto que pueden influir en las
trayectorias educativas y el compromiso escolar del estudiante:</p>

<p><span>Apoyo de la familia: </span>Se refiere a que los/las estudiantes perciben ser apoyados por sus profesores y/o
sus familias. La familia del (la) estudiante suele apoyar a su hijo(a) en el proceso de aprendizaje y cuando
tiene problemas, ayudándolo con las tareas, conversando lo que sucede en la escuela, animándolo y
motivándolo a trabajar bien. El (la) estudiante se siente motivado por sus profesores para aprender y que
estos lo ayudan cuando tiene algún problema. El (la) estudiante mantiene en general buenas relaciones
con sus profesores. Existe la impresión que los profesores se comportan de acuerdo con los valores que
enseñan y mantienen un interés por el estudiante como persona y como estudiante, ayudándolo en caso de dificultades. El (la) estudiante considera que sus profesores lo tratan con respeto y lo alientan a
realizar nuevamente una tarea si se ha equivocado El (la) estudiante siente que en el colegio se valora la
participación de todos.</p>

<p><span>Apoyo de los pares: </span>Se define como la percepción que tienen los sujetos acerca de las relaciones interpersonales
entre compañeros, la preocupación, la confianza y el apoyo que se da entre pares, siendo
estos importantes frente a la integración escolar y frente a desafíos escolares y/o cuando tiene una dificultad
académica.</p>
<p><span>Apoyo de los profesores: </span>Se define como la percepción del (la) estudiante acerca del apoyo que recibe
de sus profesores.</p>

<div class="subtitulos">Reporte de Cuadrantes</div>
<p>Este reporte permite identificar al establecimiento dentro de uno de los siguientes Cuadrantes (Alto
Compromiso Escolar y bajos factores contextuales, alto compromiso escolar y factores contextuales,
bajo compromiso escolar y bajo factores contextuales.)</p>
<div>
<table  style="border-collapse: collapse; border: 1px solid #fc455c; width: 800px;">
<thead>
<tr style="background-color:#fc455c;border: 1px solid #fc455c;">
<th style="color:white;">Perfil</th>
<th style="color:white;">Definición</th>
</tr>
</thead>
<tbody>
<tr>
<td style="border: 1px solid #fc455c; font-size:18px; padding-left:10px;padding-right:10px;">Bajo compromiso escolar y Factoresmcontextualesmlimitantes (-, -)</td>
<td style="border: 1px solid #fc455c; text-align: justify; font-size:18px; padding-left:10px;padding-right:10px;">Estudiantes que presentan una débil participación e interés en las actividades académicas. En general, no consideran que el aprendizaje sea significativo para su presente y futuro, al tiempo que no se sienten parte de una comunidad escolar. No hay una disposición para invertir destrezas cognitivas dentro del aprendizaje y dominio de nuevas habilidades de gran complejidad. El no tener un compromiso escolar desarrollado es un factor de riesgo de la deserción escolar, para graduarse con altos niveles de conductas de riesgo y un bajo rendimiento académico. EI compromiso escolar es altamente permeable por factores contextuales. En este caso se aprecia que el débil compromiso escolar se vincula a un bajo involucramiento de la familia del estudiante en su proceso de aprendizaje junto con un clima escolar deteriorado (relación con profesores y pares) Io que termina desmotivando al estudiante. Un clima escolar deteriorado se puede observar en malas relaciones entre estudiante y profesores, entre los mismos estudiantes, y en un ambiente donde se han deteriorado los lazos de respeto y confianza.</td>
</tr>
<tr>
<td style="border: 1px solid #fc455c; text-align: justify; font-size:18px; padding-left:10px;padding-right:10px;">Bajo compromiso Escolar (-) y Factores contextuales facilitadores (+)</td>
<td style="border: 1px solid #fc455c; text-align: justify; font-size:18px; padding-left:10px;padding-right:10px;">Estudiantes que presentan una débil participación e interés en las actividades académicas. En general no consideran que el aprendizaje sea significativo para su presente y futuro, al tiempo que no se sienten parte de una comunidad escolar. No hay una disposición para invertir destrezas cognitivas dentro del aprendizaje y dominio de nuevas habilidades de gran complejidad. EI no tener un compromiso escolar desarrollado es un factor de riesgo de la deserción escolar, para graduarse con altos niveles de conductas de riesgo y un bajo rendimiento académico. EI compromiso escolar es altamente permeable por factores contextuales. En este caso se aprecia que los factores contextuales evaluados (familia, pares y profesores) no se constituyen en factores de riesgo, por lo que se hace necesario evaluar qué otros factores pueden estar afectando el compromiso escolar de los estudiantes, tales como prácticas educativas no motivantes o precarios servicios de apoyo a la escuela.</td>
</tr>
<tr>
<td style="border: 1px solid #fc455c; text-align: justify; font-size:18px; padding-left:10px;padding-right:10px;">Alto compromiso escolar y Factores contextuales limitantes (+, -)</td>
<td style="border: 1px solid #fc455c; text-align: justify; font-size:18px; padding-left:10px;padding-right:10px;">Estudiantes que presentan altos niveles de participación e interés en las actividades académicas. En general consideran que el aprendizaje sea significativo para su presente y futuro, al tiempo que se sienten parte de una comunidad escolar. Se trata de estudiantes que presentan una disposición para invertir destrezas cognitivas dentro del aprendizaje y logran un dominio de habilidades de gran complejidad. EI tener un compromiso escolar desarrollado es un factor protector que puede facilitar las trayectorias educativas exitosas y prevenir otras situaciones de riesgo asociadas a la deserción escolar. EI compromiso escolar es altamente permeable por factores contextuales. En este caso se aprecia que los factores contextuales evaluados (familia, pares y profesores) se constituyen en factores de riesgo, por lo que se hace necesario prestar atención pues puede el compromiso escolar verse alterado en el corto plazo si esos factores de riesgo no se revierten.</td>
</tr>
<tr>
<td style="border: 1px solid #fc455c; text-align: justify; font-size:18px; padding-left:10px;padding-right:10px;">Alto compromiso escolary Factores contextuales facilitadores (+,+)</td>
<td style="border: 1px solid #fc455c; text-align: justify; font-size:18px; padding-left:10px;padding-right:10px;">Estudiantes que presentan altos niveles de participación e interés en las actividades académicas. En general consideran que el aprendizaje sea significativo para su presente y futuro, al tiempo que se sienten parte de una comunidad escolar. Se trata de estudiantes que presentan una disposición para invertir destrezas cognitivas dentro del aprendizaje y logran un dominio de habilidades de gran complejidad. EI tener un compromiso escolar desarrollado es un factor protector que puede facilitar las trayectorias educativas exitosas y prevenir otras situaciones de riesgo asociadas a la deserción escolar. EI compromiso escolar es altamente permeable por factores contextuales tales como el apoyo de la familia los pares y los profesores. Estas variables se constituyen en facilitadores del compromiso escolar para este grupo de estudiantes.</td>
</tr>
</tbody>
</table>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
'.$graficos_edu);


$mpdf->Output('reporte_colegio.pdf', 'I');