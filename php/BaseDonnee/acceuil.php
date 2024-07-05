<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_pseudo'], $_SESSION['user_email'], $_SESSION['is_admin'])) {
    // Récupérer les informations de l'utilisateur depuis les variables de session
    $pseudo = $_SESSION['user_pseudo'];
    $email = $_SESSION['user_email'];
    $is_admin = $_SESSION['is_admin'];
} else {
    // Rediriger l'utilisateur s'il n'est pas connecté
    header("Location: ../../connexion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Jeu Siam</title>
    <link rel="stylesheet" href="../../css/acceuil.css">
</head>
<body>
<header>
    <h1>Bienvenue sur le jeu Siam</h1>
    <nav>
        <ul>
            <li><a href="acceuil.php">Accueil</a></li>
            <li><a href="creerPartie.php">Créer une partie</a></li>
            <li><a href="partiesEnCours.php">Parties en cours</a></li>
            <li><a href="recupereUtilisateurs.php">Utilisateurs</a></li>
             <?php
                // Afficher un message si l'utilisateur est administrateur
                if ($is_admin === 'true') {
                    echo "<li><a href='creerCompteJoueur.php'> Créer un compte joueur</a></li>";
                    echo "<li><a href='pageBanAdmin.html'>Bannir/Débannir des utilisateurs</a></li>";
                }
             ?>
            <!-- Lien de déconnexion -->
            <li><a href="deconnexion.php">Déconnexion</a></li>
        </ul>
    </nav>
</header>
<main>
    <section class="user-info">
        <h2>Informations sur l'utilisateur</h2>
        <p>Pseudo : <?php echo $pseudo; ?></p>
        <p>Email : <?php echo $email; ?></p>
        <?php
        // Afficher un message si l'utilisateur est administrateur
        if ($is_admin === 'true') {
            echo "<p>(Vous êtes administrateur)</p>";
        }
        ?>
    </section>
<br>
    <section class="game-info">
        <h2>Présentation du jeu Siam</h2>
        <p>Siam est un jeu de société pour deux joueurs qui se joue sur un plateau représentant un terrain de jeu de cinq par cinq cases. Le jeu se déroule dans l'univers de la jungle où s'affrontent des éléphants et des rhinocéros pour contrôler les blocs de pierre.</p>
    </section>
    <br>
    <section class="game-regles">
        <h2> Règles du jeu </h2> 
        <p>Chaque joueur contrôle cinq éléphants ou cinq rhinocéros. L'objectif est de pousser les blocs de pierre situés au centre du plateau vers les bords, en utilisant les mouvements de ses pièces pour bloquer ou repousser les pièces adverses.</p>
        <p>Le joueur qui réussit à sortir son bloc de pierre à l'extérieur du plateau gagne la partie.</p>
    </section>  
</main>
<footer>
</footer>
</body>
</html>
