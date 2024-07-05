<?php
// Vérification que la requête est bien une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données
    $bdd = new PDO('sqlite:../BaseDonnee/parties_en_cours.db');
    $id_partie = $_POST['idPartie'];
    $id_partie = json_decode($id_partie);
    $id_partie = intval($id_partie);

    // Requête SQL pour récupérer les informations de la partie avec l'ID spécifique
    $requete = $bdd->prepare("SELECT partie, pion_choisi, adversaire, tour, premierTour, pseudo_utilisateur FROM parties_en_cours WHERE id = :id");
    $requete->bindValue(':id', $id_partie, PDO::PARAM_INT); // L'id est un entier
    $requete->execute();

    // Renvoi des données au format JSON
    echo json_encode($requete->fetch(PDO::FETCH_ASSOC));

} else {
    // Requête non autorisée, renvoyer une erreur
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
