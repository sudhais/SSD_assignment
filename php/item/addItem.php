<?php

    session_start();

    // Generate CSRF token if not set
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    // Set the Content Security Policy header
  header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none';");

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Check if CSRF token is present in the form and if it matches the session token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // If the token is missing or doesn't match, stop execution and show an error
            die("Error: Invalid CSRF token.");
        }

            //creating the connection
        $conn = new mysqli("localhost", "root", "root", "assignment");

        //checking the connection
        if($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if all required fields are set
        $required_fields = ['icode', 'category', 'subcategory', 'iname', 'quantity', 'uprice'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                die("Error: All required fields must be filled.");
            }
        }

        // Sanitize input data to prevent SQL Injection and ensure safe output
        $icode = $conn->real_escape_string(trim($_POST["icode"]));
        $category = $conn->real_escape_string(trim($_POST["category"]));
        $subcategory = $conn->real_escape_string(trim($_POST["subcategory"]));
        $iname = $conn->real_escape_string(trim($_POST["iname"]));
        $quantity = $conn->real_escape_string(trim($_POST["quantity"]));
        $uprice = $conn->real_escape_string(trim($_POST["uprice"]));


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

    }

    

?>
