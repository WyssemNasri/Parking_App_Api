<?php
$post = json_decode(file_get_contents('php://input'), true);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
$mail = new PHPMailer(true);
try {
    $url="http://http://localhost/api/resetpassword/vendor/phpmailer/phpmailer/form.php"; 
    $email=$post['email'];


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
    $mail->setFrom('consultationasm@gmail.com', 'Wissem Nasri ;)');
    $mail->addAddress($email, 'Notre client');     //Add a recipient
    $mail->Subject = 'mot de passe oublier';
    $mail->Body    = "<h2>pour réinitialiser votre mot de passe cliquez ici:</h2>.$url";
    $mail->AltBody = 'Cette email pour vous reset ur password ';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}