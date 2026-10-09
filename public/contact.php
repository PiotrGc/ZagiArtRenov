<?php
session_set_cookie_params([
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
require_once '../config/entreprise.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact et demande de devis - ZagiArtRenov</title>
    <meta name="description" content="Demandez un devis gratuit à ZagiArtRenov pour vos travaux d'électricité, plomberie, peinture ou menuiserie en Île-de-France.">
    <link rel="stylesheet" href="css/styles.css?v=20261009b">
    <?php include "../templates/favicons.php"; ?>
</head>

<body>

<?php include('../templates/header.php'); ?>

    <section class="contact">

        <h1>Contactez-moi</h1>
        <p>Vous avez un projet ? Remplissez le formulaire ou contactez-moi directement.</p>

        <?php if (isset($_GET['envoye'])): ?>
            <p class="message_succes" role="status">Votre demande a bien été envoyée. Je vous réponds dès que possible.</p>
        <?php endif; ?>

        <?php if (isset($_GET['erreur'])): ?>
            <p class="message_erreur" role="alert">Votre demande n'a pas pu être envoyée. Vérifiez les champs obligatoires et réessayez, ou contactez-moi par téléphone.</p>
        <?php endif; ?>

        <?php if (isset($_GET['temps'])): ?>
            <p class="message_erreur" role="alert">Une demande a déjà été envoyée depuis ce navigateur il y a peu de temps. Pour la compléter, contactez-moi par téléphone ou par e-mail.</p>
        <?php endif; ?>

        <div class="contact_contenu">

            <form action="traitement.php" method="post" class="formulaire_devis" aria-labelledby="titre_formulaire">
                <h2 id="titre_formulaire" class="formulaire_titre">Demande de devis</h2>
                <p class="form_legende">Les champs marqués d'un astérisque (*) sont obligatoires.</p>

                <div class="form_ligne">
                    <div class="form_groupe">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" maxlength="100" autocomplete="given-name" required>
                    </div>
                    <div class="form_groupe">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" maxlength="100" autocomplete="family-name" required>
                    </div>
                </div>
                <div class="form_groupe">
                    <label for="email">E-mail *</label>
                    <input type="email" id="email" name="email" maxlength="254" autocomplete="email" aria-describedby="email_aide" required>
                    <span class="form_aide" id="email_aide">Format attendu : nom@exemple.fr</span>
                </div>
                <div class="form_groupe">
                    <label for="tel">Téléphone (facultatif)</label>
                    <input type="tel" id="tel" name="tel" maxlength="20" autocomplete="tel" aria-describedby="tel_aide">
                    <span class="form_aide" id="tel_aide">Uniquement si vous préférez être rappelé.</span>
                </div>
                <div class="form_groupe">
                    <label for="presta">Type de prestation *</label>
                    <select id="presta" name="presta" required>
                        <option value="" disabled selected>Choisir une prestation</option>
                        <option value="electricite">Électricité</option>
                        <option value="plomberie">Plomberie</option>
                        <option value="peinture">Peinture</option>
                        <option value="menuiserie">Menuiserie</option>
                    </select>
                </div>
                <div class="form_groupe">
                    <label for="description">Décrivez vos travaux *</label>
                    <textarea id="description" name="description" rows="5" maxlength="3000" aria-describedby="description_aide" required></textarea>
                    <span class="form_aide" id="description_aide">Nature des travaux, surface approximative, commune, délais souhaités.</span>
                </div>

                <!-- Champ piège anti-spam : invisible pour les humains, rempli par les robots -->
                <div class="champ_piege" aria-hidden="true">
                    <label for="site">Ne pas remplir</label>
                    <input type="text" id="site" name="site" tabindex="-1" autocomplete="off">
                </div>

                <div class="form_consentement">
                    <input type="checkbox" id="consentement" name="consentement" value="1" required>
                    <label for="consentement">
                        J'accepte que les informations saisies soient utilisées pour me recontacter au sujet de ma demande de devis. *
                    </label>
                </div>
                <p class="form_rgpd">
                    Vos données sont transmises uniquement à <?php echo ENT_NOM_COMMERCIAL; ?>, ne sont jamais revendues
                    et sont supprimées au plus tard 3 ans après notre dernier échange.
                    <a href="politique-confidentialite.php#contact">En savoir plus sur vos données et vos droits</a>
                </p>

                <div class="form_groupe">
                    <button type="submit">Envoyer ma demande de devis</button>
                </div>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            </form>

            <div class="contact_coordonnees">
                <h2 class="coordonnees_titre">Coordonnées</h2>
                <p>Téléphone : <a href="tel:<?php echo ENT_TEL_LIEN; ?>"><?php echo ENT_TEL_AFFICHE; ?></a></p>
                <p>E-mail : <a href="mailto:<?php echo ENT_EMAIL; ?>"><?php echo ENT_EMAIL; ?></a></p>
                <p>Zone d'intervention : <?php echo ENT_ZONE; ?></p>
                <p>Horaires : <?php echo ENT_HORAIRES; ?></p>
            </div>

        </div>
    </section>

    <?php include('../templates/footer.php'); ?>

    <script src="js/main.js"></script>
</body>
</html>
