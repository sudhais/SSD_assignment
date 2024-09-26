<?php
    require("../config.php");
    $id = $_GET['id'];


    $sql = "DELETE FROM item WHERE id = '$id'";
    $con->query($sql);
        if($con->query($sql)){
            header('Location: viewItem.php');
        }else{
            echo "Error : ".$con->error;
        }
    
?>