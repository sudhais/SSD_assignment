<?php

  session_start();

  // Generate CSRF token if not set
  if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }

  // Set the Content Security Policy header
  header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none';");

  // Check if 'id' is set in the query string and sanitize it
  $id = isset($_GET['id']) ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : null;

  if (!$id) {
      die('Invalid customer ID.');
  }


  // Check if the request method is POST
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check if CSRF token is present in the form and if it matches the session token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      // If the token is missing or doesn't match, stop execution and show an error
      die("Error: Invalid CSRF token.");
    }


    if (isset($_POST['sbm_edit'])) {

      require("../config.php");

      // Check if all required fields are set
      $required_fields = ['icode', 'category', 'subcategory', 'iname', 'quantity', 'uprice'];
      foreach ($required_fields as $field) {
          if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
              die("Error: All required fields must be filled.");
          }
      }

      // Sanitize input data to prevent SQL Injection and ensure safe output
      $icode = $con->real_escape_string(trim($_POST["icode"]));
      $category = $con->real_escape_string(trim($_POST["category"]));
      $subcategory = $con->real_escape_string(trim($_POST["subcategory"]));
      $iname = $con->real_escape_string(trim($_POST["iname"]));
      $quantity = $con->real_escape_string(trim($_POST["quantity"]));
      $uprice = $con->real_escape_string(trim($_POST["uprice"]));

      if (!$icode) {
        die('Invalid item code.');
      }

      // Prepare the SQL statement to prevent SQL injection
      $sql = "UPDATE item SET item_code = ?, item_category = ?, item_subcategory = ?, item_name = ?, quantity = ?, unit_price = ? WHERE id = ?";

      // Prepare and bind
      if ($stmt = $con->prepare($sql)) {
          // Bind parameters (s for string, i for integer, d for double)
          $stmt->bind_param("ssssssi", $icode, $category, $subcategory, $iname, $quantity, $uprice, $id);

          // Execute the query
          if ($stmt->execute()) {
              header('Location: viewItem.php');
              echo "
              <script>
                alert('Update Successful');
              </script>";
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
    }
  }

   
?>

<?php
    // Edit: Fetch data for the item
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM item WHERE id = ?";

    require("../config.php");

    // Prepare the SQL statement
    if ($stmt = $con->prepare($sql1)) {
        // Bind the parameter (i for integer)
        $stmt->bind_param("i", $id);

        // Execute the statement
        $stmt->execute();

        // Fetch the result
        $result1 = $stmt->get_result();
        
        if ($row = $result1->fetch_assoc()) {
            $icode = $row['item_code'];
            $category = $row['item_category'];
            $subcategory = $row['item_subcategory'];
            $iname = $row['item_name'];
            $quantity = $row['quantity'];
            $uprice = $row['unit_price'];
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $con->error;
    }

    // Close the connection
    $con->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSQUARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
 <!-- navbar -->
 <nav class="navbar navbar-expand-lg bg-body-tertiary" >
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="../../img/csquared.png" width="100" height="30" alt="logo">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link " aria-current="page" href="../customer/viewCustomer.php">Customer</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="viewItem.php">Item</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Reports
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="../reports/invoiceReport.php">Invoice Report</a></li>
                  <li><a class="dropdown-item" href="../reports/invoiceItemReport.php">Invoice Item Report</a></li>
                  <li><a class="dropdown-item" href="../reports/itemReport.php">Item Report</a></li>
                </ul>
              </li>
            </ul>
            <form class="d-flex" role="search">
             <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
          </div>
        </div>
      </nav>
    <!-- navbar -->
    <div class="container text-center">
  <h2>Edit Item</h2>
</div>
<div class="container">
<form action="" method="post">
  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label for="icode" class="form-label">Item Code: </label>
        <input type="text" class="form-control" value="<?php echo $icode;?>" id="icode" name="icode">
        
        <label for="iname" class="form-label">Item Name: </label>
        <input type="text" class="form-control" value="<?php echo $iname;?>" id="iname" name="iname">
        
        <label for="category" class="form-label">Category: </label>
        <select name="category" class="form-select" value="<?php echo $category;?>" id="category">
            <option value="1">Printers</option>
            <option value="2">Laptops</option>
            <option value="3">Gadgets</option>
            <option value="4">Ink bottels</option>
            <option value="5">Cartridges</option>
        </select>
        
        <label for="subcategory" class="form-label">Sub Category: </label>
        <select name="subcategory" class="form-select" value="<?php echo $subcategory;?>" id="subcategory">
            <option value="1">HP</option>
            <option value="2">Dell</option>
            <option value="3">Lenovo</option>
            <option value="4">Acer</option>
            <option value="5">Samsung</option>
        </select>
        
        <label for="quantity" class="form-label">Quantity: </label>
        <input type="number" class="form-control" value="<?php echo $quantity;?>" id="quantity" name="quantity">
        
        <label for="uprice" class="form-label">Unit Price: </label>
        <input type="number" class="form-control" value="<?php echo $uprice;?>" id="uprice" name="uprice">
        
        <input type="submit" class="btn btn-primary" style="margin-left: 90%; margin-top: 20px;" name="sbm_edit" value="Submit">
    </form>
    
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>