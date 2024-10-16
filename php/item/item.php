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
            <a class="nav-link " aria-current="page" href="../customer/viewCustomer.php">Customer</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="../item/viewItem.php">Item</a>
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
  <h2>Add Item</h2>
</div>
<div class="container">
    <form action="./addItem.php" method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label for="icode" class="form-label">Item Code: </label>
        <input type="text" class="form-control" pattern="[A-Z][A-Z][0-9]{1,18}" id="icode" name="icode" required>
        
        <label for="iname" class="form-label">Item Name: </label>
        <input type="text" class="form-control" pattern="[A-Z a-z]{1,20}" id="iname" name="iname" required>
        
        <label for="category" class="form-label">Category: </label>
        <select name="category" class="form-select" id="category" required>
            <option value="1">Printers</option>
            <option value="2">Laptops</option>
            <option value="3">Gadgets</option>
            <option value="4">Ink bottels</option>
            <option value="5">Cartridges</option>
        </select>
        
        <label for="subcategory" class="form-label">Sub Category: </label>
        <select name="subcategory" class="form-select" id="subcategory" required>
            <option value="1">HP</option>
            <option value="2">Dell</option>
            <option value="3">Lenovo</option>
            <option value="4">Acer</option>
            <option value="5">Samsung</option>
        </select>
        
        <label for="quantity" class="form-label">Quantity: </label>
        <input type="number" class="form-control" id="quantity" name="quantity" required>
       
        <label for="uprice" class="form-label">Unit Price: </label>
        <input type="number" class="form-control" step="any" id="uprice" name="uprice" required>
        
        <input type="submit" class="btn btn-primary" style="margin-left: 90%; margin-top: 20px;" value="Submit">
    </form>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>