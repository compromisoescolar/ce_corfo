
//Validamos login de auto
function valida_campos() {
    $(document).ready(function () {
        $('#ingresar').click(function () {
            if ($('#nombre').val() == "") {
                alertify.defaults.glossary.title = '<p class="text-center">Notificación<p>';
                alertify.alert("Debes ingresar el usuario");

                return false;
            } else if ($('#contrasena').val() == "") {
                alertify.defaults.glossary.title = '<p class="text-center">Notificación<p>';
                alertify.alert("Debes ingresar la contraseña");
                return false;
            } else {
                cadena = "nombre=" + $('#nombre').val() +
                        "&contrasena=" + $('#contrasena').val();
                $.ajax({
                    type: "POST",
                    url: "php/valida_login.php",
                    data: cadena,
                    cache: false,
                    beforeSend: function(){
                        document.getElementById("ingresar").disabled =true;
                        document.getElementById("inicia").innerHTML = '';
                        document.getElementById("spinner").innerHTML = '<div class="sk-three-bounce"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>';
                    },
                    success: function (r) {
                        if (r == 1) {
                            window.location.href = "index.php";
                        } else {
                            document.getElementById("ingresar").disabled =false;
                            document.getElementById("spinner").innerHTML = '';
                            document.getElementById("inicia").innerHTML = 'Iniciar';
                        
                            alertify.alert("Notificacion",'Usuario Incorrecto');

                        }
                    }
                });
            }

        });
    });


}
;
function nuevo_estudiante() {
    $(document).ready(function () {
        $('.new_student').click(function () {

            var ciudad = $('#ciudad_estudiante').text();

            var id_establecimiento = $('#id_establecimiento').text();

            var id_curso = $('#id_curso').text();

            var id_docente = $('#id_docente').text();

            $('#new_ciudad').val(ciudad);         
                            
            $('#guardar_new_student').click(function () {
                if ($('#new_name').val() === '') {
                    alertify.set('notifier', 'position', 'top-left');
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#new_name").focus();
                    alertify.alert($('#ciudad_estudiante').val());
                } else if ($('#new_apellidos').val() === '') {
                    alertify.set('notifier', 'position', 'top-left');
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#new_apellidos").focus();
                } else if ($('#new_run').val() === '') {
                    alertify.set('notifier', 'position', 'top-left');
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#new_run").focus();
                } else if ($('#fecha_naci_es_new').val() === '') {
                    alertify.set('notifier', 'position', 'top-left');
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#fecha_naci_es_new").focus();
                }

                else {
                    var run_a_limpiar = $('#new_run').val();
                    var run_limpio = run_a_limpiar.replace(/[.-]/g,'');
                    var nuevo_estu = 'new';
                    $.ajax({
                        url: "conf/delete.php",
                        type: "POST",
                        dataType: "html",
                        data: "nuevo=" + nuevo_estu + "&nombres=" + $('#new_name').val() + "&apellidos=" + $('#new_apellidos').val() + "&run_estudiante=" + run_limpio + "&ciudad=" + $('#ciudad_estudiante').val() + "&fecha_nacimi=" + $('#fecha_naci_es_new').val() + "&id_establecimiento=" + id_establecimiento + "&id_docente=" + id_docente + "&id_curso=" + id_curso,
                        success: function (response) {
                            alertify.alert(response);
                            var fila = (JSON.parse(response));
                            if (fila.Estado === 'Exitoso') {
                                alertify.alert('Notificación', 'El Estudiante con nombre ' + fila.nombre + ' ' + fila.apellidos + ', Run: ' + fila.run_estudi + ' se ha guardado correctamente');
                            }
                            if (fila.Estado === 'Existe') {
                                alertify.alert('Notificación', 'El Estudiante con nombre ' + fila.nombre + ' ' + fila.apellidos + ', Run: ' + fila.run_estudi + ' existe');
                            }
                            setTimeout(function () {
                                recarga_tabla();
                            }, 2000);
                        }
                    });
                }

            });
        });
    });
}
;
function carga_update() {
    $(document).ready(function () {
        $('.update_estu').click(function () {
            var update = 'update';
            var id_es = $(this).parents("tr").find("td").eq(0).text();
            var nombre_es = $(this).parents("tr").find("td").eq(1).text();
            var apelli_es = $(this).parents("tr").find("td").eq(2).text();
            var run_es_proble = $(this).parents("tr").find("td").eq(3).text();
            var fecha_naci_es = $(this).parents("tr").find("td").eq(4).text();
            var ciu_est = $(this).parents("tr").find("td").eq(5).text();
            var token_es = $(this).parents("tr").find("td").eq(7).text();
            var curso_es = $(this).parents("tr").find("td").eq(8).text();
                      
            $('#nombre_es').val(nombre_es); 
            $('#apelli_es').val(apelli_es);
            $('#run_es_proble').val(run_es_proble);        
            $('#curso_es').val(curso_es);          
            $('#ciu_est').val(ciu_est);
            $('#token_es').val(token_es);
            $('#fecha_naci_es').val(fecha_naci_es);
                      
            $('#actualizar_es').click(function () {
                if ($('#nombre_es').val() === '') {
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#nombre_es").focus();
                } else if ($('#apelli_es').val() === '') {
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#apelli_es").focus();
                } else if ($('#run_es').val() === '') {
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#run_es").focus();
                } 
                else if ($('#curso_es').val() === '') {
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#run_es_proble").focus();
                    }
                 else if ($('#ciu_est').val() === '') {
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#ciu_est").focus();
                } else if ($('#token_es').val() === '') {
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#token_es").focus();
                } else if ($('#fecha_naci_es').val() === '') {
                    alertify.error('<div class="text-white text-center">Campo Requerido</div>');
                    $("#fecha_naci_es").focus();
                } else {

                    $.ajax({
                        url: "conf/delete.php",
                        type: "POST",
                        data: "update=" + update + "&id_es=" + id_es + "&nombre_es=" + $('#nombre_es').val() + "&apelli_es=" + $('#apelli_es').val() + "&run_es_proble=" + $('#run_es_proble').val() + "&curso_es=" + $('#curso_es').val() +  "&ciu_est=" + $('#ciu_est').val() + "&token_es=" + $('#token_es').val() + "&fecha_naci_es=" + $('#fecha_naci_es').val(),})
                        .done(function (res) {                       
                        alertify.success('<div class="text-center text-white">Datos Actualizados</div>');
                        console.log(res)
                        /*
                        setTimeout(function () {
                            recarga_tabla();
                        }, 2000);
                        */
                    }).fail(function () {
                        alert('Fallo');
                    });
                }

            });

        });
    });
}

