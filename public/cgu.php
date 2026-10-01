<?php require_once '../config/entreprise.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conditions générales d'utilisation - ZagiArtRenov</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<?php include('../templates/header.php'); ?>

    <article class="page_legale">
        <h1>Conditions générales d'utilisation</h1>
        <p class="page_maj">Dernière mise à jour : <?php echo ENT_MAJ_LEGALE; ?></p>

        <h2>1. Objet</h2>
        <p>
            Les présentes conditions encadrent l'utilisation du site de <?php echo ENT_NOM_COMMERCIAL; ?>.
            En naviguant sur le site, vous les acceptez. L'éditeur est présenté dans les
            <a href="mentions-legales.php">mentions légales</a>.
        </p>

        <h2>2. Accès au site</h2>
        <p>
            Le site est accessible gratuitement. L'éditeur s'efforce d'en assurer la disponibilité mais ne peut
            garantir un accès sans interruption, notamment en cas de maintenance ou de panne.
        </p>

        <h2>3. Informations présentées</h2>
        <p>
            Les descriptions de prestations et les photographies sont données à titre indicatif et ne constituent
            pas une offre contractuelle. Seul le devis écrit, établi gratuitement après étude de votre projet,
            précise le prix, le contenu des travaux, les délais et les conditions d'exécution.
        </p>

        <h2>4. Demande de devis et prestations</h2>
        <p>
            L'envoi du formulaire de contact n'engage ni vous ni l'entreprise. Un contrat n'est formé qu'à la
            signature du devis. Les conditions de la prestation (prix, modalités de paiement, garanties légales,
            assurance) figurent sur le devis et les conditions générales de vente qui l'accompagnent.
        </p>
        <p>
            Si vous êtes un consommateur et que le contrat est signé hors des locaux de l'entreprise (par exemple
            à votre domicile), vous disposez d'un délai de rétractation de 14 jours à compter de la signature
            (articles L221-18 et suivants du Code de la consommation), sauf exceptions prévues par la loi,
            notamment pour les travaux d'urgence que vous avez expressément demandés à votre domicile.
        </p>

        <h2>5. Avis clients</h2>
        <p>En déposant un avis, vous vous engagez à :</p>
        <ul>
            <li>décrire une expérience réelle et personnelle avec l'entreprise ;</li>
            <li>ne publier aucun propos injurieux, diffamatoire, discriminatoire ou sans rapport avec la prestation ;</li>
            <li>ne pas indiquer de données personnelles (adresse, téléphone, e-mail), les vôtres ou celles d'autrui.</li>
        </ul>
        <p>
            Les avis sont relus avant publication. Un avis ne respectant pas ces règles peut être refusé ;
            un avis n'est jamais refusé au seul motif qu'il est négatif. Aucun avis n'est rémunéré ni sollicité
            contre une contrepartie. Vous pouvez demander la modification ou la suppression de votre avis à tout
            moment en écrivant à <a href="mailto:<?php echo ENT_EMAIL; ?>"><?php echo ENT_EMAIL; ?></a>.
        </p>
        <p>
            En publiant un avis, vous autorisez <?php echo ENT_NOM_COMMERCIAL; ?> à l'afficher gratuitement sur ce
            site, tant qu'il n'est pas supprimé.
        </p>

        <h2>6. Propriété intellectuelle</h2>
        <p>
            Les textes, le logo et les photographies du site sont protégés par le droit d'auteur.
            Toute reproduction sans autorisation écrite est interdite.
        </p>

        <h2>7. Responsabilité</h2>
        <p>
            L'éditeur ne saurait être tenu responsable d'une utilisation du site non conforme aux présentes
            conditions, ni du contenu des sites externes vers lesquels des liens pourraient renvoyer.
        </p>

        <h2>8. Données personnelles</h2>
        <p>
            Voir la <a href="politique-confidentialite.php">politique de confidentialité et cookies</a>.
        </p>

        <h2>9. Droit applicable et litiges</h2>
        <p>
            Les présentes conditions sont soumises au droit français. En cas de litige, une solution amiable sera
            recherchée en priorité.
            <?php if (ENT_MEDIATEUR !== ''): ?>
            Le consommateur peut recourir gratuitement au médiateur de la consommation
            indiqué dans les <a href="mentions-legales.php">mentions légales</a>.
            <?php endif; ?>
            À défaut d'accord, les tribunaux français seront compétents.
        </p>
    </article>

<?php include('../templates/footer.php'); ?>

<script src="js/main.js"></script>
</body>
</html>
