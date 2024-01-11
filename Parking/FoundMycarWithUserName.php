<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "parkingapp");
$post = json_decode(file_get_contents('php://input'), true);

if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

if (isset($post['nom_prenom'])) {
    $nomPrenom = $post['nom_prenom'];

    // Requête pour trouver les voitures du propriétaire dans le parking
    $query = "SELECT Matricule, ParkingName, PositionDansParking FROM voiture WHERE Proprietaire = '$nomPrenom'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $voitures = array();

        // Parcourir les résultats et les stocker dans un tableau
        while ($row = mysqli_fetch_assoc($result)) {
            $voiture = array(
                'matricule' => $row['Matricule'],
                'parking_name' => $row['ParkingName'],
                'position' => $row['PositionDansParking']
            );
            $voitures[] = $voiture;
        }

        // Renvoyer les données des voitures du propriétaire au format JSON
        http_response_code(200);
        echo json_encode($voitures);
    } else {
        // Si aucune voiture trouvée pour ce propriétaire
        http_response_code(404);
        echo json_encode([
            'message' => 'Aucune voiture trouvée pour ce propriétaire'
        ]);
    }
} else {
    // Si le paramètre "nom_prenom" n'est pas fourni
    http_response_code(400);
    echo json_encode([
        'message' => 'Veuillez fournir le nom et prénom du propriétaire'
    ]);
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>
