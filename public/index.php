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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil - ZagiArtRenov, artisan rénovation en Île-de-France</title>
    <meta name="description" content="ZagiArtRenov : électricité, plomberie, peinture et menuiserie pour particuliers et professionnels à Paris et en Île-de-France.">
    <link rel="stylesheet" href="css/styles.css">
    <?php include "../templates/favicons.php"; ?>
</head>
<body
    data-avis-envoye="<?php echo isset($_GET['avis_envoye']) ? '1' : '0'; ?>"
    data-avis-erreur="<?php echo isset($_GET['avis_erreur'])  ? '1' : '0'; ?>"
>

<?php include('../templates/header.php'); ?>

    <!-- HERO -->
    <section class="hero">
        <h1>Votre artisan <span>multi-services</span><br>en Île-de-France</h1>
        <p>Rénovation et dépannage pour particuliers et professionnels.<br>Devis gratuit et sans engagement, du lundi au samedi.</p>
        <ul class="hero_services">
            <li>Électricité</li>
            <li>Plomberie</li>
            <li>Peinture</li>
            <li>Menuiserie</li>
        </ul>
        <a href="contact.php" class="hero_cta">Demander un devis gratuit</a>
    </section>

    <!-- PRESTATIONS -->
    <section class="prestations" aria-labelledby="titre_prestations">
        <p class="section_label">Ce que je fais</p>
        <h2 id="titre_prestations">Mes prestations</h2>
        <div class="cards_grid">

            <div class="card_prestation">
                <h3>Électricité</h3>
                <p>Installation, dépannage, remplacement de tableau électrique, prises, éclairage LED.</p>
            </div>

            <div class="card_prestation">
                <h3>Plomberie</h3>
                <p>Réparation de fuites, installation de sanitaires, cumulus, robinetterie, chasse d'eau.</p>
            </div>

            <div class="card_prestation">
                <h3>Peinture</h3>
                <p>Intérieur et extérieur, préparation des murs, finitions, pose de papier peint.</p>
            </div>

            <div class="card_prestation">
                <h3>Menuiserie</h3>
                <p>Portes, fenêtres, parquet, pose de placards, travaux sur mesure en bois.</p>
            </div>

        </div>
    </section>

    <!-- TÉMOIGNAGES -->
    <section class="temoignages" aria-labelledby="titre_avis">

        <div class="temoignages_header">
            <div>
                <p class="section_label">Avis clients</p>
                <h2 id="titre_avis">Ce que disent mes clients</h2>
            </div>
            <button type="button" class="btn_avis_toggle" id="btn_toggle_avis" aria-expanded="false" aria-controls="avis_formulaire">Laisser un avis</button>
        </div>

        <!-- Information obligatoire sur le traitement des avis (art. L111-7-2 et D111-17 Code de la consommation) -->
        <p class="avis_info">
            Les avis sont déposés librement par les clients via le formulaire ci-dessous, sans contrepartie.
            Ils sont relus avant publication pour écarter les contenus injurieux, hors sujet ou contenant des données personnelles ;
            un avis n'est jamais refusé parce qu'il est négatif. Ils sont affichés du plus récent au plus ancien, avec leur date de dépôt.
            Aucune vérification de la réalisation d'une prestation n'est effectuée.
        </p>

        <!-- Affichage des avis -->
        <div class="temoignages_grid">
            <?php
            include('../config/connexion.php');
            $stmt = $conn->query("SELECT nom, ville, note, commentaire, date FROM avis WHERE valide = 1 ORDER BY date DESC");
            $count = 0;
            while ($avis = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $count++;
                $note = (int)$avis['note'];
            ?>
                <article class="temoignage">
                    <div class="temoignage_top">
                        <span class="temoignage_etoiles" role="img" aria-label="Note : <?php echo $note; ?> sur 5">
                            <?php echo str_repeat('★', $note) . str_repeat('☆', 5 - $note); ?>
                        </span>
                        <time class="temoignage_date" datetime="<?php echo date('Y-m-d', strtotime($avis['date'])); ?>">
                            <?php echo date('d/m/Y', strtotime($avis['date'])); ?>
                        </time>
                    </div>
                    <p>« <?php echo htmlspecialchars($avis['commentaire']); ?> »</p>
                    <span class="temoignage_auteur">
                        <?php echo htmlspecialchars($avis['nom']); ?>
                        <?php if ($avis['ville'] !== ''): ?>
                            <span class="temoignage_ville">— <?php echo htmlspecialchars($avis['ville']); ?></span>
                        <?php endif; ?>
                    </span>
                </article>
            <?php } ?>

            <?php if ($count === 0): ?>
                <p class="temoignages_vide">
                    Aucun avis pour le moment. Vous avez fait appel à mes services ? Laissez le premier avis.
                </p>
            <?php endif; ?>
        </div>

        <!-- Formulaire avis (caché par défaut, ouvert par main.js) -->
        <div class="avis_formulaire" id="avis_formulaire" hidden>

            <h3>Votre avis</h3>

            <?php if (isset($_GET['avis_envoye'])): ?>
                <p class="message_succes" role="status">Merci ! Votre avis sera publié après relecture.</p>
            <?php endif; ?>

            <?php if (isset($_GET['avis_erreur'])): ?>
                <p class="message_erreur" role="alert">Votre avis n'a pas pu être envoyé. Vérifiez les champs obligatoires, ou réessayez dans quelques minutes.</p>
            <?php endif; ?>

            <p class="form_legende">Les champs marqués d'un astérisque (*) sont obligatoires.</p>

            <form action="traitement_avis.php" method="post">
                <div class="avis_form_row">
                    <div class="form_groupe">
                        <label for="avis_nom">Prénom ou nom *</label>
                        <input type="text" id="avis_nom" name="nom" maxlength="100" autocomplete="given-name" aria-describedby="avis_nom_aide" required>
                        <span class="form_aide" id="avis_nom_aide">Publié avec l'avis. Un prénom ou des initiales suffisent.</span>
                    </div>
                    <div class="form_groupe">
                        <label for="avis_ville">Ville (facultatif)</label>
                        <input type="text" id="avis_ville" name="ville" maxlength="100" autocomplete="address-level2" aria-describedby="avis_ville_aide">
                        <span class="form_aide" id="avis_ville_aide">Publiée avec l'avis.</span>
                    </div>
                    <div class="form_groupe">
                        <label for="avis_note">Note *</label>
                        <select id="avis_note" name="note" required>
                            <option value="" disabled selected>Choisir une note</option>
                            <option value="5">5 sur 5 — Excellent</option>
                            <option value="4">4 sur 5 — Très bien</option>
                            <option value="3">3 sur 5 — Bien</option>
                            <option value="2">2 sur 5 — Moyen</option>
                            <option value="1">1 sur 5 — Décevant</option>
                        </select>
                    </div>
                </div>
                <div class="form_groupe">
                    <label for="avis_commentaire">Commentaire *</label>
                    <textarea id="avis_commentaire" name="commentaire" rows="3" maxlength="500" aria-describedby="avis_commentaire_aide" required></textarea>
                    <span class="form_aide" id="avis_commentaire_aide">
                        500 caractères maximum. N'indiquez ni adresse, ni téléphone, ni e-mail.
                        <span class="compteur_chars" aria-hidden="true"><span id="compteur_actuel">0</span> / 500</span>
                    </span>
                </div>

                <!-- Champ piège anti-spam : invisible pour les humains, rempli par les robots -->
                <div class="champ_piege" aria-hidden="true">
                    <label for="avis_site">Ne pas remplir</label>
                    <input type="text" id="avis_site" name="site" tabindex="-1" autocomplete="off">
                </div>

                <div class="form_consentement">
                    <input type="checkbox" id="avis_consentement" name="consentement" value="1" required>
                    <label for="avis_consentement">
                        J'accepte que mon avis, mon prénom ou nom et, le cas échéant, ma ville soient publiés sur ce site.
                        Je peux en demander la suppression à tout moment. *
                    </label>
                </div>
                <p class="form_rgpd">
                    Vos données servent uniquement à publier votre avis.
                    <a href="politique-confidentialite.php#avis">En savoir plus sur vos données et vos droits</a>
                </p>

                <div class="avis_form_actions">
                    <button type="button" id="btn_annuler_avis" class="btn_annuler">Annuler</button>
                    <button type="submit" class="btn_soumettre">Envoyer mon avis</button>
                </div>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            </form>

        </div>

    </section>

    <!-- BANDE CTA -->
    <div class="cta_bande">
        <h2>Un projet ? Parlons-en.</h2>
        <p>Contactez-moi pour un devis gratuit et sans engagement.</p>
        <a href="contact.php">Me contacter</a>
    </div>

<?php include('../templates/footer.php'); ?>

<script src="js/main.js"></script>
</body>
</html>