function eliminar_estudiante() {
    $(document).ready(function () {
        $(".con").click(function () {
            var confirma = confirm('¿Esta seguro(a) que desea eliminar este registro?');
            if (confirma === true) {
                var id = $(this).attr("id");
                console.log( id);
                var elimina = 'borra';
                $.ajax({
                    url: "conf/delete.php",
                    type: "POST",
                    data: "id=" + id + "&eliminar=" + elimina

                }).done(function () {
                    alertify.success('<div class="text-center text-white">Estudiante Eliminado</div>');                    
                    setTimeout(function () {
                        recarga_tabla()
                    }, 2000);

                }).fail(function () {
                    alertify.error('<div class="text-center text-white">A ocurrido un error</div>');
                });

            } else
            {
                recarga_tabla();

            }

        });
    });
}



function recarga_tabla() {
    $(document).ready(function () {
        location.reload('eliminar_datos.php #myTable');
        //$('#load-products').load('eliminar_datos.php');
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

function toolpit() {
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
}


function refresh_modal() {
    $(document).ready(function () {
      /*  $('#cerrar_modal, #cerrar_crear').click(function () {
            location.reload('eliminar_datos.php #load-products');
        });
        $('#cerrar_modal_nuevo').click(function () {
            location.reload('eliminar_datos.php #load-products');
        });
        //$('#load-products').load('eliminar_datos.php');
        */
    });
}

function solo_letras() {
    $(document).ready(function () {
        $(".letras").keypress(function (key) {
            window.console.log(key.charCode);
            if ((key.charCode < 97 || key.charCode > 122)//letras mayusculas
                    && (key.charCode < 65 || key.charCode > 90) //letras minusculas
                    && (key.charCode !== 45) //retroceso
                    && (key.charCode !== 241) //ñ
                    && (key.charCode !== 209) //Ñ
                    && (key.charCode !== 32) //espacio
                    && (key.charCode !== 225) //á
                    && (key.charCode !== 233) //é
                    && (key.charCode !== 237) //í
                    && (key.charCode !== 243) //ó
                    && (key.charCode !== 250) //ú
                    && (key.charCode !== 193) //Á
                    && (key.charCode !== 201) //É
                    && (key.charCode !== 205) //Í
                    && (key.charCode !== 211) //Ó
                    && (key.charCode !== 218) //Ú

                    )
                return false;
        });
    });
}

function solo_numeros(){
    $(document).ready(function () {
        $('#rbd_usuario').keyup(function () {
            $("#rbd_usuario").attr('maxlength', '9');
            this.value = (this.value + '').replace(/[^0-9]/g, '');
        });
    });
}

function desactivar_input() {
    $(document).ready(function () {
        $("#new_rbd,#new_establecimiento,#new_curso,#new_run_docente,#estable_es,#rbd_es,#curso_es,#run_docen,#regciu_est").prop("disabled", true);
    });
}
/*
function paginacion() {
    $(function () {
        $("#myTable, #usuarios").hpaging({
            "limit": 6
        });
    });
}
*/

function paginacion_editar_estudiantes(){
    $(document).ready( function () {
        $('#myTable').DataTable({
            "responsive": true,       
            "searching": true,
            "lengthChange": true,
            "pagingType": "simple",
            "bInfo" : false,
            "ordering": true,
             "language": {
                "search": "<span class='text-white'>Buscar<span>",
                "lengthMenu": "<span class='text-white'>Cantidad</span> _MENU_",
              "paginate": {
            "next": "Siguiente <i class='fa fa-arrow-right' aria-hidden='true'></i>",
            "previous": "<i class='fa fa-arrow-left' aria-hidden='true'></i> Atras "
            
              }
            }
        });
    } );
}
function letra_mayuscula() {
    $(document).ready(function () {
        /*
        $("#contrasena").keyup(function () {
            $("#contrasena").val($(this).val().toUpperCase());
        });*/
    });
}

function validar_extension_apoyo() {
    $(document).ready(function () {
        $(document).on('change', '#file_doc', function () {
            // this.files[0].size recupera el tamaño del archivo
            // alert(this.files[0].size);
            var fileName = this.files[0].name;

            // recuperamos la extensión del archivo
            var ext = fileName.split('.').pop();

            // console.log(ext);
            switch (ext) {
                case 'docx':
                case 'pdf':
                 case 'pptx':
                case 'mp3':
                case 'mp4':
                case 'avi':
                    break;
                default:
                    alertify.alert('Notificación', 'El archivo no tiene la extensión adecuada');
                    this.value = ''; // reset del valor
            }

        });
    });
}


function validar_extension() {
    $(document).ready(function () {       
            $(document).on('change', '#file', function () {
           
                var formData = new FormData();
                formData.append('file', $('#file')[0].files[0]);
    
                $.ajax({
                    url: "php/conf_excel.php",
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
                            alertify.alert("Notificación","Algunos campos el archivo se encuentran vacíos");
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

function ingresa_material() {
    $(document).ready(function () {
        $("#carga_documento").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("carga_documento"));
            formData.append("dato", "valor");
            $.ajax({
                url: "conf/sube_material.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                statusCode: {
                    404: function () {
                        alertify.alert("Pagina no Encontrada");

                    },
                    502: function () {
                        alertify.alert("Ha ocurrido un error al conectarse con el servidor");

                    }
                },
                beforeSend: function () {
                    $('#spinner_doc').html('<span class="text-white"><i class="fa fa-spinner fa-spin fa-3x"></i></span>');

                },
                success: function (response) {
                       console.log(response);
                      $('#spinner_doc').remove();
                    alertify.alert("Notificación","Documento Guardado <br><strong>Nota: Para ver el o los documentos subidos debe refrescar la Pagina.");

                    //window.location.reload()
                   // $('#spinner_doc').html('<span class="text-white"><i class="fa fa-spinner fa-spin fa-3x"></i></span>');
                }
            });
        });
    });
}

function expandir_texto(){
    $(document).ready(function() {
      $('div.expande p').expander({
      slicePoint: 10, // si eliminamos por defecto es 100 caracteres
      expandText: '[+]', // por defecto es 'read more...'
      collapseTimer: 5000, // tiempo de para cerrar la expanción si desea poner 0 para no cerrar
      userCollapseText: '[-]' // por defecto es 'read less...'
    });
});
}


//SECCION ADMINISTRADOR

function eliminar_usuario(){
    $(document).ready(function(){
        $(".eliminar_usuario").click(function(){
            //obtenemos el id del usuario en la posición 0
            usuario = $(this).parents("tr").find("td").eq(0).text();
            alertify.confirm('Notificación','¿Esta seguro(a) que desea eliminar este registro?', function(){
            var eliminar_usuario = 'eliminar_usuario';
            $.ajax({
                url: "conf/delete.php",
                type: "POST",
                dataType: "html",
                data: "id_usuario=" + usuario + "&eliminar=" + eliminar_usuario,
                statusCode: {
                    404: function () {
                        alertify.alert("Pagina no Encontrada");

                    },
                    502: function () {
                        alertify.alert("Ha ocurrido un error al conectarse con el servidor");

                    }
                },
                success: function(response) {
                 var fila = (JSON.parse(response));
                  if(fila.Estado === "Exitoso"){
                    alertify.success("<div class='text-center text-white'>Registro Eliminado</div>");
                    setTimeout(function () {
                        location.reload('index_admin.php #usuarios');
                    }, 5000);

                  }
                  else if(fila.Estado === "No_Eliminado_usuario"){
                    alertify.error("<div class='text-center text-white'>Registro no Eliminado</div>");
                    setTimeout(function () {
                        location.reload('index_admin.php #usuarios');
                    }, 5000);
                  }
                }
            });

         },
         function(){ alertify.error('<div class="text-white text-center">No Eliminado</div>')

        });


        });

    });
}

function ingresa_nuevo_usuario() {
    $(document).ready(function () {
        $("#formu_nuevo_usuario").on("submit", function (e) {
                e.preventDefault();
                var f = $(this);
                var formData = new FormData(document.getElementById("formu_nuevo_usuario"));
                formData.append("dato", "valor");
                $.ajax({
                    url: "conf/delete.php",
                    type: "post",
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
                        var fila_usuario = (JSON.parse(response));
                        if(fila_usuario.Estado === "Guardado_Usuario"){
                            alertify.set('notifier','position', 'top-center');
                            alertify.success("<div class='text-center text-white'>Datos Guardados</div>");
                        }
                        else if(fila_usuario.Estado === "existe"){
                            alertify.set('notifier','position', 'top-center');
                            alertify.error("<div class='text-center text-white'>Los datos existen</div>");
                            $("#rbd_usuario").val("");
                            $("#contrase_usuario").val("");
                        }
                        else if(fila_usuario.Estado === "error"){
                            alertify.set('notifier','position', 'top-center');
                            alertify.error("<div class='text-center text-white'>Se ha producido un error al guardar los datos</div>");

                        }

                    }
                });



        });
    });
}

function update_usuario(){
    $(document).ready(function(){
        $("#formu_nuevo_usuario_update").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formu_nuevo_usuario_update"));
            $.ajax({
                url: "conf/delete.php",
                type: "post",
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
                    var fila_usuario = (JSON.parse(response));
                    if(fila_usuario.Estado === "Actualizado_Usuario"){
                        alertify.set('notifier','position', 'top-center');
                        alertify.success("<div class='text-center text-white'>Datos Actualizados</div>");
                        setTimeout(function () {
                            location.reload('update_usuario.php # update_usuarios');
                        }, 5000);

                    }
                    else if(fila_usuario.Estado === "Actualizado_Usuario_error"){
                        alertify.set('notifier','position', 'top-center');
                        alertify.error("<div class='text-center text-white'>Se ha producido un error al actualizar los datos</div>");

                    }

                }
            });
        });
    });
}


function valida_input_vacio(){
    $(document).ready(function(){
    $("#rbd_usuario").focusout(function(){
        if( $("#rbd_usuario").val() === ""){
            $("#rbd_usuario").focus();
        }
    });
})

}

function ante_poner_letra(){
    $(document).ready(function(){
        $( "#run_prof" ).keyup(function() {    
                var value = $( this ).val();             
              $( "#pass_docente" ).val("P"+value);                   
                 
              $( "#user_docente").val(value);

              
              
              
          });
    })
}

function ante_poner_letra_new_sos(){
    $(document).ready(function(){
        $( "#run_soste" ).keyup(function() {    
                var value = $( this ).val();             
              $("#pass_sostenedor" ).val("S"+value);                   
                 
              $("#user_sostenedor").val(value);             
              
              
          });
    })
}

function ante_poner_letra_update_docente(){
    $(document).ready(function(){
        $( "#run_docente_update" ).keyup(function() {    
                var value = $( this ).val();             
              $( "#pass_docente_update" ).val("P"+value);               
          
              $( "#user_docente_update" ).val(value);                          
              
          });
    })
}
   
function ante_poner_letra_update_sostenedor(){
    $(document).ready(function(){
        $( "#run_soste_update" ).keyup(function() {    
                var value = $( this ).val();             
              $( "#pass_soste_update" ).val("P"+value);               
          
              $( "#user_soste_update" ).val(value);                          
              
          });
    })
}
   
  
function validar_run(){
    $(document).ready(function(){
      //  $(".demo_run").rut();
      //  $(".run_es_proble").rut();
    })

}

//funcion para guardar pregunta editada
function edita_pregunta(){
    $(document).ready(function(){
        $("#formu_editar_pregunta").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formu_editar_pregunta"));
            formData.append("dato", "valor");
            $.ajax({
                url: "conf/update_pregunta.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                statusCode: {
                    404: function () {
                        alertify.alert("Notificación","Pagina no Encontrada");

                    },
                    502: function () {
                        alertify.alert("Notificación","Ha ocurrido un error al conectarse con el servidor");

                    }
                },
                success: function (response) {
                var fila_usuario = (JSON.parse(response));
                if(fila_usuario.Estado === "Exitoso"){
                    alertify.success("<div class='text-white text-center'>Pregunta Actualizada</div>");
                }
                else if(fila_usuario.Estado === "No_Exitoso"){
                    alertify.error("<div class='text-white text-center'>Pregunta No Actualizada</div>");
                }
                else{
                    alertify.error("<div class='text-white text-center'>ERROR DESCONOCIDO</div>");
                }

                }

            });
        });
    })
}

