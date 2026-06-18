<?php
session_set_cookie_params([
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit();
}

if (!isset($_POST['token']) || !isset($_SESSION['token']) || $_POST['token'] !== $_SESSION['token']) {
    header('Location: /index.php?avis_erreur=1');
    exit();
}

$temps_attente_avis = 300;
if (isset($_SESSION['dernier_avis']) && time() - $_SESSION['dernier_avis'] < $temps_attente_avis) {
    header('Location: /index.php?avis_erreur=1');
    exit();
}

include('../config/connexion.php');

$nom         = trim($_POST['nom']         ?? '');
$ville       = trim($_POST['ville']       ?? '');
$note        = intval($_POST['note']      ?? 0);
$commentaire = trim($_POST['commentaire'] ?? '');

if (!$nom || !$ville || $note < 1 || $note > 5 || !$commentaire) {
    header('Location: /index.php?avis_erreur=1');
    exit;
}

if (strlen($nom) > 100 || strlen($ville) > 100 || strlen($commentaire) > 500) {
    header('Location: /index.php?avis_erreur=1');
    exit;
}

try {
    $stmt = $conn->prepare("
        INSERT INTO avis (nom, ville, note, commentaire, date, valide)
        VALUES (:nom, :ville, :note, :commentaire, NOW(), 0)
    ");

    $stmt->execute([
        ':nom'         => $nom,
        ':ville'       => $ville,
        ':note'        => $note,
        ':commentaire' => $commentaire,
    ]);

    $_SESSION['dernier_avis'] = time();

    header('Location: /index.php?avis_envoye=1');
    exit;

} catch (PDOException $e) {
    header('Location: /index.php?avis_erreur=1');
    exit;
}