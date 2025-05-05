<?php
session_start();

if (!isset($_SESSION['dropbox_access_token'])) {
    die("No hi ha cap sessió activa amb Dropbox.");
}

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    die("Error en rebre el fitxer.");
}

$accessToken = $_SESSION['dropbox_access_token'];
$archivo = $_FILES['archivo'];
$filename = basename($archivo['name']);
$fileContent = file_get_contents($archivo['tmp_name']);

$ch = curl_init("https://content.dropboxapi.com/2/files/upload");

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken",
    "Content-Type: application/octet-stream",
    "Dropbox-API-Arg: " . json_encode([
        "path" => "/$filename",
        "mode" => "add",
        "autorename" => true,
        "mute" => false
    ])
]);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

$data = json_decode($response, true);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Fitxer pujat a Dropbox</title>
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

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 20px;
        }

        .left-panel {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .left-panel h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .left-panel p {
            font-size: 16px;
            margin: 10px 0;
        }

        .left-panel a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 15px;
        }

        .left-panel a:hover {
            text-decoration: underline;
            color: #ffbdbd;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="left-panel">
        <?php if ($error): ?>
            <h2>Error en la pujada</h2>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php elseif (isset($data['name'])): ?>
            <h2>Fitxer pujat correctament a Dropbox</h2>
            <p><strong>Nom:</strong> <?php echo htmlspecialchars($data['name']); ?></p>
            <?php
                $path_encoded = urlencode($data['path_display']);
                $shared_link = null;

                $ch2 = curl_init("https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings");
                curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                    "Authorization: Bearer $accessToken",
                    "Content-Type: application/json"
                ]);
                curl_setopt($ch2, CURLOPT_POST, true);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode([
                    "path" => $data['path_display'],
                    "settings" => ["requested_visibility" => "public"]
                ]));

                $link_response = curl_exec($ch2);
                $link_data = json_decode($link_response, true);
                curl_close($ch2);

                if (isset($link_data['url'])) {
                    $shared_link = $link_data['url'];
                    echo "<p><a href='" . htmlspecialchars($shared_link) . "' target='_blank'>Veure fitxer a Dropbox</a></p>";
                }
            ?>
        <?php else: ?>
            <h2>Error desconegut</h2>
            <p>No s'ha pogut obtenir informació del fitxer.</p>
        <?php endif; ?>

        <a href="gestionar_dropbox.php">← Tornar a la gestió de fitxers</a>
    </div>
</div>

</body>
</html>
