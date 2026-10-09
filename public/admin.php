<?php
session_set_cookie_params([
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);
session_start();
header('X-Robots-Tag: noindex, nofollow');
// Les pages admin (liste des avis, jeton) ne doivent pas rester dans le cache du navigateur.
header('Cache-Control: no-store');

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Déconnexion automatique après 30 minutes d'inactivité.
$duree_inactivite = 1800;
if (isset($_SESSION['admin'])) {
    if (time() - ($_SESSION['admin_activite'] ?? 0) > $duree_inactivite) {
        unset($_SESSION['admin'], $_SESSION['admin_activite']);
    } else {
        $_SESSION['admin_activite'] = time();
    }
}

// Anti force brute : 5 essais ratés maximum par adresse IP sur 15 minutes
// (compté par IP : une limite en session serait contournée en supprimant le cookie).
$max_essais    = 5;
$duree_blocage = 900;
require '../config/limite.php';

// Traitement de la connexion
if (isset($_POST['password']) && is_string($_POST['password'])) {
    if (limite_atteinte('admin', $max_essais, $duree_blocage)) {
        $erreur_blocage = true;
    } elseif (!isset($_POST['token']) || !is_string($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        $erreur_login = true;
    } else {
        require '../config/config.php';
        if (password_verify($_POST['password'], ADMIN_PASS)) {
            limite_effacer('admin');
            session_regenerate_id(true); // évite la fixation de session
            $_SESSION['admin'] = true;
            $_SESSION['admin_activite'] = time();
            $_SESSION['token'] = bin2hex(random_bytes(32));
            header("Location: admin.php");
            exit();
        }
        limite_enregistrer('admin', $duree_blocage);
        sleep(1);
        $erreur_login = true;
    }
}

// Déconnexion (en POST avec jeton, pour qu'un lien piégé ne puisse pas déconnecter l'admin)
if (isset($_POST['logout']) && isset($_POST['token']) && is_string($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: admin.php");
    exit();
}

// Protection de la page
if (!isset($_SESSION['admin'])) {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="color-scheme" content="light dark">
    <title>Admin - ZagiArtRenov</title>
    <link rel="stylesheet" href="css/styles.css?v=20261009b">
    <link rel="stylesheet" href="css/admin.css?v=20261009b">
    <?php include "../templates/favicons.php"; ?>
</head>
<body class="admin">
    <main class="admin_login">
        <div class="admin_login_card">
            <h1>Administration</h1>
            <p>Accès réservé</p>
            <?php if (isset($erreur_blocage)): ?>
                <p class="message_erreur" role="alert">Trop de tentatives. Réessayez dans 15 minutes.</p>
            <?php elseif (isset($erreur_login)): ?>
                <p class="message_erreur" role="alert">Mot de passe incorrect.</p>
            <?php endif; ?>
            <form action="admin.php" method="post">
                <div class="form_groupe">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required>
                </div>
                <div class="form_groupe">
                    <button type="submit">Se connecter</button>
                </div>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            </form>
        </div>
    </main>
</body>
</html>
<?php
    exit();
}

// Si connecté — charger la connexion DB et le filtre des termes interdits
require '../config/connexion.php';
require '../config/mots_interdits.php';

// Récupérer les avis en attente
$stmt_attente = $conn->query("SELECT id, nom, ville, note, commentaire, date FROM avis WHERE valide = 0 ORDER BY date DESC");
$avis_attente = $stmt_attente->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les avis publiés
$stmt_publies = $conn->query("SELECT id, nom, ville, note, commentaire, date FROM avis WHERE valide = 1 ORDER BY date DESC");
$avis_publies = $stmt_publies->fetchAll(PDO::FETCH_ASSOC);

// Bouton d'action en POST avec jeton CSRF (une action par formulaire)
function bouton_action(string $action, int $id, string $libelle, string $classe, string $token): void
{
    $confirmation = $action === 'supprimer' ? ' data-confirmer="Supprimer définitivement cet avis ?"' : '';
    ?>
    <form action="admin_traitement.php" method="post" class="admin_form_action"<?php echo $confirmation; ?>>
        <input type="hidden" name="action" value="<?php echo $action; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <button type="submit" class="<?php echo $classe; ?>"><?php echo $libelle; ?></button>
    </form>
    <?php
}

// Une carte par avis : plus lisible qu'un tableau quand le commentaire est long.
function carte_avis(array $avis, bool $publie, string $token): void
{
    $id        = (int)$avis['id'];
    $note      = max(0, min(5, (int)$avis['note']));
    $signales  = mots_interdits_trouves($avis['nom'] . ' | ' . $avis['ville'] . ' | ' . $avis['commentaire']);
    ?>
    <article class="admin_avis<?php echo $signales ? ' admin_avis_signale' : ''; ?>">
        <div class="admin_avis_entete">
            <div>
                <p class="admin_avis_nom">
                    <?php echo htmlspecialchars($avis['nom']); ?>
                    <?php if ($avis['ville'] !== ''): ?>
                        <span class="admin_avis_ville"><?php echo htmlspecialchars($avis['ville']); ?></span>
                    <?php endif; ?>
                </p>
                <p class="admin_avis_meta">
                    <span class="admin_etoiles" role="img" aria-label="Note : <?php echo $note; ?> sur 5"><?php echo str_repeat('★', $note) . str_repeat('☆', 5 - $note); ?></span>
                    <time datetime="<?php echo date('Y-m-d H:i', strtotime($avis['date'])); ?>">
                        <?php echo date('d/m/Y à H:i', strtotime($avis['date'])); ?>
                    </time>
                </p>
            </div>
        </div>

        <?php if ($signales): ?>
            <p class="admin_alerte" role="note">
                Termes interdits détectés : <strong><?php echo htmlspecialchars(implode(', ', $signales)); ?></strong>.
                Cet avis n'est pas affiché sur le site, même publié.
            </p>
        <?php endif; ?>

        <p class="admin_avis_texte"><?php echo htmlspecialchars($avis['commentaire']); ?></p>

        <div class="admin_actions">
            <?php if ($publie): ?>
                <?php bouton_action('depublier', $id, 'Retirer du site', 'btn_depublier', $token); ?>
            <?php else: ?>
                <?php bouton_action('valider', $id, 'Publier', 'btn_valider', $token); ?>
            <?php endif; ?>
            <?php bouton_action('supprimer', $id, 'Supprimer', 'btn_supprimer', $token); ?>
        </div>
    </article>
    <?php
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="color-scheme" content="light dark">
    <title>Admin - ZagiArtRenov</title>
    <link rel="stylesheet" href="css/styles.css?v=20261009b">
    <link rel="stylesheet" href="css/admin.css?v=20261009b">
    <?php include "../templates/favicons.php"; ?>
</head>
<body class="admin">

    <header class="admin_header">
        <div class="admin_header_inner">
            <p class="admin_titre">ZagiArtRenov <span>Avis clients</span></p>
            <nav class="admin_nav" aria-label="Sections">
                <a href="#en_attente">À modérer <span class="admin_badge"><?php echo count($avis_attente); ?></span></a>
                <a href="#publies">Publiés <span class="admin_badge admin_badge_neutre"><?php echo count($avis_publies); ?></span></a>
            </nav>
            <form action="admin.php" method="post">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                <button type="submit" name="logout" value="1" class="admin_logout">Se déconnecter</button>
            </form>
        </div>
    </header>

    <main class="admin_contenu">
        <h1 class="sr_only">Administration des avis clients</h1>

        <section class="admin_section" id="en_attente" aria-labelledby="titre_attente">
            <h2 id="titre_attente">À modérer</h2>
            <p class="admin_aide">Publiez un avis même négatif ; supprimez seulement les contenus injurieux, hors sujet ou contenant des données personnelles.</p>

            <?php if (empty($avis_attente)): ?>
                <p class="admin_vide">Aucun avis en attente. Les nouveaux avis apparaîtront ici.</p>
            <?php else: ?>
                <div class="admin_liste">
                    <?php foreach ($avis_attente as $avis) { carte_avis($avis, false, $_SESSION['token']); } ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="admin_section" id="publies" aria-labelledby="titre_publies">
            <h2 id="titre_publies">Publiés sur le site</h2>

            <?php if (empty($avis_publies)): ?>
                <p class="admin_vide">Aucun avis publié pour l'instant.</p>
            <?php else: ?>
                <div class="admin_liste">
                    <?php foreach ($avis_publies as $avis) { carte_avis($avis, true, $_SESSION['token']); } ?>
                </div>
            <?php endif; ?>
        </section>

    </main>

    <script src="js/admin.js"></script>
</body>
</html>
