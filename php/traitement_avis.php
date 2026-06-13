<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/index.php');
    exit();
}

include('../php/connexion.php');

$nom         = trim($_POST['nom']         ?? '');
$ville       = trim($_POST['ville']       ?? '');
$note        = intval($_POST['note']      ?? 0);
$commentaire = trim($_POST['commentaire'] ?? '');

// Validation des champs obligatoires
if (!$nom || !$ville || $note < 1 || $note > 5 || !$commentaire) {
    header('Location: ../html/index.php?avis_erreur=1');
    exit;
}

// Sécurité : longueurs maximales
if (strlen($nom) > 100 || strlen($ville) > 100 || strlen($commentaire) > 500) {
    header('Location: ../html/index.php?avis_erreur=1');
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

    header('Location: ../html/index.php?avis_envoye=1');
    exit;

} catch (PDOException $e) {
    header('Location: ../html/index.php?avis_erreur=1');
    exit;
}