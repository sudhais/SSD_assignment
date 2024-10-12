<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>
    <img src="<?php echo $_SESSION['user_picture']; ?>" alt="User Picture">
    <p>Email: <?php echo $_SESSION['user_email']; ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>
