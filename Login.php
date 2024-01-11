<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Démarrez la session


$post = json_decode(file_get_contents('php://input'), true);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parkingapp";

if (isset($post["email"]) && isset($post["password"])) {
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connexion échouée : " . mysqli_connect_error());
    }

    $email = $post["email"];
    $password = $post["password"];
    $sql = "SELECT * FROM users WHERE Email = '$email' AND MotDePasse ='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        http_response_code(200);
        echo json_encode([
            'code' => 'success',
            'data' => $row,
            'message' => 'Logged in successfully'
        ]);
        exit;
    } else {
        http_response_code(400);
        echo json_encode([
            'code' => 'fail',
            'message' => 'Invalid credentials'
        ]);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode([
        'code' => 'fail',
        'message' => 'Error in request parameters'
    ]);
    exit;
}
?>
