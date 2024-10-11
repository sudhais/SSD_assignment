<?php
    $severname = "localhost:3306";
    $username = "root";
    $password = "root";
    $dbname = "assignment";

    //creating the connection
    $con = new mysqli($severname, $username, $password, $dbname);

    //checking the connection
    if($con->connect_error){
        die("Connection failed : ".$con->connect_error);
    }
?>