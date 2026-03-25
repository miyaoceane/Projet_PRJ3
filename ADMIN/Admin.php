<?php
session_start();
require_once("../Config/config.php");

if (!isset($_SESSION['admin'])) {
    header("Location: connexion.php");
    exit();
}

/* ========== ACTIONS RESERVATIONS ========== */
if (isset($_GET['confirm'])) {
    $pdo->prepare("UPDATE reservation SET statut='confirmé' WHERE id=?")->execute([intval($_GET['confirm'])]);
    header("Location: Admin.php"); exit();
}
if (isset($_GET['cancel'])) {
    $pdo->prepare("UPDATE reservation SET statut='annulé' WHERE id=?")->execute([intval($_GET['cancel'])]);
    header("Location: Admin.php"); exit();
}
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM reservation WHERE id=?")->execute([intval($_GET['delete'])]);
    header("Location: Admin.php"); exit();
}

/* ========== ACTIONS SERVICES ========== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_service'])) {
    $pdo->prepare("UPDATE service SET nom=?, description=?, duree_minute=?, prix_euros=? WHERE id=?")
        ->execute([trim($_POST['nom']), trim($_POST['description']), intval($_POST['duree_minute']), floatval($_POST['prix_euros']), intval($_POST['id'])]);
    header("Location: Admin.php#services"); exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $pdo->prepare("INSERT INTO service (nom, description, duree_minute, prix_euros) VALUES (?,?,?,?)")
        ->execute([trim($_POST['nom']), trim($_POST['description']), intval($_POST['duree_minute']), floatval($_POST['prix_euros'])]);
    header("Location: Admin.php#services"); exit();
}
if (isset($_GET['delete_service'])) {
    $pdo->prepare("DELETE FROM service WHERE id=?")->execute([intval($_GET['delete_service'])]);
    header("Location: Admin.php#services"); exit();
}

/* ========== ACTIONS DISPONIBILITES ========== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_dispo'])) {
    $pdo->prepare("UPDATE disponibilite SET jour_semaine=?, heure_debut=?, heure_fin=?, actif=? WHERE id=?")
        ->execute([intval($_POST['jour_semaine']), $_POST['heure_debut'], $_POST['heure_fin'], isset($_POST['actif']) ? 1 : 0, intval($_POST['id'])]);
    header("Location: Admin.php#dispos"); exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_dispo'])) {
    $pdo->prepare("INSERT INTO disponibilite (jour_semaine, heure_debut, heure_fin, actif) VALUES (?,?,?,?)")
        ->execute([intval($_POST['jour_semaine']), $_POST['heure_debut'], $_POST['heure_fin'], isset($_POST['actif']) ? 1 : 0]);
    header("Location: Admin.php#dispos"); exit();
}
if (isset($_GET['delete_dispo'])) {
    $pdo->prepare("DELETE FROM disponibilite WHERE id=?")->execute([intval($_GET['delete_dispo'])]);
    header("Location: Admin.php#dispos"); exit();
}

/* ========== DONNÉES ========== */
$reservations = $pdo->query("SELECT reservation.id, service.nom, date_rdv, heure_rdv, nom_client, prenom_client, email_client, telephone, statut FROM service INNER JOIN reservation ON reservation.service_id = service.id")->fetchAll();

$services_list  = $pdo->query("SELECT * FROM service ORDER BY nom")->fetchAll();
$disponibilites = $pdo->query("SELECT * FROM disponibilites ORDER BY jour_semaine, heure_debut")->fetchAll();

$jours = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];

$edit_service_id = isset($_GET['edit_service']) ? intval($_GET['edit_service']) : null;
$edit_dispo_id   = isset($_GET['edit_dispo'])   ? intval($_GET['edit_dispo'])   : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — COIFFURE PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../asset/css/style.css" rel="stylesheet">
    
</head>
<body class="body">

<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">COIFFURE PRO</a>
            <a href="./connexion.php" class="btn btn-outline-success">Se déconnecter</a>
        </div>
    </nav>
</header>

