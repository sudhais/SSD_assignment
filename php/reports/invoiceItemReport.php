<?php

  session_start();

  // Generate CSRF token if not set
  if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }

  // Set the Content Security Policy header
  header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none';");
  
    require("../config.php");

    function invoiceItemReport(){
        global $con;

        // Set default date range
        $start = '2000-01-01';
        $to = date('Y-m-d');  // Use the correct format for the current date

        // Check if form is submitted
        if (isset($_POST['sbm_search'])) {

          // Check if CSRF token is present in the form and if it matches the session token
          if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // If the token is missing or doesn't match, stop execution and show an error
            die("Error: Invalid CSRF token.");
          }

            $start = $_POST['start'];
            $to = $_POST['to'];
        }

        // Prepare the SQL statement with placeholders to avoid SQL injection
        $sql = "SELECT i.id, i.invoice_no, i.date, c.title, c.first_name, c.middle_name, c.last_name, 
                it.item_name, it.item_code, ic.category, it.unit_price 
                FROM invoice i 
                JOIN invoice_master im ON i.invoice_no = im.invoice_no 
                JOIN item it ON im.item_id = it.id 
                JOIN customer c ON i.customer = c.id 
                JOIN item_category ic ON ic.id = it.item_category 
                WHERE i.date BETWEEN ? AND ?";

        // Prepare the statement
        if ($stmt = $con->prepare($sql)) {
            // Bind parameters to the query (s for string as dates are strings)
            $stmt->bind_param("ss", $start, $to);

            // Execute the statement
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            // Check if rows are returned
            if ($result->num_rows > 0) {
                // Loop through each row
                while ($row = $result->fetch_assoc()) {
                    $id = htmlspecialchars($row['id']);
                    $invoiceNo = htmlspecialchars($row['invoice_no']);
                    $date = htmlspecialchars($row['date']);
                    $title = htmlspecialchars($row['title']);
                    $fname = htmlspecialchars($row['first_name']);
                    $mname = htmlspecialchars($row['middle_name']);
                    $lname = htmlspecialchars($row['last_name']);
                    $iName = htmlspecialchars($row['item_name']);
                    $iCode = htmlspecialchars($row['item_code']);
                    $iCategory = htmlspecialchars($row['category']);
                    $uprice = htmlspecialchars($row['unit_price']);

                    $fullName = $title . '.' . ' ' . $fname . ' ' . $mname . ' ' . $lname;
                    $itemNC = $iCode . '-' . $iName;

                    // Output the data as HTML table rows
                    echo "<tr>
                        <td>" . htmlspecialchars($invoiceNo) . "</td>
                        <td>" . htmlspecialchars($date) . "</td>
                        <td>" . htmlspecialchars($fullName) . "</td>
                        <td>" . htmlspecialchars($itemNC) . "</td>
                        <td>" . htmlspecialchars($iCategory) . "</td>
                        <td>" . htmlspecialchars($uprice) . "</td>
                    </tr>";
                }
            } else {
                // Display empty row if no results
                echo "<tr>
                    <td colspan='6'>No results found</td>
                </tr>";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $con->error;
        }

        // Close the connection (optional, but good practice)
        $con->close();
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
    <h1>Invoice Item Report</h1>
  </div>
    <form class="container text-center" style="margin: 30px" action="" method='post'>
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label for="start">Date From: </label>
        <input type="date" id="start" name="start">
    
        <label for="to">To: </label>
        <input type="date" id="to" name="to">

        <input type="submit" class="btn btn-success" style="margin-left:20px" name="sbm_search" value="Search">
    </form>
    <div>
        <table border="2px" class="table table-hover">
            <tr class="table-dark">
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Item</th>
                <th>Item Category</th>
                <th>Item Unit Price</th>
            </tr>

            <?php invoiceItemReport();?>
        </table>
    </div>




    <!-- Footer -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>



