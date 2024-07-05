<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_email'])) {
    // Connexion à la base de données SQLite
    $bdd = new SQLite3('ma_base_de_donnees.db');

    // Vérifier la connexion
    if (!$bdd) {
        die("Connexion à la base de données échouée.");
    }

    // Récupérer l'e-mail de l'utilisateur connecté
    $email = $_SESSION['user_email'];

    // Requête pour mettre à jour isConnected à false
    $requeteUpdateIsConnected = "UPDATE utilisateurs SET isConnected = 'false' WHERE email = :email";
    $statement = $bdd->prepare($requeteUpdateIsConnected);
    $statement->bindValue(':email', $email, SQLITE3_TEXT);
    $resultat = $statement->execute();

    // Vérifier si la mise à jour a réussi
    if ($resultat) {
        // Détruire toutes les données de la session
        $_SESSION = array();

        // Détruire la session
        session_destroy();

        // Rediriger vers la page de connexion
        header("Location: ../../connexion.php");
        exit();
    } else {
        // Erreur lors de la mise à jour de la colonne isConnected
        die("Erreur lors de la mise à jour de la colonne isConnected.");
    }

    // Fermer la connexion à la base de données
    $bdd->close();
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../../connexion.php");
    exit();
}
?>
