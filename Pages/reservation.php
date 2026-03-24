<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="../asset/css/style.css" rel="stylesheet">
    <title>Réservation</title>
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    
    <a class="navbar-brand" href="#">COIFFURE PRO</a>

    <div class="d-flex align-items-center gap-3">
      <a href="../index.php" class="btn btn-outline-success">Accueil</a>
    </div>

  </div>
</nav>
</header>
<main class= "container">

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../Config/config.php');


$stmt_services = $pdo->query("SELECT id, nom, description, duree_minute, prix_euros FROM service ORDER BY nom");
$services_list = $stmt_services->fetchAll();


$stmt_res = $pdo->query("SELECT date_rdv, heure_rdv FROM reservation WHERE statut != 'annule'");
$reservations_prises = [];
while ($r = $stmt_res->fetch()) {
    $heure = substr($r['heure_rdv'], 0, 5); 
    $reservations_prises[] = $r['date_rdv'] . ' ' . $heure;
}


$stmt_dispos = $pdo->query("SELECT jour_semaine, heure_debut, heure_fin FROM disponibilites WHERE actif = 1");
$horaires = [];
while ($d = $stmt_dispos->fetch()) {
    $j = $d['jour_semaine'];
    if (!isset($horaires[$j])) $horaires[$j] = [];
    $horaires[$j][] = [
        'debut' => substr($d['heure_debut'], 0, 5),
        'fin'   => substr($d['heure_fin'], 0, 5)
    ];
}


$message      = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $service_id = intval($_POST['service_id'] ?? 0);
    $date_rdv   = trim($_POST['date_rdv']   ?? '');
    $heure_rdv  = trim($_POST['heure_rdv']  ?? '');
    $nom        = trim($_POST['nom']        ?? '');
    $prenom     = trim($_POST['prenom']     ?? '');
    $email      = trim($_POST['email']      ?? '');
    $telephone  = trim($_POST['telephone']  ?? '');

    
    if (!$service_id || !$date_rdv || !$heure_rdv || !$nom || !$prenom || !$email || !$telephone) {
        $message      = 'Veuillez remplir tous les champs.';
        $message_type = 'danger';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message      = "L'adresse email n'est pas valide.";
        $message_type = 'danger';

    } elseif (!preg_match('/^[0-9]{10}$/', $telephone)) {
        $message      = 'Le numéro de téléphone doit contenir 10 chiffres.';
        $message_type = 'danger';

    } elseif (strtotime($date_rdv) < strtotime('today')) {
        $message      = 'La date choisie est déjà passée.';
        $message_type = 'danger';

    } else {
        
        $check = $pdo->prepare(
            "SELECT id FROM reservation
             WHERE date_rdv = ? AND heure_rdv = ? AND statut != 'annule'"
        );
        $check->execute([$date_rdv, $heure_rdv]);

        if ($check->fetch()) {
            $message      = 'Ce créneau est déjà réservé. Veuillez en choisir un autre.';
            $message_type = 'danger';
        } else {
            
            $insert = $pdo->prepare(
                "INSERT INTO reservation
                 (service_id, date_rdv, heure_rdv, nom_client, prenom_client, email_client, telephone, statut)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 'attente')"
            );
            $insert->execute([$service_id, $date_rdv, $heure_rdv, $nom, $prenom, $email, $telephone]);

           
            $stmt_nom = $pdo->prepare("SELECT nom FROM service WHERE id = ?");
            $stmt_nom->execute([$service_id]);
            $nom_service = $stmt_nom->fetchColumn();

            $message      = 'Réservation confirmée pour <strong>' . htmlspecialchars(trim($nom_service)) . '</strong>'
                          . ' le <strong>' . date('d/m/Y', strtotime($date_rdv)) . '</strong>'
                          . ' à <strong>' . substr($heure_rdv, 0, 5) . '</strong>.'
                          . '<br>Un email de confirmation vous sera envoyé.';
            $message_type = 'success';
        }
    }
}
?>

