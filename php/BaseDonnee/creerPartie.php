<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_pseudo'], $_SESSION['user_email'], $_SESSION['is_admin'])) {
    // Récupérer les informations de l'utilisateur depuis les variables de session
    $pseudo = $_SESSION['user_pseudo'];
    $email = $_SESSION['user_email'];
    $is_admin = $_SESSION['is_admin'];
} else {
    // Rediriger l'utilisateur s'il n'est pas connecté
    header("Location: ../../connexion.php");
    exit();
}


// Connexion à la base de données SQLite des utilisateurs
$bdd = new SQLite3('ma_base_de_donnees.db');

// Vérifier la connexion
if (!$bdd) {
    die("Connexion à la base de données échouée.");
}

// Récupérer les autres utilisateurs à l'exception de l'utilisateur connecté
$requeteAutresUtilisateurs = "SELECT pseudo, email FROM utilisateurs WHERE pseudo != :pseudo";
$statement = $bdd->prepare($requeteAutresUtilisateurs);
$statement->bindValue(':pseudo', $_SESSION['user_pseudo'], SQLITE3_TEXT);
$resultat = $statement->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choix des pions</title>
    <!-- Inclure Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Ajoutez vos styles personnalisés ici si nécessaire */
    </style>
</head>
<body>
<div class="container">
    <h2 class="mt-3">Choix des pions</h2>
    <p>Bienvenue, <?php echo $_SESSION['user_pseudo']; ?>!</p>
    <p>Veuillez choisir votre pion :</p>
    <form method="post" action="traitementChoix.php">
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="elephant" name="pion" value="elephant" required>
                <label class="form-check-label" for="elephant">Éléphant</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="rhinoceros" name="pion" value="rhinoceros" required>
                <label class="form-check-label" for="rhinoceros">Rhinocéros</label>
            </div>
        </div>
        <div class="mb-3">
            <label for="adversaire" class="form-label">Pseudo de l'adversaire :</label>
            <input type="text" class="form-control" id="adversaire" name="adversaire" placeholder="Entrez le pseudo de l'adversaire" required>
        </div>
        <button type="submit" class="btn btn-primary">Choisir</button>
    </form>
    <!-- Bouton pour revenir à l'accueil -->
<div class="container text-center mt-3">
    <a href="acceuil.php" class="btn btn-primary">Retour à la page d'accueil</a>
</div>

    <?php if (isset($erreur)) : ?>
        <div class="alert alert-danger mt-3" role="alert"><?php echo $erreur; ?></div>
    <?php endif; ?>

    <h3 class="mt-5">Autres utilisateurs</h3>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Pseudo</th>
            <th scope="col">Email</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) : ?>
            <tr>
                <td><?php echo $row['pseudo']; ?></td>
                <td><?php echo $row['email']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>



<!-- Inclure Bootstrap JS (optionnel) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
