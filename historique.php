<?php
session_start();

require_once("connect.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

if ($_POST) {
    if (
        !empty($_POST["objet"]) && !empty($_POST["description"])
        && !empty($_POST["image"]) && !empty($_POST["categorie_id"])
    ) {
        $objet = strip_tags($_POST["objet"]);
        $description = strip_tags($_POST["description"]);
        $image = strip_tags($_POST["image"]);
        $categorie_id = strip_tags($_POST["categorie_id"]);

        $sql = "INSERT INTO produits (objet, description, image, categorie_id) 
                VALUES (:objet, :description, :image, :categorie_id)";
        $query = $db->prepare($sql);
        $query->bindValue(":objet", $objet, PDO::PARAM_STR);
        $query->bindValue(":description", $description, PDO::PARAM_STR);
        $query->bindValue(":image", $image, PDO::PARAM_STR);
        $query->bindValue(":categorie_id", $categorie_id, PDO::PARAM_STR);
        $query->execute();

        $_SESSION["toast_message"] = "Ajouté avec succès";
        $_SESSION["toast_type"] = "success";

        header("Location: historique.php");
        exit();
       
    } else {
        header("Location: login.php");
        exit();
    }
}


// pagination
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = (int) strip_tags($_GET['page']);
} else {
    $currentPage = 1;
}

$sql = 'SELECT COUNT(*) AS nb_articles FROM `produits`;';
$query = $db->prepare($sql);
$query->execute();
$results = $query->fetch();
$nbArticles = (int) $results['nb_articles'];

$parPage = 5;
$pages = ceil($nbArticles / $parPage);
$premier = ($currentPage - 1) * $parPage;

$sql = 'SELECT * FROM `produits` ORDER BY `objet` ASC LIMIT :premier, :parpage;';
$query = $db->prepare($sql);
$query->bindValue(':premier', $premier, PDO::PARAM_INT);
$query->bindValue(':parpage', $parPage, PDO::PARAM_INT);
$query->execute();
$articles = $query->fetchAll(PDO::FETCH_ASSOC);




