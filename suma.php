<?php require'conf/conf_requiere.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <?php include"assets/css/css.php"; ?>
</head>
<body>
<div class="container">
 <div class="row">
  <div class="col-md-12">
  <table class="table table-striped text-white">
  <thead>
  <tr>
    <th>Nombres</th>
    <th>Afectivo</th>
    <th>Conductual</th>
    <th>Cognitivo</th>
    <th>Apoyo Familia</th>
    <th>Apoyo Profesores</th>
    <th>Apoyo Pares</th>
  </tr>  
  </thead>
  <tbody>
   <?php
    $nuevo = suma_afectivo_coductual_cognitivo();
    $promedio = array(); 
    $contador = 0;  
    foreach ($nuevo as $row ){
        $afectivo =round($row["promedio_afectivo"],2,PHP_ROUND_HALF_UP);
        $conductual =round($row["promedio_conductual"],2,PHP_ROUND_HALF_UP);
        $cognitivo =round($row["promedio_cognitivo"],2,PHP_ROUND_HALF_UP);
        $apoyo_familiar =round($row["promedio_apoyo_familiar"],2,PHP_ROUND_HALF_UP);
        $apoyo_profesores =round($row["promedio_apoyo_profesores"],2,PHP_ROUND_HALF_UP); 
        $apoyo_pares =round($row["promedio_apoyo_pares"],2,PHP_ROUND_HALF_UP);        
        echo "<tr>"
        ."<td>".$row["nombre"]."</td>"
        ."<td>".$row["promedio_afectivo"]."</td>"
        ."<td>".round($row["promedio_conductual"],2,PHP_ROUND_HALF_UP)."</td>"
        ."<td>".round($row["promedio_cognitivo"],2,PHP_ROUND_HALF_UP)."</td>"
        ."<td>".round($row["promedio_apoyo_familiar"],2,PHP_ROUND_HALF_UP)."</td>"
        ."<td>".round($row["promedio_apoyo_profesores"],2,PHP_ROUND_HALF_UP)."</td>"
        ."<td>".round($row["promedio_apoyo_pares"],2,PHP_ROUND_HALF_UP)."</td>"
        
        ."<tr>";
        $total_afectivo[] =  $afectivo;
        $total_conductual[] =  $conductual;
        $total_cognitivo[] =  $cognitivo;
        $total_apoyo_familiar[] =  $apoyo_familiar;
        $total_apoyo_profesores[] =  $apoyo_profesores;
        $total_apoyo_pares[] =  $apoyo_pares;
        $contador++;
    }    
$suma_afectivo =  array_sum($total_afectivo);
$suma_conductual =  array_sum($total_conductual);
$suma_cognitivo =  array_sum($total_cognitivo);
$suma_apoyo_familiar =  array_sum($total_apoyo_familiar);
$suma_apoyo_profesores =  array_sum($total_apoyo_profesores);
$suma_apoyo_pares =  array_sum($total_apoyo_pares);
$promedio_afectivo_curso = $suma_afectivo/$contador;
$promedio_conductual_curso = $suma_conductual/$contador;
$promedio_cognitivo_curso = $suma_cognitivo/$contador;
$promedio_apoyo_familiar = $suma_apoyo_familiar/$contador;
$promedio_apoyo_profesores = $suma_apoyo_profesores/$contador;
$promedio_apoyo_pares = $suma_apoyo_pares/$contador;
       ?>
       <tr class="bg-success text-white">
        <td>Promedio</td>
        <td><?php echo round($promedio_afectivo_curso,2,PHP_ROUND_HALF_UP);?></td>
        <td><?php echo round($promedio_conductual_curso,2,PHP_ROUND_HALF_UP);?></td>
        <td><?php echo round($promedio_cognitivo_curso,2,PHP_ROUND_HALF_UP);?></td>
        <td><?php echo round($promedio_apoyo_familiar,2,PHP_ROUND_HALF_UP);?></td>
        <td><?php echo round($promedio_apoyo_profesores,2,PHP_ROUND_HALF_UP);?></td>
        <td><?php echo round($promedio_apoyo_pares,2,PHP_ROUND_HALF_UP);?></td>
       </tr>
       <tr class="bg-success text-white">
        <td>Brecha</td>
        <td><?php echo round(5-$promedio_afectivo_curso,2,PHP_ROUND_HALF_UP);?></td>
        <td><?php echo round(5-$promedio_conductual_curso,2,PHP_ROUND_HALF_UP);?></td>
        <td><?php echo round(5-$promedio_cognitivo_curso,2,PHP_ROUND_HALF_UP);?></td>
        <td><?php echo round(5-$promedio_apoyo_familiar,2,PHP_ROUND_HALF_UP);?></td>
        <td><?php echo round(5-$promedio_apoyo_profesores,2,PHP_ROUND_HALF_UP);?></td>
        <td><?php echo round(5-$promedio_apoyo_pares,2,PHP_ROUND_HALF_UP);?></td>
       </tr>
   </tbody>
   </table>
<?php

