<?php
require_once 'inc/init.inc.php';
//------------------------------------- COIFFURE PHP -------------------------------------//
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
?>