<?php
session_set_cookie_params([
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);
session_start();
header('X-Robots-Tag: noindex, nofollow');

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Anti force brute : 5 essais ratés maximum par adresse IP sur 15 minutes.
// Stocké dans un fichier temporaire (une limite en session serait contournée en supprimant le cookie).
$max_essais     = 5;
$duree_blocage  = 900;
$fichier_essais = sys_get_temp_dir() . '/zar_admin_' . hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '') . '.json';

function lire_essais(string $fichier, int $duree): array
{
    $essais = is_file($fichier) ? json_decode((string)file_get_contents($fichier), true) : null;
    if (!is_array($essais) || time() - ($essais['debut'] ?? 0) > $duree) {
        return ['nombre' => 0, 'debut' => time()];
    }
    return $essais;
}

// Traitement de la connexion
if (isset($_POST['password']) && is_string($_POST['password'])) {
    $essais = lire_essais($fichier_essais, $duree_blocage);

    if ($essais['nombre'] >= $max_essais) {
        $erreur_blocage = true;
    } elseif (!isset($_POST['token']) || !is_string($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        $erreur_login = true;
    } else {
        require '../config/config.php';
        if (password_verify($_POST['password'], ADMIN_PASS)) {
            @unlink($fichier_essais);
            session_regenerate_id(true); // évite la fixation de session
            $_SESSION['admin'] = true;
            $_SESSION['token'] = bin2hex(random_bytes(32));
            header("Location: admin.php");
            exit();
        }
        $essais['nombre']++;
        file_put_contents($fichier_essais, json_encode($essais), LOCK_EX);
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
    <title>Admin - ZagiArtRenov</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
    <?php include "../templates/favicons.php"; ?>
</head>
<body>
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

// Si connecté — charger la connexion DB
require '../config/connexion.php';

// Récupérer les avis en attente
$stmt_attente = $conn->query("SELECT * FROM avis WHERE valide = 0 ORDER BY date DESC");
$avis_attente = $stmt_attente->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les avis publiés
$stmt_publies = $conn->query("SELECT * FROM avis WHERE valide = 1 ORDER BY date DESC");
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin - ZagiArtRenov</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
    <?php include "../templates/favicons.php"; ?>
</head>
<body>

    <div class="admin_header">
        <h1>Administration — Avis clients</h1>
        <form action="admin.php" method="post">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <button type="submit" name="logout" value="1" class="admin_logout">Se déconnecter</button>
        </form>
    </div>

    <main class="admin_contenu">

        <!-- AVIS EN ATTENTE -->
        <section class="admin_section">
            <h2>Avis en attente de validation
                <span class="admin_badge"><?php echo count($avis_attente); ?></span>
            </h2>

            <?php if (empty($avis_attente)): ?>
                <p class="admin_vide">Aucun avis en attente.</p>
            <?php else: ?>
                <div class="admin_table_wrapper">
                    <table class="admin_table">
                        <thead>
                            <tr>
                                <th scope="col">Nom</th>
                                <th scope="col">Ville</th>
                                <th scope="col">Note</th>
                                <th scope="col">Commentaire</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($avis_attente as $avis): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($avis['nom']); ?></td>
                                <td><?php echo htmlspecialchars($avis['ville']); ?></td>
                                <td><?php echo (int)$avis['note']; ?> / 5</td>
                                <td><?php echo htmlspecialchars($avis['commentaire']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($avis['date'])); ?></td>
                                <td class="admin_actions">
                                    <?php bouton_action('valider', (int)$avis['id'], 'Publier', 'btn_valider', $_SESSION['token']); ?>
                                    <?php bouton_action('supprimer', (int)$avis['id'], 'Supprimer', 'btn_supprimer', $_SESSION['token']); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <!-- AVIS PUBLIÉS -->
        <section class="admin_section">
            <h2>Avis publiés
                <span class="admin_badge admin_badge_vert"><?php echo count($avis_publies); ?></span>
            </h2>

            <?php if (empty($avis_publies)): ?>
                <p class="admin_vide">Aucun avis publié.</p>
            <?php else: ?>
                <div class="admin_table_wrapper">
                    <table class="admin_table">
                        <thead>
                            <tr>
                                <th scope="col">Nom</th>
                                <th scope="col">Ville</th>
                                <th scope="col">Note</th>
                                <th scope="col">Commentaire</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($avis_publies as $avis): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($avis['nom']); ?></td>
                                <td><?php echo htmlspecialchars($avis['ville']); ?></td>
                                <td><?php echo (int)$avis['note']; ?> / 5</td>
                                <td><?php echo htmlspecialchars($avis['commentaire']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($avis['date'])); ?></td>
                                <td class="admin_actions">
                                    <?php bouton_action('depublier', (int)$avis['id'], 'Dépublier', 'btn_depublier', $_SESSION['token']); ?>
                                    <?php bouton_action('supprimer', (int)$avis['id'], 'Supprimer', 'btn_supprimer', $_SESSION['token']); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

    </main>

    <script src="js/admin.js"></script>
</body>
</html>
