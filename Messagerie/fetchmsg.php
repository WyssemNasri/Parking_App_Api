<?php
$post = json_decode(file_get_contents('php://input'), true);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "promedplus";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connexion échouée : " . mysqli_connect_error());
}

// Assurez-vous de récupérer les valeurs correctement depuis la requête JSON
$id_auteur = $post['id_auteur'];
$id_destinateur = $post['id_destinataire'];

// Utilisez des requêtes préparées pour éviter les injections SQL
$sql = "SELECT * FROM `descussions` WHERE (`id_auteur` = ? AND `id_destinataire` = ?) OR (`id_auteur` = ? AND `id_destinataire` = ?)";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) { // Vérifiez si la préparation de la requête a réussi
    mysqli_stmt_bind_param($stmt, "ssss", $id_auteur, $id_destinateur, $id_destinateur, $id_auteur);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Récupérez les résultats
        $messages = array();
        $response = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($response)) {
            $message = array(
                'id_auteur' => $row['id_auteur'],
                'id_destinataire' => $row['id_destinataire'],
                'message' => $row['message'],
                'date' => $row['date']
            );
            $messages[] = $message;
        }

        http_response_code(200);
        echo json_encode([
            'result' => 'success',
            'messages' => $messages,
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'result' => 'error',
            'message' => 'Failed to fetch messages',
        ]);
    }

    // Fermez la requête préparée
    mysqli_stmt_close($stmt);
} else {
    // Erreur de préparation de la requête
    http_response_code(400);
    echo json_encode([
        'result' => 'error',
        'message' => 'Failed to prepare statement',
    ]);
}

// Fermez la connexion à la base de données
mysqli_close($conn);
?>
