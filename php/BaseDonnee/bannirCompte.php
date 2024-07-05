<?php
// Connexion à la base de données SQLite
$bdd = new SQLite3('ma_base_de_donnees.db');

// Vérifier la connexion
if (!$bdd) {
    die("Connexion à la base de données échouée.");
}

// Vérifier si le formulaire a été soumis et le pseudo est présent
if (isset($_POST['pseudoBan'])) {
    // Récupérer le pseudo depuis le formulaire
    $pseudo = $_POST['pseudoBan'];

    // Requête SQL pour bannir le compte
    $requeteBannir = "UPDATE utilisateurs SET ban = 'true' WHERE pseudo = :pseudo";

    // Préparation de la requête
    $statement = $bdd->prepare($requeteBannir);
    $statement->bindValue(':pseudo', $pseudo, SQLITE3_TEXT);

    // Exécution de la requête
    $resultat = $statement->execute();

    // Vérifier si la requête a été exécutée avec succès
    if ($resultat) {
        echo "Le compte avec le pseudo '$pseudo' a été banni avec succès.";
        // Redirection vers la page acceuil.php après le bannissement
        header("Location: acceuil.php");
        exit();
    } else {
        echo "Une erreur est survenue lors du bannissement du compte.";
    }

} else {
    // Redirection vers la page admin en cas d'accès direct à ce script
    header("Location: acceuil.php");
    exit();
}

// Fermer la connexion à la base de données
$bdd->close();
?>
