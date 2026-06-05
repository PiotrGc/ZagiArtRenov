<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil - ZAR</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body
    data-avis-envoye="<?php echo isset($_GET['avis_envoye']) ? '1' : '0'; ?>"
    data-avis-erreur="<?php echo isset($_GET['avis_erreur'])  ? '1' : '0'; ?>"
>

<?php include('../php/header.php'); ?>

    <!-- HERO -->
    <section class="hero">
        <span class="hero_badge">Artisan certifié</span>
        <h1>Votre artisan <span>multi-services</span><br>en Île-de-France</h1>
        <p>Travaux de qualité pour particuliers et professionnels.<br>Devis gratuit sous 48h — Disponible 6j/7</p>
        <div class="hero_services">
            <span>Électricité</span>
            <span>Plomberie</span>
            <span>Peinture</span>
            <span>Menuiserie</span>
        </div>
        <a href="contact.php" class="hero_cta">Demander un devis gratuit →</a>
    </section>

    <!-- PRESTATIONS -->
    <section class="prestations">
        <p class="section_label">Ce que je fais</p>
        <h2>Mes prestations</h2>
        <div class="cards_grid">

            <div class="card_prestation">
                <h3>Électricité</h3>
                <p>Installation, dépannage, mise aux normes tableau électrique, prises, éclairage LED.</p>
            </div>

            <div class="card_prestation">
                <h3>Plomberie</h3>
                <p>Réparation fuite, installation sanitaires, cumulus, robinetterie, chasse d'eau.</p>
            </div>

            <div class="card_prestation">
                <h3>Peinture</h3>
                <p>Intérieur & extérieur, préparation murs, finition soignée, pose de papier peint.</p>
            </div>

            <div class="card_prestation">
                <h3>Menuiserie</h3>
                <p>Portes, fenêtres, parquet, pose de placard, travaux sur-mesure en bois.</p>
            </div>

        </div>
    </section>

    <!-- TÉMOIGNAGES -->
    <section class="temoignages">

        <div class="temoignages_header">
            <div>
                <p class="section_label">Avis clients</p>
                <h2>Ce que disent mes clients</h2>
            </div>
            <button class="btn_avis_toggle" id="btn_toggle_avis">+ Laisser un avis</button>
        </div>

        <!-- Affichage des avis -->
        <div class="temoignages_grid">
            <?php
            include('../php/connexion.php');
            $stmt = $conn->query("SELECT * FROM avis WHERE valide = 1 ORDER BY date DESC");
            $count = 0;
            while ($avis = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $count++;
            ?>
                <div class="temoignage">
                    <div class="temoignage_top">
                        <span class="temoignage_etoiles">
                            <?php echo str_repeat('★', $avis['note']) . str_repeat('☆', 5 - $avis['note']); ?>
                        </span>
                        <span class="temoignage_date">
                            <?php echo date('d/m/Y', strtotime($avis['date'])); ?>
                        </span>
                    </div>
                    <p>« <?php echo htmlspecialchars($avis['commentaire']); ?> »</p>
                    <span class="temoignage_auteur">
                        <?php echo htmlspecialchars($avis['nom']); ?>
                        <span class="temoignage_ville">— <?php echo htmlspecialchars($avis['ville']); ?></span>
                    </span>
                </div>
            <?php } ?>

            <?php if ($count === 0): ?>
                <p style="color: var(--muted); font-size: 0.9rem; grid-column: 1/-1; padding: 20px 0;">
                    Aucun avis pour le moment. Soyez le premier à laisser un commentaire !
                </p>
            <?php endif; ?>
        </div>

        <!-- Formulaire avis (caché par défaut) -->
        <div class="avis_formulaire" id="avis_formulaire" style="display:none;">

            <h3>Votre avis</h3>

            <?php if (isset($_GET['avis_envoye'])): ?>
                <p class="message_succes">Merci ! Votre avis sera publié après validation.</p>
            <?php endif; ?>

            <?php if (isset($_GET['avis_erreur'])): ?>
                <p class="message_erreur">Une erreur est survenue, veuillez réessayer.</p>
            <?php endif; ?>

            <form action="../php/traitement_avis.php" method="post">
                <div class="avis_form_row">
                    <div class="form_groupe">
                        <label for="avis_nom">Nom</label>
                        <input type="text" id="avis_nom" name="nom" placeholder="Jean Dupont" required>
                    </div>
                    <div class="form_groupe">
                        <label for="avis_ville">Ville</label>
                        <input type="text" id="avis_ville" name="ville" placeholder="Paris" required>
                    </div>
                    <div class="form_groupe">
                        <label for="avis_note">Note</label>
                        <select id="avis_note" name="note" required>
                            <option value="" disabled selected>Note...</option>
                            <option value="5">★★★★★ — Excellent</option>
                            <option value="4">★★★★☆ — Très bien</option>
                            <option value="3">★★★☆☆ — Bien</option>
                            <option value="2">★★☆☆☆ — Moyen</option>
                            <option value="1">★☆☆☆☆ — Décevant</option>
                        </select>
                    </div>
                </div>
                <div class="form_groupe">
                    <label for="avis_commentaire">
                        Commentaire
                        <span class="compteur_chars"><span id="compteur_actuel">0</span> / 500</span>
                    </label>
                    <textarea id="avis_commentaire" name="commentaire" rows="3" placeholder="Décrivez votre expérience..." maxlength="500" required></textarea>
                    <span class="compteur_hint">Maximum 500 caractères</span>
                </div>
                <div class="avis_form_actions">
                    <button type="button" id="btn_annuler_avis" class="btn_annuler">Annuler</button>
                    <button type="submit" class="btn_soumettre">Envoyer mon avis →</button>
                </div>
            </form>

        </div>

    </section>

    <!-- BANDE CTA -->
    <div class="cta_bande">
        <h2>Un projet ? Parlons-en.</h2>
        <p>Contactez-moi pour un devis gratuit et sans engagement</p>
        <a href="contact.php">Nous contacter →</a>
    </div>

<?php include('../php/footer.php'); ?>

<script src="../js/main.js"></script>
</body>
</html>