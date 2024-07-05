<?php
session_start(); // Démarrer la session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les données du formulaire existent
    if (isset($_POST['email']) && isset($_POST['mot_de_passe'])) {
        // Connexion à la base de données SQLite
        $bdd = new SQLite3('ma_base_de_donnees.db');

        // Vérifier la connexion
        if (!$bdd) {
            die("Connexion à la base de données échouée.");
        }

        // Récupérer les données du formulaire
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe']; // Le mot de passe devrait être haché dans une application réelle pour comparaison sécurisée

        // Requête pour vérifier si le compte existe et n'est pas banni
        $requeteVerification = "SELECT * FROM utilisateurs WHERE email = :email AND mot_de_passe = :mot_de_passe";
        $statement = $bdd->prepare($requeteVerification);
        $statement->bindValue(':email', $email, SQLITE3_TEXT);
        $statement->bindValue(':mot_de_passe', $mot_de_passe, SQLITE3_TEXT); // Le mot de passe devrait être haché dans une application réelle pour comparaison sécurisée
        $resultat = $statement->execute();

        // Vérifier si le compte existe et n'est pas banni
        if ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {
            if ($row['ban'] == 'false') {
                // Mise à jour de la colonne isConnected
                $requeteUpdateIsConnected = "UPDATE utilisateurs SET isConnected = 'true' WHERE email = :email";
                $statementUpdate = $bdd->prepare($requeteUpdateIsConnected);
                $statementUpdate->bindValue(':email', $email, SQLITE3_TEXT);
                $resultatUpdate = $statementUpdate->execute();

                if ($resultatUpdate) {
                    // Connexion réussie
                    $_SESSION['user_pseudo'] = $row['pseudo'];
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['is_admin'] = $row['isAdmin']; // Assurez-vous de récupérer correctement cette valeur depuis la base de données

                    // Redirection vers la page d'accueil si la connexion réussit
                    header("Location: acceuil.php");
                    exit();
                } else {
                    // Erreur lors de la mise à jour de la colonne isConnected
                    die("Erreur lors de la mise à jour de la colonne isConnected.");
                }
            } else {
                // Redirection vers la page ban.html si le compte est banni
                header("Location: infoban.html");
                exit();
            }
        } else {
           // Stocker le message d'erreur dans une variable de session
           $_SESSION['erreur'] = "Identifiants incorrects";

           // Redirection vers la page de connexion
           header("Location: ../../connexion.php");
           exit();
        }

        // Fermer la connexion à la base de données
        $bdd->close();
    } else {
        echo "Veuillez remplir tous les champs du formulaire.";
    }
}
?>
