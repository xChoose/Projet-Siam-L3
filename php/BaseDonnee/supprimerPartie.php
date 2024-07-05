<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_pseudo'], $_SESSION['user_email'], $_SESSION['is_admin'])) {
    // Rediriger l'utilisateur s'il n'est pas connecté
    header("Location: ../../connexion.php");
    exit();
}

// Vérifier si l'identifiant de la partie à supprimer est présent dans la requête
if (isset($_POST['id_partie'])) {
    // Récupérer l'identifiant de la partie depuis la requête
    $id_partie = $_POST['id_partie'];

    // Connexion à la base de données SQLite des parties en cours
    $bdd = new SQLite3('parties_en_cours.db');

    // Vérifier la connexion à la base de données
    if (!$bdd) {
        die("Connexion à la base de données échouée.");
    }

    // Requête pour supprimer la partie en cours
    $requeteSuppressionPartie = "DELETE FROM parties_en_cours WHERE id = :id_partie";
    $statement = $bdd->prepare($requeteSuppressionPartie);
    $statement->bindValue(':id_partie', $id_partie, SQLITE3_INTEGER);
    $resultat = $statement->execute();

    // Redirection vers la page des parties en cours après suppression
    header("Location: partiesEnCours.php");
    exit();
} else {
    // Redirection vers la page des parties en cours si l'identifiant de la partie n'est pas présent
    header("Location: partiesEnCours.php");
    exit();
}
