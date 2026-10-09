<?php $page_courante = basename($_SERVER['SCRIPT_NAME'], '.php'); ?>
<a href="#contenu" class="lien_evitement">Aller au contenu</a>
<header>
    <nav aria-label="Navigation principale">
        <a href="index.php" class="nav_logo">
            <img src="img/logo_ZAR.png" alt="ZagiArtRenov, retour à l'accueil">
        </a>

        <button type="button" class="nav_burger" id="nav_burger" aria-label="Ouvrir le menu" aria-controls="nav_liens" aria-expanded="false">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </button>

        <div class="nav_liens" id="nav_liens">
            <a href="index.php" <?php echo $page_courante === 'index' ? 'aria-current="page"' : ''; ?>>Accueil</a>
            <a href="apropos.php" <?php echo $page_courante === 'apropos' ? 'aria-current="page"' : ''; ?>>À propos</a>
            <a href="contact.php" class="nav_cta" <?php echo $page_courante === 'contact' ? 'aria-current="page"' : ''; ?>>Demander un devis</a>
        </div>
    </nav>
</header>
<main id="contenu" tabindex="-1">
