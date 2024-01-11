<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$post = json_decode(file_get_contents('php://input'), true);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "promedplus";

$response = array(); // Initialisez le tableau de réponse

if (isset($post["email"]) && isset($post["password"])) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        http_response_code(500); // Erreur de connexion à la base de données
        echo json_encode(array("code" => "error", "message" => "Database connection error"));
        exit;
    }

    $email = $post["email"];
    $password = $post["password"];
    $sql = "SELECT * FROM Users WHERE Email = '$email' AND password ='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            session_start(); // Démarrer une session
            $_SESSION["nomPrenom"] = $row["nomPrenom"]; // Stocker le nom et le prénom dans la session
            http_response_code(200);
            $response["code"] = "success";
            $response["data"] = $row;
        } else {
            http_response_code(400); // Identifiants incorrects
            $response["code"] = "fail";
            $response["message"] = "Invalid credentials";
        }
    } else {
        http_response_code(500); // Erreur de requête SQL
        $response["code"] = "error";
        $response["message"] = "Database query error";
    }

    mysqli_close($conn);
} else {
    http_response_code(400); // Données manquantes
    $response["code"] = "fail";
    $response["message"] = "Missing data";
}

echo json_encode($response);
?>
