<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/connexion.css">
</head>
<body>

<h2>Bienvenue dans le jeu Siam</h2>
<p>Veuillez taper vos identifiants pour vous connecter :</p>
<form method="POST" action="php/BaseDonnee/verifierConnexion.php">
    <label for="email">E-mail :</label>
    <input type="email" id="email" name="email" required> <br>
    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" id="mot_de_passe" name="mot_de_passe" required> <br>
   <?php
   session_start(); // Démarrer la session

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       // Récupérer les données du formulaire
       $email = $_POST['email'];
       $mot_de_passe = $_POST['mot_de_passe'];
       
       // Vérifier que le mot de passe ne contient pas les caractères < et >
       if (strpos($mot_de_passe, '<') !== false || strpos($mot_de_passe, '>') !== false) {
        $_SESSION['erreur'] = "Le mot de passe ne peut pas contenir les caractères < et >.";
        header("Location: connexion.php");
        exit();
        }

       // Connexion à la base de données SQLite
       $bdd = new SQLite3('php/BaseDonnee/ma_base_de_donnees.db');
       // Vérifier la connexion
       if (!$bdd) {
           die("Connexion à la base de données échouée.");
       }

       // Vérifier les informations de connexion dans la base de données
       $requeteVerification = "SELECT * FROM utilisateurs WHERE email = :email AND mot_de_passe = :mot_de_passe";
       $statement = $bdd->prepare($requeteVerification);
       $statement->bindValue(':email', $email, SQLITE3_TEXT);
       $statement->bindValue(':mot_de_passe', $mot_de_passe, SQLITE3_TEXT);
       $result = $statement->execute();
       $user = $result->fetchArray(SQLITE3_ASSOC);

       if ($user) {
           // Redirection vers la page d'accueil si la connexion réussit
           header("Location: php/BaseDonnee/acceuil.php");
           exit();
       } else {
           // Si les informations de connexion sont incorrectes, définir un message d'erreur dans la session
           $_SESSION['erreur'] = "Adresse e-mail ou mot de passe incorrect.";

           // Rediriger vers la page de connexion avec un message d'erreur
           header("Location: connexion.php");
           exit();
       }
   }

   // Vérifier si la variable de session contenant le message d'erreur est définie et afficher le message d'erreur
   if (isset($_SESSION['erreur'])) {
       echo "<p class=\"erreur\">" . $_SESSION['erreur'] . "</p>";
   }
   ?>

    <input type="submit" value="Se connecter"> <br>
</form>
<p>Si vous n'avez pas de compte, veuillez <a href="inscription.php">cliquer ici</a> pour vous inscrire.</p>
</body>
</html>
