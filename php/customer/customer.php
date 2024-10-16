<?php
  session_start();

  // Generate CSRF token if not set
  if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }

    // Set the Content Security Policy header
    header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none';");
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
                <a class="nav-link active" aria-current="page" href="./viewCustomer.php">Customer</a>
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
              <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars('sdfsdf'); ?>">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
          </div>
        </div>
      </nav>
    <!-- navbar -->
      <div class="container text-center">
    <h2>Add Customer</h2>
  </div>
    <div class="container">
      
    <form action="./addCustomer.php" method="post">
		  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label for="title" class="form-label">Title: </label>
        <select name="title" class="form-select" id="title" required>
            <option value="Mr">Mr</option>
            <option value="Mrs">Mrs</option>
            <option value="Miss">Miss</option>
            <option value="Dr">Dr</option>
        </select>
        <label for="fname" class="form-label">First Name: </label>
        <input type="text" class="form-control" id="fname" pattern="[A-Za-z]{1,50}" name="fname" required>
        
        <label for="mname" class="form-label">Middle Name: </label>
        <input type="text" class="form-control" pattern="[A-Za-z]{1,50}" id="mname" name="mname">
        
        <label for="lname" class="form-label">Last Name: </label>
        <input type="text" class="form-control" pattern="[A-Za-z]{1,50}" id="lname" name="lname" required>
        
        <label for="contactNum" class="form-label">Contact Number: </label>
        <input type="text" class="form-control" pattern="[0-9]{10}" id="contactNum" name="contactNum" required>
       
        <label for="district" class="form-label">District: </label>
        <select name="district" class="form-select" id="district" required>
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
        
        <input type="submit" class="btn btn-primary" style="margin-left: 90%; margin-top: 20px;" value="Submit">
    </form>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>