<?php
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    // Connexion à la base de données
    $db_username = 'root';
    $db_password = '';
    $db_name = 'catalogue';
    $db_host = 'localhost';
    $db = mysqli_connect($db_host, $db_username, $db_password, $db_name)
        or die('Could not connect to database');

    // Échapper les valeurs des champs de saisie
    $username = mysqli_real_escape_string($db, htmlspecialchars($_POST['username']));
    $password = mysqli_real_escape_string($db, htmlspecialchars($_POST['password']));

    if ($username !== "" && $password !== "") {
        $query = "SELECT * FROM users WHERE username = '".$username."'";
        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $hashedPassword = $row['password'];

            // Vérifier le mot de passe haché
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['username'] = $username;
                header('Location: historique.php');
                exit;
            }
        }

        // Redirection en cas d'échec de connexion
        header('Location: login.php?erreur=1'); // Utilisateur ou mot de passe incorrect
        exit;
    } else {
        header('Location: login.php?erreur=2'); // Utilisateur ou mot de passe vide
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}

mysqli_close($db); // Fermer la connexion

?>