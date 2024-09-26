<?php

    // Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Creating the connection
    $conn = new mysqli("localhost:3306", "root", "", "assignment");

    // Checking the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input data to prevent SQL Injection
    $title = $conn->real_escape_string($_POST["title"]);
    $fname = $conn->real_escape_string($_POST["fname"]);
    $mname = $conn->real_escape_string($_POST["mname"]);
    $lname = $conn->real_escape_string($_POST["lname"]);
    $contactNum = $conn->real_escape_string($_POST["contactNum"]);
    $district = $conn->real_escape_string($_POST["district"]);

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