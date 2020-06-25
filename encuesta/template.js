                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                /*
 * LimeSurvey
 * Copyright (C) 2007 The LimeSurvey Project Team / Carsten Schmitz
 * All rights reserved.
 * License: GNU/GPL License v2 or later, see LICENSE.php
 * LimeSurvey is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * 
 * Description: Javascript file for templates. Put JS-functions for your template here.
 *  
 * 
 * $Id:$
 */

var queryString = new Array();
$(function() {
    if (queryString.length == 0) {
        if (window.location.search.split('?').length > 1) {
            var params = window.location.search.split('?')[1].split('&');
            for (var i = 0; i < params.length; i++) {
                var key = params[i].split('=')[0];  
                var value = decodeURIComponent(params[i].split('=')[1]);
                queryString[key] = value;
            }
        }
    }
});


function validarCheck(obj, boton){
    //alert(boton);
    if(obj.checked===true){
        boton.disabled = "";
        boton.classList.remove("ui-state-disabled");
        boton.classList.remove("ui-button-disabled");
    }else{
		boton.disabled= "disabled";
        boton.classList.add("ui-state-disabled");
        boton.classList.add("ui-button-disabled");
	}
}

function check (obj, boton){
    var id = obj.id;
    if (obj.is(":checked")) {
         $('#' + id).prop('checked', false);
    } else {
         $('#' + id).prop('checked', true);
    }
    validarCheck(obj, boton);
}

/*
 * The function focusFirst puts the Focus on the first non-hidden element in the Survey. 
 * 
 * Normally this is the first input field (the first answer).
 */
function focusFirst(Event)
{
	
	$('#limesurvey :input:visible:enabled:first').focus();

}
/*
 * The focusFirst function is added to the eventlistener, when the page is loaded.
 * 
 * This can be used to start other functions on pageload as well. Just put it inside the 'ready' function block
 */

/* Uncomment below if you want to use the focusFirst function */
/*
$(document).ready(function(){
	focusFirst();
});
*/



function correctPNG() // correctly handle PNG transparency in Win IE 5.5 & 6.
{
   var arVersion = navigator.appVersion.split("MSIE")
   var version = parseFloat(arVersion[1])
   if ((version >= 5.5) && (version<7) && (document.body.filters)) 
   {
      for(var i=0; i<document.images.length; i++)
      {
         var img = document.images[i]
         var imgName = img.src.toUpperCase()
         if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
         {
            var imgID = (img.id) ? "id='" + img.id + "' " : "";
            var imgClass = (img.className) ? "class='" + img.className + "' " : "";
            var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' ";
            var imgStyle = "display:inline-block;" + img.style.cssText;
            if (img.align == "left") imgStyle = "float:left;" + imgStyle;
            if (img.align == "right") imgStyle = "float:right;" + imgStyle;
            if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle;
            var strNewHTML = "<span " + imgID + imgClass + imgTitle
            + " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
            + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
            + "(src='" + img.src + "', sizingMethod='scale');\"></span>" 
            img.outerHTML = strNewHTML
            i = i-1
         }
      }
   }    
}

function text2textarea( elementId, largo, row, col) {  
    var element = document.getElementById( "answer" + elementId + "othertext");  
        var parent = element.parentNode;  
        parent.removeChild( element );  
        if ( element.type == 'text' ) {  
            parent.innerHTML = '<textarea title="' + element.title + '" id="' + element.id + '" cols=' + col + ' rows=' + row + ' maxlength="' + largo + '" class="textarea empty" onkeyup="checkconditions(this.value, this.name, this.type)" alt="Opción" name="' + element.name + '" onkeyup="' + "if($.trim($(this).val())!=''){ $('#SOTH" + elementId +  ').click(); };">' + element.value + '</textarea>';
        }  
        element = null;     
}



var spanParlante; // almacena la instancia de sapn que se esta reproduciendo

