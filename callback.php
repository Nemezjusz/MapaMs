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

//     // Use these details to create a new profile
//     printf('Hello %s!', $user->getFirstname());

// } catch (Exception $e) {

//     // Failed to get user details
//     exit('Oh dear...');
// }

// // Use this to interact with an API on the users behalf
$_SESSION['token'] = serialize($token);
// $_SESSION['code'] = $_GET['code'];
$_SESSION['user'] = serialize($user);

// printf($_SESSION['token']);
header('Location: http://localhost/mapams/');
exit;