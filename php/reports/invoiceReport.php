<?php

  session_start();

  // Generate CSRF token if not set
  if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }

  // Set the Content Security Policy header
  header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none';");

    require("../config.php");
    
    $districtArr = array('Ampara', 'Anuradhapura', 'Badulla', 'Batticaloa', 'Colombo', 'Galle', 'Gampaha', 'Hambantota', 'Jaffna', 'Kalutara', 'Kalutara', 'Kandy', 'Kegalle', 'Kilinochchi', 'Kurunegala', 'Mannar', 'Matale', 'Matara', 'Moneragala', 'Mullaitivu', 'Nuwara Eliya', 'Polonnaruwa', 'Puttalam', 'Rathnapura', 'Vavuniya');

    function invoiceReport(){
        global $con;
        global $districtArr;

        $start = '2000-01-01';
        $to = date('Y').'-'.date('m').'-'.date('d');

        if (isset($_POST['sbm_search'])) {

          // Check if CSRF token is present in the form and if it matches the session token
          if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // If the token is missing or doesn't match, stop execution and show an error
            die("Error: Invalid CSRF token.");
          }

            $start = $_POST['start'];
            $to = $_POST['to'];
        }

        // Prepare the SQL query to avoid SQL injection
        $sql = "SELECT i.*, c.* FROM invoice i 
                JOIN customer c ON i.customer = c.id 
                WHERE i.date BETWEEN ? AND ?";
        
        // Prepare the statement
        if ($stmt = $con->prepare($sql)) {
            // Bind the parameters (s for string, here the date is treated as a string)
            $stmt->bind_param("ss", $start, $to);
            
            // Execute the statement
            $stmt->execute();
            
            // Get the result
            $result = $stmt->get_result();
            
            // Check if any rows are returned
            if ($result->num_rows > 0) {
                // Read data
                while ($row = $result->fetch_assoc()) {
                    // Read and utilize the row data
                    $id = htmlspecialchars($row['id']);
                    $invoiceNo = htmlspecialchars($row['invoice_no']);
                    $date = htmlspecialchars($row['date']);
                    $title = htmlspecialchars($row['title']);
                    $fname = htmlspecialchars($row['first_name']);
                    $mname = htmlspecialchars($row['middle_name']);
                    $lname = htmlspecialchars($row['last_name']);
                    $district = htmlspecialchars($row['district']);
                    $iCount = htmlspecialchars($row['item_count']);
                    $amount = htmlspecialchars($row['amount']);

                    // Format the full name
                    $fullName = $title . '. ' . $fname . ' ' . $mname . ' ' . $lname;
                    // Convert district ID to district name
                    $districtName = $districtArr[$district - 1];

                    // Output the table row
                    echo "<tr>
                        <td>{$invoiceNo}</td>
                        <td>{$date}</td>
                        <td>{$fullName}</td>
                        <td>{$districtName}</td>
                        <td>{$iCount}</td>
                        <td>{$amount}</td>
                    </tr>";
                }
            } else {
                // No rows found
                echo "<tr>
                    <td colspan='6'>No records found</td>
                </tr>";
            }

            // Close the statement
            $stmt->close();
        } else {
            // Error in preparing the SQL statement
            echo "Error: " . $con->error;
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>CSQUARE</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <!-- Header -->
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
                <a class="nav-link " href="../item/viewItem.php">Item</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Reports
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="invoiceReport.php">Invoice Report</a></li>
                  <li><a class="dropdown-item" href="invoiceItemReport.php">Invoice Item Report</a></li>
                  <li><a class="dropdown-item" href="itemReport.php">Item Report</a></li>
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
    <!-- Page Start -->
    <div class="container text-center">
    <h1>Invoice Report</h1>
  </div>
    <form class="container text-center" style="margin: 30px" action="" method='post'>
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label for="start">Date From: </label>
        <input type="date" id="start" name="start">
    
        <label for="to">To: </label>
        <input type="date" id="to" name="to">

        <input type="submit" name="sbm_search" style="margin-left:20px" class="btn btn-success" value="Search">
    </form>

    <div>
        <table border="2px" class="table table-hover">
            <tr class="table-dark">
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Customer</th>
                <th>District</th>
                <th>Item Count</th>
                <th>Invoice Amount</th>
            </tr>

            <?php invoiceReport();?>
        </table>
    </div>




    <!-- Footer -->
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>



