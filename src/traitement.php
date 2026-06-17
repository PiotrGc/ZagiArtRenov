<?php

session_set_cookie_params([
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);
session_start();

if (!isset($_POST['token']) || !isset($_SESSION['token']) || $_POST['token'] !== $_SESSION['token']) {
    header("Location: /contact.php?erreur=1");
    exit();
}

$temps_attente = 86400;

if (isset($_SESSION['dernier_envoi']) && time() - $_SESSION['dernier_envoi'] < $temps_attente) {
    header("Location: /contact.php?temps=1");
    exit();
}

$_POST = array_map("trim", $_POST);
$_POST = array_map("htmlspecialchars", $_POST);

$nom         = $_POST["nom"];
$prenom      = $_POST["prenom"];
$email       = $_POST["email"];
$tel         = $_POST["tel"];
$presta      = $_POST["presta"];
$description = $_POST["description"];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /contact.php?erreur=1");
    exit();
}

// Sécurité : on vérifie que la prestation choisie fait bien partie des options proposées
$prestations_autorisees = ["electricite", "plomberie", "peinture", "menuiserie"];
if (!in_array($presta, $prestations_autorisees, true)) {
    header("Location: /contact.php?erreur=1");
    exit();
}

require "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require '../php/config.php';

try {
    $mail = new PHPMailer(true);
    $mail->CharSet    = 'UTF-8';
    $mail->isSMTP();
    $mail->SMTPAuth   = true;
    $mail->Host       = "smtp.gmail.com";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->Username   = MAIL_USER;
    $mail->Password   = MAIL_PASS;

    $mail->setFrom(MAIL_USER, 'Zagiart Renov');
    $mail->addAddress(MAIL_DEST);
    $mail->addReplyTo($email, $prenom . ' ' . $nom); 

    $corpsMessage  = "$nom $prenom\n";
    $corpsMessage .= "$email\n";
    $corpsMessage .= "$tel\n\n";
    $corpsMessage .= "$description";

    $mail->Subject = $presta;
    $mail->Body    = $corpsMessage;

    $mail->send();

    $_SESSION['dernier_envoi'] = time();

    header("Location: /contact.php?envoye=1");

} catch (Exception $e) {
    header("Location: /contact.php?erreur=1");
}

exit();