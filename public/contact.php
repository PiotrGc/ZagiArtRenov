<?php
session_set_cookie_params([
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact - ZAR</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body>

<?php include('../templates/header.php'); ?>

    <section class="contact">

        <?php if (isset($_GET['envoye'])): ?>
            <p id="message_confirmation" class="message_succes">Votre message a bien été envoyé !</p>
        <?php endif; ?>

        <?php if (isset($_GET['erreur'])): ?>
            <p id="message_confirmation" class="message_erreur">Une erreur est survenue, veuillez réessayer.</p>
        <?php endif; ?>

        <?php if (isset($_GET['temps'])): ?>
            <p id="message_confirmation" class="message_erreur">Veuillez attendre avant d'envoyer un nouveau message.</p>
        <?php endif; ?>

        <h1>Contactez-moi</h1>
        <p>Vous avez un projet ? Remplissez le formulaire ou contactez-moi directement.</p>

        <div class="contact_contenu">

            <form action="traitement.php" method="post" class="formulaire_devis">
                <div class="form_groupe">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Jean" required>
                </div>
                <div class="form_groupe">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="Dupont" required>
                </div>
                <div class="form_groupe">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="votre@email.fr" required>
                </div>
                <div class="form_groupe">
                    <label for="tel">Téléphone</label>
                    <input type="tel" id="tel" name="tel" placeholder="06 xx xx xx xx" required>
                </div>
                <div class="form_groupe">
                    <label for="presta">Type de prestation</label>
                    <select id="presta" name="presta" required>
                        <option value="" disabled selected>Choisir...</option>
                        <option value="electricite">Électricité</option>
                        <option value="plomberie">Plomberie</option>
                        <option value="peinture">Peinture</option>
                        <option value="menuiserie">Menuiserie</option>
                    </select>
                </div>
                <div class="form_groupe">
                    <label for="description">Décrivez vos travaux</label>
                    <textarea id="description" name="description" rows="5" placeholder="Décrivez votre projet, la surface, les délais souhaités..."></textarea>
                </div>
                <div class="form_groupe">
                    <button type="submit">Envoyer ma demande</button>
                </div>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            </form>

            <div class="contact_coordonnees">
                <p>Téléphone : <a href="tel:+33676091120">06 76 09 11 20</a></p>
                <p>Email : <a href="mailto:zagiartrenov@gmail.com">zagiartrenov@gmail.com</a></p>
                <p>Zone d'intervention : Paris & Île-de-France</p>
                <p>Horaires : Lun–Sam 8h–17h + urgence Dim</p>
                <div id="map"></div>
            </div>

        </div>
    </section>

    <?php include('../templates/footer.php'); ?>

    <script src="js/main.js"></script>
</body>
</html>