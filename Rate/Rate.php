<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Récupération des données envoyées en JSON
$post = json_decode(file_get_contents('php://input'), true);

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "parkingapp");

if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

if (isset($post["nom_parking"]) && isset($post["rate"])) {
    $nom_parking = $post["nom_parking"];
    $rating = $post["rate"];

    // Récupération du taux de notation actuel et du nombre d'avis pour le parking spécifié
    $resultat = mysqli_query($conn, "SELECT Rate, NombreAvis FROM parking WHERE NomParking = '$nom_parking'");

    if (mysqli_num_rows($resultat) > 0) {
        while ($row = mysqli_fetch_assoc($resultat)) {
            $rate = floatval($row["Rate"]);
            $NombreAvis = intval($row["NombreAvis"]);
        }

        // Mise à jour du nombre d'avis et du taux de notation
        $newNombreAvis = $NombreAvis + 1;
        $new_rate = (($rate * $NombreAvis) + $rating) / $newNombreAvis;

        // Mise à jour des données dans la table 'parking'
        $sql = "UPDATE parking SET Rate = $new_rate, NombreAvis = $newNombreAvis WHERE NomParking = '$nom_parking'";

        if (mysqli_query($conn, $sql)) {
            http_response_code(200);
            echo json_encode([
                'code' => 'success',
                'data' => 'Merci pour votre avis'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'code' => 'error',
                'message' => 'Erreur lors de la mise à jour du taux de notation'
            ]);
        }
    } else {
        http_response_code(404);
        echo json_encode([
            'code' => 'error',
            'message' => 'Parking non trouvé'
        ]);
    }
    mysqli_close($conn);
} else {
    http_response_code(400);
    echo json_encode([
        'code' => 'error',
        'message' => 'Données manquantes'
    ]);
}
?>
