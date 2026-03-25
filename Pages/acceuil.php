<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href=asset/css/style.css rel="stylesheet">
</head>
    <?php 
    require_once ('Config/config.php');
    $dispo = $pdo->query("SELECT * FROM disponibilites ORDER BY jour_semaine")->fetchAll(PDO::FETCH_ASSOC);

    $jours = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
    ?>
<body>
<header>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    
    <!-- Nom du salon de coiffure -->
    <a class="navbar-brand" href="#">COIFFURE PRO</a>

    <!-- Menu -->
    <div class="d-flex align-items-center gap-3">
      <a class="nav-link" href="#services">services</a>
      <a class="nav-link" href="#avis">avis</a>
      <a class="nav-link" href="#contact">Contact</a>
      <a href="./Pages/reservation.php" class="btn btn-outline-success">Réserver</a>
    </div>

  </div>
</nav>
</header>
    <!-- NOM DU SALLON AVEC UNE IMAGE D'ACCUEIL -->
    <div class="acceuil">
        <div class='header-content'>
        <img class="card-img-top" src ="https://coiffurealimage.fr/wp-content/uploads/2018/03/img-salon-10.jpg" alt= "photo du sallon">
        <div class ="header-text">
            <h1>COIFFURE PRO</h1>
            <h2 id="description">Venez découvrir notre salon de coiffure, un espace moderne et convivial dédié à votre style.</h2>
        </div>
        </div>  
</div>
   
    <main class="acceuil"> 
        
        <div id ="services" class="service">
        <h2 >SERVICES</h2>

        <div class="container-service" style="overflow-x:auto">
        <?php  
        include_once('Config/config.php');
        
        //-- Récupération des services depuis la BDD
        $stm = $pdo -> query("SELECT nom, description, duree_minute, prix_euros, image FROM service");
        $services = $stm->fetchAll(PDO::FETCH_ASSOC);
        
        //-- Boucle pour afficher toutes les informations sous forme de card Bootstrap
        foreach($services as $service) {
            echo '<div class="card" style="width: 18rem;">';
            echo '<img src="' . $service['image'] . '" class="card-img-top" alt="...">';
            echo '<div class="card-body">';
            echo '<h3>' . $service['nom'] . '</h3>';
            echo '<p>' . $service['description'] . '</p>';
            echo '<p>Durée : ' . $service['duree_minute'] . ' minutes</p>';
            echo '<p>Prix : ' . $service['prix_euros'] . ' euros</p>';
            echo '</div>';
            echo '</div>';
        }
        ?>
        </div>
        </div>
        <br>
        <br>
        <div class="container">
        <h2 id = "dispo">DISPONIBILITÉS</h2>
        <table>
            <thead>
                <tr>
                    <th>Jour</th>
                    <th>Ouverture</th>
                    <th>Fermeture</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dispo as $d): ?>
                <tr class="<?= $d['actif'] ? '' : 'table-danger' ?>">
                    <td><?= $jours[$d['jour_semaine']] ?></td>
                    <td><?= $d['actif'] ? substr($d['heure_debut'], 0, 5) : '—' ?></td>
                    <td><?= $d['actif'] ? substr($d['heure_fin'],   0, 5) : '—' ?></td>

                    <!-- Ici après avoir récupérer les disponibilités depuis la BDD ,si actif on affiche les heures sinon on affiche un tiret "-"  -->
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
         
        <div id="avis"class="avis">
        <h2>AVIS</h2>
        <div class="container-avis">
            <div class="comments">
            <?php 
            $comment = [ //TABLEAU DES COMMENTAIRES 
                [
                    'nom' => 'Jean Dupont',
                    'commentaire' => 'Excellent service, je recommande vivement !'
                ],
                [
                    'nom' => 'Marie Curie',
                    'commentaire' => 'Coiffeurs très professionnels et à l\'écoute.👌'
                ],
                [
                    'nom' => 'Alice Martin',
                    'commentaire' => 'Ambiance agréable et résultats impeccables.😊💕'
                ],
                [
                    'nom' => 'David Smith',
                    'commentaire' => '😎Un salon de coiffure de qualité avec un personnel sympathique.'
                ],
                [
                    'nom' => 'Sophie Dubois',
                    'commentaire' =>'⭐⭐⭐⭐⭐ Service exceptionnel, je suis ravie de ma coupe !'
                ]
            ];
            
            //-- AFFICHAGE DES COMMENTAIRES SUR LA PAGE --
            foreach ($comment as $c) {
                echo '<div class="comment">';
                echo '<h4>' . $c['nom'] . '</h4>';
                echo '<p>' . $c['commentaire'] . '</p>';
                echo '</div>';
            }
            ?>
            </div>
            <img src="https://voila-le-travail.fr/wp-content/uploads/2022/09/coiffeur.png" alt="space-comment" class="space-comment">
        <div>
        </div>


    </main>


</body>
</html>