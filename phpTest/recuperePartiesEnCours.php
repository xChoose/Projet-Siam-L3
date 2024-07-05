<?php
// Connexion à la base de données SQLite des parties en cours
$bdd = new SQLite3('../php/BaseDonnee/parties_en_cours.db');

// Vérifier la connexion
if (!$bdd) {
    die("Connexion à la base de données des parties en cours échouée.");
}

// Sélectionner toutes les parties en cours
$requete = "SELECT * FROM parties_en_cours";
$resultat = $bdd->query($requete);

// Afficher les données
while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {
    echo "ID: " . $row['id'] . "<br>";
    echo "Pseudo utilisateur: " . $row['pseudo_utilisateur'] . "<br>";
    echo "Pion choisi: " . $row['pion_choisi'] . "<br>";
    echo "Adversaire: " . $row['adversaire'] . "<br>";
    echo "Date de création: " . $row['date_creation'] . "<br>";
    echo "Dernière modification: " . $row['derniere_modification'] . "<br>";
    echo "Partie: " . $row['partie'] . "<br>";
    echo "Tour: " . $row['tour'] . "<br>";
    echo "PremierTour: " . $row['premierTour'] . "<br>";
    echo "<br>";
}

// Fermer la connexion à la base de données
$bdd->close();
?>
