<?php
session_start();
session_destroy();
header("Location: gestionar_archivos.php");
exit();
