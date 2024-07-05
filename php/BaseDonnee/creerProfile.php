<?php
// Connexion à la base de données SQLite
$bdd = new SQLite3('ma_base_de_donnees.db');

// Vérifier la connexion
if (!$bdd) {
    die("Connexion à la base de données échouée.");
}

// Création de la table utilisateurs si elle n'existe pas
$requeteCreationTable = "CREATE TABLE IF NOT EXISTS utilisateurs (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            pseudo TEXT NOT NULL,
                            email TEXT NOT NULL,
                            mot_de_passe TEXT NOT NULL,
                            ban TEXT NOT NULL,
                            isAdmin TEXT NOT NULL,
                            isConnected TEXT NOT NULL
                        )";

if (!$bdd->exec($requeteCreationTable)) {
    die("Erreur lors de la création de la table utilisateurs : " . $bdd->lastErrorMsg());
}

// Vérifier si l'administrateur par défaut existe déjà
$requeteVerificationAdmin = "SELECT COUNT(*) AS count FROM utilisateurs WHERE isAdmin = 'true'";
$resultAdmin = $bdd->query($requeteVerificationAdmin);
$rowAdmin = $resultAdmin->fetchArray(SQLITE3_ASSOC);

// Si l'administrateur n'existe pas, l'insérer
if ($rowAdmin['count'] == 0) {
    $requeteDefautAdmin = "INSERT INTO utilisateurs (pseudo, email, mot_de_passe, ban, isAdmin, isConnected) VALUES ('admin', 'admin@admin.com', 'admin','false', 'true', 'false')";
    if (!$bdd->exec($requeteDefautAdmin)) {
        die("Erreur lors de l'insertion de l'administrateur par défaut : " . $bdd->lastErrorMsg());
    }
}

// Récupérer les données du formulaire
$pseudo = $_POST['pseudo'];
$email = $_POST['email'];
$mot_de_passe = $_POST['mot_de_passe']; // Hasher le mot de passe pour des raisons de sécurité dans une application réelle
$ban = "false";
$isConnected = "false";

// Déterminer si l'utilisateur est administrateur
if ($pseudo === "admin") {
    $isAdmin = "true";
} else {
    $isAdmin = "false";
}

// Vérifier que le mot de passe ne contient pas les caractères < et >
if (strpos($mot_de_passe, '<') !== false || strpos($mot_de_passe, '>') !== false) {
    $_SESSION['erreur'] = "Le mot de passe ne peut pas contenir les caractères < et >.";
    header("Location: ../../inscription.php");
    exit();
}

// Vérifier si le compte existe déjà
$requeteVerification = "SELECT COUNT(*) AS count FROM utilisateurs WHERE pseudo = :pseudo OR email = :email";
$statement = $bdd->prepare($requeteVerification);
$statement->bindValue(':pseudo', $pseudo, SQLITE3_TEXT);
$statement->bindValue(':email', $email, SQLITE3_TEXT);
$result = $statement->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);
if ($row['count'] > 0) {
    echo "Ce compte existe déjà.";
    $bdd->close();
    header("Location: ../../connexion.php"); // Redirection vers la page de connexion
    exit;
}

// Insertion des données dans la table utilisateurs
$requeteInsertion = "INSERT INTO utilisateurs (pseudo, email, mot_de_passe, ban, isAdmin, isConnected) VALUES (:pseudo, :email, :mot_de_passe, :ban, :isAdmin, :isConnected)";
$statement = $bdd->prepare($requeteInsertion);
$statement->bindValue(':pseudo', $pseudo, SQLITE3_TEXT);
$statement->bindValue(':email', $email, SQLITE3_TEXT);
$statement->bindValue(':mot_de_passe', $mot_de_passe, SQLITE3_TEXT); // Hasher le mot de passe pour des raisons de sécurité dans une application réelle
$statement->bindValue(':ban', $ban, SQLITE3_TEXT); // Utilisation de SQLITE3_TEXT pour spécifier le type de données
$statement->bindValue(':isAdmin', $isAdmin, SQLITE3_TEXT); // Définir le statut isAdmin
$statement->bindValue(':isConnected', $isConnected, SQLITE3_TEXT);  // Définir si l'utilisateur est connecté ou pas (par défaut, il ne l'est pas)
if (!$statement->execute()) {
    die("Erreur lors de l'insertion des données : " . $bdd->lastErrorMsg());
}

// Fermer la connexion à la base de données
$bdd->close();

// Redirection vers la page de connexion
header("Location: ../../connexion.php");
exit();
?>
