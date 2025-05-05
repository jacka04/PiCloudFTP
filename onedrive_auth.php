<?php
session_start();

$clientId = 'Your-Client-ID';
$redirectUri = 'http://localhost/plantillaftp/onedrive_callback.php';
$scope = 'files.readwrite offline_access';

$multi = isset($_GET['multi']) && $_GET['multi'] == '1';

$params = [
    'client_id' => $clientId,
    'response_type' => 'code',
    'redirect_uri' => $redirectUri, 
    'scope' => $scope,
    'response_mode' => 'query',
    'state' => $multi ? 'multi' : 'single' 
];

$authUrl = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?" . http_build_query($params);

header("Location: $authUrl");
exit();
?>
