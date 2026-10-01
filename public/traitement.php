<?php

session_set_cookie_params([
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /contact.php");
    exit();
}

if (!isset($_POST['token']) || !isset($_SESSION['token']) || !is_string($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
    header("Location: /contact.php?erreur=1");
    exit();
}

// Champ piège rempli : c'est un robot. On fait comme si tout s'était bien passé.
if (!empty($_POST['site'])) {
    header("Location: /contact.php?envoye=1");
    exit();
}

// Limite par session (anti double envoi). Ne protège pas d'un robot qui jette ses cookies.
$temps_attente = 600;

if (isset($_SESSION['dernier_envoi']) && time() - $_SESSION['dernier_envoi'] < $temps_attente) {
    header("Location: /contact.php?temps=1");
    exit();
}

// Récupère un champ texte du POST (refuse les tableaux), sans encodage HTML :
// l'e-mail est envoyé en texte brut, htmlspecialchars y afficherait des &amp; etc.
function champ_texte(string $nom): string
{
    return isset($_POST[$nom]) && is_string($_POST[$nom]) ? trim($_POST[$nom]) : '';
}

$nom          = champ_texte("nom");
$prenom       = champ_texte("prenom");
$email        = champ_texte("email");
$tel          = champ_texte("tel");
$presta       = champ_texte("presta");
$description  = champ_texte("description");
$consentement = champ_texte("consentement");

if ($nom === '' || $prenom === '' || $description === '' || $consentement !== '1') {
    header("Location: /contact.php?erreur=1");
    exit();
}

if (mb_strlen($nom) > 100 || mb_strlen($prenom) > 100 || mb_strlen($tel) > 20 || mb_strlen($description) > 3000) {
    header("Location: /contact.php?erreur=1");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: /contact.php?erreur=1");
    exit();
}

if ($tel !== '' && !preg_match('/^[0-9 +().-]{6,20}$/', $tel)) {
    header("Location: /contact.php?erreur=1");
    exit();
}

$prestations_autorisees = [
    "electricite" => "Électricité",
    "plomberie"   => "Plomberie",
    "peinture"    => "Peinture",
    "menuiserie"  => "Menuiserie",
];
if (!array_key_exists($presta, $prestations_autorisees)) {
    header("Location: /contact.php?erreur=1");
    exit();
}

require "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
require '../config/config.php';

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

    $mail->setFrom(MAIL_USER, 'Site ZagiArtRenov');
    $mail->addAddress(MAIL_DEST);
    $mail->addReplyTo($email, $prenom . ' ' . $nom);

    $corpsMessage  = "Nouvelle demande de devis depuis le site\n\n";
    $corpsMessage .= "Nom : $prenom $nom\n";
    $corpsMessage .= "E-mail : $email\n";
    $corpsMessage .= "Téléphone : " . ($tel !== '' ? $tel : 'non renseigné') . "\n";
    $corpsMessage .= "Prestation : " . $prestations_autorisees[$presta] . "\n\n";
    $corpsMessage .= "Description :\n$description\n";

    $mail->isHTML(false);
    $mail->Subject = "Demande de devis - " . $prestations_autorisees[$presta];
    $mail->Body    = $corpsMessage;

    $mail->send();

    $_SESSION['dernier_envoi'] = time();

    header("Location: /contact.php?envoye=1");

} catch (Exception $e) {
    error_log('Erreur envoi formulaire contact : ' . $e->getMessage());
    header("Location: /contact.php?erreur=1");
}

exit();
