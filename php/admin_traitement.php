<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../html/admin.php");
    exit();
}

if (!isset($_GET['action']) || !isset($_GET['id'])) {
    header("Location: ../html/admin.php");
    exit();
}

require '../php/connexion.php';

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

header("Location: ../html/admin.php");
exit();
?>