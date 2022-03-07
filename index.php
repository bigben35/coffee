<?php

use Carbon\Carbon;

require('vendor/autoload.php');


if($_SERVER['HTTP_HOST'] != "coffee-k66.herokuapp.com"){

  $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
}

echo "Hello World !" . '<br>';

function dbaccess() {
  $dbConnection = "mysql:dbname=". $_ENV['DB_NAME'] ."; host=". $_ENV['DB_HOST'] .":". $_ENV['DB_PORT'] ."; charset=utf8";
  $user = $_ENV['DB_USERNAME'];
  $pwd = $_ENV['DB_PASSWORD'];
  
  $db = new PDO ($dbConnection, $user, $pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  return $db;
}
  
$db = dbaccess();

$req = $db->query('SELECT name FROM waiter')->fetchAll();
// $reqCoffee = $db->query('SELECT * FROM edible WHERE price LIKE 1.3')->fetchAll();
// $reqCoffee = $db->query('SELECT * FROM edible WHERE FORMAT(price, 1) = 1.3')->fetchAll(); //autre solution

foreach ($req as $dbreq) {
  echo $dbreq['name'] . "<br>";
}


echo '<h2>Cafés</h2>';

foreach ($reqCoffee as $dbreqCoffee) {
  echo $dbreqCoffee['name'] . ":" . $dbreqCoffee['price'] . " €" . "<br>";
}

echo '<h2>Facture 1 :</h2>';

$reqOrder = $db->query("SELECT * FROM `orderedible` WHERE idOrder = 1")->fetchAll();
$price = 0;

forEach($reqOrder as $order){
  $price = $price + $order['price']*$order['quantity'];
}

echo "Prix de la facture 1 : " . $price . " €<br>";

echo '<h2>CA serveur 2 :</h2>';

// "SELECT * FROM `order` AS o INNER JOIN `orderedible` AS oe ON o.id = oe.idorder WHERE o.idWaiter= 2";

$caWaiter2 = "SELECT w.name AS name, FORMAT(SUM( price*quantity), 2) AS CA 
FROM `order` AS o 
INNER JOIN `orderedible` AS oe 
INNER JOIN `waiter` AS w 
ON o.id= oe.idOrder 
WHERE o.idWaiter=2 
AND w.id=o.idWaiter";

$total = $db->query($caWaiter2)->fetch(PDO::FETCH_OBJ);
print $total->name . " a un CA de " . $total-> CA . '€';
echo ('<br>');

echo '<h2>Serveurs ayant servi la table 3</h2>';

$waiterTable3 = "SELECT name 
FROM `waiter` 
WHERE id 
IN (SELECT `idWaiter` FROM `order` WHERE `idRestaurantTable` =3)";

$waiters = $db->query($waiterTable3)->fetchAll();
foreach($waiters as $waiter){
  echo $waiter ['name'] . '<br>';
}


echo '<h2>Café(s) le plus consommé :</h2>';

// on recupere la plus grande quantité
"SELECT SUM(oe.quantity) AS total
FROM `orderedible` AS oe
INNER JOIN `edible` AS e
ON e.id = oe.idEdible
GROUP BY oe.idEdible
ORDER BY total DESC LIMIT 1";

$cafeConsomme = "SELECT e.name, SUM(oe.quantity) AS total
FROM `orderedible` AS oe
INNER JOIN `edible` AS e
ON e.id = oe.idEdible
GROUP BY oe.idEdible
HAVING total = (
    SELECT SUM(oe.quantity) AS total
    FROM `orderedible` AS oe
    INNER JOIN `edible` AS e
	  ON e.id = oe.idEdible
	  GROUP BY oe.idEdible
    ORDER BY total DESC LIMIT 1)";

$totalcafe = $db->query($cafeConsomme)->fetch(PDO::FETCH_OBJ);
print 'le café le plus consommé est : ' . $totalcafe->name . " avec " . $totalcafe->total . " cafés consommés !";
echo ('<br>');

echo '<h2>Infos Serveur 2 :</h2>';

$waiter2 = "SELECT waiter.name 
AS waiter,`order`.createdAt 
AS date, FORMAT(SUM(price*quantity), 2) 
AS prix_Commande 
FROM `waiter`, `order`, `orderedible`
WHERE orderedible.idOrder = `order`.id 
AND waiter.id = 2 
AND idWaiter = waiter.id 
GROUP BY idOrder";

$waiters = $db->query($waiter2)->fetchAll();
foreach($waiters as $waiter){
  echo $waiter ['waiter'] . ' | ' . Carbon::parse($waiter['date'])->locale('fr')->diffForHumans() . ' | ' . $waiter['prix_Commande'] . ' €' . '<br>';
}
echo ('<br>');