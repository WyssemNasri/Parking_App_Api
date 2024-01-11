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
$message = $post['message']; // Nouveau champ pour le message

// Utilisez des requêtes préparées pour éviter les injections SQL
$sql = "INSERT INTO `descussions` (`id_auteur`, `id_destinataire`, `message`, `date`) VALUES (?, ?, ?, NOW())";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) { // Vérifiez si la préparation de la requête a réussi
    mysqli_stmt_bind_param($stmt, "sss", $id_auteur, $id_destinateur, $message);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        http_response_code(200);
        echo json_encode([
            'result' => 'success',
            'message' => 'Message sent successfully',
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'result' => 'error',
            'message' => 'Failed to send message',
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
