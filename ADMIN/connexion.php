<?php
session_start();
require_once ("../Config/config.php");


define("ID_ADMIN", "coiffeur123");
define("PASSWORD_ADMIN", password_hash("laetitia06", PASSWORD_DEFAULT));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $password = $_POST['mdp'];

    if ($id === ID_ADMIN && password_verify($password, PASSWORD_ADMIN)) {
        $_SESSION['admin'] = $id;
        header("Location: Admin.php");
        exit();
    } else {
        $erreur = "identifiant ou mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="../asset/css/style.css" rel="stylesheet">
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">COIFFURE PRO</a>
  </div>
</nav>
</header>
<main id ="connexion" class= "container">
    <h1>Connexion</h1>
    <h3>Bienvenue!</h3>
</div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <form action="connexion.php" method="post">
                    <div class="mb-3">
                        <label for="id" class="form-label">Identifiant</label>
                        <input type="text" class="form-control" name="id" id="id" placeholder="Veuillez entrer votre indentifiant"  title="caractère autorisés : a-zA-Z0-9-_." required="required">
                        <span class="error-message" id="err-email"></span>
                    </div>
                    <div class="mb-3">
                        <label for="mdp" class="form-label">Mot de Passe</label>
                        <input type="password" class="form-control" name="mdp" id="mdp" placeholder="🔑 Veuillez entrer votre mot de passe">
                        <span class="error-message" id="err-email"></span>
                    </div>
                    </div>
                    <div class="mb-3 text-center mt-5">
                        <button class="btn-confirmer"type ='submit'>se connecter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../asset/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>


