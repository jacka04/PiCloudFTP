<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pi Cloud - Selecció de servei</title>
</head>
<body style="margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; background: linear-gradient(135deg, #800020, #330000); color: white; height: 100vh;">

<nav style="display: flex; justify-content: space-between; background-color: #800020; padding: 15px 30px; align-items: center;">
    <div>
        <a href="index.php" style="color: white; text-decoration: none; font-weight: bold; margin-right: 15px;">Inici</a>
        <a href="quiensomos.php" style="color: white; text-decoration: none; font-weight: bold;">Què fem?</a>
    </div>
    <div>
        <a href="logout.php" style="color: white; text-decoration: none; font-weight: bold;">Tancar Sessió</a>
    </div>
</nav>


<div style="display: flex; justify-content: center; align-items: center; height: calc(100vh - 60px);">
    <div style="text-align: center; padding: 40px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; width: 100%; max-width: 500px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);">
        <h1 style="margin-bottom: 20px;">Benvingut, <?php echo htmlspecialchars($_SESSION["username"]); ?><br> Tria un servei per començar!</h1>
        <p style="margin-bottom: 30px;">Accedeix al teu espai personal i puja arxius directament als teus serveis al núvol preferits.</p>
     
<div style="margin-bottom: 30px;">
    <form action="connectar_tots.php" method="get" style="margin: 0; display: flex; justify-content: center;">
        <button type="submit" title="Pujar a tots els núvols"
            style="width: 260px; height: 60px; background-color: #28a745; border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(40,167,69,0.6); color: white; font-weight: bold; font-size: 18px; cursor: pointer; transition: background-color 0.3s ease;">
            🌐 Pujar simultàniament a tots els núvols
        </button>
    </form>
</div>


        <div style="display: flex; justify-content: center; gap: 30px;">


<div style="text-align: center;">
    <form action="gestionar_archivos.php" method="get" style="margin: 0;">
        <button type="submit" title="Google Drive"
            style="width: 80px; height: 80px; background-color: white; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); display: flex; justify-content: center; align-items: center; cursor: pointer;">
            <img src="img/logodrive.png" alt="Google Drive" style="max-width: 40px; max-height: 40px; object-fit: contain;">
        </button>
    </form>
    <p style="margin-top: 8px; font-size: 14px;">Drive</p>
</div>


<div style="text-align: center;">
    <form action="dropbox_auth.php" method="get" style="margin: 0;">
        <button type="submit" title="Dropbox"
            style="width: 80px; height: 80px; background-color: white; border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); display: flex; justify-content: center; align-items: center; cursor: pointer;">
            <img src="img/dropboxl.png" alt="Dropbox" style="max-width: 40px; max-height: 40px; object-fit: contain;">
        </button>
    </form>
    <p style="margin-top: 8px; font-size: 14px;">Dropbox</p>
</div>


<div style="text-align: center;">
    <form action="onedrive_auth.php" method="get" style="margin: 0;">
        <button type="submit" title="OneDrive"
            style="width: 80px; height: 80px; background-color: white; border: 1px solid rgba(255,255,255,0.2); border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); display: flex; justify-content: center; align-items: center; cursor: pointer;">
            <img src="img/onedrive.png" alt="OneDrive" style="max-width: 40px; max-height: 40px; object-fit: contain;">
        </button>
    </form>
    <p style="margin-top: 8px; font-size: 14px;">OneDrive</p>
</div>



</div>

</div>

</body>
</html>
