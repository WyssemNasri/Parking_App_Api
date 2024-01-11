<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$post = json_decode(file_get_contents('php://input'), true);
include_once "../library/function.php";
$privilege="medecin";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "promedplus";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connexion échouée : " . mysqli_connect_error());
}
$selectArray=array();
array_push($selectArray,$privilege);
$sql="SELECT * from users WHERE  privilege = ? or privilege = 'patient'";
$result=dbExec($sql,$selectArray);

if ($result->rowCount() > 0) {
    $users = $result->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'users' => $users
    ]);
} else {
    http_response_code(200); // You might want to change this to 404 if no users found.
    echo json_encode([
        'success' => false,
        'message' => "No users found"
    ]);
}