<div class="container-fluid py-4">

    <!-- ============ RÉSERVATIONS ============ -->
    <h2>Réservations</h2>
        <table>
            <thead>
                <tr>
                    <th>Client</th><th>Email</th><th>Téléphone</th>
                    <th>Service</th><th>Date</th><th>Heure</th>
                    <th>Statut</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($reservations as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['nom_client'].' '.$r['prenom_client']) ?></td>
                <td><?= htmlspecialchars($r['email_client']) ?></td>
                <td><?= htmlspecialchars($r['telephone']) ?></td>
                <td><?= htmlspecialchars($r['nom']) ?></td>
                <td><?= $r['date_rdv'] ?></td>
                <td><?= substr($r['heure_rdv'],0,5) ?></td>
                <td>
                    <?php $cls = match($r['statut']) { 'confirmé'=>'badge-ok','annulé'=>'badge-no',default=>'badge-wait' }; ?>
                    <span class="badge <?= $cls ?>"><?= ucfirst($r['statut']) ?></span>
                </td>
                <td>
                    <?php if($r['statut'] !== 'confirmé'): ?>
                    <a href="?confirm=<?= $r['id'] ?>" class="btn btn-outline-secondary">✓ Confirmer</a>
                    <?php endif; ?>
                    <?php if($r['statut'] !== 'annulé'): ?>
                    <a href="?cancel=<?= $r['id'] ?>" class="btn btn-outline-secondary">✗ Annuler</a>
                    <?php endif; ?>
                    <a href="?delete=<?= $r['id'] ?>" class="btn btn-outline-secondary"
                       onclick="return confirm('Supprimer cette réservation ?')">🗑 Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(!$reservations): ?>
            <tr><td colspan="8" class="text-center text-muted py-3">Aucune réservation</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ============ SERVICES ============ -->
    <h2 class="mb-3" id="services">✂ Services</h2>
    <div class="table-responsive mb-5">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr><th>Nom</th><th>Description</th><th>Durée (min)</th><th>Prix (€)</th><th>Actions</th></tr>
            </thead>
            <tbody>

            <?php foreach($services_list as $s): ?>
            <?php if($edit_service_id === (int)$s['id']): ?>
            <!-- Ligne édition -->
            <tr class="editing">
                <form method="POST" action="Admin.php#services">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <td><input type="text" name="nom" value="<?= htmlspecialchars($s['nom']) ?>" required></td>
                <td><input type="text" name="description" value="<?= htmlspecialchars($s['description']) ?>"></td>
                <td><input type="number" name="duree_minute" value="<?= $s['duree_minute'] ?>" min="5" step="5" style="width:80px" required></td>
                <td><input type="number" name="prix_euros" value="<?= $s['prix_euros'] ?>" min="0" step="0.5" style="width:80px" required></td>
                <td>
                    <button type="submit" name="edit_service" class="btn btn-sm btn-success">💾 Sauvegarder</button>
                    <a href="Admin.php#services" class="btn btn-sm btn-secondary">Annuler</a>
                </td>
                </form>
            </tr>
            <?php else: ?>
            <!-- Ligne normale -->
            <tr>
                <td><?= htmlspecialchars($s['nom']) ?></td>
                <td><?= htmlspecialchars($s['description']) ?></td>
                <td><?= $s['duree_minute'] ?> min</td>
                <td><?= $s['prix_euros'] ?> €</td>
                <td class="btn-action">
                    <a href="?edit_service=<?= $s['id'] ?>#services" class="btn btn-outline-secondary">✏ Modifier</a>
                    <a href="?delete_service=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Supprimer ce service ?')">🗑 Supprimer</a>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>

            <!-- Ligne ajout -->
            <tr class="add-row">
                <form method="POST" action="Admin.php#services">
                <td><input type="text" name="nom" placeholder="Nom" required></td>
                <td><input type="text" name="description" placeholder="Description"></td>
                <td><input type="number" name="duree_minute" placeholder="30" min="5" step="5" style="width:80px" required></td>
                <td><input type="number" name="prix_euros" placeholder="25" min="0" step="0.5" style="width:80px" required></td>
                <td><button type="submit" name="add_service" class="btn btn-sm btn-success">+ Ajouter</button></td>
                </form>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- ============ DISPONIBILITÉS ============ -->
    <h2 class="mb-3" id="dispos">🕐 Disponibilités</h2>
    <div class="table-responsive mb-5">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr><th>Jour</th><th>Heure début</th><th>Heure fin</th><th>Actif</th><th>Actions</th></tr>
            </thead>
            <tbody>

            <?php foreach($disponibilites as $d): ?>
            <?php if($edit_dispo_id === (int)$d['id']): ?>
            <!-- Ligne édition -->
            <tr class="editing">
                <form method="POST" action="Admin.php#dispos">
                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                <td>
                    <select name="jour_semaine">
                        <?php foreach($jours as $i => $j): ?>
                        <option value="<?= $i ?>" <?= $d['jour_semaine']==$i?'selected':'' ?>><?= $j ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="time" name="heure_debut" value="<?= $d['heure_debut'] ?>" style="width:120px"></td>
                <td><input type="time" name="heure_fin"   value="<?= $d['heure_fin'] ?>"   style="width:120px"></td>
                <td><input type="checkbox" name="actif" <?= $d['actif']?'checked':'' ?>></td>
                <td>
                    <button type="submit" name="edit_dispo" class="btn btn-sm btn-success">💾 Sauvegarder</button>
                    <a href="Admin.php#dispos" class="btn btn-sm btn-secondary">Annuler</a>
                </td>
                </form>
            </tr>
            <?php else: ?>
            <!-- Ligne normale -->
            <tr>
                <td><?= $jours[$d['jour_semaine']] ?></td>
                <td><?= $d['heure_debut'] ?></td>
                <td><?= $d['heure_fin'] ?></td>
                <td><span class="badge <?= $d['actif']?'badge-ok':'badge-no' ?>"><?= $d['actif']?'Ouvert':'Fermé' ?></span></td>
                <td class="btn-action">
                    <a href="?edit_dispo=<?= $d['id'] ?>#dispos" class="btn btn-outline-secondary">✏ Modifier</a>
                    <a href="?delete_dispo=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Supprimer ce créneau ?')">🗑 supprimer</a>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>

            <!-- Ligne ajout -->
            <tr class="add-row">
                <form method="POST" action="Admin.php#dispos">
                <td>
                    <select name="jour_semaine">
                        <?php foreach($jours as $i => $j): ?>
                        <option value="<?= $i ?>"><?= $j ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="time" name="heure_debut" value="09:00" style="width:120px"></td>
                <td><input type="time" name="heure_fin"   value="18:00" style="width:120px"></td>
                <td><input type="checkbox" name="actif" checked></td>
                <td><button type="submit" name="add_dispo" class="btn btn-sm btn-success">+ Ajouter</button></td>
                </form>
            </tr>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
