<?php
session_start();

$clientId = 'Your-Client-ID';
$clientSecret = 'Your-Client-Secret';
$redirectUri = 'http://localhost/plantillaftp/onedrive_callback.php';

if (!isset($_GET['code'])) {
    echo "<h2 style='color:white;'>No s’ha rebut cap codi de Microsoft.</h2>";
    echo "<pre>";
    print_r($_GET);
    echo "</pre>";
    exit();
}

$code = $_GET['code'];

$tokenRequestData = [
    'client_id' => $clientId,
    'scope' => 'files.readwrite offline_access',
    'code' => $code,
    'redirect_uri' => $redirectUri,
    'grant_type' => 'authorization_code',
    'client_secret' => $clientSecret
];

$ch = curl_init('https://login.microsoftonline.com/common/oauth2/v2.0/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenRequestData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['access_token'])) {
    $_SESSION['onedrive_access_token'] = $data['access_token'];
    $_SESSION['onedrive_refresh_token'] = $data['refresh_token'] ?? null;

    if (isset($_GET['multi']) && $_GET['multi'] == '1') {
        header("Location: connectar_tots.php");
    } else {
        header("Location: gestionar_onedrive.php");
    }
    exit();
} else {
    echo "<h2 style='color:white;'>Error en obtenir el token</h2>";
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}
?>
