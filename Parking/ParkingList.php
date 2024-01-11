<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = mysqli_connect("localhost", "root", "", "parkingapp");

if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

// Sélectionnez tous les parkings de la base de données
$resultat = mysqli_query($conn, "SELECT * FROM parking");

if (mysqli_num_rows($resultat) > 0) {
    $parkings = array();

    // Parcourir les résultats et les stocker dans un tableau
    while ($row = mysqli_fetch_assoc($resultat)) {
        $parking = array(
            'nom_parking' => $row['NomParking'],
            'capacite' => $row['Max_Size'],
            'rate' => $row['Rate'],
            'nombre_avis' => $row['NombreAvis']
        );
        $parkings[] = $parking;
    }

    // Renvoyer les données des parkings sous forme de JSON avec un message de succès
    $response = array(
        'code' => 'success',
        'message' => 'Parkings récupérés avec succès',
        'data' => $parkings
    );

    http_response_code(200);
    echo json_encode($response);
} else {
    // Aucun parking trouvé
    http_response_code(404);
    echo json_encode([
        'code' => 'error',
        'message' => 'Aucun parking trouvé'
    ]);
}

mysqli_close($conn);
?>
