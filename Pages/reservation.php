<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../asset/css/style.css" rel="stylesheet">
    <title>Réservation</title>
</head>
<body>
    <div class="container text-center mt-5">
    <h1>Réservation en ligne</h1>
    <p class="sous-titre">Choisissez votre service et votre créneau</p>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <form action="reservation.php" method="post" id="reservationForm">

                <!-- Champs cachés remplis par le JS quand l'utilisateur clique sur un créneau -->
                <input type="hidden" id="date_rdv"  name="date_rdv"  value="">
                <input type="hidden" id="heure_rdv" name="heure_rdv" value="">

                <!-- Sélection du service -->
                <div class="mb-3">
                    <label for="service">Service :</label>
                    <select id="service" name="service_id">
                        <option value="">-- Choisir un service --</option>
                        <?php foreach ($services_list as $s): ?>
                            <option value="<?= $s['id'] ?>"
                                    data-dur="<?= $s['duree_minute'] ?>"
                                    data-prix="<?= $s['prix_euros'] ?>">
                                <?= htmlspecialchars(trim($s['nom'])) ?> — <?= $s['prix_euros'] ?>€ — <?= $s['duree_minute'] ?> min
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error-message" id="err-service"></span>
                </div>

                <!-- Carte info service remplie par le JS -->
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
                               value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                        <span class="error-message" id="err-nom"></span>
                    </div>

                    <div class="mb-3">
                        <label for="prenom">Prénom :</label>
                        <input type="text" id="prenom" name="prenom"
                               value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
                        <span class="error-message" id="err-prenom"></span>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        <span class="error-message" id="err-email"></span>
                    </div>

                    <div class="mb-3">
                        <label for="telephone">Téléphone :</label>
                        <input type="tel" id="telephone" name="telephone"
                               value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
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
