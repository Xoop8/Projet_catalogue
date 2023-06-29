<?php
session_start();
require_once("connect.php");

// Récupération des informations du produit
$id = "";
if (isset($_GET["id"]) && !empty($_GET['id'])) {
    $id = strip_tags($_GET['id']);

    // Récupérer les informations du produit et de sa catégorie depuis les tables produits et categorie
    $sql = "SELECT p.*, c.objet AS categorie_objet 
        FROM produits p
        JOIN categorie c ON p.categorie_id = c.id
        WHERE p.id = :id";
    $query = $db->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $produit = $query->fetch();

    if ($_POST) {
        if (isset($_POST["objet"]) && isset($_POST["titre"]) &&
            isset($_POST["description"]) && isset($_POST["ingredients"]) && isset($_POST["categorie_id"])) {

            $objet = strip_tags($_POST["objet"]);
            $titre = strip_tags($_POST["titre"]);
            $description = strip_tags($_POST["description"]);
            $ingredients = strip_tags($_POST["ingredients"]);
            $categorie_id = strip_tags($_POST["categorie_id"]);

            // Vérifier si une nouvelle image est téléchargée
            if (!empty($_FILES["image"]["name"])) {
                $image = $_FILES["image"]["name"]; // Nom du fichier téléchargé
                $image_temp = $_FILES["image"]["tmp_name"]; // Chemin temporaire du fichier téléchargé
                $image_destination = './image/' . $image; // Chemin de destination du fichier

                if (move_uploaded_file($image_temp, $image_destination)) {
                    // Image téléchargée avec succès
                    $produit['image'] = $image; // Mettre à jour le nom de l'image dans les données du produit
                } else {
                    echo 'Une erreur est survenue lors du téléchargement du fichier.';
                }
            }

            // Mise à jour du produit dans la table "produits"
            $sql = "UPDATE produits SET objet=:objet, titre=:titre, description=:description, ingredients=:ingredients, image=:image, categorie_id=:categorie_id WHERE id = :id";
            $query = $db->prepare($sql);
            $query->bindValue(":id", $id, PDO::PARAM_INT);
            $query->bindValue(":objet", $objet, PDO::PARAM_STR);
            $query->bindValue(":titre", $titre, PDO::PARAM_STR);
            $query->bindValue(":description", $description, PDO::PARAM_STR);
            $query->bindValue(":ingredients", $ingredients, PDO::PARAM_STR);
            $query->bindValue(":image", $image, PDO::PARAM_STR);
            $query->bindValue(":categorie_id", $categorie_id, PDO::PARAM_INT);
            $query->execute();

            $_SESSION["toast_message"] = "Produit $id modifié avec succès";
            $_SESSION["toast_type"] = "success";

            header("Location: historique.php");
            exit();
        }
    }
} 

// Récupération des informations du produit
$id = "";
if (isset($_GET["id"]) && !empty($_GET['id'])) {
    $id = strip_tags($_GET['id']);

    // Récupérer les informations du produit et de sa catégorie depuis les tables produits et categorie
    $sql = "SELECT p.*, c.objet AS categorie_objet 
        FROM produits p
        JOIN categorie c ON p.categorie_id = c.id
        WHERE p.id = :id";
    $query = $db->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    $produit = $query->fetch();
} else {
    header("Location: login.php");
    exit();
}

require_once("close.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <title>Modification du produit</title>
</head>

<body class="bg-gray-100">
    <h1 class="font-bold text-center mt-8 text-4xl font-bold text-gray-600 tracking-wide leading-tight">Modification du produit n° <?= $id ?></h1>
    <div class="max-w-md mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">
        <form method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="objet" class="block font-bold text-gray-700">Nom</label>
                <input type="text" name="objet" required value="<?= $produit['objet'] ?>" class="form-input px-2 mt-1 border-2 border-gray-300 rounded-md py-1">



            </div>
            <div class="mb-4">
                <label for="titre" class="block font-bold text-gray-700">Titre</label>
                <input type="text" name="titre" id="titre" required value="<?= $produit['titre'] ?>" class="form-input px-2 mt-1 border-2 border-gray-300 rounded-md py-1">
            </div>
            <div class="mb-4">
                <label for="description" class="block font-bold text-gray-700">Description</label>
                <textarea name="description" required class="form-textarea mt-1 w-full resize-none max-h-[200px] px-2 mt-1 border-2 border-gray-300 rounded-md py-1"><?= $produit['description'] ?></textarea>


            </div>
            <div class="mb-4">
                <label for="ingredients" class="block font-bold text-gray-700">ingredients</label>
                <textarea name="ingredients" required class="form-textarea mt-1 w-full resize-none max-h-[200px] px-2 mt-1 border-2 border-gray-300 rounded-md py-1"><?= $produit['ingredients'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="image" class="block font-bold text-gray-700">Image</label>
                <img src="image/<?= $produit['image'] ?>" alt="Product Image" class="w-64 h-auto mt-1">
            </div>

            <?php
        if (isset($_POST['envoyer'])) {
            $dossierTempo = $_FILES['image']['tmp_name'];
            $dossierSite = './image/' . $_FILES['image']['name'];

            $tailleMax = 4 * 1024 * 1024;


            if ($_FILES['image']['size'] > $tailleMax) {
                echo 'La taille du fichier dépasse la limite autorisée.';
                // Arrêtez l'exécution du script ou effectuez une autre action appropriée.
            }

            $mime = mime_content_type($_FILES['image']['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($mime, $allowedTypes)) {
                echo 'Type de fichier non autorisé.';
            }

            $deplacer = move_uploaded_file($dossierTempo, $dossierSite);
            if ($deplacer) {
                chmod($dossierSite, 0777);

                echo 'Image envoyée avec succès';
            } else {
                echo 'Une erreur est survenue lors du téléchargement du fichier.';
            }
        }
        ?>



<div class="mb-4">
        <label for="image_upload" class="block font-bold text-gray-700">Télécharger une nouvelle image</label>
        <input type="file" name="image" id="image_upload">
    </div>
    
            <div class="mb-4">
                <label for="categorie_id" class="block font-bold text-gray-700">Catégorie</label>
                <select name="categorie_id" class="form-select mt-1 px-2 mt-1 border-2 border-gray-300 rounded-md py-1">
                    <option value="<?= $produit['categorie_id'] ?>" selected><?= $produit['categorie_objet'] ?></option>
                </select>
            </div>


            <input type="hidden" value="<?= $produit["id"] ?>" name="id">
            <div class="flex justify-center">
                <input type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700 cursor-pointer" value="Enregistrer">

                <a href="historique.php" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-700 cursor-pointer">retour</a>