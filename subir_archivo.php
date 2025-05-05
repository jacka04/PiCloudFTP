<?php
session_start();
require 'vendor/autoload.php';

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    die('❌ Error al pujar el fitxer.');
}

if (!isset($_SESSION['google_access_token'])) {
    die('❌ No hi ha sessió activa amb Google Drive.');
}

$client = new Google\Client();
$client->setAuthConfig('credentials.json');
$client->setAccessToken($_SESSION['google_access_token']);

if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    $_SESSION['google_access_token'] = $client->getAccessToken();
}

$driveService = new Google\Service\Drive($client);

$fileMetadata = new Google\Service\Drive\DriveFile([
    'name' => $_FILES['archivo']['name'],
    'parents' => ['1qVuXGUoWjxZSPh3lQqiXOjNTWQz4GRgm'] 
]);

$content = file_get_contents($_FILES['archivo']['tmp_name']);

try {
    $file = $driveService->files->create($fileMetadata, [
        'data' => $content,
        'mimeType' => $_FILES['archivo']['type'] ?: 'application/octet-stream',
        'uploadType' => 'multipart',
        'fields' => 'id, webViewLink'
    ]);
} catch (Exception $e) {
    die("❌ Error en pujar a Google Drive: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Fitxer Pujat</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            height: 100%;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #800020, #330000);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
        }
        a {
            color: white;
            font-weight: bold;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
            color: #ffbdbd;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>✅ Fitxer pujat correctament a Google Drive</h2>
    <p><strong>Nom:</strong> <?php echo htmlspecialchars($_FILES['archivo']['name']); ?></p>
    <p><a href="<?php echo $file->webViewLink; ?>" target="_blank">🔗 Veure el fitxer a Google Drive</a></p>
    <p><a href="gestionar_archivos.php">← Tornar a la gestió d’arxius</a></p>
</div>
</body>
</html>
