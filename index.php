<?php
session_start();
require_once("connect.php");

$adminIP = $_SERVER['REMOTE_ADDR'];

if ($adminIP === "90.63.192.240") {
    $showLoginButton = true;
} else {
    $showLoginButton = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>detour</title>
</head>

<body>
    <div class="background">
        <?php if ($showLoginButton) { ?>
            <a href="login.php">
                <i class="fa-solid fa-right-to-bracket"></i>
            </a>
        <?php } ?>
        <div class="image-container">
            <img class="fond" src="./parfum_1080p.png">
        </div>
        <a href="galerie.php" class="gallery-link">
            <button class="gallery-button">Accéder à la galerie</button>
        </a>
    </div>


</body>

</html>