<?php
require_once 'inc/init.inc.php';
//------------------------------------- TRAITEMENT PHP -------------------------------------//
$host = 'mysql:host=localhost;dbname=iplay';
$login = 'root';
$password = '';

$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);

try {
    $pdo = new PDO($host, $login, $password, $options);
} catch (Exception $e) {
    die('🔴Le problème est survenu lors de la connexion à la base de données : ' . $e->getMessage());
}
$users= $pdo->query("SELECT pseudo FROM membre")->fetchAll(PDO::FETCH_ASSOC);
   
    if(in_array($_POST['pseudo'], array_column($users, 'pseudo')))
    {
       echo'Vous êtes reconnu';
    } else {
        echo'Vous n\'êtes pas reconnu';
        
    };


//------------------------------------- AFFICHAGE HTML -------------------------------------//
require_once 'inc/haut.inc.php';
?>

<div class="container text-center mt-5">
    <h2>Connexion</h2>
</div>
<?=  $contenu; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="pseudo" class="form-label">Pseudo</label>
                    <input type="text" class="form-control" name="pseudo" id="pseudo" placeholder="🐱‍👤 Veuillez choisir un pseudo" pattern="[a-zA-Z0-9-_.]{1,30}" title="caractère autorisés : a-zA-Z0-9-_." required="required">
                </div>
                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de Passe</label>
                    <input type="password" class="form-control" name="mdp" id="mdp" placeholder="🔑 Veuillez choisir un mot de passe">
                </div>
                </div>
                <div class="mb-3 text-center mt-5">
                    <a href="Pages/reservation.php" class="btn btn-outline-success">se connecter</a>
                </div>
                
            </form>
        </div>
    </div>
</div>

<?php
require_once 'inc/bas.inc.php';