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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if CSRF token is present in the form and if it matches the session token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // If the token is missing or doesn't match, stop execution and show an error
        die("Error: Invalid CSRF token.");
    }

    if (isset($_POST['sbm_edit'])) {

      require "../config.php";

        // Check if all required fields are set
        $required_fields = ['title', 'fname', 'mname', 'lname', 'contactNum', 'district'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                die("Error: All required fields must be filled.");
            }
        }

        // Sanitize input data to prevent SQL Injection and ensure safe output
        $title = $con->real_escape_string(trim($_POST["title"]));
        $fname = $con->real_escape_string(trim($_POST["fname"]));
        $mname = $con->real_escape_string(trim($_POST["mname"]));
        $lname = $con->real_escape_string(trim($_POST["lname"]));
        $contactNum = $con->real_escape_string(trim($_POST["contactNum"]));
        $district = $con->real_escape_string(trim($_POST["district"]));

        

        // Validate contact number (e.g., ensuring it's numeric)
        if (!is_numeric($contactNum) || strlen($contactNum) > 10) {
            echo "Invalid contact number.";
            exit;
        }

        

        // Prepare the update statement
        $sql = "UPDATE customer SET title = ?, first_name = ?, middle_name = ?, last_name = ?, contact_no = ?, district = ? WHERE id = ?";
        $stmt = $con->prepare($sql);

        // Bind the parameters
        $stmt->bind_param('ssssssi', $title, $fname, $mname, $lname, $contactNum, $district, $id);

        // Execute the statement
        if ($stmt->execute()) {
            header('Location: viewCustomer.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
        $con->close();
    }
}

?>

<?php
// Check if 'id' is set and sanitize it
$id = isset($_GET['id']) ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : null;

if (!$id) {
    die('Invalid customer ID.');
}

require "../config.php";

// Prepare the select statement
$sql1 = "SELECT * FROM customer WHERE id = ?";
$stmt = $con->prepare($sql1);

// Bind the parameter
$stmt->bind_param('i', $id);

// Execute the statement
$stmt->execute();

// Get the result
$result1 = $stmt->get_result();

// Fetch the data
if ($row = $result1->fetch_assoc()) {
    $id = $row['id'];
    $title = $row['title'];
    $fname = $row['first_name'];
    $mname = $row['middle_name'];
    $lname = $row['last_name'];
    $contactNum = $row['contact_no'];
    $district = $row['district'];
} else {
    die('Customer not found.');
}

// Close the statement
$stmt->close();
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
                <a class="nav-link active" aria-current="page" href="viewCustomer.php">Customer</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../item/viewItem.php">Item</a>
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
    <h2>Edit Customer</h2>
  </div>
  <div class="container">
    <form action="" method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label for="title" class="form-label">Title: </label>
        <select name="title" class="form-select" value="<?php echo $title; ?>" id="title">
            <option value="Mr">Mr</option>
            <option value="Mrs">Mrs</option>
            <option value="Miss">Miss</option>
            <option value="Dr">Dr</option>
        </select>

        <label for="fname" class="form-label">First Name: </label>
        <input type="text" class="form-control" value="<?php echo $fname; ?>" id="fname" name="fname">

        <label for="mname" class="form-label">Middle Name: </label>
        <input type="text" class="form-control" value="<?php echo $mname; ?>" id="mname" name="mname">

        <label for="lname" class="form-label">Last Name: </label>
        <input type="text" class="form-control" value="<?php echo $lname; ?>" id="lname" name="lname">

        <label for="contactNum" class="form-label">Contact Number: </label>
        <input type="text" class="form-control" value="<?php echo $contactNum; ?>" id="contactNum" name="contactNum">

        <label for="district" class="form-label">District: </label>
        <select name="district" class="form-select" id="district">
            <option value="1">Ampara</option>
            <option value="2">Anuradhapura</option>
            <option value="3">Badulla</option>
            <option value="4">Batticaloa</option>
            <option value="5">Colombo</option>
            <option value="6">Galle</option>
            <option value="7">Gampaha</option>
            <option value="8">Hambantota</option>
            <option value="9">Jaffna</option>
            <option value="10">Kalutara</option>
            <option value="11">Kalutara</option>
            <option value="12">Kandy</option>
            <option value="13">Kegalle</option>
            <option value="14">Kilinochchi</option>
            <option value="15">Kurunegala</option>
            <option value="16">Mannar</option>
            <option value="17">Matale</option>
            <option value="18">Matara</option>
            <option value="19">Moneragala</option>
            <option value="20">Mullaitivu</option>
            <option value="21">Nuwara Eliya</option>
            <option value="22">Polonnaruwa</option>
            <option value="23">Puttalam</option>
            <option value="24">Rathnapura</option>
            <option value="25">Vavuniya</option>
        </select>

        <input type="submit" class="btn btn-primary" style="margin-left: 90%; margin-top: 20px;" name="sbm_edit" value="Update">
    </form>

  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>