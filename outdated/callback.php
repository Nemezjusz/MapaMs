<?php
require "vendor/autoload.php";
session_start();

$provider = new Stevenmaguire\OAuth2\Client\Provider\Microsoft([
    'clientId'                  => 'd0bc29af-27dc-4483-b433-45bb1dd47177',
    'clientSecret'              => '14H8Q~Yauss75FDfO_14aZsuKU.JrFd2621onbjU',
    'redirectUri'               => 'http://localhost/mapams/callback.php',
]);


// Try to get an access token (using the authorization code grant)

$token = $provider->getAccessToken('authorization_code', [
    'code' => $_GET['code']
]);

// try {

//     // We got an access token, let's now get the user's details
$user =  $provider->getResourceOwner($token);
$username = $user->getFirstname();
$usermail = $user->getEmail();

// $stmt = DB::getInstance()->prepare("INSERT INTO users (`name`, mail) VALUES (:username, :usermail)" );
// $stmt->execute([
//     ':username' => $username,
//     ':usermail' => $usermail,
// ]);

// // Use this to interact with an API on the users behalf
$_SESSION['token'] = serialize($token);
// $_SESSION['code'] = $_GET['code'];
//$_SESSION['user'] = serialize($user);

// printf($_SESSION['token']);
header('Location: http://localhost/mapams/');
exit;