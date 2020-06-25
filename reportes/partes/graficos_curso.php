<script>

                
zoom();
document.getElementById('curso').innerText = "<?php curso_de_establecimiento($_SESSION["id_profesor"]); ?>";

/*
Highcharts.chart('grafica_radar_curso_factores_contextuales', {

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

    title: {
        text: ''
    },
    xAxis: {
        gridLineColor: '#8a8a5c',
        categories: ['Apoyo Familia <?php echo $demos_fc["maximo_familiar"]; ?>', 'Pares <?php echo $demos_fc["maximo_pares"]; ?>', 'Apoyo Profesores <?php echo $demos_fc["maximo_profesores"]; ?>'],
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
        max: 5,
        showLastLabel: true,
        tickPositions: [100,400,800,1000,1400,1700],
        labels: {
                enabled: false
            }
    },
    series: [
        {name: 'Sumatoria',
            data: [<?php echo $demos_fc["sum_total_familiar"]; ?>,<?php echo $demos_fc["sum_total_pares"]; ?>, <?php echo $demos_fc["sum_total_profesores"]; ?>],
            pointPlacement: 'on',
            color: '#DD4B39'}
    ]

});


*/
//document.getElementById("tabla_prom_bre_fc").innerHTML = "<div class='table-responsive'> <table class='table table-bordered'> <thead><tr><th>Subscala</th><th>Sumatoria</th> <th>Brecha</th> </tr></thead><tbody><tr><td>Apoyo Familia</td><td><?php echo $demos_fc["sum_total_familiar"]; ?></td><td><?php echo $demos_fc["brecha_familiar"]; ?></td></tr> <tr> <td>Pares</td><td><?php echo $demos_fc["sum_total_pares"]; ?></td> <td><?php echo $demos_fc["brecha_pares"]; ?></td></tr><tr> <td>Apoyo Profesores</td> <td><?php echo $demos_fc["sum_total_profesores"]; ?></td><td><?php echo $demos_fc["brecha_profesores"]; ?></td></tr> </tbody> </table></div>";