//ZONA DE ENCUESTA //////////////////////////////////////
if(typeof(grecaptcha) != "undefined") {
    grecaptcha.ready(function() {
        grecaptcha.execute('6Le4kagZAAAAAPrJvezXbADOrTQVxo69xZg1cyK6', {action: 'submit'}).then(function(token) {
            $('#token').val(token); // here i set value to hidden field
        });
    });
}

function valida_token_estu(){
    $(document).ready(function(){
        $("#fm_codigo").on("submit", function (e) {
            e.preventDefault();
            if(typeof(grecaptcha) != "undefined") {
                grecaptcha.ready(function() {
                    grecaptcha.execute('6Le4kagZAAAAAPrJvezXbADOrTQVxo69xZg1cyK6', {action: 'submit'}).then(function(token) {
                        $('#token').val(token); // here i set value to hidden field
                    });
                });
            }
            var f = $(this);
            var formData = new FormData(document.getElementById("fm_codigo"));
            formData.append("dato", "valor");
            $.ajax({
                url: "encuesta/valida_token_estudiante.php",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                statusCode: {
                    404: function () {
                        alertify.alert("Notificación","Pagina no Encontrada");

                    },
                    502: function () {
                        alertify.alert("Notificación","Ha ocurrido un error al conectarse con el servidor");

                    }
                },
                beforeSend: function(){
                    document.getElementById("btn_token").disabled =true;
                    document.getElementById("ingresar").innerHTML = 'INGRESAR';
                    document.getElementById("spinner").innerHTML = '<div class="sk-spinner sk-spinner-pulse"></div>';
                },
                success: function (response) {
                var fila_usuario = (JSON.parse(response));
                if(fila_usuario.Estado === "0"){
                    document.getElementById("btn_token").disabled =false;
                    document.getElementById("ingresar").innerHTML = 'INGRESAR';
                    document.getElementById("spinner").innerHTML = '';
                    alertify.error("<div class='text-white text-center'>Usuario Invalido</div>");
                }
                else if(fila_usuario.Estado === "1"){
                    var url_base = window.location;
                    
                    document.getElementById("btn_token").disabled =false;
                    document.getElementById("ingresar").innerHTML = 'INGRESAR';
                    document.getElementById("spinner").innerHTML = '';
                    window.location.replace(
                        url_base.protocol + "//" + 
                        url_base.host + "/" + 
                        "encuesta/respn_encues2.php"
                    );
                }
                else if(fila_usuario.Estado === "2"){
                    document.getElementById("btn_token").disabled =false;
                    document.getElementById("ingresar").innerHTML = 'INGRESAR';
                    document.getElementById("spinner").innerHTML = '';
                    alertify.error("<div class='text-white text-center'>Token Usado</div>");
                }
                else if(fila_usuario.Estado === "3"){
                    document.getElementById("btn_token").disabled =false;
                    document.getElementById("ingresar").innerHTML = 'INGRESAR';
                    document.getElementById("spinner").innerHTML = '';
                    alertify.error("<div class='text-white text-center'>ERROR DESCONOCIDO</div>");
                }
                else if(fila_usuario.Estado === "4"){
                    document.getElementById("btn_token").disabled =false;
                    document.getElementById("ingresar").innerHTML = 'INGRESAR';
                    document.getElementById("spinner").innerHTML = '';
                    alertify.error("<div class='text-white text-center'>Error, Captcha incorrecto</div>");
                }

                }

            });
        });
    })
}

