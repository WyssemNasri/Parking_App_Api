<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$post = json_decode(file_get_contents('php://input'), true);
$conn = mysqli_connect("localhost", "root", "", "parkingapp");

if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

if (isset($post["date_reservation"]) && isset($post["nom_prenom_utilisateur"]) && isset($post["nom_parking"])) {
    $date_reservation = $post["date_reservation"];
    $nom_prenom_utilisateur = $post["nom_prenom_utilisateur"];
    $nom_parking = $post["nom_parking"];

    $sql = "INSERT INTO reservation (DateReservation, NomPrenomUtilisateur, NomParking) 
            VALUES ('$date_reservation', '$nom_prenom_utilisateur', '$nom_parking')";

    if (mysqli_query($conn, $sql)) {
        http_response_code(200);
        echo json_encode([
            'code' => 'success',
            'data' => 'Réservation effectuée avec succès'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'code' => 'error',
            'message' => 'Erreur lors de la réservation : ' . mysqli_error($conn)
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        'code' => 'error',
        'message' => 'Paramètres manquants pour la réservation'
    ]);
}

mysqli_close($conn);
?>
