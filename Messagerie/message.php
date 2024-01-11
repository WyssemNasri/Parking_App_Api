<?php
header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['nomPrenom'])) {
    http_response_code(401); // Non autorisé
    echo json_encode(array('message' => 'Utilisateur non connecté'));
    exit();
}

// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=promedplus', 'root', '');

// Vérifier la méthode de la requête
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Récupérer la liste des utilisateurs
    $recupUser = $bdd->query('SELECT * FROM Users');
    $users = array();
    while ($user = $recupUser->fetch()) {
        $users[] = array(
            'nomPrenom' => $user['nomPrenom']
        );
    }

    echo json_encode($users);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données de la requête
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifier les données
    if (!isset($data['message'])) {
        http_response_code(400); // Requête incorrecte
        echo json_encode(array('message' => 'Paramètre "message" manquant'));
        exit();
    }

    $message = $data['message'];

    // Enregistrer le message dans la base de données
    // ...

    echo json_encode(array('message' => 'Message enregistré avec succès'));
} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(array('message' => 'Méthode non autorisée'));
}
?>
