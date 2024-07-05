<?php
// Vérifier si l'utilisateur est connecté en tant qu'administrateur
session_start();
if (!isset($_SESSION['user_pseudo']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 'true') {
    // Rediriger l'utilisateur s'il n'est pas connecté en tant qu'administrateur
    header("Location: ../../connexion.php");
    exit();
}

// Initialiser la variable du message d'erreur
$messageErreur = "";

// Connexion à la base de données SQLite
$bdd = new SQLite3('ma_base_de_donnees.db');

// Vérifier la connexion
if (!$bdd) {
    die("Connexion à la base de données échouée.");
}

// Vérifier si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe']; // Hasher le mot de passe pour des raisons de sécurité dans une application réelle
    $ban = "false";
    $isConnected = "false";

    // Déterminer si l'utilisateur est administrateur
    $isAdmin = "false";

    // Vérifier si le mot de passe contient des caractères "<" ou ">"
    if (strpos($mot_de_passe, '<') !== false || strpos($mot_de_passe, '>') !== false) {
        $messageErreur = "Le mot de passe ne peut pas contenir les caractères '<' ou '>'.";
    } else {
        // Vérifier si le compte existe déjà
        $requeteVerification = "SELECT COUNT(*) AS count FROM utilisateurs WHERE pseudo = :pseudo OR email = :email";
        $statement = $bdd->prepare($requeteVerification);
        $statement->bindValue(':pseudo', $pseudo, SQLITE3_TEXT);
        $statement->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $statement->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if ($row['count'] > 0) {
            // Mettre à jour le message d'erreur
            $messageErreur = "Ce compte existe déjà.";
        } else {
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
            } else {
                // Redirection vers la page d'accueil ou une autre page appropriée après l'ajout avec succès
                header("Location: acceuil.php");
                exit();
            }
        }
    }
}

// Fermer la connexion à la base de données
$bdd->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un profil joueur</title>
    <link rel="stylesheet" type="text/css" href="../../css/creerCompteJoueur.css">
</head>
<body>
<div class="container">
    <h1>Créer un profil joueur</h1>
    <?php if (!empty($messageErreur)) : ?>
        <p style="color: red;"><?php echo $messageErreur; ?></p>
    <?php endif; ?>
    <p>Veuillez créer un profil joueur pour lui permettre de jouer au jeu Siam.</p>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="pseudo">Pseudonyme :</label>
        <input type="text" id="pseudo" name="pseudo" required> <br>
        <label for="email">E-mail :</label>
        <input type="email" id="email" name="email" required> <br>
        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required> <br>
        <input type="submit" value="Créer un profil joueur"> <br>
    </form>
</div>
<div class="container"> 
    <a href="acceuil.php" class="btn-back">Retour à la page d'accueil</a>
</div> 
</body>
</html>
