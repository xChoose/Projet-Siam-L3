<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_pseudo'], $_SESSION['user_email'], $_SESSION['is_admin'])) {
    // Rediriger l'utilisateur s'il n'est pas connecté
    header("Location: connexion.php");
    exit();
}

// Récupérer les informations de la partie
$pseudo = $_SESSION['user_pseudo'];
$map = $_POST['map'];
$id_partie = $_POST['idPartie'];
$id_partie = json_decode($id_partie);
$id_partie = intval($id_partie);
$adversaire = $_POST['adversaire'];
$adversaireDecod = json_decode($adversaire); 
$tour = $_POST['tour'];
$tour = json_decode($tour);
$tour = intval($tour);
$date_creation = date('Y-m-d H:i:s');

// Connexion à la base de données SQLite des parties en cours
$bdd = new SQLite3('../BaseDonnee/parties_en_cours.db');

// Vérifier la connexion à la base de données
if (!$bdd) {
    die("Connexion à la base de données échouée.");
}

// Vérifier si la partie existe déjà dans la base de données
$requeteVerifPartie = "SELECT * FROM parties_en_cours WHERE pseudo_utilisateur = :pseudo AND adversaire = :adversaire";
$statement = $bdd->prepare($requeteVerifPartie);
$statement->bindValue(':pseudo', $pseudo, SQLITE3_TEXT);
$statement->bindValue(':adversaire', $adversaireDecod, SQLITE3_TEXT);

$resultat = $statement->execute();
$partie_existe = $resultat->fetchArray(SQLITE3_ASSOC);


$derniere_modification = date('Y-m-d H:i:s');
// La partie existe déjà, mettre à jour le temps de sauvegarde
$requeteMajPartie = "UPDATE parties_en_cours SET derniere_modification = :derniere_modification, partie = :partie, tour = :tour, premierTour = :premierTour WHERE id = :id_partie";
$statement = $bdd->prepare($requeteMajPartie);
$statement->bindValue(':derniere_modification', $derniere_modification, SQLITE3_TEXT);
$statement->bindValue(':partie', $map, SQLITE3_TEXT);
$statement->bindValue(':id_partie', $id_partie, SQLITE3_INTEGER);
$statement->bindValue(':tour', $tour, SQLITE3_INTEGER);
$statement->bindValue(':premierTour', 0, SQLITE3_INTEGER);
$statement->execute();

echo "La partie a été mise à jour avec succès.";


// Fermer la connexion à la base de données
$bdd->close();
?>
