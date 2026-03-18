<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
    <?php 
    require_once ('Config/config.php');
    ?>
<body>
    <header>
        <div>
        <h1>Nom du sallon</h1>
        <h2>description du sallon</h2>
        <img src ="" alt= "photo du sallon">
        </div>  
    </header>
   
    <main>
        <div service class="service">
        <h2>Services</h2>
        
        <?php  
        include_once('Config/config.php'); 
        $stm = $pdo -> query("SELECT nom, description, duree_minute,prix_euros, image FROM services").
        $services = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach($services as $service) {
            echo '<div class="service-item">';
            echo '<img src="' . $service['image'] . '" alt="' . $service['nom'] . '" class="service-image">';
            echo '<h3>' . $service['nom'] . '</h3>';
            echo '<p>' . $service['description'] . '</p>';
            echo '<p>Durée : ' . $service['duree_minute'] . ' minutes</p>';
            echo '<p>Prix : ' . $service['prix_euros'] . ' euros</p>';
            echo '</div>';
        }
        ?>
        </div>
        <div class="avis">
        <h2>Avis</h2>
        <img src="" alt="space-comment" class="space-comment">
        <div>           

    </main>


</body>
</html>