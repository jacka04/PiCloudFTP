<?php
session_start();

$appKey = 'Your-App-Key';
$redirectUri = 'http://localhost/plantillaftp/dropbox_callback.php';

$params = [
    'response_type' => 'code',
    'client_id' => $appKey,
    'redirect_uri' => $redirectUri
];

$authUrl = 'https://www.dropbox.com/oauth2/authorize?' . http_build_query($params);
header('Location: ' . $authUrl);
exit();
