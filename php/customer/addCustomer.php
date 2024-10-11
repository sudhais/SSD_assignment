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

        // Creating the connection
        $conn = new mysqli("localhost:3306", "root", "root", "assignment");

        // Checking the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if all required fields are set
        $required_fields = ['title', 'fname', 'mname', 'lname', 'contactNum', 'district'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                die("Error: All required fields must be filled.");
            }
        }

        // Sanitize input data to prevent SQL Injection and ensure safe output
        $title = $conn->real_escape_string(trim($_POST["title"]));
        $fname = $conn->real_escape_string(trim($_POST["fname"]));
        $mname = $conn->real_escape_string(trim($_POST["mname"]));
        $lname = $conn->real_escape_string(trim($_POST["lname"]));
        $contactNum = $conn->real_escape_string(trim($_POST["contactNum"]));
        $district = $conn->real_escape_string(trim($_POST["district"]));

        

        // Validate contact number (e.g., ensuring it's numeric)
        if (!is_numeric($contactNum) || strlen($contactNum) > 10) {
            echo "Invalid contact number.";
            exit;
        }

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO Customer (title, first_name, middle_name, last_name, contact_no, district) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssi', $title, $fname, $mname, $lname, $contactNum, $district);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: viewCustomer.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();

    } else {
        // If not POST request, show error or redirect
        echo "Invalid request method.";
        exit;
    }
?>
