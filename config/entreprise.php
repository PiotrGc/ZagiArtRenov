<?php
// Informations légales de l'entreprise (mentions légales, footer, politique de confidentialité).
// Ce fichier ne contient AUCUN secret : il est versionné.
// Source des données d'immatriculation : annuaire-entreprises.data.gouv.fr (SIREN 942 987 751).
// Les valeurs laissées vides ('') ne sont pas affichées sur le site : à compléter dès que possible
// (obligations : loi LCEN art. 6, Code de la consommation).

define('ENT_NOM_COMMERCIAL', 'ZagiArtRenov');
define('ENT_RAISON_SOCIALE', 'ZAGI ART RENOV');
define('ENT_DIRIGEANT',      'Zvezdan Zaganovic');
// Capital, RCS et TVA confirmés par les registres publics (Pappers / Societe.com, oct. 2026).
define('ENT_FORME',          'Société à responsabilité limitée (SARL) au capital de 1 000 €');
define('ENT_SIRET',          '942 987 751 00016');
define('ENT_RNE',            'Immatriculée au RCS de Bobigny sous le n° 942 987 751');
define('ENT_TVA',            'FR72942987751');

define('ENT_ADRESSE',        '193 avenue Henri Barbusse');
define('ENT_CP_VILLE',       '93700 Drancy');
define('ENT_TEL_AFFICHE',    '06 76 09 11 20');
define('ENT_TEL_LIEN',       '+33676091120');
define('ENT_EMAIL',          'zagiartrenov@gmail.com');
define('ENT_ZONE',           'Paris et Île-de-France');
define('ENT_HORAIRES',       'Du lundi au samedi, de 8 h à 17 h');

// Assurance décennale : obligatoire pour les travaux de construction / rénovation lourde
// (art. L241-1 Code des assurances). Doit figurer sur les devis et factures.
// TODO : nom de l'assureur, n° de contrat, couverture géographique (sur l'attestation d'assurance).
define('ENT_ASSURANCE',      '');

// Médiateur de la consommation : obligatoire dès qu'on travaille pour des particuliers
// (art. L612-1 et L616-1 Code de la consommation).
// TODO : nom, adresse postale et site web du médiateur (sur le contrat d'adhésion au médiateur).
define('ENT_MEDIATEUR',      '');

// Hébergeur du site (LCEN art. 6 III).
define('ENT_HEBERGEUR',      'o2switch, SAS au capital de 100 000 €, Chemin des Pardiaux, 63000 Clermont-Ferrand. Téléphone : 04 44 44 60 40. Site : www.o2switch.fr');

// Date de dernière mise à jour des pages légales.
define('ENT_MAJ_LEGALE',     '1er octobre 2026');