//document.getElementById("tabla_prom_bre_fc").innerHTML = "<div class='table-responsive'> <table class='table table-hover'> <thead><tr><th>Dimensiones</th><th>Puntaje Máximo posible</th> <th>Puntaje obtenido</th> <th>Brecha o faltante</th><th>% de cumplimiento</th> </tr></thead><tbody><tr><td>Apoyo Familia</td><td><?php echo $demos_fc["maximo_familiar"]; ?></td><td><?php echo $demos_fc["sum_total_familiar"]; ?></td><td><?php echo $demos_fc["brecha_familiar"]; ?></td><td><?php echo $demos_fc["cumplimiento_familiar"]." %"; ?></td></tr> <tr> <td>Pares</td><td><?php echo $demos_fc["maximo_pares"]; ?></td> <td><?php echo $demos_fc["sum_total_pares"];?></td><td><?php echo $demos_fc["brecha_pares"];?></td><td><?php echo $demos_fc["cumplimiento_pares"]." %";?></td></tr><tr> <td>Apoyo Profesores</td> <td><?php echo $demos_fc["maximo_profesores"]; ?></td><td><?php echo $demos_fc["sum_total_profesores"]; ?></td><td><?php echo $demos_fc["brecha_profesores"]; ?></td><td><?php echo $demos_fc["cumplimiento_profesores"]." %"; ?></td></tr> </tbody> </table></div>";
/*
Highcharts.chart('grafica_radar_curso', {

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
    title: {text: ''},
    xAxis: {                       
        gridLineColor: '#00C0EF',
        categories: ['Afectivo <?php echo $ce["maximo_afectivo"]; ?>', 'Conductual <?php echo $ce["maximo_conductual"]; ?>', 'Cognitivo <?php echo $ce["maximo_cognitivo"];?>'],
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
        showLastLabel: false,
        tickPositions: [<?php echo incrementa_tickposition($ce["demo"]); ?>],
        labels: {
                enabled: false
            }
    },
    series: [
        {name: 'Sumatoria',
            data: [<?php echo $ce["sum_total_afectivo"]; ?>, <?php echo $ce["sum_total_conductual"]; ?>,<?php echo $ce["sum_total_cognitivo"]; ?>],
            pointPlacement: 'on',
            color: 'rgb(51, 122, 183)'}
    ]

});



//document.getElementById("tabla_prom_bre_acc").innerHTML = "<div class='table-responsive'> <table class='table table-bordered'> <thead><tr><th>Subscala</th><th>Sumatoria</th> <th>Brecha</th> </tr></thead> <tbody><tr><td>Afectivo</td><td><?php echo $ce['sum_total_afectivo']; ?></td><td><?php echo $ce['brecha_afectivo']; ?></td></tr> <tr> <td>Conductual</td><td><?php echo $ce['sum_total_conductual']; ?></td> <td><?php echo $ce['brecha_conductual']; ?></td></tr><tr> <td>Cognitivo</td> <td><?php echo $ce['sum_total_cognitivo']; ?></td><td><?php echo $ce['brecha_cognitivo']; ?></td></tr> </tbody> </table></div>";



document.getElementById("tabla_prom_bre_acc").innerHTML = "<div class='table-responsive'> <table class='table table-hover'> <thead><tr><th>Dimensiones</th><th>Puntaje Máximo Posible</th> <th>Puntaje Obtenido</th> <th>Brecha o faltante</th> <th>% de cumplimiento</th> </tr></thead> <tbody><tr><td>Afectivo</td><td><?php echo $ce['maximo_afectivo']; ?></td><td><?php echo $ce['sum_total_afectivo']; ?></td><td><?php echo $ce['brecha_afectivo']; ?></td><td><?php echo $ce['complimiento_afectivo']. " %"; ?></td></tr> <tr> <td>Conductual</td><td><?php echo $ce['maximo_conductual']; ?></td> <td><?php echo $ce['sum_total_conductual']; ?></td><td><?php echo $ce['brecha_conductual']; ?></td><td><?php echo $ce['cumplimiento_conductual']. " %"; ?></td></tr><tr> <td>Cognitivo</td> <td><?php echo $ce['maximo_cognitivo']; ?></td><td><?php echo $ce['sum_total_cognitivo']; ?></td><td><?php echo $ce['brecha_cognitivo']; ?></td><td><?php echo $ce['cumplimiento_cognitivo']. " %"; ?></td></tr> </tbody> </table></div>";
*/
// Build the chart
/*
chart.setTitle({
            useHTML: true,
            text: "Testing" + " " + "<img src='../images/appendImage.png' alt='' />"
            }, {
            text: "This is a test"
        });
        chart.options.title.text = "Testing";
        chart.options.subtitle.text = "This is a test";

*/




Highcharts.chart('grafico_nivel_curso_afectivo', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: "Afectivo"
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
                    color: '#2d6693'
                                     
                                           
                },
                {name: 'En Desarrollo',
                    y: <?php echo $compromiso_escolar["afectivo_en_desarrollo"]; ?>,
                    color: '#fc455c'
                },
                {name: 'Satisfactorio',
                    y: <?php echo $compromiso_escolar["afectivo_satisfactorio"]; ?>,
                    color: '#f4af1f'
                },
                {name: 'Muy Desarrollado',
                    y: <?php echo $compromiso_escolar["afectivo_muy_desarrollado"]; ?>,
                    color: '#40c2d4'
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
}, function(chart) { // on complete
    chart.renderer.text(
        "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<hr style='border: 1px solid #fc455c; width: 100%;'>", 
        0, 
        10, 
        "<hr style='border: 1px solid #fc455c; width: 100%;'>"
    )
    .add();
});

