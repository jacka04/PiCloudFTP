<?php
session_start();
require_once "conexiondb.php"; 
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servidor FTP</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-left">
        <a class="active" href="index.php">Inici</a>
        <a href="quiensomos.php">Què fem?</a>
    </div>
    <div class="navbar-right">
        <?php if (isset($_SESSION['username'])): ?>
            <a href="logout.php">Tancar sessió</a>
        <?php else: ?>
            <a href="login.php">Iniciar sessió</a>
            <a href="registro.php">Registrar-se</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">
    <div class="left-panel">
        <h1>PI Cloud</h1>
        <img style="width:150px" src="img/fotologop.png" alt="Logo">
        <p>Benvingut al servidor Pi FTP. Aquí podràs gestionar els teus fitxers i connectar-te a serveis en el núvol amb Google Drive.</p>
        <?php if (!isset($_SESSION['username'])): ?>
            <a href="login.php">Iniciar sessió</a>
            <a href="registro.php">No tens compte? Crea'n una ara!</a>
        <?php else: ?>
            <a href="pagina_ftp.php">Anar al meu espai personal</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
