<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil - ZAR</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

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
        <a href="contact.html" class="hero_cta">Demander un devis gratuit →</a>
    </section>

    <!-- PRESTATIONS -->
    <section class="prestations">
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
        <h2>Ce que disent mes clients</h2>
        <div class="temoignages_grid">
            <!-- Les témoignages seront ajoutés ici -->
        </div>
    </section>

    <!-- BANDE CTA -->
    <div class="cta_bande">
        <h2>Un projet ? Parlons-en.</h2>
        <p>Contactez-moi pour un devis gratuit et sans engagement</p>
        <a href="contact.html">Nous contacter</a>
    </div>

    <?php include('../php/footer.php'); ?>

    <script src="../js/main.js"></script>
</body>
</html>