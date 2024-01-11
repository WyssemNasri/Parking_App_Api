<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = mysqli_connect("localhost", "root", "", "parkingapp");

if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

// Vérifier si les données JSON ont été envoyées
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (isset($input['ville'])) {
    $ville = $input['ville'];

    // Sélectionnez les parkings dans la ville spécifiée
    $query = "SELECT * FROM parking WHERE Ville = '$ville'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $parkings = array();

        // Parcourir les résultats et les stocker dans un tableau
        while ($row = mysqli_fetch_assoc($result)) {
            $parking = array(
                'nom_parking' => $row['NomParking'],
                'capacite' => $row['Max_Size'],
                'rate' => $row['Rate'],
                'nombre_avis' => $row['NombreAvis']
            );
            $parkings[] = $parking;
        }

        // Renvoyer les données des parkings sous forme de JSON
        http_response_code(200);
        echo json_encode($parkings);
    } else {
        // Aucun parking trouvé dans cette ville
        http_response_code(404);
        echo json_encode([
            'message' => 'Aucun parking trouvé dans cette ville'
        ]);
    }
} else {
    // Aucune donnée de ville fournie
    http_response_code(400);
    echo json_encode([
        'message' => 'Veuillez fournir le nom de la ville'
    ]);
}

mysqli_close($conn);
?>
