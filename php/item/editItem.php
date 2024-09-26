<?php
    $id = $_GET['id'];

    if(isset($_POST['sbm_edit'])){
        $icode = $_POST["icode"];
        $category = $_POST["category"];
        $subcategory = $_POST["subcategory"];
        $iname = $_POST["iname"];
        $quantity = $_POST["quantity"];
        $uprice = $_POST["uprice"];

        require("../config.php");

        $sql = "UPDATE item SET id='$id', item_code= '$icode', item_category='$category', item_subcategory='$subcategory', item_name='$iname',quantity='$quantity',unit_price='$uprice' WHERE id=$id";
        $con->query($sql);

        if($con->query($sql)){
            header('Location: viewItem.php');
            echo "
            <script>
              alert('Hellow World');
            </script>
        "; 
        }else{
            echo "error".$con->error;
        }

    }
?>

<?php
     //edit
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM item WHERE id = $id";
    require("../config.php");

    $result1 = $con->query($sql1);

    while($row = $result1->fetch_array()){

        $id = $row['id'];
        $icode =$row['item_code'];
        $category = $row['item_category'];
        $subcategory = $row['item_subcategory'];
        $iname = $row['item_name'];
        $quantity = $row['quantity'];
        $uprice = $row['unit_price'];
    }

    
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