<?php

    session_start();

    // Generate CSRF token if not set
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

        // Set the Content Security Policy header
    header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none';");

    require("../config.php");


    $id = $_GET['id'];

    // Prepare the SQL statement to prevent SQL injection
    $sql = "DELETE FROM customer WHERE id = ?";
    
    // Initialize prepared statement
    if ($stmt = $con->prepare($sql)) {
        // Bind the parameter (s for string, i for integer, etc.)
        $stmt->bind_param("i", $id);
        
        // Execute the statement
        if ($stmt->execute()) {
            header('Location: viewCustomer.php');
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $con->error;
    }

    // Close the database connection
    $con->close();

?>