$(document).ready(function(){      
    //Genera un span que muestra un parlante en cada item de un ascala liker

    //si es la pagina de bienvenida
    var $myDiv = $('#welcome');
    if ( $myDiv.length){
        $(".progress").css("display","none");

    }
    //si es la pagina de despedida
    var $myDiv = $('#completed');
    if ( $myDiv.length){
        $(".progress").css("display","none");

    }
    

    if($('#sid').val() == '172626') { //sid 172626 = encuesta apoderado
        var codItem = 10;
        $(".answertext").each(function(index, obj) {   
            //obteniendo codigo de grupo
            var idInput = $(obj).find("input").attr("id");            
            var arrayIdInput = idInput.split("X");                    
            var codGrupo =  arrayIdInput[1];
            console.log(codGrupo);
            var carpeta = 1;
            if(codGrupo==9) {
               carpeta=1;
            }else if(codGrupo==10){
               carpeta+=1;
            }else if(codGrupo==17){
               carpeta+=2;
            }else if(codGrupo==11){
               carpeta+=3;
            }else if(codGrupo==12){
               carpeta+=4;
            }else if(codGrupo==18){
               carpeta+=5;
            }
            var codFinal="apoderado/"+carpeta+"/APO_"+codGrupo+"_ITEM_"+codItem;
            
            var str = $(obj).html();
            var res = str.replace('<input', '<span id="parlante-' + index + '" class="audio-item reproducir " data-audio="'+codFinal+'"></span><input');
            $(obj).html(res);
             codItem += 10;
            console.log(codItem);
        });
    }

    if($('#sid').val() == '172626') { //sid 172626 = encuesta apoderado
        var head_group_id = ["group-0"];
        var survey_color_id = [['group-0','survey-1'],['group-1','survey-1'],['group-2','survey-1'],['group-3','survey-1'],['group-4','survey-1'],['group-5','survey-1']];


        //$("audio").attr("test", this);
        //AUDIO TAG
        //accion al finalizar repro
        var aud = $("audio").get(0);
        aud.onended = function(){
            /*$(spanParlante).removeClass("pausar");
            $(spanParlante).addClass("reproducir");*/            
            restablecerParlantes(spanParlante);
        };
        //

    }

    if($('#sid').val() == '583538') { //sid 583538 = encuesta alumno
        var head_group_id = ["group-0"];
        var survey_color_id = [['group-0','survey-2'],['group-1','survey-2'],['group-2','survey-2'],['group-3','survey-2'],['group-4','survey-2'],['group-5','survey-2'],['group-6','survey-2'],['group-7','survey-2'],['group-8','survey-2'],['group-9','survey-2'],['group-10','survey-2'],['group-11','survey-2']];
    }
    if ( $.inArray( $('div[id^="group-"]').attr('id'), head_group_id ) > -1 ) {
        if ($('div[id^="group-"]').attr('id') == "group-0") $('.navigator').html('<p class="navigator"><button class="submit button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-secondary" accesskey="n" name="movenext" value="movenext" id="movenextbtn" type="submit" role="button" aria-disabled="false"><span class="ui-button-text">Siguiente</span><span class="ui-button-icon-secondary ui-icon ui-icon-triangle-1-e"></span></button></p>');
        $('.groupdescription').addClass('expand');
        $('.groupdescription').find('.fa').removeClass('fa-plus-square').addClass('fa-minus-square'); 
        $('.groupdescription .text').css({"height": "100%", "display": "block"});
    }

    //Verificar si el acceso viene desde taller
    if (queryString['from']) {        
        localStorage.setItem("fromtaller", "TRUE"); 
        localStorage.setItem("token", queryString['T']); 
        localStorage.setItem("evaluacion", queryString['E']); 
        localStorage.setItem("param4", queryString['K']);
    }

    if (localStorage.getItem("fromtaller") == "TRUE" ){
        document.getElementById('back-taller').style.display = "block";
    }

    $('.groupdescription .head-group').on('click', function() {
        if ($('.groupdescription').hasClass('expand')) {
            $('.groupdescription').removeClass('expand');
            $('.groupdescription').find('.fa').removeClass('fa-minus-square').addClass('fa-plus-square'); 
            
            $('.groupdescription .text').animate( { 'height': '0' } , 250 , function () {
                $(this).hide();
        	} );
        } else {
            $('.groupdescription').addClass('expand');
            $('.groupdescription .text').css({"display": "block"});
            $('.groupdescription .text').animate( { 'height': '100%' } , 250 );
            
             $('.groupdescription').find('.fa').removeClass('fa-plus-square').addClass('fa-minus-square'); 
        }
    });
    
    $.each(survey_color_id, function( index, item ) {
        if ( item[0] == $('div[id^="group-"]').attr('id') ) {
            $('body').addClass(item[1]);
        };
    });

    $('#progress-pre').html("Avance");
    //insertar asterisco
    var errorMandatoryMsg = $(".errormandatory").html();
    $(".errormandatory").first().html("* "+errorMandatoryMsg);
    
    $(".clearall").hide();
    $("#saveallbtn").hide();    
       
    var theWidth = $("table").eq(0).find("tr > td:first").width();
    $("table:gt(0)").find("tr > td:first").width(theWidth + "px");
    

  //called when key is pressed in textbox
  $(".solo-numeros").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
   
   $(".solo-letras").keypress(function (e) {
     //Acá va el valor de las letras en ascii
     if (e.which != 8 && e.which != 32 && (e.which < 65 || e.which > 122)) {
        return false;
    }
   });
   
    /* Icono mostrar ocultar descripcion de grupo */
    $( "#descripcion-grupo" ).click(function() {
        if ($( "#descripcion-grupo" ).hasClass("mostrar-descripcion")) {
            $( ".group-description" ).fadeIn(500);
            $( "#descripcion-grupo" ).removeClass( "mostrar-descripcion" );
            $( "#descripcion-grupo" ).addClass( "ocultar-descripcion" );
        } else {
            $( ".group-description" ).fadeOut(500);
            $( "#descripcion-grupo" ).removeClass( "ocultar-descripcion" );
            $( "#descripcion-grupo" ).addClass( "mostrar-descripcion" );
        }
    });
        $(".perfil-encuestado").append(function(){
        var parametros = {
                    "ajax" : true,
                    "funcion" : "identificador"
                    };
        $.ajax({
                url: '/ajax/session.php',
                type: 'post',
                data: parametros,
                datatype: 'json',
                success: function (data) {                    
                    var obj = jQuery.parseJSON(data);
                    if(obj.status == "success"){          
                        //salida = "<strong class=\"nombre-usuario\">"+obj.nombre+"</strong></span class=\"nombre-colegio\">"+obj.colegio+"</span>";
                        $(".nombre-encuestado").html(obj.nombre);
                        $(".colegio-encuestado").html(obj.colegio);
                        
                        //poner barra de progreso, lo puse acá por motivos que se carga al final
                        $("#progress-post").html($("#progressbar").attr("aria-valuenow")+"%");
                        
                    }
                }
            });
        });
        
        $(".btn-limon-validar").click(function(e){
          var code = $(".btn-limon-validar").parent("form").find('input').val();
          var parametros = {
              "ajax" : true,
              "funcion" : "validador",
              "codigo" : code
            };

          $.ajax({
            async: false,
            url: '/ajax/session.php',
            type: 'post',
            data: parametros,
            datatype: 'json',
            success: function (data) {                                    
                if(data.status == "error"){
                  
                    e.preventDefault();
                  alertify.alert("El código ingresado no es válido.");
                }else{
                  $("form").submit();
                }
            }
          });
        });         
    
    $('#txt-codigo').keypress(function(e){
        if(e.keyCode==13) {
            $('.btn-limon-validar').click();
            return false;
        }
    });
    
    /*$("#progressbar").children(".ui-progressbar-value").append(function(){
        $("#progress-post").html($("#progressbar").attr("aria-valuenow")+"%");
    });*/
    
    
    $('.audio-item').on('click', function(){        
        if($(this).hasClass("reproducir")){   
            var audio_file = $(this).attr("data-audio");

            var url = "audio/opciones-preguntas.mp3";
            $(".audio-item, .audio-parrafo").each(function(index, obj) {    
                restablecerParlantes(obj);
            });
            $(this).removeClass('reproducir').addClass('pausar');
            $("audio").attr("src", url).get(0).play();
            //set span parlante en una nueva propiedad del obj audio
            spanParlante = this;
        } else {
            $(this).removeClass('pausar').addClass('reproducir');
            $("audio").get(0).pause();
        }        

        /*var ancestorThElement = $(this).closest('th');
        var text = encodeURIComponent($(ancestorThElement).text());

        var url = "https://translate.google.com/translate_tts?tl=es&q=" + text + "&ie=UTF-8&client=t";
        console.log(url);
        $("audio").attr("src", url).get(0).play();*/
    });
    
    
    $('.audio-parrafo').on('click', function(){ 
    console.log("audio-parrafo");
        if($(this).hasClass("reproducir-parrafo")){            
            var audio_file = $(this).attr("data-audio");
            var url = "http://compromisoescolar.cl/upload/templates/pace_aplicacion_talleres/audio/" + audio_file + ".mp3";
            if (audio_file) {
                $(".audio-parrafo, .audio-item").each(function(index, obj) {
                    restablecerParlantes(obj);
                });            
                $(this).removeClass('reproducir-parrafo').addClass('pausar-parrafo');
                $("audio").attr("src", url).get(0).play();
                spanParlante = this;
                console.log(spanParlante);
            }
        } else {
            $(this).removeClass('pausar-parrafo').addClass('reproducir-parrafo');
            $("audio").get(0).pause();
        }
    });
    
});

