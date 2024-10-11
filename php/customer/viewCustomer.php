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

    function readCustomer(){
        global $con;
        global $districtArr;
        $sql = "SELECT * FROM customer";
    
        $result = $con->query($sql);
    
        if($result->num_rows > 0){
            //read data
            while($row = $result->fetch_assoc()){
                //read and utilize the row data
                $id = htmlspecialchars($row['id']);
				$title = htmlspecialchars($row['title']);
				$fname = htmlspecialchars($row['first_name']);
				$mname = htmlspecialchars($row['middle_name']);
				$lname = htmlspecialchars($row['last_name']);
				$contactNum = htmlspecialchars($row['contact_no']);
				$district = htmlspecialchars($row['district']);

				$fullName = htmlspecialchars($title.'.'.' '.$fname.' '.$mname.' '.$lname);
				$district = htmlspecialchars($districtArr[$district - 1]);

                echo "<tr>
                <td>".$id."</td>
                <td>".$fullName."</td>
                <td>".$contactNum."</td>
                <td>".$district."</td>
                <td>
                    <a href='editCustomer.php?id=$id' class='btn btn-info'>Edit</a>  
                    <a href='deleteCustomer.php?id=$id' class='btn btn-danger'>Delete</a>
                </td>
            </tr>";

        }
        }else{
            echo "<tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                
            </tr>";
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
    <!-- Page Start -->
    <div class="container text-center">
  <h2>Customer List</h2>
</div>
    <div>
        <table border="2px" class="table table-hover">
            <tr class="table-dark">
                <th>Customer ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>District</th>
                <th></th>
            </tr>

            <?php readCustomer();?>
        </table>
        
        
        <a href="./customer.php" class="btn btn-primary" style="margin-left:85%">Add Customer</a>
    </div>




    <!-- Footer -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>



