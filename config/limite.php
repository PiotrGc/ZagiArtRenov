<?php
// Limitation du nombre d'actions par adresse IP (formulaires publics, connexion admin).
// Une limite en session seule se contourne en supprimant le cookie : on compte donc par IP,
// dans des petits fichiers rangés hors du dossier public (var/limites, non versionné).
// Ce fichier ne contient AUCUN secret : il est versionné.
//
// Sûreté :
// - le compteur est lu et incrémenté sous verrou (flock) : des requêtes envoyées en parallèle
//   ne peuvent pas toutes lire « 0 essai » avant que le compteur augmente ;
// - si aucun dossier n'est utilisable, l'action est REFUSÉE (et l'erreur journalisée)
//   plutôt que de laisser passer sans limite.

function limite_dossier(): ?string
{
    // Dossier du projet en priorité, sinon le dossier temporaire du serveur.
    foreach ([__DIR__ . '/../var/limites', sys_get_temp_dir() . '/zar_limites'] as $dossier) {
        if (!is_dir($dossier)) {
            @mkdir($dossier, 0700, true);
        }
        if (is_dir($dossier) && is_writable($dossier)) {
            return $dossier;
        }
    }
    error_log('Limiteur : aucun dossier accessible en écriture, actions refusées.');
    return null;
}

function limite_fichier(string $cle): ?string
{
    $dossier = limite_dossier();
    if ($dossier === null) {
        return null;
    }
    return $dossier . '/' . hash('sha256', $cle . '|' . ($_SERVER['REMOTE_ADDR'] ?? '')) . '.json';
}

// Ajoute une action pour cette IP, de façon atomique, et indique si elle est encore autorisée
// (vrai tant que l'IP n'a pas dépassé $max actions « $cle » sur $duree secondes).
// À appeler AVANT de faire l'action (envoi, enregistrement, vérification du mot de passe).
function limite_consommer(string $cle, int $max, int $duree): bool
{
    $fichier = limite_fichier($cle);
    $f = $fichier !== null ? @fopen($fichier, 'c+') : false;
    if ($f === false) {
        return false;
    }
    if (!flock($f, LOCK_EX)) {
        fclose($f);
        return false;
    }

    $donnees = json_decode((string)stream_get_contents($f), true);
    if (!is_array($donnees) || time() - (int)($donnees['debut'] ?? 0) > $duree) {
        $donnees = ['nombre' => 0, 'debut' => time()];
    }
    $donnees['nombre']++;

    ftruncate($f, 0);
    rewind($f);
    $ecrit = fwrite($f, json_encode($donnees));
    fflush($f);
    flock($f, LOCK_UN);
    fclose($f);

    return $ecrit !== false && $donnees['nombre'] <= $max;
}

// Remet le compteur à zéro (ex. après une connexion admin réussie).
function limite_effacer(string $cle): void
{
    $fichier = limite_fichier($cle);
    if ($fichier !== null) {
        @unlink($fichier);
    }
}
