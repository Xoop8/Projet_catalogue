<?php require_once 'connect.php'; ?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_produits = "SELECT * FROM produits WHERE id = :id";
    $query_produits = $db->prepare($sql_produits);
    $query_produits->bindParam(':id', $id);
    $query_produits->execute();
    $produits = $query_produits->fetch();

    if (isset($produit['objet']) && isset($produit['ingredients'])) {
        $description = $produits['objet'];
        $ingredients = $produits['ingredients'];
    } else {
        // Gérer le cas où le produit n'a pas de description ou d'ingrédients
        $description = "Description non disponible";
    }
} else {
    // Gérer le cas où l'ID du produit n'est pas défini
    $description = "Description non disponible";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_page1.css">
    <link rel="shortcut icon" href="./image/favicon.png" type="image/x-icon">
    <title>Détour</title>
</head>

<body>
    <header>
        <h1><?= $produits['objet'] ?></h1>
        <nav>
            <ul>
                <li><a href="galerie.php">Accueil</a></li>
                <li><a href="./contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>