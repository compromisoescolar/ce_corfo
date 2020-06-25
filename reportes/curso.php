<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <br>
            </div>
            <div class="pull-left info">
                <p>
                    <?php echo $_SESSION["tipo_nombre"]; ?>
                </p>
                <a href="#">
                    <i class="fa fa-circle text-success"></i> Online
                </a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Menú De Navegación</li>
            <?php
                    $id_rol = $_SESSION["tipo_usuario"];

                    $menu = menu_modulos($id_rol);

                    foreach ($menu as $fila) {
                        echo $fila["menu"];
                    }
                ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1 class="text-center">
                            Curso <span id="curso"></span>
                        </h1>
        <ul class="nav nav-pills">
            <li class="active"><a class="curso_dimensiones" data-toggle="pill" href="#dimensiones"><span>Dimensiones</span></a></li>
            <li><a data-toggle="pill" href="#niveles">Niveles</a></li>

        </ul>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">

            <?php tarjeta_de_presentacion($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>

        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">

            <!-- Left col -->
            <section class="col-lg-12 connectedSortable">

                <button id="btn_curso" style="margin-top:2.3%;" class="btn btn-primary pull-right"><span id="btn_reporte_curso" hidden><a class="text-white" href="reporte_curso.php" target="_blank">Descargar Reporte <i class="fa fa-download" aria-hidden="true"></i></a></span> <span id="cargando_reporte_curso">Cargando Reporte<i class="fa fa-spinner fa-spin fa-fw"></i></span></button>
                <div class="tab-content">

                    <div id="dimensiones" class="tab-pane fade in active mt-4">
                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#dimensiones_compro_escolar">Compromiso Escolar</a></li>
                            <li><a data-toggle="pill" href="#dimensiones_factores_contextuales">Factores Contextuales</a></li>

                        </ul>
                        <div class="tab-content">
                            <div id="dimensiones_compro_escolar" class="tab-pane fade in active mt-4">
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">
                                        <h4><b>Compromiso Escolar</b></h4></div>
                                    <div class="panel-body">
                                        <ul class="nav nav-pills">
                                            <li class="active"><a data-toggle="pill" href="#dimension_afect">Afectivo</a></li>
                                            <li><a data-toggle="pill" href="#dimension_conduc">Conductual</a></li>
                                            <li><a data-toggle="pill" href="#dimension_cogniti">Cognitivo</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="dimension_afect" class="tab-pane fade in active mt-4">
                                                <?php include "partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" style="font-size:14px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size:16px;">
                                                                <?php echo dimension_afectivo_curso_copia($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>
                                            <div id="dimension_conduc" class="tab-pane fade">
                                                <?php include"partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size:16px;">
                                                                <?php dimension_conductual_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>
                                            <div id="dimension_cogniti" class="tab-pane fade">
                                                <?php include"partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size:16px;">
                                                                <?php dimension_cognitivo_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div id="dimensiones_factores_contextuales" class="tab-pane fade mt-4">
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">
                                        <h4><b>Factores Contextuales</b></h4></div>
                                    <div class="panel-body">
                                        <ul class="nav nav-pills">
                                            <li class="active"><a data-toggle="pill" href="#dimension_familia">Apoyo Familiar</a></li>
                                            <li><a data-toggle="pill" href="#dimension_pares">Apoyo Pares</a></li>
                                            <li><a data-toggle="pill" href="#dimension_profesores">Apoyo Profesores</a></li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="dimension_familia" class="tab-pane fade in active mt-4">
                                                <?php include "partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size:16px;">
                                                                <?php dimension_apoyo_familiar_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>

                                            <div id="dimension_pares" class="tab-pane fade mt-4">
                                                <?php include "partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size:16px;">
                                                                <?php dimension_pares_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>

                                            <div id="dimension_profesores" class="tab-pane fade mt-4">
                                                <?php include "partes/alertas_info.php"; ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Items</th>
                                                                    <th>NU</th>
                                                                    <th>AL</th>
                                                                    <th>AM</th>
                                                                    <th>MV</th>
                                                                    <th>SC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody style="font-size:16px;">
                                                                <?php dimension_profesores_curso($_SESSION["id_establecimiento"], $_SESSION["id_profesor"]); ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <div id="niveles" class="tab-pane fade">
                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#niveles_compromiso_escolar">Compromiso Escolar</a></li>
                            <li><a data-toggle="pill" href="#niveles_factores_contextuales">Factores Contextuales</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="niveles_compromiso_escolar" class="tab-pane fade in active mt-4">
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">
                                        <h4>Compromiso Escolar</h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12 mt-4">
                                            <?php include 'partes/alerta_niveles.php'; ?>
                                            <div class="mt-4">
                                                <div class="col-md-12">
                                                    <div class="col-md-4">
                                                        <div class="bordes-div">
                                                            <div id="grafico_nivel_curso_afectivo" style="min-width: 300px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="bordes-div">
                                                            <div id="grafico_nivel_curso_conductual" style="min-width: 300px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="bordes-div">

                                                            <div id="grafico_nivel_curso_cognitivo" style="min-width: 300px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="niveles_factores_contextuales" class="tab-pane fade">
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">
                                        <h4>Factores Contextuales</h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12 mt-4">
                                            <?php include 'partes/alerta_niveles_fc.php'; ?>
                                            <div class="mt-4">
                                                <div class="col-md-12">
                                                    <div class="col-md-4">
                                                        <div class="bordes-div">
                                                            <div id="grafico_nivel_apoyo_familia"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="bordes-div">
                                                            <div id="grafico_nivel_apoyo_profesores"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="bordes-div">
                                                            <div id="grafico_nivel_apoyo_pares"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="dispersion" class="tab-pane fade">
                        <ul class="nav nav-pills">
                            <li class="active"><a data-toggle="pill" href="#dispersion_estudiantes_compromiso_escolar">Compromiso Escolar</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="dispersion_estudiantes_compromiso_escolar" class="tab-pane fade in active">
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center">
                                        <h4>Estudiantes</h4></div>
                                    <div class="panel-body">
                                        <div class="col-md-12 mt-4">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>