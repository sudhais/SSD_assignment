<?php

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