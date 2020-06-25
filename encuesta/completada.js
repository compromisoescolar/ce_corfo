    $("#enviarDiplomaEmail").click(function(){      

        mostrarLoading("Enviando diploma...");
        var email = $("#txt_email_enviar1").val() + "@" + $("#txt_email_enviar2").val();
        var rutaDiploma = $(this).data("ruta");
        var data = { param1: email, param2: rutaDiploma };
        $.ajax({
            url: '/diplomas/enviarDiplomaEmail.php',
            type: 'post',
            dataType: 'json',
            data: data,
            async:false,
            success: function (data) {
                ocultarLoading();
                if(data.error.message) {
                     // confirm dialog
                     alertify.error(data.error.message);
                } else {
                    alertify.alert(data.message);
                }
            }
        });
    });