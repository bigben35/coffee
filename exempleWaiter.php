<?php

//charger les packages
  require('vendor/autoload.php'); //charger le composer, les packages que composer a installé dans vendor

  //on vérifie que l'URL ne soit PAS celle d'heroku 
  if($_SERVER['HTTP_HOST'] != "coffee-k66.herokuapp.com") {

    //chargement de php .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__); //avec composer on a pu téléchargé dotenv
    $dotenv->load();
  }

  //chemin de connexion à la bdd
  //path =url 
  $path = "mysql:host=".$_ENV['DB_HOST'].":".$_ENV['DB_PORT'].";dbname=".$_ENV['DB_NAME'].";charset=utf8";

  //objet php qui permet la connexion a la bdd, php data objet PDO
  $pdo = new PDO(
    $path, 
    $_ENV['DB_USERNAME'], //variables d'environnement pour protection des données
    $_ENV['DB_PASSWORD']
  );


  //créer des objet waiter
// qui offre une méthode info

  
if(count($waiters) > 0) {
$title = "Liste des serveurs";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title; ?></title>
</head>
<body>

<h1>Les serveurs !</h1>

<?php
  foreach ($waiters as $waiter) {
    echo "<p>" . $waiter->info() . "</p>";
  }
?>
  
</body>
</html>


<?php
} else {
$title = "Serveurs introuvables";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title; ?></title>
</head>
<body>

<h1>Oups il n y a aucun serveur, voulez-vous en ajouter un ?</h1>

<button id="add">Ajouter un serveur</button>
  
<script type="text/javascript" src="newWaiter.js"></script>
  
</body>
</html>

<?php
}