<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href=asset/css/style.css rel="stylesheet">
</head>
    <?php 
    require_once ('Config/config.php');
    ?>
<body>
    <header class="acceuil">
        <div class='header-content'>
        <img class="card-img-top" src ="https://coiffurealimage.fr/wp-content/uploads/2018/03/img-salon-10.jpg" alt= "photo du sallon">
        <div class ="header-text">
            <h1>COIFFURE PRO</h1>
            <h2 id="description">Venez découvrir notre salon de coiffure, un espace moderne et convivial dédié à votre style.</h2>
        </div>
        </div>  
    </header>

    <main class="acceuil"> 
        <div service class="service">
        <h2 >SERVICES</h2>

        <div class="container-service">
        <?php  
        include_once('Config/config.php'); 
        $stm = $pdo -> query("SELECT nom, description, duree_minute, prix_euros, image FROM service");
        $services = $stm->fetchAll(PDO::FETCH_ASSOC);

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
         
        <div class="avis">
        <h2>AVIS</h2>
        <div class="container-avis">
            <div class="comments">
            <?php 
            $comment = [
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