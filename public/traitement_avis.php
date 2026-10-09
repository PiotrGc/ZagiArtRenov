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

if (!isset($_POST['token']) || !isset($_SESSION['token']) || !is_string($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
    header('Location: /index.php?avis_erreur=1');
    exit();
}

// Champ piège rempli : c'est un robot. On fait comme si tout s'était bien passé.
if (!empty($_POST['site'])) {
    header('Location: /index.php?avis_envoye=1');
    exit();
}

// Limite par session + par IP (sinon un robot qui jette ses cookies remplit la table).
$temps_attente_avis = 300;
require '../config/limite.php';
if (isset($_SESSION['dernier_avis']) && time() - $_SESSION['dernier_avis'] < $temps_attente_avis) {
    header('Location: /index.php?avis_erreur=1');
    exit();
}

// Récupère un champ texte du POST (refuse les tableaux).
function champ_texte(string $nom): string
{
    // Texte non UTF-8 refusé : il fausserait le filtre des termes interdits et l'affichage.
    return isset($_POST[$nom]) && is_string($_POST[$nom]) && mb_check_encoding($_POST[$nom], 'UTF-8')
        ? trim($_POST[$nom]) : '';
}

$nom          = champ_texte('nom');
$ville        = champ_texte('ville');
$note         = (int)champ_texte('note');
$commentaire  = champ_texte('commentaire');
$consentement = champ_texte('consentement');

// La ville est facultative ; le consentement à la publication est obligatoire.
if ($nom === '' || $note < 1 || $note > 5 || $commentaire === '' || $consentement !== '1') {
    header('Location: /index.php?avis_erreur=1');
    exit;
}

// mb_strlen : compte les caractères (comme le maxlength du navigateur), pas les octets.
if (mb_strlen($nom) > 100 || mb_strlen($ville) > 100 || mb_strlen($commentaire) > 500) {
    header('Location: /index.php?avis_erreur=1');
    exit;
}

// Termes injurieux / racistes : l'avis est refusé avant même d'arriver en modération.
require '../config/mots_interdits.php';
if (mots_interdits_trouves($nom . ' | ' . $ville . ' | ' . $commentaire)) {
    header('Location: /index.php?avis_refuse=1');
    exit;
}

// Limite par IP comptée juste avant l'enregistrement (compteur atomique, voir config/limite.php).
if (!limite_consommer('avis', 3, 3600)) {
    header('Location: /index.php?avis_erreur=1');
    exit;
}

include('../config/connexion.php');

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
    error_log('Erreur enregistrement avis : ' . $e->getMessage());
    header('Location: /index.php?avis_erreur=1');
    exit;
}
