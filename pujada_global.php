<?php
session_start();

require 'vendor/autoload.php';

if (!isset($_SESSION['google_access_token']) || !isset($_SESSION['dropbox_access_token']) || !isset($_SESSION['onedrive_access_token'])) {
    die("Has d'estar connectat a tots els serveis al núbol abans de fer una pujada simultània.");
}

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    die("Error en rebre l'arxiu.");
}

$fitxer = $_FILES['archivo'];
$nomFitxer = basename($fitxer['name']);
$contingut = file_get_contents($fitxer['tmp_name']);
$tipusMime = $fitxer['type'] ?: 'application/octet-stream';


$client = new Google\Client();
$client->setAuthConfig('credentials.json');
$client->setAccessToken($_SESSION['google_access_token']);
$googleService = new Google\Service\Drive($client);
$fileMetadata = new Google\Service\Drive\DriveFile([
  'name' => $nomFitxer,
  'parents' => ['1qVuXGUoWjxZSPh3lQqiXOjNTWQz4GRgm']
]);

$googleFile = $googleService->files->create($fileMetadata, [
    'data' => $contingut,
    'mimeType' => $tipusMime,
    'uploadType' => 'multipart',
    'fields' => 'id, webViewLink'
]);
$linkGoogle = $googleFile->webViewLink;

$ch = curl_init("https://content.dropboxapi.com/2/files/upload");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$_SESSION['dropbox_access_token']}",
    "Content-Type: application/octet-stream",
    "Dropbox-API-Arg: " . json_encode([
        "path" => "/$nomFitxer",
        "mode" => "add",
        "autorename" => true,
        "mute" => false,
    ])
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $contingut);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$responseDropbox = curl_exec($ch);
curl_close($ch);
$linkDropbox = "https://www.dropbox.com/home?preview=" . urlencode($nomFitxer);


$createSessionUrl = "https://graph.microsoft.com/v1.0/me/drive/root:/PiCloud/{$nomFitxer}:/createUploadSession";
$ch = curl_init($createSessionUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$_SESSION['onedrive_access_token']}",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "item" => [
        "@microsoft.graph.conflictBehavior" => "rename",
        "name" => $nomFitxer
    ]
]));

$response = curl_exec($ch);
if (!$response) {
    die("❌ Error al crear sessió de pujada: " . curl_error($ch));
}
curl_close($ch);

$data = json_decode($response, true);
if (!isset($data['uploadUrl'])) {
    die("❌ No s’ha pogut obtenir l’uploadUrl de OneDrive.");
}

$uploadUrl = $data['uploadUrl'];
$ch = curl_init($uploadUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $contingut);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Length: " . strlen($contingut),
    "Content-Range: bytes 0-" . (strlen($contingut) - 1) . "/" . strlen($contingut)
]);

$responseUpload = curl_exec($ch);
if (!$responseUpload) {
    die("❌ Error de CURL OneDrive (upload session): " . curl_error($ch));
}
$dataOneDrive = json_decode($responseUpload, true);
curl_close($ch);

$linkOneDrive = $dataOneDrive['webUrl'] ?? '#';


?>

<!DOCTYPE html>
<html lang="ca">
<head>
  <meta charset="UTF-8">
  <title>Fitxer Pujat</title>
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
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }
    a {
      color: white;
      font-weight: bold;
      text-decoration: none;
      display: block;
      margin-top: 10px;
    }
    a:hover {
      color: #ffbdbd;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>✅ Fitxer pujat correctament a tots els serveis!</h2>
    <p><strong>Nom:</strong> <?php echo htmlspecialchars($nomFitxer); ?></p>
    <a href="<?php echo $linkGoogle; ?>" target="_blank">🔗 Veure a Google Drive</a>
    <a href="<?php echo $linkDropbox; ?>" target="_blank">🔗 Veure a Dropbox</a>
    <a href="<?php echo $linkOneDrive; ?>" target="_blank">🔗 Veure a OneDrive</a>
    <a href="pagina_ftp.php">← Tornar a l'inici</a>
  </div>
</body>
</html>

