<?php
require'../conf/conexion_db.php';
require'../conf/funciones_db.php';
require'../assets/librerias/simplexlsx.class.php';
ini_set('max_execution_time', 800);
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <?php include"../assets/css/css.php"; ?>
        <link href="../assets/librerias/datatable/datatables.css" type="text/css"/>      


    </head>
    <body
        <div class="container">

            <div class="row">
                <?php
                echo tabla_establecimientos();
                ?>
            </div>
        </div> 

        <script src="https://code.jquery.com/jquery-3.3.1.js"
                integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>

        <script src="../assets/librerias/datatable/datatables.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#tabla_establecimiento').DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                    },

                    dom: 'Bfrtip',
                    buttons: {
                        buttons: [
                            {extend: 'pdf', className: 'btn btn-primary mt-4 mr-4 float-right'},
                            {extend: 'excel', className: 'btn btn-primary mt-4 float-right'}
                        ]
                    }
                });
            });
        </script>
        <?php include"../assets/js/js.php"; ?>
    </body>
</html>





