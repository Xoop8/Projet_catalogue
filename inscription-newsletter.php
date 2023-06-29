<?php
require_once "connect.php";

$error = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["email"])) {
        $nom = strip_tags($_POST["nom"]);
        $prenom = strip_tags($_POST["prenom"]);
        $email = strip_tags($_POST["email"]);

        if (!empty($nom) && !empty($prenom) && !empty($email)) {
            // Vérifier si l'email existe déjà
            $checkEmailQuery = $db->prepare("SELECT COUNT(*) FROM newsletter WHERE email = :email");
            $checkEmailQuery->bindValue(':email', $email);
            $checkEmailQuery->execute();
            $emailExists = ($checkEmailQuery->fetchColumn() > 0);

            if ($emailExists) {
                // L'email existe déjà, afficher un message d'erreur
                $error = "L'email est déjà utilisé.";
            } else {
                // L'email n'existe pas, effectuer l'insertion dans la base de données
                $insertQuery = $db->prepare("INSERT INTO newsletter (nom, prenom, email) VALUES (:nom, :prenom, :email)");
                $insertQuery->bindValue(":nom", $nom, PDO::PARAM_STR);
                $insertQuery->bindValue(":prenom", $prenom, PDO::PARAM_STR);
                $insertQuery->bindValue(":email", $email, PDO::PARAM_STR);

                $success = $insertQuery->execute();

                if (!$success) {
                    // Une erreur s'est produite lors de l'insertion
                    $error = "Une erreur s'est produite lors de l'insertion.";
                }
            }
        } else {
            // Un ou plusieurs champs sont vides
            $error = "Veuillez remplir tous les champs du formulaire.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head> 
    <link rel="stylesheet" href="newsletter.css">
</head>
<body>
    
    <?php if (!empty($error)) { ?>
        <p class="toast"><?php echo $error; ?></p>
    <?php } elseif ($success) { ?>
        <p class="toast">Inscription réussie !</p>
    <?php } ?>
    <h1>Je m'inscris </h1>
    <form method="POST" action="inscription-newsletter.php">
        
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required><br>

        <label for="email">Adresse e-mail :</label>
        <input type="email" name="email" required><br>

        <button type="submit">S'inscrire</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toast = document.querySelector('.toast');
            if (toast) {
                toast.classList.add('show');
                setTimeout(function() {
                    toast.classList.remove('show');
                }, 3000);
            }
        });
    </script>
    </form>
</body>
</html>
