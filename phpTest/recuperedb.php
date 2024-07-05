<?php
// Connexion à la base de données SQLite
$bdd = new SQLite3('../php/BaseDonnee/ma_base_de_donnees.db');

// Vérifier la connexion
if (!$bdd) {
    die("Connexion à la base de données échouée.");
}

// Récupérer les données de la table utilisateurs
$requeteSelection = "SELECT * FROM utilisateurs";
$resultat = $bdd->query($requeteSelection);

// Vérifier si la requête a retourné des résultats
if ($resultat) {
    // Affichage des données dans un tableau HTML
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Pseudo</th><th>Email</th><th>Mot de passe</th><th>Banni(e)</th><th>isAdmin</th><th>isConnected</th></tr>";
    while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['pseudo'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['mot_de_passe'] . "</td>";
        echo "<td>" . $row['ban'] . "</td>";
        echo "<td>" . $row['isAdmin'] . "</td>" ;
        echo "<td>" . $row['isConnected'] . "</td>" ;
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Aucune donnée à afficher.";
}


// Fermer la connexion à la base de données
$bdd->close();
?>
