<?php
try {
    $server_name = "localhost";
    $db_name = "catalogue";
    $user_name = "root";
    $password = "";

    $db = new PDO("mysql:host=$server_name;dbname=$db_name;charset=utf8mb4", $user_name, $password);
} catch (PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage();
}
