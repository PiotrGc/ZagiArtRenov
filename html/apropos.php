<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>À propos - ZAR</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<?php include('../php/header.php'); ?>

    <!-- HERO À PROPOS -->
    <section class="about_hero">
        <div class="about_photo">
            <img src="../img/photo_profil.jpg" alt="Photo de profil">
        </div>
        <div class="about_info">
            <h1>Zaganovic Zvezdan</h1>
            <p class="about_subtitle">Artisan BTP · Île-de-France</p>
            <p class="about_bio">Je mets mon savoir-faire au service de vos projets avec rigueur et honnêteté. Basé en Île-de-France, j'interviens rapidement chez les particuliers comme les professionnels.</p>
        </div>
    </section>

    <!-- EXPERTISE -->
    <section class="expertise">
        <h2>Mon expertise</h2>
        <div class="expertise_liste">

            <div class="expertise_item">
                <div>
                    <h3>Électricité</h3>
                    <p>Installation complète, mise aux normes NF C 15-100, remplacement de tableau électrique, pose de prises et interrupteurs, éclairage intérieur et extérieur.</p>
                </div>
            </div>

            <div class="expertise_item">
                <div>
                    <h3>Plomberie</h3>
                    <p>Détection et réparation de fuites, installation de sanitaires, remplacement de chauffe-eau, rénovation de salle de bain complète.</p>
                </div>
            </div>

            <div class="expertise_item">
                <div>
                    <h3>Peinture</h3>
                    <p>Travaux de peinture intérieure et extérieure, préparation et rebouchage des murs, pose de papier peint, finitions soignées.</p>
                </div>
            </div>

            <div class="expertise_item">
                <div>
                    <h3>Menuiserie</h3>
                    <p>Pose de portes et fenêtres, installation de parquet, fabrication et pose de placards sur-mesure, travail du bois en général.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- ENGAGEMENTS -->
    <section class="engagements">
        <h2>Mes engagements</h2>
        <div class="engagements_grid">

            <div class="engagement_card">
                <h3>Qualité garantie</h3>
                <p>Finitions soignées et matériaux sélectionnés pour un résultat durable.</p>
            </div>

            <div class="engagement_card">
                <h3>Ponctualité</h3>
                <p>Respect des délais convenus, je m'engage sur des dates et je les tiens.</p>
            </div>

            <div class="engagement_card">
                <h3>Transparence</h3>
                <p>Devis détaillé et gratuit, sans mauvaise surprise sur la facture finale.</p>
            </div>

        </div>
    </section>

    <!-- RÉALISATIONS -->
    <section class="realisations">
        <h2>Quelques réalisations</h2>
        <div class="galerie">
            <div class="galerie_item"><img src="../img/real1.jpg" alt="Réalisation électricité"></div>
            <div class="galerie_item"><img src="../img/real2.jpg" alt="Réalisation plomberie"></div>
            <div class="galerie_item"><img src="../img/real3.jpg" alt="Réalisation peinture"></div>
            <div class="galerie_item"><img src="../img/real4.jpg" alt="Réalisation menuiserie"></div>
        </div>
    </section>

    <!-- BANDE CTA -->
    <div class="cta_bande">
        <h2>Prêt à démarrer votre projet ?</h2>
        <p>Contactez-moi pour un devis gratuit et sans engagement</p>
        <a href="contact.php">Demander un devis</a>
    </div>

    <?php include('../php/footer.php'); ?>

    <script src="../js/main.js"></script>
</body>
</html>