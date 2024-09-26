<?php
    require("../config.php");
    
    function readItem(){
        global $con;
        $sql = "SELECT * FROM item";
    
        $result = $con->query($sql);

        $categoryArr = array("Printers", "Laptops", "Gadgets", "Ink bottels", "Cartridges");
        $subcategoryArr = array("HP", "Dell", "Lenovo", "Acer", "Samsung");
        if($result->num_rows > 0){
            //read data
            while($row = $result->fetch_assoc()){
                //read and utilize the row data
                $id = $row['id'];
                $icode =$row['item_code'];
                $category = $row['item_category'];
                $subcategory = $row['item_subcategory'];
                $iname = $row['item_name'];
                $quantity = $row['quantity'];
                $uprice = $row['unit_price'];

                $category = $categoryArr[$category - 1];
                $subcategory = $subcategoryArr[$subcategory - 1];
                echo "<tr>
                <td>".$id."</td>
                <td>".$icode."</td>
                <td>".$iname."</td>
                <td>".$category."</td>
                <td>".$subcategory."</td>
                <td>".$quantity."</td>
                <td>".$uprice."</td>
                <td>
                    <a href='editItem.php?id=$id' class='btn btn-info'>Edit</a>  
                    <a href='deleteItem.php?id=$id' class='btn btn-danger'>Delete</a>
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

    function updateQuestion(){
        global $con;
        $sql = "UPDATE ExamQuestion SET Question= '', Answer1='', Answer2='', Answer3='',Answer4=''";

        if($con->query($sql)){
            echo "updated successfully";
        }else{
            echo "Error : ".con->error;
        }
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
    <!-- Page Start -->
    <div class="container text-center">
  <h2>Item List</h2>
</div>

    <div>
        <table border="2px" class="table">
            <tr>
                <th>Item ID</th>
                <th>Item Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Quantity</th>
                <th>Unit Price</th>
            </tr>

            <?php readItem();?>
        </table>
        
        
        <a href="../../html/item.html" class="btn btn-primary" style="margin-left:85%">Add Item</a>
    </div>




    <!-- Footer -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>



