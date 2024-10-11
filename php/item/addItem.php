<?php

    //creating the connection
    $conn = new mysqli("localhost", "root", "root", "assignment");

    //checking the connection
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieving POST data
    $icode = $_POST["icode"];
    $category = $_POST["category"];
    $subcategory = $_POST["subcategory"];
    $iname = $_POST["iname"];
    $quantity = $_POST["quantity"];
    $uprice = $_POST["uprice"];

    // Preparing the SQL statement to prevent SQL injection
    $sql = "INSERT INTO item(item_code, item_category, item_subcategory, item_name, quantity, unit_price) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters (s for string, i for integer, d for double)
        $stmt->bind_param("ssssdi", $icode, $category, $subcategory, $iname, $quantity, $uprice);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: ../item/viewItem.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the connection
    $conn->close();

?>