// Build the chart
Highcharts.chart('grafico_nivel_curso_conductual', {
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
                    color: '#2d6693'
                },
                {name: 'En Desarrollo',
                    y: <?php echo $compromiso_escolar["conductual_en_desarrollo"]; ?>,
                    color: '#fc455c'
                },
                {name: 'Satisfactorio',
                    y: <?php echo $compromiso_escolar["conductual_satisfactorio"]; ?>,
                    color: '#f4af1f'
                },
                {name: 'Muy Desarrollado',
                    y: <?php echo $compromiso_escolar["conductual_muy_desarrollado"]; ?>,
                    color: '#40c2d4'
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
},function(chart) { // on complete
    chart.renderer.text(
        "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<hr style='border: 1px solid #fc455c; width: 100%;'>", 
        0, 
        10, 
        "<hr style='border: 1px solid #fc455c; width: 100%;'>"
    )
    .add();
});

// Build the chart
Highcharts.chart('grafico_nivel_curso_cognitivo', {
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
                    color: '#2d6693'
                },
                {name: 'En Desarrollo',
                    y: <?php echo $compromiso_escolar["cognitivo_en_desarrollo"]; ?>,
                    color: '#fc455c'
                },
                {name: 'Satisfactorio',
                    y: <?php echo $compromiso_escolar["cognitivo_satisfactorio"]; ?>,
                    color: '#f4af1f'
                },
                {name: 'Muy Desarrollado',
                    y: <?php echo $compromiso_escolar["cognitivo_muy_desarrollado"]; ?>,
                    color: '#40c2d4'
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
},function(chart) { // on complete
    chart.renderer.text(
        "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<hr style='border: 1px solid #fc455c; width: 100%;'>", 
        0, 
        10, 
        "<hr style='border: 1px solid #fc455c; width: 100%;'>"
    )
    .add();
});

// Build the chart
Highcharts.chart('grafico_nivel_apoyo_familia', {
    chart: {
    
        type: 'pie'
    },
    title: {
        text: 'Familiar <hr style="border: 1px solid #fc455c; width: 100%;">'
    },

    credits: {
        enabled: false
    },

    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                distance: 1,
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
                    color: '#2d6693'
                },
                {name: 'Mediano',
                    y: <?php echo $factores_contextuales["apoyo_familiar_en_desarrollo"]; ?>,
                    color: '#fc455c'
                },
                {name: 'Alto',
                    y: <?php echo $factores_contextuales["apoyo_familiar_satisfactorio"]; ?>,
                    color: '#f4af1f'
                },
                {name: 'Muy Alto',
                    y: <?php echo $factores_contextuales["apoyo_familiar_muy_desarrollado"]; ?>,
                    color: '#40c2d4'
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
},function(chart) { // on complete
    chart.renderer.text(
        "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<hr style='border: 1px solid #fc455c; width: 100%;'>", 
        0, 
        10, 
        "<hr style='border: 1px solid #fc455c; width: 100%;'>"
    )
    .add();
});

// Build the chart
Highcharts.chart('grafico_nivel_apoyo_pares', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Pares'
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
                    y: <?php echo $factores_contextuales["apoyo_pares_emergente"]; ?>,
                    color: '#2d6693'
                },
                {name: 'Mediano',
                    y: <?php echo $factores_contextuales["apoyo_pares_en_desarrollo"]; ?>,
                    color: '#fc455c'
                },
                {name: 'Alto',
                    y: <?php echo $factores_contextuales["apoyo_pares_satisfactorio"]; ?>,
                    color: '#f4af1f'
                },
                {name: 'Muy Alto',
                    y: <?php echo $factores_contextuales["apoyo_pares_muy_desarrollado"]; ?>,
                    color: '#40c2d4'
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
},function(chart) { // on complete
    chart.renderer.text(
        "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<hr style='border: 1px solid #fc455c; width: 100%;'>", 
        0, 
        10, 
        "<hr style='border: 1px solid #fc455c; width: 100%;'>"
    )
    .add();
});

