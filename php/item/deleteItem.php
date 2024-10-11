<?php
    require("../config.php");
    $id = $_GET['id'];

    // Prepare the SQL statement to avoid SQL injection
    $sql = "DELETE FROM item WHERE id = ?";

    // Prepare the statement
    if ($stmt = $con->prepare($sql)) {
        // Bind the id parameter (i for integer)
        $stmt->bind_param("i", $id);

        // Execute the statement
        if ($stmt->execute()) {
            header('Location: viewItem.php');
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $con->error;
    }

    // Close the connection
    $con->close();

?>
