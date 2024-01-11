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

if (
    isset($post["matricule"]) &&
    isset($post["proprietaire"]) &&
    isset($post["parking_name"]) &&
    isset($post["position"])
) {
    $matricule = $post["matricule"];
    $proprietaire = $post["proprietaire"];
    $parking_name = $post["parking_name"];
    $position = $post["position"];

    // Vérification si le parking existe
    $checkParking = mysqli_query($conn, "SELECT * FROM parking WHERE NomParking = '$parking_name'");

    if (mysqli_num_rows($checkParking) > 0) {
        // Insertion des données dans la table 'voiture'
        $sql = "INSERT INTO voiture (Matricule, Proprietaire, ParkingName, PositionDansParking)
                VALUES ('$matricule', '$proprietaire', '$parking_name', '$position')";

        if (mysqli_query($conn, $sql)) {
            http_response_code(200);
            echo json_encode([
                'code' => 'success',
                'message' => 'Voiture ajoutée au parking avec succès'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'code' => 'error',
                'message' => 'Erreur lors de l\'ajout de la voiture au parking'
            ]);
        }
    } else {
        http_response_code(404);
        echo json_encode([
            'code' => 'error',
            'message' => 'Le parking spécifié n\'existe pas'
        ]);
    }
    mysqli_close($conn);
} else {
    http_response_code(400);
    echo json_encode([
        'code' => 'error',
        'message' => 'Données manquantes pour ajouter la voiture au parking'
    ]);
}
?>
