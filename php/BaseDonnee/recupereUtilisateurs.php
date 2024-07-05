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
    <title>Autres Utilisateurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
       h1 {
                   text-align: center;
                   color: #007bff; /* Couleur bleue */
                   margin-bottom: 20px; /* Marge en bas pour séparer du tableau */
               }


        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .status {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .green {
            background-color: #28a745;
        }

        .red {
            background-color: #dc3545;
        }

        .container {
              max-width: 600px;
              margin: 50px auto;

              padding: 20px;


              text-align: center;
        }
        .btn-back {
              max-width: 600px;
              text-align: center;
              margin-top: 20px;
              text-decoration: none;
              color: #007bff;
              border: 1px solid #007bff;
              padding: 10px 20px;
              border-radius: 5px;
              transition: background-color 0.3s ease;
            }

            .btn-back:hover {
              background-color: #007bff;
              color: #fff;
            }
    </style>
</head>
<body>
<h1> Table des utilisateurs </h1>
<?php
// Connexion à la base de données SQLite
$bdd = new SQLite3('ma_base_de_donnees.db');

// Vérifier la connexion
if (!$bdd) {
    die("Connexion à la base de données échouée.");
}

// Récupérer les données de la table utilisateurs
$requeteSelection = "SELECT id, pseudo, email, ban, isConnected FROM utilisateurs";
$resultat = $bdd->query($requeteSelection);

// Vérifier si la requête a retourné des résultats
if ($resultat) {
    // Affichage des données dans un tableau HTML
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Pseudo</th>";

    if ($is_admin == 'true') {
        echo "<th>Email</th><th>Banni(e)</th>";
    }

    echo "<th>Connecté</th></tr>";
    while ($row = $resultat->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['pseudo'] . "</td>";

        if ($is_admin == 'true') {
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . ($row['ban'] == 'true' ? 'Oui' : 'Non') . "</td>";
        }

        // Affichage du point en fonction de l'état de connexion
        echo "<td>";
        if ($row['isConnected'] == 'true') {
            echo "<div class='status green'></div>";
        } else {
            echo "<div class='status red'></div>";
        }
        echo "</td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Aucune donnée à afficher.";
}

// Fermer la connexion à la base de données
$bdd->close();
?>

<div class="container">
<a href="acceuil.php" class="btn-back">Retour à la page d'acceuil</a>
</div>

</body>
</html>