<div class="container text-center mt-5">
    <h1>Réservation en ligne</h1>
    <p class="sous-titre">Choisissez votre service et votre créneau</p>
</div>

<?php if ($message): ?>
<div class="container mt-3">
    <div class="alert alert-<?= $message_type ?>">
        <?= $message ?>
    </div>
</div>
<?php endif; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <form action="reservation.php" method="post" id="reservationForm" novalidate>

                <!-- Champs cachés remplis par le JS quand l'utilisateur clique sur un créneau -->
                <input type="hidden" id="date_rdv"  name="date_rdv"  value="">
                <input type="hidden" id="heure_rdv" name="heure_rdv" value="">

                <!-- Sélection du service -->
                <div class="mb-3">
                    <label for="service">Service :</label>
                    <select id="service" name="service_id">
                        <option value="">Choisissez un service</option>
                       <?php 
                        foreach ($services_list as $s) {
                            echo '<option value="' . htmlspecialchars($s['id']) . '"'
                            . ' data-dur="' . htmlspecialchars($s['duree_minute']) . '"'
                            . ' data-prix="' . htmlspecialchars($s['prix_euros']) . '">'
                            . htmlspecialchars(trim($s['nom'])) . ' — '
                            . htmlspecialchars($s['prix_euros']) . ' € — '
                            . htmlspecialchars($s['duree_minute']) . ' min'
                            . '</option>';
                        }
                        ?>
                    </select>
                    <span class="error-message" id="err-service"></span>
                </div>

                <!-- Carte info service  -->
                <div id="info-card" class="info-card">
                    <p>Sélectionnez un service pour voir les disponibilités</p>
                </div>

                <!-- Calendrier -->
                <div id="cal-section" style="display:none">
                    <h2>Calendrier</h2>
                    <div class="cal-header">
                        <button type="button" id="prev-btn">←</button>
                        <span id="cal-title"></span>
                        <button type="button" id="next-btn">→</button>
                    </div>
                    <div class="cal-jours">
                        <span>Lun</span><span>Mar</span><span>Mer</span>
                        <span>Jeu</span><span>Ven</span><span>Sam</span><span>Dim</span>
                    </div>
                    <div id="cal-grid" class="cal-grid"></div>
                    <span class="error-message" id="err-date"></span>
                </div>

                <!-- Créneaux horaires -->
                <div id="slots-section" style="display:none">
                    <p id="slots-label"></p>
                    <div id="slots-grid" class="slots-grid"></div>
                    <span class="error-message" id="err-slot"></span>
                </div>

                <!-- Récapitulatif créneau sélectionné -->
                <div id="confirm-bar" style="display:none">
                    <p id="confirm-info"></p>
                </div>

                <!-- Formulaire client -->
                <div id="form-client" style="display:none">
                    <h2>Vos informations</h2>

                    <div class="mb-3">
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom"
                               value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                        <span class="error-message" id="err-nom"></span>
                    </div>

                    <div class="mb-3">
                        <label for="prenom">Prénom :</label>
                        <input type="text" id="prenom" name="prenom"
                               value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"required>
                        <span class="error-message" id="err-prenom"></span>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        <span class="error-message" id="err-email"></span>
                    </div>

                    <div class="mb-3">
                        <label for="telephone">Téléphone :</label>
                        <input type="tel" id="telephone" name="telephone"
                               value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>" required>
                        <span class="error-message" id="err-tel"></span>
                    </div>

                    <div class="mb-3 text-center mt-4">
                        <button type="submit" class="btn-confirmer">Confirmer la réservation</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
// conversion des donnés php en js en passant par le format JSON 
$horaires_json     = json_encode($horaires);
$reservations_json = json_encode($reservations_prises);
?>
<script>
    var HORAIRES_BDD = <?= $horaires_json ?>;
    var RESERVATIONS_bdd = <?= $reservations_json ?>;
</script>
<script src="../asset/js/script.js"></script>

<?php require_once('../INC/footer.inc.php'); ?>
</main>
</body>
</html>
