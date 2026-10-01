<?php require_once __DIR__ . '/../config/entreprise.php'; ?>
</main>
<footer>
    <div class="footer_inner">

        <div class="footer_gauche">
            <img src="img/logo_ZAR.png" alt="" class="footer_logo">
            <nav aria-label="Pied de page">
                <ul class="footer_liens">
                    <li><a href="index.php" class="footer_nav_lien">Accueil</a></li>
                    <li><a href="apropos.php" class="footer_nav_lien">À propos</a></li>
                    <li><a href="contact.php" class="footer_nav_lien">Contact</a></li>
                </ul>
            </nav>
        </div>

        <div class="footer_droite">
            <p class="footer_nom"><?php echo ENT_NOM_COMMERCIAL; ?> — <?php echo ENT_DIRIGEANT; ?></p>
            <p><?php echo ENT_ADRESSE; ?>, <?php echo ENT_CP_VILLE; ?></p>
            <p>Tél. : <a href="tel:<?php echo ENT_TEL_LIEN; ?>"><?php echo ENT_TEL_AFFICHE; ?></a></p>
            <p>E-mail : <a href="mailto:<?php echo ENT_EMAIL; ?>"><?php echo ENT_EMAIL; ?></a></p>
            <p>SIRET : <?php echo ENT_SIRET; ?></p>
        </div>

    </div>

    <div class="footer_bas">
        <ul class="footer_legal">
            <li><a href="mentions-legales.php">Mentions légales</a></li>
            <li><a href="politique-confidentialite.php">Politique de confidentialité</a></li>
            <li><a href="politique-confidentialite.php#cookies">Cookies</a></li>
            <li><a href="cgu.php">Conditions d'utilisation</a></li>
        </ul>
        <p class="footer_copyright">© <?php echo date('Y'); ?> <?php echo ENT_NOM_COMMERCIAL; ?>. Textes et photographies : tous droits réservés.</p>
    </div>
</footer>
