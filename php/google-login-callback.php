<?php
require_once 'vendor/autoload.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();

// Initialize Google Client
$client = new Google_Client();
$client->setClientId(getenv('clientID'));
$client->setClientSecret(getenv('clientSecret'));
$client->setRedirectUri('http://localhost/google-login-callback.php');
$client->addScope("email");
$client->addScope("profile");

// Authenticate user
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
    $client->setAccessToken($token);

    // Get user info
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Store user info in session (or database)
    $_SESSION['user_email'] = $userInfo->email;
    $_SESSION['user_name'] = $userInfo->name;
    $_SESSION['user_picture'] = $userInfo->picture;

    // Redirect to profile page
    header('Location: profile.php');
    exit();
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
}
?>
