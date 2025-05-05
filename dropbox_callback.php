<?php
session_start();

$appKey = 'Your-App-Key';
$appSecret = 'Your-App-Secret';
$redirectUri = 'http://localhost/plantillaftp/dropbox_callback.php';

if (!isset($_GET['code'])) {
    die('Codi no rebut de Dropbox');
}

$code = $_GET['code'];

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.dropboxapi.com/oauth2/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
    CURLOPT_USERPWD => "$appKey:$appSecret",
    CURLOPT_POSTFIELDS => http_build_query([
        'code' => $code,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $redirectUri
    ])
]);

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);

if (isset($data['access_token'])) {
    $_SESSION['dropbox_access_token'] = $data['access_token'];
    header("Location: gestionar_dropbox.php"); 
    exit();
} else {
    echo "Error en obtenir el token:";
    var_dump($data);
}
