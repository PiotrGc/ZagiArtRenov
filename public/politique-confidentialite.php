<?php require_once '../config/entreprise.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Politique de confidentialité et cookies - ZagiArtRenov</title>
    <link rel="stylesheet" href="css/styles.css?v=20261009b">
    <?php include "../templates/favicons.php"; ?>
</head>
<body>

<?php include('../templates/header.php'); ?>

    <article class="page_legale">
        <h1>Politique de confidentialité</h1>
        <p class="page_maj">Dernière mise à jour : <?php echo ENT_MAJ_LEGALE; ?></p>

        <p>
            Cette page explique quelles données personnelles sont collectées sur ce site, pourquoi, combien de temps
            elles sont conservées et comment exercer vos droits, conformément au Règlement général sur la protection
            des données (RGPD) et à la loi Informatique et Libertés.
        </p>

        <h2>Responsable du traitement</h2>
        <p>
            <?php echo ENT_NOM_COMMERCIAL; ?> — <?php echo ENT_DIRIGEANT; ?><br>
            <?php echo ENT_ADRESSE; ?>, <?php echo ENT_CP_VILLE; ?><br>
            E-mail : <a href="mailto:<?php echo ENT_EMAIL; ?>"><?php echo ENT_EMAIL; ?></a>
        </p>

        <h2 id="contact">Formulaire de demande de devis</h2>
        <div class="tableau_defilant">
            <table>
                <tbody>
                    <tr><th scope="row">Données collectées</th><td>Prénom, nom, adresse e-mail, téléphone (facultatif), type de prestation, description des travaux.</td></tr>
                    <tr><th scope="row">Finalité</th><td>Répondre à votre demande et établir un devis.</td></tr>
                    <tr><th scope="row">Base légale</th><td>Mesures précontractuelles prises à votre demande (art. 6.1.b RGPD).</td></tr>
                    <tr><th scope="row">Destinataires</th><td><?php echo ENT_NOM_COMMERCIAL; ?> uniquement. Le message est acheminé par le service de messagerie Gmail (Google Ireland Ltd), qui agit comme sous-traitant.</td></tr>
                    <tr><th scope="row">Durée de conservation</th><td>3 ans après le dernier échange si aucun contrat n'est conclu. Si un contrat est signé, les données sont conservées pendant la durée légale de conservation des pièces comptables (10 ans).</td></tr>
                </tbody>
            </table>
        </div>
        <p>Ces données ne sont pas enregistrées dans une base de données du site : elles sont uniquement transmises par e-mail.</p>

        <h2 id="avis">Formulaire d'avis clients</h2>
        <div class="tableau_defilant">
            <table>
                <tbody>
                    <tr><th scope="row">Données collectées</th><td>Prénom, nom ou initiales, ville (facultative), note, commentaire, date de dépôt.</td></tr>
                    <tr><th scope="row">Finalité</th><td>Publier votre avis sur le site après relecture.</td></tr>
                    <tr><th scope="row">Base légale</th><td>Votre consentement (art. 6.1.a RGPD), que vous pouvez retirer à tout moment.</td></tr>
                    <tr><th scope="row">Destinataires</th><td>Une fois publié, votre avis (avec le prénom ou nom et la ville indiqués) est visible par tous les visiteurs du site.</td></tr>
                    <tr><th scope="row">Durée de conservation</th><td>Tant que l'avis est publié. Un avis non publié est supprimé. Vous pouvez demander la suppression de votre avis à tout moment par e-mail.</td></tr>
                </tbody>
            </table>
        </div>

        <h2>Journaux techniques de l'hébergeur</h2>
        <p>
            Comme pour tout site web, l'hébergeur enregistre automatiquement des journaux de connexion (adresse IP,
            date, page demandée, navigateur) afin d'assurer la sécurité et le bon fonctionnement du service.
            Ces journaux sont conservés au maximum un an, conformément à la réglementation.
        </p>

        <h2>Ce que nous ne faisons pas</h2>
        <ul>
            <li>Aucune donnée n'est vendue, louée ou cédée à des tiers.</li>
            <li>Aucun outil de mesure d'audience, de publicité ou de suivi n'est utilisé.</li>
            <li>Aucun contenu tiers (réseau social, vidéo, carte, police d'écriture externe) n'est chargé : vos visites ne sont donc pas communiquées à d'autres sociétés.</li>
            <li>Aucune décision automatisée ni aucun profilage n'est réalisé.</li>
        </ul>

        <h2>Vos droits</h2>
        <p>
            Vous disposez d'un droit d'accès, de rectification, d'effacement, de limitation, d'opposition et de
            portabilité de vos données, ainsi que du droit de retirer votre consentement à tout moment et de définir
            des directives relatives au sort de vos données après votre décès.
        </p>
        <p>
            Pour exercer ces droits, écrivez à <a href="mailto:<?php echo ENT_EMAIL; ?>"><?php echo ENT_EMAIL; ?></a>.
            Une réponse vous sera apportée dans un délai d'un mois.
        </p>
        <p>
            Si vous estimez, après nous avoir contactés, que vos droits ne sont pas respectés, vous pouvez adresser
            une réclamation à la CNIL : <a href="https://www.cnil.fr/fr/plaintes">www.cnil.fr/fr/plaintes</a>.
        </p>

        <h2 id="cookies">Cookies</h2>
        <p>
            Ce site n'utilise <strong>aucun cookie publicitaire, de mesure d'audience ou de réseau social</strong>.
            Il dépose un seul cookie, strictement nécessaire à son fonctionnement :
        </p>
        <div class="tableau_defilant">
            <table>
                <thead>
                    <tr><th scope="col">Nom</th><th scope="col">Rôle</th><th scope="col">Durée</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PHPSESSID</td>
                        <td>Sécuriser l'envoi des formulaires (protection contre les envois frauduleux et le spam) et, pour l'administrateur, maintenir la connexion.</td>
                        <td>Supprimé à la fermeture du navigateur</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p>
            Ce cookie étant indispensable au service que vous demandez, il est exempté de consentement
            (article 82 de la loi Informatique et Libertés, lignes directrices de la CNIL). C'est pourquoi aucun
            bandeau de consentement n'est affiché. Vous pouvez néanmoins le bloquer dans les réglages de votre
            navigateur ; les formulaires ne fonctionneront alors plus.
        </p>
    </article>

<?php include('../templates/footer.php'); ?>

<script src="js/main.js"></script>
</body>
</html>
