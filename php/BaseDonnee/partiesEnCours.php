<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_pseudo'], $_SESSION['user_email'], $_SESSION['is_admin'])) {
    // Rediriger l'utilisateur s'il n'est pas connecté
    header("Location: ../../connexion.php");
    exit();
}

// Récupérer les informations de l'utilisateur depuis les variables de session
$pseudo = $_SESSION['user_pseudo'];
$is_admin = $_SESSION['is_admin'];

// Connexion à la base de données SQLite des parties en cours
$bdd = new SQLite3('parties_en_cours.db');

// Vérifier la connexion à la base de données
if (!$bdd) {
    die("Connexion à la base de données échouée.");
}

// Requête de base pour récupérer les parties en cours
$requetePartiesEnCours = "SELECT id, pseudo_utilisateur, pion_choisi, adversaire, date_creation FROM parties_en_cours WHERE ";

// Si l'utilisateur est un administrateur, sélectionnez toutes les parties
if ($is_admin) {
    $requetePartiesEnCours = $requetePartiesEnCours . "1=1";
} else {
    // Si l'utilisateur est un joueur, sélectionnez les parties qu'il a créées ou dans lesquelles il est adversaire
    $requetePartiesEnCours = $requetePartiesEnCours . "(pseudo_utilisateur = :pseudo OR adversaire = :pseudo)";
}

$statement = $bdd->prepare($requetePartiesEnCours);

// Lier le pseudo à la requête si l'utilisateur n'est pas un administrateur
if (!$is_admin) {
    $statement->bindValue(':pseudo', $pseudo, SQLITE3_TEXT);
}

$resultat = $statement->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Parties en cours</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header class="bg-primary text-white text-center py-4">
        <h1 class="mb-0">Parties en cours</h1>
    </header>

    <div class="container my-4">
        <div class="row">
            <?php if ($is_admin == 'true') : ?>
            <!-- Afficher seulement si l'utilisateur est administrateur -->
            <div class="col-md-6">
                <section class="parties bg-light p-4">
                    <h2 class="mb-4">Parties en cours pour tous les autres utilisateurs:</h2>
                    <ul class="list-unstyled">
                        <?php while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) : ?>
                            <?php if ($row['pseudo_utilisateur'] != 'admin') : ?>
                            <li class="border-bottom pb-3">
                                <h3 class="text-primary">Créateur de la partie : <?php echo $row['pseudo_utilisateur']; ?></h3>
                                <p> Id de la partie : <?php echo $row['id']; ?> </p>
                                <p>Pion choisi : <?php echo $row['pion_choisi']; ?></p>
                                <p>Adversaire : <?php echo $row['adversaire']; ?></p>
                                <p>Date de création : <?php echo $row['date_creation']; ?></p>
                                <form action="rejoindrePartie.php" method="POST" class="join-form">
                                    <input type="hidden" name="id_partie" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-primary mr-2">Rejoindre</button>
                                </form>

                              <!-- Ajout du bouton Supprimer -->
                              <form action="supprimerPartie.php" method="POST" class="delete-form">
                                  <input type="hidden" name="id_partie" value="<?php echo $row['id']; ?>">
                                  <button type="submit" class="btn btn-danger">Supprimer</button>
                              </form>
                            </li>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                </section>
            </div>
            <?php endif; ?>

            <div class="col-md-6">
                <section class="parties bg-light p-4">
                    <h2 class="mb-4">Parties en cours pour <?php echo $pseudo; ?>:</h2>
                    <ul class="list-unstyled">
                        <?php
                        // Réinitialiser le pointeur de résultat pour réutilisation
                        $resultat->reset();

                        // Afficher les parties en cours de l'utilisateur
                        while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) :
                        ?>
                        <?php if ($pseudo == $row['pseudo_utilisateur'] || $pseudo == $row['adversaire']) : ?>
                            <li class="border-bottom pb-3">
                                <h3 class="text-primary"> Créateur de la partie : <?php echo $row['pseudo_utilisateur']; ?></h3>
                                <p> Id de la partie : <?php echo $row['id']; ?> </p>
                                <p>Pion choisi : <?php echo $row['pion_choisi']; ?></p>
                                <p>Adversaire : <?php echo $row['adversaire']; ?></p>
                                <p>Date de création : <?php echo $row['date_creation']; ?></p>
                                <form action="rejoindrePartie.php" method="POST" class="join-form">
                                    <input type="hidden" name="id_partie" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-primary mr-2">Rejoindre</button>
                                </form>

                              <!-- Ajout du bouton Supprimer -->
                              <form action="supprimerPartie.php" method="POST" class="delete-form">
                                  <input type="hidden" name="id_partie" value="<?php echo $row['id']; ?>">
                                  <button type="submit" class="btn btn-danger">Supprimer</button>
                              </form>

                            </li>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                </section>
            </div>

        </div>
    </div>

    <footer class="bg-primary text-white text-center py-4">
        <a href="acceuil.php" class="btn btn-light">Retour à la page d'accueil</a>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$bdd->close();
?>
