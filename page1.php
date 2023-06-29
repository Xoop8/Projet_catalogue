<?php
include 'header.php';
require_once 'connect.php';

$sql = "SELECT * FROM produits 
        INNER JOIN commentaires ON produits.id_commentaires = commentaires.id";
$query = $db->query($sql);
$produits = $query->fetchAll(PDO::FETCH_ASSOC);

$truncatedDescription = "Description non disponible";
$ingredients = "Ingrédients non disponibles";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_produits = "SELECT * FROM produits WHERE id = :id";
    $query_produits = $db->prepare($sql_produits);
    $query_produits->bindParam(':id', $id);
    $query_produits->execute();
    $produit = $query_produits->fetch();

    if (isset($produit['description']) && isset($produit['ingredients'])) {
        $description = $produit['description'];
        $truncatedDescription = substr($description, 0, 400); // Tronque la description à 400 caractères
        $ingredients = $produit['ingredients'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {
    if (!empty($_POST["notes"]) && !empty($_POST["avis"])) {
        $notes = strip_tags($_POST["notes"]);
        $avis = strip_tags($_POST["avis"]);
        $id_produit = $produit['id']; // Récupère l'ID du produit associé au commentaire
        $sql = "INSERT INTO commentaires (id_produit, notes, avis) VALUES(:id_produit, :notes, :avis)";
        $query = $db->prepare($sql);
        $query->bindValue(":id_produit", $id_produit, PDO::PARAM_INT);
        $query->bindValue(":notes", $notes, PDO::PARAM_INT);
        $query->bindValue(":avis", $avis, PDO::PARAM_STR);
        $query->execute();
    }
}

// Récupérer tous les commentaires et les regrouper par produit
$sql_commentaires = "SELECT * FROM commentaires WHERE id_produit = :id_produit";
$query_commentaires = $db->prepare($sql_commentaires);
$query_commentaires->bindParam(':id_produit', $id);
$query_commentaires->execute();
$commentaires_list = $query_commentaires->fetchAll(PDO::FETCH_ASSOC);

// Pagination des commentaires
$commentairesParPage = 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $commentairesParPage;

$commentairesAffiches = array_slice($commentaires_list, $offset, $commentairesParPage);
$nombreDePages = ceil(count($commentaires_list) / $commentairesParPage);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_page1.css">
    <title><?= $produit['objet'] ?></title>
</head>

<body>
    <div class="card-container">
        <div class="card">
            <div class="card-image" id="productImage">
                <a href="image/<?php echo $produit['image']; ?>" data-lightbox="gallery">
                    <img src="image/<?php echo $produit['image']; ?>" alt="parfum rosalie">
                </a>
            </div>
        </div>

        <div class="slide-content">
            <div class="card-description">
                <h2>Description</h2>
                <p class="truncated-text"><?= $truncatedDescription ?></p>
                <a href="#" id="showMore">Afficher plus</a>
                <p class="full-text"><?= $description ?></p>
            </div>

            <div class="card-description">
                <h2>Ingrédients</h2>
                <p><?= $ingredients ?></p>
            </div>
        </div>




        <h2 class="aviis">AVIS</h2>
        <div class="card-notes">
            <?php if (isset($produit['id'])) : ?>
                <?php if (isset($commentairesAffiches)) : ?>
                    <div class="comment-columns">
                        <?php foreach ($commentairesAffiches as $commentaire) : ?>


                            <!-- si besoin de recupere ip pour evite les spam d avis -->
                            <!--  ($commentaire['ip'] == $_SERVER['REMOTE_ADDR']) : -->

                            <div class="comment-bubble">
                                <p>
                                    <?php for ($i = 1; $i <= $commentaire['notes']; $i++) : ?>
                                        &#x2B50;
                                    <?php endfor; ?>
                                </p>
                                <p><?= $commentaire['avis'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p>Aucun commentaire disponible pour ce produit.</p>
                <?php endif; ?>
            <?php else : ?>
                <p>Veuillez sélectionner un produit pour afficher les commentaires.</p>
            <?php endif; ?>

            <!-- Pagination -->
            <?php if ($nombreDePages > 1) : ?>
                <div class="pagination">
                    <?php if ($page > 1) : ?>
                        <a href="?id=<?= $id ?>&page=<?= $page - 1 ?>">Précédent</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $nombreDePages; $i++) : ?>
                        <?php if ($i == $page) : ?>
                            <span class="current-page"><?= $i ?></span>
                        <?php else : ?>
                            <a href="?id=<?= $id ?>&page=<?= $i ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $nombreDePages) : ?>
                        <a href="?id=<?= $id ?>&page=<?= $page + 1 ?>">Suivant</a>
                    <?php endif; ?>
                </div>


            <?php endif; ?>
        </div>

        <div class="notes-contenaire">
            <div class="notes">
                <form method="post" action="">
                    <div class="stars" id="starsContainer">
                        <span class="star" data-value="1">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" id="rating" name="notes">
            </div>
            <div class="avis">
                <input type="text" name="avis" placeholder="Donnez nous votre Avis &#128512 ! ">
                <input type="submit" name="submit" value="Envoyer">
            </div>
            </form>
        </div>


        <script>
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('rating');

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const value = star.getAttribute('data-value');
                    ratingInput.value = value;
                    stars.forEach(star => star.classList.remove('active'));
                    star.classList.add('active');
                });
            });
        </script>


        <!-- Toast -->
        <div class="toast" id="newsletter-toast">
            <span>Inscrivez-vous pour profiter de -20% lors de notre event ainsi que pleins d'autres surprises ! <a href="inscription-newsletter.php">S'inscrire</a></span>
            <button onclick="hideToast()">Fermer</button>
        </div>

        <!-- Script pour afficher/masquer le toast -->
        <script>
            function showToast() {
                var toast = document.getElementById('newsletter-toast');
                toast.classList.add('show');
            }

            function hideToast() {
                var toast = document.getElementById('newsletter-toast');
                toast.classList.remove('show');
            }

            // Afficher le toast après un certain délai (par exemple, 5 secondes)
            setTimeout(showToast, 5000);

            window.addEventListener('click', function(event) {
                var toast = document.getElementById('newsletter-toast');
                if (event.target !== toast && !toast.contains(event.target)) {
                    hideToast();
                }
            });
        </script>
        <script>
            const showMoreButton = document.getElementById('showMore');
            const slideContent = document.querySelector('.slide-content');
            const productImage = document.getElementById('productImage');
            const starsContainer = document.getElementById('starsContainer');

            showMoreButton.addEventListener('click', function(event) {
                event.preventDefault();
                slideContent.classList.toggle('expanded');
                productImage.style.display = slideContent.classList.contains('expanded') ? 'none' : 'block';
                starsContainer.style.top = slideContent.classList.contains('expanded') ? '260px' : '180px';

                if (slideContent.classList.contains('expanded')) {
                    showMoreButton.textContent = 'Afficher moins';
                    showMoreButton.style.color = '#dc3545';
                    document.querySelector('.avis').style.marginTop = '80px';
                } else {
                    showMoreButton.textContent = 'Afficher plus';
                    showMoreButton.style.color = '#007bff';
                    document.querySelector('.avis').style.marginTop = '0';
                }
            });
        </script>

</body>
<?php include 'footer.php'; ?>

</html>