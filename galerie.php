<?php
session_start();

require_once("connect.php");

$sql_produits = "SELECT * FROM produits";
$query_produits = $db->query($sql_produits);
$produits = $query_produits->fetchAll();

$sql_categories = "SELECT * FROM categorie";
$query_categories = $db->query($sql_categories);
$categories = $query_categories->fetchAll();


$sql = "SELECT p.*, c.objet AS categorie_objet 
        FROM produits p
        JOIN categorie c ON p.categorie_id = c.id";
$query = $db->query($sql);
$produits = $query->fetchAll();

require_once("close.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="galerie.css">
    <link rel="shortcut icon" href="./image/favicon.png" type="image/x-icon">
    <title>Galerie</title>
</head>

<body>
    <section class="gallery">
        <div class="container">
            <div class="rowi">
                <div class="gallery-filter">
                    <h1>Galerie</h1>
                    <a href="galerie.php?categorie_id=all" class="filter-item" data-filter="all">All</a>
                    <a href="galerie.php?categorie_id=1" class="filter-item" data-filter="parfum">Parfum</a>
                    <a href="galerie.php?categorie_id=3" class="filter-item" data-filter="Déodorant">Déodorant</a>
                    <a href="galerie.php?categorie_id=2" class="filter-item" data-filter="Shampoing">Shampoing</a>
                </div>
            </div>

            <div class="row">
                <?php
                $categorieId = $_GET['categorie_id'] ?? 'all';

                foreach ($produits as $produit) {
                    if ($categorieId == 'all' || $produit['categorie_id'] == $categorieId) {
                        echo '<div class="gallery-item" data-category="' . $produit['categorie_objet'] . '">';
                        echo '<a href="page1.php?id=' . $produit['id'] . '">';
                        echo '<img src="image/' . $produit['image'] . '">';
                        echo '<h3>' . $produit['titre'] . '</h3>';
                        echo '</a>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <script src="galerie.js"></script>
    <?php include 'footer.php'; ?>
</body>

</html>