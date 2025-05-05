<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['onedrive_access_token'])) {
    die("No hi ha sessió iniciada amb OneDrive.");
}

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    die("Error al rebre el fitxer.");
}

$accessToken = $_SESSION['onedrive_access_token'];
$file = $_FILES['archivo'];
$fileName = $file['name'];
$fileContent = file_get_contents($file['tmp_name']);
$uploadUrl = "https://graph.microsoft.com/v1.0/me/drive/root:/PiCloud/{$fileName}:/content";
$ch = curl_init($uploadUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: " . mime_content_type($file['tmp_name'])
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

$data = json_decode($response, true);

if ($http_code >= 200 && $http_code < 300) {
    $webUrl = $data['webUrl'] ?? '#';
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
            }
            a:hover {
                color: #ffbdbd;
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>✅ Fitxer pujat correctament a OneDrive</h2>
            <p><strong>Nom:</strong> <?php echo htmlspecialchars($fileName); ?></p>
            <p><a href="<?php echo $webUrl; ?>" target="_blank">🔗 Veure fitxer a OneDrive</a></p>
            <p><a href="gestionar_onedrive.php">← Tornar a la gestió de fitxers</a></p>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "<h2 style='color:red;'>❌ Error en pujar el fitxer</h2>";
    echo "<pre style='color:white;'>$response</pre>";
}
