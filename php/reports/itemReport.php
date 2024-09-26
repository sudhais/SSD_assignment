<?php
    require("../config.php");

    function itemReport(){
        global $con;
        global $districtArr;
        $sql = "SELECT i.item_name, ic.category, isc.sub_category, i.quantity FROM item i, item_category ic, item_subcategory isc WHERE i.item_category = ic.id AND i.item_subcategory = isc.id;";
    
        $result = $con->query($sql);
    
        if($result->num_rows > 0){
            //read data
            while($row = $result->fetch_assoc()){
                //read and utilize the row data
                $iName = $row['item_name'];
                $category =$row['category'];
                $subCategory = $row['sub_category'];
                $quantity = $row['quantity'];

                echo "<tr>
                <td>".$iName."</td>
                <td>".$category."</td>
                <td>".$subCategory."</td>
                <td>".$quantity."</td>
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
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
          </div>
        </div>
      </nav>
    <!-- navbar -->

    <!-- Page Start -->
    <div class="container text-center">
    <h1>Item Report</h1>
  </div>
    <div>
        <table border="2px" class="table table-hover">
            <tr class="table-dark">
                <th>Item Name</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Quntity</th>
            </tr>

            <?php itemReport();?>
        </table>
    </div>




    <!-- Footer -->
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>



