<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$post = json_decode(file_get_contents('php://input'), true);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

function generateKey() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz/*-+.():;,?§!,<>#@$µ';  
    $key = '';
    for ($i = 0; $i < 8; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $key .= $characters[$index];
    }
    return $key;
  } 

if($post["privilege"]=="Propriétaire du parking")  
 {
  $connexion = mysqli_connect("localhost", "root", "", "parkingapp");
  if (!$connexion) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
  }
  do{
    $x=generateKey();
    $sql = "SELECT * FROM `users` WHERE `ID_utilisateur` = '$x'";
    $result = mysqli_query($connexion, $sql);
    }while($x===$result);

    $email = $post["email"];
    $passwordi= $post["password"];
    $nomprenom = $post["nomprenom"];
    $numero = $post["numero"];
    $privilege = $post["privilege"];
    $NomParking=$post["NomParking"];
    $adresse=$post["adresse"];
    $max_size=$post["max_size"];

    
    $sql1 = "INSERT INTO users (ID_Utilisateur,NomPrenom,MotDePasse,Email,privilege,NumeroTelephone)
    VALUES ('$x','$nomprenom', '$email','$passwordi', '$privilege', '$numero')";
    $result1 = mysqli_query($connexion, $sql1);
    $sql = "INSERT INTO parking (NomParking,Proprietaire,ville,max_size)
    VALUES ('$NomParking','$nomprenom','$adresse', '$max_size');";
    $result = mysqli_query($connexion, $sql);
    $resJson = array("result" => "success", "code" => "200", "message" => "done");
    echo json_encode($resJson, JSON_UNESCAPED_UNICODE);
} else {
  $connexion = mysqli_connect("localhost", "root", "", "parkingapp");
  if (!$connexion) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
  }
  do{
    $x=generateKey();
    $sql = "SELECT * FROM `users` WHERE `ID_utilisateur` = '$x'";
    $result = mysqli_query($connexion, $sql);
    }while($x===$result);

    $email = $post["email"];
    $passwordi= $post["password"];
    $nomprenom = $post["nomprenom"];
    $numero = $post["numero"];
    $privilege = $post["privilege"];

    $sql1 = "INSERT INTO users (ID_utilisateur,NomPrenom,MotDePasse,Email,privilege,NumeroTelephone)
    VALUES ('$x','$nomprenom', '$email','$passwordi', '$privilege', '$numero');";
    $result1 = mysqli_query($connexion, $sql1);
    $resJson = array("result" => "success", "code" => "200", "message" => "done");
    echo json_encode($resJson, JSON_UNESCAPED_UNICODE);
} 
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'consultationasm@gmail.com';                     //SMTP username
    $mail->Password   = 'zznmrgpcpjmkctoq';                               //SMTP password
    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('consultationasm@gmail.com', 'asm consultation');
    $mail->addAddress($email, 'Notre');     //Add a recipient
    $mail->Subject = 'information';
    $mail->Body    = '<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
            }
    
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
    
            h2 {
                font-size: 28px;
                color: #007bff;
                margin-bottom: 20px;
            }
    
            .message {
                font-size: 18px;
                color: #333;
                margin-top: 20px;
                text-align: left;
            }
    
            .details {
                font-weight: bold;
                color: #333;
            }
    
            .email {
                color: #007bff;
            }
    
            .key {
                color: #ffc107;
            }
    
            .name {
                color: #28a745;
            }
    
            .number {
                color: #17a2b8;
            }
    
            .activation {
                font-size: 22px;
                color: #28a745;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>🚀 Bienvenue sur ParkiTN 📱</h2>
            <div class="message">
                <p>Merci pour votre inscription à notre application mobile ParkiTN. Voici vos informations :</p>
                <p class="details">Votre email : <span class="email">' . $email . '</span></p>
                <p class="details">Votre mot de passe  : <span class="email">' . $passwordi . '</span></p>
                <p class="details">Votre clé : <span class="key">' . $x . '</span></p>
                <p class="details">Nom et Prénom : <span class="name">' . $nomprenom . '</span></p>
                <p class="details">Numéro : <span class="number">' . $numero . '</span></p>
                <p>Votre licence est automatiquement activée. Profitez de notre application!</p>
            </div>
            <div class="activation">Votre compte est maintenant opérationnel.</div>
        </div>
    </body>
    </html>';
    
    $mail->AltBody = 'Cette email pour vous reset ur password ';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>
