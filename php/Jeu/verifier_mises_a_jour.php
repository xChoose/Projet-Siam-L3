<?php
// Connexion à la base de données
$bdd = new PDO('sqlite:../BaseDonnee/parties_en_cours.db');

// Récupération de l'ID de la partie à vérifier
$idPartie = $_POST['idPartie'];
$idPartie = json_decode($idPartie);
$idPartie = intval($idPartie);

$requete = $bdd->prepare("SELECT partie FROM parties_en_cours WHERE id = :idPartie");
$requete->bindValue(':idPartie', $idPartie, PDO::PARAM_INT);
$requete->execute();

echo json_encode($requete->fetch(PDO::FETCH_ASSOC));
?>