// Build the chart
Highcharts.chart('grafico_nivel_apoyo_profesores', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Profesores'
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
                    y: <?php echo $factores_contextuales["apoyo_profesores_emergente"]; ?>,
                    color: '#2d6693'
                },
                {name: 'Mediano',
                    y: <?php echo $factores_contextuales["apoyo_profesores_en_desarrollo"]; ?>,
                    color: '#fc455c'
                },
                {name: 'Alto',
                    y: <?php echo $factores_contextuales["apoyo_profesores_satisfactorio"]; ?>,
                    color: '#f4af1f'
                },
                {name: 'Muy Alto',
                    y: <?php echo $factores_contextuales["apoyo_profesores_muy_desarrollado"]; ?>,
                    color: '#40c2d4'
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
},function(chart) { // on complete
    chart.renderer.text(
        "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<hr style='border: 1px solid #fc455c; width: 100%;'>", 
        0, 
        10, 
        "<hr style='border: 1px solid #fc455c; width: 100%;'>"
    )
    .add();
});



setTimeout(function(){
screen_grafica_nivel_curso_pares(<?php echo $factores_contextuales["apoyo_pares_emergente"]; ?>, <?php echo $factores_contextuales["apoyo_pares_en_desarrollo"]; ?>,<?php echo $factores_contextuales["apoyo_pares_satisfactorio"]; ?>,<?php echo $factores_contextuales["apoyo_pares_muy_desarrollado"]; ?>)

},500)
/*
setTimeout(function(){
screen_grafica_radar_curso_fc(<?php echo $demos_fc["sum_total_familiar"]; ?>,<?php echo $demos_fc["sum_total_pares"]; ?>, <?php echo $demos_fc["sum_total_profesores"]; ?>,<?php echo $demos_fc["maximo_familiar"]; ?>,<?php echo $demos_fc["maximo_pares"]; ?>,<?php echo $demos_fc["maximo_profesores"];?>)

},1000)
*/
setTimeout(function(){
screen_grafica_nivel_curso_profesores(<?php echo $factores_contextuales["apoyo_profesores_emergente"]; ?>,<?php echo $factores_contextuales["apoyo_profesores_en_desarrollo"]; ?>,<?php echo $factores_contextuales["apoyo_profesores_satisfactorio"]; ?>, <?php echo $factores_contextuales["apoyo_profesores_muy_desarrollado"]; ?>)

},1500)

setTimeout(function(){
screen_grafica_nivel_curso_familiar(<?php echo $factores_contextuales["apoyo_familiar_emergente"]; ?>,<?php echo $factores_contextuales["apoyo_familiar_en_desarrollo"]; ?>,<?php echo $factores_contextuales["apoyo_familiar_satisfactorio"]; ?>,<?php echo $factores_contextuales["apoyo_familiar_muy_desarrollado"]; ?>)

},2000)
setTimeout(function(){
screen_grafica_nivel_curso_cognitivo(<?php echo $compromiso_escolar["cognitivo_emergente"]; ?>,<?php echo $compromiso_escolar["cognitivo_en_desarrollo"]; ?>,<?php echo $compromiso_escolar["cognitivo_satisfactorio"]; ?>,<?php echo $compromiso_escolar["cognitivo_muy_desarrollado"]; ?>)

},2500)
setTimeout(function(){
screen_grafica_nivel_curso_conductual(<?php echo $compromiso_escolar["conductual_emergente"]; ?>,<?php echo $compromiso_escolar["conductual_en_desarrollo"]; ?>,<?php echo $compromiso_escolar["conductual_satisfactorio"]; ?>,<?php echo $compromiso_escolar["conductual_muy_desarrollado"]; ?>)

},3000)

setTimeout(function(){
screen_grafica_nivel_curso_afectivo(<?php echo $compromiso_escolar["afectivo_emergente"]; ?>,<?php echo $compromiso_escolar["afectivo_en_desarrollo"]; ?>,<?php echo $compromiso_escolar["afectivo_satisfactorio"]; ?>,<?php echo $compromiso_escolar["afectivo_muy_desarrollado"]; ?>)

},3500)

/*
setTimeout(function(){
screen_grafica_radar_curso_ce(<?php echo $ce["sum_total_afectivo"]; ?>,<?php echo $ce["sum_total_conductual"]; ?>,<?php echo $ce["sum_total_cognitivo"];?>,<?php echo $ce['maximo_afectivo'];?>,<?php echo $ce['maximo_conductual']; ?>,<?php echo $ce['maximo_cognitivo']; ?>);

},3700)

*/
</script>