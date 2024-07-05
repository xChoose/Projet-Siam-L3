<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_pseudo'], $_SESSION['user_email'], $_SESSION['is_admin'])) {
    $nomUtilisateur = $_SESSION['user_pseudo'];
    echo json_encode(array("nomUtilisateur" => $nomUtilisateur));
} else {
    echo "Utilisateur non connecté";
}

?>