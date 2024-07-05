<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="css/inscription.css">
</head>
<body>
<div class="container">
    <h1>Bienvenue dans le jeu Siam</h1>
    <p>Veuillez vous inscrire pour pouvoir jouer.</p>
    <p>Si vous avez déjà un compte, <a href="connexion.php">cliquez ici</a> pour vous connecter.</p>
    <form method="POST" action="php/BaseDonnee/creerProfile.php">
        <h2>Créer un compte</h2>
        <label for="pseudo">Pseudonyme :</label>
        <input type="text" id="pseudo" name="pseudo" required> <br>
        <label for="email">E-mail :</label>
        <input type="email" id="email" name="email" required> <br>
        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required> <br>
        <input type="submit" value="Soumettre"> <br>
    </form>
    <?php
    session_start() ;
        // Vérifier si la variable de session contenant le message d'erreur est définie et afficher le message d'erreur
    if (isset($_SESSION['erreur'])) {
        echo "<p class=\"erreur\">" . $_SESSION['erreur'] . "</p>";
    }
    exit() ; 
    ?>
</div>
</body>
</html>
