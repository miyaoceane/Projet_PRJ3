<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        
    <?php
    setlocale(LC_TIME, 'fr_FR.UTF-8');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); 

    require_once('../Config/config.php');
    require_once('../INC/header.inc.php');
    $services = $pdo -> query("SELECT nom, prix_euros ,duree_minute FROM service");
    $today = date('d');
    $jour = $pdo->query("SELECT jour_semaine FROM disponibilites");
     ?>

     <div class="container text-center mt-5">
     <h1>Réservation</h1>
     </div>

     <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <form action="" method="post" enctype="multipart/form-data" id="reservationForm">
                
                 <div class="mb-3">
                    <label for="service">services:</label>
                    <select id="service" name="service">
                        <!-- liste des services proposés avec durée et prix --> 
                         <?php
                        while($service = $services->fetch()) { 
                            echo'<option value ="'.$service['nom'].'">'.$service['nom'].'   '.$service['prix_euros'].'€    '.$service['duree_minute'].' min </option>';
                        };
                        ?>                  
                    </select>
                </div>
                <div id='calendrier'>
                    <?php 
                       
                    ?>
                 <table>
                    <tr>
                        <?php
                        while($jours = $jour->fetch()){
                          echo  '<th>'.strftime('%A', strtotime('+1 day')).'</th>';
                        }
                    ?>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <div class="mb-3">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" >
                </div>

                <div class="mb-3">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom">
                </div>

                <div class="mb-3">
                    <label for="email">Email :</label>
                    <input type="text" id="email" name="email">
                </div>

                <div class="mb-3">
                    <label for="telephone">Téléphone :</label>
                    <input type="tel" id="telephone" name="telephone">
                </div>

                <div class="mb-3 text-center mt-5">
                    <button type="submit" class="btn btn-primary btn-lg">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <?php
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $service = $_POST['service'];
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $email = $_POST['email'];
                $telephone = $_POST['telephone'];

                // Validation des données 

                if (!empty($service) && !empty($nom) && !empty($telephone) && !empty($email)) {
                    
                    if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
                        echo'<div class="alert alert-error" >⚠ Attention, le format de l\'adresse email n\'est pas correct. Veuillez renseigner une adresse email valide.</div>';

                    }else if(preg_match("/^[0-9]{10}$/", $telephone) == false){
                        echo'<div class="alert alert-error">⚠ Attention, le format du numéro de téléphone n\'est pas correct. Veuillez renseigner un numéro de téléphone valide (10 chiffres).</div>';
                    } else {
            
                    // Enregistrement de la réservation dans la base de données
                    $stmt1 = $pdo->prepare("SELECT id FROM service WHERE nom = ?");
                    $stmt1->execute([$service]);
                    $id_service = $stmt1->fetchColumn();

                    $stmt2 = $pdo->prepare("INSERT INTO reservation (service_id, nom_client, email_client, telephone) VALUES (?, ?, ?, ?)");

                    $stmt2->execute([$id_service, $nom, $email, $telephone]);

                    echo'<div class="alert alert-success">Votre réservation pour le service : '.$service.'a été enregistrée.</div>';
                    };
            
                } else {
                    echo '<div class="alert alert-danger">Veuillez remplir tous les champs!</div>';
                };

            }
        
        require_once('../INC/footer.inc.php');
    ?>

    <script src="../asset/js/script.js"></script>
</body>
</html>
