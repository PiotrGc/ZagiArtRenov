<?php

session_start();

$temps_attente = 10800;

if (isset($_SESSION['dernier_envoi']) && time() - $_SESSION['dernier_envoi'] < $temps_attente) {
    header("Location: ../html/contact.php?temps=1");
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
    header("Location: ../html/contact.php?erreur=1");
    exit();
}

require "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

try {
    $mail = new PHPMailer(true);

    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    $mail->isSMTP();
    $mail->SMTPAuth   = true;
    $mail->Host       = "smtp.gmail.com";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->Username   = "grabiecpiotr07@gmail.com";
    $mail->Password   = "«SUPPRIMÉ»";

    $mail->setFrom($email, $prenom . ' ' . $nom);
    $mail->addAddress("grabiecpiotr07@gmail.com");
    $mail->addReplyTo($email, $prenom . ' ' . $nom);

    $corpsMessage  = "$nom $prenom\n";
    $corpsMessage .= "$email\n";
    $corpsMessage .= "$tel\n\n";
    $corpsMessage .= "$description";

    $mail->Subject = $presta;
    $mail->Body    = $corpsMessage;

    $mail->send();

    $_SESSION['dernier_envoi'] = time();

    header("Location: ../html/contact.php?envoye=1");

} catch (Exception $e) {
    header("Location: ../html/contact.php?erreur=1");
}

exit();