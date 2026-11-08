<?php
// Adresse canonique (sans .php, comme les URLs réécrites par .htaccess) et aperçu des liens partagés
// (WhatsApp, Facebook, SMS...). Inclus dans le <head> de chaque page publique.
require_once __DIR__ . '/../config/entreprise.php';
$page_meta     = basename($_SERVER['SCRIPT_NAME'], '.php');
$url_canonique = ENT_SITE . '/' . ($page_meta === 'index' ? '' : $page_meta);
?>
<link rel="canonical" href="<?php echo $url_canonique; ?>">
<meta property="og:type" content="website">
<meta property="og:locale" content="fr_FR">
<meta property="og:site_name" content="<?php echo ENT_NOM_COMMERCIAL; ?>">
<meta property="og:url" content="<?php echo $url_canonique; ?>">
<meta property="og:title" content="<?php echo ENT_NOM_COMMERCIAL; ?>, artisan rénovation en Île-de-France">
<meta property="og:description" content="Électricité, plomberie, peinture et menuiserie à Paris et en Île-de-France. Devis gratuit et sans engagement.">
<meta property="og:image" content="<?php echo ENT_SITE; ?>/img/cuisine.jpg">
<meta property="og:image:width" content="900">
<meta property="og:image:height" content="1600">
<meta property="og:image:alt" content="Cuisine rénovée, avant et après les travaux">
