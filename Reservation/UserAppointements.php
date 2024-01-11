<?php
// Connexion à la base de données
$conn = mysqli_connect("localhost", "utilisateur", "mot_de_passe", "nom_base_de_données");

if (!$conn) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

// Requête pour récupérer les rendez-vous avec l'état "Accepté"
$resultat = mysqli_query($conn, "SELECT * FROM reservation WHERE Etat = 'Accepté'");

if (mysqli_num_rows($resultat) > 0) {
    $rendezVousAcceptes = array();

    // Parcourir les résultats et les stocker dans un tableau
    while ($row = mysqli_fetch_assoc($resultat)) {
        $rendezVous = array(
            'IdReservation' => $row['IdReservation'],
            'DateReservation' => $row['DateReservation'],
            'NomPrenomUtilisateur' => $row['NomPrenomUtilisateur'],
            'NomParking' => $row['NomParking'],
            'Etat' => $row['Etat']
        );
        $rendezVousAcceptes[] = $rendezVous;
    }

    // Renvoyer les rendez-vous acceptés sous forme de JSON
    http_response_code(200);
    echo json_encode($rendezVousAcceptes);
} else {
    // Aucun rendez-vous accepté trouvé
    http_response_code(404);
    echo json_encode([
        'message' => 'Aucun rendez-vous accepté trouvé'
    ]);
}

mysqli_close($conn);
?>