$emergente_afectivo = 0;
$en_desarrolo_afectivo = 0;
$desarrollado_afectivo = 0;
$muy_desarrollado_afectivo = 0;
foreach($total_afectivo as $fila){
    if($fila <= 1.25){
        $emergente_afectivo++;
    }elseif($fila > 1.25 and $fila <= 2.50){
      $en_desarrolo_afectivo++;
    }elseif($fila > 2.50 and $fila <= 3.75){
        $desarrollado_afectivo++;
    }elseif($fila > 3.75){
        $muy_desarrollado_afectivo++;

    }
}

$emergente_conductual = 0;
$en_desarrolo_conductual = 0;
$desarrollado_conductual = 0;
$muy_desarrollado_conductual = 0;
foreach($total_conductual as $fila){
    if($fila <= 1.25){
        $emergente_conductual++;
    }elseif($fila > 1.25 and $fila <= 2.50){
      $en_desarrolo_conductual++;
    }elseif($fila > 2.50 and $fila <= 3.75){
        $desarrollado_conductual++;
    }elseif($fila > 3.75){
        $muy_desarrollado_conductual++;

    }
}

$emergente_cognitivo = 0;
$en_desarrolo_cognitivo = 0;
$desarrollado_cognitivo= 0;
$muy_desarrollado_cognitivo = 0;
foreach($total_cognitivo as $fila){
    if($fila <= 1.25){
        $emergente_cognitivo++;
    }elseif($fila > 1.25 and $fila <= 2.50){
      $en_desarrolo_cognitivo++;
    }elseif($fila > 2.50 and $fila <= 3.75){
        $desarrollado_cognitivo++;
    }elseif($fila > 3.75){
        $muy_desarrollado_cognitivo++;

    }
}

$emergente_familiar = 0;
$en_desarrolo_familiar = 0;
$desarrollado_familiar = 0;
$muy_desarrollado_familiar = 0;
foreach($total_apoyo_familiar as $fila){
    if($fila <= 1.25){
        $emergente_familiar++;
    }elseif($fila > 1.25 and $fila <= 2.50){
      $en_desarrolo_familiar++;
    }elseif($fila > 2.50 and $fila <= 3.75){
        $desarrollado_familiar++;
    }elseif($fila > 3.75){
        $muy_desarrollado_familiar++;

    }
}

$emergente_apoyo_pares = 0;
$en_desarrolo_apoyo_pares = 0;
$desarrollado_apoyo_pares = 0;
$muy_desarrollado_apoyo_pares = 0;
foreach($total_apoyo_pares as $fila){
    if($fila <= 1.25){
        $emergente_apoyo_pares++;
    }elseif($fila > 1.25 and $fila <= 2.50){
      $en_desarrolo_apoyo_pares++;
    }elseif($fila > 2.50 and $fila <= 3.75){
        $desarrollado_apoyo_pares++;
    }elseif($fila > 3.75){
        $muy_desarrollado_apoyo_pares++;

    }
}

$emergente_apoyo_profesores = 0;
$en_desarrolo_apoyo_profesores = 0;
$desarrollado_apoyo_profesores = 0;
$muy_desarrollado_apoyo_profesores = 0;
foreach($total_apoyo_profesores as $fila){
    if($fila <= 1.25){
        $emergente_apoyo_profesores++;
    }elseif($fila > 1.25 and $fila <= 2.50){
      $en_desarrolo_apoyo_profesores++;
    }elseif($fila > 2.50 and $fila <= 3.75){
        $desarrollado_apoyo_profesores++;
    }elseif($fila > 3.75){
        $muy_desarrollado_apoyo_profesores++;

    }
}


?>
  </div>
  <div class="col-md-12 mt-4">
  <div class="row">
  <div class="col-md-4">
  <div id="grafico1" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  </div>
  <div class="col-md-4">
  <div id="grafico2" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  </div>
  <div class="col-md-4">
  <div id="grafico3" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  </div>
  <div class="col-md-4 mt-2">
  <div id="grafico_apoyo_familiar_curso" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  </div>
  <div class="col-md-4 mt-2">
  <div id="grafico_apoyo_profesores_curso" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  </div>
  <div class="col-md-4 mt-2">
  <div id="grafico_apoyo_pares_curso" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  </div>
  <div class="col-md-4 mt-2">
  <div id="grafico4" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  </div>
  </div>
   
    </div>   
  </div>
  <div class="col-md-4"></div>
 </div>
</div>

    <?php include"assets/js/js.php"; ?> 
    <script>
    // Radialize the colors
Highcharts.setOptions({
    colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
            },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    })
});

// Build the chart
Highcharts.chart('grafico1', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Afectivo'
    },
     
    credits: {
      enabled: false
  },
 
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
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
            y: <?php echo $emergente_afectivo;?>,
            color: '#fc455c'
            },      
            { name: 'En Desarrollo',
             y: <?php echo $en_desarrolo_afectivo;?>,
             color: '#FFD700'
             },
            { name: 'Desarrollado', 
            y: <?php echo $desarrollado_afectivo;?>,
              color: '#4169E1'
             },
            { name: 'Muy Desarrollado',
             y: <?php echo $muy_desarrollado_afectivo;?> ,
             color: '#5CB85C'
             }
            
        ],
        size: '60%',
                innerSize: '60%',
                showInLegend:true,               
                dataLabels: {
                    enabled: true
                }
    }]
});

