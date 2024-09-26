<?php

    require("../config.php");
    $id = $_GET['id'];


    $sql = "DELETE FROM customer WHERE id = '$id'";
    $con->query($sql);
        if($con->query($sql)){
            header('Location: viewCustomer.php');
        }else{
            echo "Error : ".$con->error;
        }
    
?>