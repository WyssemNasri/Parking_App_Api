<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "parkingapp");
$post = json_decode(file_get_contents('php://input'), true);

if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

if (isset($post['matricule'])) {
    $matricule = $post['matricule'];

    // Requête pour trouver la voiture dans le parking
    $query = "SELECT ParkingName, PositionDansParking FROM voiture WHERE Matricule = '$matricule'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Renvoyer les données de localisation de la voiture au format JSON
        http_response_code(200);
        echo json_encode([
            'parking_name' => $row['ParkingName'],
            'position' => $row['PositionDansParking']
        ]);
    } else {
        // Si la voiture n'est pas trouvée
        http_response_code(404);
        echo json_encode([
            'message' => 'Voiture non trouvée dans un parking'
        ]);
    }
} else {
    // Si le paramètre "matricule" n'est pas fourni
    http_response_code(400);
    echo json_encode([
        'message' => 'Veuillez fournir le matricule de la voiture'
    ]);
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>
