<?php
session_start();
require_once ("../Config/config.php");

if (!isset($_SESSION['admin'])) {
    header("Location: connexion.php");
    exit();
}

/* ---- ACTIONS ---- */
if (isset($_GET['confirm'])) {
    $id = intval($_GET['confirm']);
    $pdo->prepare("UPDATE reservation SET statut='confirmé' WHERE id=?")->execute([$id]);
}

if (isset($_GET['cancel'])) {
    $id = intval($_GET['cancel']);
    $pdo->prepare("UPDATE reservation SET statut='annulé' WHERE id=?")->execute([$id]);
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM reservation WHERE id=?")->execute([$id]);
}

$req = $pdo->query("SELECT reservation.id, nom, date_rdv, heure_rdv, nom_client, prenom_client, email_client, telephone, statut FROM service INNER JOIN reservation WHERE reservation.service_id = service.id;");
$reservations = $req->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="../asset/css/style.css" rel="stylesheet">
</head>
<body class="body">
<header>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">COIFFURE PRO</a>
    <div class="d-flex align-items-center gap-3">
      <a href="./connexion.php" class="btn btn-outline-success">Se déconnecter</a>
    </div>

  </div>
</nav>
</header>
<h1>Tableau des réservations</h1>
<br><br>

<table>
<tr>
    <th>Client</th>
    <th>Email</th>
    <th>Téléphone</th>
    <th>Service</th>
    <th>Date</th>
    <th>Heure</th>
    <th>Statut</th>
    <th>Actions</th>
</tr>

<?php foreach($reservations as $r): ?>
<tr>

<td><?= htmlspecialchars($r['nom_client']) ?></td>
<td><?= htmlspecialchars($r['email_client']) ?></td>
<td><?= htmlspecialchars($r['telephone']) ?></td>
<td><?= htmlspecialchars($r['nom']) ?></td>
<td><?= $r['date_rdv'] ?></td>
<td><?= $r['heure_rdv'] ?></td>
<td><?= $r['statut'] ?></td>

<td>
    <a type="button" class="btn btn-outline-secondary" href="?confirm=<?= $r['id'] ?>">Confirmer</a>
    <a type="button" class="btn btn-outline-secondary" href="?cancel=<?= $r['id'] ?>">Annuler</a>
    <a type="button" class="btn btn-outline-secondary" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
</td>

</tr>
<?php endforeach; ?>

</table>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPV1z7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>