// Build the chart
Highcharts.chart('grafico2', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
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
            y: <?php echo $emergente_conductual;?>,
            color: '#fc455c'
            },      
            { name: 'En Desarrollo',
             y: <?php echo $en_desarrolo_conductual;?>,
             color: '#FFD700'
             },
            { name: 'Desarrollado', 
            y: <?php echo $desarrollado_conductual;?>,
              color: '#4169E1'
             },
            { name: 'Muy Desarrollado',
             y: <?php echo $muy_desarrollado_conductual;?> ,
             color: '#5CB85C'
             }
            
        ],
        size: '60%',
                innerSize: '60%',
                showInLegend:true,               
                dataLabels: {
                    enabled: true
                }
    }]
});
// Build the chart
Highcharts.chart('grafico3', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
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
            y: <?php echo $emergente_cognitivo;?>,
            color: '#fc455c'
            },      
            { name: 'En Desarrollo',
             y: <?php echo $en_desarrolo_cognitivo;?>,
             color: '#FFD700'
             },
            { name: 'Desarrollado', 
            y: <?php echo $desarrollado_cognitivo;?>,
              color: '#4169E1'
             },
            { name: 'Muy Desarrollado',
             y: <?php echo $muy_desarrollado_cognitivo;?> ,
             color: '#5CB85C'
             }
            
        ],
        size: '60%',
                innerSize: '60%',
                showInLegend:true,               
                dataLabels: {
                    enabled: true
                }
    }]
});

// Build the chart
Highcharts.chart('grafico_apoyo_familiar_curso', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Apoyo Familia'
    },
     
    credits: {
      enabled: false
  },
 
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
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
            y: <?php echo $emergente_familiar;?>,
            color: '#fc455c'
            },      
            { name: 'En Desarrollo',
             y: <?php echo $en_desarrolo_familiar;?>,
             color: '#FFD700'
             },
            { name: 'Desarrollado', 
            y: <?php echo $desarrollado_familiar;?>,
              color: '#4169E1'
             },
            { name: 'Muy Desarrollado',
             y: <?php echo $muy_desarrollado_familiar;?> ,
             color: '#5CB85C'
             }
            
        ],
        size: '60%',
                innerSize: '60%',
                showInLegend:true,               
                dataLabels: {
                    enabled: true
                }
    }]
});

// Build the chart
Highcharts.chart('grafico_apoyo_pares_curso', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
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
            y: <?php echo $emergente_apoyo_pares;?>,
            color: '#fc455c'
            },      
            { name: 'En Desarrollo',
             y: <?php echo $en_desarrolo_apoyo_pares;?>,
             color: '#FFD700'
             },
            { name: 'Desarrollado', 
            y: <?php echo $desarrollado_apoyo_pares;?>,
              color: '#4169E1'
             },
            { name: 'Muy Desarrollado',
             y: <?php echo $muy_desarrollado_apoyo_pares;?> ,
             color: '#5CB85C'
             }
            
        ],
        size: '60%',
                innerSize: '60%',
                showInLegend:true,               
                dataLabels: {
                    enabled: true
                }
    }]
});

// Build the chart
Highcharts.chart('grafico_apoyo_profesores_curso', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
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
            y: <?php echo $emergente_apoyo_profesores;?>,
            color: '#fc455c'
            },      
            { name: 'En Desarrollo',
             y: <?php echo $en_desarrolo_apoyo_profesores;?>,
             color: '#FFD700'
             },
            { name: 'Desarrollado', 
            y: <?php echo $desarrollado_apoyo_profesores;?>,
              color: '#4169E1'
             },
            { name: 'Muy Desarrollado',
             y: <?php echo $muy_desarrollado_apoyo_profesores;?> ,
             color: '#5CB85C'
             }
            
        ],
        size: '60%',
                innerSize: '60%',
                showInLegend:true,               
                dataLabels: {
                    enabled: true
                }
    }]
});

//grafica de radar curso

Highcharts.chart('grafico4',{
							                		
												  
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
                                                        tickPositions: [1,2,3,4,5]
                                                   },
                                                   series:[
                                                      {name: 'Promedio', 
                                                      data:[<?php echo round($promedio_afectivo_curso,2,PHP_ROUND_HALF_UP);?>, <?php echo round($promedio_conductual_curso,2,PHP_ROUND_HALF_UP);?>,<?php echo round($promedio_cognitivo_curso,2,PHP_ROUND_HALF_UP);?> ], 
                                                      pointPlacement: 'on',
                                                      color: 'rgb(51, 122, 183)'}
                                                   ]
                                                
                                             });
    
    </script>
</body>
</html>
<?php


