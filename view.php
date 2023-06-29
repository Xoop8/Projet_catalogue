<?php include "db_conn.php";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view</title>
</head>
<body>
    <a href= "ajoutpicture.php">&#8592;</a> 
    <?php 
    $sql = "SELECT * FROM images ORDER BY id DESC";
    $res = mysqli_query($conn,$sql );

    if (mysqli_num_rows($res) > 0) {
    while ($images = mysqli_fetch_assoc($res)) {
       
        ?>
        <div class="alb">
            <img src="uploads/<?=$images['image_url']?>">
        </div>
    
    <?php }

    }
    ?>
</body>
</html>