<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Connectar a tots els serveis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #800020, #330000);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        a.auth-button {
            display: inline-block;
            background-color: #ff4d4d;
            color: white;
            padding: 12px 20px;
            margin: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .status {
            margin-top: 20px;
        }

        .status span {
            font-weight: bold;
            color: #90ee90;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Connecta't als serveis al núvol</h1>
    <p>Per pujar arxius a tots els serveis, primer has d’iniciar sessió a cada un d’ells.</p>

    <?php if (!isset($_SESSION['google_access_token'])): ?>
        <a href="gestionar_archivos.php" class="auth-button">Connectar amb Google Drive</a>
    <?php else: ?>
        <div class="status">✅ Connectat a <span>Google Drive</span></div>
    <?php endif; ?>

    <?php if (!isset($_SESSION['dropbox_access_token'])): ?>
        <a href="dropbox_auth.php" class="auth-button">Connectar amb Dropbox</a>
    <?php else: ?>
        <div class="status">✅ Connectat a <span>Dropbox</span></div>
    <?php endif; ?>

    <?php if (!isset($_SESSION['onedrive_access_token'])): ?>
        <a href="onedrive_auth.php?multi=1" class="auth-button">Connectar amb OneDrive</a>
        <?php else: ?>
        <div class="status">✅ Connectat a <span>OneDrive</span></div>
    <?php endif; ?>

    <?php if (
        isset($_SESSION['google_access_token']) &&
        isset($_SESSION['dropbox_access_token']) &&
        isset($_SESSION['onedrive_access_token'])
    ): ?>
<a href="formulari_pujada_global.php" class="auth-button" style="background-color: #4CAF50;">Tots connectats! Anar a pujada</a>
<?php endif; ?>
</div>

</body>
</html>
