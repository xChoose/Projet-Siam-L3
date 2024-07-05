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
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="../../css/CSS.css" />
    <title>Siam</title>
</head>
<body>

    <?php
    // Vérifier si l'ID de la partie est présent dans l'URL
    if (isset($_GET['idPartie'])) {
        // Récupérer l'ID de la partie depuis les paramètres de l'URL
        $id_partie = $_GET['idPartie'];
    }
    echo "<div id='Partie'> Id de la Partie : <div class='idPartie'>". $id_partie . "</div> </div>";
    ?>
    <div id="joueurConnecte"></div>
    <div id="createur"></div>
    <div id="adversaire"></div>
    <div id="tour"></div>
    <div class="button-container">
        <button id="reInit" onclick="init(true)">Réinitialiser</button>
        <button id="annuler" onclick="cancelSelect(true)" disabled>Désélectionner</button>
        <button id="demiTour" onclick="demiTour()" class="bouton1" disabled>Demi-tour</button>
        <button id="quartTourGauche" onclick="quartTourGauche()" class="bouton2" disabled >Quart de tour à gauche</button>
        <button id="quartTourDroite" onclick="quartTourDroite()" class="bouton3" disabled>Quart de tour à droite</button>
        <button id="passeTour" onclick="passeTour()" class="bouton4" disabled>Passer le tour</button>
    </div>
    <div id="plateau"></div>
    
</body>
<script src="../../js/siam.js"></script>
</html>