require_once("close.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <title>Historique des changements</title>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <?php if (isset($_SESSION["toast_message"]) && isset($_SESSION["toast_type"])) : ?>
        <script>
            // Affiche un toast avec un message
            document.addEventListener('DOMContentLoaded', function() {
                Toastify({
                    text: "<?php echo $_SESSION["toast_message"]; ?>",
                    duration: 3000,
                    destination: "https://github.com/apvarun/toastify-js",
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "center",
                    image: "center",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(45deg, #555, #333)",
                        borderRadius: "10px",
                        textAlign: "center",
                        display: "flex",
                        alignItems: "center",
                        justifyContent: "center",
                        border: "1px solid white",
                        opacity: "0.95"
                    },
                    onClick: function() {}
                }).showToast();
            });
        </script>
    <?php
        unset($_SESSION["toast_message"]);
        unset($_SESSION["toast_type"]);
    endif;
    ?>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <h1 class="text-4xl font-bold mt-8 text-gray-600 tracking-wide leading-tight border-b-4 border-gray-700 pb-2">Historique des ajouts</h1>

    <table class="bg-white shadow-md rounded-lg overflow-hidden my-10 mx-2">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left border-b-2 border-gray-800 font-bold uppercase tracking-wider text-gray-600">Objet</th>
                <th class="px-4 py-2 text-left border-b-2 border-gray-800 font-bold uppercase tracking-wider text-gray-600">Description</th>
                <th class="text-center px-4 py-2 border-b-2 border-gray-800 font-bold uppercase tracking-wider text-gray-600">Image</th>
                <th class="text-center px-4 py-2 border-b-2 border-gray-800 font-bold uppercase tracking-wider text-gray-600">Catégorie ID</th>
                <th class="text-center px-4 py-2 border-b-2 border-gray-800 font-bold uppercase tracking-wider text-gray-600">Modifier / Supprimer</th>
            </tr>


        </thead>
        <tbody>
            <?php foreach ($articles as $produit) { ?>
                <tr>
                    <td class="px-4 py-2 border-b-2 border-r-2 border-l-2 border-gray-800">
                        <span><?= $produit['objet'] ?></span>
                    </td>
                    <td class="px-4 py-2 border-b-2 border-r-2 border-l-2 border-gray-800">
                        <span><?= $produit['description'] ?></span>
                    </td>
                    <td class="text-center px-4 py-2 border-b-2 border-r-2 border-l-2 border-gray-800">
                    <img src="image/<?= $produit['image'] ?>" alt="Product Image" class="w-64 h-auto mt-1">
                    </td>
                    <td class="text-center px-4 py-2 border-b-2 border-r-2 border-l-2 border-gray-800">
                        <span><?= $produit['categorie_id'] ?></span>
                    </td>
                    <td class="text-center px-4 py-2 border-b-2 border-r-2 border-l-2 border-gray-800">
                        <a class="modify-link btn-modif text-blue-500 hover:underline transition duration-200 hover:bg-blue-400 hover:text-white rounded px-2 py-1 " href="modifier.php?id=<?= $produit['id'] ?>" onclick="modif(event)">Modifier</a>
                        <a class="delete-link btn-suppr text-red-500 hover:underline transition duration-200 hover:bg-red-400 hover:text-white rounded px-2 py-1" href="supprimer.php?id=<?= $produit['id'] ?>" onclick="supprimer(event)">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    <nav class="flex justify-center mt-4">
        <ul class="pagination flex items-center space-x-4">
            <li class="pagination-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
                <a href="./historique.php?page=<?php echo $currentPage - 1; ?>" class="pagination-link px-3 py-1.5 bg-gradient-to-r from-gray-300 to-gray-400 rounded text-gray-700 hover:text-white transition duration-200">&laquo; Précédente</a>
            </li>

            <?php for ($page = 1; $page <= $pages; $page++) { ?>
                <li class="pagination-item">
                    <a href="./historique.php?page=<?php echo $page; ?>" class="pagination-link <?php if ($currentPage == $page) echo 'active'; ?> text-blue-500 hover:text-blue-700 hover:underline transition duration-200"><?php echo $page; ?></a>
                </li>
            <?php } ?>

            <li class="pagination-item <?php if ($currentPage == $pages) echo 'disabled'; ?>">
                <a href="./historique.php?page=<?php echo $currentPage + 1; ?>" class="pagination-link px-3 py-1.5 bg-gradient-to-r from-gray-300 to-gray-400 rounded text-gray-700 hover:text-white transition duration-200">Suivante &raquo;</a>
            </li>
        </ul>
    </nav>

    <div class="flex justify-center mt-4">
        <a href="ajout.php" class=" mt-5 ml-6 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700 cursor-pointer">Ajouter</a>
        <a href="deconnexion.php" class=" mt-5 ml-6 bg-red-500 text-white py-2 px-4 rounded hover:bg-red-700 cursor-pointer">deconnexion</a>
        <a href="galerie.php" class=" mt-5 ml-6 bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700 cursor-pointer">galerie</a>
    </div>

    <br><br>

    <br>

    <script>
        // Fonction pour afficher une boîte de confirmation avant la modification
        function modif(event) {
            event.preventDefault();

            const confirmationBox = document.createElement('div');
            confirmationBox.className = 'confirmation pl-5 mt-3 bg-gradient-to-r from-gray-300 to-gray-400 rounded-lg text-gray-700 text-center flex items-center justify-center border-white opacity-95 transition duration-200';

            const message = document.createElement('p');
            message.textContent = 'Êtes-vous sûr de vouloir modifier cet élément ?';

            const confirmButton = document.createElement('button');
            confirmButton.textContent = 'Oui, modifier';
            confirmButton.className = 'ml-3 btn-confirm text-white font-semibold bg-blue-500 hover:bg-blue-700 px-4 py-2 rounded mr-2 transition duration-200';
            const cancelButton = document.createElement('button');
            cancelButton.textContent = 'Annuler';
            cancelButton.className = 'btn-cancel text-white-500 hover:text-gray-700 hover:underline  ml-2 hover:bg-gray-200 hover:text-gray-800 px-4 py-2 rounded mr-2 transition duration-200';

            confirmButton.addEventListener('click', function() {
                window.location.href = event.target.href;
                confirmationBox.remove();
            });

            cancelButton.addEventListener('click', function() {
                confirmationBox.remove();
            });

            confirmationBox.appendChild(message);
            confirmationBox.appendChild(confirmButton);
            confirmationBox.appendChild(cancelButton);
            document.body.prepend(confirmationBox);
        }

        // Fonction pour afficher une boîte de confirmation avant la suppression
        function supprimer(event) {
            event.preventDefault();

            const confirmationBox = document.createElement('div');
            confirmationBox.className = 'pl-5 confirmation mt-3 bg-gradient-to-r from-gray-300 to-gray-400 rounded-lg text-gray-700 text-center flex items-center justify-center border-white opacity-95 transition duration-200';

            const message = document.createElement('p');
            message.textContent = 'Êtes-vous sûr de vouloir supprimer cet élément ?';

            const confirmButton = document.createElement('button');
            confirmButton.textContent = 'Oui, supprimer';
            confirmButton.className = 'ml-3 btn-confirm text-white font-semibold bg-red-500 hover:bg-red-700 px-4 py-2 rounded mr-2 transition duration-200';
            const cancelButton = document.createElement('button');
            cancelButton.textContent = 'Annuler';
            cancelButton.className = 'btn-cancel text-white-500 hover:text-gray-700 hover:underline ml-2 hover:bg-gray-200 hover:text-gray-800 px-4 py-2 rounded mr-2 transition duration-200';

            confirmButton.addEventListener('click', function() {
                window.location.href = event.target.href;
                confirmationBox.remove();
            });

            cancelButton.addEventListener('click', function() {
                confirmationBox.remove();
            });

            confirmationBox.appendChild(message);
            confirmationBox.appendChild(confirmButton);
            confirmationBox.appendChild(cancelButton);
            document.body.prepend(confirmationBox);
        }
    </script>

    <?php require_once("close.php"); ?>
</body>

</html>