<?php

    //creating the connection
    $conn = new mysqli("localhost:3306", "root", "", "assignment");

    //checking the connection
    if($conn->connect_error){
        die("Connection failed : ".$conn->connect_error);
    }

    $icode = $_POST["icode"];
    $category = $_POST["category"];
    $subcategory = $_POST["subcategory"];
	$iname = $_POST["iname"];
    $quantity = $_POST["quantity"];
    $uprice = $_POST["uprice"];



    $sql = "INSERT INTO item(item_code, item_category, item_subcategory, item_name, quantity, unit_price) VALUES('$icode','$category', '$subcategory', '$iname', '$quantity', '$uprice')";

    $conn->query($sql);
    header("Location: ../item/viewItem.php");
?>