<?php
	session_start();
	require_once "../conf/conexion_db.php";
	require_once "../conf/funciones_db.php";
	require_once "diplomas/genera_diploma.php";

	if (isset($_SESSION['estudiante'])) {
	    $mi_pais = $_SESSION['pais'];
		$usuario = $_SESSION['estudiante'];
		$estudiante_nivel = $_SESSION["nivel_estudiante"];

	    $con = connectDB_demos();
	    $query = $con->query("SELECT id_ce_pais,ce_pais_nombre FROM ce_pais WHERE id_ce_pais ='$mi_pais'");
	    $resultado = $query->fetch(PDO::FETCH_ASSOC);
		$id_pais = $resultado["id_ce_pais"];
		$nivel = $_SESSION["nivel_estudiante"];
		txt_inicia_encuesta($usuario, $mi_pais);
?>
	
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Compromiso Escolar - Encuesta Estudiante</title>
	<?php require_once "css.php"; ?>
	<script src="../assets/js/jquery-1.10.2.js"></script>
	<script type="text/javascript">
		url_base = window.location;
		var count = 1;
		$("#instrucciones").css('display', 'none');

		function Cambia_Numero(op) {
			if (op == 0) {
				count++;
			} else {
				count--;
			}

			$('#img_pasos').attr("src","../assets/img/" + count + ".png");
			
			if (op != 1) {
				$("#instrucciones").css('display', 'none');
			}
		}

        $(document).ready(function() {
			$("#btn_cerrar_session").click(function() {
                window.location.replace(
                    url_base.protocol + "//" + 
                    url_base.host + "/" + 
                    "index2.php"
                );
            });            
        });
	</script>
</head>
<body style="background: #BDC3C7;" style="margin-top: 0px;">
	<div id="cabecera-salir" style="padding-top: 1px; background: #fc455c;">
		<table width="100%" height="100%" style="padding-top: 200px;">
            <tr valign="top">
                <td align="left" valign="top" style="padding-top: 0px;">
                    <div style="display: flex; align-items: baseline;">
                        <img style="height: 78px; width: 750px;"  src="../assets/img/c1_encuesta.png">
                        <div style="margin-top: 20px; margin-left: 195px; font-size: 20px; position: absolute; color: white;">
                            Completa la encuesta
                        </div>
                    </div>
                </td> 

                <td>
                    <div  style="width: 140px; height: 50px; ">
                        <button id="btn_cerrar_session" style="text-decoration: none; background: transparent; width: 100%; height: 100%;  background-repeat: no-repeat; border-radius: 35px; border: none; cursor:pointer; overflow: hidden; outline:none; background-position: center;">
                            <img style="width: 100%; height: 100%; " src="../assets/img/salir-2.png">
                        </button>
                    </div>
                </td>
            </tr>
        </table>
	</div>
	<audio src="" class="tts" hidden=""></audio>
	<div style=" padding-left: 10%; padding-right: 10%; padding-top: 10px; padding-bottom: 10px;"> 
	<table  class="" style="border-radius: 15px; width: 100%">
	<tr align="center" style="">
		<td align="right" width="50%" style="padding-top: 0; padding-bottom: 0;">
			<div style="width: 100%; " class="cont_encuesta">
		<div class="">
		<div class="tools progress">
			<div id="progress-wrapper">
				<div class="progressce">
					<div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						<span class="sr-only">0% Complete</span>
					</div>
				</div>

			</div>
		</div>
		<div id="content" class="outerframe">

			<div id="id_instru" class="groupdescription expand">
				<div id="div-instru" style="font: condensed 90% sans-serif;">
					<div id="btn_menos" style="display:flex; align-items: baseline;">
						<span style="width: 100%; display:flex;" class="head-group">
							<i  class="fa fa-minus-square">
								&nbsp;&nbsp;
								Instrucciones 
							</i>	
						</span>
						<span class="audio-parrafo reproducir-parrafo" data-audio="instrucciones_apoderado"></span>
					</div>
					<br>
					<span id="instrucciones" class="text" style="font: condensed 125% sans-serif; width: 100%; height: 100%; display: block;"><?php $result = etimologia_textos($mi_pais); echo $result["text_1_intro"];?>
                    </span>
				</div>
			</div>
			<div class="col-md-12">
				<span class="float-left"><img
					src="<?php echo $resultado["ce_pais_nombre"]; ?>.svg" alt=""
					width="50px" height="50px" data-toggle="tooltip"
					data-placement="right"
					title="<?php echo $resultado["ce_pais_nombre"]; ?>"></span>
			</div>
			<div id="question1"
				class="question-wrapper array-flexible-row mandatory input-error">

				<div class="question-text" style="color: black; font: condensed 90% sans-serif; text-align: center;">
					<span class="asterisk">*</span>
					<span class="qnumcode"> </span>
					<br>
					<span class="audio-item reproducir" data-audio="opciones-preguntas"></span>
					<span class="description-text"><strong>NU</strong> Nunca o casi nunca  </span>
					/ <span class="description-text"><strong> AL</strong> Algunas veces
					</span> / <span class="description-text"><strong> AM</strong> A
						menudo</span> / <span class="description-text"><strong> MV</strong>
						Muchas veces </span> / <span class="description-text"><strong> SC</strong>
						Siempre o casi siempre</span>
				</div>
				<div class="help-wrapper">
					<div class="mandatory error" id="obligatorio">
						<strong><br>
						<span class="errormandatory">Estas preguntas son de respuesta
								obligatoria. Por favor, complete todas las preguntas. </span></strong>
					</div>
					<div class="questionhelp" id="vmsg_1"></div>
					<div class="tip"></div>
				</div>
				<div class="answers-wrapper">
					<form id="encuesta_estudiante">
				<div id="grupo1">

					<table id="tabla_preguntas2"
						class="question subquestions-list questions-list mb-4 table table-striped">
						<colgroup class="col-responses">
							<col class="col-answers" width="20%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
						</colgroup>
						<thead>
							<tr class="dontread">
								<th><input type="text" name="txt_token" class="invisible" value="<?php echo $usuario;?>" readonly></th>
								<th>NU</th>
								<th>AL</th>
								<th>AM</th>
								<th>MV</th>
								<th>SC</th>
							</tr>
						</thead>
						<tbody>

							  <?php
							

							 if($id_pais != 1){
								 $nivel = 2;
							 }
							 $resultado = preguntas_compromiso_escolar_encuesta($nivel, $id_pais);
                                 
		                        ?>
                            <tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p01"] ?></td>
								<td><input type="radio" name="numero1" value="1"></td>
								<td><input type="radio" name="numero1" value="2"></td>
								<td><input type="radio" name="numero1" value="3"></td>
								<td><input type="radio" name="numero1" value="4"></td>
								<td><input type="radio" name="numero1" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p02"] ?></td>
								<td><input type="radio" name="numero2" value="1"></td>
								<td><input type="radio" name="numero2" value="2"></td>
								<td><input type="radio" name="numero2" value="3"></td>
								<td><input type="radio" name="numero2" value="4"></td>
								<td><input type="radio" name="numero2" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p03"] ?></td>
								<td><input type="radio" name="numero3" value="1"></td>
								<td><input type="radio" name="numero3" value="2"></td>
								<td><input type="radio" name="numero3" value="3"></td>
								<td><input type="radio" name="numero3" value="4"></td>
								<td><input type="radio" name="numero3" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p04"] ?></td>
								<td><input type="radio" name="numero4" value="1"></td>
								<td><input type="radio" name="numero4" value="2"></td>
								<td><input type="radio" name="numero4" value="3"></td>
								<td><input type="radio" name="numero4" value="4"></td>
								<td><input type="radio" name="numero4" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p05"] ?></td>
								<td><input type="radio" name="numero5" value="1"></td>
								<td><input type="radio" name="numero5" value="2"></td>
								<td><input type="radio" name="numero5" value="3"></td>
								<td><input type="radio" name="numero5" value="4"></td>
								<td><input type="radio" name="numero5" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p06"] ?></td>
								<td><input type="radio" name="numero6" value="1"></td>
								<td><input type="radio" name="numero6" value="2"></td>
								<td><input type="radio" name="numero6" value="3"></td>
								<td><input type="radio" name="numero6" value="4"></td>
								<td><input type="radio" name="numero6" value="5"></td>
							</tr>

                                    
                                </tbody>

					</table>
					<div>
					<input type="button" class="siguiente float-right" id="demos" value="Siguiente" onclick="grupo1()"></a>
					</div>
					</div>

					<div id="grupo2">
					<table id="tabla_preguntas2"
						class="question subquestions-list questions-list mb-4 table table-striped">
						<colgroup class="col-responses">
							<col class="col-answers" width="20%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
						</colgroup>
						<thead>
							<tr class="dontread">
								<th><input type="text" name="txt_token" class="invisible"
									value="<?php echo $usuario;?>" readonly></th>
								<th>NU</th>
								<th>AL</th>
								<th>AM</th>
								<th>MV</th>
								<th>SC</th>
							</tr>
						</thead>
						<tbody>
						
                            <tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p07"] ?></td>
								<td><input type="radio" name="numero7" value="1"></td>
								<td><input type="radio" name="numero7" value="2"></td>
								<td><input type="radio" name="numero7" value="3"></td>
								<td><input type="radio" name="numero7" value="4"></td>
								<td><input type="radio" name="numero7" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p08"] ?></td>
								<td><input type="radio" name="numero8" value="1"></td>
								<td><input type="radio" name="numero8" value="2"></td>
								<td><input type="radio" name="numero8" value="3"></td>
								<td><input type="radio" name="numero8" value="4"></td>
								<td><input type="radio" name="numero8" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p09"] ?></td>
								<td><input type="radio" name="numero9" value="1"></td>
								<td><input type="radio" name="numero9" value="2"></td>
								<td><input type="radio" name="numero9" value="3"></td>
								<td><input type="radio" name="numero9" value="4"></td>
								<td><input type="radio" name="numero9" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p010"] ?></td>
								<td><input type="radio" name="numero10" value="1"></td>
								<td><input type="radio" name="numero10" value="2"></td>
								<td><input type="radio" name="numero10" value="3"></td>
								<td><input type="radio" name="numero10" value="4"></td>
								<td><input type="radio" name="numero10" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p011"] ?></td>
								<td><input type="radio" name="numero11" value="1"></td>
								<td><input type="radio" name="numero11" value="2"></td>
								<td><input type="radio" name="numero11" value="3"></td>
								<td><input type="radio" name="numero11" value="4"></td>
								<td><input type="radio" name="numero11" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p012"] ?></td>
								<td><input type="radio" name="numero12" value="1"></td>
								<td><input type="radio" name="numero12" value="2"></td>
								<td><input type="radio" name="numero12" value="3"></td>
								<td><input type="radio" name="numero12" value="4"></td>
								<td><input type="radio" name="numero12" value="5"></td>
							</tr>
                                    
                                </tbody>
					</table>

					<div>
					<input type="button" class="siguiente float-right" id="demos2" value="Siguiente" onclick="grupo2()"/>
					<input type="button" class="atras float-left text-dark" id="atras" value="Atras" onclick="atras_pag_uno()"/>
					</div>
					</div>

					
					<div id="grupo3">
					<table id="tabla_preguntas2"
						class="question subquestions-list questions-list mb-4 table table-striped">
						<colgroup class="col-responses">
							<col class="col-answers" width="20%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
						</colgroup>
						<thead>
							<tr class="dontread">
								<th><input type="text" name="txt_token" class="invisible"
									value="<?php echo $usuario;?>" readonly></th>
								<th>NU</th>
								<th>AL</th>
								<th>AM</th>
								<th>MV</th>
								<th>SC</th>
							</tr>
						</thead>
						<tbody>

						
                            <tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p013"] ?></td>
								<td><input type="radio" name="numero13" value="1"></td>
								<td><input type="radio" name="numero13" value="2"></td>
								<td><input type="radio" name="numero13" value="3"></td>
								<td><input type="radio" name="numero13" value="4"></td>
								<td><input type="radio" name="numero13" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p014"] ?></td>
								<td><input type="radio" name="numero14" value="1"></td>
								<td><input type="radio" name="numero14" value="2"></td>
								<td><input type="radio" name="numero14" value="3"></td>
								<td><input type="radio" name="numero14" value="4"></td>
								<td><input type="radio" name="numero14" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p015"] ?></td>
								<td><input type="radio" name="numero15" value="1"></td>
								<td><input type="radio" name="numero15" value="2"></td>
								<td><input type="radio" name="numero15" value="3"></td>
								<td><input type="radio" name="numero15" value="4"></td>
								<td><input type="radio" name="numero15" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p016"] ?></td>
								<td><input type="radio" name="numero16" value="1"></td>
								<td><input type="radio" name="numero16" value="2"></td>
								<td><input type="radio" name="numero16" value="3"></td>
								<td><input type="radio" name="numero16" value="4"></td>
								<td><input type="radio" name="numero16" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p017"] ?></td>
								<td><input type="radio" name="numero17" value="1"></td>
								<td><input type="radio" name="numero17" value="2"></td>
								<td><input type="radio" name="numero17" value="3"></td>
								<td><input type="radio" name="numero17" value="4"></td>
								<td><input type="radio" name="numero17" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p018"] ?></td>
								<td><input type="radio" name="numero18" value="1"></td>
								<td><input type="radio" name="numero18" value="2"></td>
								<td><input type="radio" name="numero18" value="3"></td>
								<td><input type="radio" name="numero18" value="4"></td>
								<td><input type="radio" name="numero18" value="5"></td>
							</tr>

                                    
                                </tbody>

					</table>
					<div>
					<input type="button" class="siguiente float-right" id="demos3" value="Siguiente" onclick="grupo3()"/>
					<input type="button" class="atras float-left text-dark" id="atras_3" value="Atras" onclick="atras_pag_dos()"/>
					</div>
					</div>

					<div id="grupo4">
					<table id="tabla_preguntas2"
						class="question subquestions-list questions-list mb-4 table table-striped">
						<colgroup class="col-responses">
							<col class="col-answers" width="20%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
						</colgroup>
						<thead>
							<tr class="dontread">
								<th><input type="text" name="txt_token" class="invisible"
									value="<?php echo $usuario;?>" readonly></th>
								<th>NU</th>
								<th>AL</th>
								<th>AM</th>
								<th>MV</th>
								<th>SC</th>
							</tr>
						</thead>
						<tbody>

						
                            <tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p019"] ?></td>
								<td><input type="radio" name="numero19" value="1"></td>
								<td><input type="radio" name="numero19" value="2"></td>
								<td><input type="radio" name="numero19" value="3"></td>
								<td><input type="radio" name="numero19" value="4"></td>
								<td><input type="radio" name="numero19" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p020"] ?></td>
								<td><input type="radio" name="numero20" value="1"></td>
								<td><input type="radio" name="numero20" value="2"></td>
								<td><input type="radio" name="numero20" value="3"></td>
								<td><input type="radio" name="numero20" value="4"></td>
								<td><input type="radio" name="numero20" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p021"] ?></td>
								<td><input type="radio" name="numero21" value="1"></td>
								<td><input type="radio" name="numero21" value="2"></td>
								<td><input type="radio" name="numero21" value="3"></td>
								<td><input type="radio" name="numero21" value="4"></td>
								<td><input type="radio" name="numero21" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p022"] ?></td>
								<td><input type="radio" name="numero22" value="1"></td>
								<td><input type="radio" name="numero22" value="2"></td>
								<td><input type="radio" name="numero22" value="3"></td>
								<td><input type="radio" name="numero22" value="4"></td>
								<td><input type="radio" name="numero22" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p023"] ?></td>
								<td><input type="radio" name="numero23" value="1"></td>
								<td><input type="radio" name="numero23" value="2"></td>
								<td><input type="radio" name="numero23" value="3"></td>
								<td><input type="radio" name="numero23" value="4"></td>
								<td><input type="radio" name="numero23" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p024"] ?></td>
								<td><input type="radio" name="numero24" value="1"></td>
								<td><input type="radio" name="numero24" value="2"></td>
								<td><input type="radio" name="numero24" value="3"></td>
								<td><input type="radio" name="numero24" value="4"></td>
								<td><input type="radio" name="numero24" value="5"></td>
							</tr>

                                    
                                </tbody>

					</table>
					<div>
					<input type="button" class="siguiente float-right" id="demos4" value="Siguiente" onclick="grupo4()"/>
					<input type="button" class="atras float-left text-dark" id="atras_4" value="Atras" onclick="atras_pag_tres()"/>
					</div>
					</div>

					<div id="grupo5">
					<table id="tabla_preguntas2"
						class="question subquestions-list questions-list mb-4 table table-striped">
						<colgroup class="col-responses">
							<col class="col-answers" width="20%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
						</colgroup>
						<thead>
							<tr class="dontread">
								<th><input type="text" name="txt_token" class="invisible"
									value="<?php echo $usuario;?>" readonly></th>
								<th>NU</th>
								<th>AL</th>
								<th>AM</th>
								<th>MV</th>
								<th>SC</th>
							</tr>
						</thead>
						<tbody>

						
                            <tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p025"] ?></td>
								<td><input type="radio" name="numero25" value="1"></td>
								<td><input type="radio" name="numero25" value="2"></td>
								<td><input type="radio" name="numero25" value="3"></td>
								<td><input type="radio" name="numero25" value="4"></td>
								<td><input type="radio" name="numero25" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p026"] ?></td>
								<td><input type="radio" name="numero26" value="1"></td>
								<td><input type="radio" name="numero26" value="2"></td>
								<td><input type="radio" name="numero26" value="3"></td>
								<td><input type="radio" name="numero26" value="4"></td>
								<td><input type="radio" name="numero26" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p027"] ?></td>
								<td><input type="radio" name="numero27" value="1"></td>
								<td><input type="radio" name="numero27" value="2"></td>
								<td><input type="radio" name="numero27" value="3"></td>
								<td><input type="radio" name="numero27" value="4"></td>
								<td><input type="radio" name="numero27" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p028"] ?></td>
								<td><input type="radio" name="numero28" value="1"></td>
								<td><input type="radio" name="numero28" value="2"></td>
								<td><input type="radio" name="numero28" value="3"></td>
								<td><input type="radio" name="numero28" value="4"></td>
								<td><input type="radio" name="numero28" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p029"] ?></td>
								<td><input type="radio" name="numero29" value="1"></td>
								<td><input type="radio" name="numero29" value="2"></td>
								<td><input type="radio" name="numero29" value="3"></td>
								<td><input type="radio" name="numero29" value="4"></td>
								<td><input type="radio" name="numero29" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p030"] ?></td>
								<td><input type="radio" name="numero30" value="1"></td>
								<td><input type="radio" name="numero30" value="2"></td>
								<td><input type="radio" name="numero30" value="3"></td>
								<td><input type="radio" name="numero30" value="4"></td>
								<td><input type="radio" name="numero30" value="5"></td>
							</tr>

                                    
                                </tbody>

					</table>
					<div>
					<input type="button" class="siguiente float-right" id="demos5" value="Siguiente" onclick="grupo5()"/>
					<input type="button" class="atras float-left text-dark" id="atras_5" value="Atras" onclick="atras_pag_cuatro()"/>
					</div>
					</div>
					<div id="grupo6">
					<table id="tabla_preguntas2"
						class="question subquestions-list questions-list mb-4 table table-striped">
						<colgroup class="col-responses">
							<col class="col-answers" width="20%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
						</colgroup>
						<thead>
							<tr class="dontread">
								<th><input type="text" name="txt_token" class="invisible"
									value="<?php echo $usuario;?>" readonly></th>
								<th>NU</th>
								<th>AL</th>
								<th>AM</th>
								<th>MV</th>
								<th>SC</th>
							</tr>
						</thead>
						<tbody>

						
                            <tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p031"] ?></td>
								<td><input type="radio" name="numero31" value="1"></td>
								<td><input type="radio" name="numero31" value="2"></td>
								<td><input type="radio" name="numero31" value="3"></td>
								<td><input type="radio" name="numero31" value="4"></td>
								<td><input type="radio" name="numero31" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p032"] ?></td>
								<td><input type="radio" name="numero32" value="1"></td>
								<td><input type="radio" name="numero32" value="2"></td>
								<td><input type="radio" name="numero32" value="3"></td>
								<td><input type="radio" name="numero32" value="4"></td>
								<td><input type="radio" name="numero32" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p033"] ?></td>
								<td><input type="radio" name="numero33" value="1"></td>
								<td><input type="radio" name="numero33" value="2"></td>
								<td><input type="radio" name="numero33" value="3"></td>
								<td><input type="radio" name="numero33" value="4"></td>
								<td><input type="radio" name="numero33" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p034"] ?></td>
								<td><input type="radio" name="numero34" value="1"></td>
								<td><input type="radio" name="numero34" value="2"></td>
								<td><input type="radio" name="numero34" value="3"></td>
								<td><input type="radio" name="numero34" value="4"></td>
								<td><input type="radio" name="numero34" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p035"] ?></td>
								<td><input type="radio" name="numero35" value="1"></td>
								<td><input type="radio" name="numero35" value="2"></td>
								<td><input type="radio" name="numero35" value="3"></td>
								<td><input type="radio" name="numero35" value="4"></td>
								<td><input type="radio" name="numero35" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p036"] ?></td>
								<td><input type="radio" name="numero36" value="1"></td>
								<td><input type="radio" name="numero36" value="2"></td>
								<td><input type="radio" name="numero36" value="3"></td>
								<td><input type="radio" name="numero36" value="4"></td>
								<td><input type="radio" name="numero36" value="5"></td>
							</tr>

                                    
                                </tbody>

					</table>
					<div>
					<input type="button" class="siguiente float-right" id="demos6" value="Siguiente" onclick="grupo6()"/>
					<input type="button" class="atras float-left text-dark" id="atras_6" value="Atras" onclick="atras_pag_cinco()"/>
					</div>
					</div>
					<div id="grupo7">
					<table id="tabla_preguntas2"
						class="question subquestions-list questions-list mb-4 table table-striped">
						<colgroup class="col-responses">
							<col class="col-answers" width="20%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
						</colgroup>
						<thead>
							<tr class="dontread">
								<th><input type="text" name="txt_token" class="invisible"
									value="<?php echo $usuario;?>" readonly></th>
								<th>NU</th>
								<th>AL</th>
								<th>AM</th>
								<th>MV</th>
								<th>SC</th>
							</tr>
						</thead>
						<tbody>

						
                            <tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p037"] ?></td>
								<td><input type="radio" name="numero37" value="1"></td>
								<td><input type="radio" name="numero37" value="2"></td>
								<td><input type="radio" name="numero37" value="3"></td>
								<td><input type="radio" name="numero37" value="4"></td>
								<td><input type="radio" name="numero37" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p038"] ?></td>
								<td><input type="radio" name="numero38" value="1"></td>
								<td><input type="radio" name="numero38" value="2"></td>
								<td><input type="radio" name="numero38" value="3"></td>
								<td><input type="radio" name="numero38" value="4"></td>
								<td><input type="radio" name="numero38" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p039"] ?></td>
								<td><input type="radio" name="numero39" value="1"></td>
								<td><input type="radio" name="numero39" value="2"></td>
								<td><input type="radio" name="numero39" value="3"></td>
								<td><input type="radio" name="numero39" value="4"></td>
								<td><input type="radio" name="numero39" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p040"] ?></td>
								<td><input type="radio" name="numero40" value="1"></td>
								<td><input type="radio" name="numero40" value="2"></td>
								<td><input type="radio" name="numero40" value="3"></td>
								<td><input type="radio" name="numero40" value="4"></td>
								<td><input type="radio" name="numero40" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p041"] ?></td>
								<td><input type="radio" name="numero41" value="1"></td>
								<td><input type="radio" name="numero41" value="2"></td>
								<td><input type="radio" name="numero41" value="3"></td>
								<td><input type="radio" name="numero41" value="4"></td>
								<td><input type="radio" name="numero41" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p042"] ?></td>
								<td><input type="radio" name="numero42" value="1"></td>
								<td><input type="radio" name="numero42" value="2"></td>
								<td><input type="radio" name="numero42" value="3"></td>
								<td><input type="radio" name="numero42" value="4"></td>
								<td><input type="radio" name="numero42" value="5"></td>
							</tr>

                                    
                                </tbody>

					</table>
					<div>
					<input type="button" class="siguiente float-right" id="demos7" value="Siguiente" onclick="grupo7()"/>
					<input type="button" class="atras float-left text-dark" id="atras_7" value="Atras" onclick="atras_pag_seis()"/>
					</div>
					</div>
					<div id="grupo8">
					
					<table id="tabla_preguntas2"
						class="question subquestions-list questions-list mb-4 table table-striped">
					
						<colgroup class="col-responses">
							<col class="col-answers" width="20%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
							<col class="even" width="16%">
							<col class="odd" width="16%">
						</colgroup>
						<thead>
							<tr class="dontread">
								<th><input type="text" name="txt_token" class="invisible"
									value="<?php echo $usuario;?>" readonly></th>
								<th>NU</th>
								<th>AL</th>
								<th>AM</th>
								<th>MV</th>
								<th>SC</th>
							</tr>
						</thead>
						<tbody>

						
                            <tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p043"] ?></td>
								<td><input type="radio" name="numero43" value="1" onclick="activa_boton_guardar()"></td>
								<td><input type="radio" name="numero43" value="2" onclick="activa_boton_guardar()"></td>
								<td><input type="radio" name="numero43" value="3" onclick="activa_boton_guardar()"></td>
								<td><input type="radio" name="numero43" value="4" onclick="activa_boton_guardar()"></td>
								<td><input type="radio" name="numero43" value="5" onclick="activa_boton_guardar()"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p044"] ?></td>
								<td><input type="radio" name="numero44" onclick="activa_boton_guardar()" value="1"></td>
								<td><input type="radio" name="numero44" onclick="activa_boton_guardar()" value="2"></td>
								<td><input type="radio" name="numero44" onclick="activa_boton_guardar()" value="3"></td>
								<td><input type="radio" name="numero44" onclick="activa_boton_guardar()" value="4"></td>
								<td><input type="radio" name="numero44" onclick="activa_boton_guardar()" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p045"] ?></td>
								<td><input type="radio" name="numero45" onclick="activa_boton_guardar()" value="1"></td>
								<td><input type="radio" name="numero45" onclick="activa_boton_guardar()" value="2"></td>
								<td><input type="radio" name="numero45" onclick="activa_boton_guardar()" value="3"></td>
								<td><input type="radio" name="numero45" onclick="activa_boton_guardar()" value="4"></td>
								<td><input type="radio" name="numero45" onclick="activa_boton_guardar()" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p046"] ?></td>
								<td><input type="radio" name="numero46" onclick="activa_boton_guardar()" value="1"></td>
								<td><input type="radio" name="numero46" onclick="activa_boton_guardar()" value="2"></td>
								<td><input type="radio" name="numero46" onclick="activa_boton_guardar()" value="3"></td>
								<td><input type="radio" name="numero46" onclick="activa_boton_guardar()" value="4"></td>
								<td><input type="radio" name="numero46" onclick="activa_boton_guardar()" value="5"></td>
							</tr>
							<tr>
								<td class="font-weight-bold text-right pr-5"><?php echo $resultado["p047"] ?></td>
								<td><input type="radio" name="numero47" onclick="activa_boton_guardar()" value="1"></td>
								<td><input type="radio" name="numero47" onclick="activa_boton_guardar()" value="2"></td>
								<td><input type="radio" name="numero47" onclick="activa_boton_guardar()" value="3"></td>
								<td><input type="radio" name="numero47" onclick="activa_boton_guardar()" value="4"></td>
								<td><input type="radio" name="numero47" onclick="activa_boton_guardar()" value="5"></td>
							</tr>
						                                    
                                </tbody>

					</table>
					<input name="fecha" type="text" id="horainicio" hidden readonly>		
</form>
					<div>
					<input type="button" class="desactivado float-right" id="demos8" value="Guardar" onclick="grupo8()" />
					<input type="button" class="atras float-left text-dark" id="atras_8" value="Atras" onclick="atras_pag_siete()"/>
					</div>
					</div>
					<div>					
				</div>
				<div class="question-help">
					<div id="pageInfo"></div>
				</div>
			</div>

		</div>
	</div>
</div>

</div>
	</td>
		<td align="left" width="50%" style="display: flex; float: left; background: #e6e6e6; padding-bottom: 0; padding-top: 0;">
			<div id="pasos" style="">
	        	<img id="img_pasos" src="../assets/img/1.png" style="height: 550px;">
	        </div>	
		</td>
	</tr>
		
	</table>
</div>

	
	


<?php require_once "js.php"; ?>

		<script type="text/javascript"> 
		var hora_inicio =  moment().format("YYYY-MM-DD HH:mm:ss");
        $('#horainicio').val(hora_inicio);


history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
	
	$('#demos8').prop('disabled', true);

		$("#grupo1").show();
			
		$("#grupo2").hide();
		$("#grupo3").hide();
		$("#grupo4").hide();    
		$("#grupo5").hide();
		$("#grupo6").hide();
		$("#grupo7").hide();
		$("#grupo8").hide();
		$("#obligatorio").hide();

		function grupo1() {	
			if( $('input[name="numero1"]:checked').val() === undefined || $('input[name="numero2"]:checked').val() === undefined || $('input[name="numero3"]:checked').val() === undefined || $('input[name="numero4"]:checked').val() === undefined || $('input[name="numero5"]:checked').val() === undefined || $('input[name="numero6"]:checked').val() === undefined ) {
				alertify.error("Existen preguntas sin responder");
				$("#obligatorio").show();
				return
			} else {	
				Cambia_Numero(0);
				$("#obligatorio").hide();
				document.getElementById('progress-wrapper').innerHTML = '<div class="progressce"> <div class="progress-bar active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%">10%  </div></div>';
				$("#grupo2").show();
				$("#grupo1").hide();								
			}
		}

		function grupo2() {
			if( $('input[name="numero7"]:checked').val() === undefined || $('input[name="numero8"]:checked').val() === undefined || $('input[name="numero9"]:checked').val() === undefined || $('input[name="numero10"]:checked').val() === undefined || $('input[name="numero11"]:checked').val() === undefined || $('input[name="numero12"]:checked').val() === undefined ) {
				alertify.error("Existen preguntas sin responder");
				$("#obligatorio").show();
				return
			} else {
				Cambia_Numero(0);
			   document.getElementById('progress-wrapper').innerHTML = '<div class="progressce">  <div class="progress-bar active" role="progressbar" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100" style="width:28%">28%</div></div>';
				$("#grupo3").show();
				$("#grupo2").hide();
				$("#obligatorio").hide();
			}	
		}

		function grupo3() {
			if( $('input[name="numero13"]:checked').val() === undefined || $('input[name="numero14"]:checked').val() === undefined || $('input[name="numero15"]:checked').val() === undefined || $('input[name="numero16"]:checked').val() === undefined || $('input[name="numero17"]:checked').val() === undefined || $('input[name="numero18"]:checked').val() === undefined ) {
				alertify.error("Existen preguntas sin responder");
				$("#obligatorio").show();
				return
			} else {
				Cambia_Numero(0);
				document.getElementById('progress-wrapper').innerHTML = '<div class="progressce">  <div class="progress-bar active" role="progressbar" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100" style="width:42%">42%</div></div>';
				$("#grupo4").show();
				$("#grupo3").hide();
				$("#obligatorio").hide();

			}	
		}

		function grupo4() {
			if( $('input[name="numero19"]:checked').val() === undefined || $('input[name="numero20"]:checked').val() === undefined || $('input[name="numero21"]:checked').val() === undefined || $('input[name="numero22"]:checked').val() === undefined || $('input[name="numero23"]:checked').val() === undefined || $('input[name="numero24"]:checked').val() === undefined ) {
				alertify.error("Existen preguntas sin responder");
				$("#obligatorio").show();
				return
			} else {
				Cambia_Numero(0);
				document.getElementById('progress-wrapper').innerHTML = '<div class="progressce">  <div class="progress-bar active" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width:57%">57%</div></div>';
				$("#grupo5").show();
				$("#grupo4").hide();
				$("#obligatorio").hide();

			}	
		}

		function grupo5() {
			if( $('input[name="numero25"]:checked').val() === undefined || $('input[name="numero26"]:checked').val() === undefined || $('input[name="numero27"]:checked').val() === undefined || $('input[name="numero28"]:checked').val() === undefined || $('input[name="numero29"]:checked').val() === undefined || $('input[name="numero30"]:checked').val() === undefined ) {
				alertify.error("Existen preguntas sin responder");
				$("#obligatorio").show();
				return
			} else {
				Cambia_Numero(0);
				document.getElementById('progress-wrapper').innerHTML = '<div class="progressce">  <div class="progress-bar active" role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100" style="width:71%">71%</div></div>';
				$("#grupo6").show();
				$("#grupo5").hide();
				$("#obligatorio").hide();

			}	
		}

		function grupo6() {
			if($('input[name="numero31"]:checked').val() === undefined || $('input[name="numero32"]:checked').val() === undefined || $('input[name="numero33"]:checked').val() === undefined || $('input[name="numero34"]:checked').val() === undefined || $('input[name="numero35"]:checked').val() === undefined || $('input[name="numero36"]:checked').val() === undefined ) {
				alertify.error("Existen preguntas sin responder");
				$("#obligatorio").show();
				return
			} else {	
				document.getElementById('progress-wrapper').innerHTML = '<div class="progressce">  <div class="progress-bar active" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width:85%">85%</div></div>';		
				$("#grupo7").show();
				$("#grupo6").hide();
				$("#obligatorio").hide();
				Cambia_Numero(0);
			}	
		}

		function grupo7() {
			if($('input[name="numero37"]:checked').val() === undefined || $('input[name="numero38"]:checked').val() === undefined || $('input[name="numero39"]:checked').val() === undefined || $('input[name="numero40"]:checked').val() === undefined || $('input[name="numero41"]:checked').val() === undefined || $('input[name="numero42"]:checked').val() === undefined ) {
				alertify.error("Existen preguntas sin responder");
				$("#obligatorio").show();
				return
			} else {	
				Cambia_Numero(0);
				document.getElementById('progress-wrapper').innerHTML = '<div class="progressce">  <div class="progress-bar active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">100%</div></div>';			
				$("#grupo8").show();
				$("#grupo7").hide();
				$("#obligatorio").hide();			
			}	
		}

		function grupo8() {
			if($('input[name="numero43"]:checked').val() === undefined || $('input[name="numero44"]:checked').val() === undefined || $('input[name="numero45"]:checked').val() === undefined || $('input[name="numero46"]:checked').val() === undefined || $('input[name="numero47"]:checked').val() === undefined ) {
				alertify.error("Existen preguntas sin responder");
				$("#obligatorio").show();
				return
			} else {
				Cambia_Numero(0);
				$("#obligatorio").hide();
				var hora_termino = moment().format('YYYY-MM-DD HH:mm:ss');
				var hora_inicio = $('#horainicio').val()
				var formData = new FormData(document.getElementById("encuesta_estudiante"));
				formData.append('hora_inicio', hora_inicio);
				formData.append('hora_final', hora_termino);
				$.ajax({
                    url: "guarda_encuesta.php",
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
						var respuesta = (JSON.parse(response));
						console.log(respuesta);
						if(respuesta.estado === "1") {                   
                            window.location.href = 'final.php';
                        } else if(respuesta.estado === "0") {     
							alertify.alert(
								"Notificación",
								"Respuestas no guardadas, por favor intente nuevamente."
							);              
                        } else if(respuesta.estado === "error") {    
							alertify.alert(
								"Notificación",
								"Ha ocurrido un error, por favor contactarse con el administrador de sistema."
							);              
						}		
                    }
                });
			}	
		}
			

		function atras_pag_uno(){
			$(document).ready(function(){
				$("#grupo1").show();
				$("#grupo2").hide();
				Cambia_Numero(1);
			});

		}
		function atras_pag_dos(){
			$(document).ready(function(){
				$("#grupo2").show();
				$("#grupo3").hide();
				Cambia_Numero(1);
			});

		}
		function atras_pag_tres(){
			$(document).ready(function(){
				$("#grupo3").show();
				$("#grupo4").hide();
				Cambia_Numero(1);
			});

		}
		function atras_pag_cuatro(){
			$(document).ready(function(){
				$("#grupo4").show();
				$("#grupo5").hide();
				Cambia_Numero(1);
			});

		}

		function atras_pag_cinco(){
			$(document).ready(function(){
				$("#grupo5").show();
				$("#grupo6").hide();
				Cambia_Numero(1);
			});

		}
		function atras_pag_seis(){
			$(document).ready(function(){
				$("#grupo6").show();
				$("#grupo7").hide();
				Cambia_Numero(1);
			});

		}

		function atras_pag_siete(){
			$(document).ready(function(){
				$("#grupo7").show();
				$("#grupo8").hide();
				Cambia_Numero(1);
			});

		}
		
				
		function activa_boton_guardar(){
			$(document).ready(function(){
				if( $('input[name="numero43"]:checked').val() != undefined && $('input[name="numero44"]:checked').val() != undefined && $('input[name="numero45"]:checked').val() != undefined && $('input[name="numero46"]:checked').val() != undefined && $('input[name="numero47"]:checked').val() != undefined ){
				$("#demos8").show();
				$('#demos8').prop('disabled', false);
				$("#demos8").removeClass("desactivado").addClass("siguiente");
				
			}else{	
				$('#demos8').prop('disabled', true);				
				
			}	

			})
		}

		
     
		function deshabilitaRetroceso(){
    window.location.hash="no-back-button";
    window.location.hash="Again-No-back-button" //chrome
    window.onhashchange=function(){window.location.hash="no-back-button";}
}


										
		</script>

    <footer class="page-footer pt-4" style="margin-bottom: 0px; padding-bottom: 0px; bottom: 0; height: 200px;">
        <div class="container" style="margin-bottom: 20px;">
            <table cellpadding="10">
                    <tr>
                        <td align="left" valign="center">
                            <div style="display: flex; align-items: baseline;">
                                <img style="margin-right: 5px;" width="44" src="../assets/img/mineduc.png">
                                <img style="margin-right: 5px;" width="120" src="../assets/img/fondef.png">
                                <img style="margin-right: 5px;" width="100" src="../assets/img/corfo.png">
                                <img style="margin-right: 5px;" width="30" src="../assets/img/ufro.png">
                                <img style="margin-right: 5px;" width="90" src="../assets/img/autonoma.png">
                                <img style="margin-right: 5px;" width="150" src="../assets/img/fund_telefonica.png">
                            </div>
                        </td>
                        <td width="33%" align="center" valign="center" >
                            <p style="font-size: small; text-align: justify; font: condensed 80% sans-serif; color: #212529;">
                                Estas encuestas forman parte del Proyecto FONDEF ID14I10078-ID14I20078 Medición del compromiso del niño, niña y adolescente con sus estudios para la promoción de trayectorias educativas exitosas.
                            </p>
                        </td>
                    </tr>
               </table>
            </div>
        </footer>
        
		<style type="text/css">
			.progress-bar {
  background: #40c2d4;
}
            .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
                font-weight: 900;
            }

            body {
                color: #212529;
            }
		</style>
</body>
</html>
<?php
} else {
    header("location:../index2.php");
}