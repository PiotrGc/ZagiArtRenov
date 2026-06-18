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

if (!isset($_GET['action']) || !isset($_GET['id'])) {
    header("Location: /admin.php");
    exit();
}

require '../config/connexion.php';

$id     = (int)$_GET['id'];
$action = $_GET['action'];

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
    // erreur silencieuse
}

header("Location: /admin.php");
exit();
?>