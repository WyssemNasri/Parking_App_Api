<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = mysqli_connect("localhost", "root", "", "parkingapp");

if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (isset($input["nom_prenom"])) {
    $nom_prenom = $input["nom_prenom"];

    $query = "SELECT parking.NomParking, parking.Ville, parking.Max_Size, parking.Rate, parking.NombreAvis,
              voiture.Matricule, voiture.PositionDansParking
              FROM parking
              JOIN voiture ON parking.NomParking = voiture.ParkingName
              JOIN users ON voiture.Proprietaire = users.NomPrenom
              WHERE users.NomPrenom = '$nom_prenom'";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        http_response_code(404);
        echo json_encode([
            'message' => 'Aucun parking trouvé pour ce propriétaire'
        ]);
    } else {
        $parking_info = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $parking = array(
                'NomParking' => $row['NomParking'],
                'Ville' => $row['Ville'],
                'CapaciteMax' => $row['Max_Size'],
                'Rate' => $row['Rate'],
                'NombreAvis' => $row['NombreAvis'],
                'Voitures' => array(
                    'Matricule' => $row['Matricule'],
                    'PositionDansParking' => $row['PositionDansParking']
                )
            );
            $parking_info[] = $parking;
        }

        http_response_code(200);
        echo json_encode($parking_info);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'message' => 'Paramètres manquants'
    ]);
}

mysqli_close($conn);
?>
