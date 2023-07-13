<?php
require "vendor/autoload.php";


$provider = new Stevenmaguire\OAuth2\Client\Provider\Microsoft([
    'clientId'                  => 'd0bc29af-27dc-4483-b433-45bb1dd47177',
    'clientSecret'              => '14H8Q~Yauss75FDfO_14aZsuKU.JrFd2621onbjU',
    'redirectUri'               => 'http://localhost/mapams/callback.php',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack

}



