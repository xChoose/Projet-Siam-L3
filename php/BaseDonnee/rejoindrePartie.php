<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_pseudo']) && isset($_SESSION['user_email']) && isset($_SESSION['is_admin'])) {
    // Rediriger l'utilisateur s'il n'est pas connecté
    header("Location: ../../connexion.php");
    exit();
}

// Vérifier si l'ID de la partie à rejoindre est présent dans la requête POST
if (isset($_POST['id_partie'])) {
    // Récupérer l'ID de la partie à rejoindre depuis la requête POST
    $id_partie = $_POST['id_partie'];

    // Connexion à la base de données pour récupérer les informations de la partie
    $bdd = new SQLite3('parties_en_cours.db');

    // Vérifier la connexion à la base de données
    if (!$bdd) {
        die("Connexion à la base de données échouée.");
    }

    // Requête pour récupérer les informations de la partie en fonction de l'ID fourni
    $requetePartie = "SELECT * FROM parties_en_cours WHERE id = :id_partie";
    $statement = $bdd->prepare($requetePartie);
    $statement->bindValue(':id_partie', $id_partie, SQLITE3_INTEGER);
    $resultat = $statement->execute();

    // Vérifier si la partie existe dans la base de données
    if ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {
        // Récupérer les informations de la partie
        $pion_choisi = $row['pion_choisi'];
        $adversaire = $row['adversaire'];
        $id_partie = $row['id'];
        // Fermer la connexion à la base de données
        $bdd->close();

        // Rediriger l'utilisateur vers la page de jeu avec les informations de la partie
        header("Location: index.php?idPartie=$id_partie");
        exit();
    } else {
        // Rediriger l'utilisateur vers une page d'erreur si la partie n'existe pas dans la base de données
        header("Location: erreur.php");
        exit();
    }
} else {
    // Rediriger l'utilisateur vers une page d'erreur si l'ID de la partie n'est pas fourni
    header("Location: erreur.php");
    exit();
}
?>
