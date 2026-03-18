<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>

       
<body>
    <div>
        <form action="" method="post" enctype="multipart/form-data" id="reservationForm">
            <label for="service">service:</label>
            <select id="service" name="service" required>

                <!-- liste des services proposés avec durée et prix -->

                <?php
                $services = $pdo -> query("SELECT nom FROM services");
                while($service = $services->fetch()) { 
                    echo'<option value ="'.$service['nom'].'">'.$service['nom'].'</option>';
                 };
                ?>
            </select>
            <label for="date">Date et heure :</label>
            <!-- <?echo ?> calendrier interractif -->

             <label for = "heure">Heure :</label>//
             <!-- Horraire disponible --><?php echo '';?>

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="telephone">Téléphone :</label>
            <input type="tel" id="telephone" name="telephone" required>

            <button type="submit">Réserver</button>
        </form>

        <!-- validation côté serveur et enregistrement de la réservation dans la base de données -->
        <?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $service = $_POST['service'];
            $date = $_POST['date'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];

            // Validation des données 

            if (!empty($service) && !empty($date) && !empty($nom) && !empty($telephone) && !empty($email) && !empty($heure)) {
                
                if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
                    echo'<div>⚠ Attention, le format de l\'adresse email n\'est pas correct. Veuillez renseigner une adresse email valide.</div>';

                }else if(preg_match("/^[0-9]{10}$/", $telephone) == false){
                    echo'<div>⚠ Attention, le format du numéro de téléphone n\'est pas correct. Veuillez renseigner un numéro de téléphone valide (10 chiffres).</div>';
                } else {
        
                // Enregistrement de la réservation dans la base de données
                $id_service = $pdo->query("SELECT id FROM services WHERE nom = '$service'")->fetchColumn();

                $stmt = $pdo->prepare("INSERT INTO reservation (service_id, date_rdv, nom_client, email_client, telephone) VALUES (?, ?, ?, ?, ?)");

                $stmt->execute([$id_service, $date, $nom, $email, $telephone]);

                echo'<div>Votre réservation pour le service : '.$service.' le '.$date.' à '.$heure.' a été enregistrée.</div>';
                };
        
            } else {
                echo "<div>Veuillez remplir tous les champs!</div>";
            };

        }
        ?>
    </div>

</body>
  <script src="../assets/js/script.js"></script>
</html>