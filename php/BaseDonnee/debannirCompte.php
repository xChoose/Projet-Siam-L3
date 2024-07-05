<?php
// Connexion à la base de données SQLite
$bdd = new SQLite3('ma_base_de_donnees.db');

// Vérifier la connexion
if (!$bdd) {
    die("Connexion à la base de données échouée.");
}

// Vérifier si le formulaire a été soumis etv le pseudo est présent
if (isset($_POST['pseudoDeban'])) {
    // Récupérer le pseudo depuis le formulaire
    $pseudo = $_POST['pseudoDeban'];

    // Afficher le pseudo pour débogage
    echo "Pseudo soumis via le formulaire : " . $pseudo;

    // Requête SQL pour débannir le compte
    $requeteBannir = "UPDATE utilisateurs SET ban = 'false' WHERE pseudo = :pseudo";

    // Préparation de la requête
    $statement = $bdd->prepare($requeteBannir);
    $statement->bindValue(':pseudo', $pseudo, SQLITE3_TEXT);

    // Exécution de la requête
    $resultat = $statement->execute();

    // Vérifier si la requête a été exécutée avec succès
    if ($resultat) {
        echo "Le compte avec le pseudo '$pseudo' a été débanni avec succès.";
        header("Location: acceuil.php");
        exit() ;
    } else {
        echo "Une erreur est survenue lors du débannissement du compte.";
    }
} else {
    // Redirection vers la page admin en cas d'accès direct à ce script
    echo "Le pseudo n'a pas été soumis via le formulaire.";
}

// Fermer la connexion à la base de données
$bdd->close();
?>
