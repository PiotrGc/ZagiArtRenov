<?php require_once '../config/entreprise.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mentions légales - ZagiArtRenov</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<?php include('../templates/header.php'); ?>

    <article class="page_legale">
        <h1>Mentions légales</h1>
        <p class="page_maj">Dernière mise à jour : <?php echo ENT_MAJ_LEGALE; ?></p>

        <h2>Éditeur du site</h2>
        <p>
            <?php echo ENT_RAISON_SOCIALE; ?> (nom commercial : <?php echo ENT_NOM_COMMERCIAL; ?>)<br>
            <?php echo ENT_FORME; ?><br>
            Gérant : <?php echo ENT_DIRIGEANT; ?><br>
            Adresse : <?php echo ENT_ADRESSE; ?>, <?php echo ENT_CP_VILLE; ?><br>
            Téléphone : <a href="tel:<?php echo ENT_TEL_LIEN; ?>"><?php echo ENT_TEL_AFFICHE; ?></a><br>
            E-mail : <a href="mailto:<?php echo ENT_EMAIL; ?>"><?php echo ENT_EMAIL; ?></a><br>
            SIRET : <?php echo ENT_SIRET; ?><br>
            <?php echo ENT_RNE; ?><br>
            TVA : <?php echo ENT_TVA; ?>
        </p>
        <p>Directeur de la publication : <?php echo ENT_DIRIGEANT; ?>.</p>

        <h2>Hébergement</h2>
        <p><?php echo ENT_HEBERGEUR; ?></p>

        <?php if (ENT_ASSURANCE !== ''): ?>
        <h2>Assurance professionnelle</h2>
        <p>Assurance responsabilité civile décennale : <?php echo ENT_ASSURANCE; ?></p>
        <?php endif; ?>

        <?php if (ENT_MEDIATEUR !== ''): ?>
        <h2>Médiation de la consommation</h2>
        <p>
            Conformément aux articles L612-1 et suivants du Code de la consommation, en cas de litige non résolu
            directement avec l'entreprise, le client consommateur peut recourir gratuitement au médiateur suivant :
        </p>
        <p><?php echo ENT_MEDIATEUR; ?></p>
        <?php endif; ?>

        <h2>Propriété intellectuelle</h2>
        <p>
            Les textes, le logo et les photographies de réalisations présents sur ce site sont la propriété de
            <?php echo ENT_NOM_COMMERCIAL; ?>. Les photographies montrent des chantiers réalisés par l'entreprise.
            Toute reproduction, totale ou partielle, sans autorisation écrite préalable est interdite.
        </p>
        <p>
            Polices de caractères : Bebas Neue (© Dharma Type) et Inter (© The Inter Project Authors),
            utilisées sous licence SIL Open Font License 1.1.
        </p>

        <h2>Données personnelles et cookies</h2>
        <p>
            Le traitement de vos données personnelles est décrit dans la
            <a href="politique-confidentialite.php">politique de confidentialité</a>.
            Ce site n'utilise qu'un cookie technique, sans publicité ni mesure d'audience :
            voir la <a href="politique-confidentialite.php#cookies">section Cookies</a>.
        </p>
    </article>

<?php include('../templates/footer.php'); ?>

<script src="js/main.js"></script>
</body>
</html>
