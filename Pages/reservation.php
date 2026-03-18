<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        
    <?php 
    require_once('../Config/config.php');
     ?>
     <div>
            <form action="" method="post" enctype="multipart/form-data" id="reservationForm">
            
            

                <div>
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" required>
                </div>

                <div>
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>

                <div>
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div>
                    <label for="telephone">Téléphone :</label>
                    <input type="tel" id="telephone" name="telephone" required>
                </div>

                <div>
                    <button type="submit">Confirmer</button>
                </div>
            </form>
    </div>
    <?php
    require_once('../Debug/debug.php');

    ?>
   
