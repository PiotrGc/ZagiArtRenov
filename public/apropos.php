<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>À propos - ZagiArtRenov</title>
    <meta name="description" content="Zvezdan Zaganovic, artisan en rénovation à Paris et en Île-de-France : électricité, plomberie, peinture et menuiserie.">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<?php include('../templates/header.php'); ?>

    <!-- HERO À PROPOS -->
    <section class="about_hero">
        <div class="about_photo">
            <img src="img/photo_profil.jpg" alt="Portrait de Zvezdan Zaganovic">
        </div>
        <div class="about_info">
            <h1>Zvezdan Zaganovic</h1>
            <p class="about_subtitle">Artisan en rénovation · Île-de-France</p>
            <p class="about_bio">Je mets mon savoir-faire au service de vos projets avec rigueur et honnêteté. J'interviens à Paris et en Île-de-France, chez les particuliers comme chez les professionnels.</p>
        </div>
    </section>

    <!-- EXPERTISE -->
    <section class="expertise" aria-labelledby="titre_expertise">
        <h2 id="titre_expertise">Mon expertise</h2>
        <div class="expertise_liste">

            <div class="expertise_item">
                <h3>Électricité</h3>
                <p>Installation et rénovation électrique, remplacement de tableau électrique, pose de prises et interrupteurs, éclairage intérieur et extérieur.</p>
            </div>

            <div class="expertise_item">
                <h3>Plomberie</h3>
                <p>Recherche et réparation de fuites, installation de sanitaires, remplacement de chauffe-eau, rénovation complète de salle de bain.</p>
            </div>

            <div class="expertise_item">
                <h3>Peinture</h3>
                <p>Peinture intérieure et extérieure, préparation et rebouchage des murs, pose de papier peint, finitions.</p>
            </div>

            <div class="expertise_item">
                <h3>Menuiserie</h3>
                <p>Pose de portes et fenêtres, installation de parquet, fabrication et pose de placards sur mesure, travail du bois en général.</p>
            </div>

        </div>
    </section>

    <!-- ENGAGEMENTS -->
    <section class="engagements" aria-labelledby="titre_engagements">
        <h2 id="titre_engagements">Ma façon de travailler</h2>
        <div class="engagements_grid">

            <div class="engagement_card">
                <h3>Travail soigné</h3>
                <p>Des finitions propres et des matériaux choisis avec vous en fonction de votre budget.</p>
            </div>

            <div class="engagement_card">
                <h3>Délais convenus ensemble</h3>
                <p>Les dates d'intervention sont fixées avec vous avant le début du chantier et inscrites au devis.</p>
            </div>

            <div class="engagement_card">
                <h3>Devis détaillé</h3>
                <p>Un devis gratuit et détaillé, poste par poste, avant tout engagement de votre part.</p>
            </div>

        </div>
    </section>

<!-- RÉALISATIONS -->
<section class="realisations" aria-labelledby="titre_realisations">
    <h2 id="titre_realisations">Quelques réalisations</h2>
    <p class="realisations_intro">Photos avant / après de chantiers réalisés par l'entreprise. Cliquez sur une photo pour l'agrandir.</p>

    <div class="galerie_carousel">
        <button type="button" class="galerie_prev" aria-label="Photos précédentes">‹</button>

        <div class="galerie_viewport">
            <ul class="galerie_track">
                <li class="galerie_item"><button type="button" class="galerie_bouton"><img src="img/chambre.jpg" alt="Chambre avant / après : murs jaunis et moquette usée, puis murs repeints en blanc, sol stratifié et placard posé"></button></li>
                <li class="galerie_item"><button type="button" class="galerie_bouton"><img src="img/chambre2.jpg" alt="Deuxième chambre avant / après : papier peint décollé et sol abîmé, puis murs blancs, parquet stratifié et nouvel éclairage"></button></li>
                <li class="galerie_item"><button type="button" class="galerie_bouton"><img src="img/cuisine.jpg" alt="Cuisine avant / après : meubles en bois des années 70 et faïence orange, puis cuisine blanche équipée avec plan de travail noir et carrelage métro"></button></li>
                <li class="galerie_item"><button type="button" class="galerie_bouton"><img src="img/salle_de_bain.jpg" alt="Salle de bain avant / après : carrelage rose ancien et baignoire jaunie, puis faïence grise, baignoire neuve, meuble vasque et sèche-serviettes"></button></li>
                <li class="galerie_item"><button type="button" class="galerie_bouton"><img src="img/toilette.jpg" alt="Toilettes avant / après : murs sales et tuyauterie apparente, puis murs blancs, lave-mains, carrelage clair et nouvelle alimentation en eau"></button></li>
                <li class="galerie_item"><button type="button" class="galerie_bouton"><img src="img/couloir.jpg" alt="Couloir d'entrée avant / après : boiseries défraîchies et tableau électrique apparent, puis murs et portes repeints en blanc"></button></li>
            </ul>
        </div>

        <button type="button" class="galerie_next" aria-label="Photos suivantes">›</button>
    </div>
</section>

    <!-- BANDE CTA -->
    <div class="cta_bande">
        <h2>Prêt à démarrer votre projet ?</h2>
        <p>Contactez-moi pour un devis gratuit et sans engagement.</p>
        <a href="contact.php">Demander un devis</a>
    </div>

    <?php include('../templates/footer.php'); ?>

    <!-- LIGHTBOX (hors de <main> pour le mode modal) -->
    <div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Photo agrandie" hidden>
        <button type="button" class="lightbox_fermer" id="lightbox_fermer" aria-label="Fermer la photo agrandie">×</button>
        <img src="" alt="" id="lightbox_img">
    </div>

    <script src="js/main.js"></script>
</body>
</html>