function restablecerParlantes(obj){
    console.log("restablecerParlantes");
    if($(obj).hasClass("audio-parrafo")) {        
        $(obj).removeClass('pausar-parrafo').addClass('reproducir-parrafo');
    } else {
        $(obj).removeClass('pausar').addClass('reproducir');
    }
}

function saveAgree(radAcepto,nombres,apellidos){
    var agree = "SI";
    if(!radAcepto) {
        agree = "NO";
    }
    
    /*$.post( "/ajax/consentimiento.php", {agree: agree,nombres: nombres, apellidos:apellidos})
        .done(function( data ) {
            return data;
    });*/

    $.ajax({
        type: "POST",
        url: '/ajax/consentimiento.php',
        dataType:'json',
        async: false,
        data: {agree: agree,nombres: nombres, apellidos:apellidos},
        success: function (data) {                    
            pasarAEncuesta(data);
        }
    });
}

function expandirDescripcionGrupo (){
        $('.groupdescription').addClass('expand');
        $('.groupdescription').find('.fa').removeClass('fa-plus-square').addClass('fa-minus-square'); 
        $('.groupdescription .text').css({"height": "100%", "display": "block"});
}

function closeWindow() {
    window.open('','_parent','');
    window.close();
}



function mostrarLoading (texto){
    if (!texto) texto = "Generando PDF";
    jQuery("#loading-text").html(texto);
    jQuery(".loading").show();
}


function ocultarLoading (){
    jQuery(".loading").hide();
}

$(window).scroll(function () { 
       
    if ($(window).scrollTop()   >  1 ) {
        $('.progress').fadeIn();
        $('.progress').addClass("caja-flotante");            
    }else {                     
        $('.progress').fadeIn();
        $('.progress').removeClass("caja-flotante");

}
});

