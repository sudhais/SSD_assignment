<?php
require_once 'vendor/autoload.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialize Google Client
$client = new Google_Client();
$client->setClientId(getenv('clientID'));
$client->setClientSecret(getenv('clientSecret'));
$client->setRedirectUri('http://localhost/google-login-callback.php');
$client->addScope("email");
$client->addScope("profile");

// Generate Google login URL
$loginUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Google</title>
</head>
<body>
    <h1>Login with Google</h1>
    <a href="<?php echo $loginUrl; ?>">Login with Google</a>
</body>
</html>
