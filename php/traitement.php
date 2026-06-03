<?php

session_start();

$temps_attente = 3600;

$_POST = array_map("trim", $_POST);

$nom = $_POST["nom"];
$prenom = $_POST["prenom"];
$email = $_POST["email"];
$tel = $_POST["tel"];
$presta = $_POST["presta"];
$description = $_POST["description"];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo("Email invalide : veuillez entrez une adresse mail valide");
    exit();
}

require "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->Username = "grabiecpiotr07@gmail.com";
$mail->Password = "«SUPPRIMÉ»";

$mail->setFrom($email, $prenom . ' ' . $nom);
$mail->addAddress("grabiecpiotr07@gmail.com");
$mail->addReplyTo($email, $prenom . ' ' . $nom);

$corpsMessage = "$nom $prenom\n";
$corpsMessage .= "$email\n";
$corpsMessage .= "$tel\n\n";
$corpsMessage .= "$description";
    
$mail->Body = $corpsMessage;

$mail->send();echo 'Message has been sent';

header("Location: ../html/contact.html");
exit();