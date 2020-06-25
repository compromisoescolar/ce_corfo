<?php

require 'conf/conf_requiere.php';
session_start();
echo "<div class='row'>";
selec_cursos_admin_establecimiento($_SESSION['user']);

echo "</div>";
?>