//funcion que guarda los datos de la encuesta estudiante
            function contar(respuesta,posicion,name){   
                var final = document.getElementsByName(respuesta)[posicion].value+'Q00'+name;
                var nombre = 'Q00'+name;
                var respuesta = document.getElementsByName(respuesta)[posicion].value;           
                $.ajax({
                url: 'demo_encuesta.php',
                 data: {      
                  nombre:nombre,
                  respuesta:respuesta     
              },
            type: 'post',
            success:function(response){               
                
            }
            
            });
                
            }

            function no_atras(){
                window.location.hash="no-back-button";
window.location.hash="Again-No-back-button";//esta linea es necesaria para chrome
window.onhashchange=function(){window.location.hash="no-back-button";}
            }

            function elimina_documento(id_doc,nombre_doc){

                alertify.confirm('Confirmar', '¿Esta seguro de eliminar el documento?: <strong>'+nombre_doc+'</strong>', function(){ 
                    $.ajax({
                        url: 'material_delete.php',
                         data: {      
                            id_doc:id_doc,
                            nombre_doc:nombre_doc,    
                      },
                    type: 'POST',
                             
                    success:function(response){
                        alertify.notify('<div class="text-white text-center">Eliminado</div>', 'success', 3, function(){  location.reload();  });
                    }
                    
                    });
                    
                 }
                , function(){ 
                    alertify.error('<div class="text-center text-white">Cancelado</div>')
            });
                    
            }

            function llena_profesores(){
                $(document).ready(function(){
                    $("#id_profesor").change(function () {
                        $("#id_profesor option:selected").each(function () {
                            id_establecimiento = $(this).val();
                            tipo = "llena_cursos"
                            $.post("php/llena_select.php", { id_establecimiento: id_establecimiento, tipo: tipo  }, function(data){
                                $("#id_curso").html(data);
                            });            
                        });
                    })
                });
            }

           

            function carga_excel(){
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
                                url: "demo_insert.php",
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
                            console.log(response)
                            if(response.includes('Invalid datetime format')) {
                                alertify.set('notifier','position', 'center-top');
                                alertify.success("<div class='text-white text-center'>Las fechas de nacimiento pueden contener caracteres no válidos (Formato debe ser dd-mm-yyyy)</div>");

                                setTimeout(function(){ window.location.reload() }, 3000);
                                return;
                            }
                            var respuesta = (JSON.parse(response));

                            if(respuesta.estado === "1") {
                                $("#boton_subir_excel").attr('disabled', false);
                                $('#file').val('');
                                alertify.set('notifier','position', 'center-top');
                                alertify.success("<div class='text-white text-center'>Carga Existosa</div>");
                                document.getElementById("dowload").innerHTML = (respuesta.archivo);
                                $("#curso_establecimiento_selec").load("lista_curso_establecimientos.php");
                            }
                            else if(respuesta.estado === "0") {
                                $("#boton_subir_excel").attr('disabled', false);
                                $('#file').val('');
                                document.getElementById("dowload").innerHTML = ('');
                                alertify.set('notifier','position', 'center-top');
                                alertify.error("<div class='text-white text-center'>Error al realizar la carga</div>");
                                $("#curso_establecimiento_selec").load("lista_curso_establecimientos.php");
                            }
                            else if(respuesta.estado === "2"){
                                $("#boton_subir_excel").attr('disabled', false);
                                $('#file').val('');
                                document.getElementById("dowload").innerHTML = ('');
                                alertify.set('notifier','position', 'center-top');
                                alertify.error("<div class='text-white text-center'>La carga asociada al docente ya se ha realizado</div>");

                                $("#curso_establecimiento_selec").load("lista_curso_establecimientos.php");
                            }
                            else if(respuesta.estado === "3"){
                                $("#boton_subir_excel").attr('disabled', false);
                                $('#file').val('');
                                document.getElementById("dowload").innerHTML = ('');
                                alertify.set('notifier','position', 'center-top');
                                alertify.error("<div class='text-white text-center'>Ya se encuentran registros de estudiantes a registrar</div>");
                                $("#curso_establecimiento_selec").load("lista_curso_establecimientos.php");
                            }
                        },
                        onError: function(response) {
                           console.log('error: ', response);
                        },
                    })
                }
                                                   
                                
                        });
                    });
                })
            }

            function carga_establecimiento(){
                $(document).ready(function(){
                    $(function(){
                        $("#formulario_establecimiento").on("submit", function(e){
                            e.preventDefault();
                            var f = $(this);
                            var formData = new FormData(document.getElementById("formulario_establecimiento"));
                            formData.append("dato", "establecimiento");
                            //formData.append(f.attr("name"), $(this)[0].files[0]);
                            alertify.confirm('Notificación', '¿Los datos ingresados son correctos?', function(){ 
                                $.ajax({
                                    url: "php/gest_administrador.php",
                                    type: "post",
                                    dataType: "html",
                                    data: formData,
                                    cache: false,
                                    contentType: false,
                             processData: false,
                            
                             success: function(response){
                               var respuesta = (JSON.parse(response));
                            if(respuesta.estado === "1"){                                
                                    alertify.success("<div class='text-white text-center'>Establecimiento Guardado Exitosamente</div>");
                                    $('#estable_lista').load("php/lista_establecimientos.php");
                                    $("#myModal").modal('hide');//ocultamos el modal
                                    $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
                                    $('.modal-backdrop').remove();
    
                            }else if(respuesta.estado === "0"){
                                alertify.error("<div class='text-white text-center'>El establecimiento ya esta registrado</div>");
                                $('#estable_lista').load("php/lista_establecimientos.php");
                            }                         
                            
                              
                    
                             }
                                })

                             }
                , function(){
                });
                });
                });
                })
            }

            function ingreso_sostenedor(){
                $(document).ready(function(){
                    $(function(){
                        $("#formulario_sostenedor").on("submit", function(e){
                            e.preventDefault();
                            var f = $(this);
                            var formData = new FormData(document.getElementById("formulario_sostenedor"));
                            formData.append("dato", "sostenedor");
                           
                            $.ajax({
                                url: "php/gest_administrador.php",
                                type: "post",
                                dataType: "html",
                                data: formData,
                                cache: false,
                                contentType: false,
                         processData: false,
                         
                         success: function(response){

                            $("#tabla_soste").load("php/tabla_sostenedor.php");
                            $("#tabla_prof").load("php/tabla_docente.php");
                            $("#tabla_cur").load("php/tabla_curso.php");

                           var respuesta = (JSON.parse(response));
                           if(respuesta.estado === "1"){
                            alertify.success("<div class='text-white text-center'>Datos guardados exitosamente</>");
                            
                           
                           }
                           else if(respuesta.estado === "0"){
                          
                            alertify.success("<div class='text-white text-center'>Datos no guardados</>");    
                           
                           }
                           else if(respuesta.estado === "3"){
                            
                            alertify.error("<div class='text-white text-center'>Los datos que desea ingresar ya existen</>");
                           
                           }
                          
                
                         }
                            })
                                
                        });
                    });
                })
            }


            function actualiza_sostenedor(){
                $(document).ready(function(){
                    $('.actualiza_sostenedor').click(function () {
                    var id_sostenedor = $(this).parents("tr").find("td").eq(0).text();
                    var nombre_sostenedor = $(this).parents("tr").find("td").eq(1).text();
                    var apelli_sostenedor = $(this).parents("tr").find("td").eq(2).text();
                    var run_sostenedor = $(this).parents("tr").find("td").eq(3).text();

                    var id_usuario_sostenedor = $(this).parents("tr").find("td").eq(6).text();
                    var usuario_sostenedor = $(this).parents("tr").find("td").eq(7).text();
                    var pass_sostenedor = $(this).parents("tr").find("td").eq(8).text();

                    $('#id_soste_update').val(id_sostenedor); 
                    $('#nom_soste_update').val(nombre_sostenedor); 
                    $('#apelli_soste_update').val(apelli_sostenedor); 
                    $('#run_soste_update').val(run_sostenedor); 

                    $('#id_user_soste_update').val(id_usuario_sostenedor); 
                    $('#user_soste_update').val(usuario_sostenedor); 
                    $('#pass_soste_update').val(pass_sostenedor); 

                    $("#formulario_update_sostenedor").on("submit", function(e){
                        e.preventDefault();
                        var f = $(this);
                        var formData = new FormData(document.getElementById("formulario_update_sostenedor"));
                        formData.append("dato", "sostenedor_update");                       
                                            
                        $.ajax({
                            url: "php/gest_administrador.php",
                            type: "post",
                            dataType: "html",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                     
                     success: function(response){
                          $("#tabla_soste").load("php/tabla_sostenedor.php");
                            $("#tabla_prof").load("php/tabla_docente.php");
                            $("#tabla_cur").load("php/tabla_curso.php");
                       var respuesta = (JSON.parse(response));
                       
                       if(respuesta.estado === "1"){
                           alertify.success("<div class='text-white text-center'>Datos Actualizados</>");                      
                       
                       }
                       else if(respuesta.estado === "0"){                                              
                        alertify.success("<div class='text-white text-center'>Datos no actualizados</>");     
                       
                       }
                       else if(respuesta.estado === "2"){
                        
                          
                       
                       }
                      
            
                     }
                        })
                            
                    });
                  // console.log(id_sostenedor);
                })
            })

            }

            function ingreso_docente(){
                $(document).ready(function(){
                    $(function(){
                        $("#formulario_profesor").on("submit", function(e){
                            e.preventDefault();
                            var f = $(this);
                            var formData = new FormData(document.getElementById("formulario_profesor"));
                            formData.append("dato", "nuevo_docente");                           
                            $.ajax({
                                url: "php/gest_administrador.php",
                                type: "post",
                                dataType: "html",
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,                         
                             success: function(response){
                             console.log(response);
                        
                             $("#tabla_soste").load("php/tabla_sostenedor.php");
                             $("#tabla_prof").load("php/tabla_docente.php");
                             $("#tabla_cur").load("php/tabla_curso.php");                             
                             $("#modal_nuevo_curso").load("php/modal_nuevo_curso.php");
                             $("#modal_actualiza_curso").load("php/modal_update_curso.php");
                             
                           var respuesta = (JSON.parse(response));
                           if(respuesta.estado === "1"){

                            alertify.success("<div class='text-white text-center'>Datos guardados exitosamente</>"); 
                                                             
                               }

                           else if(respuesta.estado === "0"){
                          
                            alertify.error("<div class='text-white text-center'>Los datos que desea registrar ya existen</>");    
                           
                           }
                           else if(respuesta.estado === "2"){                           
                              
                           
                           }
                          
                
                         }
                            })
                                
                        });
                    });
                })
            }

            
            function diseno_tabla_sostenedor(){               
                $(document).ready( function () {
                    $('#tabla_sostenedor').DataTable({
                        "responsive": true,       
                            "searching": true,
                            "lengthChange": true,
                            "pagingType": "simple",
                            "bInfo" : false,
                            "ordering": true,
                             "language": {
                                "search": "<span class='text-white'>Buscar<span>",
                                "lengthMenu": "<span class='text-white'>Cantidad</span> _MENU_",
                              "paginate": {
                            "next": "Siguiente <i class='fa fa-arrow-right' aria-hidden='true'></i>",
                            "previous": "<i class='fa fa-arrow-left' aria-hidden='true'></i> Atras " 
                              }
                            }
                    });
               
            })
        }

        function diseno_tabla_docente(){               
            $(document).ready( function () {
                $('#tabla_docente').DataTable({
                    "responsive": true,       
                        "searching": true,
                        "lengthChange": true,
                        "pagingType": "simple",
                        "bInfo" : false,
                        "ordering": true,
                         "language": {
                            "search": "<span class='text-white'>Buscar<span>",
                            "lengthMenu": "<span class='text-white'>Cantidad</span> _MENU_",
                          "paginate": {
                        "next": "Siguiente <i class='fa fa-arrow-right' aria-hidden='true'></i>",
                        "previous": "<i class='fa fa-arrow-left' aria-hidden='true'></i> Atras " 
                          }
                        }
                });
           
        })
    }
    function diseno_tabla_curso(){               
        $(document).ready( function () {
            $('#tabla_curso').DataTable({
                "responsive": true,       
                    "searching": true,
                    "lengthChange": true,
                    "pagingType": "simple",
                    "bInfo" : false,
                    "ordering": true,
                     "language": {
                        "search": "<span class='text-white'>Buscar<span>",
                        "lengthMenu": "<span class='text-white'>Cantidad</span> _MENU_",
                      "paginate": {
                    "next": "Siguiente <i class='fa fa-arrow-right' aria-hidden='true'></i>",
                    "previous": "<i class='fa fa-arrow-left' aria-hidden='true'></i> Atras " 
                      }
                    }
            });
       
    })
}



    function actualiza_docente(){
        $(document).ready(function(){
            $('.actualiza_docente').click(function () {
                var id_docente = $(this).parents("tr").find("td").eq(0).text();
             
                var nombre_docente = $(this).parents("tr").find("td").eq(1).text();
                var apelli_docente = $(this).parents("tr").find("td").eq(2).text();
                var run_docente = $(this).parents("tr").find("td").eq(3).text();
                var mail_docente = $(this).parents("tr").find("td").eq(4).text();
                var id_user_docente = $(this).parents("tr").find("td").eq(7).text();
                var user_docente = $(this).parents("tr").find("td").eq(8).text();
                var pass_docente = $(this).parents("tr").find("td").eq(9).text();

               
                $('#id_docente_update').val(id_docente); 
                $('#nom_docente_update').val(nombre_docente); 
                $('#apelli_docente_update').val(apelli_docente); 
                $('#run_docente_update').val(run_docente); 
                $('#mail_docente_update').val(mail_docente);
                $('#id_docente_user_update').val(id_user_docente); 
                $('#user_docente_update').val(user_docente); 
                $('#pass_docente_update').val(pass_docente); 
        
                $("#formulario_update_docente").on("submit", function(e){
                    e.preventDefault();
                    var f = $(this);
                    var formData = new FormData(document.getElementById("formulario_update_docente"));
                    formData.append("dato", "docente_update");                       
                                        
                    $.ajax({
                        url: "php/gest_administrador.php",
                        type: "post",
                        dataType: "html",
                        data: formData,
                        cache: false,
                        contentType: false,
                 processData: false,
                 
                 success: function(response){
                    $("#tabla_soste").load("php/tabla_sostenedor.php");
                            $("#tabla_prof").load("php/tabla_docente.php");
                            $("#tabla_cur").load("php/tabla_curso.php");
                  
                   var respuesta = (JSON.parse(response));
                   if(respuesta.estado === "1"){
                       alertify.success("<div class='text-white text-center'>Datos Actualizados</>");                      
                   
                   }
                   else if(respuesta.estado === "0"){                                              
                    alertify.success("<div class='text-white text-center'>Datos no actualizados</>");     
                   
                   }
                   else if(respuesta.estado === "2"){
                    
                      
                   
                   }
                  
        
                 }
                    })
                        
                });
             
            })

        })
    }

    function ingreso_curso(){
        $(document).ready(function(){
            $(function(){
                $("#formulario_curso").on("submit", function(e){
                    e.preventDefault();
                    var f = $(this);
                    var formData = new FormData(document.getElementById("formulario_curso"));
                    formData.append("dato", "nuevo_curso");                           
                    $.ajax({
                        url: "php/gest_administrador.php",
                        type: "post",
                        dataType: "html",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                 
                 success: function(response){
                   
                  
                   $("#tabla_soste").load("php/tabla_sostenedor.php");
                            $("#tabla_prof").load("php/tabla_docente.php");
                            $("#tabla_cur").load("php/tabla_curso.php");                  
                      
                   var respuesta = (JSON.parse(response));
                   if(respuesta.estado === "1"){
                    alertify.success("<div class='text-white text-center'>Datos guardados exitosamente</>");     
                   
                   }
                   else if(respuesta.estado === "0"){
                  
                    alertify.error("<div class='text-white text-center'>Datos no guardados</>");    
                   
                   }
                   else if(respuesta.estado === "2"){
                    
                      
                   
                   }
                  
        
                 }
                    })
                        
                });
            });
        })
    }
    


    function actualiza_curso(){
        $(document).ready(function(){
            $('.actualiza_curso').click(function () {
                var id_curso = $(this).parents("tr").find("td").eq(0).text();
                var curso = $(this).parents("tr").find("td").eq(2).text();
                var apelli_docente = $(this).parents("tr").find("td").eq(3).text();
                var docente = $(this).parents("tr").find("td").eq(4).text();

                var anio = $(this).parents("tr").find("td").eq(1).text();

                $('#anios_curso').empty();

                $('#id_curso_update').val(id_curso); 

                $('#nombre_curso_update').val(curso);           
 
              $('#niveles_ce_update option:contains(' + apelli_docente + ')').prop({selected: true});
           
              $('#id_curso_docente_update option:contains(' + docente + ')').prop({selected: true});    
              
              for(let i = 0; i< 10; i++) {
                  $('#anios_curso').append('<option id="' + anio + '">' + anio + '</option>');
                  anio++;
              }

              $('#anios_curso').val($(this).parents("tr").find("td").eq(1).text());
            
            })

        })
    }

    function sesion(){
        var cierraSesionIrLogeo = "salir"; 
     //la sessión durara 30 minutos y luego se destruira

     //300000 1800000
      setTimeout(function(){ location.href=cierraSesionIrLogeo; }, 1800000); 
    }
    
    
    function encuesta_consolidado(){

        $(document).ready( function () {
         
var mytable = $('#tabla_preguntas').DataTable({
  "responsive": true,
  "pageLength" : 6,
  "pagingType": "simple", // "simple" option for 'Previous' and 'Next' buttons only
  "searching": false,
  "lengthChange": false,
  "bInfo" : false,
  "ordering": false,
   "language": {
  "paginate": {
  "next": "Siguiente",
  "previous": "Atrás"
}

}, drawCallback: function(){

$("#tabla_preguntas_previous").hide();
$('.paginate_button.next').on('click', function(){
	 var info = mytable.page.info();
	 console.log(info);            
	 
           if(info.page === 1) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress"> <div class="progress-bar active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%">10%  </div></div>';
          var formData = new FormData();
          formData.append("numero", 1);               
      	 $.ajax({    		 
               url: 'valida.php',
               type: "POST",
               dataType: "html",
                data: formData,
             cache: false,
             contentType: false,
             processData: false,     
           
           success: function(response){               
             
              var condicion = (JSON.parse(response));
                           if(condicion.estado === "0"){
                          	 $("#obligatorio").removeClass("invisible");
                          	 $("#obligatorio").addClass("visible");
                          	  alertify.error("existen preguntas sin responder");
                                $(".paginate_button.previous").click(); 
                           } else if(condicion.estado === "1"){                                	                       	                 	
                          	 $("#obligatorio").addClass("invisible");
                           }
                
              
              
           }
           
           });     	  
                          
         } 
         else if(info.page === 2) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100" style="width:28%">28%</div></div>';
          var formData = new FormData();
          formData.append("numero", 2); 
          $.ajax({              		 
              url: 'valida.php',
              type: "post",
              dataType: "html",
              data: formData,                   
            cache: false,
            contentType: false,
            processData: false,               
          
          success: function(response){               
             console.log(response);
             var condicion = (JSON.parse(response));
                          if(condicion.estado === "0"){
                         	 $("#obligatorio").removeClass("invisible");
                         	 $("#obligatorio").addClass("visible");
                         	  alertify.error("existen preguntas sin responder");
                               $(".paginate_button.previous").click(); 
                          } else if(condicion.estado === "1"){
                                                      	
                         	 $("#obligatorio").addClass("invisible");
                          }
               
             
             
          }
          
          });            
             	
               	                  
         } 

          else if(info.page === 3) {
              $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100" style="width:42%">42%</div></div>';
          var formData = new FormData();
          formData.append("numero", 3); 
          $.ajax({              		 
              url: 'valida.php',
              type: "post",
              dataType: "html",
              data: formData,                   
            cache: false,
            contentType: false,
            processData: false,               
          
          success: function(response){               
             console.log(response);
             var condicion = (JSON.parse(response));
                          if(condicion.estado === "0"){
                         	 $("#obligatorio").removeClass("invisible");
                         	 $("#obligatorio").addClass("visible");
                         	  alertify.error("existen preguntas sin responder");
                               $(".paginate_button.previous").click(); 
                          } else if(condicion.estado === "1"){
                                                      	
                         	 $("#obligatorio").addClass("invisible");
                          }
               
             
             
          }
          
          });       
        	 
         }
         else if(info.page === 4) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width:57%">57%</div></div>';
          var formData = new FormData();
          formData.append("numero", 4); 
          $.ajax({              		 
              url: 'valida.php',
              type: "post",
              dataType: "html",
              data: formData,                   
            cache: false,
            contentType: false,
            processData: false,               
          
          success: function(response){               
             console.log(response);
             var condicion = (JSON.parse(response));
                          if(condicion.estado === "0"){
                         	 $("#obligatorio").removeClass("invisible");
                         	 $("#obligatorio").addClass("visible");
                         	  alertify.error("existen preguntas sin responder");
                               $(".paginate_button.previous").click(); 
                          } else if(condicion.estado === "1"){
                                                      	
                         	 $("#obligatorio").addClass("invisible");
                          }
               
             
             
          }
          
          });       
        	 
            
         } 
         else if(info.page === 5) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100" style="width:71%">71%</div></div>';
          var formData = new FormData();
          formData.append("numero", 5); 
          $.ajax({              		 
              url: 'valida.php',
              type: "post",
              dataType: "html",
              data: formData,                   
            cache: false,
            contentType: false,
            processData: false,               
          
          success: function(response){               
             console.log(response);
             var condicion = (JSON.parse(response));
                          if(condicion.estado === "0"){
                         	 $("#obligatorio").removeClass("invisible");
                         	 $("#obligatorio").addClass("visible");
                         	  alertify.error("existen preguntas sin responder");
                               $(".paginate_button.previous").click(); 
                          } else if(condicion.estado === "1"){
                                                      	
                         	 $("#obligatorio").addClass("invisible");
                          }
               
             
             
          }
          
          });       
        	
            
         } 
         else if(info.page === 6) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width:85%">85%</div></div>';
          var formData = new FormData();
          formData.append("numero", 6); 
          $.ajax({              		 
              url: 'valida.php',
              type: "post",
              dataType: "html",
              data: formData,                   
            cache: false,
            contentType: false,
            processData: false,               
          
          success: function(response){               
             console.log(response);
             var condicion = (JSON.parse(response));
                          if(condicion.estado === "0"){
                         	 $("#obligatorio").removeClass("invisible");
                         	 $("#obligatorio").addClass("visible");
                         	  alertify.error("existen preguntas sin responder");
                               $(".paginate_button.previous").click(); 
                          } else if(condicion.estado === "1"){
                                                      	
                         	 $("#obligatorio").addClass("invisible");
                          }
               
             
             
          }
          
          });    
                          }
         else if(info.page === 7) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">100%</div></div>';
         // $('.paginate_button.next').hide();
          var btn_guardar = document.getElementById("tabla_preguntas_next")
          btn_guardar.innerHTML = "";
          btn_guardar.innerHTML = "<p class='text-white'>Guardar</p>";
          var formData = new FormData();
          formData.append("numero", 7); 
          $.ajax({              		 
              url: 'valida.php',
              type: "post",
              dataType: "html",
              data: formData,                   
            cache: false,
            contentType: false,
            processData: false,               
          
          success: function(response){               
             console.log(response);
             var condicion = (JSON.parse(response));
                          if(condicion.estado === "0"){
                         	 $("#obligatorio").removeClass("invisible");
                         	 $("#obligatorio").addClass("visible");
                         	  alertify.error("existen preguntas sin responder");
                               $(".paginate_button.previous").click(); 
                          } else if(condicion.estado === "1"){
                                                      	
                         	 $("#obligatorio").addClass("invisible");
                          }
               
             
             
          }
          
          });    
          $( "#tabla_preguntas_next" ).click(function() {
        	  var formData = new FormData();
              formData.append("numero", 8); 
              
              $.ajax({              		 
                  url: 'valida.php',
                  type: "post",
                  dataType: "html",
                  data: formData,                   
                   cache: false,
                   contentType: false,
                   processData: false,           
                success: function(response){               
                 console.log(response);
                 var condicion = (JSON.parse(response));
                              if(condicion.estado === "0"){
                             	 $("#obligatorio").removeClass("invisible");
                             	 $("#obligatorio").addClass("visible");
                             	  alertify.error("<div class='text-white text-center'>existen preguntas sin responder</div>");
                                 
                              } else if(condicion.estado === "1"){                                          
                                 
                                  if(condicion.guarda === "guardado"){                   
                                      window.location.href='final.php';
                                  }                                        
                                                                       	
                              }
                              else if(condicion.estado === "0"){
                            	  if(condicion.guarda === "no_guardado"){     
                                           
                            		  alertify.error("<div class='text-white text-center'>Encuesta No Guardada</div>");
                                  }   
                                 
                              }
                   
                 
                 
              }
              
              });
        });
        
         
         }
                     

       }); 
       $('.paginate_button.previous', this.api().table().container())          
       .on('click', function(){
       var info = mytable.page.info();            
       if(info.page === 0) {              
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"> 0%</div></div>';
         } 
         if(info.page === 1) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%"> 10%</div></div>';
         } 
         else if(info.page === 2) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100" style="width:28%">45%</div></div>';
         } 

          else if(info.page === 3) {
              $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100" style="width:42%">42%</div></div>';
         }
         else if(info.page === 4) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width:57%">57% </div></div>';
         } 
         else if(info.page === 5) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="71" aria-valuemin="0" aria-valuemax="100" style="width:71%">71% </div></div>';
         } 
         else if(info.page === 6) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width:85%">85%</div></div>';
         } 
         else if(info.page === 7) {
          $(".paginate_button.previous").show(); 
          document.getElementById('progress-wrapper').innerHTML = '<div class="progress">  <div class="progress-bar active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">100% </div></div>';
         }               

       });       
}
  
});

} );
    }



    function redireccionar(tipo){

        if(tipo === 2){
            const reportes = document.getElementById("reporte_colegio");
        const autogestion = document.getElementById("autogestion_estable");
        const administracion = document.getElementById("admin_establecimiento");
       

        reportes.onclick = function(event){
            window.location.href = "reportes/colegio_index.php"
        }
        autogestion.onclick = function(event){
            window.location.href = "./index.php"
        }
        administracion.onclick = function(event){
            window.location.href = "./index_colaboradores.php"
        }
        
        }else if(tipo === 4){
            const lista_estable = document.getElementById("lista_establecimientos");
            const edit_pregun = document.getElementById("editar_preguntas");
                    lista_estable.onclick = function(event){
                window.location.href = "./index_admin.php"
            }
            edit_pregun.onclick = function(event){
                window.location.href = "./gestion_encuesta.php"
            }
        }
        
    }


	