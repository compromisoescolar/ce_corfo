<?php

require 'conf/conf_requiere.php';
session_start();
echo "<div class='row'>";
selec_cursos_admin_establecimiento_admin($_SESSION['user']);

echo "</div>";
?>