<?php

    $sname = "localhost";
    $uname = "root";
    $password = "";
    $db_name = "catalogue";
    $conn = mysqli_connect($sname, $uname, $password, $db_name);
    if (!$conn){
        echo "Connexion failed!";
        exit();
    }
?>
