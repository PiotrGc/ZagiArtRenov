<?php
session_set_cookie_params([
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: /admin.php");
    exit();
}

// Actions en POST uniquement + jeton CSRF : un lien piégé ouvert par l'admin
// ne peut plus valider ou supprimer un avis à son insu.
if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_POST['action'], $_POST['id'], $_POST['token'])
    || !is_string($_POST['token'])
    || !hash_equals($_SESSION['token'] ?? '', $_POST['token'])) {
    header("Location: /admin.php");
    exit();
}

require '../config/connexion.php';

$id     = (int)$_POST['id'];
$action = $_POST['action'];

try {
    switch ($action) {
        case 'valider':
            $stmt = $conn->prepare("UPDATE avis SET valide = 1 WHERE id = ?");
            $stmt->execute([$id]);
            break;

        case 'depublier':
            $stmt = $conn->prepare("UPDATE avis SET valide = 0 WHERE id = ?");
            $stmt->execute([$id]);
            break;

        case 'supprimer':
            $stmt = $conn->prepare("DELETE FROM avis WHERE id = ?");
            $stmt->execute([$id]);
            break;
    }
} catch (PDOException $e) {
    error_log('Erreur action admin avis : ' . $e->getMessage());
}

header("Location: /admin.php");
exit();
