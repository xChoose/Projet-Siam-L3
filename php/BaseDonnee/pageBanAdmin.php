<?php
session_start(); // Démarrer la session

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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gérer les comptes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 50px;
        }
        form {
            width: 300px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .btn-back {
            display: block;
            width: 100px; /* Ajustez la largeur selon vos préférences */
            margin: 20px auto; /* Centrez horizontalement et ajoutez un espace en haut */
            padding: 10px;
            text-align: center;
            background-color: #ccc;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn-back:hover {
            background-color: #aaa;
        }
    </style>
</head>
<body>
<h1>Administration - Gérer les comptes</h1>
<!-- Formulaire pour bannir un compte -->
<form action="bannirCompte.php" method="post">
    <h2>Bannir un compte</h2>
    <label for="pseudoBan">Pseudo à bannir :</label>
    <input type="text" id="pseudoBan" name="pseudoBan" required>
    <input type="submit" value="Bannir">
</form>

<!-- Formulaire pour débannir un compte -->
<form action="debannirCompte.php" method="post">
    <h2>Débannir un compte</h2>
    <label for="pseudoDeban">Pseudo à débannir :</label>
    <input type="text" id="pseudoDeban" name="pseudoDeban" required>
    <input type="submit" value="Débannir">
</form>

<!-- Bouton pour revenir à l'accueil -->
<a href="acceuil.php" class="btn-back">Accueil</a>
</body>
</html>
