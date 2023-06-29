
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

}
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="footer.css">
    <title>Rosalie</title>
</head>

<body>
    <footer>
        <h2>Détour</h2>
        <p>&copy; 2023 Carte détour. Tous droits réservés.</p>
        <nav>
            <ul>
                <li><a href="./politique_confidentialite.php">Politique de confidentialité</a></li>
                <li><a href="./conditions_utilisation.php">Conditions d'utilisation</a></li>
            </ul>
        </nav>
    </footer>
</body>

</html>