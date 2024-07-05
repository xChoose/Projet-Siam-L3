<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_pseudo'], $_SESSION['user_email'], $_SESSION['is_admin']))
    {
    // Rediriger l'utilisateur s'il n'est pas connecté
    header("Location: ../../connexion.php");
    exit();
}

// Définition du tableau map en PHP
$map = array(
    array(array(-1, 0), array("E", 2), array("E", 2), array("E", 2), array("E", 2), array("E", 2), array(-1, 0)),
    array(array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0)),
    array(array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0)),
    array(array("V", 0), array("V", 0), array("O", 0), array("O", 0), array("O", 0), array("V", 0), array("V", 0)),
    array(array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0)),
    array(array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0), array("V", 0)),
    array(array(-1, 0), array("R", 0), array("R", 0), array("R", 0), array("R", 0), array("R", 0), array(-1, 0))
);

// Encodage du tableau en JSON
$mapJSON = json_encode($map);

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données SQLite des parties en cours
    $bdd = new SQLite3('parties_en_cours.db');
    // Connexion à la base de données SQLite des utilisateurs
    $bdd2 = new SQLite3('ma_base_de_donnees.db');

    // Vérifier si la connexion a échoué
    if (!$bdd || !$bdd2) {
        die("Connexion à l'une des bases de données échouée.");
    }

    // Créer la table des parties en cours si elle n'existe pas
   // Créer la table des parties en cours si elle n'existe pas
    $requeteCreationTable = "CREATE TABLE IF NOT EXISTS parties_en_cours (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        pseudo_utilisateur TEXT NOT NULL,
        pion_choisi TEXT NOT NULL,
        adversaire TEXT,
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        derniere_modification DATETIME DEFAULT CURRENT_TIMESTAMP,
        partie TEXT NOT NULL,
        tour BOOLEAN CHECK(tour IN (0, 1)),
        premierTour BOOLEAN DEFAULT 1 CHECK(premierTour IN (0, 1))
    )";

    if (!$bdd->exec($requeteCreationTable)) {
        die("Erreur lors de la création de la table parties_en_cours : " . $bdd->lastErrorMsg());
    }

    // Vérifier si l'utilisateur a choisi un adversaire via le champ de saisie
    if (!empty($_POST['adversaire'])) {
        $adversaire = $_POST['adversaire'];

        // Vérifier si le pseudo de l'adversaire existe dans la base de données des utilisateurs
        $requeteVerifPseudo = "SELECT COUNT(*) AS count FROM utilisateurs WHERE pseudo = :pseudo";
        $statement2 = $bdd2->prepare($requeteVerifPseudo); // Utiliser $bdd2 pour accéder à la base de données des utilisateurs
        $statement2->bindValue(':pseudo', $adversaire, SQLITE3_TEXT);
        $resultat2 = $statement2->execute();
        $row2 = $resultat2->fetchArray(SQLITE3_ASSOC);

        // Si le pseudo de l'adversaire n'existe pas, enregistrer le message d'erreur dans la session et rediriger
        if ($row2['count'] == 0) {
            $_SESSION['erreur'] = "Le pseudo de l'adversaire n'existe pas dans la base de données des utilisateurs.";
            header("Location: creerPartie.php");
            exit();
        }
    } else {
        $adversaire = null;
    }

    // Récupérer les autres données du formulaire
    $pseudo_utilisateur = $_SESSION['user_pseudo'];
    $pion_choisi = isset($_POST['pion']) ? $_POST['pion'] : null;
    if ($pion_choisi == "elephant") {
        $tour = 1;
    } else {
        $tour = 0;
    }

    // Insertion des données dans la table des parties en cours
   // Insertion des données dans la table des parties en cours
$requeteInsertion = "INSERT INTO parties_en_cours (pseudo_utilisateur, pion_choisi, adversaire, partie, tour) VALUES (:pseudo_utilisateur, :pion_choisi, :adversaire, :partie, :tour)";
$statement = $bdd->prepare($requeteInsertion);
$statement->bindValue(':pseudo_utilisateur', $pseudo_utilisateur, SQLITE3_TEXT);
$statement->bindValue(':pion_choisi', $pion_choisi, SQLITE3_TEXT);
$statement->bindValue(':adversaire', $adversaire, SQLITE3_TEXT);
$statement->bindValue(':partie', $mapJSON, SQLITE3_TEXT);
$statement->bindValue(':tour', $tour, SQLITE3_INTEGER);
if (!$statement->execute()) {
    // En cas d'erreur, récupérer l'ID de la dernière ligne insérée
    $requeteLastInsertId = "SELECT last_insert_rowid() AS last_id;";
    $resultat = $bdd->query($requeteLastInsertId);
    $dernierIdInsere = $resultat->fetchArray(SQLITE3_ASSOC)['last_id'];
    die("Erreur lors de l'insertion des données : " . $bdd->lastErrorMsg());
} else {
    // Si l'insertion réussit, récupérer l'ID de la dernière ligne insérée
    $requeteLastInsertId = "SELECT last_insert_rowid() AS last_id;";
    $resultat = $bdd->query($requeteLastInsertId);
    $dernierIdInsere = $resultat->fetchArray(SQLITE3_ASSOC)['last_id'];
}

// Fermer la connexion à la base de données
$bdd->close();
$bdd2->close();

// Redirection vers index.html
header("Location: partiesEnCours.php");
exit();

}
?>
