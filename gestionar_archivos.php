<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

$client = new Google\Client();
$client->setAuthConfig('credentials.json');
$client->setRedirectUri('http://localhost/plantillaftp/gestionar_archivos.php');
$client->addScope(Google\Service\Drive::DRIVE_FILE);
$client->addScope("email");
$client->addScope("profile");
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

if (!isset($_SESSION['google_access_token'])) {
    if (!isset($_GET['code'])) {
        $authUrl = $client->createAuthUrl();
        header("Location: $authUrl");
        exit();
    } else {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['google_access_token'] = $token;
        $client->setAccessToken($token);

        $oauth2 = new Google\Service\Oauth2($client);
        $userInfo = $oauth2->userinfo->get();
        $_SESSION['user_name'] = $userInfo->name;
        $_SESSION['user_email'] = $userInfo->email;

        header("Location: gestionar_archivos.php");
        exit();
    }
}

$client->setAccessToken($_SESSION['google_access_token']);
if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    $_SESSION['google_  '] = $client->getAccessToken();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestió d'Arxius</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      width: 100%;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #800020, #330000);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .user-info {
      position: absolute;
      top: 20px;
      right: 20px;
      background: rgba(255, 255, 255, 0.1);
      padding: 10px 15px;
      border-radius: 8px;
      color: white;
      font-weight: bold;
    }

    .upload-container {
      text-align: center;
      background: rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
      max-width: 500px;
      width: 90%;
    }

    h1, p {
      font-weight: bold;
      margin-bottom: 20px;
    }

    .drop-zone {
      padding: 40px;
      border: 2px dashed rgba(255, 255, 255, 0.7);
      border-radius: 10px;
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
      cursor: pointer;
      margin-bottom: 20px;
    }

    .drop-zone p {
      margin: 0;
      font-size: 16px;
    }

    #preview img {
      max-width: 100%;
      max-height: 200px;
      border-radius: 8px;
      margin-top: 10px;
    }

    .upload-button {
      padding: 12px 20px;
      background-color: #ff4d4d;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      font-weight: bold;
    }

    .upload-button:hover {
      background-color: #cc0000;
    }

    .link-back {
      margin-top: 30px;
    }

    .link-back a {
      color: white;
      font-weight: bold;
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="user-info">
  <?php if (isset($_SESSION['user_name'])): ?>
    Connectat com <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong><br>
    <?php echo htmlspecialchars($_SESSION['user_email']); ?>
  <?php endif; ?>
</div>

<div class="upload-container">
  <h1>Gestió d'Arxius</h1>
  <p>Arrossega i deixa anar arxius aquí per pujar-los a Google Drive.</p>

  <form action="subir_archivo.php" method="post" enctype="multipart/form-data" id="upload-form">
    <input type="file" name="archivo" id="file-input" hidden>
    <div class="drop-zone" id="drop-zone">
      <p id="drop-message">Arrossega i deixa anar arxius aquí o fes clic per seleccionar</p>
      <div id="preview"></div>
    </div>
    <button type="submit" class="upload-button">Pujar a Google Drive</button>
  </form>

  <div class="link-back">
    <a href="connectar_tots.php">← Torna a la connexió múltiple</a>
  </div>
</div>

<script>
  const dropZone = document.getElementById("drop-zone");
  const fileInput = document.getElementById("file-input");
  const preview = document.getElementById("preview");
  const dropMessage = document.getElementById("drop-message");

  dropZone.addEventListener("click", () => fileInput.click());

  dropZone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZone.classList.add("dragover");
  });

  dropZone.addEventListener("dragleave", () => {
    dropZone.classList.remove("dragover");
  });

  dropZone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropZone.classList.remove("dragover");
    const files = e.dataTransfer.files;
    if (files.length > 0) {
      fileInput.files = files;
      showPreview(files[0]);
    }
  });

  fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
      showPreview(fileInput.files[0]);
    }
  });

  function showPreview(file) {
    preview.innerHTML = "";

    if (file.type.startsWith("image/")) {
      const img = document.createElement("img");
      img.src = URL.createObjectURL(file);
      preview.appendChild(img);
    } else {
      const fileInfo = document.createElement("p");
      fileInfo.textContent = `Arxiu seleccionat: ${file.name}`;
      preview.appendChild(fileInfo);
    }

    dropMessage.textContent = "Arxiu llest per pujar:";
  }
</script>

</body>
</html>
