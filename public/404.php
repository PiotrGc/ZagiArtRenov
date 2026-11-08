<?php
require_once '../config/entreprise.php';
// Servie par Apache (ErrorDocument dans .htaccess) : le code 404 est conservé.
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <!-- Les liens du header et du footer sont relatifs : sans ça, ils cassent sur /dossier/page-inconnue -->
    <base href="/">
    <title>Page introuvable - ZagiArtRenov</title>
    <link rel="stylesheet" href="/css/styles.css?v=20261108">
    <?php include "../templates/favicons.php"; ?>
</head>
<body>

<?php include('../templates/header.php'); ?>

    <section class="cta_bande">
        <h1>Page introuvable</h1>
        <p>Cette adresse n'existe pas ou plus sur le site. Pour un devis ou une question, l'accueil et le téléphone sont là.</p>
        <div class="cta_actions">
            <a href="/">Retour à l'accueil</a>
            <a href="tel:<?php echo ENT_TEL_LIEN; ?>" class="cta_tel"><?php echo ENT_TEL_AFFICHE; ?></a>
        </div>
    </section>

<?php include('../templates/footer.php'); ?>

<script src="/js/main.js"></script>
</body>
</html>
