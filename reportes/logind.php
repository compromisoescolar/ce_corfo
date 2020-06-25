<?php require_once "dist/conf/require_conf.php";?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Compromiso Escolar Login</title>
  <?php require "dist/css/css.php"; ?>

</head>
<body class="hold-transition login-page">
  <div style="margin-top:10px;margin-left:10px; color:white;">
  <img src="dist/img/logo.png" alt="" srcset=""> <span>PANEL DE REPORTES COMPROMISO ESCOLAR</span>
  </div>
<div class="login-box">
  <div class="login-logo">
    <span style="color:white;">Panel de Reportes<br><b>Compromiso </b>Escolar</span>
      </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"></p>
    <form>
    <div class="form-group has-feedback">
        <?php echo tipo_usuario(); ?>
      </div>
      <div class="form-group has-feedback">
        <input type="text" name="usuario" id="usuario" class="form-control" placeholder="usuario" required />
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      
      <div class="form-group has-feedback">
        <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="contraseña" required />
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-12">
        <button type="button" class="btn btn-primary color_boton pull-right" name="login-button" id="ingresar"><span id="inicia">Ingresar</span>
                  <div id="spinner"></div>
        </button>
        <!-- <input type="button" id="ingresar" class="btn btn-primary color_boton pull-right" value="Ingresar">--><!--  <i class="fa fa-spinner fa-2x fa-spin  fa-fw"></i>-->
        </div>
      

      </div>
    </form>
<div class="text-center"><a href="../index2.php">Volver</a></div>
  </div>
  <!-- /.login-box-body -->
</div>
<?php require "dist/js/js.php"; ?>
<script>
 login_final();
  
    </script>
</body>
</html>
