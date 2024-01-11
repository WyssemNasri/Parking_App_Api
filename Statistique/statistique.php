<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$post = json_decode(file_get_contents('php://input'), true);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parkingapp";

if (isset($post["nomPrenom"])) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if (!$conn) {
        die("Connexion échouée : " . mysqli_connect_error());
    }
    
    $nomprenom = $post["nomPrenom"];
    $sql = "SELECT * FROM reservation WHERE NomPrenomUtilisateur = '$nomprenom'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $sql1 = "SELECT 
                    SUM(CASE WHEN Etat = 'refused' THEN 1 ELSE 0 END) AS refused_count,
                    SUM(CASE WHEN Etat = 'accepted' THEN 1 ELSE 0 END) AS accepted_count,
                    SUM(CASE WHEN Etat = 'En attente' THEN 1 ELSE 0 END) AS waiting_count
                 FROM reservation 
                 WHERE NomPrenomUtilisateur = '$nomprenom'";
                 
        $result1 = mysqli_query($conn, $sql1);

        if ($result1) {
            $row1 = mysqli_fetch_assoc($result1);
            http_response_code(200);
            echo json_encode([
                'code' => 'success',
                'data' => $row1
            ]);
            exit;
        } else {
            http_response_code(400);
            echo json_encode([
                'code' => 'fail',
                'data' => "Erreur lors de la récupération des données"
            ]);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'code' => 'fail',
            'data' => "Aucun rendez-vous trouvé pour ce nomPrenom"
        ]);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode([
        'code' => 'fail',
        'data' => "Veuillez fournir un nomPrenom"
    ]);
    exit;
}
?>
