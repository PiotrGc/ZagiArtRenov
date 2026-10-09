<?php
// Limitation du nombre d'actions par adresse IP (formulaires publics, connexion admin).
// Une limite en session seule se contourne en supprimant le cookie : on compte donc par IP,
// dans des petits fichiers rangés hors du dossier public (var/limites, non versionné).
// Ce fichier ne contient AUCUN secret : il est versionné.

function limite_fichier(string $cle): string
{
    $dossier = __DIR__ . '/../var/limites';
    if (!is_dir($dossier)) {
        @mkdir($dossier, 0700, true);
    }
    return $dossier . '/' . hash('sha256', $cle . '|' . ($_SERVER['REMOTE_ADDR'] ?? '')) . '.json';
}

function limite_lire(string $fichier, int $duree): array
{
    $donnees = is_file($fichier) ? json_decode((string)@file_get_contents($fichier), true) : null;
    if (!is_array($donnees) || time() - (int)($donnees['debut'] ?? 0) > $duree) {
        return ['nombre' => 0, 'debut' => time()];
    }
    return $donnees;
}

// Vrai si l'IP a déjà fait $max actions « $cle » dans la fenêtre de $duree secondes.
function limite_atteinte(string $cle, int $max, int $duree): bool
{
    return limite_lire(limite_fichier($cle), $duree)['nombre'] >= $max;
}

// Compte une action de plus pour cette IP.
function limite_enregistrer(string $cle, int $duree): void
{
    $fichier = limite_fichier($cle);
    $donnees = limite_lire($fichier, $duree);
    $donnees['nombre']++;
    @file_put_contents($fichier, json_encode($donnees), LOCK_EX);
}

// Remet le compteur à zéro (ex. après une connexion admin réussie).
function limite_effacer(string $cle): void
{
    @unlink(limite_fichier($cle));
}
