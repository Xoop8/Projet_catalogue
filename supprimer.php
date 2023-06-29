<?php
session_start();
require_once('connect.php');

if ($_GET && isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM `produits` WHERE `id` = :id";
    $query = $db->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $produit = $query->fetch(PDO::FETCH_ASSOC);

    if ($produit) {
        // Suppression de l'image correspondante
        $imagePath = 'image/' . $produit['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Suppression du produit de la base de données
        $sql = "DELETE FROM `produits` WHERE `id` = :id";
        $query = $db->prepare($sql);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
    }
}

header("Location: historique.php");
exit();
?>
