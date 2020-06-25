
<div class="col-lg-4 col-xs-6">
                        <?php
                        $id_estable =  $_SESSION["id_establecimiento"];
                        $id_profesor =  $_SESSION["id_profesor"];
                        $contador = 1;
                         $respuestas = respuesta_no_respuesta($id_estable,$id_profesor);
                        while( $fila = $respuestas->fetch(PDO::FETCH_ASSOC)){
                            if($contador == 1 ){
                                $respon = $fila["respondidas"];
                                if($respon == 0){
                                    $respon = 1;
                                }
                            }elseif($contador == 2){
                                $no_respon = $fila["respondidas"];
                                if($no_respon == 0){
                                    $no_respon=1;
                                }
                                $suma_estudi = $respon+$no_respon;
                                $avance_curso = ($respon*100)/$suma_estudi;
                            }
                            $contador++;
                        }

                        
                        ?>
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3><?php echo $suma_estudi; $_SESSION["suma_estudi"] = $suma_estudi;?></h3>
                                    
                                    <p><h4>Número total de estudiantes</h4></p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>

                            </div>
                        </div>
                        <!-- ./col -->                       
                        <!-- ./col -->
                        <div class="col-lg-4 col-xs-6">

                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3><?php echo $respon; $_SESSION["respon"]=$respon;?></h3>

                                    <p><h4>Estudiantes Encuestados</h4></p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-user"></i>
                                </div>

                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-4 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                <h3><?php echo round($avance_curso,1,PHP_ROUND_HALF_UP)."%";?></h3>
                                    <p><h4>Total curso encuestado</h4></p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-pie-chart"></i>
                                </div>

                            </div>
                        </div>      