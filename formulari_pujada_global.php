<?php
session_start();
if (!isset($_SESSION['google_access_token']) || !isset($_SESSION['dropbox_access_token']) || !isset($_SESSION['onedrive_access_token'])) {
  header("Location: connectar_tots.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
  <meta charset="UTF-8">
  <title>Pujar fitxer a tots els serveis</title>
  <style>
    body {
      background: linear-gradient(135deg, #800020, #330000);
      color: white;
      font-family: Arial, sans-serif;
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
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }
    input[type="file"] {
      margin-bottom: 20px;
    }
    .upload-button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      font-weight: bold;
    }
    .upload-button:hover {
      background-color: #388e3c;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>Pujada global d’arxius</h1>
  <p>Selecciona un fitxer per pujar-lo a Google Drive, Dropbox i OneDrive alhora:</p>
  <form action="pujada_global.php" method="post" enctype="multipart/form-data">
    <input type="file" name="archivo" required>
    <br>
    <button type="submit" class="upload-button">Pujar a tots els serveis</button>
  </form>
</div>
</body>
</html>
