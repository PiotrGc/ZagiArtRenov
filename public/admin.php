<?php
session_set_cookie_params([
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']),
    'samesite' => 'Lax'
]);
session_start();

// Traitement de la connexion
if (isset($_POST['password'])) {
    require '../config/config.php';
    if (password_verify($_POST['password'], ADMIN_PASS)) {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $erreur_login = true;
    }
}

// Déconnexion
if (isset($_GET['logout'])) {
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
    <title>Admin - ZAR</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <section class="admin_login">
        <div class="admin_login_card">
            <h1>Administration</h1>
            <p>Accès réservé</p>
            <?php if (isset($erreur_login)): ?>
                <p class="message_erreur">Mot de passe incorrect.</p>
            <?php endif; ?>
            <form action="admin.php" method="post">
                <div class="form_groupe">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form_groupe">
                    <button type="submit">Se connecter</button>
                </div>
            </form>
        </div>
    </section>
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - ZAR</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

    <div class="admin_header">
        <h1>Administration — Avis clients</h1>
        <a href="admin.php?logout=1" class="admin_logout">Se déconnecter</a>
    </div>

    <div class="admin_contenu">

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
                                <th>Nom</th>
                                <th>Ville</th>
                                <th>Note</th>
                                <th>Commentaire</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($avis_attente as $avis): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($avis['nom']); ?></td>
                                <td><?php echo htmlspecialchars($avis['ville']); ?></td>
                                <td><?php echo str_repeat('★', $avis['note']); ?></td>
                                <td><?php echo htmlspecialchars($avis['commentaire']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($avis['date'])); ?></td>
                                <td class="admin_actions">
                                    <a href="admin_traitement.php?action=valider&id=<?php echo $avis['id']; ?>" class="btn_valider">Valider</a>
                                    <a href="admin_traitement.php?action=supprimer&id=<?php echo $avis['id']; ?>" class="btn_supprimer" onclick="return confirm('Supprimer cet avis ?')">Supprimer</a>
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
                                <th>Nom</th>
                                <th>Ville</th>
                                <th>Note</th>
                                <th>Commentaire</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($avis_publies as $avis): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($avis['nom']); ?></td>
                                <td><?php echo htmlspecialchars($avis['ville']); ?></td>
                                <td><?php echo str_repeat('★', $avis['note']); ?></td>
                                <td><?php echo htmlspecialchars($avis['commentaire']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($avis['date'])); ?></td>
                                <td class="admin_actions">
                                    <a href="admin_traitement.php?action=depublier&id=<?php echo $avis['id']; ?>" class="btn_depublier">Dépublier</a>
                                    <a href="admin_traitement.php?action=supprimer&id=<?php echo $avis['id']; ?>" class="btn_supprimer" onclick="return confirm('Supprimer cet avis ?')">Supprimer</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

    </div>

</body>
</html>