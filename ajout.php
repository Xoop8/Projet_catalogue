<?php
session_start();

require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_POST) {
    if (
        isset($_POST["objet"]) && isset($_POST["titre"]) && isset($_POST["description"]) &&
        isset($_FILES["image"]) && isset($_POST["categorie_id"]) &&
        isset($_POST["ingredients"]) && isset($_POST["id_commentaires"])
    ) {
        $objet = strip_tags($_POST["objet"]);
        $titre = strip_tags($_POST["titre"]);
        $description = strip_tags($_POST["description"]);
        $ingredients = strip_tags($_POST["ingredients"]);
        $id_commentaires = strip_tags($_POST["id_commentaires"]);

        // Gérer le téléchargement de l'image
        $image = $_FILES["image"]["name"]; // Nom du fichier téléchargé
        $image_temp = $_FILES["image"]["tmp_name"]; // Chemin temporaire du fichier téléchargé
        $image_destination = './image/' . $image; // Chemin de destination du fichier

        if (move_uploaded_file($image_temp, $image_destination)) {
            // Image téléchargée avec succès
            $categorie_id = strip_tags($_POST["categorie_id"]);

            // ... le reste du code pour la base de données ...

            $sql = "INSERT INTO produits (objet, titre, description, ingredients, image, categorie_id, id_commentaires)
            VALUES (:objet, :titre, :description, :ingredients, :image, :categorie_id, :id_commentaires)";
            $query = $db->prepare($sql);
            $query->bindValue(":objet", $objet, PDO::PARAM_STR);
            $query->bindValue(":titre", $titre, PDO::PARAM_STR);
            $query->bindValue(":description", $description, PDO::PARAM_STR);
            $query->bindValue(":ingredients", $ingredients, PDO::PARAM_STR);
            $query->bindValue(":image", $image, PDO::PARAM_STR);
            $query->bindValue(":categorie_id", $categorie_id, PDO::PARAM_INT);
            $query->bindValue(
                ":id_commentaires",
                $id_commentaires,
                PDO::PARAM_INT
            );
            $success = $query->execute();

          

         


            if ($success) {
                // ... le reste du code pour la redirection et les messages ...
            } else {
                $error = "Erreur lors de l'ajout du produit : " . $query->errorInfo()[2];
            }
        } else {
            $error = "Une erreur est survenue lors du téléchargement de l'image.";
        }
    } else {
        header("Location: login.php");
        exit();
    }
}

$sql_categories = "SELECT * FROM categorie";
$query_categories = $db->query($sql_categories);
$categories = $query_categories->fetchAll();



require_once("close.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <title>Ajout de produit</title>
</head>

<body class="bg-gray-100">
    <h1 class="font-bold text-center mt-8 text-4xl font-bold text-gray-600 tracking-wide leading-tight">Ajout de produit</h1>
    <?php if (isset($error)) : ?>
        <div class="text-red-500 text-center"><?= $error ?></div>
    <?php endif; ?>
    <form method="post" class="max-w-md mx-auto mt-8 bg-white p-6 rounded-lg shadow-md" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="objet" class="block font-bold text-gray-700">Objet</label>
            <input type="text" name="objet" required class="form-input px-2 mt-1 border-2 border-gray-300 rounded-md py-1">
        </div>
        <div class="mb-4">
            <label for="titre" class="block font-bold text-gray-700">Titre</label>
            <input type="text" name="titre" id="titre" required class="form-input px-2 mt-1 border-2 border-gray-300 rounded-md py-1">
        </div>
        <div class="mb-4">
            <label for="description" class="block font-bold text-gray-700">Description</label>
            <textarea name="description" required class="form-textarea mt-1 w-full resize-none max-h-[200px] px-2 mt-1 border-2 border-gray-300 rounded-md py-1"></textarea>
        </div>
        <div class="mb-4">
            <label for="ingredients" class="block font-bold text-gray-700">Ingredients</label>
            <textarea name="ingredients" required class="form-textarea mt-1 w-full resize-none max-h-[200px] px-2 mt-1 border-2 border-gray-300 rounded-md py-1"></textarea>
        </div>
        <div class="mb-4">
            <label for="id_commentaires" class="block font-bold text-gray-700"></label>
            <input type="hidden" name="id_commentaires" required class="form-select mt-1 px-2 mt-1 border-2 border-gray-300 rounded-md py-1" value="0">
        </div>





        <?php
        if (isset($_POST['envoyer'])) {
            $dossierTempo = $_FILES['image']['tmp_name'];
            $dossierSite = './image/' . $_FILES['image']['name'];

            $tailleMax = 3 * 1024 * 1024;


            if ($_FILES['image']['size'] > $tailleMax) {
                echo 'La taille du fichier dépasse la limite autorisée.';
                // Arrêtez l'exécution du script ou effectuez une autre action appropriée.
            }

            $mime = mime_content_type($_FILES['image']['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png'];
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
            <label for="upload">Envoyer image</label>
            <input type="file" name="image" id="upload">

        </div>
        <div class="mb-4">
            <label for="categorie_id" class="block font-bold text-gray-700">Catégorie</label>
            <select name="categorie_id" class="form-select mt-1 px-2 mt-1 border-2 border-gray-300 rounded-md py-1">

                <?php foreach ($categories as $cat) : ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['objet'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex justify-center">
            <input type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700 cursor-pointer" value="Ajouter">
        </div>
        <div class="flex justify-center mt-10">
            <!-- <a href="historique.php" class="text-black-500 mr-8 hover:underline bg-gray-200 rounded px-2 py-1 hover:bg-gray-600 hover:text-white">Historique</a>
            <a href="modifier.php?id=<?= $produit_id ?>" class="text-blue-500 mr-8 hover:underline bg-blue-200 rounded px-2 py-1 hover:bg-blue-400 hover:text-white">Modifier</a>
            <a href="supprimer.php?id=<?= $produit_id ?>" class="text-red-500 mr-8 hover:underline bg-red-200 rounded px-2 py-1 hover:bg-red-400 hover:text-white">Supprimer</a> -->
            <a href="#" class="text-black-500 hover:underline bg-gray-200 rounded px-2 py-1 hover:bg-gray-400 hover:text-white" onclick="history.back()">Retour</a>


        </div>
    </form>
</body>

</html>